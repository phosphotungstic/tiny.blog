<?
    include_once("/class/Authorizer.php");
    include_once("/db/postDbGateway.php");

    class Post{
        private $authorizer;
        private $postDbGateway;
        public $postername;
        public $posterId;
        public $postId;
        public $postContent;

        public function __construct($postername, $posterId, $postId, $postContent) {
            $this->authorizer = new Authorizer;
            $this->postDbGateway = new PostDbGateway;
            $this->postername = $postername;
            $this->posterId = $posterId;
            $this->postId = $postId;
            $this->postContent = $postContent;
        }

        public function createPost() {
            return $this->postDbGateway->createPost($this->authorizer->getUserId(), $_POST["textbox"]);
        }

        public function canDelete() {
            return $this->authorizer->getUserId() === $this->posterId;
        }
    }
?>