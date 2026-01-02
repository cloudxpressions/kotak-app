<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Core\Request;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Question;

class ContentController
{
    // --- Chapters ---
    public function getChapters()
    {
        $chapters = Chapter::all();
        return Response::success($chapters);
    }

    public function createChapter()
    {
        $data = Request::all();
        // Validation
        if(empty($data['title'])) return Response::error('Title is required');
        
        $chapter = Chapter::create($data);
        return Response::success($chapter, 'Chapter created successfully');
    }

    public function updateChapter($id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) return Response::notFound('Chapter not found');
        
        $chapter->update(Request::all());
        return Response::success($chapter, 'Chapter updated successfully');
    }

    public function deleteChapter($id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) return Response::notFound('Chapter not found');
        
        $chapter->delete();
        return Response::success(null, 'Chapter deleted successfully');
    }

    // --- Topics ---
    // Note: You'll need to update routes/admin.php to map these if they aren't already
    // Current routes: only Chapters mapped explicitly in my previous view_file.
    // I should check if I need to add routes for Topics/Questions or if they share endpoint structure.
    
    public function getTopics()
    {
        $topics = Topic::all();
        return Response::success($topics);
    }
    
    public function createTopic() {
        $data = Request::all();
        $topic = Topic::create($data);
        return Response::success($topic, 'Topic created successfully');
    }
    
    public function updateTopic($id) {
         $topic = Topic::find($id);
         if(!$topic) return Response::notFound('Topic not found');
         $topic->update(Request::all());
         return Response::success($topic, 'Topic updated');
    }
    
    public function deleteTopic($id) {
         $topic = Topic::find($id);
         if(!$topic) return Response::notFound('Topic not found');
         $topic->delete();
         return Response::success(null, 'Topic deleted');
    }

    // --- Questions ---
    public function getQuestions()
    {
        $questions = Question::all();
        return Response::success($questions);
    }
    
    public function createQuestion() {
        $questions = Question::create(Request::all());
        return Response::success($questions, 'Question created');
    }
    
    public function updateQuestion($id) {
        $q = Question::find($id);
        if(!$q) return Response::notFound();
        $q->update(Request::all());
        return Response::success($q, 'Question updated');
    }
    
    public function deleteQuestion($id) {
        $q = Question::find($id);
        if(!$q) return Response::notFound();
        $q->delete();
        return Response::success(null, 'Question deleted');
    }
}
