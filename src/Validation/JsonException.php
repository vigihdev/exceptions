<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class JsonException extends ValidationException
{

    public static function invalidJson(string $field, string $value): self
    {
        return new self(
            message: sprintf("JSON '%s' is not a valid JSON for field '%s'.", $value, $field),
            code: 400,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check the JSON format. It should be valid JSON.",
            ],
        );
    }
}
