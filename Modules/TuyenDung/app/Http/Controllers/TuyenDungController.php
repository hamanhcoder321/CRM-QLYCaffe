<?php

namespace Modules\TuyenDung\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Position;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TuyenDungController extends Controller
{
    public function index()
    {
        return view('tuyendung::TuyenDung.index');
    }

    public function create()
    {
        $parts = Part::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('tuyendung::TuyenDung.create', compact('parts', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'part_id' => ['required', 'integer', 'exists:parts,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'number' => ['required', 'integer', 'min:1'],
            'prioritize' => ['required', 'integer', 'in:0,1,2'],
            'deadline' => ['nullable', 'date'],
            'social' => ['nullable', 'string', 'max:255'],
            'obstacle' => ['nullable', 'string', 'max:255'],
            'solution' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:0,1,2'],
            'result' => ['nullable', 'integer', 'in:0,1'],
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 0;
        $data['result'] = $data['result'] ?? 0;

        Recruitment::create($data);

        return redirect()->route('tuyendung.list')->with('success', 'Đã tạo post tuyển dụng thành công');
    }

    public function data(Request $request)
    {
        abort_unless($request->ajax(), 403);

        $query = Recruitment::with(['part', 'position', 'user'])->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('part_name', fn ($r) => $r->part?->name ?? '—')
            ->addColumn('position_name', fn ($r) => $r->position?->name ?? '—')
            ->addColumn('user_name', fn ($r) => $r->user?->name ?? '—')
            ->editColumn('prioritize', function ($r) {
                return match ((int) $r->prioritize) {
                    0 => '<span class="badge badge-secondary">Thấp</span>',
                    1 => '<span class="badge badge-warning">Trung bình</span>',
                    2 => '<span class="badge badge-danger">Cao</span>',
                    default => '—',
                };
            })
            ->editColumn('status', function ($r) {
                return match ((int) $r->status) {
                    0 => '<span class="badge badge-info">Đang tuyển</span>',
                    1 => '<span class="badge badge-success">Hoàn thành</span>',
                    2 => '<span class="badge badge-danger">Trễ</span>',
                    default => '—',
                };
            })
            ->editColumn('result', function ($r) {
                return match ((int) $r->result) {
                    0 => '<span class="badge badge-secondary">Chưa có</span>',
                    1 => '<span class="badge badge-success">Đạt</span>',
                    default => '—',
                };
            })
            ->editColumn('deadline', fn ($r) => $r->deadline ? date('d/m/Y H:i', strtotime($r->deadline)) : '—')
            ->addColumn('action', fn ($r) => '<a class="btn btn-sm btn-primary" href="' . route('tuyendung.create') . '">Tạo mới</a>')
            ->rawColumns(['prioritize', 'status', 'result', 'action'])
            ->make(true);
    }
}
