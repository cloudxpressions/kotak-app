<?php

namespace App\Models;

class TestAttempt extends Model
{
    protected $table = 'test_attempts';
    protected $fillable = [
        'test_id',
        'user_id',
        'answers',
        'score',
        'total_questions',
        'correct_answers',
        'incorrect_answers',
        'percentage',
        'start_time',
        'end_time',
        'status'
    ];
    
    public static function findByUser($userId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM test_attempts WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        $results = $stmt->fetchAll();
        $attempts = [];
        
        foreach ($results as $result) {
            $attempts[] = new self($result);
        }
        
        return $attempts;
    }
    
    public static function findByTestAndUser($testId, $userId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM test_attempts WHERE test_id = ? AND user_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$testId, $userId]);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
}