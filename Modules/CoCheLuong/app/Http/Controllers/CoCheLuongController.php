<?php

namespace Modules\CoCheLuong\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SalaryMechanism;

class CoCheLuongController extends Controller
{
    /**
     * Hiển thị danh sách nhân sự kèm cơ chế lương hiện tại
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403, 'Bạn không có quyền truy cập cơ chế lương.');

        $userType = $request->get('user_type');

        $query = User::with(['position', 'typeAccount', 'branch'])
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

        $mechanisms = SalaryMechanism::whereIn('user_id', $users->pluck('id'))->get()->keyBy('user_id');

        return view('cocheluong::index', compact('users', 'mechanisms'));
    }

    /**
     * Lấy thông tin cơ chế lương của 1 user cụ thể
     */
    public function getMechanism($userId)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403, 'Bạn không có quyền truy cập cơ chế lương.');

        $mechanism = SalaryMechanism::where('user_id', $userId)->first();
        if (!$mechanism) {
            return response()->json([
                'salary' => '',
                'responsibility' => '',
                'enthusiasm' => '',
                'support' => '',
                'salary_keep' => '',
                'salary_need_keep' => '',
            ]);
        }
        return response()->json($mechanism);
    }

    /**
     * Cập nhật cơ chế lương
     */
    public function updateMechanism(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403, 'Bạn không có quyền truy cập cơ chế lương.');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary' => 'required|numeric|min:0',
            'responsibility' => 'nullable|numeric|min:0',
            'enthusiasm' => 'nullable|numeric|min:0',
            'support' => 'nullable|numeric|min:0',
            'salary_keep' => 'nullable|numeric|min:0',
            'salary_need_keep' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $data = [
            'salary' => $request->salary,
            'responsibility' => $request->responsibility ?? 0,
            'enthusiasm' => $request->enthusiasm ?? 0,
            'support' => $request->support ?? 0,
            'salary_keep' => $request->salary_keep ?? 0,
            'salary_need_keep' => $request->salary_need_keep ?? 0,
        ];

        if ($data['salary_keep'] > 0 && $data['salary_need_keep'] > 0) {
            $data['month_keep'] = ceil($data['salary_need_keep'] / $data['salary_keep']);
        } else {
            $data['month_keep'] = 0;
            $data['salary_keep'] = 0;
            $data['salary_need_keep'] = 0;
        }

        SalaryMechanism::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Lưu cấu hình cơ chế lương thành công!']);
    }
}
