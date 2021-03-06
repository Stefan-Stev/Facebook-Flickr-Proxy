<?php
	// require our config file and load the php graph sdk
	require 'defines.php';
	require_once 'vendor/php-graph-sdk/autoload.php';

	// start the session
	session_start();
	

	$appCreds = array( // array to hold app creds from fb app
		'app_id' => '1140094136341213',
		'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
		'default_graph_version' => 'v6.0'
	);
	

	if ( isset( $_SESSION['fb_access_token'] ) && $_SESSION['fb_access_token'] ) { // if we have access token, add it to the app creds
		$appCreds['default_access_token'] = $_SESSION['fb_access_token'];
	}

	if ( isset( $_SESSION['fb_access_token'] ) && $_SESSION['fb_access_token'] ) { // we have an access token, use it to get user info from fb
		$isLoggedIn = true;
	} elseif ( isset( $_GET['code'] ) && !$_SESSION['fb_access_token'] ) { // user is coming from allowing our app
		// create new facebook object and helper for getting access token
		$fb = new \Facebook\Facebook( $appCreds );
		$helper = $fb->getRedirectLoginHelper();

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

		// user is not logged in
		$isLoggedIn = false;
	}

	if ( $isLoggedIn ) { // logged in
		// create new facebook object
		$fb = new \Facebook\Facebook( $appCreds );
		$fb2 = new \Facebook\Facebook( $appCreds );
		// call facebook and ask for name and picture
		$facebookResponse = $fb->get( '/me?fields=first_name,last_name,accounts' );
		$facebookUser = $facebookResponse->getGraphUser();

		// Use handler to get access token info
		$oAuth2Client = $fb->getOAuth2Client();
		$accessToken = $oAuth2Client->debugToken( $_SESSION['fb_access_token'] );
		$data = $facebookUser['accounts'];
		$id = $data['0'];
		$id2= $id['id'];
		$id2.="?fields=instagram_business_account";

		$fb2 = new \Facebook\Facebook( $appCreds );
		$facebookResponse2 = $fb2->get( $id2 );
		$facebookUser2 = $facebookResponse2->getGraphUser();
		$temp= $facebookUser2['instagram_business_account'];
		$idInstagram=$temp['id'];
		$idInstagram.="?fields=followers_count,media";

		
		$facebookResponse2 = $fb2->get( $idInstagram );
		$instagramuser2 = $facebookResponse2->getGraphUser();
		$media=$instagramuser2['media'];
		$media=$media['0'];
		$media=$media['id'];
		$media.="?fields=comments_count,like_count,comments";

		$facebookResponse2 = $fb2->get( $media );
		$instagramuser3 = $facebookResponse2->getGraphUser(); 
		/*w*/


		

		
		


		
		
		// display everything in the browser
		?>
		<div><b>Logged in as <?php echo $facebookUser['first_name']; ?> <?php echo $facebookUser['last_name']; ?></b></div>
		<div><b>FB User ID: <?php echo $facebookUser['id']; ?></b></div>
		<div><b>Page ID : <?php echo $id2; ?></b></div>
		<div><b>Instagram ID : <?php echo $idInstagram; ?></b></div>
		
		<br />
		<br />
		<hr />
		<br />
		<br />
		<b>User Info</b>
		<textarea style="height:200px;width:100%"><?php echo print_r( $facebookUser, true ); ?></textarea>
		<br />
		<br />
		
		<b>Instagram query</b>
		<textarea style="height:200px;width:100%"><?php  echo print_r($instagramuser2,true); ?></textarea>
		<br />
		<b>Post query</b>
		<textarea style="height:200px;width:100%"><?php  echo print_r($instagramuser3,true); ?></textarea>
		<br />
		
		<br />
		<b>Access Token</b>
		<textarea style="height:200px;width:100%"><?php echo print_r( $accessToken, true ); ?></textarea>
		<br />
		<br />
		<b>Access Token Expires</b>
		<textarea style="height:100px;width:100%"><?php echo print_r( $accessToken->getExpiresAt(), true ); ?></textarea>
		<br />
		<br />
		<b>Access Token Is Valid</b>
		<textarea style="height:50px;width:100%"><?php echo print_r( $accessToken->getIsValid(), true ); ?></textarea>
		<br />
		<br />
		<?php
	} else { // not logged in
		$permissions = ['email,instagram_basic,instagram_manage_insights,instagram_manage_comments,manage_pages,business_management']; // Optional permissions
		$loginUrl = $helper->getLoginUrl( 'https://web-rfnl5hmkocvsi.azurewebsites.net/IGGraph/index.php', $permissions );

		?>
		<a href="<?php echo $loginUrl; ?>">Log in to Instagram Business Account</a>
		<?php
	}
?>