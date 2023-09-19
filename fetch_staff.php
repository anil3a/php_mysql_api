<?php
// Include the Log class
require_once('src/Log.php');

// Include the DatabaseConfig class
require_once('src/DatabaseConfig.php');

// Include the DatabaseConnection class
require_once('src/DatabaseConnection.php');

// Include the ApiConnection class
require_once('src/ApiConnection.php');

// Include the User class
require_once('src/Staff.php');

// Usage example
try {
    // Create a database connection
    $db = new DatabaseConnection();

    // Create a User object
    $user = new Staff($db);

    // Fetch user data
    $userData = $user->fetchUserData();

    // Close the database connection
    $db->closeConnection();

    // Usage example
    echo "User Data:<br>";
    foreach ($userData as $row) {
        echo "ID: " . $row["staffid"] . " - FirstName: " . $row["firstname"] . " - LastName: " . $row["lastname"]  . "<br>". PHP_EOL;
    }
} catch (Exception $e) {
    // Handle the exception as needed
    echo "An error occurred: " . $e->getMessage();
}