<?php
namespace RakotobeH\TechChalenge\Survey\Tests;

use PHPUnit\Framework\TestCase;
use RakotobeH\TechChalenge\Survey\Survey;
use VirtualFileSystem\FileSystem;

class SurveyTest extends TestCase
{
    /**
     * @dataProvider initData
     */
    public function testInitWithFiled(
        $json,
        $infoArray,
        $expectError
    ) {

        $fileSystem = new FileSystem();
        file_put_contents($fileSystem->path('/test.json'),$json);
        $mockFile = new \SplFileObject($fileSystem->path('/test.json'));

        $survey = new Survey();

        if (!$expectError) {

            $survey->initWithFile($mockFile);

            $this->assertSame(
                $survey->getCode(),
                $infoArray['code']
            );
            $this->assertSame(
                $survey->getInfo(),
                $infoArray
            );
        } else {
            $this->expectException(\Exception::class);
            $survey->initWithFile($mockFile);
        }
    }

    public function initData()
    {
        return [
            'nominal' => [
                'json' => '{"survey": {"name":"Paris", "code":"XX1"}}',
                'info' => [
                    'name' => 'Paris',
                    'code' => 'XX1'
                ],
                'expectError' => false
            ],
            'code empty' => [
                'json' => '{"survey": {"name":"Paris", "code":""}}',
                'info' => null,
                'expectError' => true
            ],
            'invalid json' => [
                'json' => '{"survey":{"name":"Paris", "code:XX1"}}',
                'info' => null,
                'expectError' => true
            ],
        ];
    }
}
