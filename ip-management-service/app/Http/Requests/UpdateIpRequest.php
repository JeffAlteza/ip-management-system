<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ip_address' => ['sometimes', 'ip', Rule::unique('ips', 'ip_address')->ignore($this->route('ip'))],
            'label' => ['sometimes', 'string', 'max:255'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
