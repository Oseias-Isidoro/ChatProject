<?php

namespace App\Http\Requests;

use App\Models\Number;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreNumberRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('store', Number::class);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:12', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique('numbers', 'phone_number')->where(function ($query) {
                return $query->where('account_id', Auth::user()->account_id);
            })->withoutTrashed()]
        ];
    }
}
