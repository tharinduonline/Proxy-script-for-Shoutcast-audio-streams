<?php

// Proxy script for Shoutcast audio streams. 
// Overcomes the CORS issue when using JavaScript to fetch streams for playback and analysis.
// Also http > https 

/*
// .htaccess file
// eg: index.php and .htaccess in /radio on your host. 
// Point JS/fetch to https://yourhost/radio/audio.mp3  (or any made-up .mp3 name)
<FilesMatch "mp3$">
    SetHandler application/x-httpd-php5
</FilesMatch>
<IfModule mod_rewrite.c>
RewriteEngine On
# RewriteBase /
# Redirect MP3 to PHP
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*).mp3$ index.php [L]
</IfModule>
*/

header('Content-Type: audio/mpeg');
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    //header('Access-Control-Allow-Credentials: true');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

$server = "213.136.81.4"; //Server-IP-Address
$port = "9302"; //Port
//$mount = "[Mount-Point]";

// HTTP Radio Stream URL with Mount Point
$url = "http://".$server.":".$port;

// Open Radio Stream URL
// Make Sure Radio Stream [Port] must be open / allow in this script hosting server firewall 
$f=fopen($url,'r');

// Read chunks maximum number of bytes to read
if(!$f) exit;
while(!feof($f))
{
    echo fread($f,128);
    flush();
}
fclose($f);

?>