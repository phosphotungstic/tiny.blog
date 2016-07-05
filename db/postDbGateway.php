<?
    include_once("/class/Post.php");
    include_once("/db/Dbconnect.php");
    
    class PostDbGateway{
        public $connection;

        function __construct() {
            include_once("/db/Dbconnect.php");
            $this->connection = Dbconnect::getConnection();
        }

        function getPostFromPostId($postId) {
            $query = "select users.username, posts.poster_id, posts.post_id, posts.post_content from posts JOIN users where delete_bit = 0 and users.user_id = posts.poster_id and posts.post_id=" . $postId . ";";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            $newPost = new Post($resultAssoc["username"], $resultAssoc["poster_id"], $resultAssoc["post_id"], $resultAssoc["post_content"]);
            return $newPost;
        }

        function getRecentPosts() {
            $query = "select users.username, posts.poster_id, posts.post_id, posts.post_content from users join posts where users.user_id = posts.poster_id and delete_bit = 0 order by post_id desc limit 5;";
            $result = $this->connection->query($query);

            $recentPostArray = array();
            while($resultAssoc = $result->fetch_assoc()) {
                $newPost = new Post($resultAssoc["username"], $resultAssoc["poster_id"], $resultAssoc["post_id"], $resultAssoc["post_content"]);
                array_push($recentPostArray, $newPost);
            }
            return $recentPostArray;
        }

        function createPost($userId, $postText) {
            $textToStore = nl2br(htmlentities($postText, ENT_QUOTES, 'UTF-8'));
            $query = "insert into posts VALUES(" . $userId . ", NULL, '" . $textToStore . "', 0);";
            $this->connection->query($query);
            return $this->connection->insert_id;
        }

        function deletePost($postId) {
            $query = "update posts set delete_bit = 1 where post_id=" . $postId . ";";
            $this->connection->query($query);
        }

        function doesPostExist($postId) {
            $query = "select * from posts where post_id=" . $postId . " and delete_bit = 0;";
            $result = $this->connection->query($query);
            return $result->num_rows;
        }

        function doesUserOwnPost($userId, $postId) {
            $query = "select * from posts where post_id=" . $postId . " and poster_id=" . $userId . ";";
            $result = $this->connection->query($query);
            return $result->num_rows;
        }
    }
?>