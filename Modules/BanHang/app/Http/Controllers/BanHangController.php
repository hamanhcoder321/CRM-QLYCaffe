<?php

namespace Modules\BanHang\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sell;
use Illuminate\Http\Request;
use Modules\BanHang\Repositories\Interfaces\BanHangRepositoryInterface;
use Yajra\DataTables\DataTables;

class BanHangController extends Controller
{
    public function __construct(protected BanHangRepositoryInterface $repo) {}

    // ================== THỰC ĐƠN / MENU (DRINKS) ==================

    public function thucDon()
    {
        return view('banhang::BanHang.thuc-don');
    }

    public function thucDonData(Request $request)
    {
        abort_unless($request->ajax(), 403);
        $drinks = \App\Models\Drink::with('recipes.product')->latest()->get();
        return DataTables::of($drinks)
            ->addIndexColumn()
            ->editColumn('name', function($r) {
                $img = $r->image ? asset('storage/' . $r->image) : 'https://placehold.co/40x40?text=Drink';
                return '<div class="d-flex align-items-center"><img src="'.$img.'" style="width:40px;height:40px;object-fit:cover;border-radius:6px;margin-right:10px"> <span>' . htmlspecialchars($r->name) . '</span></div>';
            })
            ->editColumn('price', fn($r) => number_format($r->price) . ' đ')
            ->addColumn('recipes', function($r) {
                if ($r->recipes->isEmpty()) return '<span class="text-muted small">Chưa có công thức</span>';
                $html = '<ul class="mb-0 pl-3 small">';
                foreach($r->recipes as $rc) {
                    $html .= '<li>' . ($rc->product->name ?? 'N/A') . ' (' . $rc->quantity . ')</li>';
                }
                $html .= '</ul>';
                return $html;
            })
            ->editColumn('status', fn($r) => $r->status == 1 ? '<span class="badge badge-success">Đang bán</span>' : '<span class="badge badge-danger">Ngừng bán</span>')
            ->addColumn('action', fn($r) => '
                <div class="d-flex gap-1">
                    <button class="btn-action btn-edit" onclick="openEditDrink(' . $r->id . ')" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-del" onclick="deleteDrink(' . $r->id . ')" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ')
            ->rawColumns(['name', 'recipes', 'status', 'action'])
            ->make(true);
    }

    public function thucDonGet(\App\Models\Drink $drink)
    {
        return response()->json($drink->load('recipes.product'));
    }

    public function thucDonStore(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'price'  => ['required', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
            'image'  => ['nullable', 'image', 'max:2048'],
            'recipes'=> ['nullable', 'string'] // JSON string from frontend
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink = \App\Models\Drink::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'status' => $data['status'],
            'image' => $data['image'] ?? null
        ]);

        if (!empty($data['recipes'])) {
            $recipes = json_decode($data['recipes'], true);
            foreach ($recipes as $rc) {
                if (!empty($rc['product_id']) && !empty($rc['quantity'])) {
                    \App\Models\Recipe::create([
                        'drink_id' => $drink->id,
                        'product_id' => $rc['product_id'],
                        'quantity' => $rc['quantity']
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Thêm thực đơn thành công!']);
    }

    public function thucDonUpdate(Request $request, \App\Models\Drink $drink)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'price'  => ['required', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
            'image'  => ['nullable', 'image', 'max:2048'],
            'recipes'=> ['nullable', 'string']
        ]);

        if ($request->hasFile('image')) {
            if ($drink->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($drink->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($drink->image);
            }
            $data['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'status' => $data['status'],
            'image' => $data['image'] ?? $drink->image
        ]);

        if (isset($data['recipes'])) {
            $drink->recipes()->delete(); // Clear old recipes
            $recipes = json_decode($data['recipes'], true);
            if(is_array($recipes)) {
                foreach ($recipes as $rc) {
                    if (!empty($rc['product_id']) && !empty($rc['quantity'])) {
                        \App\Models\Recipe::create([
                            'drink_id' => $drink->id,
                            'product_id' => $rc['product_id'],
                            'quantity' => $rc['quantity']
                        ]);
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function thucDonDelete(\App\Models\Drink $drink)
    {
        if ($drink->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($drink->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($drink->image);
        }
        $drink->delete();
        return response()->json(['success' => true, 'message' => 'Đã xóa thực đơn!']);
    }

    // ================== NGUYÊN LIỆU KHO (PRODUCTS) ==================

    public function thuocUong()
    {
        // Route này không dùng nữa nhưng giữ hàm lại tránh lỗi gọi nhầm
        return view('banhang::BanHang.ton-kho');
    }

    public function thuocUongData(Request $request)
    {
        abort_unless($request->ajax(), 403);

        return DataTables::of($this->repo->getProducts())
            ->addIndexColumn()
            ->editColumn('name', function($r) {
                $img = $r->image ? asset('storage/' . $r->image) : 'https://placehold.co/40x40?text=Drink';
                return '<div class="d-flex align-items-center"><img src="'.$img.'" style="width:40px;height:40px;object-fit:cover;border-radius:6px;margin-right:10px"> <span>' . htmlspecialchars($r->name) . '</span></div>';
            })
            ->editColumn('price', fn($r) => number_format($r->price) . ' đ')
            ->addColumn('ton_kho', function($r) {
                $ton = ($r->number_in ?? 0) - ($r->number_out ?? 0);
                if ($ton <= 0)
                    return '<span class="badge badge-danger">Hết hàng</span>';
                if ($ton <= 10)
                    return '<span class="badge badge-warning">' . $ton . '</span>';
                return '<span class="badge badge-success">' . $ton . '</span>';
            })
            ->addColumn('action', fn($r) => '
                <div class="d-flex gap-1">
                    <button class="btn-action btn-edit" onclick="openEditProduct(' . $r->id . ')" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-del" onclick="deleteProduct(' . $r->id . ')" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ')
            ->rawColumns(['name', 'ton_kho', 'action'])
            ->make(true);
    }

    public function thuocUongStore(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'shipment_id' => ['nullable', 'integer', 'exists:shipments,id'],
            'number_in'   => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'integer', 'min:0'],
            'cost_price'  => ['nullable', 'integer', 'min:0'],
        ], ['name.required' => 'Tên nguyên liệu bắt buộc']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['number_out'] = 0;
        $data['cost_price'] = (int) ($data['cost_price'] ?? 0);
        $data['shipment_id'] = empty($data['shipment_id']) ? null : $data['shipment_id'];

        $this->repo->storeProduct($data);
        return response()->json(['success' => true, 'message' => 'Thêm thức uống thành công!']);
    }

    public function thuocUongGet(Product $product)
    {
        return response()->json($product->load('shipment.arrange'));
    }

    public function thuocUongUpdate(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'shipment_id' => ['nullable', 'integer', 'exists:shipments,id'],
            'number_in'   => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'integer', 'min:0'],
            'cost_price'  => ['nullable', 'integer', 'min:0'],
        ]);

        $data['cost_price'] = (int) ($data['cost_price'] ?? 0);
        $data['shipment_id'] = empty($data['shipment_id']) ? null : $data['shipment_id'];
        
        // Bỏ qua update ảnh vì Nguyên liệu không dùng ảnh nữa
        unset($data['image']);

        $this->repo->updateProduct($product->id, $data);

        if ($request->has('type_arrange') && $request->type_arrange !== null) {
            $product->refresh();
            if ($product->shipment?->arrange) {
                $product->shipment->arrange->update(['type_arrange' => $request->type_arrange]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function thuocUongDelete(Product $product)
    {
        $this->repo->deleteProduct($product->id);
        return response()->json(['success' => true, 'message' => 'Đã xóa!']);
    }

    // ================== TỒN KHO ==================

    public function tonKho()
    {
        return view('banhang::BanHang.ton-kho');
    }

    public function tonKhoData(Request $request)
    {
        abort_unless($request->ajax(), 403);

        return DataTables::of($this->repo->getTonKho())
            ->addIndexColumn()
            ->addColumn('shipment_name', fn($r) => $r->shipment?->arrange?->name_arrange ?? '—')
            ->addColumn('shipment_type', function($r) {
                $type = $r->shipment?->arrange?->type_arrange;
                return $type === 0 ? '<span style="color:#1d4ed8; font-size:11px; font-weight:600">Mới</span>' 
                     : ($type === 1 ? '<span style="color:#374151; font-size:11px; font-weight:600">Cũ</span>' : '—');
            })
            ->addColumn('price_raw', fn($r) => (int) $r->price)
            ->editColumn('price', fn($r) => number_format($r->price) . ' đ')
            ->addColumn('con_lai_raw', fn($r) => ($r->number_in ?? 0) - ($r->number_out ?? 0))
            ->addColumn('con_lai', function ($r) {
                $val = ($r->number_in ?? 0) - ($r->number_out ?? 0);
                if ($val <= 0)  return '<span class="badge badge-danger px-2">Hết hàng</span>';
                if ($val <= 20) return '<span class="badge badge-warning px-2">' . $val . '</span>';
                return '<span class="badge badge-success px-2">' . $val . '</span>';
            })
            ->addColumn('action', fn($r) => '
                <div class="d-flex gap-1 justify-content-center">
                    <button class="btn-action btn-edit" onclick="openEditProduct(' . $r->id . ')" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-del" onclick="deleteProduct(' . $r->id . ')" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ')
            ->rawColumns(['con_lai', 'shipment_type', 'action'])
            ->make(true);
    }

    // ================== GIAO DỊCH BÁN HÀNG ==================

    public function giaoDich()
    {
        $shipments = $this->repo->getShipmentsForSelect();
        $drinks = \App\Models\Drink::with('recipes')->where('status', 1)->orderBy('name')->get();

        return view('banhang::BanHang.giao-dich', compact('shipments', 'drinks'));
    }

    public function giaoDichData(Request $request)
    {
        abort_unless($request->ajax(), 403);

        return DataTables::of($this->repo->getSells())
            ->addIndexColumn()
            ->addColumn('arrange_name', fn($r) => $r->shipment?->arrange?->name_arrange ?? '—')
            // Chỉ tính doanh thu / lợi nhuận khi Đã bán (status = 1)
            ->addColumn('shipment_revenue_raw', fn($r) => $r->status == 1 ? (int) $r->shipment_revenue : 0)
            ->addColumn('profit_raw',           fn($r) => $r->status == 1 ? (int) $r->profit          : 0)
            ->editColumn('status', fn($r) => match($r->status) {
                0 => '<span class="badge-result badge-nhaplieu">Chưa bán</span>',
                1 => '<span class="badge-result badge-hoanthanh">Đã bán</span>',
                default => '—'
            })
            ->editColumn('sell_day', fn($r) => $r->sell_day ?? ($r->created_at ? $r->created_at->format('Y-m-d') : '—'))
            // Hiển thị 0 đ nếu Chưa bán, bất kể giá trị lưu trong DB
            ->editColumn('shipment_revenue', fn($r) => $r->status == 1 ? number_format($r->shipment_revenue) . ' đ' : '—')
            ->editColumn('profit', function($r) {
                if ($r->status != 1) return '—';
                return $r->profit >= 0
                    ? '<span class="text-success font-weight-bold">+' . number_format($r->profit) . ' đ</span>'
                    : '<span class="text-danger">-' . number_format(abs($r->profit)) . ' đ</span>';
            })
            ->addColumn('so_sp', fn($r) => $r->sellProducts->sum('number_sell') . ' món')
            ->addColumn('action', fn($r) => '
                <div class="d-flex gap-1">
                    <button class="btn-action btn-edit" onclick="viewSell(' . $r->id . ')" title="Chi tiết">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-del" onclick="deleteSell(' . $r->id . ')" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ')
            ->rawColumns(['status', 'profit', 'action'])
            ->make(true);
    }

    public function giaoDichStore(Request $request)
    {
        $data = $request->validate([
            'name'            => ['nullable', 'string', 'max:255'],
            'status'          => ['required', 'integer'],
            'sell_day'        => ['required', 'date'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'paid_amount'     => ['nullable', 'integer', 'min:0'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.drink_id'    => ['required', 'integer', 'exists:drinks,id'],
            'items.*.number_sell' => ['required', 'integer', 'min:1'],
            'items.*.price_sell'  => ['required', 'integer', 'min:0'],
            'items.*.note'        => ['nullable', 'string', 'max:255'],
        ], [
            'items.required'              => 'Phải có ít nhất 1 sản phẩm',
            'items.*.drink_id.required'   => 'Chọn thức uống',
            'items.*.number_sell.min'     => 'Số lượng phải ≥ 1',
        ]);

        $sell = $this->repo->storeSell($data, $data['items']);
        return response()->json(['success' => true, 'message' => 'Tạo giao dịch thành công!', 'id' => $sell->id]);
    }

    public function giaoDichUpdate(Request $request, Sell $sell)
    {
        $data = $request->validate([
            'name'            => ['nullable', 'string', 'max:255'],
            'status'          => ['required', 'integer'],
            'sell_day'        => ['required', 'date'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'paid_amount'     => ['nullable', 'integer', 'min:0'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.drink_id'    => ['required', 'integer', 'exists:drinks,id'],
            'items.*.number_sell' => ['required', 'integer', 'min:1'],
            'items.*.price_sell'  => ['required', 'integer', 'min:0'],
            'items.*.note'        => ['nullable', 'string', 'max:255'],
        ], [
            'items.required'              => 'Phải có ít nhất 1 sản phẩm',
            'items.*.drink_id.required'   => 'Chọn thức uống',
            'items.*.number_sell.min'     => 'Số lượng phải ≥ 1',
        ]);

        $this->repo->updateSell($sell->id, $data, $data['items']);
        return response()->json(['success' => true, 'message' => 'Cập nhật giao dịch thành công!']);
    }

    public function giaoDichGet(Sell $sell)
    {
        return response()->json($sell->load('sellProducts.drink', 'shipment.arrange'));
    }

    public function giaoDichDelete(Sell $sell)
    {
        $this->repo->deleteSell($sell->id);
        return response()->json(['success' => true, 'message' => 'Đã xóa giao dịch!']);
    }

    /**
     * Tạo link thanh toán PayOS cho giao dịch BanHang.
     * POST /ban-hang/giao-dich/payos/tao-link
     */
    public function payosTaoLink(Request $request)
    {
        $data = $request->validate([
            'so_tien'        => ['required', 'integer', 'min:1000'],
            'sell_day'       => ['required', 'date'],
            'payment_method' => ['nullable', 'string'],
            'name'           => ['nullable', 'string', 'max:255'],
            'note'           => ['nullable', 'string', 'max:255'],
            'status'         => ['nullable', 'integer'],
            'items'          => ['required', 'array', 'min:1'],
            'items.*.drink_id'    => ['required', 'integer', 'exists:drinks,id'],
            'items.*.number_sell' => ['required', 'integer', 'min:1'],
            'items.*.price_sell'  => ['required', 'integer', 'min:0'],
            'items.*.note'        => ['nullable', 'string'],
        ]);

        $amount    = (int) $data['so_tien'];
        $orderCode = intval(substr(strval(microtime(true) * 10000), -9));
        $domain    = $request->getSchemeAndHttpHost();
        $user      = auth()->user();

        // Lưu session để tạo Sell sau callback
        session()->put('banhang_payos_data', [
            'sell_day'       => $data['sell_day'],
            'payment_method' => $data['payment_method'] ?? 'payos',
            'items'          => $data['items'],
            'so_tien'        => $amount,
            'order_code'     => $orderCode,
            'name'           => $data['name'] ?? null,
            'note'           => $data['note'] ?? null,
            'status'         => $data['status'] ?? 1,
        ]);

        // Lưu lịch sử thanh toán (pending)
        \App\Modules\Payment\Models\LichSuNapTienPayos::create([
            'admin_id'   => $user?->id,
            'ma_don'     => (string) $orderCode,
            'loai_don'   => 'ban_hang',
            'so_tien'    => $amount,
            'status'     => 'pending',
            'trang_thai' => 'Cho thanh toan',
            'description'=> 'BanHang#' . $orderCode,
            'cancel'     => 0,
        ]);

        // Gọi PayOS API
        $clientId    = env('PAYOS_CLIENT_ID');
        $apiKey      = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $description = 'BanHang' . $orderCode; // tối đa 25 ký tự ASCII
        $returnUrl   = $domain . '/ban-hang/payos/success?orderCode=' . $orderCode;
        $cancelUrl   = $domain . '/ban-hang/payos/cancel?orderCode=' . $orderCode;

        $payload = [
            'orderCode'   => $orderCode,
            'amount'      => $amount,
            'description' => $description,
            'returnUrl'   => $returnUrl,
            'cancelUrl'   => $cancelUrl,
            'items'       => [['name' => 'Giao dich #' . $orderCode, 'quantity' => 1, 'price' => $amount]],
        ];

        // Tạo chữ ký HMAC-SHA256
        $dataStr = 'amount=' . $amount
            . '&cancelUrl=' . $cancelUrl
            . '&description=' . $description
            . '&orderCode=' . $orderCode
            . '&returnUrl=' . $returnUrl;
        $payload['signature'] = hash_hmac('sha256', $dataStr, $checksumKey);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api-merchant.payos.vn/v2/payment-requests',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'x-client-id: ' . $clientId,
                'x-api-key: ' . $apiKey,
                'Content-Type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);

        if (isset($result['code']) && $result['code'] == '00' && !empty($result['data']['checkoutUrl'])) {
            // Cập nhật link vào lịch sử
            \App\Modules\Payment\Models\LichSuNapTienPayos::where('ma_don', $orderCode)->update([
                'link'          => $result['data']['checkoutUrl'],
                'paymentLinkId' => $result['data']['paymentLinkId'] ?? null,
                'checkoutUrl'   => $result['data']['checkoutUrl'],
            ]);
            return response()->json(['success' => true, 'checkout_url' => $result['data']['checkoutUrl']]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tạo được link thanh toán: ' . ($result['desc'] ?? 'Lỗi không rõ'),
        ], 422);
    }

    /**
     * PayOS redirect về sau khi thanh toán thành công.
     * GET /ban-hang/payos/success?orderCode=...
     */
    public function payosSuccess(Request $request)
    {
        $orderCode = $request->get('orderCode');
        $payment   = \App\Modules\Payment\Models\LichSuNapTienPayos::where('ma_don', $orderCode)->first();

        if ($payment && $payment->trang_thai !== 'Da thanh toan') {
            $payment->update(['status' => 'paid', 'trang_thai' => 'Da thanh toan']);
        }

        // Lấy dữ liệu từ session để tạo Sell
        $sessionData = session()->get('banhang_payos_data');

        if ($sessionData && isset($sessionData['items'])) {
            try {
                $sellData = [
                    'sell_day'       => $sessionData['sell_day'] ?? now()->format('Y-m-d'),
                    'payment_method' => $sessionData['payment_method'] ?? 'payos',
                    'status'         => $sessionData['status'] ?? 1, // Đã bán
                    'paid_amount'    => $sessionData['so_tien'] ?? 0,
                    'name'           => $sessionData['name'] ?? ('Thanh toán PayOS #' . $orderCode),
                    'note'           => $sessionData['note'] ?? null,
                ];
                $sell = $this->repo->storeSell($sellData, $sessionData['items']);

                // Liên kết lịch sử PayOS với Sell vừa tạo
                if ($payment) {
                    $payment->update(['sell_id' => $sell->id]);
                }

                session()->forget('banhang_payos_data');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('BanHang PayOS success error: ' . $e->getMessage());
                return redirect()->route('banhang.giao-dich')->with('error', 'Thanh toán thành công nhưng lỗi tạo giao dịch: ' . $e->getMessage());
            }
        }

        return redirect()->route('banhang.giao-dich')->with('success', 'Thanh toán PayOS thành công! Giao dịch đã được ghi nhận.');
    }

    /**
     * Hủy thanh toán PayOS.
     * GET /ban-hang/payos/cancel?orderCode=...
     */
    public function payosCancel(Request $request)
    {
        $orderCode = $request->get('orderCode');
        \App\Modules\Payment\Models\LichSuNapTienPayos::where('ma_don', $orderCode)
            ->update(['status' => 'cancel', 'trang_thai' => 'Da huy', 'cancel' => 1]);

        session()->forget('banhang_payos_data');
        return redirect()->route('banhang.giao-dich')->with('error', 'Bạn đã hủy thanh toán PayOS.');
    }
}
