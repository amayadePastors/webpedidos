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
    <title>Web Pedidos</title>
</head>

<body>
	<h1>Alta Pedido</h1>
	<?php
	$productos = obtenerProductos($db);
	?>
	<form  action="" method="post">
		<label for="producto">Seleccionar Producto: </label><br/>
		<select name="producto">
			<?php foreach($productos as $producto) : ?>
				<option> <?php echo $producto ?> </option>
			<?php endforeach; ?>
		</select><br/><br/>

		<label for="unidades">Unidades a comprar: </label><br/>
		<input type="number" name="unidades" placeholder="unidades"><br/><br/>

		<input type="submit" value="Seleccionar Producto"><br/><br/>
		<br/><a href="logout.php">Cerrar Sesion</a>
		<br/><a href="pe_inicio.html">Volver al Menu Principal</a>
	</form>
	
	<?php
	// Aquí va el código al pulsar submit
	if (isset($_POST) && !empty($_POST)) { 
		$producto=$_POST['producto'];
		$unidades= (integer)$_POST['unidades'];

		if($unidades < 0){
			trigger_error("Error: No se puede comprar un numero negativo de unidades");
		}else{
			$codigoProducto=obtenerCodigoProducto($producto,$db);
			if(!compobarUnidadesSuficientes($codigoProducto,$unidades,$db))
				trigger_error("Error: No hay unidades suficientes de producto");
			else
				if(!annadirAlCarrito($codigoProducto,$unidades,$db)){
					trigger_error("Error: Ha habido un problema al anadir el producto al carrito");
				}			
		}
	
	}
	if (isset($_SESSION["carrito"]) && !empty($_SESSION["carrito"])) 			
		mostrarCarrito($db);
	mysqli_close($db);
	?>
	
</body>

</html>



