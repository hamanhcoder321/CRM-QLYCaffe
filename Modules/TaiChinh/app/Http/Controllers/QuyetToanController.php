<?php

namespace Modules\TaiChinh\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SalaryMechanism;
use App\Models\Timesheet;
use App\Models\TotalFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuyetToanController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', date('m'));
        $year = (int) $request->get('year', date('Y'));
        $branchId = $request->get('branch_id', auth()->user()->branch_id);
        $userType = $request->get('user_type'); // manager, staff

        $query = User::with(['position', 'branch', 'typeAccount'])
            ->where('status', 0);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($userType === 'manager') {
            $query->managers();
        } elseif ($userType === 'staff') {
            $query->staff();
        }

        $users = $query->get()->filter(fn($u) => !$u->isSuperAdmin());

        $salaryData = [];
        foreach ($users as $user) {
            $salaryData[$user->id] = $this->calculateSalary($user, $month, $year);
        }

        $expenses = TotalFee::with(['typeFee', 'branch'])
            ->whereMonth('day', $month)
            ->whereYear('day', $year);
        
        if ($branchId) {
            $expenses->where('branch_id', $branchId);
        }
        $expenses = $expenses->orderBy('day', 'desc')->get();

        $branches = Branch::all();
        $selectedBranch = $branchId ? Branch::find($branchId) : null;

        return view('taichinh::quyet-toan.index', compact(
            'users', 'salaryData', 'expenses', 'month', 'year', 'branches', 'selectedBranch'
        ));
    }

    private function calculateSalary($user, $month, $year)
    {
        $mechanism = SalaryMechanism::where('user_id', $user->id)->first();
        if (!$mechanism) return null;

        $timesheets = Timesheet::where('user_id', $user->id)
            ->whereMonth('day', $month)
            ->whereYear('day', $year)
            ->get();

        $totalDays = $timesheets->sum('number');
        $totalHours = $timesheets->sum('hour');

        $baseSalary = 0;
        
        // Kiểm tra nếu là cấp nhận lương cứng (Quản lý/Giám đốc)
        if ($user->isManagerSalary()) {
            // Quản lý hưởng lương cứng chia cho 26 ngày công chuẩn
            // Nếu làm đủ 26 ngày hoặc hơn sẽ nhận đủ lương cứng
            $baseSalary = ($mechanism->salary / 26) * $totalDays; 
        } else {
            // Nhân viên bình thường tính lương theo giờ thực tế
            $baseSalary = $mechanism->salary * $totalHours;
        }

        $allowances = ($mechanism->responsibility ?? 0) + ($mechanism->enthusiasm ?? 0) + ($mechanism->support ?? 0);
        $keepSalary = $mechanism->salary_keep ?? 0;

        $finalSalary = $baseSalary + $allowances - $keepSalary;

        return (object) [
            'total_days' => $totalDays,
            'total_hours' => $totalHours,
            'agreement_salary' => $mechanism->salary,
            'base_salary' => $baseSalary,
            'allowances' => $allowances,
            'keep_salary' => $keepSalary,
            'final_salary' => $finalSalary
        ];
    }

    public function getDetail(Request $request, $userId)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $timesheets = Timesheet::where('user_id', $userId)
            ->whereMonth('day', $month)
            ->whereYear('day', $year)
            ->orderBy('day', 'asc')
            ->get();
            
        return response()->json($timesheets);
    }
}
