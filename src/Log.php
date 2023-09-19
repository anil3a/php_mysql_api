<?php

class Log
{
    private static $logDirectory = 'logs';

    public static function logError($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] ERROR: $message\n";
        $logFileName = self::getLogFileName('error');

        self::writeLog($logMessage, $logFileName);
    }

    public static function logAccess($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] ACCESS: $message\n";
        $logFileName = self::getLogFileName('access');

        self::writeLog($logMessage, $logFileName);
    }

    public static function clearLog($logType)
    {
        $logFileName = self::getLogFileName($logType);

        try {
            if (file_exists($logFileName)) {
                unlink($logFileName);
            }
        } catch (Exception $e) {
            // Handle any exceptions that occur during log clearing
            echo "Error clearing log file: " . $e->getMessage() . "\n";
        }
    }

    public static function getLogFileName($logType)
    {
        $currentMonthYear = date('Y-m');
        return self::$logDirectory . '/' . $logType . '-' . $currentMonthYear . '.log';
    }

    private static function writeLog($logMessage, $logFile)
    {
        try {
            if (!file_exists(self::$logDirectory)) {
                mkdir(self::$logDirectory, 0777, true);
            }

            if (!file_exists($logFile)) {
                touch($logFile);
                chmod($logFile, 0666);
            }

            file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // Handle any exceptions that occur during logging
            echo "Error writing to log file: " . $e->getMessage() . "\n";
        }
    }
}