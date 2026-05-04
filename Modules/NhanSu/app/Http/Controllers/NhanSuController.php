<?php

namespace Modules\NhanSu\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facilicity;

class NhanSuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('nhansu::index');
    }

    public function facilities()
    {
        $facilities = Facilicity::orderBy('id', 'desc')->get();
        return view('nhansu::co-so-vat-chat', compact('facilities'));
    }

    public function storeFacility(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'day' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('facilities', 'public');
        }

        Facilicity::create([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'number' => $request->number,
            'status' => $request->status,
            'day' => $request->day,
            'note' => $request->note,
        ]);

        return response()->json(['success' => true, 'message' => 'Đã thêm cơ sở vật chất mới.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nhansu::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('nhansu::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('nhansu::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }
}
