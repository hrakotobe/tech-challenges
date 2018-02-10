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
        $expected = [
            [
                "code" => "XX1",
                "name" => "Paris"
            ],
            [
                "code" => "XX2",
                "name" => "Chartres"
            ],
            [
                "code" => "XX3",
                "name" => "Melun"
            ]
        ];

        $result = $this->surveyManager->getSurveyList();
        $this->assertSame(
            array_multisort($expected),
            array_multisort($result)
        );
    }
    public function testGetAggregatedSurvey() {
        $expected = [
            'What best sellers are available in your store?' => [
                "Product 1" => 2,
                "Product 2" => 1
            ],
            'Number of products?' => [
                680
            ],
            '"What is the visit date?"' => [
                '2017-06-09T00:00:00.000Z',
                '2017-06-10T00:00:00.000Z'
            ]
        ];

        $result = $this->surveyManager->getAggregatedSurvey('XX1');
        $this->assertSame(
            array_multisort($expected),
            array_multisort($result)
        );
    }

}
