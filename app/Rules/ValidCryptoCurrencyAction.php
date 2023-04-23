<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCryptoCurrencyAction implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, config('crypto.exchangeActions'))) {
            $fail('The :attribute not correct.');
        }
    }
}
