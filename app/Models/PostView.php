<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 15/09/16
 * Time: 14:26
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    protected $fillable = ['post_id','user_id'];
    //protected $appends = ['user_id'];
    public $timestamps = false;

    /**
     * Relasi dengan Model Post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }



}