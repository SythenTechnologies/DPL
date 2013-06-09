<?php
	session_start();
	session_destroy();
	echo "You have been logged out <br /><br /><a href=index.php>Click Here to return to Login</a>";
	header('Location: /');
?>