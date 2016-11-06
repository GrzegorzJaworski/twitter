<?php
include_once 'src/User.php';
include_once 'src/Connetion.php';
include_once 'src/Tweet.php';

$connetion = new Connetion();

$tweets = Tweet::loadAllTweets($connetion->getConn());
var_dump($tweets);

//$user = new User;
//$user->setEmail('daria@daria');
//$user->setUsername('Daria');
//$user->setHashedPassword('daria');

//$connetion = new Connetion();
//
//$user = User::loadUserById($connetion->getConn(), 5);
////$user->saveToDB($connetion->getConn());
//var_dump($user);
////$users = User::loadAllUser($connetion->getConn());
//$user->delete($connetion->getConn());
////var_dump($user)
//var_dump($user);
////$user->saveToDB();

$connetion->close();
$connetion = null;

?>