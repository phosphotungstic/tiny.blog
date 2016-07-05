<?
    class PostController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            $this->validateRequest();
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

        function validateRequest(){
            if(!$this->isValidUri()) {
                $this->redirect("/html/404.html");
            }

            if(!$this->isValidPostId()) {
                include("/html/postNotExist.html");
                exit();
            }
        }

        function isValidUri() {
            return $this->uriParser->isKeySet("postId");
        }

        function isValidPostId() {
            return $this->postDbGateway->doesPostExist((int)$this->uriParser->getPostId());
        }

        function viewAction() {
            $postPageView["post"] = $this->postDbGateway->getPostFromPostId($this->uriParser->getPostId());
            $postPageView["userId"] = $this->authorizer->getUserId();
            include("/html/postPage.html");
        }

        function deleteAction() {
            if($this->authorizer->canDelete($this->uriParser->getPostId())) {
                $this->deletePost($this->uriParser->getPostId());
            }
            $this->redirect("/html/404.html");
        }

        function deletePost($postId) {
            $this->postDbGateway->deletePost($postId);
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }
?>