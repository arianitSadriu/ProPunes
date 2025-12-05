<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * CvController
 * Handles CV (resume) file management operations.
 * Manages uploading, updating, and deleting CV files for users.
 */
class CvController extends Controller
{
    /**
     * Store a new CV file for a user.
     * 
     * Validates the uploaded file and stores it in the cv_files directory.
     * Generates a unique filename with timestamp to avoid conflicts.
     * Creates a CV record in the database linked to the user.
     * 
     * @param Request $request Contains the CV file and user_id
     * @return \Illuminate\Http\RedirectResponse Redirect back with success or error message
     */
    public function storeCv(Request $request){
        $request->validate([
            'file' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('cv_files', $fileName, 'public');
        } else {
            return redirect()->back()->with('error', 'No valid file uploaded.');
        }
        Cv::create([
            'file' => $path,
            'user_id' => $request->user_id,
        ]);
        return redirect()->back()->with('success', 'File uploaded.');
    }

    /**
     * Update an existing CV file for the authenticated user.
     * 
     * Validates and replaces the user's current CV with a new PDF file.
     * Deletes the old CV file from storage before saving the new one.
     * Only allows users to update their own CV files.
     * 
     * @param Request $request Contains the new CV file and user_id
     * @return \Illuminate\Http\RedirectResponse Redirect with success or error message
     */
    public function updateCV(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);
        $user = auth()->id();
        $cv = CV::where('user_id', $user)->first();
        if ($user !== $cv->user_id) {
            return redirect()->back()->with('error', 'You cannot change this file.');
        }
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'No File Uploaded.');
        }
        if ($cv->file) {
            Storage::delete($cv->file);
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $filePath = $file->storeAs('cv_files', $fileName, 'public');
        } else {
            return redirect()->back()->with('error', 'No valid file uploaded.');
        }
        $cv->update([
            'file' => $filePath,
        ]);

        return back()->with('success', 'Your CV has been updated successfully.');
    }

    /**
     * Delete a CV file.
     * 
     * Removes the CV record from the database.
     * Only allows the CV owner to delete their own CV.
     * Performs authorization check before deletion.
     * 
     * @param int $id The ID of the CV to delete
     * @return \Illuminate\Http\RedirectResponse Redirect with success or error message
     */
     public function deleteCV($id){
        $cv = CV::findOrFail($id);
        if (auth()->id() !== $cv->user_id) {
            return redirect()->back()->with('error', 'You cannot delete this file.');
        }
        $cv->delete();
        return back()->with('success', 'Your CV has been deleted successfully.');
    }
}

