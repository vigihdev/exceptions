<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\NumberException;
use Vigihdev\Exceptions\Tests\TestCase;

class NumberExceptionTest extends TestCase
{
    public function test_invalid_too_small_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::invalidTooSmall(10, 5, 'age');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('age', $exception->getMessage());
        $this->assertStringContainsString('10', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('age', $exception->getField());
        $this->assertEquals(5, $exception->getValue());
        $this->assertEquals([
            'min' => 10,
            'actual' => 5,
            'field' => 'age',
        ], $exception->getContext());
        $this->assertContains('Increase the number to at least 10', $exception->getSolutions());
    }

    public function test_invalid_too_small_without_field(): void
    {
        $exception = NumberException::invalidTooSmall(100, 50);

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('is too small', $exception->getMessage());
        $this->assertStringNotContainsString('for', $exception->getMessage());
        $this->assertEquals([
            'min' => 100,
            'actual' => 50,
            'field' => '',
        ], $exception->getContext());
    }

    public function test_invalid_too_big_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::invalidTooBig(100, 200, 'score');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('score', $exception->getMessage());
        $this->assertStringContainsString('100', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'max' => 100,
            'actual' => 200,
            'field' => 'score',
            'value' => 200
        ], $exception->getContext());
        $this->assertContains('Decrease the number to at most 100', $exception->getSolutions());
    }

    public function test_to_array_returns_correct_structure_for_invalid_too_small(): void
    {
        $exception = NumberException::invalidTooSmall(50, 25, 'count');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('count', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('count', $array['field']);
        $this->assertEquals(25, $array['value']);
        $this->assertEquals([
            'min' => 50,
            'actual' => 25,
            'field' => 'count',
        ], $array['context']);
        $this->assertContains('Increase the number to at least 50', $array['solutions']);
        $this->assertEquals(NumberException::class, $array['exception']);
    }

    public function test_to_array_returns_correct_structure_for_invalid_too_big(): void
    {
        $exception = NumberException::invalidTooBig(1000, 1500, 'amount');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('amount', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals([
            'max' => 1000,
            'actual' => 1500,
            'field' => 'amount',
            'value' => 1500
        ], $array['context']);
        $this->assertContains('Decrease the number to at most 1000', $array['solutions']);
        $this->assertEquals(NumberException::class, $array['exception']);
    }
}
