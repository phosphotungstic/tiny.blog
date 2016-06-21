<?
    class LoginController extends BaseController{
        function __construct() {
            parent::__construct();
        }
        
        function action() {
            if($this->authorizer->isLoggedIn()) {
                $this->redirect("/main");
            }

            if(!$this->uriParser->isKeySet("action")) {
                $this->defaultLoginHandler();
            }
            elseif($this->uriParser->getAction() === "createAccount") {
                $this->createAccountHandler();
            }
            else{
                header("Location: /html/404.html");
            }
        }

        function defaultLoginHandler() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                $this->loginHandler($_POST["username"], $_POST["password"]);
            }
            include("/html/loginPage.html");
        }

        function createAccountHandler() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                $this->accountCreator();
            }
            include("/html/createUser.html");   
        }

        function loginHandler($username, $password) {
            if($this->authenticator->checkCredentials($username, $password)) {
                $this->loadUser($username);
                $this->redirect("/main");
            }
            else{
                $this->redirect("/main/login");
            }
        }

        function loadUser($username) {
            $loggedInUser = $this->userDbGateway->getUser($username);
            $this->authenticator->setLoggedIn(true);
            $this->authenticator->setUsername($loggedInUser->username);
            $this->authenticator->setUserId($loggedInUser->userId);
        }

        function accountCreator() {
            if(!$this->validateAccount()) {
                $this->redirect("/main/login/action/createAccount");
            }
            $this->userDbGateway->addAccount($_POST["username"], $_POST["password"]);
            $this->loginHandler($_POST["username"], $_POST["password"]);
        }

        function validateAccount() {
            $validAccount = true;
            if(strcmp($_POST["password"], $_POST["confirmPassword"])) {
                $validAccount = false;
            }
            if(!$this->userDbGateway->checkUsernameAvailable($_POST["username"])) {
                $validAccount = false;
            }
            if(strlen($_POST["username"]) > 20 || strlen($_POST["password"]) > 20) {
                $validAccount = false;
            }
            return $validAccount;
        }
    }
?>