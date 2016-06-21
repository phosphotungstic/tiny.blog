<?
    class Post{
        public $postername;
        public $posterId;
        public $postId;
        public $postContent;

        public function __construct($postername, $posterId, $postId, $postContent) {
            $this->postername = $postername;
            $this->posterId = $posterId;
            $this->postId = $postId;
            $this->postContent = $postContent;
        }
    }
?>