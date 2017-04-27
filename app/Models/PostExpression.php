<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 24/10/16
 * Time: 14:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostExpression extends Model
{
    protected $fillable = ['user_id','post_id','expression_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }


}