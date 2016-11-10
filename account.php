<?php

session_start();

include_once 'src/Connetion.php';
include_once 'src/Messages.php';
include_once 'src/User.php';

if ($_SESSION['logiIn']) {
    
    $connetion = new Connetion();
    $messages = Messages::loadAllMessagesByUserId($connetion->getConn(), $_SESSION['id']);
    $connetion->close();
    $connetion=null;
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['username']) && trim($_POST['username'] != "") ||
            isset($_POST['password']) && trim($_POST['password'] != "") ||
            isset($_POST['delete']) && trim($_POST['delete'] != "")        
            ) {
            
            if (@$_POST['username']) {
                $connetion = new Connetion();
                $conn = $connetion->getConn();
                $user = User::loadUserById($connetion->getConn(), $_SESSION['id']);
                $username = $conn->real_escape_string($_POST['username']);
                $user->setUsername($username);
                $user->saveToDB($connetion->getConn());
                $connetion->close();
                $connetion = null;
            }
            
            if (@$_POST['password']) {
                $connetion = new Connetion();
                $conn = $connetion->getConn();
                $user = User::loadUserById($connetion->getConn(), $_SESSION['id']);
                $password = $conn->real_escape_string($_POST['password']);
                $user->setHashedPassword($password);
                $user->saveToDB($connetion->getConn());
                $connetion->close();
                $connetion = null;
            }
            
            if (@$_POST['delete']) {
                $connetion = new Connetion();
                $user = new User();
                $user = $user->loadUserById($connetion->getConn(), $_SESSION['id']);
                $user->delete($connetion->getConn());
                $connetion->close();
                $connetion = null;
                session_abort();
                header("Location: login.php");
            }
        }
    }
} else {
    header("Location: login.php");
}
?>

<html>
    <head>
        
    </head>
    <body>
        <a href="index.php">Powrót </a><br>
        <div class="change" align="center">
            <form action="#" method="POST">
                <label>Zmień imię: <br>
                    <input type="text" name="username" placeholder="imię"><br>
                    <button type="submit">Zmień</button>
                </label>
            </form>
            <form action="#" method="POST">
                <label>Zmień hasło: <br>
                    <input type="password" name="password" placeholder="hasło"><br>
                    <button type="submit">Zmień</button>
                </label>
            </form>
            <form action="#" method="POST">
                <label><br>
                    <button name="delete" type="submit" value="1">Usuń konto</button>
                </label>
            </form>
        </div>    
        <div class="message" align="center">
            <h3>Wiadomości:</h3>
            <?php
            if ($messages != null) {
                foreach ($messages as $message) {
                    $connetion = new Connetion();
                    $user = User::loadUserById($connetion->getConn(), $message->getSenderId());
                    $connetion->close();
                    $connetion = null;
                    echo("<table border='1'>");
                        echo("<tr>");
                            echo("<th>");
                                echo('Wiadomość od : '. $user->getUsername());
                            echo("</th>");
                            echo("<th>");
                                echo($message->getCreationDate());
                            echo("</th>");

                        echo("</tr>");
                        echo("<tr>");
                            echo("<td colspan='2'>");
                                if ($message->getRead() == 0) {
                                    $read = ' -Wiadomość nie przeczytana';
                                } else {
                                    $read = ' -Wiadomość przeczytana';
                                }
                                echo( "<a href='message.php?messId=" . $message->getId() ."'>" . substr ($message->getText(),0,30) . "..." . "</a>  <b>" . $read . "</b>");
                            echo("</td>");
                        echo("</tr>");
                    echo("</table>");
                } 
                
            }else {
                echo ('Nie masz żadnej wiadomości! :(');
            }
            ?>
        </div>
    </body>
</html>