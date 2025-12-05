<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * AdminController
 * Handles administrative actions and dashboard functionality.
 * Provides methods for viewing dashboard statistics and managing users and posts.
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard with statistics and data.
     * 
     * Retrieves and displays:
     * - Count of employee users
     * - Count of employer users
     * - Total number of job posts
     * - Total number of applications
     * - List of all users with their associated cities
     * - List of all job posts with user and company information
     * - Count of accepted applications
     * - Count of rejected applications
     * 
     * @return \Inertia\Response Renders the Dashboard view with collected statistics
     */
    public function index()
    {
        $EmployeeCount = User::where('role' , 'employee')->count();
        $EmployerCount = User::where('role' , 'employer')->count();
        $PostCount = Post::all()->count();
        $ApplicationCount = Application::all()->count();
        $Users = User::with('city')->get();
        $Posts = Post::with('user','company')->get();
        $accepted = Application::where('status' , 'accepted')->count();
        $rejected = Application::where('status' , 'rejected')->count();

        return Inertia::render('Dashboard', [
            'EmployeeCount' => $EmployeeCount,
            'EmployerCount' => $EmployerCount,
            'PostCount' => $PostCount,
            'ApplicationCount' => $ApplicationCount,
            'Users' => $Users,
            'Posts' => $Posts,
            'accepted' => $accepted,
            'rejected' => $rejected,
        ]);
    }

    /**
     * Delete a user from the system.
     * 
     * Removes the specified user account and all associated data.
     * Redirects back with a success message.
     * 
     * @param User $user The user to be deleted (automatically resolved through route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirects back with success message
     */
    public function deleteUser(User $user){

        $user->delete();
        return Redirect::back()->with('success', 'User has been deleted');

    }

    /**
     * Delete a job post from the system.
     * 
     * Removes the specified job posting and all associated data.
     * Redirects back with a success message.
     * 
     * @param Post $post The post to be deleted (automatically resolved through route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirects back with success message
     */
    public function deletePost(Post $post){
        $post->delete();
        return Redirect::back()->with('success', 'Post has been deleted');
    }

}
