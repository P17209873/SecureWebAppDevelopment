<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post(
    '/processmessage',
    function(Request $request, Response $response) use ($app)
    {
        $validated_messages = false;
        $validated_detail = false;
        $country_detail_result = [];
        $comment = '';

        $tainted_parameters = $request->getParsedBody();
        $tainted_parameters['detail'] = 'peekMessages'; //TEMPORARY, should be passed in through request
        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);
        $downloaded_messages = getMessage($app, $cleaned_parameters);
        $validated_messages = validateDownloadedData($app, $downloaded_messages);

        $html_output = $this->view->render($response,
            'display_message.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
                'messages' => $validated_messages
            ]);

        $processed_output = processOutput($app, $html_output);

        return $processed_output;
    });

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