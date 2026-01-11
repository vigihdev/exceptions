<?php

declare(strict_types=1);

namespace Vigihdev\Exceptions\Validation;

class UriException extends ValidationException
{

    public static function notFound(string $uri, string $field = '', int $statusCode = 0): self
    {
        $name = $field !== '' ? "for field '{$field}'" : '';
        $message = $statusCode
            ? sprintf("URI %s not found: %s (Status Code: %d)", $name, $uri, $statusCode)
            : sprintf("URI %s not found: %s", $name, $uri);

        return new self(
            message: $message,
            code: 404,
            field: $field,
            context: [
                'uri' => $uri,
                'field' => $field,
                'status_code' => $statusCode,
            ],
            solutions: [
                "Check if the URI is properly formatted",
                "Ensure special characters are URL-encoded",
                "Verify the URI follows RFC 3986 standards"
            ]
        );
    }

    public static function invalid(string $uri, string $field = '', string $reason = ''): self
    {
        $name = $field !== '' ? "for field '{$field}'" : '';
        $message = $reason
            ? sprintf("Invalid URI %s: %s", $name, $reason)
            : sprintf("Invalid URI: %s", $name);

        return new self(
            message: $message,
            code: 400,
            field: $field,
            context: [
                'uri' => $uri,
                'field' => $field,
                'reason' => $reason,
                'filtered' => filter_var($uri, FILTER_VALIDATE_URL)
            ],
            solutions: [
                "Check if the URI is properly formatted",
                "Ensure special characters are URL-encoded",
                "Verify the URI follows RFC 3986 standards"
            ]
        );
    }

    public static function invalidScheme(string $uri, string $scheme, string $field = '', array $allowed = []): self
    {
        $name = $field !== '' ? "for field '{$field}'" : '';

        return new self(
            message: sprintf("Invalid scheme %s for URI: %s", $scheme, $name),
            code: 400,
            field: $field,
            context: [
                'uri' => $uri,
                'field' => $field,
                'scheme' => $scheme,
                'allowed_schemes' => $allowed,
                'common_schemes' => ['http', 'https', 'ftp', 'file', 'data']
            ],
            solutions: [
                "Check if the URI is properly formatted",
                "Ensure special characters are URL-encoded",
                "Verify the URI follows RFC 3986 standards"
            ]
        );
    }

    public static function unsupportedScheme(string $uri, string $scheme, string $field = ''): self
    {
        $name = $field !== '' ? "for field '{$field}'" : '';

        return new self(
            message: sprintf("Unsupported scheme %s for URI: %s", $scheme, $name),
            code: 400,
            field: $field,
            context: [
                'uri' => $uri,
                'field' => $field,
                'scheme' => $scheme,
                'common_schemes' => ['http', 'https', 'ftp', 'file', 'data']
            ],
            solutions: [
                "Check if the URI is properly formatted",
                "Ensure special characters are URL-encoded",
                "Verify the URI follows RFC 3986 standards"
            ]
        );
    }

    public static function malformed(string $uri, string $component, string $field = ''): self
    {
        $name = $field !== '' ? "for field '{$field}'" : '';

        return new self(
            message: sprintf("Malformed URI component %s in: %s", $component, $name),
            code: 400,
            field: $field,
            context: [
                'uri' => $uri,
                'field' => $field,
                'component' => $component
            ],
            solutions: [
                "Check if the URI is properly formatted",
                "Ensure special characters are URL-encoded",
                "Verify the URI follows RFC 3986 standards"
            ]
        );
    }
}
