<?php

namespace Changerawr\Api;

use Changerawr\Exceptions\ApiException;
use Changerawr\Http\Client;
use Changerawr\Models\Project;

/**
 * API interface for managing projects.
 */
class ProjectsApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $basePath = '/api/projects';

    /**
     * Create a new ProjectsApi instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of projects.
     *
     * @return Project[]
     * @throws ApiException
     */
    public function list(): array
    {
        $response = $this->client->get($this->basePath);
        $data = $response->getJson();

        $projects = [];
        foreach ($data as $projectData) {
            $projects[] = Project::fromArray($projectData);
        }

        return $projects;
    }

    /**
     * Get a project by ID.
     *
     * @param string $projectId
     * @return Project
     * @throws ApiException
     */
    public function get(string $projectId): Project
    {
        $response = $this->client->get("{$this->basePath}/{$projectId}");
        return Project::fromArray($response->getJson());
    }

    /**
     * Create a new project.
     *
     * @param Project $project
     * @return Project
     * @throws ApiException
     */
    public function create(Project $project): Project
    {
        $response = $this->client->post($this->basePath, $project->toArray());
        return Project::fromArray($response->getJson());
    }

    /**
     * Update a project.
     *
     * @param Project $project
     * @return Project
     * @throws ApiException
     */
    public function update(Project $project): Project
    {
        if ($project->getId() === null) {
            throw new ApiException('Project ID is required for updates');
        }

        $response = $this->client->patch(
            "{$this->basePath}/{$project->getId()}",
            $project->toArray()
        );

        return Project::fromArray($response->getJson());
    }

    /**
     * Delete a project.
     *
     * @param string $projectId
     * @return bool
     * @throws ApiException
     */
    public function delete(string $projectId): bool
    {
        $response = $this->client->delete("{$this->basePath}/{$projectId}");
        return $response->getStatusCode() === 204;
    }

    /**
     * Get project settings.
     *
     * @param string $projectId
     * @return array
     * @throws ApiException
     */
    public function getSettings(string $projectId): array
    {
        $response = $this->client->get("{$this->basePath}/{$projectId}/settings");
        return $response->getJson();
    }

    /**
     * Update project settings.
     *
     * @param string $projectId
     * @param array $settings
     * @return array
     * @throws ApiException
     */
    public function updateSettings(string $projectId, array $settings): array
    {
        $response = $this->client->patch(
            "{$this->basePath}/{$projectId}/settings",
            $settings
        );

        return $response->getJson();
    }

    /**
     * Get project versions.
     *
     * @param string $projectId
     * @return array
     * @throws ApiException
     */
    public function getVersions(string $projectId): array
    {
        $response = $this->client->get("{$this->basePath}/{$projectId}/versions");
        return $response->getJson()['versions'] ?? [];
    }
}