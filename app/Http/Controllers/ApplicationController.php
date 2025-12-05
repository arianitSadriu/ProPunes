<?php

namespace App\Http\Controllers;

use App\Jobs\SendAcceptedMail;
use App\Jobs\SendAppliedMail;
use App\Jobs\SendNewApplicationMail;
use App\Jobs\SendRejectedMail;
use App\Mail\NewApplication;
use App\Models\Application;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ApplicationController
 * Handles job application operations including creating, deleting, accepting, and rejecting applications.
 * Manages the workflow of users applying for jobs and employers managing applications.
 */
class ApplicationController extends Controller
{
        /**
         * Store a new job application.
         * 
         * Validates that:
         * - The user is a job seeker (not an employer)
         * - The job post has available slots
         * - The user has not already applied for this job
         * - The user has uploaded a CV
         * 
         * Creates an application, decrements available worker slots, and dispatches email notifications.
         * 
         * @param Request $request Contains user_id and post_id
         * @return \Illuminate\Http\RedirectResponse Redirect with success or error message
         */
        public function store(Request $request){
        $user = auth()->user();
        if ($user->role === 'employer' ) {
            return redirect()->back()->with('error', 'You are registered as a employer');
        }
        $post = Post::findOrFail($request->post_id);
        if ($post->nrWorkers === 0) {
            return redirect()->back()->with('error', 'no free slots available' );
        }
        $exists = Application::where('user_id', $request->user_id)
            ->where('post_id', $request->post_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with(['error' => 'You have already applied for this job.']);
        }
        if (!$user->cv) {
            return redirect()->back()->with(['error' => 'You must upload a CV before applying.']);
        }
        $request->validate([
            'user_id'=>'required',
            'post_id'=>'required',
        ]);
        $application = Application::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'status' => 'pending',
        ]);
        $post->decrement('nrWorkers');
        SendNewApplicationMail::dispatch($application)->onQueue('queue');
        SendAppliedMail::dispatch($application)->onQueue('queue');
        return redirect(route('jobs.index'))->with('success', 'You have successfully applied for this job');
    }

    /**
     * Delete a job application.
     * 
     * Only allows the application owner to delete their own application.
     * Increments the available worker slots when application is deleted.
     * 
     * @param int $id The ID of the application to delete
     * @return \Illuminate\Http\RedirectResponse Redirect with success or error message
     */
    public function delete($id)
    {
        $application = Application::findOrFail($id);
        $owner = auth()->id();
        if ($owner !== $application->user_id) {
            return redirect()->back()->with('error', 'You are not allowed to delete this job');
        }
        $post = $application->post;
        $application->delete();
        $post->increment('nrWorkers');
        return redirect()->back()->with('success', 'Application has been deleted');
    }

    /**
     * Accept a job application.
     * 
     * Changes application status to 'accepted' if it's currently pending or rejected.
     * Sends acceptance email notification to the applicant.
     * 
     * @param int $id The ID of the application to accept
     * @return \Illuminate\Http\RedirectResponse Redirect with success message
     */
    public function accept($id){
        $application = Application::findOrFail($id);
        if ($application->status === 'pending' || $application->status === 'rejected') {
            $application->update(['status' => 'accepted']);
        }
        SendAcceptedMail::dispatch($application)->onQueue('queue');
        return redirect()->back()->with('success', 'Application accepted');
    }

    /**
     * Reject a job application.
     * 
     * Changes application status to 'rejected' if it's currently pending or accepted.
     * Sends rejection email notification to the applicant.
     * 
     * @param int $id The ID of the application to reject
     * @return \Illuminate\Http\RedirectResponse Redirect with success message
     */
    public function reject($id){
        $application = Application::findOrFail($id);
        if ($application->status === 'pending' || $application->status === 'accepted') {
            $application->update(['status' => 'rejected']);
        }
        SendRejectedMail::dispatch($application)->onQueue('queue');
        return redirect()->back()->with('success', 'Application rejected');
    }
}
