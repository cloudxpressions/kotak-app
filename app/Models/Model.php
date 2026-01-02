<?php

namespace App\Models;

use App\Core\Database;

abstract class Model
{
    protected $table;
    protected $fillable = [];
    protected $hidden = [];
    
    // Properties that will be set from database columns
    protected $attributes = [];
    
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }
    
    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        
        return null;
    }
    
    public function __set($key, $value)
    {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }
    
    public function save()
    {
        $db = Database::getInstance()->getConnection();
        
        if (isset($this->attributes['id'])) {
            // Update existing record
            $columns = array_keys(array_diff_key($this->attributes, array_flip(['id'])));
            $setClause = implode(' = ?, ', $columns) . ' = ?';
            
            $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
            $params = array_values(array_diff_key($this->attributes, array_flip(['id'])));
            $params[] = $this->attributes['id'];
            
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
        } else {
            // Insert new record
            $columns = array_keys($this->attributes);
            $placeholders = str_repeat('?,', count($this->attributes) - 1) . '?';
            
            $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") VALUES ({$placeholders})";
            $stmt = $db->prepare($sql);
            return $stmt->execute(array_values($this->attributes));
        }
    }
    
    public static function find($id)
    {
        $db = Database::getInstance()->getConnection();
        $table = static::getTable();
        
        $sql = "SELECT * FROM {$table} WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        
        return $result ? new static($result) : null;
    }
    
    public static function all()
    {
        $db = Database::getInstance()->getConnection();
        $table = static::getTable();
        
        $sql = "SELECT * FROM {$table}";
        $stmt = $db->query($sql);
        
        $results = $stmt->fetchAll();
        $models = [];
        
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        
        return $models;
    }
    
    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        
        $db = Database::getInstance()->getConnection();
        $table = $this->table;
        
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$this->attributes['id']]);
    }
    
    protected static function getTable()
    {
        $instance = new static();
        return $instance->table;
    }
    
    protected static function query($sql, $params = [])
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt;
    }
    public static function create(array $data)
    {
        $instance = new static();
        // Simple fill
        foreach ($data as $key => $value) {
             // We could check fillable here
             $instance->$key = $value; 
        }
        
        $instance->save();
        
        // We need the ID back. existing save() returns boolean. 
        // We should really fix save() to set ID, but for now assuming save() pattern:
        // Let's rely on save() implementation. But wait, save() in Model.php uses execute() which returns bool.
        // It does NOT set the lastInsertId. We should fix save() too if we want `create` to return the full object with ID.
        // For this task, I'll update save() in a separate call or just rely on 'create' returning success without ID if that's acceptable, 
        // but frontend usually needs ID for the table row.
        
        // Let's fix save() logic in create for now
        $db = Database::getInstance()->getConnection();
        $instance->attributes['id'] = $db->lastInsertId();
        
        return $instance;
    }

    public function update(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this->save();
    }
    
    public static function count()
    {
        $db = Database::getInstance()->getConnection();
        $table = static::getTable();
        $stmt = $db->query("SELECT COUNT(*) as count FROM {$table}");
        $res = $stmt->fetch();
        return $res ? $res['count'] : 0;
    }

    public function jsonSerialize()
    {
        return $this->attributes;
    }
}