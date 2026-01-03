<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Jobs\SendNewsletterJob;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class NewsletterController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:newsletter.view', only: ['index', 'show']),
            new Middleware('permission:newsletter.create', only: ['create', 'store']),
            new Middleware('permission:newsletter.update', only: ['edit', 'update', 'send']),
            new Middleware('permission:newsletter.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $newsletters = Newsletter::query();

            // Apply search if provided
            if ($request->has('search') && $request->get('search')['value']) {
                $searchValue = $request->get('search')['value'];
                $newsletters->where(function($query) use ($searchValue) {
                    $query->where('subject', 'LIKE', "%{$searchValue}%")
                          ->orWhere('status', 'LIKE', "%{$searchValue}%");
                });
            }

            // Apply ordering
            if ($request->has('order')) {
                $columnIndex = $request->get('order')[0]['column'];
                $direction = $request->get('order')[0]['dir'];

                $columns = [
                    0 => 'id',        // checkbox column
                    1 => 'subject',
                    2 => 'status',
                    3 => 'total_recipients',
                    4 => 'total_sent',
                    5 => 'created_at',
                    6 => 'scheduled_for',
                    7 => 'id'         // actions column
                ];

                $column = $columns[$columnIndex] ?? 'created_at';
                $newsletters->orderBy($column, $direction);
            } else {
                $newsletters->orderBy('created_at', 'desc');
            }

            return DataTables::of($newsletters)
                ->addColumn('status_badge', function ($newsletter) {
                    $statusColors = [
                        'draft' => 'bg-secondary-lt',
                        'scheduled' => 'bg-info-lt',
                        'sending' => 'bg-warning-lt',
                        'sent' => 'bg-success-lt',
                        'cancelled' => 'bg-danger-lt'
                    ];

                    $color = $statusColors[$newsletter->status] ?? 'bg-secondary-lt';

                    return "<span class='badge {$color}'>" . ucfirst($newsletter->status) . "</span>";
                })
                ->addColumn('stats', function ($newsletter) {
                    $openRate = $newsletter->total_sent > 0 ? round(($newsletter->total_opened / $newsletter->total_sent) * 100, 1) : 0;
                    $clickRate = $newsletter->total_sent > 0 ? round(($newsletter->total_clicked / $newsletter->total_sent) * 100, 1) : 0;

                    return "
                        <div class='d-flex flex-column'>
                            <div><strong>Recipients:</strong> {$newsletter->total_recipients}</div>
                            <div><strong>Sent:</strong> {$newsletter->total_sent}</div>
                            <div><strong>Opened:</strong> {$newsletter->total_opened} ({$openRate}%)</div>
                            <div><strong>Clicked:</strong> {$newsletter->total_clicked} ({$clickRate}%)</div>
                        </div>
                    ";
                })
                ->addColumn('action', function ($newsletter) {
                    $actions = '<div class="btn-list flex-nowrap">';

                    // Preview button
                    $actions .= '<a href="' . route('admin.system.newsletters.preview', $newsletter->id) . '" target="_blank" class="btn btn-sm btn-icon btn-info" title="Preview">';
                    $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                    $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />';
                    $actions .= '</svg>';
                    $actions .= '</a>';

                    // Edit button
                    $actions .= '<a href="' . route('admin.system.newsletters.edit', $newsletter->id) . '" class="btn btn-sm btn-icon btn-primary ms-1" title="Edit">';
                    $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                    $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" />';
                    $actions .= '</svg>';
                    $actions .= '</a>';

                    // Send button (only for drafts and scheduled newsletters)
                    if (in_array($newsletter->status, ['draft', 'scheduled'])) {
                        $actions .= '<button type="button" class="btn btn-sm btn-icon btn-success ms-1 send-btn" data-id="' . $newsletter->id . '" title="Send Now">';
                        $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                        $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />';
                        $actions .= '</svg>';
                        $actions .= '</button>';
                    }

                    // Delete button
                    $actions .= '<button type="button" class="btn btn-sm btn-icon btn-danger ms-1 delete-btn" data-id="' . $newsletter->id . '" title="Delete">';
                    $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                    $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />';
                    $actions .= '</svg>';
                    $actions .= '</button>';

                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('scheduled_for', function ($newsletter) {
                    return $newsletter->scheduled_for ? $newsletter->scheduled_for->format('Y-m-d H:i:s') : 'Immediate';
                })
                ->editColumn('created_at', function ($newsletter) {
                    return $newsletter->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['status_badge', 'stats', 'action'])
                ->make(true);
        }

        return view('admin.system.newsletters.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.system.newsletters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'status' => 'required|in:draft,scheduled',
            'scheduled_for' => 'nullable|date|after:now',
        ]);

        $newsletter = Newsletter::create([
            'subject' => $request->subject,
            'body_html' => $request->body_html,
            'body_text' => $request->body_text ?? strip_tags($request->body_html),
            'status' => $request->status,
            'scheduled_for' => $request->status === 'scheduled' ? $request->scheduled_for : null,
        ]);

        return redirect()->route('admin.system.newsletters.index')
            ->with('success', 'Newsletter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        return view('admin.system.newsletters.show', compact('newsletter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        return view('admin.system.newsletters.edit', compact('newsletter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'status' => 'required|in:draft,scheduled,sent,cancelled',
            'scheduled_for' => 'nullable|date|after:now',
        ]);

        $newsletter->update([
            'subject' => $request->subject,
            'body_html' => $request->body_html,
            'body_text' => $request->body_text ?? strip_tags($request->body_html),
            'status' => $request->status,
            'scheduled_for' => in_array($request->status, ['scheduled', 'sending']) ? $request->scheduled_for : null,
        ]);

        return redirect()->route('admin.system.newsletters.index')
            ->with('success', 'Newsletter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Newsletter deleted successfully.'
        ]);
    }

    /**
     * Send newsletter to subscribers
     */
    public function send(Request $request, Newsletter $newsletter)
    {
        // Validate that the newsletter is in the correct status
        if (!in_array($newsletter->status, ['draft', 'scheduled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot send newsletter in current status.'
            ], 400);
        }

        // Update newsletter status to sending
        $newsletter->update(['status' => 'sending']);

        // Get all active subscribers
        $subscribers = NewsletterSubscriber::active()->get();

        // Create newsletter sends and dispatch job
        foreach ($subscribers as $subscriber) {
            $newsletterSend = $newsletter->newsletterSends()->create([
                'subscriber_id' => $subscriber->id,
                'status' => 'queued'
            ]);
        }

        // Dispatch the job to send the newsletter
        SendNewsletterJob::dispatch($newsletter);

        return response()->json([
            'success' => true,
            'message' => 'Newsletter queued for sending to ' . $subscribers->count() . ' subscribers.'
        ]);
    }

    /**
     * Preview newsletter
     */
    public function preview(Newsletter $newsletter)
    {
        $content = $newsletter->body_html;
        
        // Add tracking pixel to the content if the newsletter is sent
        if ($newsletter->status === 'sent') {
            $trackingPixel = '<img src="' . route('newsletter.track-open', ['id' => $newsletter->id, 'subscriber_id' => '{subscriber_id}']) . '" alt="" width="1" height="1" style="display:none;">';
            $content .= $trackingPixel;
        }
        
        return $content;
    }
}