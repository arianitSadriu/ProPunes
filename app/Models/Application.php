<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Application Model
 * 
 * Represents a job application submitted by a user to a job posting.
 * Each application tracks the relationship between a user, a job post, and the application status.
 * This model serves as a pivot/junction table concept with additional status tracking.
 */
class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * 
     * - user_id: References the User who submitted the application
     * - post_id: References the Post (job posting) the user applied to
     * - status: Tracks the current status of the application (e.g., pending, accepted, rejected)
     */
    protected $fillable = [
      'user_id',
      'post_id',
      'status',
    ];

    /**
     * Get the user who submitted this application.
     * 
     * Defines a many-to-one inverse relationship.
     * An Application belongs to a single User.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job post this application was submitted for.
     * 
     * Defines a many-to-one inverse relationship.
     * An Application belongs to a single Post (job posting).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(){
        return $this->belongsTo(Post::class);
    }
}
