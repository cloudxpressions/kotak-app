<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class NewsletterSubscriberController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:newsletter-subscriber.view', only: ['index']),
            new Middleware('permission:newsletter-subscriber.create', only: ['store']),
            new Middleware('permission:newsletter-subscriber.update', only: ['update']),
            new Middleware('permission:newsletter-subscriber.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscribers = NewsletterSubscriber::query();

            // Apply search if provided
            if ($request->has('search') && $request->get('search')['value']) {
                $searchValue = $request->get('search')['value'];
                $subscribers->where(function($query) use ($searchValue) {
                    $query->where('email', 'LIKE', "%{$searchValue}%")
                          ->orWhere('name', 'LIKE', "%{$searchValue}%");
                });
            }

            // Apply ordering
            if ($request->has('order')) {
                $columnIndex = $request->get('order')[0]['column'];
                $direction = $request->get('order')[0]['dir'];

                $columns = [
                    0 => 'id',        // checkbox column
                    1 => 'email',
                    2 => 'name',
                    3 => 'status',
                    4 => 'source',
                    5 => 'created_at',
                    6 => 'id'         // actions column
                ];

                $column = $columns[$columnIndex] ?? 'created_at';
                $subscribers->orderBy($column, $direction);
            } else {
                $subscribers->orderBy('created_at', 'desc');
            }

            return DataTables::of($subscribers)
                ->addColumn('status_badge', function ($subscriber) {
                    $statusColors = [
                        'pending' => 'bg-warning-lt',
                        'subscribed' => 'bg-success-lt',
                        'unsubscribed' => 'bg-secondary-lt',
                        'bounced' => 'bg-danger-lt'
                    ];

                    $color = $statusColors[$subscriber->status] ?? 'bg-secondary-lt';

                    return "<span class='badge {$color}'>" . ucfirst($subscriber->status) . "</span>";
                })
                ->addColumn('action', function ($subscriber) {
                    $actions = '<div class="btn-list flex-nowrap">';

                    $actions .= '<a href="#" class="btn btn-sm btn-icon btn-info" title="View">';
                    $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                    $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />';
                    $actions .= '</svg>';
                    $actions .= '</a>';

                    $actions .= '<button type="button" class="btn btn-sm btn-icon btn-danger ms-1 delete-btn" data-id="' . $subscriber->id . '" title="Delete">';
                    $actions .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
                    $actions .= '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />';
                    $actions .= '</svg>';
                    $actions .= '</button>';

                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($subscriber) {
                    return $subscriber->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.system.newsletter-subscribers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:pending,subscribed,unsubscribed,bounced',
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'status' => $request->status,
            'subscribed_at' => $request->status === 'subscribed' ? now() : null,
            'source' => 'admin-panel',
        ]);

        return redirect()->route('admin.system.newsletter-subscribers.index')
            ->with('success', 'Subscriber created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsletterSubscriber $newsletterSubscriber)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email,' . $newsletterSubscriber->id,
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:pending,subscribed,unsubscribed,bounced',
        ]);

        $oldStatus = $newsletterSubscriber->status;
        
        $newsletterSubscriber->update([
            'email' => $request->email,
            'name' => $request->name,
            'status' => $request->status,
            'subscribed_at' => $request->status === 'subscribed' && $oldStatus !== 'subscribed' ? now() : $newsletterSubscriber->subscribed_at,
            'unsubscribed_at' => $request->status === 'unsubscribed' && $oldStatus !== 'unsubscribed' ? now() : $newsletterSubscriber->unsubscribed_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscriber updated successfully.',
            'data' => $newsletterSubscriber
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriber deleted successfully.'
        ]);
    }

    /**
     * Bulk delete subscribers
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:newsletter_subscribers,id'
        ]);

        NewsletterSubscriber::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' subscribers deleted successfully.'
        ]);
    }
}