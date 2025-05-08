<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Set up environment variables for testing
if (!getenv('CHANGERAWR_API_KEY')) {
    putenv('CHANGERAWR_API_KEY=test_api_key');
}

if (!getenv('CHANGERAWR_BASE_URL')) {
    putenv('CHANGERAWR_BASE_URL=http://localhost:3000');
}

// Function to get test configuration
function getTestConfig() {
    return [
        'apiKey' => getenv('CHANGERAWR_API_KEY'),
        'baseUrl' => getenv('CHANGERAWR_BASE_URL'),
    ];
}

// Create a mock HTTP response for testing
function createMockResponse($statusCode, $body, $headers = []) {
    return new \Changerawr\Http\Response(
        $statusCode,
        $headers,
        $body instanceof \stdClass || is_array($body) ? json_encode($body) : $body
    );
}

// Create a mock HTTP client for testing
function createMockClient($responses = []) {
    $mock = new \PHPUnit\Framework\MockObject\MockBuilder(
        \PHPUnit\Framework\TestCase::class,
        \Changerawr\Http\Client::class
    );

    $client = $mock->getMock();

    foreach ($responses as $method => $response) {
        $client->method($method)->willReturn($response);
    }

    return $client;
}