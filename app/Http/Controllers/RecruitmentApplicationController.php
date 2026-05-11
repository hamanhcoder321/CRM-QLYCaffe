<?php

namespace App\Http\Controllers;

use App\Models\ListRecruitment;
use Illuminate\Http\Request;

class RecruitmentApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'recruitment_id' => 'required|exists:recruitments,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        ListRecruitment::create([
            'recruitment_id' => $request->recruitment_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'status' => 0, // Mới
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ứng tuyển thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.'
        ]);
    }
}
