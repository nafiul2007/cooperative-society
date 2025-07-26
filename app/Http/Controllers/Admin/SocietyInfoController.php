<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocietyInfo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SocietyInfoController extends Controller
{
    // Show form to create or edit society info
    public function edit()
    {
        // Assume only one record exists, fetch first or null
        $societyInfo = SocietyInfo::first();

        return view('society-info.edit', compact('societyInfo'));
    }

    // Store or update the info
    public function update(Request $request)
    {
        // Fetch existing record or null
        $societyInfo = SocietyInfo::first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('society_infos', 'registration_no')->ignore($societyInfo?->id),
            ],
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('society_infos', 'email')->ignore($societyInfo?->id),
            ],
            'established_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
        if ($societyInfo) {
            $societyInfo->update($validated);
        } else {
            $societyInfo = SocietyInfo::create($validated);
        }

        return redirect()->route('society-info.show')->with('success', 'Society info updated successfully.');
    }

    public function show()
    {
        $societyInfo = SocietyInfo::first();

        if (!$societyInfo) {
            return redirect()->route('society-info.edit')->with('error', 'Society information not found.');
        }

        return view('society-info.show', compact('societyInfo'));
    }
}
