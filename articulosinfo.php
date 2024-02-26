<?php

// Global variable for table object
$articulos = NULL;

//
// Table class for articulos
//
class carticulos extends cTable {
	var $id;
	var $denominacionExterna;
	var $denominacionInterna;
	var $idAlicuotaIva;
	var $idCategoria;
	var $idSubcateogoria;
	var $idRubro;
	var $idMarca;
	var $fabricante;
	var $codigoBarras;
	var $imagenes;
	var $idPrecioCompra;
	var $proveedor;
	var $calculoPrecio;
	var $rentabilidad;
	var $precioVenta;
	var $tags;
	var $detalle;
	var $idUnidadMedidaCompra;
	var $idUnidadMedidaVenta;
	var $codigosExternos;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'articulos';
		$this->TableName = 'articulos';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`articulos`";
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
		$this->id = new cField('articulos', 'articulos', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// denominacionExterna
		$this->denominacionExterna = new cField('articulos', 'articulos', 'x_denominacionExterna', 'denominacionExterna', '`denominacionExterna`', '`denominacionExterna`', 200, -1, FALSE, '`denominacionExterna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacionExterna->Sortable = TRUE; // Allow sort
		$this->fields['denominacionExterna'] = &$this->denominacionExterna;

		// denominacionInterna
		$this->denominacionInterna = new cField('articulos', 'articulos', 'x_denominacionInterna', 'denominacionInterna', '`denominacionInterna`', '`denominacionInterna`', 200, -1, FALSE, '`denominacionInterna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacionInterna->Sortable = TRUE; // Allow sort
		$this->fields['denominacionInterna'] = &$this->denominacionInterna;

		// idAlicuotaIva
		$this->idAlicuotaIva = new cField('articulos', 'articulos', 'x_idAlicuotaIva', 'idAlicuotaIva', '`idAlicuotaIva`', '`idAlicuotaIva`', 3, -1, FALSE, '`idAlicuotaIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idAlicuotaIva->Sortable = TRUE; // Allow sort
		$this->idAlicuotaIva->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idAlicuotaIva->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idAlicuotaIva->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idAlicuotaIva'] = &$this->idAlicuotaIva;

		// idCategoria
		$this->idCategoria = new cField('articulos', 'articulos', 'x_idCategoria', 'idCategoria', '`idCategoria`', '`idCategoria`', 3, -1, FALSE, '`idCategoria`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idCategoria->Sortable = TRUE; // Allow sort
		$this->idCategoria->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idCategoria->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idCategoria->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idCategoria'] = &$this->idCategoria;

		// idSubcateogoria
		$this->idSubcateogoria = new cField('articulos', 'articulos', 'x_idSubcateogoria', 'idSubcateogoria', '`idSubcateogoria`', '`idSubcateogoria`', 3, -1, FALSE, '`idSubcateogoria`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idSubcateogoria->Sortable = TRUE; // Allow sort
		$this->idSubcateogoria->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idSubcateogoria->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idSubcateogoria->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idSubcateogoria'] = &$this->idSubcateogoria;

		// idRubro
		$this->idRubro = new cField('articulos', 'articulos', 'x_idRubro', 'idRubro', '`idRubro`', '`idRubro`', 3, -1, FALSE, '`idRubro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idRubro->Sortable = TRUE; // Allow sort
		$this->idRubro->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idRubro->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idRubro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idRubro'] = &$this->idRubro;

		// idMarca
		$this->idMarca = new cField('articulos', 'articulos', 'x_idMarca', 'idMarca', '`idMarca`', '`idMarca`', 3, -1, FALSE, '`idMarca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idMarca->Sortable = TRUE; // Allow sort
		$this->idMarca->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idMarca->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idMarca->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idMarca'] = &$this->idMarca;

		// fabricante
		$this->fabricante = new cField('articulos', 'articulos', 'x_fabricante', 'fabricante', '`fabricante`', '`fabricante`', 200, -1, FALSE, '`fabricante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fabricante->Sortable = FALSE; // Allow sort
		$this->fields['fabricante'] = &$this->fabricante;

		// codigoBarras
		$this->codigoBarras = new cField('articulos', 'articulos', 'x_codigoBarras', 'codigoBarras', '`codigoBarras`', '`codigoBarras`', 200, -1, FALSE, '`codigoBarras`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigoBarras->Sortable = TRUE; // Allow sort
		$this->fields['codigoBarras'] = &$this->codigoBarras;

		// imagenes
		$this->imagenes = new cField('articulos', 'articulos', 'x_imagenes', 'imagenes', '`imagenes`', '`imagenes`', 201, -1, TRUE, '`imagenes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->imagenes->Sortable = TRUE; // Allow sort
		$this->imagenes->UploadMultiple = TRUE;
		$this->imagenes->Upload->UploadMultiple = TRUE;
		$this->imagenes->UploadMaxFileCount = 0;
		$this->fields['imagenes'] = &$this->imagenes;

		// idPrecioCompra
		$this->idPrecioCompra = new cField('articulos', 'articulos', 'x_idPrecioCompra', 'idPrecioCompra', '`idPrecioCompra`', '`idPrecioCompra`', 3, -1, FALSE, '`idPrecioCompra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPrecioCompra->Sortable = TRUE; // Allow sort
		$this->idPrecioCompra->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPrecioCompra->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPrecioCompra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPrecioCompra'] = &$this->idPrecioCompra;

		// proveedor
		$this->proveedor = new cField('articulos', 'articulos', 'x_proveedor', 'proveedor', '(SELECT idTercero FROM `articulos-proveedores` AS proveedor WHERE id = `idPrecioCompra`)', '(SELECT idTercero FROM `articulos-proveedores` AS proveedor WHERE id = `idPrecioCompra`)', 3, -1, FALSE, '(SELECT idTercero FROM `articulos-proveedores` AS proveedor WHERE id = `idPrecioCompra`)', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->proveedor->FldIsCustom = TRUE; // Custom field
		$this->proveedor->Sortable = TRUE; // Allow sort
		$this->proveedor->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->proveedor->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->proveedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['proveedor'] = &$this->proveedor;

		// calculoPrecio
		$this->calculoPrecio = new cField('articulos', 'articulos', 'x_calculoPrecio', 'calculoPrecio', '`calculoPrecio`', '`calculoPrecio`', 3, -1, FALSE, '`calculoPrecio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->calculoPrecio->Sortable = TRUE; // Allow sort
		$this->calculoPrecio->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->calculoPrecio->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->calculoPrecio->OptionCount = 2;
		$this->calculoPrecio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['calculoPrecio'] = &$this->calculoPrecio;

		// rentabilidad
		$this->rentabilidad = new cField('articulos', 'articulos', 'x_rentabilidad', 'rentabilidad', '`rentabilidad`', '`rentabilidad`', 4, -1, FALSE, '`rentabilidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rentabilidad->Sortable = TRUE; // Allow sort
		$this->rentabilidad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['rentabilidad'] = &$this->rentabilidad;

		// precioVenta
		$this->precioVenta = new cField('articulos', 'articulos', 'x_precioVenta', 'precioVenta', '`precioVenta`', '`precioVenta`', 4, -1, FALSE, '`precioVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->precioVenta->Sortable = TRUE; // Allow sort
		$this->precioVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precioVenta'] = &$this->precioVenta;

		// tags
		$this->tags = new cField('articulos', 'articulos', 'x_tags', 'tags', '`tags`', '`tags`', 201, -1, FALSE, '`tags`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->tags->Sortable = TRUE; // Allow sort
		$this->fields['tags'] = &$this->tags;

		// detalle
		$this->detalle = new cField('articulos', 'articulos', 'x_detalle', 'detalle', '`detalle`', '`detalle`', 201, -1, FALSE, '`detalle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->detalle->Sortable = TRUE; // Allow sort
		$this->fields['detalle'] = &$this->detalle;

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra = new cField('articulos', 'articulos', 'x_idUnidadMedidaCompra', 'idUnidadMedidaCompra', '`idUnidadMedidaCompra`', '`idUnidadMedidaCompra`', 19, -1, FALSE, '`idUnidadMedidaCompra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idUnidadMedidaCompra->Sortable = FALSE; // Allow sort
		$this->idUnidadMedidaCompra->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idUnidadMedidaCompra->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idUnidadMedidaCompra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUnidadMedidaCompra'] = &$this->idUnidadMedidaCompra;

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta = new cField('articulos', 'articulos', 'x_idUnidadMedidaVenta', 'idUnidadMedidaVenta', '`idUnidadMedidaVenta`', '`idUnidadMedidaVenta`', 19, -1, FALSE, '`idUnidadMedidaVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idUnidadMedidaVenta->Sortable = FALSE; // Allow sort
		$this->idUnidadMedidaVenta->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idUnidadMedidaVenta->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idUnidadMedidaVenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUnidadMedidaVenta'] = &$this->idUnidadMedidaVenta;

		// codigosExternos
		$this->codigosExternos = new cField('articulos', 'articulos', 'x_codigosExternos', 'codigosExternos', '`codigosExternos`', '`codigosExternos`', 201, -1, FALSE, '`codigosExternos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->codigosExternos->Sortable = TRUE; // Allow sort
		$this->fields['codigosExternos'] = &$this->codigosExternos;
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
		if ($this->getCurrentDetailTable() == "articulos2Dproveedores") {
			$sDetailUrl = $GLOBALS["articulos2Dproveedores"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "articulos2Dterceros2Ddescuentos") {
			$sDetailUrl = $GLOBALS["articulos2Dterceros2Ddescuentos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "articulos2Dstock") {
			$sDetailUrl = $GLOBALS["articulos2Dstock"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "articuloslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`articulos`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, (SELECT idTercero FROM `articulos-proveedores` AS proveedor WHERE id = `idPrecioCompra`) AS `proveedor` FROM " . $this->getSqlFrom();
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`denominacionExterna` ASC,`denominacionInterna` ASC";
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

		// Cascade Update detail table 'articulos-proveedores'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['id']) && $rsold['id'] <> $rs['id'])) { // Update detail field 'idArticulo'
			$bCascadeUpdate = TRUE;
			$rscascade['idArticulo'] = $rs['id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["articulos2Dproveedores"])) $GLOBALS["articulos2Dproveedores"] = new carticulos2Dproveedores();
			$rswrk = $GLOBALS["articulos2Dproveedores"]->LoadRs("`idArticulo` = " . ew_QuotedValue($rsold['id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["articulos2Dproveedores"]->Update($rscascade, "`idArticulo` = " . ew_QuotedValue($rsold['id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 'articulos-terceros-descuentos'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['id']) && $rsold['id'] <> $rs['id'])) { // Update detail field 'idArticulo'
			$bCascadeUpdate = TRUE;
			$rscascade['idArticulo'] = $rs['id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos"])) $GLOBALS["articulos2Dterceros2Ddescuentos"] = new carticulos2Dterceros2Ddescuentos();
			$rswrk = $GLOBALS["articulos2Dterceros2Ddescuentos"]->LoadRs("`idArticulo` = " . ew_QuotedValue($rsold['id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["articulos2Dterceros2Ddescuentos"]->Update($rscascade, "`idArticulo` = " . ew_QuotedValue($rsold['id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}
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

		// Cascade delete detail table 'articulos-proveedores'
		if (!isset($GLOBALS["articulos2Dproveedores"])) $GLOBALS["articulos2Dproveedores"] = new carticulos2Dproveedores();
		$rscascade = $GLOBALS["articulos2Dproveedores"]->LoadRs("`idArticulo` = " . ew_QuotedValue($rs['id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["articulos2Dproveedores"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 'articulos-terceros-descuentos'
		if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos"])) $GLOBALS["articulos2Dterceros2Ddescuentos"] = new carticulos2Dterceros2Ddescuentos();
		$rscascade = $GLOBALS["articulos2Dterceros2Ddescuentos"]->LoadRs("`idArticulo` = " . ew_QuotedValue($rs['id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["articulos2Dterceros2Ddescuentos"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}
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
			return "articuloslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "articuloslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("articulosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("articulosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "articulosadd.php?" . $this->UrlParm($parm);
		else
			$url = "articulosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("articulosedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("articulosedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
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
			$url = $this->KeyUrl("articulosadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("articulosadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("articulosdelete.php", $this->UrlParm());
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
		$this->denominacionExterna->setDbValue($rs->fields('denominacionExterna'));
		$this->denominacionInterna->setDbValue($rs->fields('denominacionInterna'));
		$this->idAlicuotaIva->setDbValue($rs->fields('idAlicuotaIva'));
		$this->idCategoria->setDbValue($rs->fields('idCategoria'));
		$this->idSubcateogoria->setDbValue($rs->fields('idSubcateogoria'));
		$this->idRubro->setDbValue($rs->fields('idRubro'));
		$this->idMarca->setDbValue($rs->fields('idMarca'));
		$this->fabricante->setDbValue($rs->fields('fabricante'));
		$this->codigoBarras->setDbValue($rs->fields('codigoBarras'));
		$this->imagenes->Upload->DbValue = $rs->fields('imagenes');
		$this->idPrecioCompra->setDbValue($rs->fields('idPrecioCompra'));
		$this->proveedor->setDbValue($rs->fields('proveedor'));
		$this->calculoPrecio->setDbValue($rs->fields('calculoPrecio'));
		$this->rentabilidad->setDbValue($rs->fields('rentabilidad'));
		$this->precioVenta->setDbValue($rs->fields('precioVenta'));
		$this->tags->setDbValue($rs->fields('tags'));
		$this->detalle->setDbValue($rs->fields('detalle'));
		$this->idUnidadMedidaCompra->setDbValue($rs->fields('idUnidadMedidaCompra'));
		$this->idUnidadMedidaVenta->setDbValue($rs->fields('idUnidadMedidaVenta'));
		$this->codigosExternos->setDbValue($rs->fields('codigosExternos'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// denominacionExterna
		// denominacionInterna
		// idAlicuotaIva
		// idCategoria
		// idSubcateogoria
		// idRubro
		// idMarca
		// fabricante

		$this->fabricante->CellCssStyle = "white-space: nowrap;";

		// codigoBarras
		// imagenes
		// idPrecioCompra
		// proveedor
		// calculoPrecio
		// rentabilidad
		// precioVenta
		// tags
		// detalle
		// idUnidadMedidaCompra
		// idUnidadMedidaVenta
		// codigosExternos
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacionExterna
		$this->denominacionExterna->ViewValue = $this->denominacionExterna->CurrentValue;
		$this->denominacionExterna->ViewCustomAttributes = "";

		// denominacionInterna
		$this->denominacionInterna->ViewValue = $this->denominacionInterna->CurrentValue;
		$this->denominacionInterna->ViewCustomAttributes = "";

		// idAlicuotaIva
		if (strval($this->idAlicuotaIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idAlicuotaIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `valor` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `alicuotas-iva`";
		$sWhereWrk = "";
		$this->idAlicuotaIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idAlicuotaIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `valor` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idAlicuotaIva->ViewValue = $this->idAlicuotaIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idAlicuotaIva->ViewValue = $this->idAlicuotaIva->CurrentValue;
			}
		} else {
			$this->idAlicuotaIva->ViewValue = NULL;
		}
		$this->idAlicuotaIva->ViewCustomAttributes = "";

		// idCategoria
		if (strval($this->idCategoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias-articulos`";
		$sWhereWrk = "";
		$this->idCategoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCategoria->ViewValue = $this->idCategoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCategoria->ViewValue = $this->idCategoria->CurrentValue;
			}
		} else {
			$this->idCategoria->ViewValue = NULL;
		}
		$this->idCategoria->ViewCustomAttributes = "";

		// idSubcateogoria
		if (strval($this->idSubcateogoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subcategorias-articulos`";
		$sWhereWrk = "";
		$this->idSubcateogoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->CurrentValue;
			}
		} else {
			$this->idSubcateogoria->ViewValue = NULL;
		}
		$this->idSubcateogoria->ViewCustomAttributes = "";

		// idRubro
		if (strval($this->idRubro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
		$sWhereWrk = "";
		$this->idRubro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idRubro->ViewValue = $this->idRubro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idRubro->ViewValue = $this->idRubro->CurrentValue;
			}
		} else {
			$this->idRubro->ViewValue = NULL;
		}
		$this->idRubro->ViewCustomAttributes = "";

		// idMarca
		if (strval($this->idMarca->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
		$sWhereWrk = "";
		$this->idMarca->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMarca->ViewValue = $this->idMarca->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMarca->ViewValue = $this->idMarca->CurrentValue;
			}
		} else {
			$this->idMarca->ViewValue = NULL;
		}
		$this->idMarca->ViewCustomAttributes = "";

		// fabricante
		$this->fabricante->ViewValue = $this->fabricante->CurrentValue;
		$this->fabricante->ViewCustomAttributes = "";

		// codigoBarras
		$this->codigoBarras->ViewValue = $this->codigoBarras->CurrentValue;
		$this->codigoBarras->ViewCustomAttributes = "";

		// imagenes
		if (!ew_Empty($this->imagenes->Upload->DbValue)) {
			$this->imagenes->ViewValue = $this->imagenes->Upload->DbValue;
		} else {
			$this->imagenes->ViewValue = "";
		}
		$this->imagenes->ViewCustomAttributes = "";

		// idPrecioCompra
		if (strval($this->idPrecioCompra->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `precios-compra`";
		$sWhereWrk = "";
		$this->idPrecioCompra->LookupFilters = array();
		$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = ew_FormatDateTime($rswrk->fields('Disp3Fld'), 0);
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->CurrentValue;
			}
		} else {
			$this->idPrecioCompra->ViewValue = NULL;
		}
		$this->idPrecioCompra->ViewCustomAttributes = "";

		// proveedor
		if (strval($this->proveedor->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->proveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->proveedor->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proveedor->ViewValue = $this->proveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proveedor->ViewValue = $this->proveedor->CurrentValue;
			}
		} else {
			$this->proveedor->ViewValue = NULL;
		}
		$this->proveedor->ViewCustomAttributes = "";

		// calculoPrecio
		if (strval($this->calculoPrecio->CurrentValue) <> "") {
			$this->calculoPrecio->ViewValue = $this->calculoPrecio->OptionCaption($this->calculoPrecio->CurrentValue);
		} else {
			$this->calculoPrecio->ViewValue = NULL;
		}
		$this->calculoPrecio->ViewCustomAttributes = "";

		// rentabilidad
		$this->rentabilidad->ViewValue = $this->rentabilidad->CurrentValue;
		$this->rentabilidad->ViewCustomAttributes = "";

		// precioVenta
		$this->precioVenta->ViewValue = $this->precioVenta->CurrentValue;
		$this->precioVenta->ViewCustomAttributes = "";

		// tags
		$this->tags->ViewValue = $this->tags->CurrentValue;
		$this->tags->ViewCustomAttributes = "";

		// detalle
		$this->detalle->ViewValue = $this->detalle->CurrentValue;
		$this->detalle->ViewCustomAttributes = "";

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->ViewCustomAttributes = "";

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->ViewCustomAttributes = "";

		// codigosExternos
		$this->codigosExternos->ViewValue = $this->codigosExternos->CurrentValue;
		$this->codigosExternos->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// denominacionExterna
		$this->denominacionExterna->LinkCustomAttributes = "";
		$this->denominacionExterna->HrefValue = "";
		$this->denominacionExterna->TooltipValue = "";

		// denominacionInterna
		$this->denominacionInterna->LinkCustomAttributes = "";
		$this->denominacionInterna->HrefValue = "";
		$this->denominacionInterna->TooltipValue = "";

		// idAlicuotaIva
		$this->idAlicuotaIva->LinkCustomAttributes = "";
		$this->idAlicuotaIva->HrefValue = "";
		$this->idAlicuotaIva->TooltipValue = "";

		// idCategoria
		$this->idCategoria->LinkCustomAttributes = "";
		$this->idCategoria->HrefValue = "";
		$this->idCategoria->TooltipValue = "";

		// idSubcateogoria
		$this->idSubcateogoria->LinkCustomAttributes = "";
		$this->idSubcateogoria->HrefValue = "";
		$this->idSubcateogoria->TooltipValue = "";

		// idRubro
		$this->idRubro->LinkCustomAttributes = "";
		$this->idRubro->HrefValue = "";
		$this->idRubro->TooltipValue = "";

		// idMarca
		$this->idMarca->LinkCustomAttributes = "";
		$this->idMarca->HrefValue = "";
		$this->idMarca->TooltipValue = "";

		// fabricante
		$this->fabricante->LinkCustomAttributes = "";
		$this->fabricante->HrefValue = "";
		$this->fabricante->TooltipValue = "";

		// codigoBarras
		$this->codigoBarras->LinkCustomAttributes = "";
		$this->codigoBarras->HrefValue = "";
		$this->codigoBarras->TooltipValue = "";

		// imagenes
		$this->imagenes->LinkCustomAttributes = "";
		$this->imagenes->HrefValue = "";
		$this->imagenes->HrefValue2 = $this->imagenes->UploadPath . $this->imagenes->Upload->DbValue;
		$this->imagenes->TooltipValue = "";

		// idPrecioCompra
		$this->idPrecioCompra->LinkCustomAttributes = "";
		$this->idPrecioCompra->HrefValue = "";
		$this->idPrecioCompra->TooltipValue = "";

		// proveedor
		$this->proveedor->LinkCustomAttributes = "";
		$this->proveedor->HrefValue = "";
		$this->proveedor->TooltipValue = "";

		// calculoPrecio
		$this->calculoPrecio->LinkCustomAttributes = "";
		$this->calculoPrecio->HrefValue = "";
		$this->calculoPrecio->TooltipValue = "";

		// rentabilidad
		$this->rentabilidad->LinkCustomAttributes = "";
		$this->rentabilidad->HrefValue = "";
		$this->rentabilidad->TooltipValue = "";

		// precioVenta
		$this->precioVenta->LinkCustomAttributes = "";
		$this->precioVenta->HrefValue = "";
		$this->precioVenta->TooltipValue = "";

		// tags
		$this->tags->LinkCustomAttributes = "";
		$this->tags->HrefValue = "";
		$this->tags->TooltipValue = "";

		// detalle
		$this->detalle->LinkCustomAttributes = "";
		$this->detalle->HrefValue = "";
		$this->detalle->TooltipValue = "";

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->LinkCustomAttributes = "";
		$this->idUnidadMedidaCompra->HrefValue = "";
		$this->idUnidadMedidaCompra->TooltipValue = "";

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->LinkCustomAttributes = "";
		$this->idUnidadMedidaVenta->HrefValue = "";
		$this->idUnidadMedidaVenta->TooltipValue = "";

		// codigosExternos
		$this->codigosExternos->LinkCustomAttributes = "";
		$this->codigosExternos->HrefValue = "";
		$this->codigosExternos->TooltipValue = "";

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

		// denominacionExterna
		$this->denominacionExterna->EditAttrs["class"] = "form-control";
		$this->denominacionExterna->EditCustomAttributes = "";
		$this->denominacionExterna->EditValue = $this->denominacionExterna->CurrentValue;
		$this->denominacionExterna->PlaceHolder = ew_RemoveHtml($this->denominacionExterna->FldCaption());

		// denominacionInterna
		$this->denominacionInterna->EditAttrs["class"] = "form-control";
		$this->denominacionInterna->EditCustomAttributes = "";
		$this->denominacionInterna->EditValue = $this->denominacionInterna->CurrentValue;
		$this->denominacionInterna->PlaceHolder = ew_RemoveHtml($this->denominacionInterna->FldCaption());

		// idAlicuotaIva
		$this->idAlicuotaIva->EditAttrs["class"] = "form-control";
		$this->idAlicuotaIva->EditCustomAttributes = "";

		// idCategoria
		$this->idCategoria->EditAttrs["class"] = "form-control";
		$this->idCategoria->EditCustomAttributes = 'data-s2="true"';

		// idSubcateogoria
		$this->idSubcateogoria->EditAttrs["class"] = "form-control";
		$this->idSubcateogoria->EditCustomAttributes = 'data-s2="true"';

		// idRubro
		$this->idRubro->EditAttrs["class"] = "form-control";
		$this->idRubro->EditCustomAttributes = 'data-s2="true"';

		// idMarca
		$this->idMarca->EditAttrs["class"] = "form-control";
		$this->idMarca->EditCustomAttributes = 'data-s2="true"';

		// fabricante
		$this->fabricante->EditAttrs["class"] = "form-control";
		$this->fabricante->EditCustomAttributes = "";
		$this->fabricante->EditValue = $this->fabricante->CurrentValue;
		$this->fabricante->PlaceHolder = ew_RemoveHtml($this->fabricante->FldCaption());

		// codigoBarras
		$this->codigoBarras->EditAttrs["class"] = "form-control";
		$this->codigoBarras->EditCustomAttributes = "";
		$this->codigoBarras->EditValue = $this->codigoBarras->CurrentValue;
		$this->codigoBarras->PlaceHolder = ew_RemoveHtml($this->codigoBarras->FldCaption());

		// imagenes
		$this->imagenes->EditAttrs["class"] = "form-control";
		$this->imagenes->EditCustomAttributes = "";
		if (!ew_Empty($this->imagenes->Upload->DbValue)) {
			$this->imagenes->EditValue = $this->imagenes->Upload->DbValue;
		} else {
			$this->imagenes->EditValue = "";
		}
		if (!ew_Empty($this->imagenes->CurrentValue))
			$this->imagenes->Upload->FileName = $this->imagenes->CurrentValue;

		// idPrecioCompra
		$this->idPrecioCompra->EditAttrs["class"] = "form-control";
		$this->idPrecioCompra->EditCustomAttributes = "";

		// proveedor
		$this->proveedor->EditAttrs["class"] = "form-control";
		$this->proveedor->EditCustomAttributes = 'data-s2="true"';

		// calculoPrecio
		$this->calculoPrecio->EditAttrs["class"] = "form-control";
		$this->calculoPrecio->EditCustomAttributes = "";
		$this->calculoPrecio->EditValue = $this->calculoPrecio->Options(TRUE);

		// rentabilidad
		$this->rentabilidad->EditAttrs["class"] = "form-control";
		$this->rentabilidad->EditCustomAttributes = "";
		$this->rentabilidad->EditValue = $this->rentabilidad->CurrentValue;
		$this->rentabilidad->PlaceHolder = ew_RemoveHtml($this->rentabilidad->FldCaption());
		if (strval($this->rentabilidad->EditValue) <> "" && is_numeric($this->rentabilidad->EditValue)) $this->rentabilidad->EditValue = ew_FormatNumber($this->rentabilidad->EditValue, -2, -1, -2, 0);

		// precioVenta
		$this->precioVenta->EditAttrs["class"] = "form-control";
		$this->precioVenta->EditCustomAttributes = "";
		$this->precioVenta->EditValue = $this->precioVenta->CurrentValue;
		$this->precioVenta->PlaceHolder = ew_RemoveHtml($this->precioVenta->FldCaption());
		if (strval($this->precioVenta->EditValue) <> "" && is_numeric($this->precioVenta->EditValue)) $this->precioVenta->EditValue = ew_FormatNumber($this->precioVenta->EditValue, -2, -1, -2, 0);

		// tags
		$this->tags->EditAttrs["class"] = "form-control";
		$this->tags->EditCustomAttributes = "";
		$this->tags->EditValue = $this->tags->CurrentValue;
		$this->tags->PlaceHolder = ew_RemoveHtml($this->tags->FldCaption());

		// detalle
		$this->detalle->EditAttrs["class"] = "form-control";
		$this->detalle->EditCustomAttributes = "";
		$this->detalle->EditValue = $this->detalle->CurrentValue;
		$this->detalle->PlaceHolder = ew_RemoveHtml($this->detalle->FldCaption());

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->EditAttrs["class"] = "form-control";
		$this->idUnidadMedidaCompra->EditCustomAttributes = "";

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->EditAttrs["class"] = "form-control";
		$this->idUnidadMedidaVenta->EditCustomAttributes = "";

		// codigosExternos
		$this->codigosExternos->EditAttrs["class"] = "form-control";
		$this->codigosExternos->EditCustomAttributes = "";
		$this->codigosExternos->EditValue = $this->codigosExternos->CurrentValue;
		$this->codigosExternos->PlaceHolder = ew_RemoveHtml($this->codigosExternos->FldCaption());

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
					if ($this->denominacionExterna->Exportable) $Doc->ExportCaption($this->denominacionExterna);
					if ($this->denominacionInterna->Exportable) $Doc->ExportCaption($this->denominacionInterna);
					if ($this->idAlicuotaIva->Exportable) $Doc->ExportCaption($this->idAlicuotaIva);
					if ($this->idCategoria->Exportable) $Doc->ExportCaption($this->idCategoria);
					if ($this->idSubcateogoria->Exportable) $Doc->ExportCaption($this->idSubcateogoria);
					if ($this->idRubro->Exportable) $Doc->ExportCaption($this->idRubro);
					if ($this->idMarca->Exportable) $Doc->ExportCaption($this->idMarca);
					if ($this->codigoBarras->Exportable) $Doc->ExportCaption($this->codigoBarras);
					if ($this->imagenes->Exportable) $Doc->ExportCaption($this->imagenes);
					if ($this->idPrecioCompra->Exportable) $Doc->ExportCaption($this->idPrecioCompra);
					if ($this->proveedor->Exportable) $Doc->ExportCaption($this->proveedor);
					if ($this->calculoPrecio->Exportable) $Doc->ExportCaption($this->calculoPrecio);
					if ($this->rentabilidad->Exportable) $Doc->ExportCaption($this->rentabilidad);
					if ($this->precioVenta->Exportable) $Doc->ExportCaption($this->precioVenta);
					if ($this->tags->Exportable) $Doc->ExportCaption($this->tags);
					if ($this->detalle->Exportable) $Doc->ExportCaption($this->detalle);
					if ($this->codigosExternos->Exportable) $Doc->ExportCaption($this->codigosExternos);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->denominacionExterna->Exportable) $Doc->ExportCaption($this->denominacionExterna);
					if ($this->denominacionInterna->Exportable) $Doc->ExportCaption($this->denominacionInterna);
					if ($this->idAlicuotaIva->Exportable) $Doc->ExportCaption($this->idAlicuotaIva);
					if ($this->idCategoria->Exportable) $Doc->ExportCaption($this->idCategoria);
					if ($this->idSubcateogoria->Exportable) $Doc->ExportCaption($this->idSubcateogoria);
					if ($this->idRubro->Exportable) $Doc->ExportCaption($this->idRubro);
					if ($this->idMarca->Exportable) $Doc->ExportCaption($this->idMarca);
					if ($this->codigoBarras->Exportable) $Doc->ExportCaption($this->codigoBarras);
					if ($this->idPrecioCompra->Exportable) $Doc->ExportCaption($this->idPrecioCompra);
					if ($this->proveedor->Exportable) $Doc->ExportCaption($this->proveedor);
					if ($this->calculoPrecio->Exportable) $Doc->ExportCaption($this->calculoPrecio);
					if ($this->rentabilidad->Exportable) $Doc->ExportCaption($this->rentabilidad);
					if ($this->precioVenta->Exportable) $Doc->ExportCaption($this->precioVenta);
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
						if ($this->denominacionExterna->Exportable) $Doc->ExportField($this->denominacionExterna);
						if ($this->denominacionInterna->Exportable) $Doc->ExportField($this->denominacionInterna);
						if ($this->idAlicuotaIva->Exportable) $Doc->ExportField($this->idAlicuotaIva);
						if ($this->idCategoria->Exportable) $Doc->ExportField($this->idCategoria);
						if ($this->idSubcateogoria->Exportable) $Doc->ExportField($this->idSubcateogoria);
						if ($this->idRubro->Exportable) $Doc->ExportField($this->idRubro);
						if ($this->idMarca->Exportable) $Doc->ExportField($this->idMarca);
						if ($this->codigoBarras->Exportable) $Doc->ExportField($this->codigoBarras);
						if ($this->imagenes->Exportable) $Doc->ExportField($this->imagenes);
						if ($this->idPrecioCompra->Exportable) $Doc->ExportField($this->idPrecioCompra);
						if ($this->proveedor->Exportable) $Doc->ExportField($this->proveedor);
						if ($this->calculoPrecio->Exportable) $Doc->ExportField($this->calculoPrecio);
						if ($this->rentabilidad->Exportable) $Doc->ExportField($this->rentabilidad);
						if ($this->precioVenta->Exportable) $Doc->ExportField($this->precioVenta);
						if ($this->tags->Exportable) $Doc->ExportField($this->tags);
						if ($this->detalle->Exportable) $Doc->ExportField($this->detalle);
						if ($this->codigosExternos->Exportable) $Doc->ExportField($this->codigosExternos);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->denominacionExterna->Exportable) $Doc->ExportField($this->denominacionExterna);
						if ($this->denominacionInterna->Exportable) $Doc->ExportField($this->denominacionInterna);
						if ($this->idAlicuotaIva->Exportable) $Doc->ExportField($this->idAlicuotaIva);
						if ($this->idCategoria->Exportable) $Doc->ExportField($this->idCategoria);
						if ($this->idSubcateogoria->Exportable) $Doc->ExportField($this->idSubcateogoria);
						if ($this->idRubro->Exportable) $Doc->ExportField($this->idRubro);
						if ($this->idMarca->Exportable) $Doc->ExportField($this->idMarca);
						if ($this->codigoBarras->Exportable) $Doc->ExportField($this->codigoBarras);
						if ($this->idPrecioCompra->Exportable) $Doc->ExportField($this->idPrecioCompra);
						if ($this->proveedor->Exportable) $Doc->ExportField($this->proveedor);
						if ($this->calculoPrecio->Exportable) $Doc->ExportField($this->calculoPrecio);
						if ($this->rentabilidad->Exportable) $Doc->ExportField($this->rentabilidad);
						if ($this->precioVenta->Exportable) $Doc->ExportField($this->precioVenta);
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
					include_once("funciones.php");
					$ids=array();
					array_push($ids, $rsold["id"]);			
					actualizarPrecio($ids);				
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
			$sql="SELECT * FROM `articulos-stock` WHERE idArticulo = '".ltrim($this->id->DbValue, '0')."' AND idUnidadMedida = '".$this->idUnidadMedidaCompra->DbValue."' LIMIT 1";
			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();
			if (count($rows)>0) {
				$this->idUnidadMedidaCompra->ViewValue .= '('.$rows[0]["stock"].')';
			}
			$sql="SELECT * FROM `articulos-stock` WHERE idArticulo = '".ltrim($this->id->DbValue, '0')."' AND idUnidadMedida = '".$this->idUnidadMedidaVenta->DbValue."' LIMIT 1";
			$rs = ew_LoadRecordset($sql);
			$rows = $rs->GetRows();
			if (count($rows)>0) {
				$this->idUnidadMedidaVenta->ViewValue .= '('.$rows[0]["stock"].')';
			}		

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
