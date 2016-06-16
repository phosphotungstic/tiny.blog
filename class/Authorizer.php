<?
    include_once("/class/UriParse.php");
    include_once("/db/PostDbGateway.php");
    include_once("/class/AuthSharedData.php");



    class Authorizer{
        private $uriParser;
        private $postDbGatewayObject;
        private $authSharedDataObject;

        function __construct() {
            $this->uriParser = new UriParse;
            $this->postDbGatewayObject = new PostDbGateway;
            $this->authSharedDataObject = new AuthSharedData;
        }

        public function getUserId() {
            return $this->authSharedDataObject->getUserId();
        }

        public function getUsername() {
            return $this->authSharedDataObject->getUsername();
        }

        function ownsUserPage() {
            if((int)$this->uriParser->uriAssociativeArray["userId"] == $this->getUserId()) {
                return true;
            }
            else{
                return false;
            }
        }

        function canDelete($postId) {
            if($this->postDbGatewayObject->doesUserOwnPost($this->getUserId(), $postId)) {
                return true;
            }
            else{
                return false;
            }
        }

        function isLoggedIn() {
            return $this->authSharedDataObject->isloggedIn();
        }

    }
?>