<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model
 * Represents a user account in the application.
 * Users can be either job seekers or company employers.
 * Handles authentication, profile information, and relationships to posts, applications, and companies.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * 
     * - image: User's profile picture/avatar
     * - name: User's first name
     * - lastname: User's last name
     * - email: User's email address (unique)
     * - city_id: The ID of the city where the user is located
     * - address: User's physical address
     * - password: Hashed password for authentication
     * - birthday: User's date of birth
     * - phone: User's contact phone number
     * - gender: User's gender
     * - role: User's role in the system (e.g., job seeker, employer)
     * - google_id: Google OAuth ID for social login
     * - email_verified_at: Timestamp when email was verified
     *
     * @var list<string>
     */
    protected $fillable = [
        'image',
        'name',
        'lastname',
        'email',
        'city_id',
        'address',
        'password',
        'birthday',
        'phone',
        'gender',
        'role',
        'google_id',
        'email_verified_at',
    ];

    /**
     * Get all job posts created by this user (company).
     * Defines a one-to-many relationship.
     * A User (company) has many Posts (job listings).
     * Uses 'user_id' as the foreign key in the posts table.
     */
    public function jobs(){
        return $this->hasMany(Post::class, 'user_id');
    }
    /**
     * Get the company account associated with this user.
     * Defines a one-to-one relationship.
     * A User has at most one Company (if they are an employer).
     * Uses 'user_id' as the foreign key in the companies table.
     */
    public function company(){
        return $this->hasOne(Company::class, 'user_id');
    }
    /**
     * Get the city where this user is located.
     * Defines a many-to-one inverse relationship.
     * A User belongs to a City (geographical location).
     * Uses 'city_id' as the foreign key.
     */
    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }
    /**
     * Get all job applications submitted by this user.
     * Defines a one-to-many relationship.
     * A User has many Applications (job applications they submitted).
     * Uses 'user_id' as the foreign key in the applications table.
     */
    public function applications(){
        return $this->hasMany(Application::class, 'user_id');
    }
    /**
     * Get the CV (resume) file associated with this user.
     * Defines a one-to-one relationship.
     * A User has at most one CV file.
     * Uses 'user_id' as the foreign key in the cvs table.
     */
    public function cv(){
        return $this->hasOne(Cv::class, 'user_id');
    }
    /**
     * Get all saved/bookmarked job posts by this user.
     * Defines a one-to-many relationship.
     * A User has many saved Posts (job postings they bookmarked).
     * Uses 'user_id' as the foreign key in the saved_posts table.
     */
    public function savedPosts(){
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     * These sensitive fields are excluded when the model is converted to JSON.
     * 
     * - password: Never expose the password hash
     * - remember_token: Laravel's "remember me" token
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * Defines how model attributes should be cast to specific types.
     * 
     * - email_verified_at: Cast to datetime object
     * - password: Automatically hash password when set
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
