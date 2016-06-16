<?
    include_once("/db/UserDbGateway.php");
    include_once("/class/AuthSharedData.php");

    class Authenticator{
        private $userDbGatewayObject;
        private $authSharedDataObject;

        function __construct() {
            $this->userDbGatewayObject = new UserDbGateway;
            $this->authSharedDataObject = new AuthSharedData;
        }

        function setLoggedIn($loggedIn) {
            $this->authSharedDataObject->setLoggedIn($loggedIn);
        }

        public  function setUserId($userId) {
            $this->authSharedDataObject->setUserId($userId);
        }

        public function setUsername($username) {
            $this->authSharedDataObject->setUsername($username);
        }

        public function setLoggedInUserIdUsername($loggedIn, $userId, $username) {
            $this->authSharedDataObject->setLoggedInUserIdUsername($loggedIn, $userId, $username);
        }

        function checkCredentials($username, $password) {
            if($this->userDbGatewayObject->checkUsernamePassword($username, $password)) {
                $this->authSharedDataObject->setLoggedIn(true);
                return true;
            }
            else{
                return false;
            }
        }

    }
?>