<?php

namespace Modules\NhanSu\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facilicity;
use Illuminate\Support\Facades\Storage;

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

    public function getFacility($id)
    {
        $facility = Facilicity::findOrFail($id);
        return response()->json($facility);
    }

    public function updateFacility(Request $request, $id)
    {
        $facility = Facilicity::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
            'day' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'number' => $request->number,
            'status' => $request->status,
            'day' => $request->day,
            'note' => $request->note,
        ];

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            $data['image'] = $request->file('image')->store('facilities', 'public');
        }

        $facility->update($data);

        return response()->json(['success' => true, 'message' => 'Đã cập nhật cơ sở vật chất thành công.']);
    }

    public function destroyFacility($id)
    {
        $facility = Facilicity::findOrFail($id);
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }
        $facility->delete();

        return redirect()->back()->with('success', 'Đã xoá cơ sở vật chất.');
    }

    public function timekeeping(Request $request)
    {
        $userType = $request->get('user_type');

        // Lấy danh sách users (trừ Super Admin)
        $query = \App\Models\User::with(['position', 'typeAccount', 'branch'])
            ->where('status', 0);

        if ($userType === 'manager') {
            $query->managers();
        } elseif ($userType === 'staff') {
            $query->staff();
        }

        $users = $query->get()
            ->filter(function ($user) {
                return !$user->isSuperAdmin();
            })
            ->values();

        // Lấy lịch sử chấm công hôm nay
        $todayTimesheets = \App\Models\Timesheet::whereDate('day', date('Y-m-d'))
            ->get()
            ->keyBy('user_id');

        return view('nhansu::cham-cong', compact('users', 'todayTimesheets'));
    }

    public function storeTimekeeping(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'day' => 'required|date',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Chuẩn bị dữ liệu
        $data = [
            'user_id' => $user->id,
            'day' => $request->day,
        ];

        if ($user->isManagerSalary()) {
            // Đối với Quản lý
            $data['shift'] = 'Fulltime';
            $data['number'] = 1; // 1 ngày công
            $data['hour'] = 8;
            $data['note'] = $request->note; // Trách nhiệm quản lý
        } else {
            // Đối với Nhân viên
            $request->validate([
                'shift' => 'required|in:Sáng,Chiều,Tối',
                'hour' => 'required|numeric|min:0.5',
            ]);
            $data['shift'] = $request->shift;
            $data['hour'] = $request->hour;
            $data['number'] = $request->hour / 8; // Tính ngày công tương đối
            $data['note'] = $request->note;
        }

        // Lưu hoặc cập nhật chấm công của ngày hôm đó
        \App\Models\Timesheet::updateOrCreate(
            [
                'user_id' => $user->id,
                'day' => $request->day,
                'shift' => $data['shift'] // Cho phép 1 người có nhiều ca trong ngày
            ],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Đã chấm công thành công.']);
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
