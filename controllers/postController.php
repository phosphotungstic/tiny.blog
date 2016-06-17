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
                if(!strcmp($this->uriParser->getAssociativeValue("action"), "view")) {
                    $post = $this->postDbGatewayObject->getPostFromPostId($this->uriParser->getAssociativeValue("postId"));
                    include("/html/postPage.html");
                }
                elseif(!strcmp($this->uriParser->getAssociativeValue("action"), "delete")) {
                    if($this->authorizerObject->canDelete($this->uriParser->getAssociativeValue("postId"))) {
                        $this->deletePost($this->uriParser->getAssociativeValue("postId"));
                    }
                    else{
                        $this->redirect("/html/404.html");
                    }
                }
                else{
                    $this->redirect("/html/404.html");
                }
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

        function deletePost($postId) {
            $this->postDbGatewayObject->deletePost($postId);
            $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
        }
    }
?>