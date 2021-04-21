<?php

session_start();
require './src2/JWT.php';
spl_autoload_register(function($className)
{
    $className = str_replace ('\\', DIRECTORY_SEPARATOR, $className);
    include (dirname(__FILE__) . '/src2/' . $className . '.php');
});
use \Firebase\JWT\JWT;

$key = "example_key";
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
$jwt = JWT::encode($payload, $key);
var_dump($jwt);
$newKey="v[ex+Tw74scKC8VS";
$decodToken="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMTksImlhdCI6MTU4OTAxNTA4Ni41MjMzNDF9.Pmhld1koMi1BnUQ77EBCoYyxeQJABHAIl07B1x4AirQ";
$decoded = JWT::decode($decodToken, $newKey, array('HS256'));
var_dump($decoded);


/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/
print_r(session_save_path());
//$temp1= scandir(session_save_path());

//$temp1=file_get_contents(session_save_path() . $temp2,TRUE,);

/*$url="http://sma-a4.herokuapp.com/token";
var_dump($_SESSION);

$url="http://sma-a4.herokuapp.com/token";
$curl = curl_init();
$parameters=array();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 curl_setopt($curl, CURLOPT_TIMEOUT, 30);
 curl_setopt($curl, CURLOPT_URL, $url);
 $response = curl_exec($curl);
 $headers = curl_getinfo($curl);

 curl_close($curl);
 print_r($response);*/
/*$smakey="v[ex+Tw74scKC8VS";
 $Type101=JWT::decode($response,$smakey,array('HS256'));
 echo "<br>";
 print_r($Type101);
 var_dump($Type101);
 header('Location: ' . $url);
 */echo " past all redirects.";
 



?>



