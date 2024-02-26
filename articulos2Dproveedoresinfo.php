<?php

// Global variable for table object
$articulos2Dproveedores = NULL;

//
// Table class for articulos-proveedores
//
class carticulos2Dproveedores extends cTable {
	var $id;
	var $idArticulo;
	var $codExterno;
	var $idAlicuotaIva;
	var $idMoneda;
	var $precio;
	var $idUnidadMedida;
	var $dto1;
	var $dto2;
	var $dto3;
	var $idTercero;
	var $precioPesos;
	var $ultimaActualizacion;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'articulos2Dproveedores';
		$this->TableName = 'articulos-proveedores';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`articulos-proveedores`";
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
		$this->id = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// idArticulo
		$this->idArticulo = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_idArticulo', 'idArticulo', '`idArticulo`', '`idArticulo`', 3, -1, FALSE, '`idArticulo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idArticulo->Sortable = TRUE; // Allow sort
		$this->idArticulo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idArticulo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idArticulo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idArticulo'] = &$this->idArticulo;

		// codExterno
		$this->codExterno = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_codExterno', 'codExterno', '`codExterno`', '`codExterno`', 200, -1, FALSE, '`codExterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codExterno->Sortable = TRUE; // Allow sort
		$this->fields['codExterno'] = &$this->codExterno;

		// idAlicuotaIva
		$this->idAlicuotaIva = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_idAlicuotaIva', 'idAlicuotaIva', '`idAlicuotaIva`', '`idAlicuotaIva`', 3, -1, FALSE, '`idAlicuotaIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idAlicuotaIva->Sortable = TRUE; // Allow sort
		$this->idAlicuotaIva->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idAlicuotaIva->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idAlicuotaIva->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idAlicuotaIva'] = &$this->idAlicuotaIva;

		// idMoneda
		$this->idMoneda = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_idMoneda', 'idMoneda', '`idMoneda`', '`idMoneda`', 3, -1, FALSE, '`idMoneda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idMoneda->Sortable = TRUE; // Allow sort
		$this->idMoneda->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idMoneda->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idMoneda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idMoneda'] = &$this->idMoneda;

		// precio
		$this->precio = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_precio', 'precio', '`precio`', '`precio`', 4, -1, FALSE, '`precio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->precio->Sortable = TRUE; // Allow sort
		$this->precio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio'] = &$this->precio;

		// idUnidadMedida
		$this->idUnidadMedida = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_idUnidadMedida', 'idUnidadMedida', '`idUnidadMedida`', '`idUnidadMedida`', 3, -1, FALSE, '`idUnidadMedida`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idUnidadMedida->Sortable = TRUE; // Allow sort
		$this->idUnidadMedida->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idUnidadMedida->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idUnidadMedida->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUnidadMedida'] = &$this->idUnidadMedida;

		// dto1
		$this->dto1 = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_dto1', 'dto1', '`dto1`', '`dto1`', 4, -1, FALSE, '`dto1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto1->Sortable = TRUE; // Allow sort
		$this->dto1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto1'] = &$this->dto1;

		// dto2
		$this->dto2 = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_dto2', 'dto2', '`dto2`', '`dto2`', 4, -1, FALSE, '`dto2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto2->Sortable = TRUE; // Allow sort
		$this->dto2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto2'] = &$this->dto2;

		// dto3
		$this->dto3 = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_dto3', 'dto3', '`dto3`', '`dto3`', 4, -1, FALSE, '`dto3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dto3->Sortable = TRUE; // Allow sort
		$this->dto3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dto3'] = &$this->dto3;

		// idTercero
		$this->idTercero = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_idTercero', 'idTercero', '`idTercero`', '`idTercero`', 3, -1, FALSE, '`idTercero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTercero->Sortable = TRUE; // Allow sort
		$this->idTercero->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTercero->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTercero->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTercero'] = &$this->idTercero;

		// precioPesos
		$this->precioPesos = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_precioPesos', 'precioPesos', '`precioPesos`', '`precioPesos`', 4, -1, FALSE, '`precioPesos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->precioPesos->Sortable = TRUE; // Allow sort
		$this->precioPesos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precioPesos'] = &$this->precioPesos;

		// ultimaActualizacion
		$this->ultimaActualizacion = new cField('articulos2Dproveedores', 'articulos-proveedores', 'x_ultimaActualizacion', 'ultimaActualizacion', '`ultimaActualizacion`', 'DATE_FORMAT(`ultimaActualizacion`, \'%Y/%m/%d\')', 133, 0, FALSE, '`ultimaActualizacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ultimaActualizacion->Sortable = TRUE; // Allow sort
		$this->ultimaActualizacion->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['ultimaActualizacion'] = &$this->ultimaActualizacion;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "articulos") {
			if ($this->idArticulo->getSessionValue() <> "")
				$sMasterFilter .= "`id`=" . ew_QuotedValue($this->idArticulo->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "terceros") {
			if ($this->idTercero->getSessionValue() <> "")
				$sMasterFilter .= "`id`=" . ew_QuotedValue($this->idTercero->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "articulos") {
			if ($this->idArticulo->getSessionValue() <> "")
				$sDetailFilter .= "`idArticulo`=" . ew_QuotedValue($this->idArticulo->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "terceros") {
			if ($this->idTercero->getSessionValue() <> "")
				$sDetailFilter .= "`idTercero`=" . ew_QuotedValue($this->idTercero->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_articulos() {
		return "`id`=@id@";
	}

	// Detail filter
	function SqlDetailFilter_articulos() {
		return "`idArticulo`=@idArticulo@";
	}

	// Master filter
	function SqlMasterFilter_terceros() {
		return "`id`=@id@";
	}

	// Detail filter
	function SqlDetailFilter_terceros() {
		return "`idTercero`=@idTercero@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`articulos-proveedores`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`precioPesos` ASC";
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
			return "articulos2Dproveedoreslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "articulos2Dproveedoreslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("articulos2Dproveedoresview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("articulos2Dproveedoresview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "articulos2Dproveedoresadd.php?" . $this->UrlParm($parm);
		else
			$url = "articulos2Dproveedoresadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("articulos2Dproveedoresedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("articulos2Dproveedoresadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("articulos2Dproveedoresdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "articulos" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_id=" . urlencode($this->idArticulo->CurrentValue);
		}
		if ($this->getCurrentMasterTable() == "terceros" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_id=" . urlencode($this->idTercero->CurrentValue);
		}
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
		$this->idArticulo->setDbValue($rs->fields('idArticulo'));
		$this->codExterno->setDbValue($rs->fields('codExterno'));
		$this->idAlicuotaIva->setDbValue($rs->fields('idAlicuotaIva'));
		$this->idMoneda->setDbValue($rs->fields('idMoneda'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->idUnidadMedida->setDbValue($rs->fields('idUnidadMedida'));
		$this->dto1->setDbValue($rs->fields('dto1'));
		$this->dto2->setDbValue($rs->fields('dto2'));
		$this->dto3->setDbValue($rs->fields('dto3'));
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->precioPesos->setDbValue($rs->fields('precioPesos'));
		$this->ultimaActualizacion->setDbValue($rs->fields('ultimaActualizacion'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idArticulo
		// codExterno
		// idAlicuotaIva
		// idMoneda
		// precio
		// idUnidadMedida
		// dto1
		// dto2
		// dto3
		// idTercero
		// precioPesos
		// ultimaActualizacion
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idArticulo
		if (strval($this->idArticulo->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idArticulo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacionExterna` AS `DispFld`, `denominacionInterna` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articulos`";
		$sWhereWrk = "";
		$this->idArticulo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idArticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->idArticulo->ViewValue = $this->idArticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idArticulo->ViewValue = $this->idArticulo->CurrentValue;
			}
		} else {
			$this->idArticulo->ViewValue = NULL;
		}
		$this->idArticulo->ViewCustomAttributes = "";

		// codExterno
		$this->codExterno->ViewValue = $this->codExterno->CurrentValue;
		$this->codExterno->ViewCustomAttributes = "";

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

		// idMoneda
		if (strval($this->idMoneda->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMoneda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `simbolo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `monedas`";
		$sWhereWrk = "";
		$this->idMoneda->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMoneda, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMoneda->ViewValue = $this->idMoneda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMoneda->ViewValue = $this->idMoneda->CurrentValue;
			}
		} else {
			$this->idMoneda->ViewValue = NULL;
		}
		$this->idMoneda->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewCustomAttributes = "";

		// idUnidadMedida
		$this->idUnidadMedida->ViewCustomAttributes = "";

		// dto1
		$this->dto1->ViewValue = $this->dto1->CurrentValue;
		$this->dto1->ViewCustomAttributes = "";

		// dto2
		$this->dto2->ViewValue = $this->dto2->CurrentValue;
		$this->dto2->ViewCustomAttributes = "";

		// dto3
		$this->dto3->ViewValue = $this->dto3->CurrentValue;
		$this->dto3->ViewCustomAttributes = "";

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `dto1` AS `Disp2Fld`, `dto2` AS `Disp3Fld`, `dto3` AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTercero->ViewValue = $this->idTercero->CurrentValue;
			}
		} else {
			$this->idTercero->ViewValue = NULL;
		}
		$this->idTercero->ViewCustomAttributes = "";

		// precioPesos
		$this->precioPesos->ViewValue = $this->precioPesos->CurrentValue;
		$this->precioPesos->ViewCustomAttributes = "";

		// ultimaActualizacion
		$this->ultimaActualizacion->ViewValue = $this->ultimaActualizacion->CurrentValue;
		$this->ultimaActualizacion->ViewValue = ew_FormatDateTime($this->ultimaActualizacion->ViewValue, 0);
		$this->ultimaActualizacion->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// idArticulo
		$this->idArticulo->LinkCustomAttributes = "";
		$this->idArticulo->HrefValue = "";
		$this->idArticulo->TooltipValue = "";

		// codExterno
		$this->codExterno->LinkCustomAttributes = "";
		$this->codExterno->HrefValue = "";
		$this->codExterno->TooltipValue = "";

		// idAlicuotaIva
		$this->idAlicuotaIva->LinkCustomAttributes = "";
		$this->idAlicuotaIva->HrefValue = "";
		$this->idAlicuotaIva->TooltipValue = "";

		// idMoneda
		$this->idMoneda->LinkCustomAttributes = "";
		$this->idMoneda->HrefValue = "";
		$this->idMoneda->TooltipValue = "";

		// precio
		$this->precio->LinkCustomAttributes = "";
		$this->precio->HrefValue = "";
		$this->precio->TooltipValue = "";

		// idUnidadMedida
		$this->idUnidadMedida->LinkCustomAttributes = "";
		$this->idUnidadMedida->HrefValue = "";
		$this->idUnidadMedida->TooltipValue = "";

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

		// idTercero
		$this->idTercero->LinkCustomAttributes = "";
		$this->idTercero->HrefValue = "";
		$this->idTercero->TooltipValue = "";

		// precioPesos
		$this->precioPesos->LinkCustomAttributes = "";
		$this->precioPesos->HrefValue = "";
		$this->precioPesos->TooltipValue = "";

		// ultimaActualizacion
		$this->ultimaActualizacion->LinkCustomAttributes = "";
		$this->ultimaActualizacion->HrefValue = "";
		$this->ultimaActualizacion->TooltipValue = "";

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

		// idArticulo
		$this->idArticulo->EditAttrs["class"] = "form-control";
		$this->idArticulo->EditCustomAttributes = "";
		if ($this->idArticulo->getSessionValue() <> "") {
			$this->idArticulo->CurrentValue = $this->idArticulo->getSessionValue();
		if (strval($this->idArticulo->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idArticulo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacionExterna` AS `DispFld`, `denominacionInterna` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articulos`";
		$sWhereWrk = "";
		$this->idArticulo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idArticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->idArticulo->ViewValue = $this->idArticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idArticulo->ViewValue = $this->idArticulo->CurrentValue;
			}
		} else {
			$this->idArticulo->ViewValue = NULL;
		}
		$this->idArticulo->ViewCustomAttributes = "";
		} else {
		}

		// codExterno
		$this->codExterno->EditAttrs["class"] = "form-control";
		$this->codExterno->EditCustomAttributes = "";
		$this->codExterno->EditValue = $this->codExterno->CurrentValue;
		$this->codExterno->PlaceHolder = ew_RemoveHtml($this->codExterno->FldCaption());

		// idAlicuotaIva
		$this->idAlicuotaIva->EditAttrs["class"] = "form-control";
		$this->idAlicuotaIva->EditCustomAttributes = "";

		// idMoneda
		$this->idMoneda->EditAttrs["class"] = "form-control";
		$this->idMoneda->EditCustomAttributes = "";

		// precio
		$this->precio->EditAttrs["class"] = "form-control";
		$this->precio->EditCustomAttributes = "";
		$this->precio->EditValue = $this->precio->CurrentValue;
		$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());
		if (strval($this->precio->EditValue) <> "" && is_numeric($this->precio->EditValue)) $this->precio->EditValue = ew_FormatNumber($this->precio->EditValue, -2, -1, -2, 0);

		// idUnidadMedida
		$this->idUnidadMedida->EditAttrs["class"] = "form-control";
		$this->idUnidadMedida->EditCustomAttributes = "";

		// dto1
		$this->dto1->EditAttrs["class"] = "form-control";
		$this->dto1->EditCustomAttributes = "";
		$this->dto1->EditValue = $this->dto1->CurrentValue;
		$this->dto1->PlaceHolder = ew_RemoveHtml($this->dto1->FldCaption());
		if (strval($this->dto1->EditValue) <> "" && is_numeric($this->dto1->EditValue)) $this->dto1->EditValue = ew_FormatNumber($this->dto1->EditValue, -2, -1, -2, 0);

		// dto2
		$this->dto2->EditAttrs["class"] = "form-control";
		$this->dto2->EditCustomAttributes = "";
		$this->dto2->EditValue = $this->dto2->CurrentValue;
		$this->dto2->PlaceHolder = ew_RemoveHtml($this->dto2->FldCaption());
		if (strval($this->dto2->EditValue) <> "" && is_numeric($this->dto2->EditValue)) $this->dto2->EditValue = ew_FormatNumber($this->dto2->EditValue, -2, -1, -2, 0);

		// dto3
		$this->dto3->EditAttrs["class"] = "form-control";
		$this->dto3->EditCustomAttributes = "";
		$this->dto3->EditValue = $this->dto3->CurrentValue;
		$this->dto3->PlaceHolder = ew_RemoveHtml($this->dto3->FldCaption());
		if (strval($this->dto3->EditValue) <> "" && is_numeric($this->dto3->EditValue)) $this->dto3->EditValue = ew_FormatNumber($this->dto3->EditValue, -2, -1, -2, 0);

		// idTercero
		$this->idTercero->EditAttrs["class"] = "form-control";
		$this->idTercero->EditCustomAttributes = "";
		if ($this->idTercero->getSessionValue() <> "") {
			$this->idTercero->CurrentValue = $this->idTercero->getSessionValue();
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `dto1` AS `Disp2Fld`, `dto2` AS `Disp3Fld`, `dto3` AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTercero->ViewValue = $this->idTercero->CurrentValue;
			}
		} else {
			$this->idTercero->ViewValue = NULL;
		}
		$this->idTercero->ViewCustomAttributes = "";
		} else {
		}

		// precioPesos
		$this->precioPesos->EditAttrs["class"] = "form-control";
		$this->precioPesos->EditCustomAttributes = "";
		$this->precioPesos->EditValue = $this->precioPesos->CurrentValue;
		$this->precioPesos->PlaceHolder = ew_RemoveHtml($this->precioPesos->FldCaption());
		if (strval($this->precioPesos->EditValue) <> "" && is_numeric($this->precioPesos->EditValue)) $this->precioPesos->EditValue = ew_FormatNumber($this->precioPesos->EditValue, -2, -1, -2, 0);

		// ultimaActualizacion
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
					if ($this->idArticulo->Exportable) $Doc->ExportCaption($this->idArticulo);
					if ($this->codExterno->Exportable) $Doc->ExportCaption($this->codExterno);
					if ($this->idAlicuotaIva->Exportable) $Doc->ExportCaption($this->idAlicuotaIva);
					if ($this->idMoneda->Exportable) $Doc->ExportCaption($this->idMoneda);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->idUnidadMedida->Exportable) $Doc->ExportCaption($this->idUnidadMedida);
					if ($this->dto1->Exportable) $Doc->ExportCaption($this->dto1);
					if ($this->dto2->Exportable) $Doc->ExportCaption($this->dto2);
					if ($this->dto3->Exportable) $Doc->ExportCaption($this->dto3);
					if ($this->idTercero->Exportable) $Doc->ExportCaption($this->idTercero);
					if ($this->precioPesos->Exportable) $Doc->ExportCaption($this->precioPesos);
					if ($this->ultimaActualizacion->Exportable) $Doc->ExportCaption($this->ultimaActualizacion);
				} else {
					if ($this->idArticulo->Exportable) $Doc->ExportCaption($this->idArticulo);
					if ($this->codExterno->Exportable) $Doc->ExportCaption($this->codExterno);
					if ($this->idAlicuotaIva->Exportable) $Doc->ExportCaption($this->idAlicuotaIva);
					if ($this->idMoneda->Exportable) $Doc->ExportCaption($this->idMoneda);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->idUnidadMedida->Exportable) $Doc->ExportCaption($this->idUnidadMedida);
					if ($this->dto1->Exportable) $Doc->ExportCaption($this->dto1);
					if ($this->dto2->Exportable) $Doc->ExportCaption($this->dto2);
					if ($this->dto3->Exportable) $Doc->ExportCaption($this->dto3);
					if ($this->idTercero->Exportable) $Doc->ExportCaption($this->idTercero);
					if ($this->precioPesos->Exportable) $Doc->ExportCaption($this->precioPesos);
					if ($this->ultimaActualizacion->Exportable) $Doc->ExportCaption($this->ultimaActualizacion);
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
						if ($this->idArticulo->Exportable) $Doc->ExportField($this->idArticulo);
						if ($this->codExterno->Exportable) $Doc->ExportField($this->codExterno);
						if ($this->idAlicuotaIva->Exportable) $Doc->ExportField($this->idAlicuotaIva);
						if ($this->idMoneda->Exportable) $Doc->ExportField($this->idMoneda);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->idUnidadMedida->Exportable) $Doc->ExportField($this->idUnidadMedida);
						if ($this->dto1->Exportable) $Doc->ExportField($this->dto1);
						if ($this->dto2->Exportable) $Doc->ExportField($this->dto2);
						if ($this->dto3->Exportable) $Doc->ExportField($this->dto3);
						if ($this->idTercero->Exportable) $Doc->ExportField($this->idTercero);
						if ($this->precioPesos->Exportable) $Doc->ExportField($this->precioPesos);
						if ($this->ultimaActualizacion->Exportable) $Doc->ExportField($this->ultimaActualizacion);
					} else {
						if ($this->idArticulo->Exportable) $Doc->ExportField($this->idArticulo);
						if ($this->codExterno->Exportable) $Doc->ExportField($this->codExterno);
						if ($this->idAlicuotaIva->Exportable) $Doc->ExportField($this->idAlicuotaIva);
						if ($this->idMoneda->Exportable) $Doc->ExportField($this->idMoneda);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->idUnidadMedida->Exportable) $Doc->ExportField($this->idUnidadMedida);
						if ($this->dto1->Exportable) $Doc->ExportField($this->dto1);
						if ($this->dto2->Exportable) $Doc->ExportField($this->dto2);
						if ($this->dto3->Exportable) $Doc->ExportField($this->dto3);
						if ($this->idTercero->Exportable) $Doc->ExportField($this->idTercero);
						if ($this->precioPesos->Exportable) $Doc->ExportField($this->precioPesos);
						if ($this->ultimaActualizacion->Exportable) $Doc->ExportField($this->ultimaActualizacion);
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
				$ids=array();
				include_once("funciones.php");
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
				ORDER BY `articulos-proveedores`.id DESC
				LIMIT 1";
				$rs = ew_LoadRecordset($sql);
				$rows = $rs->GetRows();
				$precio=$rows[0]["precio"];

					//Si tiene unidad de medida
					if ($rows[0]["indiceConversion"] != NULL){
						$precio=$rows[0]["precio"] * $rows[0]["indiceConversion"];
					}

					//if ($rows[0]["dto1"]==NULL && $rows[0]["dto2"]==NULL && $rows[0]["dto3"]==NULL) {	
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
				ew_Execute($sql);
				array_push($ids, $rows[0]["idArticulo"]);			
				actualizarPrecio($ids);
				codigosExternos($rows[0]["idArticulo"]);
		}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

		// Row Updated event
		function Row_Updated($rsold, &$rsnew) {
				$ids=array();
					include_once("funciones.php");

					//Obtengo el registro
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
					WHERE `articulos-proveedores`.id = '".$rsold["id"]."'";

					//Guardo el resultado en rows
					$rs = ew_LoadRecordset($sql);
					$rows = $rs->GetRows();

					//Guardo en precio el costo del artículo
					$precio=$rows[0]["precio"];

					//Si tiene unidad de medida
					if ($rows[0]["indiceConversion"] != NULL){
						$precio=$rows[0]["precio"] * $rows[0]["indiceConversion"];
					}

					//Si tiene descuento el proveedor se aplica
					if ($rows[0]["dto1tercero"]!=NULL && $rows[0]["dto1tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto1tercero"]/100);
					}
					if ($rows[0]["dto2tercero"]!=NULL && $rows[0]["dto2tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto2tercero"]/100);
					}
					if ($rows[0]["dto3tercero"]!=NULL && $rows[0]["dto3tercero"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto3tercero"]/100);
					}										

					//Si tiene descuento el artículo se aplica
					if ($rows[0]["dto1"]!=NULL && $rows[0]["dto1"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto1"]/100);
					}
					if ($rows[0]["dto2"]!=NULL && $rows[0]["dto2"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto2"]/100);
					}
					if ($rows[0]["dto3"]!=NULL && $rows[0]["dto3"]!=0) {
						$precio=$precio-($precio*$rows[0]["dto3"]/100);
					}	

					//Multiplico por la cotización de la moneda
					$precio=$precio*$rows[0]["cotizacion"];

					//Actualizo el campo precioPesos con el resultado
					$sql="UPDATE
					`articulos-proveedores`
					SET
					precioPesos='".$precio."',
					ultimaActualizacion='".date("Y-m-d")."'
					WHERE
					id='".$rows[0]["id"]."'";
					ew_Execute($sql);

					//Actualizo precio de venta
					array_push($ids, $rows[0]["idArticulo"]);			
					actualizarPrecio($ids);

					//Actualizo los códigos externos
					codigosExternos($rows[0]["idArticulo"]);
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
			include_once("funciones.php");
			codigosExternos($rs["idArticulo"]);
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

		$sql = "SELECT * FROM `unidades-medida` WHERE id='".$this->idUnidadMedida->DbValue."'";
		$rs = ew_Execute($sql);
		$rows = $rs->GetRows();
		if(count($rows)>0){
			$this->idUnidadMedida->ViewValue = $rows[0]["denominacion"];
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
