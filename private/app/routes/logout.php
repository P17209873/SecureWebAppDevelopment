<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/logout', function (Request $request, Response $response, $args) use ($app) {

    session_start();

    unset($_SESSION['userid']);

    $_SESSION['message'] = 'Successfully Logged out';

    $url = $this->router->pathFor('login');
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('logout');
