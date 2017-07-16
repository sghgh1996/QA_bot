<?php

namespace App\QAProcessing;

use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;

class QAnswering {

    /**
     * @param $numberOfChoices
     * @param $question
     * @param $choices
     */
    public function answerToQuestion($question, $choices){
        $engine = new LaravelGoogleCustomSearchEngine();
        $answer = null;
        $max = 0;
        foreach ($choices as $choice){
            $engine->getResults($choice.$question);
            $total = $engine->getRawResult()->searchInformation->totalResults;
            if($total > $max){
                $max = $total;
                $answer = $choice;
            }
        }
        return $answer;
    }
}