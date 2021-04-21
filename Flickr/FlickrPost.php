<?php

use DPZ\Flickr;

require_once './src/DPZ/Flickr.php';

class FlickrPost extends Flickr
{
    public function UploatPhoto()
    {
        echo'<pre>';
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
            var_dump($parameters);
            $response = $this->upload($parameters);

            $ok = @$response['stat'];
            
           var_dump($response);
           var_dump($photo);

            if ($ok == 'ok') {
                $photos = $response['photos'];
                $message = "Photo uploaded";
            } else {
                $err = @$response['err'];
                $message = "Error: " . @$err['msg'];
            }
        }
        else{
            echo 'No files?';
        }
        echo '</pre>';
    }
    public function UploatPhotowithURL()
    {
        echo'<pre>';
        if (!empty($_FILES['photo'])) {
            $title = @$_POST['title'];
            
            $parameters = array(
                'title' => $title,
                'tags' => 'DPZFlickr'
            );

            $photo = $_FILES['photo'];

            
                $parameters['photo'] = '@' . $photo;
            
            var_dump($parameters);
            $response = $this->upload($parameters);

            $ok = @$response['stat'];
            
           var_dump($response);
           var_dump($photo);

            if ($ok == 'ok') {
                $photos = $response['photos'];
                $message = "Photo uploaded";
            } else {
                $err = @$response['err'];
                $message = "Error: " . @$err['msg'];
            }
        }
        else{
            echo 'No files?';
        }
        echo '</pre>';
    }
}
