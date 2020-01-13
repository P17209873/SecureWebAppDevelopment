<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/viewmessages', function (Request $request, Response $response) use ($app) {
    session_start();

    if (isset($_SESSION['userid'])) {
        $retrieved_messages = selectAllMessagesFromDb($app);
      
        $html_output = $this->view->render(
            $response,
            'messagelistform.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
                'messages' => $retrieved_messages,
                'loggedin' => true
            ]
        );
        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    } else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('viewmessages');

function selectAllMessagesFromDb($app)
{
    $model = $app->getContainer()->get('secureWebAppModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $messages = $model->getMessagesFromDB();

    return $messages;
}
