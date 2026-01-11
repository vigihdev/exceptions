<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\JsonException;
use Vigihdev\Exceptions\Tests\TestCase;

class JsonExceptionTest extends TestCase
{
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

    public function test_invalid_json_with_different_values(): void
    {
        $exception = JsonException::invalidJson('data', '{unclosed: bracket');

        $this->assertInstanceOf(JsonException::class, $exception);
        $this->assertStringContainsString('{unclosed: bracket', $exception->getMessage());
        $this->assertStringContainsString('data', $exception->getMessage());
        $this->assertEquals('data', $exception->getField());
        $this->assertEquals('{unclosed: bracket', $exception->getValue());
    }

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
}