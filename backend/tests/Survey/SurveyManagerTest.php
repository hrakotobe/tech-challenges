<?php
namespace RakotobeH\TechChalenge\Survey\Tests;

use PHPUnit\Framework\TestCase;
use RakotobeH\TechChalenge\Survey\Survey;
use RakotobeH\TechChalenge\Survey\SurveyManager;

class SurveyTest extends TestCase
{

    /**
     * @var FileSystem
     */
    private $fileSystem;
    /**
     * @var string
     */
    private $directoryPath = __DIR__.'/mock';

    public function setup()
    {
        $this->fileSystem = new FileSystem();
        $this->fileSystem->createDirectory($this->directoryPath, false, 'r');

    }

    public function testGetResultList()
    {



        $this->assertSame(
            $survey->getCode(),
            'XX1'
        );
        $this->assertSame(
            $survey->getInfo(),
            [
                "name" => "Paris",
                "code" => "XX1"
            ]
        );
    }

    private function generateFileData($code)
    {
    }
}
