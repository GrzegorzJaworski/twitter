<?php
include_once 'src/Connetion.php';
include_once 'src/User.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && trim($_POST['email']) != "" &&
        isset($_POST['username']) && trim($_POST['username']) != "" &&    
        isset($_POST['password']) && trim($_POST['password']) != ""    
        ) {
        session_start();
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $connetion = new Connetion();
        $conn = $connetion->getConn();
        $email = $conn->real_escape_string($email);
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);
        
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setHashedPassword($password);
        if ( $user->saveToDB($connetion->getConn()) ) {
            $_SESSION['logiIn'] = true;
            $_SESSION['id'] = $user->getId();
            $connetion->close();
            $connetion = null;
            header("Location: index.php");
        } else {
            $error = "<h3>Istnieje konto dla podanego emaila.</h3><br>";
            $connetion->close();
            $connetion = null;
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body>
        <?= $error ?>
        <form action="#" method="POST">
            <label>Dodaj nowe konot: <br>
                <input type="text" name="email" placeholder="email"><br>
                <input type="text" name="username" placeholder="imię"><br>
                <input type="password" name="password" placeholder="hasło"><br>
                <button type="submit">Dodaj</button>
            </label>
        </form>
        <a href="login.php">Powrót do logowania</a>
    </body>
</html>

