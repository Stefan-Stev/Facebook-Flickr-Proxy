<?php

require_once '../Facebook/autoload.php';

session_start();

$fb = new Facebook\Facebook([
	'app_id' => '1140094136341213',
	'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
	'default_graph_version' => 'v3.2'
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email'];
$loginUrl = $helper->getLoginUrl('https://web-rfnl5hmkocvsi.azurewebsites.net/fbTest/try/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>