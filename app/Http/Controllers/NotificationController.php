<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 12/10/16
 * Time: 11:20
 */

namespace App\Http\Controllers;


use App\Models\NotificationPost;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public $message;

    /** Construct Function */
    public function __construct()
    {
        $this->middleware('auth');
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    /** Post Function */
    public function post_notification_comment($notif)
    {
        NotificationPost::create($notif);
    }

    /** Get Function */
    public function get_notification(Request $request)
    {
        $check = User::whereApiToken($request->input('api_token'))->first();

        $notif = NotificationPost::where('user_id', '=', $check->id)->orderBy('created_at','desc')->get();
        if (count($notif) > 0) {
            return response()->json(['message' => $this->message['message_success'], 'data' => $notif]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'description' => 'tidak ada notifikasi']);
        }
    }

    public function get_readed(Request $request)
    {
        $this->validate($request,['action' => 'required']);

        if($request->input('action') == 'all') {
            $check = User::whereApiToken($request->input('api_token'))->first();
            $notif = $check->notification_post()->where('user_id',$check->id);
            $notif->update(['readed' => 1]);

            if (count($notif->get())) {
                return response()->json(['message' => $this->message['message_success'], 'data' => $notif->get()]);
            } else {
                return response()->json(['message' => $this->message['message_error'], 'description' => 'notif tidak ditemukan']);
            }


        } elseif ($request->input('action') == 'first') {
            $this->validate($request,['id' => 'required']);
            $notif = NotificationPost::find($request->input('id'));
            $notif->readed = 1;
            $notif->update();
            return response()->json(['message' => $this->message['message_success'], 'data' => $notif]);

        } else {
            return response()->json(['message' => $this->message['message_error']]);
        }
    }

}