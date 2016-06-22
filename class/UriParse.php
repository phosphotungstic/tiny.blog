<?
    class UriParse{
        private $uri;
        private $uriComponents;
        private $uriAssociativeArray;

        function __construct() {
            $this->uri = $_SERVER['REQUEST_URI'];
            $this->createUriComponents();
            $this->createUriAssociativeArray();
        }

        private function createUriComponents() {
            $this->uriComponents = explode("/", $this->uri);
            array_shift($this->uriComponents);
        }

        private function createUriAssociativeArray() {
            $this->uriAssociativeArray = array();
            $uriComponentsPairs = array_chunk($this->uriComponents, 2);

            foreach($uriComponentsPairs as $uriKeyValuePairs) {
                $this->uriAssociativeArray[$uriKeyValuePairs[0]] = $uriKeyValuePairs[1];
            }
        }

        public function getAssociativeValue($key) {
            return $this->uriAssociativeArray[$key];
        }

        public function getPostId() {
            if(!$this->isKeySet("postId")) {
                return -1;
            }
            else{
                return $this->uriAssociativeArray["postId"];
            }
        }

        public function getUserId() {
            if(!$this->isKeySet("userId")) {
                return -1;
            }
            else{
                return $this->uriAssociativeArray["userId"];
            }
        }

        public function isKeySet($key) {
            if(isset($this->uriAssociativeArray[$key])) {
                return true;
            }
            else{
                return false;
            }
        }

        public function getAction() {
            if(!$this->isKeySet("action")) {
                return "";
            }
            else{
                return $this->uriAssociativeArray["action"];
            }
        }
    }
?>