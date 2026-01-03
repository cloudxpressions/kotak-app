<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSavedItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserSavedItemController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user_saved_item.view', only: ['index']),
            new Middleware('permission:user_saved_item.delete', only: ['destroy', 'bulkDelete']),
        ];
    }

    /**
     * Display a listing of the user saved items.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userSavedItems = UserSavedItem::select(['id', 'user_id', 'entity_type', 'entity_id', 'action', 'created_at'])
                ->with([
                    'user:id,name'
                ])
                ->get();

            return DataTables::of($userSavedItems)
                ->addIndexColumn()
                ->addColumn('user', function($userSavedItem) {
                    return $userSavedItem->user->name ?? '-';
                })
                ->addColumn('entity_type_badge', function($userSavedItem) {
                    $type = ucfirst(str_replace('_', ' ', $userSavedItem->entity_type));
                    $color = match($userSavedItem->entity_type) {
                        'chapter' => 'bg-blue-lt',
                        'concept' => 'bg-green-lt',
                        'one_liner' => 'bg-orange-lt',
                        'short_simple' => 'bg-purple-lt',
                        'terminology' => 'bg-red-lt',
                        'material' => 'bg-yellow-lt',
                        'question' => 'bg-cyan-lt',
                        default => 'bg-gray-lt'
                    };
                    return "<span class=\"badge $color\">$type</span>";
                })
                ->addColumn('action_badge', function($userSavedItem) {
                    $action = ucfirst($userSavedItem->action);
                    $color = $userSavedItem->action === 'bookmark' ? 'bg-success-lt' : 'bg-info-lt';
                    return "<span class=\"badge $color\">$action</span>";
                })
                ->addColumn('action', function ($userSavedItem) {
                    $buttons = '';

                    if (auth('admin')->user()->can('user_saved_item.delete')) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-icon btn-danger delete-btn ms-1" data-id="' . $userSavedItem->id . '" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>';
                    }

                    return $buttons ?: '<span class="text-muted">No actions</span>';
                })
                ->rawColumns(['entity_type_badge', 'action_badge', 'action'])
                ->make(true);
        }

        return view('admin.insurance.user_saved_items.index');
    }

    /**
     * Remove the specified user saved item from storage.
     */
    public function destroy(UserSavedItem $userSavedItem)
    {
        $userSavedItem = UserSavedItem::select(['id'])->where('id', $userSavedItem->id)->firstOrFail();

        try {
            $userSavedItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'User saved item deleted successfully'
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user saved item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete user saved items
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No user saved item IDs provided.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $deletedCount = UserSavedItem::whereIn('id', $ids)->count();
            UserSavedItem::whereIn('id', $ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$deletedCount user saved item(s) deleted successfully."
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bulk deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}