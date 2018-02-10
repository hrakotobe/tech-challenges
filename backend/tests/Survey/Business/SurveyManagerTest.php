<?php
namespace RakotobeH\TechChalenge\Survey\Business\Test;

use RakotobeH\TechChalenge\Survey\Business\SurveyAggregator;
use RakotobeH\TechChalenge\Survey\Business\SurveyManager;

use PHPUnit\Framework\TestCase;

class SurveyManagerTest extends TestCase
{

    /**
     * @var SurveyManager
     */
    private $surveyManager;

    public function setup()
    {
        $mockDirectoryPath = __DIR__.'/mock';
        $surveyAggregator = new SurveyAggregator();

        $this->surveyManager = new SurveyManager($mockDirectoryPath, $surveyAggregator);
    }
    public function testGetResultList()
    {

    }
}
