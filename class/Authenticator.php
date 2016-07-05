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

        public function isSuccessfulLogin($username, $password) {
            if($this->checkCredentials($username, $password)) {
                $this->setUser($username);
                return true;
            }
            return false;
        }

        public function setUser($username) {
            $loggedInUser = $this->userDbGateway->getUser($username);
            $this->authSharedData->setUser(true, $loggedInUser->userId, $loggedInUser->username);
        }

        public function checkCredentials($username, $password) {
            return $this->userDbGateway->checkUsernamePassword($username, $password);
        }
    }
?>