<?php

require_once '../Facebook/autoload.php';

session_start();

$fb = new Facebook\Facebook([
	'app_id' => '1140094136341213',
	'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
  'default_graph_version' => 'v3.2'
]);

try {
  $myPDO = new PDO("pgsql:host=ec2-54-247-118-139.eu-west-1.compute.amazonaws.com dbname=ddc96j0h6vcha2",
          "ulcnrmhvugakls", "4173dfbb7c968ad0ca1333a89cc5ccd9b9cf3e7661eb18a252fefc377de72487");
  echo "Connected to database\n";
} catch(PDOException $e) {
  echo "Error: \"" . $e->getMessage() . "\"";
}

$accessToken = (string) $_SESSION['fb_access_token'];


$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  ;//echo 'Facebook SDK returned an error: ' . $e->getMessage();
  //exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}


/*metadata access_token
// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';

echo "<pre>"; print_r($tokenMetadata); echo "</pre>";
*/



$accessToken = (string) $accessToken;

try{
  //caut user_id in bd ca sa vad daca s-a mai logat utilizatorul si inainte
  $stmt = $myPDO->prepare("SELECT user_id FROM facebook_token where access_token = ?");
  $stmt->execute(array((string) $accessToken));
  $user = $stmt->fetch(PDO::FETCH_LAZY);
 
  if($user['user_id']){
    //s-a mai logat
    echo "welcome back user ";
  }
  else{
    $stmt1 = $myPDO->prepare("insert into facebook_token (access_token) values (?)");
    $stmt1->execute(array((string) $accessToken));
    echo "hello for the first time user ";
  }


  $stmt2 = $myPDO->prepare("SELECT user_id FROM facebook_token where access_token = ?");
  $stmt2->execute(array((string) $accessToken));
  $user = $stmt2->fetch(PDO::FETCH_LAZY);
  $_SESSION['user_id'] = $user['user_id'];
  echo $user['user_id']; 
} catch(PDOException $e) {
  echo "Error: \"" . $e->getMessage() . "\"";
}

echo "<br><br><br><br><br>";
$_SESSION['fb_access_token'] = (string) $accessToken;



//iau access token de la pagina
try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('110396577275014?fields=access_token', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$page = $response->getGraphUser();
$_SESSION['page_access_token'] = (string) $page['access_token'];


//folosesc access token de la pagina pentru like comment si subscribe =)

$fb = new Facebook\Facebook([
	'app_id' => '1140094136341213',
	'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
	'default_graph_version' => 'v3.2'
  ]);
try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('110396577275014/feed?fields=from{name},message,likes{name,id},comments{message,like_count,comment_count,comments}', $_SESSION['page_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

//afiseaza informatia ca un zeu
echo '<pre>'; print_r($response->getDecodedBody()); echo '</pre>';

?>