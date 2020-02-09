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
	<h1>Alta pedido</h1>
<?php 

$preciototal=0;
$orderlinenumber=1;
if(crearOrder($db)){
	$ordernumber=$_SESSION["orderNumber"];
	foreach($_SESSION["carrito"] as $clave=>$valor){
		if(!compobarUnidadesSuficientes($clave,$valor,$db))
			echo "Error: No hay unidades suficientes del producto ".$clave;
		else{
			$pvp=obtenerPVP($db,$clave);
			if($pvp!=null){
				if(crearOrderDetails($db,$clave,$valor,$pvp,$ordernumber,$orderlinenumber) && actualizarProducto($db,$clave,$valor)){
					$preciototal+=($pvp*$valor);
					$orderlinenumber+=1;
				}else
					trigger_error ("Ha habido un error a la hora de comprar el producto " . $clave);
				}else
					trigger_error ("Ha habido un error a la hora de obtener el precio del producto " . $clave);
		}
	}
	//Queda crear la orden de pago
	$checknumber=generarCheckNumber($db);
	if($checknumber!=null){
		if(crearPayment($db,$checknumber,$preciototal)){
			echo "Compra realizada correctamente <br/>";
			echo "Precio total de la compra: ".$preciototal."<br/>";
			echo "Su Check Number es " .$checknumber."<br/>";
			$_SESSION["carrito"]=array();
		}
		else
			trigger_error ("Ha habido un error a la hora de realizar la compra.");
	}else
		trigger_error ("Ha habido un error a la hora de crear la orden de pago.");
}

mysqli_close($db);

?>

<br/><a href="logout.php">Cerrar Sesión</a><br/>
<br/><a href="pe_inicio.html">Volver al menu principal</a><br/>

</body>

</html>
