<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Terminology;
use App\Core\Language;

class ContentService
{
    public function getChapters($languageCode = null)
    {
        $chapters = Chapter::allActive();
        
        // Get translations for each chapter
        foreach ($chapters as $chapter) {
            $translation = $this->getTranslation('chapter', $chapter->id, $languageCode);
            if ($translation) {
                $chapter->title = $translation['title'] ?? $chapter->title;
                $chapter->description = $translation['description'] ?? $chapter->description;
            }
        }
        
        return $chapters;
    }

    public function getChapter($id, $languageCode = null)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return null;
        }
        
        $translation = $this->getTranslation('chapter', $chapter->id, $languageCode);
        if ($translation) {
            $chapter->title = $translation['title'] ?? $chapter->title;
            $chapter->description = $translation['description'] ?? $chapter->description;
        }
        
        return $chapter;
    }

    public function getTopicsByChapter($chapterId, $languageCode = null)
    {
        $topics = Topic::findByChapter($chapterId);
        
        foreach ($topics as $topic) {
            $translation = $this->getTranslation('topic', $topic->id, $languageCode);
            if ($translation) {
                $topic->title = $translation['title'] ?? $topic->title;
                $topic->content_html = $translation['content_html'] ?? $topic->content_html;
            }
        }
        
        return $topics;
    }

    public function getTopic($id, $languageCode = null)
    {
        $topic = Topic::find($id);
        if (!$topic) {
            return null;
        }
        
        $translation = $this->getTranslation('topic', $topic->id, $languageCode);
        if ($translation) {
            $topic->title = $translation['title'] ?? $topic->title;
            $topic->content_html = $translation['content_html'] ?? $topic->content_html;
        }
        
        return $topic;
    }

    public function getTerminologies($languageCode = null)
    {
        $terminologies = Terminology::all();
        
        foreach ($terminologies as $terminology) {
            $translation = $this->getTranslation('terminology', $terminology->id, $languageCode);
            if ($translation) {
                $terminology->term = $translation['term'] ?? $terminology->term;
                $terminology->definition = $translation['definition'] ?? $terminology->definition;
            }
        }
        
        return $terminologies;
    }

    public function getTerminology($id, $languageCode = null)
    {
        $terminology = Terminology::find($id);
        if (!$terminology) {
            return null;
        }
        
        $translation = $this->getTranslation('terminology', $terminology->id, $languageCode);
        if ($translation) {
            $terminology->term = $translation['term'] ?? $terminology->term;
            $terminology->definition = $translation['definition'] ?? $terminology->definition;
        }
        
        return $terminology;
    }

    public function getQuestionsByTest($testId, $languageCode = null)
    {
        $questions = Question::findByTest($testId);
        
        foreach ($questions as $question) {
            $translation = $this->getTranslation('question', $question->id, $languageCode);
            if ($translation) {
                $question->question_text = $translation['question_text'] ?? $question->question_text;
                $question->option_a = $translation['option_a'] ?? $question->option_a;
                $question->option_b = $translation['option_b'] ?? $question->option_b;
                $question->option_c = $translation['option_c'] ?? $question->option_c;
                $question->option_d = $translation['option_d'] ?? $question->option_d;
            }
        }
        
        return $questions;
    }

    private function getTranslation($entityType, $entityId, $languageCode = null)
    {
        if (!$languageCode) {
            $app = \App\Core\App::getInstance();
            $languageCode = $app->get('language')->getCurrentLanguage();
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        $translationTable = $this->getTranslationTable($entityType);
        
        if (!$translationTable) {
            return null;
        }

        $stmt = $db->prepare("
            SELECT * FROM {$translationTable} 
            WHERE {$entityType}_id = ? AND language_code = ?
        ");
        $stmt->execute([$entityId, $languageCode]);
        
        $translation = $stmt->fetch();
        
        // If no translation found, try fallback language
        if (!$translation) {
            $stmt = $db->prepare("
                SELECT * FROM {$translationTable} 
                WHERE {$entityType}_id = ? AND language_code = ?
            ");
            $stmt->execute([$entityId, 'en']); // Fallback to English
            $translation = $stmt->fetch();
        }

        return $translation;
    }

    private function getTranslationTable($entityType)
    {
        $tables = [
            'chapter' => 'chapter_translations',
            'topic' => 'topic_translations',
            'question' => 'question_translations',
            'terminology' => 'terminology_translations'
        ];

        return $tables[$entityType] ?? null;
    }
}