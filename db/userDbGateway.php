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

        function createUserFromUserId($userId) {
            $newUser = new User;
            $newUser->userId = $userId;

            $query = "select username from users where user_id=" . $userId . ";";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            $newUser->username = $resultAssoc["username"];

            return $newUser;
        }

        function createUserFromUsername($username) {
            $newUser = new User;
            $newUser->username = $username;

            $query = "select user_id from users where username='" . $username . "';";
            $result = $this->connection->query($query);
            $resultAssoc = $result->fetch_assoc();
            $newUser->userId = $resultAssoc["user_id"];

            return $newUser;
        }

        function addPostsAndCommentsFromUserClass($user) {
            $user->postIds = $this->getPostsFromUserClass($user);
            $user->commentIds = $this->getCommentsFromUserClass($user);
        }

        function getPostsFromUserClass($user) {
            $query = "select post_id from posts where poster_id=" . $user->userId . " and delete_bit = 0;";
            $result = $this->connection->query($query);            
            $userPostIdArray = array();
            while($resultAssoc = $result->fetch_assoc()) {
                array_push($userPostIdArray, $resultAssoc["post_id"]);
            }

            return $userPostIdArray;
        }

        function getCommentsFromUserClass($user) {
            $query = "select comment_id from comments where commenter_id=" . $user->userId . ";";
            $result = $this->connection->query($query);            
            $userCommentIdArray = array();
            while($resultAssoc = $result->fetch_assoc()) {
                array_push($userCommentIdArray, $resultAssoc["comment_id"]);
            }

            return $userCommentIdArray;
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