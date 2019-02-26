<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QAProcessing\QAnswering;

class AnswerController extends Controller {
    public function getBotInterfaceHome() {
        return view('home');
    }

    public function answer(Request $r) {
//        try {
            $payload = json_decode($r->getContent());
            $choices = [
                $payload->choice1,
                $payload->choice2,
                $payload->choice3,
                $payload->choice4
            ];
            $process = new QAnswering();
            $reply = $process->answerToQuestion($payload->question, $choices, $payload->user_choice);
            if($reply != null) {
                $result['success'] = true;
                $result['result'] = $reply;
                return $result;
            } else {
                $result['success'] = false;
                $result['result'] = 'متاسفم نمیتونم به این سوال پاسخ بدم.';
                return $result;
            }
//        } catch (\Exception $e) {
//            return 'مشکلی پیش آمده است.';
//        }
    }
}
