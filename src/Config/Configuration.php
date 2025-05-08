<?php

namespace Changerawr\Config;

/**
 * Configuration class for the Changerawr SDK.
 */
class Configuration
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var int
     */
    private $timeout = 30;

    /**
     * @var string
     */
    private $userAgent = 'Changerawr-PHP-SDK/0.1.0';

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * Create a new Configuration instance.
     *
     * @param string $apiKey Your Changerawr API key
     * @param string $baseUrl Base URL for the API
     */
    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Get the API key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set the API key.
     *
     * @param string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get the base URL.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Set the base URL.
     *
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * Get the timeout in seconds.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set the timeout in seconds.
     *
     * @param int $timeout
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Get the user agent.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * Set the user agent.
     *
     * @param string $userAgent
     * @return self
     */
    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Get the debug mode.
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Set the debug mode.
     *
     * @param bool $debug
     * @return self
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Get the authorization header for API requests.
     *
     * @return array
     */
    public function getAuthorizationHeader(): array
    {
        return ['Authorization' => 'Bearer ' . $this->apiKey];
    }

    /**
     * Get the default headers for API requests.
     *
     * @return array
     */
    public function getDefaultHeaders(): array
    {
        return array_merge(
            $this->getAuthorizationHeader(),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => $this->userAgent
            ]
        );
    }
}