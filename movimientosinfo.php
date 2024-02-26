<?php

// Global variable for table object
$movimientos = NULL;

//
// Table class for movimientos
//
class cmovimientos extends cTable {
	var $id;
	var $nroComprobanteCompleto;
	var $tipoMovimiento;
	var $fecha;
	var $idSociedad;
	var $codTercero;
	var $idTercero;
	var $idComprobante;
	var $importeTotal;
	var $importeIva;
	var $importeNeto;
	var $importeCancelado;
	var $nombreTercero;
	var $idDocTercero;
	var $nroDocTercero;
	var $ptoVenta;
	var $nroComprobante;
	var $cae;
	var $vtoCae;
	var $idEstado;
	var $idUsuarioAlta;
	var $fechaAlta;
	var $idUsuarioModificacion;
	var $fechaModificacion;
	var $contable;
	var $archivo;
	var $valorDolar;
	var $comentarios;
	var $articulosAsociados;
	var $movimientosAsociados;
	var $condicionVenta;
	var $vigencia;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'movimientos';
		$this->TableName = 'movimientos';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`movimientos`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);
		$this->BasicSearch->TypeDefault = "OR";

		// id
		$this->id = new cField('movimientos', 'movimientos', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// nroComprobanteCompleto
		$this->nroComprobanteCompleto = new cField('movimientos', 'movimientos', 'x_nroComprobanteCompleto', 'nroComprobanteCompleto', 'CONCAT(ptoVenta,\'-\', nroComprobante)', 'CONCAT(ptoVenta,\'-\', nroComprobante)', 200, -1, FALSE, 'CONCAT(ptoVenta,\'-\', nroComprobante)', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nroComprobanteCompleto->FldIsCustom = TRUE; // Custom field
		$this->nroComprobanteCompleto->Sortable = TRUE; // Allow sort
		$this->fields['nroComprobanteCompleto'] = &$this->nroComprobanteCompleto;

		// tipoMovimiento
		$this->tipoMovimiento = new cField('movimientos', 'movimientos', 'x_tipoMovimiento', 'tipoMovimiento', '`tipoMovimiento`', '`tipoMovimiento`', 3, -1, FALSE, '`tipoMovimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipoMovimiento->Sortable = TRUE; // Allow sort
		$this->tipoMovimiento->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipoMovimiento->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipoMovimiento->OptionCount = 2;
		$this->tipoMovimiento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->tipoMovimiento->AdvancedSearch->SearchValueDefault = 1;
		$this->tipoMovimiento->AdvancedSearch->SearchOperatorDefault = "=";
		$this->tipoMovimiento->AdvancedSearch->SearchOperatorDefault2 = "";
		$this->tipoMovimiento->AdvancedSearch->SearchConditionDefault = "AND";
		$this->fields['tipoMovimiento'] = &$this->tipoMovimiento;

		// fecha
		$this->fecha = new cField('movimientos', 'movimientos', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%Y/%m/%d\')', 133, 0, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->Sortable = TRUE; // Allow sort
		$this->fecha->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha'] = &$this->fecha;

		// idSociedad
		$this->idSociedad = new cField('movimientos', 'movimientos', 'x_idSociedad', 'idSociedad', '`idSociedad`', '`idSociedad`', 3, -1, FALSE, '`idSociedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idSociedad->Sortable = FALSE; // Allow sort
		$this->idSociedad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idSociedad'] = &$this->idSociedad;

		// codTercero
		$this->codTercero = new cField('movimientos', 'movimientos', 'x_codTercero', 'codTercero', 'idTercero', 'idTercero', 3, -1, FALSE, 'idTercero', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codTercero->FldIsCustom = TRUE; // Custom field
		$this->codTercero->Sortable = TRUE; // Allow sort
		$this->codTercero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codTercero'] = &$this->codTercero;

		// idTercero
		$this->idTercero = new cField('movimientos', 'movimientos', 'x_idTercero', 'idTercero', '`idTercero`', '`idTercero`', 3, -1, FALSE, '`idTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTercero->Sortable = TRUE; // Allow sort
		$this->idTercero->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTercero->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTercero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTercero'] = &$this->idTercero;

		// idComprobante
		$this->idComprobante = new cField('movimientos', 'movimientos', 'x_idComprobante', 'idComprobante', '`idComprobante`', '`idComprobante`', 3, -1, FALSE, '`idComprobante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idComprobante->Sortable = TRUE; // Allow sort
		$this->idComprobante->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idComprobante->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idComprobante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idComprobante'] = &$this->idComprobante;

		// importeTotal
		$this->importeTotal = new cField('movimientos', 'movimientos', 'x_importeTotal', 'importeTotal', '`importeTotal`', '`importeTotal`', 5, -1, FALSE, '`importeTotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeTotal->Sortable = TRUE; // Allow sort
		$this->importeTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeTotal'] = &$this->importeTotal;

		// importeIva
		$this->importeIva = new cField('movimientos', 'movimientos', 'x_importeIva', 'importeIva', '`importeIva`', '`importeIva`', 5, -1, FALSE, '`importeIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeIva->Sortable = TRUE; // Allow sort
		$this->importeIva->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeIva'] = &$this->importeIva;

		// importeNeto
		$this->importeNeto = new cField('movimientos', 'movimientos', 'x_importeNeto', 'importeNeto', '`importeNeto`', '`importeNeto`', 5, -1, FALSE, '`importeNeto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeNeto->Sortable = TRUE; // Allow sort
		$this->importeNeto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeNeto'] = &$this->importeNeto;

		// importeCancelado
		$this->importeCancelado = new cField('movimientos', 'movimientos', 'x_importeCancelado', 'importeCancelado', '`importeCancelado`', '`importeCancelado`', 5, -1, FALSE, '`importeCancelado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeCancelado->Sortable = TRUE; // Allow sort
		$this->importeCancelado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeCancelado'] = &$this->importeCancelado;

		// nombreTercero
		$this->nombreTercero = new cField('movimientos', 'movimientos', 'x_nombreTercero', 'nombreTercero', '`nombreTercero`', '`nombreTercero`', 200, -1, FALSE, '`nombreTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreTercero->Sortable = FALSE; // Allow sort
		$this->fields['nombreTercero'] = &$this->nombreTercero;

		// idDocTercero
		$this->idDocTercero = new cField('movimientos', 'movimientos', 'x_idDocTercero', 'idDocTercero', '`idDocTercero`', '`idDocTercero`', 3, -1, FALSE, '`idDocTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idDocTercero->Sortable = FALSE; // Allow sort
		$this->idDocTercero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idDocTercero'] = &$this->idDocTercero;

		// nroDocTercero
		$this->nroDocTercero = new cField('movimientos', 'movimientos', 'x_nroDocTercero', 'nroDocTercero', '`nroDocTercero`', '`nroDocTercero`', 200, -1, FALSE, '`nroDocTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nroDocTercero->Sortable = FALSE; // Allow sort
		$this->fields['nroDocTercero'] = &$this->nroDocTercero;

		// ptoVenta
		$this->ptoVenta = new cField('movimientos', 'movimientos', 'x_ptoVenta', 'ptoVenta', '`ptoVenta`', '`ptoVenta`', 19, -1, FALSE, '`ptoVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ptoVenta->Sortable = FALSE; // Allow sort
		$this->ptoVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ptoVenta'] = &$this->ptoVenta;

		// nroComprobante
		$this->nroComprobante = new cField('movimientos', 'movimientos', 'x_nroComprobante', 'nroComprobante', '`nroComprobante`', '`nroComprobante`', 19, -1, FALSE, '`nroComprobante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nroComprobante->Sortable = FALSE; // Allow sort
		$this->nroComprobante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nroComprobante'] = &$this->nroComprobante;

		// cae
		$this->cae = new cField('movimientos', 'movimientos', 'x_cae', 'cae', '`cae`', '`cae`', 200, -1, FALSE, '`cae`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cae->Sortable = FALSE; // Allow sort
		$this->fields['cae'] = &$this->cae;

		// vtoCae
		$this->vtoCae = new cField('movimientos', 'movimientos', 'x_vtoCae', 'vtoCae', '`vtoCae`', 'DATE_FORMAT(`vtoCae`, \'%Y/%m/%d\')', 133, 0, FALSE, '`vtoCae`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->vtoCae->Sortable = FALSE; // Allow sort
		$this->vtoCae->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['vtoCae'] = &$this->vtoCae;

		// idEstado
		$this->idEstado = new cField('movimientos', 'movimientos', 'x_idEstado', 'idEstado', '`idEstado`', '`idEstado`', 3, -1, FALSE, '`idEstado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idEstado->Sortable = FALSE; // Allow sort
		$this->idEstado->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idEstado->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idEstado->OptionCount = 2;
		$this->idEstado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->idEstado->AdvancedSearch->SearchValueDefault = 1;
		$this->idEstado->AdvancedSearch->SearchOperatorDefault = "=";
		$this->idEstado->AdvancedSearch->SearchOperatorDefault2 = "";
		$this->idEstado->AdvancedSearch->SearchConditionDefault = "AND";
		$this->fields['idEstado'] = &$this->idEstado;

		// idUsuarioAlta
		$this->idUsuarioAlta = new cField('movimientos', 'movimientos', 'x_idUsuarioAlta', 'idUsuarioAlta', '`idUsuarioAlta`', '`idUsuarioAlta`', 3, -1, FALSE, '`idUsuarioAlta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idUsuarioAlta->Sortable = FALSE; // Allow sort
		$this->idUsuarioAlta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUsuarioAlta'] = &$this->idUsuarioAlta;

		// fechaAlta
		$this->fechaAlta = new cField('movimientos', 'movimientos', 'x_fechaAlta', 'fechaAlta', '`fechaAlta`', 'DATE_FORMAT(`fechaAlta`, \'%Y/%m/%d\')', 133, 0, FALSE, '`fechaAlta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaAlta->Sortable = FALSE; // Allow sort
		$this->fechaAlta->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fechaAlta'] = &$this->fechaAlta;

		// idUsuarioModificacion
		$this->idUsuarioModificacion = new cField('movimientos', 'movimientos', 'x_idUsuarioModificacion', 'idUsuarioModificacion', '`idUsuarioModificacion`', '`idUsuarioModificacion`', 3, -1, FALSE, '`idUsuarioModificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idUsuarioModificacion->Sortable = FALSE; // Allow sort
		$this->idUsuarioModificacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUsuarioModificacion'] = &$this->idUsuarioModificacion;

		// fechaModificacion
		$this->fechaModificacion = new cField('movimientos', 'movimientos', 'x_fechaModificacion', 'fechaModificacion', '`fechaModificacion`', 'DATE_FORMAT(`fechaModificacion`, \'%Y/%m/%d\')', 133, 0, FALSE, '`fechaModificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaModificacion->Sortable = FALSE; // Allow sort
		$this->fechaModificacion->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fechaModificacion'] = &$this->fechaModificacion;

		// contable
		$this->contable = new cField('movimientos', 'movimientos', 'x_contable', 'contable', '`contable`', '`contable`', 3, -1, FALSE, '`contable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->contable->Sortable = FALSE; // Allow sort
		$this->contable->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['contable'] = &$this->contable;

		// archivo
		$this->archivo = new cField('movimientos', 'movimientos', 'x_archivo', 'archivo', '`archivo`', '`archivo`', 200, -1, FALSE, '`archivo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->archivo->Sortable = FALSE; // Allow sort
		$this->fields['archivo'] = &$this->archivo;

		// valorDolar
		$this->valorDolar = new cField('movimientos', 'movimientos', 'x_valorDolar', 'valorDolar', '`valorDolar`', '`valorDolar`', 4, -1, FALSE, '`valorDolar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->valorDolar->Sortable = FALSE; // Allow sort
		$this->valorDolar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['valorDolar'] = &$this->valorDolar;

		// comentarios
		$this->comentarios = new cField('movimientos', 'movimientos', 'x_comentarios', 'comentarios', '`comentarios`', '`comentarios`', 201, -1, FALSE, '`comentarios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->comentarios->Sortable = TRUE; // Allow sort
		$this->fields['comentarios'] = &$this->comentarios;

		// articulosAsociados
		$this->articulosAsociados = new cField('movimientos', 'movimientos', 'x_articulosAsociados', 'articulosAsociados', '(SELECT	group_concat(		DISTINCT CONCAT(			COALESCE(`movimientos-detalle`.nombreProducto,\'\'),			\' \',			COALESCE(articulos.denominacionExterna,\'\'),			\' \',			COALESCE(articulos.denominacionInterna,\'\')		)SEPARATOR \',  \'	)FROM `movimientos-detalle` LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', '(SELECT	group_concat(		DISTINCT CONCAT(			COALESCE(`movimientos-detalle`.nombreProducto,\'\'),			\' \',			COALESCE(articulos.denominacionExterna,\'\'),			\' \',			COALESCE(articulos.denominacionInterna,\'\')		)SEPARATOR \',  \'	)FROM `movimientos-detalle` LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', 201, -1, FALSE, '(SELECT	group_concat(		DISTINCT CONCAT(			COALESCE(`movimientos-detalle`.nombreProducto,\'\'),			\' \',			COALESCE(articulos.denominacionExterna,\'\'),			\' \',			COALESCE(articulos.denominacionInterna,\'\')		)SEPARATOR \',  \'	)FROM `movimientos-detalle` LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->articulosAsociados->FldIsCustom = TRUE; // Custom field
		$this->articulosAsociados->Sortable = TRUE; // Allow sort
		$this->fields['articulosAsociados'] = &$this->articulosAsociados;

		// movimientosAsociados
		$this->movimientosAsociados = new cField('movimientos', 'movimientos', 'x_movimientosAsociados', 'movimientosAsociados', '(SELECT group_concat(DISTINCT CONCAT(comprobantes.denominacion,\' \',`movimientos1`.ptoVenta,\'-\',	`movimientos1`.nroComprobante)SEPARATOR \',  \') FROM `movimientos-detalle`INNER JOIN `movimientos-detalle` `movimientos-detalle1` ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id	INNER JOIN movimientos movimientos1	ON `movimientos-detalle1`.idMovimientos = movimientos1.id	INNER JOIN comprobantes	ON movimientos1.idComprobante = comprobantes.id	WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', '(SELECT group_concat(DISTINCT CONCAT(comprobantes.denominacion,\' \',`movimientos1`.ptoVenta,\'-\',	`movimientos1`.nroComprobante)SEPARATOR \',  \') FROM `movimientos-detalle`INNER JOIN `movimientos-detalle` `movimientos-detalle1` ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id	INNER JOIN movimientos movimientos1	ON `movimientos-detalle1`.idMovimientos = movimientos1.id	INNER JOIN comprobantes	ON movimientos1.idComprobante = comprobantes.id	WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', 201, -1, FALSE, '(SELECT group_concat(DISTINCT CONCAT(comprobantes.denominacion,\' \',`movimientos1`.ptoVenta,\'-\',	`movimientos1`.nroComprobante)SEPARATOR \',  \') FROM `movimientos-detalle`INNER JOIN `movimientos-detalle` `movimientos-detalle1` ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id	INNER JOIN movimientos movimientos1	ON `movimientos-detalle1`.idMovimientos = movimientos1.id	INNER JOIN comprobantes	ON movimientos1.idComprobante = comprobantes.id	WHERE `movimientos-detalle`.idMovimientos = movimientos.id)', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->movimientosAsociados->FldIsCustom = TRUE; // Custom field
		$this->movimientosAsociados->Sortable = TRUE; // Allow sort
		$this->movimientosAsociados->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['movimientosAsociados'] = &$this->movimientosAsociados;

		// condicionVenta
		$this->condicionVenta = new cField('movimientos', 'movimientos', 'x_condicionVenta', 'condicionVenta', '`condicionVenta`', '`condicionVenta`', 19, -1, FALSE, '`condicionVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->condicionVenta->Sortable = TRUE; // Allow sort
		$this->condicionVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['condicionVenta'] = &$this->condicionVenta;

		// vigencia
		$this->vigencia = new cField('movimientos', 'movimientos', 'x_vigencia', 'vigencia', '`vigencia`', '`vigencia`', 19, -1, FALSE, '`vigencia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->vigencia->Sortable = TRUE; // Allow sort
		$this->vigencia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vigencia'] = &$this->vigencia;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`movimientos`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, CONCAT(ptoVenta,'-', nroComprobante) AS `nroComprobanteCompleto`, idTercero AS `codTercero`, (SELECT	group_concat(		DISTINCT CONCAT(			COALESCE(`movimientos-detalle`.nombreProducto,''),			' ',			COALESCE(articulos.denominacionExterna,''),			' ',			COALESCE(articulos.denominacionInterna,'')		)SEPARATOR ',  '	)FROM `movimientos-detalle` LEFT JOIN articulos ON `movimientos-detalle`.codProducto = articulos.id WHERE `movimientos-detalle`.idMovimientos = movimientos.id) AS `articulosAsociados`, (SELECT group_concat(DISTINCT CONCAT(comprobantes.denominacion,' ',`movimientos1`.ptoVenta,'-',	`movimientos1`.nroComprobante)SEPARATOR ',  ') FROM `movimientos-detalle`INNER JOIN `movimientos-detalle` `movimientos-detalle1` ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id	INNER JOIN movimientos movimientos1	ON `movimientos-detalle1`.idMovimientos = movimientos1.id	INNER JOIN comprobantes	ON movimientos1.idComprobante = comprobantes.id	WHERE `movimientos-detalle`.idMovimientos = movimientos.id) AS `movimientosAsociados` FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = $_SESSION["modo"] != 2? '`contable` = '.$_SESSION["modo"]: '`contable` = 0 OR `contable` = 1';
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`id` DESC";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "movimientoslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "movimientoslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("movimientosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("movimientosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "movimientosadd.php?" . $this->UrlParm($parm);
		else
			$url = "movimientosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("movimientosedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("movimientosadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("movimientosdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKeys[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->nroComprobanteCompleto->setDbValue($rs->fields('nroComprobanteCompleto'));
		$this->tipoMovimiento->setDbValue($rs->fields('tipoMovimiento'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->idSociedad->setDbValue($rs->fields('idSociedad'));
		$this->codTercero->setDbValue($rs->fields('codTercero'));
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->idComprobante->setDbValue($rs->fields('idComprobante'));
		$this->importeTotal->setDbValue($rs->fields('importeTotal'));
		$this->importeIva->setDbValue($rs->fields('importeIva'));
		$this->importeNeto->setDbValue($rs->fields('importeNeto'));
		$this->importeCancelado->setDbValue($rs->fields('importeCancelado'));
		$this->nombreTercero->setDbValue($rs->fields('nombreTercero'));
		$this->idDocTercero->setDbValue($rs->fields('idDocTercero'));
		$this->nroDocTercero->setDbValue($rs->fields('nroDocTercero'));
		$this->ptoVenta->setDbValue($rs->fields('ptoVenta'));
		$this->nroComprobante->setDbValue($rs->fields('nroComprobante'));
		$this->cae->setDbValue($rs->fields('cae'));
		$this->vtoCae->setDbValue($rs->fields('vtoCae'));
		$this->idEstado->setDbValue($rs->fields('idEstado'));
		$this->idUsuarioAlta->setDbValue($rs->fields('idUsuarioAlta'));
		$this->fechaAlta->setDbValue($rs->fields('fechaAlta'));
		$this->idUsuarioModificacion->setDbValue($rs->fields('idUsuarioModificacion'));
		$this->fechaModificacion->setDbValue($rs->fields('fechaModificacion'));
		$this->contable->setDbValue($rs->fields('contable'));
		$this->archivo->setDbValue($rs->fields('archivo'));
		$this->valorDolar->setDbValue($rs->fields('valorDolar'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->articulosAsociados->setDbValue($rs->fields('articulosAsociados'));
		$this->movimientosAsociados->setDbValue($rs->fields('movimientosAsociados'));
		$this->condicionVenta->setDbValue($rs->fields('condicionVenta'));
		$this->vigencia->setDbValue($rs->fields('vigencia'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// nroComprobanteCompleto
		// tipoMovimiento
		// fecha
		// idSociedad

		$this->idSociedad->CellCssStyle = "white-space: nowrap;";

		// codTercero
		// idTercero
		// idComprobante
		// importeTotal
		// importeIva
		// importeNeto
		// importeCancelado
		// nombreTercero

		$this->nombreTercero->CellCssStyle = "white-space: nowrap;";

		// idDocTercero
		$this->idDocTercero->CellCssStyle = "white-space: nowrap;";

		// nroDocTercero
		$this->nroDocTercero->CellCssStyle = "white-space: nowrap;";

		// ptoVenta
		$this->ptoVenta->CellCssStyle = "white-space: nowrap;";

		// nroComprobante
		$this->nroComprobante->CellCssStyle = "white-space: nowrap;";

		// cae
		// vtoCae
		// idEstado
		// idUsuarioAlta
		// fechaAlta
		// idUsuarioModificacion
		// fechaModificacion
		// contable
		// archivo
		// valorDolar
		// comentarios
		// articulosAsociados

		$this->articulosAsociados->CellCssStyle = "width: 170px;";

		// movimientosAsociados
		$this->movimientosAsociados->CellCssStyle = "width: 170px;";

		// condicionVenta
		// vigencia
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nroComprobanteCompleto
		$this->nroComprobanteCompleto->ViewValue = $this->nroComprobanteCompleto->CurrentValue;
		$this->nroComprobanteCompleto->ViewCustomAttributes = "";

		// tipoMovimiento
		if (strval($this->tipoMovimiento->CurrentValue) <> "") {
			$this->tipoMovimiento->ViewValue = $this->tipoMovimiento->OptionCaption($this->tipoMovimiento->CurrentValue);
		} else {
			$this->tipoMovimiento->ViewValue = NULL;
		}
		$this->tipoMovimiento->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// idSociedad
		$this->idSociedad->ViewValue = $this->idSociedad->CurrentValue;
		$this->idSociedad->ViewCustomAttributes = "";

		// codTercero
		$this->codTercero->ViewValue = $this->codTercero->CurrentValue;
		$this->codTercero->ViewCustomAttributes = "";

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTercero->ViewValue = $this->idTercero->CurrentValue;
			}
		} else {
			$this->idTercero->ViewValue = NULL;
		}
		$this->idTercero->ViewCustomAttributes = "";

		// idComprobante
		if (strval($this->idComprobante->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idComprobante->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
		$sWhereWrk = "";
		$this->idComprobante->LookupFilters = array();
		$lookuptblfilter = "`activo` = 1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idComprobante, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idComprobante->ViewValue = $this->idComprobante->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idComprobante->ViewValue = $this->idComprobante->CurrentValue;
			}
		} else {
			$this->idComprobante->ViewValue = NULL;
		}
		$this->idComprobante->ViewCustomAttributes = "";

		// importeTotal
		$this->importeTotal->ViewValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->ViewCustomAttributes = "";

		// importeIva
		$this->importeIva->ViewValue = $this->importeIva->CurrentValue;
		$this->importeIva->ViewCustomAttributes = "";

		// importeNeto
		$this->importeNeto->ViewValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->ViewCustomAttributes = "";

		// importeCancelado
		$this->importeCancelado->ViewValue = $this->importeCancelado->CurrentValue;
		$this->importeCancelado->ViewCustomAttributes = "";

		// nombreTercero
		$this->nombreTercero->ViewValue = $this->nombreTercero->CurrentValue;
		$this->nombreTercero->ViewCustomAttributes = "";

		// idDocTercero
		$this->idDocTercero->ViewValue = $this->idDocTercero->CurrentValue;
		$this->idDocTercero->ViewCustomAttributes = "";

		// nroDocTercero
		$this->nroDocTercero->ViewValue = $this->nroDocTercero->CurrentValue;
		$this->nroDocTercero->ViewCustomAttributes = "";

		// ptoVenta
		$this->ptoVenta->ViewValue = $this->ptoVenta->CurrentValue;
		$this->ptoVenta->ViewCustomAttributes = "";

		// nroComprobante
		$this->nroComprobante->ViewValue = $this->nroComprobante->CurrentValue;
		$this->nroComprobante->ViewCustomAttributes = "";

		// cae
		$this->cae->ViewValue = $this->cae->CurrentValue;
		$this->cae->ViewCustomAttributes = "";

		// vtoCae
		$this->vtoCae->ViewValue = $this->vtoCae->CurrentValue;
		$this->vtoCae->ViewValue = ew_FormatDateTime($this->vtoCae->ViewValue, 0);
		$this->vtoCae->ViewCustomAttributes = "";

		// idEstado
		if (strval($this->idEstado->CurrentValue) <> "") {
			$this->idEstado->ViewValue = $this->idEstado->OptionCaption($this->idEstado->CurrentValue);
		} else {
			$this->idEstado->ViewValue = NULL;
		}
		$this->idEstado->ViewCustomAttributes = "";

		// idUsuarioAlta
		$this->idUsuarioAlta->ViewValue = $this->idUsuarioAlta->CurrentValue;
		$this->idUsuarioAlta->ViewCustomAttributes = "";

		// fechaAlta
		$this->fechaAlta->ViewValue = $this->fechaAlta->CurrentValue;
		$this->fechaAlta->ViewValue = ew_FormatDateTime($this->fechaAlta->ViewValue, 0);
		$this->fechaAlta->ViewCustomAttributes = "";

		// idUsuarioModificacion
		$this->idUsuarioModificacion->ViewValue = $this->idUsuarioModificacion->CurrentValue;
		$this->idUsuarioModificacion->ViewCustomAttributes = "";

		// fechaModificacion
		$this->fechaModificacion->ViewValue = $this->fechaModificacion->CurrentValue;
		$this->fechaModificacion->ViewValue = ew_FormatDateTime($this->fechaModificacion->ViewValue, 0);
		$this->fechaModificacion->ViewCustomAttributes = "";

		// contable
		$this->contable->ViewValue = $this->contable->CurrentValue;
		$this->contable->ViewCustomAttributes = "";

		// archivo
		$this->archivo->ViewValue = $this->archivo->CurrentValue;
		$this->archivo->ViewCustomAttributes = "";

		// valorDolar
		$this->valorDolar->ViewValue = $this->valorDolar->CurrentValue;
		$this->valorDolar->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// articulosAsociados
		$this->articulosAsociados->ViewValue = $this->articulosAsociados->CurrentValue;
		$this->articulosAsociados->ViewCustomAttributes = "";

		// movimientosAsociados
		$this->movimientosAsociados->ViewValue = $this->movimientosAsociados->CurrentValue;
		$this->movimientosAsociados->ViewCustomAttributes = "";

		// condicionVenta
		$this->condicionVenta->ViewValue = $this->condicionVenta->CurrentValue;
		$this->condicionVenta->ViewCustomAttributes = "";

		// vigencia
		$this->vigencia->ViewValue = $this->vigencia->CurrentValue;
		$this->vigencia->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// nroComprobanteCompleto
		$this->nroComprobanteCompleto->LinkCustomAttributes = "";
		$this->nroComprobanteCompleto->HrefValue = "";
		$this->nroComprobanteCompleto->TooltipValue = "";

		// tipoMovimiento
		$this->tipoMovimiento->LinkCustomAttributes = "";
		$this->tipoMovimiento->HrefValue = "";
		$this->tipoMovimiento->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// idSociedad
		$this->idSociedad->LinkCustomAttributes = "";
		$this->idSociedad->HrefValue = "";
		$this->idSociedad->TooltipValue = "";

		// codTercero
		$this->codTercero->LinkCustomAttributes = "";
		$this->codTercero->HrefValue = "";
		$this->codTercero->TooltipValue = "";

		// idTercero
		$this->idTercero->LinkCustomAttributes = "";
		$this->idTercero->HrefValue = "";
		$this->idTercero->TooltipValue = "";

		// idComprobante
		$this->idComprobante->LinkCustomAttributes = "";
		$this->idComprobante->HrefValue = "";
		$this->idComprobante->TooltipValue = "";

		// importeTotal
		$this->importeTotal->LinkCustomAttributes = "";
		$this->importeTotal->HrefValue = "";
		$this->importeTotal->TooltipValue = "";

		// importeIva
		$this->importeIva->LinkCustomAttributes = "";
		$this->importeIva->HrefValue = "";
		$this->importeIva->TooltipValue = "";

		// importeNeto
		$this->importeNeto->LinkCustomAttributes = "";
		$this->importeNeto->HrefValue = "";
		$this->importeNeto->TooltipValue = "";

		// importeCancelado
		$this->importeCancelado->LinkCustomAttributes = "";
		$this->importeCancelado->HrefValue = "";
		$this->importeCancelado->TooltipValue = "";

		// nombreTercero
		$this->nombreTercero->LinkCustomAttributes = "";
		$this->nombreTercero->HrefValue = "";
		$this->nombreTercero->TooltipValue = "";

		// idDocTercero
		$this->idDocTercero->LinkCustomAttributes = "";
		$this->idDocTercero->HrefValue = "";
		$this->idDocTercero->TooltipValue = "";

		// nroDocTercero
		$this->nroDocTercero->LinkCustomAttributes = "";
		$this->nroDocTercero->HrefValue = "";
		$this->nroDocTercero->TooltipValue = "";

		// ptoVenta
		$this->ptoVenta->LinkCustomAttributes = "";
		$this->ptoVenta->HrefValue = "";
		$this->ptoVenta->TooltipValue = "";

		// nroComprobante
		$this->nroComprobante->LinkCustomAttributes = "";
		$this->nroComprobante->HrefValue = "";
		$this->nroComprobante->TooltipValue = "";

		// cae
		$this->cae->LinkCustomAttributes = "";
		$this->cae->HrefValue = "";
		$this->cae->TooltipValue = "";

		// vtoCae
		$this->vtoCae->LinkCustomAttributes = "";
		$this->vtoCae->HrefValue = "";
		$this->vtoCae->TooltipValue = "";

		// idEstado
		$this->idEstado->LinkCustomAttributes = "";
		$this->idEstado->HrefValue = "";
		$this->idEstado->TooltipValue = "";

		// idUsuarioAlta
		$this->idUsuarioAlta->LinkCustomAttributes = "";
		$this->idUsuarioAlta->HrefValue = "";
		$this->idUsuarioAlta->TooltipValue = "";

		// fechaAlta
		$this->fechaAlta->LinkCustomAttributes = "";
		$this->fechaAlta->HrefValue = "";
		$this->fechaAlta->TooltipValue = "";

		// idUsuarioModificacion
		$this->idUsuarioModificacion->LinkCustomAttributes = "";
		$this->idUsuarioModificacion->HrefValue = "";
		$this->idUsuarioModificacion->TooltipValue = "";

		// fechaModificacion
		$this->fechaModificacion->LinkCustomAttributes = "";
		$this->fechaModificacion->HrefValue = "";
		$this->fechaModificacion->TooltipValue = "";

		// contable
		$this->contable->LinkCustomAttributes = "";
		$this->contable->HrefValue = "";
		$this->contable->TooltipValue = "";

		// archivo
		$this->archivo->LinkCustomAttributes = "";
		$this->archivo->HrefValue = "";
		$this->archivo->TooltipValue = "";

		// valorDolar
		$this->valorDolar->LinkCustomAttributes = "";
		$this->valorDolar->HrefValue = "";
		$this->valorDolar->TooltipValue = "";

		// comentarios
		$this->comentarios->LinkCustomAttributes = "";
		$this->comentarios->HrefValue = "";
		$this->comentarios->TooltipValue = "";

		// articulosAsociados
		$this->articulosAsociados->LinkCustomAttributes = "";
		$this->articulosAsociados->HrefValue = "";
		$this->articulosAsociados->TooltipValue = "";

		// movimientosAsociados
		$this->movimientosAsociados->LinkCustomAttributes = "";
		$this->movimientosAsociados->HrefValue = "";
		$this->movimientosAsociados->TooltipValue = "";

		// condicionVenta
		$this->condicionVenta->LinkCustomAttributes = "";
		$this->condicionVenta->HrefValue = "";
		$this->condicionVenta->TooltipValue = "";

		// vigencia
		$this->vigencia->LinkCustomAttributes = "";
		$this->vigencia->HrefValue = "";
		$this->vigencia->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nroComprobanteCompleto
		$this->nroComprobanteCompleto->EditAttrs["class"] = "form-control";
		$this->nroComprobanteCompleto->EditCustomAttributes = "";
		$this->nroComprobanteCompleto->EditValue = $this->nroComprobanteCompleto->CurrentValue;
		$this->nroComprobanteCompleto->PlaceHolder = ew_RemoveHtml($this->nroComprobanteCompleto->FldCaption());

		// tipoMovimiento
		$this->tipoMovimiento->EditAttrs["class"] = "form-control";
		$this->tipoMovimiento->EditCustomAttributes = "";
		$this->tipoMovimiento->EditValue = $this->tipoMovimiento->Options(TRUE);

		// fecha
		$this->fecha->EditAttrs["class"] = "form-control";
		$this->fecha->EditCustomAttributes = "";
		$this->fecha->EditValue = ew_FormatDateTime($this->fecha->CurrentValue, 8);
		$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

		// idSociedad
		$this->idSociedad->EditAttrs["class"] = "form-control";
		$this->idSociedad->EditCustomAttributes = "";
		$this->idSociedad->EditValue = $this->idSociedad->CurrentValue;
		$this->idSociedad->PlaceHolder = ew_RemoveHtml($this->idSociedad->FldCaption());

		// codTercero
		$this->codTercero->EditAttrs["class"] = "form-control";
		$this->codTercero->EditCustomAttributes = "";
		$this->codTercero->EditValue = $this->codTercero->CurrentValue;
		$this->codTercero->PlaceHolder = ew_RemoveHtml($this->codTercero->FldCaption());

		// idTercero
		$this->idTercero->EditAttrs["class"] = "form-control";
		$this->idTercero->EditCustomAttributes = 'data-s2="true"';

		// idComprobante
		$this->idComprobante->EditAttrs["class"] = "form-control";
		$this->idComprobante->EditCustomAttributes = 'data-s2="true"';

		// importeTotal
		$this->importeTotal->EditAttrs["class"] = "form-control";
		$this->importeTotal->EditCustomAttributes = "";
		$this->importeTotal->EditValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());
		if (strval($this->importeTotal->EditValue) <> "" && is_numeric($this->importeTotal->EditValue)) $this->importeTotal->EditValue = ew_FormatNumber($this->importeTotal->EditValue, -2, -1, -2, 0);

		// importeIva
		$this->importeIva->EditAttrs["class"] = "form-control";
		$this->importeIva->EditCustomAttributes = "";
		$this->importeIva->EditValue = $this->importeIva->CurrentValue;
		$this->importeIva->PlaceHolder = ew_RemoveHtml($this->importeIva->FldCaption());
		if (strval($this->importeIva->EditValue) <> "" && is_numeric($this->importeIva->EditValue)) $this->importeIva->EditValue = ew_FormatNumber($this->importeIva->EditValue, -2, -1, -2, 0);

		// importeNeto
		$this->importeNeto->EditAttrs["class"] = "form-control";
		$this->importeNeto->EditCustomAttributes = "";
		$this->importeNeto->EditValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->PlaceHolder = ew_RemoveHtml($this->importeNeto->FldCaption());
		if (strval($this->importeNeto->EditValue) <> "" && is_numeric($this->importeNeto->EditValue)) $this->importeNeto->EditValue = ew_FormatNumber($this->importeNeto->EditValue, -2, -1, -2, 0);

		// importeCancelado
		$this->importeCancelado->EditAttrs["class"] = "form-control";
		$this->importeCancelado->EditCustomAttributes = "";
		$this->importeCancelado->EditValue = $this->importeCancelado->CurrentValue;
		$this->importeCancelado->PlaceHolder = ew_RemoveHtml($this->importeCancelado->FldCaption());
		if (strval($this->importeCancelado->EditValue) <> "" && is_numeric($this->importeCancelado->EditValue)) $this->importeCancelado->EditValue = ew_FormatNumber($this->importeCancelado->EditValue, -2, -1, -2, 0);

		// nombreTercero
		$this->nombreTercero->EditAttrs["class"] = "form-control";
		$this->nombreTercero->EditCustomAttributes = "";
		$this->nombreTercero->EditValue = $this->nombreTercero->CurrentValue;
		$this->nombreTercero->PlaceHolder = ew_RemoveHtml($this->nombreTercero->FldCaption());

		// idDocTercero
		$this->idDocTercero->EditAttrs["class"] = "form-control";
		$this->idDocTercero->EditCustomAttributes = "";
		$this->idDocTercero->EditValue = $this->idDocTercero->CurrentValue;
		$this->idDocTercero->PlaceHolder = ew_RemoveHtml($this->idDocTercero->FldCaption());

		// nroDocTercero
		$this->nroDocTercero->EditAttrs["class"] = "form-control";
		$this->nroDocTercero->EditCustomAttributes = "";
		$this->nroDocTercero->EditValue = $this->nroDocTercero->CurrentValue;
		$this->nroDocTercero->PlaceHolder = ew_RemoveHtml($this->nroDocTercero->FldCaption());

		// ptoVenta
		$this->ptoVenta->EditAttrs["class"] = "form-control";
		$this->ptoVenta->EditCustomAttributes = "";
		$this->ptoVenta->EditValue = $this->ptoVenta->CurrentValue;
		$this->ptoVenta->PlaceHolder = ew_RemoveHtml($this->ptoVenta->FldCaption());

		// nroComprobante
		$this->nroComprobante->EditAttrs["class"] = "form-control";
		$this->nroComprobante->EditCustomAttributes = "";
		$this->nroComprobante->EditValue = $this->nroComprobante->CurrentValue;
		$this->nroComprobante->PlaceHolder = ew_RemoveHtml($this->nroComprobante->FldCaption());

		// cae
		$this->cae->EditAttrs["class"] = "form-control";
		$this->cae->EditCustomAttributes = "";
		$this->cae->EditValue = $this->cae->CurrentValue;
		$this->cae->PlaceHolder = ew_RemoveHtml($this->cae->FldCaption());

		// vtoCae
		$this->vtoCae->EditAttrs["class"] = "form-control";
		$this->vtoCae->EditCustomAttributes = "";
		$this->vtoCae->EditValue = ew_FormatDateTime($this->vtoCae->CurrentValue, 8);
		$this->vtoCae->PlaceHolder = ew_RemoveHtml($this->vtoCae->FldCaption());

		// idEstado
		$this->idEstado->EditAttrs["class"] = "form-control";
		$this->idEstado->EditCustomAttributes = "";
		$this->idEstado->EditValue = $this->idEstado->Options(TRUE);

		// idUsuarioAlta
		$this->idUsuarioAlta->EditAttrs["class"] = "form-control";
		$this->idUsuarioAlta->EditCustomAttributes = "";
		$this->idUsuarioAlta->EditValue = $this->idUsuarioAlta->CurrentValue;
		$this->idUsuarioAlta->PlaceHolder = ew_RemoveHtml($this->idUsuarioAlta->FldCaption());

		// fechaAlta
		$this->fechaAlta->EditAttrs["class"] = "form-control";
		$this->fechaAlta->EditCustomAttributes = "";
		$this->fechaAlta->EditValue = ew_FormatDateTime($this->fechaAlta->CurrentValue, 8);
		$this->fechaAlta->PlaceHolder = ew_RemoveHtml($this->fechaAlta->FldCaption());

		// idUsuarioModificacion
		$this->idUsuarioModificacion->EditAttrs["class"] = "form-control";
		$this->idUsuarioModificacion->EditCustomAttributes = "";
		$this->idUsuarioModificacion->EditValue = $this->idUsuarioModificacion->CurrentValue;
		$this->idUsuarioModificacion->PlaceHolder = ew_RemoveHtml($this->idUsuarioModificacion->FldCaption());

		// fechaModificacion
		$this->fechaModificacion->EditAttrs["class"] = "form-control";
		$this->fechaModificacion->EditCustomAttributes = "";
		$this->fechaModificacion->EditValue = ew_FormatDateTime($this->fechaModificacion->CurrentValue, 8);
		$this->fechaModificacion->PlaceHolder = ew_RemoveHtml($this->fechaModificacion->FldCaption());

		// contable
		$this->contable->EditAttrs["class"] = "form-control";
		$this->contable->EditCustomAttributes = "";
		$this->contable->EditValue = $this->contable->CurrentValue;
		$this->contable->PlaceHolder = ew_RemoveHtml($this->contable->FldCaption());

		// archivo
		$this->archivo->EditAttrs["class"] = "form-control";
		$this->archivo->EditCustomAttributes = "";
		$this->archivo->EditValue = $this->archivo->CurrentValue;
		$this->archivo->PlaceHolder = ew_RemoveHtml($this->archivo->FldCaption());

		// valorDolar
		$this->valorDolar->EditAttrs["class"] = "form-control";
		$this->valorDolar->EditCustomAttributes = "";
		$this->valorDolar->EditValue = $this->valorDolar->CurrentValue;
		$this->valorDolar->PlaceHolder = ew_RemoveHtml($this->valorDolar->FldCaption());
		if (strval($this->valorDolar->EditValue) <> "" && is_numeric($this->valorDolar->EditValue)) $this->valorDolar->EditValue = ew_FormatNumber($this->valorDolar->EditValue, -2, -1, -2, 0);

		// comentarios
		$this->comentarios->EditAttrs["class"] = "form-control";
		$this->comentarios->EditCustomAttributes = "";
		$this->comentarios->EditValue = $this->comentarios->CurrentValue;
		$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

		// articulosAsociados
		$this->articulosAsociados->EditAttrs["class"] = "form-control";
		$this->articulosAsociados->EditCustomAttributes = "";
		$this->articulosAsociados->EditValue = $this->articulosAsociados->CurrentValue;
		$this->articulosAsociados->PlaceHolder = ew_RemoveHtml($this->articulosAsociados->FldCaption());

		// movimientosAsociados
		$this->movimientosAsociados->EditAttrs["class"] = "form-control";
		$this->movimientosAsociados->EditCustomAttributes = "";
		$this->movimientosAsociados->EditValue = $this->movimientosAsociados->CurrentValue;
		$this->movimientosAsociados->PlaceHolder = ew_RemoveHtml($this->movimientosAsociados->FldCaption());

		// condicionVenta
		$this->condicionVenta->EditAttrs["class"] = "form-control";
		$this->condicionVenta->EditCustomAttributes = "";
		$this->condicionVenta->EditValue = $this->condicionVenta->CurrentValue;
		$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());

		// vigencia
		$this->vigencia->EditAttrs["class"] = "form-control";
		$this->vigencia->EditCustomAttributes = "";
		$this->vigencia->EditValue = $this->vigencia->CurrentValue;
		$this->vigencia->PlaceHolder = ew_RemoveHtml($this->vigencia->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->nroComprobanteCompleto->Exportable) $Doc->ExportCaption($this->nroComprobanteCompleto);
					if ($this->tipoMovimiento->Exportable) $Doc->ExportCaption($this->tipoMovimiento);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->codTercero->Exportable) $Doc->ExportCaption($this->codTercero);
					if ($this->idTercero->Exportable) $Doc->ExportCaption($this->idTercero);
					if ($this->idComprobante->Exportable) $Doc->ExportCaption($this->idComprobante);
					if ($this->importeTotal->Exportable) $Doc->ExportCaption($this->importeTotal);
					if ($this->importeIva->Exportable) $Doc->ExportCaption($this->importeIva);
					if ($this->importeNeto->Exportable) $Doc->ExportCaption($this->importeNeto);
					if ($this->importeCancelado->Exportable) $Doc->ExportCaption($this->importeCancelado);
					if ($this->cae->Exportable) $Doc->ExportCaption($this->cae);
					if ($this->vtoCae->Exportable) $Doc->ExportCaption($this->vtoCae);
					if ($this->idEstado->Exportable) $Doc->ExportCaption($this->idEstado);
					if ($this->idUsuarioAlta->Exportable) $Doc->ExportCaption($this->idUsuarioAlta);
					if ($this->fechaAlta->Exportable) $Doc->ExportCaption($this->fechaAlta);
					if ($this->idUsuarioModificacion->Exportable) $Doc->ExportCaption($this->idUsuarioModificacion);
					if ($this->fechaModificacion->Exportable) $Doc->ExportCaption($this->fechaModificacion);
					if ($this->contable->Exportable) $Doc->ExportCaption($this->contable);
					if ($this->archivo->Exportable) $Doc->ExportCaption($this->archivo);
					if ($this->valorDolar->Exportable) $Doc->ExportCaption($this->valorDolar);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->articulosAsociados->Exportable) $Doc->ExportCaption($this->articulosAsociados);
					if ($this->movimientosAsociados->Exportable) $Doc->ExportCaption($this->movimientosAsociados);
					if ($this->condicionVenta->Exportable) $Doc->ExportCaption($this->condicionVenta);
					if ($this->vigencia->Exportable) $Doc->ExportCaption($this->vigencia);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->nroComprobanteCompleto->Exportable) $Doc->ExportCaption($this->nroComprobanteCompleto);
					if ($this->tipoMovimiento->Exportable) $Doc->ExportCaption($this->tipoMovimiento);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->codTercero->Exportable) $Doc->ExportCaption($this->codTercero);
					if ($this->idTercero->Exportable) $Doc->ExportCaption($this->idTercero);
					if ($this->idComprobante->Exportable) $Doc->ExportCaption($this->idComprobante);
					if ($this->importeTotal->Exportable) $Doc->ExportCaption($this->importeTotal);
					if ($this->importeIva->Exportable) $Doc->ExportCaption($this->importeIva);
					if ($this->importeNeto->Exportable) $Doc->ExportCaption($this->importeNeto);
					if ($this->importeCancelado->Exportable) $Doc->ExportCaption($this->importeCancelado);
					if ($this->articulosAsociados->Exportable) $Doc->ExportCaption($this->articulosAsociados);
					if ($this->movimientosAsociados->Exportable) $Doc->ExportCaption($this->movimientosAsociados);
					if ($this->condicionVenta->Exportable) $Doc->ExportCaption($this->condicionVenta);
					if ($this->vigencia->Exportable) $Doc->ExportCaption($this->vigencia);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->nroComprobanteCompleto->Exportable) $Doc->ExportField($this->nroComprobanteCompleto);
						if ($this->tipoMovimiento->Exportable) $Doc->ExportField($this->tipoMovimiento);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->codTercero->Exportable) $Doc->ExportField($this->codTercero);
						if ($this->idTercero->Exportable) $Doc->ExportField($this->idTercero);
						if ($this->idComprobante->Exportable) $Doc->ExportField($this->idComprobante);
						if ($this->importeTotal->Exportable) $Doc->ExportField($this->importeTotal);
						if ($this->importeIva->Exportable) $Doc->ExportField($this->importeIva);
						if ($this->importeNeto->Exportable) $Doc->ExportField($this->importeNeto);
						if ($this->importeCancelado->Exportable) $Doc->ExportField($this->importeCancelado);
						if ($this->cae->Exportable) $Doc->ExportField($this->cae);
						if ($this->vtoCae->Exportable) $Doc->ExportField($this->vtoCae);
						if ($this->idEstado->Exportable) $Doc->ExportField($this->idEstado);
						if ($this->idUsuarioAlta->Exportable) $Doc->ExportField($this->idUsuarioAlta);
						if ($this->fechaAlta->Exportable) $Doc->ExportField($this->fechaAlta);
						if ($this->idUsuarioModificacion->Exportable) $Doc->ExportField($this->idUsuarioModificacion);
						if ($this->fechaModificacion->Exportable) $Doc->ExportField($this->fechaModificacion);
						if ($this->contable->Exportable) $Doc->ExportField($this->contable);
						if ($this->archivo->Exportable) $Doc->ExportField($this->archivo);
						if ($this->valorDolar->Exportable) $Doc->ExportField($this->valorDolar);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->articulosAsociados->Exportable) $Doc->ExportField($this->articulosAsociados);
						if ($this->movimientosAsociados->Exportable) $Doc->ExportField($this->movimientosAsociados);
						if ($this->condicionVenta->Exportable) $Doc->ExportField($this->condicionVenta);
						if ($this->vigencia->Exportable) $Doc->ExportField($this->vigencia);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->nroComprobanteCompleto->Exportable) $Doc->ExportField($this->nroComprobanteCompleto);
						if ($this->tipoMovimiento->Exportable) $Doc->ExportField($this->tipoMovimiento);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->codTercero->Exportable) $Doc->ExportField($this->codTercero);
						if ($this->idTercero->Exportable) $Doc->ExportField($this->idTercero);
						if ($this->idComprobante->Exportable) $Doc->ExportField($this->idComprobante);
						if ($this->importeTotal->Exportable) $Doc->ExportField($this->importeTotal);
						if ($this->importeIva->Exportable) $Doc->ExportField($this->importeIva);
						if ($this->importeNeto->Exportable) $Doc->ExportField($this->importeNeto);
						if ($this->importeCancelado->Exportable) $Doc->ExportField($this->importeCancelado);
						if ($this->articulosAsociados->Exportable) $Doc->ExportField($this->articulosAsociados);
						if ($this->movimientosAsociados->Exportable) $Doc->ExportField($this->movimientosAsociados);
						if ($this->condicionVenta->Exportable) $Doc->ExportField($this->condicionVenta);
						if ($this->vigencia->Exportable) $Doc->ExportField($this->vigencia);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {
		ew_Execute("UPDATE `movimientos-detalle`
				LEFT JOIN `movimientos-detalle` `movimientos-detalle1`
				ON `movimientos-detalle`.origenImportacion = `movimientos-detalle1`.id
				SET `movimientos-detalle1`.cantidadImportada = `movimientos-detalle1`.cantidadImportada - `movimientos-detalle`.cant
				WHERE `movimientos-detalle`.idMovimientos = " . $rs["id"]);
		ew_Execute("DELETE FROM `movimientos-detalle` WHERE idMovimientos=" . $rs["id"]);
	}     

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {
				if ($this->idEstado->CurrentValue==2) {
					$this->RowAttrs["style"] = "background-color: rgba(0,100,0,0.3); "; 
				}
			if($this->archivo->ViewValue!=""){
				$this->idComprobante->ViewValue =
			  '<a href="upload/'.$this->archivo->ViewValue.'" target="_blank">'.$this->idComprobante->ViewValue." ".str_pad($this->ptoVenta->ViewValue, 4,0, STR_PAD_LEFT).' - '.str_pad($this->nroComprobante->ViewValue, 8,0, STR_PAD_LEFT).'</a>';
			}else{
			if($this->nroComprobante->ViewValue!=0){
				$this->idComprobante->ViewValue =$this->idComprobante->ViewValue." ".str_pad($this->ptoVenta->ViewValue, 4,0, STR_PAD_LEFT).' - '.str_pad($this->nroComprobante->ViewValue, 8,0, STR_PAD_LEFT);
			}else{                               
				$this->idComprobante->ViewValue =$this->idComprobante->ViewValue; 
			}        
		} 
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
