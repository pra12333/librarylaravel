<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    public function handle(Request $request)
    {
        // Create the BotMan instance
        $botman = app('botman');

        // Listen for the "hello" message
        $botman->hears('hello', function (BotMan $bot) {
            // BotMan instance is now correctly type-hinted
            $bot->reply('Hello! How can I assist you today?');
        });

        $botman->hears('help',function(BotMan $bot ){
            $bot->reply('Here are some things you can ask me: "borrow book","return book","find book"');
        });

        $botman->hears('borrow book', function(Botman $bot) {
            $bot->reply('please provide the title or author of the book you would like to borrow .');
        });

        

        $botman->listen();
        
        // Start listening
       
    }
}