<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QAProcessing\QAnswering;

class AnswerController extends Controller {
    public function getBotInterfaceHome() {
        return view('home');
    }

    public function answer(Request $r) {
        $payload = json_decode($r->getContent());
        $question = $payload->question;
        $choices = [
            $payload->answer1,
            $payload->answer2,
            $payload->answer3,
            $payload->answer4
        ];
        try{
            $process = new QAnswering();
            $reply = $process->answerToQuestion($question, $choices);
            if($reply != null) {
                return 'جواب سوال به احتمال زیاد  ' . '"' . $reply . '"' . '  است';
            } else {
                return 'متاسفم نمیتونم به این سوال پاسخ بدم.';
            }
        } catch (\Exception $e){
            return 'مشکلی پیش آمده است.';
        }
    }
}
