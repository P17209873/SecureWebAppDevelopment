<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/registeruser', function(Request $request, Response $response) use ($app){

    session_start();
    $monologWrapper = $app->getContainer()->get('monologWrapper');

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    $username_exists_result = doesUsernameExist($app, $cleaned_parameters['sanitised_username']);
    $email_exists_result = doesEmailExist($app, $cleaned_parameters['sanitised_email']);

    if($username_exists_result != true && $cleaned_parameters['password'] === $cleaned_parameters['rpassword'] &&
        $email_exists_result != true && strpos($cleaned_parameters['sanitised_username'], " ") === false )
    {
        $check_nulls = array();
        foreach($cleaned_parameters as $key=>$value)
        {
            if($value != null)
            {
                $check_nulls[$key]=false;
            }
            else
            {
                $check_nulls[$key]=true;
            }
        }

        if(!(in_array(true, $check_nulls)))
        {
            $hashed_password = hashPassword($app, $cleaned_parameters['password']);

            $cleaned_parameters['password'] = '';
            $cleaned_parameters['rpassword'] = '';

            $validNewUser = createNewUser($app, $cleaned_parameters, $hashed_password);
            if($validNewUser == true)
            {
                $_SESSION['message'] = 'User Successfully created';
            }
            else
            {
                $_SESSION['message'] = 'Failed to create User';
            }
            $monologWrapper->addLogMessage($cleaned_parameters['sanitised_username'] . $_SESSION['message'], 'info');

            $url = $this->router->pathFor('login');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        $_SESSION['error'] = 'Invalid Account Credentials';
        $monologWrapper->addLogMessage($_SESSION['error'], 'info');
        $url = $this->router->pathFor('register');
        return $response->withStatus(302)->withHeader('Location', $url);
    }

})->setName('registeruser');

function hashPassword($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}


function doesUsernameExist($app, $username)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesUsernameExist($username);
}

function doesEmailExist($app, $email)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesEmailExist($email);
}

/**
 * Creates a new user in the database by calling the relevant method in the RegistrationModel, which deals with executing  the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function createNewUser($app, $cleaned_parameters, $hashed_password)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_username = $cleaned_parameters['sanitised_username'];
    $cleaned_firstname = $cleaned_parameters['sanitised_first_name'];
    $cleaned_lastname = $cleaned_parameters['sanitised_last_name'];
    $cleaned_email = $cleaned_parameters['sanitised_email'];

    return $model->createNewUser($cleaned_username, $hashed_password, $cleaned_firstname, $cleaned_lastname, $cleaned_email);
}