<?php

namespace App\Models;

class AdEvent extends Model
{
    protected $table = 'ad_events';
    protected $fillable = [
        'user_id',
        'ad_type',
        'event',
        'platform'
    ];
    
    public static function findByUser($userId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM ad_events WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        $results = $stmt->fetchAll();
        $events = [];
        
        foreach ($results as $result) {
            $events[] = new self($result);
        }
        
        return $events;
    }
    
    public static function getPerformance($adType = null, $startDate = null, $endDate = null)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT ad_type, event, COUNT(*) as count FROM ad_events WHERE 1=1";
        $params = [];
        
        if ($adType) {
            $sql .= " AND ad_type = ?";
            $params[] = $adType;
        }
        
        if ($startDate) {
            $sql .= " AND created_at >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND created_at <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY ad_type, event";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public static function countRecentByUserAndType($userId, $adType, $seconds)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT COUNT(*) as count FROM ad_events WHERE user_id = ? AND ad_type = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $adType, $seconds]);
        
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }
}