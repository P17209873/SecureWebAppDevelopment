<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/processusermessage', function(Request $request, Response $response) use ($app) {

    session_start();

    $tainted_parameters = $request->getParsedBody();

    //Testing different messaging formats
    //Form needs to create an array similar to tempMessages
    var_dump($tainted_parameters);
    $tempMessages['Switches'] = [
        '1' => true,
        '2' => false,
        '3' => true,
        '4' => false
    ];
    $tempMessages['Fan'] = true;
    $tempMessages['Temperature'] = 50;
    $tempMessages['Keypad'] = 6;
    $tempMessages['Id'] = TEAM_CODE;

    //encode form once passed through into json string
    $stringTempMessages = json_encode($tempMessages);

    //how to decode json string back into an array at a later date
    $arrayTempMessages = json_decode($stringTempMessages, true);
    var_dump($arrayTempMessages);

    $tainted_parameters['usermessage'] = $stringTempMessages;
    $tainted_parameters['detail'] = 'sendMessage';

    $cleaned_parameters = cleanupAllParameters($app, $tainted_parameters);
    var_dump($cleaned_parameters);
    $successfully_sent = sendMessage($app, $cleaned_parameters);
    $_SESSION['message'] = $successfully_sent;

    //return $response->withRedirect('home', 301);

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
        $validated_message = $validator->sanitiseString($tainted_message);
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
    //$securewebapp_model->sendMessage($cleaned_parameters);
    $successfully_sent = $securewebapp_model->getResult();

    return $successfully_sent;
}
