<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 13/09/16
 * Time: 13:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostSticker extends Model
{
    protected $fillable = ['post_id', 'sticker_id'];
    public $timestamps = false;


    /**
     * Relasi Dengan Model Post
     * Relasi Dengan Model Sticker
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function sticker()
    {
        return $this->belongsTo(Sticker::class);
    }

}