<?php

namespace App\Services;

use App\Models\PerformanceStat;
use App\Models\TestAttempt;

class PerformanceService
{
    public function getUserPerformance($userId)
    {
        $stats = PerformanceStat::findByUser($userId);
        
        if (!$stats) {
            // Create initial stats if they don't exist
            $stats = new PerformanceStat();
            $stats->user_id = $userId;
            $stats->total_tests = 0;
            $stats->avg_score = 0;
            $stats->accuracy = 0;
            $stats->save();
        }
        
        return $stats;
    }

    public function updatePerformance($userId, $testAttempt)
    {
        $stats = PerformanceStat::findByUser($userId);
        
        if (!$stats) {
            $stats = new PerformanceStat();
            $stats->user_id = $userId;
        }
        
        // Update stats based on the test attempt
        $stats->total_tests += 1;
        $stats->last_test_at = date('Y-m-d H:i:s');
        
        // Calculate new average score
        $totalScore = ($stats->avg_score * ($stats->total_tests - 1)) + $testAttempt->percentage;
        $stats->avg_score = $totalScore / $stats->total_tests;
        
        // Calculate accuracy based on correct answers
        if ($testAttempt->total_questions > 0) {
            $totalCorrect = ($stats->accuracy * ($stats->total_tests - 1) * $testAttempt->total_questions) + $testAttempt->correct_answers;
            $totalPossible = $stats->total_tests * $testAttempt->total_questions;
            $stats->accuracy = $totalCorrect / $totalPossible;
        }
        
        return $stats->save();
    }

    public function getUserTestHistory($userId)
    {
        return TestAttempt::findByUser($userId);
    }

    public function getPerformanceInsights($userId)
    {
        $attempts = $this->getUserTestHistory($userId);
        
        if (empty($attempts)) {
            return [
                'improvement_trend' => 0,
                'strong_areas' => [],
                'improvement_areas' => [],
                'recommended_tests' => []
            ];
        }
        
        // Calculate improvement trend (average change in scores)
        $scores = array_map(function($attempt) {
            return $attempt->percentage;
        }, $attempts);
        
        $improvementTrend = 0;
        if (count($scores) > 1) {
            $firstScore = $scores[0];
            $lastScore = $scores[count($scores) - 1];
            $improvementTrend = $lastScore - $firstScore;
        }
        
        // For simplicity, we'll return basic insights
        // In a real implementation, this would include more detailed analysis
        return [
            'improvement_trend' => $improvementTrend,
            'strong_areas' => [], // Would be determined by analyzing test results by category
            'improvement_areas' => [], // Would be determined by analyzing weak areas
            'recommended_tests' => [] // Would be determined by analyzing performance gaps
        ];
    }
}