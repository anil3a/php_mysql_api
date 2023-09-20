<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once( APP_PATH .'/src/model/Customers.php');

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
}