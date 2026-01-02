<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Auth;
use App\Services\TestEngineService;

class TestController
{
    private $testEngineService;

    public function __construct()
    {
        $this->testEngineService = new TestEngineService();
    }

    public function getTests($params = [])
    {
        // For now, return a simple response
        // In a real implementation, this would fetch available tests
        $tests = [
            ['id' => 1, 'name' => 'IC-38 Practice Test 1', 'description' => 'Practice test for IC-38 exam'],
            ['id' => 2, 'name' => 'IC-38 Practice Test 2', 'description' => 'Another practice test for IC-38 exam']
        ];
        
        Response::success($tests, 'Tests retrieved successfully');
    }

    public function startTest($params = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            Response::unauthorized('Authentication required');
        }
        
        $testId = $params['id'] ?? null;
        
        if (!$testId) {
            Response::error('Test ID is required', 400);
        }
        
        $result = $this->testEngineService->startTest($testId, $user->id);
        
        if ($result) {
            Response::success($result, 'Test started successfully');
        } else {
            Response::error('Failed to start test', 500);
        }
    }

    public function submitTest($params = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            Response::unauthorized('Authentication required');
        }
        
        $testId = $params['id'] ?? null;
        $data = Request::input();
        $answers = $data['answers'] ?? null;
        
        if (!$testId) {
            Response::error('Test ID is required', 400);
        }
        
        if (!$answers) {
            Response::error('Answers are required', 400);
        }
        
        // In a real implementation, we would need the attempt ID
        // For this example, we'll assume the user can only have one active attempt per test
        Response::error('Test submission requires attempt ID', 501);
    }
}