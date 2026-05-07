<?php

namespace Modules\QuanLyChiTieu\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Atm;
use App\Models\Branch;
use App\Models\TotalFee;
use App\Models\TypeFee;
use Illuminate\Http\Request;

class ChiTieuController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->get('branch_id', auth()->user()->branch_id);
        
        $query = TotalFee::with(['typeFee', 'branch', 'atm'])->orderBy('day', 'desc');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $expenses = $query->get();
        $typeFees = TypeFee::all();
        $atms = Atm::all();
        $branches = Branch::all();

        return view('quanlychitieu::index', compact('expenses', 'typeFees', 'atms', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|date',
            'content' => 'required|string|max:255',
            'money' => 'required|numeric|min:0',
            'type_fee_id' => 'required|exists:type_fees,id',
            'atm_id' => 'nullable|exists:atms,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        TotalFee::create($request->all());

        return response()->json(['success' => true, 'message' => 'Đã thêm chi phí mới.']);
    }

    public function edit($id)
    {
        $expense = TotalFee::findOrFail($id);
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $expense = TotalFee::findOrFail($id);

        $request->validate([
            'day' => 'required|date',
            'content' => 'required|string|max:255',
            'money' => 'required|numeric|min:0',
            'type_fee_id' => 'required|exists:type_fees,id',
            'atm_id' => 'nullable|exists:atms,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $expense->update($request->all());

        return response()->json(['success' => true, 'message' => 'Đã cập nhật chi phí.']);
    }

    public function destroy($id)
    {
        TotalFee::destroy($id);
        return redirect()->back()->with('success', 'Đã xóa chi phí.');
    }
}
