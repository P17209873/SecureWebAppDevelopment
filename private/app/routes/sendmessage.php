<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/sendmessage', function(Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'send_message.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'processusermessage',
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Edit the Systems state',
        ]);

    $processed_output = processOutput($app, $html_output);
    return $processed_output;

})->setName('sendmessage');;
