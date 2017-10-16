<?php

namespace App\Conversations;

use App\QAProcessing\QAnswering;
use Mpociot\BotMan\Answer;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Question;

class QAConversation extends Conversation{

    protected $bot;
    protected $question;
    protected $choiceNum;
    protected $choices = array();

    public function __construct(BotMan $bot){
        $this->bot = $bot;
    }
    
    /**
     * Ask Question
     */
    private function askQuestion(){
        $this->ask('سوالتو بپرس. سعی میکنم جواب درست رو بگم.', function (Answer $response) {
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
//                Button::create('پنج')->value('5'),
//                Button::create('شش')->value('6'),
//                Button::create('هفت')->value('7'),
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
        $process = new QAnswering();
        try{
            $reply = $process->answerToQuestion($this->question, $this->choices);
            if($reply != null) {
                $this->bot->typesAndWaits(2);
                $this->say('جواب سوال به احتمال زیاد  ' . '"' . $reply . '"' . '  است');
                $this->say('اگه دوباره خواستی سوال بپرسی روی /ask بزن.');
            } else {
                $this->bot->typesAndWaits(2);
                $this->say('متاسفم نمیتونم به این سوال پاسخ بدم.');
                $this->say('اگه دوباره خواستی سوال بپرسی روی /ask بزن.');
            }
        } catch (\Exception $e){
            $this->say('مشکلی رخ داده است. اگه دوباره خواستی سوال بپرسی روی /ask بزن.');
        }
    }
    /**
     * @return mixed
     */
    public function run() {
        $this->askQuestion();
    }
}