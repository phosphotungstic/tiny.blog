<?
    include_once("/db/userDbGateway.php");
    include_once("/db/postDbGateway.php");
    include_once("/class/authenticatorClass.php");
    include_once("/class/authorizerClass.php");

    class userController extends baseController{
        private $userDbGatewayObject;
        private $postDbGatewayObject;
        private $authenticatorObject;
        private $authorizerObject;

        function __construct(){
            $this->userDbGatewayObject = new userDbGateway;
            $this->postDbGatewayObject = new postDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
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
            include_once("/class/uriParseClass.php");
            $uriParser = new uriParse;

            if($uriParser->uriCheckAssociativePair("action", "logout")){
                session_unset();
                $this->redirect("/main");
            }
            elseif($uriParser->uriCheckAssociativePair("action", "post") && $this->authorizerObject->isLoggedIn()){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->postHandler();
                }
                include_once("/html/userPost.html");
            }
            elseif($uriParser->uriCheckAssociativePair("action", "settings") && $this->authorizerObject->isLoggedIn()){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->settingsHandler();
                }
                include_once("/html/userSettings.html");
            }
            elseif($this->userDbGatewayObject->isGreaterThanMaxUserId((int)$uriParser->getAssociativeValue("userId")) || !is_numeric($uriParser->getAssociativeValue("userId"))){
                $this->redirect("/html/404.html");
            }
            else{
                $profileUser = $this->userDbGatewayObject->createUserFromUserId($uriParser->getAssociativeValue("userId"));
                $this->userDbGatewayObject->addPostsAndCommentsFromUserClass($profileUser);
                $postArray = $this->postDbGatewayObject->getPostsFromPostIdArrayWithoutPostername($profileUser->postIds);
                $numberPosts = sizeof($profileUser->postIds);
                $numberComments = sizeof($profileUser->commentIds);

                include_once("/html/userPage.html");
            }
        }
    }
?>