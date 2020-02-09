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
	<style>
	th{
		color:#454952;
	}
		td{
			text-align:center;
			color:darkblue;
		}
		.order{
			color:darkgreen;
			font-weight:bold;
		}
	</style>
</head>
<body>
	<h1>Consultar pedidos de un cliente</h1>
	<?php	
	$clientes = obtenerClientes($db);
	?>
	<form  action="" method="post">
		<label for="cliente">Seleccionar el número de cliente: </label><br/>
		<select name="cliente">
			<?php foreach($clientes as $cliente) : ?>
				<option> <?php echo $cliente ?> </option>
			<?php endforeach; ?>
		</select><br/><br/>
		
		<input type="submit" value="Aceptar"><br/><br/>
		<br/><a href="pe_inicio.html">Volver al menu Principal</a>
		<br/><a href="logout.php">Cerrar Sesion</a>
	</form>
	<?php

	// Aquí va el código al pulsar submit
	if (isset($_POST) && !empty($_POST)) { 
		$cliente=$_POST['cliente'];
		mostrarPedidosCliente($cliente,$db);
		mysqli_close($db);
	}
	
	?>
	
</body>

</html>
