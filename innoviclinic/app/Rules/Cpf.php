<?php

namespace App\Rules;

use Closure;
use App\Helpers\Utils;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;
        if (!is_string($value)) {
            $valid = false;
        }

        if ($valid) {
            $valid = Utils::cpfValido($value);
        }

        if (!$valid) {
            $fail('O campo :attribute possui um formato inválido.');
        }
    }
}
