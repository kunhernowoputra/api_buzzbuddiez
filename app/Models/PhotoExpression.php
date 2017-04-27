<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 07/11/16
 * Time: 16:05
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PhotoExpression extends Model
{
    protected $fillable = ['photo_id', 'expression_id'];

    public $timestamps = false;

}