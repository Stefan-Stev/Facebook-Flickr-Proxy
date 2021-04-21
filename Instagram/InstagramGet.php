<?php

require './InstagramUser.php';

class InstagramGet extends InstagramUser {
    public function getPost($postId){
        try {
        $post=$this->fb->get($postId . '?fields=caption,comments_count,like_count,media_type,media_url',$this->accessToken);
        return $post->getDecodedBody();
        }catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    }

    public function getUserInfo($instaID){
        try {
        $post=$this->fb->get($instaID . '?fields=name,username,media_count',$this->accessToken);
        return $post->getDecodedBody();
        }catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    }
    public function getLikesCount($postID){
        try {
        $post=$this->fb->get($postID . '?fields=like_count',$this->accessToken);
        return $post->getDecodedBody();
        }catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    }
    public function getCommentCount($postID){
        try {
        $post=$this->fb->get($postID . '?fields=comments_count',$this->accessToken);
        return $post->getDecodedBody();
        }catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    }
    public function getComments($postID){
        try {
        $post=$this->fb->get($postID . '?fields=comments',$this->accessToken);
        $temp=$post->getDecodedBody();
        $temp=$temp['comments']['data'];
        return $temp;

        return $post->getDecodedBody();
        }catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

    }
};

#EAAQM6NoB0t0BAD6fyvsOyKqAbdMijXAhPiLe7wQZBp4KrFV1f08LTYNptC4Wa6Yqw8ZAJLHlbIl5SpxhsTeab8KJkIlX5jwJ1f6DJ9Bu3ZBhuAbQHwpCPZA3G4ZBJKDzBxZAKhGypAvAbXTe23vs182Yrw1AZBZBguVfgdR46lJ8IFiI6qvVtQe3AfMusXZBK3z4ZD
#$Idinsta='17841403789483121';

#$test = new InstagramGet('EAAQM6NoB0t0BANzVPeyqxhuLBBSIbPusjfrbzg0nMqiqZBPWntwhhwJZCDoDaAWbtkUK5LY8BExZB2zfEQyiJ8ZAEN2dSrRalFRde4rZATtLpcZCv5T0cevf8fPjtpMfMwr8UhVZAWloQD9x2o9E3TJAZCR5jcQYZAUKHoqz5MReqlA3OG2YBO1Pas0JfsZBF8zd2h6GO7Np28XHNI7pypZBvc43gvxHcbgwiRjQ4I57xZCcOQZDZD');
#$test2=$test->getPost('17850222661964436');
#$userinfo=$test->getUserInfo($Idinsta);
#$comments=$test->getComments('17850222661964436');
#$comments=$comments['comments']['data'];
?>
