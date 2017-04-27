<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 14/09/16
 * Time: 12:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{

    public $fillable = ['name', 'path'];


    /**
     *
     */

    public function post()
    {
        return $this->hasMany(Post::class);
    }


}