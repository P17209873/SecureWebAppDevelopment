<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/viewmessages', function(Request $request, Response $response) use ($app) {
    session_start();

    if (isset($_SESSION['userid']))
    {
        $cleaned_parameters['detail'] = 'peekMessages';;
        $downloaded_messages = getMessages($app, $cleaned_parameters);
        $validated_downloaded_messages = validateDownloadedData($app, $downloaded_messages);
        $parsed_xml_messages = parseXml($app, $validated_downloaded_messages);
        $filtered_messages = filterMessages($app, $parsed_xml_messages);

        $html_output = $this->view->render($response,
            //'display_message.html.twig',
            'messagelistform.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
                'messages' => $filtered_messages
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

})->setName('viewmessages');;

function validateDownloadedData($app, $tainted_data)
{
    $cleaned_data = '';
    if (is_string($tainted_data) == true)
    {
        $validator = $app->getContainer()->get('validator');
        $cleaned_data = $validator->validateDownloadedData($tainted_data);
        var_dump($tainted_data);
    }
    else
    {
        $cleaned_data = $tainted_data;
    }
    return $cleaned_data;
}

function getMessages($app, $cleaned_parameters)
{
    $messages = [];
    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $securewebapp_model = $app->getContainer()->get('secureWebAppModel');
    $securewebapp_model->setSoapWrapper($soap_wrapper);

    $securewebapp_model->setParameters($cleaned_parameters);
    $securewebapp_model->performDetailRetrieval();
    $messages = $securewebapp_model->getResult();

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