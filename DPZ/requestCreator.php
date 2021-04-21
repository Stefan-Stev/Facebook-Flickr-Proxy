<style>
    form {
        display: inline-block;
        background-color: whitesmoke;
        border: 5px solid black;
        padding: 5px;
        margin: 2px;
    }
</style>


<!-- -------------------------LOGIN-------------------------->
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="login">Login</option>
    </select><br>
    <label>ourAppUserId:</label><br>
    <input type="text" name="userId" /><br>
    <label>token:</label><br>
    <input type="text" name="token" /><br>
    <label>redirect:</label><br>
    <input type="text" name="redirect" /><br>
    <input type="submit" value="Submit" />
</form>
<!-- -------------------------GET -------------------------->
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="getComments">comments</option>
        <option value="getCommentCount">comment count</option>
        <option value="getViewCount">view count</option>
        <option value="getFaveCount">fave count</option>
        <option value="last3comments">last 3 comments</option>
    </select><br>
    <label>post ID:</label><br>
    <input type="text" name="postId" /><br>
    <label>USER ID:</label><br>
    <input type="text" name="userid" /><br>
    <input type="submit" value="Submit" />
</form>




<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="getPhotosArray">get Posts</option>
        <option value="getBestPost">get best post</option>
        <option value="getAccountName">get account name</option>
        <option value="getAverageFaves">get faves average</option>
        <option value="getAverageComments">get comments average</option>
        <option value="getMostCommonTag">get most common tag</option>
    </select><br>
    <label>FB ID:</label><br>
    <input type="text" name="userid" /><br>
    <label>token:</label><br>
    <input type="text" name="token" /><br>
    <input type="submit" value="Submit" />
</form>



<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="getAverageFavesBetween">get faves average between</option>
        <option value="getAverageCommentsBetween">get comments average between</option>
    </select><br>
    <label>FB ID:</label><br>
    <input type="text" name="userid" /><br>
    <label>token:</label><br>
    <input type="text" name="token" /><br>
    <label>first timestamp:</label><br>
    <input type="text" name="timestamp1"/><br>
    <label>second timestamp:</label><br>
    <input type="text" name="timestamp2"/><br>    
    <input type="submit" value="Submit" />
</form>


<!-- -------------------------POST-------------------------->


</form>
<form action="REST.php" method="POST" enctype="multipart/form-data">
    <select id="do" name="do">
        <option value="PostImage1">Post Image</option><br>
        Image Title:<input type="text" name="image"><br>
        <label>UserID:</label><br>
        <input type="text" name="userid" /><br>
        <label>Token:</label><br>
        <input type="text" name="token" /><br>

        <label for="photo">Attach a photo</label>
        <input id="photo" name="photo" type="file">

        <input type="submit" name="submit" value="PostImage"><br>
</form>
<!--------------------Post URL--------->
<form action="REST.php" method="GET" enctype="multipart/form-data">
    <select id="do" name="do">
    <option value="PostImage">Post Image</option><br>
        <label>URL:</label><br>
        <input type="text" name="image" /><br>

        
        <label>Image Title:</label><br>
        <input type="text" name="message"><br>

        <label>UserID:</label><br>
        <input type="text" name="userid" /><br>
        <label>Token:</label><br>
        <input type="text" name="token" /><br>

        <input type="submit" name="submit" value="PostImage"><br>
       
    </form>

<!-- <form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostVideo">Post Video</option>
        Video Name:<input type="text" name="video"><br>
        Title:<input type="text" name="title"><br>
        Description: <input type="text" name="descriere"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <input type="submit" name="submit" value="Video"><br>
</form> -->
</body>

</html>