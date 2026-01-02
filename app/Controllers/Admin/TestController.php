<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Core\Request;
use App\Models\Test;

class TestController
{
    public function index()
    {
        $tests = Test::all();
        return Response::success($tests);
    }

    public function createTest()
    {
        $test = Test::create(Request::all());
        return Response::success($test, 'Test created');
    }

    public function updateTest($id)
    {
        $test = Test::find($id);
        if (!$test) return Response::notFound();
        $test->update(Request::all());
        return Response::success($test, 'Test updated');
    }
    
    public function deleteTest($id)
    {
        $test = Test::find($id);
        if (!$test) return Response::notFound();
        $test->delete();
        return Response::success(null, 'Test deleted');
    }
}
