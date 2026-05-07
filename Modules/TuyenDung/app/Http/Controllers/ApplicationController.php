<?php

namespace Modules\TuyenDung\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ListRecruitment;
use App\Mail\RecruitmentResultMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('tuyendung::Applications.index');
    }

    public function data(Request $request)
    {
        abort_unless($request->ajax(), 403);

        $query = ListRecruitment::with(['recruitment.position', 'recruitment.part'])->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('position_name', fn ($r) => $r->recruitment?->position?->name ?? '—')
            ->addColumn('part_name', fn ($r) => $r->recruitment?->part?->name ?? '—')
            ->editColumn('status', function ($r) {
                return match ((int) $r->status) {
                    0 => '<span class="badge badge-info">Mới</span>',
                    1 => '<span class="badge badge-success">Đạt</span>',
                    2 => '<span class="badge badge-danger">Không đạt</span>',
                    default => '—',
                };
            })
            ->editColumn('created_at', fn ($r) => $r->created_at->format('d/m/Y H:i'))
            ->addColumn('action', function ($r) {
                if ($r->status != 0) return '—';
                
                return '<button class="btn btn-sm btn-success mr-1" onclick="updateStatus(' . $r->id . ', 1)" title="Duyệt / Đạt">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="updateStatus(' . $r->id . ', 2)" title="Không đạt">
                            <i class="fas fa-times"></i>
                        </button>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function updateStatus(Request $request, $id)
    {
        $application = ListRecruitment::with('recruitment.position')->findOrFail($id);
        $status = $request->status;

        $application->update([
            'status' => $status,
            'result' => $status == 1 ? 1 : 0
        ]);

        // Gửi mail thông báo
        try {
            Mail::to($application->email)->send(new RecruitmentResultMail($application));
        } catch (\Exception $e) {
            // Log error or ignore if mail not configured
            \Log::error('Mail error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái và gửi email thông báo.'
        ]);
    }
}
