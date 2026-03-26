<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(){
        return view('users.list');
    }

    public function getUsersData(){
        $users = User::with('part', 'position', 'team', 'typeAccount', 'branch');

        return DataTables::of($users)
        ->addIndexColumn()
        ->editColumn('sex', function($row){
            return $row->sex == 0 ? 'nam' : 'nữ';
        })
        ->make(true);
    }
}