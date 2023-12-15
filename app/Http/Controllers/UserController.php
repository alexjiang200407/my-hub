<?php

namespace App\Http\Controllers;


class UserController extends Controller
{
    public function getReminders(Int $userId)
    {
        return [
            'reminderC' => 1,
            'reminders' => array(
                [
                    'title' => 'Hello World',
                    'content' => 'This is the content of the reminder',
                    'id' => '69696969',
                    'tags' => array('Hello', 'World'),
                    'timestamp' => '25 Dec 2023'
                ]
            )
        ];
    }
}

