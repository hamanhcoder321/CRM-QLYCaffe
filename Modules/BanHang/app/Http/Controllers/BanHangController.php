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

    // ================== THỨC UỐNG ==================

    public function getShipments()
    {
        return response()->json($this->repo->getShipmentsForSelect());
    }

    public function thuocUong()
    {
        return view('banhang::BanHang.thuc-uong');
    }

    public function thuocUongData(Request $request)
    {
        abort_unless($request->ajax(), 403);

        return DataTables::of($this->repo->getProducts())
            ->addIndexColumn()
            ->addColumn('shipment_name', fn($r) => $r->shipment?->arrange?->name_arrange ?? '—')
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
            ->rawColumns(['ton_kho', 'action'])
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
        ], ['name.required' => 'Tên thức uống bắt buộc']);

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
            'shipment_id' => ['nullable', 'integer', 'exists:shipments,id'],
            'number_in'   => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'integer', 'min:0'],
            'cost_price'  => ['nullable', 'integer', 'min:0'],
        ]);

        $data['cost_price'] = (int) ($data['cost_price'] ?? 0);
        $data['shipment_id'] = empty($data['shipment_id']) ? null : $data['shipment_id'];
        $this->repo->updateProduct($product->id, $data);
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
            ->addColumn('price_raw', fn($r) => (int) $r->price)
            ->editColumn('price', fn($r) => number_format($r->price) . ' đ')
            ->addColumn('con_lai_raw', fn($r) => ($r->number_in ?? 0) - ($r->number_out ?? 0))
            ->addColumn('con_lai', function ($r) {
                $val = ($r->number_in ?? 0) - ($r->number_out ?? 0);
                if ($val <= 0)  return '<span class="badge badge-danger px-2">Hết hàng</span>';
                if ($val <= 20) return '<span class="badge badge-warning px-2">' . $val . '</span>';
                return '<span class="badge badge-success px-2">' . $val . '</span>';
            })
            ->rawColumns(['con_lai'])
            ->make(true);
    }

    // ================== GIAO DỊCH BÁN HÀNG ==================

    public function giaoDich()
    {
        $shipments = $this->repo->getShipmentsForSelect();
        $products  = Product::orderBy('name')
            ->get()
            ->map(function ($p) {
                $p->ton_kho = ($p->number_in ?? 0) - ($p->number_out ?? 0);
                return $p;
            })
            ->filter(fn($p) => $p->ton_kho > 0)
            ->values();

        return view('banhang::BanHang.giao-dich', compact('shipments', 'products'));
    }

    public function giaoDichData(Request $request)
    {
        abort_unless($request->ajax(), 403);

        return DataTables::of($this->repo->getSells())
            ->addIndexColumn()
            ->addColumn('arrange_name', fn($r) => $r->shipment?->arrange?->name_arrange ?? '—')
            ->addColumn('shipment_revenue_raw', fn($r) => (int) $r->shipment_revenue)
            ->addColumn('profit_raw', fn($r) => (int) $r->profit)
            ->editColumn('status', fn($r) => match($r->status) {
                0 => '<span class="badge-result badge-nhaplieu">Chưa bán</span>',
                1 => '<span class="badge-result badge-hoanthanh">Đã bán</span>',
                default => '—'
            })
            ->editColumn('sell_day', fn($r) => $r->sell_day ?? ($r->created_at ? $r->created_at->format('Y-m-d') : '—'))
            ->editColumn('shipment_revenue', fn($r) => number_format($r->shipment_revenue) . ' đ')
            ->editColumn('profit', fn($r) => ($r->profit >= 0)
                ? '<span class="text-success font-weight-bold">+' . number_format($r->profit) . ' đ</span>'
                : '<span class="text-danger">-' . number_format(abs($r->profit)) . ' đ</span>')
            ->addColumn('so_sp', fn($r) => $r->sellProducts->count() . ' sản phẩm')
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
            'shipment_id'     => ['nullable', 'integer'],
            'status'          => ['required', 'integer'],
            'sell_day'        => ['required', 'date'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'paid_amount'     => ['nullable', 'integer', 'min:0'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'integer', 'exists:products,id'],
            'items.*.number_sell' => ['required', 'integer', 'min:1'],
            'items.*.price_sell'  => ['required', 'integer', 'min:0'],
        ], [
            'items.required'              => 'Phải có ít nhất 1 sản phẩm',
            'items.*.product_id.required' => 'Chọn sản phẩm',
            'items.*.number_sell.min'     => 'Số lượng phải ≥ 1',
        ]);

        $sell = $this->repo->storeSell($data, $data['items']);
        return response()->json(['success' => true, 'message' => 'Tạo giao dịch thành công!', 'id' => $sell->id]);
    }

    public function giaoDichUpdate(Request $request, Sell $sell)
    {
        $data = $request->validate([
            'name'            => ['nullable', 'string', 'max:255'],
            'shipment_id'     => ['nullable', 'integer'],
            'status'          => ['required', 'integer'],
            'sell_day'        => ['required', 'date'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'paid_amount'     => ['nullable', 'integer', 'min:0'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'integer', 'exists:products,id'],
            'items.*.number_sell' => ['required', 'integer', 'min:1'],
            'items.*.price_sell'  => ['required', 'integer', 'min:0'],
        ], [
            'items.required'              => 'Phải có ít nhất 1 sản phẩm',
            'items.*.product_id.required' => 'Chọn sản phẩm',
            'items.*.number_sell.min'     => 'Số lượng phải ≥ 1',
        ]);

        $this->repo->updateSell($sell->id, $data, $data['items']);
        return response()->json(['success' => true, 'message' => 'Cập nhật giao dịch thành công!']);
    }

    public function giaoDichGet(Sell $sell)
    {
        return response()->json($sell->load('sellProducts.product', 'shipment.arrange'));
    }

    public function giaoDichDelete(Sell $sell)
    {
        $this->repo->deleteSell($sell->id);
        return response()->json(['success' => true, 'message' => 'Đã xóa giao dịch!']);
    }
}
