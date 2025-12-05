<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CV Model
 * Represents a user's Curriculum Vitae (resume/CV) file.
 * Each user can have a CV file uploaded to their profile for job applications.
 */
class CV extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * By default, Laravel uses the plural snake_case model name.
     * This explicitly specifies the table name as 'cvs'.
     */
    protected $table = 'cvs';
    /**
     * The attributes that are mass assignable.
     * These fields can be populated via mass assignment operations like create() or update().
     * - file: The path or filename of the uploaded CV file
     * - user_id: The ID of the user who owns this CV
     */
    protected $fillable = [
        'file',
        'user_id',
    ];

    /**
     * Get the user who owns this CV.
     * Defines a many-to-one inverse relationship.
     * A CV belongs to a single User.
     * Uses 'user_id' as the foreign key.
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
