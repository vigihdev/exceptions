<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class DateException extends ValidationException
{

    public const DATE_FORMAT = 'Y-m-d';

    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public static function emptyValue(string $field): static
    {
        return new self(
            message: sprintf("Field '%s' cannot be blank.", $field),
            code: 400,
            field: $field,
            value: null,
            context: [
                'field' => $field,
                'value' => null,
            ],
            solutions: [
                sprintf("Field '%s' must be a valid date. Example: 2020-08-08 or 2020-08-08 05:05:03", $field),
                "Check if the date is in the past.",
            ],
        );
    }

    public static function tooEarly(string $field, string $value, string $minDate): static
    {

        return new self(
            message: sprintf("Field '%s' value '%s' must be greater than date %s", $field, $value, $minDate),
            field: $field,
            value: $value,
            code: 400,
            context: [
                'min_date' => $minDate,
                'actual' => $value,
                'field' => $field,
                'value' => $value
            ],
            solutions: [
                sprintf("Field '%s' value '%s' must be after %s", $field, $value, $minDate),
                "Check if the date is in the past.",
            ],
        );
    }

    public static function tooLate(string $field, string $value, string $actual): static
    {

        return new self(
            message: sprintf("Field '%s' value '%s' must be less than %s", $field, $value, $actual),
            field: $field,
            value: $value,
            code: 400,
            context: [
                'actual' => $value,
                'field' => $field,
                'value' => $value
            ],
            solutions: [
                sprintf("Field '%s' value '%s' must be before %s", $field, $value, $actual),
                "Check if the date is in the future.",
            ],
        );
    }

    public static function invalidFormat(string $field, mixed $value): static
    {
        return new self(
            message: sprintf("'%s' is not a valid date for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the date format. It should be 'Y-m-d' or 'Y-m-d H:i:s'.",
            ],
        );
    }

    public static function invalidDate(string $field, string $value): static
    {
        return new self(
            message: sprintf("Date '%s' is not a valid date for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the date format. It should be 'Y-m-d'.",
                "Example 2020-09-09"
            ],
        );
    }

    public static function invalidDateTime(string $field, string $value): static
    {
        return new self(
            message: sprintf("Date '%s' is not a valid date time for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the date time format. It should be 'Y-m-d H:i:s'.",
                "Check if the date time is in the past.",
                "Example 2020-08-08 05:05:03"
            ],
        );
    }

    public static function outOfRange(
        string $field,
        string $value,
        string $minDate,
        string $maxDate
    ): static {
        return new self(
            message: sprintf(
                "The date '%s' for field '%s' is outside the allowed range (%s – %s).",
                $value,
                $field,
                $minDate,
                $maxDate
            ),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
                'min_date' => $minDate,
                'max_date' => $maxDate,
            ],
            solutions: [
                sprintf("Please enter a date between %s and %s for field '%s'.", $minDate, $maxDate, $field),
            ],
        );
    }
}
