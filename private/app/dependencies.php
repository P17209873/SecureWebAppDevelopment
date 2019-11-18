<?php

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
$validator = new \SecureWebAppCoursework\SoapWrapper();
return $validator;
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
$model = new \SecureWebAppCoursework\ProcessOutput();
return $model;
};

$container['xmlParser'] = function ($container) {
$model = new \SecureWebAppCoursework\XmlParser();
return $model;
};