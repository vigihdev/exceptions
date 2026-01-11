<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\FileException;
use Vigihdev\Exceptions\Tests\TestCase;

class FileExceptionTest extends TestCase
{
    public function test_invalid_mime_type_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidMimeType('document.pdf', 'image/jpeg');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('document.pdf', $exception->getMessage());
        $this->assertStringContainsString('image/jpeg', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('', $exception->getField()); // No field passed to constructor
        $this->assertEquals(null, $exception->getValue()); // No value passed to constructor
        $this->assertEquals([
            'file' => 'document.pdf',
            'mimeType' => 'image/jpeg',
        ], $exception->getContext());
        $this->assertContains('Check the file mime type.', $exception->getSolutions());
    }

    public function test_invalid_extension_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidExtension('image.jpg', 'png');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('image.jpg', $exception->getMessage());
        $this->assertStringContainsString('png', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'file' => 'image.jpg',
            'extension' => 'png',
        ], $exception->getContext());
        $this->assertContains('Check the file extension.', $exception->getSolutions());
    }

    public function test_invalid_too_small_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidTooSmall(1024, 512, 'avatar');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('avatar', $exception->getMessage());
        $this->assertStringContainsString('1024', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'min' => 1024,
            'actual' => 512,
            'attribute' => 'avatar',
        ], $exception->getContext());
        $this->assertContains('Increase the file size to at least 1024 bytes', $exception->getSolutions());
    }

    public function test_invalid_too_small_without_attribute(): void
    {
        $exception = FileException::invalidTooSmall(2048, 1024);

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('is too small', $exception->getMessage());
        $this->assertStringNotContainsString('for', $exception->getMessage());
        $this->assertEquals([
            'min' => 2048,
            'actual' => 1024,
            'attribute' => '',
        ], $exception->getContext());
    }

    public function test_invalid_too_big_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidTooBig(512000, 1024000, 'document');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('document', $exception->getMessage());
        $this->assertStringContainsString('512000', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'max' => 512000,
            'actual' => 1024000,
            'attribute' => 'document',
        ], $exception->getContext());
        $this->assertContains('Reduce the file size to at most 512000 bytes', $exception->getSolutions());
    }

    public function test_to_array_returns_correct_structure_for_invalid_mime_type(): void
    {
        $exception = FileException::invalidMimeType('document.pdf', 'image/jpeg');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('document.pdf', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('', $array['field']);
        $this->assertNull($array['value']);
        $this->assertEquals([
            'file' => 'document.pdf',
            'mimeType' => 'image/jpeg',
        ], $array['context']);
        $this->assertContains('Check the file mime type.', $array['solutions']);
        $this->assertEquals(FileException::class, $array['exception']);
    }

    public function test_to_array_returns_correct_structure_for_invalid_too_big(): void
    {
        $exception = FileException::invalidTooBig(512000, 1024000, 'document');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('document', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals([
            'max' => 512000,
            'actual' => 1024000,
            'attribute' => 'document',
        ], $array['context']);
        $this->assertContains('Reduce the file size to at most 512000 bytes', $array['solutions']);
        $this->assertEquals(FileException::class, $array['exception']);
    }
}