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
                $recentPostsArray = $this->postDbGateway->getRecentPosts();
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