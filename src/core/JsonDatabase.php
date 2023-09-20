<?php
defined('APP_PATH') or exit('No direct script access allowed');

class JsonDatabase
{
    private $filePath;
    private $defaultDirectory = 'data'; // Default directory
    private $defaultFileName = 'data.json'; // Default filename

    public function __construct($directory = null, $fileName = null)
    {
        $directory = $directory ?? $this->defaultDirectory;
        $fileName = $fileName ?? $this->defaultFileName;
        $this->filePath = rtrim($directory, '/') . '/' . $fileName;
    }

    public function read()
    {
        $this->acquireLock();

        if (!file_exists($this->filePath)) {
            $this->releaseLock();
            return [];
        }

        $jsonContent = file_get_contents($this->filePath);
        $this->releaseLock();

        return json_decode($jsonContent, true);
    }

    public function write($data)
    {
        $this->acquireLock();

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonData);

        $this->releaseLock();
    }

    public function insert($record)
    {
        $this->acquireLock();

        $data = $this->read();
        $data[] = $record;
        $this->write($data);

        $this->releaseLock();
    }

    public function update($index, $record)
    {
        $this->acquireLock();

        $data = $this->read();
        if (isset($data[$index])) {
            $data[$index] = $record;
            $this->write($data);
            $this->releaseLock();
            return true;
        }

        $this->releaseLock();
        return false;
    }

    public function delete($index)
    {
        $this->acquireLock();

        $data = $this->read();
        if (isset($data[$index])) {
            unset($data[$index]);
            $this->write(array_values($data)); // Reindex the array
            $this->releaseLock();
            return true;
        }

        $this->releaseLock();
        return false;
    }

    private function acquireLock()
    {
        $lockFile = $this->filePath . '.lock';
        $lockHandle = fopen($lockFile, 'w');
        flock($lockHandle, LOCK_EX);
    }

    private function releaseLock()
    {
        $lockFile = $this->filePath . '.lock';
        flock($lockHandle, LOCK_UN);
        fclose($lockHandle);
        unlink($lockFile);
    }
}
