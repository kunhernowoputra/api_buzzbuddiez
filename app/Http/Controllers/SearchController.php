<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 11/10/16
 * Time: 10:32
 */

namespace App\Http\Controllers;

use App\Models\UserProfile;

class SearchController extends Controller
{
    public $message;

    public function __construct()
    {
        $this->middleware('auth');
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    public function get_search($params)
    {
        if($params) {
            $data = UserProfile::where('fullname', 'LIKE', '%'.$params.'%')->get();
            if(!$data->isEmpty()) {
                return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
            } else {
                return response()->json(['message' => $this->message['message_success'], 'data' => 'tidak ditemukan']);
            }
        } else {
            return response()->json(['message' => $this->message['message_error']]);
        }
    }

}