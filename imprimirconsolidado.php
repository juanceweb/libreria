<?php

include_once("connect.php");

//Obtengo Cabecera

if (isset($_GET["id"])) {

	$sql = "SELECT
	SUM(`movimientos-detalle`.cant) AS cantidad,
	articulos.id,
	articulos.denominacionExterna,
	articulos.denominacionInterna
	FROM `movimientos-detalle`
	INNER JOIN articulos
	ON `movimientos-detalle`.codProducto = articulos.id
	WHERE `movimientos-detalle`.idMovimientos IN (".$_GET['id'].")
	GROUP BY `movimientos-detalle`.codProducto
	ORDER BY `movimientos-detalle`.codProducto";

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
			<title>Consolidado</title>

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
									<th>Cantidad</th>
									<th>Código</th>
									<th>Den Externa</th>
									<th>Den Interna</th>
								</tr>
							</thead>
							<?php 

							foreach ($datos as $key => $value) {
								?>
								<tr>
									<td><?php echo $value["cantidad"] ?></td>
									<td><?php echo $value["id"] ?></td>
									<td><?php echo $value["denominacionExterna"] ?></td>
									<td><?php echo $value["denominacionInterna"] ?></td>
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

}else{
	?>
	<pre>No se indicó el id del movimiento</pre>
	<?php
	return;
}


?>
