<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class FileException extends ValidationException
{

    public static function invalidMimeType(string $field, string $value, string $mimeType): static
    {
        return new self(
            message: sprintf('File %s has invalid mime type. Expected %s.', $value, $mimeType),
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
                'mimeType' => $mimeType,
            ],
            code: 400,
            solutions: [
                sprintf("Check the mime type of file '%s'.", $value),
                sprintf("Check the extension of file '%s'.", $value),
            ],
        );
    }

    public static function invalidExtension(string $field, string $value, string $extension): static
    {
        return new self(
            message: sprintf('File %s has invalid extension. Expected %s.', $value, $extension),
            field: $field,
            value: $value,
            context: [
                'field' => $field,
                'value' => $value,
                'extension' => $extension,
            ],
            code: 400,
            solutions: [
                sprintf("Check the extension of file '%s'.", $value),
                sprintf("Check the mime type of file '%s'.", $value),
            ],
        );
    }

    public static function invalidTooSmall(int $min, int $actual = 0, string $field = ''): static
    {
        $name = $field !== '' ? "for field '{$field}'" : '';

        return new self(
            message: sprintf('File %s is too small. Minimum size is %d bytes', $name, $min),
            context: [
                'min' => $min,
                'actual' => $actual,
                'field' => $field,
            ],
            code: 400,
            solutions: [
                'Increase the file size to at least ' . $min . ' bytes',
            ],
        );
    }

    /**
     * Create exception for file that is too big
     */
    public static function invalidTooBig(int $max, int $actual = 0, string $field = ''): static
    {
        $name = $field !== '' ? "for field '{$field}'" : '';

        return new self(
            message: sprintf('File %s is too big. Maximum size is %d bytes', $name, $max),
            context: [
                'max' => $max,
                'actual' => $actual,
                'field' => $field,
            ],
            code: 400,
            solutions: [
                'Reduce the file size to at most ' . $max . ' bytes',
            ],
        );
    }
}
