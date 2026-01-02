<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Core\Request;
use App\Models\Terminology;

class TerminologyController
{
    public function index()
    {
        $terms = Terminology::all();
        return Response::success($terms);
    }
    
    public function store() {
        $data = Request::all();
        // Validation...
        $term = Terminology::create($data);
        return Response::success($term, 'Terminology created');
    }
    
    public function update($id) {
        $term = Terminology::find($id);
        if(!$term) return Response::notFound('Term not found');
        $term->update(Request::all());
        return Response::success($term, 'Terminology updated');
    }
    
    public function delete($id) {
        $term = Terminology::find($id);
        if(!$term) return Response::notFound('Term not found');
        $term->delete();
        return Response::success(null, 'Terminology deleted');
    }
}
