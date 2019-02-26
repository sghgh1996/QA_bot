<?php

namespace App\QAProcessing;

use App\Choice;
use App\Question;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;

class QAnswering {

    /**
     * @param $question
     * @param $choices
     * @param string $user_choice
     * @return null
     * @throws \Exception
     */
    public function answerToQuestion($question, $choices, $user_choice = 'none'){
        $q = new Question();
        $q->text = $question;
        $q->save();

        $engine = new LaravelGoogleCustomSearchEngine();
        $google = new Google();

        $answer = null;
        $max = 0;
        $counter = 1;
        $resultChoices = array();
        foreach ($choices as $choice) {
            $query = $choice. ' ' .$question;
            $engine->getResults($query);
            $total = $engine->getRawResult()->searchInformation->totalResults;
//            $total = $google->getResult($query);
//            sleep(rand(1.5, 3));
            $c = new Choice();
            $c->text = $choice;
            $c->rank_count = $total;
            $c->rank_snippet = 0;
            $c->question_id = $q->id;
            $c->save();
            $resultChoices[$choice] = $total;
            if ($user_choice === 'choice'.$counter) {
                $q->user_choice_id = $c->id;
            }
            $counter++;
            if($total > $max) {
                $max = $total;
                $answer = $choice;
                $q->answer_id = $c->id;
                $q->save();
            }
        }
        return [
            'answer' => $answer,
            'choices' => $resultChoices
        ];
    }
}