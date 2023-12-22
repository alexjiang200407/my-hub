<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function retrieve(Request $request)
    {
        $user = $request->user();
        $posts = DB::table("posts")->where("posts.userId","=", $user->id)->get();
        $output = [];

        foreach ($posts as $post) {
            $temp = $post;
            $post_data = [
                "title" => $temp->title,
                "content" => $temp->content,
                "id" => $temp->id,
                "timestamp" => $temp->created_at,
                "tags" => []
            ];
            $tags = DB::table("tags")->where("tags.postId","=", $post->id)->get();
            
            // Set the tags
            foreach ($tags as $tag) {
                $post_data["tags"][] = $tag->tag;
            }
            $output[] = $post_data;
        }
        return [
            "postsC" => $posts->count(),
            "posts" => $output
        ];
    }


    public function save(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'title' => 'required|string',
            'content' => 'string',
            'tags' => 'required|array',
            'tags.*' => 'string|distinct'
        ]);

        $post = new Post([
            'title' => $request->title,
            'content' => $request->content,
            'userId' => $user->id
        ]);

        if ($post->save())
        {
            return response()->json([
                'message' => 'Successfully created post!',
                'id' => $post->id
            ], 201);
        }
        else
        {
            return response()->json(['message' => 'Provide proper details']);
        }
    }
}
