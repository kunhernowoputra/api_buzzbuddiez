<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes;


    protected $fillable = [
        'email','username','password','api_token','register_type'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    protected $dates = ['delete_at'];


    /**
     * Set attribute password
     * Set attribute email
     * Set attribute api token
     */

    public function setPasswordAttribute($v)
    {
        $this->attributes['password'] = app()->make('hash')->make($v);
    }

    public function setEmailAttribute($v)
    {
        $this->attributes['email'] = strtolower($v);
    }

    public function setApiTokenAttribute()
    {
        $this->attributes['api_token'] = sha1(time());
    }

    /**
     * Relasi dengan Model UserProfile
     * Relasi dengan Model UserInterest
     * Relasi dengan Model Comment
     */

    public function user_profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function user_interest()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function comment()
    {
        return $this->hasOne(Comment::class);
    }

    public function notification_post()
    {
        return $this->hasMany(NotificationPost::class);
    }
    /**
     * fungsi static untuk register dari web
     * fungsi static untuk register dari sosmed
     */

    public static function register_web($request, $image)
    {
        $user = User::create($request->only('email','password','api_token', 'register_type')); // create to database user
        $profile = UserProfile::create(['fullname' => $request->input('fullname'), 'image' => $image]); // create to database user_profile
        $profile->user()->associate($user);
        $profile->save();

        foreach ($request->input('interest_id') as $v) // deklarasikan array interest_id, sehingga harus diforeach
        {
            UserInterest::create([ // create to database user_interest
                'user_id' => $user->id,
                'interest_id' => $v
            ]);
        }

        $interest = Interest::whereIn('id', $request->input('interest_id'))->get(); // select interest sesuai dengan user input

        $arr_data = [ // filter data array yang dibutuhin saja yang dikeluarin
            'api_token' => $user->api_token,
            'user_id' => array_get($profile,'user_id'),
            'fullname' => array_get($profile,'fullname'),
            'image' => array_get($profile,'image'),
            'email' => array_get($profile, 'user.email'),
            'interest' => $interest
        ];
        return $arr_data; // lalu kembalikan nilai array nya

    }

    public static function register_sosmed($request)
    {
        $user = User::create($request->only('email','api_token','register_type')); // create to database user

        $image = ($request->input('image')) ? $request->input('image') : null;
        $profile = UserProfile::create(['fullname' => $request->input('fullname'), 'image' => $image]); // create to database user_profile
        $profile->user()->associate($user);
        $profile->save();

        foreach ($request->input('interest_id') as $v) // deklarasikan array interest_id, sehingga harus diforeach
        {
            UserInterest::create([ // create to database user_interest
                'user_id' => $user->id,
                'interest_id' => $v
            ]);
        }

        $interest = Interest::whereIn('id', $request->input('interest_id'))->get(); // select interest sesuai dengan apa yang telah diinput user

        $arr_data = [ // filter data array yang dibutuhin saja yang dikeluarin
            'api_token' => $user->api_token,
            'user_id' => array_get($profile,'user_id'),
            'fullname' => array_get($profile,'fullname'),
            'image' => array_get($profile,'image'),
            'email' => array_get($profile, 'user.email'),
            'interest' => $interest
        ];
        return $arr_data; // lalu kembalikan nilai array nya
    }

    public static function update_user_profile($request, $id)
    {
        $update_user = [
            'username' => $request->input('username'),
            'fullname' => $request->input('fullname'),
            'about' => $request->input('about'),
            'gender' => $request->input('gender'),
            'image' => $request->input('image'),
        ];

        $user = User::find($id);
        $user->user_profile()->save($update_user);

        return $user;

    }


}
