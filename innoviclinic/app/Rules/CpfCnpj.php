<?php

namespace App\Rules;

use Closure;
use App\Helpers\Utils;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tipo = request()->input('tipo');
        $valid = true;
        $msg = '';
        if (!is_string($value) || empty($tipo)) {
            $valid = false;
        }

        if ($valid && $tipo == 'PF') {
            $valid = Utils::cpfValido($value);
        } else if ($valid && $tipo == 'PJ') {
            $valid = Utils::cnpjValido($value);
        }

        if (!$valid) {
            $fail('O campo :attribute possui um formato inválido.');
        }
    }
}
