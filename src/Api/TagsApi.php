<?php

namespace Changerawr\Api;

use Changerawr\Exceptions\ApiException;
use Changerawr\Http\Client;
use Changerawr\Models\Tag;

/**
 * API interface for managing tags.
 */
class TagsApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new TagsApi instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get tags for a project.
     *
     * @param string $projectId
     * @param array $options Query parameters for pagination and search
     * @return array Returns [tags, pagination] where tags is an array of Tag objects
     * @throws ApiException
     */
    public function list(string $projectId, array $options = []): array
    {
        $path = "/api/projects/{$projectId}/changelog/tags";
        $response = $this->client->get($path, $options);
        $data = $response->getJson();

        $tags = [];
        foreach ($data['tags'] ?? [] as $tagData) {
            $tags[] = Tag::fromArray($tagData);
        }

        return [
            'tags' => $tags,
            'pagination' => $data['pagination'] ?? []
        ];
    }

    /**
     * Create a new tag.
     *
     * @param Tag $tag
     * @return Tag
     * @throws ApiException
     */
    public function create(Tag $tag): Tag
    {
        $path = "/api/changelog/tags";
        $response = $this->client->post($path, $tag->toArray());
        return Tag::fromArray($response->getJson());
    }

    /**
     * Update a tag.
     *
     * @param Tag $tag
     * @return Tag
     * @throws ApiException
     */
    public function update(Tag $tag): Tag
    {
        if ($tag->getId() === null) {
            throw new ApiException('Tag ID is required for updates');
        }

        $path = "/api/changelog/tags/{$tag->getId()}";
        $response = $this->client->patch($path, $tag->toArray());
        return Tag::fromArray($response->getJson());
    }

    /**
     * Delete a tag.
     *
     * @param string $tagId
     * @return bool
     * @throws ApiException
     */
    public function delete(string $tagId): bool
    {
        $path = "/api/changelog/tags/{$tagId}";
        $response = $this->client->delete($path);
        return $response->getStatusCode() === 204;
    }

    /**
     * Get tags used in a project.
     *
     * @param string $projectId
     * @return Tag[]
     * @throws ApiException
     */
    public function getUsedTags(string $projectId): array
    {
        $path = "/api/projects/{$projectId}/tags/used";
        $response = $this->client->get($path);
        $data = $response->getJson();

        $tags = [];
        foreach ($data as $tagData) {
            $tags[] = Tag::fromArray($tagData);
        }

        return $tags;
    }

    /**
     * Search for tags.
     *
     * @param string $query Search query
     * @param int $limit Maximum number of results
     * @return Tag[]
     * @throws ApiException
     */
    public function search(string $query, int $limit = 10): array
    {
        $path = "/api/changelog/tags/search";
        $response = $this->client->get($path, [
            'query' => $query,
            'limit' => $limit
        ]);

        $data = $response->getJson();

        $tags = [];
        foreach ($data as $tagData) {
            $tags[] = Tag::fromArray($tagData);
        }

        return $tags;
    }
}