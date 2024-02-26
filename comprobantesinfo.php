<?php

// Global variable for table object
$comprobantes = NULL;

//
// Table class for comprobantes
//
class ccomprobantes extends cTable {
	var $id;
	var $denominacion;
	var $denominacionCorta;
	var $discriminaIVA;
	var $seAutoriza;
	var $letra;
	var $seanula;
	var $contracomprobante;
	var $comportamiento;
	var $activo;
	var $ventaStock;
	var $ventaStockReservadoVenta;
	var $ventaStockReservadoCompra;
	var $compraStock;
	var $compraStockReservadoVenta;
	var $compraStockReservadoCompra;
	var $muestraPendientes;
	var $impresion;
	var $comprobanteDefaultImportacion;
	var $preimpreso;
	var $configuracionImpresion;
	var $configuracionImpresionCompra;
	var $cantidadRegistros;
	var $limitarModo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'comprobantes';
		$this->TableName = 'comprobantes';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`comprobantes`";
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
		$this->id = new cField('comprobantes', 'comprobantes', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// denominacion
		$this->denominacion = new cField('comprobantes', 'comprobantes', 'x_denominacion', 'denominacion', '`denominacion`', '`denominacion`', 200, -1, FALSE, '`denominacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacion->Sortable = TRUE; // Allow sort
		$this->fields['denominacion'] = &$this->denominacion;

		// denominacionCorta
		$this->denominacionCorta = new cField('comprobantes', 'comprobantes', 'x_denominacionCorta', 'denominacionCorta', '`denominacionCorta`', '`denominacionCorta`', 200, -1, FALSE, '`denominacionCorta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacionCorta->Sortable = TRUE; // Allow sort
		$this->fields['denominacionCorta'] = &$this->denominacionCorta;

		// discriminaIVA
		$this->discriminaIVA = new cField('comprobantes', 'comprobantes', 'x_discriminaIVA', 'discriminaIVA', '`discriminaIVA`', '`discriminaIVA`', 3, -1, FALSE, '`discriminaIVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->discriminaIVA->Sortable = TRUE; // Allow sort
		$this->discriminaIVA->OptionCount = 1;
		$this->discriminaIVA->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['discriminaIVA'] = &$this->discriminaIVA;

		// seAutoriza
		$this->seAutoriza = new cField('comprobantes', 'comprobantes', 'x_seAutoriza', 'seAutoriza', '`seAutoriza`', '`seAutoriza`', 3, -1, FALSE, '`seAutoriza`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->seAutoriza->Sortable = TRUE; // Allow sort
		$this->seAutoriza->OptionCount = 1;
		$this->seAutoriza->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['seAutoriza'] = &$this->seAutoriza;

		// letra
		$this->letra = new cField('comprobantes', 'comprobantes', 'x_letra', 'letra', '`letra`', '`letra`', 200, -1, FALSE, '`letra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->letra->Sortable = TRUE; // Allow sort
		$this->fields['letra'] = &$this->letra;

		// seanula
		$this->seanula = new cField('comprobantes', 'comprobantes', 'x_seanula', 'seanula', '`seanula`', '`seanula`', 3, -1, FALSE, '`seanula`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->seanula->Sortable = FALSE; // Allow sort
		$this->seanula->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['seanula'] = &$this->seanula;

		// contracomprobante
		$this->contracomprobante = new cField('comprobantes', 'comprobantes', 'x_contracomprobante', 'contracomprobante', '`contracomprobante`', '`contracomprobante`', 3, -1, FALSE, '`contracomprobante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->contracomprobante->Sortable = FALSE; // Allow sort
		$this->contracomprobante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['contracomprobante'] = &$this->contracomprobante;

		// comportamiento
		$this->comportamiento = new cField('comprobantes', 'comprobantes', 'x_comportamiento', 'comportamiento', '`comportamiento`', '`comportamiento`', 3, -1, FALSE, '`comportamiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->comportamiento->Sortable = FALSE; // Allow sort
		$this->comportamiento->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->comportamiento->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->comportamiento->OptionCount = 2;
		$this->comportamiento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['comportamiento'] = &$this->comportamiento;

		// activo
		$this->activo = new cField('comprobantes', 'comprobantes', 'x_activo', 'activo', '`activo`', '`activo`', 3, -1, FALSE, '`activo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->activo->Sortable = TRUE; // Allow sort
		$this->activo->OptionCount = 1;
		$this->activo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activo'] = &$this->activo;

		// ventaStock
		$this->ventaStock = new cField('comprobantes', 'comprobantes', 'x_ventaStock', 'ventaStock', '`ventaStock`', '`ventaStock`', 3, -1, FALSE, '`ventaStock`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->ventaStock->Sortable = TRUE; // Allow sort
		$this->ventaStock->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->ventaStock->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->ventaStock->OptionCount = 2;
		$this->ventaStock->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ventaStock'] = &$this->ventaStock;

		// ventaStockReservadoVenta
		$this->ventaStockReservadoVenta = new cField('comprobantes', 'comprobantes', 'x_ventaStockReservadoVenta', 'ventaStockReservadoVenta', '`ventaStockReservadoVenta`', '`ventaStockReservadoVenta`', 3, -1, FALSE, '`ventaStockReservadoVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->ventaStockReservadoVenta->Sortable = TRUE; // Allow sort
		$this->ventaStockReservadoVenta->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->ventaStockReservadoVenta->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->ventaStockReservadoVenta->OptionCount = 2;
		$this->ventaStockReservadoVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ventaStockReservadoVenta'] = &$this->ventaStockReservadoVenta;

		// ventaStockReservadoCompra
		$this->ventaStockReservadoCompra = new cField('comprobantes', 'comprobantes', 'x_ventaStockReservadoCompra', 'ventaStockReservadoCompra', '`ventaStockReservadoCompra`', '`ventaStockReservadoCompra`', 3, -1, FALSE, '`ventaStockReservadoCompra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->ventaStockReservadoCompra->Sortable = TRUE; // Allow sort
		$this->ventaStockReservadoCompra->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->ventaStockReservadoCompra->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->ventaStockReservadoCompra->OptionCount = 2;
		$this->ventaStockReservadoCompra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ventaStockReservadoCompra'] = &$this->ventaStockReservadoCompra;

		// compraStock
		$this->compraStock = new cField('comprobantes', 'comprobantes', 'x_compraStock', 'compraStock', '`compraStock`', '`compraStock`', 3, -1, FALSE, '`compraStock`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->compraStock->Sortable = TRUE; // Allow sort
		$this->compraStock->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->compraStock->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->compraStock->OptionCount = 2;
		$this->compraStock->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['compraStock'] = &$this->compraStock;

		// compraStockReservadoVenta
		$this->compraStockReservadoVenta = new cField('comprobantes', 'comprobantes', 'x_compraStockReservadoVenta', 'compraStockReservadoVenta', '`compraStockReservadoVenta`', '`compraStockReservadoVenta`', 3, -1, FALSE, '`compraStockReservadoVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->compraStockReservadoVenta->Sortable = TRUE; // Allow sort
		$this->compraStockReservadoVenta->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->compraStockReservadoVenta->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->compraStockReservadoVenta->OptionCount = 2;
		$this->compraStockReservadoVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['compraStockReservadoVenta'] = &$this->compraStockReservadoVenta;

		// compraStockReservadoCompra
		$this->compraStockReservadoCompra = new cField('comprobantes', 'comprobantes', 'x_compraStockReservadoCompra', 'compraStockReservadoCompra', '`compraStockReservadoCompra`', '`compraStockReservadoCompra`', 3, -1, FALSE, '`compraStockReservadoCompra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->compraStockReservadoCompra->Sortable = TRUE; // Allow sort
		$this->compraStockReservadoCompra->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->compraStockReservadoCompra->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->compraStockReservadoCompra->OptionCount = 2;
		$this->compraStockReservadoCompra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['compraStockReservadoCompra'] = &$this->compraStockReservadoCompra;

		// muestraPendientes
		$this->muestraPendientes = new cField('comprobantes', 'comprobantes', 'x_muestraPendientes', 'muestraPendientes', '`muestraPendientes`', '`muestraPendientes`', 19, -1, FALSE, '`muestraPendientes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->muestraPendientes->Sortable = TRUE; // Allow sort
		$this->muestraPendientes->OptionCount = 1;
		$this->muestraPendientes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['muestraPendientes'] = &$this->muestraPendientes;

		// impresion
		$this->impresion = new cField('comprobantes', 'comprobantes', 'x_impresion', 'impresion', '`impresion`', '`impresion`', 200, -1, FALSE, '`impresion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->impresion->Sortable = FALSE; // Allow sort
		$this->fields['impresion'] = &$this->impresion;

		// comprobanteDefaultImportacion
		$this->comprobanteDefaultImportacion = new cField('comprobantes', 'comprobantes', 'x_comprobanteDefaultImportacion', 'comprobanteDefaultImportacion', '`comprobanteDefaultImportacion`', '`comprobanteDefaultImportacion`', 19, -1, FALSE, '`comprobanteDefaultImportacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->comprobanteDefaultImportacion->Sortable = TRUE; // Allow sort
		$this->comprobanteDefaultImportacion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->comprobanteDefaultImportacion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->comprobanteDefaultImportacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['comprobanteDefaultImportacion'] = &$this->comprobanteDefaultImportacion;

		// preimpreso
		$this->preimpreso = new cField('comprobantes', 'comprobantes', 'x_preimpreso', 'preimpreso', '`preimpreso`', '`preimpreso`', 200, -1, TRUE, '`preimpreso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->preimpreso->Sortable = TRUE; // Allow sort
		$this->fields['preimpreso'] = &$this->preimpreso;

		// configuracionImpresion
		$this->configuracionImpresion = new cField('comprobantes', 'comprobantes', 'x_configuracionImpresion', 'configuracionImpresion', '`configuracionImpresion`', '`configuracionImpresion`', 201, -1, FALSE, '`configuracionImpresion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->configuracionImpresion->Sortable = TRUE; // Allow sort
		$this->fields['configuracionImpresion'] = &$this->configuracionImpresion;

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra = new cField('comprobantes', 'comprobantes', 'x_configuracionImpresionCompra', 'configuracionImpresionCompra', '`configuracionImpresionCompra`', '`configuracionImpresionCompra`', 201, -1, FALSE, '`configuracionImpresionCompra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->configuracionImpresionCompra->Sortable = TRUE; // Allow sort
		$this->fields['configuracionImpresionCompra'] = &$this->configuracionImpresionCompra;

		// cantidadRegistros
		$this->cantidadRegistros = new cField('comprobantes', 'comprobantes', 'x_cantidadRegistros', 'cantidadRegistros', '`cantidadRegistros`', '`cantidadRegistros`', 19, -1, FALSE, '`cantidadRegistros`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cantidadRegistros->Sortable = TRUE; // Allow sort
		$this->cantidadRegistros->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cantidadRegistros'] = &$this->cantidadRegistros;

		// limitarModo
		$this->limitarModo = new cField('comprobantes', 'comprobantes', 'x_limitarModo', 'limitarModo', '`limitarModo`', '`limitarModo`', 3, -1, FALSE, '`limitarModo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->limitarModo->Sortable = TRUE; // Allow sort
		$this->limitarModo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->limitarModo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->limitarModo->OptionCount = 2;
		$this->limitarModo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['limitarModo'] = &$this->limitarModo;
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
		if ($this->getCurrentDetailTable() == "comprobantes2Dnumeracion") {
			$sDetailUrl = $GLOBALS["comprobantes2Dnumeracion"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "comprobanteslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`comprobantes`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`activo` DESC,`denominacion` ASC";
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
			return "comprobanteslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "comprobanteslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("comprobantesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("comprobantesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "comprobantesadd.php?" . $this->UrlParm($parm);
		else
			$url = "comprobantesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("comprobantesedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("comprobantesedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
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
			$url = $this->KeyUrl("comprobantesadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("comprobantesadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("comprobantesdelete.php", $this->UrlParm());
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
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->denominacionCorta->setDbValue($rs->fields('denominacionCorta'));
		$this->discriminaIVA->setDbValue($rs->fields('discriminaIVA'));
		$this->seAutoriza->setDbValue($rs->fields('seAutoriza'));
		$this->letra->setDbValue($rs->fields('letra'));
		$this->seanula->setDbValue($rs->fields('seanula'));
		$this->contracomprobante->setDbValue($rs->fields('contracomprobante'));
		$this->comportamiento->setDbValue($rs->fields('comportamiento'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->ventaStock->setDbValue($rs->fields('ventaStock'));
		$this->ventaStockReservadoVenta->setDbValue($rs->fields('ventaStockReservadoVenta'));
		$this->ventaStockReservadoCompra->setDbValue($rs->fields('ventaStockReservadoCompra'));
		$this->compraStock->setDbValue($rs->fields('compraStock'));
		$this->compraStockReservadoVenta->setDbValue($rs->fields('compraStockReservadoVenta'));
		$this->compraStockReservadoCompra->setDbValue($rs->fields('compraStockReservadoCompra'));
		$this->muestraPendientes->setDbValue($rs->fields('muestraPendientes'));
		$this->impresion->setDbValue($rs->fields('impresion'));
		$this->comprobanteDefaultImportacion->setDbValue($rs->fields('comprobanteDefaultImportacion'));
		$this->preimpreso->Upload->DbValue = $rs->fields('preimpreso');
		$this->configuracionImpresion->setDbValue($rs->fields('configuracionImpresion'));
		$this->configuracionImpresionCompra->setDbValue($rs->fields('configuracionImpresionCompra'));
		$this->cantidadRegistros->setDbValue($rs->fields('cantidadRegistros'));
		$this->limitarModo->setDbValue($rs->fields('limitarModo'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// denominacion
		// denominacionCorta
		// discriminaIVA
		// seAutoriza
		// letra
		// seanula

		$this->seanula->CellCssStyle = "white-space: nowrap;";

		// contracomprobante
		$this->contracomprobante->CellCssStyle = "white-space: nowrap;";

		// comportamiento
		$this->comportamiento->CellCssStyle = "white-space: nowrap;";

		// activo
		// ventaStock
		// ventaStockReservadoVenta
		// ventaStockReservadoCompra
		// compraStock
		// compraStockReservadoVenta
		// compraStockReservadoCompra
		// muestraPendientes
		// impresion

		$this->impresion->CellCssStyle = "white-space: nowrap;";

		// comprobanteDefaultImportacion
		// preimpreso
		// configuracionImpresion
		// configuracionImpresionCompra
		// cantidadRegistros
		// limitarModo
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// denominacionCorta
		$this->denominacionCorta->ViewValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->ViewCustomAttributes = "";

		// discriminaIVA
		if (strval($this->discriminaIVA->CurrentValue) <> "") {
			$this->discriminaIVA->ViewValue = "";
			$arwrk = explode(",", strval($this->discriminaIVA->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->discriminaIVA->ViewValue .= $this->discriminaIVA->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->discriminaIVA->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->discriminaIVA->ViewValue = NULL;
		}
		$this->discriminaIVA->ViewCustomAttributes = "";

		// seAutoriza
		if (strval($this->seAutoriza->CurrentValue) <> "") {
			$this->seAutoriza->ViewValue = "";
			$arwrk = explode(",", strval($this->seAutoriza->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->seAutoriza->ViewValue .= $this->seAutoriza->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->seAutoriza->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->seAutoriza->ViewValue = NULL;
		}
		$this->seAutoriza->ViewCustomAttributes = "";

		// letra
		$this->letra->ViewValue = $this->letra->CurrentValue;
		$this->letra->ViewCustomAttributes = "";

		// seanula
		$this->seanula->ViewValue = $this->seanula->CurrentValue;
		$this->seanula->ViewCustomAttributes = "";

		// contracomprobante
		$this->contracomprobante->ViewValue = $this->contracomprobante->CurrentValue;
		$this->contracomprobante->ViewCustomAttributes = "";

		// comportamiento
		if (strval($this->comportamiento->CurrentValue) <> "") {
			$this->comportamiento->ViewValue = $this->comportamiento->OptionCaption($this->comportamiento->CurrentValue);
		} else {
			$this->comportamiento->ViewValue = NULL;
		}
		$this->comportamiento->ViewCustomAttributes = "";

		// activo
		if (strval($this->activo->CurrentValue) <> "") {
			$this->activo->ViewValue = "";
			$arwrk = explode(",", strval($this->activo->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->activo->ViewValue .= $this->activo->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->activo->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->activo->ViewValue = NULL;
		}
		$this->activo->ViewCustomAttributes = "";

		// ventaStock
		if (strval($this->ventaStock->CurrentValue) <> "") {
			$this->ventaStock->ViewValue = $this->ventaStock->OptionCaption($this->ventaStock->CurrentValue);
		} else {
			$this->ventaStock->ViewValue = NULL;
		}
		$this->ventaStock->ViewCustomAttributes = "";

		// ventaStockReservadoVenta
		if (strval($this->ventaStockReservadoVenta->CurrentValue) <> "") {
			$this->ventaStockReservadoVenta->ViewValue = $this->ventaStockReservadoVenta->OptionCaption($this->ventaStockReservadoVenta->CurrentValue);
		} else {
			$this->ventaStockReservadoVenta->ViewValue = NULL;
		}
		$this->ventaStockReservadoVenta->ViewCustomAttributes = "";

		// ventaStockReservadoCompra
		if (strval($this->ventaStockReservadoCompra->CurrentValue) <> "") {
			$this->ventaStockReservadoCompra->ViewValue = $this->ventaStockReservadoCompra->OptionCaption($this->ventaStockReservadoCompra->CurrentValue);
		} else {
			$this->ventaStockReservadoCompra->ViewValue = NULL;
		}
		$this->ventaStockReservadoCompra->ViewCustomAttributes = "";

		// compraStock
		if (strval($this->compraStock->CurrentValue) <> "") {
			$this->compraStock->ViewValue = $this->compraStock->OptionCaption($this->compraStock->CurrentValue);
		} else {
			$this->compraStock->ViewValue = NULL;
		}
		$this->compraStock->ViewCustomAttributes = "";

		// compraStockReservadoVenta
		if (strval($this->compraStockReservadoVenta->CurrentValue) <> "") {
			$this->compraStockReservadoVenta->ViewValue = $this->compraStockReservadoVenta->OptionCaption($this->compraStockReservadoVenta->CurrentValue);
		} else {
			$this->compraStockReservadoVenta->ViewValue = NULL;
		}
		$this->compraStockReservadoVenta->ViewCustomAttributes = "";

		// compraStockReservadoCompra
		if (strval($this->compraStockReservadoCompra->CurrentValue) <> "") {
			$this->compraStockReservadoCompra->ViewValue = $this->compraStockReservadoCompra->OptionCaption($this->compraStockReservadoCompra->CurrentValue);
		} else {
			$this->compraStockReservadoCompra->ViewValue = NULL;
		}
		$this->compraStockReservadoCompra->ViewCustomAttributes = "";

		// muestraPendientes
		if (strval($this->muestraPendientes->CurrentValue) <> "") {
			$this->muestraPendientes->ViewValue = "";
			$arwrk = explode(",", strval($this->muestraPendientes->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->muestraPendientes->ViewValue .= $this->muestraPendientes->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->muestraPendientes->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->muestraPendientes->ViewValue = NULL;
		}
		$this->muestraPendientes->ViewCustomAttributes = "";

		// impresion
		$this->impresion->ViewValue = $this->impresion->CurrentValue;
		$this->impresion->ViewCustomAttributes = "";

		// comprobanteDefaultImportacion
		if (strval($this->comprobanteDefaultImportacion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->comprobanteDefaultImportacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
		$sWhereWrk = "";
		$this->comprobanteDefaultImportacion->LookupFilters = array();
		$lookuptblfilter = "`activo` = 1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->comprobanteDefaultImportacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->comprobanteDefaultImportacion->ViewValue = $this->comprobanteDefaultImportacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->comprobanteDefaultImportacion->ViewValue = $this->comprobanteDefaultImportacion->CurrentValue;
			}
		} else {
			$this->comprobanteDefaultImportacion->ViewValue = NULL;
		}
		$this->comprobanteDefaultImportacion->ViewCustomAttributes = "";

		// preimpreso
		if (!ew_Empty($this->preimpreso->Upload->DbValue)) {
			$this->preimpreso->ViewValue = $this->preimpreso->Upload->DbValue;
		} else {
			$this->preimpreso->ViewValue = "";
		}
		$this->preimpreso->ViewCustomAttributes = "";

		// configuracionImpresion
		$this->configuracionImpresion->ViewValue = $this->configuracionImpresion->CurrentValue;
		$this->configuracionImpresion->ViewCustomAttributes = "";

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra->ViewValue = $this->configuracionImpresionCompra->CurrentValue;
		$this->configuracionImpresionCompra->ViewCustomAttributes = "";

		// cantidadRegistros
		$this->cantidadRegistros->ViewValue = $this->cantidadRegistros->CurrentValue;
		$this->cantidadRegistros->ViewCustomAttributes = "";

		// limitarModo
		if (strval($this->limitarModo->CurrentValue) <> "") {
			$this->limitarModo->ViewValue = $this->limitarModo->OptionCaption($this->limitarModo->CurrentValue);
		} else {
			$this->limitarModo->ViewValue = NULL;
		}
		$this->limitarModo->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// denominacion
		$this->denominacion->LinkCustomAttributes = "";
		$this->denominacion->HrefValue = "";
		$this->denominacion->TooltipValue = "";

		// denominacionCorta
		$this->denominacionCorta->LinkCustomAttributes = "";
		$this->denominacionCorta->HrefValue = "";
		$this->denominacionCorta->TooltipValue = "";

		// discriminaIVA
		$this->discriminaIVA->LinkCustomAttributes = "";
		$this->discriminaIVA->HrefValue = "";
		$this->discriminaIVA->TooltipValue = "";

		// seAutoriza
		$this->seAutoriza->LinkCustomAttributes = "";
		$this->seAutoriza->HrefValue = "";
		$this->seAutoriza->TooltipValue = "";

		// letra
		$this->letra->LinkCustomAttributes = "";
		$this->letra->HrefValue = "";
		$this->letra->TooltipValue = "";

		// seanula
		$this->seanula->LinkCustomAttributes = "";
		$this->seanula->HrefValue = "";
		$this->seanula->TooltipValue = "";

		// contracomprobante
		$this->contracomprobante->LinkCustomAttributes = "";
		$this->contracomprobante->HrefValue = "";
		$this->contracomprobante->TooltipValue = "";

		// comportamiento
		$this->comportamiento->LinkCustomAttributes = "";
		$this->comportamiento->HrefValue = "";
		$this->comportamiento->TooltipValue = "";

		// activo
		$this->activo->LinkCustomAttributes = "";
		$this->activo->HrefValue = "";
		$this->activo->TooltipValue = "";

		// ventaStock
		$this->ventaStock->LinkCustomAttributes = "";
		$this->ventaStock->HrefValue = "";
		$this->ventaStock->TooltipValue = "";

		// ventaStockReservadoVenta
		$this->ventaStockReservadoVenta->LinkCustomAttributes = "";
		$this->ventaStockReservadoVenta->HrefValue = "";
		$this->ventaStockReservadoVenta->TooltipValue = "";

		// ventaStockReservadoCompra
		$this->ventaStockReservadoCompra->LinkCustomAttributes = "";
		$this->ventaStockReservadoCompra->HrefValue = "";
		$this->ventaStockReservadoCompra->TooltipValue = "";

		// compraStock
		$this->compraStock->LinkCustomAttributes = "";
		$this->compraStock->HrefValue = "";
		$this->compraStock->TooltipValue = "";

		// compraStockReservadoVenta
		$this->compraStockReservadoVenta->LinkCustomAttributes = "";
		$this->compraStockReservadoVenta->HrefValue = "";
		$this->compraStockReservadoVenta->TooltipValue = "";

		// compraStockReservadoCompra
		$this->compraStockReservadoCompra->LinkCustomAttributes = "";
		$this->compraStockReservadoCompra->HrefValue = "";
		$this->compraStockReservadoCompra->TooltipValue = "";

		// muestraPendientes
		$this->muestraPendientes->LinkCustomAttributes = "";
		$this->muestraPendientes->HrefValue = "";
		$this->muestraPendientes->TooltipValue = "";

		// impresion
		$this->impresion->LinkCustomAttributes = "";
		$this->impresion->HrefValue = "";
		$this->impresion->TooltipValue = "";

		// comprobanteDefaultImportacion
		$this->comprobanteDefaultImportacion->LinkCustomAttributes = "";
		$this->comprobanteDefaultImportacion->HrefValue = "";
		$this->comprobanteDefaultImportacion->TooltipValue = "";

		// preimpreso
		$this->preimpreso->LinkCustomAttributes = "";
		$this->preimpreso->HrefValue = "";
		$this->preimpreso->HrefValue2 = $this->preimpreso->UploadPath . $this->preimpreso->Upload->DbValue;
		$this->preimpreso->TooltipValue = "";

		// configuracionImpresion
		$this->configuracionImpresion->LinkCustomAttributes = "";
		$this->configuracionImpresion->HrefValue = "";
		$this->configuracionImpresion->TooltipValue = "";

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra->LinkCustomAttributes = "";
		$this->configuracionImpresionCompra->HrefValue = "";
		$this->configuracionImpresionCompra->TooltipValue = "";

		// cantidadRegistros
		$this->cantidadRegistros->LinkCustomAttributes = "";
		$this->cantidadRegistros->HrefValue = "";
		$this->cantidadRegistros->TooltipValue = "";

		// limitarModo
		$this->limitarModo->LinkCustomAttributes = "";
		$this->limitarModo->HrefValue = "";
		$this->limitarModo->TooltipValue = "";

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

		// denominacion
		$this->denominacion->EditAttrs["class"] = "form-control";
		$this->denominacion->EditCustomAttributes = "";
		$this->denominacion->EditValue = $this->denominacion->CurrentValue;
		$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

		// denominacionCorta
		$this->denominacionCorta->EditAttrs["class"] = "form-control";
		$this->denominacionCorta->EditCustomAttributes = "";
		$this->denominacionCorta->EditValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->PlaceHolder = ew_RemoveHtml($this->denominacionCorta->FldCaption());

		// discriminaIVA
		$this->discriminaIVA->EditCustomAttributes = "";
		$this->discriminaIVA->EditValue = $this->discriminaIVA->Options(FALSE);

		// seAutoriza
		$this->seAutoriza->EditCustomAttributes = "";
		$this->seAutoriza->EditValue = $this->seAutoriza->Options(FALSE);

		// letra
		$this->letra->EditAttrs["class"] = "form-control";
		$this->letra->EditCustomAttributes = "";
		$this->letra->EditValue = $this->letra->CurrentValue;
		$this->letra->PlaceHolder = ew_RemoveHtml($this->letra->FldCaption());

		// seanula
		$this->seanula->EditAttrs["class"] = "form-control";
		$this->seanula->EditCustomAttributes = "";
		$this->seanula->EditValue = $this->seanula->CurrentValue;
		$this->seanula->PlaceHolder = ew_RemoveHtml($this->seanula->FldCaption());

		// contracomprobante
		$this->contracomprobante->EditAttrs["class"] = "form-control";
		$this->contracomprobante->EditCustomAttributes = "";
		$this->contracomprobante->EditValue = $this->contracomprobante->CurrentValue;
		$this->contracomprobante->PlaceHolder = ew_RemoveHtml($this->contracomprobante->FldCaption());

		// comportamiento
		$this->comportamiento->EditAttrs["class"] = "form-control";
		$this->comportamiento->EditCustomAttributes = "";
		$this->comportamiento->EditValue = $this->comportamiento->Options(TRUE);

		// activo
		$this->activo->EditCustomAttributes = "";
		$this->activo->EditValue = $this->activo->Options(FALSE);

		// ventaStock
		$this->ventaStock->EditAttrs["class"] = "form-control";
		$this->ventaStock->EditCustomAttributes = "";
		$this->ventaStock->EditValue = $this->ventaStock->Options(TRUE);

		// ventaStockReservadoVenta
		$this->ventaStockReservadoVenta->EditAttrs["class"] = "form-control";
		$this->ventaStockReservadoVenta->EditCustomAttributes = "";
		$this->ventaStockReservadoVenta->EditValue = $this->ventaStockReservadoVenta->Options(TRUE);

		// ventaStockReservadoCompra
		$this->ventaStockReservadoCompra->EditAttrs["class"] = "form-control";
		$this->ventaStockReservadoCompra->EditCustomAttributes = "";
		$this->ventaStockReservadoCompra->EditValue = $this->ventaStockReservadoCompra->Options(TRUE);

		// compraStock
		$this->compraStock->EditAttrs["class"] = "form-control";
		$this->compraStock->EditCustomAttributes = "";
		$this->compraStock->EditValue = $this->compraStock->Options(TRUE);

		// compraStockReservadoVenta
		$this->compraStockReservadoVenta->EditAttrs["class"] = "form-control";
		$this->compraStockReservadoVenta->EditCustomAttributes = "";
		$this->compraStockReservadoVenta->EditValue = $this->compraStockReservadoVenta->Options(TRUE);

		// compraStockReservadoCompra
		$this->compraStockReservadoCompra->EditAttrs["class"] = "form-control";
		$this->compraStockReservadoCompra->EditCustomAttributes = "";
		$this->compraStockReservadoCompra->EditValue = $this->compraStockReservadoCompra->Options(TRUE);

		// muestraPendientes
		$this->muestraPendientes->EditCustomAttributes = "";
		$this->muestraPendientes->EditValue = $this->muestraPendientes->Options(FALSE);

		// impresion
		$this->impresion->EditAttrs["class"] = "form-control";
		$this->impresion->EditCustomAttributes = "";
		$this->impresion->EditValue = $this->impresion->CurrentValue;
		$this->impresion->PlaceHolder = ew_RemoveHtml($this->impresion->FldCaption());

		// comprobanteDefaultImportacion
		$this->comprobanteDefaultImportacion->EditAttrs["class"] = "form-control";
		$this->comprobanteDefaultImportacion->EditCustomAttributes = "";

		// preimpreso
		$this->preimpreso->EditAttrs["class"] = "form-control";
		$this->preimpreso->EditCustomAttributes = "";
		if (!ew_Empty($this->preimpreso->Upload->DbValue)) {
			$this->preimpreso->EditValue = $this->preimpreso->Upload->DbValue;
		} else {
			$this->preimpreso->EditValue = "";
		}
		if (!ew_Empty($this->preimpreso->CurrentValue))
			$this->preimpreso->Upload->FileName = $this->preimpreso->CurrentValue;

		// configuracionImpresion
		$this->configuracionImpresion->EditAttrs["class"] = "form-control";
		$this->configuracionImpresion->EditCustomAttributes = "";
		$this->configuracionImpresion->EditValue = $this->configuracionImpresion->CurrentValue;
		$this->configuracionImpresion->PlaceHolder = ew_RemoveHtml($this->configuracionImpresion->FldCaption());

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra->EditAttrs["class"] = "form-control";
		$this->configuracionImpresionCompra->EditCustomAttributes = "";
		$this->configuracionImpresionCompra->EditValue = $this->configuracionImpresionCompra->CurrentValue;
		$this->configuracionImpresionCompra->PlaceHolder = ew_RemoveHtml($this->configuracionImpresionCompra->FldCaption());

		// cantidadRegistros
		$this->cantidadRegistros->EditAttrs["class"] = "form-control";
		$this->cantidadRegistros->EditCustomAttributes = "";
		$this->cantidadRegistros->EditValue = $this->cantidadRegistros->CurrentValue;
		$this->cantidadRegistros->PlaceHolder = ew_RemoveHtml($this->cantidadRegistros->FldCaption());

		// limitarModo
		$this->limitarModo->EditAttrs["class"] = "form-control";
		$this->limitarModo->EditCustomAttributes = "";
		$this->limitarModo->EditValue = $this->limitarModo->Options(TRUE);

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
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->denominacionCorta->Exportable) $Doc->ExportCaption($this->denominacionCorta);
					if ($this->discriminaIVA->Exportable) $Doc->ExportCaption($this->discriminaIVA);
					if ($this->seAutoriza->Exportable) $Doc->ExportCaption($this->seAutoriza);
					if ($this->letra->Exportable) $Doc->ExportCaption($this->letra);
					if ($this->activo->Exportable) $Doc->ExportCaption($this->activo);
					if ($this->ventaStock->Exportable) $Doc->ExportCaption($this->ventaStock);
					if ($this->ventaStockReservadoVenta->Exportable) $Doc->ExportCaption($this->ventaStockReservadoVenta);
					if ($this->ventaStockReservadoCompra->Exportable) $Doc->ExportCaption($this->ventaStockReservadoCompra);
					if ($this->compraStock->Exportable) $Doc->ExportCaption($this->compraStock);
					if ($this->compraStockReservadoVenta->Exportable) $Doc->ExportCaption($this->compraStockReservadoVenta);
					if ($this->compraStockReservadoCompra->Exportable) $Doc->ExportCaption($this->compraStockReservadoCompra);
					if ($this->muestraPendientes->Exportable) $Doc->ExportCaption($this->muestraPendientes);
					if ($this->comprobanteDefaultImportacion->Exportable) $Doc->ExportCaption($this->comprobanteDefaultImportacion);
					if ($this->preimpreso->Exportable) $Doc->ExportCaption($this->preimpreso);
					if ($this->configuracionImpresion->Exportable) $Doc->ExportCaption($this->configuracionImpresion);
					if ($this->configuracionImpresionCompra->Exportable) $Doc->ExportCaption($this->configuracionImpresionCompra);
					if ($this->cantidadRegistros->Exportable) $Doc->ExportCaption($this->cantidadRegistros);
					if ($this->limitarModo->Exportable) $Doc->ExportCaption($this->limitarModo);
				} else {
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->denominacionCorta->Exportable) $Doc->ExportCaption($this->denominacionCorta);
					if ($this->discriminaIVA->Exportable) $Doc->ExportCaption($this->discriminaIVA);
					if ($this->seAutoriza->Exportable) $Doc->ExportCaption($this->seAutoriza);
					if ($this->letra->Exportable) $Doc->ExportCaption($this->letra);
					if ($this->activo->Exportable) $Doc->ExportCaption($this->activo);
					if ($this->ventaStock->Exportable) $Doc->ExportCaption($this->ventaStock);
					if ($this->ventaStockReservadoVenta->Exportable) $Doc->ExportCaption($this->ventaStockReservadoVenta);
					if ($this->ventaStockReservadoCompra->Exportable) $Doc->ExportCaption($this->ventaStockReservadoCompra);
					if ($this->compraStock->Exportable) $Doc->ExportCaption($this->compraStock);
					if ($this->compraStockReservadoVenta->Exportable) $Doc->ExportCaption($this->compraStockReservadoVenta);
					if ($this->compraStockReservadoCompra->Exportable) $Doc->ExportCaption($this->compraStockReservadoCompra);
					if ($this->muestraPendientes->Exportable) $Doc->ExportCaption($this->muestraPendientes);
					if ($this->comprobanteDefaultImportacion->Exportable) $Doc->ExportCaption($this->comprobanteDefaultImportacion);
					if ($this->preimpreso->Exportable) $Doc->ExportCaption($this->preimpreso);
					if ($this->cantidadRegistros->Exportable) $Doc->ExportCaption($this->cantidadRegistros);
					if ($this->limitarModo->Exportable) $Doc->ExportCaption($this->limitarModo);
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
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->denominacionCorta->Exportable) $Doc->ExportField($this->denominacionCorta);
						if ($this->discriminaIVA->Exportable) $Doc->ExportField($this->discriminaIVA);
						if ($this->seAutoriza->Exportable) $Doc->ExportField($this->seAutoriza);
						if ($this->letra->Exportable) $Doc->ExportField($this->letra);
						if ($this->activo->Exportable) $Doc->ExportField($this->activo);
						if ($this->ventaStock->Exportable) $Doc->ExportField($this->ventaStock);
						if ($this->ventaStockReservadoVenta->Exportable) $Doc->ExportField($this->ventaStockReservadoVenta);
						if ($this->ventaStockReservadoCompra->Exportable) $Doc->ExportField($this->ventaStockReservadoCompra);
						if ($this->compraStock->Exportable) $Doc->ExportField($this->compraStock);
						if ($this->compraStockReservadoVenta->Exportable) $Doc->ExportField($this->compraStockReservadoVenta);
						if ($this->compraStockReservadoCompra->Exportable) $Doc->ExportField($this->compraStockReservadoCompra);
						if ($this->muestraPendientes->Exportable) $Doc->ExportField($this->muestraPendientes);
						if ($this->comprobanteDefaultImportacion->Exportable) $Doc->ExportField($this->comprobanteDefaultImportacion);
						if ($this->preimpreso->Exportable) $Doc->ExportField($this->preimpreso);
						if ($this->configuracionImpresion->Exportable) $Doc->ExportField($this->configuracionImpresion);
						if ($this->configuracionImpresionCompra->Exportable) $Doc->ExportField($this->configuracionImpresionCompra);
						if ($this->cantidadRegistros->Exportable) $Doc->ExportField($this->cantidadRegistros);
						if ($this->limitarModo->Exportable) $Doc->ExportField($this->limitarModo);
					} else {
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->denominacionCorta->Exportable) $Doc->ExportField($this->denominacionCorta);
						if ($this->discriminaIVA->Exportable) $Doc->ExportField($this->discriminaIVA);
						if ($this->seAutoriza->Exportable) $Doc->ExportField($this->seAutoriza);
						if ($this->letra->Exportable) $Doc->ExportField($this->letra);
						if ($this->activo->Exportable) $Doc->ExportField($this->activo);
						if ($this->ventaStock->Exportable) $Doc->ExportField($this->ventaStock);
						if ($this->ventaStockReservadoVenta->Exportable) $Doc->ExportField($this->ventaStockReservadoVenta);
						if ($this->ventaStockReservadoCompra->Exportable) $Doc->ExportField($this->ventaStockReservadoCompra);
						if ($this->compraStock->Exportable) $Doc->ExportField($this->compraStock);
						if ($this->compraStockReservadoVenta->Exportable) $Doc->ExportField($this->compraStockReservadoVenta);
						if ($this->compraStockReservadoCompra->Exportable) $Doc->ExportField($this->compraStockReservadoCompra);
						if ($this->muestraPendientes->Exportable) $Doc->ExportField($this->muestraPendientes);
						if ($this->comprobanteDefaultImportacion->Exportable) $Doc->ExportField($this->comprobanteDefaultImportacion);
						if ($this->preimpreso->Exportable) $Doc->ExportField($this->preimpreso);
						if ($this->cantidadRegistros->Exportable) $Doc->ExportField($this->cantidadRegistros);
						if ($this->limitarModo->Exportable) $Doc->ExportField($this->limitarModo);
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

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
