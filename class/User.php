<?
    include_once("/class/UriParse.php");
    include_once("/db/userDbGateway.php");

    class User{
        public $username;
        public $userId;
        public $posts;

        private $uriParser;
        private $userDbGateway;

        public function __construct() {
            $this->uriParser = new UriParse;
            $this->userDbGateway = new UserDbGateway;
        }

        public function isValidUser() {
            if(!$this->userDbGateway->isValidUser((int)$this->uriParser->getUserId())) {
                return true;
            }
            if(!is_numeric($this->uriParser->getUserId())) {
                return true;
            }
            return false;
        }

        function getDisplayUser(){
            $displayUserId = $this->uriParser->getUserId();
            $profileUser = $this->userDbGateway->getUser($displayUserId);

            if($profileUser->username == ""){
                header("Location: /html/404.html");
                exit();
            }

            return $profileUser;
        }

        function isPageOwner() {
            return $this->userId === $this->uriParser->getUserId();
        }

    }

?>