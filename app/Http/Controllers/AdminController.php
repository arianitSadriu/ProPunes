<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Post;
use App\Models\User;

class AdminController extends Controller
{
    
    public function deleteUser(User $user){

        $user->delete();
        return Redirect::back()->with('success', 'User has been deleted');

    }

    public function deletePost(Post $post){
        $post->delete();
        return Redirect::back()->with('success', 'Post has been deleted');
    }

}
