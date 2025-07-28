<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContributionFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContributionFileController extends Controller
{
    public function destroy(ContributionFile $file)
    {
        $userId = Auth::id();

        // Make sure the authenticated user owns the contribution and it's still pending
        if (
            $file->contribution->user_id !== $userId ||
            $file->contribution->status !== 'pending'
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Delete the physical file
            Storage::delete($file->file_path);
            $file->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'File deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function download($contributionId, $filename)
    {
        $file = ContributionFile::where('contribution_id', $contributionId)
            ->where('file_path', 'like', "%/{$filename}")
            ->firstOrFail();

        if (!Storage::exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($file->file_path, $file->original_name);
    }
}
