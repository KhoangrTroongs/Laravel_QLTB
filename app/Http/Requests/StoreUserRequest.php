<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|string|max:50|unique:users,employee_id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'status'      => 'required|boolean',
            'password'    => 'required|string|min:6',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Mã nhân viên không được để trống.',
            'employee_id.unique'   => 'Mã nhân viên đã tồn tại.',
            'name.required'        => 'Tên nhân viên không được để trống.',
            'email.required'       => 'Email không được để trống.',
            'email.unique'         => 'Email đã tồn tại.',
            'password.required'    => 'Mật khẩu không được để trống.',
            'password.min'         => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'avatar.image'         => 'Avatar phải là định dạng hình ảnh.',
            'avatar.max'           => 'Dung lượng ảnh không được quá 2MB.',
        ];
    }
}
