<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Conversations\QAConversation;
use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;

class BotManController extends Controller
{
	/**
	 * Place your BotMan logic here.
	 */
    public function handle(Request $request)
    {
        $botman = app('botman');
        $botman->verifyServices(env('TOKEN_VERIFY'));


        $botman->hears('/start', function (BotMan $bot) {
            $bot->reply('برای پرسیدن سوال روی /ask کلیک کن.');
        });

        $botman->hears('/ask', function (BotMan $bot) {
            $bot->startConversation(new QAConversation());
        });

        $botman->hears('/cancel', function (BotMan $bot) {
            $bot->reply('برای پرسیدن سوال روی /ask کلیک کن.');
        });

        $botman->fallback(function($bot) {
            $bot->typesAndWaits(1);
            $txt = ("متاسفم پیام شما غیر قابل فهم است. برای پرسیدن سوال روی  /ask  کلیک کنید.");
            $bot->reply($txt);
        });

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
