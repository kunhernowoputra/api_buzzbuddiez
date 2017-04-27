<?php
/**
 * Created by PhpStorm.
 * User: linuxers
 * Date: 03/10/16
 * Time: 18:17
 */

namespace App\Http\Controllers;


class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_friend()
    {
        /*
         * syarat fungsi add_friend
         * -
         */
    }

}