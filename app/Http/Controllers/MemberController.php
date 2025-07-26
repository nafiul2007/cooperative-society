<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberCredentialsMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('user')->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'mobile_number' => 'required|string|max:20',
            'nid' => 'required|string|max:25',
            'date_of_birth' => 'required|date',
            'tin' => 'nullable|string',
            'business_share' => 'nullable|numeric',
        ]);
        // Generate a random password
        // $randomPassword = Str::random(10); 
        $randomPassword = "12345"; // for testing purposes, use a fixed password

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($randomPassword),
            ]);

            // Assign member role using Spatie
            $user->assignRole('member');

            // Create member
            $member = Member::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'mobile_number' => $validated['mobile_number'],
                'nid' => $validated['nid'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'tin' => $validated['tin'] ?? null,
                'business_share' => $validated['business_share'] !== null ? $validated['business_share'] : null,
                // 'created_by_user_id' => Auth::user()->id, // 👤 Capturing the creator
            ]);

            // Send credentials email
            Mail::to($user->email)->send(new MemberCredentialsMail($user, $randomPassword, $member->name));

            DB::commit();

            return redirect()->route('members.index')->with('success', 'Member created and credentials emailed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Failed to create member: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'nid' => 'required|string|max:25',
            'date_of_birth' => 'required|date',
            'tin' => 'nullable|string',
            'business_share' => 'nullable|numeric',
            // 'update_by_user_id' => Auth::user()->id, // 👤 Capturing the creator
        ]);

        DB::beginTransaction();

        try {

            // Update Member's details
            $member->update([
                'name' => $validated['name'],
                'mobile_number' => $validated['mobile_number'],
                'nid' => $validated['nid'] ?? $member->nid,
                'date_of_birth' => $validated['date_of_birth'] ?? $member->date_of_birth,
                'tin' => $validated['tin'] ?? $member->tin,
                'business_share' => $validated['business_share'] !== null ? $validated['business_share'] : null,
            ]);

            DB::commit();

            // return redirect()->route('members.index')->with('success', 'Member updated.');
            return redirect()->route('members.show', $member->id)
                 ->with('success', 'Member updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Failed to update member: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Member $member)
    {
        // $member->delete();
        // return redirect()->route('members.index')->with('success', 'Member deleted.');

        abort(403, 'Deleting members is not allowed.');
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function disable(Member $member)
    {
        $user = $member->user;

        Log::info("Disabling member: {$member->id}, User ID: {$user->id}");

        if ($user->isAdmin()) {
            return redirect()->route('members.index')->with('error', 'Admin user cannot be disabled.');
        }

        $user->update(['is_active' => false]);

        Log::info("Disabling member: {$member->id}, status: {$user->is_active}");
        return redirect()->route('members.index')->with('success', 'Member has been disabled.');
    }

    public function enable(Member $member)
    {
        $user = $member->user;

        $user->update(['is_active' => true]);

        return redirect()->route('members.index')->with('success', 'Member has been enabled.');
    }
}
