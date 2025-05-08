# Changerawr PHP SDK

This PHP SDK provides a simple and convenient way to interact with the API of your self-hosted Changerawr instance for managing changelogs, subscribers, email notifications, and embed widgets.

## Installation

Install the SDK via Composer:

```bash
composer require changerawr/php
```

## Requirements

- PHP 7.4 or later
- Guzzle HTTP library (automatically installed as a dependency)
- A self-hosted Changerawr instance

## Quick Start

```php
<?php

// Create the Changerawr client with your API key and the URL of your Changerawr instance
$changerawr = new Changerawr\Changerawr('your_api_key', 'https://mychangerawr.com');

// Get a list of projects
$projects = $changerawr->projects()->list();

// Create a new project
$project = new Changerawr\Models\Project('My Awesome Project', true);
$createdProject = $changerawr->projects()->create($project);

// Get changelog entries for a project
$result = $changerawr->changelog()->list($createdProject->getId());
$entries = $result['entries'];

// Create a new changelog entry
$entry = new Changerawr\Models\ChangelogEntry(
    'New Feature: Dark Mode',
    'We have added a new dark mode to the application.\n\n## Features\n- Toggle between light and dark mode\n- Automatic detection of system preferences\n- Customizable theme colors',
    'v1.2.0',
    ['Feature', 'UI/UX']
);

$createdEntry = $changerawr->changelog()->create($createdProject->getId(), $entry);

// Publish the entry
$publishedEntry = $changerawr->changelog()->publish($createdProject->getId(), $createdEntry->getId());
```

## Usage

### Authentication

The SDK uses API key authentication. You can generate an API key from your self-hosted Changerawr instance's admin dashboard.

```php
// Always specify both your API key and the URL of your Changerawr instance
$changerawr = new Changerawr\Changerawr('your_api_key', 'https://mychangerawr.com');
```

### Projects

```php
// Get all projects
$projects = $changerawr->projects()->list();

// Get a specific project
$project = $changerawr->projects()->get('project_id');

// Create a new project
$newProject = new Changerawr\Models\Project('Project Name');
$newProject->setIsPublic(true);
$newProject->setDefaultTags(['Feature', 'Bugfix', 'Improvement']);
$createdProject = $changerawr->projects()->create($newProject);

// Update a project
$project->setName('New Project Name');
$updatedProject = $changerawr->projects()->update($project);

// Delete a project
$changerawr->projects()->delete('project_id');

// Get project settings
$settings = $changerawr->projects()->getSettings('project_id');

// Update project settings
$updatedSettings = $changerawr->projects()->updateSettings('project_id', [
    'isPublic' => true,
    'allowAutoPublish' => false,
    'requireApproval' => true
]);
```

### Changelog Entries

```php
// Get entries for a project
$result = $changerawr->changelog()->list('project_id', [
    'page' => 1,
    'limit' => 10,
    'search' => 'feature'
]);
$entries = $result['entries'];
$pagination = $result['pagination'];

// Get a specific entry
$entry = $changerawr->changelog()->get('project_id', 'entry_id');

// Create a new entry
$entry = new Changerawr\Models\ChangelogEntry(
    'Entry Title',
    'Entry Content in Markdown',
    'v1.0.0'
);
$entry->addTag('Feature');
$createdEntry = $changerawr->changelog()->create('project_id', $entry);

// Update an entry
$entry->setTitle('Updated Title');
$updatedEntry = $changerawr->changelog()->update('project_id', $entry);

// Delete an entry
$changerawr->changelog()->delete('project_id', 'entry_id');

// Publish an entry
$publishedEntry = $changerawr->changelog()->publish('project_id', 'entry_id');

// Unpublish an entry
$unpublishedEntry = $changerawr->changelog()->unpublish('project_id', 'entry_id');

// Get public entries (for widget)
$result = $changerawr->changelog()->getPublic('project_id', [
    'cursor' => 'cursor_value',
    'search' => 'search_term',
    'tags' => 'tag1,tag2',
    'sort' => 'newest'
]);
```

### Tags

```php
// Get tags for a project
$result = $changerawr->tags()->list('project_id', [
    'page' => 1,
    'limit' => 20,
    'search' => 'feature'
]);
$tags = $result['tags'];

// Create a new tag
$tag = new Changerawr\Models\Tag('Feature');
$createdTag = $changerawr->tags()->create($tag);

// Update a tag
$tag->setName('New Feature');
$updatedTag = $changerawr->tags()->update($tag);

// Delete a tag
$changerawr->tags()->delete('tag_id');

// Search for tags
$tags = $changerawr->tags()->search('feat', 5);
```

### Email Notifications

```php
// Get email configuration for a project
$emailConfig = $changerawr->email()->getConfig('project_id');

// Update email configuration
$emailConfig->setEnabled(true);
$emailConfig->setFromName('My App Updates');
$updatedConfig = $changerawr->email()->updateConfig('project_id', $emailConfig);

// Test email configuration
$result = $changerawr->email()->testConfig('project_id', $emailConfig, 'test@example.com');

// Send an email about a specific changelog entry
$result = $changerawr->email()->sendEntry(
    'project_id',
    'entry_id',
    'New Feature: Dark Mode',
    ['specific@example.com'], // optional specific recipients
    ['ALL_UPDATES', 'MAJOR_ONLY'] // optional subscription types
);

// Send a digest email
$result = $changerawr->email()->sendDigest(
    'project_id',
    'Weekly Updates Digest',
    [], // no specific recipients
    ['ALL_UPDATES', 'DIGEST_ONLY'] // subscription types
);
```

### Subscribers

```php
// Get subscribers for a project
$result = $changerawr->subscribers()->list('project_id', [
    'page' => 1,
    'limit' => 20,
    'search' => 'gmail'
]);
$subscribers = $result['subscribers'];

// Get a specific subscriber
$subscriber = $changerawr->subscribers()->get('subscriber_id', 'project_id');

// Create a new subscriber
$subscriber = new Changerawr\Models\Subscriber(
    'user@example.com',
    'John Doe',
    true,
    'ALL_UPDATES'
);
$createdSubscriber = $changerawr->subscribers()->create('project_id', $subscriber);

// Update a subscriber
$result = $changerawr->subscribers()->update('subscriber_id', [
    'name' => 'Jane Doe',
    'isActive' => true,
    'subscriptionType' => 'MAJOR_ONLY'
], 'project_id');

// Delete a subscriber
$changerawr->subscribers()->delete('subscriber_id');

// Subscribe a user (public endpoint)
$changerawr->subscribers()->subscribe(
    'project_id',
    'user@example.com',
    'John Doe',
    'ALL_UPDATES'
);

// Unsubscribe a user (public endpoint)
$changerawr->subscribers()->unsubscribe('unsubscribe_token', 'project_id');
```

### Widget

```php
// Get the widget script
$script = $changerawr->widget()->getScript('project_id');

// Generate embed code for a widget
$embedCode = $changerawr->widget()->generateEmbedCode('project_id', [
    'theme' => 'light',
    'position' => 'bottom-right',
    'maxHeight' => '400px',
    'popup' => true,
    'trigger' => 'changelog-trigger'
]);

// Generate a button with popup widget
$html = $changerawr->widget()->generatePopupWithButton(
    'project_id',
    'View Updates',
    'changelog-button',
    ['theme' => 'dark']
);
```

## Error Handling

The SDK throws exceptions when errors occur. You should wrap your API calls in try-catch blocks to handle these exceptions:

```php
try {
    $projects = $changerawr->projects()->list();
} catch (Changerawr\Exceptions\AuthenticationException $e) {
    // Handle authentication errors (401, 403)
    echo "Authentication error: " . $e->getMessage();
} catch (Changerawr\Exceptions\NotFoundException $e) {
    // Handle not found errors (404)
    echo "Resource not found: " . $e->getMessage();
} catch (Changerawr\Exceptions\ValidationException $e) {
    // Handle validation errors (400)
    echo "Validation error: " . $e->getMessage();
    print_r($e->getValidationErrors());
} catch (Changerawr\Exceptions\ApiException $e) {
    // Handle other API errors
    echo "API error: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle other exceptions
    echo "Error: " . $e->getMessage();
}
```

## Advanced Configuration

You can customize the HTTP client configuration:

```php
$changerawr = new Changerawr\Changerawr('your_api_key');

// Set timeout
$changerawr->getConfig()->setTimeout(60);

// Set user agent
$changerawr->getConfig()->setUserAgent('MyApp/1.0');

// Enable debug mode
$changerawr->getConfig()->setDebug(true);
```

## License

This SDK is licensed under the MIT License. See the LICENSE file for details.

## Support

For support with the PHP SDK, please check the [GitHub repository issues](https://github.com/changerawr/php/issues) or refer to your self-hosted Changerawr instance documentation.