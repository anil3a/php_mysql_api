<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once(APP_PATH.'/src/core/Log.php');
require_once(APP_PATH.'/src/core/DatabaseConnection.php');

class Customers
{
    private $db;
    private static $tableName = 'customers';
    private $accessLoggingEnabled = true;

    /**
     * Constructor for Customers class
     *
     * @param DatabaseConnection $db
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function __construct(DatabaseConnection $db = null)
    {
        if($db === null) {
            $this->db = new DatabaseConnection();
        } else {
            $this->db = $db;
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
     * Method to get 100 latest customers
     * 
     * @param int $limit
     * @param int $offset - for pagination
     * @return array
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function get_customers($limit = 100, $offset = 0)
    {
        $this->logAccess("Fetching latest customers from table: " . self::$tableName ." with limit: " . $limit);
        return $this->db->from(self::$tableName)
            ->orderBy('id', 'DESC')
            ->limit($limit, $offset)
            ->getAll();
    }

    /**
     * Method to get a customer by id
     * 
     * @param int|bigint|string $customer_id
     * @param string $select - comma separated column names
     * @return array
     * 
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function get_customer($customer_id, $select="*"){
        $this->logAccess("Fetching customer with id: " . number_format($customer_id, 0, '', ''));
        return $this->db->from(self::$tableName)
            ->select($select)
            ->where('customer_id', $customer_id)
            ->get();
    }
}