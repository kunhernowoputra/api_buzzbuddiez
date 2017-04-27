<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 18/10/16
 * Time: 10:31
 */

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public $message;

    /** Construct Function */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['get_interest']]);
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    /** Get Function */
    public function get_interest()
    {
        $interest = Interest::orderBy('name', 'asc')->get();
        return response()->json(['message'=>$this->message['message_success'], 'data' => $interest]);
    }

    /** Post Function */
    public function get_user_interest(Request $request)
    {

        $user = User::whereApiToken($request->input('api_token'))->first();
        $user_interest = UserInterest::whereUserId($user->id)->select('interest_id')->get();

        foreach ($user_interest as $v) {
            $interest_id[] = $v->interest_id;
        }

        $interest = Interest::orderBy('name', 'asc')->get();
        return response()->json([
            'message' => $this->message['message_success'],
            'data_interest' => $interest,
            'user_interest'=> Interest::whereIn('id', $interest_id)->get()
            ]);
    }

    /** Patch function */
    public function update_user_interest(Request $request)
    {
        $rules = [
            'interest_id' => 'required|array'
        ];

        $this->validate($request, $rules);

        $check = User::whereApiToken($request->input('api_token'))->first();

        UserInterest::whereUserId($check->id)->delete();
        foreach ($request->input('interest_id') as $interest) {
            UserInterest::create([
                'user_id' => $check->id,
                'interest_id' => $interest
            ]);
        }

        return response()->json(['message' => $this->message['message_success'], 'data' => UserInterest::whereUserId($check->id)->get()]);
    }

}