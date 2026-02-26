<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ip_address' => ['required', 'ip', 'unique:ips,ip_address'],
            'label' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
