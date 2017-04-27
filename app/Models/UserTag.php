<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 04/10/16
 * Time: 11:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    protected $fillable = ['post_id', 'user_id'];
    public $timestamps = false;

    /**
     * Relationship Model Post
     * Relationship Model UserProfile
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class);
    }

}