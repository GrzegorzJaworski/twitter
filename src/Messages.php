<?php

class Messages {

    private $id;
    private $recipientId;
    private $senderId;
    private $text;
    private $read;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->recipientId = "";
        $this->senderId = "";
        $this->text = "";
        $this->read = 0;
        $this->creationDate = "";
    }

    public function getId() {
        return $this->id;
    }

    public function getRecipientId() {
        return $this->recipientId;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getText() {
        return $this->text;
    }
    
    public function getRead() {
        return $this->read;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setRecipientId($recipientId) {
        $this->recipientId = $recipientId;
    }

    public function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    public function setText($text) {
        $this->text = $text;
    }
    
    public function setRead() {
        $this->read = 1;
    }
    
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }
    
    public function saveToDB(mysqli $connect) {
        if ($this->id == -1) {

            $sql = "INSERT INTO `Messages`(`id`, `recipient_id`, `sender_id`, `text`, `read`, `creation_date`) VALUES (null, $this->recipientId, $this->senderId, '$this->text', $this->read, '$this->creationDate')";
            $result = $connect->query($sql);

            if ($result == true) {
                $this->id = $connect->insert_id;
                return true;
            } else {
                echo $connect->error;
                return false;
            }
        } else {
            $sql = "UPDATE `Messages` SET 
                `recipient_id`=$this->recipientId,
                `sender_id`=$this->senderId,
                `text`='$this->text',
                `read`=$this->read,
                `creation_date`='$this->creationDate'
                 WHERE `id`=$this->id";

            $result = $connect->query($sql);

            if ($result == true) {
                return true;
            } else {
                echo($connect->error);
            }
        }    
    }
    
    static public function loadAllMessagesByUserId(mysqli $connetion, $userId) {
        $sql = "SELECT * FROM Messages 
            WHERE recipient_id = $userId
            ORDER BY creation_date DESC";
        
        $result = $connetion->query($sql);
        $messages = [];

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Messages();
                $loadedMessage->id = $row['id'];
                $loadedMessage->recipientId = $row['recipient_id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->read = $row['read'];
                $loadedMessage->creationDate = $row['creation_date'];
               
                $messages[] = $loadedMessage;
            }
            return $messages;
        }
    } 
    
    static public function loadMessageById(mysqli $connetion, $id) {
        $sql = "SELECT * FROM Messages WHERE id = $id";

        $result = $connetion->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedMessage = new Messages();
            $loadedMessage->id = $row['id'];
            $loadedMessage->recipientId = $row['recipient_id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->read = $row['read'];
            $loadedMessage->creationDate = $row['creation_date'];

            return $loadedMessage;
        } else {
            echo('Nie ma Tweeta o podanym ID');
        }
    }


}
?>

