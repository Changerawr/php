<?php

namespace Changerawr\Api;

use Changerawr\Exceptions\ApiException;
use Changerawr\Http\Client;
use Changerawr\Models\ChangelogEntry;

/**
 * API interface for managing changelog entries.
 */
class ChangelogApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new ChangelogApi instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get changelog entries for a project.
     *
     * @param string $projectId
     * @param array $options Query parameters for filtering, pagination, etc.
     * @return array Returns [entries, pagination] where entries is an array of ChangelogEntry objects
     * @throws ApiException
     */
    public function list(string $projectId, array $options = []): array
    {
        $path = "/api/projects/{$projectId}/changelog";
        $response = $this->client->get($path, $options);
        $data = $response->getJson();

        $entries = [];
        foreach ($data['entries'] ?? [] as $entryData) {
            $entries[] = ChangelogEntry::fromArray($entryData);
        }

        return [
            'entries' => $entries,
            'pagination' => $data['pagination'] ?? [],
            'tags' => $data['tags'] ?? []
        ];
    }

    /**
     * Get a changelog entry by ID.
     *
     * @param string $projectId
     * @param string $entryId
     * @return ChangelogEntry
     * @throws ApiException
     */
    public function get(string $projectId, string $entryId): ChangelogEntry
    {
        $path = "/api/projects/{$projectId}/changelog/{$entryId}";
        $response = $this->client->get($path);
        return ChangelogEntry::fromArray($response->getJson());
    }

    /**
     * Create a new changelog entry.
     *
     * @param string $projectId
     * @param ChangelogEntry $entry
     * @return ChangelogEntry
     * @throws ApiException
     */
    public function create(string $projectId, ChangelogEntry $entry): ChangelogEntry
    {
        $path = "/api/projects/{$projectId}/changelog";
        $response = $this->client->post($path, $entry->toArray());
        return ChangelogEntry::fromArray($response->getJson());
    }

    /**
     * Update a changelog entry.
     *
     * @param string $projectId
     * @param ChangelogEntry $entry
     * @return ChangelogEntry
     * @throws ApiException
     */
    public function update(string $projectId, ChangelogEntry $entry): ChangelogEntry
    {
        if ($entry->getId() === null) {
            throw new ApiException('Entry ID is required for updates');
        }

        $path = "/api/projects/{$projectId}/changelog/{$entry->getId()}";
        $response = $this->client->put($path, $entry->toArray());
        return ChangelogEntry::fromArray($response->getJson());
    }

    /**
     * Delete a changelog entry.
     *
     * @param string $projectId
     * @param string $entryId
     * @return bool
     * @throws ApiException
     */
    public function delete(string $projectId, string $entryId): bool
    {
        $path = "/api/projects/{$projectId}/changelog/{$entryId}";
        $response = $this->client->delete($path);

        // Some DELETE operations return 202 for deferred/pending operations
        return in_array($response->getStatusCode(), [200, 202, 204]);
    }

    /**
     * Publish a changelog entry.
     *
     * @param string $projectId
     * @param string $entryId
     * @return ChangelogEntry
     * @throws ApiException
     */
    public function publish(string $projectId, string $entryId): ChangelogEntry
    {
        $path = "/api/projects/{$projectId}/changelog/{$entryId}";
        $response = $this->client->patch($path, ['action' => 'publish']);
        return ChangelogEntry::fromArray($response->getJson());
    }

    /**
     * Unpublish a changelog entry.
     *
     * @param string $projectId
     * @param string $entryId
     * @return ChangelogEntry
     * @throws ApiException
     */
    public function unpublish(string $projectId, string $entryId): ChangelogEntry
    {
        $path = "/api/projects/{$projectId}/changelog/{$entryId}";
        $response = $this->client->patch($path, ['action' => 'unpublish']);
        return ChangelogEntry::fromArray($response->getJson());
    }

    /**
     * Get public changelog entries for a project.
     * This is used by the widget and doesn't require authentication.
     *
     * @param string $projectId
     * @param array $options Options like cursor, search, tags, sort
     * @return array Returns [project, items, nextCursor]
     * @throws ApiException
     */
    public function getPublic(string $projectId, array $options = []): array
    {
        $path = "/api/changelog/{$projectId}/entries";
        $response = $this->client->get($path, $options);
        $data = $response->getJson();

        $entries = [];
        foreach ($data['items'] ?? [] as $entryData) {
            $entries[] = ChangelogEntry::fromArray($entryData);
        }

        return [
            'project' => $data['project'] ?? [],
            'items' => $entries,
            'nextCursor' => $data['nextCursor'] ?? null
        ];
    }
}