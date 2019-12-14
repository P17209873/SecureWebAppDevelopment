<?php
/**
 * This file declares the necessary dependencies for the Slim Application container, allowing the application to run.
 */
// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['validator'] = function ($container) {
    $validator = new \secureWebAppCoursework\Validator(); // requires "secureWebAppCoursework\\": "secureWebAppCoursework/app/src" in composer.json
    return $validator;
};

$container['soapWrapper'] = function ($container) {
    $soap_wrapper = new \secureWebAppCoursework\SoapWrapper();
    return $soap_wrapper;
};

$container['secureWebAppModel'] = function ($container) {
    $model = new \secureWebAppCoursework\SecureWebAppModel();
    return $model;
};


$container['databaseWrapper'] = function ($container) {
    $database_wrapper = new \secureWebAppCoursework\DatabaseWrapper();
    return $database_wrapper;
};

$container['sqlQueries'] = function ($container) {
    $sql_queries = new \secureWebAppCoursework\SQLQueries();
    return $sql_queries;
};

$container['processOutput'] = function ($container) {
    $output_processor = new \secureWebAppCoursework\ProcessOutput();
    return $output_processor;
};

$container['xmlParser'] = function ($container) {
    $parser = new \secureWebAppCoursework\XmlParser();
    return $parser;
};

$container['monologWrapper'] = function ($container) {
    $logger = new \secureWebAppCoursework\MonologWrapper();
    return $logger;
};

$container['loginModel'] = function ($container) {
    $loginModel = new \secureWebAppCoursework\LoginModel();
    return $loginModel;
};

$container['registrationModel'] = function ($container) {
    $regModel = new \secureWebAppCoursework\RegistrationModel();
    return $regModel;
};

$container['bcryptWrapper'] = function ($container) {
  $bcryptWrapper = new secureWebAppCoursework\BcryptWrapper();
  return $bcryptWrapper;
};