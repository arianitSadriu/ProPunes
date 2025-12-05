<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * SavedPosts Model
 * Represents a job posting that a user has bookmarked/saved.
 * This is a pivot/junction model that creates a many-to-many relationship between users and posts.
 * Allows users to save job postings for later viewing or reference.
 */
class SavedPosts extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * - post_id: The ID of the job post that was saved
     * - user_id: The ID of the user who saved the post
     */
    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Get the user who saved this post.
     * Defines a many-to-one inverse relationship.
     * A SavedPost belongs to a User (the person who saved it).
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
    /**
     * Get the job post that was saved.
     * Defines a many-to-one inverse relationship.
     * A SavedPost belongs to a Post (the job posting that was saved).
     */
    public function post(){
        return $this->belongsTo(Post::class);
    }
}
