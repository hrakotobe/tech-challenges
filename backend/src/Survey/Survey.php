<?php
namespace RakotobeH\TechChalenge\Survey;

class Survey
{
    const READ_BUFFER_SIZE = 1024*1024; //1M

    /**
     * @var array
     */
    protected $surveyData;

    public static function createFromFile($surveyFile)
    {
        $survey = new Survey();
        $survey->initWithFile($surveyFile);
        return $survey;
    }

    /**
     * @param \SplFileObject $surveyFile

     * @return array survey data
     */
    public function initWithFile($surveyFile)
    {
        $jsonData = '';
        while(!$surveyFile->eof()) {
            $jsonData .= $surveyFile->fread(self::READ_BUFFER_SIZE);
        }
        $data = json_decode($jsonData, true);

        if ($data === null || json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid survey file '.$surveyFile->getPath());
        }
        if (empty($data['survey']['code'])) {
            throw new \Exception('Invalid survey structure '.var_export($data, true));
        }
        $this->surveyData = $data;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->surveyData['survey']['code'];
    }
    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->surveyData['survey'];
    }
}
