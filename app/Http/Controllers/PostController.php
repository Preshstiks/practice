<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function createPost(Request $request){
  
         $request->validate([
            'title' => 'required|string',
            'body' => 'required|string'
        ]);
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'body' => $request->body
        ]);
      return response()->json([
       'status' => 'success',
       'message' => 'Post Created Successfully',
       'post' => $post
      ], 201);
   
    }

    public function getPost(Request $request){
     
        $user = $request->user();
        $post = Post::all();
   
        return response()->json([
            'status' => 'success',
            'data' => $post,
               'user' => $user
        ]);
    }
    public function updatePost(Request $request, Post $post){
        $user = $request->user();

        $validatedPost = $request->validate([
           'title' => 'sometimes|required|string',
           'body' => 'sometimes|required|string'
        ]);
        $post->update($validatedPost);

        return response()->json([
            'status' => 'success',
            'message' => 'Post validated successfully!',
            'data' => $post
            
        ]);
    }
    
    public function deletePost(Request $request, Post $post){
        $user = $request->user();
        if($user->id !== $post->user_id){
              return response()->json([
            'status' => 'error',
            'message' => 'Not your post',
        ], 404);
        }
        $post->deleteOrFail($post);

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted successfully!',
            
        ]);
    }
}
