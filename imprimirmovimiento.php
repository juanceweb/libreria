<?php

include_once("connect.php");

//Obtengo Cabecera

if (isset($_GET["id"])) {
	
	$idscabecera = explode(",", $_GET["id"]);

	foreach ($idscabecera as $key => $idcabecera) {

	$sql = "SELECT
	DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS cabecerafecha,
	movimientos.importeTotal AS cabeceraimportetotal,
	movimientos.importeIva AS cabeceraimporteiva,
	movimientos.importeNeto AS cabeceraimporteneto,
	movimientos.ptoVenta AS cabecerapuntoventa,
	movimientos.nroComprobante AS cabeceranumerocomprobante,
	movimientos.idDocTercero AS cabeceraiddoctercero,
	movimientos.nroDocTercero AS cabeceranumerodoctercero,
	movimientos.nombreTercero AS cabeceranombretercero,
	movimientos.cae AS cabeceracae,
	movimientos.comentarios AS cabeceracomentarios,
	DATE_FORMAT(movimientos.vtoCae, '%d/%m/%Y') AS cabeceravencimientocae,
	movimientos.condicionVenta AS cabeceracondicionventa,
	movimientos.tipoMovimiento AS cabeceratipomovimiento,
	comprobantes.denominacion AS cabeceracomrpobante,
	comprobantes.discriminaIVA AS cabeceradiscriminaiva,
	comprobantes.seAutoriza AS cabeceraseautoriza,
	comprobantes.letra AS cabeceraletra,
	comprobantes.preimpreso AS preimpreso,
	comprobantes.configuracionImpresion AS configuracionimpresion,
	comprobantes.configuracionImpresionCompra AS configuracionimpresioncompra,
	comprobantes.cantidadRegistros AS cantidadregistros,
	terceros.razonSocial AS tercerorazonsocial,
	movimientos.nombreTercero AS tercerodenominacion,
	movimientos.nroDocTercero AS tercerodocumento,
	terceros.calle AS tercerocalle,
	terceros.direccion AS tercerodireccion,
	paises.denominacion AS terceropais,
	provincias.denominacion AS terceroprovincia,
	partidos.denominacion AS terceropartido,
	localidades.denominacion AS tercerolocalidad,
	terceros.domicilioFiscal AS tercerodomiciliofiscal,
	terceros.calleFiscal AS tercerocallefiscal,
	terceros.direccionFiscal AS tercerodireccionfiscal,
	terceros.id AS terceroid,
	paisesfiscal.denominacion AS terceropaisfiscal,
	provinciasfiscal.denominacion AS terceroprovinciafiscal,
	partidosfiscal.denominacion AS terceropartidofiscal,
	localidadesfiscal.denominacion AS tercerolocalidadfiscal,
	`condiciones-iva`.denominacion AS tercerocondicioniva,
	`tipos-documentos`.denominacion AS tercerotipodocumento
	FROM movimientos
	LEFT JOIN terceros ON movimientos.idTercero = terceros.id
	LEFT JOIN `condiciones-iva` ON terceros.condicionIva = `condiciones-iva`.id
	LEFT JOIN `tipos-documentos` ON movimientos.idDocTercero = `tipos-documentos`.id
	LEFT JOIN paises ON terceros.idPais = paises.id
	LEFT JOIN provincias ON terceros.idProvincia = provincias.id
	LEFT JOIN partidos ON terceros.idPartido = partidos.id
	LEFT JOIN localidades ON terceros.idLocalidad = localidades.id
	LEFT JOIN comprobantes ON movimientos.idComprobante = comprobantes.id
	LEFT JOIN paises paisesfiscal ON terceros.idPaisFiscal = paisesfiscal.id
	LEFT JOIN provincias provinciasfiscal ON terceros.idProvinciaFiscal = provinciasfiscal.id
	LEFT JOIN partidos partidosfiscal ON terceros.idPartidoFiscal = partidosfiscal.id
	LEFT JOIN localidades localidadesfiscal ON terceros.idLocalidadFiscal = localidadesfiscal.id
	WHERE movimientos.id = ".$idcabecera;

	$resultado=mysqli_query($mysqli,$sql);

	//Si obtengo un resultado
	if ($resultado->num_rows === 1) {

		//La variable cabecera contiene toda la información de la cabereca del movimiento
		$registro = mysqli_fetch_assoc($resultado);
		$movimiento = array_map('utf8_encode', $registro);

		//Obtengo los comprobantes asociados
		$sql = "SELECT
		GROUP_CONCAT(
			DISTINCT CONCAT(
				comprobantes.denominacionCorta,
				' ',
				TRIM(LEADING '0' FROM `movimientos1`.nroComprobante)
			)SEPARATOR ', '
		) AS detalle
		FROM `movimientos-detalle`
		INNER JOIN `movimientos-detalle` `movimientos-detalle1`
		ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id
		INNER JOIN movimientos movimientos1
		ON `movimientos-detalle1`.idMovimientos = movimientos1.id
		INNER JOIN comprobantes
		ON movimientos1.idComprobante = comprobantes.id
		WHERE `movimientos-detalle`.idMovimientos = ".$idcabecera;

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows === 1) {
			$registro = mysqli_fetch_assoc($resultado);
			$registro = array_map('utf8_encode', $registro);
			//Guardo el resultado en cabecera
			$movimiento["movimientosasociados"] = $registro["detalle"];
		}

		//Obtengo el detalle
		$sql = "SELECT
		ROUND(SUM(`movimientos-detalle`.cant)) AS cantidad,
		`unidades-medida`.denominacionCorta AS unidadmedida,
		`unidades-medida`.denominacion AS unidadmedidalarga,
		`movimientos-detalle`.codProducto AS codigoproducto,
		`movimientos-detalle`.nombreProducto AS nombreproducto,
		CONCAT(COALESCE(articulos.denominacionExterna, ''), '', COALESCE(`movimientos-detalle`.nombreProducto, '')) AS denominacionexterna,
		articulos.denominacionInterna AS denominacioninterna,
		CONCAT(COALESCE(denominacionexterna, ''), ' ', COALESCE(denominacioninterna, '')) AS denominacioncompleta,
		`movimientos-detalle`.importeUnitario AS importeunitario,
		SUM(`movimientos-detalle`.importeTotal) AS importetotal,
		SUM(`movimientos-detalle`.importeIva) AS importeiva,
		ROUND(SUM(`movimientos-detalle`.importeNeto / `movimientos-detalle`.cant),2) AS importenetounitario,
		SUM(`movimientos-detalle`.importeNeto) AS importeneto,
		`alicuotas-iva`.valor AS alicuotaiva,
		`articulos-proveedores`.codExterno AS codexterno
		FROM `movimientos-detalle`
		LEFT JOIN `unidades-medida` ON `movimientos-detalle`.idUnidadMedida = `unidades-medida`.id
		LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id
		LEFT JOIN `alicuotas-iva` ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
		LEFT JOIN `articulos-proveedores` ON `movimientos-detalle`.codProducto = `articulos-proveedores`.idArticulo AND `articulos-proveedores`.idTercero = '".$movimiento["terceroid"]."'
		WHERE `movimientos-detalle`.idMovimientos= '".$idcabecera."'
		GROUP BY `movimientos-detalle`.codProducto, `movimientos-detalle`.importeUnitario, `movimientos-detalle`.nombreProducto
		ORDER BY codigoproducto";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows > 0) {

			$movimiento["detalles"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {

				$registro=mysqli_fetch_assoc($resultado);
				$registro = array_map('utf8_encode', $registro);
				array_push($movimiento["detalles"], $registro);

			}

			//var_dump($movimiento);

		}else{
			?>
			<pre>No se pudo obtener al menos un detalle</pre>
			<?php
			return;
		}

	}else{
		?>
		<pre>No se pudo obtener la cabecera</pre>
		<?php
		return;
	}




$registrosporpagina = $movimiento["cantidadregistros"];
$cantidadpaginas = ceil(count($movimiento["detalles"]) / $registrosporpagina);

if($movimiento["cabeceratipomovimiento"] == 1){
	$elementos = json_decode($movimiento["configuracionimpresion"], true);
}else{
	if($movimiento["configuracionimpresioncompra"] == ""){
		$elementos = json_decode($movimiento["configuracionimpresion"], true);
	}else{
		$elementos = json_decode($movimiento["configuracionimpresioncompra"], true);
	}
}

 ?>

 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="UTF-8">
 	<title>Impresión de Movimiento</title>

 	<link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css" >
 	<link rel="stylesheet" href="bootstrap3/css/bootstrap-theme.min.css" >
 	<link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">

	<style>

		body {
			font-family: 'Varela Round', sans-serif;
			font-size: 9pt;
			width: 21cm;
			height: 29.7cm;
		}

		table{
			border-collapse: collapse;
			width: 19cm;
    	left: 1cm;
		}

		span, table{
			position: absolute;
		}

		tr{
			line-height: 13px;
		}

		td{
			padding: 0 3px;
		}

		.pre-detalle{
			float: left;
			position: relative;
		}

		page[size="A4"] {
			display: block;
			margin: 0 auto;
			padding:20px;
			width: 21cm;
			height: 29.7cm;
			background-image: url('img/<?php echo $movimiento["preimpreso"] ?>');
			background-size: cover;
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
				width: 21cm;
				height: 29.7cm;
				background-image: url('img/<?php echo $movimiento["preimpreso"] ?>')!important;
				background-size: cover!important;
			}

			.not-print page{
				background-image: none!important;
			}

			.table-striped>tbody>tr:nth-of-type(odd) {
			    background-color: #f9f9f9!important;
			}

		}
	</style>

 </head>
 <body>
	
	<?php 

		if ($key == 0) {
			?>
			 	<nav id="panelcontrol" class="navbar navbar-default">
			 		<div class="container-fluid">
			 			<div class="navbar-header">
							<div class="btn-group" data-toggle="buttons" style="padding: 5px">
							  	<label for="sin-fondo">
							  		<span class="glyphicon glyphicon-tint" aria-hidden="true"></span>				  		
							  	</label>
						    	<input type="radio" name="fondo" checked id="sin-fondo">
						    	<br> 
							  	<label for="con-fondo">
							  		<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
								</label>
						    	<input type="radio" name="fondo" id="con-fondo"> 
							</div> 				
			 				<a type="button" href="movimientoslist.php" class="btn btn-primary navbar-btn">
			 					<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
			 				</a>
			 				<button type="button" onclick="imprimir()" class="btn btn-primary navbar-btn">
								<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
			 				</button>
			 			</div>
			 		</div>
			 	</nav>
			
			<?php
		}

	 ?>


 	<?php 

 		for ($pagina = 0; $pagina < $cantidadpaginas; $pagina++) {

 			?>

	<page size="A4">

		<?php

			//Por cada elemento cabecera
			foreach ($elementos["cabecera"] as $value) {

				$renderizar = FALSE;

				if (isset($value["soloautorizado"])) {
					if ($value["soloautorizado"] && $movimiento["cabeceraseautoriza"] == 1) {
						$renderizar = TRUE;
					}	
				}else{
					$renderizar = TRUE;
				}

				if ($renderizar) {
					
					//Inicializo el array de valores
					$valores = array();

					foreach ($value["campos"] as $campo) {

						//Inicializo la variable del valor
						$valor = '';

						//Si tiene valor
						if (array_key_exists("valor", $campo)) {
							if ($movimiento[$campo["valor"]] != '') {

								//Si tiene pre
								if (array_key_exists("pre", $campo)) {
									$valor .= $campo["pre"];
								}

								$valor .= $movimiento[$campo["valor"]];

								//Si tiene post
								if (array_key_exists("post", $campo)) {
									$valor .= $campo["post"];
								}

								//Pusheo el valor a valores
								array_push($valores, $valor);
							}
						}

					}

					//Concateno los valores con el concatenador configurado, en caso de que lo tenga
					$valor = implode( array_key_exists("concatenador", $value) ? $value["concatenador"] : '', $valores);

					//Si el valor resultante es distinto a vacío
					if ($valor != "") {

						//Calculo la altura dependiendo el orden de página
						$value["y"] += $pagina * 29.7 + $key * 29.7;

						//Genero un span de acuerdo a los estilos configurados
						?>
							<span style="
								top : <?php echo $value["y"]."cm" ?>;
								left: <?php echo $value["x"]."cm" ?>;
								<?php
									if (isset($value["fontsize"])) {
										?>
											font-size: <?php echo $value["fontsize"]."pt"?>;
										<?php
									}
								?>
								<?php
									if (isset($value["width"])) {
										?>
											width: <?php echo $value["width"]."cm"?>;
										<?php
									}
								?>
							">

							<?php

							echo $valor

							?>

							</span>
						<?php
					}
				}				
			}

			//Detalle

			$altodetalle = $elementos["detalle"]["top"] + $pagina * 29.7 + $key * 29.7; 

			?>

			<table class="table-striped" style="
				top: <?php echo $altodetalle ?>cm;
			">
				<tbody>
					<?php

						//Si es la última página el límite es la cantidad de registros, sino los registros de esa página
						if ($pagina + 1 == $cantidadpaginas ) {
							$limite = count($movimiento["detalles"]);
						}else{
							$limite = $registrosporpagina + $pagina * $registrosporpagina;
						}

						for ($i = (0 + $pagina * $registrosporpagina); $i < $limite; $i++) {

							$registro = $movimiento["detalles"][$i];
							?>
							<tr>
								<?php
									foreach ($elementos["detalle"]["campos"] as $campo) {
										?>
											<td style="
												<?php echo array_key_exists('ancho', $campo)?'width: '.$campo['ancho'].'cm':''?>;
												<?php echo array_key_exists('alineacion', $campo)?'text-align: '.$campo['alineacion']:''?>
												">
												<?php echo array_key_exists('pre', $campo)?'<span class="pre-detalle">'.$campo['pre'].'</span>':''?>
												<?php echo $registro[$campo["valor"]]?>
												<?php echo array_key_exists('post', $campo)?$campo['post']:''?>
											</td>
										<?php
									}
								?>
							</tr>
							<?php							
							
						}

					?>
				</tbody>
			</table>

			<?php


		?>

	</page>


 			<?php 

 			
 		}

 	 ?>


	<script src="jquery/jquery-1.12.4.min.js"></script>

	<script>
		function imprimir(){

			if ($("#sin-fondo").prop('checked')) {
				$("body").addClass("not-print");
			}else{
				$("body").removeClass("not-print");
			}

			window.print();

		}
	</script> 	 


 </body>
 </html>






<?php  

	}
}else{
	?>
	<pre>No se indicó el id del movimiento</pre>
	<?php
	return;
}


?>
