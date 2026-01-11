<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class NumberException extends ValidationException
{

    /**
     * Create exception for number that is too small
     */
    public static function invalidTooSmall(int $min, int $actual = 0, string $attribute = ''): self
    {
        $name = $attribute !== '' ? "for {$attribute}" : '';

        return new self(
            message: sprintf(
                'Number %s is too small. Minimum value is %d.',
                $name,
                $min
            ),
            code: 400,
            field: $attribute,
            value: $actual,
            context: [
                'min' => $min,
                'actual' => $actual,
                'attribute' => $attribute,
            ],
            solutions: [
                'Increase the number to at least ' . $min,
            ],
        );
    }


    /**
     * Create exception for number that is too big
     */
    public static function invalidTooBig(int $max, int $actual = 0, string $attribute = ''): self
    {
        return new self(
            message: sprintf(
                'Number %s is too big. Maximum value is %d',
                $attribute,
                $max
            ),
            code: 400,
            field: $attribute,
            value: $actual,
            context: [
                'max' => $max,
                'actual' => $actual,
                'attribute' => $attribute,
                'value' => $actual
            ],
            solutions: [
                'Decrease the number to at most ' . $max
            ],
        );
    }
}
