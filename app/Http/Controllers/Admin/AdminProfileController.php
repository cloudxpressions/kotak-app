<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile settings page
     */
    public function index()
    {
        $admin = auth()->guard('admin')->user()->load(['education', 'skills']);

        $proficiencyLevels = ['Beginner', 'Intermediate', 'Advanced', 'Expert', 'Master'];

        // Fetch only required columns for dropdown options
        $countries = \App\Models\Country::select('id', 'name')->get();

        // Only fetch states if country is selected, otherwise return empty collection
        $states = $admin->country_id
            ? \App\Models\State::select('id', 'name')->where('country_id', $admin->country_id)->get()
            : collect();

        // Only fetch cities if state is selected, otherwise return empty collection
        $cities = $admin->state_id
            ? \App\Models\City::select('id', 'name')->where('state_id', $admin->state_id)->get()
            : collect();

        $languages = \App\Models\Language::select('id', 'name')->get();
        $dateFormats = \App\Models\DateFormat::select('id', 'format')->get();
        $timeZones = \App\Models\TimeZone::select('id', 'name')->get();
        $currencies = \App\Models\Currency::select('id', 'name', 'symbol')->get();
        $userClassifications = \App\Models\UserClassification::select('id', 'name')->get();
        $communities = \App\Models\Community::select('id', 'name')->get();
        $daCategories = \App\Models\DACategory::select('id', 'name')->get();
        $religions = \App\Models\Religion::select('id', 'name')->get();
        $specialCategories = \App\Models\SpecialCategory::select('id', 'name')->get();

        return view('admin.profile.index', compact(
            'admin', 'proficiencyLevels', 'countries', 'states', 'cities', 'languages', 'dateFormats',
            'timeZones', 'currencies', 'userClassifications', 'communities',
            'daCategories', 'religions', 'specialCategories'
        ));
    }

    /**
     * Update basic profile information
     */
    public function updateBasicInfo(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            // Personal Details
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'bio' => ['nullable', 'string'],
            'short_bio' => ['nullable', 'string', 'max:255'],
            'is_differently_abled' => ['boolean'],

            // Profile image handling
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max

            // Address Details
            'locality' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'aadhaar_number' => ['nullable', 'string', 'max:20'],

            // Family Details
            'fathers_name' => ['nullable', 'string', 'max:100'],
            'mothers_name' => ['nullable', 'string', 'max:100'],
            'parent_mobile_number' => ['nullable', 'string', 'max:20'],

            // Preferences
            'language_id' => ['nullable', 'exists:languages,id'],
            'dateformat_id' => ['nullable', 'exists:date_formats,id'],
            'timezone_id' => ['nullable', 'exists:time_zones,id'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'medium_of_exam' => ['nullable', 'string'],
            'favorite_topics' => ['nullable', 'array'],

            // Classifications
            'user_classifications_id' => ['nullable', 'exists:user_classifications,id'],
            'community_id' => ['nullable', 'exists:communities,id'],
            'd_a_category_id' => ['nullable', 'exists:d_a_categories,id'],
            'religion_id' => ['nullable', 'exists:religions,id'],
            'special_category_id' => ['nullable', 'exists:special_categories,id'],

            // Social Links
            'facebook' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],

            // Payout
            'payout_email' => ['nullable', 'email'],
            'payout_icon' => ['nullable', 'string'],
            'payout' => ['nullable', 'numeric'],
        ]);

        // Handle boolean fields explicitly if needed, though validate handles it
        $validated['is_differently_abled'] = $request->has('is_differently_abled');

        // Handle image deletion if requested
        if ($request->has('delete_current_image') && $request->input('delete_current_image') == 1) {
            // If there's an existing image, delete it from storage
            if (! empty($admin->image) && Storage::disk('public')->exists($admin->image)) {
                Storage::disk('public')->delete($admin->image);
            }
            $validated['image'] = null;
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Since client-side JavaScript already compresses and converts to WebP,
            // we just need to store it appropriately
            $imagePath = $image->storeAs(
                'profile-pictures/admins/'.$admin->id.'/'.date('Y/m/d'),
                time().'_'.uniqid().'.webp',
                'public'
            );

            // If we're uploading a new image and there was an old one, delete the old one
            if (! empty($admin->image) && Storage::disk('public')->exists($admin->image)) {
                Storage::disk('public')->delete($admin->image);
            }

            $validated['image'] = $imagePath;
        } elseif (! $request->has('delete_current_image')) {
            // Only preserve the old image if user didn't request deletion and isn't uploading new one
            unset($validated['image']); // Don't update the image field if no new image and not deleting
        }

        $admin->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (! Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Add education for self
     */
    public function addEducation(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'qualification' => ['required', 'string', 'max:255'],
            'year_of_passing' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 10)],
            'medium' => ['required', 'string', 'max:255'],
        ]);

        $admin->education()->create($validated);

        return back()->with('success', 'Education added successfully.');
    }

    /**
     * Update education for self
     */
    public function updateEducation(Request $request, $educationId)
    {
        $admin = auth()->guard('admin')->user();
        $education = $admin->education()->findOrFail($educationId);

        $validated = $request->validate([
            'qualification' => ['required', 'string', 'max:255'],
            'year_of_passing' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 10)],
            'medium' => ['required', 'string', 'max:255'],
        ]);

        $education->update($validated);

        return back()->with('success', 'Education updated successfully.');
    }

    /**
     * Delete education for self
     */
    public function deleteEducation($educationId)
    {
        $admin = auth()->guard('admin')->user();
        $education = $admin->education()->findOrFail($educationId);
        $education->delete();

        return back()->with('success', 'Education deleted successfully.');
    }

    /**
     * Add skill for self
     */
    public function addSkill(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', 'in:Beginner,Intermediate,Advanced,Expert,Master'],
            'description' => ['nullable', 'string'],
        ]);

        $admin->skills()->create($validated);

        return back()->with('success', 'Skill added successfully.');
    }

    /**
     * Update skill for self
     */
    public function updateSkill(Request $request, $skillId)
    {
        $admin = auth()->guard('admin')->user();
        $skill = $admin->skills()->findOrFail($skillId);

        $validated = $request->validate([
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', 'in:Beginner,Intermediate,Advanced,Expert,Master'],
            'description' => ['nullable', 'string'],
        ]);

        $skill->update($validated);

        return back()->with('success', 'Skill updated successfully.');
    }

    /**
     * Delete skill for self
     */
    public function deleteSkill($skillId)
    {
        $admin = auth()->guard('admin')->user();
        $skill = $admin->skills()->findOrFail($skillId);
        $skill->delete();

        return back()->with('success', 'Skill deleted successfully.');
    }
}
