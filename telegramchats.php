<?php

/*
MIT License

Copyright (C) 2024 Helmut Kaufmann

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

function getChats( $bot, $title = null ) {

    $ch = curl_init( "https://api.telegram.org/bot$bot/getUpdates" );
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    $result = curl_exec( $ch );
    curl_close( $ch );
    $result = json_decode( $result, true );

    $chats = array();
    foreach ( $result["result"] as $key => $ritem ) {
        if ( array_key_exists( "message", $ritem ) && array_key_exists( "chat", $ritem["message"] ) )
           $chats[$ritem["message"]["chat"]["id"]] = $ritem["message"]["chat"]["title"];
    }

    if ( $title ) {
        $key = array_search( $title, $chats );
        if ( $key !== false ) {
            return $key;
        } else
        return null ;
    } else
    return $chats;
}

if ( $argc < 2 ) {
    echo ( "Usage: " . $argv[0] . " 'botAPIID' " . PHP_EOL );
    exit( 0 );
}

if ( $argc == 2 )
   foreach ( @getChats( $argv[1] ) as $id => $title )
      echo "$title : $id" . PHP_EOL;
else
echo "Chat ID for chat '$argv[2]' is " .  @getChats( $argv[1], $argv[2] ) . PHP_EOL;
