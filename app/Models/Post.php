<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Location;

/**
 * Post Model
 * Represents a job posting created by a company.
 * Each post contains job details, requirements, and relationships to users, categories, and applications.
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * - user_id: The ID of the user (company) who created the post
     * - category_id: The ID of the job category this post belongs to
     * - company_id: The ID of the company posting the job
     * - tittle: The title/name of the job position
     * - description: Detailed description of the job and responsibilities
     * - type: The type of employment (e.g., full-time, part-time, contract)
     * - salary: The salary or salary range for the position
     * - nrWorkers: The number of workers/positions available
     * - location_id: The ID of the city/location where the job is based
     * - expiration_date: The date when the job posting expires
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'company_id',
        'tittle',
        'description',
        'type',
        'salary',
        'nrWorkers',
        'location_id',
        'expiration_date',
    ];

    /**
     * Get the user who created this job post.
     * Defines a many-to-one inverse relationship.
     * A Post belongs to a User (the company/employer).
     * Uses 'user_id' as the foreign key.
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Get the job category this post belongs to.
     * Defines a many-to-one inverse relationship.
     * A Post belongs to a Category (job classification).
     * Uses 'category_id' as the foreign key.
     */
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    /**
     * Get the city/location where this job is based.
     * Defines a many-to-one inverse relationship.
     * A Post belongs to a City (geographical location).
     * Uses 'location_id' as the foreign key.
     */
    public function location(){
        return $this->belongsTo(City::class, 'location_id');
    }
    /**
     * Get the company that posted this job.
     * Defines a many-to-one inverse relationship.
     * A Post belongs to a Company.
     * Uses 'company_id' as the foreign key.
     */
    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
    /**
     * Get all applications submitted for this job post.
     * Defines a one-to-many relationship.
     * A Post has many Applications (user applications to this job).
     * Uses 'post_id' as the foreign key in the applications table.
     */
    public function applications(){
        return $this->hasMany(Application::class, 'post_id');
    }
    /**
     * Get all saved/bookmarked instances of this post.
     * Defines a one-to-many relationship.
     * A Post has many SavedPosts (users who saved this posting).
     * Uses 'post_id' as the foreign key in the saved_posts table.
     */
    public function savedPosts(){
        return $this->hasMany(Post::class, 'post_id');
    }
}
