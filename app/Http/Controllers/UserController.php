<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 01/09/16
 * Time: 16:13
 __  __            _    _                                      _____       _
| |/ /            | |  | |                                    |  __ \     | |
| ' /_   _ _ __   | |__| | ___ _ __ _ __   _____      _____   | |__) |   _| |_ _ __ __ _
|  <| | | | '_ \  |  __  |/ _ \ '__| '_ \ / _ \ \ /\ / / _ \  |  ___/ | | | __| '__/ _` |
| . \ |_| | | | | | |  | |  __/ |  | | | | (_) \ V  V / (_) | | |   | |_| | |_| | | (_| |
|_|\_\__,_|_| |_| |_|  |_|\___|_|  |_| |_|\___/ \_/\_/ \___/  |_|    \__,_|\__|_|  \__,_|

 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBuzz;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{

    public $message;

    /** Construct Function */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['post_register','post_login','get_email','post_forgot_password']]);

        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    /** Post Function*/
    public function post_register(Request $request)
    {
        $rules = [
            'register_type' => 'required',
            'fullname' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'image' => 'image|max:15000|mimes:jpeg,png',
            'interest_id' => 'required|array'
        ];

        $register_type = $request->input('register_type'); // syarat mutlak harus ada register typenya

        if ($register_type == 1)
        {
            $this->validate($request, $rules);
            $image = $this->upload_image($request->file('image')); // fungsi upload image
            $user = User::register_web($request, $image); // insert to database untuk user yang register melalui web

            return response()->json(['message' => $this->message['message_success'],'data' => $user]);

        }
        elseif ($register_type == 2)
        {
            $sosmed_rules = array_except($rules, array('password', 'image')); // rules yang harus dimandatory
            $this->validate($request, array_add($sosmed_rules, 'image', 'url')); // validasi sosial media

            $user = User::register_sosmed($request); // insert to database untuk user yang register melalui sosial media

            return response()->json(['message' => $this->message['message_success'],'data' => $user]);
        }
        else
        {
            return response()->json(['message' => $this->message['message_error'], 'register_type' => 'register type is required']);
        }
    }

    public function post_login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $this->validate($request, $rules);
        $login = User::whereEmail($email)->first();

        if($login)
        {
            if(app()->make('hash')->check($password, $login->password)) {
                $api_token = sha1(time());
                User::find($login->id)->update(['api_token'=>$api_token]);
                return response()->json([
                    'message' => $this->message['message_success'],
                    'api_token' => $api_token,
                    'data' => UserProfile::whereUserId($login->id)->first()]);
            }
            else
            {
                return response()->json(['message' => $this->message['message_error']]);
            }
        }
        else
        {
            return response()->json(['message' => $this->message['message_error']]);
        }
    }

    public function post_change_password(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required',
            'repeat_password' => 'required|same:new_password'
        ]);


        $user = User::whereApiToken($request->input('api_token'))->first();

        if(Hash::check($request->input('current_password'), $user->password))
        {
            $user->password = $request->input('new_password');
            $user->save();
            return response()->json(['message' => $this->message['message_success']]);
        }
        else
        {
            return response()->json(['message' => $this->message['message_error'], 'alert' => 'Password tidak sesuai']);
        }
    }

    public function post_forgot_password($id, Request $request)
    {
        $check = User::whereId($id)->first();
        if ($check) {
            $mailer = app()['mailer'];
            $mailer->send();
        }
    }

    public function post_logout(Request $request)
    {
        $api_token = $request->input('api_token');
        if($api_token) {
            $check = User::find($request->input('id'));
            $check->api_token = null;
            $check->save();
            return response()->json(['message'=>$this->message['message_success']]);
        } else {
            return response()->json(['message'=>$this->message['message_error']]);
        }
    }

    public function post_buzz(Request $request)
    {
        $rules = [
            'to_id' => 'required|integer',
        ];

        $this->validate($request, $rules);

        $user = User::whereApiToken($request->input('api_token'))->first();
        $check_buzz = UserBuzz::whereUserId($request->input('user_id'))->whereToId($request->input('to_id'))->first();

        if($check_buzz) {
            return response()->json(['message' => $this->message['message_success'], 'selected' => true]);
        } else {
            $data = UserBuzz::create([
                'user_id' => $user->id,
                'to_id' => $request->input('to_id')
            ]);

            $buddies = UserBuzz::whereUserId($data->to_id)->whereToId($data->user_id)->first();
            if ($buddies) {
                return 'benar';
            }

            /** Notification Buzz Me*/
            $data_notif = [
                'user_id' => $request->input('to_id'),
                'notification_type_id' => 4,
                'readed' => 0,
            ];
            $notif = new NotificationController();
            $notif->post_notification_comment($data_notif);

            return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
        }

    }

    /** Get Function */
    public function get_profile($id)
    {
        /**
         * Syarat untuk melihat detail user adalah token
         * jadi token harus mandatory bila tidak ada maka request direject
         */

        $user = User::whereId($id)->with('user_profile')->first();

        if($user) {
            ($user->user_profile->birthday == null) ? $age = '' : $age = Carbon::parse($user->user_profile->birthday)->diff(Carbon::now())->format('%y tahun');

            $arr_data = [
                'api_token' => array_get($user,'api_token'),
                'user_id' => array_get($user,'id'),
                'email' => array_get($user,'email'),
                'username' => array_get($user,'username'),
                'fullname' => array_get($user,'user_profile.fullname'),
                'birthday' => array_get($user,'user_profile.birthday'),
                'phone' => array_get($user, 'user_profile.phone'),
                'age' => $age,
                'about' => array_get($user,'user_profile.about'),
                'image' => array_get($user,'user_profile.image'),
                'gender' => array_get($user,'user_profile.gender'),
            ];
            return response()->json(['message' => $this->message['message_success'], 'data' => $arr_data]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'description' => 'user tidak ditemukan']);
        }

    }

    public function get_email($email)
    {
        $check = User::where('email', 'LIKE', $email)->first();

        if($check) {
            return response()->json(['message' => $this->message['message_success'], 'email' => 1]);
        }
        else {
            return response()->json(['message' => $this->message['message_success'], 'email' => 0]);
        }
    }

    public function get_all_user()
    {
        $user = UserProfile::orderBy('fullname', 'asc')->take(20)->get();
        return response()->json(['message' => $this->message['message_success'], 'data' => $user]);
    }

    /** Patch Function */
    public function update_profile($id, Request $request)
    {
        $rules = [
            'fullname' => 'required|min:5',
            'birthday' => 'date_format:Y-m-d',
            'image' => 'image|max:15000',
        ];

        $this->validate($request, $rules);


        $image = $this->upload_image($request->file('image'));
        $user = User::find($id);
        $user->user_profile()->update(array_add($request->only('fullname', 'birthday','location','gender','about','phone'), 'image' ,$image));

        $data_update = UserProfile::whereUserId($id)->with(['user'])->first();

        $arr_data = [

            'api_token' => $user->api_token,
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'fullname' => $data_update->fullname,
            'birthday' => $data_update->birthday,
            'phone' => $data_update->phone,
            'age' => $data_update->phone,
            'about' => $data_update->about,
            'image' => $data_update->image,
            'location' => $data_update->location,
            'gender' => $data_update->gender,

        ];
        return response()->json(['message' => $this->message['message_success'], 'data' => $arr_data]);

    }

    /** Delete Function */
    public function delete_user($id)
    {
        $user = User::find($id);
        $user->user_profile->delete();
        $user->delete();
        return response()->json(['message' => $this->message['message_success']]);
    }

    /** Private Function */

    private function user_device()
    {

    }

    private function upload_image($image)
    {
        if(!empty($image)) {
            $image->move(base_path() . '/public/images/user_profile/', sha1(time()) .'.'. $image->getClientOriginalExtension());
            $data = URL::to('/images/user_profile') .'/'. sha1(time()) .'.'. $image->getClientOriginalExtension();
            return $data;
        } else {
            return false;
        }
    }

}