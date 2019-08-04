<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard() {
        return view('dashboard.dashboard');
    }
    public function getTestQuestions () {
        $questions = DB::table('questions')->where('is_test', 1)->get();
        foreach ($questions as $question) {
            $choices = DB::table('choices')->where('question_id', $question->id)->get();
            $question->choices = $choices;
        }    
        return view('dashboard.questions', ['questions' => $questions]);
    }
}
