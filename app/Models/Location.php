<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 31/08/16
 * Time: 16:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'post_id','lat','long','address'
    ];
    protected $hidden = [
        //'id','created_at','updated_at'
    ];

    /**
     * Relasi dengan Model Post
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}