<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * City Model
 * Represents a city/location in the application.
 * Cities are used to identify geographical locations for users and job postings.
 * Each city contains geographic coordinates (latitude and longitude).
 */
class City extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * - name: The name of the city
     * - latitude: The geographic latitude coordinate of the city
     * - longitude: The geographic longitude coordinate of the city
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * Get all users in this city.
     * Defines a one-to-many relationship.
     * A City has many Users associated with it.
     * Uses 'city_id' as the foreign key in the users table.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'city_id' );
    }
    /**
     * Get all job posts in this city/location.
     * Defines a one-to-many relationship.
     * A City has many Posts (job postings) associated with it.
     * Uses 'location_id' as the foreign key in the posts table.
     */
    public function posts(){
        return $this->hasMany(Post::class, 'location_id');
    }
}
