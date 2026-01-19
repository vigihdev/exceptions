<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class DirectoryException extends ValidationException
{


    public static function emptyValue(string $field, ?string $value = null): self
    {
        return new self(
            message: sprintf("%s is empty: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                sprintf("Check the %s value and make sure it is not empty", $field),
                'Check the directory path and make sure it is not empty',
            ]
        );
    }

    public static function exist(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s already exists: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 409,
            solutions: [
                sprintf("Check the %s value and make sure it is not already in use", $field),
                'Check the directory path and make sure it is not already in use',
            ]
        );
    }

    public static function notExist(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s does not exist: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 409,
            solutions: [
                sprintf("Check the %s value and make sure it is not already in use", $field),
                'Check the directory path and make sure it is not already in use',
            ]
        );
    }

    public static function notFound(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s not found: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 404,
            solutions: [
                'Check the directory path and make sure the directory exists',
                'Create the directory if it does not exist'
            ]
        );
    }

    public static function notReadable(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s not readable: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                sprintf("Check the %s permissions: chmod +r %s", $field, basename($value)),
                sprintf("Check the %s ownership", $field),
            ]
        );
    }

    public static function notWritable(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s not writable: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 403,
            solutions: [
                sprintf("Check the %s permissions: chmod +w %s", $field, basename($value)),
                sprintf("Check the %s ownership", $field),
            ]
        );
    }

    public static function alreadyExists(string $field, string $value): self
    {
        return new self(
            message: sprintf("%s already exists: %s", $field, $value),
            context: [
                'field' => $field,
                'value' => $value,
            ],
            code: 409,
            solutions: [
                'Use a different directory path',
                'Delete the existing directory first',
                'Use the --overwrite flag if available'
            ]
        );
    }

    public static function notEmpty(string $field, string $value, int $fileCount = 0): self
    {
        $message = sprintf("%s not empty: %s", $field, $value);
        if ($fileCount > 0) {
            $message .= sprintf(" (%d file/folder)", $fileCount);
        }

        return new self(
            message: $message,
            context: [
                'field' => $field,
                'value' => $value,
                'file_count' => $fileCount,
            ],
            code: 409,
            solutions: [
                'Empty the directory first',
                'Use the --force flag to delete the directory and its contents'
            ]
        );
    }

    public static function createFailed(string $field, string $value, string $error = ''): self
    {
        $message = sprintf("Failed to create %s: %s", $field, $value);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'field' => $field,
                'value' => $value,
                'error' => $error,
            ],
            code: 409,
            solutions: [
                'Check the parent directory permissions',
                'Check if the path is valid',
                'Check disk space'
            ]
        );
    }

    public static function deleteFailed(string $field, string $value, string $error = ''): self
    {
        $message = sprintf("Failed to delete %s: %s", $field, $value);
        if ($error) {
            $message .= ". Error: " . $error;
        }

        return new self(
            message: $message,
            context: [
                'field' => $field,
                'value' => $value,
                'error' => $error,
            ],
            code: 409,
            solutions: [
                'Check the directory permissions',
                'Make sure the directory is not in use',
                'Empty the directory first'
            ]
        );
    }

    public static function invalidPath(string $field, string $value, string $reason = ''): self
    {
        $message = sprintf("Invalid %s path: %s", $field, $value);
        if ($reason) {
            $message .= ". " . $reason;
        }

        return new self(
            message: $message,
            context: [
                'field' => $field,
                'value' => $value,
                'reason' => $reason,
            ],
            code: 400,
            solutions: [
                'Use an absolute or relative path that is valid',
                'Avoid special characters in the path',
                'Check the path length (max 255 characters)'
            ]
        );
    }

    public static function cannotScan(string $field, string $value): self
    {
        return new self(
            message: sprintf("Cannot scan %s %s.", $field, $value),
            code: 403,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check if the directory exists and is readable",
                "Check if you have permission to scan the directory",
                "Check for any file system issues",
                "Try again after some time"
            ],
        );
    }

    public static function cannotCreate(string $field, string $value): self
    {
        return new self(
            message: sprintf("Cannot create %s %s.", $field, $value),
            code: 403,
            context: [
                'field' => $field,
                'value' => $value,
            ],
            solutions: [
                "Check if the parent directory exists and is writable",
                "Check if you have permission to create the directory",
                "Check for any file system issues",
                "Try again after some time"
            ],
        );
    }
}
