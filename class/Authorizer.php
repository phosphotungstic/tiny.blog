<?
    include_once("/class/UriParse.php");
    include_once("/db/PostDbGateway.php");
    include_once("/class/AuthSharedData.php");

    class Authorizer extends authSharedData{
        private $uriParser;
        private $postDbGateway;
        private $authSharedData;

        public function __construct() {
            $this->uriParser = new UriParse;
            $this->postDbGateway = new PostDbGateway;
            $this->authSharedData = new AuthSharedData;
        }

        public function getUserId() {
            return $this->authSharedData->getUserId();
        }

        public function getUsername() {
            return $this->authSharedData->getUsername();
        }

        public function isPageOwner() {
            if((int)$this->uriParser->getUserId() == $this->getUserId()) {
                return true;
            }
            else{
                return false;
            }
        }

        public function canDelete($postId) {
            if($this->postDbGateway->doesUserOwnPost($this->getUserId(), $postId)) {
                return true;
            }
            else{
                return false;
            }
        }

        public function isLoggedIn() {
            return $this->authSharedData->isLoggedIn();
        }

    }
?>