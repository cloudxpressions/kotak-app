<?php

namespace App\Services;

use App\Models\Test;
use App\Models\Question;
use App\Models\TestAttempt;

class TestEngineService
{
    public function getTest($id)
    {
        return Test::find($id);
    }

    public function startTest($testId, $userId)
    {
        // Create a new test attempt
        $attempt = new TestAttempt();
        $attempt->test_id = $testId;
        $attempt->user_id = $userId;
        $attempt->start_time = date('Y-m-d H:i:s');
        $attempt->status = 'in_progress';
        
        if ($attempt->save()) {
            // Get questions for the test
            $questions = $this->getTestQuestions($testId);
            
            return [
                'attempt_id' => $attempt->id,
                'questions' => $questions,
                'start_time' => $attempt->start_time
            ];
        }
        
        return null;
    }

    public function submitTest($attemptId, $answers)
    {
        $attempt = TestAttempt::find($attemptId);
        if (!$attempt || $attempt->status !== 'in_progress') {
            return ['success' => false, 'message' => 'Invalid test attempt'];
        }

        // Calculate score
        $result = $this->calculateScore($attempt->test_id, $answers);
        
        // Update attempt with results
        $attempt->answers = json_encode($answers);
        $attempt->score = $result['score'];
        $attempt->total_questions = $result['total_questions'];
        $attempt->correct_answers = $result['correct_answers'];
        $attempt->incorrect_answers = $result['incorrect_answers'];
        $attempt->percentage = $result['percentage'];
        $attempt->end_time = date('Y-m-d H:i:s');
        $attempt->status = 'completed';
        
        if ($attempt->save()) {
            return [
                'success' => true,
                'score' => $result['score'],
                'percentage' => $result['percentage'],
                'total_questions' => $result['total_questions'],
                'correct_answers' => $result['correct_answers'],
                'incorrect_answers' => $result['incorrect_answers']
            ];
        }
        
        return ['success' => false, 'message' => 'Failed to save test results'];
    }

    private function getTestQuestions($testId)
    {
        // This would typically get questions for a specific test
        // For now, we'll return all questions for the test
        return Question::findByTest($testId);
    }

    private function calculateScore($testId, $userAnswers)
    {
        $questions = $this->getTestQuestions($testId);
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        
        foreach ($questions as $question) {
            $questionId = $question->id;
            if (isset($userAnswers[$questionId]) && $userAnswers[$questionId] == $question->correct_option) {
                $correctAnswers++;
            }
        }
        
        $score = $correctAnswers;
        $percentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $incorrectAnswers = $totalQuestions - $correctAnswers;
        
        return [
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'percentage' => round($percentage, 2)
        ];
    }
}