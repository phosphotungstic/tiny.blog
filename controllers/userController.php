<?
    class UserController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            $action = $this->uriParser->getAction();

            switch($action) {
                case "logout":
                    session_unset();
                    $this->redirect("/main");
                    break;
                case "post":
                    if($this->isLoggedInAccess()) {
                        $this->postHandler();
                    }
                    else{
                        $this->redirect("/html/404.html");
                    }
                    break;
                case "settings":
                    if($this->isLoggedInAccess()) {
                        $this->settingsHandler();
                    }
                    else{
                        $this->redirect("/html/404.html");
                    }
                    break;
                default:
                    if($this->isInvalidUser()) {
                        $this->redirect("/html/404.html");
                    }
                    else {
                        $this->displayUserPage();
                    }
                    break;
            }

        }

        function postHandler() {
            if($this->isPostRequest()) {
                $newPostId = $this->postDbGateway->createPost($this->authorizer->getUserId(), $_POST["textbox"]);
                $this->redirect("/main/post/action/view/postId/" . $newPostId);
            }
            $userId = $this->authorizer->getUserId();
            include_once("/html/userPost.html");
        }

        function settingsHandler() {
            if($this->isPostRequest()) {
                if($this->isValidPasswordChange()) {
                    $this->userDbGateway->updatePassword($this->authorizer->getUserId(), $_POST["passwordUpdate"]);
                    $this->redirect("/main/user/action/view/userId/" . $this->authorizer->getUserId());
                }
            }

            $userId = $this->authorizer->getUserId();
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
            if($this->userDbGateway->isGreaterThanMaxUserId((int)$this->uriParser->getUserId())) {
                return true;
            }
            if(!is_numeric($this->uriParser->getUserId())) {
                return true;
            }
            return false;
        }

        function displayUserPage() {
            $displayUserId = $this->uriParser->getUserId();
            $profileUser = $this->userDbGateway->getUser($displayUserId);
            $postArray = $profileUser->posts;
            $numberPosts = sizeof($profileUser->posts);
            $ownsPage = $this->authorizer->ownsUserPage();

            include_once("/html/userPage.html");
            include_once("/html/postList.html");
        }
    }
?>