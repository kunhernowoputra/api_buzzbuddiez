<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 31/08/16
 * Time: 16:08
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','location_id','interest_id','content','post_type'
    ];

    protected $hidden = [
        'interest_id', 'created_at','updated_at'
    ];

    protected $dates = ['delete_at'];


    /**
     * Relasi Dengan Model Interest
     * Relasi Dengan Model Photo
     * Relasi Dengan Model Location
     * Relasi Dengan Model User
     * Relasi Dengan Model Video
     * Relasi Dengan Model Polling
     * Relasi Dengan Model PostView
     * Relasi Dengan Model Comment
     * Relasi Dengan Model UserTag
     * Relasi Dengan Model UserProfile
     * Relasi Dengan Model PostSticker
     */

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    public function photo()
    {
        return $this->hasMany(Photo::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(UserProfiler::class);
    }

    public function video()
    {
        return $this->hasOne(Video::class);
    }

    public function polling()
    {
        return $this->hasOne(Polling::class);
    }

    public function post_view()
    {
        return $this->hasMany(PostView::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function user_tag()
    {
        return $this->hasMany(UserTag::class);
    }

    public function user_profile()
    {
        return $this->hasMany('App\Models\UserProfile', 'user_id', 'user_id');
    }

    public function post_sticker()
    {
        return $this->hasMany(PostSticker::class);
    }

    public function post_expression()
    {
        return $this->hasMany(PostExpression::class)->selectRaw('post_id, expression_id, count(*) as total_expression')->groupBy('post_id');
    }

    /**
     * Query Post
     * function harus berdasarkan post type
     */

    public static function post_type($request)
    {
        $post_type = $request->input('post_type');

        // create post
        $data = Post::create($request->all());

        // count post view
        PostView::create([
            'post_id' => $data->id,
            'user_id' => $data->user_id
        ]);

        // User Device insert
        if(!empty($request->input('device_token'))) {
            DB::table('user_devices')->insert([
                'user_id' => $data->user_id,
                'device_token' => $request->input('device_token'),
                'device_id' => $request->input('device_id')
            ]);
        }


        // filter hashtag yang ada di content
        $hashtag_string = $request->input('content');
        preg_match_all('/#([^\s]+)/', $hashtag_string, $matches);

        if($matches) {
            foreach ($matches as $tags) {
                $tag = $tags;
            }

            foreach ($tag  as $d)
            {
                \App\Models\PostHashtag::create([
                    'post_id' => $data->id,
                    'name' => $d
                ]);
            }
        }

        if ($sticker = $request->input('sticker_id'))
        {
            foreach ($sticker as $v)
            {
                PostSticker::create([
                    'post_id' => $data->id,
                    'sticker_id' => $v
                ]);
            }
        }

        if ($location = $request->input('location'))
        {
            Location::create([
                'post_id' => $data->id,
                'lat' => $location['lat'],
                'long' => $location['long'],
                'address' => $location['address'],
            ]);
        }

        if ($user_tag = $request->input('user_tag'))
        {
            foreach ($user_tag as $v)
            {
                UserTag::create([
                    'post_id' => $data->id,
                    'user_id' => $v
                ]);
            }
        }

        if ($photo = $request->file('image') and $post_type == 1)
        {
            foreach ($photo as $v)
            {
                $name = str_replace(' ','_', $v->getClientOriginalName());
                $v->move(base_path() . '/public/images/posts/', sha1(time()) . $name);
                Photo::create([
                    'post_id' => $data->id,
                    'thumbnail' => sha1(time()) . $name,
                ]);
            }
        }

        if ($video = $request->file('video_path') and $post_type == 2)
        {

            $name = str_replace(' ','_', $video->getClientOriginalName());
            $video->move(base_path() . '/public/videos/posts/', sha1(time()) . $name);

            Video::create([
                'post_id' => $data->id,
                'thumbnail' => sha1(time()) . $name,
                'path' => sha1(time()) . $name,
            ]);
        }

        if ($question = $request->input('question') and $end = $request->input('end') and $answer = $request->input('answer') and $post_type == 3)
        {
            $polling = Polling::create([
                'post_id' => $data->id,
                'question' => $question,
                'end' => $end,
            ]);

            foreach ($answer as $v)
            {
                PollingItem::create([
                    'polling_id' => $polling->id,
                    'answer' => $v
                ]);
            }
        }

        return $data;

    }

}