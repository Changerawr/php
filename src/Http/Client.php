<?php

namespace Changerawr\Http;

use Changerawr\Config\Configuration;
use Changerawr\Exceptions\ApiException;
use Changerawr\Exceptions\AuthenticationException;
use Changerawr\Exceptions\NotFoundException;
use Changerawr\Exceptions\ValidationException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * HTTP client for making API requests.
 */
class Client
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * Create a new HTTP client instance.
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->client = new GuzzleClient([
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getTimeout(),
            'headers' => $config->getDefaultHeaders(),
            'http_errors' => false,
        ]);
    }

    /**
     * Send a GET request.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function get(string $endpoint, array $queryParams = [], array $headers = []): Response
    {
        return $this->request('GET', $endpoint, [
            RequestOptions::QUERY => $queryParams,
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * Send a POST request.
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function post(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('POST', $endpoint, [
            RequestOptions::JSON => $data,
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * Send a PUT request.
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function put(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('PUT', $endpoint, [
            RequestOptions::JSON => $data,
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * Send a PATCH request.
     *
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function patch(string $endpoint, array $data = [], array $headers = []): Response
    {
        return $this->request('PATCH', $endpoint, [
            RequestOptions::JSON => $data,
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * Send a DELETE request.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function delete(string $endpoint, array $queryParams = [], array $headers = []): Response
    {
        return $this->request('DELETE', $endpoint, [
            RequestOptions::QUERY => $queryParams,
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * Send a request to the API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return Response
     * @throws ApiException
     */
    private function request(string $method, string $endpoint, array $options = []): Response
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);

            $httpResponse = new Response(
                $response->getStatusCode(),
                $response->getHeaders(),
                (string) $response->getBody()
            );

            if ($httpResponse->isSuccessful()) {
                return $httpResponse;
            }

            $this->handleErrorResponse($httpResponse);
        } catch (ClientException $e) {
            $httpResponse = new Response(
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getHeaders(),
                (string) $e->getResponse()->getBody()
            );

            $this->handleErrorResponse($httpResponse);
        } catch (GuzzleException $e) {
            throw new ApiException('Request failed: ' . $e->getMessage(), 0, $e);
        }

        // This should never be reached, but is needed for static analysis
        throw new ApiException('Unexpected error occurred');
    }

    /**
     * Handle error response from the API.
     *
     * @param Response $response
     * @throws ApiException
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws ValidationException
     */
    private function handleErrorResponse(Response $response): void
    {
        $data = $response->getJson();
        $message = $data['error'] ?? $data['message'] ?? 'Unknown error';
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 400:
                throw new ValidationException($message, $statusCode, null, $data['details'] ?? null);
            case 401:
            case 403:
                throw new AuthenticationException($message, $statusCode);
            case 404:
                throw new NotFoundException($message, $statusCode);
            default:
                throw new ApiException($message, $statusCode);
        }
    }
}