<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use Illuminate\Http\Request;
use App\Models\Images;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'adventure_id' => 'required|integer|exists:adventures,id',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_files' => 'nullable|array',
            'image_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        Gate::authorize('manage', Adventure::findOrFail($validated['adventure_id']));
        $files = array_filter(array_merge(
            $request->file('image_files', []),
            [$request->file('image_path')]
        ));
        if ($files === []) {
            return back()->withErrors(['image_files' => 'Select at least one image.'])->withInput();
        }

        File::ensureDirectoryExists(public_path('images'));
        foreach ($files as $file) {
            $fileName = Str::uuid() . '.' . $file->extension();
            $file->move(public_path('images'), $fileName);
            Images::create([
                'adventure_id' => $validated['adventure_id'],
                'image_path' => $fileName,
            ]);
        }

        $redirect = $request->input('_form_type') === 'optional'
            ? redirect()->route('adventures.index', [
                'phase' => 'optional',
                'adventure_id' => $validated['adventure_id'],
            ])
            : redirect()->route('adventures.edit', $validated['adventure_id']);

        return $redirect->with('success', 'Adventure images uploaded successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Images $image)
    {
        Gate::authorize('manage', $image->adventure);
        $request->validate([
            'image_path' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $file = $request->file('image_path');
        File::ensureDirectoryExists(public_path('images'));
        $fileName = Str::uuid() . '.' . $file->extension();
        $file->move(public_path('images'), $fileName);
        $image->update(['image_path' => $fileName]);

        return redirect()->route('adventures.edit', $image->adventure_id)
            ->with('success', 'Adventure updated successfully');
    }

}
