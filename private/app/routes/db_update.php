<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * This route is used as a database
 */

// REMEMBER TO UNCOMMENT crontab -e LINE WHEN FILE IS UPLOADED TO DMU WEB SERVERS
// file can also be executed manually by visiting '/test/db_update'

/*
 * Process to follow:
 * 1. Perform SOAP server call to retrieve information
 * 2. Perform a foreach loop to check whether the messages received already exist in the database (need to select from and insert into)
 * 3. If they do, drop them
 * 4. If they don't, add to database
 * (also put hyperlink on the homepage to allow the user to manually update the messages by pressing the button
 */

$app->get('/test/db_update', function (Request $request, Response $response) use ($app) {

    $cleaned_parameters['detail'] = 'peekMessages';
    $downloaded_messages = getMessages($app, $cleaned_parameters);
    $validated_downloaded_messages = validateDownloadedData($app, $downloaded_messages);
    $parsed_xml_messages = parseXml($app, $validated_downloaded_messages);

    $filtered_messages = filterMessages($app, $parsed_xml_messages);
    $db_messages = rebuildDatabaseMessages($app);
    $remapped_db_messages = remapMessageKeyValues($db_messages);

    foreach ($filtered_messages as $soap_message) {
        $sameMessage = false;
        foreach ($remapped_db_messages as $db_message) {
            if ($db_message == $soap_message) {
                $sameMessage = true;
            }
        }

        if ($sameMessage == false) {
            insertIntoCircuitBoardStates($app, $soap_message['MESSAGE']);
            insertIntoRetrievedMessages($app, $soap_message);
        }
    }
})->setName('db_update');

/**
 * Performs the GetMessages function from the SOAP Server
 *
 * @param $app
 * @param $cleaned_parameters
 * @return array
 */
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


/**
 * Validates the retrieved messages from the SOAP Server and the database retrieval
 *
 * @param $app
 * @param $tainted_data
 * @return string
 */
function validateDownloadedData($app, $tainted_data)
{
    $cleaned_data = '';
    if (is_string($tainted_data) == true) {
        $validator = $app->getContainer()->get('validator');
        $cleaned_data = $validator->validateDownloadedData($tainted_data);
        var_dump($tainted_data);
    } else {
        $cleaned_data = $tainted_data;
    }
    return $cleaned_data;
}

/**
 * Filters the retrieved messages from the SOAP Server and the database retrieval
 *
 * @param $app
 * @param $parsed_xml_messages
 * @return array
 */
function filterMessages($app, $parsed_xml_messages)
{
    $filtered_messages = [];

    $validator = $app->getContainer()->get('validator');

    foreach ($parsed_xml_messages as $message) {
        if (isset($message['MESSAGE'])) {
            $message_array = json_decode($message['MESSAGE'], true);
            if (isset($message_array['Id'])) {
                if ($validator->validateMessage($message_array)) {
                    $message['MESSAGE'] = $message_array;
                    $filtered_messages[] = $message;
                }
            }
        }
    }
    return $filtered_messages;
}

/**
 * Parses the retrieved SOAP Server and database retrieval messages
 *
 * @param $app
 * @param $xml_strings_to_parse
 * @return array
 */
function parseXml($app, $xml_strings_to_parse)
{
    $parsedXmlArray = [];

    $xmlParser = $app->getContainer()->get('xmlParser');

    foreach ($xml_strings_to_parse as $xml_string_to_parse) {
        if (is_string($xml_string_to_parse) == true) {
            $xmlParser->resetXmlParser();
            $xmlParser->setXmlStringToParse($xml_string_to_parse);
            $xmlParser->parseTheXmlString();
            $parsedXml = $xmlParser->getParsedData();
        } else {
            $parsedXml = $xml_string_to_parse;
        }
        $parsedXmlArray[] = $parsedXml;
    }

    return $parsedXmlArray;
}

/**
 * Uses the two query functions below to rebuild the message data into the same format as retrieved from SOAP
 *
 * @param $app
 * @return mixed
 */
function rebuildDatabaseMessages($app)
{
    $settings = $app->getContainer()->get('settings');

    $securewebapp_model = $app->getContainer()->get('secureWebAppModel');
    $securewebapp_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $securewebapp_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $securewebapp_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $result = $securewebapp_model->retrieveMessagesFromDB();

    return $result;
}

function remapMessageKeyValues($db_messages)
{
    $remapped_messages = array();

    foreach ($db_messages as $message) { //foreach message-array inside retrieved_messages
        $new_message = array();
        $new_message_values = array();

        foreach ($message as $key => $value) { //foreach message-array VALUE
            if ($key == 'messagesentto') {
                $new_message['DESTINATIONMSISDN'] = $value;
            }

            if ($key == 'messagesentfrom') {
                $new_message['SOURCEMSISDN'] = $value;
            }

            if ($key == 'receivedtime') {
                $new_message['RECEIVEDTIME'] = $value;
            }

            if ($key == 'bearer') {
                $new_message['BEARER'] = $value;
            }

            if ($key == 'messageref') {
                $new_message['MESSAGEREF'] = $value;
            }


            if ($key == 'switch01state') {
                $new_message_values['Switches']['switch1'] = $value;
            }

            if ($key == 'switch02state') {
                $new_message_values['Switches']['switch2'] = $value;
            }

            if ($key == 'switch03state') {
                $new_message_values['Switches']['switch3'] = $value;
            }

            if ($key == 'switch04state') {
                $new_message_values['Switches']['switch4'] = $value;
            }

            if ($key == 'fanstate') {
                $new_message_values['Fan'] = $value;
            }

            if ($key == 'heatertemperature') {
                $new_message_values['Temperature'] = $value;
            }

            if ($key == 'keypadvalue') {
                $new_message_values['Keypad'] = $value;
            }
        }
        $new_message['MESSAGE'] = $new_message_values;
        $new_message['MESSAGE']['Id'] = '18-3110-AC';
        array_push($remapped_messages, $new_message);
    }


    return $remapped_messages;
}

/**
 * Performs the insertIntoCircuitBoardStates query
 *
 * @param $app
 * @param $soap_message
 */
function insertIntoCircuitBoardStates($app, $soap_message)
{
    $settings = $app->getContainer()->get('settings');

    $securewebapp_model = $app->getContainer()->get('secureWebAppModel');
    $securewebapp_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $securewebapp_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $securewebapp_model->insertIntoCircuitBoardStates($soap_message['Switches']['switch1'], $soap_message['Switches']['switch2'], $soap_message['Switches']['switch3'], $soap_message['Switches']['switch4'], $soap_message['Fan'], $soap_message['Temperature'], $soap_message['Keypad']);
}

/**
 * Performs the insertIntoRetrievedMessages query
 *
 * @param $app
 * @param $soap_message
 */
function insertIntoRetrievedMessages($app, $soap_message)
{
    $settings = $app->getContainer()->get('settings');

    $securewebapp_model = $app->getContainer()->get('secureWebAppModel');
    $securewebapp_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $securewebapp_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $securewebapp_model->insertIntoRetrievedMessages($soap_message['DESTINATIONMSISDN'], $soap_message['SOURCEMSISDN'], $soap_message['RECEIVEDTIME'], $soap_message['BEARER'], $soap_message['MESSAGEREF']);
}
