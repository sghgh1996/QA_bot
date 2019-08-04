<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

                $max_rank = $choices->max('value');
                if ($max_rank !== 0) {
                    $choice = $choices->where('value', $max_rank)->first();
                    if($choice->is_answer) $correct++;
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
        $question = DB::table('questions')->where('id', $id)->get()->first();
        $choices = DB::table('choices')
            ->where('question_id', $question->id)
            ->get();
        $question->choices = $choices;
        $choices_ids = array();
        foreach ($choices as $choice) {
            array_push($choices_ids, $choice->id);
            if ($choice->is_answer) $question->answer_id = $choice->id;
        }
        $algorithms = DB::table('algorithms')->get();
        foreach ($algorithms as $algorithm) {
            $algorithm_choices = DB::table('choices')
                ->join('ranks', 'ranks.choice_id', '=', 'choices.id')
                ->whereIn('choices.id', $choices_ids)
                ->where('algorithm_id', $algorithm->id)
                ->select('choices.text', 'choices.text', 'choices.is_answer', 'choices.id as choice_id', 'ranks.value')
                ->get();
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
        }
        $question->algorithms = $algorithms;
        // return json_encode($question);
        return view('dashboard.question', ['question' => $question]);
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

                $max_rank = $choices->max('value');
                if ($max_rank !== 0) {
                    $choice = $choices->where('value', $max_rank)->first();
                    if($choice->is_answer) $correct++;
                }
            }
            $algorithm->accuracy = $correct / $total;
        }

        return view('dashboard.algorithms', ['algorithms' => $algorithms]);
    }
}
