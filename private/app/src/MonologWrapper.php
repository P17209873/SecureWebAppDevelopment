<?php

namespace SecureWebAppCoursework;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonologWrapper
{
    public function __construct(){}

    public function __destruct()
    {
    }

    /**
     * Allows the log type to be set externally, and passed through into the application
     *
     * @param $log_type
     * @return int - returns selected log type in Logger format
     */

    public function setLogType($logType)
    {
        switch ($logType){
            case 'debug':
                return Logger::DEBUG;
                break;
            case 'info':
                return Logger::INFO;
                break;
            case 'warning':
                return Logger::WARNING;
                break;
            case 'error':
                return Logger::ERROR;
                break;
            case 'critical':
                return Logger::CRITICAL;
                break;
            case 'alert':
                return Logger::ALERT;
                break;
            case 'emergency':
                return Logger::EMERGENCY;
                break;
        }
    }

    public function addLogMessage($message, $logType)
    {
        $logger = new Logger('SecureWebAppLogger');
        $logger->pushHandler(new StreamHandler(LOG_FILE_LOCATION . LOG_FILE_NAME, $this->setLogType($logType)));
        $logger->$logType($message);
    }
}
