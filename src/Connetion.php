<?php

class Connetion {

    private $host;
    private $user;
    private $password;
    private $database;
    private $connetion;

    public function __construct() {
        $this->host = 'localhost';
        $this->user = 'twitter_db';
        $this->password = 'twitter_db';
        $this->database = 'twitter_db';
    }

    public function getConn() {
        $this->connetion = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connetion->connect_error) {
            die('Blad logowania z baza.' . $this->connetion->connect_error);
        } else {
            //echo('Nawiazano polaczenie z baza');
            return $this->connetion;
        }
    }
    
    public function close() {
        $this->connetion->close();
    }

}

?>