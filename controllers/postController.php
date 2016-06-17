<?
    include_once("/db/PostDbGateway.php");
    include_once("/class/UriParse.php");
    include_once("/class/Authorizer.php");

    class PostController extends BaseController{
        private $userDbGatewayObject;
        private $postDbGatewayObject;
        private $uriParser;
        private $authorizerObject;

        function __construct() {
            $this->postDbGatewayObject = new PostDbGateway;
            $this->uriParser = new UriParse;
            $this->authorizerObject = new Authorizer;
        }

        function action() {
            if($this->isValidUri()) {
                $this->redirect("/html/404.html");
            }
            elseif(!$this->isValidPostId()) {
                include("/html/postNotExist.html");
            }
            else{
                $this->validHandler();
            }

        }

        function isValidUri() {
            if(!$this->uriParser->isKeySet("postId")) {
                return false;
            }
        }

        function isValidPostId() {
            if($this->postDbGatewayObject->doesPostExist((int)$this->uriParser->getAssociativeValue("postId"))) {
                return true;
            }
            else{
                return false;
            }
        }

        function validHandler() {
            if($this->uriParser->uriCheckAssociativePair("action", "view")) {
                $this->viewAction();
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "delete")) {
                $this->deleteAction();
            }
            else{
                $this->redirect("/html/404.html");
            }
        }

        function viewAction() {
            $post = $this->postDbGatewayObject->getPostFromPostId($this->uriParser->getAssociativeValue("postId"));
            include("/html/postPage.html");
        }

        function deleteAction() {
            if($this->authorizerObject->canDelete($this->uriParser->getAssociativeValue("postId"))) {
                $this->deletePost($this->uriParser->getAssociativeValue("postId"));
            }
            else{
                $this->redirect("/html/404.html");
            }   
        }

        function deletePost($postId) {
            $this->postDbGatewayObject->deletePost($postId);
            $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
        }
    }
?>