<?php
/**
 * Created by PhpStorm.
 * User: aleksander
 * Date: 01.01.15
 * Time: 15:23
 */

// Delete these next 3 lines after testing complete
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function generateCaption($userId,$authToken,$videoUrl,$language='en-GB')
{
    // after this function we have video file stored locally
    $fileName = getVideoAndReturnFileName($videoUrl) ;
    $jobId = uploadVideoAndReturnJobId($userId,$authToken,$fileName,$language) ;

    while (isset(getVideoTranslation($userId,$authToken,$jobId)->code))
    {
        sleep(10) ;
    }

    $translationFile = getVideoTranslation($userId,$authToken,$jobId) ;

    $lineCharacterCounter = 0 ;
    $fileContent = "WEBVTT\n\n" ;
    $line= '' ;
    foreach ($translationFile->words as $word)
    {
        $name = $word->name;
        if ($lineCharacterCounter == 0 && $name =='.')
            continue;
        $duration = $word->duration;
        $time = $word->time;
        if ($lineCharacterCounter == 0)
            $startTime = $time;

        if ($name =='.')
            $line .= $name ;
        else
            $line .= ' ' . $name;

        $lineCharacterCounter += strlen($name);
        $endTime = $time + $duration;

        if (35 <= $lineCharacterCounter)
        {
            $timing = "\n\n" . convertTime($startTime) ." --> ". convertTime($endTime) . "\n$line" ;
            $fileContent .= $timing;
            $lineCharacterCounter = 0 ;
            $line = '' ;
        }
    }
    $captionFile = getIdFromUrl($videoUrl) ;
    file_put_contents($captionFile.'.vtt',$fileContent) ;
    echo("Done");
}

function convertTime($time)
{
    $seconds = $time+1-round($time,0);
    $rest = strval($seconds);
    $temp = '' ;
    for ($i =1 ; $i<=4 ; $i++)
    {
        if (isset($rest[$i]))
            $temp .= $rest[$i] ;
        else
            $temp .= '0' ;

    }

    $time = gmdate("H:i:s", $time). $temp ;
    return $time ;
}

function getIdFromUrl($videoUrl)
{
    parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $my_array_of_vars );
    $id = $my_array_of_vars['v'];
    return $id ;
}

function getVideoAndReturnFileName($videoUrl,$language='en-GB')
{
    $command = './youtube -o "%(id)s".mp4 ' . $videoUrl ;
    system($command) ;
    parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $my_array_of_vars );
    $id = $my_array_of_vars['v'];
    echo(getcwd());
    shell_exec($command) ;
    return $id.'.mp4' ;
}

function uploadVideoAndReturnJobId($userId,$authToken,$fileName,$language='en-GB')
{
    $target_url = 'https://api.speechmatics.com/v1.0/user/' . $userId. '/jobs/?auth_token=' . $authToken ;
    $videoLocation = realpath(getcwd() . '/' .$fileName);

    $post = array('model' => 'en-GB','data_file'=>'@'.$videoLocation);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$target_url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);

    $result= curl_exec($ch);
    curl_close ($ch);

    return json_decode($result)->id ;
}

function getVideoTranslation($userId,$authToken,$jobId)
{
    $target_url = 'https://api.speechmatics.com/v1.0/user/' . $userId . '/jobs/' . $jobId .'/transcript?auth_token='. $authToken ;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$target_url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);

    $result = curl_exec($ch) ;
    return json_decode($result) ;
}

$userId = 327 ;
$authToken = 'OTBhMzVlOTQtY2Q1ZC00OTUzLWE1NGItZmY5ZGRjODFhYzJj' ;
$videoUrl = 'https://www.youtube.com/watch?v=UuhLOrX_GLI' ;

generateCaption($userId,$authToken,$videoUrl) ;
