<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogCommentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $comments = BlogComment::with(['post', 'commentable'])->latest()->get();

            return DataTables::of($comments)
                ->addIndexColumn()
                ->addColumn('post_title', function ($comment) {
                    return $comment->post ? $comment->post->title : 'N/A';
                })
                ->addColumn('author', function ($comment) {
                    return $comment->commentable ? $comment->commentable->name : 'Guest';
                })
                ->addColumn('status', function ($comment) {
                    return $comment->is_approved
                        ? '<span class="badge bg-success-lt">Approved</span>'
                        : '<span class="badge bg-warning-lt">Pending</span>';
                })
                ->addColumn('created_at', function ($comment) {
                    return $comment->created_at->format('d M Y, h:i A');
                })
                ->addColumn('action', function ($comment) {
                    $approveBtn = $comment->is_approved
                        ? '<button type="button" class="btn btn-sm btn-icon btn-warning status-btn" data-id="' . $comment->id . '" data-status="0" title="Unapprove"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg></button>'
                        : '<button type="button" class="btn btn-sm btn-icon btn-success status-btn" data-id="' . $comment->id . '" data-status="1" title="Approve"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg></button>';

                    $deleteBtn = '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $comment->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';

                    return $approveBtn . $deleteBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.blog.comments.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $comment = BlogComment::select(['id'])->findOrFail($id);
        $comment->is_approved = $request->status;
        $comment->save();

        return response()->json(['success' => true, 'message' => 'Comment status updated successfully.']);
    }

    public function destroy($id)
    {
        $comment = BlogComment::select(['id'])->findOrFail($id);
        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully.']);
    }
}
