<?php

namespace App\Models;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'difficulty',
        'correct_option'
    ];

    public static function all()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $table = static::getTable();

        // Join with question_translations to get question text
        // Note: questions table doesn't have is_active, so we'll add a default status
        $sql = "SELECT q.*, qt.question_text, q.correct_option as correct_answer, 1 as status
                FROM questions q
                LEFT JOIN question_translations qt ON q.id = qt.question_id AND qt.language_code = 'en'";
        $stmt = $db->query($sql);

        $results = $stmt->fetchAll();
        $models = [];

        foreach ($results as $result) {
            $models[] = new static($result);
        }

        return $models;
    }

    public static function findByTest($testId)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $sql = "SELECT q.*, qt.question_text, q.correct_option as correct_answer, 1 as status FROM questions q
                JOIN test_questions tq ON q.id = tq.question_id
                LEFT JOIN question_translations qt ON q.id = qt.question_id AND qt.language_code = 'en'
                WHERE tq.test_id = ?
                ORDER BY tq.order_no";
        $stmt = $db->prepare($sql);
        $stmt->execute([$testId]);

        $results = $stmt->fetchAll();
        $questions = [];

        foreach ($results as $result) {
            $questions[] = new static($result);
        }

        return $questions;
    }
}