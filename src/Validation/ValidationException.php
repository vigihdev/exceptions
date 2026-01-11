<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

use Exception;

/**
 * Base exception untuk semua validation-related errors
 * 
 * @abstract Tidak bisa di-instansiate langsung
 */
abstract class ValidationException extends Exception implements ValidationExceptionInterface
{
    /**
     * Field yang divalidasi
     */
    protected string $field;

    /**
     * Value yang invalid
     */
    protected mixed $value;

    /**
     * Additional context data
     */
    protected array $context = [];

    /**
     * Suggested solutions
     */
    protected array $solutions = [];

    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
        string $field = '',
        mixed $value = null,
        array $context = [],
        array $solutions = []
    ) {
        $this->field = $field;
        $this->value = $value;
        $this->context = $context;
        $this->solutions = $solutions;

        parent::__construct($message, $code, $previous);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getSolutions(): array
    {
        return $this->solutions;
    }

    public function getFormattedMessage(): string
    {
        $message = $this->getMessage();

        if (!empty($this->context)) {
            $contextStr = json_encode($this->context, JSON_UNESCAPED_SLASHES);
            $message .= " (context: {$contextStr})";
        }
        return $message;
    }

    /**
     * Convert to array for logging/API responses
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'field' => $this->field,
            'value' => $this->value,
            'context' => $this->context,
            'solutions' => $this->solutions,
            'exception' => static::class,
        ];
    }
}
