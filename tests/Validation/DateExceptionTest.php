<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\DateException;
use Vigihdev\Exceptions\Tests\TestCase;

class DateExceptionTest extends TestCase
{
    /**
     * Test that emptyValue creates exception with correct properties
     */
    public function test_empty_date_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::emptyValue('created_at');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertStringContainsString("cannot be blank", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertNull($exception->getValue());
        $this->assertEquals([
            'field' => 'created_at',
            'value' => null,
        ], $exception->getContext());
        $this->assertContains("Field 'created_at' must be a valid date. Example: 2020-08-08 or 2020-08-08 05:05:03", $exception->getSolutions());
    }

    /**
     * Test emptyValue with null value
     */
    public function test_empty_date_with_null_value(): void
    {
        $exception = DateException::emptyValue('updated_at');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('updated_at', $exception->getMessage());
        $this->assertStringContainsString("cannot be blank", $exception->getMessage());
        $this->assertNull($exception->getValue());
        $this->assertEquals([
            'field' => 'updated_at',
            'value' => null,
        ], $exception->getContext());
    }

    /**
     * Test that invalidDate creates exception with correct properties
     */
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
        $this->assertContains("Check the date format. It should be 'Y-m-d'.", $exception->getSolutions());
    }

    /**
     * Test that invalidDateTime creates exception with correct properties
     */
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

    /**
     * Test that toArray returns correct structure for invalid date
     */
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
        $this->assertContains("Check the date format. It should be 'Y-m-d'.", $array['solutions']);
        $this->assertEquals(DateException::class, $array['exception']);
    }

    /**
     * Test that toArray returns correct structure for empty date
     */
    public function test_to_array_returns_correct_structure_for_empty_date(): void
    {
        $exception = DateException::emptyValue('updated_at');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('updated_at', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('updated_at', $array['field']);
        $this->assertNull($array['value']);
        $this->assertEquals([
            'field' => 'updated_at',
            'value' => null,
        ], $array['context']);
        $this->assertContains("Field 'updated_at' must be a valid date. Example: 2020-08-08 or 2020-08-08 05:05:03", $array['solutions']);
        $this->assertEquals(DateException::class, $array['exception']);
    }

    /**
     * Test invalidDate with various invalid date formats
     */
    public function test_invalid_date_with_various_formats(): void
    {
        $invalidDates = [
            'not-a-date',
            '2023-13-01',  // Invalid month
            '2023-02-30',  // Invalid day
            '2023/12/01',  // Wrong separator
            '01-12-2023',  // Wrong format
            '2023-01',     // Incomplete
            '2023',        // Just year
            'abc-def-ghi', // Random string
        ];

        foreach ($invalidDates as $date) {
            $exception = DateException::invalidDate('test_field', $date);

            $this->assertInstanceOf(DateException::class, $exception);
            $this->assertStringContainsString($date, $exception->getMessage());
            $this->assertEquals('test_field', $exception->getField());
            $this->assertEquals($date, $exception->getValue());
        }
    }

    /**
     * Test invalidDateTime with various invalid datetime formats
     */
    public function test_invalid_datetime_with_various_formats(): void
    {
        $invalidDatetimes = [
            'not-a-datetime',
            '2023-13-01 25:00:00',  // Invalid hour
            '2023-02-30 12:00:00',  // Invalid day
            '2023/12/01 12:00:00',  // Wrong separator
            '01-12-2023 12:00:00',  // Wrong format
            '2023-01-01',            // Date only
            '12:00:00',              // Time only
            'abc:def:ghi jkl:mno:pqr', // Random string
        ];

        foreach ($invalidDatetimes as $datetime) {
            $exception = DateException::invalidDateTime('test_field', $datetime);

            $this->assertInstanceOf(DateException::class, $exception);
            $this->assertStringContainsString($datetime, $exception->getMessage());
            $this->assertEquals('test_field', $exception->getField());
            $this->assertEquals($datetime, $exception->getValue());
        }
    }

    /**
     * Test solutions array contains expected elements for invalid date
     */
    public function test_invalid_date_solutions_contains_expected_elements(): void
    {
        $exception = DateException::invalidDate('test_field', 'invalid_date');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Check the date format. It should be 'Y-m-d'.", $solutions);
    }

    /**
     * Test solutions array contains expected elements for invalid datetime
     */
    public function test_invalid_datetime_solutions_contains_expected_elements(): void
    {
        $exception = DateException::invalidDateTime('test_field', 'invalid_datetime');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Check the date time format. It should be 'Y-m-d H:i:s'.", $solutions);
    }

    /**
     * Test toArray consistency across different inputs
     */
    public function test_to_array_consistency_across_different_inputs(): void
    {
        $testInputs = [
            [DateException::invalidDate('field1', 'invalid1'), 'invalidDate'],
            [DateException::invalidDateTime('field2', 'invalid2'), 'invalidDateTime'],
            [DateException::emptyValue('field3'), 'emptyValue'],
        ];

        foreach ($testInputs as [$exception, $type]) {
            $array = $exception->toArray();

            $this->assertIsArray($array);
            $this->assertArrayHasKey('message', $array);
            $this->assertArrayHasKey('code', $array);
            $this->assertArrayHasKey('field', $array);
            $this->assertArrayHasKey('value', $array);
            $this->assertArrayHasKey('context', $array);
            $this->assertArrayHasKey('solutions', $array);
            $this->assertArrayHasKey('exception', $array);

            $this->assertEquals(400, $array['code']);
            $this->assertEquals(DateException::class, $array['exception']);
        }
    }

    /**
     * Test invalidFormat creates exception with correct properties
     */
    public function test_invalid_format_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::invalidFormat('created_at', 'not-a-date');

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

    /**
     * Test tooEarly creates exception with correct properties
     */
    public function test_too_early_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::tooEarly('created_at', '2020-01-01', '2021-01-01');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('2020-01-01', $exception->getMessage());
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertStringContainsString('2021-01-01', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertEquals('2020-01-01', $exception->getValue());
        $this->assertEquals([
            'min_date' => '2021-01-01',
            'actual' => '2020-01-01',
            'field' => 'created_at',
            'value' => '2020-01-01'
        ], $exception->getContext());
        $this->assertContains("Field 'created_at' value '2020-01-01' must be after 2021-01-01", $exception->getSolutions());
    }

    /**
     * Test tooLate creates exception with correct properties
     */
    public function test_too_late_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::tooLate('created_at', '2022-01-01', '2021-01-01');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('2022-01-01', $exception->getMessage());
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertStringContainsString('2021-01-01', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertEquals('2022-01-01', $exception->getValue());
        $this->assertEquals([
            'actual' => '2022-01-01',
            'field' => 'created_at',
            'value' => '2022-01-01'
        ], $exception->getContext());
        $this->assertContains("Field 'created_at' value '2022-01-01' must be before 2021-01-01", $exception->getSolutions());
    }

    /**
     * Test outOfRange creates exception with correct properties
     */
    public function test_out_of_range_creates_exception_with_correct_properties(): void
    {
        $exception = DateException::outOfRange('created_at', '2022-01-01', '2020-01-01', '2021-01-01');

        $this->assertInstanceOf(DateException::class, $exception);
        $this->assertStringContainsString('2022-01-01', $exception->getMessage());
        $this->assertStringContainsString('created_at', $exception->getMessage());
        $this->assertStringContainsString('2020-01-01', $exception->getMessage());
        $this->assertStringContainsString('2021-01-01', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('created_at', $exception->getField());
        $this->assertEquals('2022-01-01', $exception->getValue());
        $this->assertEquals([
            'field' => 'created_at',
            'value' => '2022-01-01',
            'min_date' => '2020-01-01',
            'max_date' => '2021-01-01',
        ], $exception->getContext());
        $this->assertContains("Please enter a date between 2020-01-01 and 2021-01-01 for field 'created_at'.", $exception->getSolutions());
    }
}
