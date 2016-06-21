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

        public function isKeySet($key) {
            if(!isset($this->uriAssociativeArray[$key])) {
                return false;
            }
            else{
                return true;
            }
        }

        public function getAction(){
            if(!$this->isKeySet("action")){
                return "";
            }
            else{
                return $this->uriAssociativeArray["action"];
            }
        }
    }
?>