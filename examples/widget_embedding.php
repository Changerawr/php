<?php

// Include the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Changerawr\Changerawr;
use Changerawr\Exceptions\ApiException;

// Get the project ID from the URL parameter
$projectId = $_GET['project_id'] ?? null;

if (!$projectId) {
    die('Project ID is required. Use ?project_id=YOUR_PROJECT_ID in the URL.');
}

// Set your API key and the URL where Changerawr is hosted
$apiKey = 'your_api_key';
$baseUrl = 'https://mychangerawr.com';  // Replace with your self-hosted Changerawr instance URL

// Create the Changerawr client
$changerawr = new Changerawr($apiKey, $baseUrl);

try {
    // Different widget configuration examples
    $inlineWidget = $changerawr->widget()->generateEmbedCode($projectId, [
        'theme' => 'light',
        'maxHeight' => '400px'
    ]);

    $popupWidget = $changerawr->widget()->generatePopupWithButton(
        $projectId,
        'View Changelog',
        'changelog-button',
        [
            'theme' => 'light',
            'position' => 'bottom-right'
        ]
    );

    $darkModeWidget = $changerawr->widget()->generateEmbedCode($projectId, [
        'theme' => 'dark',
        'maxHeight' => '400px'
    ]);

    $immediatePopupWidget = $changerawr->widget()->generateEmbedCode($projectId, [
        'popup' => true,
        'position' => 'bottom-right',
        'trigger' => 'immediate',
        'theme' => 'light'
    ]);
} catch (ApiException $e) {
    die('API Error: ' . $e->getMessage());
} catch (\Exception $e) {
    die('Error: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changerawr Widget Examples</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .example {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .code {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-family: monospace;
            margin-bottom: 20px;
        }
        .dark-mode {
            background: #222;
            color: #eee;
        }
        .dark-mode .code {
            background: #333;
            color: #eee;
        }
        .widget-area {
            min-height: 200px;
            padding: 20px;
            border: 1px dashed #ccc;
            margin-top: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<h1>Changerawr Widget Examples</h1>
<p>This page demonstrates different ways to embed the Changerawr widget in your website.</p>

<div class="example">
    <h2>Basic Inline Widget</h2>
    <p>This widget is displayed inline on the page with light theme.</p>
    <div class="code"><?= htmlspecialchars($inlineWidget) ?></div>
    <div class="widget-area">
        <h3>Widget Preview:</h3>
        <?= $inlineWidget ?>
    </div>
</div>

<div class="example">
    <h2>Popup Widget with Button</h2>
    <p>This widget appears as a popup when a button is clicked.</p>
    <div class="code"><?= htmlspecialchars($popupWidget) ?></div>
    <div class="widget-area">
        <h3>Widget Preview:</h3>
        <?= $popupWidget ?>
    </div>
</div>

<div class="example dark-mode">
    <h2>Dark Mode Widget</h2>
    <p>This widget uses the dark theme to match dark mode interfaces.</p>
    <div class="code"><?= htmlspecialchars($darkModeWidget) ?></div>
    <div class="widget-area">
        <h3>Widget Preview:</h3>
        <?= $darkModeWidget ?>
    </div>
</div>

<div class="example">
    <h2>Immediate Popup Widget</h2>
    <p>This widget appears automatically as a popup when the page loads.</p>
    <div class="code"><?= htmlspecialchars($immediatePopupWidget) ?></div>
    <div class="widget-area">
        <h3>Widget Preview:</h3>
        <?= $immediatePopupWidget ?>
    </div>
</div>

<p>
    <strong>Note:</strong> For these examples to work properly, you need to:
<ol>
    <li>Replace the API key and base URL in the PHP code</li>
    <li>Use a valid project ID in the URL query parameter</li>
    <li>Ensure your Changerawr project is set to public</li>
</ol>
</p>
</body>
</html>