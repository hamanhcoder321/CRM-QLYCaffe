<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Team;
use App\Models\Type_account;
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


    public function getFilters(){
        $status_f = collect([
            ['id' => 1, 'text' => 'Đã nghỉ'],
            ['id' => 0, 'text' => 'Đang làm']
        ]);

        $part_f = Part::select('id', 'name as text')->orderBy('name')->get();
        $team_f = Team::select('id', 'name as text')->orderBy('name')->get();
        $role_f = Type_account::select('id', 'name as text')->orderBy('name')->get();

        return response()->json([
            'status_f' => $status_f,
            'part_f' => $part_f,
            'team_f' => $team_f,
            'role_f' => $role_f 
        ]);
    }

    public function getUsersData()
    {
        $users = User::leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->with('part', 'position', 'team', 'typeAccount')
            ->select('users.*', 'branches.name as branch_name');

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

            ->addColumn('action', function ($row) {
                return '<div class="btn btn-warning">Sửa</div>
                    <div class="btn btn-danger">Xóa</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

