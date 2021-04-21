<?php
require_once "C:/xampp\htdocs\ip\Refactor/fb\FacebookUser.php";
class FacebookGet extends FacebookUser
{
    //for pages
    public function execute($command)
    {
        try {
            $response = $this->fb->get($command, $this->accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $response;
    }

    public function getWholePost($postId)
    {
        $response = $this->execute($postId . "?fields=comments{message,from},likes");
        return $response;
    }
    public function getCommentCount($postId)
    { //deprecated
        $response = $this->execute($postId . "?fields=comments");
        return count($response->getDecodedBody()['comments']['data']);
    }

    public function getLikeCount($postId)
    {//deprecated
        $response = $this->execute($postId . "?fields=likes");
        return count($response->getDecodedBody()['likes']['data']);
    }
    public function getComments($postId)
    {//deprecated
        $response = $this->execute($postId . "?fields=message,comments{message}");
        return $response->getDecodedBody()['comments']['data'];
    }
    //for user
    public function getPages($userId)
    {
        $response = $this->execute($userId . "/accounts?fields=id");
        return $response->getDecodedBody()['data'];
    }
    public function getPageToken($pageId)
    {
        $response = $this->execute($pageId . "?fields=access_token");
        return $response->getDecodedBody();
    }
    public function getPostsArray($pageId)
    {
        $response = $this->execute($pageId . "?fields=posts");
        return $response->getDecodedBody()['posts']['data'];
    }
};

class Post
{
    private $post;
    function __construct($post)
    {
        $this->post = $post;
    }
    public function getComments()
    {
        return $this->post['comments']['data'];
    }
    public function getLikes()
    {
        return $this->post['likes']['data'];
    }
    public function getCommentCount()
    {
        return count($this->post['comments']['data']);
    }
    public function getLikeCount()
    {
        return count($this->post['likes']['data']);
    }
};
/*
echo "<pre>";


$utilizator = "2624476484345351"; // id-ul utilizatorului
$x = new FacebookGet("EAAQM6NoB0t0BAJF2lXODziDeIeqe7qoFGujf5CAhUn7M9EdMDBstoZAQeZCbadZCBEbXdZBrB3AZAjZAoipRndP8OwIX4ZC1kMZBjhggZBNKh8ZBf5ERVLM62xnnUMxQjOF4SHOmhnwOOkvFZAKp8fYb7uss4p2ezSzT9MIgz97Ed8w0hmWO8S1bGlZA34Oe5UamJdOin2ZAJBw0Q2xyZBIdEvbTwHWNEuu03p0JCnSTgH7vjAZCs3foSrAVLuaJZAj5uqstUysZD"); // insert user token here ----------------<<<<<<<<<<<<<<<<<________________________
echo "The id of the first page of the user: " .  $pageId = $x->getPages($utilizator)[0]['id']; //prima [x] reprezinta a cata pagina a utilizatorului o accesam
echo "<br> The access_token of the page: " . $pageToken = $x->getPageToken($pageId)['access_token'];
$y = $x->getPostsArray($pageId);
for ($i=0; $i < count($y); $i++) { 
    echo "post " . $i . ": " . $y[$i]['id'] . "<br>";//prints ID for each post 
}
$postare = '112510383726603_119708059673502'; // id-ul postarii
$x = new FacebookGet("$pageToken");  //insert page token here
echo 'pentru postarea: ' . $postare . '<br>'; 
echo "Nr comentarii: " . $x->getCommentCount($postare) . "<br>";
echo "Nr like: " . $x->getLikeCount($postare);



$post = new Post($x->getWholePost($postare));
echo "comments------------------------------------";
print_r($post->getComments());
echo "likes ----------------------------";
print_r($post->getLikes());


echo "</pre>";
*/
