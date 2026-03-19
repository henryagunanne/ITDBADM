<?php
    session_start();
    session_unset();
    session_destroy();
    header('Location: /itdbadm-mp/coffee-backend/index.php');
    exit();
?>
