<?php
namespace RakotobeH\TechChalenge\Survey\Business\Test;

use RakotobeH\TechChalenge\Survey\Business\SurveyAggregator;

use PHPUnit\Framework\TestCase;

class SurveyAggregatorTest extends TestCase
{

    /**
     *  @var SurveyAggregator
     */
    private $surveyAggregator;

    public function setup()
    {
        $this->surveyAggregator = new SurveyAggregator();
    }

    public function testAggregateAnswerListNumeric()
    {
        $values = [];
        $answerList = [];
        for($i = 0; $i < 100; $i++) {
            $value = rand(0, 100);
            $values[] = $value;
            $answerList[] = [
                "type" => "numeric",
                "label" => "label",
                "options" => null,
                "answer" => $value
            ];
        }
        $expected = array_sum($values) / count($values);
        $result = $this->surveyAggregator->aggregateAnswerList($answerList);

        $this->assertSame($expected, $result);
    }

    public function testAggregateAnswerListDate()
    {
        $values = [];
        $answerList = [];
        for($i = 0; $i < 100; $i++) {
            $value = new \DateTime();
            $value->add(new \DateInterval(sprintf('P%dD',$i)));
            $values[] = $value;
            $answerList[] = [
                "type" => "date",
                "label" => "label",
                "options" => null,
                "answer" => $value
            ];
        }

        $result = $this->surveyAggregator->aggregateAnswerList($answerList);
        $this->assertSame( $values, $result);
    }

    public function testAggregateAnswerListQcm()
    {
        $products = [];
        $productCount = [];
        for ($productCounter = 0; $productCounter < 2; $productCounter++) {
            $name = 'Product'.$productCounter;
            $products[] = $name;
            $productCount[$name] = 0;
        }

        $answerList = [];
        for($answerCount = 0; $answerCount < 20; $answerCount++) {
            $choices = [];
            foreach($products as $product) {
                $choice = rand(0, 5) > 3;
                if ($choice ) {
                    $productCount[$product]++;
                }
                $choices[] = $choice;
            }
            $answerList[] = [
                "type" => "qcm",
                "label" => "label",
                "options" => $products,
                "answer" => $choices
            ];
        }

        $result = $this->surveyAggregator->aggregateAnswerList($answerList);
        $this->assertSame( $productCount , $result);
    }

}
