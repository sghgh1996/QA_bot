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
        $algorithm = $this->choice('What is the algorithm?', ['count_normal', 'count_quote', 'snippet']);
        $algorithmRow = DB::table('algorithms')->where('name', $algorithm)->first();

        // Counting algorithms
        if ($algorithmRow) {
            $question_offset = 1; // The starting id of question
            $counter = $question_offset; // Showing in console
            $questions = DB::table('questions')
                ->where('id', '>=', $question_offset)
                ->get();
            $this->info('Starting...');
            foreach ($questions as $question) {
                $choices = DB::table('choices')
                ->where('question_id', $question->id)
                ->get();

                $this->info('Answering Question ' . $counter);
                $this->info('text: '.$question->text);
                $counter++;

                $answer_id = -1;
                foreach ($choices as $choice) {
                    if ($algorithm === 'snippet') {
                        // TODO: implement
                    } else {
                        if ($algorithm === 'count_normal') {
                            $query = $question->text. ' ' .$choice->text;
                        } else {
                            $query = $question->text. ' "' .$choice->text . '"';
                        }
                        $google = new Google();
                        $this->info('Choice ' . $choice->text);
                        $total = $google->getResult($query);
                        DB::table('ranks')->insert([
                            'choice_id' => $choice->id,
                            'algorithm_id' => $algorithmRow->id,
                            'value' => $total
                        ]);
                        sleep(rand(6, 12));
                    }
                }
            }
        } else {
            $this->error('No Such Algorithm Found D:');
        }
    }
}
