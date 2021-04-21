<?php

require_once './vendor/php-graph-sdk/autoload.php';

class InstagramUser {
    public $accessToken;
    public $fb;
    public $appCreds = array( // array to hold app creds from fb app
		'app_id' => '1140094136341213',
		'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
		'default_graph_version' => 'v6.0'
	);
    function __construct($accessToken) {
         $this->appCreds = array( // array to hold app creds from fb app
            'app_id' => '1140094136341213',
            'app_secret' => 'f3ccb98dc558350d9dc80914ac413655',
            'default_graph_version' => 'v6.0'
        );
        $this->appCreds['default_access_token'] = $accessToken;
        
		$this->fb = new Facebook\Facebook($this->appCreds);
		$this->accessToken = $accessToken;
    }

}