<?php

include_once("connect.php");

$sql="SELECT configuracion.*,
  `tipos-documentos`.denominacion AS tipodoc,
  partidos.denominacion AS partido,
  provincias.denominacion AS provincia,
  paises.denominacion AS pais,
  `condiciones-iva`.denominacion AS condiva,
  localidades.denominacion AS localidad
FROM configuracion
  INNER JOIN `tipos-documentos` ON configuracion.idTipoDoc =
    `tipos-documentos`.id
  INNER JOIN partidos ON partidos.id = configuracion.idPartido
  INNER JOIN provincias ON provincias.id = configuracion.idProvincia
  INNER JOIN paises ON paises.id = configuracion.idPais
  INNER JOIN `condiciones-iva` ON `condiciones-iva`.id =
    configuracion.idCondicionIva
  INNER JOIN localidades ON localidades.id = configuracion.idLocalidad";

$resultado = mysqli_query($mysqli,$sql);

if ($resultado) {
	if (mysqli_num_rows($resultado) > 0) {
		$registro = mysqli_fetch_assoc($resultado);
		$configuracion = array_map("utf8_encode", $registro);
	}
}


$sql="SELECT movimientos.fecha AS cabecerafecha,
  terceros.razonSocial AS tercerorazonsocial,
  `condiciones-iva`.denominacion AS tercerocondicioniva,
  `tipos-documentos`.denominacion AS tercerotipodocumento,
  terceros.documento AS tercerodocumento,
  paises.denominacion AS terceropais,
  provincias.denominacion AS terceroprovincia,
  partidos.denominacion AS terceropartido,
  localidades.denominacion AS tercerolocalidad,
  terceros.calle AS tercerocalle,
  terceros.direccion AS tercerodireccion,
  comprobantes.denominacion AS cabeceracomrpobante,
  comprobantes.discriminaIVA AS cabeceradiscriminaiva,
  comprobantes.seAutoriza AS cabeceraseautoriza,
  comprobantes.letra AS cabeceraletra,
  movimientos.importeTotal AS cabeceraimportetotal,
  movimientos.importeIva AS cabeceraimporteiva,
  movimientos.importeNeto AS cabeceraimporteneto,
  movimientos.ptoVenta AS cabecerapuntoventa,
  movimientos.nroComprobante AS cabeceranumerocomprobante,
  movimientos.cae AS cabeceracae,
  movimientos.comentarios AS cabeceracomentarios,
  movimientos.vtoCae AS cabeceravencimientocae,
  SUM(`movimientos-detalle`.cant) AS detallecantidad,
  `unidades-medida`.denominacionCorta AS detalleunidadmedida,
  `movimientos-detalle`.codProducto AS detallecodigoproducto,
  `movimientos-detalle`.nombreProducto AS detallenombreproducto,
  articulos.denominacionExterna AS detalledenominacionproducto,
  articulos.denominacionInterna AS detalledenominacioninternaproducto,  
  `movimientos-detalle`.importeUnitario AS detalleimporteunitario,
  SUM(`movimientos-detalle`.importeTotal) AS detalleimportetotal,
  SUM(`movimientos-detalle`.importeIva) AS detalleimporteiva,
  SUM(`movimientos-detalle`.importeNeto) AS detalleimporteneto,
  `alicuotas-iva`.valor AS detallealicuotaiva,
  terceros.domicilioFiscal AS tercerodomiciliofiscal,
  paises1.denominacion AS terceropaisfiscal,
  provincias1.denominacion AS terceroprovinciafiscal,
  partidos1.denominacion AS terceropartidofiscal,
  localidades1.denominacion AS tercerolocalidadfiscal,
  terceros.calleFiscal AS tercerocallefiscal,
  terceros.direccionFiscal AS tercerodireccionfiscal
FROM movimientos
  LEFT JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos =
    movimientos.id
  LEFT JOIN terceros ON movimientos.idTercero = terceros.id
  LEFT JOIN `condiciones-iva` ON terceros.condicionIva = `condiciones-iva`.id
  LEFT JOIN `tipos-documentos` ON terceros.tipoDoc = `tipos-documentos`.id
  LEFT JOIN paises ON terceros.idPais = paises.id
  LEFT JOIN provincias ON terceros.idProvincia = provincias.id
  LEFT JOIN partidos ON terceros.idPartido = partidos.id
  LEFT JOIN localidades ON terceros.idLocalidad = localidades.id
  LEFT JOIN comprobantes ON movimientos.idComprobante = comprobantes.id
  LEFT JOIN `unidades-medida` ON `movimientos-detalle`.idUnidadMedida =
    `unidades-medida`.id
  LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id
  LEFT JOIN `alicuotas-iva` ON `movimientos-detalle`.alicuotaIva =
    `alicuotas-iva`.id
  LEFT JOIN paises paises1 ON terceros.idPaisFiscal = paises1.id
  LEFT JOIN provincias provincias1 ON terceros.idProvinciaFiscal =
    provincias1.id
  LEFT JOIN partidos partidos1 ON terceros.idPartidoFiscal = partidos1.id
  LEFT JOIN localidades localidades1 ON terceros.idLocalidadFiscal =
    localidades1.id
  WHERE movimientos.id='".$_GET["id"]."'
  GROUP BY `movimientos-detalle`.codProducto, `movimientos-detalle`.importeUnitario, `movimientos-detalle`.nombreProducto
  ORDER BY detallecodigoproducto";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows>0) {

		$respuesta=array();

		for ($i=0; $i < $resultado->num_rows; $i++) { 
			$registro=mysqli_fetch_assoc($resultado);
			$registro = array_map('utf8_encode', $registro);
			array_push($respuesta, $registro);				
		}

	}

$sql = "SELECT group_concat(
		DISTINCT CONCAT(
			comprobantes.denominacion, ' ', `movimientos1`.ptoVenta, '-', `movimientos1`.nroComprobante )SEPARATOR ', ' ) AS detalle
			FROM `movimientos-detalle`
			INNER JOIN `movimientos-detalle` `movimientos-detalle1`
			ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id
			INNER JOIN movimientos movimientos1
			ON `movimientos-detalle1`.idMovimientos = movimientos1.id
			INNER JOIN comprobantes
			ON movimientos1.idComprobante = comprobantes.id
			WHERE `movimientos-detalle`.idMovimientos = ".$_GET["id"];

$movimientosasociados = "";

$resultado=mysqli_query($mysqli,$sql);

if ($resultado->num_rows == 1) {

	$registro = mysqli_fetch_assoc($resultado);
	$registro = array_map('utf8_encode', $registro);
	$movimientosasociados = $registro["detalle"];
}	

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Impresión de Movimiento</title>

	<style>

		body {
		  font-family: arial,helvetica, sans-serif;
		}

		table{
			border-collapse: collapse;
		}

		thead{
			background-color: #bbb;
			color:white;
			text-align: left;
		}

		#detalle tbody tr{
			border-bottom: dotted thin;
			font-size: 8pt;
		}

		.impar{
			background-color: #efefef;	
		}

		page[size="A4"] {
		  background: white;
		  display: block;
		  margin: 0 auto;
		  padding:20px;
		}

		h1,h2,h3,h4,h5,h6{
			margin-top: 0;
			margin-bottom: 0;
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
		}


	#panelcontrol{
		position: fixed;
		top: -8px;
		left: 0px;
		padding: 14px;
		background: rgba(153, 153, 153, 0.16);
		width: 100%;
	}		
		
	</style>

</head>
<body>

	<div id="panelcontrol">
		<button style="margin-right:10px" class="btn btn-primary" onclick="volver('movimientoslist.php')">
			Volver
		</button>		

		<button class="btn btn-primary" onclick="window.print();">
			Imprimir
		</button>
		<h3 id="mensaje"></h3>
	</div>

	<page size="A4">
		<table style="border-collapse: collapse;" width="100%">
			<tbody>
				<tr>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
				</tr>
				<tr>
					<td colspan="5" rowspan="3" align="center">
						<img width="100%" src="img/<?php echo $configuracion["logo"] ?>" alt="">
						<h4><?php echo $configuracion["calle"]." ".$configuracion["direccion"]." ".$configuracion["localidad"]." ".$configuracion["partido"] ?></h4>
						<h4>(C.P.: <?php echo $configuracion["codigoPostal"] ?>) <?php echo $configuracion["provincia"] ?></h4>
						<h4>Tel.: <?php echo $configuracion["telefono"] ?></h4>
						<h4><?php echo $configuracion["email"] ?></h4>
						<h4><?php echo $configuracion["condiva"] ?></h4>	
					</td>
					<td colspan="2" align="center" style="font-size: 32pt;position: 0;vertical-align: top;padding-top: 20px;">
						<?php echo $respuesta[0]["cabeceraletra"] ?>
					</td>
					<td colspan="5" rowspan="3" align="left" style="padding-left: 10px;">
						<h2><?php echo $respuesta[0]["cabeceracomrpobante"] ?></h2>
						<h2>Nº <?php echo $respuesta[0]["cabecerapuntoventa"]."-".$respuesta[0]["cabeceranumerocomprobante"] ?></h2>						
						<h2>Fecha <?php echo $respuesta[0]["cabecerafecha"] ?></h2>
						<h4><?php echo $configuracion["tipodoc"] ?>: <?php echo $configuracion["documento"] ?></h4>
						<h4>Ing. Brutos: <?php echo $configuracion["ingresosBrutos"] ?></h4>
						<h4>Inicio de Actividades: <?php echo $configuracion["inicioActividades"] ?></h4>				
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>
		<table style="border-collapse: collapse;" width="100%">
			<tbody>
				<tr>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
					<td style="width:100px"></td>
				</tr>				
				<tr>
					<td colspan="12" style="border-top: double;padding-top: 5px">
						Sres: <?php echo $respuesta[0]["tercerorazonsocial"] ?>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						IVA: <?php echo $respuesta[0]["tercerocondicioniva"] ?>
					</td>
					<td colspan="6">
						<?php echo $respuesta[0]["tercerotipodocumento"]." ".$respuesta[0]["tercerodocumento"] ?>
					</td>
				</tr>

				<tr>
					<td colspan="12" style="border-bottom: double;padding-bottom: 5px">
						Domicilio: <?php echo $respuesta[0]["terceropais"]." ".$respuesta[0]["terceroprovincia"]." ".$respuesta[0]["terceropartido"]." ".$respuesta[0]["tercerolocalidad"]." ".$respuesta[0]["tercerocalle"]." ".$respuesta[0]["tercerodireccion"] ?>
					</td>
				</tr>

				<?php 

				if ($respuesta[0]["tercerodomiciliofiscal"] == 1) {
				?>

				<tr>
					<td colspan="12" style="border-bottom: double;padding-bottom: 5px">
						Domicilio Fiscal: <?php echo $respuesta[0]["terceropaisfiscal"]." ".$respuesta[0]["terceroprovinciafiscal"]." ".$respuesta[0]["terceropartidofiscal"]." ".$respuesta[0]["tercerolocalidadfiscal"]." ".$respuesta[0]["tercerocallefiscal"]." ".$respuesta[0]["tercerodireccionfiscal"] ?>
					</td>
				</tr>

				<?php	
				}

				 ?>

			</tbody>
		</table>

		<table id="detalle" style="margin-top:10px" width="100%">
			<thead>
				<tr>
					<th>Código</th>
					<th>Cantidad</th>
					<th>Concepto</th>
					<th>Imp Unit</th>
					<?php 

						if ($respuesta[0]["cabeceradiscriminaiva"]==1) {
							?>
								<th>Imp Total</th>
								<th>Iva</th>				
							<?php
						}

					?>
					<th>Imp Neto</th>
				</tr>
			</thead>
			<tbody>
				<?php

				for ($i=0; $i < count($respuesta); $i++) { 
					?>

						<tr class="<?php echo $i%2==0?'':'impar' ?>">
							<td><?php echo $respuesta[$i]["detallecodigoproducto"] ?></td>
							<td><?php echo $respuesta[$i]["detallecantidad"]." ".$respuesta[$i]["detalleunidadmedida"] ?></td>
							<td><?php echo $respuesta[$i]["detalledenominacionproducto"]." ".$respuesta[$i]["detalledenominacioninternaproducto"]?></td>
							<?php
								if ($respuesta[0]["cabeceradiscriminaiva"]==1) {
									?>
										<td>$ <?php echo $respuesta[$i]["detalleimporteunitario"] ?></td>	
									<?php
								}else{
									?>
										<td>$ <?php echo $respuesta[$i]["detalleimporteunitario"]+($respuesta[$i]["detalleimporteunitario"]*$respuesta[$i]["detallealicuotaiva"]/100) ?></td>	
									<?php									
								}
							?>								
							<?php
								if ($respuesta[0]["cabeceradiscriminaiva"]==1) {
									?>
										<td>$ <?php echo $respuesta[$i]["detalleimportetotal"] ?></td>
										<td>$ <?php echo $respuesta[$i]["detalleimporteneto"]-$respuesta[$i]["detalleimportetotal"]."(".$respuesta[$i]["detallealicuotaiva"]."%)" ?></td>							
											
									<?php
								}
							?>							
							<td>$ <?php echo $respuesta[$i]["detalleimporteneto"] ?></td>
						</tr>																											
					
					<?php
				}

				?>
			</tbody>
		</table>

		<table style="border-collapse: collapse;" width="100%">
			<tr>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
				<td style="width:100px"></td>
			</tr>			
			<tr>
				<td align="left" colspan="4" style="border-top: double;padding-top: 15px;border-bottom: double;padding-bottom: 5px">

		<?php

			if ($movimientosasociados != "") {
				?>
					<p><?php echo "Movimientos Asociados: ".$movimientosasociados ?></p><br>
				<?php
			}

		 ?>					
					<?php echo $respuesta[0]["cabeceracomentarios"] ?>
				</td>				
				<td align="left" colspan="4" style="border-top: double;padding-top: 15px;border-bottom: double;padding-bottom: 5px">
					CAE:  <?php echo $respuesta[0]["cabeceracae"] ?><br>
					Vto. CAE:  <?php echo $respuesta[0]["cabeceravencimientocae"] ?><br>
				</td>
				<td align="left" colspan="4" style="border-top: double;padding-top: 15px;border-bottom: double;padding-bottom: 5px">
					<?php 
						if ($respuesta[0]["cabeceradiscriminaiva"]==1) {
							?>
								Importe Total: <?php echo $respuesta[0]["cabeceraimportetotal"] ?><br>
								Importe IVA: <?php echo $respuesta[0]["cabeceraimporteiva"] ?><br>
							<?php
						}
					?>
					Importe Neto: <?php echo $respuesta[0]["cabeceraimporteneto"] ?><br>
				</td>
			</tr>
			<tr>
				<td colspan="12">
					<?php 
						require 'librerias/NumberToLetter.class.php';
						$V=new EnLetras(); 
						$con_letra=strtolower($V->ValorEnLetras($respuesta[0]["cabeceraimporteneto"] ,"Pesos")); 
						echo "Son ".$con_letra;					
					?>
				</td>
			</tr>
			<tr>

			</tr>						
		</table>

	</page>

</body>
</html>

<script>
	
		function volver(destino){
		window.location = "./"+destino;		
	}


</script>


