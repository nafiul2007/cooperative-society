<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContributionFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContributionFileController extends Controller
{
    public function destroy(ContributionFile $attachment)
    {
        $userId = Auth::id();

        // Make sure the authenticated user owns the contribution and it's still pending
        if (
            $attachment->contribution->user_id !== $userId ||
            $attachment->contribution->status !== 'pending'
        ) {
            abort(403, 'Unauthorized');
        }

        // Delete the physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete DB record
        $attachment->delete();

        return back()->with('success', 'Attachment removed.');
    }
}
