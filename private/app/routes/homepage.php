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

    $error_message=null;
    if (isset($_SESSION['error']))
    {
        $error_message = 'Error Message Failed to send';
        unset($_SESSION['error']);
    }

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
        $past_states = getMessages($app, array('detail' => 'peekMessages'));
        $validated_past_states = validateDownloadedData($app, $past_states);
        $parsed_past_states = parseXml($app, $validated_past_states);
        $past_team_states = filterMessages($app, $parsed_past_states);
        $current_state_message = end($past_team_states);
        $current_state['date'] = $current_state_message['RECEIVEDTIME'];
        $current_state['message'] = $current_state_message['MESSAGE'];

        $html_output = $this->view->render($response,
            'homepageform.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'error_message' => $error_message,
                'message' => $message,
                'page_title' => APP_NAME,   //TODO: Title and text need changing
                'page_heading_1' => APP_NAME,
                'current_state' => $current_state,
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