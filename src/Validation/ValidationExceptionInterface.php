<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

interface ValidationExceptionInterface
{
    public function getContext(): array;

    public function getSolutions(): array;

    public function getField(): string;

    public function getValue(): mixed;

    public function getMessage(): string;

    public function getCode(): int;

    public function getPrevious(): ?\Throwable;

    public function toArray(): array;

    public function getFormattedMessage(): string;
}
