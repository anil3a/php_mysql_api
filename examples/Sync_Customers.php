<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once( APP_PATH .'/src/model/Customers.php');
require_once( APP_PATH .'/src/core/JsonDatabase.php');

class Sync_CustomersTest {

    public function __construct()
    {
        // empty
    }

    public function get_my_customer()
    {
        // Usage example
        try {
            $dbcustomer = new Customers();
            $customer = $dbcustomer->get_customer(698761723921023432423432423234);
        } catch (Exception $e) {
            // Handle the exception as needed
            echo "An error occurred: " . $e->getMessage();
        }

        echo '<pre>';
        echo '<br>';
        echo '<br>Debugging customer';
        echo '<br>';
        print_r($customer);
        echo '<br>';
        echo '<br>';
        echo '</pre>';
        die;
    }

    public function store_customer_fetched()
    {
        // Use the default directory and filename
        $database = new JsonDatabase();

        // OR, specify a custom directory and filename
        // $customDirectory = 'custom_data';
        // $customFileName = 'custom.json';
        // $database = new JsonDatabase($customDirectory, $customFileName);


        // Reading Data
        $data = $database->read(); // Acquires a lock

        // Simulate some processing
        sleep(5); // Sleep for 5 seconds to simulate work

        // Writing Data
        $newRecord = ['id' => 1, 'name' => 'John'];
        $database->insert($newRecord); // Acquires a lock to write

        // Release the lock automatically when the operation is done

        // Simulate more processing
        sleep(5); // Sleep for 5 seconds to simulate more work

        // Continue working with the data (e.g., update or delete)

        // When the operations are complete, the lock is automatically released
    }
}