<!DOCTYPE html>
<html>

<head>
    <title>DPZFlickr Post Example</title>

</head>

<body>
    <form action="REST1.php" method="post" enctype="multipart/form-data">
   
            
        <label>user ID:</label><br>
        <input type="text" name="userId" /><br>

        <label>access_token_secret:</label><br>
        <input type="text" name="tokensecret" /><br>
        
        <label>request_secret:</label><br>
        <input type="text" name="requestsecret" /><br>
        
        <label>access_token:</label><br>
        <input type="text" name="token" /><br>

        <label for="title">Title</label>
        <input id="title" name="title" type="text" size="50">

        <label for="photo">Attach a photo</label>
        <input id="photo" name="photo" type="file">

        <input id="upload-button" class="submit" type="submit" value="Upload-photo">
    </form>
<br><br>

    <form action="REST1.php" method="post" enctype="multipart/form-data">
   
            
        <label>URL:</label><br>
        <input type="text" name="URL" /><br>

        <label>access_token_secret:</label><br>
        <input type="text" name="tokensecret" /><br>
        
        <label>request_secret:</label><br>
        <input type="text" name="requestsecret" /><br>
        
        <label>access_token:</label><br>
        <input type="text" name="token" /><br>

        <label for="title">Title</label>
        <input id="title" name="title" type="text" size="50">


        <input id="upload-button" class="submit" type="submit" value="Upload-photo">
    </form>
</body>

</html>