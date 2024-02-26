<?php

include_once("connect.php");

//////////////////////////////////////////////////////////

function obtieneComprobantes($tipo = 1){

global $mysqli;
$comprobantes=array();

$sql="SELECT * FROM `comprobantes`
WHERE activo=1
AND (limitarModo = $tipo OR limitarModo IS NULL)
ORDER BY denominacion";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($comprobantes, $registro);				
	}

}

return $comprobantes;

}

//////////////////////////////////////////////////////////

function obtieneObservacionesCheques(){

global $mysqli;
$observaciones=array();

$sql="SELECT * FROM `observaciones-cheques`
ORDER BY observacion";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($observaciones, $registro);				
	}

}

return $observaciones;

}

//////////////////////////////////////////////////////////

function obtieneTerceros(){

global $mysqli;
$terceros=array();

$sql = "SELECT * FROM `terceros` ORDER BY denominacion";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($terceros, $registro);				
	}
}

return $terceros;

}

//////////////////////////////////////////////////////////

function obtieneTiposDocumentos(){

global $mysqli;
$tiposdocumentos=array();

$sql = "SELECT * FROM `tipos-documentos`
WHERE activo=1
ORDER BY denominacion";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($tiposdocumentos, $registro);				
	}

}

return $tiposdocumentos;

}

//////////////////////////////////////////////////////////

function obtieneAlicuotasIva(){

global $mysqli;
$alicuotasiva=array();

$sql = "SELECT * FROM `alicuotas-iva`";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($alicuotasiva, $registro);				
	}

}
return $alicuotasiva;

}

//////////////////////////////////////////////////////////

function obtieneUnidadesMedida(){

global $mysqli;
$unidadesmedida=array();

$sql = "SELECT * FROM `unidades-medida`";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($unidadesmedida, $registro);				
	}

}

return $unidadesmedida;

}

//////////////////////////////////////////////////////////

function obtieneMediosPagos(){

global $mysqli;
$mediospagos=array();

$sql = "SELECT * FROM `medios-pagos`";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($mediospagos, $registro);				
	}

}

return $mediospagos;

}

//////////////////////////////////////////////////////////

function obtienePuntoVenta(){

global $mysqli;

$sql="SELECT puntoVenta FROM configuracion";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	$registro=mysqli_fetch_assoc($resultado);
	$registro = array_map('utf8_encode', $registro);
}

return $registro["puntoVenta"];

}

//////////////////////////////////////////////////////////

function obtieneMovimiento($idmovimiento){

global $mysqli;
$movimiento=array();

$sql="SELECT
`movimientos-detalle`.id as detalleid,
`movimientos-detalle`.cant as detallecant,
`movimientos-detalle`.idUnidadMedida as detalleidunidadmedida,
`movimientos-detalle`.codProducto as detallecodproducto,
`movimientos-detalle`.medida as detallemedida,
`movimientos-detalle`.nombreProducto as detallenombreproducto,
`movimientos-detalle`.importeUnitario as detalleimporteunitario,
`movimientos-detalle`.bonificacion as detallebonificacion,
`movimientos-detalle`.importeTotal as detalleimportetotal,
`movimientos-detalle`.alicuotaIva as detallealicuotaiva,
`movimientos-detalle`.importeIva as detalleimporteiva,
`movimientos-detalle`.importeNeto as detalleimporteneto,
`movimientos-detalle`.importePesos as detalleimportepesos,
`movimientos-detalle`.estadoImportacion as detalleestadoimportacion,
`movimientos-detalle`.origenImportacion as detalleorigenimportacion,
`movimientos-detalle`.cantidadImporto as detallecantidadimporto,
`movimientos`.*,
`unidades-medida`.denominacionCorta,
`alicuotas-iva`.valor AS iva,
CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna, '')) AS 'articulo'
FROM `movimientos-detalle`
INNER JOIN `movimientos`
ON `movimientos-detalle`.`idMovimientos` = `movimientos`.id
INNER JOIN `alicuotas-iva`
ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
INNER JOIN `unidades-medida`
ON `movimientos-detalle`.idUnidadMedida = `unidades-medida`.id
LEFT JOIN articulos
ON articulos.id = `movimientos-detalle`.codProducto
WHERE idMovimientos = '".$idmovimiento."'";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($movimiento, $registro);				
	}

}

return $movimiento;

}

//////////////////////////////////////////////////////////


function obtieneRecibo($idrecibo){

global $mysqli;
$recibo=array();

$sql="SELECT `recibos-pagos`.id,
`recibos-pagos`.tipoFlujo,
`recibos-pagos`.fecha,
`recibos-pagos`.idTercero,
`recibos-pagos`.importe,
`recibos-pagos`.valorDolar,
`recibos-pagos`.nroComprobante,
`recibos-pagos`.contable,
`recibos-pagos`.idEstado,
`recibos-pagos-detalle`.id AS id1,
`recibos-pagos-detalle`.`idRecibo-Pago`,
`recibos-pagos-detalle`.idMedioPago,
`recibos-pagos-detalle`.importe AS importe1,
`recibos-pagos-detalle`.codcheque,
`recibos-pagos-detalle`.banco,
`recibos-pagos-detalle`.numero,
`recibos-pagos-detalle`.fecha AS fecha1,
`recibos-pagos-detalle`.observaciones,
`recibos-pagos-detalle`.contable AS contable1
FROM `recibos-pagos`
left JOIN `recibos-pagos-detalle` ON `recibos-pagos-detalle`.`idRecibo-Pago`
= `recibos-pagos`.id  
WHERE `recibos-pagos`.id='".$idrecibo."'";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($recibo, $registro);				
	}

}

return $recibo;

}

//////////////////////////////////////////////////////////

function obtieneAdelantos($idrecibo){

global $mysqli;
$adelantos=array();

$sql="SELECT `recibos-pagos`.id,
adelantos.id as idadelanto,
adelantos.fecha,
adelantos.idTercero,
adelantos.importe
FROM `recibos-pagos`
INNER JOIN `adelantos-recibos-pagos`
ON `adelantos-recibos-pagos`.`idRecibo-Pago` = `recibos-pagos`.id
INNER JOIN adelantos ON `adelantos-recibos-pagos`.idAdelanto = adelantos.id 
WHERE `recibos-pagos`.id='".$idrecibo."'";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($adelantos, $registro);				
	}

}

return $adelantos;

}

//////////////////////////////////////////////////////////

function obtieneMovimientosRecibos($idrecibo){

global $mysqli;
$movimientosrecibos=array();

$sql="SELECT movimientos.id AS id1,
movimientos.fecha,
movimientos.idComprobante,
movimientos.nroComprobante,
movimientos.importeNeto,
movimientos.importeCancelado AS importeCancelado,
`movimientos-recibos-pagos`.`idRecibo-Pago`,
comprobantes.denominacion,
comprobantes.comportamiento,
movimientos.ptoVenta,
`movimientos-recibos-pagos`.importeCancelado AS importeACancelar
FROM movimientos
INNER JOIN `movimientos-recibos-pagos` ON movimientos.id =
`movimientos-recibos-pagos`.idMovimientos
INNER JOIN comprobantes ON movimientos.idComprobante = comprobantes.id   
WHERE `movimientos-recibos-pagos`.`idRecibo-Pago`='".$idrecibo."'";

$resultado=mysqli_query($mysqli, $sql);
if ($resultado->num_rows>0) {	
	for ($i=0; $i < $resultado->num_rows; $i++) { 
		$registro=mysqli_fetch_assoc($resultado);
		$registro = array_map('utf8_encode', $registro);
		array_push($movimientosrecibos, $registro);				
	}

}

return $movimientosrecibos;

}

//////////////////////////////////////////////////////////

?>




