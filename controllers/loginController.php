<?
    include_once(__DIR__ . "/../class/UserCreator.php");

    class LoginController extends BaseController{
        private $userCreator;

        function __construct() {
            parent::__construct();
            $this->userCreator = new UserCreator;
        }
        
        function action() {
            if($this->isLoggedIn()) {
                $this->redirect("/main");
            }

            $action = $this->uriParser->getAction();
            switch($action) {
                case "":
                    $this->handleDefaultLogin();
                    break;
                case "createAccount":
                    $this->handleUserCreation();
                    break;
                default:
                    $this->redirect("/html/404.html");
            }
        }

        function handleDefaultLogin() {
            if($this->isPostRequest()) {
                $this->handleLogin();
            }
            include("/html/loginPage.html");
        }

        function handleUserCreation() {
            if($this->isPostRequest()) {
                if($this->userCreator->successfulUserCreation()) {
                    $this->handleLogin();
                }
                $this->redirect("/main/login/action/createAccount");
            }
            include("/html/createUser.html");   
        }

        function handleLogin() {
            if($this->authenticator->isSuccessfulLogin($_POST["username"], $_POST["password"])){
                $this->redirect("/main");
            }
            $this->redirect("/main/login");
        }
    }
?>