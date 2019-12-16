<?php

/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 22/11/2019
 * Time: 15:16
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/home', function (Request $request, Response $response, $args) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');

    $user_id_result = checkUserID($app, $cleaned_parameters['sanitised_username']);
    $user_id_result = intval($user_id_result);

    if ($user_id_result != null) {
        if ($user_id_result != 'Unfortunately there has been a query error') {
            $check_user_password = checkUserPassword($app, $user_id_result, $cleaned_parameters['sanitised_username']);

            $user_authenticated_result = $bcrypt_wrapper->authenticatePassword($cleaned_parameters['password'], $check_user_password);

            // uses switch statement to prevent MySQL PDO error of incorrect integer value when trying to insert 'false'
            switch ($user_authenticated_result) {
                case true:
                    $user_authenticated_result = 1;
                    break;
                case false:
                    $user_authenticated_result = 0;
                    break;
            }

            logAttemptToDatabase($app, $user_id_result, $user_authenticated_result);

            if ($user_authenticated_result == 1) {
                $html_output = $this->view->render($response,
                    'homepageform.html.twig',
                    [
                        'css_path' => CSS_PATH,
                        'landing_page' => LANDING_PAGE,
                        'page_title' => APP_NAME,   //TODO: Title and text need changing
                        'page_heading_1' => APP_NAME,
                        'username' => $cleaned_parameters['sanitised_username'],
                        'method' => 'post',
                        'action' => 'processchoice'
                    ]);

                $processed_output = processOutput($app, $html_output);
                return $processed_output;
            } else {
                echo 'you are not logged in';
            }
        } else {
            echo 'There has been an error performing the query';
        }

        //
    } else // This signifies that there is NO SUCH USER in the database
    {
        return 'Unfortunately this user doesn\'t exist';
    }

})->setName('loggedin_homepage');


function checkUserPassword($app, $userid, $username)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $password_result = $model->checkUserPassword($userid, $username);

    return $password_result;
}

function checkUserID($app, $username)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $userid = $model->checkUserID($username);

    return $userid;
}

function logAttemptToDatabase($app, $userid, $login_result)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $model->storeLoginAttempt($userid, $login_result);
}

function cleanParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    foreach ($tainted_parameters as $key => $param) {
        if ($key != 'password' && $key != 'rpassword') {
            $cleaned_parameters['sanitised_' . $key] = $validator->sanitiseString($param);
        } else {
            $cleaned_parameters[$key] = $tainted_parameters[$key];
        }
    }

    return $cleaned_parameters;
}
