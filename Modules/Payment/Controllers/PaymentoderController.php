<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Payment\Models\LichSuNapTienPayos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Modules\Affilate\Request\ThanhToanRequest;

class PaymentoderController extends Controller
{
    /**
     * Khi người dùng ấn Đặt hàng -> tạo link thanh toán PayOS
     */
    public function createPaymentLink(Request $request)
    {
        $YOUR_DOMAIN = $request->getSchemeAndHttpHost();
        // Lưu dữ liệu form để callback dùng tạo bill
        session(['order_request_data' => $request->all()]);

        // 1️⃣ Lấy tổng tiền đơn hàng
        $amount = intval($request->input('soTien'));
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'Số tiền thanh toán không hợp lệ.');
        }

        // 2️⃣ Tạo mã đơn hàng (unique)
        $orderCode = intval(substr(strval(microtime(true) * 10000), -9));
        $user = Auth::user();

        // 3️⃣ Lưu tạm lịch sử thanh toán
        $loai_don = $request->input('loai_don', 'Đơn hàng sản phẩm');
        $lichsu = LichSuNapTienPayos::create(array(
            'admin_id'      => $user ? $user->id : null,
            'ma_don'        => strval($orderCode),
            'loai_don'      => $loai_don,
            'cancel'        => 0,
            'so_tien'       => $amount,
            'status'        => 'pending',
            'trang_thai'    => 'Chờ thanh toán',
            'description'   => 'Thanh toán ' . $loai_don
        ));

        // 4️⃣ Gọi API PayOS để lấy QR
        $data = array(
            "orderCode"   => $orderCode,
            "amount"      => $amount,
            "items"       => array(
                array(
                    "name"     => "đơn hàng #" . $orderCode,
                    "quantity" => 1,
                    "price"    => $amount
                )
            ),
            "description" => "đơn hàng #" . $orderCode,
            "returnUrl"   => $YOUR_DOMAIN . '/payos/success?orderCode=' . $orderCode,
            "cancelUrl"   => $YOUR_DOMAIN . '/payos/cancel?orderCode=' . $orderCode
        );

        try {
            $response = $this->createLink($data);

            $lichsu->update(array(
                'link'         => isset($response['checkoutUrl']) ? $response['checkoutUrl'] : null,
                'paymentLinkId'=> isset($response['paymentLinkId']) ? $response['paymentLinkId'] : null
            ));

            if (isset($response['checkoutUrl'])) {
                return redirect($response['checkoutUrl']);
            }

            return redirect()->back()->with('error', 'Không tạo được liên kết thanh toán.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Lỗi tạo thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * 5️⃣ Xử lý callback từ PayOS (webhook / redirect success)
     * Khi PayOS báo thành công => gọi hàm tạo đơn hàng (OrderController@thanhtoan)
     */
    public function paymentSuccess(Request $request)
    {
        $orderCode = $request->get('orderCode');
        if (!$orderCode) {
            return redirect('/')->with('error', 'Thiếu mã đơn hàng.');
        }

        $payment = \App\Modules\Payment\Models\LichSuNapTienPayos::where('ma_don', $orderCode)->first();
        if (!$payment) {
            return redirect('/')->with('error', 'Không tìm thấy lịch sử thanh toán.');
        }

        // Đánh dấu đã thanh toán
        $payment->status = 'paid';
        $payment->trang_thai = 'Đã thanh toán';
        $payment->save();

        try {
            // Lấy lại form đã lưu từ session
            $sessionData = session()->get('order_request_data', []);
            $orderRequest = new \Illuminate\Http\Request();
            $orderRequest->merge($sessionData);
            
            // Ép tổng tiền và phương thức thanh toán
            $orderRequest->merge([
                'soTien' => $payment->so_tien,
                'pttt'           => 'PayOS', 
                'ptdv'           => 'PayOS',
                'is_paid_online' => true,
                'status'         => 1, // Đã thanh toán
            ]);

            if ($payment->loai_don == 'Giao dịch bán hàng') {
                // Xử lý cho module BanHang (POS)
                $orderCtrl = app(\Modules\BanHang\Http\Controllers\BanHangController::class);
                $response = $orderCtrl->thanhtoan($orderRequest);
            } else {
                // Xử lý mặc định cho module Affilate
                $orderCtrl = new \App\Modules\Affilate\Controllers\Frontend\OrderController();
                // Vì Affilate dùng FormRequest riêng nên ta cần merge vào đúng class đó
                $affilateRequest = new \App\Modules\Affilate\Request\ThanhToanRequest();
                $affilateRequest->merge($orderRequest->all());
                $response = $orderCtrl->thanhtoan($affilateRequest);
            }

            // Xoá session form cũ
            session()->forget('order_request_data');

            // Trả về view/redirect y như thanhtoan trả về
            if ($response instanceof \Illuminate\View\View || $response instanceof \Illuminate\Http\RedirectResponse) {
                return $response;
            }

            return redirect('/')->with('success', 'Thanh toán thành công, đơn hàng đã được ghi nhận!');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tạo đơn hàng sau PayOS: '.$e->getMessage());
            return redirect('/')->with('error', 'Thanh toán thành công nhưng lỗi tạo đơn: '.$e->getMessage());
        }
    }



    /**
     * Khi người dùng hủy thanh toán
     */
    public function paymentCancel(Request $request)
    {
        $orderCode = $request->get('orderCode');
        $payment = LichSuNapTienPayos::where('ma_don', $orderCode)->first();

        if ($payment) {
            $payment->status = 'cancel';
            $payment->trang_thai = 'Đã hủy';
            $payment->cancel = 1;
            $payment->save();
        }

        if ($payment && $payment->loai_don == 'Giao dịch bán hàng') {
            return redirect()->route('banhang.giao-dich')->with('error', 'Bạn đã hủy thanh toán.');
        }
        return redirect()->route('cart.view')->with('error', 'Bạn đã hủy thanh toán.');
    }

    /**
     *  Hàm gọi API PayOS (chuẩn PHP 5.6)
     */
    private function createLink($paymentData)
    {
        $clientId    = env('PAYOS_CLIENT_ID');
        $apiKey      = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $orderCode   = isset($paymentData['orderCode']) ? $paymentData['orderCode'] : null;
        $amount      = isset($paymentData['amount']) ? $paymentData['amount'] : null;
        $returnUrl   = isset($paymentData['returnUrl']) ? $paymentData['returnUrl'] : null;
        $cancelUrl   = isset($paymentData['cancelUrl']) ? $paymentData['cancelUrl'] : null;
        $description = isset($paymentData['description']) ? $paymentData['description'] : null;

        if (!$orderCode || !$amount || !$returnUrl || !$cancelUrl || !$description) {
            throw new Exception('Thiếu tham số bắt buộc khi tạo yêu cầu thanh toán.');
        }

        $signature = $this->createSignatureOfPaymentRequest($checksumKey, $paymentData);

        $paymentData['signature'] = $signature;

        $url = 'https://api-merchant.payos.vn/v2/payment-requests';

        $headers = array(
            'x-client-id: ' . $clientId,
            'x-api-key: ' . $apiKey,
            'Content-Type: application/json'
        );

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

        throw new Exception('Tạo thanh toán thất bại: ' . (isset($result['desc']) ? $result['desc'] : 'Không rõ lỗi'));
    }

    private function createSignatureOfPaymentRequest($checksumKey, $obj)
    {
        $dataStr = "amount=" . $obj["amount"]
            . "&cancelUrl=" . $obj["cancelUrl"]
            . "&description=" . $obj["description"]
            . "&orderCode=" . $obj["orderCode"]
            . "&returnUrl=" . $obj["returnUrl"];

        return hash_hmac("sha256", $dataStr, $checksumKey);
    }
}
