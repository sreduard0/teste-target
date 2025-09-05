<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove non-numeric characters
        $cpf = preg_replace('/\D/', '', $value);

        // Basic format validation (XXX.XXX.XXX-XX or XXXXXXXXXXX)
        if (!preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $cpf)) {
            $fail('O campo :attribute deve ter um formato de CPF válido (XXX.XXX.XXX-XX ou XXXXXXXXXXX).');
        }

        // Add more robust CPF validation (checksum) here for a production-ready solution.
        // For this audit, a basic format check is sufficient to demonstrate the concept.
    }
}