<?php
error_reporting(E_ERROR | E_PARSE);

include_once("connect.php");
include_once("funciones.php");

include_once("./config.php");

//Listado de ids de comprobantes que se deben mostrar en el panel de control
$idsComprobantesSuman = $config["panel_control"]["documentos_contemplados"]["suman"];
$idsComprobantesRestan = $config["panel_control"]["documentos_contemplados"]["restan"];
$idsComprobantesTodos = array_merge($idsComprobantesSuman, $idsComprobantesRestan);

$respuesta=array();
$respuesta["errores"]["coderror"]=array();
$respuesta["errores"]["mensajeerror"]=array();
$respuesta["exito"]=array();

function f_array_map($charset, $datos){

	//Función para convertir los datos a UTF-8 tanto si es un array como si es un string
	//Dependiendo la configuración del servidor, los datos pueden venir en ISO-8859-1 o UTF-8

	if(is_array($datos)){
		$datos=array_map(function($d) use ($charset){
			return f_array_map($charset, $d);
		}, $datos);
	}else{
		$datos=utf8_encode($datos);
	}

	return $datos;

}

switch ($_REQUEST["accion"]) {


	case 'horarios-terceros':

	if (isset($_REQUEST["idtercero"])) {
		$sql="DELETE FROM `terceros-horarios`
		WHERE idTercero='".$_REQUEST["idtercero"]."'";

		mysqli_query($mysqli,$sql);

		$registros=array();

		for ($i=0; $i < count($_REQUEST["guardar"]); $i++) {
			$registro="('".$_REQUEST["idtercero"]."','".$_REQUEST["guardar"][$i]["dia"]."','".$_REQUEST["guardar"][$i]["horaDesde"]."','".$_REQUEST["guardar"][$i]["horaHasta"]."')";
			array_push($registros, $registro);
		}

		$sql="INSERT INTO `terceros-horarios` (`idTercero`, `dia`, `horaDesde`, `horaHasta`) VALUES".implode(",", $registros);

		mysqli_query($mysqli,$sql);

	}

	break;

	case 'obtiene-costo':

	if (isset($_REQUEST["idpreciocompra"])) {
		
		$sql="SELECT `precioPesos` FROM `articulos-proveedores`
		WHERE id='".$_REQUEST["idpreciocompra"]."'
		LIMIT 1";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows===1) {
			$respuesta["error"]=FALSE;
			$registro=mysqli_fetch_assoc($resultado);
			array_push($respuesta["exito"], $registro);
		}else{
			$respuesta["error"]=TRUE;
		}

	}

	break;

/********************* UNIDADES DE MEDIDA POR ARTICULO *************************/

case 'obtenerunidadesporarticulo':

	if (isset($_REQUEST["idarticulo"])) {
		
		$sql="SELECT
		idUnidadMedidaCompra,
		idUnidadMedidaVenta
		FROM articulos
		WHERE id='".$_REQUEST["idarticulo"]."'";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows == 1) {
			$respuesta["error"]=FALSE;
			$registro=mysqli_fetch_assoc($resultado);
			$respuesta["adicionales"]["unidadCompra"]=$registro["idUnidadMedidaCompra"];
			$respuesta["adicionales"]["unidadVenta"]=$registro["idUnidadMedidaVenta"];
		}else{
			$respuesta["error"]=TRUE;
		}

	}

	$respuesta["exito"] = obtenerUnidadesArticulo($_REQUEST["idarticulo"]);

	break;

/********************* DATOS ARTICULOS ********************/

	case 'datosarticulo':

		$respuesta["exito"] = array();

		//$respuesta["exito"]["unidades"] = obtenerUnidadesArticulo($_REQUEST["idarticulo"]);
		
		if($_REQUEST["tipomovimiento"] == 1){
			$respuesta["exito"]["unidades"] = obtenerUnidadesArticulo($_REQUEST["idarticulo"]);
		}else{
			$respuesta["exito"]["unidades"] = obtenerUnidadesArticulo($_REQUEST["idarticulo"], $_REQUEST["tipomovimiento"] != 1, $_REQUEST["idtercero"]);
		}
		

		if ($_REQUEST["tipomovimiento"] == 1) {//es venta
			$sql = "SELECT articulos.idAlicuotaIva AS iva,
			articulos.idUnidadMedidaVenta AS unid
			FROM articulos
			WHERE articulos.id='".$_REQUEST["idarticulo"]."'";
		}else{//es compra
			$sql = "SELECT articulos.idAlicuotaIva AS iva,
			  articulos.idUnidadMedidaCompra AS unid
			FROM articulos
			WHERE articulos.id='".$_REQUEST["idarticulo"]."'";
		}

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}
		}else{
			$respuesta["exito"]["iva"]=0;
			$respuesta["exito"]["unid"]=0;
		}

		break;

/********************* PRODUCTOS ********************/

	case 'productos':

		if (is_numeric($_REQUEST["q"])) {

			$sql = "SELECT
id,
denominacion,
precio,
SUM(cantidadvendida) AS cantidadtotal
FROM (SELECT articulos.id AS id,
CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacion,
articulos.precioVenta AS precio,
0 AS cantidadvendida,
CONCAT_WS(
				articulos.`id`,
				articulos.`denominacionExterna`,
				articulos.`denominacionInterna`,
				articulos.`codigoBarras`,
				articulos.`tags`,
				articulos.`codigosExternos`
				) AS busqueda,
articulos.codigoBarras,
articulos.`codigosExternos`
FROM articulos
UNION ALL
SELECT articulos.id AS id,
CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacion,
articulos.precioVenta AS precio,
SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantidadvendida,
CONCAT_WS(
				articulos.`id`,
				articulos.`denominacionExterna`,
				articulos.`denominacionInterna`,
				articulos.`codigoBarras`,
				articulos.`tags`,
				articulos.`codigosExternos`
				) AS busqueda,
articulos.codigoBarras,
articulos.`codigosExternos`
FROM articulos
INNER JOIN `movimientos-detalle`
ON articulos.id = `movimientos-detalle`.codProducto
INNER JOIN movimientos
ON `movimientos-detalle`.idMovimientos = movimientos.id
AND movimientos.idEstado = 2
AND movimientos.idTercero = ".$_REQUEST["tercero"]."
GROUP BY id) t
			WHERE	t.`id`
			= '".$_REQUEST["q"]."'
			OR t.`codigoBarras`
			= '".$_REQUEST["q"]."'
			OR t.`codigosExternos`
			= '".$_REQUEST["q"]."'
			GROUP BY id
			ORDER BY cantidadtotal DESC, denominacion ASC
			LIMIT ".$_REQUEST["limit"];

		}else{

			$sql = "SELECT
id,
denominacion,
precio,
SUM(cantidadvendida) AS cantidadtotal
FROM (SELECT articulos.id AS id,
CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacion,
articulos.precioVenta AS precio,
0 AS cantidadvendida,
CONCAT_WS(
				articulos.`id`,
				articulos.`denominacionExterna`,
				articulos.`denominacionInterna`,
				articulos.`codigoBarras`,
				articulos.`tags`,
				articulos.`codigosExternos`
				) AS busqueda,
articulos.codigoBarras,
articulos.`codigosExternos`
FROM articulos
UNION ALL
SELECT articulos.id AS id,
CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacion,
articulos.precioVenta AS precio,
SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantidadvendida,
CONCAT_WS(
				articulos.`id`,
				articulos.`denominacionExterna`,
				articulos.`denominacionInterna`,
				articulos.`codigoBarras`,
				articulos.`tags`,
				articulos.`codigosExternos`
				) AS busqueda,
articulos.codigoBarras,
articulos.`codigosExternos`
FROM articulos
INNER JOIN `movimientos-detalle`
ON articulos.id = `movimientos-detalle`.codProducto
INNER JOIN movimientos
ON `movimientos-detalle`.idMovimientos = movimientos.id
AND movimientos.idEstado = 2
AND movimientos.idTercero = '".$_REQUEST["tercero"]."'
GROUP BY id) t
WHERE	t.busqueda like '%".$_REQUEST["q"]."%'
GROUP BY id
			ORDER BY cantidadtotal DESC, denominacion ASC
			LIMIT ".$_REQUEST["limit"];

		}

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				$respuesta["exito"][] = array("id"=>$registro['id'],"denominacion"=>$registro['denominacion'], "precio"=>$registro['precio'],"cantidadtotal"=>$registro['cantidadtotal']);
			}
		}

		break;



	case 'producto':

	$sql = "SELECT articulos.id AS id,
	CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacion
	FROM articulos
	WHERE id = '".$_REQUEST["idarticulo"]."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows === 1) {

		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);
		$respuesta["exito"] = $registro;

	}


	break;

/********************* DATOS TERCERO ********************/

case 'datosterceros':

	$comprobantesbloqueados = array();

	$sql = "SELECT
	terceros.*,
	`condiciones-iva`.denominacion AS denominacionCondicionIVA,
	`lista-precios`.descuento
	FROM `terceros`
	LEFT JOIN `lista-precios`
	ON terceros.idListaPrecios = `lista-precios`.id
	LEFT JOIN `condiciones-iva`
	ON terceros.condicionIva = `condiciones-iva`.id
	 where terceros.id= '".$_REQUEST["idtercero"]."'";

	$resultado=mysqli_query($mysqli,$sql);


	if ($resultado) {
		if (mysqli_num_rows($resultado) == 1) {

			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);


			$sql = "SELECT `comprobantes-bloqueados-condiciones-iva`.idComprobanteBloqueado
							FROM `condiciones-iva`
							INNER JOIN `comprobantes-bloqueados-condiciones-iva` ON `condiciones-iva`.id =
  						`comprobantes-bloqueados-condiciones-iva`.idCondicionIva
  						WHERE idCondicionIva = '".$registro["condicionIva"]."'";

  		$resultado=mysqli_query($mysqli,$sql);

  		if ($resultado) {
  			if (mysqli_num_rows($resultado) > 0) {

  				for ($i=0; $i < mysqli_num_rows($resultado); $i++) {

						$registro=mysqli_fetch_assoc($resultado);
						$registro = f_array_map('utf8_encode', $registro);
						array_push($comprobantesbloqueados, $registro);

  				}
  			}
  		}
		}
	}

	$respuesta["exito"]["comprobantesbloqueados"] = $comprobantesbloqueados;

	break;

/********************* IMPORTE ARTICULO ********************/

	case 'importearticulo':

	if ($_REQUEST["tipomovimiento"]==1) {

		//Es venta

		//Obtengo el artículo, el precio de venta y los descuentos para ese cliente
		$sql = "SELECT articulos.id AS idArticulo,
		articulos.idAlicuotaIva AS iva,
		articulos.precioVenta AS precioVenta,
		articulos.rentabilidad AS rentabilidad,
		articulos.idUnidadMedidaVenta,
		`articulos-terceros-descuentos`.descuento AS dtoArticulo,
		`categorias-terceros-descuentos`.descuento AS dtoCategoria,
		`subcategoria-terceros-descuentos`.descuento AS dtoSubcategoria,
		`articulos-stock`.indiceConversion
		FROM articulos
		LEFT JOIN `articulos-terceros-descuentos`
		ON articulos.id = `articulos-terceros-descuentos`.idArticulo AND
		`articulos-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
		LEFT JOIN `categorias-terceros-descuentos` ON articulos.idCategoria =
		`categorias-terceros-descuentos`.idCategoria AND
		`categorias-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
		LEFT JOIN `subcategoria-terceros-descuentos` ON articulos.idSubcateogoria =
		`subcategoria-terceros-descuentos`.idSubcategoria AND
		`subcategoria-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
		LEFT JOIN `articulos-stock`
		ON articulos.idUnidadMedidaVenta = `articulos-stock`.idUnidadMedida AND
		articulos.id = `articulos-stock`.idArticulo
		WHERE articulos.id='".$_REQUEST["idarticulo"]."'
		LIMIT 1";

		$resultado=mysqli_query($mysqli,$sql);
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);

		//obtengo precio y costo
		$precio = $registro["precioVenta"];
		$costo = $precio / ($registro["rentabilidad"] / 100 + 1);

		//obtengo los descuentos
		$dtoArticulo = $registro["dtoArticulo"];
		$dtoCategoria = $registro["dtoCategoria"];
		$dtoSubcategoria = $registro["dtoSubcategoria"];

		//Obtengo unidad de medida de venta e indice de conversión
		$idUnidadMedidaVenta = $registro["idUnidadMedidaVenta"];
		$indice = $registro["indiceConversion"];

		//Obtengo la rentabilidad y el IVA
		$rentabilidad = $registro["rentabilidad"];
		$respuesta["exito"]["iva"]=$registro["iva"];

		//Descuento del cliente
		$sql = "SELECT terceros.id AS idTercero,
		terceros.dtoCliente AS dtoCliente,
		`lista-precios`.descuento AS dtoLista
		FROM terceros
		LEFT JOIN `lista-precios` ON terceros.idListaPrecios = `lista-precios`.id
		WHERE terceros.id='".$_REQUEST["idtercero"]."'
		LIMIT 1";

		$resultado=mysqli_query($mysqli,$sql);
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);

		$dtoCliente = $registro["dtoCliente"];
		$dtoLista = $registro["dtoLista"];
		
		//Calculo la rentabilidad final
		$rentabilidadfinal = $rentabilidad - $dtoArticulo - $dtoCategoria - $dtoSubcategoria - $dtoCliente - $dtoLista;

		//Calculo el precio final redondeado
		$precioFinal = $costo * ($rentabilidadfinal / 100) + $costo;
		$precioFinal = round($precioFinal,3);

		//Si la unidad de medida especificada es distinta, calculo el precio mediante el índice de conversión
		if ($_REQUEST["unidadmedida"]!=$idUnidadMedidaVenta) {

			$sql="SELECT * FROM `articulos-stock`
			WHERE idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
			AND idArticulo='".$_REQUEST["idarticulo"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			//Si no tiene especificada esa unidad de medida retorno valor 0, sino la calculo
			if ($resultado->num_rows==1) {

				$registro=mysqli_fetch_assoc($resultado);

				$precioFinal = $precioFinal * $indice / $registro["indiceConversion"];

				$precioFinal = round($precioFinal,3);

			}else{

				$precioFinal=0;

			}

		}

		$respuesta["exito"]["precio"]=$precioFinal;

	}else{

		//Es compra

		$sql = "SELECT articulos.id AS idArticulo,
		articulos.idPrecioCompra AS idPrecioCompra,
		`articulos-proveedores`.precioPesos AS precioCompra,
		`articulos-proveedores`.idUnidadMedida AS idUnidadMedidaCompra,
		`unidades-medida`.indiceConversion AS indiceConversion
		FROM articulos
		LEFT JOIN `articulos-proveedores` ON articulos.idPrecioCompra = `articulos-proveedores`.id
		LEFT JOIN `unidades-medida` ON `articulos-proveedores`.idUnidadMedida = `unidades-medida`.id
		WHERE articulos.id='".$_REQUEST["idarticulo"]."'";

		$resultado=mysqli_query($mysqli,$sql);
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);

		$precioFinal = $registro["precioCompra"] / ($registro["indiceConversion"]);

		$unidadMedidaCompra = $registro["idUnidadMedidaCompra"];

		//Si la unidad de medida especificada es distinta, calculo el precio mediante el índice de conversión
		if($_REQUEST["unidadmedida"] != $registro["idUnidadMedidaCompra"]){

			//Obtengo el índice de conversión de la unidad de medida especificada
			$sql="SELECT indiceConversion FROM `articulos-stock`
			WHERE idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
			AND idArticulo='".$_REQUEST["idarticulo"]."'";
			$resultado=mysqli_query($mysqli,$sql);

			if ($resultado->num_rows==1) {
				$registro=mysqli_fetch_assoc($resultado);
				$indiceUnidadSeleccionada = $registro["indiceConversion"];
			}else{
				array_push($respuesta["errores"]["mensajeerror"], "No se encontró el índice de conversión de la unidad de medida especificada");
				$indiceUnidadSeleccionada = 0;
			}

			//Obtengo el índice de conversión de la unidad de medida del precio de compra
			$sql="SELECT indiceConversion FROM `articulos-stock`
			WHERE idUnidadMedida = '".$unidadMedidaCompra."'
			AND idArticulo='".$_REQUEST["idarticulo"]."'";
			$resultado=mysqli_query($mysqli,$sql);
			
			if ($resultado->num_rows==1) {
				$registro=mysqli_fetch_assoc($resultado);
				$indiceUnidadCompra = $registro["indiceConversion"];
			}else{
				array_push($respuesta["errores"]["mensajeerror"], "No se encontró índice de conversión para la unidad de medida indicada en el precio de compra");
				$indiceUnidadCompra = 0;
			}
			
			if($indiceUnidadSeleccionada != 0 && $indiceUnidadCompra != 0){
				$precioFinal = $precioFinal * $indiceUnidadCompra / $indiceUnidadSeleccionada;
			}else{
				$precioFinal = 0;
			}
			
		}

		//Redondeo el precio final
		$respuesta["exito"]["precio"]=round($precioFinal,3);
	}

		/*
		//Obtengo el precio
		$sql="SELECT
		`articulos-proveedores`.precioPesos AS precio,
		`articulos-proveedores`.idAlicuotaIva AS iva,
		articulos.idUnidadMedidaCompra,
		`articulos-stock`.indiceConversion
		FROM `articulos-proveedores`
		INNER JOIN articulos
		ON `articulos-proveedores`.idArticulo = articulos.id
		LEFT JOIN `articulos-stock`
		ON articulos.idUnidadMedidaCompra = `articulos-stock`.idUnidadMedida AND
		articulos.id = `articulos-stock`.idArticulo
		WHERE `articulos-proveedores`.idArticulo='".$_REQUEST["idarticulo"]."'
		AND `articulos-proveedores`.idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
		AND idTercero='".$_REQUEST["idtercero"]."'
		LIMIT 1";

		$resultado=mysqli_query($mysqli,$sql);

		//Si no existe el precio de ese artículo para ese proveedor retorno 0, sino continúo
		if($resultado->num_rows != 0){
			
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			
			//Obtengo el precio y el IVA
			$respuesta["exito"]["iva"]=$registro["iva"];
			$respuesta["exito"]["precio"]=$registro["precio"];
			
			/*
			//Si la unidad de medida es distinta a la default de compra
			if ($_REQUEST["unidadmedida"]!=$registro["idUnidadMedidaCompra"]) {
				
				//Calculo el valor en base al índice de conversión
				$indice=$registro["indiceConversion"];
	
				$sql="SELECT * FROM `articulos-stock`
				WHERE idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
				AND idArticulo='".$_REQUEST["idarticulo"]."'";
	
				$resultado=mysqli_query($mysqli,$sql);
				
				//Si ese artículo no tiene índice de conversión para esa unidad de medida, retorno 0
				if ($resultado->num_rows==1) {
	
					$registro=mysqli_fetch_assoc($resultado);
	
					$respuesta["exito"]["precio"] = $respuesta["exito"]["precio"] * $indice / $registro["indiceConversion"];
	
				}else{
	
					$respuesta["exito"]["precio"] = 0;
	
				}
	
			}
			
		}else{
			$respuesta["exito"]["precio"] = 0;
		}
		*/

		break;

/********************* GUARDAR MOVIMIENTO ********************/


	case 'guardar':
		$error=array();
		if ($_REQUEST["cabecera"]["importetotal"]=="" || $_REQUEST["cabecera"]["importeiva"]=="" || $_REQUEST["cabecera"]["importeneto"]=="" || $_REQUEST["cabecera"]["importetotal"]==0 || $_REQUEST["cabecera"]["importeneto"]==0) {
			array_push($error, "Faltan importes");
		}

		if ($_REQUEST["cabecera"]["comprobante"]==0) {
			array_push($error, "Falta Comprobante");
		}
/*
		if ($_REQUEST["cabecera"]["tipodoctercero"]==0) {
			array_push($error, "Falta el tipo de documento del Tercero");
		}

		if ($_REQUEST["cabecera"]["doctercero"]==0) {
			array_push($error, "Falta el documento del Tercero");
		}
*/
		if ($_REQUEST["cabecera"]["condicionventa"] == "" || $_REQUEST["cabecera"]["condicionventa"] == NULL) {
			$_REQUEST["cabecera"]["condicionventa"] = 0;
		}

		if ($_REQUEST["cabecera"]["condicionventa"] < 0) {
			array_push($error, "La condición de venta no puede ser negativa");
		}

		if (isset($_REQUEST["detalles"])) {

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				if ($_REQUEST["detalles"][$i]["cantidad"]==0) {
					array_push($error, "Falta cantidad en detalle número ".$i);
				}

				if ($_REQUEST["detalles"][$i]["imptotal"]==""  || $_REQUEST["detalles"][$i]["impneto"]=="" || $_REQUEST["detalles"][$i]["imptotal"]==0 || $_REQUEST["detalles"][$i]["impneto"]==0) {
					array_push($error, "Faltan importes en detalle número ".$i);
				}
			}
		}

		if (!isset($_REQUEST["cabecera"]["archivo"])) {
			$_REQUEST["cabecera"]["archivo"]="";
		}

		if (count($error)==0) {
			$sql="INSERT INTO `movimientos`
				(
					`fecha`,
					`vigencia`,
					`idTercero`,
					`idComprobante`,
					`importeTotal`,
					`importeIva`,
					`importeNeto`,
					`nombreTercero`,
					`idDocTercero`,
					`nroDocTercero`,
					`ptoVenta`,
					`nroComprobante`,
					`cae`,
					`vtoCae`,
					`idEstado`,
					`idUsuarioAlta`,
					`fechaAlta`,
					`idUsuarioModificacion`,
					`fechaModificacion`,
					`contable`,
					`archivo`,
					`tipoMovimiento`,
					`importeCancelado`,
					`comentarios`,
					`condicionVenta`
				) VALUES (
					'".$_REQUEST["cabecera"]["fecha"]."',
					'".$_REQUEST["cabecera"]["vigencia"]."',
					'".$_REQUEST["cabecera"]["tercero"]."',
					'".$_REQUEST["cabecera"]["comprobante"]."',
					'".$_REQUEST["cabecera"]["importetotal"]."',
					'".$_REQUEST["cabecera"]["importeiva"]."',
					'".$_REQUEST["cabecera"]["importeneto"]."',
					'".$_REQUEST["cabecera"]["nomtercero"]."',
					'".$_REQUEST["cabecera"]["tipodoctercero"]."',
					'".$_REQUEST["cabecera"]["doctercero"]."',
					'".$_REQUEST["cabecera"]["puntoventa"]."',
					'".$_REQUEST["cabecera"]["numerocomprobante"]."',
					'".$_REQUEST["cabecera"]["cae"]."',
					'".$_REQUEST["cabecera"]["vtocae"]."',
					'1',
					'1',
					'".date('Y-m-d')."',
					'1',
					'".date('Y-m-d')."',
					'".$_REQUEST["cabecera"]["contable"]."',
					'".$_REQUEST["cabecera"]["archivo"]."',
					'".$_REQUEST["cabecera"]["tipomovimiento"]."',
					0,
					'".$_REQUEST["cabecera"]["comentarios"]."',
					'".$_REQUEST["cabecera"]["condicionventa"]."'
				)";

			$resultado=mysqli_query($mysqli,$sql);

			$ultimoid=$mysqli->insert_id;

			////////

			aumentarNumeroComprobante(
				$_REQUEST["cabecera"]["puntoventa"],
				$_REQUEST["cabecera"]["comprobante"],
				$_REQUEST["cabecera"]["contable"]
			);

			//////

			$origenes=array();

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {


				if ($_REQUEST["detalles"][$i]["origenimportacion"]!="" || $_REQUEST["detalles"][$i]["origenimportacion"] !=0) {
					array_push($origenes, $_REQUEST["detalles"][$i]["origenimportacion"]);
				}else{
					$_REQUEST["detalles"][$i]["origenimportacion"] = "NULL";
					$_REQUEST["detalles"][$i]["cantidadimporto"] = "NULL";
				}


				$sql="INSERT INTO `movimientos-detalle`
					(
						`idMovimientos`,
						`cant`,
						`idUnidadMedida`,
						`codProducto`,
						`nombreProducto`,
						`importeUnitario`,
						`importeTotal`,
						`alicuotaIva`,
						`importeNeto`,
						`origenImportacion`,
						`cantidadImporto`
					) VALUES (
						'".$ultimoid."',
						'".$_REQUEST["detalles"][$i]["cantidad"]."',
						'".$_REQUEST["detalles"][$i]["unidadmedida"]."',
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["detalles"][$i]["productocustom"]."',
						'".$_REQUEST["detalles"][$i]["impunit"]."',
						'".$_REQUEST["detalles"][$i]["imptotal"]."',
						'".$_REQUEST["detalles"][$i]["alicuotaiva"]."',
						'".$_REQUEST["detalles"][$i]["impneto"]."',
						".$_REQUEST["detalles"][$i]["origenimportacion"].",
						".$_REQUEST["detalles"][$i]["cantidadimporto"]."
					)";


				$resultado=mysqli_query($mysqli,$sql);
			}

			if (count($origenes)>0) {

				$origenes=implode("','", $origenes);

				$sql="UPDATE `movimientos-detalle`
				SET estadoImportacion=2
				WHERE id IN('".$origenes."')";
				$resultado=mysqli_query($mysqli,$sql);

			}
		}

		$respuesta["error"]=$error;
		if($ultimoid){
			$respuesta["exito"]=$ultimoid;
		}

		break;

/********************* GUARDAR COTIZACIÓN ********************/


	case 'guardar-cotizacion':
		$error=array();
		if ($_REQUEST["cabecera"]["importetotal"]=="" || $_REQUEST["cabecera"]["importeiva"]=="" || $_REQUEST["cabecera"]["importeneto"]=="" || $_REQUEST["cabecera"]["importetotal"]==0 || $_REQUEST["cabecera"]["importeneto"]==0) {
			array_push($error, "Faltan importes");
		}


		if ($_REQUEST["cabecera"]["tercero"] == 0) {
			array_push($error, "Falta seleccionar el tercero");
		}

		if (isset($_REQUEST["detalles"])) {

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				if ($_REQUEST["detalles"][$i]["cantidad"]==0) {
					array_push($error, "Falta cantidad en detalle número ".$i);
				}

			}

		}

		if (count($error)==0) {
			$sql="INSERT INTO `cotizaciones`
				(
					`fecha`,
					`vigencia`,
					`idTercero`,
					`contable`,
					`importeTotal`,
					`importeIva`,
					`importeNeto`,
					`discriminaIVA`
				) VALUES (
					'".$_REQUEST["cabecera"]["fecha"]."',
					'".$_REQUEST["cabecera"]["vigencia"]."',
					'".$_REQUEST["cabecera"]["tercero"]."',
					'".$_REQUEST["cabecera"]["contable"]."',
					'".$_REQUEST["cabecera"]["importetotal"]."',
					'".$_REQUEST["cabecera"]["importeiva"]."',
					'".$_REQUEST["cabecera"]["importeneto"]."',
					'".$_REQUEST["cabecera"]["discriminaiva"]."'
				)";


			$resultado=mysqli_query($mysqli,$sql);

			$ultimoid=$mysqli->insert_id;

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				$sql="INSERT INTO `cotizaciones_detalles`
					(
						`idCotizacion`,
						`idArticulo`,
						`cantidad`,
						`referencia`,
						`precioReferencia`,
						`idAlicuota`,
						`cantidadAprobada`,
						`margenCotizado`,
						`precioCotizado`,
						dtoCliente
					) VALUES (
						'".$ultimoid."',
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["detalles"][$i]["cantidad"]."',
						'".$_REQUEST["detalles"][$i]["referencia"]."',
						'".$_REQUEST["detalles"][$i]["precio-referencia"]."',
						'".$_REQUEST["detalles"][$i]["alicuotaiva"]."',
						'".$_REQUEST["detalles"][$i]["cantidad-aprobada"]."',
						'".$_REQUEST["detalles"][$i]["nuevo-margen"]."',
						'".$_REQUEST["detalles"][$i]["nuevo-precio-unitario"]."',
						'".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					)";

				$resultado=mysqli_query($mysqli,$sql);

				// El descuento a los clientes se debe guardar solo cuando se audita la cotización a partir del pedido del cliente.

				/*

				$sql = "SELECT id FROM `articulos-terceros-descuentos`
				WHERE `articulos-terceros-descuentos`.idArticulo = '".$_REQUEST["detalles"][$i]["producto"]."'
				AND `articulos-terceros-descuentos`.idTercero = '".$_REQUEST["cabecera"]["tercero"]."'";

				$resultado=mysqli_query($mysqli,$sql);

				if (mysqli_num_rows($resultado) > 0) {

					$sql = "UPDATE `articulos-terceros-descuentos`
					SET descuento = '".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					WHERE `articulos-terceros-descuentos`.idArticulo = '".$_REQUEST["detalles"][$i]["producto"]."'
					AND `articulos-terceros-descuentos`.idTercero = '".$_REQUEST["cabecera"]["tercero"]."'";

					mysqli_query($mysqli,$sql);
				}else{

					$sql = "INSERT INTO `articulos-terceros-descuentos`
					(idArticulo, idTercero, descuento) VALUES (
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["cabecera"]["tercero"]."',
						'".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					)";

					mysqli_query($mysqli,$sql);
				}

				*/

			}

		}

		$respuesta["error"]=$error;

		break;

/********************* EDITAR MOVIMIENTO ********************/


	case 'editar':
		$error=array();
		if ($_REQUEST["cabecera"]["importetotal"]=="" || $_REQUEST["cabecera"]["importeiva"]=="" || $_REQUEST["cabecera"]["importeneto"]=="" || $_REQUEST["cabecera"]["importetotal"]==0 || $_REQUEST["cabecera"]["importeneto"]==0) {
			array_push($error, "Faltan importes");
		}

		if ($_REQUEST["cabecera"]["comprobante"]==0) {
			array_push($error, "Falta Comprobante");
		}

		/*
		if ($_REQUEST["cabecera"]["tipodoctercero"]==0) {
			array_push($error, "Falta el tipo de documento del Tercero");
		}

		if ($_REQUEST["cabecera"]["doctercero"]==0) {
			array_push($error, "Falta el documento del Tercero");
		}
		*/
		if ($_REQUEST["cabecera"]["condicionventa"] == "" || $_REQUEST["cabecera"]["condicionventa"] == NULL) {
			$_REQUEST["cabecera"]["condicionventa"] = 0;
		}

		if ($_REQUEST["cabecera"]["condicionventa"] < 0) {
			array_push($error, "La condición de venta no puede ser negativa");
		}

		if (isset($_REQUEST["detalles"])) {

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				if ($_REQUEST["detalles"][$i]["cantidad"]==0) {
					array_push($error, "Falta cantidad en detalle número ".$i);
				}

				if ($_REQUEST["detalles"][$i]["imptotal"]=="" || $_REQUEST["detalles"][$i]["impneto"]=="" || $_REQUEST["detalles"][$i]["imptotal"]==0 || $_REQUEST["detalles"][$i]["impneto"]==0) {
					array_push($error, "Faltan importes en detalle número ".$i);
				}
			}
		}

		if (!isset($_REQUEST["cabecera"]["archivo"])) {
			$_REQUEST["cabecera"]["archivo"]="";
		}

		if (count($error)==0) {
			$sql="UPDATE `movimientos` SET
			`fecha` = '".$_REQUEST["cabecera"]["fecha"]."',
			`vigencia` = '".$_REQUEST["cabecera"]["vigencia"]."',
			`idTercero`='".$_REQUEST["cabecera"]["tercero"]."',
			`idComprobante`='".$_REQUEST["cabecera"]["comprobante"]."',
			`importeTotal`='".$_REQUEST["cabecera"]["importetotal"]."',
			`importeIva`='".$_REQUEST["cabecera"]["importeiva"]."',
			`importeNeto`='".$_REQUEST["cabecera"]["importeneto"]."',
			`nombreTercero`='".$_REQUEST["cabecera"]["nomtercero"]."',
			`idDocTercero`='".$_REQUEST["cabecera"]["tipodoctercero"]."',
			`nroDocTercero`='".$_REQUEST["cabecera"]["doctercero"]."',
			`ptoVenta`='".$_REQUEST["cabecera"]["puntoventa"]."',
			`nroComprobante`='".$_REQUEST["cabecera"]["numerocomprobante"]."',
			`cae`='".$_REQUEST["cabecera"]["cae"]."',
			`vtoCae`='".$_REQUEST["cabecera"]["vtocae"]."',
			`idEstado`='1',
			`idUsuarioModificacion`='1',
			`fechaModificacion`='".date('Y-m-d')."',
			`contable`='".$_REQUEST["cabecera"]["contable"]."',
			`archivo`='".$_REQUEST["cabecera"]["archivo"]."',
			`comentarios`='".$_REQUEST["cabecera"]["comentarios"]."',
			`tipoMovimiento`='".$_REQUEST["cabecera"]["tipomovimiento"]."',
			`condicionVenta`='".$_REQUEST["cabecera"]["condicionventa"]."'
			WHERE `movimientos`.`id` = '".$_REQUEST["cabecera"]["idcabecera"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			$sql="UPDATE `movimientos-detalle`
			LEFT JOIN `movimientos-detalle` `movimientos-detalle1`
		ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id
			SET `movimientos-detalle1`.estadoImportacion = NULL
			WHERE `movimientos-detalle`.idMovimientos = '".$_REQUEST["cabecera"]["idcabecera"]."'
			AND `movimientos-detalle1`.estadoImportacion=3";
			$resultado=mysqli_query($mysqli,$sql);

			$sql="DELETE FROM `movimientos-detalle` WHERE `movimientos-detalle`.`idMovimientos` = '".$_REQUEST["cabecera"]["idcabecera"]."'";
			$resultado=mysqli_query($mysqli,$sql);

			$origenes=array();

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				if ($_REQUEST["detalles"][$i]["origenimportacion"]!="" || $_REQUEST["detalles"][$i]["origenimportacion"] !=0) {
					array_push($origenes, $_REQUEST["detalles"][$i]["origenimportacion"]);
				}else{
					$_REQUEST["detalles"][$i]["origenimportacion"] = "NULL";
					$_REQUEST["detalles"][$i]["cantidadimporto"] = "NULL";
				}

				$sql="INSERT INTO `movimientos-detalle`
					(
						`idMovimientos`,
						`cant`,
						`idUnidadMedida`,
						`codProducto`,
						`nombreProducto`,
						`importeUnitario`,
						`importeTotal`,
						`alicuotaIva`,
						`importeNeto`,
						`origenImportacion`,
						`cantidadImporto`
					) VALUES (
						'".$_REQUEST["cabecera"]["idcabecera"]."',
						'".$_REQUEST["detalles"][$i]["cantidad"]."',
						'".$_REQUEST["detalles"][$i]["unidadmedida"]."',
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["detalles"][$i]["productocustom"]."',
						'".$_REQUEST["detalles"][$i]["impunit"]."',
						'".$_REQUEST["detalles"][$i]["imptotal"]."',
						'".$_REQUEST["detalles"][$i]["alicuotaiva"]."',
						'".$_REQUEST["detalles"][$i]["impneto"]."',
						".$_REQUEST["detalles"][$i]["origenimportacion"].",
						".$_REQUEST["detalles"][$i]["cantidadimporto"]."
					)";

				$resultado=mysqli_query($mysqli,$sql);
			}

			if (count($origenes)>0) {

				$origenes=implode("','", $origenes);

				$sql="UPDATE `movimientos-detalle`
				SET estadoImportacion=2
				WHERE id IN('".$origenes."')";
				$resultado=mysqli_query($mysqli,$sql);

			}

		}

		$respuesta["error"]=$error;

		break;

/********************* EDITAR COTIZACIÓN ********************/


	case 'editar-cotizacion':
		$error=array();
		if ($_REQUEST["cabecera"]["importetotal"]=="" || $_REQUEST["cabecera"]["importeiva"]=="" || $_REQUEST["cabecera"]["importeneto"]=="" || $_REQUEST["cabecera"]["importetotal"]==0 || $_REQUEST["cabecera"]["importeneto"]==0) {
			array_push($error, "Faltan importes");
		}


		if ($_REQUEST["cabecera"]["tercero"] == 0) {
			array_push($error, "Falta seleccionar el tercero");
		}

		if (isset($_REQUEST["detalles"])) {

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				if ($_REQUEST["detalles"][$i]["cantidad"]==0) {
					array_push($error, "Falta cantidad en detalle número ".$i);
				}

			}

		}

		if (count($error)==0) {

			$sql = "UPDATE `cotizaciones`
			SET `fecha` = '".$_REQUEST["cabecera"]["fecha"]."',
			`vigencia` = '".$_REQUEST["cabecera"]["vigencia"]."',
			`idTercero` = '".$_REQUEST["cabecera"]["tercero"]."',
			`contable` = '".$_REQUEST["cabecera"]["contable"]."',
			`importeTotal` = '".$_REQUEST["cabecera"]["importetotal"]."',
			`importeIva` = '".$_REQUEST["cabecera"]["importeiva"]."',
			`importeNeto` = '".$_REQUEST["cabecera"]["importeneto"]."',
			`discriminaIVA` = '".$_REQUEST["cabecera"]["discriminaiva"]."'
			WHERE id = '".$_REQUEST["cabecera"]["idcotizacion"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			$ultimoid = $_REQUEST["cabecera"]["idcotizacion"];

			$sql = "DELETE FROM `cotizaciones_detalles`
			WHERE idCotizacion = '".$_REQUEST["cabecera"]["idcotizacion"]."'";

			mysqli_query($mysqli,$sql);

			for ($i=1; $i < count($_REQUEST["detalles"])+1; $i++) {

				$sql="INSERT INTO `cotizaciones_detalles`
					(
						`idCotizacion`,
						`idArticulo`,
						`cantidad`,
						`referencia`,
						`precioReferencia`,
						`idAlicuota`,
						`cantidadAprobada`,
						`margenCotizado`,
						`precioCotizado`,
						dtoCliente
					) VALUES (
						'".$ultimoid."',
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["detalles"][$i]["cantidad"]."',
						'".$_REQUEST["detalles"][$i]["referencia"]."',
						'".$_REQUEST["detalles"][$i]["precio-referencia"]."',
						'".$_REQUEST["detalles"][$i]["alicuotaiva"]."',
						'".$_REQUEST["detalles"][$i]["cantidad-aprobada"]."',
						'".$_REQUEST["detalles"][$i]["nuevo-margen"]."',
						'".$_REQUEST["detalles"][$i]["nuevo-precio-unitario"]."',
						'".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					)";

					$resultado=mysqli_query($mysqli,$sql);

				/*

				$sql = "SELECT id FROM `articulos-terceros-descuentos`
				WHERE `articulos-terceros-descuentos`.idArticulo = '".$_REQUEST["detalles"][$i]["producto"]."'
				AND `articulos-terceros-descuentos`.idTercero = '".$_REQUEST["cabecera"]["tercero"]."'";

				$resultado=mysqli_query($mysqli,$sql);

				if (mysqli_num_rows($resultado) > 0) {

					$sql = "UPDATE `articulos-terceros-descuentos`
					SET descuento = '".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					WHERE `articulos-terceros-descuentos`.idArticulo = '".$_REQUEST["detalles"][$i]["producto"]."'
					AND `articulos-terceros-descuentos`.idTercero = '".$_REQUEST["cabecera"]["tercero"]."'";

					mysqli_query($mysqli,$sql);
				}else{

					$sql = "INSERT INTO `articulos-terceros-descuentos`
					(idArticulo, idTercero, descuento) VALUES (
						'".$_REQUEST["detalles"][$i]["producto"]."',
						'".$_REQUEST["cabecera"]["tercero"]."',
						'".$_REQUEST["detalles"][$i]["dto-articulo"]."'
					)";

					mysqli_query($mysqli,$sql);
				}

				*/

			}

		}

		$respuesta["error"]=$error;

		break;


/********************* RETORNAR TERCEROS  ********************/

	case 'retornarterceros':
		$sql = "SELECT
		`id`,
		`denominacion`
		FROM `terceros`
		WHERE `idTipoTercero`='".$_REQUEST["tipotercero"]."'
		ORDER BY `denominacion`";

		$resultado=mysqli_query($mysqli,$sql);
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}


		break;

/********************* RETORNAR SUCURSALES  ********************/

	case 'retornarsucursales':

		if ($_REQUEST["central"] == 0) {

			$sql = "SELECT
			terceros.id,
			terceros.denominacion
			FROM terceros
			ORDER BY `denominacion`";	

		}else{

			$sql = "SELECT
			sucursales.idTerceroSucursal as id,
			terceros.denominacion
			FROM sucursales
			INNER JOIN terceros
			ON sucursales.idTerceroSucursal = terceros.id
			WHERE sucursales.idTerceroPadre = '".$_REQUEST["central"]."'
			ORDER BY `denominacion`";

		}


		$resultado=mysqli_query($mysqli,$sql);
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}


		break;		

		/********************* AUDITAR  ********************/
		
		case 'auditar':
			
		//Variables temporales
		$audita=FALSE;
		$autoriza=FALSE;
		$movimientocompleto=array();
		$iva=array();
		$ivaformato=array();
		$cbteasocformato = array();
	
		//Comprobantes que requieren comprobantes asociados
		$comprobantesconasoc = array(
			2,3,7,8,12,13
		);
			
		//Obtengo el movimiento que quiero auditar y lo guardo en resultado
		$sql="SELECT movimientos.tipoMovimiento AS cabeceraTipoMovimiento,
		movimientos.fecha AS cabeceraFecha,
		movimientos.idComprobante AS cabeceraComprobante,
		comprobantes.discriminaIVA AS cabeceraDiscriminaIVA,
		comprobantes.seAutoriza AS cabeceraSeAutoriza,
		movimientos.importeTotal AS cabeceraImporteTotal,
		movimientos.importeIva AS cabeceraImporteIVA,
		movimientos.importeNeto AS cabeceraImporteNeto,
		movimientos.idDocTercero AS cabeceraIdDocTercero,
		movimientos.nroDocTercero AS cabeceraNumeroDocTercero,
		movimientos.idEstado AS cabeceraEstado,
		`movimientos-detalle`.importeTotal AS detalleImporteTotal,
		`movimientos-detalle`.alicuotaIva AS detalleAlicuotaIVA,
		`movimientos-detalle`.importeIva AS detalleImporteIVA,
		`movimientos-detalle`.importeNeto AS detalleImporteNeto,
		`movimientos-detalle`.origenImportacion AS origenImportacion
		FROM movimientos
		INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos =
		movimientos.id
		INNER JOIN comprobantes ON movimientos.idComprobante = comprobantes.id
  		WHERE movimientos.id='".$_REQUEST["id"]."'";

		$resultado=mysqli_query($mysqli,$sql);
		
		//Recorro el movimiento
		for ($i=0; $i < $resultado->num_rows; $i++) {
			//Por cada registro
			$registro=mysqli_fetch_assoc($resultado);
			//Guardo el registro en movimientocompleto
			array_push($movimientocompleto, $registro);
			//Genero el array de iva
			if (!array_key_exists($registro["detalleAlicuotaIVA"], $iva)) {
				$iva[$registro["detalleAlicuotaIVA"]]=array(
					'Id'=>$registro["detalleAlicuotaIVA"],
					'BaseImp'=>0,
					'Importe'=>0,
				);
			}
			$iva[$registro["detalleAlicuotaIVA"]]["BaseImp"]+=$registro["detalleImporteTotal"];
			$iva[$registro["detalleAlicuotaIVA"]]["Importe"]+=$registro["detalleImporteNeto"]-$registro["detalleImporteTotal"];
		}

		//Genero el array ivaformato
		foreach ($iva as $key => $value) {
			array_push($ivaformato, $value);
		}

		//Si el comprobante requiere comprobantes asociados (AFIP)
		if (in_array($movimientocompleto[0]["cabeceraComprobante"], $comprobantesconasoc)) {

			$idsAsociados = array();

			foreach ($movimientocompleto as $key => $value) {
				if($value["origenImportacion"]){

					$qCbteAsoc = "SELECT
					movimientos.ptoVenta,
					movimientos.nroComprobante,
					movimientos.idComprobante,
					`movimientos-detalle`.id
					FROM movimientos
					INNER JOIN `movimientos-detalle`
					ON movimientos.id = `movimientos-detalle`.idMovimientos
					WHERE `movimientos-detalle`.id = '".$value["origenImportacion"]."'";

					$resultado = mysqli_query($mysqli,$qCbteAsoc);

					if($resultado->num_rows == 1){
						
						$registro=mysqli_fetch_assoc($resultado);
						
						if(!in_array($registro["nroComprobante"], $idsAsociados)){
							array_push($idsAsociados, $registro["nroComprobante"]);
							array_push($cbteasocformato, array(
								'Tipo' => $registro["idComprobante"],
								'PtoVta' => $registro["ptoVenta"],
								'Nro' => $registro["nroComprobante"]
							));
						}
					}
				}
			}
		}

		//Si es venta, se autoriza y no está auditado
		if ($movimientocompleto[0]["cabeceraTipoMovimiento"] == 1 && $movimientocompleto[0]["cabeceraSeAutoriza"] == 1 && $movimientocompleto[0]["cabeceraEstado"] == 1) {
			//Se autoriza			
			$autoriza=TRUE;
			//Incluyo librería AFIP
			include 'librerias/facturaelectronica/Afip.php';
			//Obtengo la configuración y la guardo en configuración
			$sql="SELECT * FROM configuracion";
			$resultado=mysqli_query($mysqli,$sql);
			$configuracion=mysqli_fetch_assoc($resultado);
			$configuracion = f_array_map('utf8_encode', $configuracion);
			//Inicializo la clase AFIP
			$afip = new Afip(array('CUIT' => cuitnumerico($configuracion["documento"])));
			//Obtengo el último comprobante
			$ultimocomprobante = $afip->GetLastVoucher($configuracion["puntoVentaElectronico"],$movimientocompleto[0]["cabeceraComprobante"]); //Devuelve el número del último comprobante creado para el punto de venta 1 y el tipo de comprobante 6 (Factura B)
			//armo el objeto para auditar

			$data = array(
				'CantReg' 		=> 1, // Cantidad de comprobantes a registrar
				'PtoVta' 		=> $configuracion["puntoVentaElectronico"], // Punto de venta
				'CbteTipo' 		=> $movimientocompleto[0]["cabeceraComprobante"], // Tipo de comprobante (ver tipos disponibles)
				'Concepto' 		=> 1, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
				'DocTipo' 		=> $movimientocompleto[0]["cabeceraIdDocTercero"], // Tipo de documento del comprador (ver tipos disponibles)
				'DocNro' 		=> cuitnumerico($movimientocompleto[0]["cabeceraNumeroDocTercero"]), // Numero de documento del comprador
				'CbteDesde' 	=> $ultimocomprobante +1, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
				'CbteHasta' 	=> $ultimocomprobante +1, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
				'CbteFch' 		=> date("Ymd", strtotime($movimientocompleto[0]["cabeceraFecha"])), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
				'ImpTotal' 		=> $movimientocompleto[0]["cabeceraImporteNeto"], // Importe total del comprobante
				'ImpTotConc' 	=> 0, // Importe neto no gravado
				'ImpNeto' 		=> $movimientocompleto[0]["cabeceraImporteTotal"], // Importe neto gravado
				'ImpOpEx' 		=> 0, // Importe exento de IVA
				'ImpIVA' 		=> $movimientocompleto[0]["cabeceraImporteIVA"], //Importe total de IVA
				'ImpTrib' 		=> 0, //Importe total de tributos
				'FchServDesde' 	=> NULL, // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'FchServHasta' 	=> NULL, // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'FchVtoPago' 	=> NULL, // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
				'MonId' 		=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos)
				'MonCotiz' 		=> 1, // Cotización de la moneda usada (1 para pesos argentinos)
				'Iva' 			=> $ivaformato, // (Opcional) Alícuotas asociadas al comprobante
			);
			//Si requiere comprobante asociado le sumo el campo al objeto
			if($cbteasocformato){
				$data['CbtesAsoc'] = $cbteasocformato;
			}
			//Envío el comprobante a AFIP
			$res = $afip->CreateVoucher($data);
			//Si obtuve respuesta de AFIP		
			if ($res) {
				//Guardo la respuesta para devolver en el JSON
				$respuesta["facturaelectronica"]=$res;
				//Guardo la respuesta en la base de datos
				$sql="INSERT INTO `respuestas-afip`
				(respuesta)
				VALUES
				('".json_encode($res)."')";
				mysqli_query($mysqli,$sql);
			}
			//Si el resultado es APROBADO
			if ($res->FeCabResp->Resultado == 'A') {
				$audita=TRUE;
			//Si el resultado NO es APROBADO
			}else{
				$audita=FALSE;
			}
		//Si NO es venta O NO se autoriza O ESTÁ auditado
		}else{
			$audita=TRUE;
			$autoriza=FALSE;
		}
		//Si se debe auditar
		if ($audita===TRUE) 
		{
			//Obtengo el movimiento (nuevamente?)
			$sql="SELECT movimientos.tipoMovimiento,
			`movimientos-detalle`.cant,
			`movimientos-detalle`.idUnidadMedida,
			`movimientos-detalle`.codProducto,
			`movimientos-detalle`.origenImportacion,
			comprobantes.id AS comprobanteid,
			comprobantes.ventaStock,
			comprobantes.ventaStockReservadoVenta,
			comprobantes.ventaStockReservadoCompra,
			comprobantes.compraStock,
			comprobantes.compraStockReservadoVenta,
			comprobantes.compraStockReservadoCompra,
			`articulos-stock`.id AS idarticulostock,
			`articulos-stock`.stock,
			`articulos-stock`.stockReservadoVenta,
			`articulos-stock`.stockReservadoCompra,
			`articulos-stock`.stockMinimo,
			comprobanteorigen.id AS idcomprobanteorigen
			FROM movimientos
			INNER JOIN `movimientos-detalle` ON movimientos.id = `movimientos-detalle`.idMovimientos
			INNER JOIN comprobantes ON movimientos.idComprobante = comprobantes.id
			INNER JOIN `articulos-stock` ON `movimientos-detalle`.codProducto =
			`articulos-stock`.idArticulo AND `movimientos-detalle`.idUnidadMedida =
			`articulos-stock`.idUnidadMedida
			LEFT JOIN `movimientos-detalle` AS detalleorigen ON `movimientos-detalle`.origenImportacion = detalleorigen.id 
			LEFT JOIN movimientos AS cabeceraorigen ON detalleorigen.idMovimientos = cabeceraorigen.id
			LEFT JOIN comprobantes AS comprobanteorigen ON cabeceraorigen.idComprobante = comprobanteorigen.id
			WHERE movimientos.id='".$_REQUEST["id"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			//Recorro los items del movimiento
			for ($i=0; $i < $resultado->num_rows; $i++) {
				//Por cada registro
				$registro=mysqli_fetch_assoc($resultado);
				//Variable temporal
				$cambios=0;
				//Guardo el stock actual

				$sql = "SELECT
				stock,
				stockReservadoVenta,
				stockReservadoCompra
				FROM `articulos-stock`
				WHERE id='".$registro["idarticulostock"]."'";

				$resultado2=mysqli_query($mysqli,$sql);
				$stockRegistro=mysqli_fetch_assoc($resultado2);

				$stock=$stockRegistro["stock"];
				$stockReservadoVenta=$stockRegistro["stockReservadoVenta"];
				$stockReservadoCompra=$stockRegistro["stockReservadoCompra"];
				//Si el comprobante origen de la importación es distinto al comprobante actual
				/* Esto evita que el stock cambie si se importa, por ejemplo, de una nota de pedido a otra */
				if($registro["comprobanteid"] != $registro["idcomprobanteorigen"]){
					//Si es venta
					if ($registro["tipoMovimiento"]==1) {
						
						if ($registro["ventaStock"]==1) {
							$cambios++;
							$stock += $registro["cant"];
						}
						if ($registro["ventaStock"]==2) {
							$cambios++;
							$stock -= $registro["cant"];
						}
	
						if ($registro["ventaStockReservadoVenta"]==1) {
							$cambios++;
							$stockReservadoVenta += $registro["cant"];
						}
						if ($registro["ventaStockReservadoVenta"]==2) {
							$cambios++;
							$stockReservadoVenta -= $registro["cant"];
						}
	
						if ($registro["ventaStockReservadoCompra"]==1) {
							$cambios++;
							$stockReservadoCompra += $registro["cant"];
						}
						if ($registro["ventaStockReservadoCompra"]==2) {
							$cambios++;
							$stockReservadoCompra -= $registro["cant"];
						}
	
					}else{
	
						if ($registro["compraStock"]==1) {
							$cambios++;
							$stock += $registro["cant"];
						}
						if ($registro["compraStock"]==2) {
							$cambios++;
							$stock -= $registro["cant"];
						}
	
						if ($registro["compraStockReservadoVenta"]==1) {
							$cambios++;
							$stockReservadoVenta += $registro["cant"];
						}
						if ($registro["compraStockReservadoVenta"]==2) {
							$cambios++;
							$stockReservadoVenta -= $registro["cant"];
						}
	
						if ($registro["compraStockReservadoCompra"]==1) {
							$cambios++;
							$stockReservadoCompra += $registro["cant"];
						}
						if ($registro["compraStockReservadoCompra"]==2) {
							$cambios++;
							$stockReservadoCompra -= $registro["cant"];
						}
	
					}

				}

				if ($cambios>0) {

					$sql="UPDATE `articulos-stock`
					SET
					stock='".$stock."',
					stockReservadoVenta='".$stockReservadoVenta."',
					stockReservadoCompra='".$stockReservadoCompra."'
					WHERE id='".$registro["idarticulostock"]."'";

					mysqli_query($mysqli,$sql);

					actualizarStock($registro["idarticulostock"], $stock, $stockReservadoVenta, $stockReservadoCompra, $registro["stockMinimo"]);

				}

			}

			if ($autoriza === TRUE) {
				$sql = "UPDATE `movimientos`
				SET
				`idEstado` = '2',
				`cae` = '".$res->FeDetResp->FECAEDetResponse->CAE."',
				vtoCae = '".date("Y-m-d", strtotime($res->FeDetResp->FECAEDetResponse->CAEFchVto))."',
				nroComprobante = '".($ultimocomprobante + 1)."'
				WHERE `movimientos`.`id` = '".$_REQUEST["id"]."'";
			}else{
				$sql = "UPDATE `movimientos` SET `idEstado` = '2' WHERE `movimientos`.`id` = '".$_REQUEST["id"]."'";
			}

			$resultado=mysqli_query($mysqli,$sql);



		}

		$respuesta["exito"]["audita"]=$audita;
		$respuesta["exito"]["autoriza"]=$autoriza;

		break;

/********************* MOVIMIENTOS PARA IMPORTAR  ********************/

	case 'movimientosparaimportar':

		if ($_REQUEST["comprobante"] == "c") {

			//Importar una cotización
			$sql = "SELECT
			cotizaciones.id,
			0 AS ptoVenta,
			cotizaciones.id AS nroComprobante,
			DATE(cotizaciones.fecha) AS fecha,
			cotizaciones.importeTotal,
			COUNT(cotizaciones_detalles.id) AS cantdetalles
			FROM cotizaciones
			INNER JOIN cotizaciones_detalles
			ON cotizaciones_detalles.idCotizacion = cotizaciones.id
			WHERE cotizaciones.estado = 1
			AND cotizaciones.idTercero = '".$_REQUEST["tercero"]."'
			AND cotizaciones_detalles.cantidadAprobada > 0
			GROUP BY cotizaciones.id
			ORDER BY cotizaciones.id ASC";

		}else{

			//Importar otro documento

			$sql = "SELECT movimientos.ptoVenta,
			movimientos.nroComprobante,
			movimientos.fecha,
			movimientos.tipoMovimiento,
			terceros.denominacionCorta,
			movimientos.importeTotal,
			movimientos.idComprobante,
			movimientos.idEstado,
			movimientos.contable,
			`movimientos-detalle`.estadoImportacion,
			Count(`movimientos-detalle`.id) AS cantdetalles,
			movimientos.id
			FROM movimientos
			INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos =
			movimientos.id
			INNER JOIN terceros ON movimientos.idTercero = terceros.id
			WHERE (`movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant OR `movimientos-detalle`.cantidadImportada IS NULL)
			AND movimientos.tipoMovimiento='".$_REQUEST["tipomovimiento"]."'
			AND movimientos.contable='".$_REQUEST["contable"]."'
			AND movimientos.idComprobante='".$_REQUEST["comprobante"]."'
			AND movimientos.idTercero='".$_REQUEST["tercero"]."'
			AND movimientos.idEstado=2
			GROUP BY movimientos.id
			ORDER BY movimientos.id ASC";


			/*
			cantidadImportada < cant (puede ser que importe menos cantidad y queden pendientes items)
			tipo de movimiento venta o compra
			contable o no contable
			comprobante lo selecciona en el combo
			debe ser el mismo tercero
			idEstado 2 (auditado)

			*/
		}


		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}


		break;

/********************* IMPORTAR MOVIMIENTO  ********************/

	case 'importarmovimiento':

		$sql = "SELECT `movimientos-detalle`.*,
		`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada AS cantidadimportar,
		`movimientos-detalle`.cantidadImportada AS cantidadreservada,
		`unidades-medida`.denominacionCorta,
		`alicuotas-iva`.valor AS iva,
		CONCAT(
			COALESCE(articulos.denominacionExterna,''),
			' ',
			COALESCE(articulos.denominacionInterna, '')
		) AS 'articulo'
		FROM `movimientos-detalle`
		INNER JOIN `alicuotas-iva`
		ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
		INNER JOIN `unidades-medida`
		ON `movimientos-detalle`.idUnidadMedida = `unidades-medida`.id
		LEFT JOIN articulos
		ON articulos.id = `movimientos-detalle`.codProducto
		WHERE idMovimientos = '".$_REQUEST["id"]."'
		AND (
			`movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant
			OR
			`movimientos-detalle`.cantidadImportada IS NULL
		)";

		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}

		$sql = "UPDATE `movimientos-detalle`
		SET cantidadImportada = cant
		WHERE idMovimientos = '".$_REQUEST["id"]."'
		AND (`movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant OR `movimientos-detalle`.cantidadImportada IS NULL)";

		$resultado=mysqli_query($mysqli,$sql);

		break;

/********************* IMPORTAR COTIZACIÓN  ********************/

		case 'importarcotizacion';

		$sql = "SELECT
		cotizaciones_detalles.cantidadAprobada as cantidad,
		cotizaciones_detalles.idArticulo AS idarticulo,
		cotizaciones_detalles.precioCotizado AS preciocotizado
		FROM cotizaciones_detalles
		WHERE cotizaciones_detalles.idCotizacion = '".$_REQUEST["id"]."'
		AND cotizaciones_detalles.cantidadAprobada > 0";

		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}

		break;

/********************* CAMBIAR CANTIDAD  ********************/

	// Cambia la cantidad importada de un detalle de movimiento
	// Requiere de 2 parámetros : id del movimiento-detalle y nueva cantidad

	case 'cambiarcantidadimportada':

		//Selecciono la cantidad disponible para importar

		$sql = "SELECT
		cant AS cant
		FROM `movimientos-detalle`
		WHERE id = '".$_REQUEST["id"]."'";

		$resultado=mysqli_query($mysqli,$sql);

		$registro = mysqli_fetch_assoc($resultado);

		//Si la nueva cantidad es mayor a la disponible

		if ($_REQUEST["cantidad"] > $registro["cant"]) {

			//La nueva cantidad importada es lo máximo que tiene disponible

			$nuevacantidad = $registro["cant"];
		}else{

			//Sino, la nueva cantidad importada, será la cantidad que quiero usar

			$nuevacantidad = $_REQUEST["cantidad"];
		}

		// Actualizo la cantidad importada con la nueva cantidad

		$sql = "UPDATE `movimientos-detalle`
		SET cantidadImportada = '".$nuevacantidad."'
		WHERE id = '".$_REQUEST["id"]."'";

		$resultado=mysqli_query($mysqli,$sql);

		break;

/********************* ELIMINAR ORIGEN  ********************/

	case 'eliminarorigen':

		foreach ($_REQUEST["data"] as $value) {

			$sql = "UPDATE `movimientos-detalle`
			SET cantidadImportada = cantidadImportada - '".$value["cantidad"]."'
			WHERE id = '".$value["id"]."'";		

			$resultado=mysqli_query($mysqli,$sql);

		}


		break;

/********************* RECUPERA IMPORTADOS ELIMINADOS ********************/
/*
	case 'recuperaimportadoseliminados':

			$sql="UPDATE `movimientos-detalle`
			LEFT JOIN `movimientos-detalle` `movimientos-detalle1`
		ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id
			SET `movimientos-detalle1`.estadoImportacion = 2
			WHERE `movimientos-detalle`.idMovimientos = '".$_REQUEST["idmovimiento"]."'
			AND `movimientos-detalle1`.estadoImportacion=3";

			$resultado=mysqli_query($mysqli,$sql);

		break;
*/
/********************* RECUPERA IMPORTADOS ELIMINADOS ********************/

	case 'recuperareliminados':

		foreach ($_REQUEST["datos"] as $key => $value) {
			if ($value != "") {

				$sql = "UPDATE `movimientos-detalle`
				SET cantidadImportada = cantidadImportada + ".$value."
				WHERE id = ".$key;

				$resultado=mysqli_query($mysqli,$sql);

			}
		}

	break;

///////////////////////////////// RECIBOS /////////////////////////////////

/********************* FILTRAR MOVIMIENTOS ********************/

	case 'filtrarmovimientos':

		$terceros = $_REQUEST["idtercero"];

		$sql = "SELECT * FROM sucursales WHERE idTerceroPadre = '".$_REQUEST["idtercero"]."'";

		$resultado = mysqli_query($mysqli, $sql);

		if ($resultado) {
			if ($resultado->num_rows > 0) {
				$tercerosarray = array();
				for ($i=0; $i < $resultado->num_rows; $i++) {
					$registro=mysqli_fetch_assoc($resultado);
					$registro = f_array_map('utf8_encode', $registro);
					array_push($tercerosarray, $registro["idTerceroSucursal"]);
				}
				$terceros = implode(",", $tercerosarray);
			}
		}

		$sql = "SELECT `movimientos`.*,
			`comprobantes`.denominacion as comprobantenombre,
			`comprobantes`.comportamiento as comprobantecomportamiento,
			terceros.denominacion AS denominaciontercero
		 FROM `movimientos`
		 INNER JOIN terceros
		 ON movimientos.idTercero = terceros.id
		 inner join `comprobantes`
		 on comprobantes.id = movimientos.idComprobante
		 where idTercero IN('".$terceros."')
		 AND tipoMovimiento='".$_REQUEST["tiporecibopago"]."'
		 AND contable = '".$_REQUEST["contable"]."'
		 AND comprobantes.id IN (1,2,3,6,7,8,11,12,13)
		 AND importeNeto - importeCancelado > 0
		 AND idEstado=2";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}

		break;

/********************* FILTRAR ADELANTOS ********************/

	case 'filtraradelantos':
		$sql = "SELECT *
				FROM `adelantos`
		 where idTercero= '".$_REQUEST["idtercero"]."'
		 AND usado IS NULL
		 AND tipoAdelanto='".$_REQUEST["tiporecibopago"]."'";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}

		break;

	case 'tipo-medio-pago':

	/********** DETERMINA SI ES CHEQUE EL MEDIO DE PAGO ***********/

		$sql = "SELECT esCheque FROM `medios-pagos` WHERE id = '".$_REQUEST["idmediopago"]."'";
		$resultado=mysqli_query($mysqli,$sql);
		$registro=mysqli_fetch_assoc($resultado);

		if ($registro["esCheque"] == 1) {
			$respuesta["exito"] = TRUE;
		}else{
			$respuesta["exito"] = FALSE;
		}

	break;

/********************* CHEQUES DISPONIBLES ********************/

	case 'chequesdisponibles':
		$sql = "SELECT * FROM `cheques`
		 where estado= 1 or estado= 2
		 order by estado";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}

		break;

/********************* CAMBIA ESTADO CHEQUE ********************/

	case 'cambiarestadocheque':
		$sql = "UPDATE `cheques` SET `estado` = '".$_REQUEST["valor"]."' WHERE `cheques`.`id` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);

		break;

/********************* AUDITA RECIBO ********************/

	case 'auditarrecibo':

		$respuesta["cheques"]=array();
		$sql = "UPDATE `recibos-pagos` SET `idEstado` = '2' WHERE `recibos-pagos`.`id` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);

		//cabecera

		$sql = "SELECT * FROM `recibos-pagos`
		WHERE `id` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);
		$registrocabecera=mysqli_fetch_assoc($resultado);

		//medios de pagos

		$sql = "SELECT * FROM `recibos-pagos-detalle`
		WHERE `idRecibo-Pago` = '".$_REQUEST["id"]."'
		AND idMedioPago = 1";
		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);

			if ($registrocabecera["tipoFlujo"]==1) {

				//Si es un recibo guardo el cheque

				$qcheques="INSERT INTO `cheques`
					(
						`nro`,
						`banco`,
						`importe`,
						`fecha`,
						`estado`
					) VALUES (
						'".$registro["numero"]."',
						'".$registro["banco"]."',
						'".$registro["importe"]."',
						'".$registro["fecha"]."',
						1
					)";
				$qcheques=mysqli_query($mysqli,$qcheques);

				$codcheque=$mysqli->insert_id;

				$qdetalle="UPDATE `recibos-pagos-detalle`
				SET `codcheque` = '".$codcheque."'
				WHERE `recibos-pagos-detalle`.`id` = '".$registro["id"]."'";

				$qdetalle=mysqli_query($mysqli,$qdetalle);

				$registro["id"]=$codcheque;
				array_push($respuesta["cheques"], $registro);

			}else{

				//Si es un pago pongo el cheque como usado

				$qcheques="UPDATE `cheques` SET `estado` = '3' WHERE `cheques`.`id` = '".$registro["codcheque"]."'";
				$qcheques=mysqli_query($mysqli,$qcheques);
			}

		}

		//Si el importe recibido es mayor al importe de movimientos creo un adelanto

		if ($registrocabecera["importe"] + $registrocabecera["importeAdelantos"] > $registrocabecera["importeMovimientos"]) {

			$qadelanto="INSERT INTO `adelantos` (
				`fecha`,
				`idTercero`,
				`importe`,
				`usado`,
				`tipoAdelanto`
				) VALUES (
				'".$registrocabecera["fecha"]."',
				'".$registrocabecera["idTercero"]."',
				'".($registrocabecera["importe"] + $registrocabecera["importeAdelantos"] - $registrocabecera["importeMovimientos"])."',
				NULL,
				'".$registrocabecera["tipoFlujo"]."'
				)";
			$qadelanto=mysqli_query($mysqli,$qadelanto);
		}


		//Por cada documento le adiciono el valor cancelado

		$sql = "SELECT * FROM `movimientos-recibos-pagos`
		WHERE `idRecibo-Pago` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows ; $i++) {
			$registromovimientos=mysqli_fetch_assoc($resultado);
			$qmovimientos="update movimientos set `importeCancelado`=`importeCancelado`+".$registromovimientos["importeCancelado"]." WHERE id='".$registromovimientos["idMovimientos"]."'";
			$qmovimientos=mysqli_query($mysqli,$qmovimientos);
		}

		//Por cada adelanto lo marco como usado

		$sql = "SELECT * FROM `adelantos-recibos-pagos`
		WHERE `idRecibo-Pago` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows ; $i++) {
			$registroadelantos=mysqli_fetch_assoc($resultado);
			$qadelantos="update adelantos set `usado`=1 WHERE id='".$registroadelantos["idAdelanto"]."'";
			$qadelantos=mysqli_query($mysqli,$qadelantos);
		}


		break;

/********************* AUDITA COTIZACION ********************/

	case 'auditar-cotizacion':

		//Se modifican los descuentos al cliente solo cuando se audita la cotización

		//Obtengo los datos de la cotización

		$sql = "SELECT
		cotizaciones_detalles.idArticulo,
		cotizaciones_detalles.dtoCliente,
		cotizaciones.idTercero
		FROM cotizaciones_detalles
		INNER JOIN cotizaciones
		ON cotizaciones_detalles.idCotizacion = cotizaciones.id
		WHERE cotizaciones.id = '".$_REQUEST["id"]."'";

		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows; $i++) {

			$registro=mysqli_fetch_assoc($resultado);

			$sql = "SELECT id FROM `articulos-terceros-descuentos`
			WHERE `articulos-terceros-descuentos`.idArticulo = '".$registro["idArticulo"]."'
			AND `articulos-terceros-descuentos`.idTercero = '".$registro["idTercero"]."'";

			$resultado2=mysqli_query($mysqli,$sql);

			if (mysqli_num_rows($resultado2) > 0) {

				$sql = "UPDATE `articulos-terceros-descuentos`
				SET descuento = '".$registro["dtoCliente"]."'
				WHERE `articulos-terceros-descuentos`.idArticulo = '".$registro["idArticulo"]."'
				AND `articulos-terceros-descuentos`.idTercero = '".$registro["idTercero"]."'";

				mysqli_query($mysqli,$sql);
			}else{

				$sql = "INSERT INTO `articulos-terceros-descuentos`
				(idArticulo, idTercero, descuento) VALUES (
					'".$registro["idArticulo"]."',
					'".$registro["idTercero"]."',
					'".$registro["dtoCliente"]."'
				)";

				mysqli_query($mysqli,$sql);
			}	

		}

		$terceros = array();

		$sql = "SELECT idTercero
		FROM descuentosasociados
		WHERE idTerceroBase = ".$registro["idTercero"];

		$resultado=mysqli_query($mysqli,$sql);

		for ($i=0; $i < $resultado->num_rows ; $i++) {
			$reg=mysqli_fetch_assoc($resultado);
			array_push($terceros, $reg["idTercero"]);
		}

		foreach ($terceros as $tercero) {

			$sql = "SELECT *
			FROM `articulos-terceros-descuentos`
			WHERE idTercero = '".$registro["idTercero"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			$rows = array();

			for ($i=0; $i < $resultado->num_rows ; $i++) {
				$reg=mysqli_fetch_assoc($resultado);
				array_push($rows, $reg);
			}

			if(count($rows) > 0){

				$values = array();

				foreach ($rows as $value) {
					$reg = array(
						$value["idArticulo"],
						$tercero,
						$value["descuento"]
					);
					$reg = implode(",", $reg);
					$reg = '('.$reg.')';
					array_push($values, $reg);
				}

				$values = implode(",", $values);

				//Borro los descuentos actuales

				$sql = "DELETE FROM `articulos-terceros-descuentos`
				WHERE idTercero = ".$tercero;

				mysqli_query($mysqli,$sql);

				//Inserto los descuentos

				$sql = "INSERT INTO `articulos-terceros-descuentos`
				(
					idArticulo,
					idTercero,
					descuento
				) VALUES ".$values;

				mysqli_query($mysqli,$sql);
			}		
		}

	
////
		$sql="UPDATE cotizaciones
		SET estado = 1
		WHERE id = '".$_REQUEST["id"]."'";

		$resultado = mysqli_query($mysqli,$sql);

	break;



/********************* GUARDAR RECIBO ********************/


	case 'guardarrecibo':
		$error=array();

		/*Comprobación de errores de cabecera*/

		if ($_REQUEST["cabecera"]["nrocomprobante"]=="") {
			array_push($error, "Falta el número del comprobante");
		}

		if ($_REQUEST["cabecera"]["tercero"]=="" || $_REQUEST["cabecera"]["tercero"]==0 || !isset($_REQUEST["cabecera"]["tercero"])) {
			array_push($error, "Falta el tercero");
		}

		/*FIN Comprobación de errores de cabecera*/

		/*Comprobación de errores de detalle*/

		if (isset($_REQUEST["detalles"])) {

			for ($i=0; $i < count($_REQUEST["detalles"]); $i++) {


			}
		}

		/*FIN Comprobación de errores de detalle*/

		if (count($error)==0) {

			//Guardo Cabecera

			$sql="INSERT INTO `recibos-pagos` (
				`tipoFlujo`,
				`fecha`,
				`idTercero`,
				`importe`,
				`nroComprobante`,
				`contable`,
				`idEstado`,
				`importeMovimientos`,
				`importeAdelantos`
			) VALUES (
				'".$_REQUEST["cabecera"]["tiporecibopago"]."',
				'".$_REQUEST["cabecera"]["fecha"]."',
				'".$_REQUEST["cabecera"]["tercero"]."',
				'".$_REQUEST["cabecera"]["importetotal"]."',
				'".$_REQUEST["cabecera"]["nrocomprobante"]."',
				'".$_REQUEST["cabecera"]["contable"]."',
				1,
				'".$_REQUEST["cabecera"]["sumamovimientos"]."',
				'".$_REQUEST["cabecera"]["sumaadelantos"]."'
			)";
			$resultado=mysqli_query($mysqli,$sql);

			$ultimoid=$mysqli->insert_id;

			//Guardo Detalle

			if (isset($_REQUEST["detalles"])) {
				for ($i=0; $i < count($_REQUEST["detalles"]); $i++) {
					if ($_REQUEST["detalles"][$i]["importetotal"]>0) {

						$sql="INSERT INTO `recibos-pagos-detalle`
							(
								`idRecibo-Pago`,
								`idMedioPago`,
								`importe`,
								`codcheque`,
								`banco`,
								`numero`,
								`fecha`,
								`observaciones`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["detalles"][$i]["mediopago"]."',
								'".$_REQUEST["detalles"][$i]["importetotal"]."',
								'".$_REQUEST["detalles"][$i]["codcheque"]."',
								'".$_REQUEST["detalles"][$i]["banco"]."',
								'".$_REQUEST["detalles"][$i]["nro"]."',
								'".$_REQUEST["detalles"][$i]["fecha"]."',
								'".$_REQUEST["detalles"][$i]["observaciones"]."'
							)";


						$resultado=mysqli_query($mysqli,$sql);



					}
				}

			}


			//Guardo los importes cancelados
			if (isset($_REQUEST["movimientos"])) {
				for ($i=0; $i < count($_REQUEST["movimientos"]); $i++) {
					if ($_REQUEST["movimientos"][$i]["importecancelado"]>0) {

						$sql="INSERT INTO `movimientos-recibos-pagos`
							(
								`idRecibo-Pago`,
								`idMovimientos`,
								`importeCancelado`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["movimientos"][$i]["codigocomp"]."',
								'".$_REQUEST["movimientos"][$i]["importecancelado"]."'
							)";
						$resultado=mysqli_query($mysqli,$sql);
					}
				}
			}

			//Guardo los adelantos

			if (isset($_REQUEST["adelantos"])) {
				for ($i=0; $i < count($_REQUEST["adelantos"]); $i++) {


						$sql="INSERT INTO `adelantos-recibos-pagos`
							(
								`idRecibo-Pago`,
								`idAdelanto`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["adelantos"][$i]["codigoadelanto"]."'
							)";
						$resultado=mysqli_query($mysqli,$sql);

				}

			}



		}

		$respuesta["error"]=$error;

		break;

/********************* EDITAR RECIBO ********************/


	case 'editarrecibo':
		$error=array();

		/*Comprobación de errores de cabecera*/

		if ($_REQUEST["cabecera"]["nrocomprobante"]=="") {
			array_push($error, "Falta el número del comprobante");
		}

		if ($_REQUEST["cabecera"]["tercero"]=="" || $_REQUEST["cabecera"]["tercero"]==0 || !isset($_REQUEST["cabecera"]["tercero"])) {
			array_push($error, "Falta el tercero");
		}

		/*FIN Comprobación de errores de cabecera*/

		/*Comprobación de errores de detalle*/

		if (isset($_REQUEST["detalles"])) {

			for ($i=0; $i < count($_REQUEST["detalles"]); $i++) {


			}
		}

		/*FIN Comprobación de errores de detalle*/

		if (count($error)==0) {

			//Edito Cabecera

			$sql="UPDATE `recibos-pagos` SET
			`tipoFlujo` = '".$_REQUEST["cabecera"]["tiporecibopago"]."',
			`fecha`='".$_REQUEST["cabecera"]["fecha"]."',
			`idTercero`='".$_REQUEST["cabecera"]["tercero"]."',
			`importe`='".$_REQUEST["cabecera"]["importetotal"]."',
			`nroComprobante`='".$_REQUEST["cabecera"]["nrocomprobante"]."',
			`contable`='".$_REQUEST["cabecera"]["contable"]."',
			`idEstado`=1,
			`importeMovimientos`='".$_REQUEST["cabecera"]["sumamovimientos"]."',
			`importeAdelantos`='".$_REQUEST["cabecera"]["sumaadelantos"]."'
			WHERE `recibos-pagos`.`id` = '".$_REQUEST["id"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			$ultimoid=$_REQUEST["id"];


			//Guardo Detalle

			if (isset($_REQUEST["detalles"])) {
			$sql="DELETE FROM `recibos-pagos-detalle` WHERE `idRecibo-Pago`='".$ultimoid."'";
			$resultado=mysqli_query($mysqli,$sql);
				for ($i=0; $i < count($_REQUEST["detalles"]); $i++) {
					if ($_REQUEST["detalles"][$i]["importetotal"]>0) {

						$sql="INSERT INTO `recibos-pagos-detalle`
							(
								`idRecibo-Pago`,
								`idMedioPago`,
								`importe`,
								`codcheque`,
								`banco`,
								`numero`,
								`fecha`,
								`observaciones`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["detalles"][$i]["mediopago"]."',
								'".$_REQUEST["detalles"][$i]["importetotal"]."',
								'".$_REQUEST["detalles"][$i]["codcheque"]."',
								'".$_REQUEST["detalles"][$i]["banco"]."',
								'".$_REQUEST["detalles"][$i]["nro"]."',
								'".$_REQUEST["detalles"][$i]["fecha"]."',
								'".$_REQUEST["detalles"][$i]["observaciones"]."'
							)";
						$resultado=mysqli_query($mysqli,$sql);

					}
				}

			}



			//Guardo los importes cancelados
			if (isset($_REQUEST["movimientos"])) {
			$sql="DELETE FROM `movimientos-recibos-pagos` WHERE `idRecibo-Pago`='".$ultimoid."'";
			$resultado=mysqli_query($mysqli,$sql);
				for ($i=0; $i < count($_REQUEST["movimientos"]); $i++) {
					if ($_REQUEST["movimientos"][$i]["importecancelado"]>0) {

						$sql="INSERT INTO `movimientos-recibos-pagos`
							(
								`idRecibo-Pago`,
								`idMovimientos`,
								`importeCancelado`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["movimientos"][$i]["codigocomp"]."',
								'".$_REQUEST["movimientos"][$i]["importecancelado"]."'
							)";
						$resultado=mysqli_query($mysqli,$sql);
					}
				}
			}

			//Guardo los adelantos

			if (isset($_REQUEST["adelantos"])) {
			$sql="DELETE FROM `adelantos-recibos-pagos` WHERE `idRecibo-Pago`='".$ultimoid."'";
			$resultado=mysqli_query($mysqli,$sql);
				for ($i=0; $i < count($_REQUEST["adelantos"]); $i++) {


						$sql="INSERT INTO `adelantos-recibos-pagos`
							(
								`idRecibo-Pago`,
								`idAdelanto`
							) VALUES (
								'".$ultimoid."',
								'".$_REQUEST["adelantos"][$i]["codigoadelanto"]."'
							)";
						$resultado=mysqli_query($mysqli,$sql);
				}
			}
		}

		$respuesta["error"]=$error;

		break;

/********************* DETALLE DE RECIBO ********************/

	case 'retornardetallerecibo':
		$sql = "SELECT `medios-pagos`.denominacion AS medio,
  `recibos-pagos-detalle`.importe AS importe,
  `recibos-pagos-detalle`.banco AS banco,
  `recibos-pagos-detalle`.numero AS numero,
  `recibos-pagos-detalle`.fecha AS fecha,
  `recibos-pagos-detalle`.codcheque AS codigo
FROM `recibos-pagos-detalle`
  INNER JOIN `medios-pagos` ON `recibos-pagos-detalle`.idMedioPago =
	`medios-pagos`.id
WHERE `recibos-pagos-detalle`.`idRecibo-Pago` = '".$_REQUEST["id"]."'";
		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				//$registro["fecha"]=formatofecha($registro["fecha"]);

				if ($registro["fecha"] == "1970-01-01" || $registro["fecha"] == "0000-00-00") {
					$registro["fecha"] = "";
				}


				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}
		}

		break;

/********************* NUMERO COMPROBANTE ********************/

	case 'numerocomprobante':

		$respuesta["exito"] = obtenerNumeroComprobante($_REQUEST["comprobante"], $_REQUEST["puntoventa"], $_REQUEST["contable"]);

		break;

/********************* PUNTO DE VENTA ********************/

	case 'obtenerpuntoventa':

		$respuesta["exito"] = obtenerpuntoventa($_REQUEST["comprobante"]);

	break;		

/********************* ESTADO DE CUENTA ********************/

	case 'estadodecuenta':

	$tercero = "";

	if ($_REQUEST["tercero"] != 0) {
		$tercero = " AND idTercero = '".$_REQUEST["tercero"]."' ";
	}

		$sql = "SELECT
		movimientos.fecha,
		movimientos.tipoMovimiento,
		movimientos.idTercero,
		terceros.denominacion as tercero,
		movimientos.importeNeto,
		comprobantes.comportamiento,
		comprobantes.denominacion as comprobante
		FROM movimientos
		INNER JOIN comprobantes
		ON movimientos.idComprobante = comprobantes.id
		INNER JOIN terceros
		ON terceros.id = movimientos.idTercero
		WHERE comportamiento IS NOT NULL
		AND tipoMovimiento = '".$_REQUEST["tipomovimiento"]."'
		".$tercero."
		UNION
		SELECT
		`recibos-pagos`.fecha,
		`recibos-pagos`.tipoFlujo,
		`recibos-pagos`.idTercero,
		terceros.denominacion as tercero,
		`recibos-pagos`.importe,
		2,
		'Recibo'
		FROM `recibos-pagos`
		INNER JOIN terceros
		ON terceros.id = `recibos-pagos`.idTercero
		WHERE tipoFlujo = '".$_REQUEST["tipomovimiento"]."'
		".$tercero."
		ORDER BY idTercero, fecha";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro["fecha"]=formatofecha($registro["fecha"]);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}
		}

	break;

/********************* SALDOS ********************/

	case 'saldos':

	$tercero = "";

	if ($_REQUEST["tercero"] != 0) {
		$tercero = " AND idTercero = '".$_REQUEST["tercero"]."' ";
	}

	$sql = "SELECT
	movimientos.id,
	movimientos.idTercero AS tercero,
	terceros.denominacion AS denominaciontercero,
	DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS fecha,
	DATE_FORMAT(DATE_ADD(movimientos.fecha, INTERVAL movimientos.condicionVenta DAY), '%d/%m/%Y') AS fechaVencimiento,
	IF(comprobantes.comportamiento = 1, movimientos.importeNeto, movimientos.importeCancelado) AS debe,
	IF(comprobantes.comportamiento = 2, movimientos.importeNeto, movimientos.importeCancelado) AS haber,
	comprobantes.denominacion AS comprobante,
	CONCAT(movimientos.ptoVenta,'-',movimientos.nroComprobante) AS numeroComprobante
	FROM movimientos
	INNER JOIN comprobantes
	ON movimientos.idComprobante = comprobantes.id
	INNER JOIN terceros
	ON movimientos.idTercero = terceros.id
	WHERE movimientos.importeCancelado < movimientos.importeNeto
	AND comprobantes.comportamiento IN (1,2)
	AND movimientos.tipoMovimiento = '".$_REQUEST["tipomovimiento"]."'
	".$tercero."
	UNION
	SELECT
	adelantos.id,
	adelantos.idTercero AS tercero,
	terceros.denominacion AS denominaciontercero,
	DATE_FORMAT(adelantos.fecha, '%d/%m/%Y') AS fecha,
	'' AS fechaVencimiento,
	0 AS debe,
	adelantos.importe AS haber,
	'Adelanto' AS comprobante,
	'' AS numeroComprobante
	FROM adelantos
	INNER JOIN terceros
	ON adelantos.idTercero = terceros.id
	WHERE adelantos.usado IS NULL
	AND adelantos.tipoAdelanto = '".$_REQUEST["tipomovimiento"]."'
	".$tercero."
	ORDER BY tercero, fecha";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {
			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}
		}

	break;

	case 'actualizar-cotizacion-moneda':

		/********** ACTUALIZAR COTIZACIÓN MONEDA ***********/

		if (isset($_REQUEST["idmoneda"])) {

			$sql="SELECT * FROM `monedas` WHERE id = '".$_REQUEST["idmoneda"]."'";

		}else{

			$sql="SELECT * FROM `monedas`";

		}

		$resultado=mysqli_query($mysqli, $sql);

		if(!$resultado){
			$respuesta["error"]=TRUE;
		}else{

			$url = "https://www.dolarsi.com/api/api.php?";

			$get="type=cotizador";
			$monedas = json_decode(file_get_contents($url.$get));
			$get="type=valoresprincipales";
			$dolar = json_decode(file_get_contents($url.$get));

			$actualizaciones = 0;

			for ($i=0; $i < $resultado->num_rows; $i++) {

				$registro=mysqli_fetch_assoc($resultado);

				if ($registro["idMonedaActualizacion"] != NULL) {

					if ($registro["idMonedaActualizacion"] == 0){
						if ($registro["idTipoDolar"] != NULL) {
							$cotizacion = str_replace(",", ".", $dolar[$registro["idTipoDolar"]]->casa->venta);
						}else{
							$cotizacion = str_replace(",", ".", $monedas[$registro["idMonedaActualizacion"]]->casa->venta);
						}
					}else{
						$cotizacion = str_replace(",", ".", $monedas[$registro["idMonedaActualizacion"]]->casa->venta);
					}

					$valor = $cotizacion * $registro["porcentajeVariacion"]/100;
					$valor = $cotizacion + $valor;

					$sql="UPDATE `monedas` SET cotizacion = '".$valor."' WHERE id = '".$registro["id"]."'";
					mysqli_query($mysqli, $sql);
					$actualizaciones ++;

				}

			}

			if ($actualizaciones > 0) {
				$respuesta["error"] = FALSE;
				$respuesta["exito"] = $actualizaciones;

			}else{
				$respuesta["error"] = TRUE;
			}


		}

	break;

	case 'actualizacion-masiva-precios':

		/********** ACTUALIZAR MASIVA PRECIOS ***********/

		$ids = array();

		foreach ($_REQUEST["registros"] as $key => $value) {

			$sql = "UPDATE `articulos-proveedores` SET precio = '".$value."' WHERE codExterno = '".$key."'";
			mysqli_query($mysqli, $sql);

			array_push($ids, $key);

		}

		$idsquery = implode("','", $ids);

		$sql = "SELECT id, idArticulo FROM `articulos-proveedores` WHERE codExterno IN ('".$idsquery."')";
		$resultado = mysqli_query($mysqli, $sql);

		$ids = array();


		for ($i=0; $i < $resultado->num_rows; $i++) {

			$registro = mysqli_fetch_assoc($resultado);
			array_push($ids, $registro["idArticulo"]);

				$rows = array();

				$sql="SELECT
				`articulos-proveedores`.id,
				`articulos-proveedores`.idArticulo,
				`articulos-proveedores`.precio,
				`articulos-proveedores`.dto1,
				`articulos-proveedores`.dto2,
				`articulos-proveedores`.dto3,
				`terceros`.dto1 as 'dto1tercero',
				`terceros`.dto2 as 'dto2tercero', 
				`terceros`.dto3 as 'dto3tercero',
				`unidades-medida`.indiceConversion,
				monedas.cotizacion
				FROM `articulos-proveedores`
				INNER JOIN monedas
				ON `articulos-proveedores`.idMoneda = monedas.id
				INNER JOIN terceros
				ON `terceros`.id = `articulos-proveedores`.idTercero
				LEFT JOIN `unidades-medida`
				ON `unidades-medida`.id = `articulos-proveedores`.idUnidadMedida
				WHERE `articulos-proveedores`.id = '".$registro["id"]."'";

				$resultado2 = mysqli_query($mysqli, $sql);
				$registro2 = mysqli_fetch_assoc($resultado2);
				array_push($rows, $registro2);

				$precio=$rows[0]["precio"];

				//Si tiene unidad de medida
				if ($rows[0]["indiceConversion"] != NULL){
					$precio=$rows[0]["precio"] * $rows[0]["indiceConversion"];
				}					

				//$rows[0]["dto1"]==NULL && $rows[0]["dto2"]==NULL && $rows[0]["dto3"]==NULL) {
					if ($rows[0]["dto1tercero"]!=NULL && $rows[0]["dto1tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto1tercero"]/100);
					}
					if ($rows[0]["dto2tercero"]!=NULL && $rows[0]["dto2tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto2tercero"]/100);
					}
					if ($rows[0]["dto3tercero"]!=NULL && $rows[0]["dto3tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto3tercero"]/100);
					}
				//}else{
					if ($rows[0]["dto1"]!=NULL && $rows[0]["dto1"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto1"]/100);
					}
					if ($rows[0]["dto2"]!=NULL && $rows[0]["dto2"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto2"]/100);
					}
					if ($rows[0]["dto3"]!=NULL && $rows[0]["dto3"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto3"]/100);
					}
				//}

				$precio=$precio*$rows[0]["cotizacion"];

				$sql="UPDATE
				`articulos-proveedores`
				SET
				precioPesos='".$precio."',
				ultimaActualizacion='".date("Y-m-d")."'
				WHERE
				id='".$rows[0]["id"]."'";

				mysqli_query($mysqli, $sql);

		}


		actualizarPrecio($ids);



	break;

/********************* DESCUENTOS DE CLIENTE ********************/

	case 'obtener-descuentos-cliente':

	$cliente = $_REQUEST["idcliente"];
	$respuesta = array();
	$respuesta["descuentoscategorias"] = array();
	$respuesta["descuentossubcategorias"] = array();

	$sql = "SELECT
	idListaPrecios,
	dtoCliente
	FROM terceros
	WHERE id = '".$cliente."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows === 1) {
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);
		$respuesta["cabecera"] = $registro;
	}else{
		$respuesta["cabecera"] = array();
	}

	$sql = "SELECT
	`categorias-terceros-descuentos`.descuento,
	`categorias-articulos`.id,
	`categorias-articulos`.denominacion
	FROM `categorias-terceros-descuentos`
	INNER JOIN `categorias-articulos`
	ON `categorias-terceros-descuentos`.idCategoria = `categorias-articulos`.id
	WHERE `categorias-terceros-descuentos`.idTercero = '".$cliente."'";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["descuentoscategorias"], $registro);
		}
	}

	$sql = "SELECT
	`subcategoria-terceros-descuentos`.descuento,
	`subcategorias-articulos`.id,
	`subcategorias-articulos`.denominacion
	FROM `subcategoria-terceros-descuentos`
	INNER JOIN `subcategorias-articulos`
	ON `subcategoria-terceros-descuentos`.idSubcategoria = `subcategorias-articulos`.id
	WHERE `subcategoria-terceros-descuentos`.idTercero = '".$cliente."'";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["descuentossubcategorias"], $registro);
		}
	}

	break;

/********************* DESCUENTOS DE ARTICULOS ********************/

	case 'obtener-descuentos-articulos':

	$cliente = $_REQUEST["idcliente"];

	$categoria = "";

	if (isset($_REQUEST["categoria"])) {
		if (!empty($_REQUEST["categoria"])) {
			$categoria = " AND articulos.idCategoria = '".$_REQUEST["categoria"]."'";
		}
	}

	$subcategoria = "";

	if (isset($_REQUEST["subcategoria"])) {
		if (!empty($_REQUEST["subcategoria"])) {
			$subcategoria = " AND articulos.idSubcateogoria = '".$_REQUEST["subcategoria"]."'";
		}
	}

	$rubro = "";

	if (isset($_REQUEST["rubro"])) {
		if (!empty($_REQUEST["rubro"])) {
			$rubro = " AND articulos.idRubro = '".$_REQUEST["rubro"]."'";
		}
	}

	$marca = "";

	if (isset($_REQUEST["marca"])) {
		if (!empty($_REQUEST["marca"])) {
			$marca = " AND articulos.idMarca = '".$_REQUEST["marca"]."'";
		}
	}

	$respuesta = array();

	$sql = "SELECT
		articulos.id,
		articulos.denominacionExterna,
		articulos.denominacionInterna,
		IFNULL(articulos.rentabilidad,0) as rentabilidad,
		ROUND(IFNULL(articulos.precioVenta,0),2) as precioventa,
		IFNULL(`categorias-terceros-descuentos`.descuento,0) AS dtocat,
		IFNULL(`subcategoria-terceros-descuentos`.descuento,0) AS dtosub,
		IFNULL(`articulos-terceros-descuentos`.descuento,0) AS dtoart,
		IFNULL(terceros.dtoCliente,0) AS dtocli,
		IFNULL(`lista-precios`.descuento,0) AS dtolis
		FROM `articulos`
		LEFT JOIN `categorias-terceros-descuentos`
		ON articulos.idCategoria = `categorias-terceros-descuentos`.idCategoria
		AND `categorias-terceros-descuentos`.idTercero = '".$cliente."'
		LEFT JOIN `subcategoria-terceros-descuentos`
		ON articulos.idSubcateogoria = `subcategoria-terceros-descuentos`.idSubcategoria
		AND `subcategoria-terceros-descuentos`.idTercero = '".$cliente."'
		LEFT JOIN `articulos-terceros-descuentos`
		ON articulos.id = `articulos-terceros-descuentos`.idArticulo
		AND `articulos-terceros-descuentos`.idTercero = '".$cliente."'
		JOIN terceros
		ON terceros.id =  '".$cliente."'
		LEFT JOIN `lista-precios`
		ON terceros.idListaPrecios = `lista-precios`.id
		WHERE 1 = 1".
	  $categoria.$subcategoria.$rubro.$marca.
	  " LIMIT 100";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta, $registro);
		}
	}

	break;

/********************* ACTUALIZAR DESCUENTOS DE ARTICULOS ********************/

	case 'actualizar-descuentos-articulos':

	$cliente = $_REQUEST["idcliente"];
	$ids = array();

	foreach ($_REQUEST["actualizar"] as $key => $value) {
		array_push($ids, $value["id"]);
	}

	$ids = implode(",", $ids);

	$sql = "DELETE FROM `articulos-terceros-descuentos`
	WHERE idTercero = '".$cliente."'
	AND idArticulo IN(".$ids.")";

	mysqli_query($mysqli,$sql);

	$ids = array();

	foreach ($_REQUEST["actualizar"] as $key => $value) {
		if ($value["descuento"] > 0) {
			$sql = "INSERT INTO `articulos-terceros-descuentos`(
				idTercero,
				idArticulo,
				descuento
			)VALUES(
				'".$cliente."',
				'".$value["id"]."',
				'".$value["descuento"]."'
			)";

			mysqli_query($mysqli,$sql);

		}
	}

	break;

/********************* ASIGNAR PRECIO COMPRA ********************/

	case 'asignar-precio-compra':

	$sql = "UPDATE articulos SET
	articulos.idPrecioCompra = '".$_REQUEST["idpreciocompra"]."'
	WHERE articulos.id = '".$_REQUEST["idarticulo"]."'";
	
	mysqli_query($mysqli,$sql);

	$ids = array();
	array_push($ids, $_REQUEST["idarticulo"]);

	actualizarPrecioPesos($_REQUEST["idpreciocompra"]);
	actualizarPrecio($ids);

	break;

/********************* OBTENER LOS COMPROBANTES QUE ESTÁN MARCADOS COMO "muestra pendiente" ********************/

	case 'comprobantes-muestra-pendientes':

		$respuesta = array();
		$error = array();

		$sql = "SELECT
		id,
		denominacion
		FROM comprobantes
		WHERE muestraPendientes = 1";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER MOVIMIENTOS SEGÚN CENTRAL PARA GENERACIÓN MASIVA ********************/

	case 'obtener-movimientos-central':

	$central = "";

	if ($_REQUEST["central"] != 0) {
		$central = " AND sucursales.idTerceroPadre = '".$_REQUEST["central"]."' ";
	}

	$tercero = "";

	if ($_REQUEST["tercero"] != 0) {
		$tercero = " AND idTercero = '".$_REQUEST["tercero"]."' ";
	}

	$comprobante = "";

	if ($_REQUEST["comprobante"] != 0) {
		$comprobante = " AND comprobantes.id = '".$_REQUEST["comprobante"]."' ";
	}

	$respuesta = array();
	$error = array();
	$respuesta["exito"] = array();

	$sql = "SELECT
	movimientos.id,
	movimientos.idTercero AS tercero,
	SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantPendiente,
	ROUND((SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada))) + (SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada)) * `alicuotas-iva`.valor / 100),2) AS importePendiente,
	terceros.denominacion AS denominaciontercero,
	DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS fecha,
	comprobantes.denominacion AS comprobante,
	CONCAT(movimientos.ptoVenta,'-',movimientos.nroComprobante) AS numeroComprobante
	FROM movimientos
	INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN `alicuotas-iva` ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
	INNER JOIN comprobantes
	ON movimientos.idComprobante = comprobantes.id
	LEFT JOIN sucursales
	ON movimientos.idTercero = sucursales.idTerceroSucursal	
	INNER JOIN terceros
	ON movimientos.idTercero = terceros.id
	WHERE comprobantes.muestraPendientes = 1
	AND coalesce(`movimientos-detalle`.cantidadImportada,0)  < `movimientos-detalle`.cant
	AND movimientos.idEstado=2
	AND movimientos.fecha BETWEEN CAST('".$_REQUEST["fechadesde"]."' AS DATE) AND CAST('".$_REQUEST["fechahasta"]."' AS DATE)
	".$central." ".$tercero." ".$comprobante."
	GROUP BY movimientos.id
	ORDER BY tercero, fecha";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}
	}

	break;	

/********************* OBTENER LOS ARTICULOS PENDIENTES DE IMPORTACIÓN PARA REPORTE ********************/

	case 'movimientos-pendientes':

	$tercero = "";

	if ($_REQUEST["tercero"] != 0) {
		$tercero = " AND idTercero = '".$_REQUEST["tercero"]."' ";
	}

	$comprobante = "";

	if ($_REQUEST["comprobante"] != 0) {
		$comprobante = " AND comprobantes.id = '".$_REQUEST["comprobante"]."' ";
	}

	$respuesta = array();
	$error = array();
	$respuesta["exito"] = array();

	$sql = "SELECT
	movimientos.id,
	movimientos.idTercero AS tercero,
	SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantPendiente,
	ROUND((SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada))) + (SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada)) * `alicuotas-iva`.valor / 100),2) AS importePendiente,
	terceros.denominacion AS denominaciontercero,
	DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS fecha,
	comprobantes.denominacion AS comprobante,
	CONCAT(movimientos.ptoVenta,'-',movimientos.nroComprobante) AS numeroComprobante
	FROM movimientos
	INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN `alicuotas-iva` ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
	INNER JOIN comprobantes
	ON movimientos.idComprobante = comprobantes.id
	INNER JOIN terceros
	ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = '".$_REQUEST["tipomovimiento"]."'
	AND comprobantes.muestraPendientes = 1
	AND `movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant
	AND movimientos.idEstado=2
	AND movimientos.fecha BETWEEN CAST('".$_REQUEST["fechadesde"]."' AS DATE) AND CAST('".$_REQUEST["fechahasta"]."' AS DATE)
	".$tercero." 	".$comprobante."
	GROUP BY movimientos.id
	ORDER BY tercero, fecha";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}
	}

	break;

/********************* OBTENER LOS ARTICULOS PENDIENTES DE IMPORTACIÓN PARA REPORTE ********************/

case 'articulos-pendientes':

	$tercero = "";

	if ($_REQUEST["tercero"] != 0) {
		$tercero = " AND idTercero = '".$_REQUEST["tercero"]."' ";
	}

	$comprobante = "";

	if ($_REQUEST["comprobante"] != 0) {
		$comprobante = " AND comprobantes.id = '".$_REQUEST["comprobante"]."' ";
	}

	$respuesta = array();
	$error = array();
	$respuesta["exito"] = array();

	$sql = "SELECT
	movimientos.id,
	movimientos.idTercero AS tercero,
	SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantPendiente,
	ROUND((SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada))) + (SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada)) * `alicuotas-iva`.valor / 100),2) AS importePendiente,
	terceros.denominacion AS denominaciontercero,
	DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS fecha,
	comprobantes.denominacion AS comprobante,
	CONCAT(movimientos.ptoVenta,'-',movimientos.nroComprobante) AS numeroComprobante,
	articulos.denominacionExterna AS denominacionArticulo
	FROM movimientos
	INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN `alicuotas-iva` ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
	INNER JOIN comprobantes
	ON movimientos.idComprobante = comprobantes.id
	INNER JOIN terceros
	ON movimientos.idTercero = terceros.id
	INNER JOIN articulos
	ON `movimientos-detalle`.codProducto = articulos.id
	WHERE movimientos.tipoMovimiento = '".$_REQUEST["tipomovimiento"]."'
	AND comprobantes.muestraPendientes = 1
	AND `movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant
	AND movimientos.idEstado=2
	AND movimientos.fecha BETWEEN CAST('".$_REQUEST["fechadesde"]."' AS DATE) AND CAST('".$_REQUEST["fechahasta"]."' AS DATE)
	".$tercero." 	".$comprobante."
	GROUP BY articulos.id, movimientos.id
	ORDER BY tercero, fecha";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {
		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}
	}

	break;	

/********************* OBTENER LOS ARTICULOS QUE NO ESTÁN IMPORTADOS DE LOS MOVIMIENTOS ********************/

	case 'articulos-pendientes-movimientos':

	$respuesta = array();
	$error = array();

	$sql="SELECT
	SUM(`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada) AS cantPendiente,
	ROUND((SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada))) + (SUM(`movimientos-detalle`.importeUnitario * (`movimientos-detalle`.cant - `movimientos-detalle`.cantidadImportada)) * `alicuotas-iva`.valor / 100),2) AS importePendiente,
	comprobantes.denominacionCorta,
	movimientos.importeTotal,
	movimientos.fecha,
	movimientos.ptoVenta,
	movimientos.nroComprobante,	
	Count(`movimientos-detalle`.id) AS cantdetalles,
	movimientos.id AS idmovimiento
	FROM `movimientos-detalle`
	INNER JOIN `alicuotas-iva`
	ON `movimientos-detalle`.alicuotaIva = `alicuotas-iva`.id
	INNER JOIN movimientos
	ON `movimientos-detalle`.idMovimientos = movimientos.id
	AND movimientos.idTercero = '".$_REQUEST["idtercero"]."'
	AND movimientos.contable = '".$_REQUEST["contable"]."'
	AND movimientos.idEstado=2
	INNER JOIN comprobantes
	ON movimientos.idComprobante = comprobantes.id
	AND comprobantes.muestraPendientes = 1
	WHERE `movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant
	GROUP BY comprobantes.id, movimientos.id";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {

		$respuesta["error"] = FALSE;
		$respuesta["exito"] = array();

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);
		}

	}else{
		$respuesta["error"] = TRUE;
	}

	break;

/********************* OBTENER IMPORTE ARTÍCULO COTIZACIÓN ********************/

	case 'importe-articulo-cotizacion':

	$respuesta = array();
	$error = array();

	$sql = "SELECT
	ROUND(articulos.precioVenta,2) AS precioventa,
	ROUND(`articulos-proveedores`.precioPesos,2) AS costo,
	articulos.idUnidadMedidaVenta,
	articulos.idUnidadMedidaCompra,
	`articulosstockventa`.indiceConversion AS indiceVenta,
	`articulosstockcompra`.indiceConversion AS indiceCompra,
	articulos.rentabilidad,
	COALESCE(`categorias-terceros-descuentos`.descuento,0) AS dtocategoria,
	COALESCE(`subcategoria-terceros-descuentos`.descuento,0) AS dtosubcategoria,
	COALESCE(`articulos-terceros-descuentos`.descuento,0) AS dtoarticulo
	FROM articulos
	INNER JOIN `articulos-proveedores`
	ON articulos.idPrecioCompra = `articulos-proveedores`.id
	LEFT JOIN `categorias-terceros-descuentos`
	ON articulos.idCategoria = `categorias-terceros-descuentos`.idCategoria
	AND `categorias-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
	LEFT JOIN `subcategoria-terceros-descuentos`
	ON articulos.idSubcateogoria = `subcategoria-terceros-descuentos`.idSubcategoria
	AND `subcategoria-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
	LEFT JOIN `articulos-terceros-descuentos`
	ON articulos.id = `articulos-terceros-descuentos`.idArticulo
	AND `articulos-terceros-descuentos`.idTercero = '".$_REQUEST["idtercero"]."'
	LEFT JOIN `articulos-stock` AS articulosstockcompra
	ON articulos.idUnidadMedidaCompra = articulosstockcompra.idUnidadMedida
	AND articulosstockcompra.idArticulo = '".$_REQUEST["idarticulo"]."'
	LEFT JOIN `articulos-stock` AS articulosstockventa
	ON articulos.idUnidadMedidaVenta = articulosstockventa.idUnidadMedida
	AND articulosstockventa.idArticulo = '".$_REQUEST["idarticulo"]."'	
	WHERE articulos.id = '".$_REQUEST["idarticulo"]."'";

	$resultado=mysqli_query($mysqli,$sql);
	if ($resultado->num_rows>0) {

		$respuesta["error"] = FALSE;
		$respuesta["exito"] = array();
		
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);

		//VENTA Si la unidad de medida especificada es distinta, calculo el precio mediante el índice de conversión
		if ($_REQUEST["unidadmedida"] != $registro["idUnidadMedidaVenta"]) {

			$sql="SELECT * FROM `articulos-stock`
			WHERE idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
			AND idArticulo='".$_REQUEST["idarticulo"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			//Si no tiene especificada esa unidad de medida retorno valor 0, sino la calculo
			if ($resultado->num_rows==1) {
				$unmed=mysqli_fetch_assoc($resultado);
				$registro["precioventa"] = $registro["precioventa"] * $registro["indiceVenta"] / $unmed["indiceConversion"];
			}else{
				$registro["precioventa"]=0;
			}
		}
		
		//COSTO Si la unidad de medida especificada es distinta, calculo el precio mediante el índice de conversión
		if ($_REQUEST["unidadmedida"] != $registro["idUnidadMedidaCompra"]) {

			$sql="SELECT * FROM `articulos-stock`
			WHERE idUnidadMedida = '".$_REQUEST["unidadmedida"]."'
			AND idArticulo='".$_REQUEST["idarticulo"]."'";

			$resultado=mysqli_query($mysqli,$sql);

			//Si no tiene especificada esa unidad de medida retorno valor 0, sino la calculo
			if ($resultado->num_rows==1) {
				$unmed=mysqli_fetch_assoc($resultado);
				$registro["costo"] = $registro["costo"] * $registro["indiceCompra"] / $unmed["indiceConversion"];
			}else{
				$registro["costo"]=0;
			}
		}		

		$respuesta["exito"] = $registro;

	}else{
		$respuesta["error"] = TRUE;
	}

	break;

/********************* OBTENER IMPORTE ARTÍCULO COTIZACIÓN ********************/

	case 'obtener-cotizacion':

		$respuesta = array();
		$error = array();

		$sql = "SELECT
		cotizaciones_detalles.idArticulo,
		cotizaciones_detalles.cantidad,
		cotizaciones_detalles.referencia,
		cotizaciones_detalles.precioReferencia,
		cotizaciones_detalles.idAlicuota,
		cotizaciones_detalles.cantidadAprobada,
		cotizaciones_detalles.margenCotizado,
		cotizaciones_detalles.precioCotizado,
		cotizaciones_detalles.dtoCliente,
		cotizaciones.idTercero,
		DATE(cotizaciones.fecha) AS fecha,
		cotizaciones.vigencia,
		cotizaciones.contable,
		cotizaciones.importeNeto,
		cotizaciones.importeIva,
		cotizaciones.importeTotal,
		cotizaciones.discriminaIVA,
		CONCAT(COALESCE(articulos.denominacionExterna,''),' ',COALESCE(articulos.denominacionInterna,'')) AS denominacionarticulo
		FROM cotizaciones_detalles
		INNER JOIN cotizaciones
		ON cotizaciones_detalles.idCotizacion = cotizaciones.id
		INNER JOIN articulos
		ON articulos.id = cotizaciones_detalles.idArticulo
		WHERE cotizaciones.id = '".$_REQUEST["id"]."'";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;


/********************* OBTENER LAS CATEGORIAS DE LOS ARTICULOS DE DETERMINADOS PROVEEDORES ********************/

	case 'obtener-categorias-proveedores':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["proveedores"] == NULL) {

			$whereproveedores = "";

		}else{

			$proveedores = implode(",", $_REQUEST["proveedores"]);

			$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

		}

		$sql = "SELECT
		DISTINCT(articulos.idCategoria) AS id,
		`categorias-articulos`.denominacion
		FROM articulos
		INNER JOIN `categorias-articulos`
		ON articulos.idCategoria = `categorias-articulos`.id
		INNER JOIN `articulos-proveedores`
		ON `articulos-proveedores`.idArticulo = articulos.id
		WHERE 1=1 ".$whereproveedores." ORDER BY `categorias-articulos`.denominacion";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER LAS SUBCATEGORIAS DE LOS ARTICULOS DE DETERMINADAS CATEGORIAS ********************/

	case 'obtener-subcategorias-categorias':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["proveedores"] == NULL) {

			$whereproveedores = "";

		}else{

			$proveedores = implode(",", $_REQUEST["proveedores"]);

			$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

		}

		if ($_REQUEST["categorias"] == NULL) {

			$wherecategorias = "";

		}else{

			$categorias = implode(",", $_REQUEST["categorias"]);

			$wherecategorias = " AND articulos.idCategoria IN (".$categorias.")";

		}

		$sql = "SELECT
		DISTINCT(articulos.idSubcateogoria) AS id,
		`subcategorias-articulos`.denominacion
		FROM articulos
		INNER JOIN `subcategorias-articulos`
		ON articulos.idSubcateogoria = `subcategorias-articulos`.id
		INNER JOIN `articulos-proveedores`
		ON `articulos-proveedores`.idArticulo = articulos.id
		WHERE 1=1 ".$whereproveedores.$wherecategorias." ORDER BY `subcategorias-articulos`.denominacion";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER LOS RUBROS DE LOS ARTICULOS DE DETERMINADAS SUBCATEGORIAS ********************/

	case 'obtener-rubros-subcategorias':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["proveedores"] == NULL) {

			$whereproveedores = "";

		}else{

			$proveedores = implode(",", $_REQUEST["proveedores"]);

			$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

		}

		if ($_REQUEST["categorias"] == NULL) {

			$wherecategorias = "";

		}else{

			$categorias = implode(",", $_REQUEST["categorias"]);

			$wherecategorias = " AND articulos.idCategoria IN (".$categorias.")";

		}

		if ($_REQUEST["subcategorias"] == NULL) {

			$wheresubcategorias = "";

		}else{

			$subcategorias = implode(",", $_REQUEST["subcategorias"]);

			$wheresubcategorias = " AND articulos.idSubcateogoria IN (".$subcategorias.")";

		}

		$sql = "SELECT
		DISTINCT(articulos.idRubro) AS id,
		rubros.denominacion
		FROM articulos
		INNER JOIN rubros
		ON articulos.idRubro = rubros.id
		INNER JOIN `articulos-proveedores`
		ON `articulos-proveedores`.idArticulo = articulos.id
		WHERE 1=1 ".$whereproveedores.$wherecategorias.$wheresubcategorias." ORDER BY rubros.denominacion";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER LAS MARCAS DE LOS ARTICULOS DE DETERMINADOS RUBROS ********************/

	case 'obtener-marcas-rubros':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["proveedores"] == NULL) {

			$whereproveedores = "";

		}else{

			$proveedores = implode(",", $_REQUEST["proveedores"]);

			$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

		}

		if ($_REQUEST["categorias"] == NULL) {

			$wherecategorias = "";

		}else{

			$categorias = implode(",", $_REQUEST["categorias"]);

			$wherecategorias = " AND articulos.idCategoria IN (".$categorias.")";

		}

		if ($_REQUEST["subcategorias"] == NULL) {

			$wheresubcategorias = "";

		}else{

			$subcategorias = implode(",", $_REQUEST["subcategorias"]);

			$wheresubcategorias = " AND articulos.idSubcateogoria IN (".$subcategorias.")";

		}

		if ($_REQUEST["rubros"] == NULL) {

			$whererubros = "";

		}else{

			$rubros = implode(",", $_REQUEST["rubros"]);

			$whererubros = " AND articulos.idRubro IN (".$rubros.")";

		}

		$sql = "SELECT
		DISTINCT(articulos.idMarca) AS id,
		marcas.denominacion
		FROM articulos
		INNER JOIN marcas
		ON articulos.idMarca = marcas.id
		INNER JOIN `articulos-proveedores`
		ON `articulos-proveedores`.idArticulo = articulos.id
		WHERE 1=1 ".$whereproveedores.$wherecategorias.$wheresubcategorias.$whererubros." ORDER BY marcas.denominacion";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER LOS ARTICULOS PARA ACTUALIZACION MASIVA ********************/

	case 'obtener-articulos-actualizacion':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["proveedores"] == NULL) {

			$whereproveedores = "";

		}else{

			$proveedores = implode(",", $_REQUEST["proveedores"]);

			$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

		}

		if ($_REQUEST["categorias"] == NULL) {

			$wherecategorias = "";

		}else{

			$categorias = implode(",", $_REQUEST["categorias"]);

			$wherecategorias = " AND articulos.idCategoria IN (".$categorias.")";

		}

		if ($_REQUEST["subcategorias"] == NULL) {

			$wheresubcategorias = "";

		}else{

			$subcategorias = implode(",", $_REQUEST["subcategorias"]);

			$wheresubcategorias = " AND articulos.idSubcateogoria IN (".$subcategorias.")";

		}

		if ($_REQUEST["rubros"] == NULL) {

			$whererubros = "";

		}else{

			$rubros = implode(",", $_REQUEST["rubros"]);

			$whererubros = " AND articulos.idRubro IN (".$rubros.")";

		}

		if ($_REQUEST["marcas"] == NULL) {

			$wheremarcas = "";

		}else{

			$marcas = implode(",", $_REQUEST["marcas"]);

			$wheremarcas = " AND articulos.idMarca IN (".$marcas.")";

		}

		$sql = "SELECT
		articulos.id,
		articulos.denominacionInterna AS denominacioninterna,
		articulos.denominacionExterna AS denominacionexterna,
		terceros.denominacion AS proveedor,
		terceros.dto1 AS dtoproveedor1,
		terceros.dto2 AS dtoproveedor2,
		terceros.dto3 AS dtoproveedor3,
		`categorias-articulos`.denominacion AS categoria,
		`subcategorias-articulos`.denominacion AS subcategoria,
		rubros.denominacion AS rubro,
		marcas.denominacion AS marca,
		monedas.simbolo,
		monedas.cotizacion,
		`articulos-proveedores`.id AS idarticuloproveedor,
		`articulos-proveedores`.precio,
		`articulos-proveedores`.precioPesos,
		`articulos-proveedores`.dto1 AS dtoarticulo1,
		`articulos-proveedores`.dto2 AS dtoarticulo2,
		`articulos-proveedores`.dto3 AS dtoarticulo3,
		articulos.calculoPrecio AS calculoprecio,
		articulos.rentabilidad,
		articulos.precioVenta,
		IF(articulos.idPrecioCompra = `articulos-proveedores`.id, 'SI', `articulos-proveedores`.id) AS idpreciocompra,
		IFNULL(articulosstockcompra.indiceConversion, 1) AS indicecompra,
		IFNULL(articulosstockventa.indiceConversion, 1) AS indiceventa,
		unidadescompra.denominacionCorta AS unidadcompra,
		unidadesventa.denominacionCorta AS unidadventa
		FROM articulos
		LEFT JOIN `articulos-stock` AS articulosstockcompra
		ON articulosstockcompra.idArticulo = articulos.id
		AND articulosstockcompra.idUnidadMedida = articulos.idUnidadMedidaCompra
		LEFT JOIN `articulos-stock` AS articulosstockventa
		ON articulosstockventa.idArticulo = articulos.id
		AND articulosstockventa.idUnidadMedida = articulos.idUnidadMedidaVenta
		INNER JOIN `unidades-medida` AS unidadescompra
		ON unidadescompra.id = articulos.idUnidadMedidaCompra
		INNER JOIN `unidades-medida` AS unidadesventa
		ON unidadesventa.id = articulos.idUnidadMedidaVenta
		INNER JOIN `articulos-proveedores`
		ON `articulos-proveedores`.idArticulo = articulos.id
		INNER JOIN monedas
		ON `articulos-proveedores`.idMoneda = monedas.id
		INNER JOIN terceros
		ON `articulos-proveedores`.idTercero = terceros.id
		INNER JOIN `categorias-articulos`
		ON `categorias-articulos`.id = articulos.idCategoria
		INNER JOIN `subcategorias-articulos`
		ON `subcategorias-articulos`.id = articulos.idSubcateogoria
		INNER JOIN rubros
		ON rubros.id = articulos.idRubro
		INNER JOIN marcas
		ON marcas.id = articulos.idMarca
		WHERE 1=1 ".$whereproveedores.$wherecategorias.$wheresubcategorias.$whererubros.$wheremarcas." ORDER BY proveedor, categoria, subcategoria, rubro, marca, denominacioninterna, denominacionexterna";

		$resultado=mysqli_query($mysqli,$sql);
		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;


/********************* ACTUALIZACION MASIVA DE COSTOS ********************/

	case 'actualizacion-masiva-costos':

		$respuesta = array();
		$error = array();

		$set = array();

		if ($_REQUEST["dtoarticulo1"] != "") {
			array_push($set, "dto1 = ".$_REQUEST["dtoarticulo1"]);
		}

		if ($_REQUEST["dtoarticulo2"] != "") {
			array_push($set, "dto2 = ".$_REQUEST["dtoarticulo2"]);
		}

		if ($_REQUEST["dtoarticulo3"] != "") {
			array_push($set, "dto3 = ".$_REQUEST["dtoarticulo3"]);
		}

		if (count($set) > 0) {

			$sqlset = implode(", ", $set);

			$sqlset = " , ".$sqlset;

		}else{
			$sqlset = " ";
		}

		foreach ($_REQUEST["costos"] as $key => $value) {
			$sql = "UPDATE `articulos-proveedores`
			SET precio = '".$value["1"]."'".$sqlset."
			WHERE id = '".$value["0"]."'";

			mysqli_query($mysqli,$sql);

			actualizarPrecioPesos($value["0"]);

		}

	break;

/********************* ACTUALIZACION MASIVA DE PRECIOS ********************/

	case 'actualizacion-masiva-precios-venta':

		$respuesta = array();
		$error = array();

		$ids = array();

		foreach ($_REQUEST["precios"] as $key => $value) {

			$sql = "UPDATE `articulos`
			SET	rentabilidad = '".$value["1"]."'
			WHERE id = '".$value["0"]."'";

			mysqli_query($mysqli,$sql);

			array_push($ids, $value[0]);

		}

		actualizarPrecio($ids);

	break;

/********************* MODIFICAR DESCUENTOS PROVEEDORES ********************/

	case 'modificar-descuentos-proveedores':

		$respuesta = array();
		$error = array();

		if ($_REQUEST["dtoproveedor1"] != "" || $_REQUEST["dtoproveedor2"] != "" || $_REQUEST["dtoproveedor3"] != "" ) {

			$set = array();

			if ($_REQUEST["dtoproveedor1"] != "") {
				array_push($set, "dto1 = ".$_REQUEST["dtoproveedor1"]);
			}

			if ($_REQUEST["dtoproveedor2"] != "") {
				array_push($set, "dto2 = ".$_REQUEST["dtoproveedor2"]);
			}

			if ($_REQUEST["dtoproveedor3"] != "") {
				array_push($set, "dto3 = ".$_REQUEST["dtoproveedor3"]);
			}

			$sqlset = implode(", ", $set);

			$sqlset = " SET ".$sqlset;

			foreach ($_REQUEST["proveedores"] as $key => $value) {
				$sql = "UPDATE `terceros`". $sqlset ."
				WHERE id = '".$value."'";

				mysqli_query($mysqli,$sql);

			}
		}

	break;
/********************* OBTENER ULTIMA COTIZACION ********************/

	case 'obtener-ultima-cotizacion':

		$respuesta = array();
		$error = array();

		$sql = "SELECT
		cotizaciones_detalles.cantidad,
		cotizaciones_detalles.cantidadAprobada
		FROM cotizaciones_detalles
		INNER JOIN cotizaciones
		ON cotizaciones_detalles.idCotizacion = cotizaciones.id
		WHERE cotizaciones.idTercero = '".$_REQUEST["idtercero"]."'
		AND cotizaciones.estado = 1
		AND cotizaciones_detalles.idArticulo = '".$_REQUEST["idarticulo"]."'
		ORDER BY cotizaciones_detalles.id DESC
		LIMIT 1";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows > 0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			$registro=mysqli_fetch_assoc($resultado);

			if ($registro["cantidadAprobada"] >= $registro["cantidad"]) {
				$respuesta["exito"] = 2;
			}else if ($registro["cantidadAprobada"] > 0){
				$respuesta["exito"] = 1;
			}else{
				$respuesta["exito"] = 0;
			}

		}else{

			$respuesta["error"] = TRUE;

		}

	break;

/********************* OBTENER HISTORIAL COTIZACIONES ********************/

	case 'obtener-historial-cotizaciones':

		$respuesta = array();
		$error = array();

		$sql = "SELECT
		DATE_FORMAT(DATE(cotizaciones.fecha),'%d-%m-%y') AS fecha,
		cotizaciones_detalles.cantidad,
		cotizaciones_detalles.cantidadAprobada,
		cotizaciones_detalles.precioReferencia,
		cotizaciones_detalles.margenCotizado,
		cotizaciones_detalles.precioCotizado
		FROM cotizaciones_detalles
		INNER JOIN cotizaciones
		ON cotizaciones_detalles.idCotizacion = cotizaciones.id
		WHERE cotizaciones.idTercero = '".$_REQUEST["idtercero"]."'
		AND cotizaciones.estado = 1
		AND cotizaciones_detalles.idArticulo = '".$_REQUEST["idarticulo"]."'
		ORDER BY cotizaciones_detalles.id DESC";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows>0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				if ($registro["cantidadAprobada"] >= $registro["cantidad"]) {
					$registro["aprobacion"] = 2;
				}else if ($registro["cantidadAprobada"] > 0){
					$registro["aprobacion"] = 1;
				}else{
					$registro["aprobacion"] = 0;
				}
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* OBTENER COMPROBANTE IMPORTACIÓN DEFAULT ********************/

	case 'obtener-comprobante-importacion-default':

		$respuesta = array();
		$error = array();

		$sql = "SELECT comprobanteDefaultImportacion
		FROM comprobantes
		WHERE id = '".$_REQUEST["idcomprobante"]."'";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows > 0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);
			array_push($respuesta["exito"], $registro);

		}else{
			$respuesta["error"] = TRUE;
		}

	break;


/********************* OBTENER COMPROBANTES SEGÚN CENTRAL ********************/

	case 'obtener-comprobantes-central':

		$respuesta = array();
		$error = array();

		$sql = "SELECT
		movimientos.id AS idmovimiento,
		DATE_FORMAT(movimientos.fecha, '%d/%m/%Y') AS fecha,
		movimientos.importeTotal,
		movimientos.importeIva,
		movimientos.importeNeto,
		movimientos.ptoVenta,
		movimientos.nroComprobante,
		comprobantes.denominacionCorta AS comprobante,
		terceros.denominacion AS tercero
		FROM movimientos
		INNER JOIN terceros
		ON movimientos.idTercero = terceros.id
		INNER JOIN sucursales
		ON sucursales.idTerceroSucursal = terceros.id
		INNER JOIN terceros AS centrales
		ON sucursales.idTerceroPadre = centrales.id
		INNER JOIN comprobantes
		ON comprobantes.id = movimientos.idComprobante
		WHERE
		(
			movimientos.idEstado = 1 OR
			movimientos.idEstado = 2
		) AND 
		centrales.id = '".$_REQUEST["idcentral"]."' AND 
		movimientos.idComprobante = '".$_REQUEST["idcomprobante"]."' AND 
		(
			movimientos.fecha >= '".$_REQUEST["fechadesde"]."' AND
			movimientos.fecha <= '".$_REQUEST["fechahasta"]."'
		)
		ORDER BY sucursales.orden";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows > 0) {

			$respuesta["error"] = FALSE;
			$respuesta["exito"] = array();

			for ($i=0; $i < $resultado->num_rows; $i++) {
				$registro=mysqli_fetch_assoc($resultado);
				$registro = f_array_map('utf8_encode', $registro);
				array_push($respuesta["exito"], $registro);
			}

		}else{
			$respuesta["error"] = TRUE;
		}

	break;

/********************* GENERACION MASIVA MOVIMIENTOS ********************/

	case 'generacion-masiva-movimientos':

	$puntoVenta = obtenerpuntoventa($_REQUEST["comprobante"]);

	foreach ($_REQUEST["ids"] as $key => $value) {
		
		$numeroComprobante = obtenerNumeroComprobante($_REQUEST["comprobante"], $puntoVenta, $_REQUEST["contable"]);

		$sql = "INSERT INTO movimientos (
			fecha,
			ptoVenta,
			nroComprobante,
			vigencia,
			idTercero,
			idComprobante,
			importeTotal,
			importeIva,
			importeNeto,
			nombreTercero,
			idDocTercero,
			nroDocTercero,
			condicionVenta,
			cae,
			vtoCae,
			idEstado,
			contable,
			tipoMovimiento,
			importeCancelado
		) 
		SELECT 
			'".$_REQUEST["fecha"]."',
			'".$puntoVenta."',
			'".$numeroComprobante."',
			vigencia,
			idTercero,
			'".$_REQUEST["comprobante"]."',
			importeTotal,
			importeIva,
			importeNeto,
			nombreTercero,
			idDocTercero,
			nroDocTercero,
			condicionVenta,
			0,
			NOW(),
			1,
			'".$_REQUEST["contable"]."',
			1,
			0
		FROM movimientos WHERE id = '".$value."'";	

		$resultado = mysqli_query($mysqli,$sql);

		$ultimoid = mysqli_insert_id($mysqli);

		aumentarNumeroComprobante(
			$puntoVenta,
			$_REQUEST["comprobante"],
		 	$_REQUEST["contable"]
		);

		$sql = "INSERT INTO `movimientos-detalle` (
			idMovimientos,
			cant,
			idUnidadMedida,
			codProducto,
			medida,
			nombreProducto,
			importeUnitario,
			bonificacion,
			importeTotal,
			alicuotaIva,
			importeIva,
			importeNeto,
			importePesos,
			estadoImportacion,
			cantidadImportada,
			origenImportacion,
			cantidadImporto
		) 
		SELECT 
			'".$ultimoid."',
			cant - COALESCE(cantidadImporto,0),
			idUnidadMedida,
			codProducto,
			medida,
			nombreProducto,
			importeUnitario,
			bonificacion,
			(cant - COALESCE(cantidadImporto,0)) * importeUnitario,
			alicuotaIva,
			importeIva,
			(importeNeto / cant) * (cant - COALESCE(cantidadImporto,0)),
			importePesos,
			NULL,
			0,
			id,
			0
		FROM `movimientos-detalle`
		WHERE idMovimientos = '".$value."'
		AND (cant - COALESCE(cantidadImporto,0)) > 0";

		$resultado = mysqli_query($mysqli,$sql);

		$sql = "UPDATE `movimientos-detalle`
		SET estadoImportacion = 2,
		cantidadImportada = cant
		WHERE idMovimientos = '".$value."'";

		$resultado = mysqli_query($mysqli,$sql);
		
	}

	$respuesta["exito"] = true;
	

	break;
	
	
/********************* PANEL DE CONTROL ********************/
/*
	Consulta que obtenga los movimientos que correspondan a 
	ventas (tipoMovimiento = 1)
	movimientos auditados (idEstado = 2)
	comprobantes solo remitos y devoluciones (configurar en un array de ids)
	los remitos suman y los devoluciones restan
	la consulta debe agrupar por cliente
	la consulta debe recibir fecha desde y hasta
*/
/*
	Los datos necesarios son:
	idTercero
	nombreTercero
	importe total (suma de todos los importes de los remitos menos los de devoluciones)
	mes
*/

case 'ventas-mensuales-importe':
	
	//Obtengo desde y hasta por get si no se envian los datos establecer por defecto
	//valores por defecto
	//$desde = "2000-01-01";
	//$hasta = date("Y-m-d");

	$desde = $_REQUEST["fechaDesde"] ? $_REQUEST["fechaDesde"] : "2019-11-01";
	$hasta = $_REQUEST["fechaHasta"] ? $_REQUEST["fechaHasta"] : date("Y-m-d");

	//Si obtengo articulo por get los agrego a la consulta

	$articulo = $_REQUEST["articulo"] ? $_REQUEST["articulo"] : "";

	//Si obtengo cliente por get los agrego a la consulta

	$cliente = $_REQUEST["cliente"] ? $_REQUEST["cliente"] : "";

	//obtener los movimientos agrupados por mes y año
	//Obtengo la suma de cantidad en movimientos detalle

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal,
	SUM(`movimientos-detalle`.cant) AS cantidad,
	DATE_FORMAT(`fecha`,'%Y-%m') AS fecha,
	movimientos.idComprobante AS idComprobante
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	WHERE movimientos.tipoMovimiento = 1
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}
	if($cliente != ""){
		$sql .= " AND movimientos.idTercero = '".$cliente."'";
	}
	$sql .= "
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesTodos).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	GROUP BY DATE_FORMAT(`fecha`,'%Y-%m'), movimientos.idComprobante
	ORDER BY fecha";

	//Armo un array con los datos formateados para graficar agrupados por mes
	//Si el comprobante es remito, suma al total, pero si es devolucion, resta

	$resultado = mysqli_query($mysqli,$sql);

	$datos = array();

	//Get all posibles months between desde y hasta

	$meses = array();

	$desde = new DateTime($desde);
	$hasta = new DateTime($hasta);
	
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($desde, $interval, $hasta);

	foreach ($period as $dt) {
		$datos[$dt->format("Y-m")] = array(
			"importeTotal" => 0,
			"cantidad" => 0
		);
	}
	
	while($fila = mysqli_fetch_array($resultado)){

		//Agrupo por mes y año
			
		if(in_array($fila["idComprobante"],$idsComprobantesSuman)){
			$datos[$fila["fecha"]]["importeTotal"] += $fila["importeTotal"];
			$datos[$fila["fecha"]]["cantidad"] += $fila["cantidad"];
		}else if(in_array($fila["idComprobante"],$idsComprobantesResta)){
			$datos[$fila["fecha"]]["importeTotal"] -= $fila["importeTotal"];
			$datos[$fila["fecha"]]["cantidad"] -= $fila["cantidad"];
		}

	}

	$respuesta["exito"] = true;
	$respuesta["datos"] = $datos;

break;

case 'ventas-clientes':

	//Obtengo desde y hasta por get si no se envian los datos establecer por defecto
	//valores por defecto
	//$desde = "2000-01-01";
	//$hasta = date("Y-m-d");

	$desde = $_REQUEST["fechaDesde"] ? $_REQUEST["fechaDesde"] : "2019-11-01";
	$hasta = $_REQUEST["fechaHasta"] ? $_REQUEST["fechaHasta"] : date("Y-m-d");

	//Si obtengo articulo por get los agrego a la consulta

	$articulo = $_REQUEST["articulo"] ? $_REQUEST["articulo"] : "";

	//Si obtengo cliente por get los agrego a la consulta

	$cliente = $_REQUEST["cliente"] ? $_REQUEST["cliente"] : "";

	//Obtengo el top 10 de clientes con mayor importe de ventas Si el comprobante es remito, suma al total, pero si es devolucion, resta

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal,
	terceros.id AS idTercero,
	terceros.denominacion AS denominacion
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesSuman).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}
	if($cliente != ""){
		$sql .= " AND movimientos.idTercero = '".$cliente."'";
	}
	$sql .= "
	GROUP BY terceros.id
	ORDER BY importeTotal DESC
	LIMIT 10
	";

	$resultado = mysqli_query($mysqli,$sql);

	$datos = array();
	$idTerceros = array();

	//Guardo los datos obtenidos usando el id de usuario como clave
	while($fila = mysqli_fetch_array($resultado)){

		$idTerceros[] = $fila["idTercero"];

		$datos[$fila["idTercero"]] = array(
			"importeTotal" => $fila["importeTotal"],
			"denominacion" => $fila["denominacion"]
		);
	}

	//Obtengo las devoluciones de los clientes que tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal,
	terceros.id AS idTercero,
	terceros.denominacion AS denominacion
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesRestan).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	AND terceros.id IN (".implode(",",$idTerceros).")
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}
	$sql .= "	
	GROUP BY terceros.id
	ORDER BY importeTotal DESC
	";

	$resultado = mysqli_query($mysqli,$sql);

	//Recorro los datos obtenidos y descuento los que corresponden a devoluciones

	while($fila = mysqli_fetch_array($resultado)){
		$datos[$fila["idTercero"]]["importeTotal"] -= $fila["importeTotal"];
	}

	//Obtengo los datos de los clientes que no tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesSuman).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	AND terceros.id NOT IN (".implode(",",$idTerceros).")
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}


	$resultado = mysqli_query($mysqli,$sql);

	//Guardo el importe obtenido
	$fila = mysqli_fetch_array($resultado);

	$datos["otros"]["importeTotal"] = $fila["importeTotal"];
	$datos["otros"]["denominacion"] = "Otros";

	//Obtengo las devoluciones de los clientes que tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesRestan).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	AND terceros.id NOT IN (".implode(",",$idTerceros).")
	";

	$resultado = mysqli_query($mysqli,$sql);
	
	//Guardo el importe obtenido
	$fila = mysqli_fetch_array($resultado);

	$datos["otros"]["importeTotal"] -= $fila["importeTotal"];

	$respuesta["exito"] = true;
	$respuesta["datos"] = $datos;

break;

case 'ventas-articulos':

	//Obtengo desde y hasta por get si no se envian los datos establecer por defecto
	//valores por defecto
	//$desde = "2000-01-01";
	//$hasta = date("Y-m-d");

	$desde = $_REQUEST["fechaDesde"] ? $_REQUEST["fechaDesde"] : "2019-11-01";
	$hasta = $_REQUEST["fechaHasta"] ? $_REQUEST["fechaHasta"] : date("Y-m-d");

	//Si obtengo articulo por get los agrego a la consulta

	$articulo = $_REQUEST["articulo"] ? $_REQUEST["articulo"] : "";

	//Si obtengo cliente por get los agrego a la consulta

	$cliente = $_REQUEST["cliente"] ? $_REQUEST["cliente"] : "";



	//Obtengo el top 10 de articulos con mayor importe de ventas Si el comprobante es remito, suma al total, pero si es devolucion, resta

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal,
	`movimientos-detalle`.codProducto AS codProducto,
	articulos.denominacionExterna AS denominacion
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	INNER JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesSuman).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}
	if($cliente != ""){
		$sql .= " AND movimientos.idTercero = '".$cliente."'";
	}
	$sql .= "
	GROUP BY `movimientos-detalle`.codProducto
	ORDER BY importeTotal DESC
	LIMIT 10
	";

	$resultado = mysqli_query($mysqli,$sql);

	$datos = array();
	$idTerceros = array();

	//Guardo los datos obtenidos usando el id de articulo como clave
	while($fila = mysqli_fetch_array($resultado)){

		$idArticulos[] = $fila["codProducto"];

		$datos[$fila["codProducto"]] = array(
			"importeTotal" => $fila["importeTotal"],
			"denominacion" => $fila["denominacion"]
		);
	}

	//Obtengo las devoluciones de los clientes que tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal,
	`movimientos-detalle`.codProducto AS codProducto,
	articulos.denominacion AS denominacion
	terceros.id AS idTercero
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	INNER JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesRestan).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	";
	if($articulo != ""){
		$sql .= " AND `movimientos-detalle`.codProducto = '".$articulo."'";
	}
	if($cliente != ""){
		$sql .= " AND movimientos.idTercero = '".$cliente."'";
	}
	$sql .= "
	GROUP BY `movimientos-detalle`.codProducto
	ORDER BY importeTotal DESC
	LIMIT 10
	";

	$resultado = mysqli_query($mysqli,$sql);

	//Recorro los datos obtenidos y descuento los que corresponden a devoluciones

	while($fila = mysqli_fetch_array($resultado)){
		$datos[$fila["codProducto"]]["importeTotal"] -= $fila["importeTotal"];
	}

	//Obtengo los datos de los articulos que no tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesSuman).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	AND `movimientos-detalle`.codProducto NOT IN (".implode(",",$idArticulos).")
	";
	if($cliente != ""){
		$sql .= " AND movimientos.idTercero = '".$cliente."'";
	}
	$resultado = mysqli_query($mysqli,$sql);

	//Guardo el importe obtenido
	$fila = mysqli_fetch_array($resultado);

	$datos["otros"]["importeTotal"] = $fila["importeTotal"];
	$datos["otros"]["denominacion"] = "Otros";

	//Obtengo las devoluciones de los articulos que tengo guardados

	$sql = "
	SELECT
	SUM(`movimientos-detalle`.importeTotal) AS importeTotal
	FROM `movimientos-detalle`
	INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
	INNER JOIN terceros ON movimientos.idTercero = terceros.id
	WHERE movimientos.tipoMovimiento = 1
	AND movimientos.idEstado = 2
	AND movimientos.idComprobante IN (".implode(",",$idsComprobantesRestan).")
	AND fecha BETWEEN '".$desde."' AND '".$hasta."'
	AND `movimientos-detalle`.codProducto NOT IN (".implode(",",$idArticulos).")
	";

	$resultado = mysqli_query($mysqli,$sql);
	
	//Guardo el importe obtenido
	$fila = mysqli_fetch_array($resultado);

	$datos["otros"]["importeTotal"] -= $fila["importeTotal"];

	$respuesta["exito"] = true;
	$respuesta["datos"] = $datos;

break;

/********************* OBTENER COMPROBANTES SEGUN MODO ********************/

case 'obtener-comprobantes-modo':

	$respuesta = array();
	$error = array();

	$sql="SELECT * FROM `comprobantes`
	WHERE activo = 1
	AND (limitarModo = '".$_REQUEST["contable"]."' OR limitarModo IS NULL)
	ORDER BY denominacion";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows > 0) {

		$respuesta["error"] = FALSE;
		$respuesta["exito"] = array();

		while($fila = mysqli_fetch_array($resultado)){
			$registro = f_array_map('utf8_encode', $fila);
			$respuesta["exito"][] = array(
				"id" => $registro["id"],
				"denominacion" => $registro["denominacion"]
			);
		}

	}else{
		$respuesta["error"] = TRUE;
	}

break;

/********************* OBTENER DETALLES DE LA UNIDAD DE MEDIDA ********************/

case 'obtener-stock-unidad-medida':

	$respuesta = array();
	$error = array();

	$sql="SELECT * FROM `unidades-medida`
	WHERE id = '".$_REQUEST["id"]."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows === 1) {
		
		$respuesta["error"] = FALSE;
		$respuesta["exito"]["unidadMedida"] = f_array_map('utf8_encode', mysqli_fetch_assoc($resultado));

		$stock = 0;
		$stockMinimo = 0;
		$stockReservadoVenta = 0;
		$stockReservadoCompra = 0;

		$indiceConversion = $respuesta["exito"]["unidadMedida"]["indiceConversion"];

		$sql = "SELECT
		indiceConversion,
		stock,
		stockMinimo,
		stockReservadoVenta,
		stockReservadoCompra
		FROM `articulos-stock`
		WHERE idArticulo = '".$_REQUEST["idArticulo"]."'
		LIMIT 1";

		$resultado = mysqli_query($mysqli,$sql);

		if ($resultado->num_rows === 1) {
			$respuestaAuxiliar = f_array_map('utf8_encode', mysqli_fetch_assoc($resultado));
			//Redondeo a 2 decimales
			$stock = round($respuestaAuxiliar["stock"] / $respuestaAuxiliar["indiceConversion"] * $indiceConversion,2);
			$stockMinimo = round($respuestaAuxiliar["stockMinimo"] / $respuestaAuxiliar["indiceConversion"] * $indiceConversion,2);
			$stockReservadoVenta = round($respuestaAuxiliar["stockReservadoVenta"] / $respuestaAuxiliar["indiceConversion"] * $indiceConversion,2);
			$stockReservadoCompra = round($respuestaAuxiliar["stockReservadoCompra"] / $respuestaAuxiliar["indiceConversion"] * $indiceConversion,2);
		}

		$respuesta["exito"]["stock"] = array(
			"stock" => $stock,
			"stockMinimo" => $stockMinimo,
			"stockReservadoVenta" => $stockReservadoVenta,
			"stockReservadoCompra" => $stockReservadoCompra
		);

	}else{
		$respuesta["error"] = TRUE;
	}

break;

/********************* OBTENER REGISTRO ARTICULOS PROVEEDORES ********************/

case 'obtener-registro-articulos-proveedores':

	$respuesta = array();
	$error = array();

	$sql="SELECT * FROM `articulos-proveedores`
	WHERE id = '".$_REQUEST["id"]."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows === 1) {
		
		$respuesta["error"] = FALSE;
		$respuesta["exito"] = f_array_map('utf8_encode', mysqli_fetch_assoc($resultado));

	}else{
		$respuesta["error"] = TRUE;
	}


break;

/********************* OBTENER LISTA PRECIOS ********************/

case 'obtener-lista-precios':

	$respuesta = array();
	$error = array();

	$dtoTercero = $_REQUEST["dtoTercero"];
	$dtoLista = $_REQUEST["dtoLista"];


	if ($_REQUEST["proveedores"] == NULL) {

		$whereproveedores = "";

	}else{

		$proveedores = implode(",", $_REQUEST["proveedores"]);

		$whereproveedores = " AND `articulos-proveedores`.idTercero IN (".$proveedores.")";

	}

	if ($_REQUEST["categorias"] == NULL) {

		$wherecategorias = "";

	}else{

		$categorias = implode(",", $_REQUEST["categorias"]);

		$wherecategorias = " AND articulos.idCategoria IN (".$categorias.")";

	}

	if ($_REQUEST["subcategorias"] == NULL) {

		$wheresubcategorias = "";

	}else{

		$subcategorias = implode(",", $_REQUEST["subcategorias"]);

		$wheresubcategorias = " AND articulos.idSubcateogoria IN (".$subcategorias.")";

	}

	if ($_REQUEST["rubros"] == NULL) {

		$whererubros = "";

	}else{

		$rubros = implode(",", $_REQUEST["rubros"]);

		$whererubros = " AND articulos.idRubro IN (".$rubros.")";

	}

	if ($_REQUEST["marcas"] == NULL) {

		$wheremarcas = "";

	}else{

		$marcas = implode(",", $_REQUEST["marcas"]);

		$wheremarcas = " AND articulos.idMarca IN (".$marcas.")";

	}

	$sql="SELECT
	articulos.id AS idArticulo,
	articulos.denominacionExterna,
	coalesce(`categorias-articulos`.denominacion, 'No Especificado') AS denominacionCategoria,
	coalesce(`subcategorias-articulos`.denominacion, 'No Especificado') AS denominacionSubcategoria,
	coalesce(rubros.denominacion, 'No Especificado') AS denominacionRubro,
	coalesce(marcas.denominacion, 'No Especificado') AS denominacionMarca,
	`alicuotas-iva`.valor AS iva,
	`articulos-proveedores`.precioPesos AS costo,
	articulos.precioVenta AS precioVenta,
	'".$dtoTercero."' AS dtoTercero,
	'".$dtoLista."' AS dtoLista,
	coalesce(articulos.rentabilidad, 0) AS rentabilidad,
	coalesce(`articulos-terceros-descuentos`.descuento, 0) AS dtoArticulo,
	coalesce(`categorias-terceros-descuentos`.descuento, 0) AS dtoCategoria,
	coalesce(`subcategoria-terceros-descuentos`.descuento, 0) AS dtoSubcategoria,
	coalesce(articulos.rentabilidad, 0) -
	coalesce(`articulos-terceros-descuentos`.descuento, 0) -
	coalesce(`categorias-terceros-descuentos`.descuento, 0) -
	coalesce(`subcategoria-terceros-descuentos`.descuento, 0) AS rentabilidadFinal,
	ROUND(
	(`articulos-proveedores`.precioPesos * (
	coalesce(articulos.rentabilidad, 0) -
	coalesce(`articulos-terceros-descuentos`.descuento, 0) -
	coalesce(`categorias-terceros-descuentos`.descuento, 0) -
	coalesce(`subcategoria-terceros-descuentos`.descuento, 0) -
	'".$dtoTercero."' -
	'".$dtoLista."'
	) / 100) + `articulos-proveedores`.precioPesos
	,3) AS precioCliente
	FROM articulos
	INNER JOIN `alicuotas-iva` ON articulos.idAlicuotaIva = `alicuotas-iva`.id
	LEFT JOIN `articulos-terceros-descuentos` ON articulos.id = `articulos-terceros-descuentos`.idArticulo AND
	`articulos-terceros-descuentos`.idTercero = '".$_REQUEST["idTercero"]."'
	LEFT JOIN `categorias-terceros-descuentos` ON articulos.idCategoria =
	`categorias-terceros-descuentos`.idCategoria AND
	`categorias-terceros-descuentos`.idTercero = '".$_REQUEST["idTercero"]."'
	LEFT JOIN `subcategoria-terceros-descuentos` ON articulos.idSubcateogoria =
	`subcategoria-terceros-descuentos`.idSubcategoria AND
	`subcategoria-terceros-descuentos`.idTercero = '".$_REQUEST["idTercero"]."'
	LEFT JOIN `articulos-proveedores` ON `articulos-proveedores`.id =
	articulos.idPrecioCompra
	LEFT JOIN `categorias-articulos` ON articulos.idCategoria = `categorias-articulos`.id
	LEFT JOIN `subcategorias-articulos` ON articulos.idSubcateogoria = `subcategorias-articulos`.id
	LEFT JOIN rubros ON articulos.idRubro = rubros.id
	LEFT JOIN marcas ON articulos.idMarca = marcas.id
	WHERE 1=1 ".
	$whereproveedores.
	$wherecategorias.
	$wheresubcategorias.
	$whererubros.
	$wheremarcas.
	" ORDER BY denominacionCategoria, denominacionSubcategoria, denominacionRubro, denominacionExterna";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows > 0) {

		$respuesta["error"] = FALSE;
		$respuesta["exito"] = array();

		while($fila = mysqli_fetch_array($resultado)){
			array_push($respuesta["exito"], f_array_map('utf8_encode', $fila));
		}

	}else{
		$respuesta["error"] = TRUE;
	}

	break;

/********************* DEFAULT ********************/

	default:
		$error = array();
		$respuesta["error"] = TRUE;

		array_push($error, "La acción no existe");
		$respuesta["error"]=$error;

	break;
}
	echo (json_encode($respuesta)) ;


?>