<?
    session_start();

    class AuthSharedData{
        protected function setUser($loggedIn, $userId, $username) {
            $_SESSION["loggedIn"] = $loggedIn;
            $_SESSION["userId"] = $userId;
            $_SESSION["username"] = $username;
        }

        protected function setLoggedIn($loggedIn) {
            $_SESSION["loggedIn"] = $loggedIn;
        }

        protected function setUserId($userId) {
            $_SESSION["userId"] = $userId;
        }

        protected function setUsername($username) {
            $_SESSION["username"] = $username;
        }

        protected function isLoggedIn() {
            return $_SESSION["loggedIn"];
        }

        protected function getUserId() {
            return $_SESSION["userId"];
        }

        protected function getUsername() {
            return $_SESSION["username"];
        }
    }

?>