<style>
    form {
        display: inline-block;
        background-color: whitesmoke;
        border: 5px solid black;
        padding: 5px;
        margin: 2px;
    }
</style>

<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="avgCommsTime">average comments in time period</option>
        <option value="avgLikesTime">average likes in time period</option>
        <option value="avgSharesTime">average shares in time period</option>
    </select><br>
    <label>JWT:</label><br>
    <input type="text" name="jwt" placeholder="jwt"/><br>
    <label>time begin:</label><br>
    <input type="text" name="begin" /><br>
    <label>time end:</label><br>
    <input type="text" name="end" /><br>
    <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
    <input type="submit" value="Submit" />
</form>


<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="login">Login</option>
        <option value="avgComms">average comments</option>
        <option value="avgLikes">average likes</option>
        <option value="avgShares">average shares</option>
        <option value="getPages">get pages</option>
        <option value="getUserName">get user name</option>
        <option value="getPageName">get page name</option>
        <option value="getPostsArray">get Posts</option>
        <option value="getBestPost">get best post</option>
    </select><br>
    <label>jwt</label><br>
    <input type="text" name="jwt" /><br>
    <label>redirect:</label><br>
    <input type="text" name="redirect" /><br>
    <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
    <input type="submit" value="Submit" />
</form>
<!-- -------------------------LOGIN-------------------------->
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="login">Login</option>
    </select><br>
    <label>ourAppUserId:</label><br>
    <input type="text" name="userId" /><br>
    <label>redirect:</label><br>
    <input type="text" name="redirect" /><br>
    <input type="submit" value="Submit" />
</form>
<!-- -------------------------GET -------------------------->
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="getComments">comments</option>
        <option value="getCommentCount">comment count</option>
        <option value="getLikeCount">like count</option>
        <option value="last3comments">last 3 comments</option>
    </select><br>
    <label>post ID:</label><br>
    <input type="text" name="postId" /><br>
    <label>FB ID:</label><br>
    <input type="text" name="fbid" /><br>
    
    <input type="submit" value="Submit" />
</form>


<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="avgComms">average comments</option>
        <option value="avgLikes">average likes</option>
        <option value="avgShares">average shares</option>
        <option value="getPages">get pages</option>
        <option value="getUserName">get user name</option>
        <option value="getPageName">get page name</option>
        <option value="getPostsArray">get Posts</option>
        <option value="getBestPost">get best post</option>
    </select><br>
    <label>FB ID:</label><br>
    <input type="text" name="fbid" /><br>
    <label> Page Id:</label><br>
    <input type="text" name="paginaId" ><br>
    <input type="submit" value="Submit" />
</form>
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="getNumberShares">NumberOfShares</option>
    </select><br>
    <label>post ID:</label><br>
    <input type="text" name="postId" /><br>
    <label>FB ID:</label><br>
    <input type="text" name="fbid" /><br>
  
    <input type="submit" name="Submit" value="getNumberShares" />
</form>
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="MostShared">MostShares</option>
    </select><br>
    <label>FB ID:</label><br>
    <input type="text" name="fbid" /><br>
    <label> Page Id:</label><br>
    <input type="text" name="paginaId" ><br>
    <input type="submit" name="Submit" value="MostShared" />
</form>
<form action="REST.php" method="get">
    <select id="do" name="do">
        <option value="MostTagged">MostTagged</option>
    </select><br>
    <label>FB ID:</label><br>
    <input type="text" name="fbid" /><br>
    <label> Page Id:</label><br>
    <input type="text" name="paginaId" ><br>
    <input type="submit" name="Submit" value="MostTagged" />
</form>
<!-- -------------------------POST-------------------------->
<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostUrl">Post URL</option>
        URL:<input type="text" name="url"><br>
        Mesaj:<input type="text" name="mesaj"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
        <input type="submit" name="submit" value="Url"><br>
</form>

</form>
<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostImage">Post Image</option>
        Image Name:<input type="text" name="image"><br>
        mesaj:<input type="text" name="mesaj"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
        <input type="submit" name="submit" value="Image"><br>
</form>
<!-- multiple post images
Se va folosi '+' ca delimitator
-->
<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="MultipleImage">Multiple Image</option>
        Image Name:<input type="text" name="image"><br>
        mesaj:<input type="text" name="mesaj"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
        <input type="submit" name="submit" value="MultipleImage"><br>
</form>
<!-- -->
</form>
<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostMessage">Post Message</option>
        Mesajul dorit:<input type="text" name="messenger"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
        <input type="submit" name="submit" value="Message"><br>
</form>
<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostReply">Post Reply</option>
        Reply dorit : <input type="text" name="reply"><br>
        Id postarii : <input type="text" name="id_postare"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <input type="submit" name="submit" value="Reply"><br>
    </select>
</form>

<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostUpdate">Update Post</option>
        Mesaj dorit:<input type="text" name="update"><br>
        Id postarii : <input type="text" name="id_postare"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <input type="submit" name="submit" value="Update"><br>
    </select>
</form>

<form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="PostDelete">Id Postare Sters</option>
        Id postarii : <input type="text" name="id_postare"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <input type="submit" name="submit" value="Delete"><br>
    </select>
</form>
 <form action="REST.php" method="GET">
    <select id="do" name="do">
        <option value="Video">Post Video</option>
        Video Name:<input type="text" name="video"><br>
        Title:<input type="text" name="title"><br>
        Description: <input type="text" name="descriere"><br>
        <label>FB ID:</label><br>
        <input type="text" name="fbid" /><br>
        <label> Page Id:</label><br>
        <input type="text" name="paginaId" ><br>
        <input type="submit" name="submit" value="Video"><br>
    </select>
</form> 
</body>

</html>