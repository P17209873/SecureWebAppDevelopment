<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/processusermessage', function(Request $request, Response $response) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    $tainted_parameters['detail'] = 'sendMessage';
    $cleaned_parameters = cleanupAllParameters($app, $tainted_parameters);
    var_dump($cleaned_parameters);
    $messageSent = sendMessage($app, $cleaned_parameters);
    var_dump($messageSent);

    $html_output = $this->view->render($response,
        'display_message.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Result',
            //'messages' => $parsed_xml_messages
        ]);

    $processed_output = processOutput($app, $html_output);
    return $processed_output;

})->setName('processusermessage');

function cleanupAllParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_detail = false;
    $validated_message = false;

    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['detail']))
    {
        $tainted_detail = $tainted_parameters['detail'];
        $validated_detail = $validator->validateDetailType($tainted_detail);
    }

    if (isset($tainted_parameters['usermessage']))
    {
        $tainted_message = $tainted_parameters['usermessage'];
        //$validated_message = $validator->validateUserMessage($tainted_message);
        $validated_message = $tainted_message;
    }

    if ($validated_detail != false && $validated_message != false)
    {
        $cleaned_parameters['detail'] = $validated_detail;
        $cleaned_parameters['usermessage'] = $validated_message;
    }
    return $cleaned_parameters;
}

function validatingDownloadedData($app, $tainted_data)
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

function sendMessage($app, $cleaned_parameters)
{
    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $securewebapp_model = $app->getContainer()->get('secureWebAppModel');
    $securewebapp_model->setSoapWrapper($soap_wrapper);

    $securewebapp_model->setParameters($cleaned_parameters);
    $securewebapp_model->sendMessage($cleaned_parameters);
    $successfullUpload = $securewebapp_model->getResult();

    return $successfullUpload;
}
