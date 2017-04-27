<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 05/09/16
 * Time: 16:21
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['post_id','thumbnail','path'];
    public $timestamps = false;

    /**
     * Relasi dengan Model Post
     *
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}