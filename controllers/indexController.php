<?
    class IndexController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            if($this->isPostRequest()) {
                $this->searchHandler();
            }
            else{
                $postArray = $this->postDbGateway->getRecentPosts();
                $userId = $this->authorizer->getUserId();
                $username = $this->authorizer->getUsername();
                include("/html/index.html");
                include("/html/postList.html");
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