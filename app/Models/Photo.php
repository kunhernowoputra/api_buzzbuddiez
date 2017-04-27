<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 01/09/16
 * Time: 13:16
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['post_id', 'thumbnail','original','description'];
    public $timestamps = false;

    /**
     * Relasi dengan Model Post
     * Relasi dengan Model UserProfile
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