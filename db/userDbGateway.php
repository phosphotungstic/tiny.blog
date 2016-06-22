<?
    include_once("/class/User.php");
    include_once("/db/Dbconnect.php");
    
    class UserDbGateway{
        public $connection;

        function __construct() {
            include_once("/db/Dbconnect.php");
            $this->connection = Dbconnect::getConnection();
        }

        function checkUsernamePassword($username, $password) {
            $query = "select username, password from users where username='" . $username . "' and password='" . $password . "';";
            $result = $this->connection->query($query);
            if($result->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }

        function getUser($userInfo) {
            $newUser = new User;

            if(is_numeric($userInfo)) {
                $query = "select users.username, users.user_id, posts.post_id, posts.post_content from users JOIN posts where delete_bit = 0 AND poster_id = user_id AND user_id=" . $userInfo . ";";
            }
            else{
                $query = "select users.username, users.user_id, posts.post_id, posts.post_content from users JOIN posts where delete_bit = 0 AND poster_id = user_id AND username='" . $userInfo . "';";
            }
            $result = $this->connection->query($query);
            $postArray = array();

            while($resultArray = $result->fetch_assoc()) {
                $newUser->username = $resultArray["username"];
                $newUser->userId = $resultArray["user_id"];
                $post = new Post($resultArray["username"], $resultArray["user_id"], $resultArray["post_id"], $resultArray["post_content"]);
                array_unshift($postArray, $post);
            }

            $newUser->posts = $postArray;
            return $newUser;
        }

        function maxUserId() {
            $query = "select max(user_id) from users;";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();

            return $resultAssoc["max(user_id)"];
        }

        function checkUsernameAvailable($username) {
            $query = "select * from users where username='" . $username . "';";
            $result = $this->connection->query($query);
            if($result->num_rows == 0) {
                return true;
            }
            else{
                return false;
            }
        }


        function addAccount($username, $password) {
            $query = "insert into users VALUES(NULL, '" . $username . "','" . $password . "');";
            $this->connection->query($query);
        }

        function getUserIdFromUsername($username) {
            $query = "select user_id from users where username='" . $username . "';";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();

            return $resultAssoc["user_id"];
        }

        function getUsernameFromUserId($userId) {
            $query = "select username from users where user_id=" . $userId . ";";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            
            return $resultAssoc["username"];
        }

        function updatePassword($userId, $passwordUpdate) {
            $query = "update users set password='" . $passwordUpdate . "' where user_id=" . $userId . ";";
            $this->connection->query($query);
        }

        function isGreaterThanMaxUserId($userId) {
            $maxUserId = $this->maxUserId();
            if($userId > $maxUserId) {
                return true;
            }
            else{
                return false;
            }
        }

    }
?>