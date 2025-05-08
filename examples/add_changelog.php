<?php

// Include the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Changerawr\Changerawr;
use Changerawr\Models\ChangelogEntry;
use Changerawr\Exceptions\ApiException;

// Configuration
$apiKey = 'your_api_key';
$baseUrl = 'https://mychangerawr.com';  // Replace with your self-hosted Changerawr instance URL

// Try to get project ID from saved file or use from command line
$projectIdFile = __DIR__ . '/last_project_id.txt';
if (file_exists($projectIdFile)) {
    $projectId = trim(file_get_contents($projectIdFile));
} else {
    $projectId = $argv[1] ?? null;
    if (!$projectId) {
        die("Please provide a project ID as a command line argument or run create_project.php first.\n");
    }
}

// Get version from command line or use default
$version = $argv[2] ?? ('v1.' . mt_rand(0, 9) . '.' . mt_rand(0, 9));

// Initialize the Changerawr client
$changerawr = new Changerawr($apiKey, $baseUrl);

try {
    echo "Adding a changelog entry to project {$projectId} with version {$version}\n";

    // Sample markdown content for the changelog
    $content = <<<MARKDOWN
## What's New

We're excited to share the latest updates to our product:

### New Features
- Added dark mode support
- Implemented new dashboard layout
- Added export to PDF functionality

### Improvements
- Enhanced performance for search queries
- Updated UI components for better accessibility
- Refined documentation

### Bug Fixes
- Fixed login issues on certain browsers
- Resolved data synchronization problems
- Fixed layout issues on mobile devices
MARKDOWN;

    // Create changelog entry
    $entry = new ChangelogEntry(
        "New Release: {$version}",
        $content,
        $version
    );

    // Add some tags
    $entry->addTag('Feature');
    $entry->addTag('Improvement');
    $entry->addTag('Bugfix');

    // Send to API
    $createdEntry = $changerawr->changelog()->create($projectId, $entry);

    // Output success
    echo "Changelog entry created successfully!\n";
    echo "ID: {$createdEntry->getId()}\n";
    echo "Title: {$createdEntry->getTitle()}\n";
    echo "Version: {$createdEntry->getVersion()}\n";
    echo "Tags: " . implode(', ', array_map(function($tag) {
            return $tag['name'] ?? $tag;
        }, $createdEntry->getTags())) . "\n";
    echo "Created At: " . $createdEntry->getCreatedAt()->format('Y-m-d H:i:s') . "\n";

    // Ask if user wants to publish the entry
    echo "\nWould you like to publish this entry? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    if (strtolower($line) === 'y') {
        $publishedEntry = $changerawr->changelog()->publish($projectId, $createdEntry->getId());
        echo "Entry published successfully at " .
            $publishedEntry->getPublishedAt()->format('Y-m-d H:i:s') . "\n";
    } else {
        echo "Entry saved as draft.\n";
    }

    // Save the entry ID to a file for other examples
    file_put_contents(__DIR__ . '/last_entry_id.txt', $createdEntry->getId());
    echo "Entry ID saved to last_entry_id.txt for use with other examples.\n";

} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage() . "\n";
    if (method_exists($e, 'getDetails') && $e->getDetails()) {
        echo "Details: " . print_r($e->getDetails(), true) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}