<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Posts\StoreRequest;
use App\Http\Requests\Api\Posts\UpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $posts = $user->posts()->with('author')->paginate();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;
        
        $post = Post::create($data);
        
        return response()->json(new PostResource($post));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized, Only the author can view this post');
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized, Only the author can view this post');

        $data = $request->validated();
        
        
        $post->update($data);
        
        return response()->json(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(Auth::id() !== $post->author_id, 403, 'Unauthorized, Only the author can view this post');

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
