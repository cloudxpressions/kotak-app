<?php

namespace App\Models;

class Terminology extends Model
{
    protected $table = 'terminologies';
    protected $fillable = [
        'category'
    ];

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $table = static::getTable();

        // Join with terminology_translations to get term and definition
        // Note: terminologies table doesn't have is_active, so we'll add a default status
        $sql = "SELECT t.*, tt.term, tt.definition, 1 as status
                FROM terminologies t
                LEFT JOIN terminology_translations tt ON t.id = tt.terminology_id AND tt.language_code = 'en'";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $models = [];

        foreach ($results as $result) {
            $models[] = new static($result);
        }

        return $models;
    }
}