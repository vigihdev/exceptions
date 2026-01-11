<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\ValidationException;
use Vigihdev\Exceptions\Validation\ValidationExceptionInterface;
use Vigihdev\Exceptions\Tests\TestCase;

class ConcreteValidationException extends ValidationException
{
    // Concrete implementation for testing purposes
}

class ValidationExceptionTest extends TestCase
{
    public function test_it_extends_exception_and_implements_interface(): void
    {
        $exception = new ConcreteValidationException("Test message");
        
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(ValidationExceptionInterface::class, $exception);
    }

    public function test_constructor_sets_properties(): void
    {
        $exception = new ConcreteValidationException(
            message: "Test message",
            code: 400,
            field: "email",
            value: "invalid@example.com",
            context: ["key" => "value"],
            solutions: ["solution1", "solution2"]
        );

        $this->assertEquals("Test message", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals("email", $exception->getField());
        $this->assertEquals("invalid@example.com", $exception->getValue());
        $this->assertEquals(["key" => "value"], $exception->getContext());
        $this->assertEquals(["solution1", "solution2"], $exception->getSolutions());
    }

    public function test_get_field_returns_correct_value(): void
    {
        $exception = new ConcreteValidationException("Test", 0, null, "username");
        $this->assertEquals("username", $exception->getField());
    }

    public function test_get_value_returns_correct_value(): void
    {
        $exception = new ConcreteValidationException("Test", 0, null, "", "test_value");
        $this->assertEquals("test_value", $exception->getValue());
    }

    public function test_get_context_returns_array(): void
    {
        $exception = new ConcreteValidationException("Test", 0, null, "", null, ["test" => "data"]);
        $this->assertIsArray($exception->getContext());
        $this->assertEquals(["test" => "data"], $exception->getContext());
    }

    public function test_get_solutions_returns_array(): void
    {
        $exception = new ConcreteValidationException("Test", 0, null, "", null, [], ["fix this"]);
        $this->assertIsArray($exception->getSolutions());
        $this->assertEquals(["fix this"], $exception->getSolutions());
    }

    public function test_get_formatted_message(): void
    {
        $exception = new ConcreteValidationException(
            message: "Original message",
            context: ["key" => "value"]
        );

        $formattedMessage = $exception->getFormattedMessage();
        $this->assertStringContainsString("Original message", $formattedMessage);
        $this->assertStringContainsString("{\"key\":\"value\"}", $formattedMessage);
    }

    public function test_get_formatted_message_without_context(): void
    {
        $exception = new ConcreteValidationException("Simple message");

        $formattedMessage = $exception->getFormattedMessage();
        $this->assertEquals("Simple message", $formattedMessage);
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $exception = new ConcreteValidationException(
            message: "Test message",
            code: 400,
            field: "email",
            value: "invalid@example.com",
            context: ["key" => "value"],
            solutions: ["solution1"]
        );

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertEquals("Test message", $array["message"]);
        $this->assertEquals(400, $array["code"]);
        $this->assertEquals("email", $array["field"]);
        $this->assertEquals("invalid@example.com", $array["value"]);
        $this->assertEquals(["key" => "value"], $array["context"]);
        $this->assertEquals(["solution1"], $array["solutions"]);
        $this->assertEquals(ConcreteValidationException::class, $array["exception"]);
    }
}
