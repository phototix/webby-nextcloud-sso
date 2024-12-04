<?php
// Start the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to a specific page after logout (e.g., homepage or login page)
header("Location: /"); // Replace with your desired redirect URL
exit;
