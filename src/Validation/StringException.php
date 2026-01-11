<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

final class StringException extends ValidationException
{

    public static function emptyValue(string $field): self
    {

        return new self(
            message: sprintf('%s cannot be empty', $field),
            field: $field,
            context: [
                'field' => $field,
                'value' => null
            ],
            code: 400,
            solutions: [
                'Ensure the string has a value',
                'Provide a valid string value'
            ],
        );
    }

    /**
     * Create exception for string that is too short
     */
    public static function tooShort(int $min, string $value, string $field): self
    {

        return new self(
            message: sprintf('%s minimum length is %d characters', $field, $min),
            field: $field,
            value: $value,
            code: 400,
            context: [
                'min' => $min,
                'actual' => strlen($value),
                'field' => $field,
                'value' => $value
            ],
            solutions: [
                sprintf('Extend the string to at least %d characters', $min),
            ],
        );
    }

    /**
     * Create exception for string that is too long
     */
    public static function tooLong(int $max, string $value, string $field): self
    {

        return new self(
            message: sprintf('%s maximum length is %d characters', $field, $max),
            field: $field,
            value: $value,
            code: 400,
            context: [
                'max' => $max,
                'actual' => strlen($value),
                'field' => $field,
                'value' => $value
            ],
            solutions: [
                sprintf('Shorten the string to maximum %d characters', $max),
                'Remove unnecessary characters or whitespace',
                'Consider using a substring or trimming the string',
            ],
        );
    }

    /**
     * Create exception for string that doesn't match expected value
     */
    public static function notEqual(string $expected, string $actual, string $field): self
    {

        return new self(
            message: sprintf('%s %s does not match %s', $field, $expected, $actual),
            field: $field,
            value: $actual,
            code: 400,
            context: [
                'expected' => $expected,
                'actual' => $actual,
                'field' => $field
            ],
            solutions: [
                'Ensure the string matches the expected value: ' . $expected,
                'Check for differences in case sensitivity or whitespace'
            ],
        );
    }

    /**
     * Create exception for string that doesn't match a pattern
     */
    public static function notMatch(string $pattern, string $actual, string $field): self
    {

        return new self(
            message: sprintf('%s %s does not match %s', $field, $pattern, $actual),
            field: $field,
            value: $actual,
            code: 400,
            context: [
                'pattern' => $pattern,
                'actual' => $actual,
                'field' => $field
            ],
            solutions: [
                'Ensure the string matches the pattern: ' . $pattern,
                'Check the string format'
            ],
        );
    }

    /**
     * Create exception for string that contains invalid characters
     */
    public static function invalidCharacters(string $invalidChars, string $actual, string $field): self
    {

        return new self(
            message: sprintf('%s contains invalid characters. Invalid: %s', $field, $invalidChars),
            field: $field,
            value: $actual,
            context: [
                'invalid_chars' => $invalidChars,
                'actual' => $actual,
                'field' => $field
            ],
            code: 400,
            solutions: [
                'Remove invalid characters: ' . $invalidChars,
                'Use only allowed characters'
            ],
        );
    }

    /**
     * Create exception for string that doesn't contain required characters
     */
    public static function missingRequiredCharacters(string $required, string $actual, string $field): self
    {
        return new self(
            message: sprintf('%s does not contain required characters. Required: %s', $field, $required),
            field: $field,
            context: [
                'required' => $required,
                'actual' => $actual,
                'field' => $field
            ],
            code: 400,
            solutions: [
                'Add required characters: ' . $required,
                'Ensure the string contains all required characters'
            ],
        );
    }

    /**
     * Create exception for string that fails validation against a custom rule
     */
    public static function failedValidation(string $rule, string $actual, string $field): self
    {

        return new self(
            message: sprintf('%s failed validation rule. Rule: %s', $field, $rule),
            field: $field,
            context: [
                'rule' => $rule,
                'actual' => $actual,
                'field' => $field
            ],
            code: 400,
            solutions: [
                'Review the validation rule: ' . $rule,
                'Ensure the string meets all requirements'
            ],
        );
    }
}
