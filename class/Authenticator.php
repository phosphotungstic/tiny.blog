<?
    include_once("/db/UserDbGateway.php");
    include_once("/class/AuthSharedData.php");

    class Authenticator extends AuthSharedData{
        private $userDbGateway;
        private $authSharedData;

        public function __construct() {
            $this->userDbGateway = new UserDbGateway;
            $this->authSharedData = new AuthSharedData;
        }

        public function setLoggedIn($loggedIn) {
            $this->authSharedData->setLoggedIn($loggedIn);
        }

        public  function setUserId($userId) {
            $this->authSharedData->setUserId($userId);
        }

        public function setUsername($username) {
            $this->authSharedData->setUsername($username);
        }

        public function setUser($loggedIn, $user) {
            $this->authSharedData->setUser($loggedIn, $user->userId, $user->username);
        }

        public function checkCredentials($username, $password) {
            if($this->userDbGateway->checkUsernamePassword($username, $password)) {
                $this->authSharedData->setLoggedIn(true);
                return true;
            }
            else{
                return false;
            }
        }

    }
?>