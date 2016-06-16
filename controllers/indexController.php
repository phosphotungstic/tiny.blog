<?
    include_once("/db/UserDbGateway.php");
    include_once("/db/PostDbGateway.php");
    include_once("/class/Authenticator.php");
    include_once("/class/Authorizer.php");

    class IndexController extends BaseController{
        private $postDbGatewayObject;
        private $userDbGatewayObject;
        private $authenticatorObject;
        private $authorizerObject;

        function __construct() {
            $this->userDbGatewayObject = new UserDbGateway;
            $this->postDbGatewayObject = new PostDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
        }

        function searchHandler() {
            if(isset($_POST["usernameSearchSubmit"])) {
                $foundUserId = $this->userDbGatewayObject->getUserIdFromUsername($_POST["usernameSearch"]);
                $this->redirect("/main/user/action/view/userId/" . $foundUserId);
            }
            if(isset($_POST["postSearchSubmit"])) {
                $this->redirect("/main/post/action/view/postId/" . $_POST["postSearch"]);
            }
        }

        function action() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                $this->searchHandler();
            }
            else{
                $recentPostsArray = $this->postDbGatewayObject->getRecentPosts();
                include("/html/index.html");
            }
        }
    }

?>