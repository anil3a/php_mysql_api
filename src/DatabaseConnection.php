<?php
require_once('Log.php');
require_once('DatabaseConfig.php');

class DatabaseConnection
{
    private $conn;
    private $accessLoggingEnabled = true; // Flag to control access logging

    public function __construct()
    {
        try {
            // Create a connection to the database using the credentials from DatabaseConfig
            $this->conn = new mysqli(
                DatabaseConfig::$host,
                DatabaseConfig::$username,
                DatabaseConfig::$password,
                DatabaseConfig::$database
            );

            // Check if the connection was successful
            if ($this->conn->connect_error) {
                $errorMessage = "Connection failed: " . $this->conn->connect_error;
                Log::logError($errorMessage);
                throw new Exception($errorMessage);
            }
            $this->logAccess("Database connection established.");
        } catch (Exception $e) {
            // Log the exception
            Log::logError($e->getMessage());
            throw $e;
        }
    }

    public function fetchDataFromTable($tableName)
    {
        try {
            // SQL query to fetch data
            $sql = "SELECT * FROM $tableName";

            // Execute the query
            $result = $this->conn->query($sql);

            // Check if the query was successful
            if (!$result) {
                $errorMessage = "Error executing query: " . $this->conn->error;
                Log::logError($errorMessage);
                throw new Exception($errorMessage);
            }

            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $this->logAccess("Fetched data from table: $tableName");
                return $data;
            } else {
                $this->logAccess("No data fetched from table: $tableName");
                return [];
            }
        } catch (Exception $e) {
            // Log the exception
            Log::logError($e->getMessage());
            throw $e;
        }
    }

    public function closeConnection()
    {
        try {
            // Close the database connection
            if ($this->conn) {
                $this->conn->close();
                $this->logAccess("Database connection closed.");
            }
        } catch (Exception $e) {
            // Log the exception
            Log::logError($e->getMessage());
            throw $e;
        }
    }

    public function enableAccessLogging()
    {
        $this->accessLoggingEnabled = true;
    }

    public function disableAccessLogging()
    {
        $this->accessLoggingEnabled = false;
    }

    private function logAccess($message)
    {
        if ($this->accessLoggingEnabled) {
            Log::logAccess($message);
        }
    }
}


