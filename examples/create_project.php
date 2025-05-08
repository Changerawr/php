<?php

// Include the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Changerawr\Changerawr;
use Changerawr\Models\Project;
use Changerawr\Exceptions\ApiException;

// Configuration
$apiKey = 'your_api_key';
$baseUrl = 'https://mychangerawr.com';  // Replace with your self-hosted Changerawr instance URL

// Get project name from command line argument or use default
$projectName = $argv[1] ?? 'New Example Project ' . date('Y-m-d H:i:s');

// Initialize the Changerawr client
$changerawr = new Changerawr($apiKey, $baseUrl);

try {
    echo "Creating a new project: {$projectName}\n";

    // Create a new project
    $project = new Project($projectName);
    $project->setIsPublic(true);
    $project->setAllowAutoPublish(false);
    $project->setRequireApproval(true);
    $project->setDefaultTags(['Feature', 'Bugfix', 'Improvement', 'Breaking Change']);

    // Send to API
    $createdProject = $changerawr->projects()->create($project);

    // Output success
    echo "Project created successfully!\n";
    echo "ID: {$createdProject->getId()}\n";
    echo "Name: {$createdProject->getName()}\n";
    echo "Public: " . ($createdProject->isPublic() ? 'Yes' : 'No') . "\n";
    echo "Default Tags: " . implode(', ', $createdProject->getDefaultTags()) . "\n";
    echo "Created At: " . $createdProject->getCreatedAt()->format('Y-m-d H:i:s') . "\n";

    // Save the project ID to a file for other examples
    file_put_contents(__DIR__ . '/last_project_id.txt', $createdProject->getId());
    echo "Project ID saved to last_project_id.txt for use with other examples.\n";

} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage() . "\n";
    if (method_exists($e, 'getDetails') && $e->getDetails()) {
        echo "Details: " . print_r($e->getDetails(), true) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}