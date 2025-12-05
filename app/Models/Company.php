<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Company Model
 * Represents a company that posts job listings.
 * Each company has a user owner and can post multiple job postings.
 * Stores company profile information including contact details and branding.
 */
class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * - user_id: The ID of the user who owns/manages this company account
     * - name: The name of the company
     * - image: The company's logo or branding image
     * - description: A detailed description of the company
     * - address: The physical address of the company
     * - phone: The company's contact phone number
     * - website: The company's website URL
     * - email: The company's contact email address
     */
    protected $fillable = [
      'user_id',
      'name',
      'image',
      'description',
      'address',
      'phone',
      'website',
      'email',
    ];

    /**
     * Get all job posts created by this company.
     * Defines a one-to-many relationship.
     * A Company has many Posts (job postings) associated with it.
     * Uses 'company_id' as the foreign key in the posts table.
     */
    public function jobs(){
        return $this->hasMany(Post::class, 'company_id');
    }
    /**
     * Get the user who owns this company account.
     * Defines a many-to-one inverse relationship.
     * A Company belongs to a single User (the owner/manager).
     * Uses 'user_id' as the foreign key.
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
