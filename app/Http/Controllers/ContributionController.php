<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ContributionFile;

class ContributionController extends Controller
{
    public function index()
    {
        // $contributions = Contribution::with('attachments')->where('user_id', Auth::id())->latest()->get();
        $contributions = Contribution::with('attachments')->latest()->get();
        return view('contributions.index', compact('contributions'));
    }

    public function create()
    {
        return view('contributions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'contribution_date' => 'required|date',
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $contribution = Contribution::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'contribution_date' => $request->contribution_date,
            'status' => 'pending',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                ContributionFile::create([
                    'contribution_id' => $contribution->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('contributions.index')->with('success', 'Contribution created successfully.');
    }

    public function update(Request $request, Contribution $contribution)
    {
        // $this->authorize('update', $contribution); // Only owner or admin can update
        $userId = Auth::id();
        // Make sure the authenticated user owns the contribution and it's still pending
        if ($contribution->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        if ($contribution->status !== 'pending') {
            return back()->with('error', 'Only pending contributions can be updated.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'contribution_date' => 'required|date',
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        // ✅ Update contribution fields
        $contribution->update([
            'amount' => $request->amount,
            'contribution_date' => $request->contribution_date,
        ]);

        // ✅ Handle new file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                ContributionFile::create([
                    'contribution_id' => $contribution->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('contributions.index')->with('success', 'Contribution updated successfully.');
    }


    public function edit(Contribution $contribution)
    {
        // $this->authorize('update', $contribution); // Only owner or admin can edit
        $userId = Auth::id();
        // Make sure the authenticated user owns the contribution and it's still pending
        if ($contribution->user_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        if ($contribution->status !== 'pending') {
            return back()->with('error', 'Only pending contributions can be edited.');
        }

        // Load attachments for the view
        $contribution->load('attachments');

        return view('contributions.edit', compact('contribution'));
    }

    public function show(Contribution $contribution)
    {
        return view('contributions.show', [
            'contribution' => $contribution->load('attachments'),
        ]);
    }

    // 🔐 Admin only
    public function approve(Contribution $contribution)
    {
        if ($contribution->status !== 'pending') {
            return back()->with('error', 'Only pending contributions can be approved.');
        }
        $contribution->update(['status' => 'approved']);
        return back()->with('success', 'Contribution approved.');
    }

    public function reject(Contribution $contribution)
    {
        if ($contribution->status !== 'pending') {
            return back()->with('error', 'Only pending contributions can be rejected.');
        }
        $contribution->update(['status' => 'rejected']);
        return back()->with('error', 'Contribution rejected.');
    }
}
