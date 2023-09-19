# Personal Standalone PHP class based script

## Description
This PHP project template provides a basic structure for developing web applications with PHP. It includes classes for database connection, API communication, and logging. You can use this template as a starting point for your PHP projects.

## Table of Contents

- [Features](#features)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [Directory Structure](#directory-structure)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Database Connection:** The `DatabaseConnection` class allows you to establish a connection to a MySQL database and perform database operations.

- **API Communication:** The `ApiConnection` class enables you to send data to an external PHP webhook using HTTP POST requests.

- **Logging:** The `Log` class provides simple logging functionality for errors, access actions, and API requests. Log files are organized by type and month.

## Getting Started

### Prerequisites

Before using this template, make sure you have the following prerequisites installed:

- PHP 7.0 or higher
- MySQL database (for the `DatabaseConnection` class)

### Installation

1. Clone this repository to your local machine:

   ```bash
   git clone https://github.com/anil3a/php_mysql_api.git
   ```

2. Configure your database credentials in the `DatabaseConfig.php` file.

3. Include the necessary class files in your PHP scripts. See the Usage section for examples.

### Usage
Here's how to use the classes provided by this template in your PHP scripts:

#### Database Connection

```php
// Create a database connection
$db = new DatabaseConnection();

// Fetch data from a table
$data = $db->fetchDataFromTable('your_table_name');

// Close the database connection
$db->closeConnection();
```

#### API Communication
```php
// Create an instance of ApiConnection with the webhook URL
$webhookUrl = "https://example.com/webhook.php"; // Replace with your webhook URL
$apiConnection = new ApiConnection($webhookUrl);

// Data to send to the webhook
$dataToSend = [
    'key1' => 'value1',
    'key2' => 'value2',
];

// Send the data to the webhook
$response = $apiConnection->sendData($dataToSend);
```

#### Logging
You can use the Log class to log errors, access actions, and API requests. Use the enableAccessLogging and disableAccessLogging methods to control access logging.

```php
// Log an error
Log::logError("An error occurred.");

// Log an access action
Log::logAccess("User accessed a resource.");

// Log an API request
$apiConnection->logApiRequest($requestData, $response);

```

For more examples and detailed usage, refer to the code comments and documentation in each class.


### Directory Structure
The project directory structure is as follows:

- `src/`: Contains the PHP class files (e.g., DatabaseConnection.php, ApiConnection.php).
- `logs/`: Stores log files organized by type and month.
- `fetch_staff.php``: Example PHP script using the classes.


## Contributing
Contributions are welcome! If you have any improvements or feature suggestions, please open an issue or submit a pull request.


## License
This project is licensed under the MIT License. See the LICENSE file for details.

