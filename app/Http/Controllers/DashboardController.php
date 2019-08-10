<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Choice;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;
use App\QAProcessing\Google;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard() {
        $questions = DB::table('questions')->where('is_test', 1)->get();
        $algorithms = DB::table('algorithms')->get();

        $questions_count = $questions->count();
        $algorithms_count = $algorithms->count();

        $accuracy_total = 0;
        $max_acc = 0;
        foreach($algorithms as $algorithm) {
            $correct = 0;
            foreach($questions as $question) {
                $choices = DB::table('choices')
                    ->join('ranks', 'choices.id', '=', 'ranks.choice_id')
                    ->where('question_id', $question->id)
                    ->where('algorithm_id',$algorithm->id)
                    ->get();
                
                if ($choices->count() > 0) {
                    $max_rank = $choices->max('value');
                    if ($max_rank !== 0) {
                        $choice = $choices->where('value', $max_rank)->first();
                        if($choice->is_answer) $correct++;
                    }
                }
            }
            $acc = $correct / $questions_count;
            if ($acc > $max_acc) {
                $max_acc = $acc;
                $best = $algorithm->name;
            }
            $accuracy_total += $acc;
        }

        return view('dashboard.dashboard', [
            'questions_count' => $questions_count,
            'algorithms_count' => $algorithms_count,
            'average_accuracy' => round($accuracy_total/$algorithms_count, 2) * 100,
            'max_accuracy' => round($max_acc, 2) * 100,
            'best_algorithm' => $best
        ]);
    }

    public function run () {
        return view('dashboard.run');
    }

    public function getTestQuestions () {
        $questions = DB::table('questions')->where('is_test', 1)->get();
        foreach ($questions as $question) {
            $choices = DB::table('choices')->where('question_id', $question->id)->get();
            $question->choices = $choices;
        }    
        return view('dashboard.questions', ['questions' => $questions]);
    }

    public function analyzeQuestion ($id) {
        $question = $this->analyzeQuestionFunction($id);
        // return json_encode($question);
        return view('dashboard.question', ['question' => $question]);
    }

    public function answerQuestion(Request $r) {
        try {
            $payload = json_decode($r->getContent());
            $choicesText = [
                $payload->choice1,
                $payload->choice2,
                $payload->choice3,
                $payload->choice4
            ];
            $algorithms = $payload->algorithms;
            $questionText = $payload->question;
            
            $question = new Question();
            $question->text = $questionText;
            $question->save();

            $counter = 0;
            foreach ($algorithms as $algorithmName => $status) {
                if ($status) {
                    $counter++;
                    $algorithmRow = DB::table('algorithms')->where('name', $algorithmName)->first();
                    // Creating new question
                    if ($algorithmName === 'snippet') {
                        $engine = new LaravelGoogleCustomSearchEngine();
                        $items = $engine->getResults($questionText);
                        $items = json_decode(json_encode($items, JSON_UNESCAPED_UNICODE));
                        
                        foreach ($choicesText as $choiceText) {
                            $choice_rank = 0;
                            foreach ($items as $item) {
                                if (strpos($item->snippet, $choiceText) !== false) {
                                    $choice_rank++;
                                }
                            }
                            if ($counter === 1) {
                                $choice = new Choice();
                                $choice->text = $choiceText;
                                $choice->question_id = $question->id;
                                $choice->save();
                            } else {
                                $choice = DB::table('choices')
                                    ->where('question_id', $question->id)
                                    ->where('text', $choiceText)
                                    ->get()
                                    ->first();
                            }

                            DB::table('ranks')->insert([
                                'choice_id' => $choice->id,
                                'algorithm_id' => $algorithmRow->id,
                                'value' => $choice_rank
                            ]);
                        }
                    } else {
                        foreach ($choicesText as $choiceText) {
                            if ($algorithmName === 'count_normal') {
                                $query = $questionText. ' ' .$choiceText;
                            } else {
                                $query = $questionText. ' "' .$choiceText . '"';
                            }
                            $google = new Google();
                            $total = $google->getResult($query);
                            
                            if ($counter === 1) {
                                $choice = new Choice();
                                $choice->text = $choiceText;
                                $choice->question_id = $question->id;
                                $choice->save();
                            } else {
                                $choice = DB::table('choices')
                                    ->where('question_id', $question->id)
                                    ->where('text', $choiceText)
                                    ->get()
                                    ->first();
                            }

                            DB::table('ranks')->insert([
                                'choice_id' => $choice->id,
                                'algorithm_id' => $algorithmRow->id,
                                'value' => $total
                            ]);
                            sleep(rand(1.5, 3.5));
                        }
                    }
                }
            }
            // $result = $this->analyzeQuestionFunction($question->id);
            return json_encode(['question_id' => $question->id]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    private function analyzeQuestionFunction ($id) {
        $question = DB::table('questions')->where('id', $id)->get()->first();
        $choices = DB::table('choices')
            ->where('question_id', $question->id)
            ->get();
        $question->choices = $choices;
        $question->answer_id = 0;
        $choices_ids = array();
        foreach ($choices as $choice) {
            array_push($choices_ids, $choice->id);
            if ($choice->is_answer) $question->answer_id = $choice->id;
        }
        $algorithms = DB::table('algorithms')->get();
        foreach ($algorithms as $key => $algorithm) {
            $algorithm_choices = DB::table('choices')
                ->join('ranks', 'ranks.choice_id', '=', 'choices.id')
                ->whereIn('choices.id', $choices_ids)
                ->where('algorithm_id', $algorithm->id)
                ->select('choices.text', 'choices.text', 'choices.is_answer', 'choices.id as choice_id', 'ranks.value')
                ->get();
            if ($algorithm_choices->count() > 0) {
                $max_rank = $algorithm_choices->max('value');
                $sum_rank = $algorithm_choices->sum('value');
                if ($max_rank === 0) {
                    $algorithm->predicted = 0;
                } else {
                    $algorithm->predicted = 1;
                    $choice = $algorithm_choices->where('value', $max_rank)->first();
                    $algorithm->predicted_id = $choice->choice_id;
                    $algorithm->predicted_text = $choice->text;
                    foreach($algorithm_choices as $algorithm_choice) {
                        $algorithm_choice->normalized_value = $algorithm_choice->value / $sum_rank;
                    }
                }
                $algorithm->choices = $algorithm_choices;  
            } else {
                $algorithm->predicted = 0;
            }
        }
        $question->algorithms = $algorithms;
        return $question;
    }

    public function analyzeAlgorithms() {
        $algorithms = DB::table('algorithms')->get();
        $questions = DB::table('questions')->where('is_test', 1)->get();
        
        $total = $questions->count();
        
        foreach($algorithms as $algorithm) {
            $correct = 0;
            foreach($questions as $question) {
                $choices = DB::table('choices')
                    ->join('ranks', 'choices.id', '=', 'ranks.choice_id')
                    ->where('question_id', $question->id)
                    ->where('algorithm_id',$algorithm->id)
                    ->get();
                if ($choices->count() > 0) {
                    $max_rank = $choices->max('value');
                    if ($max_rank !== 0) {
                        $choice = $choices->where('value', $max_rank)->first();
                        if($choice->is_answer) $correct++;
                    }
                }
            }
            $algorithm->accuracy = $correct / $total;
        }

        return view('dashboard.algorithms', ['algorithms' => $algorithms]);
    }
}
