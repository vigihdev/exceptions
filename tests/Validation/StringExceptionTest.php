<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\StringException;
use Vigihdev\Exceptions\Tests\TestCase;

class StringExceptionTest extends TestCase
{
    public function test_too_short_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::tooShort(10, 'short', 'username');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('username', $exception->getMessage());
        $this->assertStringContainsString('10', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('username', $exception->getField());
        $this->assertEquals('short', $exception->getValue());
        $this->assertEquals([
            'min' => 10,
            'actual' => 5,
            'attribute' => 'username',
            'value' => 'short'
        ], $exception->getContext());
        $this->assertContains('Extend the string to at least 10 characters', $exception->getSolutions());
    }

    public function test_too_long_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::tooLong(100, 'very long string that exceeds the limit');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('100', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'max' => 100,
            'actual' => 39,
            'value' => 'very long string that exceeds the limit'
        ], $exception->getContext());
        $this->assertContains('Shorten the string to maximum 100 characters', $exception->getSolutions());
    }

    public function test_not_equal_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::notEqual('expected_value', 'actual_value');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('expected_value', $exception->getMessage());
        $this->assertStringContainsString('actual_value', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'expected' => 'expected_value',
            'actual' => 'actual_value'
        ], $exception->getContext());
        $this->assertContains('Ensure the string matches the expected format: expected_value', $exception->getSolutions());
    }

    public function test_not_match_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::notMatch('/^[a-z]+$/i', '123abc');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('/^[a-z]+$/i', $exception->getMessage());
        $this->assertStringContainsString('123abc', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'pattern' => '/^[a-z]+$/i',
            'actual' => '123abc'
        ], $exception->getContext());
        $this->assertContains('Ensure the string matches the pattern: /^[a-z]+$/i', $exception->getSolutions());
    }

    public function test_empty_not_allowed_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::emptyNotAllowed();

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertEquals('String cannot be empty', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([], $exception->getContext());
        $this->assertContains('Ensure the string has a value', $exception->getSolutions());
    }

    public function test_invalid_characters_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::invalidCharacters('<script>', '<script>alert("xss")</script>');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('<script>', $exception->getMessage());
        $this->assertStringContainsString('invalid characters', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'invalid_chars' => '<script>',
            'actual' => '<script>alert("xss")</script>'
        ], $exception->getContext());
        $this->assertContains('Remove invalid characters: <script>', $exception->getSolutions());
    }

    public function test_missing_required_characters_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::missingRequiredCharacters('@', 'example.com');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('@', $exception->getMessage());
        $this->assertStringContainsString('required characters', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'required' => '@',
            'actual' => 'example.com'
        ], $exception->getContext());
        $this->assertContains('Add required characters: @', $exception->getSolutions());
    }

    public function test_failed_validation_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::failedValidation('alpha_num', 'hello world!');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('alpha_num', $exception->getMessage());
        $this->assertStringContainsString('validation rule', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'rule' => 'alpha_num',
            'actual' => 'hello world!'
        ], $exception->getContext());
        $this->assertContains('Review the validation rule: alpha_num', $exception->getSolutions());
    }

    public function test_not_in_list_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::notInList(['red', 'green', 'blue'], 'yellow');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('red, green, blue', $exception->getMessage());
        $this->assertStringContainsString('yellow', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'allowed' => ['red', 'green', 'blue'],
            'actual' => 'yellow'
        ], $exception->getContext());
        $this->assertContains('Use one of the following values: red, green, blue', $exception->getSolutions());
    }

    public function test_invalid_email_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::invalidEmail('invalid-email');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('invalid-email', $exception->getMessage());
        $this->assertStringContainsString('Invalid email address', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'email' => 'invalid-email'
        ], $exception->getContext());
        $this->assertContains('Ensure email format is correct (example@domain.com)', $exception->getSolutions());
    }

    public function test_invalid_url_creates_exception_with_correct_properties(): void
    {
        $exception = StringException::invalidUrl('not-a-url');

        $this->assertInstanceOf(StringException::class, $exception);
        $this->assertStringContainsString('not-a-url', $exception->getMessage());
        $this->assertStringContainsString('Invalid URL', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals([
            'url' => 'not-a-url'
        ], $exception->getContext());
        $this->assertContains('Ensure URL has correct format (http:// or https://)', $exception->getSolutions());
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $exception = StringException::tooShort(10, 'short', 'username');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertStringContainsString('username', $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('username', $array['field']);
        $this->assertEquals('short', $array['value']);
        $this->assertEquals([
            'min' => 10,
            'actual' => 5,
            'attribute' => 'username',
            'value' => 'short'
        ], $array['context']);
        $this->assertContains('Extend the string to at least 10 characters', $array['solutions']);
        $this->assertEquals(StringException::class, $array['exception']);
    }
}