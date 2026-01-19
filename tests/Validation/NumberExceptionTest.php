<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\NumberException;
use Vigihdev\Exceptions\Tests\TestCase;

class NumberExceptionTest extends TestCase
{
    /**
     * Test that tooSmall creates exception with correct properties
     */
    public function test_too_small_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::tooSmall(10, 5, 'age');

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
        $this->assertContains('Increase the value of \'age\' to at least 10.', $exception->getSolutions());
    }

    /**
     * Test tooSmall without field parameter
     */
    public function test_too_small_without_field(): void
    {
        $exception = NumberException::tooSmall(100, 50, '');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('is too small', $exception->getMessage());
        $this->assertStringContainsString('for', $exception->getMessage());
        $context = $exception->getContext();
        $this->assertEquals('', $context['field']);
        $this->assertEquals(50.0, $context['actual']);
        $this->assertEquals(100.0, $context['min']);
    }

    /**
     * Test that tooLarge creates exception with correct properties
     */
    public function test_too_large_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::tooLarge(100, 200, 'score');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('score', $exception->getMessage());
        $this->assertStringContainsString('100', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $context = $exception->getContext();
        $this->assertEquals('score', $context['field']);
        $this->assertEquals(200.0, $context['actual']);
        $this->assertEquals(100.0, $context['max']);
        $this->assertContains('Reduce the value of \'score\' to 100 or below.', $exception->getSolutions());
    }

    /**
     * Test that toArray returns correct structure for tooSmall
     */
    public function test_to_array_returns_correct_structure_for_too_small(): void
    {
        $exception = NumberException::tooSmall(50, 25, 'count');

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
        $this->assertContains('Increase the value of \'count\' to at least 50.', $array['solutions']);
        $this->assertEquals(NumberException::class, $array['exception']);
    }

    /**
     * Test that toArray returns correct structure for tooLarge
     */
    public function test_to_array_returns_correct_structure_for_too_large(): void
    {
        $exception = NumberException::tooLarge(1000, 1500, 'amount');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('amount', $array['message']);
        $this->assertEquals(400, $array['code']);
        $context = $array['context'];
        $this->assertEquals('amount', $context['field']);
        $this->assertEquals(1500.0, $context['actual']);
        $this->assertEquals(1000.0, $context['max']);
        $this->assertContains('Reduce the value of \'amount\' to 1000 or below.', $array['solutions']);
        $this->assertEquals(NumberException::class, $array['exception']);
    }

    /**
     * Test tooSmall with various numeric types
     */
    public function test_too_small_with_various_numeric_types(): void
    {
        $testCases = [
            [10, 5, 'integers'],
            [10.5, 5.2, 'floats'],
            [0, -5, 'negative_numbers'],
            [100, 0, 'zero_actual'],
        ];

        foreach ($testCases as [$min, $actual, $field]) {
            $exception = NumberException::tooSmall($min, $actual, $field);

            $this->assertInstanceOf(NumberException::class, $exception);
            $this->assertEquals($field, $exception->getField());
            $this->assertEquals($actual, $exception->getValue());

            $context = $exception->getContext();
            $this->assertEquals([
                'min' => $min,
                'actual' => $actual,
                'field' => $field,
            ], $context);
        }
    }

    /**
     * Test tooLarge with various numeric types
     */
    public function test_too_large_with_various_numeric_types(): void
    {
        $testCases = [
            [10, 15, 'integers'],
            [5.2, 10.5, 'floats'],
            [-5, 0, 'negative_numbers'],
            [0, 100, 'zero_limit'],
        ];

        foreach ($testCases as [$max, $actual, $field]) {
            $exception = NumberException::tooLarge($max, $actual, $field);

            $this->assertInstanceOf(NumberException::class, $exception);
            $this->assertEquals($field, $exception->getField());
            $this->assertEquals($actual, $exception->getValue());

            $context = $exception->getContext();
            $this->assertEquals($field, $context['field']);
            $this->assertEquals($actual, $context['actual']);
            $this->assertEquals($max, $context['max']);
        }
    }

    /**
     * Test solutions array contains expected elements for too small
     */
    public function test_too_small_solutions_contains_expected_elements(): void
    {
        $exception = NumberException::tooSmall(10, 5, 'test_field');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains('Increase the value of \'test_field\' to at least 10.', $solutions);
    }

    /**
     * Test solutions array contains expected elements for too large
     */
    public function test_too_large_solutions_contains_expected_elements(): void
    {
        $exception = NumberException::tooLarge(100, 150, 'test_field');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains('Reduce the value of \'test_field\' to 100 or below.', $solutions);
    }

    /**
     * Test toArray consistency across different inputs
     */
    public function test_to_array_consistency_across_different_inputs(): void
    {
        $testInputs = [
            [NumberException::tooSmall(10, 5, 'field1'), 'tooSmall'],
            [NumberException::tooLarge(100, 150, 'field2'), 'tooLarge'],
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
            $this->assertEquals(NumberException::class, $array['exception']);
        }
    }

    /**
     * Test invalidType creates exception with correct properties
     */
    public function test_invalid_type_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::invalidType('age', 'not_a_number');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('not_a_number', $exception->getMessage());
        $this->assertStringContainsString('age', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('age', $exception->getField());
        $this->assertEquals('not_a_number', $exception->getValue());
        $this->assertEquals([
            'field' => 'age',
            'value' => 'not_a_number',
        ], $exception->getContext());
        $this->assertContains('Ensure the input is numeric (e.g., 42, -10, 3.14).', $exception->getSolutions());
    }

    /**
     * Test emptyValue creates exception with correct properties
     */
    public function test_empty_value_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::emptyValue('quantity');

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('quantity', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('quantity', $exception->getField());
        $this->assertNull($exception->getValue());
        $this->assertEquals([
            'field' => 'quantity',
            'value' => null,
        ], $exception->getContext());
        $this->assertContains('Provide a valid numeric value for field \'quantity\'.', $exception->getSolutions());
    }

    /**
     * Test notInteger creates exception with correct properties
     */
    public function test_not_integer_creates_exception_with_correct_properties(): void
    {
        $exception = NumberException::notInteger('count', 5.7);

        $this->assertInstanceOf(NumberException::class, $exception);
        $this->assertStringContainsString('5.7', $exception->getMessage());
        $this->assertStringContainsString('count', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('count', $exception->getField());
        $this->assertEquals(5.7, $exception->getValue());
        $this->assertEquals([
            'field' => 'count',
            'actual' => 5.7,
        ], $exception->getContext());
        $this->assertContains('Round or truncate the value of \'count\' to a whole number (e.g., 5).', $exception->getSolutions());
    }
}
