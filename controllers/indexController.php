<?
    class IndexController extends BaseController{
        function __construct() {
            parent::__construct();
        }

        function action() {
            if($this->isPostRequest()) {
                $this->handleSearch();
            }
            else{
                $this->displayIndex();
            }
        }

        function displayIndex(){
            $indexView = array();
            $indexView["userId"] = $this->authorizer->getUserId();
            $indexView["username"] = $this->authorizer->getUsername();
            include("/html/index.html");

            $postListView = array();
            $postListView["postArray"] = $this->postDbGateway->getRecentPosts();
            include("/html/postList.html");
        }

        function handleSearch() {
            if(isset($_POST["usernameSearchSubmit"])) {
                $this->redirectUsernameSearch();
            }
            if(isset($_POST["postSearchSubmit"])) {
                $this->redirectPostSearch();
            }
        }

        function redirectUsernameSearch() {
            $foundUserId = $this->userDbGateway->getUserIdFromUsername($_POST["usernameSearch"]);
            $this->redirect("/main/user/action/view/userId/" . $foundUserId);
        }

        function redirectPostSearch() {
            $this->redirect("/main/post/action/view/postId/" . $_POST["postSearch"]);
        }
    }
?>