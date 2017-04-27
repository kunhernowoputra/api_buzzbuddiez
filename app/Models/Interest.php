<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 01/09/16
 * Time: 17:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = [
        'name','colour', 'foto'
    ];

    /**
     * Relasi dengan Model Post
     * Relasi dengan Model UserInterest
     */

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function user_interest()
    {
        return $this->hasOne(UserInterest::class);
    }
}