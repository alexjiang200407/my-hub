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

            $post_data["tags"] = PostsController::getTags($post->id);

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

    public static function getTags($postId)
    {
        $tagsArr = [];
        $tags = DB::table("tags")->where("tags.postId","=", $postId)->get();
        foreach ($tags as $tag) {
            $tagsArr[] = $tag->tag;
        }

        return $tagsArr;
    }


    public function filter(Request $request)
    {
        $request->validate([
            'tags' => 'array',
            'tags.*' => 'string|distinct',
        ]);

        if (count($request->tags) <= 0)
        {
            return response()->json([
                'postC'=> 0,
                'posts' => []
            ]);
        }

        $posts = [];
        
        $matches = DB::table('tags')
        ->whereIn('tag', $request->tags)
        ->join('posts', 'tags.postId', '=', 'posts.id')
        ->join('users', 'users.id', '=', 'posts.userId')
        ->get();

        
        foreach ($matches as $match)
        {
            $tagsArr = PostsController::getTags($match->postId);

            $posts[] = [
                'posterName' => $match->name,
                'id' => $match->postId,
                'tags' => $tagsArr,
                'timestamp' => $match->created_at,
                'title' => $match->title,
                'content' => $match->content
            ];
        }

        return [
            "posts" => $posts,
            "postC" => $matches->count()
        ];

    }
}
