<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once(APP_PATH.'/src/core/Log.php');
require_once(APP_PATH.'/src/core/DatabaseConnection.php');

class Orders
{
    private $db;
    private static $tableName = 'orders';
    private $accessLoggingEnabled = true;

    /**
     * Constructor for Orders class
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
     * @return void
     * @author Anil Prajapati <anilprz3@gmail.com>
     */
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
     * Method to get 100 latest orders
     * 
     * @param int $limit
     * @param int $offset - for pagination
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public function get_orders($limit = 100, $offset = 0)
    {
        $this->logAccess("Fetching latest orders from table: " . self::$tableName ." with limit: " . $limit);
        return $this->db->from(self::$tableName)
            ->orderBy('id', 'DESC')
            ->limit($limit, $offset)
            ->getAll();
    }

    public function get_order($order_id, $select="*"){
        $this->logAccess("Fetching order with id: " . $order_id);
        return $this->db->from(self::$tableName)
            ->select($select)
            ->where('order_id', $order_id)
            ->get();
    }
}