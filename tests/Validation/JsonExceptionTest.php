<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\JsonException;
use Vigihdev\Exceptions\Tests\TestCase;

class JsonExceptionTest extends TestCase
{
    /**
     * Test that invalidJson creates exception with correct properties
     */
    public function test_invalid_json_creates_exception_with_correct_properties(): void
    {
        $exception = JsonException::invalidJson('config', '{"invalid": json}');

        $this->assertInstanceOf(JsonException::class, $exception);
        $this->assertStringContainsString('{"invalid": json}', $exception->getMessage());
        $this->assertStringContainsString('config', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('config', $exception->getField());
        $this->assertEquals('{"invalid": json}', $exception->getValue());
        $this->assertEquals([
            'field' => 'config',
            'value' => '{"invalid": json}',
        ], $exception->getContext());
        $this->assertContains("Check the JSON format. It should be valid JSON.", $exception->getSolutions());
    }

    /**
     * Test invalidJson with different values
     */
    public function test_invalid_json_with_different_values(): void
    {
        $testCases = [
            ['data', '{unclosed: bracket'],
            ['settings', '"unquoted_key": "value"'],
            ['response', '{extra: comma,}'],
            ['input', '{trailing: comma,}'],
            ['body', '{single quote: \'value\'}'],
        ];

        foreach ($testCases as [$field, $value]) {
            $exception = JsonException::invalidJson($field, $value);

            $this->assertInstanceOf(JsonException::class, $exception);
            $this->assertStringContainsString($value, $exception->getMessage());
            $this->assertStringContainsString($field, $exception->getMessage());
            $this->assertEquals($field, $exception->getField());
            $this->assertEquals($value, $exception->getValue());
        }
    }

    /**
     * Test that toArray returns correct structure
     */
    public function test_to_array_returns_correct_structure(): void
    {
        $exception = JsonException::invalidJson('payload', '{"malformed": }');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('{"malformed": }', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('payload', $array['field']);
        $this->assertEquals('{"malformed": }', $array['value']);
        $this->assertEquals([
            'field' => 'payload',
            'value' => '{"malformed": }',
        ], $array['context']);
        $this->assertContains("Check the JSON format. It should be valid JSON.", $array['solutions']);
        $this->assertEquals(JsonException::class, $array['exception']);
    }

    /**
     * Test solutions array contains expected elements
     */
    public function test_invalid_json_solutions_contains_expected_elements(): void
    {
        $exception = JsonException::invalidJson('test_field', '{"invalid": json}');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Check the JSON format. It should be valid JSON.", $solutions);
    }

    /**
     * Test invalid JSON with various malformed JSON strings
     */
    public function test_invalid_json_with_various_malformed_strings(): void
    {
        $malformedJsonStrings = [
            '',                    // Empty string
            '{',                   // Unclosed brace
            '[',                   // Unclosed bracket
            '{"key": "value"',     // Missing closing brace
            '["item",',            // Missing closing bracket
            '{"key": value}',      // Unquoted value
            '{"key": "value",}',   // Trailing comma
            '{key: "value"}',      // Unquoted key
            '{"key": undefined}',  // Undefined value
            '{"key": NaN}',        // NaN value
            '{"key": Infinity}',   // Infinity value
            '\x00\x01\x02',       // Binary data
        ];

        foreach ($malformedJsonStrings as $json) {
            $exception = JsonException::invalidJson('test_field', $json);

            $this->assertInstanceOf(JsonException::class, $exception);
            $this->assertStringContainsString($json, $exception->getMessage());
            $this->assertEquals('test_field', $exception->getField());
            $this->assertEquals($json, $exception->getValue());
        }
    }

    /**
     * Test toArray consistency across different inputs
     */
    public function test_to_array_consistency_across_different_inputs(): void
    {
        $testInputs = [
            ['config', '{"invalid": json}'],
            ['data', '{unclosed: bracket'],
            ['payload', '{"malformed": }'],
        ];

        foreach ($testInputs as [$field, $value]) {
            $exception = JsonException::invalidJson($field, $value);
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
            $this->assertEquals(JsonException::class, $array['exception']);
            $this->assertEquals($field, $array['field']);
            $this->assertEquals($value, $array['value']);
        }
    }
}