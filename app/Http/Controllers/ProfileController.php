<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        \Log::info('profile deletion start', ['id' => $user->id]);

        $user->update([
            'delete_request_at' => now(),
            'delete_request_reason' => $request->reason,
        ]);

        // Notify Super Admin
        if (Role::where('guard_name', 'admin')->where('name', 'Super Admin')->exists()) {
            \App\Models\Admin::role('Super Admin')->each(function ($superAdmin) use ($user) {
                $superAdmin->notify(new \App\Notifications\AccountDeletionRequested($user));
            });
        }

        return Redirect::route('profile.edit')->with('status', 'account-deletion-requested');
    }
}
