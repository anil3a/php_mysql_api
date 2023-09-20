<?php
require_once('config.php');
require_once('app.php');

$app = new App();

// Check if at least one argument (method name) is provided
if ($argc >= 2) {
    $method = $argv[1]; // Method name

    if (method_exists($app, $method)) {
        if (method_exists($app, $method) && $argc >= 3) {
            // Call the method with two parameters if they are provided
            $param1 = $argv[2];
            $param2 = $argv[3];
            $result = call_user_func([$app, $method], $param1, $param2);
        } else {
            // Call the method with no parameters
            $result = call_user_func([$app, $method]);
        }
        echo "Method $method returned: " . var_export($result, true) . "\n";
    } else {
        echo "Method not found: $method\n";
    }
} else {
    echo "Usage: php cli.php method_name [parameter1] [parameter2]\n";
}