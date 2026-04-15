<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    public function index()
    {
        return view('branches.list');
    }

    public function getData(Request $request)
    {
        if (!$request->ajax()) return;

        $query = Branch::with('manager')->withCount('users')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('status', fn($r) => $r->status == 0
                ? '<span class="badge-result badge-hoanthanh">Đang hoạt động</span>'
                : '<span class="badge-result badge-fail">Tạm đóng</span>')
            ->addColumn('manager_name', fn($r) => $r->manager?->name ?? '—')
            ->addColumn('action', fn($r) => '
                <div class="d-flex gap-1">
                    <button class="btn-action btn-edit" onclick="openEdit(' . $r->id . ')" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="' . route('branches.delete', $r->id) . '" method="POST" class="form-delete-branch">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="button" class="btn-action btn-del btn-delete-branch" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            ')
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getManagers()
    {
        $users = \App\Models\User::select('id', 'name', 'email')
            ->where('status', 0)
            ->orderBy('name')
            ->get();
        return response()->json($users);
    }

    public function get(Branch $branch)
    {
        return response()->json($branch->load('manager'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string', 'max:500'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status'     => ['nullable', 'integer'],
        ], ['name.required' => 'Tên chi nhánh là bắt buộc']);

        if (isset($data['manager_id']) && $data['manager_id'] === '') $data['manager_id'] = null;

        Branch::create($data);
        return response()->json(['success' => true, 'message' => 'Thêm chi nhánh thành công!']);
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string', 'max:500'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status'     => ['nullable', 'integer'],
        ], ['name.required' => 'Tên chi nhánh là bắt buộc']);

        $data['manager_id'] = empty($data['manager_id']) ? null : $data['manager_id'];

        $branch->update($data);
        return response()->json(['success' => true, 'message' => 'Cập nhật chi nhánh thành công!']);
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.list')->with('success', 'Xóa chi nhánh thành công!');
    }
}
