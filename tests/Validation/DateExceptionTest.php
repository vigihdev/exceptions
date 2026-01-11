<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\DateException;
use Vigihdev\Exceptions\Tests\TestCase;

class DateExceptionTest extends TestCase
{
    public function test_empty_date_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::emptyDate('created_at', '');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertStringContainsString("''", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertEquals('', $exception->getValue());
        $this->assertEquals([
            'field' => 'created_at',
            'value' => '',
        ], $exception->getContext());
        $this->assertContains("Provide a valid date for field '%s'.", $exception->getSolutions());
    }

    public function test_empty_date_with_null_value(): void
    {
        $exception = DateException::emptyDate('updated_at');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('updated_at', $exception->getMessage());
        $this->assertStringContainsString("''", $exception->getMessage());
        $this->assertEquals('', $exception->getValue());
        $this->assertEquals([
            'field' => 'updated_at',
            'value' => '',
        ], $exception->getContext());
    }

    public function test_invalid_date_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::invalidDate('created_at', 'not-a-date');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('not-a-date', $exception->getMessage());
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertEquals('not-a-date', $exception->getValue());
        $this->assertEquals([
            'field' => 'created_at',
            'value' => 'not-a-date',
        ], $exception->getContext());
        $this->assertContains("Check the date format. It should be 'Y-m-d' or 'Y-m-d H:i:s'.", $exception->getSolutions());
    }

    public function test_invalid_datetime_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::invalidDateTime('timestamp', 'not-a-datetime');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('not-a-datetime', $exception->getMessage());
        $this->assertStringContainsString('timestamp', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('timestamp', $exception->getField());
        $this->assertEquals('not-a-datetime', $exception->getValue());
        $this->assertEquals([
            'field' => 'timestamp',
            'value' => 'not-a-datetime',
        ], $exception->getContext());
        $this->assertContains("Check the date time format. It should be 'Y-m-d H:i:s'.", $exception->getSolutions());
    }

    public function test_to_array_returns_correct_structure_for_invalid_date(): void
    {
        $exception = DateException::invalidDate('created_at', 'not-a-date');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('not-a-date', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('created_at', $array['field']);
        $this->assertEquals('not-a-date', $array['value']);
        $this->assertEquals([
            'field' => 'created_at',
            'value' => 'not-a-date',
        ], $array['context']);
        $this->assertContains("Check the date format. It should be 'Y-m-d' or 'Y-m-d H:i:s'.", $array['solutions']);
        $this->assertEquals(DateException::class, $array['exception']);
    }

    public function test_to_array_returns_correct_structure_for_empty_date(): void
    {
        $exception = DateException::emptyDate('updated_at', '');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('updated_at', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('updated_at', $array['field']);
        $this->assertEquals('', $array['value']);
        $this->assertEquals([
            'field' => 'updated_at',
            'value' => '',
        ], $array['context']);
        $this->assertContains("Provide a valid date for field '%s'.", $array['solutions']);
        $this->assertEquals(DateException::class, $array['exception']);
    }
}