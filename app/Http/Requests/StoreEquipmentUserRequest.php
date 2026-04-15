<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'      => 'required|exists:users,id',
            'equipment_id' => 'required|exists:equipment,id',
            'ngaymuon'     => 'required|date',
            'status'       => 'required|in:0,1',
            'description'  => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'      => 'Vui lòng chọn nhân viên.',
            'user_id.exists'        => 'Nhân viên không tồn tại.',
            'equipment_id.required' => 'Vui lòng chọn thiết bị.',
            'equipment_id.exists'   => 'Thiết bị không tồn tại.',
            'ngaymuon.required'     => 'Ngày mượn không được để trống.',
            'status.required'       => 'Trạng thái không được để trống.',
        ];
    }
}
