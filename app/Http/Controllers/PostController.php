<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    // return all posts data
    public function index()
    {
        // Get all posts data from Redis cache
        $posts = json_decode(Redis::get('posts'), true);
        if (!$posts) {
            // Get all posts data from DB
            $posts = Post::get()->toArray();

            // Store all posts data in Redis cache for 10 minutes
            Redis::set('posts', json_encode($posts), "EX", 600);
        }

        if (!empty($posts)) {
            $this->apiValid = true;
            $this->apiMessage = "Posts data returned successfully";
            $this->apiData = $posts;
        } else {
            $this->apiMessage = "No posts data found";
        }

        return response()->json([
            'valid' => $this->apiValid,
            'message' => $this->apiMessage,
            'data' => $this->apiData,
        ]);
    }

    // store new post data
    public function store(Request $request)
    {
        $status = 200;

        // add vaiidation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            $this->apiData = $validator->errors();
            $this->apiMessage = $validator->errors()->first();
            $status = 400;
        } else {
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->id(),
            ]);

            if (!empty($post)) {

                // Clear Redis cache
                Redis::del('posts');

                $this->apiValid = true;
                $this->apiMessage = "New post created successfully";
            } else {
                $this->apiMessage = "Error creating new post";
                $status = 500;
            }
        }

        return response()->json([
            'valid' => $this->apiValid,
            'message' => $this->apiMessage,
            'data' => $this->apiData,
        ], $status);
    }

    public function show($id)
    {
        // Get post data by ID from Redis cache
        $post = json_decode(Redis::get("post_$id"), true);
        if (!$post) {
            // post data by ID from DB
            $post = Post::find($id);

            // Store post data by ID in Redis cache for 10 minutes
            Redis::set("post_$id", json_encode($post), "EX", 600);
        }

        if (!empty($post)) {
            $this->apiValid = true;
            $this->apiMessage = "Post data returned successfully";
            $this->apiData = $post;
        } else {
            $this->apiMessage = "No post data found";
        }

        return response()->json([
            'valid' => $this->apiValid,
            'message' => $this->apiMessage,
            'data' => $this->apiData,
        ]);
    }
}
