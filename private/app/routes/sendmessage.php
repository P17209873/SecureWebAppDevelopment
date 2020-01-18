<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/sendmessage', function(Request $request, Response $response) use ($app) {

    session_start();

    if (isset($_SESSION['userid']))
    {
        $html_output = $this->view->render($response,
            'messagesenderform.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'post',
                'action' => 'processusermessage',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Edit the Systems state',
                'page_text' => 'Send a Message'
            ]);

        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    }
    else
    {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }

})->setName('sendmessage');;
