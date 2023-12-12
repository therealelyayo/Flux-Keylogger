<?php
session_start();

function showPageContent()
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        ?>
            <?php require_once("includes/html.php");?>
        <?php
    } else { $PASSWORD = "password";
        if (isset($_POST['senha']) && $_POST['senha'] === $PASSWORD) {
            $_SESSION['logged_in'] = true;
            $_SESSION['expiry_time'] = time() + 300; // 5 minutes
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo '<form method="post"><label for="senha">Password:</label>
                  <input type="password" name="senha" id="senha">
                  <input type="submit" value="Entrar"></form>';
        }
    }
}

if (isset($_SESSION['expiry_time']) && $_SESSION['expiry_time'] < time()) {
    session_unset();
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

showPageContent();
?>