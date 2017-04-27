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

use App\Models\Comment;
use App\Models\Location;
use App\Models\Photo;
use App\Models\PollingAnswer;
use App\Models\PollingItem;
use App\Models\Post;
use App\Models\PostExpression;
use App\Models\PostHashtag;
use App\Models\PostLink;
use App\Models\PostReport;
use App\Models\PostSticker;
use App\Models\Sticker;
use App\Models\User;
use App\Models\UserBuzz;
use App\Models\UserProfile;
use App\Models\UserTag;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class PostController extends Controller
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

    /** Get Function */
    public function get_view_public(Request $request)
    {
        $this->validate($request, [
            'post_limit' => 'required',
            'comment_limit' => 'required'
        ]);

        $post = Post::select('*')->orderBy('created_at', 'desc')->paginate($request->input('post_limit'),['*'],'posts');

        $data = [];
        foreach ($post as $v)
        {
            $expression = DB::table('expressions as e')
                ->select('*')
                ->addSelect(DB::raw("(
                    SELECT 
                        count(*) 
                    FROM 
                        post_expressions as p
					WHERE
					    p.expression_id = e.id and
						p.post_id = {$v->id}
					) as total_voter"))
                ->orderBy('e.id', 'asc')
                ->get();

            $stickers = [];
            foreach ($v->post_sticker as $sticker){
               $stickers[] = $sticker->sticker_id;
            }

            $user_tags = [];
            foreach ($v->user_tag as $user_tag) {
                $user_tags[] = $user_tag->user_id;
            }


            $comments = [];
            $query_comment = Comment::wherePostId($v->id)->orderBy('created_at','desc')->take($request->input('comment_limit'))->get();
            $offset = (empty($request->input('offset')) ? 0 : $request->input('offset'));
            foreach ($query_comment as $comment) {
                $comments[] = [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                    'image' => $comment->image,
                    'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                    'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                    'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                ];
            }

            $photos = [];
            foreach ($v->photo as $photo) {
                $photos[] = [
                    'id' => $photo->id,
                    'thumbnail' => $request->root() . '/images/posts/' .$photo->thumbnail  ,
                ];
            }

            $data[] = [
                'id' => $v->id,
                'user_id' => $v->user_id,
                'user_profile' => UserProfile::whereUserId($v->user_id)->first(),
                'post_type' => $v->post_type,
                'content' => $v->content,
                'created_at' => date('Y-m-d h:i:s', $v->created_at->timestamp),
                'updated_at' => date('Y-m-d h:i:s', $v->updated_at->timestamp),
                'hashtag' => PostHashtag::wherePostId($v->id)->get(),
                'link' => PostLink::wherePostId($v->id)->first(),
                'comment' => [
                    'next_page_url' => URL::to('comment',[$v->id, $offset]),
                    'data' => $comments
                ],
                'photo' => $photos,
                'video' => [
                    'id' => $v->video['id'],
                    'thumbnail' => $request->root() . '/videos/posts/' .$v->video['thumbnail'],
                    'path' => $request->root() . '/videos/posts/' .$v->video['path'],
                ],
                'pollings' => [
                    'polling' => $v->polling,
                    'polling_item' => PollingItem::wherePollingId($v->polling['id'])->get(),
                ],
                'post_sticker' => Sticker::whereIn('id',$stickers)->get(),
                'location' => $v->location,
                'interest' => $v->interest,
                'post_view' => $v->post_view->count(),
                'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
                'expressions' => [
                    'total' => $v->post_expression()->count(),
                    'list' => $expression,
                ]
            ];
        }

        return response()->json([
            'message' => $this->message['message_success'],
            'data' => [
                'total' => $post->total(),
                'per_page' => $post->perPage(),
                'current_page' => $post->currentPage(),
                'last_page' => $post->lastPage(),
                'next_page_url' => $post->nextPageUrl(),
                'prev_page_url' => $post->previousPageUrl(),
                'results' => $data
            ]
        ]);
    }

    public function get_view(Request $request)
    {
        $this->validate($request, [
            'post_limit' => 'required',
            'comment_limit' => 'required'
        ]);

        $user = User::whereApiToken($request->input('api_token'))->with('user_interest')->first();
        foreach ($user->user_interest as $v) {
           $interest_id[] = $v->interest_id;
        }

        $post = Post::whereIn('interest_id', $interest_id)->orderBy('created_at','desc');
        $paginate = $post->paginate($request->input('post_limit'),['*'],'posts');

        $data = [];
        foreach ($post->get() as  $v) {
            $q_expression = DB::table('expressions as e')
               ->select('*')
               ->addSelect(DB::raw("(
					select count(*) from post_expressions as p
					where p.expression_id = e.id and p.post_id = {$v->id}
				) as total_voter"))
               ->orderBy('e.id', 'asc')
               ->get();

            $selected_expression = PostExpression::whereUserId($user->id)->wherePostId($v->id)->first();
            $expressions = [];
            foreach ($q_expression as $expression) {
               $expressions[] = [
                   'id' => $expression->id,
                   'name' => $expression->name,
                   'path' => $expression->path,
                   'total_voter' => $expression->total_voter
               ];
            }


            $stickers = [];
            foreach ($v->post_sticker as $sticker){
               $stickers[] = $sticker->sticker_id;
             }

            $user_tags = [];
            foreach ($v->user_tag as $user_tag) {
               $user_tags[] = $user_tag->user_id;
            }

            $comments = [];
            $query_comment = Comment::wherePostId($v->id)->orderBy('created_at','desc')->take($request->input('comment_limit'))->get();
            $offset = (empty($request->input('offset')) ? 0 : $request->input('offset'));

            foreach ($query_comment as $comment) {
               $comments[] = [
                   'id' => $comment->id,
                   'comment' => $comment->comment,
                   'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                   'image' => $comment->image,
                   'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                   'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                   'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
               ];
            }

            $polling_answer = DB::table('polling_items as a')
                ->select('*')
                ->addSelect(DB::raw("(
					select count(*) from polling_answers as b
					where b.polling_item_id = a.id 
				) as total_voter"))
                ->where('a.polling_id','=', $v->polling['id'])
                ->get();

            $selected_polling = PollingAnswer::whereUserId($user->id)->wherePostId($v->id)->first();

            $user_profile = (UserProfile::whereUserId($v->user_id)->first()) ? UserProfile::whereUserId($v->user_id)->first() : 'tidak ditemukan';

            $data[] = [
                'id' => $v->id,
                'user_id' => $v->user_id,
                'user_profile' => $user_profile,
                'post_type' => $v->post_type,
                'content' => $v->content,
                'created_at' =>  date('Y-m-d h:i:s', $v->created_at->timestamp),
                'updated_at' => date('Y-m-d h:i:s', $v->updated_at->timestamp),
                'hashtag' => PostHashtag::wherePostId($v->id)->get(),
                'link' => PostLink::wherePostId($v->id)->first(),
                'comment' =>[
                    'next_page_url' => URL::to('comment',[$v->id, $offset]),
                    'data' => $comments
                ],
                'photo' => $v->photo,
                'video' => [
                    'id' => $v->video['id'],
                    'thumbnail' => $request->root() . '/videos/posts/' .$v->video['thumbnail'],
                    'path' => $request->root() . '/videos/posts/' .$v->video['path'],
                ],
                'polling' => [
                    'selected' => ($selected_polling) ? true : false,
                    'question' => $v->polling,
                    'answer' => $polling_answer
                ],
                'post_sticker' => Sticker::whereIn('id', $stickers)->get(),
                'location' => $v->location,
                'interest' => $v->interest,
                'post_view' => $v->post_view->count(),
                'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
                'total_expression' => $v->post_expression()->count(),
                'expressions' => [
                   'total' => $v->post_expression()->count(),
                   'selected' => ($selected_expression) ? true : false,
                   'list' => $q_expression,
               ]

            ];
        }

       return response()->json([
           'message' => $this->message['message_success'],
           'data' => [
               'total' => $paginate->total(),
               'per_page' => $paginate->perPage(),
               'current_page' => $paginate->currentPage(),
               'last_page' => $paginate->lastPage(),
               'next_page_url' => (empty($paginate->nextPageUrl()) ? $paginate->nextPageUrl() : $paginate->nextPageUrl().'&api_token='.$request->input('api_token')),
               'prev_page_url' => (empty($paginate->previousPageUrl()) ? $paginate->previousPageUrl() : $paginate->previousPageUrl().'&api_token='.$request->input('api_token')),
               'results' => $data
           ]
       ]);
    }

    public function get_post_user(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'post_limit' => 'required',
            'comment_limit' => 'required'
        ]);

        $post = Post::whereUserId($request->input('user_id'));
        $paginate = $post->orderBy('created_at','desc')->paginate($request->input('post_limit'));
        $data = [];

        if (count($post->get())) {
            foreach ($post->get() as $v) {
                $expression = DB::table('expressions as e')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from post_expressions as p
					where
						p.expression_id = e.id and
						p.post_id = {$v->id}
				) as total_voter"))
                    ->orderBy('e.id', 'asc')
                    ->get();

                $user = User::whereApiToken($request->input('api_token'))->first();
                $selected = PostExpression::whereUserId($user->id)->wherePostId($v->id)->first();

                $stickers = [];
                foreach ($v->post_sticker as $sticker){
                    $stickers[] = $sticker->sticker_id;
                }

                $user_tags = [];
                foreach ($v->user_tag as $user_tag) {
                    $user_tags[] = $user_tag->user_id;
                }

                $comments = [];
                $query_comment = Comment::wherePostId($v->id)->orderBy('created_at','desc')->take($request->input('comment_limit'))->get();
                $offset = (empty($request->input('offset')) ? 0 : $request->input('offset'));

                foreach ($query_comment as $comment) {
                    $comments[] = [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                        'image' => $comment->image,
                        'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                        'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                        'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                    ];
                }

                $polling_answer = DB::table('polling_items as a')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from polling_answers as b
					where b.polling_item_id = a.id 
				) as total_voter"))
                    ->where('a.polling_id','=', $v->polling['id'])
                    ->get();

                $selected_polling = PollingAnswer::whereUserId($user->id)->wherePostId($v->id)->first();

                $photos = [];
                foreach ($v->photo as $photo) {
                    $photos[] = [
                        'id' => $photo->id,
                        'thumbnail' => $request->root() . '/images/posts/' .$photo->thumbnail  ,
                    ];
                }

                $buzz = UserBuzz::whereToId($user->id)->get();

                $data[] = [
                    'counter_buzz' => count($buzz),
                    'id' => $v->id,
                    'user_id' => $v->user_id,
                    'user_profile' => UserProfile::whereUserId($v->user_id)->first(),
                    'post_type' => $v->post_type,
                    'content' => $v->content,
                    'created_at' =>  date('Y-m-d h:i:s', $v->created_at->timestamp),
                    'updated_at' => date('Y-m-d h:i:s', $v->updated_at->timestamp),
                    'hashtag' => PostHashtag::wherePostId($v->id)->get(),
                    'link' => PostLink::wherePostId($v->id)->first(),
                    'comment' =>[
                        'next_page_url' => URL::to('comment',[$v->id, $offset]),
                        'data' => $comments
                    ],
                    'photo' => $photos,
                    'video' => [
                        'id' => $v->video['id'],
                        'thumbnail' => $request->root() . '/videos/posts/' .$v->video['thumbnail'],
                        'path' => $request->root() . '/videos/posts/' .$v->video['path'],
                    ],
                    'polling' => [
                        'selected' => ($selected_polling) ? true : false,
                        'question' => $v->polling,
                        'answer' => $polling_answer
                    ],
                    'location' => $v->location,
                    'interest' => $v->interest,
                    'post_view' => $v->post_view->count(),
                    'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
                    'total_expression' => $v->post_expression()->count(),
                    'expressions' => [
                        'total' => $v->post_expression()->count(),
                        'selected' => ($selected) ? true : false,
                        'list' => $expression,
                    ]

                ];
            }
            return response()->json([
                'message' => $this->message['message_success'],
                'data' => [
                    'total' => $paginate->total(),
                    'per_page' => $paginate->perPage(),
                    'current_page' => $paginate->currentPage(),
                    'last_page' => $paginate->lastPage(),
                    'next_page_url' => $paginate->nextPageUrl(),
                    'prev_page_url' => $paginate->previousPageUrl(),
                    'results' => $data
                ]
            ]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'description' => 'Post not found']);
        }


    }

    public function get_post_interest(Request $request)
    {
        $this->validate($request, [
            'post_limit' => 'required',
            'comment_limit' => 'required',
            'interest_id' => 'required',
        ]);

        $user = User::whereApiToken($request->input('api_token'))->first();

        $interest = $request->get('interest_id');
        $post = Post::whereInterestId($interest);
        $paginate = $post->paginate($request->input('post_limit'));


        if(count($post->get()) > 0) {
            $data = [];
            foreach ($post->get() as $v) {
                $expression = DB::table('expressions as ex')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from post_expressions as pe
					where
						pe.expression_id = ex.id and
						pe.post_id = {$v->id}
				) as total_voter"))
                    ->orderBy('ex.id', 'asc')
                    ->get();

                $selected = PostExpression::whereUserId($user->id)->wherePostId($v->id)->first();

                $stickers = [];
                foreach ($v->post_sticker as $sticker){
                    $stickers[] = $sticker->sticker_id;
                }

                $user_tags = [];
                foreach ($v->user_tag as $user_tag) {
                    $user_tags[] = $user_tag->user_id;
                }

                $comments = [];
                $query_comment = Comment::wherePostId($v->id)->orderBy('created_at','desc')->take($request->input('comment_limit'))->get();
                $offset = (empty($request->input('offset')) ? 0 : $request->input('offset'));

                foreach ($query_comment as $comment) {
                    $comments[] = [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                        'image' => $comment->image,
                        'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                        'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                        'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                    ];
                }

                $polling_answer = DB::table('polling_items as a')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from polling_answers as b
					where b.polling_item_id = a.id 
				) as total_voter"))
                    ->where('a.polling_id','=', $v->polling['id'])
                    ->get();

                $selected_polling = PollingAnswer::whereUserId($user->id)->wherePostId($v->id)->first();

                $photos = [];
                foreach ($v->photo as $photo) {
                    $photos[] = [
                        'id' => $photo->id,
                        'thumbnail' => $request->root() . '/images/posts/' .$photo->thumbnail  ,
                    ];
                }


                $data[] = [
                    'id' => $v->id,
                    'user_id' => $v->user_id,
                    'user_profile' => UserProfile::whereUserId($v->user_id)->first(),
                    'post_type' => $v->post_type,
                    'content' => $v->content,
                    'created_at' =>  date('Y-m-d h:i:s', $v->created_at->timestamp),
                    'updated_at' => date('Y-m-d h:i:s', $v->updated_at->timestamp),
                    'hashtag' => PostHashtag::wherePostId($v->id)->get(),
                    'link' => PostLink::wherePostId($v->id)->first(),
                    'comment' =>[
                        'next_page_url' => URL::to('comment',[$v->id, $offset]),
                        'data' => $comments
                    ],
                    'photo' => $photos,
                    'video' => [
                        'id' => $v->video['id'],
                        'thumbnail' => $request->root() . '/videos/posts/' .$v->video['thumbnail'],
                        'path' => $request->root() . '/videos/posts/' .$v->video['path'],
                    ],
                    'polling' => [
                        'selected' => ($selected_polling) ? true : false,
                        'question' => $v->polling,
                        'answer' => $polling_answer
                    ],
                    'post_sticker' => Sticker::whereIn('id', $stickers)->get(),
                    'location' => $v->location,
                    'interest' => $v->interest,
                    'post_view' => $v->post_view->count(),
                    'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
                    'total_expression' => $v->post_expression()->count(),
                    'expressions' => [
                        'total' => $v->post_expression()->count(),
                        'selected' => ($selected) ? true : false,
                        'list' => $expression,
                    ]

                ];
            }
            return response()->json([
                'message' => $this->message['message_success'],
                'data' => [
                    'total' => $paginate->total(),
                    'per_page' => $paginate->perPage(),
                    'current_page' => $paginate->currentPage(),
                    'last_page' => $paginate->lastPage(),
                    'next_page_url' => $paginate->nextPageUrl(),
                    'prev_page_url' => $paginate->previousPageUrl(),
                    'results' => $data
                ]
            ]);
        } else {
            return response(['message' => $this->message['message_error'], 'description' => 'data not found']);
        }


    }

    public function get_post_hashtag(Request $request)
    {
        $this->validate($request, [
            'post_limit' => 'required',
            'comment_limit' => 'required',
            'hashtag' => 'required',
        ]);

        $user = User::whereApiToken($request->input('api_token'))->with('user_interest')->first();
        $hashtag = PostHashtag::whereName($request->input('hashtag'))->get();

        if (count($hashtag) > 0) {
            $ids = [];
            foreach ($hashtag as $v) {
                $ids[] = $v->post_id;
            }

            $post = Post::whereIn('id', $ids)->orderBy('created_at','desc');
            $paginate = $post->paginate($request->input('post_limit'),['*'],'posts');

            $data = [];
            foreach ($post->get() as $v) {

                $q_expression = DB::table('expressions as e')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from post_expressions as p
					where p.expression_id = e.id and p.post_id = {$v->id}
				) as total_voter"))
                    ->orderBy('e.id', 'asc')
                    ->get();

                $selected = PostExpression::whereUserId($user->id)->wherePostId($v->id)->first();

                $expressions = [];
                foreach ($q_expression as $expression) {
                    $expressions[] = [
                        'id' => $expression->id,
                        'name' => $expression->name,
                        'path' => $expression->path,
                        'total_voter' => $expression->total_voter
                    ];
                }

                $stickers = [];
                foreach ($v->post_sticker as $sticker){
                    $stickers[] = $sticker->sticker_id;
                }

                $user_tags = [];
                foreach ($v->user_tag as $user_tag) {
                    $user_tags[] = $user_tag->user_id;
                }

                $comments = [];
                $query_comment = Comment::wherePostId($v->id)->orderBy('created_at','desc')->take($request->input('comment_limit'))->get();
                $offset = (empty($request->input('offset')) ? 0 : $request->input('offset'));

                foreach ($query_comment as $comment) {
                    $comments[] = [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                        'image' => $comment->image,
                        'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                        'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                        'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                    ];
                }

                $polling_answer = DB::table('polling_items as a')
                    ->select('*')
                    ->addSelect(DB::raw("(
					select count(*) from polling_answers as b
					where b.polling_item_id = a.id 
				) as total_voter"))
                    ->where('a.polling_id','=', $v->polling['id'])
                    ->get();

                $selected_polling = PollingAnswer::whereUserId($user->id)->wherePostId($v->id)->first();

                $photos = [];
                foreach ($v->photo as $photo) {
                    $photos[] = [
                        'id' => $photo->id,
                        'thumbnail' => $request->root() . '/images/posts/' .$photo->thumbnail  ,
                    ];
                }

                $data[] = [
                    'id' => $v->id,
                    'user_id' => $v->user_id,
                    'user_profile' => UserProfile::whereUserId($v->user_id)->first(),
                    'post_type' => $v->post_type,
                    'content' => $v->content,
                    'created_at' =>  date('Y-m-d h:i:s', $v->created_at->timestamp),
                    'updated_at' => date('Y-m-d h:i:s', $v->updated_at->timestamp),
                    'hashtag' => PostHashtag::wherePostId($v->id)->get(),
                    'link' => PostLink::wherePostId($v->id)->first(),
                    'comment' =>[
                        'next_page_url' => URL::to('comment',[$v->id, $offset]),
                        'data' => $comments
                    ],
                    'photo' => $photos,
                    'video' => [
                        'id' => $v->video['id'],
                        'thumbnail' => $request->root() . '/videos/posts/' .$v->video['thumbnail'],
                        'path' => $request->root() . '/videos/posts/' .$v->video['path'],
                    ],
                    'polling' => [
                        'selected' => ($selected_polling) ? true : false,
                        'question' => $v->polling,
                        'answer' => $polling_answer
                    ],
                    'post_sticker' => Sticker::whereIn('id', $stickers)->get(),
                    'location' => $v->location,
                    'interest' => $v->interest,
                    'post_view' => $v->post_view->count(),
                    'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
                    'total_expression' => $v->post_expression()->count(),
                    'expressions' => [
                        'total' => $v->post_expression()->count(),
                        'selected' => ($selected) ? true : false,
                        'list' => $q_expression,
                    ]

                ];

            }
            return response()->json([
                'message' => $this->message['message_success'],
                'data' => [
                    'total' => $paginate->total(),
                    'per_page' => $paginate->perPage(),
                    'current_page' => $paginate->currentPage(),
                    'last_page' => $paginate->lastPage(),
                    'next_page_url' => (empty($paginate->nextPageUrl()) ? $paginate->nextPageUrl() : $paginate->nextPageUrl().'&api_token='.$request->input('api_token')),
                    'prev_page_url' => (empty($paginate->previousPageUrl()) ? $paginate->previousPageUrl() : $paginate->previousPageUrl().'&api_token='.$request->input('api_token')),
                    'results' => $data
                ]
            ]);
        } else {
            return response()->json(['message' => $this->message['message_error'], 'description' => 'data not found']);
        }

    }

    public function get_post_detail(Request $request, $id)
    {
        $this->validate($request,[
            'comment_limit' => 'required'
        ]);

        $user = User::whereApiToken($request->api_token)->first();

        $post = Post::find($id);
        $list_expression = DB::table('expressions as ex')
            ->select('name','path')
            ->addSelect(DB::raw("(
                        select count(*) from post_expressions as pe
                        where
                            pe.expression_id = ex.id and
                            pe.post_id = {$post->id}
                    ) as total_voter"))
            ->orderBy('ex.id', 'asc')
            ->get();

        $selected = PostExpression::whereUserId($user->id)->wherePostId($id)->first();

        $stickers = [];
        foreach ($post->post_sticker()->get() as $v)
        {
            $stickers[] = $v->sticker_id;
        }

        $user_tags = [];
        foreach ($post->user_tag as $user_tag) {
            $user_tags[] = $user_tag->user_id;
        }

        $comments = [];
        $paginate_comment = Comment::wherePostId($post->id)->orderBy('created_at', 'desc')->paginate($request->input('comment_limit'),['*'],'comment');
        foreach ($paginate_comment as $comment) {
            $comments[] = [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'sticker' => Sticker::whereId($comment->sticker_id)->first(),
                'image' => $comment->image,
                'user' => UserProfile::whereUserId($comment->user_id)->select('user_id','fullname','image')->get(),
                'created_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
                'updated_at' => date('Y-m-d h:i:s', $comment->created_at->timestamp),
            ];
        }

        $selected_polling = [];
        if ($post->polling()->get() ) {

            foreach ($post->polling()->get() as $p) {
                $polling_answer = DB::table('polling_items as a')
                    ->select('*')
                    ->addSelect(DB::raw("(
                        select count(*) from polling_answers as b
                        where b.polling_item_id = a.id
                        ) as total_voter"))
                    ->where('a.polling_id','=', $p->id)
                    ->get();

                $data_pollings[] = [
                    'id' =>  $p->id,
                    'question' => $p->question,
                    'answer' => $polling_answer
                ];

                $selected_polling =$p->id;
            }
        }
        $photos = [];
        foreach ($post->photo()->get() as $photo) {
            $photos[] = [
                'id' => $photo->id,
                'thumbnail' => $request->root() . '/images/posts/' .$photo->thumbnail  ,
            ];
        }

        $arr_data = [
            'post' => $post,
            'hashtag' => PostHashtag::wherePostId($post->id)->get(),
            'link' => PostLink::wherePostId($post->id)->first(),
            'comment' =>[
                'total' => $paginate_comment->total(),
                'per_page' => $paginate_comment->perPage(),
                'current_page' => $paginate_comment->currentPage(),
                'last_page' => $paginate_comment->lastPage(),
                'next_page_url' => $paginate_comment->nextPageUrl(),
                'prev_page_url' => $paginate_comment->previousPageUrl(),
                'data' => $comments
            ],
            'photo' => $photos,
            'video' => [
                'id' => $post->video['id'],
                'path' => $request->root() . '/videos/posts/' .$post->video['path'],
                'thumbnail' => $request->root() . '/videos/posts/' .$post->video['thumbnail'],
            ],
            'polling' => [
                'selected' => ($selected_polling) ? true : false,
                'data' => (!empty($data_pollings)) ? $data_pollings : null,
            ],
            'post_sticker' => Sticker::whereIn('id', $stickers)->get(),
            'location' => $post->location()->get(),
            'user_profile' => $post->user_profile()->get(),
            'interest' => $post->interest()->get(),
            'expression' => [
                'total_expression' => $post->post_expression()->count(),
                'selected' => ($selected) ? true : false,
                'list_expression' => $list_expression
            ],
            'post_view' => Post::whereId($id)->count(),
            'tagging' => UserProfile::whereIn('user_id', $user_tags)->select('user_id','fullname')->get(),
        ];

        return response()->json(['message' => $this->message['message_success'], 'data' => $arr_data]);
    }

    public function get_link(Request $request)
    {
        $rules = [
            'url' => 'required|url',
        ];

        $this->validate($request, $rules);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTMLFile($request->input('url'));

        $metas = $dom->getElementsByTagName('meta');
        $title = $dom->getElementsByTagName('title');
        $links = $dom->getElementsByTagName('link');

        $data = [];

        $parseUrl = parse_url($request->input('url'));
        $data['url'] = $parseUrl['host'];

        if($title->length > 0){
            $data['title'] = $title->item(0)->nodeValue;
        }

        for ($i = 0; $i < $links->length; $i++)
        {
            $link = $links->item($i);
            if ($link->getAttribute('itemprop') == 'embedURL') {
                $data['embed_url'] = $link->getAttribute('href');
            }
        }

        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);

            if($meta->getAttribute('name') == 'description') {
                $data['description'] = $meta->getAttribute('content');
            }
            if($meta->getAttribute('property') == 'og:image') {
                $data['image_link'] = $meta->getAttribute('content');
            }
            if($meta->getAttribute('property') == 'og:url') {
                $data['link'] = $meta->getAttribute('content');
            }

        }

        return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
    }

    /** Post Function */
    public function post_data(Request $request)
    {
        $post_type = $request->input('post_type');

        $rules = [
            'user_id' => 'required|integer',
            'interest_id' => 'required|integer',
            'post_type' => 'required|integer',
            'content' => 'min:3',
            'sticker_id' => 'array',
            'location' => 'array',
            'user_tag' => 'array',
            'image' => 'required|array',
            'video_path' => 'required',
            'question' => 'required',
            'answer' => 'required|array',
            'end' => 'required|date_format:Y-m-d'
        ];

        if($post_type)
        {
            switch ($post_type)
            {
                case 1;

                    $case_rules = array_except($rules, [
                        'video_path', 'question', 'answer', 'end', 'image'
                    ]);

                    $this->validate($request, array_add($case_rules, 'image', 'array'));
                    $post = Post::post_type($request);
                    $data = Post::whereId($post->id)->with(['post_sticker.sticker','location','user_tag', 'photo'])->get();

                    if ($request->input('url')) {
                        $this->post_link($request, $post->id);
                    }


                    return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
                    break;

                case 2;
                    $case_rules = array_except($rules, [
                        'question', 'answer', 'end', 'image','content'
                    ]);

                    $this->validate($request, $case_rules);
                    $post = Post::post_type($request);
                    $data = Post::whereId($post->id)->with(['post_sticker.sticker', 'location', 'user_tag', 'video'])->get();

                    if ($request->input('url')) {
                        $this->post_link($request, $post->id);
                    }

                    return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
                    break;

                case 3;
                    $case_rules = array_except($rules, [
                        'sticker_id', 'content', 'image', 'video_path'
                    ]);

                    $this->validate($request, array_add($case_rules, 'image', 'array'));
                    $post = Post::post_type($request);
                    $data = Post::whereId($post->id)->with(['location', 'user_tag', 'polling', 'polling.polling_item'])->get();

                    return response()->json(['message' => $this->message['message_success'], 'data' => $data]);
                    break;
            }
        }
        else
        {
            return response()->json(['message' => $this->message['message_error'], 'error' => 'post_type required']);
        }
    }

    public function post_expression(Request $request)
    {
        $rules = [
            'post_id' => 'required|integer',
            'user_id' => 'required|integer',
            'expression_id' => 'required|integer',
        ];

        $this->validate($request, $rules);

        $check = PostExpression::where([
            ['user_id', $request->input('user_id')],
            ['post_id', $request->input('post_id')]
        ])->first();

        if($check)
        {
            return response()->json(['message' => $this->message['message_error'], 'pesan' => 'user_id sudah pernah memilih']);
        } else {
            $new = PostExpression::create([
                'post_id' => $request->input('post_id'),
                'user_id' => $request->input('user_id'),
                'expression_id' => $request->input('expression_id')
            ]);
            return response()->json(['message' => $this->message['message_success'], 'data' => $new]);
        }

    }

    public function post_polling_answer(Request $request)
    {
        $this->validate($request, [
            'post_id' => 'required|integer',
            'polling_item_id' => 'required|integer'
        ]);

        $user = User::whereApiToken($request->input('api_token'))->first();

        PollingAnswer::create([
            'user_id' => $user->id,
            'post_id' => $request->input('post_id'),
            'polling_item_id' => $request->input('polling_item_id'),
        ]);

        return response()->json(['message' => $this->message['message_success']]);

    }

    public function post_report(Request $request)
    {
        $rules = [
            'post_id' => 'required|integer',
            'message' => 'required',
        ];

        $this->validate($request, $rules);

        $user = User::whereApiToken($request->input('api_token'))->first();

        $post = PostReport::create([
            'user_id' => $user->id,
            'post_id' => $request->input('post_id'),
            'message' => $request->input('message')
        ]);

        return response()->json(['message' => $this->message['message_success'], 'data' => $post]);
    }

    public function post_image(Request $request)
    {
        $rules = [
            'post_id' => 'required',
            'image' => 'required',
        ];

        $this->validate($request, $rules);

        $image = $request->file('image');

        $name = str_replace(' ','_', $image->getClientOriginalName());
        $image->move(base_path() . '/public/images/posts/', sha1(time()) . $name);
        $data = Photo::create([
            'post_id' => $request->input('post_id'),
            'thumbnail' => sha1(time()) . $name
        ]);

        return response()->json(['message' => $this->message['message_success'], 'data' => $data]);

    }

    /** Patch Function */
    public function update_data(Request $request)
    {
        $rules = [
            'post_type' => 'required',
            'id' => 'required'
        ];

        $this->validate($request, $rules);

        $post = Post::find($request->input('id'));

        if ($post->post_type == 1)
        {

            $rules_type= [
                'interest_id' => 'required|integer',
                'sticker_id' => 'array',
                'content' => 'max:150',
                'location' => 'array',
                'user_tag' => 'array',
                'image' => 'array',
            ];

            $this->validate($request, $rules_type);

            $hashtag_string = $request->input('content');
            preg_match_all('/#([^\s]+)/', $hashtag_string, $matches);

            if ($matches) {
                foreach ($matches as $tags) {
                    $tag = $tags;
                }

                PostHashtag::wherePostId($post->id)->delete();
                foreach ($tag as $d) {
                    PostHashtag::create([
                        'post_id' => $post->id,
                        'name' => $d
                    ]);
                }
            }

            if ($sticker = $request->input('sticker_id')) {
                PostSticker::wherePostId($post->id)->delete();
                foreach ($sticker as $v) {
                    PostSticker::create([
                        'post_id' => $post->id,
                        'sticker_id' => $v
                    ]);
                }
            }

            if ($location = $request->input('location')) {
                $check = Location::wherePostId($post->id)->first();
                $this->validate($request, [
                    'location.lat' => 'required',
                    'location.long' => 'required',
                    'location.address' => 'required'
                ]);

                if ($check) {
                    Location::wherePostId($post->id)->update([
                        'post_id' => $post->id,
                        'lat' => $location['lat'],
                        'long' => $location['long'],
                        'address' => $location['address'],
                    ]);
                } else {
                    Location::create([
                        'post_id' => $post->id,
                        'lat' => $location['lat'],
                        'long' => $location['long'],
                        'address' => $location['address'],
                    ]);
                }
            }

            if ($user_tag = $request->input('user_tag')) {
                UserTag::wherePostId($post->id)->delete();
                foreach ($user_tag as $v) {
                    UserTag::create([
                        'post_id' => $post->id,
                        'user_id' => $v
                    ]);
                }
            }

            if ($photo = $request->file('image')) {

                $check_images = Photo::wherePostId($post->id)->get();

                if ($check_images) {
                    foreach ($check_images as $image) {
                        File::delete('public/posts/images/' . $image->thumbnail);
                        Photo::wherePostId($image->post_id)->delete();
                    }

                    foreach ($photo as $v) {
                        $fileName =str_replace(' ','', $v->getClientOriginalName());
                        $v->move(base_path() . '/public/post/images' , sha1(time()) . $fileName);
                        Photo::create([
                            'post_id' => $post->id,
                            'thumbnail' => sha1(time()) . $fileName
                        ]);
                    }
                } else {

                    foreach ($photo as $v) {
                        $fileName =str_replace(' ','', $v->getClientOriginalName());
                        $v->move(base_path() . '/public/posts/images' , sha1(time()) . $fileName);
                        Photo::create([
                            'post_id' => $post->id,
                            'thumbnail' => sha1(time()) . $fileName
                        ]);
                    }
                }
            }
        }

        elseif ($post->post_type == 2)
        {
            $rules_type = [
                'interest_id' => 'required|integer',
                'sticker_id' => 'array',
                'video_path' => 'required',
                'content' => 'max:150',
                'location' => 'array',
                'user_tag' => 'array',
                'image' => 'array',
            ];

            $this->validate($request, $rules_type);

            $hashtag_string = $request->input('content');
            preg_match_all('/#([^\s]+)/', $hashtag_string, $matches);

            if ($matches) {
                foreach ($matches as $tags) {
                    $tag = $tags;
                }

                PostHashtag::wherePostId($post->id)->delete();
                foreach ($tag as $d) {
                    PostHashtag::create([
                        'post_id' => $post->id,
                        'name' => $d
                    ]);
                }

            }

            if ($sticker = $request->input('sticker_id')) {
                PostSticker::wherePostId($post->id)->delete();
                foreach ($sticker as $v) {
                    PostSticker::create([
                        'post_id' => $post->id,
                        'sticker_id' => $v
                    ]);
                }
            }

            if ($location = $request->input('location')) {
                $check = Location::wherePostId($post->id)->first();
                $this->validate($request, [
                    'location.lat' => 'required',
                    'location.long' => 'required',
                    'location.address' => 'required'
                ]);

                if ($check) {
                    Location::wherePostId($post->id)->update([
                        'post_id' => $post->id,
                        'lat' => $location['lat'],
                        'long' => $location['long'],
                        'address' => $location['address'],
                    ]);
                } else {
                    Location::create([
                        'post_id' => $post->id,
                        'lat' => $location['lat'],
                        'long' => $location['long'],
                        'address' => $location['address'],
                    ]);
                }


            }

            if ($user_tag = $request->input('user_tag')) {
                UserTag::wherePostId($post->id)->delete();
                foreach ($user_tag as $v) {
                    UserTag::create([
                        'post_id' => $post->id,
                        'user_id' => $v
                    ]);
                }
            }

            if ($video = $request->input('video_path')) {

                $check_video = Video::wherePostId($post->id)->first();
                $video->move(base_path() . '/public/posts/videos' . $video->getClientOriginalExtension());

                if ($check_video) {
                    File::delete('public/posts/videos/' . $check_video->path);
                    $check_video->delete();

                    Video::create([
                        'post_id' => $post->id,
                        'thumbnail' => $video->getClientOriginalExtension(),
                        'path' => $video->getClientOriginalExtension(),
                    ]);
                } else {
                    Video::create([
                        'post_id' => $post->id,
                        'thumbnail' => $video->getClientOriginalExtension(),
                        'path' => $video->getClientOriginalExtension(),
                    ]);
                }

            }
        }

        else
        {
            return response()->json(['message' => $this->message['message_error']]);
        }
        
        $post->update($request->all());

        return $this->get_post_detail($request, $post->id);


    }

    /** Delete Function */
    public function delete_data(Request $request)
    {
        $rules = ['id' => 'required'];
        $this->validate($request, $rules);

        $post = Post::find($request->input('id'));
        $post->delete();
        return response()->json(['message' => $this->message['message_success']]);
    }

    /** Private Function */
    private function post_link($request, $post_id)
    {
        DB::table('post_links')->insert([
            'post_id' => $post_id,
            'url' => $request['url'],
            'title' => $request['title'],
            'description' => $request['description'],
            'link' => $request['link'],
            'embed_url' => $request['embed_url'],
            'image' => $request['image_link'],
        ]);
    }


}