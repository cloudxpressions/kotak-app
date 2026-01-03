<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Language;
use App\Models\DateFormat;
use App\Models\TimeZone;
use App\Models\Currency;
use App\Models\UserClassification;
use App\Models\Community;
use App\Models\DACategory;
use App\Models\Religion;
use App\Models\SpecialCategory;
use Illuminate\Support\Facades\Storage;

class UserAdditionalDetailsController extends Controller
{
    /**
     * Show the detailed profile of a user (for editing by admin)
     */
    public function show($id)
    {
        $user = User::select([
            'id', 'name', 'email', 'mobile', 'whatsapp_number', 'dob', 'gender', 'bio',
            'short_bio', 'is_differently_abled', 'locality', 'address', 'pincode',
            'country_id', 'state_id', 'city_id', 'aadhaar_number', 'fathers_name',
            'mothers_name', 'parent_mobile_number', 'language_id', 'dateformat_id',
            'timezone_id', 'currency_id', 'medium_of_exam', 'favorite_topics',
            'user_classifications_id', 'community_id', 'd_a_category_id', 'religion_id', 'special_category_id',
            'facebook', 'twitter', 'linkedin', 'payout_email', 'payout_icon', 'payout',
            'image' // Add image field
        ])->with(['education', 'skills'])->findOrFail($id);

        $proficiencyLevels = ['Beginner', 'Intermediate', 'Advanced', 'Expert', 'Master'];

        // Fetch only required columns for dropdown options
        $countries = Country::select('id', 'name')->get();

        // Only fetch states if country is selected, otherwise return empty collection
        $states = $user->country_id
            ? State::select('id', 'name')->where('country_id', $user->country_id)->get()
            : collect();

        // Only fetch cities if state is selected, otherwise return empty collection
        $cities = $user->state_id
            ? City::select('id', 'name')->where('state_id', $user->state_id)->get()
            : collect();

        $languages = Language::select('id', 'name')->get();
        $dateFormats = DateFormat::select('id', 'format')->get();
        $timeZones = TimeZone::select('id', 'name')->get();
        $currencies = Currency::select('id', 'name', 'symbol')->get();
        $userClassifications = UserClassification::select('id', 'name')->get();
        $communities = Community::select('id', 'name')->get();
        $daCategories = DACategory::select('id', 'name')->get();
        $religions = Religion::select('id', 'name')->get();
        $specialCategories = SpecialCategory::select('id', 'name')->get();

        return view('admin.users.details', compact(
            'user', 'proficiencyLevels', 'countries', 'states', 'cities',
            'languages', 'dateFormats', 'timeZones', 'currencies',
            'userClassifications', 'communities', 'daCategories',
            'religions', 'specialCategories'
        ));
    }

    /**
     * Update user's detailed information
     */
    public function updateDetails(Request $request, $id)
    {
        $user = User::select(['id', 'email', 'image'])->findOrFail($id);

        $validated = $request->validate([
            // Personal Details
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
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

        // Handle boolean fields explicitly if needed
        $validated['is_differently_abled'] = $request->has('is_differently_abled');

        // Handle image deletion if requested
        if ($request->has('delete_current_image') && $request->input('delete_current_image') == 1) {
            // If there's an existing image, delete it from storage
            if (!empty($user->image) && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $validated['image'] = null;
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Since client-side JavaScript already compresses and converts to WebP,
            // we just need to store it appropriately
            $imagePath = $image->storeAs(
                'profile-pictures/users/' . $user->id . '/' . date('Y/m/d'),
                time() . '_' . uniqid() . '.webp',
                'public'
            );

            // If we're uploading a new image and there was an old one, delete the old one
            if (!empty($user->image) && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $validated['image'] = $imagePath;
        } elseif (!$request->has('delete_current_image')) {
            // Only preserve the old image if user didn't request deletion and isn't uploading new one
            unset($validated['image']); // Don't update the image field if no new image and not deleting
        }

        $user->update($validated);

        return back()->with('success', 'User details updated successfully.');
    }

    /**
     * Add education for user
     */
    public function addEducation(Request $request, $id)
    {
        // Just verify the user exists without loading all data
        User::select('id')->findOrFail($id);

        $validated = $request->validate([
            'qualification' => ['required', 'string', 'max:255'],
            'year_of_passing' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'medium' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($id);
        $user->education()->create($validated);

        return back()->with('success', 'Education added successfully.');
    }

    /**
     * Update education for user
     */
    public function updateEducation(Request $request, $id, $educationId)
    {
        // Verify that the education record belongs to this user
        $education = UserEducation::where('user_id', $id)->findOrFail($educationId);

        $validated = $request->validate([
            'qualification' => ['required', 'string', 'max:255'],
            'year_of_passing' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'medium' => ['required', 'string', 'max:255'],
        ]);

        $education->update($validated);

        return back()->with('success', 'Education updated successfully.');
    }

    /**
     * Delete education for user
     */
    public function deleteEducation($id, $educationId)
    {
        $education = UserEducation::where('user_id', $id)->findOrFail($educationId);
        $education->delete();

        return back()->with('success', 'Education deleted successfully.');
    }

    /**
     * Add skill for user
     */
    public function addSkill(Request $request, $id)
    {
        // Just verify the user exists without loading all data
        User::select('id')->findOrFail($id);

        $validated = $request->validate([
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', 'in:Beginner,Intermediate,Advanced,Expert,Master'],
            'description' => ['nullable', 'string'],
        ]);

        $user = User::find($id);
        $user->skills()->create($validated);

        return back()->with('success', 'Skill added successfully.');
    }

    /**
     * Update skill for user
     */
    public function updateSkill(Request $request, $id, $skillId)
    {
        // Verify that the skill record belongs to this user
        $skill = UserSkill::where('user_id', $id)->findOrFail($skillId);

        $validated = $request->validate([
            'skill_name' => ['required', 'string', 'max:255'],
            'proficiency_level' => ['required', 'in:Beginner,Intermediate,Advanced,Expert,Master'],
            'description' => ['nullable', 'string'],
        ]);

        $skill->update($validated);

        return back()->with('success', 'Skill updated successfully.');
    }

    /**
     * Delete skill for user
     */
    public function deleteSkill($id, $skillId)
    {
        $skill = UserSkill::where('user_id', $id)->findOrFail($skillId);
        $skill->delete();

        return back()->with('success', 'Skill deleted successfully.');
    }
}
