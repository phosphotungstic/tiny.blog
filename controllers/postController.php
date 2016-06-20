<?
    class PostController extends BaseController{
        function __construct() {
            parent::__construct();
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
            if($this->postDbGateway->doesPostExist((int)$this->uriParser->getAssociativeValue("postId"))) {
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
            $post = $this->postDbGateway->getPostFromPostId($this->uriParser->getAssociativeValue("postId"));
            include("/html/postPage.html");
        }

        function deleteAction() {
            if($this->authorizer->canDelete($this->uriParser->getAssociativeValue("postId"))) {
                $this->deletePost($this->uriParser->getAssociativeValue("postId"));
            }
            else{
                $this->redirect("/html/404.html");
            }   
        }

        function deletePost($postId) {
            $this->postDbGateway->deletePost($postId);
            $this->redirect("/main/user/action/view/userId/" . $this->authorizer->getUserId());
        }
    }
?>