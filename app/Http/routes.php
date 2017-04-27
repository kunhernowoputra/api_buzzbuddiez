<?php

/*
 _  __             _    _                                      _____       _
| |/ /            | |  | |                                    |  __ \     | |
| ' /_   _ _ __   | |__| | ___ _ __ _ __   _____      _____   | |__) |   _| |_ _ __ __ _
|  <| | | | '_ \  |  __  |/ _ \ '__| '_ \ / _ \ \ /\ / / _ \  |  ___/ | | | __| '__/ _` |
| . \ |_| | | | | | |  | |  __/ |  | | | | (_) \ V  V / (_) | | |   | |_| | |_| | | (_| |
|_|\_\__,_|_| |_| |_|  |_|\___|_|  |_| |_|\___/ \_/\_/ \___/  |_|    \__,_|\__|_|  \__,_|

*/

$app->get('tester', function (\Illuminate\Http\Request $request) {

    /**
     * uid, pid, comment
     */

});

$app->group(['prefix' => '/', 'namespace'=>'App\Http\Controllers'], function () use ($app) {

    /** route group */
    $app->group(['prefix' => 'user','namespace'=>'App\Http\Controllers'], function () use($app) {

        /** post route */
        $app->post('/register', 'UserController@post_register'); // registrasi user baik itu tipe web dan tipe social media
        $app->post('/login', 'UserController@post_login'); // user login
        $app->post('/change_password', 'UserController@post_change_password');
        $app->post('/forgot_password/{id}', 'UserController@post_forgot_password'); // mengubah password jika user lupa password
        $app->post('/logout', 'UserController@post_logout'); // logout user
        $app->post('/buzz', 'UserController@post_buzz');

        /** get route */
        $app->get('/profile/{id}', 'UserController@get_profile'); //  detail user berdasarkan id
        $app->get('/email/{email}', 'UserController@get_email'); // check email
        $app->get('/all', 'UserController@get_all_user'); // select semua user buzz

        /** update route */
        $app->patch('/update/{id}', 'UserController@update_profile'); // update data user buzz

        /** delete route */
        $app->delete('/delete/{id}', 'UserController@delete_user'); // delete user buzz
    });
    $app->group(['prefix' => 'interest','namespace' => 'App\Http\Controllers'], function ($app) {

        /** get route */
        $app->get('/','InterestController@get_interest');
        $app->get('/users','InterestController@get_user_interest');

        /** patch route */
        $app->patch('/update', 'InterestController@update_user_interest');
    });
    $app->group(['prefix' => 'post','namespace'=>'App\Http\Controllers'], function ($app) {

        /** post route */
        $app->post('/create', 'PostController@post_data');
        $app->post('/create/expression', 'PostController@post_expression');
        $app->post('/polling_answer', 'PostController@post_polling_answer');
        $app->post('/report', 'PostController@post_report');
        $app->post('/image', 'PostController@post_image');

        /** update route */
        $app->patch('/update', 'PostController@update_data');

        /** get route */
        $app->get('/view_public', 'PostController@get_view_public');
        $app->get('/view', 'PostController@get_view');
        $app->get('/profile', 'PostController@get_post_user');
        $app->get('/interest', 'PostController@get_post_interest');
        $app->get('/detail/{id}', 'PostController@get_post_detail');
        $app->get('/hashtag', 'PostController@get_post_hashtag');
        $app->get('/link','PostController@get_link');

        /** delete route */
        $app->delete('/delete', 'PostController@delete_data');

    });
    $app->group(['prefix' => 'comment', 'namespace'=>'App\Http\Controllers'], function ($app) {

        /** get route */
        $app->get('/{post_id}/{offset}', 'CommentController@get_comment');

        /** post route */
        $app->post('/create', 'CommentController@post_comment');

        /** delete route */
        $app->delete('/{id}', 'CommentController@delete_comment');
    });
    $app->group(['prefix' => 'search', 'namespace' => 'App\Http\Controllers'], function ($app) {

            /** get route */
            $app->get('/{params}', 'SearchController@get_search');
    });
    $app->group(['prefix' => 'notification', 'namespace'=>'App\Http\Controllers'], function ($app) {

        /** get route */
        $app->get('/', 'NotificationController@get_notification');
        $app->get('/read', 'NotificationController@get_readed');
    });
    $app->group(['prefix' => 'chat', 'namespace' => 'App\Http\Controllers'], function ($app) {

        /** get route */
        $app->get('/','ChatController@create_room');
        $app->get('/list', 'ChatController@list_history');

        /** post route */
        $app->post('/','ChatController@chat_message');
    });

    /** route get */
    $app->get('sticker', function () {
        $sticker = \App\Models\Sticker::all();
        return response()->json($sticker);
    });


});




