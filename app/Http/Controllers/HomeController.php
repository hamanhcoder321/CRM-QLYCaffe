<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;

class HomeController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::with(['part', 'position', 'user'])
            ->latest()
            ->limit(6)
            ->get();

        return view('home.index', compact('recruitments'));
    }
}
