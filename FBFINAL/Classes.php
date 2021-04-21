<?php
if (!session_id()) {
	session_start();
}

require_once './vendor/php-graph-sdk/autoload.php';
require_once './QueryManager.php';

class FacebookUser
{
	//access token de la Proiect IP
	public $accessToken;
	public $fb;
	function __construct($accessToken)
	{
		$this->fb = new Facebook\Facebook([
			'app_id' => '1140094136341213',
			'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
			'default_graph_version' => 'v3.2'
		]);
		$this->accessToken = $accessToken;
	}
	function login($userId)
	{
		$obiect = new QueryManager();
		$obiect->getConexiune();
		$sql = "SELECT * FROM facebook_token WHERE user_id = " . $userId;
		$PDOresponse = $obiect->query($sql);
		if ($PDOresponse) {
			header('Location: ' . $_SESSION['redirect']);
		} else {
			$helper = $this->fb->getRedirectLoginHelper();
			$permissions = ['email', 'manage_pages', 'publish_pages'];
			$loginUrl = $helper->getLoginUrl('https://web-rfnl5hmkocvsi.azurewebsites.net/FBFINAL/fb-callback.php', $permissions);
			header('Location: ' . $loginUrl);
		}
	}
};

class FacebookGet extends FacebookUser
{
	public function getAvgLikesTime($beginTime, $endTime,$pageId)
	{
		$beginTime = gmdate("Y-m-d\TH:i:s\+0000", $beginTime);
		$endTime = gmdate("Y-m-d\TH:i:s\+0000", $endTime);

		$var = null; // nu stiu de ce fac asta
		if($pageId==null)
		$response = $this->execute("me?fields=posts.limit(10000){likes, created_time}");
		else
		$response = $this->execute($pageId ."?fields=posts.limit(10000){likes, created_time}");
		$decoded = $response->getDecodedBody();
		if (array_key_exists('data',  $decoded['posts'])) {
			$nrLikes = 0;
			$nrPostari = 0;
			$until = count($decoded['posts']['data']);
			for ($i = 0; $i < $until &&  $decoded['posts']['data'][$i]['created_time'] > $beginTime; $i++) {
				if ($decoded['posts']['data'][$i]['created_time'] < $endTime) {
					$nrLikes = $nrLikes + count($decoded['posts']['data'][$i]['likes']['data']);
					$nrPostari = $nrPostari + 1;
				}
			}
			if ($nrPostari == 0)
				$var->ERROR = "no likes";
			else {
				$var->LIKEAVG = $nrLikes / $nrPostari;
				return $var;
			}
		}

		$var->ERROR = "no likes";
		return $var;
	}

	public function getAvgCommsTime($beginTime, $endTime,$pageId)
	{
		$beginTime = gmdate("Y-m-d\TH:i:s\+0000", $beginTime);
		$endTime = gmdate("Y-m-d\TH:i:s\+0000", $endTime);

		$var = null; // nu stiu de ce fac asta
		if($pageId==null)
		$response = $this->execute("me?fields=posts.limit(10000){comments, created_time}");
		else
		$response = $this->execute($pageId ."?fields=posts.limit(10000){comments, created_time}");
		$decoded = $response->getDecodedBody();

		if (array_key_exists('data',  $decoded['posts'])) {
			$nrComms = 0;
			$nrPostari = 0;
			$until = count($decoded['posts']['data']);
			for ($i = 0; $i < $until &&  $decoded['posts']['data'][$i]['created_time'] > $beginTime; $i++) {
				if ($decoded['posts']['data'][$i]['created_time'] < $endTime) {
					$nrComms = $nrComms +   count($decoded['posts']['data'][$i]['comments']['data']);
					$nrPostari = $nrPostari + 1;
				}
			}
			if ($nrPostari == 0)
				$var->ERROR = "no likes";
			else {
				$var->AVGCOMMS = $nrComms / $nrPostari;
				return $var;
			}
		}

		$var->ERROR = "no likes";
		return $var;
	}

	public function getAvgSharesTime($beginTime, $endTime,$paginaId)
	{

		$beginTime = gmdate("Y-m-d\TH:i:s\+0000", $beginTime);
		$endTime = gmdate("Y-m-d\TH:i:s\+0000", $endTime);

		$var = null; // nu stiu de ce fac asta
		if($paginaId==null)
		$response = $this->execute("me?fields=posts.limit(10000){shares, created_time}");
		else
		$response = $this->execute($paginaId."?fields=posts.limit(10000){shares, created_time}");
		$decoded = $response->getDecodedBody();
		if (array_key_exists('data',  $decoded['posts'])) {
			$nrShares = 0;
			$nrPostari = 0;

			$until = count($decoded['posts']['data']);
			for ($i = 0; $i < $until &&  $decoded['posts']['data'][$i]['created_time'] > $beginTime; $i++) {
				if ($decoded['posts']['data'][$i]['created_time'] < $endTime) {
					$nrShares = $nrShares +  $decoded['posts']['data'][$i]['shares']['count'];
					$nrPostari = $nrPostari + 1;
				}
			}
			if ($nrPostari == 0)
				$var->ERROR = "no likes";
			else {
				$var->AVGSHARES = $nrShares / $nrPostari;
				return $var;
			}
		}
		$var->ERROR = "no likes";
		return $var;
	}
	//for pages
	public function execute($command)
	{

		try {
			$response = $this->fb->get($command, $this->accessToken);
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// echo 'Graph returned an error: ' . $e->getMessage();
			$response['ERROR'] = $e->getMessage();
			// exit;
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// echo 'Facebook SDK returned an error: ' . $e->getMessage();
			$response['ERROR'] = $e->getMessage();
			// exit;
		}
		return $response;
	}
	//pentru numarul de sharuri
	public function getShares($postId)
	{
		$response = $this->execute($postId . "?fields=shares");

		return  $response->getDecodedBody();
	}
	//pentru most  sharuri
	public function getMostShares($idpagina)
	{
		if($idpagina==null)
		$response = $this->execute('me?fields=posts{shares}');
		else
		$response=$this->execute($idpagina . '?fields=posts{shares}');
		return $response->getDecodedBody();
	}
	//pentru hashtaguri
	public function getNumberOfHashTags($idpagina)
	{
		if($idpagina==null)
		$response = $this->execute('me/posts');
		else
		$response=$this->execute($idpagina.'/posts');
		return $response->getDecodedBody();
	}
	public function getWholePost($postId)
	{
		$response = $this->execute($postId . "?fields=message,comments{message,from},likes");

		return $response->getDecodedBody();
	}

	public function getAvgLikes($pageId)
	{
		$var = null; // nu stiu de ce fac asta
		if($pageId==null)
		$response = $this->execute("me/posts?fields=likes{id},id");
		else
		$response = $this->execute($pageId . "/posts?fields=likes{id},id");
		if (array_key_exists('data', $response->getDecodedBody())) {
			$nrLikes = 0;
			$nrPostari = count($response->getDecodedBody()['data']);
			for ($i = 0; $i < $nrPostari; $i++) {
				$nrLikes = $nrLikes +   count($response->getDecodedBody()['data'][$i]['likes']['data']);
			}

			$var->LIKEAVG = $nrLikes / $nrPostari;
		} else {
			$var->ERROR = "no posts";
		}
		return $var;
	}

	public function getAvgComms($pageId)
	{

		$var = null; // nu stiu de ce fac asta
		if($pageId==null)
		$response = $this->execute("me/posts?fields=id,comments{id}");
		else
		$response = $this->execute($pageId ."/posts?fields=id,comments{id}");
		if (array_key_exists('data', $response->getDecodedBody())) {
			$nrComms = 0;
			$nrPostari = count($response->getDecodedBody()['data']);
			for ($i = 0; $i < $nrPostari; $i++) {
				$nrComms = $nrComms +   count($response->getDecodedBody()['data'][$i]['comments']['data']);
			}

			$var->AVGCOMMS = $nrComms / $nrPostari;
		} else {
			$var->ERROR = "no posts";
		}
		return $var;
	}

	public function getAvgShares($pageId)
	{
		$var = null; // nu stiu de ce fac asta
		if($pageId==null)
		$response = $this->execute("me/posts?fields=id,shares");
		else
		$response = $this->execute($pageId ."/posts?fields=id,shares");
		if (array_key_exists('data', $response->getDecodedBody())) {
			$nrShares = 0;
			$nrPostari = count($response->getDecodedBody()['data']);
			for ($i = 0; $i < $nrPostari; $i++) {
				$nrShares = $nrShares + $response->getDecodedBody()['data'][$i]['shares']['count'];
			}
			$var->AVGSHARES = $nrShares / $nrPostari;
		} else {
			$var->ERROR = "no posts";
		}
		return $var;
	}

	public function getCommentCount($postId)
	{ //obsolete
		$response = $this->execute($postId . "?fields=comments");
		return count($response->getDecodedBody()['comments']['data']);
	}

	public function getLikeCount($postId)
	{ //obsolete
		$response = $this->execute($postId . "?fields=likes");
		return count($response->getDecodedBody()['likes']['data']);
	}
	public function getComments($postId)
	{ //obsolete
		$response = $this->execute($postId . "?fields=message,comments{message}");
		return $response->getDecodedBody()['comments']['data'];
	}
	//for user
	public function getName($pageId)
	{
		if ($pageId == null)
			$response = $this->execute("me?fields=name");
		else
			$response = $this->execute($pageId . "?fields=name");

		$err = null;
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		
		$response = $response->getDecodedBody();
		$var = null; // nu stiu de ce fac asta
		$var->NAME = $response['name'];
		return $var;
	}
	public function getPages($userId)
	{
		$response = $this->execute("me/accounts?fields=id");
		$err = null;
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		$response = $response->getDecodedBody()['data'];

		$var = null; // nu stiu de ce fac asta
		$var->COUNT = count($response);
		$var->PAGE_IDS = array();
		for ($i = 0; $i < $var->COUNT; $i++) {
			array_push($var->PAGE_IDS, $response[$i]['id']);
		}
		return $response;
	}
	public function getPagesJson($userId)
	{
		$err = null;
		$response = $this->execute("me/accounts?fields=id");
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		$response = $response->getDecodedBody()['data'];

		$var = null; // nu stiu de ce fac asta
		$var->COUNT = count($response);
		$var->PAGE_IDS = array();
		for ($i = 0; $i < $var->COUNT; $i++) {
			array_push($var->PAGE_IDS, $response[$i]['id']);
		}
		return json_encode($var);
	}
	public function getPageToken($pageId)
	{
		$response = $this->execute($pageId . "?fields=access_token");
		return $response->getDecodedBody();
	}
	public function getPostsArray($pageId)
	{
		$err = null;
		if($pageId==null)
		$response = $this->execute("me?fields=posts.limit(100){likes, comments, message}");
		else
		$response = $this->execute($pageId. "?fields=posts.limit(100){likes, comments, message}");
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		$response = $response->getDecodedBody()['posts']['data'];

		$var = null; // stiu ca nu e nevoie sa fac asta, dar o fac in continuare
		$var->COUNT = count($response);
		$var->POST_IDS = array();
		for ($i = 0; $i < $var->COUNT; $i++) {
			array_push($var->POST_IDS, $response[$i]['id']);
		}


		return json_encode($var);
	}
	public function last3Comments($postId)
	{
		$err = null;
		$response = $this->execute($postId . "?fields=comments.limit(3)");
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		$response =  $response->getDecodedBody()['comments']['data'];

		$var = null;
		$var->COUNT = count($response);

		$var->COMMENTS = array();
		for ($i = 0; $i < $var->COUNT; $i++) {
			$currentComm = $response[$i];
			$currentMessage = $currentComm['message'];
			$currentName = $currentComm['from']['name'];
			array_push($var->COMMENTS, array($currentMessage, $currentName));
		}


		return json_encode($var);
	}
	public function getBestPost($pageId,$pageId2)
	{
		$err = null;
		if($pageId2==null)
		$response = $this->execute("me?fields=posts.limit(100){likes, comments, message}");
		else
		$response = $this->execute($pageId2 ."?fields=posts.limit(100){likes, comments, message}");
		if (array_key_exists('ERROR', $response)) {
			$err->MESSAGE = "Failure";
			$err->ERROR = $response['ERROR'];
			return $err;
		}
		$decoded = $response->getDecodedBody();
		$maxim = -1;
		$bestPost = 0;

		if (array_key_exists('posts', $decoded))
			if (array_key_exists('data', $decoded['posts'])) {
				$postList = $decoded['posts']['data'];
				for ($i = 0; $i < count($postList); $i++) {
					if (array_key_exists('likes', $postList[$i]))
						if (array_key_exists('data', $postList[$i]['likes']))
							if ($maxim < count($postList[$i]['likes']['data'])) {
								$maxim = count($postList[$i]['likes']['data']);
								$bestPost = $postList[$i]['id'];
							}
				}
			}

		$var = null; // deja e redundant
		if ($maxim != -1) {
			$var->COUNT = 1;
			$var->PAGE_IDS = array($bestPost);
		} else {
			$var->MESSAGE = "Failure";
			$var->ERROR = "Did not found any posts on page " . $pageId;
		}
		return json_encode($var);
	}
};

class Post
{
	private $post;
	function __construct($post)
	{
		$this->post = $post;
	}
	public function getText()
	{
		$var = null;
		$var->MESSAGE = null;
		if (array_key_exists('message', $this->post)) {
			$var->MESSAGE = $this->post['message'];
		}

		return json_encode($var);
	}
	public function getComments()
	{
		$var = null;
		$var->COUNT = 0;
		if (array_key_exists('comments', $this->post))
			if (array_key_exists('data', $this->post['comments'])) {

				$var->COUNT = count($this->post['comments']['data']);
				$var->COMMENTS = array();
				for ($i = 0; $i < $var->COUNT; $i++) {
					$currentComm = $this->post['comments']['data'][$i];
					$currentMessage = $currentComm['message'];
					$currentName = $currentComm['from']['name'];
					array_push($var->COMMENTS, array($currentMessage, $currentName));
				}
			}

		return json_encode($var);
	}
	public function getLikes()
	{
		if (array_key_exists('likes', $this->post))
			if (array_key_exists('data', $this->post['likes']))
				return $this->post['likes']['data'];
		return 0;
	}
	public function getNumberShares()
	{

		if (array_key_exists('shares', $this->post))
			if (array_key_exists('count', $this->post['shares']))
				{
					$var = null;
					$var->COUNT = $this->post['shares']['count'];
					return json_encode($var);
				}
		return 0;
	}
	public function MostShares()
	{

		$frecventa = 0;
		$idutilizat = 0;
		if (array_key_exists('posts', $this->post))
			if (array_key_exists('data', $this->post['posts'])) {
				$postList = $this->post['posts']['data'];
				for ($i = 0; $i < count($postList); $i++) {
					$id = $postList[$i]['id'];

					if (array_key_exists('shares', $postList[$i])) {

						if ($frecventa < $postList[$i]['shares']['count']) {
							$frecventa = $postList['shares']['count'];
							$idutilizat = $id;
						}
					}
				}
			}

		$toReturn['mostShared'] = $idutilizat;
		echo json_encode($toReturn);
	}

	public function getTheMostWordTagged()
	{

		$frecventa = array();
		if (array_key_exists('data', $this->post)) {
			$postList = $this->post['data'];

			$count = count($postList);
			for ($i = 0; $i < $count; $i++) {
				if (array_key_exists('message', $postList[$i])) {
					array_push($frecventa, $postList[$i]['message']);
				}
			}
		}
		$contor = 0;
		//filtram doar postarile cu #
		foreach ($frecventa as $word) {
		
			if (strpos($word,'#',0)==0 && ($word[0]!='#')) {
				unset($frecventa[$contor]);
			}
			
			$contor++;
		}
	
		//le punem in array pe cele ce contin #
		$frecventaCuvintelorTaguite=array();
	
		foreach($frecventa as $word)
		{
			
			$pos2=0;
			while(($pos=strpos($word,'#',$pos2))!==false)
			{
				
				if(strpos($word,' ',$pos)!=null)
				$pos2=strpos($word,' ',$pos);
				else
				$pos2=strlen($word)-1;
				
				array_push($frecventaCuvintelorTaguite,substr($word,$pos,$pos2-$pos));
			}
		}

	


		$values = array_count_values($frecventaCuvintelorTaguite);

		arsort($values);
		$popularHashTagged = array_slice(array_keys($values), 0, 1, true);

		$toReturn['popularHashTag'] = $popularHashTagged[0];
		echo json_encode($toReturn);
	}
	public function getCommentCount()
	{

		if (array_key_exists('comments', $this->post))
			if (array_key_exists('data', $this->post['comments']))
				return count($this->post['comments']['data']);
		return 0;
	}

	public function getLikeCount()
	{
		$var = null;
		$var->COUNT = 0;
		if (array_key_exists('likes', $this->post))
			if (array_key_exists('data', $this->post['likes'])) {
				$var->COUNT = count($this->post['likes']['data']);
			}
		return json_encode($var);
	}
};

class FacebookPost extends FacebookUser
{
	public function postareUrl($url, $message, $page_id)
	{
		$data = ['link' => $url, 'message' => $message,];
		try {
			if($page_id!=null){
				$response = $this->fb->post($page_id . '/feed', $data, $this->accessToken);}
				else
				$response = $this->fb->post( 'me/feed', $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}
	}
	public function postareImage($name, $message, $page_id)
	{
		$data = ['message' => $message,  'source' => $this->fb->fileToUpload($name),];
		try {
			if($page_id!=null){
				$response = $this->fb->post($page_id . '/photos', $data, $this->accessToken);}
				else
				$response = $this->fb->post( 'me/photos', $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}
	}
	public function postareMultipla($imagini,$mesaj,$pageId)
	{
		
		$idarray=array();
		foreach($imagini as $imagine)
		{
			try{
				if($pageId==null)
				$response=$this->fb->post('me/photos',array('url'=>$imagine,'published'=>'false','temporary'=>'true'),$this->accessToken);
				else
				$response=$this->fb->post($pageId.'/photos',array('url'=>$imagine,'published'=>'false','temporary'=>'true'),$this->accessToken);
			}catch (FacebookExceptionsFacebookResponseException $e) {
				echo 'Graph returne an error: ' . $e->getMessage();
				exit;
			} catch (FacebookExceptionsFacebookSDKException $e) {
				echo 'facebook SDK return an error: ' . $e->getMessage();
				exit;
			} catch (Exception $e) {
				$toReturn['MESSAGE'] = 'Failure';
				$toReturn['ERROR'] = $e;
				return json_encode($toReturn);
			}
			$graphNode=$response->getDecodedBody()['id'];
			array_push($idarray,$graphNode);

		}
		
		$attached_media='[';
		foreach($idarray as $id)
		{
			$attached_media.='{"media_fbid" : '.$id.'},';
		}
		
		$attached_media.=']';
		
		try
		{
			if($pageId==null)
			{
				
			$response=$this->fb->post('me/feed',array('attached_media' =>$attached_media,'message'=>$mesaj),$this->accessToken);}
			else
			{
			
			$response=$this->fb->post($pageId .'/feed',array('attached_media' =>$attached_media,'message'=>$mesaj),$this->accessToken);
			}
			
		}catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}

		
	}
	public function postareMesaj($mesaj, $page_id)
	{

		$data = ['message' => $mesaj,];
		try {
			if($page_id!=null){
			$response = $this->fb->post($page_id . '/feed', $data, $this->accessToken);}
			else
			$response = $this->fb->post( 'me/feed', $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e->getErrorType();
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}
	}
	public function postareVideo($name, $title, $descriere, $page_id)
	{
		$data = ['title' => $title, 'description' => $descriere, 'source' => $name,];
		try {
			$response = $this->fb->post( 'me/videos', $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}
	}
	public function postareReply($reply, $messageId)
	{

		$data = ['message' => $reply,];

		try {
			$response = $this->fb->post($messageId . '/comments', $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		if (isset($response->getDecodedBody()['id'])) {
			$toReturn['MESSAGE'] = 'Success';
			$toReturn['POST_ID'] = $response->getDecodedBody()['id'];

			return json_encode($toReturn);
		} else {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = 'IT JUST DONT WORK';
			return json_encode($toReturn);
		}
	}
	public function update($mesaj, $messageId)
	{
		$data = ['message' => $mesaj,];
		try {
			$response = $this->fb->post($messageId, $data, $this->accessToken);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returne an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'facebook SDK return an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}

		$toReturn['Message'] = 'Success';
		echo json_encode($toReturn);
	}
	public function delete($postId)
	{
		try {

			$response = $this->fb->delete(
				$postId,
				array(),
				$this->accessToken
			);
		} catch (FacebookExceptionsFacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (FacebookExceptionsFacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		} catch (Exception $e) {
			$toReturn['MESSAGE'] = 'Failure';
			$toReturn['ERROR'] = $e;
			return json_encode($toReturn);
		}


		$toReturn['Message'] = "success";
		echo json_encode($toReturn);
	}
}
