<?php

namespace App\Http\Requests;

use App\Rules\ValidCryptoCurrencyAction;
use App\Rules\ValidCryptoCurrencyPair;
use Illuminate\Foundation\Http\FormRequest;

class CalculatePriceRequest extends FormRequest
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
        return [
            'amount' => 'required|numeric|min:0',
            'action' => [new ValidCryptoCurrencyAction(), 'required'],
            'pair' => [new ValidCryptoCurrencyPair(), 'required']
        ];
    }
}
