<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 07/11/16
 * Time: 9:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PollingAnswer extends Model
{

    protected $fillable = ['user_id', 'polling_item_id','post_id'];

}