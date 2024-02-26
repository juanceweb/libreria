<?php

include_once("connect.php");

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
  movimientos.condicionVenta AS cabeceraCondicionVenta,
  SUM(`movimientos-detalle`.cant) AS detallecantidad,
  `unidades-medida`.denominacionCorta AS detalleunidadmedida,
  `movimientos-detalle`.codProducto AS detallecodigoproducto,
  `movimientos-detalle`.nombreProducto AS detallenombreproducto,
  articulos.denominacionExterna AS detalledenominacionproducto,
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
		  width: 21cm;
		  height: 29.7cm;		  
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
		  /*background: white;*/
		  display: block;
		  margin: 0 auto;
		  padding:20px;
		  width: 21cm;
		  height: 29.7cm;
		background-image: url('img/factura.jpg');
		background-size: cover;		  
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
			page[size="A4"] {
			  /*background: white;*/
			  display: block;
			  margin: 0 auto;
			  padding:20px;
			  width: 21cm;
			  height: 29.7cm;
			background-image: none;		  
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
		<h2
		style="
		position:absolute;
		top:5.4cm;
		left:16cm;
		">
		<?php echo date("d/m/Y",strtotime($respuesta[0]["cabecerafecha"]))  ?></h2>
		
		<h5 style="
		position:absolute;
		top:8.3cm;
		left:3cm;
		"><?php echo $respuesta[0]["tercerorazonsocial"] ?> <br>
		Domicilio: <?php echo $respuesta[0]["terceropais"]." ".$respuesta[0]["terceroprovincia"]." ".$respuesta[0]["terceropartido"]." ".$respuesta[0]["tercerolocalidad"]." ".$respuesta[0]["tercerocalle"]." ".$respuesta[0]["tercerodireccion"] ?>
		<?php
			if ($respuesta[0]["tercerodomiciliofiscal"] == 1) {
				?>
					<br>Domicilio Fiscal: <?php echo $respuesta[0]["terceropaisfiscal"]." ".$respuesta[0]["terceroprovinciafiscal"]." ".$respuesta[0]["terceropartidofiscal"]." ".$respuesta[0]["tercerolocalidadfiscal"]." ".$respuesta[0]["tercerocallefiscal"]." ".$respuesta[0]["tercerodireccionfiscal"] ?>
				<?php	
			}	
		?>
		</h5>
		<h3 style="
		position:absolute;
		top:10cm;
		left:3cm;
		"><?php echo $respuesta[0]["tercerocondicioniva"] ?></h3>
		<h3 style="
		position:absolute;
		top:10cm;
		left:16cm;
		"><?php echo $respuesta[0]["tercerodocumento"] ?></h3>

		<h3 style="
		position:absolute;
		top:11.2cm;
		left:5.5cm;
		"><?php echo $respuesta[0]["cabeceraCondicionVenta"] ?> días</h3>		


		<table id="detalle" style="
		position:absolute;
		top:13.1cm;
		left:1cm;
		width:20.1cm;
		">
			<tbody>
				<?php

				for ($i=0; $i < count($respuesta); $i++) { 
					?>

						<tr class="<?php echo $i%2==0?'':'impar' ?>">
							<td style="width:1.7cm"><?php echo $respuesta[$i]["detallecodigoproducto"] ?></td>
							<td style="width:4cm"><?php echo $respuesta[$i]["detallecantidad"]." ".$respuesta[$i]["detalleunidadmedida"] ?></td>
							<td style="width:7.6cm"><?php echo $respuesta[$i]["detalledenominacionproducto"].$respuesta[$i]["detallenombreproducto"] ?></td>
							<td style="width:4.5cm"><?php echo $respuesta[$i]["detalleimporteunitario"] ?></td>
							<td style=""><?php echo $respuesta[$i]["detalleimportetotal"] ?></td>

						</tr>																											
					
					<?php
				}

				?>
			</tbody>
		</table>


		<?php

			if ($movimientosasociados != "") {
				?>
					<p style="
		position:absolute;
		top:22cm;
		left:1cm;
		width:12cm;
		"><?php echo "Movimientos Asociados: ".$movimientosasociados ?></p>
				<?php
			}

		 ?>		

		<h5 style="
		position:absolute;
    top: 22.2cm;
    left: 0.8cm;
    width: 12.2cm;
    height: 2.7cm;
		"><?php echo $respuesta[0]["cabeceracomentarios"] ?></h5>

		<h3 style="
		position:absolute;
		top:23cm;
		left:18cm;
		"><?php echo $respuesta[0]["cabeceraimportetotal"] ?></h3>		
		<h3 style="
		position:absolute;
		top:24cm;
		left:18cm;
		"><?php echo $respuesta[0]["cabeceraimporteiva"] ?></h3>
		<h3 style="
		position:absolute;
		top:26cm;
		left:18cm;
		"><?php echo $respuesta[0]["cabeceraimporteneto"] ?></h3>		

	</page>

</body>
</html>

<script>
	
		function volver(destino){
		window.location = "./"+destino;		
	}


</script>





