<?php

use DPZ\Flickr;

require_once './FlickrPost.php';
require_once './config.php';

function rest($flickrApiKey, $flickrApiSecret)
{
    $callback = 'https://web-rfnl5hmkocvsi.azurewebsites.net/Flickr/auth.php';
    if (isset($_POST['title']) && isset($_FILES['photo']) && isset($_POST['token']) && isset($_POST['tokensecret']) && isset($_POST['requestsecret'])) {
        $flickr = new FlickrPost($flickrApiKey, $flickrApiSecret, $callback);
        $flickr->authenticateWith($_POST['token'], $_POST['tokensecret'], $_POST['requestsecret']);
        $flickr->UploatPhoto();
    } else {
        if (isset($_POST['title']) && isset($_POST['title']) && isset($_POST['URL']) && isset($_POST['token']) && isset($_POST['tokensecret']) && isset($_POST['requestsecret'])) {
            $flickr = new FlickrPost($flickrApiKey, $flickrApiSecret, $callback);
            $flickr->authenticateWith($_POST['token'], $_POST['tokensecret'], $_POST['requestsecret']);
            $url = $_POST['URL'];
            $urls=array();
            $urls = explode(',', $url);
            var_dump($urls);
            echo '<br>';
            echo $url;
            // Image path
            for ($i = 0; $i < count($urls); $i++) {
                $img = 'D:\local\Temp\randomss' . $_POST['title'] . '.jpg';
                unlink($img);
                // Save image 
                $ok = file_put_contents($img, file_get_contents($urls[$i]));
                if (!$ok) {
                    echo 'it failed';
                }
                $_FILES['photo'] = $img;
                var_dump($_FILES['photo']);
                $flickr->UploatPhotowithURL();
                sleep(2);
            }
            /*$img = 'D:\local\Temp\randomss'.$_POST['title'].'.jpg';
            unlink($img);
            // Save image 
            $ok=file_put_contents($img, file_get_contents($urls[0]));
            if(!$ok){
                echo 'it failed';
            }
            $_FILES['photo']=$img;
            var_dump($_FILES['photo']);
            $flickr->UploatPhotowithURL();*/
        }
    }
}
rest($flickrApiKey, $flickrApiSecret);
