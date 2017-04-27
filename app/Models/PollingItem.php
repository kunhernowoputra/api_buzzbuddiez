<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 06/09/16
 * Time: 11:41
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PollingItem extends Model
{
    protected $fillable = ['polling_id', 'answer'];
    public $timestamps = false;

    /**
     * Relationship dengan Model Polling
     */

    public function polling()
    {
        return $this->belongsTo(Polling::class);
    }

}