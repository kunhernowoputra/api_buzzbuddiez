<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 28/10/16
 * Time: 10:57
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostHashtag extends Model
{
    protected $fillable = ['post_id', 'name'];
    public $timestamps = false;

}