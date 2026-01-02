<?php

namespace App\Models;

class PerformanceStat extends Model
{
    protected $table = 'performance_stats';
    protected $fillable = [
        'user_id',
        'total_tests',
        'avg_score',
        'accuracy',
        'last_test_at'
    ];
    
    public static function findByUser($userId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM performance_stats WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
}