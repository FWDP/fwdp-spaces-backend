<?php

namespace App\Modules\Learning\Services;

class QuizService
{
    public function evaluate($quiz, $answers): int
    {
        $score = 0;

        foreach ($quiz->questions as $question) {
            if (isset($answers[$question->id]) &&
                $answers[$question->id] == $question->correct_answer
            ) {
                $score++;
            }
        }

        return $score;
    }
}
