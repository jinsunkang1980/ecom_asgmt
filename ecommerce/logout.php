<?php
// Clear user cookies by setting them with an expired time
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_name', '', time() - 3600, '/');

// Redirect to homepage after logout
header("Location: index.php");
exit();
?>