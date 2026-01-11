<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\EmailException;
use Vigihdev\Exceptions\Tests\TestCase;

class EmailExceptionTest extends TestCase
{
    public function test_invalid_email_creates_exception_with_correct_properties(): void
    {
        $exception = EmailException::invalidEmail('email', 'invalid-email');

        $this->assertInstanceOf(EmailException::class, $exception);
        $this->assertEquals("Email 'invalid-email' is not a valid email for field 'email'.", $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals('email', $exception->getField());
        $this->assertEquals('invalid-email', $exception->getValue());
        $this->assertEquals([
            'field' => 'email',
            'value' => 'invalid-email',
        ], $exception->getContext());
        $this->assertEquals([
            "Check the email format. It should be 'example@domain.com'.",
        ], $exception->getSolutions());
    }

    public function test_invalid_email_with_different_values(): void
    {
        $exception = EmailException::invalidEmail('user_email', 'not-an-email');

        $this->assertEquals("Email 'not-an-email' is not a valid email for field 'user_email'.", $exception->getMessage());
        $this->assertEquals('user_email', $exception->getField());
        $this->assertEquals('not-an-email', $exception->getValue());
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $exception = EmailException::invalidEmail('email', 'invalid');

        $array = $exception->toArray();
        
        $this->assertIsArray($array);
        $this->assertEquals("Email 'invalid' is not a valid email for field 'email'.", $array['message']);
        $this->assertEquals(400, $array['code']);
        $this->assertEquals('email', $array['field']);
        $this->assertEquals('invalid', $array['value']);
        $this->assertEquals([
            'field' => 'email',
            'value' => 'invalid',
        ], $array['context']);
        $this->assertEquals([
            "Check the email format. It should be 'example@domain.com'.",
        ], $array['solutions']);
        $this->assertEquals(EmailException::class, $array['exception']);
    }
}