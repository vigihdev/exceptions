<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\BooleanException;
use Vigihdev\Exceptions\Tests\TestCase;

class BooleanExceptionTest extends TestCase
{
    public function test_invalid_boolean_creates_exception_with_correct_properties(): void
    {
        $exception = BooleanException::invalidBoolean('active', 'maybe');

        $this->assertInstanceOf(BooleanException::class, $exception);
        $this->assertStringContainsString("'maybe' is not a valid boolean", $exception->getMessage());
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

    public function test_invalid_boolean_with_various_value_types(): void
    {
        // Test with integer
        $exception = BooleanException::invalidBoolean('flag', 123);
        $this->assertStringContainsString("'123' is not a valid boolean", $exception->getMessage());

        // Test with boolean
        $exception = BooleanException::invalidBoolean('flag', true);
        $this->assertStringContainsString("'true' is not a valid boolean", $exception->getMessage());

        // Test with array
        $exception = BooleanException::invalidBoolean('flag', []);
        $this->assertStringContainsString('array', $exception->getMessage());
    }

    public function test_to_array_returns_correct_structure_for_invalid_boolean(): void
    {
        $exception = BooleanException::invalidBoolean('active', 'maybe');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString("'maybe' is not a valid boolean", $array['message']);
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
}