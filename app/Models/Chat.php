<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 21/09/16
 * Time: 14:06
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_id', 'to_id', 'chat_room_id','message'];
    protected $hidden = ['id'];

}