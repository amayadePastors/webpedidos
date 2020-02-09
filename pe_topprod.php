<?php
session_start();
include "conexion.php";
include "funciones.php";
set_error_handler("errores");
echo "BIENVENIDO " . $_SESSION['username'];
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Web pedidos</title>
</head>
<body>
	<h1>Consultar unidades vendidas entre 2 fechas</h1>
	<form  action="" method="post">
		<label for="fechaini">Seleccionar Fecha de inicio:</label>
		<input type="date" name="fechaini"><br>
		<label for="fechafin">Seleccionar Fecha de fin:</label>
		<input type="date" name="fechafin">
		
		<input type="submit" value="Aceptar"><br/><br/>
		<br/><a href="pe_inicio.html">Volver al Menu Principal</a>
		<br/><a href="logout.php">Cerrar Sesion</a>
	</form>
	<?php

	// Aquí va el código al pulsar submit
	if (isset($_POST) && !empty($_POST)) { 
		$fechaini=$_POST['fechaini'];	
		$fechafin=$_POST['fechafin'];
		if($fechaini>$fechafin)
			trigger_error("La feha de fin no puede ser inferior a la de inicio");
		else
			mostrarUnidadesVendidas($fechaini,$fechafin,$db);
		
		mysqli_close($db);
	}
?>	
</body>

</html>
