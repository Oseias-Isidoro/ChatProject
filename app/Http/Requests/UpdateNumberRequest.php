<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateNumberRequest extends FormRequest
{
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->number);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:12', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique('numbers', 'phone_number')
                ->where('account_id', $this->number->account_id)
                ->whereNull('deleted_at')
                ->ignore($this->number)
            ],
        ];
    }
}
