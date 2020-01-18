<?php
/**
 * Created by PhpStorm.
 * User: p17209873
 * Date: 22/11/2019
 * Time: 15:37
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/register', function(Request $request, Response $response) use ($app){

    session_start();

    $error_message = null;
    if (isset($_SESSION['error']))
    {
        $error_message = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    $html_output = $this->view->render($response,
        'registrationform.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'registeruser',
            'login' => '/',
            'error_message' => $error_message,
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,   //TODO: Title and text need changing
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Enter registration details',
        ]);

    $processed_output = processOutput($app, $html_output);
    return $processed_output;

})->setName('register');