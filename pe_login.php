<?php
include "conexion.php";
include "funciones.php";
set_error_handler("errores");
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Web Pedidos</title>
</head>

<body>
	<h1>LOGIN</h1>
	<form  action="" method="post">
		<label for="username">Nombre de usuario:</label>
		<input type='text' name='username' value='' size=9><br/>
		<br/>
		<label for="passcode">Password:</label>
		<input type='text' name='passcode' value='' size=40><br/>
		<br/>
		</br>
		<input type="submit" value="Acceder al Portal">
		</br>
			
	</form>
<?php
if (isset($_POST) && !empty($_POST)) { 
	$nombre=limpiarCampo($_POST['username']);
	$password=limpiarCampo($_POST['passcode']);

	$sql = "SELECT id FROM admin WHERE username ='$nombre' and passcode='$password'";
	$resultado = mysqli_query($db, $sql);
	
	if(mysqli_num_rows($resultado) == 1){
		$row = mysqli_fetch_assoc($resultado);
		$id= $row['id'];
		session_start();
		$_SESSION["username"] =$nombre;
		$_SESSION["id"] =$id;
		$_SESSION["carrito"]=array();
		header("Location: pe_inicio.html");			
	}
	else
		trigger_error ("El usuario y/o la contrasena no son correctos");
			
			
	mysqli_close($db);			
}
?>
</body>

</html>



