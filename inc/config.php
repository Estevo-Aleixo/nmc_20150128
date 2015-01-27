<?php

Class Config {

    private static $config = array(
        "analytics.id" => "UA-22770104-6",
        "index.title" => "Turning online horse manure into strawberry jam - since 2009",
        "index.description" => "FIX THE TRANSCRIPTION ON ANY YOUTUBE VIDEO WITH AUTOMATIC CRAPTIONING (CAPTIONING)",
        "index.keywords" => "Transcription, transcribe, Youtube, Caption, Craption, captioning, craptioning, ASR, automatic",
        "go.title" => "NOMORECRAPTIONS FOR YOU: [TITLE]",
        "go.description" => "NOMORECRAPTIONS FOR YOU: VIDEO: [TITLE]",
        "go.keywords" => "Transcription, Youtube, Caption, Craption, captioning, craptioning, ASR, [ID], [TITLE]",
        
        
    );

    public static function get($key) {
        return self::$config[$key];
    }

}
?>
