<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
/**
 * Validator for
 * src/Validator/Constraints/PolishAlphanumeric
 */
class PolishAlphanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PolishAlphanumeric) {
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if (!preg_match('/^[a-zĄĆĘŁÓŚŻŹąćęłóśżź ]+$/iu', $value, $matches)) {
        //if (!preg_match('/^[a-zĄĆĘŁÓŚŻŹąćęłóśżź ]+$/iu', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}