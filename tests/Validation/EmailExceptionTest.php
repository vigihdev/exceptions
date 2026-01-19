<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Tests\Validation;

use Vigihdev\Exceptions\Validation\EmailException;
use Vigihdev\Exceptions\Tests\TestCase;

class EmailExceptionTest extends TestCase
{
    /**
     * Test that invalidEmail creates exception with correct properties
     */
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

    /**
     * Test invalidEmail with different values
     */
    public function test_invalid_email_with_different_values(): void
    {
        $testCases = [
            ['user_email', 'not-an-email'],
            ['contact', '@invalid'],
            ['admin', 'invalid@'],
            ['support', 'user@domain'],
            ['billing', 'user.name@'],
            ['info', '@domain.com'],
        ];

        foreach ($testCases as [$field, $value]) {
            $exception = EmailException::invalidEmail($field, $value);

            $this->assertInstanceOf(EmailException::class, $exception);
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

    /**
     * Test solutions array contains expected elements
     */
    public function test_invalid_email_solutions_contains_expected_elements(): void
    {
        $exception = EmailException::invalidEmail('email', 'invalid');

        $solutions = $exception->getSolutions();

        $this->assertIsArray($solutions);
        $this->assertContains("Check the email format. It should be 'example@domain.com'.", $solutions);
    }

    /**
     * Test edge cases with various invalid email formats
     */
    public function test_invalid_email_edge_cases(): void
    {
        $invalidEmails = [
            '',           // Empty string
            'plainText',  // No @ or domain
            'missing@',   // Missing domain
            '@missing',   // Missing local part
            'space @com', // Space in email
            'double@@com',// Double @
            'back..slash',// Double dot
            'special!char',// Special character
        ];

        foreach ($invalidEmails as $email) {
            $exception = EmailException::invalidEmail('test_field', $email);

            $this->assertInstanceOf(EmailException::class, $exception);
            $this->assertStringContainsString($email, $exception->getMessage());
            $this->assertEquals('test_field', $exception->getField());
            $this->assertEquals($email, $exception->getValue());
        }
    }

    /**
     * Test toArray returns consistent structure with different inputs
     */
    public function test_to_array_consistency_across_different_inputs(): void
    {
        $testInputs = [
            ['email', 'invalid1'],
            ['contact', 'invalid2'],
            ['admin', 'test@'],
        ];

        foreach ($testInputs as [$field, $value]) {
            $exception = EmailException::invalidEmail($field, $value);
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
            $this->assertEquals(EmailException::class, $array['exception']);
            $this->assertEquals($field, $array['field']);
            $this->assertEquals($value, $array['value']);
        }
    }
}