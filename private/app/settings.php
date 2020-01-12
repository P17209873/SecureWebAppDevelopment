<?php

/**
 * Contains the required settings for the application to run.
 * These settings vary from Development Only (!!!) ini settings, to the WSDL path for the soap server,
 * to the PDO database connection settings
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'swad_coursework.%t');

define('DIRSEP', DIRECTORY_SEPARATOR);

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/style.css';
$js_path = $url_root . '/js/script.js';

define('JS_PATH', $js_path);
define('CSS_PATH', $css_path);
define('APP_NAME', 'Coursework');
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);

define ('BCRYPT_ALGO', PASSWORD_DEFAULT);
define ('BCRYPT_COST', 12);

define ('TEAM_CODE', '18-3110-AC');

define ('LOG_FILE_NAME', 'secureWebApp.log');
define ('LOG_FILE_LOCATION', '../logs/');

define ('SYSTEM_MSISDN', '+447817814149');

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define('WSDL', $wsdl);

$detail_types = ['peekMessages', 'sendMessage'];
define('DETAIL_TYPES', $detail_types);

$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'class_path' => __DIR__ . '/src/',
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]
        ],

        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',//'mysql.tech.dmu.ac.uk',
            'db_name' => 'swadcoursework',//'p17204157',
            'port' => '3306',
            'user_name' => 'coursework',//'p17204157',
            'user_password' => 'password',//'taXes+86',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ]
    ],
];

return $settings;