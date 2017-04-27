<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 06/09/16
 * Time: 11:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
    protected $fillable = ['question','end','post_id'];
    public $timestamps = false;


    /**
     * Relationship dengan model Post
     * Relationship dengan model PollingItem
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function polling_item()
    {
        return $this->hasMany(PollingItem::class);
    }
}