<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post(
    '/processmessage',
    function(Request $request, Response $response) use ($app)
    {
        $validated_message = false;
        $validated_detail = false;
        $country_detail_result = [];
        $comment = '';

        $tainted_parameters = $request->getParsedBody();
        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);

        $html_output = $this->view->render($response,
            'display_result.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Result',
            ]);

        $processed_output = processOutput($app, $html_output);

        return $processed_output;
    });