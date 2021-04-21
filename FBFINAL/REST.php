<?php
if (!session_id()) {
	session_start();
}

require_once '.\kitty\JWT.php';

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

function printCurrentUrl()
{


	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$file = 'urls.txt';
	$current = file_get_contents($file);
	$current .= $actual_link . "\n";
	file_put_contents($file, $current);
}

function deliver()
{

	$key = "v[ex+Tw74scKC8VS";
	if (isset($_GET['jwt']) && $_GET['jwt'] != "") {
		$decoded = JWT::decode($_GET['jwt'], $key, array('HS256'));
		$decoded_array = (array) $decoded;
		$_GET['fbid'] = $decoded_array['user_id'];
		if (!(isset($_GET['userId']) && $_GET['userId'] != '')) {
			$_GET['userId'] = $decoded_array['user_id'];
		}
	}
	$err = null;

	if (isset($_GET)) {
		if (isset($_GET['do'])) {
			if (isset($_GET['fbid'])) {
				$obiect = new QueryManager();
				$obiect->getConexiune();
				$sql = "SELECT * FROM facebook_token WHERE user_id = " . $_GET['fbid'];
				$PDOresponse = $obiect->query($sql);
				if (!$PDOresponse) {
					echo "invalid user ID";
					return -1;
				}

				$x = new FacebookGet($PDOresponse['access_token']);

				$obiect = new QueryManager();
				$obiect->getConexiune();
				$sql = "SELECT * FROM facebook_pages WHERE user_id = " . $_GET['fbid'];
				$PDOresponse = $obiect->query($sql);
				if (!$PDOresponse) {
					echo "invalid user ID";
					return -1;
				}
				$y = new FacebookGet($PDOresponse['access_token']);
			}
			switch ($_GET['do']) {
				case 'avgSharesTime':
					$response = $y->getAvgSharesTime($_GET['begin'], $_GET['end'],$_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'avgLikesTime':
					$response = $y->getAvgLikesTime($_GET['begin'], $_GET['end'],$_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'avgCommsTime':
					$response = $y->getAvgCommsTime($_GET['begin'], $_GET['end'],$_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'avgShares':
					$response = $y->getAvgShares($_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'avgLikes':
					$response = $y->getAvgLikes($_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'avgComms':
					$response = $y->getAvgComms($_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'getUserName':
					$response = $x->getName(null);
					echo json_encode($response);
					break;
				case 'getPageName':
					$response = $y->getName($_GET['paginaId']);
					echo json_encode($response);
					break;
				case 'register':
					$x = new FacebookGet($_GET['token']);
					$obiect = new QueryManager();
					$obiect->getConexiune();
					$sql = 'INSERT INTO facebook_token(user_id, access_token) VALUES(' . $_SESSION['userId'] . ', \'' . $_GET['token'] . '\')';
					$obiect->query($sql);
					$pages = $x->getPages('_');
					for ($i = 0; $i < count($pages); $i++) {
						$pageToken = $x->getPageToken($pages[$i]['id']);
						$sql = 'INSERT INTO facebook_pages(user_id, page_id, access_token) VALUES(' . $_SESSION['userId'] . ', ' . $pages[$i]['id'] . ', \'' . $pageToken['access_token'] . '\')';
						$obiect->query($sql);
					}
					header('Location: ' . $_SESSION['redirect']);
					break;
				case 'login':
					$_SESSION['userId'] = $_GET['userId'];
					$_SESSION['redirect'] = $_GET['redirect'];
					$x = new FacebookUser(" ");
					echo $_GET['userId'];
					$x->login($_GET['userId']);
					break;
					//for GET requests DOWN-------------------------------------------------------------
				case 'getComments':
					$response = $y->getWholePost($_GET['postId']);
					if (array_key_exists('ERROR', $response)) {
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					echo $post->getComments();
					break;

				case 'getCommentCount':
					$response = $y->getWholePost($_GET['postId']);
					if (array_key_exists('ERROR', $response)) {
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					echo $post->getCommentCount();
					break;

				case 'getLikeCount':
					$response = $y->getWholePost($_GET['postId']);
					if (array_key_exists('ERROR', $response)) {
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					echo $post->getLikeCount();
					break;

				case 'getText':
					$response = $y->getWholePost($_GET['postId']);
					if (array_key_exists('ERROR', $response)) {
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					echo $post->getText();
					break;

				case 'getPages':
					echo $x->getPagesJson($_GET['userId']);
					break;

				case 'getPostsArray':
					echo $y->getPostsArray($_GET['paginaId']);
					break;
				case 'last3comments':
					echo $y->last3Comments($_GET['postId']);
					break;
				case 'getBestPost':
					
					print_r($y->getBestPost($_GET['userId'],$_GET['paginaId']));
					break;
					///for getting the number of shares
				case 'getNumberShares':

					$response = $y->getShares($_GET['postId']);
					if (array_key_exists('ERROR', $response)) {
						echo 'Eroare';
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}

					$post = new Post($response);
					echo $post->getNumberShares();
					break;
					//for getting the most shared
				case 'MostShared':

					$response = $y->getMostShares($_GET['paginaId']);
					if (array_key_exists('ERROR', $response)) {
						echo 'Eroare';
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					$post->MostShares();
					break;
				case 'MostTagged':
					$response = $y->getNumberOfHashTags($_GET['paginaId']);
					if (array_key_exists('ERROR', $response)) {
						echo 'Eroare';
						$err->MESSAGE = "Failure";
						$err->ERROR = $response['ERROR'];
						return $err;
					}
					$post = new Post($response);
					$post->getTheMostWordTagged();
					break;
					//for POST requests DOWN--------------------------------------------------------
				case 'PostUrl':
					$ob = new FacebookPost($y->accessToken);
					print_r($ob->postareUrl($_GET['url'], $_GET['mesaj'], $_GET['paginaId']));//$PDOresponse['page_id']));
					break;
				case 'PostImage':
					$ob = new FacebookPost($y->accessToken);
					print_r($ob->postareImage($_GET['image'], $_GET['mesaj'], $_GET['paginaId']));
					break;
				case 'MultipleImage':
					$ob=new FacebookPost($y->accessToken);
					$urls=array();
					$delimitare=$_GET['image'];
					$urls=explode("+",$delimitare);
					print_r($ob->postareMultipla($urls,$_GET['mesaj'],$_GET['paginaId']));
				break;
				case 'PostMessage':
					$ob = new FacebookPost($y->accessToken);
					print_r($ob->postareMesaj($_GET['messenger'],($_GET['paginaId'])));
					break;
				case 'PostReply':
					$ob = new FacebookPost($y->accessToken);
					print_r($ob->postareReply($_GET['reply'], $_GET['id_postare']));
					break;
				case 'PostUpdate':

					$ob = new FacebookPost($y->accessToken);
					print_r($ob->update($_GET['update'], $_GET['id_postare']));
					break;
				case 'PostDelete':
					$ob = new FacebookPost($y->accessToken);
					print_r($ob->delete(($_GET['id_postare'])));
					break;
				case 'Video':
					$ob=new FacebookPost(($y->accessToken));
					print_r($ob->postareVideo($_GET['video'],$_GET['title'],$_GET['descriere'],$_GET['paginaId']));
				break;
				default:
					break;
			}
		} else {
			echo "invalid arguments";
		}
	} else {
		echo "invalid request";
	}
}
deliver();
