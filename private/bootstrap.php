<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 08/11/2019
 * Time: 00:37
 */
session_start();

require 'vendor/autoload.php';

$settings = require __DIR__ . '/app/settings/php';

$container =  new \Slim\Container($settings);

require __DIR__ . '/app/dependencies.php';

$app = new \Slim\App($container);

require __DIR__ . '/app/routes.php';

$app->run();

session_regenerate_id(true);