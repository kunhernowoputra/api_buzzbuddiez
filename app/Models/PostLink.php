<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 03/11/16
 * Time: 16:34
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PostLink extends Model
{
    protected $fillable = ['post_id', 'url', 'title', 'description', 'link','embed_url'];
    public $timestamps = false;

}