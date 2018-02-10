<?php
namespace RakotobeH\TechChalenge\Survey;

use RakotobeH\TechChalenge\Survey\Survey;

class SurveyManager
{
    /**
     * @var string
     */
    private $dataDirectoryPath;

    /**
     * @param string $dataDirectoryPath
     */
    public function __construct($dataDirectoryPath)
    {
        $this->dataDirectoryPath = $dataDirectoryPath;
    }
    /**
     * @return Survey[]
     */
    public function getResultList()
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

        return $resultList;
    }

    /**
     * @return string[][]
     */
    public function getSurveyList()
    {
        return array_map(function($surveyResultList) {
                $sample = array_pop($surveyResultList);
                return $sample->getInfo();
            },
            $this->getResultList()
        );
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
