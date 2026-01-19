<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class FileException extends ValidationException
{

    public static function notExist(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s does not exist: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 404,
            solutions: [
                'Check the filepath and make sure the file exists',
                'Create the file if it does not exist'
            ]
        );
    }

    public static function exist(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s already exists: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 409,
            solutions: [
                'Check the filepath and make sure the file does not exist',
                'Rename the file if it exists'
            ]
        );
    }

    public static function notFound(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s not found: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 404,
            solutions: [
                'Check the filepath and make sure the file exists',
                'Create the file if it does not exist'
            ]
        );
    }

    public static function notReadable(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s not readable: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                'Check the file permissions: chmod +r ' . basename($value),
                'Check the file ownership'
            ]
        );
    }

    public static function notWritable(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s not writable: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                'Check the file permissions: chmod +w ' . basename($value),
                'Check the file ownership'
            ]
        );
    }

    public static function notExecutable(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s not executable: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                'Check the file permissions: chmod +x ' . basename($value),
                'Check the file ownership'
            ]
        );
    }

    public static function notFile(string $field, string $value): static
    {
        return new self(
            message: sprintf("File %s is not a valid file: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 404,
            solutions: [
                'Check the filepath and make sure the file exists',
                'Create the file if it does not exist'
            ]
        );
    }

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

    public static function tooSmall(int $min, string $field, int $actual = 0): static
    {
        $actualSize = $actual !== 0 ? " (actual size is {$actual} bytes)" : '';

        return new self(
            message: sprintf('File %s is too small. Minimum size is %d bytes%s', $field, $min, $actualSize),
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
    public static function tooBig(int $max, string $field, int $actual = 0): static
    {
        $actualSize = $actual !== 0 ? " (actual size is {$actual} bytes)" : '';

        return new self(
            message: sprintf('File %s is too big. Maximum size is %d bytes%s', $field, $max, $actualSize),
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
