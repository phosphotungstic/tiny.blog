<?
    include_once("/class/Post.php");
    include_once("/db/Dbconnect.php");
    
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
            $query = "select * from posts where post_id=" . $postId . " and delete_bit = 0;";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            $newPost = new Post($resultAssoc["poster_id"], $resultAssoc["post_id"], $resultAssoc["post_content"], $resultAssoc["number_comments"]);

            return $newPost;
        }

        function getPostFromPostId($postId) {
            $newPost = $this->getPostFromPostIdWithoutPostername($postId);    
            include_once("/db/UserDbGateway.php");
            $userDbGateway = new UserDbGateway;
            $newPost->postername = $userDbGateway->getUsernameFromUserId($newPost->posterId);

            return $newPost;
        }

        function getRecentPosts() {
            $query = "select users.username, posts.poster_id, posts.post_id, posts.post_content, posts.number_comments from users inner join posts on users.user_id = posts.poster_id and delete_bit = 0 order by post_id desc limit 5;";
            $result = $this->connection->query($query);

            $recentPostArray = array();
            while($resultAssoc = $result->fetch_assoc()) {
                $newPost = new Post($resultAssoc["poster_id"], $resultAssoc["post_id"], $resultAssoc["post_content"], $resultAssoc["number_comments"]);
                $newPost->postername = $resultAssoc["username"];
                array_push($recentPostArray, $newPost);
            }

            return $recentPostArray;
        }

        function createPost($userId, $postText) {
            $textToStore = nl2br(htmlentities($postText, ENT_QUOTES, 'UTF-8'));
            $query = "insert into posts VALUES(" . $userId . ", NULL, '" . $textToStore . "', 0, 0);";
            $this->connection->query($query);

            return $this->connection->insert_id;
        }

        function deletePost($postId) {
            $query = "update posts set delete_bit = 1 where post_id=" . $postId . ";";
            $this->connection->query($query);
        }

        function maxPostId() {
            $query = "select max(post_id) from posts;";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();

            return $resultAssoc["max(post_id)"];
        }

        function doesPostExist($postId) {
            $query = "select * from posts where post_id=" . $postId . " and delete_bit = 0;";
            $result = $this->connection->query($query);
            if($result->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }

        function doesUserOwnPost($userId, $postId) {
            $query = "select * from posts where post_id=" . $postId . " and poster_id=" . $userId . ";";
            $result = $this->connection->query($query);
            if($result->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }
    }
?>