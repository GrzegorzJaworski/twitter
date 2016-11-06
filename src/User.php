<?php

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->hashedPassword = "";
        $this->email = "";
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getHashedPassword() {
        return $this->hashedPassword;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setHashedPassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->hashedPassword = $hashedPassword;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function saveToDB(mysqli $connetion) {
        if ($this->id == -1) {

            $sql = "INSERT INTO Users(username, email, hashed_password) VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connetion->query($sql);

            if ($result == true) {
                $this->id = $connetion->insert_id;
                return true;
            } else {
                echo($connetion->error);
            }
        } else {
            $sql = "UPDATE Users SET 
                    username='$this->username',
                    email='$this->email',
                    hashed_password='$this->hashedPassword'
                WHERE id=$this->id";
            
            $result = $connetion->query($sql);

            if ($result == true) {
                return true;
            } else {
                echo($connetion->error);
            }
        }
    }

    static public function loadUserById(mysqli $connetion, $id) {
        $sql = "SELECT * FROM Users WHERE id = $id";

        $result = $connetion->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];

            return $loadedUser;
        } else {
            echo('Nie ma uztykownika o podanym ID');
        }
    }

    static public function loadAllUser(mysqli $connetion) {
        $sql = "SELECT * FROM Users";
        $users = [];

        $result = $connetion->query($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];

                $users[] = $loadedUser;
            }

            return $users;
        } else {
            return null;
        }
    }
    
    public function delete(mysqli $connetion) {
        if ($this->id != 1) {
            $sql = "DELETE FROM Users WHERE id=$this->id";
            $result = $connetion->query($sql);
            
            if ($result) {
                $this->id = -1;
                echo('Uzytkownik skasowany');
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

}
?>

