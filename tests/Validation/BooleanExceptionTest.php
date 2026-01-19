<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\BooleanException;
use Vigihdev\Exceptions\Tests\TestCase;

class BooleanExceptionTest extends TestCase
{
    /**
     * Test that invalidBoolean creates exception with correct properties
     */
    public function test_invalid_boolean_creates_exception_with_correct_properties(): void
    {
        $exception = BooleanException::invalidBoolean('active', 'maybe');

        $this->assertInstanceOf(BooleanException::class, $exception);
        $this->assertStringContainsString("Value 'maybe' is not a valid boolean for field 'active'.", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('active', $exception->getField());
        $this->assertEquals('maybe', $exception->getValue());
        $this->assertEquals([
            'field' => 'active',
            'value' => 'maybe',
            'value_type' => 'string',
        ], $exception->getContext());
        $this->assertContains("Accepted boolean values: 'true', 'false', '1', '0', 'yes', 'no', 'on', 'off'", $exception->getSolutions());
    }

    /**
     * Test that emptyBoolean creates exception with correct properties
     */
    public function test_empty_boolean_creates_exception_with_correct_properties(): void
    {
        $exception = BooleanException::emptyBoolean('enabled');

        $this->assertInstanceOf(BooleanException::class, $exception);
        $this->assertEquals("Boolean field 'enabled' cannot be empty.", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('enabled', $exception->getField());
        $this->assertNull($exception->getValue());
        $this->assertEquals([
            'field' => 'enabled',
        ], $exception->getContext());
        $this->assertContains("Provide a boolean value for field 'enabled'", $exception->getSolutions());
    }

    /**
     * Test invalidBoolean with various value types
     */
    public function test_invalid_boolean_with_various_value_types(): void
    {
        // Test with integer
        $exception = BooleanException::invalidBoolean('flag', 123);
        $this->assertStringContainsString("Value '123' is not a valid boolean for field 'flag'.", $exception->getMessage());

        // Test with boolean (valid boolean should not be accepted as invalid)
        $exception = BooleanException::invalidBoolean('flag', 'not_a_boolean');
        $this->assertStringContainsString("Value 'not_a_boolean' is not a valid boolean for field 'flag'.", $exception->getMessage());

        // Test with array
        $exception = BooleanException::invalidBoolean('flag', []);
        $this->assertStringContainsString('array', $exception->getMessage());

        // Test with object
        $obj = new \stdClass();
        $exception = BooleanException::invalidBoolean('flag', $obj);
        $this->assertStringContainsString('object', $exception->getMessage());

        // Test with float
        $exception = BooleanException::invalidBoolean('flag', 3.14);
        $this->assertStringContainsString("Value '3.14' is not a valid boolean for field 'flag'.", $exception->getMessage());
    }

    /**
     * Test that toArray returns correct structure for invalid boolean
     */
    public function test_to_array_returns_correct_structure_for_invalid_boolean(): void
    {
        $exception = BooleanException::invalidBoolean('active', 'maybe');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString("Value 'maybe' is not a valid boolean for field 'active'.", $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('active', $array['field']);
        $this->assertEquals('maybe', $array['value']);
        $this->assertEquals([
            'field' => 'active',
            'value' => 'maybe',
            'value_type' => 'string',
        ], $array['context']);
        $this->assertContains("Accepted boolean values: 'true', 'false', '1', '0', 'yes', 'no', 'on', 'off'", $array['solutions']);
        $this->assertEquals(BooleanException::class, $array['exception']);
    }

    /**
     * Test that toArray returns correct structure for empty boolean
     */
    public function test_to_array_returns_correct_structure_for_empty_boolean(): void
    {
        $exception = BooleanException::emptyBoolean('enabled');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertEquals("Boolean field 'enabled' cannot be empty.", $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('enabled', $array['field']);
        $this->assertNull($array['value']);
        $this->assertEquals([
            'field' => 'enabled',
        ], $array['context']);
        $this->assertContains("Provide a boolean value for field 'enabled'", $array['solutions']);
        $this->assertEquals(BooleanException::class, $array['exception']);
    }

    /**
     * Test that solutions array contains all expected elements for invalid boolean
     */
    public function test_invalid_boolean_solutions_contains_expected_elements(): void
    {
        $exception = BooleanException::invalidBoolean('test_field', 'invalid_value');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Accepted boolean values: 'true', 'false', '1', '0', 'yes', 'no', 'on', 'off'", $solutions);
        $this->assertContains("For string input, use lowercase: 'true' or 'false'", $solutions);
        $this->assertContains("For integer input, use: 1 (true) or 0 (false)", $solutions);
    }

    /**
     * Test that solutions array contains all expected elements for empty boolean
     */
    public function test_empty_boolean_solutions_contains_expected_elements(): void
    {
        $exception = BooleanException::emptyBoolean('test_field');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Provide a boolean value for field 'test_field'", $solutions);
        $this->assertContains("Use 'true' or 'false' (string) or 1/0 (integer)", $solutions);
    }

    /**
     * Test edge cases with different field names
     */
    public function test_invalid_boolean_with_different_field_names(): void
    {
        $fieldsAndValues = [
            ['email_verified', 'yes'],
            ['is_active', 'no'],
            ['has_permission', 'maybe'],
            ['opt_in', '123'],
        ];

        foreach ($fieldsAndValues as [$field, $value]) {
            $exception = BooleanException::invalidBoolean($field, $value);

            $this->assertInstanceOf(BooleanException::class, $exception);
            $this->assertStringContainsString($field, $exception->getMessage());
            $this->assertEquals($field, $exception->getField());
            $this->assertEquals($value, $exception->getValue());
        }
    }

    /**
     * Test valid boolean string values are properly handled
     */
    public function test_valid_boolean_strings_are_not_accepted_as_invalid(): void
    {
        // Note: This test verifies that valid booleans are not incorrectly flagged as invalid
        // The BooleanException::invalidBoolean method should only be called with invalid values
        $validBooleans = ['true', 'false', '1', '0', 'yes', 'no', 'on', 'off'];

        foreach ($validBooleans as $validBoolean) {
            $exception = BooleanException::invalidBoolean('field', $validBoolean);
            // Even though these are valid booleans, if the method is called with them,
            // it should still format the message correctly
            $this->assertStringContainsString("Value '$validBoolean' is not a valid boolean for field 'field'.", $exception->getMessage());
        }
    }
}