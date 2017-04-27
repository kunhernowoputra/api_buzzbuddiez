<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 21/09/16
 * Time: 10:56
 */

namespace App\Http\Controllers;


use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public $message ;

    public function __construct()
    {
        $this->middleware('auth');
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    public function create_room(Request $request)
    {
        $rules = [
            'api_token' => 'required',
            'to_id' => 'required|unique:chat_rooms,to_id'
        ];

        $this->validate($request, $rules);
        $check_token = User::whereApiToken($request->input('api_token'))->first();

        $data = ChatRoom::create([
            'user_id' => $check_token->id,
            'to_id' => $request->input('to_id')
        ]);
        return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
    }

    public function list_history(Request $request)
    {
        $user = User::whereApiToken($request->input('api_token'))->first();
        $rooms = ChatRoom::whereUserId($user->id)->get();

        if(count($rooms) > 0) {
            $data = [];
            foreach ($rooms as $room) {
                $data[] = [
                    'to_id' => UserProfile::whereUserId($room->to_id)->select('user_id','fullname', 'image')->first(),
                    'last_message' => Chat::whereToId($room->to_id)->select('message','created_at')->orderBy('created_at', 'desc')->first()
                ];
            }
            return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'alert' => 'room_id berlum di create']);
        }

    }

    public function chat_message(Request $request)
    {
        $rules = [
            'chat_room_id' => 'required',
            'to_id' => 'required',
            'message' => 'required',
        ];

        $this->validate($request, $rules);

        $user = User::whereApiToken($request->input('api_token'))->first();

        $message = Chat::create([
            'chat_room_id' => $request->input('chat_room_id'),
            'user_id' => $user->id,
            'to_id' => $request->input('to_id'),
            'message' => $request->input('message'),
        ]);

        $data = [
            'chat_room_id' => $message->chat_room_id,
            'user_id' => UserProfile::whereUserId($message->user_id)->first(),
            'to_id' => UserProfile::whereUserId($message->to_id)->first(),
            'message' => $message->message,
        ];

        return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
    }

}