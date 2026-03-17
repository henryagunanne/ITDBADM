<?php
    // Tear down the session and send the user back to login.
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../pages/login.php");
    exit();
?>
