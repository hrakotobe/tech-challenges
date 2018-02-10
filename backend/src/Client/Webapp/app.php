<?php
declare(strict_types=1);

if (file_exists(ROOT_PATH.'/vendor/autoload.php') === false) {
    echo "run this command first: composer install";
    exit();
}
require_once ROOT_PATH.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application();

// Configuration

$app['debug'] = true;

$app['dataDirectoryPath'] = ROOT_PATH.'/data';

// Services

$app['surveyAggregator'] = new RakotobeH\TechChalenge\Survey\Business\SurveyAggregator();

$app['surveyManager'] = new RakotobeH\TechChalenge\Survey\Business\SurveyManager(
    $app['dataDirectoryPath'],
    $app['surveyAggregator']
);

$app['surveyControllerProvider'] = new RakotobeH\TechChalenge\Survey\SurveyControllerProvider();

// Handlers

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => ROOT_PATH.'/logs/development.log',
));

$app->error(function (\Exception $exception, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'Resource not found';
            break;
        default:
            $message = 'An error occured ('.$code.')';
    }
    return new Response($message);
});

$app->view(function (array $result, Request $request) use ($app) {
    return $app->json($result);
});

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

// Controllers
$app->mount('/survey', $app['surveyControllerProvider']);

$app->get('/', function () use ($app) {
    return ['test' => 'test2'];
});

$app->run();

return $app;
