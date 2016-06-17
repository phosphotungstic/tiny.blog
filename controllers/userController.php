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
            elseif($this->isInvalidUser()) {
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
                if($this->isValidPasswordChange()) {
                    $this->userDbGatewayObject->updatePassword($this->authorizerObject->getUserId(), $_POST["passwordUpdate"]);
                    $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
                }
            }
            include_once("/html/userSettings.html");
        }

        function isValidPasswordChange() {
            if($this->authenticatorObject->checkCredentials($this->authorizerObject->getUsername(), $_POST["oldPassword"])) {
                if(!strcmp($_POST["passwordUpdate"], $_POST["passwordUpdateRetype"])) {
                    return true;
                }
            }
            return false;
        }

        function isInvalidUser() {
            if($this->userDbGatewayObject->isGreaterThanMaxUserId((int)$this->uriParser->getAssociativeValue("userId"))) {
                return true;
            }
            if(!is_numeric($this->uriParser->getAssociativeValue("userId"))) {
                return true;
            }
            return false;
        }

        function displayUserPage() {
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