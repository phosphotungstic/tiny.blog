<?
    include_once("/db/UserDbGateway.php");
    include_once("/db/PostDbGateway.php");
    include_once("/class/Authenticator.php");
    include_once("/class/Authorizer.php");
    include_once("/class/UriParse.php");

    abstract class BaseController{
        public $controller;
        protected $postDbGateway;
        protected $userDbGateway;
        protected $authenticator;
        protected $authorizer;
        protected $uriParser;


        public function __construct() {
            $this->userDbGateway = new UserDbGateway;
            $this->postDbGateway = new PostDbGateway;
            $this->authenticator = new Authenticator;
            $this->authorizer = new Authorizer;
            $this->uriParser = new UriParse;
        }

        protected function redirect($url) {
            header("Location: $url");
            exit();
        }
        
        abstract protected function action();

        protected  function isLoggedInAccess() {
            if($this->authorizer->isLoggedIn()) {
                return true;
            }
            else{
                return false;
            }
        }

        protected function isPostRequest() {
            if(!strcmp($_SERVER['REQUEST_METHOD'], "POST")) {
                return true;
            }
            else{
                return false;
            }
        }
    }

?>