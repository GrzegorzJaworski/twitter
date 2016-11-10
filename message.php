<?php

session_start();

include 'src/Messages.php';
include 'src/Connetion.php';
include 'src/User.php';

if ($_SESSION['logiIn']) {
    if (isset($_GET['messId']) && is_numeric($_GET['messId'])) {
        
        $connetion = new Connetion();
        $message = Messages::loadMessageById($connetion->getConn(), $_GET['messId']);
        $message->setRead();
        $message->saveToDB($connetion->getConn());
        $connetion->close();
        $connetion = null;
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
        <a href="account.php">Powrót </a><br>
        <div class="tweets" align="center">
             <?php
             $connetion = new Connetion();
             $user = User::loadUserById($connetion->getConn(), $message->getSenderId());
             $connetion->close();
             $connetion = null;
             ?>
            <h3>Wiadomość od użytkownika: <?= $user->getUsername()?>. Utworzony: <?=$message->getCreationDate()?></h3>
            <fieldset><?=$message->getText()?></fieldset>
        </div>
    </body>
</html>