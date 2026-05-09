<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Payment\Models\LichSuNapTienPayos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentController extends Controller
{
    /**
     * Khi người dùng bấm Đặt hàng → lưu session → tạo link thanh toán PayOS
     */
    public function createPaymentLink(Request $request)
    {
        $YOUR_DOMAIN = $request->getSchemeAndHttpHost();

        //  Lấy tổng tiền đơn hàng (từ hidden input soTien trên form)
        $amount = intval($request->input('soTien'));
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ.');
        }

        // Tạo mã đơn hàng (unique, phải là số nguyên)
        $orderCode = intval(substr(strval(microtime(true) * 10000), -9));
        $user = Auth::user();

        // Lưu tạm lịch sử thanh toán (pending)
        $lichsu = LichSuNapTienPayos::create([
            'admin_id'   => $user ? $user->id : null,
            'ma_don'     => strval($orderCode),
            'loai_don'   => 'Don hang san pham',
            'cancel'     => 0,
            'so_tien'    => $amount,
            'status'     => 'pending',
            'trang_thai' => 'Cho thanh toan',
            'description'=> 'Don hang #' . $orderCode,
        ]);

        // Gọi API PayOS để lấy checkout URL
        // Description phải ASCII, tối đa 25 ký tự, không dấu tiếng Việt
        $description = 'DonHang' . $orderCode; // 7 + 9 = 16 chars ✓

        $data = [
            'orderCode'   => $orderCode,
            'amount'      => $amount,
            'description' => $description,
            'returnUrl'   => $YOUR_DOMAIN . '/payos/success?orderCode=' . $orderCode,
            'cancelUrl'   => $YOUR_DOMAIN . '/payos/cancel?orderCode=' . $orderCode,
            'items'       => [
                [
                    'name'     => 'Don hang #' . $orderCode,
                    'quantity' => 1,
                    'price'    => $amount,
                ]
            ],
        ];

        try {
            $response = $this->createLink($data);

            $lichsu->update([
                'link'          => $response['checkoutUrl'] ?? null,
                'paymentLinkId' => $response['paymentLinkId'] ?? null,
            ]);

            if (!empty($response['checkoutUrl'])) {
                return redirect($response['checkoutUrl']);
            }

            return redirect()->back()->with('error', 'Không tạo được liên kết thanh toán.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Lỗi tạo thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * PayOS redirect về sau khi thanh toán thành công.
     * Cập nhật trạng thái rồi tạo đơn hàng từ session.
     */
    public function paymentSuccess(Request $request)
    {
        $orderCode = $request->get('orderCode');
        if (!$orderCode) {
            return redirect('/')->with('error', 'Thiếu mã đơn hàng.');
        }

        $payment = LichSuNapTienPayos::where('ma_don', $orderCode)->first();
        if (!$payment) {
            return redirect('/')->with('error', 'Không tìm thấy bản ghi thanh toán.');
        }

        // Đánh dấu đã thanh toán (chỉ xử lý 1 lần)
        if ($payment->trang_thai !== 'Da thanh toan') {
            $payment->status     = 'paid';
            $payment->trang_thai = 'Da thanh toan';
            $payment->save();
        }

        try {
            // Lấy dữ liệu đơn hàng đã lưu vào session trước khi redirect sang PayOS
            $sessionData = session()->get('order_request_data');

            if (!is_array($sessionData) || empty($sessionData['product_id'])) {
                return redirect('/')->with('error', 'Không tìm thấy dữ liệu đơn hàng trong session. Vui lòng liên hệ hỗ trợ, mã giao dịch: ' . $orderCode);
            }

            // Reconstruct Request với đúng kiểu array
            $orderRequest = new \Illuminate\Http\Request();
            $orderRequest->merge([
                'product_id'   => (array) $sessionData['product_id'],
                'quantity'     => (array) $sessionData['quantity'],
                'user_name'    => $sessionData['user_name']    ?? '',
                'tel'          => $sessionData['tel']          ?? '',
                'address'      => $sessionData['address']      ?? '',
                'ptdv'         => $sessionData['ptdv']         ?? '',
                'notes'        => $sessionData['notes']        ?? '',
                'shipping_fee' => $sessionData['shipping_fee'] ?? 0,
                // Ép tổng tiền theo giao dịch thực tế đã thu
                'soTien'       => $payment->so_tien,
            ]);

            // Gọi logic tạo đơn hàng
            $orderCtrl = new \App\Modules\Affilate\Controllers\Frontend\OrderController();
            return $orderCtrl->createOrdersFromRequest($orderRequest);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayOS paymentSuccess error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Thanh toán thành công nhưng lỗi khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Khi người dùng hủy thanh toán trên trang PayOS
     */
    public function paymentCancel(Request $request)
    {
        $orderCode = $request->get('orderCode');
        $payment = LichSuNapTienPayos::where('ma_don', $orderCode)->first();

        if ($payment) {
            $payment->status     = 'cancel';
            $payment->trang_thai = 'Da huy';
            $payment->cancel     = 1;
            $payment->save();
        }

        return redirect()->route('cart.view')->with('error', 'Bạn đã hủy thanh toán. Vui lòng thử lại.');
    }

    /**
     * Gọi API PayOS tạo payment request
     */
    private function createLink($paymentData)
    {
        $clientId    = env('PAYOS_CLIENT_ID');
        $apiKey      = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $orderCode   = $paymentData['orderCode']   ?? null;
        $amount      = $paymentData['amount']      ?? null;
        $returnUrl   = $paymentData['returnUrl']   ?? null;
        $cancelUrl   = $paymentData['cancelUrl']   ?? null;
        $description = $paymentData['description'] ?? null;

        if (!$orderCode || !$amount || !$returnUrl || !$cancelUrl || !$description) {
            throw new Exception('Thiếu tham số bắt buộc khi tạo yêu cầu thanh toán.');
        }

        $signature = $this->createSignatureOfPaymentRequest($checksumKey, $paymentData);
        $paymentData['signature'] = $signature;

        $url     = 'https://api-merchant.payos.vn/v2/payment-requests';
        $headers = [
            'x-client-id: ' . $clientId,
            'x-api-key: '   . $apiKey,
            'Content-Type: application/json',
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($paymentData));
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        if (isset($result['code']) && $result['code'] == '00' && isset($result['data'])) {
            return $result['data'];
        }

        throw new Exception('Tạo thanh toán thất bại: ' . ($result['desc'] ?? 'Không rõ lỗi'));
    }

    /**
     * Tạo chữ ký HMAC-SHA256 theo chuẩn PayOS
     * (các field phải sorted theo alphabet)
     */
    private function createSignatureOfPaymentRequest($checksumKey, $obj)
    {
        $dataStr = 'amount='      . $obj['amount']
            . '&cancelUrl='  . $obj['cancelUrl']
            . '&description='. $obj['description']
            . '&orderCode='  . $obj['orderCode']
            . '&returnUrl='  . $obj['returnUrl'];

        return hash_hmac('sha256', $dataStr, $checksumKey);
    }
}
