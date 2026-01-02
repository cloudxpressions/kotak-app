<?php

namespace App\Core;

use App\Models\Language as LanguageModel;

class Language
{
    private $currentLanguage;
    private $fallbackLanguage = 'en';

    public function __construct()
    {
        $this->detectLanguage();
    }

    private function detectLanguage()
    {
        // Check for language in request header
        $requestLanguage = Request::header('Accept-Language');
        if ($requestLanguage) {
            $this->currentLanguage = substr($requestLanguage, 0, 2);
        } else {
            // Fallback to default language from config
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $this->currentLanguage = $config['locale'];
        }

        // Validate that the language exists in our system
        if (!$this->isValidLanguage($this->currentLanguage)) {
            $this->currentLanguage = $this->fallbackLanguage;
        }
    }

    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }

    public function getFallbackLanguage()
    {
        return $this->fallbackLanguage;
    }

    public function getAllLanguages()
    {
        return LanguageModel::all();
    }

    public function getTranslationsForEntity($entityType, $entityId, $languageCode = null)
    {
        if (!$languageCode) {
            $languageCode = $this->currentLanguage;
        }

        // Determine the translation table based on entity type
        $translationTable = $this->getTranslationTable($entityType);
        
        if (!$translationTable) {
            return null;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT * FROM {$translationTable} 
            WHERE {$entityType}_id = ? AND language_code = ?
        ");
        $stmt->execute([$entityId, $languageCode]);
        
        $translation = $stmt->fetch();
        
        // If no translation found, try fallback language
        if (!$translation && $languageCode !== $this->fallbackLanguage) {
            $stmt = $db->prepare("
                SELECT * FROM {$translationTable} 
                WHERE {$entityType}_id = ? AND language_code = ?
            ");
            $stmt->execute([$entityId, $this->fallbackLanguage]);
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
            'terminology' => 'terminology_translations',
            'material' => 'material_translations'
        ];

        return $tables[$entityType] ?? null;
    }

    private function isValidLanguage($languageCode)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM languages WHERE code = ? AND is_active = 1");
        $stmt->execute([$languageCode]);
        return $stmt->fetchColumn() > 0;
    }

    public function translate($entityType, $entityId, $fields = ['title', 'content'])
    {
        $translation = $this->getTranslationsForEntity($entityType, $entityId);
        
        if (!$translation) {
            return null;
        }

        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $translation[$field] ?? null;
        }

        return $result;
    }
}