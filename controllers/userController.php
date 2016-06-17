<?
    include_once("/db/UserDbGateway.php");
    include_once("/db/PostDbGateway.php");
    include_once("/class/Authenticator.php");
    include_once("/class/Authorizer.php");
    include_once("/class/UriParse.php");

    class UserController extends BaseController{
        private $userDbGatewayObject;
        private $postDbGatewayObject;
        private $authenticatorObject;
        private $authorizerObject;
        private $uriParser;

        function __construct() {
            $this->userDbGatewayObject = new UserDbGateway;
            $this->postDbGatewayObject = new PostDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
            $this->uriParser = new UriParse;
        }

        function action() {
            if($this->uriParser->uriCheckAssociativePair("action", "logout")) {
                session_unset();
                $this->redirect("/main");
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "post") && $this->authorizerObject->isLoggedIn()) {
                $this->postHandler();
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "settings") && $this->authorizerObject->isLoggedIn()) {
                $this->settingsHandler();
            }
            elseif($this->userDbGatewayObject->isGreaterThanMaxUserId((int)$this->uriParser->getAssociativeValue("userId")) || !is_numeric($this->uriParser->getAssociativeValue("userId"))) {
                $this->redirect("/html/404.html");
            }
            else{
                $this->displayUserpage();
            }
        }

        function postHandler() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                $newPostId = $this->postDbGatewayObject->createPost($this->authorizerObject->getUserId(), $_POST["textbox"]);
                $this->redirect("/main/post/action/view/postId/" . $newPostId);
            }
            include_once("/html/userPost.html");
        }

        function settingsHandler() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                if($this->authenticatorObject->checkCredentials($this->authorizerObject->getUsername(), $_POST["oldPassword"]) && !strcmp($_POST["passwordUpdate"], $_POST["passwordUpdateRetype"])) {
                    $this->userDbGatewayObject->updatePassword($this->authorizerObject->getUserId(), $_POST["passwordUpdate"]);
                    $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
                }
            }
            include_once("/html/userSettings.html");
        }

        function displayUserPage(){
            $displayUserId = $this->uriParser->getAssociativeValue("userId");
            $profileUser = $this->userDbGatewayObject->createUserFromUserId($displayUserId);
            $this->userDbGatewayObject->addPostsAndCommentsFromUserClass($profileUser);
            $postArray = $this->postDbGatewayObject->getPostsFromPostIdArrayWithoutPostername($profileUser->postIds);
            $numberPosts = sizeof($profileUser->postIds);
            $numberComments = sizeof($profileUser->commentIds);

            include_once("/html/userPage.html");
        }
    }
?>