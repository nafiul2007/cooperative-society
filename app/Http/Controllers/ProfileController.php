<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailChange;
use App\Mail\VerifyNewEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $member = $user->member; //can be null if profile is not completed
        return view('profile.edit', [
            'user' => $user,
            'member' => $member,
            'warning' => $member == null ? 'Please complete your profile to continue.' : null
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function updateProfile(Request $request)
    {
        // Step 1: Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'nid' => 'required|string|max:25',
            'date_of_birth' => 'required|date',
            'tin' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            // For AJAX requests, return JSON errors
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Fallback: regular request
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // Convert empty strings to null for optional fields
        foreach (['tin'] as $field) {
            if (!isset($validated[$field]) || trim($validated[$field]) === '') {
                $validated[$field] = null;
            }
        }
        // Step 2: Get user and update or create member
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            // $member->created_by_user_id = Auth::user()->id;
            $user->member()->create($validated);
        } else {
            // $member->updated_by_user_id = Auth::user()->id;
            $member->update($validated);
        }

        // Step 3: Return appropriate response
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.'
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateEmail(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update([
            'email' => $validated['email'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Email updated successfully.',
            ]);
        }

        return back()->with('status', 'email-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function requestEmailChange(Request $request)
    {
        $user = $request->user();
        $member = $user->member;
        // Get last sent time (timestamp) for this user (from DB or cache)
        $lastSent = cache()->get("email_change_sent_time_{$user->id}");

        $cooldownSeconds = 120;

        if ($lastSent && (time() - $lastSent) < $cooldownSeconds) {
            $secondsLeft = $cooldownSeconds - (time() - $lastSent);
            return response()->json([
                'message' => "Please wait before requesting again.",
                'cooldown' => $secondsLeft,
            ], 429); // HTTP 429 Too Many Requests
        }

        $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        $token = (string) Str::orderedUuid();
        $newEmail = $request->email;

        EmailChange::updateOrCreate(
            ['user_id' => $user->id],
            ['new_email' => $newEmail, 'token' => $token]
        );

        Mail::to($newEmail)->send(new VerifyNewEmail($token, $newEmail, $member->name));

        // Save last sent time to cache
        cache()->put("email_change_sent_time_{$user->id}", time(), $cooldownSeconds);

        return response()->json([
            'message' => 'Verification link has been sent to the new email address.',
            'cooldown' => $cooldownSeconds,
        ]);
    }

    public function verifyNewEmail($token)
    {
        $record = EmailChange::where('token', $token)->first();
        // Log::info("Auth user ID: " . Auth::user()->id);
        if (!$record || $record->user_id != Auth::user()->id) {
            return redirect()->route('profile.edit')->withErrors(['email' => 'Invalid or expired token.']);
        }

        $user = \App\Models\User::find($record->user_id);
        if (!$user) {
            return redirect()->route('profile.edit')->withErrors(['email' => 'User not found.']);
        }

        // Check if new email is already taken by another user
        $emailExists = \App\Models\User::where('email', $record->new_email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return redirect()->route('profile.edit')->withErrors(['email' => 'This email is already taken by another user.']);
        }

        $user->email = $record->new_email;
        $user->email_verified_at = now();
        $user->save();

        $record->delete();

        return redirect()->route('profile.edit')->with('status', 'email-updated');
    }
}
