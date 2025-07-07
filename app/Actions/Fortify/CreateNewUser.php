<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::validate($input, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'password_confirmation' => 'required|same:password',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string|max:500',
            'birthday' => 'nullable|date|before:-5 years',
            'gender' => 'required|in:man,woman,other',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng. Vui lòng chọn email khác.',
            'password.required' => 'Vui lòng tạo mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu.',
            'password_confirmation.same' => 'Mật khẩu xác nhận không khớp với mật khẩu đã nhập.',
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các chữ số.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'birthday.date' => 'Ngày sinh không đúng định dạng.',
            'birthday.before' => 'Bạn phải từ 16 tuổi trở lên để đăng ký.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính được chọn không hợp lệ.',
        ]);

        return User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'name' => $input['name'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'birthday' => $input['birthday'],
            'gender' => $input['gender'],
            'status' => 'active',
        ]);
    }
}
