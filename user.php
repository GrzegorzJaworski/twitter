<?php
session_start();

include_once 'src/Connetion.php';
include_once 'src/Tweet.php';
include_once 'src/User.php';
include_once 'src/Messages.php';

if ($_SESSION['logiIn']) {
    if (isset($_POST['message']) && trim($_POST['message']) != "") {
        $connetion = new Connetion();
        $conn = $connetion->getConn();
        $message = new Messages();
        $text = $conn->real_escape_string($_POST['message']);
        $message->setText($text);
        $message->setSenderId($_SESSION['id']);
        $message->setRecipientId($_GET['userId']);
        $message->setCreationDate(date('Y-m-d H:i:s'));
        $message->saveToDB($connetion->getConn());
        $connetion->close();
        $connetion=null;
    }
    if (isset($_GET['userId']) && trim($_GET['userId']) != "" &&
        isset($_GET['username']) && trim($_GET['username']) != ""
        ) {
        $tweets = new Tweet();
        $connetion = new Connetion();
        $tweets = $tweets->loadAllTweetsByUserId($connetion->getConn(), $_GET['userId']);
        $connetion->close();
        $connetion=null;
    } else {
        header("Location: index.php");
    }
} else {
        header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body>
        <a href="index.php">Powrót </a><br>
        <div class="tweets" align="center">
            <h3>Użytkownik: <?= $_GET['username'] ?></h3>
            <?php
            
            foreach ($tweets as $tweet) {
                $connetion = new Connetion();
                $user = User::loadUserById($connetion->getConn(), $tweet->getUserId());
                $connetion->close();
                $connetion = null;
                echo("<table border='1'>");
                    echo("<tr>");
                        echo("<th>");
                            echo($user->getUsername());
                        echo("</th>");
                        echo("<th>");
                            echo($tweet->getCreationDate());
                        echo("</th>");
                    echo("</tr>");
                    echo("<tr>");
                        echo("<td colspan='2'>");
                            echo($tweet->getTweet());
                        echo("</td>");
                    echo("</tr>");
                echo("</table>");    
            }
            ?>
        </div>
        <div class="message">
            <form action="#" method="POST">
                <label>
                    Napisz wiadomosć do użytkownika: <?= $_GET['username'] ?>
                    <textarea  name="message" rows="5" cols="25" placeholder="Wiadomość"></textarea>
                    <button type="submit">Wyślij</button>
                </label>
            </form>
        </div>
    </body>
</html>


