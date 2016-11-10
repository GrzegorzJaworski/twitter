<?php
session_start();

include_once 'src/Connetion.php';
include_once 'src/Tweet.php';
include_once 'src/User.php';
include_once 'src/Comment.php';

if ($_SESSION['logiIn']) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['tweet']) && trim($_POST['tweet']) != "") {
           $connetion = new Connetion();
           $conn = $connetion->getConn();
           $tweet = new Tweet();
           $id = $conn->real_escape_string($_SESSION['id']);
           $tweet->setUserId($id);
           $text = $conn->real_escape_string($_POST['tweet']);
           $tweet->setTweet($text);
           $tweet->setCreationDate(date('Y-m-d H:i:s'));
           $tweet->saveToDB($connetion->getConn());
           $connetion->close();
           $connetion = null;
           
        }
    }
    
    $connetion = new Connetion();
    $tweets = Tweet::loadAllTweets($connetion->getConn());
    $connetion->close();
    $connetion = null;
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body>
        <a href="account.php">Konto i wiadomości</a>
        <div id="new_tweet" align="center">
            <form action="#" method="POST">
                <label>Nowy tweet
                    <br>
                    <textarea  name="tweet" maxlength="140" rows="5" cols="25" placeholder="Max 140 znakow"></textarea>
                    <br>
                    <button type="submit">Dodaj</button>
                </label>
            </form>
        </div>
        <div class="tweets" align="center">
            <?php
            foreach ($tweets as $tweet) {
                $connetion = new Connetion();
                $user = User::loadUserById($connetion->getConn(), $tweet->getUserId());
                $commentsQuantity = Comment::countCommentsByTweetId($connetion->getConn(), $tweet->getId());
                $connetion->close();
                $connetion = null;
                echo("<table border='1'>");
                    echo("<tr>");
                        echo("<th>");
                            echo("<a href='user.php?userId=" . $tweet->getUserId() . "&username=" . $user->getUsername() . "'>" . $user->getUsername() . "</a>");
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
                    echo("<tr>");
                        echo("<td>");
                            echo('Liczba komentarzy: ') . $commentsQuantity;
                        echo("</td>");
                        echo("<td>");
                            echo("<a href='tweet.php?id=" . $tweet->getId() . "'>Skomentuj</a>");
                        echo("</td>");
                    echo("</tr>");
                echo("</table>");    
            }
            ?>
        </div>    
    </body>
</html>


