<?
    include_once("/db/userDbGateway.php");
    include_once("/class/authenticatorClass.php");
    include_once("/class/authorizerClass.php");


    class loginController extends baseController{
        private $userDbGatewayObject;
        private $authenticatorObject;
        private $authorizerObject;

        function __construct(){
            $this->userDbGatewayObject = new userDbGateway;
            $this->authenticatorObject = new Authenticator;
            $this->authorizerObject = new Authorizer;
        }

        function loginHandler($username, $password){
            if($this->authenticatorObject->checkCredentials($username, $password)){
                $this->loadUser($username);
                $this->redirect("/main");
            }
            else{
                $this->redirect("/main/login");
            }
        }

        function loadUser($username){
            $loggedInUser = $this->userDbGatewayObject->createUserFromUsername($username);
            $this->authenticatorObject->setLoggedIn(true);
            $this->authenticatorObject->setUsername($loggedInUser->username);
            $this->authenticatorObject->setUserId($loggedInUser->userId);
        }

        function accountCreator(){
            if(!$this->validateAccount()){
                $this->redirect("/main/login/action/createAccount");
            }

            $this->userDbGatewayObject->addAccount($_POST["username"], $_POST["password"]);
            $this->loginHandler($_POST["username"], $_POST["password"]);
        }

        function validateAccount(){
            $validAccount = true;
            if(strcmp($_POST["password"], $_POST["confirmPassword"])){
                $validAccount = false;
            }

            if(!$this->userDbGatewayObject->checkUsernameAvailable($_POST["username"])){
                $validAccount = false;
            }

            if(strlen($_POST["username"]) > 20 || strlen($_POST["password"]) > 20){
                $validAccount = false;
            }

            return $validAccount;
        }

        function action(){
            include_once("/class/uriParseClass.php");
            $uriParser = new uriParse;

            $possibleUriArray = array("createAccount");

            if($this->authorizerObject->isLoggedIn()){
                $this->redirect("/main");
            }


            if(!$uriParser->isKeySet("action")){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->loginHandler($_POST["username"], $_POST["password"]);
                }
                include("/html/loginPage.html");
            }
            elseif($uriParser->uriCheckAssociativePair("action", "createAccount")){
                if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")){
                    $this->accountCreator();
                }
                include("/html/createUser.html");
            }
            else{
                header("Location: /html/404.html");
            }
        }

    }
?>