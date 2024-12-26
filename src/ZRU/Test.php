<?php
require __DIR__ . '/Notification.php';
require __DIR__ . '/ZRUClient.php';

// Define the path to your .env file
$envFilePath = dirname(dirname(__DIR__)) . '/env.test';

// Check if the file exists
if (!file_exists($envFilePath)) {
    throw new Exception("Environment file not found: $envFilePath");
}

// Read the file content
$envConfig = file_get_contents($envFilePath);

// Split file content by new lines and set each as an environment variable
$lines = explode("\n", $envConfig);

foreach ($lines as $line) {
    // Ignore empty lines and comments
    if (trim($line) === '' || strpos(trim($line), '#') === 0) {
        continue;
    }

    // Split the line into key and value
    [$key, $value] = explode('=', $line, 2);

    if (!empty($key) && isset($value)) {
        // Set the environment variable
        putenv(trim($key) . '=' . trim($value));
    } elseif (!empty($key) && !isset($value)) {
        throw new Exception("Environment variable $key is missing a value in the .env file.");
    }
}

// List of required environment variables
$requiredVariables = ['API_KEY', 'SECRET_KEY'];

foreach ($requiredVariables as $variable) {
    if (getenv($variable) === false) {
        throw new Exception("Missing required environment variable: $variable");
    }
}

// Now you can access the environment variables
$apiKey = getenv('API_KEY');
$secretKey = getenv('SECRET_KEY');


use ZRU\ZRUClient;
// Example usage
$payload = [
    'id' => '123',
    'status' => 'completed',
    'subscription_status' => 'active',
    'authorization_status' => 'approved',
    'type' => 'P',
    'order_id' => '12345',
    'amount' => '100',
    'action' => 'create',
    'sale_id' => 'sale123',
    'sale_action' => 'processed'
];

$zru = new ZRUClient($apiKey, $secretKey);


$notificationData = new \ZRU\NotificationData($payload, $zru);

// Access instance attributes dynamically
echo "Status: " . $notificationData->transaction . "\n";
echo "Subscription Status: " . $notificationData->subscription_status . "\n";
echo "Authorization Status: " . $notificationData->authorization_status . "\n";
echo "Type: " . $notificationData->type . "\n";
echo "Order ID: " . $notificationData->order_id . "\n";
echo "Amount: " . $notificationData->amount . "\n";
echo "Action: " . $notificationData->action . "\n";
echo "Transaction: " . $notificationData->transaction . "\n";
echo "Subscription: " . $notificationData->subscription . "\n";
echo "Sale: " . $notificationData->sale . "\n";
echo "Sale Action: " . $notificationData->sale_action . "\n";
