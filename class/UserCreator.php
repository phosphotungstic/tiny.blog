<?
    include_once("/db/userDbGateway.php");

    class UserCreator {
        private $userDbGateway;

        public function __construct(){
            $userDbGateway = new UserDbGateway;
        }

        function createUser() {
            if($this->isValidateNewUser()) {
                $this->userDbGateway->createAccount($_POST["username"], $_POST["password"]);
                return true;
            }
            return false;
        }

        function isValidNewUser() {
            $validInfo = true;
            if(strcmp($_POST["password"], $_POST["confirmPassword"])) {
                $validInfo = false;
            }
            if(!$this->userDbGateway->checkUsernameAvailable($_POST["username"])) {
                $validInfo = false;
            }
            if(strlen($_POST["username"]) > 20 || strlen($_POST["password"]) > 20) {
                $validInfo = false;
            }
            return $validInfo;
        }
    }
?>