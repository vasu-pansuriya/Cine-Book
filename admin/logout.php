<?php
session_start();
session_destroy();
echo "<script>alert('You have been logged out!'); window.location.href='admin_login.php';</script>";
exit();
?>
