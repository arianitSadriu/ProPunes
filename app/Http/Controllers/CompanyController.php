<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/**
 * CompanyController
 * Handles company profile management operations.
 * Manages creating, updating, and deleting company profiles along with company image uploads.
 */
class CompanyController extends Controller
{
     /**
      * Show the form to create a new company.
      * 
      * Renders the company creation form with the authenticated user's ID.
      * 
      * @return \Inertia\Response Renders the CreateCompany form
      */
     public function create(){

        return Inertia::render('Jobs/CreateCompany', [
            'authUser' => auth()->id(),
        ]);
    }

    /**
     * Store a new company in the database.
     * 
     * Validates company information, handles image file upload, and creates a company record.
     * Only allows employers to create companies (blocks employees).
     * 
     * @param Request $request Contains company details and image file
     * @return \Illuminate\Http\RedirectResponse Redirect to profile page with success or error message
     */
    public function store(Request $request){
        $user = auth()->user();
        if ($user->role === 'employee'){
            return redirect()->back()->with('error', 'You are registered as an Employee');
        }
        $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'image' => 'required|max:10420',
            'description' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'website' => 'required',
            'email' => 'required',
        ]);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('company', 'public');
        } else {
            return redirect()->back()->with('error', 'No valid file uploaded.');
        }
        $company = Company::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'image' => $path,
            'description' => $request->description,
            'phone' => $request->phone,
            'address' => $request->address,
            'website' => $request->website,
            'email' => $request->email,
        ]);

        return redirect(route('profilePage'))->with('success', 'Company created.');
    }

    /**
     * Update company information.
     * 
     * Updates company details for the authenticated user's company.
     * Allows updating name, description, contact details, and website information.
     * 
     * @param Request $request Contains updated company information
     * @param int $id The ID of the company to update
     * @return \Illuminate\Http\RedirectResponse Redirect to profile page with success message
     */
    public function update(Request $request, $id){
        $company = Company::where('user_id', auth()->id())->find($id);
        $request->validate([
            'user_id' => 'required',
            'name' => 'sometimes|required',
            'description' => 'required',
            'phone' => 'sometimes|required',
            'address' => 'required',
            'website' => 'required',
            'email' => 'required',
        ]);
        $company->update($request->except('user_id'));
        return redirect(route('profilePage'))->with('success', 'Company information updated.');
    }

    /**
     * Update the company's image/logo.
     * 
     * Handles image file upload validation, storage, and old image deletion.
     * Validates that the uploaded file is a valid image with appropriate mime types.
     * 
     * @param Request $request Contains the image file to upload
     * @param int $id The ID of the company to update
     * @return \Illuminate\Http\RedirectResponse Redirect to profile page with success or error message
     */
    public function updateImage(Request $request, $id)
    {
    // Validate that an image file is provided and meets the requirements
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
    ]);

    // Retrieve the company by ID
    $company = Company::findOrFail($id);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $imagePath = $file->storeAs('company', $imageName, 'public');
        if ($company->image && Storage::disk('public')->exists($company->image)) {
            Storage::disk('public')->delete($company->image);
        }
        $company->update(['image' => $imagePath]);

        return Redirect::route('ProfilePage')->with('success', 'Company image updated.');
    }

    return back()->with('error', 'No image was uploaded!');

}

/**
 * Delete a company.
 * 
 * Only allows the company owner to delete their own company.
 * Performs authorization check before deletion.
 * 
 * @param int $id The ID of the company to delete
 * @return \Illuminate\Http\RedirectResponse Redirect back with success or error message
 */
public function deleteCompany($id)
{
    $company = Company::where('user_id', auth()->id())->find($id);
    if (auth()->id() !== $company->user_id) {
        return redirect()->back()->with('error', 'You are not allowed to delete this company.');
    }
    $company->delete();
    return redirect()->back()->with('success', 'Company deleted.');
}
}
