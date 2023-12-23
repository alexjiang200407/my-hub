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
            'tags' => 'array',
            'tags.*' => 'string|distinct'
        ]);
        
        $content = $request->content;

        $post = new Post([
            'title' => $request->title,
            'content' => (!empty($request->content))? $content : '',
            'userId' => $user->id
        ]);
        
        // Add the post to the posts tabel
        if (!$post->save())
        {
            return response()->json(['message' => 'Provide proper details']);
        }

        if (count($request->tags) > 0)
        {
            foreach ($request->tags as $tag) 
            {
                $newTag = new Tag([
                    'postId' => $post->id,
                    'tag' => $tag
                ]);
                
                // Now add the tags to the tag table
                $newTag->save();
            }    
        }

    
        return response()->json([
            'message' => 'Successfully created post!',
            'postId' => $post->id
        ], 201);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'title' => 'required|string',
            'content' => 'string',
            'tags' => 'array',
            'tags.*' => 'string|distinct',
            'postId' => 'required|string'
        ]);

        $content = $request->content;
        $post = Post::where('id', '=', $request->postId)
        ->where('userId','=', $user->id)
        ->update([
            'title' => $request->title,
            'content' => (!empty($request->content))? $content : ''
        ]);

        if (count($request->tags) > 0)
        {
            DB::table('tags')->where('tags.postId', '=', $request->postId)->delete();
            foreach ($request->tags as $tag) 
            {
                $newTag = new Tag([
                    'postId' => $request->postId,
                    'tag' => $tag
                ]);
                
                // Now add the tags to the tag table
                $newTag->save();
            }    
        }

        return response()->json([
            'message' => 'Successfully updated post!'
        ], 201);
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'postId' => 'required|string'
        ]);
      
        // Delete the post from the table with the correct postId and userId
        DB::table('posts')
        ->where('id', '=' , $request->postId)
        ->where('userId', '=', $user->id)
        ->delete();

        return response()->json([
            'message' => 'Successfully deleted post!'
        ], 201);
    }
}
