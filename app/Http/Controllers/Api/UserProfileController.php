<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function show(Request $request)
    {
        $user = $request->user()->load(['education', 'skills']);

        return new UserResource($user);
    }

    /**
     * Update the authenticated user's profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            // Personal Information
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'max:12'],
            'whatsapp_number' => ['nullable', 'string', 'max:12'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'dob' => ['nullable', 'date'],
            'bio' => ['nullable', 'string'],
            'short_bio' => ['nullable', 'string', 'max:255'],

            // Address
            'locality' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'aadhaar_number' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],

            // Family Details
            'fathers_name' => ['nullable', 'string', 'max:100'],
            'mothers_name' => ['nullable', 'string', 'max:100'],
            'parent_mobile_number' => ['nullable', 'string', 'max:12'],

            // Social Profiles
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],

            // Preferences
            'language_id' => ['nullable', 'exists:languages,id'],
            'timezone_id' => ['nullable', 'exists:time_zones,id'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'dateformat_id' => ['nullable', 'exists:date_formats,id'],
            'dark_mode_enabled' => ['nullable', 'boolean'],
            'medium_of_exam' => ['nullable', 'string', 'max:255'],
            'favorite_topics' => ['nullable', 'array'],

            // Accessibility
            'is_differently_abled' => ['nullable', 'boolean'],
            'd_a_category_id' => ['nullable', 'exists:d_a_categories,id'],

            // Classifications
            'community_id' => ['nullable', 'exists:communities,id'],
            'religion_id' => ['nullable', 'exists:religions,id'],
            'user_classifications_id' => ['nullable', 'exists:user_classifications,id'],
            'special_category_id' => ['nullable', 'exists:special_categories,id'],

            // Image
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max
        ]);

        // Handle image upload with server-side processing
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $image = $request->file('image');

            // Process and convert to WebP using Intervention Image
            $processedImage = \Intervention\Image\Laravel\Facades\Image::read($image->getRealPath())
                ->scaleDown(width: 1920, height: 1920)
                ->toWebp(quality: 80);

            // Generate unique filename
            $filename = time().'_'.uniqid().'.webp';
            $directory = 'profile-pictures/users/'.$user->id.'/'.date('Y/m/d');

            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);

            // Save the processed image
            $imagePath = $directory.'/'.$filename;
            Storage::disk('public')->put($imagePath, (string) $processedImage);

            $validated['image'] = $imagePath;
        }

        // Update last_profile_update_at timestamp
        $validated['last_profile_update_at'] = now();

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user->fresh()->load(['education', 'skills'])),
        ]);
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['The provided password does not match our records.'],
                ],
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'last_password_change_at' => now(),
        ]);

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    /**
     * Upload/Update profile image only
     */
    public function uploadImage(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max
        ]);

        // Delete old image if exists
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $image = $request->file('image');

        // Process and convert to WebP using Intervention Image
        $processedImage = \Intervention\Image\Laravel\Facades\Image::read($image->getRealPath())
            ->scaleDown(width: 1920, height: 1920)
            ->toWebp(quality: 80);

        // Generate unique filename
        $filename = time().'_'.uniqid().'.webp';
        $directory = 'profile-pictures/users/'.$user->id.'/'.date('Y/m/d');

        // Ensure directory exists
        Storage::disk('public')->makeDirectory($directory);

        // Save the processed image
        $imagePath = $directory.'/'.$filename;
        Storage::disk('public')->put($imagePath, (string) $processedImage);

        // Update user image and timestamp
        $user->update([
            'image' => $imagePath,
            'last_profile_update_at' => now(),
        ]);

        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'data' => [
                'image' => asset('storage/'.$imagePath),
            ],
        ]);
    }

    /**
     * Delete the user's profile picture
     */
    public function deleteImage(Request $request)
    {
        $user = $request->user();

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
            $user->update([
                'image' => null,
                'last_profile_update_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Profile image deleted successfully',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    /**
     * Get user's education
     */
    public function getEducation(Request $request)
    {
        $education = $request->user()->education;

        return response()->json(['data' => $education]);
    }

    /**
     * Add/Update education
     */
    public function updateEducation(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'education' => ['required', 'array'],
            'education.*.id' => ['nullable', 'exists:user_education,id'],
            'education.*.qualification' => ['required', 'string', 'max:255'],
            'education.*.year_of_passing' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 10)],
            'education.*.medium' => ['required', 'string', 'max:255'],
        ]);

        // Delete existing education
        $user->education()->delete();

        // Add new education
        foreach ($validated['education'] as $edu) {
            $user->education()->create([
                'qualification' => $edu['qualification'],
                'year_of_passing' => $edu['year_of_passing'],
                'medium' => $edu['medium'],
            ]);
        }

        return response()->json([
            'message' => 'Education updated successfully',
            'data' => $user->fresh()->education,
        ]);
    }

    /**
     * Get user's skills
     */
    public function getSkills(Request $request)
    {
        $skills = $request->user()->skills;

        return response()->json(['data' => $skills]);
    }

    /**
     * Add/Update skills
     */
    public function updateSkills(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'skills' => ['required', 'array'],
            'skills.*.id' => ['nullable', 'exists:user_skills,id'],
            'skills.*.skill_name' => ['required', 'string', 'max:255'],
            'skills.*.proficiency_level' => ['required', 'in:Beginner,Intermediate,Advanced,Expert,Master'],
            'skills.*.description' => ['nullable', 'string'],
        ]);

        // Delete existing skills
        $user->skills()->delete();

        // Add new skills
        foreach ($validated['skills'] as $skill) {
            $user->skills()->create([
                'skill_name' => $skill['skill_name'],
                'proficiency_level' => $skill['proficiency_level'],
                'description' => $skill['description'] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Skills updated successfully',
            'data' => $user->fresh()->skills,
        ]);
    }

    /**
     * Request account deletion
     */
    public function requestAccountDeletion(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update([
            'delete_request_at' => now(),
            'delete_request_reason' => $validated['reason'] ?? null,
        ]);

        return response()->json([
            'message' => 'Account deletion request submitted successfully. Your account will be reviewed by our team.',
        ]);
    }
}
