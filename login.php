<?php
include_once 'src/Connetion.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && trim($_POST['email']) != "" &&
        isset($_POST['password']) && trim($_POST['password']) != ""    
        ) {
        session_start();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $connetion = new Connetion();
        $conn = $connetion->getConn();
        $email = $conn->real_escape_string($email);
        $password = $conn->real_escape_string($password);
        
        
        $sql="SELECT id, hashed_password FROM Users WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($_POST['password'], $row[hashed_password])) {
                $_SESSION['logiIn'] = true;
                $_SESSION['id'] = $row['id'];
                $conn->close();
                $conn = null;
                header("Location: index.php");
            }
        } else {
            $error = "<h3>Nieprawidłowy email lub hasło</h3>";
            $conn->close();
            $conn = null;
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
            <input type="text" name="email" placeholder="email">
            <input type="password" name="password" placeholder="hasło">
            <button type="submit">Zaloguj</button>
        </form>
        <a href="addUser.php">Załóż konto</a>
    </body>
</html>