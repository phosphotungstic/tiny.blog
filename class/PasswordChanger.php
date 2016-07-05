<?
    include_once("/class/Authenticator.php");
    include_once("/class/Authorizer.php");
    include_once("/db/userDbGateway.php");

    class PasswordChanger {
        private $authenticator;
        private $authorizer;
        private $userDbGateway;

        public function __construct() {
            $this->authenticator = new Authenticator;
            $this->authorizer = new Authorizer;
            $this->userDbGateway = new userDbGateway;
        }

        public function isValidPasswordChange() {
            $username = $this->authorizer->getUsername();
            return $this->isCorrectOldPassword($username) && $this->isPasswordUpdateEqual();
        }

        private function isCorrectOldPassword($username) {
            return $this->authenticator->checkCredentials($username, $_POST["oldPassword"]);
        }

        private function isPasswordUpdateEqual() {
            return !strcmp($_POST["passwordUpdate"], $_POST["passwordUpdateRetype"]);
        }

        public function changePassword() {
            $this->userDbGateway->updatePassword($this->authorizer->getUserId(), $_POST["passwordUpdate"]);
        }
    }
?>