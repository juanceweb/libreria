<?php

include_once("connect.php");

//Obtengo Cabecera

if (isset($_GET["id"])) {

	$sql = "SELECT
		movimientos.id AS idmovimiento,
		terceros.denominacion AS tercero,
		terceros.calle,
		terceros.direccion,
		terceros.codigoPostal,
		localidades.denominacion AS localidad,
		partidos.denominacion AS partido,
		provincias.denominacion AS provincia,
		sucursales.orden
		FROM movimientos
		INNER JOIN terceros
		ON movimientos.idTercero = terceros.id
		INNER JOIN sucursales
		ON sucursales.idTerceroSucursal = terceros.id
		INNER JOIN terceros AS centrales
		ON sucursales.idTerceroPadre = centrales.id
		INNER JOIN comprobantes
		ON comprobantes.id = movimientos.idComprobante
		INNER JOIN localidades
		ON terceros.idLocalidad = localidades.id
		INNER JOIN partidos
		ON terceros.idPartido = partidos.id
		INNER JOIN provincias
		ON terceros.idProvincia = provincias.id
		WHERE movimientos.id IN (".$_GET['id'].")
		GROUP BY terceros.id
		ORDER BY sucursales.orden";

	$resultado=mysqli_query($mysqli,$sql);

	//Si obtengo un resultado
	if ($resultado->num_rows > 0) {
		$datos = array();


		//La variable cabecera contiene toda la información de la cabereca del movimiento
		for ($i = 0; $i < $resultado->num_rows ; $i++) {
			$registro = mysqli_fetch_assoc($resultado);
			$registro = array_map('utf8_encode', $registro);
			array_push($datos, $registro);
		}

		//var_dump($datos);

		?>



 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="UTF-8">
 	<title>Impresión de Ruta</title>

 	<link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css" >
 	<link rel="stylesheet" href="bootstrap3/css/bootstrap-theme.min.css" >
 	<link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">

	<style>

		body {
			font-family: 'Varela Round', sans-serif;
			font-size: 9pt;
			width: 29.7cm;
			height: 21cm;
		}

		page[size="A4"] {
			display: block;
			margin: 0 auto;
			padding:20px;
			width: 29.7cm;
			height: 21cm;
			display: block;	
			text-align: center;	
		}

		#panelcontrol{
			position: fixed;
			top: 0px;
			right: 	0px;
		}

		@media print {

			body, page[size="A4"] {
				margin: 0;
				box-shadow: 0;
			}

			.container{
				width: calc(29.7cm - 50px);
			}

			#panelcontrol{
				display: none;
			}

			#panelcontrol button{
				display: none;
			}

			#mensaje{
				display: none;
			}

			page[size="A4"] {
				display: block;
				margin: 0 auto;
				padding:20px;
				width: 29.7cm;
				height: 21cm;
				text-align: center;
			}

		}
	</style>

 </head>
 <body>
	

			 	<nav id="panelcontrol" class="navbar navbar-default">
			 		<div class="container-fluid">
			 			<div class="navbar-header">				
			 				<a type="button" href="movimientoslist.php" class="btn btn-primary navbar-btn">
			 					<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
			 				</a>
			 				<button type="button" onclick="imprimir()" class="btn btn-primary navbar-btn">
								<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
			 				</button>
			 			</div>
			 		</div>
			 	</nav>

			 	<div class="container">
			 		<div class="row">
			 			<div class="col-xs-12">
							<table class="table table-striped" id="ruta">
								<thead>
									<tr>
										<th
											colspan="5"
											style="text-align:left;"
										>
											Hoja de Ruta
										</th>
										<th
											colspan="2"
											style="text-align:right;"
										>
											<?php
											//Imprimo la fecha de hoy
											echo date("d/m/Y");
											?>
										</th>
									</tr>
									<tr>
										<th>Orden</th>
										<th>Sucursal</th>
										<th>Domicilio</th>
										<th>Localidad</th>
										<th>Partido</th>
										<th style="width:5cm; text-align: center;">Firma</th>
										<th style="width:5cm; text-align: center;">Aclaración</th>
									</tr>
								</thead>
							 	<?php 

							 		foreach ($datos as $key => $value) {
										?>
											<tr>
												<td><?php echo "#". ($key + 1) ?></td>
												<td><?php echo $value["tercero"] ?></td>
												<td><?php echo $value["calle"]." ".$value["direccion"] ?></td>
												<td><?php echo $value["localidad"] ?></td>
												<td><?php echo $value["partido"] ?></td>
												<td></td>
												<td></td>
											</tr>
										<?php 			
							 		}

							 	 ?>
								
							</table>
			 				
			 			</div>
			 		</div>
			 	</div>
			




	<script src="jquery/jquery-1.12.4.min.js"></script>

	<script>
		function imprimir(){

			window.print();

		}
	</script> 	 


 </body>
 </html>





		<?php

	}else{
		?>
		<pre>No se pudo obtener la cabecera</pre>
		<?php
		return;
	}


 ?>





<?php  

	
}else{
	?>
	<pre>No se indicó el id del movimiento</pre>
	<?php
	return;
}


?>
