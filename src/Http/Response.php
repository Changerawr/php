<?php

namespace Changerawr\Http;

/**
 * Wrapper for HTTP responses.
 */
class Response
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var mixed|null
     */
    private $decoded = null;

    /**
     * Create a new Response instance.
     *
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     */
    public function __construct(int $statusCode, array $headers, string $body)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get a specific header.
     *
     * @param string $name
     * @return string|null
     */
    public function getHeader(string $name): ?string
    {
        $normalizedName = strtolower($name);

        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $normalizedName) {
                return is_array($value) ? implode(', ', $value) : $value;
            }
        }

        return null;
    }

    /**
     * Get the raw body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get the decoded JSON body.
     *
     * @param bool $assoc Whether to return associative arrays instead of objects
     * @return mixed
     */
    public function getJson(bool $assoc = true)
    {
        if ($this->decoded === null) {
            $this->decoded = json_decode($this->body, $assoc);
        }

        return $this->decoded;
    }

    /**
     * Check if the response is successful (2xx status code).
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Check if the response has a client error (4xx status code).
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * Check if the response has a server error (5xx status code).
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500;
    }
}