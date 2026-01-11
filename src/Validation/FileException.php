<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class FileException extends ValidationException
{

    public static function invalidMimeType(string $file, string $mimeType): static
    {
        return new self(
            message: sprintf(
                'File %s has invalid mime type. Expected %s.',
                $file,
                $mimeType
            ),
            context: [
                'file' => $file,
                'mimeType' => $mimeType,
            ],
            code: 400,
            solutions: [
                'Check the file mime type.',
            ],
        );
    }

    public static function invalidExtension(string $file, string $extension): static
    {
        return new self(
            message: sprintf(
                'File %s has invalid extension. Expected %s.',
                $file,
                $extension
            ),
            context: [
                'file' => $file,
                'extension' => $extension,
            ],
            code: 400,
            solutions: [
                'Check the file extension.',
            ],
        );
    }


    public static function invalidTooSmall(int $min, int $actual = 0, string $attribute = ''): static
    {
        $name = $attribute !== '' ? "for {$attribute}" : '';

        return new self(
            message: sprintf(
                'File %s is too small. Minimum size is %d bytes.',
                $name,
                $min
            ),
            context: [
                'min' => $min,
                'actual' => $actual,
                'attribute' => $attribute,
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
    public static function invalidTooBig(int $max, int $actual = 0, string $attribute = ''): static
    {
        return new self(
            message: sprintf(
                'File %s is too big. Maximum size is %d bytes',
                $attribute,
                $max
            ),
            context: [
                'max' => $max,
                'actual' => $actual,
                'attribute' => $attribute,
            ],
            code: 400,
            solutions: [
                'Reduce the file size to at most ' . $max . ' bytes',
            ],
        );
    }
}
