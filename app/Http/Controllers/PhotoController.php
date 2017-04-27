<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 01/09/16
 * Time: 14:35
_   __            _    _                                     _____        _
| |/ /            | |  | |                                    |  __ \     | |
| ' /_   _ _ __   | |__| | ___ _ __ _ __   _____      _____   | |__) |   _| |_ _ __ __ _
|  <| | | | '_ \  |  __  |/ _ \ '__| '_ \ / _ \ \ /\ / / _ \  |  ___/ | | | __| '__/ _` |
| . \ |_| | | | | | |  | |  __/ |  | | | | (_) \ V  V / (_) | | |   | |_| | |_| | | (_| |
|_|\_\__,_|_| |_| |_|  |_|\___|_|  |_| |_|\___/ \_/\_/ \___/  |_|    \__,_|\__|_|  \__,_|

 *
 */

namespace App\Http\Controllers;


use App\Models\PhotoExpression;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public $message;

    /** Construct Function */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['get_view_public']]);
        $this->message = [
            'message_success' => 'success',
            'message_error' => 'error',
        ];
    }

    public function photo_expression(Request $request)
    {
        $rules = [
            'photo_id' => 'required|integer',
            'expression_id' => 'required|integer',
        ];

        $this->validate($request, $rules);

        $check = PhotoExpression::wherePhotoId($request->input('photo_id'))->whereExpressionId($request->input('expression_id'))->first();

        if($check) {
            return response()->json(['message' => $this->message['message_error'], 'pesan' => 'user_id sudah pernah memilih']);

        }
    }


}