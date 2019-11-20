<?php

/** Initial Login page */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) use ($app) {

    $html_output = $this->view->render($response,
        'loginform.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'processmessage',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,   //TODO: Title and text need changing
            'page_heading_1' => APP_NAME,
            'page_text' => 'Please Login to your account',
        ]);

    /*Tom delete
    $processed_output = processOutput($app, $html_output);

    return $processed_output;

    */

    return $html_output;

})->setName('login');

function processOutput($app, $html_output)
{
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}

//function getCountryNamesAndIsoCodes($app)
//{
//    $country_detail_result = [];
//    $soap_wrapper = $app->getContainer()->get('soapWrapper');
//
//    $countrydetails_model = $app->getContainer()->get('countryDetailsModel');
//    $countrydetails_model->setSoapWrapper($soap_wrapper);
//
//    $countrydetails_model->retrieveCountryNames();
//    $country_detail_result = $countrydetails_model->getResult();
//
//    return $country_detail_result;
//
//}