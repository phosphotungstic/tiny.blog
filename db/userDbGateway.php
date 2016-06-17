<?
    include_once("/class/User.php");
    
    class UserDbGateway{
        public $connection;

        function __construct() {
            include_once("/db/Dbconnect.php");
            $this->connection = Dbconnect::getConnection();
        }

        function checkUsernamePassword($username, $password) {
            $usernamePasswordQuery = "select username, password from users where username='" . $username . "' and password='" . $password . "';";
            $usernamePasswordQueryResult = $this->connection->query($usernamePasswordQuery);
            if($usernamePasswordQueryResult->num_rows == 0) {
                return false;
            }
            else{
                return true;
            }
        }

        function createUserFromUserId($userId) {
            $newUser = new User;
            $newUser->userId = $userId;

            $usernameFromUserIdQuery = "select username from users where user_id=" . $userId . ";";
            $usernameFromUserIdResult = $this->connection->query($usernameFromUserIdQuery);
            $usernameFromUserIdResultArray = $usernameFromUserIdResult->fetch_assoc();
            $newUser->username = $usernameFromUserIdResultArray["username"];

            return $newUser;
        }

        function createUserFromUsername($username) {
            $newUser = new User;
            $newUser->username = $username;

            $userIdFromUsernameQuery = "select user_id from users where username='" . $username . "';";
            $userIdFromUsernameResult = $this->connection->query($userIdFromUsernameQuery);
            $userIdFromUsernameResultArray = $userIdFromUsernameResult->fetch_assoc();
            $newUser->userId = $userIdFromUsernameResultArray["user_id"];

            return $newUser;
        }

        function addPostsAndCommentsFromUserClass($user) {
            $user->postIds = $this->getPostsFromUserClass($user);
            $user->commentIds = $this->getCommentsFromUserClass($user);
        }

        function getPostsFromUserClass($user){
            $userPostIdFromUserIdQuery = "select post_id from posts where poster_id=" . $user->userId . " and delete_bit = 0;";
            $userPostIdFromUserIdResult = $this->connection->query($userPostIdFromUserIdQuery);            
            $userPostIdArray = array();
            while($userPostIdFromUserIdResultArray = $userPostIdFromUserIdResult->fetch_assoc()) {
                array_push($userPostIdArray, $userPostIdFromUserIdResultArray["post_id"]);
            }

            return $userPostIdArray;
        }

        function getCommentsFromUserClass($user){
            $userCommentIdFromUserIdQuery = "select comment_id from comments where commenter_id=" . $user->userId . ";";
            $userCommentIdFromUserIdResult = $this->connection->query($userCommentIdFromUserIdQuery);            
            $userCommentIdArray = array();
            while($userCommentIdFromUserIdResultArray = $userCommentIdFromUserIdResult->fetch_assoc()) {
                array_push($userCommentIdArray, $userCommentIdFromUserIdResultArray["comment_id"]);
            }

            return $userCommentIdArray;
        }

        function maxUserId() {
            $maxUserIdQuery = "select max(user_id) from users;";
            $maxUserIdResult = $this->connection->query($maxUserIdQuery);
            $maxUserId = $maxUserIdResult->fetch_assoc();

            return $maxUserId["max(user_id)"];
        }

        function checkUsernameAvailable($username) {
            $usernameAvailableQuery = "select * from users where username='" . $username . "';";
            $usernameAvailableResult = $this->connection->query($usernameAvailableQuery);
            if($usernameAvailableResult->num_rows == 0) {
                return true;
            }
            else{
                return false;
            }
        }


        function addAccount($username, $password) {
            $addAccountQuery = "insert into users VALUES(NULL, '" . $username . "','" . $password . "');";
            $this->connection->query($addAccountQuery);
        }

        function getUserIdFromUsername($username) {
            $getUserIdFromUsernameQuery = "select user_id from users where username='" . $username . "';";
            $getUserIdResult = $this->connection->query($getUserIdFromUsernameQuery);
            $getUserId = $getUserIdResult->fetch_assoc();

            return $getUserId["user_id"];
        }

        function getUsernameFromUserId($userId) {
            $getPosterQuery = "select username from users where user_id=" . $userId . ";";
            $getPosterResult = $this->connection->query($getPosterQuery);
            $getPosterResultArray = $getPosterResult->fetch_assoc();
            
            return $getPosterResultArray["username"];
        }

        function updatePassword($userId, $passwordUpdate) {
            $updatePasswordQuery = "update users set password='" . $passwordUpdate . "' where user_id=" . $userId . ";";
            $this->connection->query($updatePasswordQuery);
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