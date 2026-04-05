<?php

namespace Modules\NhapHang\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Arrange;
use App\Models\Customer;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Modules\NhapHang\Repositories\Interfaces\NhapHangRepositoryInterface;
use Modules\NhapHang\Repositories\NhapHangRepository;
use Yajra\DataTables\DataTables;

class NhapHangController extends Controller
{
    protected NhapHangRepositoryInterface $repo;

    public function __construct(NhapHangRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('nhaphang::NhapHang.list');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            return $this->repo->getData($request);
        }
    }

    public function getFilters()
    {
        return response()->json($this->repo->getFilters());
    }

    public function store(Request $request)
    {
        // Convert empty string -> null cho các field integer trước khi validate
        $request->merge(collect($request->only([
            'sale_user_id','part_id','team_id','user_id','support_user_id',
            'type_arrange','result','total_arrange',
        ]))->map(fn($v) => $v === '' ? null : $v)->toArray());

        $validated = $request->validate([
            'day'            => ['required', 'date'],
            'name_arrange'   => ['required', 'string', 'max:255'],
            'name_customer'  => ['nullable', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:500'],
            'phone_customer' => ['nullable', 'string', 'max:20'],
            'sale_user_id'   => ['nullable', 'integer', 'exists:users,id'],
            'part_id'        => ['nullable', 'integer', 'exists:parts,id'],
            'team_id'        => ['nullable', 'integer', 'exists:teams,id'],
            'account_social' => ['nullable', 'string', 'max:255'],
            'user_id'        => ['nullable', 'integer', 'exists:users,id'],
            'support_user_id'=> ['nullable', 'integer', 'exists:users,id'],
            'type_arrange'   => ['nullable', 'integer'],
            'result'         => ['nullable', 'integer'],
            'reason_fail'    => ['nullable', 'string', 'max:500'],
            'total_arrange'  => ['nullable', 'integer'],
        ], [
            'day.required'          => 'Ngày nhập hàng là bắt buộc',
            'name_arrange.required' => 'Tên lô hàng là bắt buộc',
        ]);

        $this->repo->store($validated);

        return response()->json(['success' => true, 'message' => 'Thêm lô hàng thành công!']);
    }

    public function update(Request $request, Arrange $arrange)
    {
        $request->merge(collect($request->only([
            'sale_user_id','part_id','team_id','user_id','support_user_id',
            'type_arrange','result','total_arrange',
        ]))->map(fn($v) => $v === '' ? null : $v)->toArray());

        $validated = $request->validate([
            'day'            => ['required', 'date'],
            'name_arrange'   => ['required', 'string', 'max:255'],
            'name_customer'  => ['nullable', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:500'],
            'phone_customer' => ['nullable', 'string', 'max:20'],
            'sale_user_id'   => ['nullable', 'integer', 'exists:users,id'],
            'part_id'        => ['nullable', 'integer', 'exists:parts,id'],
            'team_id'        => ['nullable', 'integer', 'exists:teams,id'],
            'account_social' => ['nullable', 'string', 'max:255'],
            'user_id'        => ['nullable', 'integer', 'exists:users,id'],
            'support_user_id'=> ['nullable', 'integer', 'exists:users,id'],
            'type_arrange'   => ['nullable', 'integer'],
            'result'         => ['nullable', 'integer'],
            'reason_fail'    => ['nullable', 'string', 'max:500'],
            'total_arrange'  => ['nullable', 'integer'],
        ], [
            'day.required'          => 'Ngày nhập hàng là bắt buộc',
            'name_arrange.required' => 'Tên lô hàng là bắt buộc',
        ]);

        $this->repo->update($validated, $arrange);

        return response()->json(['success' => true, 'message' => 'Cập nhật lô hàng thành công!']);
    }

    public function destroy(Arrange $arrange)
    {
        $this->repo->destroy($arrange);
        return redirect()->route('nhaphang.list')->with('success', 'Xóa lô hàng thành công!');
    }

    public function getFormOptions()
    {
        /** @var NhapHangRepository $repo */
        return response()->json($this->repo->formOptions());
    }

    public function getArrange(Arrange $arrange)
    {
        $arrange->load('saleUser', 'user', 'supportUser', 'part', 'team');
        return response()->json($arrange);
    }

    // ===================== ĐƠN NHẬP =====================

    public function donNhap()
    {
        return view('nhaphang::NhapHang.don-nhap');
    }

    public function donNhapData(Request $request)
    {
        if (!$request->ajax()) return;

        $query = Shipment::with('arrange', 'customer')
            ->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('arrange_id', fn($r) => $r->arrange->name_arrange ?? '—')
            ->editColumn('customer_id', fn($r) => $r->customer->fullname ?? '—')
            ->editColumn('car_money', fn($r) => $r->car_money ? number_format($r->car_money) . ' đ' : '0 đ')
            ->addColumn('tong_gia_tri', fn($r) => $r->arrange ? number_format($r->arrange->total_arrange ?? 0) . ' đ' : '—')
            ->addColumn('ngay', fn($r) => $r->arrange ? optional($r->arrange->day)->format('d/m/Y') : '—')
            ->addColumn('result', function ($r) {
                if (!$r->arrange) return '—';
                return match ((int)$r->arrange->result) {
                    0 => '<span class="badge-result badge-nhaplieu">Nhập liệu</span>',
                    1 => '<span class="badge-result badge-hoanthanh">Hoàn thành</span>',
                    2 => '<span class="badge-result badge-fail">Thất bại</span>',
                    default => '—',
                };
            })
            ->rawColumns(['result'])
            ->make(true);
    }

    // ===================== NHÀ CUNG CẤP =====================

    public function nhaCungCap()
    {
        return view('nhaphang::NhapHang.nha-cung-cap');
    }

    public function nhaCungCapGet(Customer $customer)
    {
        return response()->json($customer);
    }

    public function nhaCungCapData(Request $request)
    {
        if (!$request->ajax()) return;

        $query = Customer::withCount('shipments')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('classify', fn($r) => $r->classify == 0
                ? '<span class="badge-result badge-fail">Hết hàng</span>'
                : '<span class="badge-result badge-hoanthanh">Còn hàng</span>')
            ->editColumn('scale', fn($r) => match ((int)$r->scale) {
                0 => 'Nhỏ', 1 => 'Vừa', 2 => 'Lớn', default => '—'
            })
            ->editColumn('potentical', fn($r) => match ((int)$r->potentical) {
                0 => '<span style="color:#dc2626">Thấp</span>',
                1 => '<span style="color:#d97706">Trung bình</span>',
                2 => '<span style="color:#16a34a">Cao</span>',
                default => '—',
            })
            ->addColumn('action', function ($r) {
                return '
                    <div class="d-flex gap-1">
                        <button class="btn-action btn-edit" onclick="openEditNCC(' . $r->id . ')" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="' . route('nhaphang.nha-cung-cap.delete', $r->id) . '" method="POST" class="form-delete">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn-action btn-del btn-delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['classify', 'potentical', 'action'])
            ->filter(function ($q) use ($request) {
                if ($s = $request->input('search.value')) {
                    $like = '%' . trim($s) . '%';
                    $q->where(fn($x) => $x->where('fullname', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('address', 'like', $like));
                }
            })
            ->make(true);
    }

    public function nhaCungCapStore(Request $request)
    {
        $data = $request->validate([
            'fullname'    => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
            'source'      => ['nullable', 'string', 'max:255'],
            'product_sale'=> ['nullable', 'string', 'max:255'],
            'classify'    => ['nullable', 'integer'],
            'scale'       => ['nullable', 'integer'],
            'potentical'  => ['nullable', 'integer'],
            'note'        => ['nullable', 'string'],
        ], ['fullname.required' => 'Tên nhà cung cấp là bắt buộc']);

        Customer::create($data);
        return response()->json(['success' => true, 'message' => 'Thêm nhà cung cấp thành công!']);
    }

    public function nhaCungCapUpdate(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'fullname'    => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
            'source'      => ['nullable', 'string', 'max:255'],
            'product_sale'=> ['nullable', 'string', 'max:255'],
            'classify'    => ['nullable', 'integer'],
            'scale'       => ['nullable', 'integer'],
            'potentical'  => ['nullable', 'integer'],
            'note'        => ['nullable', 'string'],
        ], ['fullname.required' => 'Tên nhà cung cấp là bắt buộc']);

        $customer->update($data);
        return response()->json(['success' => true, 'message' => 'Cập nhật nhà cung cấp thành công!']);
    }

    public function nhaCungCapDestroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('nhaphang.nha-cung-cap')->with('success', 'Xóa nhà cung cấp thành công!');
    }
}
