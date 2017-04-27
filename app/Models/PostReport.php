<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 07/11/16
 * Time: 12:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    protected $fillable = ['user_id', 'post_id', 'message'];

}