<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class JsonException extends ValidationException
{

    public static function emptyValue(string $field): static
    {
        return new self(
            message: sprintf("'%s' cannot be blank.", $field),
            code: 400,
            field: $field,
            value: null,
            context: [
                'field' => $field,
                'value' => null,
            ],
            solutions: [
                sprintf("Provide a valid JSON string for field '%s'.", $field),
                "Example value: {\"key\": \"value\"}",
            ],
        );
    }

    public static function invalidJson(string $field, string $value): static
    {
        return new self(
            message: sprintf("'%s' is not a valid JSON for field '%s'.", $value, $field),
            code: 400,
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the JSON format. It should be valid JSON.",
                "Verify the JSON string against a JSON schema if available.",
            ],
        );
    }
}
