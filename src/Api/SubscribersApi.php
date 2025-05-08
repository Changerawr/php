<?php

namespace Changerawr\Api;

use Changerawr\Exceptions\ApiException;
use Changerawr\Http\Client;
use Changerawr\Models\Subscriber;

/**
 * API interface for managing subscribers.
 */
class SubscribersApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new SubscribersApi instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get subscribers for a project.
     *
     * @param string $projectId
     * @param array $options Query parameters for pagination and search
     * @return array Returns [subscribers, pagination] where subscribers is an array of Subscriber objects
     * @throws ApiException
     */
    public function list(string $projectId, array $options = []): array
    {
        $path = "/api/subscribers";
        $queryParams = array_merge(['projectId' => $projectId], $options);

        $response = $this->client->get($path, $queryParams);
        $data = $response->getJson();

        $subscribers = [];
        foreach ($data['subscribers'] ?? [] as $subscriberData) {
            $subscribers[] = Subscriber::fromArray($subscriberData);
        }

        return [
            'subscribers' => $subscribers,
            'page' => $data['page'] ?? 1,
            'limit' => $data['limit'] ?? 10,
            'totalCount' => $data['totalCount'] ?? 0,
            'totalPages' => $data['totalPages'] ?? 0
        ];
    }

    /**
     * Get a subscriber by ID.
     *
     * @param string $subscriberId
     * @param string|null $projectId Optional project ID for context
     * @return Subscriber
     * @throws ApiException
     */
    public function get(string $subscriberId, ?string $projectId = null): Subscriber
    {
        $path = "/api/subscribers/{$subscriberId}";
        $queryParams = $projectId ? ['projectId' => $projectId] : [];

        $response = $this->client->get($path, $queryParams);
        return Subscriber::fromArray($response->getJson());
    }

    /**
     * Create a new subscriber.
     *
     * @param string $projectId
     * @param Subscriber $subscriber
     * @return Subscriber
     * @throws ApiException
     */
    public function create(string $projectId, Subscriber $subscriber): Subscriber
    {
        $path = "/api/subscribers";

        $data = array_merge(
            $subscriber->toArray(),
            ['projectId' => $projectId]
        );

        $response = $this->client->post($path, $data);
        return Subscriber::fromArray($response->getJson());
    }

    /**
     * Update a subscriber.
     *
     * @param string $subscriberId
     * @param array $data Update data
     * @param string|null $projectId Optional project ID for context
     * @return Subscriber
     * @throws ApiException
     */
    public function update(string $subscriberId, array $data, ?string $projectId = null): Subscriber
    {
        $path = "/api/subscribers/{$subscriberId}";
        $queryParams = $projectId ? ['projectId' => $projectId] : [];

        $response = $this->client->patch($path . '?' . http_build_query($queryParams), $data);
        return Subscriber::fromArray($response->getJson());
    }

    /**
     * Delete a subscriber.
     *
     * @param string $subscriberId
     * @param string|null $projectId Optional project ID to remove only from a specific project
     * @return bool
     * @throws ApiException
     */
    public function delete(string $subscriberId, ?string $projectId = null): bool
    {
        $path = "/api/subscribers/{$subscriberId}";
        $queryParams = $projectId ? ['projectId' => $projectId] : [];

        $response = $this->client->delete($path, $queryParams);
        return $response->isSuccessful();
    }

    /**
     * Subscribe to a project's changelog updates.
     * This is a public endpoint that doesn't require authentication.
     *
     * @param string $projectId
     * @param string $email
     * @param string|null $name
     * @param string $subscriptionType One of: ALL_UPDATES, MAJOR_ONLY, DIGEST_ONLY
     * @return bool
     * @throws ApiException
     */
    public function subscribe(
        string $projectId,
        string $email,
        ?string $name = null,
        string $subscriptionType = 'ALL_UPDATES'
    ): bool {
        $path = "/api/subscribe";

        $data = [
            'projectId' => $projectId,
            'email' => $email,
            'subscriptionType' => $subscriptionType
        ];

        if ($name !== null) {
            $data['name'] = $name;
        }

        $response = $this->client->post($path, $data);
        return $response->isSuccessful();
    }

    /**
     * Unsubscribe from a project's changelog updates.
     * This is a public endpoint that doesn't require authentication.
     *
     * @param string $token Unsubscribe token
     * @param string|null $projectId Optional project ID to unsubscribe only from a specific project
     * @return bool
     * @throws ApiException
     */
    public function unsubscribe(string $token, ?string $projectId = null): bool
    {
        $path = "/api/subscribers/unsubscribe/{$token}";
        $queryParams = $projectId ? ['projectId' => $projectId] : [];

        $response = $this->client->get($path, $queryParams);
        return $response->isSuccessful();
    }

    /**
     * Generate mock subscribers (development only).
     *
     * @param string $projectId
     * @param int $count Number of mock subscribers to generate
     * @return array
     * @throws ApiException
     */
    public function generateMock(string $projectId, int $count = 20): array
    {
        $path = "/api/subscribers/generate-mock";

        $response = $this->client->post($path, [
            'projectId' => $projectId,
            'count' => $count
        ]);

        return $response->getJson();
    }
}