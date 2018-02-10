<?php
namespace RakotobeH\TechChalenge\Survey;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SurveyControllerProvider implements ControllerProviderInterface
{
    /**
     * @implements ControllerProviderInterface
     */
    public function connect(Application $app) {
        $collection = $app['controllers_factory'];

        $surveyManager = $app['surveyManager'];

        $collection->get('/', function (Application $app) use ($surveyManager) {
            $surveyList = $surveyManager->getSurveyList();
            return $surveyList;
        });

        $collection->get('/{code}', function (Application $app, $code) use ($surveyManager) {
            $survey = $surveyManager->getAggregatedSurvey($code);
            if ($survey === null) {
                throw new NotFoundHttpException('Survey not found: '.$code);
            }
            return $survey;
        });

        return $collection;
    }
}
