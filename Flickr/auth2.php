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
$callback = 'https://web-rfnl5hmkocvsi.azurewebsites.net/Flickr/auth.php';

$flickr = new Flickr($flickrApiKey, $flickrApiSecret, $callback);
$ok=$flickr->authenticate('write');
if (!$ok)
{
    die("Hmm, something went wrong...\n");
}
else{
    $usertoken=$flickr->getOauthData(FLickr::OAUTH_ACCESS_TOKEN);
$userTokenSecret=$flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET);
$userRequest=$flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET);
echo $usertoken; echo '<br>';
echo $userTokenSecret;echo '<br>';
echo $userRequest; echo '<br>';

}
$userNsid = $flickr->getOauthData(Flickr::USER_NSID);
$userName = $flickr->getOauthData(Flickr::USER_NAME);
$userFullName = $flickr->getOauthData(Flickr::USER_FULL_NAME);
$usertoken=$flickr->getOauthData(FLickr::OAUTH_ACCESS_TOKEN);
$userTokenSecret=$flickr->getOauthData(Flickr::OAUTH_ACCESS_TOKEN_SECRET);
$userRequest=$flickr->getOauthData(Flickr::OAUTH_REQUEST_TOKEN_SECRET);
$parameters =  array(
    'per_page' => 100,
    'extras' => 'url_sq,path_alias',
);

$message='';
/*if (!empty($_FILES['photo']))
{
    $title = @$_POST['title'];

    $parameters = array(
        'title' => $title,
        'tags' => 'DPZFlickr'
    );

    $photo = $_FILES['photo'];

    if ($photo['size'] > 0)
    {
        $parameters['photo'] = '@' . $photo['tmp_name'];
    }

    $response = $flickr->upload($parameters);

    $ok = @$response['stat'];
    var_dump($response);
    var_dump($photo);

    if ($ok == 'ok')
    {
        $photos = $response['photos'];
        $message = "Photo uploaded";
    }
    else
    {
        $err = @$response['err'];
        $message = "Error: " . @$err['msg'];
    }
}
*/


?>
<!DOCTYPE html>
<html>
    <head>
        <title>DPZFlickr Auth Example</title>
        <link rel="stylesheet" href="example.css" />
    </head>
    <body>
     <?php print_r($usertoken); ?><br>
     <?php print_r($userTokenSecret); ?><br>
     <?php print_r($userRequest); ?><br>
    </body>
</html>

