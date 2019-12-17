<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/viewmessages', function(Request $request, Response $response) use ($app) {
    session_start();

    if (isset($_SESSION['userid']))
    {
        //$tainted_parameters = $request->getParsedBody();
        //var_dump($tainted_parameters);
        $tainted_parameters['detail'] = 'peekMessages';
        //TODO IS THIS NEEDED?
        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);
        $downloaded_messages = getMessage($app, $cleaned_parameters);
        $validated_messages = validateDownloadedData($app, $downloaded_messages);
        $parsed_xml_messages = parseXml($app, $validated_messages);
        $team_messages = filterTeamMessages($app, $parsed_xml_messages);

        $html_output = $this->view->render($response,
            'display_message.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
                'messages' => $team_messages
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

//THIS MIGHT NOT BE NEEDED ANYMORE
function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_detail = false;

    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['detail']))
    {
        $tainted_detail = $tainted_parameters['detail'];
        $validated_detail = $validator->validateDetailType($tainted_detail);
    }

    if ($validated_detail != false)
    {
        $cleaned_parameters['detail'] = $validated_detail;
    }
    return $cleaned_parameters;
}

function validateDownloadedData($app, $tainted_data)
{
    $cleaned_data = '';
    if (is_string($tainted_data) == true)
    {
        $validator = $app->getContainer()->get('validator');
        $cleaned_data = $validator->validateDownloadedData($tainted_data);
    }
    else
    {
        $cleaned_data = $tainted_data;
    }
    return $cleaned_data;
}

function getMessage($app, $cleaned_parameters)
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

function filterTeamMessages($app, $parsed_xml_messages)
{
    $team_messages = [];
    foreach ($parsed_xml_messages as $msg)
    {
        if (isset($msg['MESSAGE']))
        {
            var_dump($msg);
            if (strpos($msg['MESSAGE'], TEAM_CODE) !== false)
            {
                $team_messages[] = $msg;
            }
        }
    }
    return $team_messages;
}
