<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('store', Contact::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone_number' => ['required', 'string', 'max:14', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique('contacts', 'phone_number')->where(function ($query) {
                return $query->where('account_id', Auth::user()->account_id);
            })->withoutTrashed()]
        ];
    }
}
