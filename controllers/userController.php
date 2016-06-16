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

        function __construct(){
            $this->userDbGatewayObject = new UserDbGateway;
            $this->postDbGatewayObject = new PostDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
            $this->uriParser = new UriParse;
        }


        function postHandler(){
            $newPostId = $this->postDbGatewayObject->createPost($this->authorizerObject->getUserId(), $_POST["textbox"]);
            $this->redirect("/main/post/action/view/postId/" . $newPostId);
        }

        function settingsHandler(){
            if($this->authenticatorObject->checkCredentials($this->authorizerObject->getUsername(), $_POST["oldPassword"]) && !strcmp($_POST["passwordUpdate"], $_POST["passwordUpdateRetype"])){
                $this->userDbGatewayObject->updatePassword($this->authorizerObject->getUserId(), $_POST["passwordUpdate"]);
                $this->redirect("/main/user/action/view/userId/" . $this->authorizerObject->getUserId());
            }
        }

        function action(){
            if($this->uriParser->uriCheckAssociativePair("action", "logout")){
                session_unset();
                $this->redirect("/main");
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "post") && $this->authorizerObject->isLoggedIn()){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->postHandler();
                }
                include_once("/html/userPost.html");
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "settings") && $this->authorizerObject->isLoggedIn()){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->settingsHandler();
                }
                include_once("/html/userSettings.html");
            }
            elseif($this->userDbGatewayObject->isGreaterThanMaxUserId((int)$this->uriParser->getAssociativeValue("userId")) || !is_numeric($this->uriParser->getAssociativeValue("userId"))){
                $this->redirect("/html/404.html");
            }
            else{
                $profileUser = $this->userDbGatewayObject->createUserFromUserId($this->uriParser->getAssociativeValue("userId"));
                $this->userDbGatewayObject->addPostsAndCommentsFromUserClass($profileUser);
                $postArray = $this->postDbGatewayObject->getPostsFromPostIdArrayWithoutPostername($profileUser->postIds);
                $numberPosts = sizeof($profileUser->postIds);
                $numberComments = sizeof($profileUser->commentIds);

                include_once("/html/userPage.html");
            }
        }
    }
?>