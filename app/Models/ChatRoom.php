<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 21/09/16
 * Time: 13:47
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['user_id','to_id'];
    public $timestamps = false;

}