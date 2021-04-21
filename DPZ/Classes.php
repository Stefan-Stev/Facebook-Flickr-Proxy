<?php

if (!session_id()) {
	session_start();
}


use \DPZ\Flickr;

require_once './QueryManager.php';


class FlickrUser
{
	public $accessToken;
	public $flickr;


	function __construct($oauth_token, $oauth_token_secret, $oauth_request_secret)
	{
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
		$this->flickr = new Flickr($flickrApiKey, $flickrApiSecret, '');
		$this->accessToken = $oauth_token;
		$this->flickr->setOauthData(Flickr::OAUTH_ACCESS_TOKEN, $oauth_token);
		$this->flickr->setOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET, $oauth_token_secret);
		$this->flickr->setOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET, $oauth_request_secret);
	}

	function replace($flickrnew)
	{
		$this->flickr = $flickrnew;
	}



	function login($userId)
	{
		$obiect = new QueryManager();
		$obiect->getConexiune();
		$sql = "SELECT * FROM flickr_token WHERE user_id = " . $userId;
		$PDOresponse = $obiect->query($sql);
		if ($PDOresponse) {
			header('Location: ' . $_SESSION['redirect']);
		} else {/*
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
			$this->flickr = new Flickr($flickrApiKey, $flickrApiSecret, $callback);
			if (!($this->flickr->authenticate('write'))) {
				echo '3';
				die("Hmm, something went wrong...\n");
			} else {
				echo '4';
				/*$obiect = new QueryManager();
					$obiect->getConexiune();

					$sql = 'INSERT INTO flickr_token(user_id, access_token, access_token_secret, request_token_secret) VALUES(' . $_SESSION['userId'] . ', \'' . $this->flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN) . ', \'' . $this->flickr->getOauthData(flickr::OAUTH_ACCESS_TOKEN_SECRET) . ', \'' . $this->flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET) . '\')';
					$obiect->query($sql);
					echo ' After 4:';
					echo $this->flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN);
					echo $this->flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET);
					echo $this->flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET);
					echo $sql;
					empty flickr??? 
				}

				*/
			$this->flickr->signout();
			$callback = 'https://web-rfnl5hmkocvsi.azurewebsites.net/DPZ/auth2.php';
			header('Location: ' . $callback);
		}
	}
}

class FlickrGet extends FlickrUser
{


	public function getAccountName($userId)
	{
		$obiect = new QueryManager();
		$obiect->getConexiune();
		$sql = "SELECT fullname FROM flickr_token WHERE user_id = " . $userId;
		$PDOresponse = $obiect->query($sql);
		return json_encode(array('FULLNAME' => $PDOresponse['fullname']));
	}

	public function getComments($postId)
	{
		$response = $this->flickr->call('flickr.photos.comments.getList', array('photo_id' => $postId));
		$comments = $response['comments']['comment'];
		$total = sizeof($comments);
		$commentsArray = array();
		foreach ($comments as $comment) {
			$commentsArray[] = array('AUTHOR' => $comment['realname'], 'AUTHORID' => $comment['author'], 'COMMENT' => $comment['_content']);
		}
		$response = array("COUNT" => $total, "COMMENTS" => $commentsArray);
		return json_encode($response);
	}

	public function getPhotos()
	{
		$parameters =  array(
			'user_id' => 'me',
			'extras' => 'description, date_upload, date_taken, owner_name, last_update, geo, tags, views, url_l, count_faves,realname'
		);
		$response = $this->flickr->call('flickr.people.getPhotos', $parameters);
		$total = $response['photos']['total'];
		$photos = $response['photos']['photo'];
		$photosIds = array();
		foreach ($photos as $photo)
			$photosIds[] = $photo['id'];
		$response = array('COUNT' => $total, 'POST_IDS' => $photosIds);
		return json_encode($response);
	}

	public function getLast3Comments($postId)
	{
		$response = json_decode($this->getComments($postId));
		$comments = $response->COMMENTS;
		$n = $response->COUNT;
		$last3Comments = array($comments[$n - 1], $comments[$n - 2], $comments[$n - 3]);
		return json_encode($last3Comments);
	}

	public function getViewCount($postId)
	{
		$response = $this->flickr->call('flickr.photos.getInfo', array('photo_id' => $postId));
		$viewCount = array('VIEWSCOUNT' => $response['photo']['views']);
		return json_encode($viewCount);
	}

	public function getFaveCount($postId)
	{
		$response = $this->flickr->call('flickr.photos.getInfo', array('photo_id' => $postId, 'extras' => 'count_faves'));
		$faveCount = array('FAVECOUNT' => $response['photo']['count_faves']);
		return json_encode($faveCount);
	}

	public function getCommentCount($postId)
	{
		$response = $this->flickr->call('flickr.photos.getInfo', array('photo_id' => $postId));
		$commentCount = array('commentCount' => $response['photo']['comments']['_content']);
		return json_encode($commentCount);
	}

	public function getBestPost()
	{
		$parameters =  array(
			'user_id' => 'me',
			'extras' => 'count_faves'
		);
		$response = $this->flickr->call('flickr.people.getPhotos', $parameters);
		$total = $response['photos']['total'];
		$photos = $response['photos']['photo'];

		$max = -1;
		$id = 'user has no photos';
		for ($i = 0; $i < count($photos); $i++) {
			if ($photos[$i]['count_faves'] > $max) {
				$max = $photos[$i]['count_faves'];
				$id = $photos[$i]['id'];
			}
		}

		$result = null;
		$result->POST_ID = $id;
		$result->FAVES = $max;


		return json_encode($result);


		$max = -1;
		$id = 'user has no photos';
		$response = json_decode($this->getPhotos());
		$photos = $response->POST_IDS;
		foreach ($photos as $photoid) {
			$faveCount = json_decode($this->getFaveCount($photoid));
			$faveCount = $faveCount->FAVECOUNT;
			if ($faveCount > $max) {
				$max = $faveCount;
				$id = $photoid;
			}
		}
		$bestPost = array('POST_ID' => $id, 'FAVES' => $max);
		return json_encode($bestPost);


		return json_encode($result);
	}

	public function getAverageFaves()
	{
		$photos = json_decode($this->getPhotos(''));
		$photosCount = $photos->COUNT;
		$photoIds = $photos->POST_IDS;
		$favesCount = 0;
		foreach ($photoIds as $id) {
			$favesCount += json_decode($this->getFaveCount($id))->FAVECOUNT;
		}
		$averageFaves = array('AVERAGEFAVES' => $favesCount / $photosCount, 'TOTAL' => $favesCount);
		return json_encode($averageFaves);
	}

	public function getAverageComments()
	{
		$photos = json_decode($this->getPhotos(''));
		$photoIds = $photos->POST_IDS;
		$photoCount = $photos->COUNT;
		$commentCount = 0;
		foreach ($photoIds as $id) {
			$commentCount += json_decode($this->getComments($id))->COUNT;
		}
		$averageComments = array('AVERAGECOMMENTS' => $commentCount / $photoCount, 'TOTAL' => $commentCount);
		return json_encode($averageComments);
	}

	public function getMostCommonTag()
	{
		$photos = json_decode($this->getPhotos());
		$photoIds = $photos->POST_IDS;
		$tags = array();
		$mostCommonTag = '';
		$maxAppearances = 0;
		foreach ($photoIds as $id) {
			$photo = $this->flickr->call('flickr.photos.getInfo', array('photo_id' => $id));
			foreach ($photo['photo']['tags']['tag'] as $tag) {
				if (isset($tags[$tag['raw']])) {
					$tags[$tag['raw']]++;
					if ($tags[$tag['raw']] > $maxAppearances) {
						$mostCommonTag = $tag['raw'];
						$maxAppearances = $tags[$tag['raw']];
					}
				} else {
					$tags[$tag['raw']] = 1;
					if ($tags[$tag['raw']] > $maxAppearances) {
						$mostCommonTag = $tag['raw'];
						$maxAppearances = $tags[$tag['raw']];
					}
				}
			}
		}
		return json_encode(array('MOSTCOMMONTAG' => $mostCommonTag, 'APPEARANCES' => $maxAppearances));
	}

	public function getPhotoTimestamp($id)
	{
		$photo = $this->flickr->call('flickr.photos.getInfo', array('photo_id' => $id));
		$timestamp = $photo['photo']['dateuploaded'];
		return $timestamp;
	}



	public function getAverageFavesBetween($timestamp1, $timestamp2)
	{
		$photos = json_decode($this->getPhotos());
		$photoIds = $photos->POST_IDS;
		$photoCount = 0;
		$faveCount = 0;
		$ok = 0;
		foreach ($photoIds as $id) {
			$photoTimestamp = $this->getPhotoTimestamp($id);
			if ($photoTimestamp < $timestamp2 && $photoTimestamp > $timestamp1) {
				$faveCount += json_decode($this->getFaveCount($id))->FAVECOUNT;
				$photoCount++;
				if ($photoTimestamp > $timestamp2)
					break;
			}
		}
		if ($photoCount == 0)
			return json_encode(array('ERROR' => 'User has no photos between that period of time'));
		$averageFaves = array('AVERAGEFAVES' => $faveCount / $photoCount, 'TOTAL' => $faveCount);
		return json_encode($averageFaves);
	}




	function getAverageCommentsBetween($timestamp1, $timestamp2)
	{
		$photos = json_decode($this->getPhotos());
		$photoIds = $photos->POST_IDS;
		$photoCount = 0;
		$commentCount = 0;
		$ok = 0;
		foreach ($photoIds as $id) {
			$photoTimestamp = $this->getPhotoTimestamp($id);
			if ($photoTimestamp < $timestamp2 && $photoTimestamp > $timestamp1) {
				$commentCount += json_decode($this->getComments($id))->COUNT;
				$photoCount++;
				if ($photoTimestamp > $timestamp2)
					break;
			}
		}
		if ($photoCount == 0)
			return json_encode(array('ERROR' => 'User has no photos between that period of time'));
		$averageComments = array('AVERAGECOMMENTS' => $commentCount / $photoCount, 'TOTAL' => $commentCount);
		return json_encode($averageComments);
	}
}

class FlickrPost extends FlickrUser
{
	public function UploatPhoto()
	{
		echo '<pre>';
		if (!empty($_FILES['photo'])) {
			$title = @$_POST['title'];

			$parameters = array(
				'title' => $title,
				'tags' => 'DPZFlickr'
			);

			$photo = $_FILES['photo'];

			if ($photo['size'] > 0) {
				$parameters['photo'] = '@' . $photo['tmp_name'];
			}

			$response = $this->flickr->upload($parameters);

			$ok = @$response['stat'];



			if ($ok == 'ok') {
				$photos = $response['photos'];
				$message = "Photo uploaded";
			} else {
				$err = @$response['err'];
				$message = "Error: " . @$err['msg'];
			}
		} else {
			echo 'No files?';
		}
		echo '</pre>';
	}
	public function UploatPhotowithURL()
	{
		$returningJson = array();

		$url = $_GET['image'];

		$img = 'D:\local\Temp\randomss' . $_GET['title'] . '.jpg';
		unlink($img);

		$ok = file_put_contents($img, file_get_contents($url));
		if (!$ok) {
			$returningJson['MESSAGE'] = 'FAILURE';
			$returningJson['ERROR'] = 'BAD URL';
			return json_encode($returningJson);
		}
		$_FILES['photo'] = $img;

		if (!empty($_FILES['photo'])) {
			$title = @$_GET['message'];

			$parameters = array(
				'title' => $title,
				'tags' => 'DPZFlickr'
			);

			$photo = $_FILES['photo'];


			$parameters['photo'] = '@' . $photo;

			// var_dump($parameters);
			$response = $this->flickr->upload($parameters);

			$ok = @$response['stat'];


			if ($ok == 'ok') {
				$returningJson['MESSAGE'] = 'Success';
				$returningJson['POST_ID'] = $response['photoid']['_content'];
			} else {
				$err = @$response['err'];
				$returningJson['MESSAGE'] = 'Failure';
				$returningJson['ERROR'] = $response['err']['msg'];
			}
			##var_dump( $returningJson);
			return json_encode($returningJson);
		}
	}
	public function UploatPhotoswithURL($urls)
	{
		$returningJson = array();
		for ($i = 0; $i < count($urls); $i++) {
			$url = $urls[$i];


			//$url = $_GET['image'];

			$img = 'D:\local\Temp\randomss' . $_GET['title'] . '.jpg';
			unlink($img);

			$ok = file_put_contents($img, file_get_contents($url));
			if (!$ok) {
				$returningJson['MESSAGE'] = 'FAILURE';
				$returningJson['ERROR'] = 'BAD URL ' . $i;
				return json_encode($returningJson);
			}
			$_FILES['photo'] = $img;

			if (!empty($_FILES['photo'])) {
				$title = @$_GET['message'];

				$parameters = array(
					'title' => $title,
					'tags' => 'DPZFlickr'
				);

				$photo = $_FILES['photo'];


				$parameters['photo'] = '@' . $photo;

				// var_dump($parameters);
				$response = $this->flickr->upload($parameters);

				$ok = @$response['stat'];


				if ($ok == 'ok') {
					$returningJson['MESSAGE'] = 'Success';
					$returningJson['POST_ID' . $i] = $response['photoid']['_content'];
				} else {
					$err = @$response['err'];
					$returningJson['MESSAGE'] = 'Failure';
					$returningJson['ERROR ' . $i] = $response['err']['msg'];
				}
				##var_dump( $returningJson);

			}
		}
		return json_encode($returningJson);
	}
}
