<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class NumberException extends ValidationException
{
    /**
     * Thrown when the provided value is not a valid number.
     */
    public static function invalidType(string $field, mixed $value): self
    {
        return new self(
            message: sprintf("The value '%s' for field '%s' is not a valid number.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Ensure the input is numeric (e.g., 42, -10, 3.14).",
                "Remove non-numeric characters like letters or symbols.",
            ],
        );
    }

    /**
     * Thrown when the number is below the allowed minimum.
     */
    public static function tooSmall(float $min, float $actual, string $field): self
    {
        return new self(
            message: sprintf("The value '%g' for field '%s' is too small. Minimum allowed is %g.", $actual, $field, $min),
            code: 400,
            field: $field,
            value: $actual,
            context: [
                'min' => $min,
                'actual' => $actual,
                'field' => $field,
            ],
            solutions: [
                sprintf("Increase the value of '%s' to at least %g.", $field, $min),
            ],
        );
    }

    /**
     * Thrown when the number exceeds the allowed maximum.
     */
    public static function tooLarge(float $max, float $actual, string $field): self
    {
        return new self(
            message: sprintf("The value '%g' for field '%s' is too large. Maximum allowed is %g.", $actual, $field, $max),
            code: 400,
            field: $field,
            value: $actual,
            context: [
                'max' => $max,
                'actual' => $actual,
                'field' => $field,
            ],
            solutions: [
                sprintf("Reduce the value of '%s' to %g or below.", $field, $max),
            ],
        );
    }

    /**
     * Thrown when the field is required but empty or null.
     */
    public static function emptyValue(string $field): self
    {
        return new self(
            message: sprintf("The field '%s' is required and cannot be empty.", $field),
            code: 400,
            field: $field,
            value: null,
            context: [
                'field' => $field,
                'value' => null,
            ],
            solutions: [
                sprintf("Provide a valid numeric value for field '%s'.", $field),
            ],
        );
    }

    /**
     * Thrown when a decimal value is provided but only integers are allowed.
     */
    public static function notInteger(string $field, float $actual): self
    {
        return new self(
            message: sprintf("The value '%g' for field '%s' must be an integer (no decimals).", $actual, $field),
            code: 400,
            field: $field,
            value: $actual,
            context: [
                'field' => $field,
                'actual' => $actual,
            ],
            solutions: [
                sprintf("Round or truncate the value of '%s' to a whole number (e.g., %d).", $field, (int) $actual),
                "If decimals are needed, update the validation rule to accept floats.",
            ],
        );
    }
}
