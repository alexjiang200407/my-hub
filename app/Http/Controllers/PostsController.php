<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function retrieve()
    {       
        $posts = Post::all();
        $output = [];

        foreach ($posts as $post) {
            $temp = $post->toArray();
            $post_data = [
                "title" => $temp["title"],
                "content" => $temp["content"],
                "id" => $temp["id"],
                "timestamp" => $temp["created_at"]
            ];
            $tags = DB::table("tags")->where("tags.postId","=", $post["id"])->get();
            
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
}
