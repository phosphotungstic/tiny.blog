<?
    include_once("/db/userDbGateway.php");
    include_once("/db/postDbGateway.php");
    include_once("/class/uriParseClass.php");
    include_once("/class/authorizerClass.php");

    class postController extends baseController{
        private $userDbGatewayObject;
        private $postDbGatewayObject;
        private $uriParser;
        private $authorizerObject;

        function __construct(){
            $this->userDbGatewayObject = new userDbGateway;
            $this->postDbGatewayObject = new postDbGateway;
            $this->uriParser = new uriParse;
            $this->authorizerObject = new Authorizer;
        }

        function deletePost($postId){
            $this->postDbGatewayObject->deletePost($postId);
            $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
        }

        function isValidUri(){
            if($this->postDbGatewayObject->isGreaterThanMaxPostId($this->uriParser->getAssociativeValue("postId"))){
                return false;
            }
            if(!$this->uriParser->isKeySet("postId")){
                return false;
            }
        }

        function isValidPostId(){
            if($this->postDbGatewayObject->doesPostExist((int)$this->uriParser->getAssociativeValue("postId"))){
                return true;
            }
            else{
                return false;
            }
        }

        function action(){
            if($this->isValidUri()){
                $this->redirect("/html/404.html");
            }
            elseif(!$this->isValidPostId()){
                include("/html/postDeleted.html");
            }
            else{
                if(!strcmp($this->uriParser->getAssociativeValue("action"), "view")){
                    $post = $this->postDbGatewayObject->getPostFromPostId($this->uriParser->getAssociativeValue("postId"));
                    include("/html/postPage.html");
                }
                elseif(!strcmp($this->uriParser->getAssociativeValue("action"), "delete")){
                    if($this->authorizerObject->canDelete($this->uriParser->getAssociativeValue("postId"))){
                        $this->deletePost($this->uriParser->getAssociativeValue("postId"));
                    }
                }
                else{
                    $this->redirect("/html/404.html");
                }
            }

        }

    }
?>