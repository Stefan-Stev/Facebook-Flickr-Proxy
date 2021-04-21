<?php

$configFile = dirname(__FILE__) . '/config.php';

if (file_exists($configFile))
{
    include $configFile;
}
else
{
    die("Please rename the config-sample.php file to config.php and add your Flickr API key and secret to it\n");
}

spl_autoload_register(function($className)
{
    $className = str_replace ('\\', DIRECTORY_SEPARATOR, $className);
    include (dirname(__FILE__) . '/src/' . $className . '.php');
});

use \DPZ\Flickr;

// Build the URL for the current page and use it for our callback
// $callback = sprintf('http://localhost/');
// echo $callback;
$callback = 'http://localhost/DPZ/auth.php';

$flickr = new Flickr($flickrApiKey, $flickrApiSecret, $callback);

if (!$flickr->authenticate('write'))
{
    die("Hmm, something went wrong...\n");
}

$userNsid = $flickr->getOauthData(Flickr::USER_NSID);
$userName = $flickr->getOauthData(Flickr::USER_NAME);
$userFullName = $flickr->getOauthData(Flickr::USER_FULL_NAME);

$parameters =  array(
    'user_id' => 'me',
    'extras'=>'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
);

$response= $flickr->call('flickr.people.getPhotos', $parameters);
$response2=$flickr->call('flickr.photos.getInfo',array('photo_id'=>'49773772083'));
//print_r($response);
//print_r($response2);
// 49774649702
//$response = $flickr->call('flickr.people.getPhotos', $parameters);
//$response = $flickr->upload(array('C:\Users\Andrei\Desktop\Untitled.png'));
//print_r($response);

/*
if ($ok == 'ok')
{
    $photos = $response['photos'];flickr.photos.getContactsPhotos
}
else
{
    $err = @$response['err'];
    die("Error: " . @$err['msg']);
}*/

function getComments($postId){
    global $flickr;
    $response=$flickr->call('flickr.photos.comments.getList',array('photo_id'=>$postId));
    return $response;
}

function getPhotos($postId){
    $parameters =  array(
    'user_id' => 'me',
    'extras'=>'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
);
    global $flickr;
    $response= $flickr->call('flickr.people.getPhotos', $parameters);
}
//print_r(getComments('49773772083'));

echo $userFullName;


//print_r(getComments('49773772083'));

?>
<!DOCTYPE html>
<html>
    <head>
        <title>DPZFlickr Auth Example</title>
        <link rel="stylesheet" href="example.css" />
    </head>
    <body>
    	
    </body>
</html>
