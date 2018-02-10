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
            $id = $survey->getCode();
            if (empty($resultList[$id])) {
                $resultList[$id] = [];
            }
            $resultList[$id][] = $survey;
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

}
