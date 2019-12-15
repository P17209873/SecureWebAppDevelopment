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
    $validator = new \SecureWebAppCoursework\Validator(); // requires "secureWebAppCoursework\\": "secureWebAppCoursework/app/src" in composer.json
    return $validator;
};

$container['soapWrapper'] = function ($container) {
    $soap_wrapper = new \SecureWebAppCoursework\SoapWrapper();
    return $soap_wrapper;
};

$container['secureWebAppModel'] = function ($container) {
    $model = new \SecureWebAppCoursework\SecureWebAppModel();
    return $model;
};


$container['databaseWrapper'] = function ($container) {
    $database_wrapper = new \SecureWebAppCoursework\DatabaseWrapper();
    return $database_wrapper;
};

$container['sqlQueries'] = function ($container) {
    $sql_queries = new \SecureWebAppCoursework\SQLQueries();
    return $sql_queries;
};

$container['processOutput'] = function ($container) {
    $output_processor = new \SecureWebAppCoursework\ProcessOutput();
    return $output_processor;
};

$container['xmlParser'] = function ($container) {
    $parser = new \SecureWebAppCoursework\XmlParser();
    return $parser;
};

$container['monologWrapper'] = function ($container) {
    $logger = new \SecureWebAppCoursework\MonologWrapper();
    return $logger;
};

$container['loginModel'] = function ($container) {
    $loginModel = new \SecureWebAppCoursework\LoginModel();
    return $loginModel;
};

$container['registrationModel'] = function ($container) {
    $regModel = new \SecureWebAppCoursework\RegistrationModel();
    return $regModel;
};

$container['bcryptWrapper'] = function ($container) {
  $bcryptWrapper = new \SecureWebAppCoursework\BcryptWrapper();
  return $bcryptWrapper;
};