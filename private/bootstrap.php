<?php

/**
 * A file that is used to separate the public facing and private file structure, severely reducing the likelihood of
 * any exposure of the application source files to a third party.
 * The file also 'builds' the application, requiring the autoload.php file from Composer's vendor folder, the settings.php file,
 * dependencies.php file, and routes.php file. These files are then used to initiate the Slim app object.
 */

require 'vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';

//if (function_exists('xdebug_start_trace'))
//{
//    xdebug_start_trace();
//}

$container = new \Slim\Container($settings);

require __DIR__ . '/app/dependencies.php';

$app = new \Slim\App($container);

require __DIR__ . '/app/routes.php';

$app->run();

//if (function_exists('xdebug_stop_trace'))
//{
//    xdebug_stop_trace();
//}
