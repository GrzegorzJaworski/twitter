<?php
include_once 'src/Connetion.php';
include_once 'src/Tweet.php';
include_once 'src/User.php';
include_once 'src/Comment.php';
session_start();

if ($_SESSION['logiIn']) {
    if (isset($_POST['comment']) && trim($_POST['comment']) != ""){
        $connect = new Connetion();
        $conn = $connect->getConn();
        $comment = new Comment();
        $text = $conn->real_escape_string($_POST['comment']);
        $comment->setText($text);
        $comment->setUserId($_SESSION['id']);
        $comment->setTweetId($_GET['id']);
        $comment->setCreationDate(date('Y-m-d H:i:s'));
        $comment->saveToDB($connect->getConn());
        $connect->close();
        $connect=null;
    }
    if (isset($_GET['id']) && trim($_GET['id']) != "") {
        $tweet = new Tweet();
        $connect = new Connetion();
        $tweet = $tweet->loadTweetById($connect->getConn(), $_GET['id']);
        $comments= Comment::loadAllCommentsByTweetId($connect->getConn(), $_GET['id']);
        $connect->close();
        $connect=null;
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
             <?php
             $connetion = new Connetion();
             $user = User::loadUserById($connetion->getConn(), $tweet->getUserId());
             $connetion->close();
             $connetion = null;
             ?>
            <h3>Tweet użytkownika: <?= $user->getUsername()?>. Utworzony: <?=$tweet->getCreationDate()?></h3>
            <fieldset><?=$tweet->getTweet()?></fieldset>
        </div>
        <div clas="comments">
            Komentaże: <br><br>
            <?php
            
            if ($comments != null) {
                foreach ($comments as $row) {
                    $connetion = new Connetion();
                    $user = User::loadUserById($connetion->getConn(), $row->getUserId());
                    $connetion->close();
                    echo $row->getCreationDate() . " Użytkownik: " . $user->getUsername() . " napisał:<br>";
                    echo $row->getText() . '<br>';
                    echo '<hr>';
                }
            } else {
                echo "Brak. Napisz pierwszy komentarz do tweeta!";
            }
            ?>             
        </div>
        <div class="message">
            <form action="#" method="POST">
                <label><br><hr>
                    Napisz komentarz do tweeta: 
                    <textarea  name="comment" rows="3" cols="25" maxlength="60" placeholder="Max 60 znakow"></textarea>
                    <button type="submit">Wyślij</button>
                </label>
            </form>
        </div>
    </body>
</html>