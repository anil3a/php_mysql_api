<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once('Log.php');

class ApiConnection
{
    private $webhookUrl;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function sendData($data)
    {
        try {
            // Prepare the data to be sent as JSON
            $jsonData = json_encode($data);

            // Set up cURL to make an HTTP POST request to the webhook URL
            $ch = curl_init($this->webhookUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new Exception("cURL error: " . curl_error($ch));
            }

            // Close cURL
            curl_close($ch);

            // Log the successful API request
            $this->logApiRequest($data, $response);

            return $response;
        } catch (Exception $e) {
            // Log the exception
            Log::logError($e->getMessage());
            throw $e;
        }
    }

    private function logApiRequest($requestData, $response)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] API Request:\n" . json_encode($requestData) . "\n";
        $logMessage .= "[$timestamp] API Response:\n" . $response . "\n";

        Log::logAccess($logMessage);
    }
}
