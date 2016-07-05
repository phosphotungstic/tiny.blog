<?
    include_once("/class/PasswordChanger.php");
    include_once("/class/User.php");
    include_once("/class/Post.php");

    class UserController extends BaseController{
        private $passwordChanger;
        private $user;
        private $post;

        function __construct() {
            parent::__construct();
            $this->passwordChanger = new PasswordChanger;
            $this->user = new User;
            $this->post = new Post(NULL, NULL, NULL, NULL, NULL);
        }

        function action() {
            if(!$this->user->isValidUser()) {
                $this->redirect("/html/404.html");
            }

            $action = $this->uriParser->getAction();
            if($action === "view") {
                    $this->displayUserPage();
                    exit();
            }

            if($this->isLoggedIn()) {
                switch($action) {
                    case "logout":
                        session_unset();
                        $this->redirect("/main");
                        break;
                    case "post":
                        $this->handleNewPost();
                        break;
                    case "settings":
                        $this->handleSettings();
                        break;
                    default:
                        break;
                }
            }
            else {
                $this->redirect("/html/404.html");
            }
        }

        function handleNewPost() {
            if($this->isPostRequest()) {
                $newPostId = $this->post->createPost();
                $this->redirect("/main/post/action/view/postId/" . $newPostId);
            }
            $userPostView = array();
            $userPostView["userId"] = $this->authorizer->getUserId();
            include_once("/html/userPost.html");
        }

        function handleSettings() {
            if($this->isPostRequest()) {
                if($this->passwordChanger->isValidPasswordChange()) {
                    $this->passwordChanger->changePassword();
                    $this->redirect("/main/index");
                }
                $this->redirect("/main/user/action/settings");
            }
            
            $userSettingsView = array();
            $userSettingsView["userId"] = $this->authorizer->getUserId();
            include_once("/html/userSettings.html");
        }

        function displayUserPage() {
            $userPageView = array();
            $profileUser = $this->user->getDisplayUser();
            $userPageView["profileUser"] = $profileUser;
            $userPageView["numberPosts"] = sizeof($profileUser->posts);
            include_once("/html/userPage.html");

            $postListView = array();
            $postListView["postArray"] = $profileUser->posts;
            include_once("/html/postList.html");
        }
    }
?>