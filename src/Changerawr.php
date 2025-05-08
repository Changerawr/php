<?php

namespace Changerawr;

use Changerawr\Api\ChangelogApi;
use Changerawr\Api\EmailApi;
use Changerawr\Api\ProjectsApi;
use Changerawr\Api\SubscribersApi;
use Changerawr\Api\TagsApi;
use Changerawr\Api\WidgetApi;
use Changerawr\Config\Configuration;
use Changerawr\Http\Client;

/**
 * Main client class for interacting with the Changerawr API.
 */
class Changerawr
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ProjectsApi
     */
    private $projectsApi;

    /**
     * @var ChangelogApi
     */
    private $changelogApi;

    /**
     * @var TagsApi
     */
    private $tagsApi;

    /**
     * @var SubscribersApi
     */
    private $subscribersApi;

    /**
     * @var EmailApi
     */
    private $emailApi;

    /**
     * @var WidgetApi
     */
    private $widgetApi;

    /**
     * Create a new Changerawr SDK instance.
     *
     * @param string $apiKey Your Changerawr API key
     * @param string $baseUrl Base URL of your self-hosted Changerawr instance (e.g., 'https://mychangerawr.com')
     */
    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->config = new Configuration($apiKey, $baseUrl);
        $this->httpClient = new Client($this->config);

        // Initialize API interfaces
        $this->projectsApi = new ProjectsApi($this->httpClient);
        $this->changelogApi = new ChangelogApi($this->httpClient);
        $this->tagsApi = new TagsApi($this->httpClient);
        $this->subscribersApi = new SubscribersApi($this->httpClient);
        $this->emailApi = new EmailApi($this->httpClient);
        $this->widgetApi = new WidgetApi($this->httpClient);
    }

    /**
     * Get the projects API.
     *
     * @return ProjectsApi
     */
    public function projects(): ProjectsApi
    {
        return $this->projectsApi;
    }

    /**
     * Get the changelog API.
     *
     * @return ChangelogApi
     */
    public function changelog(): ChangelogApi
    {
        return $this->changelogApi;
    }

    /**
     * Get the tags API.
     *
     * @return TagsApi
     */
    public function tags(): TagsApi
    {
        return $this->tagsApi;
    }

    /**
     * Get the subscribers API.
     *
     * @return SubscribersApi
     */
    public function subscribers(): SubscribersApi
    {
        return $this->subscribersApi;
    }

    /**
     * Get the email API.
     *
     * @return EmailApi
     */
    public function email(): EmailApi
    {
        return $this->emailApi;
    }

    /**
     * Get the widget API.
     *
     * @return WidgetApi
     */
    public function widget(): WidgetApi
    {
        return $this->widgetApi;
    }

    /**
     * Get the configuration.
     *
     * @return Configuration
     */
    public function getConfig(): Configuration
    {
        return $this->config;
    }

    /**
     * Set a custom HTTP client.
     *
     * @param Client $client
     * @return self
     */
    public function setHttpClient(Client $client): self
    {
        $this->httpClient = $client;
        return $this;
    }
}