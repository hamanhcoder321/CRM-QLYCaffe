<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use App\Models\Drink;

class HomeController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::with(['part', 'position', 'user'])
            ->where('status', Recruitment::STATUS_RECRUITING)
            ->where('number', '>', 0)
            ->latest()
            ->limit(6)
            ->get();

        $drinks = Drink::where('status', 1)
            ->latest()
            ->limit(5)
            ->get();

        return view('home.index', compact('recruitments', 'drinks'));
    }
}
