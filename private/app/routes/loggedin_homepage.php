<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/home', function (Request $request, Response $response) use ($app) {

    $app_parsed = $request->getParsedBody();

    var_dump($request);

    $html_output = $this->view->render($response,
        'homepageform.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'get',
            'action' => 'loggedin_homepage',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,   //TODO: Title and text need changing
            'page_heading_1' => APP_NAME,
            'page_text' => 'Please Login to your account',
            'register' => 'register',
        ]);

    $processed_output = processOutput($app, $html_output);
    return $processed_output;


})->setName('loggedin_homepage');