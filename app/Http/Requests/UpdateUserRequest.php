<?php

namespace App\Http\Requests;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class UpdateUserRequest extends FormRequest
{
    public function authorize(): \Illuminate\Auth\Access\Response
    {
        return Gate::authorize('update', $this->user);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user)],
            'password' => ['required', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', Rule::in(UserRolesEnum::all())],
            'numbers' => ['required', 'array'],
            'numbers.*' => ['required', 'integer', 'min:1', 'distinct', 'exists:numbers,id'],
        ];
    }
}
