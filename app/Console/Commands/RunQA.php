<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\QAProcessing\Google;

class RunQA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is for running qa bot with questions';

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
        $offset = 34;
        $counter = 61;
        $questions = DB::table('questions')
        ->where('id', '>=', $counter + $offset)
        ->get();
        $this->info('Starting...');
        foreach ($questions as $question) {
            $choices = DB::table('choices')
            ->where('question_id', $question->id)
            ->get();

            $this->info('Answering Question ' . $counter);
            $this->info('text: '.$question->text);
            $counter++;

            $max = 0;
            $answer_id = -1;
            foreach ($choices as $choice) {
                $query = $question->text. ' "' .$choice->text . '"';
                $google = new Google();
                $this->info('Choice ' . $choice->text);
                $total = $google->getResult($query);
                sleep(rand(6, 12));
                if($total > $max) {
                    $max = $total;
                    $answer_id = $choice->id;
                }
                DB::table('choices')
                ->where('id', $choice->id)
                ->update(['rank_count' => $total]);
            }
            DB::table('questions')
                ->where('id', $question->id)
                ->update(['predicted_answer_id' => $answer_id]);
        }
    }
}
