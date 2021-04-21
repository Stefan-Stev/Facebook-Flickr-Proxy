<?php
require './src2/JWT.php';
spl_autoload_register(function($className)
{
    $className = str_replace ('\\', DIRECTORY_SEPARATOR, $className);
    include (dirname(__FILE__) . '/src2/' . $className . '.php');
});
use \Firebase\JWT\JWT;

$configFile = dirname(__FILE__) . '/config.php';
require_once("./Classes.php");
require_once("./QueryManager.php");
if (file_exists($configFile))
{
    include $configFile;
}
else
{
    die("Please rename the config-sample.php file to config.php and add your Flickr API key and secret to it\n");
}

spl_autoload_register(function($className)
{
    $className = str_replace ('\\', DIRECTORY_SEPARATOR, $className);
    include (dirname(__FILE__) . '/src/' . $className . '.php');
});

use \DPZ\Flickr;

// Build the URL for the current page and use it for our callback
// $callback = sprintf('http://localhost/');
// echo $callback;
$callback = 'https://web-rfnl5hmkocvsi.azurewebsites.net/DPZ/auth2.php';

$flickr = new Flickr($flickrApiKey, $flickrApiSecret, $callback);

if (!$flickr->authenticate('delete'))

    die("Hmm, something went wrong...\n");

     // in callback
$requestokenSecret = $flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET);
$tokenSecret = $flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET);
$userFullName = $flickr->getOauthData(Flickr::USER_FULL_NAME);

$obiect = new QueryManager();
$obiect->getConexiune();

if(isset($_SESSION['userId']) && $_SESSION['userid']!=''){
$sql = 'INSERT INTO flickr_token(user_id, access_token, access_token_secret, request_token_secret, fullname) VALUES(' . $_SESSION['userId'] . ', \'' . $flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN) . '\', \'' . $tokenSecret . '\', \'' . $requestokenSecret . '\', \'' . $userFullName . '\')';
}
if(isset($_SESSION['token'])){
    $newKey="v[ex+Tw74scKC8VS";
    $JWTtoken=$_SESSION['token'];
    $decoded=JWT::decode($JWTtoken,$newKey,array('HS256'));
    if(!isset($decoded->user_id)){
        echo " no user id in token";
        return -1;
    }
    $sql = 'INSERT INTO flickr_token(user_id, access_token, access_token_secret, request_token_secret, fullname) VALUES(' . $decoded->user_id . ', \'' . $flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN) . '\', \'' . $tokenSecret . '\', \'' . $requestokenSecret . '\', \'' . $userFullName . '\')';

}
$obiect->query($sql);
header('Location: ' . $_SESSION['redirect']);






