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
            if($this->postDbGateway->doesPostExist((int)$this->uriParser->getPostId())) {
                return true;
            }
            else{
                return false;
            }
        }

        function validHandler() {
            $action = $this->uriParser->getAction();

            switch ($action) {
                case "view":
                    $this->viewAction();
                    break;
                case "delete":
                    $this->deleteAction();
                    break;
                default:
                    $this->redirect("/html/404.html");
            }
        }

        function viewAction() {
            $post = $this->postDbGateway->getPostFromPostId($this->uriParser->getPostId());
            $userId = $this->authorizer->getUserId();
            include("/html/postPage.html");
        }

        function deleteAction() {
            if($this->authorizer->canDelete($this->uriParser->getPostId())) {
                $this->deletePost($this->uriParser->getPostId());
            }
            else{
                $this->redirect("/html/404.html");
            }   
        }

        function deletePost($postId) {
            $this->postDbGateway->deletePost($postId);
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }
?>