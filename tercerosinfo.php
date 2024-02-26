<?php

// Global variable for table object
$terceros = NULL;

//
// Table class for terceros
//
class cterceros extends cTable {
	var $id;
	var $idTipoTercero;
	var $denominacion;
	var $razonSocial;
	var $denominacionCorta;
	var $idPais;
	var $idProvincia;
	var $idPartido;
	var $idLocalidad;
	var $calle;
	var $direccion;
	var $domicilioFiscal;
	var $idPaisFiscal;
	var $idProvinciaFiscal;
	var $idPartidoFiscal;
	var $idLocalidadFiscal;
	var $calleFiscal;
	var $direccionFiscal;
	var $tipoDoc;
	var $documento;
	var $condicionIva;
	var $observaciones;
	var $idTransporte;
	var $idVendedor;
	var $idCobrador;
	var $comision;
	var $idListaPrecios;
	var $dtoCliente;
	var $dto1;
	var $dto2;
	var $dto3;
	var $limiteDescubierto;
	var $codigoPostal;
	var $codigoPostalFiscal;
	var $condicionVenta;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'terceros';
		$this->TableName = 'terceros';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`terceros`";
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
		$this->id = new cField('terceros', 'terceros', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// idTipoTercero
		$this->idTipoTercero = new cField('terceros', 'terceros', 'x_idTipoTercero', 'idTipoTercero', '`idTipoTercero`', '`idTipoTercero`', 3, -1, FALSE, '`idTipoTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTipoTercero->Sortable = TRUE; // Allow sort
		$this->idTipoTercero->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTipoTercero->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTipoTercero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTipoTercero'] = &$this->idTipoTercero;

		// denominacion
		$this->denominacion = new cField('terceros', 'terceros', 'x_denominacion', 'denominacion', '`denominacion`', '`denominacion`', 200, -1, FALSE, '`denominacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacion->Sortable = TRUE; // Allow sort
		$this->fields['denominacion'] = &$this->denominacion;

		// razonSocial
		$this->razonSocial = new cField('terceros', 'terceros', 'x_razonSocial', 'razonSocial', '`razonSocial`', '`razonSocial`', 200, -1, FALSE, '`razonSocial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->razonSocial->Sortable = TRUE; // Allow sort
		$this->fields['razonSocial'] = &$this->razonSocial;

		// denominacionCorta
		$this->denominacionCorta = new cField('terceros', 'terceros', 'x_denominacionCorta', 'denominacionCorta', '`denominacionCorta`', '`denominacionCorta`', 200, -1, FALSE, '`denominacionCorta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacionCorta->Sortable = TRUE; // Allow sort
		$this->fields['denominacionCorta'] = &$this->denominacionCorta;

		// idPais
		$this->idPais = new cField('terceros', 'terceros', 'x_idPais', 'idPais', '`idPais`', '`idPais`', 3, -1, FALSE, '`EV__idPais`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->idPais->Sortable = TRUE; // Allow sort
		$this->idPais->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPais->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPais->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPais'] = &$this->idPais;

		// idProvincia
		$this->idProvincia = new cField('terceros', 'terceros', 'x_idProvincia', 'idProvincia', '`idProvincia`', '`idProvincia`', 3, -1, FALSE, '`idProvincia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idProvincia->Sortable = TRUE; // Allow sort
		$this->idProvincia->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idProvincia->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idProvincia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idProvincia'] = &$this->idProvincia;

		// idPartido
		$this->idPartido = new cField('terceros', 'terceros', 'x_idPartido', 'idPartido', '`idPartido`', '`idPartido`', 3, -1, FALSE, '`idPartido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPartido->Sortable = TRUE; // Allow sort
		$this->idPartido->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPartido->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPartido->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPartido'] = &$this->idPartido;

		// idLocalidad
		$this->idLocalidad = new cField('terceros', 'terceros', 'x_idLocalidad', 'idLocalidad', '`idLocalidad`', '`idLocalidad`', 3, -1, FALSE, '`idLocalidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idLocalidad->Sortable = TRUE; // Allow sort
		$this->idLocalidad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idLocalidad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idLocalidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idLocalidad'] = &$this->idLocalidad;

		// calle
		$this->calle = new cField('terceros', 'terceros', 'x_calle', 'calle', '`calle`', '`calle`', 200, -1, FALSE, '`calle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->calle->Sortable = TRUE; // Allow sort
		$this->fields['calle'] = &$this->calle;

		// direccion
		$this->direccion = new cField('terceros', 'terceros', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, -1, FALSE, '`direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion->Sortable = TRUE; // Allow sort
		$this->fields['direccion'] = &$this->direccion;

		// domicilioFiscal
		$this->domicilioFiscal = new cField('terceros', 'terceros', 'x_domicilioFiscal', 'domicilioFiscal', '`domicilioFiscal`', '`domicilioFiscal`', 3, -1, FALSE, '`domicilioFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->domicilioFiscal->Sortable = TRUE; // Allow sort
		$this->domicilioFiscal->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->domicilioFiscal->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->domicilioFiscal->OptionCount = 1;
		$this->domicilioFiscal->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['domicilioFiscal'] = &$this->domicilioFiscal;

		// idPaisFiscal
		$this->idPaisFiscal = new cField('terceros', 'terceros', 'x_idPaisFiscal', 'idPaisFiscal', '`idPaisFiscal`', '`idPaisFiscal`', 3, -1, FALSE, '`idPaisFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPaisFiscal->Sortable = TRUE; // Allow sort
		$this->idPaisFiscal->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPaisFiscal->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPaisFiscal->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPaisFiscal'] = &$this->idPaisFiscal;

		// idProvinciaFiscal
		$this->idProvinciaFiscal = new cField('terceros', 'terceros', 'x_idProvinciaFiscal', 'idProvinciaFiscal', '`idProvinciaFiscal`', '`idProvinciaFiscal`', 3, -1, FALSE, '`idProvinciaFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idProvinciaFiscal->Sortable = TRUE; // Allow sort
		$this->idProvinciaFiscal->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idProvinciaFiscal->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idProvinciaFiscal->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idProvinciaFiscal'] = &$this->idProvinciaFiscal;

		// idPartidoFiscal
		$this->idPartidoFiscal = new cField('terceros', 'terceros', 'x_idPartidoFiscal', 'idPartidoFiscal', '`idPartidoFiscal`', '`idPartidoFiscal`', 3, -1, FALSE, '`idPartidoFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPartidoFiscal->Sortable = TRUE; // Allow sort
		$this->idPartidoFiscal->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPartidoFiscal->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPartidoFiscal->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPartidoFiscal'] = &$this->idPartidoFiscal;

		// idLocalidadFiscal
		$this->idLocalidadFiscal = new cField('terceros', 'terceros', 'x_idLocalidadFiscal', 'idLocalidadFiscal', '`idLocalidadFiscal`', '`idLocalidadFiscal`', 3, -1, FALSE, '`idLocalidadFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idLocalidadFiscal->Sortable = TRUE; // Allow sort
		$this->idLocalidadFiscal->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idLocalidadFiscal->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idLocalidadFiscal->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idLocalidadFiscal'] = &$this->idLocalidadFiscal;

		// calleFiscal
		$this->calleFiscal = new cField('terceros', 'terceros', 'x_calleFiscal', 'calleFiscal', '`calleFiscal`', '`calleFiscal`', 200, -1, FALSE, '`calleFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->calleFiscal->Sortable = TRUE; // Allow sort
		$this->fields['calleFiscal'] = &$this->calleFiscal;

		// direccionFiscal
		$this->direccionFiscal = new cField('terceros', 'terceros', 'x_direccionFiscal', 'direccionFiscal', '`direccionFiscal`', '`direccionFiscal`', 200, -1, FALSE, '`direccionFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccionFiscal->Sortable = TRUE; // Allow sort
		$this->fields['direccionFiscal'] = &$this->direccionFiscal;

		// tipoDoc
		$this->tipoDoc = new cField('terceros', 'terceros', 'x_tipoDoc', 'tipoDoc', '`tipoDoc`', '`tipoDoc`', 3, -1, FALSE, '`tipoDoc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipoDoc->Sortable = TRUE; // Allow sort
		$this->tipoDoc->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipoDoc->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipoDoc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipoDoc'] = &$this->tipoDoc;

		// documento
		$this->documento = new cField('terceros', 'terceros', 'x_documento', 'documento', '`documento`', '`documento`', 200, -1, FALSE, '`documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->documento->Sortable = TRUE; // Allow sort
		$this->fields['documento'] = &$this->documento;

		// condicionIva
		$this->condicionIva = new cField('terceros', 'terceros', 'x_condicionIva', 'condicionIva', '`condicionIva`', '`condicionIva`', 3, -1, FALSE, '`condicionIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->condicionIva->Sortable = TRUE; // Allow sort
		$this->condicionIva->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->condicionIva->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->condicionIva->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['condicionIva'] = &$this->condicionIva;

		// observaciones
		$this->observaciones = new cField('terceros', 'terceros', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// idTransporte
		$this->idTransporte = new cField('terceros', 'terceros', 'x_idTransporte', 'idTransporte', '`idTransporte`', '`idTransporte`', 3, -1, FALSE, '`idTransporte`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTransporte->Sortable = TRUE; // Allow sort
		$this->idTransporte->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTransporte->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTransporte->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTransporte'] = &$this->idTransporte;

		// idVendedor
		$this->idVendedor = new cField('terceros', 'terceros', 'x_idVendedor', 'idVendedor', '`idVendedor`', '`idVendedor`', 3, -1, FALSE, '`idVendedor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idVendedor->Sortable = TRUE; // Allow sort
		$this->idVendedor->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idVendedor->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idVendedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idVendedor'] = &$this->idVendedor;

		// idCobrador
		$this->idCobrador = new cField('terceros', 'terceros', 'x_idCobrador', 'idCobrador', '`idCobrador`', '`idCobrador`', 3, -1, FALSE, '`idCobrador`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idCobrador->Sortable = TRUE; // Allow sort
		$this->idCobrador->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idCobrador->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idCobrador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idCobrador'] = &$this->idCobrador;

		// comision
		$this->comision = new cField('terceros', 'terceros', 'x_comision', 'comision', '`comision`', '`comision`', 4, -1, FALSE, '`comision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->comision->Sortable = TRUE; // Allow sort
		$this->comision->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['comision'] = &$this->comision;

		// idListaPrecios
		$this->idListaPrecios = new cField('terceros', 'terceros', 'x_idListaPrecios', 'idListaPrecios', '`idListaPrecios`', '`idListaPrecios`', 3, -1, FALSE, '`idListaPrecios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idListaPrecios->Sortable = TRUE; // Allow sort
		$this->idListaPrecios->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idListaPrecios->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idListaPrecios->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idListaPrecios'] = &$this->idListaPrecios;

		// dtoCliente
		$this->dtoCliente = new cField('terceros', 'terceros', 'x_dtoCliente', 'dtoCliente', '`dtoCliente`', '`dtoCliente`', 4, -1, FALSE, '`dtoCliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dtoCliente->Sortable = TRUE; // Allow sort
		$this->dtoCliente->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dtoCliente'] = &$this->dtoCliente;

		// dto1
		$this->dto1 = new cField('terceros', 'terceros', 'x_dto1', 'dto1', '`dto1`', '`dto1`', 4, -1, FALSE, '`dto1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto1->Sortable = TRUE; // Allow sort
		$this->dto1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto1'] = &$this->dto1;

		// dto2
		$this->dto2 = new cField('terceros', 'terceros', 'x_dto2', 'dto2', '`dto2`', '`dto2`', 4, -1, FALSE, '`dto2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto2->Sortable = TRUE; // Allow sort
		$this->dto2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto2'] = &$this->dto2;

		// dto3
		$this->dto3 = new cField('terceros', 'terceros', 'x_dto3', 'dto3', '`dto3`', '`dto3`', 4, -1, FALSE, '`dto3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto3->Sortable = TRUE; // Allow sort
		$this->dto3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto3'] = &$this->dto3;

		// limiteDescubierto
		$this->limiteDescubierto = new cField('terceros', 'terceros', 'x_limiteDescubierto', 'limiteDescubierto', '`limiteDescubierto`', '`limiteDescubierto`', 4, -1, FALSE, '`limiteDescubierto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->limiteDescubierto->Sortable = TRUE; // Allow sort
		$this->limiteDescubierto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['limiteDescubierto'] = &$this->limiteDescubierto;

		// codigoPostal
		$this->codigoPostal = new cField('terceros', 'terceros', 'x_codigoPostal', 'codigoPostal', '`codigoPostal`', '`codigoPostal`', 200, -1, FALSE, '`codigoPostal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigoPostal->Sortable = TRUE; // Allow sort
		$this->fields['codigoPostal'] = &$this->codigoPostal;

		// codigoPostalFiscal
		$this->codigoPostalFiscal = new cField('terceros', 'terceros', 'x_codigoPostalFiscal', 'codigoPostalFiscal', '`codigoPostalFiscal`', '`codigoPostalFiscal`', 200, -1, FALSE, '`codigoPostalFiscal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigoPostalFiscal->Sortable = TRUE; // Allow sort
		$this->fields['codigoPostalFiscal'] = &$this->codigoPostalFiscal;

		// condicionVenta
		$this->condicionVenta = new cField('terceros', 'terceros', 'x_condicionVenta', 'condicionVenta', '`condicionVenta`', '`condicionVenta`', 19, -1, FALSE, '`condicionVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->condicionVenta->Sortable = TRUE; // Allow sort
		$this->condicionVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['condicionVenta'] = &$this->condicionVenta;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "terceros2Dmedios2Dcontactos") {
			$sDetailUrl = $GLOBALS["terceros2Dmedios2Dcontactos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "articulos2Dterceros2Ddescuentos") {
			$sDetailUrl = $GLOBALS["articulos2Dterceros2Ddescuentos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "articulos2Dproveedores") {
			$sDetailUrl = $GLOBALS["articulos2Dproveedores"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "subcategoria2Dterceros2Ddescuentos") {
			$sDetailUrl = $GLOBALS["subcategoria2Dterceros2Ddescuentos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "categorias2Dterceros2Ddescuentos") {
			$sDetailUrl = $GLOBALS["categorias2Dterceros2Ddescuentos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "sucursales") {
			$sDetailUrl = $GLOBALS["sucursales"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "descuentosasociados") {
			$sDetailUrl = $GLOBALS["descuentosasociados"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "terceroslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`terceros`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `denominacion` FROM `paises` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `terceros`.`idPais` LIMIT 1) AS `EV__idPais` FROM `terceros`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->idPais->AdvancedSearch->SearchValue <> "" ||
			$this->idPais->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->idPais->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->idPais->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			return "terceroslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "terceroslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tercerosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tercerosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tercerosadd.php?" . $this->UrlParm($parm);
		else
			$url = "tercerosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tercerosedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tercerosedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tercerosadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tercerosadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tercerosdelete.php", $this->UrlParm());
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
		$this->idTipoTercero->setDbValue($rs->fields('idTipoTercero'));
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->razonSocial->setDbValue($rs->fields('razonSocial'));
		$this->denominacionCorta->setDbValue($rs->fields('denominacionCorta'));
		$this->idPais->setDbValue($rs->fields('idPais'));
		$this->idProvincia->setDbValue($rs->fields('idProvincia'));
		$this->idPartido->setDbValue($rs->fields('idPartido'));
		$this->idLocalidad->setDbValue($rs->fields('idLocalidad'));
		$this->calle->setDbValue($rs->fields('calle'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->domicilioFiscal->setDbValue($rs->fields('domicilioFiscal'));
		$this->idPaisFiscal->setDbValue($rs->fields('idPaisFiscal'));
		$this->idProvinciaFiscal->setDbValue($rs->fields('idProvinciaFiscal'));
		$this->idPartidoFiscal->setDbValue($rs->fields('idPartidoFiscal'));
		$this->idLocalidadFiscal->setDbValue($rs->fields('idLocalidadFiscal'));
		$this->calleFiscal->setDbValue($rs->fields('calleFiscal'));
		$this->direccionFiscal->setDbValue($rs->fields('direccionFiscal'));
		$this->tipoDoc->setDbValue($rs->fields('tipoDoc'));
		$this->documento->setDbValue($rs->fields('documento'));
		$this->condicionIva->setDbValue($rs->fields('condicionIva'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->idTransporte->setDbValue($rs->fields('idTransporte'));
		$this->idVendedor->setDbValue($rs->fields('idVendedor'));
		$this->idCobrador->setDbValue($rs->fields('idCobrador'));
		$this->comision->setDbValue($rs->fields('comision'));
		$this->idListaPrecios->setDbValue($rs->fields('idListaPrecios'));
		$this->dtoCliente->setDbValue($rs->fields('dtoCliente'));
		$this->dto1->setDbValue($rs->fields('dto1'));
		$this->dto2->setDbValue($rs->fields('dto2'));
		$this->dto3->setDbValue($rs->fields('dto3'));
		$this->limiteDescubierto->setDbValue($rs->fields('limiteDescubierto'));
		$this->codigoPostal->setDbValue($rs->fields('codigoPostal'));
		$this->codigoPostalFiscal->setDbValue($rs->fields('codigoPostalFiscal'));
		$this->condicionVenta->setDbValue($rs->fields('condicionVenta'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idTipoTercero
		// denominacion
		// razonSocial
		// denominacionCorta
		// idPais
		// idProvincia
		// idPartido
		// idLocalidad
		// calle
		// direccion
		// domicilioFiscal
		// idPaisFiscal
		// idProvinciaFiscal
		// idPartidoFiscal
		// idLocalidadFiscal
		// calleFiscal
		// direccionFiscal
		// tipoDoc
		// documento
		// condicionIva
		// observaciones
		// idTransporte
		// idVendedor
		// idCobrador
		// comision
		// idListaPrecios
		// dtoCliente
		// dto1
		// dto2
		// dto3
		// limiteDescubierto
		// codigoPostal
		// codigoPostalFiscal
		// condicionVenta
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idTipoTercero
		if (strval($this->idTipoTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-terceros`";
		$sWhereWrk = "";
		$this->idTipoTercero->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTipoTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTipoTercero->ViewValue = $this->idTipoTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTipoTercero->ViewValue = $this->idTipoTercero->CurrentValue;
			}
		} else {
			$this->idTipoTercero->ViewValue = NULL;
		}
		$this->idTipoTercero->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// razonSocial
		$this->razonSocial->ViewValue = $this->razonSocial->CurrentValue;
		$this->razonSocial->ViewCustomAttributes = "";

		// denominacionCorta
		$this->denominacionCorta->ViewValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->ViewCustomAttributes = "";

		// idPais
		if ($this->idPais->VirtualValue <> "") {
			$this->idPais->ViewValue = $this->idPais->VirtualValue;
		} else {
		if (strval($this->idPais->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPais->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
		$sWhereWrk = "";
		$this->idPais->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPais->ViewValue = $this->idPais->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPais->ViewValue = $this->idPais->CurrentValue;
			}
		} else {
			$this->idPais->ViewValue = NULL;
		}
		}
		$this->idPais->ViewCustomAttributes = "";

		// idProvincia
		if (strval($this->idProvincia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvincia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
		$sWhereWrk = "";
		$this->idProvincia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idProvincia->ViewValue = $this->idProvincia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idProvincia->ViewValue = $this->idProvincia->CurrentValue;
			}
		} else {
			$this->idProvincia->ViewValue = NULL;
		}
		$this->idProvincia->ViewCustomAttributes = "";

		// idPartido
		if (strval($this->idPartido->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartido->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
		$sWhereWrk = "";
		$this->idPartido->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPartido->ViewValue = $this->idPartido->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPartido->ViewValue = $this->idPartido->CurrentValue;
			}
		} else {
			$this->idPartido->ViewValue = NULL;
		}
		$this->idPartido->ViewCustomAttributes = "";

		// idLocalidad
		if (strval($this->idLocalidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->idLocalidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idLocalidad->ViewValue = $this->idLocalidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idLocalidad->ViewValue = $this->idLocalidad->CurrentValue;
			}
		} else {
			$this->idLocalidad->ViewValue = NULL;
		}
		$this->idLocalidad->ViewCustomAttributes = "";

		// calle
		$this->calle->ViewValue = $this->calle->CurrentValue;
		$this->calle->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// domicilioFiscal
		if (strval($this->domicilioFiscal->CurrentValue) <> "") {
			$this->domicilioFiscal->ViewValue = $this->domicilioFiscal->OptionCaption($this->domicilioFiscal->CurrentValue);
		} else {
			$this->domicilioFiscal->ViewValue = NULL;
		}
		$this->domicilioFiscal->ViewCustomAttributes = "";

		// idPaisFiscal
		if (strval($this->idPaisFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPaisFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
		$sWhereWrk = "";
		$this->idPaisFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPaisFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPaisFiscal->ViewValue = $this->idPaisFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPaisFiscal->ViewValue = $this->idPaisFiscal->CurrentValue;
			}
		} else {
			$this->idPaisFiscal->ViewValue = NULL;
		}
		$this->idPaisFiscal->ViewCustomAttributes = "";

		// idProvinciaFiscal
		if (strval($this->idProvinciaFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvinciaFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
		$sWhereWrk = "";
		$this->idProvinciaFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idProvinciaFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idProvinciaFiscal->ViewValue = $this->idProvinciaFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idProvinciaFiscal->ViewValue = $this->idProvinciaFiscal->CurrentValue;
			}
		} else {
			$this->idProvinciaFiscal->ViewValue = NULL;
		}
		$this->idProvinciaFiscal->ViewCustomAttributes = "";

		// idPartidoFiscal
		if (strval($this->idPartidoFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartidoFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
		$sWhereWrk = "";
		$this->idPartidoFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPartidoFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPartidoFiscal->ViewValue = $this->idPartidoFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPartidoFiscal->ViewValue = $this->idPartidoFiscal->CurrentValue;
			}
		} else {
			$this->idPartidoFiscal->ViewValue = NULL;
		}
		$this->idPartidoFiscal->ViewCustomAttributes = "";

		// idLocalidadFiscal
		if (strval($this->idLocalidadFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidadFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->idLocalidadFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idLocalidadFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idLocalidadFiscal->ViewValue = $this->idLocalidadFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idLocalidadFiscal->ViewValue = $this->idLocalidadFiscal->CurrentValue;
			}
		} else {
			$this->idLocalidadFiscal->ViewValue = NULL;
		}
		$this->idLocalidadFiscal->ViewCustomAttributes = "";

		// calleFiscal
		$this->calleFiscal->ViewValue = $this->calleFiscal->CurrentValue;
		$this->calleFiscal->ViewCustomAttributes = "";

		// direccionFiscal
		$this->direccionFiscal->ViewValue = $this->direccionFiscal->CurrentValue;
		$this->direccionFiscal->ViewCustomAttributes = "";

		// tipoDoc
		if (strval($this->tipoDoc->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
		$sWhereWrk = "";
		$this->tipoDoc->LookupFilters = array();
		$lookuptblfilter = "`activo`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipoDoc, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipoDoc->ViewValue = $this->tipoDoc->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipoDoc->ViewValue = $this->tipoDoc->CurrentValue;
			}
		} else {
			$this->tipoDoc->ViewValue = NULL;
		}
		$this->tipoDoc->ViewCustomAttributes = "";

		// documento
		$this->documento->ViewValue = $this->documento->CurrentValue;
		$this->documento->ViewCustomAttributes = "";

		// condicionIva
		if (strval($this->condicionIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->condicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
		$sWhereWrk = "";
		$this->condicionIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->condicionIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->condicionIva->ViewValue = $this->condicionIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->condicionIva->ViewValue = $this->condicionIva->CurrentValue;
			}
		} else {
			$this->condicionIva->ViewValue = NULL;
		}
		$this->condicionIva->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// idTransporte
		if (strval($this->idTransporte->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTransporte->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTransporte->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`=3";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTransporte, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTransporte->ViewValue = $this->idTransporte->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTransporte->ViewValue = $this->idTransporte->CurrentValue;
			}
		} else {
			$this->idTransporte->ViewValue = NULL;
		}
		$this->idTransporte->ViewCustomAttributes = "";

		// idVendedor
		if (strval($this->idVendedor->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idVendedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idVendedor->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`='4'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idVendedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idVendedor->ViewValue = $this->idVendedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idVendedor->ViewValue = $this->idVendedor->CurrentValue;
			}
		} else {
			$this->idVendedor->ViewValue = NULL;
		}
		$this->idVendedor->ViewCustomAttributes = "";

		// idCobrador
		if (strval($this->idCobrador->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCobrador->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idCobrador->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`='4'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCobrador, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` DESC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCobrador->ViewValue = $this->idCobrador->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCobrador->ViewValue = $this->idCobrador->CurrentValue;
			}
		} else {
			$this->idCobrador->ViewValue = NULL;
		}
		$this->idCobrador->ViewCustomAttributes = "";

		// comision
		$this->comision->ViewValue = $this->comision->CurrentValue;
		$this->comision->ViewCustomAttributes = "";

		// idListaPrecios
		if (strval($this->idListaPrecios->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idListaPrecios->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `descuento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lista-precios`";
		$sWhereWrk = "";
		$this->idListaPrecios->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idListaPrecios, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `descuento` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->idListaPrecios->ViewValue = $this->idListaPrecios->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idListaPrecios->ViewValue = $this->idListaPrecios->CurrentValue;
			}
		} else {
			$this->idListaPrecios->ViewValue = NULL;
		}
		$this->idListaPrecios->ViewCustomAttributes = "";

		// dtoCliente
		$this->dtoCliente->ViewValue = $this->dtoCliente->CurrentValue;
		$this->dtoCliente->ViewCustomAttributes = "";

		// dto1
		$this->dto1->ViewValue = $this->dto1->CurrentValue;
		$this->dto1->ViewCustomAttributes = "";

		// dto2
		$this->dto2->ViewValue = $this->dto2->CurrentValue;
		$this->dto2->ViewCustomAttributes = "";

		// dto3
		$this->dto3->ViewValue = $this->dto3->CurrentValue;
		$this->dto3->ViewCustomAttributes = "";

		// limiteDescubierto
		$this->limiteDescubierto->ViewValue = $this->limiteDescubierto->CurrentValue;
		$this->limiteDescubierto->ViewCustomAttributes = "";

		// codigoPostal
		$this->codigoPostal->ViewValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->ViewCustomAttributes = "";

		// codigoPostalFiscal
		$this->codigoPostalFiscal->ViewValue = $this->codigoPostalFiscal->CurrentValue;
		$this->codigoPostalFiscal->ViewCustomAttributes = "";

		// condicionVenta
		$this->condicionVenta->ViewValue = $this->condicionVenta->CurrentValue;
		$this->condicionVenta->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// idTipoTercero
		$this->idTipoTercero->LinkCustomAttributes = "";
		$this->idTipoTercero->HrefValue = "";
		$this->idTipoTercero->TooltipValue = "";

		// denominacion
		$this->denominacion->LinkCustomAttributes = "";
		$this->denominacion->HrefValue = "";
		$this->denominacion->TooltipValue = "";

		// razonSocial
		$this->razonSocial->LinkCustomAttributes = "";
		$this->razonSocial->HrefValue = "";
		$this->razonSocial->TooltipValue = "";

		// denominacionCorta
		$this->denominacionCorta->LinkCustomAttributes = "";
		$this->denominacionCorta->HrefValue = "";
		$this->denominacionCorta->TooltipValue = "";

		// idPais
		$this->idPais->LinkCustomAttributes = "";
		$this->idPais->HrefValue = "";
		$this->idPais->TooltipValue = "";

		// idProvincia
		$this->idProvincia->LinkCustomAttributes = "";
		$this->idProvincia->HrefValue = "";
		$this->idProvincia->TooltipValue = "";

		// idPartido
		$this->idPartido->LinkCustomAttributes = "";
		$this->idPartido->HrefValue = "";
		$this->idPartido->TooltipValue = "";

		// idLocalidad
		$this->idLocalidad->LinkCustomAttributes = "";
		$this->idLocalidad->HrefValue = "";
		$this->idLocalidad->TooltipValue = "";

		// calle
		$this->calle->LinkCustomAttributes = "";
		$this->calle->HrefValue = "";
		$this->calle->TooltipValue = "";

		// direccion
		$this->direccion->LinkCustomAttributes = "";
		$this->direccion->HrefValue = "";
		$this->direccion->TooltipValue = "";

		// domicilioFiscal
		$this->domicilioFiscal->LinkCustomAttributes = "";
		$this->domicilioFiscal->HrefValue = "";
		$this->domicilioFiscal->TooltipValue = "";

		// idPaisFiscal
		$this->idPaisFiscal->LinkCustomAttributes = "";
		$this->idPaisFiscal->HrefValue = "";
		$this->idPaisFiscal->TooltipValue = "";

		// idProvinciaFiscal
		$this->idProvinciaFiscal->LinkCustomAttributes = "";
		$this->idProvinciaFiscal->HrefValue = "";
		$this->idProvinciaFiscal->TooltipValue = "";

		// idPartidoFiscal
		$this->idPartidoFiscal->LinkCustomAttributes = "";
		$this->idPartidoFiscal->HrefValue = "";
		$this->idPartidoFiscal->TooltipValue = "";

		// idLocalidadFiscal
		$this->idLocalidadFiscal->LinkCustomAttributes = "";
		$this->idLocalidadFiscal->HrefValue = "";
		$this->idLocalidadFiscal->TooltipValue = "";

		// calleFiscal
		$this->calleFiscal->LinkCustomAttributes = "";
		$this->calleFiscal->HrefValue = "";
		$this->calleFiscal->TooltipValue = "";

		// direccionFiscal
		$this->direccionFiscal->LinkCustomAttributes = "";
		$this->direccionFiscal->HrefValue = "";
		$this->direccionFiscal->TooltipValue = "";

		// tipoDoc
		$this->tipoDoc->LinkCustomAttributes = "";
		$this->tipoDoc->HrefValue = "";
		$this->tipoDoc->TooltipValue = "";

		// documento
		$this->documento->LinkCustomAttributes = "";
		$this->documento->HrefValue = "";
		$this->documento->TooltipValue = "";

		// condicionIva
		$this->condicionIva->LinkCustomAttributes = "";
		$this->condicionIva->HrefValue = "";
		$this->condicionIva->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// idTransporte
		$this->idTransporte->LinkCustomAttributes = "";
		$this->idTransporte->HrefValue = "";
		$this->idTransporte->TooltipValue = "";

		// idVendedor
		$this->idVendedor->LinkCustomAttributes = "";
		$this->idVendedor->HrefValue = "";
		$this->idVendedor->TooltipValue = "";

		// idCobrador
		$this->idCobrador->LinkCustomAttributes = "";
		$this->idCobrador->HrefValue = "";
		$this->idCobrador->TooltipValue = "";

		// comision
		$this->comision->LinkCustomAttributes = "";
		$this->comision->HrefValue = "";
		$this->comision->TooltipValue = "";

		// idListaPrecios
		$this->idListaPrecios->LinkCustomAttributes = "";
		$this->idListaPrecios->HrefValue = "";
		$this->idListaPrecios->TooltipValue = "";

		// dtoCliente
		$this->dtoCliente->LinkCustomAttributes = "";
		$this->dtoCliente->HrefValue = "";
		$this->dtoCliente->TooltipValue = "";

		// dto1
		$this->dto1->LinkCustomAttributes = "";
		$this->dto1->HrefValue = "";
		$this->dto1->TooltipValue = "";

		// dto2
		$this->dto2->LinkCustomAttributes = "";
		$this->dto2->HrefValue = "";
		$this->dto2->TooltipValue = "";

		// dto3
		$this->dto3->LinkCustomAttributes = "";
		$this->dto3->HrefValue = "";
		$this->dto3->TooltipValue = "";

		// limiteDescubierto
		$this->limiteDescubierto->LinkCustomAttributes = "";
		$this->limiteDescubierto->HrefValue = "";
		$this->limiteDescubierto->TooltipValue = "";

		// codigoPostal
		$this->codigoPostal->LinkCustomAttributes = "";
		$this->codigoPostal->HrefValue = "";
		$this->codigoPostal->TooltipValue = "";

		// codigoPostalFiscal
		$this->codigoPostalFiscal->LinkCustomAttributes = "";
		$this->codigoPostalFiscal->HrefValue = "";
		$this->codigoPostalFiscal->TooltipValue = "";

		// condicionVenta
		$this->condicionVenta->LinkCustomAttributes = "";
		$this->condicionVenta->HrefValue = "";
		$this->condicionVenta->TooltipValue = "";

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

		// idTipoTercero
		$this->idTipoTercero->EditAttrs["class"] = "form-control";
		$this->idTipoTercero->EditCustomAttributes = 'data-elemento-dependiente="true"';

		// denominacion
		$this->denominacion->EditAttrs["class"] = "form-control";
		$this->denominacion->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
		$this->denominacion->EditValue = $this->denominacion->CurrentValue;
		$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

		// razonSocial
		$this->razonSocial->EditAttrs["class"] = "form-control";
		$this->razonSocial->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
		$this->razonSocial->EditValue = $this->razonSocial->CurrentValue;
		$this->razonSocial->PlaceHolder = ew_RemoveHtml($this->razonSocial->FldCaption());

		// denominacionCorta
		$this->denominacionCorta->EditAttrs["class"] = "form-control";
		$this->denominacionCorta->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
		$this->denominacionCorta->EditValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->PlaceHolder = ew_RemoveHtml($this->denominacionCorta->FldCaption());

		// idPais
		$this->idPais->EditAttrs["class"] = "form-control";
		$this->idPais->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// idProvincia
		$this->idProvincia->EditAttrs["class"] = "form-control";
		$this->idProvincia->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// idPartido
		$this->idPartido->EditAttrs["class"] = "form-control";
		$this->idPartido->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// idLocalidad
		$this->idLocalidad->EditAttrs["class"] = "form-control";
		$this->idLocalidad->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// calle
		$this->calle->EditAttrs["class"] = "form-control";
		$this->calle->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
		$this->calle->EditValue = $this->calle->CurrentValue;
		$this->calle->PlaceHolder = ew_RemoveHtml($this->calle->FldCaption());

		// direccion
		$this->direccion->EditAttrs["class"] = "form-control";
		$this->direccion->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
		$this->direccion->EditValue = $this->direccion->CurrentValue;
		$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

		// domicilioFiscal
		$this->domicilioFiscal->EditAttrs["class"] = "form-control";
		$this->domicilioFiscal->EditCustomAttributes = 'data-elemento-dependiente="true" data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
		$this->domicilioFiscal->EditValue = $this->domicilioFiscal->Options(TRUE);

		// idPaisFiscal
		$this->idPaisFiscal->EditAttrs["class"] = "form-control";
		$this->idPaisFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';

		// idProvinciaFiscal
		$this->idProvinciaFiscal->EditAttrs["class"] = "form-control";
		$this->idProvinciaFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';

		// idPartidoFiscal
		$this->idPartidoFiscal->EditAttrs["class"] = "form-control";
		$this->idPartidoFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';

		// idLocalidadFiscal
		$this->idLocalidadFiscal->EditAttrs["class"] = "form-control";
		$this->idLocalidadFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';

		// calleFiscal
		$this->calleFiscal->EditAttrs["class"] = "form-control";
		$this->calleFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
		$this->calleFiscal->EditValue = $this->calleFiscal->CurrentValue;
		$this->calleFiscal->PlaceHolder = ew_RemoveHtml($this->calleFiscal->FldCaption());

		// direccionFiscal
		$this->direccionFiscal->EditAttrs["class"] = "form-control";
		$this->direccionFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
		$this->direccionFiscal->EditValue = $this->direccionFiscal->CurrentValue;
		$this->direccionFiscal->PlaceHolder = ew_RemoveHtml($this->direccionFiscal->FldCaption());

		// tipoDoc
		$this->tipoDoc->EditAttrs["class"] = "form-control";
		$this->tipoDoc->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// documento
		$this->documento->EditAttrs["class"] = "form-control";
		$this->documento->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
		$this->documento->EditValue = $this->documento->CurrentValue;
		$this->documento->PlaceHolder = ew_RemoveHtml($this->documento->FldCaption());

		// condicionIva
		$this->condicionIva->EditAttrs["class"] = "form-control";
		$this->condicionIva->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// idTransporte
		$this->idTransporte->EditAttrs["class"] = "form-control";
		$this->idTransporte->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';

		// idVendedor
		$this->idVendedor->EditAttrs["class"] = "form-control";
		$this->idVendedor->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';

		// idCobrador
		$this->idCobrador->EditAttrs["class"] = "form-control";
		$this->idCobrador->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';

		// comision
		$this->comision->EditAttrs["class"] = "form-control";
		$this->comision->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
		$this->comision->EditValue = $this->comision->CurrentValue;
		$this->comision->PlaceHolder = ew_RemoveHtml($this->comision->FldCaption());
		if (strval($this->comision->EditValue) <> "" && is_numeric($this->comision->EditValue)) $this->comision->EditValue = ew_FormatNumber($this->comision->EditValue, -2, -1, -2, 0);

		// idListaPrecios
		$this->idListaPrecios->EditAttrs["class"] = "form-control";
		$this->idListaPrecios->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';

		// dtoCliente
		$this->dtoCliente->EditAttrs["class"] = "form-control";
		$this->dtoCliente->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
		$this->dtoCliente->EditValue = $this->dtoCliente->CurrentValue;
		$this->dtoCliente->PlaceHolder = ew_RemoveHtml($this->dtoCliente->FldCaption());
		if (strval($this->dtoCliente->EditValue) <> "" && is_numeric($this->dtoCliente->EditValue)) $this->dtoCliente->EditValue = ew_FormatNumber($this->dtoCliente->EditValue, -2, -1, -2, 0);

		// dto1
		$this->dto1->EditAttrs["class"] = "form-control";
		$this->dto1->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
		$this->dto1->EditValue = $this->dto1->CurrentValue;
		$this->dto1->PlaceHolder = ew_RemoveHtml($this->dto1->FldCaption());
		if (strval($this->dto1->EditValue) <> "" && is_numeric($this->dto1->EditValue)) $this->dto1->EditValue = ew_FormatNumber($this->dto1->EditValue, -2, -1, -2, 0);

		// dto2
		$this->dto2->EditAttrs["class"] = "form-control";
		$this->dto2->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
		$this->dto2->EditValue = $this->dto2->CurrentValue;
		$this->dto2->PlaceHolder = ew_RemoveHtml($this->dto2->FldCaption());
		if (strval($this->dto2->EditValue) <> "" && is_numeric($this->dto2->EditValue)) $this->dto2->EditValue = ew_FormatNumber($this->dto2->EditValue, -2, -1, -2, 0);

		// dto3
		$this->dto3->EditAttrs["class"] = "form-control";
		$this->dto3->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
		$this->dto3->EditValue = $this->dto3->CurrentValue;
		$this->dto3->PlaceHolder = ew_RemoveHtml($this->dto3->FldCaption());
		if (strval($this->dto3->EditValue) <> "" && is_numeric($this->dto3->EditValue)) $this->dto3->EditValue = ew_FormatNumber($this->dto3->EditValue, -2, -1, -2, 0);

		// limiteDescubierto
		$this->limiteDescubierto->EditAttrs["class"] = "form-control";
		$this->limiteDescubierto->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
		$this->limiteDescubierto->EditValue = $this->limiteDescubierto->CurrentValue;
		$this->limiteDescubierto->PlaceHolder = ew_RemoveHtml($this->limiteDescubierto->FldCaption());
		if (strval($this->limiteDescubierto->EditValue) <> "" && is_numeric($this->limiteDescubierto->EditValue)) $this->limiteDescubierto->EditValue = ew_FormatNumber($this->limiteDescubierto->EditValue, -2, -1, -2, 0);

		// codigoPostal
		$this->codigoPostal->EditAttrs["class"] = "form-control";
		$this->codigoPostal->EditCustomAttributes = "";
		$this->codigoPostal->EditValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->PlaceHolder = ew_RemoveHtml($this->codigoPostal->FldCaption());

		// codigoPostalFiscal
		$this->codigoPostalFiscal->EditAttrs["class"] = "form-control";
		$this->codigoPostalFiscal->EditCustomAttributes = "";
		$this->codigoPostalFiscal->EditValue = $this->codigoPostalFiscal->CurrentValue;
		$this->codigoPostalFiscal->PlaceHolder = ew_RemoveHtml($this->codigoPostalFiscal->FldCaption());

		// condicionVenta
		$this->condicionVenta->EditAttrs["class"] = "form-control";
		$this->condicionVenta->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
		$this->condicionVenta->EditValue = $this->condicionVenta->CurrentValue;
		$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());

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
					if ($this->idTipoTercero->Exportable) $Doc->ExportCaption($this->idTipoTercero);
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->razonSocial->Exportable) $Doc->ExportCaption($this->razonSocial);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->domicilioFiscal->Exportable) $Doc->ExportCaption($this->domicilioFiscal);
					if ($this->idPaisFiscal->Exportable) $Doc->ExportCaption($this->idPaisFiscal);
					if ($this->idProvinciaFiscal->Exportable) $Doc->ExportCaption($this->idProvinciaFiscal);
					if ($this->idPartidoFiscal->Exportable) $Doc->ExportCaption($this->idPartidoFiscal);
					if ($this->idLocalidadFiscal->Exportable) $Doc->ExportCaption($this->idLocalidadFiscal);
					if ($this->calleFiscal->Exportable) $Doc->ExportCaption($this->calleFiscal);
					if ($this->direccionFiscal->Exportable) $Doc->ExportCaption($this->direccionFiscal);
					if ($this->documento->Exportable) $Doc->ExportCaption($this->documento);
					if ($this->condicionIva->Exportable) $Doc->ExportCaption($this->condicionIva);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->idVendedor->Exportable) $Doc->ExportCaption($this->idVendedor);
					if ($this->idCobrador->Exportable) $Doc->ExportCaption($this->idCobrador);
					if ($this->comision->Exportable) $Doc->ExportCaption($this->comision);
					if ($this->idListaPrecios->Exportable) $Doc->ExportCaption($this->idListaPrecios);
					if ($this->dtoCliente->Exportable) $Doc->ExportCaption($this->dtoCliente);
					if ($this->dto1->Exportable) $Doc->ExportCaption($this->dto1);
					if ($this->dto2->Exportable) $Doc->ExportCaption($this->dto2);
					if ($this->dto3->Exportable) $Doc->ExportCaption($this->dto3);
					if ($this->limiteDescubierto->Exportable) $Doc->ExportCaption($this->limiteDescubierto);
					if ($this->codigoPostal->Exportable) $Doc->ExportCaption($this->codigoPostal);
					if ($this->codigoPostalFiscal->Exportable) $Doc->ExportCaption($this->codigoPostalFiscal);
					if ($this->condicionVenta->Exportable) $Doc->ExportCaption($this->condicionVenta);
				} else {
					if ($this->idTipoTercero->Exportable) $Doc->ExportCaption($this->idTipoTercero);
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->razonSocial->Exportable) $Doc->ExportCaption($this->razonSocial);
					if ($this->denominacionCorta->Exportable) $Doc->ExportCaption($this->denominacionCorta);
					if ($this->idPais->Exportable) $Doc->ExportCaption($this->idPais);
					if ($this->idProvincia->Exportable) $Doc->ExportCaption($this->idProvincia);
					if ($this->idPartido->Exportable) $Doc->ExportCaption($this->idPartido);
					if ($this->idLocalidad->Exportable) $Doc->ExportCaption($this->idLocalidad);
					if ($this->calle->Exportable) $Doc->ExportCaption($this->calle);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->domicilioFiscal->Exportable) $Doc->ExportCaption($this->domicilioFiscal);
					if ($this->idPaisFiscal->Exportable) $Doc->ExportCaption($this->idPaisFiscal);
					if ($this->idProvinciaFiscal->Exportable) $Doc->ExportCaption($this->idProvinciaFiscal);
					if ($this->idPartidoFiscal->Exportable) $Doc->ExportCaption($this->idPartidoFiscal);
					if ($this->idLocalidadFiscal->Exportable) $Doc->ExportCaption($this->idLocalidadFiscal);
					if ($this->calleFiscal->Exportable) $Doc->ExportCaption($this->calleFiscal);
					if ($this->direccionFiscal->Exportable) $Doc->ExportCaption($this->direccionFiscal);
					if ($this->tipoDoc->Exportable) $Doc->ExportCaption($this->tipoDoc);
					if ($this->documento->Exportable) $Doc->ExportCaption($this->documento);
					if ($this->condicionIva->Exportable) $Doc->ExportCaption($this->condicionIva);
					if ($this->idTransporte->Exportable) $Doc->ExportCaption($this->idTransporte);
					if ($this->idVendedor->Exportable) $Doc->ExportCaption($this->idVendedor);
					if ($this->idCobrador->Exportable) $Doc->ExportCaption($this->idCobrador);
					if ($this->comision->Exportable) $Doc->ExportCaption($this->comision);
					if ($this->idListaPrecios->Exportable) $Doc->ExportCaption($this->idListaPrecios);
					if ($this->dtoCliente->Exportable) $Doc->ExportCaption($this->dtoCliente);
					if ($this->dto1->Exportable) $Doc->ExportCaption($this->dto1);
					if ($this->dto2->Exportable) $Doc->ExportCaption($this->dto2);
					if ($this->dto3->Exportable) $Doc->ExportCaption($this->dto3);
					if ($this->limiteDescubierto->Exportable) $Doc->ExportCaption($this->limiteDescubierto);
					if ($this->codigoPostal->Exportable) $Doc->ExportCaption($this->codigoPostal);
					if ($this->codigoPostalFiscal->Exportable) $Doc->ExportCaption($this->codigoPostalFiscal);
					if ($this->condicionVenta->Exportable) $Doc->ExportCaption($this->condicionVenta);
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
						if ($this->idTipoTercero->Exportable) $Doc->ExportField($this->idTipoTercero);
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->razonSocial->Exportable) $Doc->ExportField($this->razonSocial);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->domicilioFiscal->Exportable) $Doc->ExportField($this->domicilioFiscal);
						if ($this->idPaisFiscal->Exportable) $Doc->ExportField($this->idPaisFiscal);
						if ($this->idProvinciaFiscal->Exportable) $Doc->ExportField($this->idProvinciaFiscal);
						if ($this->idPartidoFiscal->Exportable) $Doc->ExportField($this->idPartidoFiscal);
						if ($this->idLocalidadFiscal->Exportable) $Doc->ExportField($this->idLocalidadFiscal);
						if ($this->calleFiscal->Exportable) $Doc->ExportField($this->calleFiscal);
						if ($this->direccionFiscal->Exportable) $Doc->ExportField($this->direccionFiscal);
						if ($this->documento->Exportable) $Doc->ExportField($this->documento);
						if ($this->condicionIva->Exportable) $Doc->ExportField($this->condicionIva);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->idVendedor->Exportable) $Doc->ExportField($this->idVendedor);
						if ($this->idCobrador->Exportable) $Doc->ExportField($this->idCobrador);
						if ($this->comision->Exportable) $Doc->ExportField($this->comision);
						if ($this->idListaPrecios->Exportable) $Doc->ExportField($this->idListaPrecios);
						if ($this->dtoCliente->Exportable) $Doc->ExportField($this->dtoCliente);
						if ($this->dto1->Exportable) $Doc->ExportField($this->dto1);
						if ($this->dto2->Exportable) $Doc->ExportField($this->dto2);
						if ($this->dto3->Exportable) $Doc->ExportField($this->dto3);
						if ($this->limiteDescubierto->Exportable) $Doc->ExportField($this->limiteDescubierto);
						if ($this->codigoPostal->Exportable) $Doc->ExportField($this->codigoPostal);
						if ($this->codigoPostalFiscal->Exportable) $Doc->ExportField($this->codigoPostalFiscal);
						if ($this->condicionVenta->Exportable) $Doc->ExportField($this->condicionVenta);
					} else {
						if ($this->idTipoTercero->Exportable) $Doc->ExportField($this->idTipoTercero);
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->razonSocial->Exportable) $Doc->ExportField($this->razonSocial);
						if ($this->denominacionCorta->Exportable) $Doc->ExportField($this->denominacionCorta);
						if ($this->idPais->Exportable) $Doc->ExportField($this->idPais);
						if ($this->idProvincia->Exportable) $Doc->ExportField($this->idProvincia);
						if ($this->idPartido->Exportable) $Doc->ExportField($this->idPartido);
						if ($this->idLocalidad->Exportable) $Doc->ExportField($this->idLocalidad);
						if ($this->calle->Exportable) $Doc->ExportField($this->calle);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->domicilioFiscal->Exportable) $Doc->ExportField($this->domicilioFiscal);
						if ($this->idPaisFiscal->Exportable) $Doc->ExportField($this->idPaisFiscal);
						if ($this->idProvinciaFiscal->Exportable) $Doc->ExportField($this->idProvinciaFiscal);
						if ($this->idPartidoFiscal->Exportable) $Doc->ExportField($this->idPartidoFiscal);
						if ($this->idLocalidadFiscal->Exportable) $Doc->ExportField($this->idLocalidadFiscal);
						if ($this->calleFiscal->Exportable) $Doc->ExportField($this->calleFiscal);
						if ($this->direccionFiscal->Exportable) $Doc->ExportField($this->direccionFiscal);
						if ($this->tipoDoc->Exportable) $Doc->ExportField($this->tipoDoc);
						if ($this->documento->Exportable) $Doc->ExportField($this->documento);
						if ($this->condicionIva->Exportable) $Doc->ExportField($this->condicionIva);
						if ($this->idTransporte->Exportable) $Doc->ExportField($this->idTransporte);
						if ($this->idVendedor->Exportable) $Doc->ExportField($this->idVendedor);
						if ($this->idCobrador->Exportable) $Doc->ExportField($this->idCobrador);
						if ($this->comision->Exportable) $Doc->ExportField($this->comision);
						if ($this->idListaPrecios->Exportable) $Doc->ExportField($this->idListaPrecios);
						if ($this->dtoCliente->Exportable) $Doc->ExportField($this->dtoCliente);
						if ($this->dto1->Exportable) $Doc->ExportField($this->dto1);
						if ($this->dto2->Exportable) $Doc->ExportField($this->dto2);
						if ($this->dto3->Exportable) $Doc->ExportField($this->dto3);
						if ($this->limiteDescubierto->Exportable) $Doc->ExportField($this->limiteDescubierto);
						if ($this->codigoPostal->Exportable) $Doc->ExportField($this->codigoPostal);
						if ($this->codigoPostalFiscal->Exportable) $Doc->ExportField($this->codigoPostalFiscal);
						if ($this->condicionVenta->Exportable) $Doc->ExportField($this->condicionVenta);
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
			if ($rsold["dto1"]!=$rsnew["dto1"] || $rsold["dto2"]!=$rsnew["dto2"] || $rsold["dto3"]!=$rsnew["dto3"]) {
				$sql="SELECT
				`articulos-proveedores`.id,
				`articulos-proveedores`.idArticulo,
				`articulos-proveedores`.precio,
				monedas.cotizacion
				FROM `articulos-proveedores`
				INNER JOIN monedas
				ON `articulos-proveedores`.idMoneda = monedas.id
				WHERE idTercero = '".$rsold["id"]."'
				AND dto1 IS NULL
				AND dto2 IS NULL
				AND dto3 IS NULL";
				$rs = ew_LoadRecordset($sql);
				$rows = $rs->GetRows();
				if (count($rows)>0) {
					include_once("funciones.php");
					$ids=array();
					$resultado=array();
					for ($i=0; $i < count($rows); $i++) {
						$precio=$rows[$i]["precio"]; 
						if ($rsnew["dto1"]!=NULL && $rsnew["dto1"]!=0) {
							$precio=$precio-($precio*$rsnew["dto1"]/100);
						}
						if ($rsnew["dto2"]!=NULL && $rsnew["dto2"]!=0) {
							$precio=$precio-($precio*$rsnew["dto2"]/100);
						}
						if ($rsnew["dto3"]!=NULL && $rsnew["dto3"]!=0) {
							$precio=$precio-($precio*$rsnew["dto3"]/100);
						}
						$precio=$precio*$rows[$i]["cotizacion"];
						$sql="UPDATE
						`articulos-proveedores`
						SET
						precioPesos='".$precio."',
						ultimaActualizacion='".date("Y-m-d")."'
						WHERE
						id='".$rows[$i]["id"]."'
						AND idTercero='".$rsold["id"]."'
						AND dto1 IS NULL
						AND dto2 IS NULL
						AND dto3 IS NULL";
						ew_Execute($sql);
						array_push($ids, $rows[$i]["idArticulo"]);
					}
					actualizarPrecio($ids);
				}
			}
			return TRUE;
		}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {
		include_once('./funciones.php');
		clonarDescuentos($rsold["id"], 'all' , 'tercero');	

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

		//echo "Row Deleted";
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
		if(!isset($this->PageID)) return;
		if(($this->PageID == "list") || ($this->PageID == "view") || ($this->PageID == "delete"))
		{
			$this->documento->ViewValue = $this->tipoDoc->ViewValue." ".$this->documento->ViewValue;

			//$this->direccion->ViewValue = ucwords(mb_strtolower($this->calle->ViewValue, 'UTF-8'))." ".$this->direccion->ViewValue." - ".ucwords(mb_strtolower($this->idLocalidad->ViewValue, 'UTF-8'))." - ".ucwords(mb_strtolower($this->idPartido->ViewValue, 'UTF-8'))." - ".ucwords(mb_strtolower($this->idProvincia->ViewValue, 'UTF-8'))." - ".ucwords(mb_strtolower($this->idPais->ViewValue, 'UTF-8'));		
			$direccion = "";
				if($this->calle->CurrentValue != NULL)
					$direccion .= strtolower($this->calle->CurrentValue).", ";
				if($this->direccion->CurrentValue != NULL)
					$direccion .= strtolower($this->direccion->CurrentValue).", ";
				if($this->idLocalidad->CurrentValue != NULL)
					$direccion .= strtolower($this->idLocalidad->ViewValue).", ";
				if($this->idPartido->CurrentValue != NULL)
					$direccion .= strtolower($this->idPartido->ViewValue).", ";
				if($this->idProvincia->CurrentValue != NULL)
					$direccion .= strtolower($this->idProvincia->ViewValue);

				//Capitalizo la direccion
				$direccion = ucwords($direccion);

				//Elimino la ultima coma y espacio de la direccion si es esa la ultima palabra
				if(substr($direccion, -2) == ", ")
					$direccion = substr($direccion, 0, -2);
			$this->direccion->ViewValue = $direccion;
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
