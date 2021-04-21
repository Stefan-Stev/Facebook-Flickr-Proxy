<?php
if(!session_id()) {
  session_start();
} 

require_once './vendor/php-graph-sdk/autoload.php';
require_once './QueryManager.php';
require_once './Classes.php';


$fb = new Facebook\Facebook([
	'app_id' => '1140094136341213',
	'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
  'default_graph_version' => 'v3.2'
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  //exit;
}
header('Location: https://web-rfnl5hmkocvsi.azurewebsites.net/FBFINAL/REST.php?do=register&token='.$accessToken);
