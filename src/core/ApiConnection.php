<?php
defined('APP_PATH') or exit('No direct script access allowed');

require_once('Log.php');

class ApiConnection
{
    private $webhookUrl;
    private $enablelogging = false;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function sendData($data, $method = 'POST')
    {
        try {
            // Prepare the data to be sent as JSON
            $jsonData = json_encode($data);

            // Set up cURL to make an HTTP POST request to the webhook URL
            $ch = curl_init($this->webhookUrl);

            if($this->enablelogging){
                $verboseFile = fopen( APP_PATH .'/logs/curl_verbose.txt', 'w');
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_STDERR, $verboseFile);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($method === 'POST' || $method === 'PUT') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                Log::logError('cURL error: ' . curl_error($ch));
                return 'cURL error: ' . curl_error($ch);
            }
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
