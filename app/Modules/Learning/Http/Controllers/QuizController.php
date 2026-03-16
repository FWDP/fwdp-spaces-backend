<?php

namespace App\Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Learning\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show($quizId)
    {
        return Quiz::query()->with('questions')->findOrFail($quizId);
    }

    public function submit(Request $request, $quizId)
    {
        $quiz = Quiz::query()->with('questions')->findOrFail($quizId);

        $score = 0;
        $answers = $request->input('answers', []);

        foreach ($quiz->questions as $question) {
            if (isset($answers[$question->id]) &&
                $answers[$question->id] == $question->correct_answer
            ) {
                $score++;
            }
        }

        return [
            'score' => $score,
            'total' => $quiz->questions->count()
        ];
    }
}
