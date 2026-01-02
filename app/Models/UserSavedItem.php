<?php

namespace App\Models;

class UserSavedItem extends Model
{
    protected $table = 'user_saved_items';
    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action'
    ];
    
    public static function findByUserAndEntity($userId, $entityType, $entityId, $action = null)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM user_saved_items WHERE user_id = ? AND entity_type = ? AND entity_id = ?";
        $params = [$userId, $entityType, $entityId];
        
        if ($action) {
            $sql .= " AND action = ?";
            $params[] = $action;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
    
    public static function findByUser($userId, $action = null)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM user_saved_items WHERE user_id = ?";
        $params = [$userId];
        
        if ($action) {
            $sql .= " AND action = ?";
            $params[] = $action;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll();
        $savedItems = [];
        
        foreach ($results as $result) {
            $savedItems[] = new self($result);
        }
        
        return $savedItems;
    }
}