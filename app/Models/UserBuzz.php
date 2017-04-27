<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 03/11/16
 * Time: 17:29
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserBuzz extends Model
{
    protected $table = 'user_buzzes';
    protected $fillable = ['user_id', 'to_id'];

}