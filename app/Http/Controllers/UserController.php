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
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return DataTables::of($users)
        ->addIndexColumn()
        ->make(true);
    }
}
