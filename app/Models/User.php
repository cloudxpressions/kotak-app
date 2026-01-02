<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email', 
        'mobile',
        'password',
        'gender',
        'dob',
        'qualification',
        'occupation',
        'state',
        'district',
        'exam_target',
        'preferred_language',
        'device_id',
        'is_active',
        'last_login_at'
    ];
    
    public static function findByEmail($email)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        
        $result = $stmt->fetch();
        
        return $result ? new self($result) : null;
    }
    
    public function setPassword($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function verifyPassword($password)
    {
        return password_verify($password, $this->attributes['password']);
    }
    
    public function getRole()
    {
        // In a more complex system, this would join with roles table
        // For now, we'll return a basic role based on some criteria
        return $this->attributes['email'] === 'admin@example.com' ? 'admin' : 'user';
    }

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $table = static::getTable();

        $sql = "SELECT *, is_active as status FROM {$table}";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $models = [];

        foreach ($results as $result) {
            $models[] = new static($result);
        }

        return $models;
    }
}