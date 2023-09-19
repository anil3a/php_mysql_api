<?php
require_once('Log.php');
require_once('DatabaseConnection.php');

class Staff
{
    private $db;
    private static $tableName = 'tblstaff'; // Table name stored as a static property
    private $accessLoggingEnabled = true; // Flag to control access logging

    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    public function fetchUserData()
    {
        try {
            // Sample method to fetch user data using the static table name property
            $userData = $this->db->fetchDataFromTable(self::$tableName);
            
            // Log the access
            $accessMessage = "Fetched user data from table: " . self::$tableName;
            $this->logAccess($accessMessage);

            return $userData;
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