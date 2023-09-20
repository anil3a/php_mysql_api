<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once('Log.php');
require_once('DatabaseConfig.php');

class DatabaseConnection
{
    private $conn;
    private $accessLoggingEnabled = true;
    private $select = '*';
    private $from = '';
    private $join = '';
    private $joinType = '';
    private $where = '';
    private $orderBy = '';
    private $groupBy = '';
    private $limit = '';

    /**
     * Constructor for DatabaseConnection class
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
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

    /**
     * Method to enable access logging
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function enableAccessLogging()
    {
        $this->accessLoggingEnabled = true;
    }

    /**
     * Method to disable access logging
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function disableAccessLogging()
    {
        $this->accessLoggingEnabled = false;
    }

    /**
     * Method to log access messages
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    private function logAccess($message)
    {
        if ($this->accessLoggingEnabled) {
            Log::logAccess($message);
        }
    }

    /**
     * Method to fetch data from a table
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
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

    /**
     * Method to close the database connection
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
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

    /**
     * Method to set SELECT query
     *
     * @param string $columns
     * @return DatabaseConnection
     */
    public function select($columns = '*')
    {
        if (is_array($columns)) {
            $this->select = implode(', ', $columns);
        } else {
            $this->select = $columns;
        }
        return $this;
    }

    /**
     * Method to set FROM query
     * 
     * @param string $table
     * @return DatabaseConnection
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    /**
     * Method to set JOIN query
     * 
     * @param string $table
     * @param string $onCondition
     * @return DatabaseConnection
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function join($table, $onCondition)
    {
        $this->joinType = ''; // Reset to default (INNER JOIN)
        $this->join = "JOIN $table ON $onCondition";
        return $this;
    }

    /**
     * Method to set LEFT JOIN query
     * 
     * @param string $table
     * @param string $onCondition
     * @return DatabaseConnection
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function leftJoin($table, $onCondition)
    {
        $this->joinType = 'LEFT'; // Set to LEFT JOIN
        $this->join = "LEFT JOIN $table ON $onCondition";
        return $this;
    }

    /**
     * Method to set WHERE query
     * 
     * @param string $condition
     * @return DatabaseConnection
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
    public function where($condition)
    {
        $this->where = "WHERE $condition";
        return $this;
    }

    /**
     * Method to set ORDER BY query
     * 
     * @param string $column
     * @param string $order
     * @return DatabaseConnection
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
    public function orderBy($column, $order = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $order";
        return $this;
    }

    /**
     * Method to set GROUP BY query
     * 
     * @param string $column
     * @return DatabaseConnection
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
    public function groupBy($column)
    {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    /**
     * Method to set LIMIT query
     * 
     * @param int $limit
     * @param int $offset
     * @return DatabaseConnection
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
    public function limit($limit, $offset = null)
    {
        if ($offset !== null) {
            $this->limit = "LIMIT $offset, $limit";
        } else {
            $this->limit = "LIMIT $limit";
        }
        return $this;
    }

    /**
     * Method to execute the query and fetch data
     * 
     * @return array
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
    public function getAll()
    {
        // $joinClause = $this->joinType === 'LEFT' ? 'LEFT JOIN' : 'JOIN';
        $sql = "SELECT {$this->select} FROM {$this->from} {$this->join} {$this->where} {$this->groupBy} {$this->orderBy} {$this->limit}";

        $result = $this->conn->query($sql);
        if (!$result) { return []; }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get()
    {
        $this->limit(1); // Limit to one row
        $data = $this->getAll();
        return !empty($data) ? $data[0] : [];
    }
}
