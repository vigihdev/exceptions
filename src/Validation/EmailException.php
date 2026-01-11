<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class EmailException extends ValidationException
{

    public static function invalidEmail(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' is not a valid email for field '%s'.", $value, $field),
            code: 400,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the email format. It should be 'example@domain.com'.",
            ],
        );
    }
}
