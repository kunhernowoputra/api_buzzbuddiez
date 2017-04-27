<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 29/09/16
 * Time: 14:22
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = ['post_id', 'user_id', 'sticker_id', 'comment', 'image'];
    protected $dates = ['delete_at'];

    /** Insert Comment */
    public static function insert_comment($request, $image)
    {
        $check_tag_friend = $request->input('tag_user_id');

        if(is_array($check_tag_friend))
        {
            $data = Comment::create([
                'post_id' => $request->input('post_id'),
                'user_id' => $request->input('user_id'),
                'sticker_id' => $request->input('sticker_id'),
                'comment' => $request->input('comment'),
                'image' => $image
            ]);
            foreach ($check_tag_friend as $v)
            {
                CommentTag::create([
                    'comment_id' => $data->id,
                    'user_id' => $v
                ]);
            }
            return $data;
        }
        else
        {
            $data = Comment::create(array_add(array_except($request->all(), ['image']), 'image', $image));
            return $data;
        }
    }

    /**
     * Relasi dengan Model Post
     * Relasi dengan Model User
     * Relasi dengan Model Sticker
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sticker()
    {
        return $this->belongsTo(Sticker::class);
    }

}