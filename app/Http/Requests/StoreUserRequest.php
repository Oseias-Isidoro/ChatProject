<?php

namespace App\Http\Requests;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('create', User::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', Rule::in(UserRolesEnum::all())],
            'numbers' => ['required', 'array'],
            'numbers.*' => ['required', 'integer', 'min:1', 'distinct', 'exists:numbers,id'],
        ];
    }
}
