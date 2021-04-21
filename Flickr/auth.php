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

if (!$flickr->authenticate('write'))
{
    die("Hmm, something went wrong...\n");
}

$userNsid = $flickr->getOauthData(Flickr::USER_NSID);
$userName = $flickr->getOauthData(Flickr::USER_NAME);
$userFullName = $flickr->getOauthData(Flickr::USER_FULL_NAME);
$parameters =  array(
    'per_page' => 100,
    'extras' => 'url_sq,path_alias',
);

$message='';
if (!empty($_FILES['photo']))
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



?>
<!DOCTYPE html>
<html>
    <head>
        <title>DPZFlickr Auth Example</title>
        <link rel="stylesheet" href="example.css" />
    </head>
    <body>
        <h1>Popular photos from <?php echo $userName ?></h1>
        <form id="upload" method="POST" enctype="multipart/form-data">
    <label for="title">Title</label>
    <input id="title" name="title" type="text" size="50">

    <label for="photo">Attach a photo</label>
    <input id="photo" name="photo" type="file">

    <input id="upload-button" class="submit" type="submit" value="Upload photo">
</form>
    </body>
</html>

