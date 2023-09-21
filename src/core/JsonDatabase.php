<?php
defined('APP_PATH') or exit('No direct script access allowed');

class JsonDatabase
{
    private $filePath;
    private $defaultDirectory = APP_PATH . '/data'; // Default directory
    private $defaultFileName = 'data.json'; // Default filename
    private $lockHandle;

    public function __construct($fileName = null, $directory = null)
    {
        $directory = $directory ?? $this->defaultDirectory;
        $fileName = $fileName ?? $this->defaultFileName;
        $this->filePath = rtrim($directory, '/') . '/' . $fileName;
    }

    public function read()
    {
        // Check if the directory exists
        if (!is_dir(dirname($this->filePath))) {
            return [];
        }

        // Acquire a lock manually before reading
        $this->acquireLock();

        // Check if the file exists
        if (!file_exists($this->filePath)) {
            $this->releaseLock();
            return [];
        }

        $jsonContent = file_get_contents($this->filePath);

        $this->releaseLock();

        return json_decode($jsonContent, true);
    }

    public function insert($data)
    {
        // Check if the directory exists; if not, create it
        $directory = dirname($this->filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Acquire a lock manually before writing
        $this->acquireLock();

        // Create the lock file if it doesn't exist
        $this->ensureLockFileExists();

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonData);

        $this->releaseLock();

        return true;
    }

    // Manual lock acquisition
    public function acquireLock()
    {
        if (!$this->lockHandle) {
            $lockFile = $this->filePath . '.lock';

            // Create the lock file if it doesn't exist
            $this->ensureLockFileExists();

            $this->lockHandle = fopen($lockFile, 'w');
            flock($this->lockHandle, LOCK_EX);
        }
    }

    // Manual lock release
    public function releaseLock()
    {
        if ($this->lockHandle) {
            flock($this->lockHandle, LOCK_UN);
            fclose($this->lockHandle);
            $this->lockHandle = null;
        }
    }

    // Ensure the lock file exists
    private function ensureLockFileExists()
    {
        $lockFile = $this->filePath . '.lock';

        if (!file_exists($lockFile)) {
            touch($lockFile);
        }
    }
}
