<?
    class Comment{
        public $commentId;
        public $commenterId;
        public $commentPostId;
        public $commentNumber;
        public $commentContent;

        public function __construct($commentId, $commenterId, $commentPostId, $commentNumber, $commentContent){
            $this->commentId = $commentId;
            $this->commenterId = $commenterId;
            $this->commentPostId = $commentPostId;
            $this->commentNumber = $commentNumber;
            $this->commentContent = $commentContent;
        }
    }