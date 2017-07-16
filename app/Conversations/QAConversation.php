<?php

namespace App\Conversations;


use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Question;

class QAConversation extends Conversation{

    protected $question;
    protected $choiceNum;
    protected $choices = array();

    /**
     * Ask Question
     */
    private function askQuestion(){
        $this->ask('خب! سوالتو بپرس تا جوابشو بهت بگم. البته قول نمیدم جوابم درست باشه', function (Answer $response) {
            $this->question = $response->getText();
            $this->say('خوبه');
            $this->askNumberOfChoices();
        });
    }

    /**
     * Ask Question
     */
    private function askNumberOfChoices(){
        $question = Question::create('تعداد گزینه های سوالت رو انتخاب کن')
            ->fallback('مشکلی رخ داده است.')
            ->callbackId('ask_number_of_choices')
            ->addButtons([
                Button::create('دو')->value('2'),
                Button::create('سه')->value('3'),
                Button::create('چهار')->value('4'),
                Button::create('پنج')->value('5'), 
                Button::create('شش')->value('6'),
                Button::create('هفت')->value('7'),
                Button::create('لغو')->value('cancel'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() == 'cancel'){
                    $this->say('اگه دوباره خواستی سوال بپرسی روی /ask بزن.');
                } else {
                    $this->choiceNum = $answer->getValue();
                    $reply = 'خب! الآن باید '.$answer->getValue().' تا گزینه وارد کنی';
                    $this->say($reply);
                    $this->askChoices();
                }
            }
        });
    }

    /**
     * @param $number
     */
    private function askChoices(){
        $this->askChoice(1);
    }

    /**
     * @param $number
     */
    private function askChoice($number){
        if($number > $this->choiceNum){
            $this->say('خب. حالا منتظر باش تا جوابتو بدم');
            $this->giveAnswer();
            return;
        }

        $this->ask('گزینه شماره '.$number .' را وارد کن', function (Answer $response) use ($number) {
            array_push($this->choices,$response->getText());
            $this->askChoice($number+1);
        });
    }

    private function giveAnswer(){

    }
    /**
     * @return mixed
     */
    public function run() {
        $this->askQuestion();
    }
}