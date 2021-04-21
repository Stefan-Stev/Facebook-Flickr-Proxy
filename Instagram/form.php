<form action="rest.php" method="get">
    <select  id="do" name="do">
            <option value="getComments">Comments</option>
            <option value="getCommentCount">Comment Count</option>
            <option value="getLikesCount">Likes Count</option>
            <option value="getUserInfo">UserINfo</option>
            <option value="getPost">getPost</option>
            
    </select><br>
    <label>user ID:</label><br>
    <input type="text" name="userId" /><br>
    <label>post ID:</label><br>
    <input type="text" name="postId" /><br>
    <label>access_token:</label><br>
    <input type="text" name="token" /><br>
    <input type="submit" value="Submit" />
</form>