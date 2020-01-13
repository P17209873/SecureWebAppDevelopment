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
                'messages' => $retrieved_messages
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

    $messages = $model->retrieveMessagesFromDB();

    return $messages;
}

function parseXml($app, $xml_strings_to_parse)
{
    $parsedXmlArray = [];

    $xmlParser = $app->getContainer()->get('xmlParser');

    foreach ($xml_strings_to_parse as $xml_string_to_parse)
    {
        if (is_string($xml_string_to_parse) == true)
        {
            $xmlParser->resetXmlParser();
            $xmlParser->setXmlStringToParse($xml_string_to_parse);
            $xmlParser->parseTheXmlString();
            $parsedXml = $xmlParser->getParsedData();

        }
        else
        {
            $parsedXml = $xml_string_to_parse;
        }
        $parsedXmlArray[] = $parsedXml;
    }

    return $parsedXmlArray;
}

function filterMessages($app, $parsed_xml_messages)
{
    $filtered_messages = [];

    $validator = $app->getContainer()->get('validator');

    foreach ($parsed_xml_messages as $message)
    {
        if (isset($message['MESSAGE']))
        {
            $message_array = json_decode($message['MESSAGE'], true);
            if (isset($message_array['Id']))
            {
                if ($validator->validateMessage($message_array))
                {
                    $message['MESSAGE'] = $message_array;
                    $filtered_messages[] = $message;
                }
            }
        }
    }
    return $filtered_messages;
}
