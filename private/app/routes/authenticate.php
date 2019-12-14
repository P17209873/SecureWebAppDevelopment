<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 22/11/2019
 * Time: 15:16
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/authenticate', function (Request $request, Response $response, $args) use ($app){
    // var_dump($request->getParsedBody());

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    $user_id_result = checkUserID($app, $cleaned_parameters['sanitised_username']);

    if($user_id_result != null)
    {
        if($user_id_result != 'Unfortunately there has been a query error')
        {
            echo 'desired behaviour ' . $user_id_result;
        }

        else
        {
            echo 'There has been an error performing the query';
        }

        //logAttemptToDatabase($app, $user_id_result, $login_result);
    }

    else // This signifies that there is NO SUCH USER in the database
    {
        return 'Unfortunately this user doesn\'t exist';
    }

    $login_result = '';



})->setName('authenticate');


function retrieveFromDatabase($app, $cleaned_parameters)
{

}

function loginToApplication($app, $username, $hashed_password)
{
    $model = $app->getContainer()->get('loginModel');

    $login_result = $model->attemptLogin($username, $hashed_password);

    if($login_result == true)
    {
        // TODO: Implement logic
    }

    else
    {
        // TODO: Implement logic
    }
}

function logAttemptToDatabase($app, $user_id, $login_result)
{
    $model = $app->getContainer()->get('loginModel');
    $model->storeLoginAttempt($user_id, $login_result);
}

function checkUserID($app, $username)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('loginModel');

    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));

    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $user_id = $model->checkUserID($username);

    return $user_id;
}

function cleanParameters($app, $tainted_parameters){
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    foreach($tainted_parameters as $key=>$param)
    {
        if($key != 'password' && $key != 'rpassword')
        {
            $cleaned_parameters['sanitised_' . $key] = $validator->sanitiseString($param);
        }

        else
        {
            $cleaned_parameters[$key] = $tainted_parameters[$key];
        }
    }

    return $cleaned_parameters;
}
