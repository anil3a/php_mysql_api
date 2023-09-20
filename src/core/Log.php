<?php
defined('APP_PATH') or exit('No direct script access allowed');

/**
 * Log Class create files in logs folder for access if turned on by each classes and for any error logs
 *
 * @author Anil Prajapati <anilprz3@gmail.com>
 **/
class Log
{
    private static $logDirectory = APP_PATH . '/logs';

    /**
     * Method to log error messages
     * Use this method to log error message
     * 
     * @param string $message
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public static function logError($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] ERROR: $message\n";
        $logFileName = self::getLogFileName('error');

        self::writeLog($logMessage, $logFileName);
    }

    /**
     * Method to log access messages
     * Use this method to log access message
     * 
     * @param string $message
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
    public static function logAccess($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] ACCESS: $message\n";
        $logFileName = self::getLogFileName('access');

        self::writeLog($logMessage, $logFileName);
    }

    /**
     * Method to clear log files
     *
     * @author Anil Prajapati <anilprz3@gmail.com>
     **/
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

    /**
     * Method to get log file name
     * 
     * @param string $logType
     * @return string
     */
    public static function getLogFileName($logType)
    {
        $currentMonthYear = date('Y-m');
        return self::$logDirectory . '/' . $logType . '-' . $currentMonthYear . '.log';
    }

    /**
     * Method to write log
     * 
     * @param string $logMessage
     * @param string $logFile
     */
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