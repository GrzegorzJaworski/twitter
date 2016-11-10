<?php

class Comment{
    private $id;
    private $userId;
    private $tweetId;
    private $text;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = '';
        $this->tweetId = '';
        $this->text = '';
        $this->creationDate = '';
    }
    
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function loadCommentById(mysqli $connect, $id){
        $sql = "SELECT * FORM Comment WHERE id=$id";
        $result = $connect->query($sql);
        
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadComment = new Comment();
            $loadComment->id = $row['id'];
            $loadComment->tweetId = $row['tweet_id'];
            $loadComment->userId = $row['user_id'];
            $loadComment->text = $row['text'];
            $loadComment->creationDate = $row['creation_date'];
        }
        return $loadComment;
    }
    
    static public function loadAllCommentsByTweetId(mysqli $connect, $tweetId){
        $sql = "SELECT * FROM Comment 
            WHERE
            tweet_id = $tweetId
            ORDER BY creation_date DESC";
        
        $result = $connect->query($sql);
        $comments = [];

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadComment = new Comment();
                $loadComment->id = $row['id'];
                $loadComment->tweetId = $row['tweet_id'];
                $loadComment->userId = $row['user_id'];
                $loadComment->text = $row['text'];
                $loadComment->creationDate = $row['creation_date'];
               

                $comments[] = $loadComment;
            }
            return $comments;
        }
    }

    static public function countCommentsByTweetId(mysqli $connect, $tweetId) {
        $sql = "SELECT COUNT(*) FROM Comment WHERE tweet_id = $tweetId";
        
        $result = $connect->query($sql);
        
        if($result == TRUE && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $commentsQuantity = $row['COUNT(*)'];
            return $commentsQuantity;
        } else {
            return false;
        }
    }

    public function saveToDB(mysqli $connect) {
        if ($this->id == -1) {

            $sql = "INSERT INTO Comment(tweet_id, user_id, text, creation_date) VALUES ($this->tweetId, $this->userId, '$this->text', '$this->creationDate')";
            $result = $connect->query($sql);

            if ($result == true) {
                $this->id = $connect->insert_id;
                return true;
            } else {
                echo $connect->error;
                return false;
            }
        }    
    }
}
?>
