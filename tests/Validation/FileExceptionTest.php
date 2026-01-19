<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\FileException;
use Vigihdev\Exceptions\Tests\TestCase;

class FileExceptionTest extends TestCase
{
    /**
     * Test that invalidMimeType creates exception with correct properties
     */
    public function test_invalid_mime_type_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidMimeType('avatar', 'document.pdf', 'image/jpeg');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('document.pdf', $exception->getMessage());
        $this->assertStringContainsString('image/jpeg', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('avatar', $exception->getField());
        $this->assertEquals('document.pdf', $exception->getValue());
        $this->assertEquals([
            'field' => 'avatar',
            'value' => 'document.pdf',
            'mimeType' => 'image/jpeg',
        ], $exception->getContext());
        $this->assertContains("Check the mime type of file 'document.pdf'.", $exception->getSolutions());
    }

    /**
     * Test that invalidExtension creates exception with correct properties
     */
    public function test_invalid_extension_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::invalidExtension('avatar', 'image.jpg', 'png');

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('image.jpg', $exception->getMessage());
        $this->assertStringContainsString('png', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('avatar', $exception->getField());
        $this->assertEquals('image.jpg', $exception->getValue());
        $this->assertEquals([
            'field' => 'avatar',
            'value' => 'image.jpg',
            'extension' => 'png',
        ], $exception->getContext());
        $this->assertContains("Check the extension of file 'image.jpg'.", $exception->getSolutions());
    }

    /**
     * Test that tooSmall creates exception with correct properties
     */
    public function test_invalid_too_small_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::tooSmall(1024, 'avatar', 512);

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('avatar', $exception->getMessage());
        $this->assertStringContainsString('1024', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'min' => 1024,
            'actual' => 512,
            'field' => 'avatar',
        ], $exception->getContext());
        $this->assertContains('Increase the file size to at least 1024 bytes', $exception->getSolutions());
    }

    /**
     * Test tooSmall without field parameter
     */
    public function test_invalid_too_small_without_field(): void
    {
        $exception = FileException::tooSmall(2048, '', 1024);

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('is too small', $exception->getMessage());
        $this->assertStringContainsString('2048', $exception->getMessage());
        $this->assertEquals([
            'min' => 2048,
            'actual' => 1024,
            'field' => '',
        ], $exception->getContext());
    }

    /**
     * Test that tooBig creates exception with correct properties
     */
    public function test_invalid_too_big_creates_exception_with_correct_properties(): void
    {
        $exception = FileException::tooBig(512000, 'document', 1024000);

        $this->assertInstanceOf(FileException::class, $exception);
        $this->assertStringContainsString('document', $exception->getMessage());
        $this->assertStringContainsString('512000', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'max' => 512000,
            'actual' => 1024000,
            'field' => 'document',
        ], $exception->getContext());
        $this->assertContains('Reduce the file size to at most 512000 bytes', $exception->getSolutions());
    }

    /**
     * Test that toArray returns correct structure for invalid mime type
     */
    public function test_to_array_returns_correct_structure_for_invalid_mime_type(): void
    {
        $exception = FileException::invalidMimeType('avatar', 'document.pdf', 'image/jpeg');

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('document.pdf', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('avatar', $array['field']);
        $this->assertEquals('document.pdf', $array['value']);
        $this->assertEquals([
            'field' => 'avatar',
            'value' => 'document.pdf',
            'mimeType' => 'image/jpeg',
        ], $array['context']);
        $this->assertContains("Check the mime type of file 'document.pdf'.", $array['solutions']);
        $this->assertEquals(FileException::class, $array['exception']);
    }

    /**
     * Test that toArray returns correct structure for invalid too big
     */
    public function test_to_array_returns_correct_structure_for_invalid_too_big(): void
    {
        $exception = FileException::tooBig(512000, 'document', 1024000);

        $array = $exception->toArray();

        $this->assertIsArray($array);
        $this->assertStringContainsString('document', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals([
            'max' => 512000,
            'actual' => 1024000,
            'field' => 'document',
        ], $array['context']);
        $this->assertContains('Reduce the file size to at most 512000 bytes', $array['solutions']);
        $this->assertEquals(FileException::class, $array['exception']);
    }

    /**
     * Test invalidMimeType with various file types and mime types
     */
    public function test_invalid_mime_type_with_various_file_types(): void
    {
        $testCases = [
            ['upload', 'document.pdf', 'image/jpeg'],
            ['avatar', 'image.png', 'text/plain'],
            ['file', 'script.js', 'application/json'],
            ['attachment', 'data.xml', 'image/gif'],
        ];

        foreach ($testCases as [$field, $file, $mimeType]) {
            $exception = FileException::invalidMimeType($field, $file, $mimeType);

            $this->assertInstanceOf(FileException::class, $exception);
            $this->assertStringContainsString($file, $exception->getMessage());
            $this->assertStringContainsString($mimeType, $exception->getMessage());
            $this->assertEquals($field, $exception->getField());

            $context = $exception->getContext();
            $this->assertEquals([
                'field' => $field,
                'value' => $file,
                'mimeType' => $mimeType,
            ], $context);
        }
    }

    /**
     * Test invalidExtension with various file types and extensions
     */
    public function test_invalid_extension_with_various_file_types(): void
    {
        $testCases = [
            ['upload', 'document.pdf', 'jpg'],
            ['avatar', 'image.png', 'txt'],
            ['file', 'script.js', 'php'],
            ['attachment', 'data.xml', 'gif'],
        ];

        foreach ($testCases as [$field, $file, $extension]) {
            $exception = FileException::invalidExtension($field, $file, $extension);

            $this->assertInstanceOf(FileException::class, $exception);
            $this->assertStringContainsString($file, $exception->getMessage());
            $this->assertStringContainsString($extension, $exception->getMessage());
            $this->assertEquals($field, $exception->getField());

            $context = $exception->getContext();
            $this->assertEquals([
                'field' => $field,
                'value' => $file,
                'extension' => $extension,
            ], $context);
        }
    }

    /**
     * Test tooSmall with various file sizes
     */
    public function test_too_small_with_various_sizes(): void
    {
        $testCases = [
            [1024, 'file1', 512],      // Standard case
            [0, 'file2', 0],           // Zero min size
            [1048576, 'file3', 512],   // Large min size (1MB)
            [100, 'file4', 50],        // Small sizes
        ];

        foreach ($testCases as [$minSize, $field, $actualSize]) {
            $exception = FileException::tooSmall($minSize, $field, $actualSize);

            $this->assertInstanceOf(FileException::class, $exception);
            $this->assertStringContainsString((string)$minSize, $exception->getMessage());
            $this->assertEquals('', $exception->getField()); // Field is not set in constructor, only in context

            $context = $exception->getContext();
            $this->assertEquals($field, $context['field']);
            $this->assertEquals($actualSize, $context['actual']);
            $this->assertEquals($minSize, $context['min']);
        }
    }

    /**
     * Test tooBig with various file sizes
     */
    public function test_too_big_with_various_sizes(): void
    {
        $testCases = [
            [1024, 'file1', 2048],      // Standard case
            [0, 'file2', 100],          // Zero max size
            [1048576, 'file3', 2097152],// Large sizes (1MB vs 2MB)
            [100, 'file4', 200],        // Small sizes
        ];

        foreach ($testCases as [$maxSize, $field, $actualSize]) {
            $exception = FileException::tooBig($maxSize, $field, $actualSize);

            $this->assertInstanceOf(FileException::class, $exception);
            $this->assertStringContainsString((string)$maxSize, $exception->getMessage());
            $this->assertEquals('', $exception->getField()); // Field is not set in constructor, only in context

            $context = $exception->getContext();
            $this->assertEquals($field, $context['field']);
            $this->assertEquals($actualSize, $context['actual']);
            $this->assertEquals($maxSize, $context['max']);
        }
    }

    /**
     * Test solutions array contains expected elements for different exception types
     */
    public function test_solutions_contain_expected_elements(): void
    {
        // Test invalidMimeType solutions
        $mimeException = FileException::invalidMimeType('upload', 'file.pdf', 'image/jpeg');
        $mimeSolutions = $mimeException->getSolutions();
        $this->assertContains("Check the mime type of file 'file.pdf'.", $mimeSolutions);
        $this->assertContains("Check the extension of file 'file.pdf'.", $mimeSolutions);

        // Test invalidExtension solutions
        $extException = FileException::invalidExtension('upload', 'file.jpg', 'png');
        $extSolutions = $extException->getSolutions();
        $this->assertContains("Check the extension of file 'file.jpg'.", $extSolutions);
        $this->assertContains("Check the mime type of file 'file.jpg'.", $extSolutions);

        // Test tooSmall solutions
        $smallException = FileException::tooSmall(1024, 'upload', 512);
        $smallSolutions = $smallException->getSolutions();
        $this->assertContains('Increase the file size to at least 1024 bytes', $smallSolutions);

        // Test tooBig solutions
        $bigException = FileException::tooBig(1024, 'upload', 2048);
        $bigSolutions = $bigException->getSolutions();
        $this->assertContains('Reduce the file size to at most 1024 bytes', $bigSolutions);
    }

    /**
     * Test toArray consistency across different inputs
     */
    public function test_to_array_consistency_across_different_inputs(): void
    {
        $testInputs = [
            [FileException::invalidMimeType('upload', 'file.pdf', 'image/jpeg'), 'invalidMimeType'],
            [FileException::invalidExtension('upload', 'file.jpg', 'png'), 'invalidExtension'],
            [FileException::tooSmall(1024, 'upload', 512), 'tooSmall'],
            [FileException::tooBig(1024, 'upload', 2048), 'tooBig'],
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
            $this->assertEquals(FileException::class, $array['exception']);
        }
    }
}
