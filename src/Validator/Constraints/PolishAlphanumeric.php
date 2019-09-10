<?php

// src/Validator/Constraints/PolishAlphanumeric.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Custom constraint with alphanumerical and polish characters
 */
class PolishAlphanumeric extends Constraint
{
    public $message = 'The string "{{ string }}" contains an illegal character: it can only contain letters or numbers.';
}