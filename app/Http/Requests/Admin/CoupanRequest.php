<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class CoupanRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $unless = "change_status";
        return [
            'code'               =>  ["required_unless:action,$unless", "max:10", Rule::unique('coupans')->ignore($this->coupan)],
            'percentage'         =>  'required_unless:action,'.$unless,
        ];
    }
}
