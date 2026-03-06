<?php
session_start();

// Destroy all session data
$_SESSION = array();
session_destroy();

// Use JavaScript for a smooth redirection to the homepage
echo "<script>
        alert('Logging out safely...');
        window.location.href='index.php';
      </script>";
exit();
?>
