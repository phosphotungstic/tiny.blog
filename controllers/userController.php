<?
    class UserController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            if($this->uriParser->uriCheckAssociativePair("action", "logout")) {
                session_unset();
                $this->redirect("/main");
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "post") && $this->authorizer->isLoggedIn()) {
                $this->postHandler();
            }
            elseif($this->uriParser->uriCheckAssociativePair("action", "settings") && $this->authorizer->isLoggedIn()) {
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
                $newPostId = $this->postDbGateway->createPost($this->authorizer->getUserId(), $_POST["textbox"]);
                $this->redirect("/main/post/action/view/postId/" . $newPostId);
            }
            include_once("/html/userPost.html");
        }

        function settingsHandler() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                if($this->isValidPasswordChange()) {
                    $this->userDbGateway->updatePassword($this->authorizer->getUserId(), $_POST["passwordUpdate"]);
                    $this->redirect("/main/user/action/view/userId/" . $this->authorizer->getUserId());
                }
            }
            include_once("/html/userSettings.html");
        }

        function isValidPasswordChange() {
            if($this->authenticator->checkCredentials($this->authorizer->getUsername(), $_POST["oldPassword"])) {
                if(!strcmp($_POST["passwordUpdate"], $_POST["passwordUpdateRetype"])) {
                    return true;
                }
            }
            return false;
        }

        function isInvalidUser() {
            if($this->userDbGateway->isGreaterThanMaxUserId((int)$this->uriParser->getAssociativeValue("userId"))) {
                return true;
            }
            if(!is_numeric($this->uriParser->getAssociativeValue("userId"))) {
                return true;
            }
            return false;
        }

        function displayUserPage() {
            $displayUserId = $this->uriParser->getAssociativeValue("userId");
            $profileUser = $this->userDbGateway->createUserFromUserId($displayUserId);
            $this->userDbGateway->addPostsAndCommentsFromUserClass($profileUser);
            $postArray = $this->postDbGateway->getPostsFromPostIdArray($profileUser->postIds);
            $numberPosts = sizeof($profileUser->postIds);
            $numberComments = sizeof($profileUser->commentIds);

            include_once("/html/userPage.html");
            include_once("/html/postList.html");
        }
    }
?>