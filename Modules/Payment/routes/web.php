<?php

// =====================================================================
// PAYMENT MODULE - ROUTES PAYOS
// Mục đích: Xử lý thanh toán PayOS cho 2 luồng:
//   1. Gia hạn shop (PaymentController + PaymentHistoryController)
//   2. Thanh toán đơn hàng online (PaymentoderController)
// =====================================================================

// --- 1. Gia hạn shop qua PayOS (dành cho seller đăng nhập) ---
Route::middleware(['auth'])->group(function () {
    Route::post('payos/create-payment-link', '\App\Modules\Payment\Controllers\PaymentController@createPaymentLink')->name('create.payment');
});

// Callback sau khi gia hạn shop thành công / hủy
Route::get('payos/succes',  '\App\Modules\Payment\Controllers\PaymentHistoryController@succesPayment')->name('payment.success');
Route::get('payos/cancel',  '\App\Modules\Payment\Controllers\PaymentHistoryController@cancelPayment')->name('payment.cancel');

// --- 2. Thanh toán đơn hàng online qua PayOS ---
Route::post('/thanh-toan-oder', '\App\Modules\Payment\Controllers\PaymentoderController@createPaymentLink')->name('thanh-toan-oder');
Route::get('/payos/success',    '\App\Modules\Payment\Controllers\PaymentoderController@paymentSuccess');
Route::get('/payos/cancel',     '\App\Modules\Payment\Controllers\PaymentoderController@paymentCancel');

// Lưu dữ liệu form vào session trước khi redirect sang PayOS
Route::post('/api/save-order-session', function (\Illuminate\Http\Request $request) {
    session()->put('order_request_data', $request->all());
    return response()->json(['status' => 'ok']);
});
