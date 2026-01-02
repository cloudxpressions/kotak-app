<?php

namespace App\Controllers\Api;

use App\Core\Response;
use App\Services\ContentService;

class ContentController
{
    private $contentService;

    public function __construct()
    {
        $this->contentService = new ContentService();
    }

    public function getChapters($params = [])
    {
        $chapters = $this->contentService->getChapters();
        Response::success($chapters, 'Chapters retrieved successfully');
    }

    public function getChapter($params = [])
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            Response::error('Chapter ID is required', 400);
        }
        
        $chapter = $this->contentService->getChapter($id);
        
        if (!$chapter) {
            Response::notFound('Chapter not found');
        }
        
        Response::success($chapter, 'Chapter retrieved successfully');
    }

    public function getTopics($params = [])
    {
        $chapterId = $params['chapter_id'] ?? null;
        
        if ($chapterId) {
            // Get topics for a specific chapter
            $topics = $this->contentService->getTopicsByChapter($chapterId);
            Response::success($topics, 'Topics retrieved successfully');
        } else {
            Response::error('Chapter ID is required to get topics', 400);
        }
    }

    public function getTopic($params = [])
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            Response::error('Topic ID is required', 400);
        }
        
        $topic = $this->contentService->getTopic($id);
        
        if (!$topic) {
            Response::notFound('Topic not found');
        }
        
        Response::success($topic, 'Topic retrieved successfully');
    }

    public function getTerminologies($params = [])
    {
        $terminologies = $this->contentService->getTerminologies();
        Response::success($terminologies, 'Terminologies retrieved successfully');
    }

    public function getTerminology($params = [])
    {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            Response::error('Terminology ID is required', 400);
        }
        
        $terminology = $this->contentService->getTerminology($id);
        
        if (!$terminology) {
            Response::notFound('Terminology not found');
        }
        
        Response::success($terminology, 'Terminology retrieved successfully');
    }
}