<?php

namespace Changerawr\Api;

use Changerawr\Exceptions\ApiException;
use Changerawr\Http\Client;

/**
 * API interface for widget functionality.
 */
class WidgetApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new WidgetApi instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get widget loader script for a project.
     *
     * @param string $projectId
     * @return string Widget loader script
     * @throws ApiException
     */
    public function getScript(string $projectId): string
    {
        $path = "/api/integrations/widget/{$projectId}";
        $response = $this->client->get($path);
        return $response->getBody();
    }

    /**
     * Generate embed code for a widget.
     *
     * @param string $projectId
     * @param array $options Widget options
     * @return string HTML code to embed the widget
     */
    public function generateEmbedCode(string $projectId, array $options = []): string
    {
        $baseUrl = $this->client->getConfig()->getBaseUrl();
        $scriptUrl = "{$baseUrl}/api/integrations/widget/{$projectId}";

        $attributes = [
            'src' => $scriptUrl,
            'async' => null
        ];

        $validOptions = [
            'theme' => 'data-theme',
            'position' => 'data-position',
            'maxHeight' => 'data-max-height',
            'popup' => 'data-popup',
            'trigger' => 'data-trigger',
            'maxEntries' => 'data-max-entries'
        ];

        foreach ($validOptions as $option => $attribute) {
            if (isset($options[$option])) {
                // For boolean values, set the attribute without a value
                if (is_bool($options[$option]) && $options[$option]) {
                    $attributes[$attribute] = null;
                } else {
                    $attributes[$attribute] = (string) $options[$option];
                }
            }
        }

        // Build the script tag
        $html = '<script';
        foreach ($attributes as $name => $value) {
            if ($value === null) {
                $html .= " {$name}";
            } else {
                $html .= " {$name}=\"{$value}\"";
            }
        }
        $html .= '></script>';

        return $html;
    }

    /**
     * Generate a button with trigger for popup widget.
     *
     * @param string $projectId
     * @param string $buttonText
     * @param string $buttonId
     * @param array $options Widget options
     * @return string HTML code for button and widget
     */
    public function generatePopupWithButton(
        string $projectId,
        string $buttonText = 'View Changes',
        string $buttonId = 'changelog-trigger',
        array $options = []
    ): string {
        $options = array_merge([
            'popup' => true,
            'position' => 'bottom-right',
            'trigger' => $buttonId
        ], $options);

        $buttonHtml = "<button id=\"{$buttonId}\">{$buttonText}</button>";
        $scriptHtml = $this->generateEmbedCode($projectId, $options);

        return $buttonHtml . "\n" . $scriptHtml;
    }
}