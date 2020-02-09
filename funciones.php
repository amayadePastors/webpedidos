<?php
function limpiarCampo($campoformulario) {
  $campoformulario = trim($campoformulario); 
  $campoformulario = stripslashes($campoformulario); 
  $campoformulario = htmlspecialchars($campoformulario);  

  return $campoformulario;   
}

function errores ($error_level,$error_message,$error_file,$error_line){
	echo "<br/>Codigo error: $error_level  </br> Mensaje: $error_message </br>FILE: $error_file </br>LINE: $error_line </br></br>";
}

function obtenerProductos($db) {
	$productos = array();
	$sql = "SELECT productName FROM products where quantityInStock >0";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			array_push($productos,$row['productName']);
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	return $productos;
}

function obtenerProductosTodos($db){
	$productos = array();
	$sql = "SELECT productName FROM products";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			array_push($productos,$row['productName']);
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	return $productos;
}

function obtenerClientes($db){
	$clientes = array();
	$sql = "SELECT customerNumber FROM customers order by customerNumber ASC";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			array_push($clientes,$row['customerNumber']);
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	return $clientes;

}

function obtenerLineas($db){
	$lineas = array();
	$sql = "SELECT productLine FROM productlines";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			array_push($lineas,$row['productLine']);
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	return $lineas;

}

function mostrarPedidosCliente($cliente,$db){
	$sql = "SELECT orders.orderNumber, orders.orderDate, orders.status  FROM orders,customers WHERE customers.customerNumber=orders.customerNumber and customers.customerNumber='$cliente' order by orders.orderDate ASC";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		
		while ($row = mysqli_fetch_assoc($resultado)) {
			echo "<table border=3 width='700px'><tr><th>";
			echo "<table width='700px'><tr><th>ORDER NUMBER</th><th>ORDER DATE</th><th>STATUS</th></tr>";
			$ordernumber=$row['orderNumber'];
			echo "<tr>";
			echo "<td class='order'>".$row['orderNumber']."</td>";
			echo "<td class='order'>".$row['orderDate']."</td>";
			echo "<td class='order'>".$row['status']."</td></tr>";	
			echo "</table></th></tr>";
			$sql2 = "SELECT orderdetails.orderLineNumber, orderdetails.quantityOrdered, products.productName, orderdetails.priceEach FROM products,orders,orderdetails WHERE products.productCode=orderdetails.productCode and orderdetails.orderNumber=orders.orderNumber and orders.orderNumber='$ordernumber' order by orderdetails.orderLineNumber ASC";		
			echo "<tr><td><table width='700px'><tr><th>LINE NUMBER</th><th>CANTIDAD</th><th>NOMBRE PRODUCTO</th><th>PRECIO</th></tr>";
			$resultado2 = mysqli_query($db, $sql2);
			if ($resultado2) {
				while ($row = mysqli_fetch_assoc($resultado2)) {
						echo "<tr>";
						echo "<td>".$row['orderLineNumber']."</td>";
						echo "<td>".$row['quantityOrdered']."</td>";
						echo "<td>".$row['productName']."</td>";
						echo "<td>".$row['priceEach']."</td>";
						echo "</tr>";
					}
				echo "</table></td></tr>";
					
				}
				else
					trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));	
			}
			echo "</th></tr></table>";
		}else
			trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));	
	
}

function obtenerCodigoProducto($producto,$db){
	$codigo = null;
	$sql = "SELECT productCode FROM products WHERE productName = '$producto'";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$codigo = $row['productCode'];
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
		
	return $codigo;
	
}

function compobarUnidadesSuficientes($codigoProducto,$unidades,$db){
	$haySuficientes=true;
	$sql = "SELECT quantityInStock as CANTIDAD FROM products WHERE productCode = '$codigoProducto'";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$cantidad = $row['CANTIDAD'];
			if($cantidad<$unidades)
				$haySuficientes=false;
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
		
	return $haySuficientes;	
}

function annadirAlCarrito($codigoProducto,$unidades,$db){
	$anadido=false;
	 if(!array_key_exists($codigoProducto,$_SESSION["carrito"])){
		$_SESSION["carrito"][$codigoProducto]=$unidades;
		$anadido=true;
	}	 
	 else{
		$unidadespedidas=$_SESSION["carrito"][$codigoProducto];
		$unidadespedidas+=$unidades;
		if(!compobarUnidadesSuficientes($codigoProducto,$unidadespedidas,$db))
			echo "<h3>No hay unidades suficientes</h3>";
		else{
			$_SESSION["carrito"][$codigoProducto]=$unidadespedidas;
			$anadido=true;
		}	
			
	}
	return $anadido;	
}

function mostrarCarrito($db) {
	echo "<br/><br/><table border=1><tr><th>PRODUCTO</th><th>UNIDADES</th><th>PVP</th><th>PRECIO TOTAL</th></tr>";
	foreach($_SESSION["carrito"] as $clave=>$valor){
		$sql = "SELECT buyPrice as PVP,productName as NOMBRE FROM products WHERE productCode = '$clave'";
		$resultado = mysqli_query($db, $sql);
		if ($resultado) {
			while ($row = mysqli_fetch_assoc($resultado)) {
				$pvp = $row['PVP'];
				$nombreproducto = $row['NOMBRE'];
				$preciototal=$pvp*$valor;
				echo "<tr>";
				echo "<td>".$nombreproducto."</td>";
				echo "<td>".$valor."</td>";
				echo "<td>". $pvp ."</td>";
				echo "<td>". $preciototal."</td>";
				echo "</tr>";
			}
		}
		else
			trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));	
	}
	echo '<form action="pe_confirmarpedido.php" method="post">';
	echo '<br/><input type="submit" value="Confirmar Compra"><br/></form>';
	
}

function obtenerPVP($db,$codigoproducto){
	$pvp;
	$sql = "SELECT buyPrice as PVP FROM products WHERE productCode = '$codigoproducto'";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) 
			$pvp = $row['PVP'];
	}
	return $pvp;
}

function generarCheckNumber($db){
	$checknumber;
	$sql = "select max(substr(checkNumber,3)) as MAXIMO from payments where checkNumber like 'AA%'";
	$resultado = mysqli_query($db, $sql);
	if($resultado){
		if (mysqli_num_rows($resultado) == 1){
			$row =mysqli_fetch_assoc($resultado);
			if ($row["MAXIMO"] == null) 
				$checknumber="AA00001";
			else{
				$maximo=(int)($row["MAXIMO"])+1;;
				$checknumber="AA".str_pad((string)$maximo,5,'0',STR_PAD_LEFT);
			}	
		}else 
			trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	
	return $checknumber;
}

function crearOrder($db){
	$creado=false;
	$ordernumber=obtenerOderNumber($db);
	if($ordernumber!=null){
		$id=$_SESSION['id'];
		$sql = "insert into orders (orderNumber,orderDate,requiredDate,shippedDate,status,comments,customerNumber) values ('$ordernumber',sysdate(),sysdate(),null,'In Process',null,'$id')";
	if (mysqli_query($db, $sql)) {
		$creado=true;
		$_SESSION["orderNumber"] =$ordernumber;
	} else 
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	}
	return $creado;
}

function obtenerOderNumber($db){
	$sql = "SELECT max(orderNumber) as MAXIMO FROM orders";
	$codigo;
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) 
			$codigo=$row['MAXIMO']+1;

	}
	else 
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
		
	return $codigo;
}

function crearOrderDetails($db,$clave,$valor,$pvp,$ordernumber,$orderlinenumber){
	$creado=false;
	$sql = "insert into orderdetails (orderNumber,productCode,quantityOrdered,priceEach,orderLineNumber) values ('$ordernumber','$clave','$valor','$pvp','$orderlinenumber')";
	if (mysqli_query($db, $sql)) {
		$creado=true;
	} else 
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
	
	return $creado;
}

function actualizarProducto($db,$clave,$valor){
	$actualizado=false;
	$sql = "update products set quantityInStock=quantityInStock-$valor WHERE productCode = '$clave'";
	if (mysqli_query($db, $sql)) 
		$actualizado=true;
	else 
		trigger_error("Error: " . $sql2 . "<br/>" . mysqli_error($db));
		
	return $actualizado;
}

function crearPayment($db,$checknumber,$preciototal){
	$creado=false;
	$id=$_SESSION['id'];
	$sql = "insert into payments (customerNumber,checkNumber,paymentDate,amount) values ('$id','$checknumber',sysdate(),'$preciototal')";
	if (mysqli_query($db, $sql)) {
		$creado=true;
	} else 
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));
		
	return $creado;
}

function mostrarStockProducto($producto,$db){
	$sql = "SELECT quantityInStock FROM products WHERE productName = '$producto'";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			echo "Producto ". $producto. "<br/>";
			echo "Stock: " . $row['quantityInStock']. " unidades";;
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));

}

function mostrarStockProductosLinea($linea,$db){
	$sql = "SELECT productName, quantityInStock FROM products WHERE productLine = '$linea' order by quantityInStock DESC";
	$resultado = mysqli_query($db, $sql);
	if ($resultado) {
		if (mysqli_num_rows($resultado) < 1)
			echo "No hay productos de esta línea";
		else{
			while ($row = mysqli_fetch_assoc($resultado)) {
				echo $row['productName']." => " . $row['quantityInStock']. " unidades. <br/>";
			}
		}
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));

}

function mostrarUnidadesVendidas($fechaini,$fechafin,$db){
	$sql = "SELECT products.productName, sum(orderdetails.quantityOrdered) as cantidad, orders.orderDate from products,orderdetails,orders WHERE products.productCode=orderdetails.productCode and orderdetails.orderNumber=orders.orderNumber and orders.orderDate >='$fechaini' and orders.orderDate <='$fechafin' group by products.productName order by orders.orderDate ASC";
	$resultado = mysqli_query($db, $sql);
	
	if ($resultado) {
		if (mysqli_num_rows($resultado) > 0){
			echo"<table border=1><tr><th>NOMBRE PRODUCTO</th><th>TOTAL UNIDADES VENDIDAS</th><th>FECHA COMPRA</th></tr>";
			while ($row = mysqli_fetch_assoc($resultado)) {
				echo "<tr>";
				echo "<td>". $row['productName'] . "</td>";
				echo "<td>". $row['cantidad'] . "</td>";
				echo "<td>". $row['orderDate'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}else
			echo "No hay ventas entre estas fechas.";
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));	
}
	
function mostrarPagosCliente($fechaini,$fechafin,$cliente,$db){
	if($fechaini!=null || $fechafin!=null)
		$sql = "SELECT checkNumber, amount, paymentDate from payments WHERE paymentDate >= '$fechaini' and paymentDate <='$fechafin' and customerNumber='$cliente' order by checkNumber ASC ";
	else
		$sql = "SELECT checkNumber, amount, paymentDate from payments WHERE customerNumber='$cliente' order by paymentDate ASC ";
	$resultado = mysqli_query($db, $sql);
	
	if ($resultado) {
		if (mysqli_num_rows($resultado) > 0){ 
			echo"<table border=1><tr><th>CHECK NUMBER</th><th>TOTAL PAGO</th><th>FECHA COMPRA</th></tr>";
			while ($row = mysqli_fetch_assoc($resultado)) {
				echo "<tr>";
				echo "<td>". $row['checkNumber'] . "</td>";
				echo "<td>". $row['amount'] . "</td>";
				echo "<td>". $row['paymentDate'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}else
			echo "No hay ventas para este cliente.";
	}else
		trigger_error("Error: " . $sql . "<br/>" . mysqli_error($db));	
	
	
}	

?>
