<?php

namespace App\Models;

class Test extends Model
{
    protected $table = 'tests';
    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'total_questions',
        'is_active'
    ];

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $table = static::getTable();

        // Join with test_translations if it exists, otherwise just select from tests
        // For now, just select from tests table and map is_active to status
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