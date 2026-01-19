<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class EmailException extends ValidationException
{

    public static function exist(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' already exists for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Choose a different email.",
                "Verify that the email address is correct.",
            ],
        );
    }

    public static function notExist(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' does not exist for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the email address. It may be misspelled or not registered.",
                "Verify that the email address is correct.",
            ],
        );
    }

    public static function emptyEmail(string $field): self
    {
        return new self(
            message: sprintf("Email for field '%s' cannot be empty.", $field),
            code: 400,
            field: $field,
            context: [
                'field' => $field,
                'value' => null,
            ],
            solutions: [
                sprintf("Provide a valid email for field '%s'.", $field),
                "Example value: example@domain.com",
            ],
        );
    }

    public static function invalidFormat(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' has an invalid format for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the email format. It should be 'example@domain.com'.",
                "Example value: example@domain.com",
            ],
        );
    }

    public static function invalidEmail(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' is not a valid email for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the email format. It should be 'example@domain.com'.",
            ],
        );
    }

    public static function duplicateEmail(string $field, string $value): self
    {
        return new self(
            message: sprintf("Email '%s' is already registered for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Use a different email address.",
                "Check if the email address is registered with another account.",
                "Contact the administrator if you believe this is an error.",
            ],
        );
    }
}
