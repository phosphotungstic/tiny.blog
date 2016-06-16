<?
    session_start();

    class AuthSharedData{
        public function setLoggedInUserIdUsername($loggedIn, $userId, $username){
            $_SESSION["loggedIn"] = $loggedIn;
            $_SESSION["userId"] = $userId;
            $_SESSION["username"] = $username;
        }

        public function setLoggedIn($loggedIn){
            $_SESSION["loggedIn"] = $loggedIn;
        }

        public function setUserId($userId){
            $_SESSION["userId"] = $userId;
        }

        public function setUsername($username){
            $_SESSION["username"] = $username;
        }

        public function isLoggedIn(){
            return $_SESSION["loggedIn"];
        }

        public function getUserId(){
            return $_SESSION["userId"];
        }

        public function getUsername(){
            return $_SESSION["username"];
        }
    }

?>