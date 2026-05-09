<?php

// =====================================================================
// AFFILATE MODULE - ROUTES
// Chú ý: Module này KHÔNG có views. Chỉ giữ lại routes liên quan đến
// các chức năng còn hoạt động và tích hợp PayOS.
// =====================================================================

// Kiểm tra đơn hàng chưa đọc (dùng cho shop seller)
Route::get('/notification/unread-orders', function () {
    if (!auth()->guard('users')->check()) {
        return response()->json(['unread' => 0]);
    }

    try {
        $sellerId = auth()->guard('users')->id();
        $shopIds = \App\Modules\Affilate\Models\Shop::where('user_id', $sellerId)->pluck('id');
        $unread = \App\Modules\Affilate\Models\Bill::whereIn('shop_id', $shopIds)
            ->where('is_read', 0)
            ->whereIn('status', [0, 1, 2])
            ->count();
        return response()->json(['unread' => $unread]);
    } catch (\Exception $e) {
        return response()->json(['unread' => 0]);
    }
});

// =====================================================================
// Các routes bên dưới đã bị VÔ HIỆU HÓA vì module không có views
// và controllers tương ứng đã bị xóa khỏi dự án
// =====================================================================

// Route::get('/', '...HomeController@getAll')   // Không có view Affilate.Frontend.Home.home
// Route::get('/shops/load-more', ...)
// Route::get('/sync-location', ...)
// Route::get('/get-districts', ...)
// Route::get('/get-wards', ...)
// Route::post('/calculate-fee', ...)
// ... (toàn bộ các routes Affilate khác đã xóa)