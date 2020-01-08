<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/processusermessage', function(Request $request, Response $response) use ($app) {

    session_start();

    $tainted_parameters = $request->getParsedBody();

    $cleaned_parameters = cleanupParameters($app, $tainted_parameters);

    if (isset($cleaned_parameters['detail']) && isset($cleaned_parameters['usermessage']))
    {
        $successfully_sent = sendMessage($app, $cleaned_parameters);
        $_SESSION['message'] = $successfully_sent;
    }
    else
    {
        $_SESSION['error'] = "Message didn't send";
    }

    return $response->withRedirect('home', 301);

})->setName('processusermessage');

function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];

    $validator = $app->getContainer()->get('validator');

    $tainted_message['Switches'] = [
        'switch1' => $tainted_parameters['switch1'],
        'switch2' => $tainted_parameters['switch2'],
        'switch3' => $tainted_parameters['switch3'],
        'switch4' => $tainted_parameters['switch4']
    ];
    $tainted_message['Fan'] = $tainted_parameters['fan'];
    $tainted_message['Temperature'] = $tainted_parameters['temp'];
    $tainted_message['Keypad'] = $tainted_parameters['key'];
    $tainted_message['Id'] = TEAM_CODE;

    if ($validator -> validateMessage($tainted_message))
    {
        $cleaned_parameters['detail'] = 'sendMessage';
        $cleaned_parameters['usermessage'] = json_encode($tainted_message);
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
    $successfully_sent = $securewebapp_model->getResult();

    return $successfully_sent;
}
