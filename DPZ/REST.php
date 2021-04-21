<?php
if (!session_id()) {
	session_start();
}

require './src2/JWT.php';
spl_autoload_register(function($className)
{
    $className = str_replace ('\\', DIRECTORY_SEPARATOR, $className);
    include (dirname(__FILE__) . '/src2/' . $className . '.php');
});
use \Firebase\JWT\JWT;


$allowedOrigins = array(
	'https?:\/\/localhost.*',
	'https?:\/\/.*.github.io.*'
);

if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {

	foreach ($allowedOrigins as $allowedOrigin) {
		$current = file_get_contents($file);
		$current .= $allowedOrigin . "\n";
		file_put_contents($file, $current);
		if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
			header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			header('Access-Control-Max-Age: 1000');
			header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
			header('Access-Control-Allow-Credentials: true');

			break;
		}
	}
}

require_once("./Classes.php");
require_once("./QueryManager.php");
$configFile = dirname(__FILE__) . '/config.php';

if (file_exists($configFile)) {
	include $configFile;
} else {
	die("Please rename the config-sample.php file to config.php and add your Flickr API key and secret to it\n");
}

spl_autoload_register(function ($className) {
	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
	include(dirname(__FILE__) . '/src/' . $className . '.php');
});

use \DPZ\Flickr;


function logUrl() {
	$file = 'logs.txt';
	$current = file_get_contents($file);
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$current .= $actual_link . "\n";
	file_put_contents($file, $current);
}



function deliver()
{

	if (isset($_GET)) { //GET METHOD
		if (isset($_GET['do'])) {
			if (isset($_GET['userid']) && $_GET['userid']!='') {
				$obiect = new QueryManager();
				$obiect->getConexiune();
				$sql = "SELECT * FROM flickr_token WHERE user_id = " . $_GET['userid'];
				
				$PDOresponse = $obiect->query($sql);
				if (!$PDOresponse) {
					echo "invalid user ID (userid)";
					return -1;
				}
				$flickr = new FlickrGet($PDOresponse['access_token'], $_GET['oauth_token_secret'], $_GET['oauth_request_secret']);
			}
			//JWTTokens
				if(isset($_GET['token']) && $_GET['do']!='login' && $_GET['token']!=''){
					$newKey="v[ex+Tw74scKC8VS";
					$JWTtoken=$_GET['token'];
					$decoded=JWT::decode($JWTtoken,$newKey,array('HS256')); 
					
					$obiect = new QueryManager();
				$obiect->getConexiune();
				$sql = "SELECT * FROM flickr_token WHERE user_id = " . $decoded->user_id;
				
				$PDOresponse = $obiect->query($sql);
				if (!$PDOresponse) {
					echo "invalid user ID (token)";
					return -1;
				}
				
				$flickr = new FlickrGet($PDOresponse['access_token'], $_GET['oauth_token_secret'], $_GET['oauth_request_secret']);

				}
				

			
			switch ($_GET['do']) {
				case 'getComments':
				$response = $flickr->getComments($_GET['postId']);
				$ok = @$response['stat'];
					//if($ok != 'ok'){
					//$err = @$response['err'];
					//die("Error: " . @$err['msg']);
					//}
				print_r($response);
				break;

				case 'last3comments':
				$response = $flickr->getLast3Comments($_GET['postId']);
				print_r($response);
				break;

				case 'getViewCount':
				$response = $flickr->getViewCount($_GET['postId']);
				print_r($response);
				break;

				case 'getFaveCount':
				$response = $flickr->getFaveCount($_GET['postId']);
				print_r($response);
				break;

				case 'getCommentCount':
				$response = $flickr->getCommentCount($_GET['postId']);
				print_r($response);
				break;

				case 'getPhotosArray':
				$response = $flickr->getPhotos();
				print_r($response);
				break;

				case 'getBestPost':
				$response = $flickr->getBestPost();
				print_r($response);
				break;

				case 'getAccountName':
				$response = $flickr->getAccountName($_GET['userid']);
				print_r($response);
				break;
				case 'getAverageFaves':
				$response = $flickr->getAverageFaves();
				print_r($response);
				break;

				case 'getAverageComments':
				$response = $flickr->getAverageComments();
				print_r($response);
				break;

				case 'getMostCommonTag':
				$response = $flickr->getMostCommonTag();
				print_r($response);
				break;

				case 'getAverageFavesBetween':
				$response = $flickr->getAverageFavesBetween($_GET['timestamp1'],$_GET['timestamp2']);
				print_r($response);
				break;

				case 'getAverageCommentsBetween':
				$response = $flickr->getAverageCommentsBetween($_GET['timestamp1'],$_GET['timestamp2']);
				print_r($response);
				break;

				case 'PostImage':
					$flickrpost = new FlickrPost($PDOresponse['access_token'], $PDOresponse['access_token_secret'], $PDOresponse['request_token_secret']);
					$url=$_GET['image'];
					$urls=explode(',',$url);
					if(count($urls)==1)
						print_r($flickrpost->UploatPhotowithURL());
					else
					print_r($flickrpost->UploatPhotoswithURL($urls));
					

					//print_r($flickrpost->UploatPhotowithURL());
				break;

				case 'register':

				$configFile = dirname(__FILE__) . '/config.php';

				if (file_exists($configFile)) {
					include $configFile;
				} else {
					die("Please rename the config-sample.php file to config.php and add your Flickr API key and secret to it\n");
				}

				spl_autoload_register(function ($className) {
					$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
					include(dirname(__FILE__) . '/src/' . $className . '.php');
				});
				echo '2';
				$callback = 'https://web-rfnl5hmkocvsi.azurewebsites.net/DPZ/REST.php?do=register';
				$flickr = new Flickr($flickrApiKey, $flickrApiSecret, $callback);
				$ok=$flickr->authenticate('write');
				if (!($ok)) {
					echo '3';
					die("Hmm, something went wrong...\n");
				} else {
					echo '4';
				}

				$requestokenSecret = $flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET);
				$tokenSecret = $flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET);
				if ($ok) { // in callback
					$flickrGet = new FlickrGet($_GET['oauth_token'], '', '');
					$flickrGet->replace($flickr);

					$obiect = new QueryManager();
					$obiect->getConexiune();

					$sql = 'INSERT INTO flickr_token(user_id, access_token, access_token_secret, request_token_secret) VALUES(' . $_SESSION['userId'] . ', \'' . $flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN) . '\', \'' . $tokenSecret . '\', \'' . $requestokenSecret . '\')';
					$obiect->query($sql);
					/*echo $sql;
					echo $requestokenSecret;
					echo '<br>';
					echo $tokenSecret;
					echo 'am ajuns aici'.' ';*/
					//echo $_SESSION['userId'].' '.$_SESSION['redirect'].' '.$_GET['oauth_token'];
					//echo $_SESSION['redirect'];
					//header('Location: ' . $_SESSION['redirect']);

				}
				break;

				case 'login':
				$_SESSION['userId'] = $_GET['userId'];
				$_SESSION['redirect'] = $_GET['redirect'];
				$_SESSION['token']=$_GET['token'];
				$flickr = new FlickrUser(' ', ' ', ' ');
				echo '1';
				$flickr->login($_GET['userId']);
				break;
				default:
				break;
			}
		}
	}
	if (isset($_POST)) {
		if (isset($_POST['userid'])) {
			echo 'In post';
			$obiect = new QueryManager();
			$obiect->getConexiune();
			$sql = "SELECT * FROM flickr_token WHERE user_id = " . $_POST['userid'];
			$PDOresponse = $obiect->query($sql);
			if (!$PDOresponse) {
				echo "invalid user ID (post)";
				return -1;
			}
			$flickrpost = new FlickrPost($PDOresponse['access_token'], $PDOresponse['access_token_secret'], $PDOresponse['request_token_secret']);
			var_dump($flickrpost);
			echo '<br>';
			var_dump($_SESSION);
			echo '<br>';
			var_dump($PDOresponse);

		} else {
			return -1;
		}

		switch ($_POST['do']) {
			case 'PostImage1':
			
			$flickrpost->UploatPhoto();
			echo 'Worked?';
			break;
			case 'PostImageFromUrl1':
			$flickrpost->UploatPhotowithURL();
			echo'in URL';
			break;
		}
	} else {
		echo 'not POST or GET ?';
	}

	$obiect = new QueryManager();
	$obiect->getConexiune();
}
deliver();
