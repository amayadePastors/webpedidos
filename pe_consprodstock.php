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
	<h1>Consultar stock de un producto</h1>
	<?php
	$productos = obtenerProductosTodos($db);
	?>
	<form  action="" method="post">
		<label for="producto">Seleccionar Producto: </label><br/>
		<select name="producto">
			<?php foreach($productos as $producto) : ?>
				<option> <?php echo $producto ?> </option>
			<?php endforeach; ?>
		</select><br/><br/>
		
		<input type="submit" value="Aceptar"><br/><br/>
		<br/><a href="pe_inicio.html">Volver al Menu Principal</a>
		<br/><a href="logout.php">Cerrar Sesion</a>
	</form>
	<?php

	// Aquí va el código al pulsar submit
	if (isset($_POST) && !empty($_POST)) { 
		$producto=$_POST['producto'];
		mostrarStockProducto($producto,$db);
		mysqli_close($db);
	}
	
	?>
	
</body>

</html>
