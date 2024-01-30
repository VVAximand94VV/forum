<?php

namespace App\Http\Controllers\Client\Post;

use App\Events\PostCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Post\PostStoreRequest;
use App\Http\Requests\Api\Client\Post\PostUpdateRequest;
use App\Http\Resources\Client\Post\PostResource;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\PostLiked;
use App\Notifications\ReplyPost;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class PostController extends Controller
{

    /**
     * @param PostStoreRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     */
    protected function store(PostStoreRequest $request, Topic $topic): \Illuminate\Http\JsonResponse
    {

        $user = AuthService::getAuthorizedUser($request);
        $data = $request->validated();
        if($user->isBanned()){
            AuthService::checkEndOfBan($user);
        }
        $data['userId'] = $user->id;
        $data['topicId'] = $topic->id;
        $post = Post::firstOrCreate($data);
        if(!empty($data['replyId'])){

            $replyPost = Post::find($data['replyId']);
            //dd($replyPost->author);
            $replyPost->author->notify(new ReplyPost($user, $post, $topic));
        }
        return response()->json([
            'message' => 'The post created.',
            'post' => new PostResource($post),
        ]);
    }

    /**
     * @param PostUpdateRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    protected function update(PostUpdateRequest $request, Post $post): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        foreach ($data as $key => $value) {
            $post->$key = $value;
        }
        $post->save();
        return response()->json([
            'message' => 'The post updated.',
            'post' => new PostResource($post),
        ]);
    }

    /**
     * @param Post $post
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function bookmarks(Request $request, Post $post): \Illuminate\Http\JsonResponse
    {
        $user = AuthService::getAuthorizedUser($request);
        $post->likes()->toggle($user->id);
        return response()->json(['message' => 'Success.']);
    }

    /**
     * @param Post $post
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function like(Request $request, Post $post): \Illuminate\Http\JsonResponse
    {
        $user = AuthService::getAuthorizedUser($request);
        $post->bookmarks()->toggle($user->id);
        // post liked event
        $post->author->notify(new PostLiked($user, $post));
        return response()->json(['message' => 'Success.']);
    }

}
