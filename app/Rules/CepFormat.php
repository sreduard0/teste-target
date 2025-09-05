<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CepFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove non-numeric characters
        $cep = preg_replace('/\D/', '', $value);

        // Basic format validation (XXXXX-XXX or XXXXXXXXX)
        if (!preg_match('/^\d{5}-?\d{3}$/', $cep)) {
            $fail('O campo :attribute deve ter um formato de CEP válido (XXXXX-XXX ou XXXXXXXXX).');
        }
    }
}