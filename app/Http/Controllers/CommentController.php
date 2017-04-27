<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 29/09/16
 * Time: 14:16
 */

namespace App\Http\Controllers;


use App\Models\Comment;
use App\Models\Post;
use App\Models\Sticker;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller
{
    public $message;

    /** Construct Function */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['get_comment']]);
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    /** Get Function */
    public function get_comment($post_id, $offset)
    {
        $query = Comment::wherePostId($post_id)->take(5)->skip($offset)->get();

        if(count($query) > 0)
        {
            $data = [];
            foreach ($query as $comment) {
                $data[] = [
                    'post_id' => $comment->post_id,
                    'user_id' => UserProfile::whereUserId($comment->user_id)->get(),
                    'comment' => $comment->comment,
                    'sticker_id' => Sticker::whereId($comment->sticker_id)->first(),
                    'image' => $comment->image,
                    'created_at' => date('Y-m-d', $comment->created_at->timestamp),
                    'updated_at' => date('Y-m-d', $comment->updated_at->timestamp),
                ];
            }
            return response()->json(['message' => $this->message['message_success'], 'data'=>$data]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'data'=> 'not found']);
        }



    }

    /** Post Function */
    public function post_comment(Request $request)
    {
        /**
         * rules untuk post comment harus ada [post_id, user_id]
         * yang tidak dimandatory [sticker_id, tag_user_id]
         */

        $rules = [
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'tag_user_id' => 'array',
            'image' => 'image'
        ];

        $this->validate($request, $rules);

        $image = $this->upload_image($request->file('image')); // function untuk upload image
        $query = Comment::insert_comment($request, $image); // insert data comment

        $data = [
            'user_id' => UserProfile::whereUserId($query->user_id)->first(),
            'post_id' => $query->post_id,
            'comment_id' => Comment::whereId($query->id)->first()
        ];


        /** Notification */
        $data_notif = [
            'user_id' => $request->input('user_id'),
            'notification_type_id' => 1,
            'readed' => 0
        ];
        $notif = new NotificationController();
        $notif->post_notification_comment($data_notif);

        return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
    }

    /** Delete Function */
    public function delete_comment($id)
    {
        $user = Comment::find($id);
        if (!is_null($user)) {
            $user->delete();
            return response()->json(['message' => $this->message['message_success']]);
        }
        return response()->json(['message' => $this->message['message_error']]);

    }

    /** Private Function */
    private function upload_image($image)
    {
        if(!empty($image))
        {
            $image->move(base_path() . '/public/images/comment/', sha1(time()) .'.'. $image->getClientOriginalExtension());
            $data = URL::to('/images/comment') .'/'. sha1(time()) .'.'. $image->getClientOriginalExtension();
            return $data;
        } else {
            return false;
        }
    }

}