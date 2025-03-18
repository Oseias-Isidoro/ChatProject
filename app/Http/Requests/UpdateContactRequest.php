<?php

namespace App\Http\Requests;

use App\Enums\UserRolesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole([UserRolesEnum::ADMIN->name, UserRolesEnum::OPERATOR->name]);
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone_number' => ['required', 'string', 'max:14', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique('contacts', 'phone_number')->where(function ($query) {
                return $query->where('account_id', Auth::user()->account_id);
            })->ignore($this->contact)]
        ];
    }
}
