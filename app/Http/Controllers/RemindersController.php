<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemindersController extends Controller
{
    public function retrieve()
    {
        $reminders = Reminder::all();
        $output = [];

        foreach ($reminders as $reminder) {
            $temp = $reminder->toArray();
            $reminder_data = [
                "title" => $temp["title"],
                "content" => $temp["content"],
                "id" => $temp["id"],
                "timestamp" => "FUCK"
            ];
            $tags = DB::table("tags")->where("tags.reminderId","=", $reminder["id"])->get();
            
            // Set the tags
            foreach ($tags as $tag) {
                $reminder_data["tags"][] = $tag->tag;
            }
            $output[] = $reminder_data;
        }
        return [
            "remindersC" => $reminders->count(),
            "reminders" => $output
        ];
    }
}
