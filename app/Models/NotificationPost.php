<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 12/10/16
 * Time: 11:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class NotificationPost extends Model
{
    protected $fillable = ['user_id', 'notification_type_id', 'readed', 'post_id'];
}