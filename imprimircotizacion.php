<?php

include_once("connect.php");

//Obtengo Cabecera

if (isset($_GET["id"])) {

	$sql = "SELECT
	DATE_FORMAT(cotizaciones.fecha, '%d/%m/%Y') AS cabecerafecha,
	cotizaciones.importeNeto AS cabeceraimporteneto,
	cotizaciones.importeIva AS cabeceraimporteiva,
	cotizaciones.importeTotal AS cabeceraimportetotal,
	cotizaciones.vigencia AS cabeceravigencia,
	cotizaciones.discriminaIVA as discriminaIVA,
	terceros.razonSocial AS tercerorazonsocial,
	terceros.denominacion AS tercerodenominacion,
	terceros.documento AS tercerodocumento,
	terceros.calle AS tercerocalle,
	terceros.direccion AS tercerodireccion,
	paises.denominacion AS terceropais,
	provincias.denominacion AS terceroprovincia,
	partidos.denominacion AS terceropartido,
	localidades.denominacion AS tercerolocalidad,
	terceros.domicilioFiscal AS tercerodomiciliofiscal,
	terceros.calleFiscal AS tercerocallefiscal,
	terceros.direccionFiscal AS tercerodireccionfiscal,
	paisesfiscal.denominacion AS terceropaisfiscal,
	provinciasfiscal.denominacion AS terceroprovinciafiscal,
	partidosfiscal.denominacion AS terceropartidofiscal,
	localidadesfiscal.denominacion AS tercerolocalidadfiscal,
	`condiciones-iva`.denominacion AS tercerocondicioniva,
	`tipos-documentos`.denominacion AS tercerotipodocumento
	FROM cotizaciones
	LEFT JOIN terceros ON cotizaciones.idTercero = terceros.id
	LEFT JOIN `condiciones-iva` ON terceros.condicionIva = `condiciones-iva`.id
	LEFT JOIN `tipos-documentos` ON terceros.tipoDoc = `tipos-documentos`.id
	LEFT JOIN paises ON terceros.idPais = paises.id
	LEFT JOIN provincias ON terceros.idProvincia = provincias.id
	LEFT JOIN partidos ON terceros.idPartido = partidos.id
	LEFT JOIN localidades ON terceros.idLocalidad = localidades.id
	LEFT JOIN paises paisesfiscal ON terceros.idPaisFiscal = paisesfiscal.id
	LEFT JOIN provincias provinciasfiscal ON terceros.idProvinciaFiscal = provinciasfiscal.id
	LEFT JOIN partidos partidosfiscal ON terceros.idPartidoFiscal = partidosfiscal.id
	LEFT JOIN localidades localidadesfiscal ON terceros.idLocalidadFiscal = localidadesfiscal.id
	WHERE cotizaciones.id = ".$_GET["id"];

	$resultado=mysqli_query($mysqli,$sql);

	//Si obtengo un resultado
	if ($resultado->num_rows === 1) {

		$registro = mysqli_fetch_assoc($resultado);
		$cotizacion = array_map('utf8_encode', $registro);

		//Obtengo el detalle
		$sql = "SELECT
		ROUND(SUM(cotizaciones_detalles.cantidad)) AS cantidad,
		cotizaciones_detalles.idAlicuota,
		`alicuotas-iva`.valor AS porcentajeiva,
		cotizaciones_detalles.idArticulo AS codigoproducto,
		articulos.denominacionExterna AS denominacionexterna,
		articulos.denominacionInterna AS denominacioninterna,
		CONCAT(denominacionexterna, ' ', denominacioninterna) AS denominacioncompleta,
		cotizaciones_detalles.precioCotizado AS importeunitario,
		ROUND(cotizaciones_detalles.precioCotizado * `alicuotas-iva`.valor / 100 + cotizaciones_detalles.precioCotizado,2) AS importeunitariototal,
		SUM(cotizaciones_detalles.precioCotizado) AS importetotal,
		ROUND((cotizaciones_detalles.precioCotizado * `alicuotas-iva`.valor / 100 + cotizaciones_detalles.precioCotizado) * ROUND(SUM(cotizaciones_detalles.cantidad)),2) AS importetotaltotal,
		ROUND((cotizaciones_detalles.precioCotizado) * ROUND(SUM(cotizaciones_detalles.cantidad)),2) AS importeunitsiniva,		
		cotizaciones_detalles.referencia AS referencia
		FROM cotizaciones_detalles
		LEFT JOIN articulos ON cotizaciones_detalles.idArticulo = articulos.id
		LEFT JOIN `alicuotas-iva` ON cotizaciones_detalles.idAlicuota = `alicuotas-iva`.id
		WHERE cotizaciones_detalles.idCotizacion = '".$_GET["id"]."'
		GROUP BY cotizaciones_detalles.idArticulo, cotizaciones_detalles.precioCotizado
		ORDER BY cotizaciones_detalles.idArticulo";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows > 0) {

			$cotizacion["detalles"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {

				$registro=mysqli_fetch_assoc($resultado);
				$registro = array_map('utf8_encode', $registro);
				array_push($cotizacion["detalles"], $registro);

			}

			//var_dump($cotizacion);

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
}else{
	?>
	<pre>No se indicó el id de la cotización</pre>
	<?php
	return;
}

if($cotizacion["discriminaIVA"]){

	$elementos = [
		"cabecera" => [
			[
				"x" => 13.7,
				"y" => 2.85,
				"campos" => [
					[
						"valor" => "cabecerafecha"
					]
				],
				"concatenador" => "",
				"pre" => "",
				"post" => "",
				"fontsize" => 11
			],
			[
				"x" => 2.7,
				"y" => 6.9,
				"campos" => [
					[
						"valor" => "tercerorazonsocial"
					],
					[
						"pre" => " (",
						"valor" => "tercerodenominacion",
						"post" => ")"
					]
				]
			],
			[
				"x" => 3.1,
				"y" => 7.43,
				"campos" => [
					[
						"valor" => "tercerocallefiscal"
					],
					[
						"valor" => "tercerodireccionfiscal",
						"pre" => " "
					],
					[
						"valor" => "tercerolocalidadfiscal",
						"pre" => ", "
					],
					[
						"valor" => "terceropartidofiscal",
						"pre" => ", "
					],
					[
						"pre" => "\n(",
						"valor" => "tercerocalle"
					],
					[
						"valor" => "tercerodireccion",
						"pre" => " "
					],
					[
						"valor" => "tercerolocalidad",
						"pre" => ", "
					],
					[
						"valor" => "terceropartido",
						"pre" => ", ",
						"post" => ")"
					],
				]
			],
			[
				"x" => 2.3,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "tercerocondicioniva"
					]
				]
			],
			[
				"x" => 8.9,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "tercerotipodocumento",
						"post" => ": "
					],
					[
						"valor" => "tercerodocumento"
					]
				]
			],
			[
				"x" => 16.3	,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "cabeceravigencia",
						"post" => " días"
					]
				]
			],
			[
				"x" => 2.6,
				"y" => 24.4,
				"fontsize" => 12,
				"campos" => [
					[
						"valor" => "cabeceraimporteneto",
						"pre" => "$ "
					]
				]
			],
			[
				"x" => 9,
				"y" => 24.4,
				"fontsize" => 12,
				"campos" => [
					[
						"valor" => "cabeceraimporteiva",
						"pre" => "$ "
					]
				]
			],
			[
				"x" => 15.4,
				"y" => 24.4,
				"fontsize" => 12,
				"campos" => [
					[
						"valor" => "cabeceraimportetotal",
						"pre" => "$ "
					]
				]
			],
		],
		"detalle" => [
			"top" => 10.4,
			"campos" => [
				[
					"valor" => "codigoproducto",
					"ancho" => 1.2,
					"alineacion" => "right"
				],
				[
					"valor" => "cantidad",
					"ancho" => 1.2,
					"alineacion" => "right"
				],
				[
					"valor" => "denominacionexterna"
				],
				[
					"valor" => "importeunitario",
					"ancho" => 2.3,
					"alineacion" => "right",
					"pre" => "$ "
				],
				[
					"valor" => "importeunitsiniva",
					"ancho" => 2.3,
					"alineacion" => "right",
					"pre" => "$ "
				]
			]
		]
	];
}else{
	$elementos = [
		"cabecera" => [
			[
				"x" => 13.7,
				"y" => 2.85,
				"campos" => [
					[
						"valor" => "cabecerafecha"
					]
				],
				"concatenador" => "",
				"pre" => "",
				"post" => "",
				"fontsize" => 11
			],
			[
				"x" => 2.7,
				"y" => 6.9,
				"campos" => [
					[
						"valor" => "tercerorazonsocial"
					],
					[
						"pre" => " (",
						"valor" => "tercerodenominacion",
						"post" => ")"
					]
				]
			],
			[
				"x" => 3.1,
				"y" => 7.43,
				"campos" => [
					[
						"valor" => "tercerocallefiscal"
					],
					[
						"valor" => "tercerodireccionfiscal",
						"pre" => " "
					],
					[
						"valor" => "tercerolocalidadfiscal",
						"pre" => ", "
					],
					[
						"valor" => "terceropartidofiscal",
						"pre" => ", "
					],
					[
						"pre" => "\n(",
						"valor" => "tercerocalle"
					],
					[
						"valor" => "tercerodireccion",
						"pre" => " "
					],
					[
						"valor" => "tercerolocalidad",
						"pre" => ", "
					],
					[
						"valor" => "terceropartido",
						"pre" => ", ",
						"post" => ")"
					],
				]
			],
			[
				"x" => 2.3,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "tercerocondicioniva"
					]
				]
			],
			[
				"x" => 8.9,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "tercerotipodocumento",
						"post" => ": "
					],
					[
						"valor" => "tercerodocumento"
					]
				]
			],
			[
				"x" => 16.3	,
				"y" => 8.5,
				"campos" => [
					[
						"valor" => "cabeceravigencia",
						"post" => " días"
					]
				]
			],
			[
				"x" => 15.4,
				"y" => 24.4,
				"fontsize" => 12,
				"campos" => [
					[
						"valor" => "cabeceraimportetotal",
						"pre" => "$ "
					]
				]
			],
		],
		"detalle" => [
			"top" => 10.4,
			"campos" => [
				[
					"valor" => "codigoproducto",
					"ancho" => 1.2,
					"alineacion" => "right"
				],
				[
					"valor" => "cantidad",
					"ancho" => 1.2,
					"alineacion" => "right"
				],
				[
					"valor" => "denominacionexterna"
				],
				[
					"valor" => "importeunitariototal",
					"ancho" => 2.3,
					"alineacion" => "right",
					"pre" => "$ "
				],
				[
					"valor" => "importetotaltotal",
					"ancho" => 2.3,
					"alineacion" => "right",
					"pre" => "$ "
				]
			]
		]
	];
}


$registrosporpagina = 30;
$cantidadpaginas = ceil(count($cotizacion["detalles"]) / $registrosporpagina);

 ?>

 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="UTF-8">
 	<title>Impresión de Cotización</title>

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
			background-image: url('img/profa.jpg');
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
				background-image: url('img/profa.jpg')!important;
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
 				<a type="button" href="cotizacioneslist.php" class="btn btn-primary navbar-btn">
 					<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
 				</a>
 				<button type="button" onclick="imprimir()" class="btn btn-primary navbar-btn">
					<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
 				</button>
 			</div>
 		</div>
 	</nav>

 	<?php 

 		for ($pagina = 0; $pagina < $cantidadpaginas; $pagina++) {

 			?> 	

	<page class="not-print" size="A4">

		<?php

			//Por cada elemento cabecera
			foreach ($elementos["cabecera"] as $value) {

				//Inicializo el array de valores
				$valores = array();

				foreach ($value["campos"] as $campo) {

					//Inicializo la variable del valor
					$valor = '';

					//Si tiene valor
					if (array_key_exists("valor", $campo)) {
						if ($cotizacion[$campo["valor"]] != '') {

							//Si tiene pre
							if (array_key_exists("pre", $campo)) {
								$valor .= $campo["pre"];
							}

							$valor .= $cotizacion[$campo["valor"]];

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
					$value["y"] += $pagina * 29.7;

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

			//Detalle

			$altodetalle = $elementos["detalle"]["top"] + $pagina * 29.7; 

			?>

			<table class="table-striped" style="
				top: <?php echo $altodetalle ?>cm;
			">
				<tbody>
					<?php

						//Si es la última página el límite es la cantidad de registros, sino los registros de esa página
						if ($pagina + 1 == $cantidadpaginas ) {
							$limite = count($cotizacion["detalles"]);
						}else{
							$limite = $registrosporpagina + $pagina * $registrosporpagina;
						}

						for ($i = (0 + $pagina * $registrosporpagina); $i < $limite; $i++) {

							$registro = $cotizacion["detalles"][$i];
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
