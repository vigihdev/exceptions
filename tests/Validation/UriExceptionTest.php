<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\UriException;
use Vigihdev\Exceptions\Tests\TestCase;

class UriExceptionTest extends TestCase
{
    public function test_not_found_creates_exception_with_correct_properties(): void
    {
        $exception = UriException::notFound('/api/users', 404);

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('/api/users', $exception->getMessage());
        $this->assertStringContainsString('404', $exception->getMessage());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals('', $exception->getField()); // No field passed to constructor
        $this->assertNull($exception->getValue()); // No value passed to constructor
        $this->assertEquals([
            'uri' => '/api/users',
            'status_code' => 404,
        ], $exception->getContext());
        $this->assertContains("Check if the URI is properly formatted", $exception->getSolutions());
    }

    public function test_not_found_without_status_code(): void
    {
        $exception = UriException::notFound('/api/products');

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('/api/products', $exception->getMessage());
        $this->assertStringNotContainsString('Status Code', $exception->getMessage());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals([
            'uri' => '/api/products',
            'status_code' => 0,
        ], $exception->getContext());
    }

    public function test_invalid_creates_exception_with_correct_properties(): void
    {
        $exception = UriException::invalid('not-a-uri', 'Invalid format');

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('not-a-uri', $exception->getMessage());
        $this->assertStringContainsString('Invalid format', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'uri' => 'not-a-uri',
            'reason' => 'Invalid format',
            'filtered' => false
        ], $exception->getContext());
        $this->assertContains("Check if the URI is properly formatted", $exception->getSolutions());
    }

    public function test_invalid_without_reason(): void
    {
        $exception = UriException::invalid('not-a-uri');

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('not-a-uri', $exception->getMessage());
        $this->assertStringContainsString('Invalid URI: not-a-uri', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'uri' => 'not-a-uri',
            'reason' => '',
            'filtered' => false
        ], $exception->getContext());
    }

    public function test_invalid_scheme_creates_exception_with_correct_properties(): void
    {
        $exception = UriException::invalidScheme('javascript:alert(1)', 'javascript', ['https', 'http']);

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('javascript', $exception->getMessage());
        $this->assertStringContainsString('javascript:alert(1)', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'uri' => 'javascript:alert(1)',
            'scheme' => 'javascript',
            'allowed_schemes' => ['https', 'http'],
            'common_schemes' => ['http', 'https', 'ftp', 'file', 'data']
        ], $exception->getContext());
        $this->assertContains("Check if the URI is properly formatted", $exception->getSolutions());
    }

    public function test_unsupported_scheme_creates_exception_with_correct_properties(): void
    {
        $exception = UriException::unsupportedScheme('tel:+1234567890', 'tel');

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('tel', $exception->getMessage());
        $this->assertStringContainsString('tel:+1234567890', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'uri' => 'tel:+1234567890',
            'scheme' => 'tel',
            'common_schemes' => ['http', 'https', 'ftp', 'file', 'data']
        ], $exception->getContext());
        $this->assertContains("Check if the URI is properly formatted", $exception->getSolutions());
    }

    public function test_malformed_creates_exception_with_correct_properties(): void
    {
        $exception = UriException::malformed('https://example.com/path with spaces', 'path');

        $this->assertInstanceOf(UriException::class, $exception);
        $this->assertStringContainsString('path', $exception->getMessage());
        $this->assertStringContainsString('https://example.com/path with spaces', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'uri' => 'https://example.com/path with spaces',
            'component' => 'path'
        ], $exception->getContext());
        $this->assertContains("Check if the URI is properly formatted", $exception->getSolutions());
    }

    public function test_to_array_returns_correct_structure_for_not_found(): void
    {
        $exception = UriException::notFound('/api/users', 404);

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('/api/users', $array['message']);
        $this->assertEquals(404, $array['code']);
        $this->assertEquals('', $array['field']);
        $this->assertNull($array['value']);
        $this->assertEquals([
            'uri' => '/api/users',
            'status_code' => 404,
        ], $array['context']);
        $this->assertContains("Check if the URI is properly formatted", $array['solutions']);
        $this->assertEquals(UriException::class, $array['exception']);
    }

    public function test_to_array_returns_correct_structure_for_invalid_scheme(): void
    {
        $exception = UriException::invalidScheme('javascript:alert(1)', 'javascript', ['https', 'http']);

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('javascript', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals([
            'uri' => 'javascript:alert(1)',
            'scheme' => 'javascript',
            'allowed_schemes' => ['https', 'http'],
            'common_schemes' => ['http', 'https', 'ftp', 'file', 'data']
        ], $array['context']);
        $this->assertContains("Check if the URI is properly formatted", $array['solutions']);
        $this->assertEquals(UriException::class, $array['exception']);
    }
}