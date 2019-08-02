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
        $algorithm = $this->choice('What is the algorithm?', ['count_normal', 'count_quote', 'snippet']);
        $algorithmRow = DB::table('algorithms')->where('name', $algorithm)->first();
        $questions = DB::table('questions')->get();
        
        $this->info('Starting...');
        $correct = 0;
        $total = $questions->count();
        $counter = 0;
        foreach ($questions as $question) {
            $this->info('Testing Question ' . $counter);
            $counter++;
            
            $choices = DB::table('choices')
                ->join('ranks', 'choices.id', '=', 'ranks.choice_id')
                ->where('question_id', $question->id)
                ->where('algorithm_id',$algorithmRow->id)
                ->get();

            $max_rank = $choices->max('value');
            if ($max_rank === 0) {
                $this->error('zero: '. $question->id);
                $total--;
            } else {
                $choice = $choices->where('value', $max_rank)->first();
                if($choice->is_answer) $correct++;
                else {
                    $this->error('wrong: '. $question->id);
                }
            }
        }

        $this->info('Total: '.$total);
        $this->info('Correct: '.$correct);
        $this->info('Wrong: '.($total - $correct));
    }
}
