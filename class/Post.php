<?
    class Post{
        public $postername;
        public $posterId;
        public $postId;
        public $postContent;
        public $numberComments;

        public function __construct($postername, $posterId, $postId, $postContent, $numberComments) {
            $this->postername = $postername;
            $this->posterId = $posterId;
            $this->postId = $postId;
            $this->postContent = $postContent;
            $this->numberComments = $numberComments;
        }
    }
?>