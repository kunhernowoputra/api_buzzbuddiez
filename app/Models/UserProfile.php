<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 01/09/16
 * Time: 10:42
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','fullname','birthday', 'about','gender','location','image'
    ];

    protected $hidden = [
        //'id','photo_id','created_at','updated_at'
    ];

    protected $dates = ['delete_at'];

    /**
     * Relasi Dengan Model User
     * Relasi Dengan Model Photo
     * Relasi Dengan Model Post
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'user_id', 'user_id');
    }

}