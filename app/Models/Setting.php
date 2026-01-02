<?php

namespace App\Models;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'group_name',
        'key_name',
        'value',
        'value_type',
        'is_public'
    ];
    
    public static function getAll($publicOnly = true)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM settings";
        if ($publicOnly) {
            $sql .= " WHERE is_public = 1";
        }
        
        $stmt = $db->query($sql);
        $results = $stmt->fetchAll();
        
        $settings = [];
        foreach ($results as $result) {
            // Convert value based on type
            $value = $result['value'];
            switch ($result['value_type']) {
                case 'int':
                    $value = (int)$value;
                    break;
                case 'bool':
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            $settings[$result['group_name'] . '.' . $result['key_name']] = $value;
        }
        
        return $settings;
    }
    
    public static function get($group, $key)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM settings WHERE group_name = ? AND key_name = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$group, $key]);
        
        $result = $stmt->fetch();
        
        if (!$result) {
            return null;
        }
        
        // Convert value based on type
        $value = $result['value'];
        switch ($result['value_type']) {
            case 'int':
                $value = (int)$value;
                break;
            case 'bool':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case 'json':
                $value = json_decode($value, true);
                break;
        }
        
        return $value;
    }
    
    public static function findByGroupAndKey($group, $key)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM settings WHERE group_name = ? AND key_name = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$group, $key]);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
}