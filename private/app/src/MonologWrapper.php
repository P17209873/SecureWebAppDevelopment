<?php

namespace SecureWebAppCoursework;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonologWrapper
{
    private $log;

    public function __construct()
    {
        $log = new Logger('logger');
    }

    public function __destruct(){}

    /**
     * Allows the log type to be set externally, and passed through into the application
     *
     * @param $log_type
     * @return int - returns selected log type in Logger format
     */
    public function setLogType($log_type){
        switch ($log_type){
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
}