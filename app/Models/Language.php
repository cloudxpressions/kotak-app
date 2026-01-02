<?php

namespace App\Models;

class Language extends Model
{
    protected $table = 'languages';
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_active'
    ];
    
    public static function findByCode($code)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM languages WHERE code = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$code]);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
    
    public static function getActiveLanguages()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM languages WHERE is_active = 1";
        $stmt = $db->query($sql);
        
        $results = $stmt->fetchAll();
        $languages = [];
        
        foreach ($results as $result) {
            $languages[] = new self($result);
        }
        
        return $languages;
    }
}