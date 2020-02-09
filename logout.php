<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<body>
	<p>Sesion cerrada</p>
	<a href="pe_login.php">Volver a la ventana de login</a>
</body>
</html>