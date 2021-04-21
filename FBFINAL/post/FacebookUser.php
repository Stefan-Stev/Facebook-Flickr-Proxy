<?php
require_once './GraphAPI.php';
class FacebookUser extends GraphAPI
{
	public $accessToken;
	public $fb;
	function __construct($accessToken) {
		$this->fb = new Facebook\Facebook([
			'app_id' => '1140094136341213',
			'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
			  'default_graph_version' => 'v3.2'
		]);
		$this->accessToken = $accessToken;
	}   
}

?>