<?php
namespace RakotobeH\TechChalenge\Survey\Business;

use RakotobeH\TechChalenge\Survey\Model\Survey;

class SurveyManager
{
    /**
     * @var string
     */
    private $dataDirectoryPath;

    /**
     * @var SurveyAggregator
     */
    private $surveyAggregator;

    /**
     * @param string $dataDirectoryPath
     */
    public function __construct($dataDirectoryPath, $surveyAggregator)
    {
        $this->dataDirectoryPath = $dataDirectoryPath;
        $this->surveyAggregator = $surveyAggregator;
    }
    /**
     * @return Survey[]
     */
    public function getResultList($valuesOnly = true)
    {
        $surveyDirectory = new \DirectoryIterator($this->dataDirectoryPath);
        $resultList = [];

        foreach($surveyDirectory as $surveyFileInfo) {
            if ($surveyFileInfo->isDot()) {
                continue;
            }

            $survey = Survey::createFromFile($surveyFileInfo->openFile('r'));
            $this->forcePushArray(
                $resultList,
                $survey->getCode(),
                $survey
            );
        }

        return $valuesOnly ? array_values($resultList) : $resultList;
    }

    /**
     * @return string[][]
     */
    public function getSurveyList()
    {
        return array_map(function($surveyResultList) {
                $sample = $surveyResultList[0];
                return $sample->getInfo();
            },
            $this->getResultList()
        );
    }

    /**
     * @param string $code
     *
     * @return string[][]
     */
    public function getAggregatedSurvey($code)
    {
        $surveyList = $this->getResultList(false);
        if (empty($surveyList[$code])) {
            return null;
        }

        $answersByQuestion = [];
        foreach($surveyList[$code] as $survey) {
            $questionList = $survey->getQuestionList();
            foreach($questionList as $question) {
                $this->forcePushArray($answersByQuestion, $question['label'], $question);
            }
        }

        $aggregatedAnswers = array_map(function($answerList) {
                return $this->surveyAggregator->aggregateAnswerList($answerList);
            },
            $answersByQuestion
        );

        return [
            $code => $aggregatedAnswers
        ];
    }

    /**
     * Guarantee a value is pushed into an array at the key specified.
     * Container array is modified.
     *
     * @param &array $container
     * @param string $key
     * @param mixed $value
     */
    private function forcePushArray(&$container, $key, $value) {
        if (empty($container[$key])) {
            $container[$key] = [];
        }
        $container[$key][] = $value;
    }

}
