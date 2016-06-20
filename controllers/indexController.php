<?
    class IndexController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                $this->searchHandler();
            }
            else{
<<<<<<< HEAD
                $postArray = $this->postDbGateway->getRecentPosts();
                $isLoggedIn = $this->authorizer->isLoggedIn();
                $username = $this->authorizer->getUsername();
=======
                $recentPostsArray = $this->postDbGateway->getRecentPosts();
>>>>>>> parent of 6e6092e... added postList.html to take care of whenever posts are listed. Changed some design.
                include("/html/index.html");
            }
        }

        function searchHandler() {
            if(isset($_POST["usernameSearchSubmit"])) {
                $foundUserId = $this->userDbGateway->getUserIdFromUsername($_POST["usernameSearch"]);
                $this->redirect("/main/user/action/view/userId/" . $foundUserId);
            }
            if(isset($_POST["postSearchSubmit"])) {
                $this->redirect("/main/post/action/view/postId/" . $_POST["postSearch"]);
            }
        }
    }
?>