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
	<h1>Consultar stock de los productos de una línea</h1>
	<?php
	$lineas = obtenerLineas($db);
	?>
	<form  action="" method="post">
		<label for="linea">Seleccionar Línea de productos: </label><br/>
		<select name="linea">
			<?php foreach($lineas as $linea) : ?>
				<option> <?php echo $linea ?> </option>
			<?php endforeach; ?>
		</select><br/><br/>
		
		<input type="submit" value="Aceptar"><br/><br/>
		<br/><a href="pe_inicio.html">Volver al Menu Principal</a>
		<br/><a href="logout.php">Cerrar Sesion</a>
	</form>
	<?php

	// Aquí va el código al pulsar submit
	if (isset($_POST) && !empty($_POST)) { 
		$linea=$_POST['linea'];
		mostrarStockProductosLinea($linea,$db);
		mysqli_close($db);
	}
	
	?>
	
</body>

</html>
