<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use function Laravel\Prompts\warning;

class UserController extends Controller
{
    public function index()
    {
        return view('users.list');
    }

    public function getUsersData()
    {
        $users = User::with('part', 'position', 'team', 'typeAccount', 'branch');

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('sex', function ($row) {
                return $row->sex == 0 ? 'nam' : 'nữ';
            })
            ->editColumn('part_id', function ($row) {
                return $row->part->name ?? '';
            })
            ->editColumn('position_id', function ($row) {
                return $row->position->name ?? '';
            })
            ->editColumn('type_work', function ($row) {
                return $row->type_work == 0 ? 'Fulltime' : 'Partime';
            })
            ->editColumn('team_id', function ($row) {
                return $row->team->name ?? '';
            })
            ->editColumn('status', function ($row) {
                return $row->status == 0 ? 'Đang làm' : 'nghỉ việc';
            })
            ->editColumn('type_accounts_id', function ($row) {
                return $row->typeAccount->name ?? '';
            })
            ->editColumn('branch_name', function ($row) {
                return $row->branch->name ?? '';
            })

            ->editColumn('action', function ($row) {
                return '<div class="btn btn-warning"><i class="fas fa-edit"></i>sửa</div>
<div class="btn btn-danger"><i class="fas fa-trash"></i>xóa</div>';
            })
            ->make(true);
    }
}

