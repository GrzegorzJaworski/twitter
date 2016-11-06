<?php
class Tweet {
    
    private $id;
    private $userId;
    private $tweet;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->tweet = "";
        $this->creationDate = "";
    }
    
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setTweet($tweet) {
        $this->tweet = $tweet;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweet() {
        return $this->tweet;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    static public function loadTweetById(mysqli $connetion, $id) {
        $sql = "SELECT * FROM Tweet WHERE id = $id";

        $result = $connetion->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->tweet = $row['tweet'];
            $loadedTweet->creationDate = $row['creationDate'];

            return $loadedTweet;
        } else {
            echo('Nie ma Tweeta o podanym ID');
        }
    }
    
    static public function loadAllTweetsByUserId(mysqli $connetion, $userId) {
        $sql = "SELECT * FROM Tweet WHERE user_id = $userId";
        
        $result = $connetion->query($sql);
        $tweets = [];

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->tweet = $row['tweet'];
                $loadedTweet->creationDate = $row['creationDate'];
                
                $tweets[] = $loadedTweet;
            }
            return $tweets;
        }
    }
    
    static public function loadAllTweets(mysqli $connetion) {
        $sql = "SELECT * FROM Tweet";
        
        $result = $connetion->query($sql);
        $tweets = [];

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->tweet = $row['tweet'];
                $loadedTweet->creationDate = $row['creationDate'];
                
                $tweets[] = $loadedTweet;
            }
            return $tweets;
        }
    }
    
    public function saveToDB(mysqli $connetion) {
        if ($this->id == -1) {

            $sql = "INSERT INTO Tweet(user_id, tweet, creationDate) VALUES ('$this->userId', '$this->tweet', '$this->creationDate')";
            $result = $connetion->query($sql);

            if ($result == true) {
                $this->id = $connetion->insert_id;
                return true;
            } else {
                echo($connetion->error);
            }
        }
    }

}
?>

