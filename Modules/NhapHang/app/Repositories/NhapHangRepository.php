<?php

namespace Modules\NhapHang\Repositories;

use App\Models\Arrange;
use App\Models\Shipment;
use App\Models\Part;
use App\Models\Team;
use App\Models\User;
use Modules\NhapHang\Repositories\Interfaces\NhapHangRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NhapHangRepository implements NhapHangRepositoryInterface
{
    public function getFilters(): array
    {
        return [
            'part_f'   => Part::select('id', 'name as text')->orderBy('name')->get(),
            'team_f'   => Team::select('id', 'name as text')->orderBy('name')->get(),
            'result_f' => collect([
                ['id' => 0, 'text' => 'Chưa bốc hàng'],
                ['id' => 1, 'text' => 'Hoàn thành'],
                ['id' => 2, 'text' => 'Thất bại'],
            ]),
        ];
    }

    public function getData(Request $request)
    {
        $query = Arrange::with('saleUser', 'user', 'supportUser', 'part', 'team')
            ->orderBy('day', 'desc');

        if ($request->filled('part_id')) {
            $query->where('part_id', $request->part_id);
        }
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('day', fn($row) => optional($row->day)->format('d/m/Y') ?? '')
            ->editColumn('part_id', fn($row) => $row->part->name ?? '')
            ->editColumn('team_id', fn($row) => $row->team->name ?? '')
            ->editColumn('sale_user_id', fn($row) => $row->saleUser->name ?? '')
            ->editColumn('user_id', fn($row) => $row->user->name ?? '')
            ->editColumn('support_user_id', fn($row) => $row->supportUser->name ?? '')
            ->editColumn('type_arrange', fn($row) => $row->type_arrange == 0 ? '<span class="badge badge-moi">Mới</span>' : '<span class="badge badge-cu">Cũ</span>')
            ->editColumn('result', function ($row) {
                return match ((int)$row->result) {
                    0 => '<span class="badge-result badge-nhaplieu">Nhập liệu</span>',
                    1 => '<span class="badge-result badge-hoanthanh">Hoàn thành</span>',
                    2 => '<span class="badge-result badge-fail">Thất bại</span>',
                    default => '',
                };
            })
            ->editColumn('total_arrange', fn($row) => $row->total_arrange ? number_format($row->total_arrange) : 0)
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex gap-1">
                        <button class="btn-action btn-edit" onclick="openEdit(' . $row->id . ')" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="' . route('nhaphang.delete', $row->id) . '" method="POST" class="form-delete">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn-action btn-del btn-delete" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action', 'result', 'type_arrange'])
            ->filter(function ($query) use ($request) {
                $search = $request->input('search.value');
                if ($search) {
                    $like = '%' . trim($search) . '%';
                    $query->where(function ($q) use ($like) {
                        $q->where('name_arrange', 'like', $like)
                            ->orWhere('name_customer', 'like', $like)
                            ->orWhere('phone_customer', 'like', $like);
                    });
                }
            })
            ->make(true);
    }

    public function store(array $data): Arrange
    {
        $arrange = Arrange::create([
            'day'             => $data['day'],
            'name_arrange'    => $data['name_arrange'],
            'name_customer'   => $data['name_customer'] ?? null,
            'address'         => $data['address'] ?? null,
            'phone_customer'  => $data['phone_customer'] ?? null,
            'sale_user_id'    => $data['sale_user_id'] ?? null,
            'part_id'         => $data['part_id'] ?? null,
            'team_id'         => $data['team_id'] ?? null,
            'account_social'  => $data['account_social'] ?? null,
            'user_id'         => $data['user_id'] ?? null,
            'support_user_id' => $data['support_user_id'] ?? null,
            'type_arrange'    => $data['type_arrange'] ?? 0,
            'result'          => $data['result'] ?? 0,
            'reason_fail'     => $data['reason_fail'] ?? null,
            'total_arrange'   => $data['total_arrange'] ?? 0,
        ]);

        // Tự động tạo Shipment (Đơn nhập) liên kết
        Shipment::create([
            'arrange_id'  => $arrange->id,
            'customer_id' => null,
            'car_money'   => 0,
        ]);

        return $arrange;
    }

    public function update(array $data, Arrange $arrange): bool
    {
        $updated = $arrange->update([
            'day'             => $data['day'],
            'name_arrange'    => $data['name_arrange'],
            'name_customer'   => $data['name_customer'] ?? null,
            'address'         => $data['address'] ?? null,
            'phone_customer'  => $data['phone_customer'] ?? null,
            'sale_user_id'    => $data['sale_user_id'] ?? null,
            'part_id'         => $data['part_id'] ?? null,
            'team_id'         => $data['team_id'] ?? null,
            'account_social'  => $data['account_social'] ?? null,
            'user_id'         => $data['user_id'] ?? null,
            'support_user_id' => $data['support_user_id'] ?? null,
            'type_arrange'    => $data['type_arrange'] ?? 0,
            'result'          => $data['result'] ?? 0,
            'reason_fail'     => $data['reason_fail'] ?? null,
            'total_arrange'   => $data['total_arrange'] ?? 0,
        ]);

        // Đồng bộ Shipment nếu chưa có
        if ($arrange->shipments()->count() === 0) {
            Shipment::create([
                'arrange_id'  => $arrange->id,
                'customer_id' => null,
                'car_money'   => 0,
            ]);
        }

        return $updated;
    }

    public function destroy(Arrange $arrange): bool
    {
        return $arrange->delete();
    }

    public function formOptions(): array
    {
        return [
            'parts'   => Part::select('id', 'name as text')->orderBy('name')->get(),
            'teams'   => Team::select('id', 'name as text')->orderBy('name')->get(),
            'users'   => User::select('id', 'name as text')->where('status', 0)->orderBy('name')->get(),
            'results' => [
                ['id' => 0, 'text' => 'Nhập liệu'],
                ['id' => 1, 'text' => 'Hoàn thành'],
                ['id' => 2, 'text' => 'Thất bại'],
            ],
            'type_arranges' => [
                ['id' => 0, 'text' => 'Mới'],
                ['id' => 1, 'text' => 'Cũ'],
            ],
        ];
    }
}
