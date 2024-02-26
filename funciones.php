<?php

include_once("connect.php");

include_once("ewshared13.php");

function actualizarPrecioPesos($idPrecioCompra){

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);

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
		WHERE `articulos-proveedores`.id = '".$idPrecioCompra."'";
		
		$rs = mysqli_query($mysqli,$sql);
		$rows = mysqli_fetch_assoc($rs);
		
		$precio=$rows["precio"]; 

		//Si tiene unidad de medida
		if ($rows["indiceConversion"] != NULL){
			$precio=$rows["precio"] * $rows["indiceConversion"];
		}		
	
		if ($rows["dto1tercero"]!=NULL && $rows["dto1tercero"]!=0) {
			$precio=$precio-($precio*$rows["dto1tercero"]/100);
		}
		if ($rows["dto2tercero"]!=NULL && $rows["dto2tercero"]!=0) {
			$precio=$precio-($precio*$rows["dto2tercero"]/100);
		}
		if ($rows["dto3tercero"]!=NULL && $rows["dto3tercero"]!=0) {
			$precio=$precio-($precio*$rows["dto3tercero"]/100);
		}										
		if ($rows["dto1"]!=NULL && $rows["dto1"]!=0) {
			$precio=$precio-($precio*$rows["dto1"]/100);
		}
		if ($rows["dto2"]!=NULL && $rows["dto2"]!=0) {
			$precio=$precio-($precio*$rows["dto2"]/100);
		}
		if ($rows["dto3"]!=NULL && $rows["dto3"]!=0) {
			$precio=$precio-($precio*$rows["dto3"]/100);
		}	
		
		$precio = $precio * $rows["cotizacion"];	

	$sql = "UPDATE `articulos-proveedores`
	SET `articulos-proveedores`.precioPesos = ".$precio."
	WHERE `articulos-proveedores`.id = '".$idPrecioCompra."'";

	mysqli_query($mysqli,$sql);

}

function obtenerUnidadesArticulo($idArticulo, $esCompra = NULL, $proveedor = NULL){

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);	

	$sql = "SELECT
	`articulos-stock`.idUnidadMedida as id,
	`unidades-medida`.denominacion as denominacion
	FROM `articulos-stock`
	INNER JOIN `unidades-medida`
	ON `articulos-stock`.idUnidadMedida = `unidades-medida`.id
	WHERE `articulos-stock`.idArticulo = '".$idArticulo."'
	ORDER BY `unidades-medida`.id";

	$resultado=mysqli_query($mysqli,$sql);
	$unidades = array();

	if ($resultado->num_rows>0) {

		$unidadesCompra = array();

		if($esCompra){
			$sql = "SELECT
			`articulos-proveedores`.idUnidadMedida as id
			FROM `articulos-proveedores`
			WHERE `articulos-proveedores`.idArticulo = '".$idArticulo."'
			AND `articulos-proveedores`.idTercero = '".$proveedor."'";

			$resultadoUnidadesCompra = mysqli_query($mysqli,$sql);

			if ($resultadoUnidadesCompra->num_rows > 0) {
				while($row = mysqli_fetch_assoc($resultadoUnidadesCompra)){
					array_push($unidadesCompra, $row["id"]);
				}
			}
		}

		for ($i=0; $i < $resultado->num_rows; $i++) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);

			if($esCompra){
				if(in_array($registro["id"], $unidadesCompra)){
					$registro["denominacion"] = $registro["denominacion"]." (*)";
				}
			}

			array_push($unidades, $registro);
		}

	}else{

		$unidaddefault = array(
			"id" => 1,
			"denominacion" => "Unidades"
		);

		array_push($unidades, $unidaddefault);
	}


	return $unidades;

}

function clonarDescuentos($idTerceroBase, $tercero, $tipoDescuento){

	/*
		
		$idTerceroBase : el id del tercero que se usará como base o plantilla de dtos
		$tercero : el id del tercero al que se le aplicará el descuento. Si es 'all' se le aplica a todos los que tengan asociado al terceroBase
		$tipoDescuento : puede ser:
			* 'tercero'
			* 'categoría'
			* 'subcategoría'
			* 'artículo'
			* 'all'
	*/

	//Obtengo los terceros a los que hay que aplicarle los descuentos

	$terceros = array();

	if($tercero != 'all'){
		array_push($terceros, $tercero);
	}else{
		$sql = "SELECT idTercero
		FROM descuentosasociados
		WHERE idTerceroBase = ".$idTerceroBase;

		$rs = ew_LoadRecordset($sql);
		$rows = $rs->GetRows();

		foreach ($rows as $value) {
			array_push($terceros, $value["idTercero"]);
		}
	}

	//Por cada tercero

	foreach ($terceros as $tercero) {

		//tipo de descuento tercero
		if($tipoDescuento == "tercero" || $tipoDescuento == "all"){

			//Obtengo los descuentos del tercero base

			$sql = "SELECT
			idListaPrecios,
			dtoCliente
			FROM `terceros`
			WHERE id = '".$idTerceroBase."'";

			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();

			//Actualizo los descuentos

			$sql = "UPDATE `terceros` SET
			idListaPrecios = '".$rows[0]["idListaPrecios"]."',
			dtoCliente = '".$rows[0]["dtoCliente"]."'
			WHERE id = '".$tercero."'";

			$rs = ew_Execute($sql);
			
		}		

		//tipo de descuento artículo
		if($tipoDescuento == "artículo" || $tipoDescuento == "all"){

			//Obtengo los descuentos del tercero base

			$sql = "SELECT *
			FROM `articulos-terceros-descuentos`
			WHERE idTercero = '".$idTerceroBase."'";

			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();

			if(count($rows) > 0){

				$values = array();

				foreach ($rows as $value) {
					$registro = array(
						$value["idArticulo"],
						$tercero,
						$value["descuento"]
					);
					$registro = implode(",", $registro);
					$registro = '('.$registro.')';
					array_push($values, $registro);
				}

				$values = implode(",", $values);

				//Borro los descuentos actuales

				$sql = "DELETE FROM `articulos-terceros-descuentos`
				WHERE idTercero = ".$tercero;

				$rs = ew_Execute($sql);

				//Inserto los descuentos

				$sql = "INSERT INTO `articulos-terceros-descuentos`
				(
					idArticulo,
					idTercero,
					descuento
				) VALUES ".$values;

				$rs = ew_Execute($sql);
			}
		}

		//tipo de descuento subcategoría
		if($tipoDescuento == "subcategoría" || $tipoDescuento == "all"){

			//Obtengo los descuentos del tercero base

			$sql = "SELECT *
			FROM `subcategoria-terceros-descuentos`
			WHERE idTercero = '".$idTerceroBase."'";

			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();

			if(count($rows) > 0){

				$values = array();

				foreach ($rows as $value) {
					$registro = array(
						$value["idSubcategoria"],
						$tercero,
						$value["descuento"]
					);
					$registro = implode(",", $registro);
					$registro = '('.$registro.')';
					array_push($values, $registro);
				}

				$values = implode(",", $values);

				//Borro los descuentos actuales

				$sql = "DELETE FROM `subcategoria-terceros-descuentos`
				WHERE idTercero = ".$tercero;

				$rs = ew_Execute($sql);

				//Inserto los descuentos

				$sql = "INSERT INTO `subcategoria-terceros-descuentos`
				(
					idSubcategoria,
					idTercero,
					descuento
				) VALUES ".$values;

				$rs = ew_Execute($sql);
			}
		}

		//tipo de descuento categoría
		if($tipoDescuento == "categoría" || $tipoDescuento == "all"){

			//Obtengo los descuentos del tercero base

			$sql = "SELECT *
			FROM `categorias-terceros-descuentos`
			WHERE idTercero = '".$idTerceroBase."'";

			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();

			if(count($rows) > 0){

				$values = array();

				foreach ($rows as $value) {
					$registro = array(
						$value["idCategoria"],
						$tercero,
						$value["descuento"]
					);
					$registro = implode(",", $registro);
					$registro = '('.$registro.')';
					array_push($values, $registro);
				}

				$values = implode(",", $values);

				//Borro los descuentos actuales

				$sql = "DELETE FROM `categorias-terceros-descuentos`
				WHERE idTercero = ".$tercero;

				$rs = ew_Execute($sql);

				//Inserto los descuentos

				$sql = "INSERT INTO `categorias-terceros-descuentos`
				(
					idCategoria,
					idTercero,
					descuento
				) VALUES ".$values;

				$rs = ew_Execute($sql);
			}
		}				

	}
}


function obtenerDiaSemana(){
	$sql="SELECT * FROM `feriados`
	WHERE fecha = '".date("Y-m-d")."'
	LIMIT 1";

	$rs = ew_Execute($sql);
	if ($rs && $rs->RecordCount() === 1){
		return "Feriado";
	}else{
		return date("l");
	}
}

function obtenerCotizacion($moneda){

	$sql="SELECT `cotizacion` FROM `monedas`
	WHERE id='".$moneda."'";	

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();

	return $rows[0]["cotizacion"];

}

function formatofecha($fecha){
	$fecha=date("d/m/Y", strtotime($fecha));
	return $fecha;
}

function formatofechainversa($fecha){
	$fecha = str_replace('/', '-', $fecha);
	$fecha= date('Y-m-d', strtotime($fecha));
	return $fecha;
}

function actualizarPrecio($ids){

	$ids=implode("','", $ids);

	$sql="SELECT articulos.*,
	articulos.id AS 'idarticulo',
	`articulos-proveedores`.*
	FROM articulos
	INNER JOIN `articulos-proveedores` ON articulos.idPrecioCompra =
	`articulos-proveedores`.id
	WHERE articulos.id IN('".$ids."')";

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);

	$rows=array();

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows > 0) {
		for ($i=0; $i < $resultado->num_rows; $i++) { 
			$registro = mysqli_fetch_assoc($resultado);
			array_push($rows, $registro);
		}
	}	

	for ($i=0; $i < count($rows); $i++) {
		/*
		if ($rows[$i]["idUnidadMedidaCompra"] != $rows[$i]["idUnidadMedidaVenta"]) {
			
			$sql="SELECT * FROM `articulos-stock`
			WHERE idUnidadMedida = '".$rows[$i]["idUnidadMedidaCompra"]."'
			AND idArticulo='".$rows[$i]["idarticulo"]."'
			UNION
			SELECT * FROM `articulos-stock`
			WHERE idUnidadMedida = '".$rows[$i]["idUnidadMedidaVenta"]."'
			AND idArticulo='".$rows[$i]["idarticulo"]."'";

			$indices = array();

			$resultado=mysqli_query($mysqli,$sql);

			if ($resultado->num_rows > 0) {
				for ($i2=0; $i2 < $resultado->num_rows; $i2++) { 
					$registro = mysqli_fetch_assoc($resultado);
					array_push($indices, $registro);
				}
			}

			$precioVenta = $rows[$i]["precioPesos"] * $indices[0]["indiceConversion"] / $indices[1]["indiceConversion"];

		}else{

			$precioVenta= $rows[$i]["precioPesos"];

		}
		*/

		$precioVenta= $rows[$i]["precioPesos"];
			
		if ($rows[$i]["calculoPrecio"]==1) {
			
			$precioVenta += ($rows[$i]["rentabilidad"] * $precioVenta /100);

			$sql="UPDATE articulos
			SET articulos.precioVenta='".$precioVenta."'
			WHERE id='".$rows[$i]["idarticulo"]."'";
	
			mysqli_query($mysqli,$sql);

		}else{

			$rentabilidad= ($rows[$i]["precioVenta"] / ($precioVenta)*100)-100;

			$sql="UPDATE articulos
			SET articulos.rentabilidad='".$rentabilidad."'
			WHERE id='".$rows[$i]["idarticulo"]."'";
								
			mysqli_query($mysqli,$sql);

		}

	}	

}

function actualizarStock($id, $stock, $stockreservadoventa, $stockreservadocompra, $stockminimo){

	$sql="SELECT *
	FROM `articulos-stock`
	WHERE id='".$id."'";

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);

	$rows=array();

	$resultado=mysqli_query($mysqli,$sql);

	$registro=mysqli_fetch_assoc($resultado);

	array_push($rows, $registro);

	$sql="UPDATE `articulos-stock`
	SET
	stock=".$stock." * `articulos-stock`.indiceConversion / ".$rows[0]["indiceConversion"].",
	stockReservadoVenta=".$stockreservadoventa." * `articulos-stock`.indiceConversion / ".$rows[0]["indiceConversion"].",
	stockReservadoCompra=".$stockreservadocompra." * `articulos-stock`.indiceConversion / ".$rows[0]["indiceConversion"].",
	stockMinimo=".$stockminimo." * `articulos-stock`.indiceConversion / ".$rows[0]["indiceConversion"]."	
	WHERE idArticulo = '".$rows[0]["idArticulo"]."'";

 	mysqli_query($mysqli,$sql);

}


function cuitnumerico($cuit){
	
	$resultado = preg_replace("/[^0-9]/", "", $cuit)*1;

	return $resultado;

}

function codigosExternos($idarticulo){

	$sql="SELECT
	`articulos-proveedores`.codExterno
	FROM `articulos-proveedores`
	WHERE `articulos-proveedores`.idArticulo='".$idarticulo."'";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();

	$codigosExternos="";

	if (count($rows)>0) {
		
		for ($i=0; $i < count($rows); $i++) { 
			$codigosExternos.=" ".$rows[$i]["codExterno"];	
		}

	}

	$sql="UPDATE
	`articulos`
	SET
	codigosExternos='".$codigosExternos."'
	WHERE
	id='".$idarticulo."'";
	
	ew_Execute($sql);

}

function obtenerCategorias(){
	
	$sql="SELECT id, denominacion FROM `categorias-articulos` ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}


function obtenerSubcategorias(){
	
	$sql="SELECT id, denominacion FROM `subcategorias-articulos` ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerRubros(){
	
	$sql="SELECT id, denominacion FROM `rubros` ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerMarcas(){
	
	$sql="SELECT id, denominacion FROM `marcas` ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerProveedores(){
	
	$sql="SELECT id, denominacion FROM `terceros` WHERE idTipoTercero = 1 ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerClientes(){
	
	$sql="SELECT id, denominacion FROM `terceros` WHERE idTipoTercero = 2 ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerListasPrecios(){
	
	$sql="SELECT id, denominacion, descuento FROM `lista-precios` ORDER BY denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;

}

function obtenerCasasCentrales(){

	$sql = "SELECT DISTINCT
	sucursales.idTerceroPadre AS id,
	terceros.denominacion AS denominacion
	FROM
	sucursales
	INNER JOIN terceros
	ON sucursales.idTerceroPadre = terceros.id
	ORDER BY terceros.denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;	

}

function obtenerComprobantesActivos(){

	$sql = "SELECT
	comprobantes.id,
	comprobantes.denominacion
	FROM comprobantes
	WHERE comprobantes.activo = 1
	ORDER BY comprobantes.denominacion";

	$rs = ew_LoadRecordset($sql);
	$rows = $rs->GetRows();	

	return $rows;	

}

function obtenerPuntoVenta($idComprobante){

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);	

	$sql = "SELECT seAutoriza FROM comprobantes WHERE id='".$idComprobante."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows>0) {
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);
	}

	$sql = "SELECT
	puntoVenta,
	puntoVentaElectronico
	FROM configuracion";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows>0) {
		$puntoventa = mysqli_fetch_assoc($resultado);
		$puntoventa  = f_array_map('utf8_encode', $puntoventa);
	}

	//Si no se autoriza
	if ($registro["seAutoriza"] != 1) {
		return($puntoventa["puntoVenta"]);
	}else{
		return($puntoventa["puntoVentaElectronico"]);
	}

}

function obtenerNumeroComprobante($idComprobante, $puntoVenta, $contable){

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);	


	$sql = "SELECT seAutoriza FROM comprobantes WHERE id='".$idComprobante."'";

	$resultado=mysqli_query($mysqli,$sql);

	if ($resultado->num_rows>0) {
		$registro=mysqli_fetch_assoc($resultado);
		$registro = f_array_map('utf8_encode', $registro);
	}

	if ($registro["seAutoriza"] != 1) {

		$sql = "SELECT * FROM `comprobantes-numeracion`
		WHERE puntoVenta='".$puntoVenta."'
		AND idComprobante = '".$idComprobante."'";

		$resultado=mysqli_query($mysqli,$sql);

		if ($resultado->num_rows>0) {
			$registro=mysqli_fetch_assoc($resultado);
			$registro = f_array_map('utf8_encode', $registro);

			if ($contable == 1) {
				return($registro["ultimoNumeroContable"]+1);
			}else{
				return($registro["ultimoNumero"]+1);
			}

		}else{
			return(1);
		}

	}else{
		return(0);
	}

}

function aumentarNumeroComprobante($puntoVenta, $idComprobante, $contable){

	include_once("ewcfg13.php");

	global $EW_CONN;

	$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);	

	////////

	/*
		Necesito:
		* punto de venta
		* id comprobante
		* contable

	*/

	//Selecciono todo cuando
	//punto de venta =
	//idcomprobante =

	$sql = "SELECT * FROM `comprobantes-numeracion`
	WHERE puntoVenta = '".$puntoVenta."'
	AND idComprobante = '".$idComprobante."'";

	$resultado=mysqli_query($mysqli,$sql);

	//Si no tengo resultados
	if (mysqli_num_rows($resultado) == 0) {

		if ($contable == 1) {

			//Si es contable

			$sql = "INSERT INTO
				`comprobantes-numeracion`
					(
						idComprobante,
						puntoVenta,
						ultimoNumero,
						ultimoNumeroContable
					) VALUES (
						'".$idComprobante."',
						'".$puntoVenta."',
						0,
						1
					)";
		}else{

			//No es contable

			$sql = "INSERT INTO
				`comprobantes-numeracion`
					(
						idComprobante,
						puntoVenta,
						ultimoNumero,
						ultimoNumeroContable
					) VALUES (
						'".$idComprobante."',
						'".$puntoVenta."',
						1,
						0
					)";
		}

	}else{

		//Ya tengo registros

		$registro = mysqli_fetch_assoc($resultado);

		if ($contable == 1) {

			$sql = "UPDATE `comprobantes-numeracion`
				SET ultimoNumeroContable = '".++$registro["ultimoNumeroContable"]."'
				WHERE puntoVenta = '".$puntoVenta."'
				AND idComprobante = '".$idComprobante."'";

		}else{
			$sql = "UPDATE `comprobantes-numeracion`
				SET ultimoNumero = '".++$registro["ultimoNumero"]."'
				WHERE puntoVenta = '".$puntoVenta."'
				AND idComprobante = '".$idComprobante."'";
		}

	}

	mysqli_query($mysqli,$sql);	
}

?>












