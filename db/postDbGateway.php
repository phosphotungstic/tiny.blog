<?
    include_once("/class/Post.php");

    class PostDbGateway{

        public $connection;

        function __construct() {
            include_once("/db/Dbconnect.php");
            $this->connection = Dbconnect::getConnection();
        }

        function getPostsFromPostIdArrayWithoutPostername($postIds) {
            $postArray = array();
            foreach($postIds as $postId) {
                $newPost = $this->getPostFromPostIdWithoutPostername($postId);
                array_unshift($postArray, $newPost);
            }
            return $postArray;
        }


        function getPostsFromPostIdArray($postIds) {
            $postArray = array();
            foreach($postIds as $postId) {
                $newPost = $this->getPostFromPostId($postId);
                array_unshift($postArray, $newPost);
            }
            return $postArray;
        }

        function getPostFromPostIdWithoutPostername($postId) {
            $getPostQuery = "select * from posts where post_id=" . $postId . ";";
            $getPostResult = $this->connection->query($getPostQuery);
            $getPostResultArray = $getPostResult->fetch_assoc();

            $GRPRA = $getPostResultArray;

            $newPost = new Post($GRPRA["poster_id"], $GRPRA["post_id"], $GRPRA["post_content"], $GRPRA["number_comments"]);

            return $newPost;
        }

        function getPostFromPostId($postId) {
            $newPost = $this->getPostFromPostIdWithoutPostername($postId);    
            include_once("/db/UserDbGateway.php");
            $userDbGatewayObject = new UserDbGateway;
            $newPost->postername = $userDbGatewayObject->getUsernameFromUserId($newPost->posterId);

            return $newPost;
        }

        function getRecentPosts() {
            $getRecentPostsQuery = "select users.username, posts.poster_id, posts.post_id, posts.post_content, posts.number_comments from users inner join posts on users.user_id = posts.poster_id order by post_id desc limit 5;";
            $getRecentPostsResult = $this->connection->query($getRecentPostsQuery);

            $recentPostArray = array();
            while($getRecentPostsResultArray = $getRecentPostsResult->fetch_assoc()) {
                $GRPRA = $getRecentPostsResultArray;
                $newPost = new Post($GRPRA["poster_id"], $GRPRA["post_id"], $GRPRA["post_content"], $GRPRA["number_comments"]);
                $newPost->postername = $GRPRA["username"];


                array_push($recentPostArray, $newPost);
            }

            return $recentPostArray;
        }

        function createPost($userId, $postText) {
            $textToStore = nl2br(htmlentities($postText, ENT_QUOTES, 'UTF-8'));
            $createPostQuery = "insert into posts VALUES(" . $userId . ", NULL, '" . $textToStore . "', 0);";
            $this->connection->query($createPostQuery);
            return $this->connection->insert_id;
        }

        function maxPostId() {
            $maxPostIdQuery = "select max(post_id) from posts;";
            $maxPostIdResult = $this->connection->query($maxPostIdQuery);
            $maxPostId = $maxPostIdResult->fetch_assoc();

            return $maxPostId["max(post_id)"];
        }

        function isGreaterThanMaxPostId($postId) {
            if($postId > $this->maxPostId) {
                return true;
            }
            else{
                return false;
            }
        }

        function doesPostExist($postId) {
            $postExistQuery = "select * from posts where post_id=" . $postId . ";";
            $postExistResult = $this->connection->query($postExistQuery);
            if($postExistResult->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }

        function doesUserOwnPost($userId, $postId) {
            $userOwnPostQuery = "select * from posts where post_id=" . $postId . " and poster_id=" . $userId . ";";
            $userOwnPostResult = $this->connection->query($userOwnPostQuery);
            if($userOwnPostResult->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }

        function deletePost($postId) {
            $deletePostQuery = "delete from posts where post_id=" . $postId . ";";
            $this->connection->query($deletePostQuery);
        }

    }