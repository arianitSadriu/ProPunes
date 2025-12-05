<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Category Model
 * Represents a job category that organizes job postings.
 * Categories are used to classify different types of jobs (e.g., IT, Healthcare, Finance, etc.).
 * Each category can have multiple job posts associated with it.
 */
class Category extends Model
{
   /**
    * The attributes that are mass assignable.
    * These fields can be populated via mass assignment operations like create() or update().
    * - name: The name of the job category (e.g., "Software Development", "Sales", etc.)
    */
   protected $fillable = [
        'name',
    ];

    /**
     * Get all job posts in this category.
     * Defines a one-to-many relationship.
     * A Category has many Posts (job postings) associated with it.
     * Uses 'category_id' as the foreign key in the posts table.
     */
    public function jobs(){
        return $this->hasMany(Post::class, 'category_id');
    }
}