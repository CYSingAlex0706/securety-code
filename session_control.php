<?php
session_start();

// Set session timeout (in seconds)
$session_timeout = 60; // 1 minutes

function check_session_timeout() {
    global $session_timeout;
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Check if the last activity time is set
    if (isset($_SESSION['last_activity'])) {
        // Calculate the session's age
        $session_age = time() - $_SESSION['last_activity'];
        
        // If the session has expired
        if ($session_age > $session_timeout) {
            // Destroy the session
            session_unset();
            session_destroy();
            // Redirect to login page with a timeout message
            header("Location: login.php?timeout=1");
            exit();
        }
    }

    // Update last activity time stamp
    $_SESSION['last_activity'] = time();
}
?>