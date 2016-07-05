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
            return $result->num_rows;
        }

        function getUser($userInfo) {
            $newUser = new User;

            if(is_numeric($userInfo)) {
                $query = "select users.username, users.user_id, posts.post_id, posts.post_content from users JOIN posts ON posts.poster_id = users.user_id where delete_bit = 0 AND users.user_id=" . $userInfo . ";";
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

        function checkUsernameAvailable($username) {
            $query = "select * from users where username='" . $username . "';";
            $result = $this->connection->query($query);
            return !$result->num_rows;
        }


        function createAccount($username, $password) {
            $query = "insert into users VALUES(NULL, '" . $username . "','" . $password . "');";
            $this->connection->query($query);
        }

        function getUserIdFromUsername($username) {
            $query = "select user_id from users where username='" . $username . "';";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            return $resultAssoc["user_id"];
        }

        function updatePassword($userId, $passwordUpdate) {
            $query = "update users set password='" . $passwordUpdate . "' where user_id=" . $userId . ";";
            $this->connection->query($query);
        }

 
        function isValidUser($userId) {
            $query = "select * from users where userId = " . $userId . ";";
            $result = $this->connection->query($query);
            return $result->num_rows;
        }

    }
?>