<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestQA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing qa bot results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $questions = DB::table('questions')->get();

        $this->info('Starting...');
        $correct = 0;
        $wrong = 0;
        $counter = 0;
        foreach ($questions as $question) {
            $this->info('Testing Question ' . $counter);
            $counter++;
            if ($question->predicted_answer_id === $question->answer_id) {
                $correct++;
            } else {
                $wrong++;
            }
        }
        $this->info('Total: '.($correct + $wrong));
        $this->info('Correct: '.$correct);
        $this->info('Wrong: '.$wrong);
    }
}
