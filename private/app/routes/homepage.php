<?php

/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 22/11/2019
 * Time: 15:16
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/home', function (Request $request, Response $response, $args) use ($app) {

    session_start();

    $message = null;
    if (isset($_SESSION['message']))
    {
        if (is_int($_SESSION['message']))
        {
            $message = 'Message Successfully Sent';
        }
        else
        {
            $message = 'Message Failed to send';
        }
        unset($_SESSION['message']);
    }

    if (isset($_SESSION['userid']))
    {
        $html_output = $this->view->render($response,
            'homepageform.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'message' => $message,
                'page_title' => APP_NAME,   //TODO: Title and text need changing
                'page_heading_1' => APP_NAME,
                'username' => $_SESSION['userid'],
                'method' => 'post',
                'action' => 'processchoice'
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

})->setName('home');