<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/processchoice', function (Request $request, Response $response) use ($app) {

    $tainted_parameters = $request->getParsedBody();

    $routeRedirect = getRedirect($app, $tainted_parameters);

    return $response->withRedirect($routeRedirect);
})->setName('processchoice');

function getRedirect($app, $tainted_paramaters)
{
    if (isset($tainted_paramaters['detail'])) {
        switch ($tainted_paramaters['detail']) {
            case 'view':
                $routeRedirect = 'viewmessages';
                break;
            case 'send':
                $routeRedirect = 'sendmessage';
                break;
            default:
                $routeRedirect = 'login';
                break;
        }
    } else {
        $routeRedirect = 'login';
    }
    return $routeRedirect;
}
