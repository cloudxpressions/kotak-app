<?php

namespace App\Models;

class Topic extends Model
{
    protected $table = 'topics';
    protected $fillable = [
        'chapter_id',
        'type',
        'order_no'
    ];

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $table = static::getTable();

        // Join with topic_translations to get title
        // Note: topics table doesn't have is_active, so we'll add a default status
        $sql = "SELECT t.*, tt.title, 1 as status
                FROM topics t
                LEFT JOIN topic_translations tt ON t.id = tt.topic_id AND tt.language_code = 'en'";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $models = [];

        foreach ($results as $result) {
            $models[] = new static($result);
        }

        return $models;
    }

    public static function findByChapter($chapterId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        // Join with topic_translations to get title
        // Note: topics table doesn't have is_active, so we'll add a default status
        $sql = "SELECT t.*, tt.title, 1 as status
                FROM topics t
                LEFT JOIN topic_translations tt ON t.id = tt.topic_id AND tt.language_code = 'en'
                WHERE t.chapter_id = ? ORDER BY t.order_no";
        $stmt = $db->prepare($sql);
        $stmt->execute([$chapterId]);

        $results = $stmt->fetchAll();
        $topics = [];

        foreach ($results as $result) {
            $topics[] = new static($result);
        }

        return $topics;
    }
}