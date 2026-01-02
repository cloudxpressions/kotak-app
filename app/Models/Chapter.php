<?php

namespace App\Models;

class Chapter extends Model
{
    protected $table = 'chapters';
    protected $fillable = [
        'order_no',
        'is_active'
    ];

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        // Join with chapter_translations to get title and description
        $sql = "SELECT c.*, ct.title, ct.description, c.is_active as status
                FROM chapters c
                LEFT JOIN chapter_translations ct ON c.id = ct.chapter_id AND ct.language_code = 'en'";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $chapters = [];

        foreach ($results as $result) {
            $chapters[] = new static($result);
        }

        return $chapters;
    }

    public static function allActive()
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $sql = "SELECT c.*, ct.title, ct.description, c.is_active as status
                FROM chapters c
                LEFT JOIN chapter_translations ct ON c.id = ct.chapter_id AND ct.language_code = 'en'
                WHERE c.is_active = 1 ORDER BY c.order_no";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $chapters = [];

        foreach ($results as $result) {
            $chapters[] = new static($result);
        }

        return $chapters;
    }

    public function getTopics()
    {
        return Topic::findByChapter($this->attributes['id']);
    }
}