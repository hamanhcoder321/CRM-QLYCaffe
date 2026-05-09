<?php

namespace App\Modules\Affilate\Request;

use Illuminate\Foundation\Http\FormRequest;

class ThanhToanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'   => 'required|array',
            'product_id.*' => 'exists:product,id',
            'soTien'       => 'required|numeric|min:0',
            'ptdv'         => 'nullable|string',
            'notes'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'  => 'Vui lòng chọn sản phẩm.',
            'product_id.*.exists'  => 'Sản phẩm không tồn tại.',
            'soTien.required'      => 'Vui lòng nhập số tiền.',
            'soTien.numeric'       => 'Số tiền phải là số.',
        ];
    }
}
