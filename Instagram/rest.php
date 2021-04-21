<?php
require_once './InstagramGet.php';
function rest(){
    echo '<pre>';
    
	if (isset($_GET) && count($_GET) >= 3) {
		if (isset($_GET['do']) && isset($_GET['postId']) && isset($_GET['token'])){
            //print_r($_GET['token']);
            print_r($_GET['userId']);
            
            $Instagramninfo = new InstagramGet($_GET['token']);

            $userID=$_GET['userId'];
			$postID=$_GET['postId'];
			switch ($_GET['do']) {
				case 'getComments':
					print_r($Instagramninfo->getComments($postID));
					break;

				case 'getCommentCount':
					print_r($Instagramninfo->getCommentCount($postID));
					break;

				case 'getLikesCount':
					print_r($Instagramninfo->getLikesCount($postID));
					break;
                case 'getPost':
                    print_r($Instagramninfo->getPost($postID));
                    break;
                case 'getUserInfo':
                    print_r($Instagramninfo->getUserInfo($userID));
                    break;
                
				default:
					break;
			}
		
		}else { 
	    echo "invalid arguments";
        }
    }else {
		echo "invalid request";
            }
echo '<pre>';
}
rest();


?>
