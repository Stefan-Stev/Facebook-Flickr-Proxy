<?php
require_once '../clase/FacebookUser.php';
class FacebookPost extends FacebookUser
{
    public function postareUrl($url,$message)
    {
        echo $message . '   ' . $url;
        $data = ['link' => $url, 'message' => $message,];
        try {
            $response = $this->fb->post('/112510383726603/feed', $data,$this->accessToken);
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returne an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'facebook SDK return an error: ' . $e->getMessage();
            exit;
        }
        $graphNode=$response->getGraphNode();
        // var_dump($graphNode);
        echo json_encode($graphNode);
    }
    public function postareImage($name,$message)
    {
        echo 'da' .$name;
                $data=['message' => $message ,  'source' => $this->fb->fileToUpload($name), ];
        try {
            $response = $this->fb->post('/112510383726603/photos', $data, $this->accessToken);
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returne an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'facebook SDK return an error: ' . $e->getMessage();
            exit;
        }
        $graphNode=$response->getGraphNode();
        //var_dump($graphNode);
        echo json_encode($graphNode);
    }
    public function postareMesaj($mesaj)
    {
        $data=['message'=>$mesaj,];
        try {
            $response = $this->fb->post('/112510383726603/feed', $data, $this->accessToken);
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returne an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'facebook SDK return an error: ' . $e->getMessage();
            exit;
        }
        $graphNode=$response->getGraphNode();
        //var_dump($graphNode);
        echo json_encode($graphNode);
    }
    public function postareVideo($name,$title,$descriere)
    {
        $data=['title'=>$title,'description'=>$descriere ,'source' =>$this->fb->videoToUpload($name),];
        try {
            $response = $this->fb->post('/112510383726603/videos', $data, $this->accessToken);
        } catch (FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returne an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            echo 'facebook SDK return an error: ' . $e->getMessage();
            exit;
        }
        $graphNode=$response->getGraphNode();
        //var_dump($graphNode);
        echo json_encode($graphNode);
    }
    
}

//$cartier =new FacebookPost();
//$cartier->postareUrl('https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DZa7K1flbsx4&psig=AOvVaw2AzWIaZktA6PnB93bwrV9q&ust=1586079882856000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCOCErfmVzugCFQAAAAAdAAAAABAD',$message);
if(isset($_GET['submit'])){
switch($_GET['submit']){
case 'Url':
if(isset($_GET['url']) &&  isset($_GET['mesaj']))
{
$url=$_GET['url'];
$mesaj=$_GET['mesaj'];
$obiect=new FacebookPost('EAAQM6NoB0t0BABnukB3CrOFpxLyPra6r6QoHuKbbMnETb1egnrZCi8w7X7Ubk4ZCbUX0BxZA451Fjbk5LY4LNiTgZBtLyc5qTZBeZBKUaI8LvcfbwSyYQtBkayhIlNknuveOsfCiWdlpucDzBksbA8wb1sfEHaEeykn7FMfM7JLwuAi0guZAdbE');
$obiect->postareUrl($url,$mesaj);
}
break;
case 'Image':

if(isset($_GET['mesaj']) && isset($_GET['image']))
{
    $mesaj=$_GET['mesaj'];
    $name=$_GET['image'];
    $obiect=new FacebookPost('EAAQM6NoB0t0BABnukB3CrOFpxLyPra6r6QoHuKbbMnETb1egnrZCi8w7X7Ubk4ZCbUX0BxZA451Fjbk5LY4LNiTgZBtLyc5qTZBeZBKUaI8LvcfbwSyYQtBkayhIlNknuveOsfCiWdlpucDzBksbA8wb1sfEHaEeykn7FMfM7JLwuAi0guZAdbE');
    $obiect->postareImage($name,$mesaj);
}
break;
case 'Message':
if(isset($_GET['messenger']))
{
   $mesaj=$_GET['messenger'];
   echo $mesaj;
   $obiect=new FacebookPost('EAAQM6NoB0t0BABnukB3CrOFpxLyPra6r6QoHuKbbMnETb1egnrZCi8w7X7Ubk4ZCbUX0BxZA451Fjbk5LY4LNiTgZBtLyc5qTZBeZBKUaI8LvcfbwSyYQtBkayhIlNknuveOsfCiWdlpucDzBksbA8wb1sfEHaEeykn7FMfM7JLwuAi0guZAdbE');
   $obiect->postareMesaj($mesaj);
}
break;
case 'Video':
    if(isset($_GET['descriere']) && ($_GET['title']) && isset($_GET['video']))
    {
        $descriere=$_GET['descriere'];
        $titlu=$_GET['title'];
        $videoName=$_GET['video'];
        $obiect=new FacebookPost('EAAQM6NoB0t0BABnukB3CrOFpxLyPra6r6QoHuKbbMnETb1egnrZCi8w7X7Ubk4ZCbUX0BxZA451Fjbk5LY4LNiTgZBtLyc5qTZBeZBKUaI8LvcfbwSyYQtBkayhIlNknuveOsfCiWdlpucDzBksbA8wb1sfEHaEeykn7FMfM7JLwuAi0guZAdbE');
        $obiect->postareVideo($videoName,$titlu,$descriere );
    }
break;
}
}
?>
<html>
<body>
<form method="GET" >
Posting for URL:
    <br>
URL:<input type="text" name="url"><br>
Mesaj:<input type="text" name="mesaj"><br>

<input type="submit" name="submit" value="Url"><br>

</form>
<form method="GET">
    Posting image:
    <br>
    Image Name:<input type="text" name="image"><br>
    mesaj:<input type="text" name="mesaj"><br>
    <input type="submit" name="submit" value="Image"><br>
</form>
</form>
<form method="GET">
    Posting message:
    <br>
    Mesajul dorit:<input type="text" name="messenger"><br>
  
    <input type="submit" name="submit" value="Message"><br>
</form>
<form method="GET">
    Posting video:
    <br>
    Video Name:<input type="text" name="video"><br>
    Title:<input type="text" name="title"><br>
    Description: <input type="text" name="descriere"><br>
    <input type="submit" name="submit" value="Video"><br>
</form>
</body>
</html>
