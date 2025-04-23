<?php
    // Start session
    session_start();

    // Check if user is logged in. If so, log out.
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
    }

    // Redirect to login page
    header("Location: login.php");
    die;

?>