<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 05/10/16
 * Time: 10:53
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CommentTag extends Model
{
    protected $fillable = ['comment_id', 'user_id'];
    public $timestamps = false;

}