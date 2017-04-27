<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 23/09/16
 * Time: 11:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $fillable = ['user_id', 'interest_id'];

    /**
     * Relasi Dengan Model User
     * Relasi Dengan Model Interest
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

}