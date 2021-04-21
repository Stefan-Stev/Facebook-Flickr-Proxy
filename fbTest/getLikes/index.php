<?php
	// require our config file and load the php graph sdk
	require 'config.php';
	require_once '../Facebook/autoload.php';

	// start the session
	session_start();

	$appCreds = array( // array to hold app creds from fb app
		'app_id' => '1140094136341213',
		'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
		'default_graph_version' => 'v3.2'
	);

	if ( isset( $_SESSION['fb_access_token'] ) && $_SESSION['fb_access_token'] ) { // if we have access token, add it to the app creds
		$appCreds['default_access_token'] = $_SESSION['fb_access_token'];
	}

	if ( isset( $_SESSION['fb_access_token'] ) && $_SESSION['fb_access_token'] ) { // we have an access token, use it to get user info from fb
		$isLoggedIn = true;
		echo 'true1';
	} elseif ( isset( $_GET['code'] ) && !$_SESSION['fb_access_token'] ) { // user is coming from allowing our app
		// create new facebook object and helper for getting access token
		$fb = new \Facebook\Facebook( $appCreds );
		$helper = $fb->getRedirectLoginHelper();
		echo 'true2';
		try { // get access token, save to session, and add to app creds
		 	$accessToken = $helper->getAccessToken();
		  	$_SESSION['fb_access_token'] = (string) $accessToken;
		  	$appCreds['default_access_token'] = $_SESSION['fb_access_token'];
		  	$isLoggedIn = true;
		} catch(Facebook\Exceptions\FacebookResponseException $e) { // When Graph returns an error
		    echo 'Graph returned an error: ' . $e->getMessage();
		    exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) { // When validation fails or other local issues
		    echo 'Facebook SDK returned an error: ' . $e->getMessage();
		    exit;
		}
	} else { // user is no logged in, display the login with facebook link
		// create new facebook object and helper for getting access token
		$fb = new \Facebook\Facebook( $appCreds );
		$helper = $fb->getRedirectLoginHelper();
		echo 'true5';
		// user is not logged in
		$isLoggedIn = false;
	}
	echo 'true3';
	if ( $isLoggedIn ) { // logged in
		$fb = new \Facebook\Facebook( $appCreds );

		// call facebook and ask for name and picture
		$facebookResponse = $fb->get( '/me', array (
				'fields' => 'likes,comments{user_likes,comment_count,message,comments{message}}'
		  ) );
		$facebookUser = $facebookResponse->getGraphUser();

		// Use handler to get access token info
		$oAuth2Client = $fb->getOAuth2Client();
		$accessToken = $oAuth2Client->debugToken( $_SESSION['fb_access_token'] );
		?><textarea> <?php  echo $facebookUser; ?> </textarea>
		<?php

		  
	}
?>