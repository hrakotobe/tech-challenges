<?php
namespace RakotobeH\TechChalenge\Survey\Business;

class SurveyAggregator
{
    /**
     * @var array $answerList
     */
    public function aggregateAnswerList($answerList)
    {
        $groupedAnswers = null;
        $sample = $answerList[0];
        switch($sample['type']) {
            case 'numeric':
                $groupedAnswers = $this->simpleGroupItems($answerList);

                return array_sum($groupedAnswers) / count($groupedAnswers);

            case 'date':

                return $this->simpleGroupItems($answerList);

            case 'qcm':
                $groupedAnswers = $this->recursiveGroupQcm($answerList);

                return array_map(function($qcmValues){
                        return array_reduce($qcmValues, function($carry, $value) {
                                return $carry += $value;
                            },
                            0
                        );
                    },
                    $groupedAnswers
                );
            default:
                throw \Exception('unsupported type:'.$sample['type']);
        }
    }

    /**
     * @param array $answerList
     * @return array
     */
    private function simpleGroupItems($answerList)
    {
        return array_reduce($answerList, function($carry, $answer) {
            $carry[] = $answer['answer'];

            return $carry;
            },
            []
        );
    }

    /**
     * @param array $answerList
     * @return array
     */
    private function recursiveGroupQcm($answerList)
    {
        return array_reduce($answerList, function($carry, $answer) {
                return array_merge_recursive(
                    $carry,
                    $this->combineQcmKeyValues($answer)
                );
            },
            []
        );
    }

    /**
     * @param array $answerList
     * @return array
     */
    private function combineQcmKeyValues($answer)
    {
        $numericResponses = array_map(function($bool) {
                return [$bool ? 1 : 0];
           },
            $answer['answer']
        );

        return array_combine($answer['options'], $numericResponses);
    }
}
