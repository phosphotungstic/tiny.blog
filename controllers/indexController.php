<?
    include_once("/db/userDbGateway.php");
    include_once("/db/postDbGateway.php");
    include_once("/class/authenticatorClass.php");
    include_once("/class/authorizerClass.php");

    class indexController extends baseController{
        private $postDbGatewayObject;
        private $userDbGatewayObject;
        private $authenticatorObject;
        private $authorizerObject;

        function __construct(){
            $this->userDbGatewayObject = new userDbGateway;
            $this->postDbGatewayObject = new postDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
        }

        function searchHandler(){
            if(isset($_POST["usernameSearchSubmit"])){
                $foundUserId = $this->userDbGatewayObject->getUserIdFromUsername($_POST["usernameSearch"]);
                $this->redirect("/main/user/action/view/userId/" . $foundUserId);
            }
            if(isset($_POST["postSearchSubmit"])){
                $this->redirect("/main/post/action/view/postId/" . $_POST["postSearch"]);
            }
        }

        function action(){
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                $this->searchHandler();
            }
            else{
                $recentPostsArray = $this->postDbGatewayObject->getRecentPosts();
                include("/html/index.html");
            }
        }
    }

?>