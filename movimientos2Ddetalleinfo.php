<?php

// Global variable for table object
$movimientos2Ddetalle = NULL;

//
// Table class for movimientos-detalle
//
class cmovimientos2Ddetalle extends cTable {
	var $id;
	var $idMovimientos;
	var $cant;
	var $idUnidadMedida;
	var $codProducto;
	var $medida;
	var $nombreProducto;
	var $importeUnitario;
	var $bonificacion;
	var $importeTotal;
	var $alicuotaIva;
	var $importeIva;
	var $importeNeto;
	var $importePesos;
	var $estadoImportacion;
	var $origenImportacion;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'movimientos2Ddetalle';
		$this->TableName = 'movimientos-detalle';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`movimientos-detalle`";
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

		// id
		$this->id = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// idMovimientos
		$this->idMovimientos = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_idMovimientos', 'idMovimientos', '`idMovimientos`', '`idMovimientos`', 3, -1, FALSE, '`idMovimientos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idMovimientos->Sortable = TRUE; // Allow sort
		$this->idMovimientos->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idMovimientos'] = &$this->idMovimientos;

		// cant
		$this->cant = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_cant', 'cant', '`cant`', '`cant`', 5, -1, FALSE, '`cant`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cant->Sortable = TRUE; // Allow sort
		$this->cant->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['cant'] = &$this->cant;

		// idUnidadMedida
		$this->idUnidadMedida = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_idUnidadMedida', 'idUnidadMedida', '`idUnidadMedida`', '`idUnidadMedida`', 3, -1, FALSE, '`idUnidadMedida`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idUnidadMedida->Sortable = TRUE; // Allow sort
		$this->idUnidadMedida->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idUnidadMedida'] = &$this->idUnidadMedida;

		// codProducto
		$this->codProducto = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_codProducto', 'codProducto', '`codProducto`', '`codProducto`', 3, -1, FALSE, '`codProducto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codProducto->Sortable = TRUE; // Allow sort
		$this->codProducto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['codProducto'] = &$this->codProducto;

		// medida
		$this->medida = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_medida', 'medida', '`medida`', '`medida`', 5, -1, FALSE, '`medida`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->medida->Sortable = TRUE; // Allow sort
		$this->medida->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['medida'] = &$this->medida;

		// nombreProducto
		$this->nombreProducto = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_nombreProducto', 'nombreProducto', '`nombreProducto`', '`nombreProducto`', 200, -1, FALSE, '`nombreProducto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombreProducto->Sortable = TRUE; // Allow sort
		$this->fields['nombreProducto'] = &$this->nombreProducto;

		// importeUnitario
		$this->importeUnitario = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_importeUnitario', 'importeUnitario', '`importeUnitario`', '`importeUnitario`', 5, -1, FALSE, '`importeUnitario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeUnitario->Sortable = TRUE; // Allow sort
		$this->importeUnitario->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeUnitario'] = &$this->importeUnitario;

		// bonificacion
		$this->bonificacion = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_bonificacion', 'bonificacion', '`bonificacion`', '`bonificacion`', 5, -1, FALSE, '`bonificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bonificacion->Sortable = TRUE; // Allow sort
		$this->bonificacion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bonificacion'] = &$this->bonificacion;

		// importeTotal
		$this->importeTotal = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_importeTotal', 'importeTotal', '`importeTotal`', '`importeTotal`', 5, -1, FALSE, '`importeTotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeTotal->Sortable = TRUE; // Allow sort
		$this->importeTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeTotal'] = &$this->importeTotal;

		// alicuotaIva
		$this->alicuotaIva = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_alicuotaIva', 'alicuotaIva', '`alicuotaIva`', '`alicuotaIva`', 3, -1, FALSE, '`alicuotaIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alicuotaIva->Sortable = TRUE; // Allow sort
		$this->alicuotaIva->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['alicuotaIva'] = &$this->alicuotaIva;

		// importeIva
		$this->importeIva = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_importeIva', 'importeIva', '`importeIva`', '`importeIva`', 5, -1, FALSE, '`importeIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeIva->Sortable = TRUE; // Allow sort
		$this->importeIva->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeIva'] = &$this->importeIva;

		// importeNeto
		$this->importeNeto = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_importeNeto', 'importeNeto', '`importeNeto`', '`importeNeto`', 5, -1, FALSE, '`importeNeto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importeNeto->Sortable = TRUE; // Allow sort
		$this->importeNeto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importeNeto'] = &$this->importeNeto;

		// importePesos
		$this->importePesos = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_importePesos', 'importePesos', '`importePesos`', '`importePesos`', 5, -1, FALSE, '`importePesos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->importePesos->Sortable = TRUE; // Allow sort
		$this->importePesos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['importePesos'] = &$this->importePesos;

		// estadoImportacion
		$this->estadoImportacion = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_estadoImportacion', 'estadoImportacion', '`estadoImportacion`', '`estadoImportacion`', 3, -1, FALSE, '`estadoImportacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->estadoImportacion->Sortable = TRUE; // Allow sort
		$this->estadoImportacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estadoImportacion'] = &$this->estadoImportacion;

		// origenImportacion
		$this->origenImportacion = new cField('movimientos2Ddetalle', 'movimientos-detalle', 'x_origenImportacion', 'origenImportacion', '`origenImportacion`', '`origenImportacion`', 3, -1, FALSE, '`origenImportacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->origenImportacion->Sortable = TRUE; // Allow sort
		$this->origenImportacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['origenImportacion'] = &$this->origenImportacion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`movimientos-detalle`";
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
			return "movimientos2Ddetallelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "movimientos2Ddetallelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("movimientos2Ddetalleview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("movimientos2Ddetalleview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "movimientos2Ddetalleadd.php?" . $this->UrlParm($parm);
		else
			$url = "movimientos2Ddetalleadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("movimientos2Ddetalleedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("movimientos2Ddetalleadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("movimientos2Ddetalledelete.php", $this->UrlParm());
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
		$this->idMovimientos->setDbValue($rs->fields('idMovimientos'));
		$this->cant->setDbValue($rs->fields('cant'));
		$this->idUnidadMedida->setDbValue($rs->fields('idUnidadMedida'));
		$this->codProducto->setDbValue($rs->fields('codProducto'));
		$this->medida->setDbValue($rs->fields('medida'));
		$this->nombreProducto->setDbValue($rs->fields('nombreProducto'));
		$this->importeUnitario->setDbValue($rs->fields('importeUnitario'));
		$this->bonificacion->setDbValue($rs->fields('bonificacion'));
		$this->importeTotal->setDbValue($rs->fields('importeTotal'));
		$this->alicuotaIva->setDbValue($rs->fields('alicuotaIva'));
		$this->importeIva->setDbValue($rs->fields('importeIva'));
		$this->importeNeto->setDbValue($rs->fields('importeNeto'));
		$this->importePesos->setDbValue($rs->fields('importePesos'));
		$this->estadoImportacion->setDbValue($rs->fields('estadoImportacion'));
		$this->origenImportacion->setDbValue($rs->fields('origenImportacion'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// idMovimientos
		// cant
		// idUnidadMedida
		// codProducto
		// medida
		// nombreProducto
		// importeUnitario
		// bonificacion
		// importeTotal
		// alicuotaIva
		// importeIva
		// importeNeto
		// importePesos
		// estadoImportacion
		// origenImportacion
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idMovimientos
		$this->idMovimientos->ViewValue = $this->idMovimientos->CurrentValue;
		$this->idMovimientos->ViewCustomAttributes = "";

		// cant
		$this->cant->ViewValue = $this->cant->CurrentValue;
		$this->cant->ViewCustomAttributes = "";

		// idUnidadMedida
		$this->idUnidadMedida->ViewValue = $this->idUnidadMedida->CurrentValue;
		$this->idUnidadMedida->ViewCustomAttributes = "";

		// codProducto
		$this->codProducto->ViewValue = $this->codProducto->CurrentValue;
		$this->codProducto->ViewCustomAttributes = "";

		// medida
		$this->medida->ViewValue = $this->medida->CurrentValue;
		$this->medida->ViewCustomAttributes = "";

		// nombreProducto
		$this->nombreProducto->ViewValue = $this->nombreProducto->CurrentValue;
		$this->nombreProducto->ViewCustomAttributes = "";

		// importeUnitario
		$this->importeUnitario->ViewValue = $this->importeUnitario->CurrentValue;
		$this->importeUnitario->ViewCustomAttributes = "";

		// bonificacion
		$this->bonificacion->ViewValue = $this->bonificacion->CurrentValue;
		$this->bonificacion->ViewCustomAttributes = "";

		// importeTotal
		$this->importeTotal->ViewValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->ViewCustomAttributes = "";

		// alicuotaIva
		$this->alicuotaIva->ViewValue = $this->alicuotaIva->CurrentValue;
		$this->alicuotaIva->ViewCustomAttributes = "";

		// importeIva
		$this->importeIva->ViewValue = $this->importeIva->CurrentValue;
		$this->importeIva->ViewCustomAttributes = "";

		// importeNeto
		$this->importeNeto->ViewValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->ViewCustomAttributes = "";

		// importePesos
		$this->importePesos->ViewValue = $this->importePesos->CurrentValue;
		$this->importePesos->ViewCustomAttributes = "";

		// estadoImportacion
		$this->estadoImportacion->ViewValue = $this->estadoImportacion->CurrentValue;
		$this->estadoImportacion->ViewCustomAttributes = "";

		// origenImportacion
		$this->origenImportacion->ViewValue = $this->origenImportacion->CurrentValue;
		$this->origenImportacion->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// idMovimientos
		$this->idMovimientos->LinkCustomAttributes = "";
		$this->idMovimientos->HrefValue = "";
		$this->idMovimientos->TooltipValue = "";

		// cant
		$this->cant->LinkCustomAttributes = "";
		$this->cant->HrefValue = "";
		$this->cant->TooltipValue = "";

		// idUnidadMedida
		$this->idUnidadMedida->LinkCustomAttributes = "";
		$this->idUnidadMedida->HrefValue = "";
		$this->idUnidadMedida->TooltipValue = "";

		// codProducto
		$this->codProducto->LinkCustomAttributes = "";
		$this->codProducto->HrefValue = "";
		$this->codProducto->TooltipValue = "";

		// medida
		$this->medida->LinkCustomAttributes = "";
		$this->medida->HrefValue = "";
		$this->medida->TooltipValue = "";

		// nombreProducto
		$this->nombreProducto->LinkCustomAttributes = "";
		$this->nombreProducto->HrefValue = "";
		$this->nombreProducto->TooltipValue = "";

		// importeUnitario
		$this->importeUnitario->LinkCustomAttributes = "";
		$this->importeUnitario->HrefValue = "";
		$this->importeUnitario->TooltipValue = "";

		// bonificacion
		$this->bonificacion->LinkCustomAttributes = "";
		$this->bonificacion->HrefValue = "";
		$this->bonificacion->TooltipValue = "";

		// importeTotal
		$this->importeTotal->LinkCustomAttributes = "";
		$this->importeTotal->HrefValue = "";
		$this->importeTotal->TooltipValue = "";

		// alicuotaIva
		$this->alicuotaIva->LinkCustomAttributes = "";
		$this->alicuotaIva->HrefValue = "";
		$this->alicuotaIva->TooltipValue = "";

		// importeIva
		$this->importeIva->LinkCustomAttributes = "";
		$this->importeIva->HrefValue = "";
		$this->importeIva->TooltipValue = "";

		// importeNeto
		$this->importeNeto->LinkCustomAttributes = "";
		$this->importeNeto->HrefValue = "";
		$this->importeNeto->TooltipValue = "";

		// importePesos
		$this->importePesos->LinkCustomAttributes = "";
		$this->importePesos->HrefValue = "";
		$this->importePesos->TooltipValue = "";

		// estadoImportacion
		$this->estadoImportacion->LinkCustomAttributes = "";
		$this->estadoImportacion->HrefValue = "";
		$this->estadoImportacion->TooltipValue = "";

		// origenImportacion
		$this->origenImportacion->LinkCustomAttributes = "";
		$this->origenImportacion->HrefValue = "";
		$this->origenImportacion->TooltipValue = "";

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

		// idMovimientos
		$this->idMovimientos->EditAttrs["class"] = "form-control";
		$this->idMovimientos->EditCustomAttributes = "";
		$this->idMovimientos->EditValue = $this->idMovimientos->CurrentValue;
		$this->idMovimientos->PlaceHolder = ew_RemoveHtml($this->idMovimientos->FldCaption());

		// cant
		$this->cant->EditAttrs["class"] = "form-control";
		$this->cant->EditCustomAttributes = "";
		$this->cant->EditValue = $this->cant->CurrentValue;
		$this->cant->PlaceHolder = ew_RemoveHtml($this->cant->FldCaption());
		if (strval($this->cant->EditValue) <> "" && is_numeric($this->cant->EditValue)) $this->cant->EditValue = ew_FormatNumber($this->cant->EditValue, -2, -1, -2, 0);

		// idUnidadMedida
		$this->idUnidadMedida->EditAttrs["class"] = "form-control";
		$this->idUnidadMedida->EditCustomAttributes = "";
		$this->idUnidadMedida->EditValue = $this->idUnidadMedida->CurrentValue;
		$this->idUnidadMedida->PlaceHolder = ew_RemoveHtml($this->idUnidadMedida->FldCaption());

		// codProducto
		$this->codProducto->EditAttrs["class"] = "form-control";
		$this->codProducto->EditCustomAttributes = "";
		$this->codProducto->EditValue = $this->codProducto->CurrentValue;
		$this->codProducto->PlaceHolder = ew_RemoveHtml($this->codProducto->FldCaption());

		// medida
		$this->medida->EditAttrs["class"] = "form-control";
		$this->medida->EditCustomAttributes = "";
		$this->medida->EditValue = $this->medida->CurrentValue;
		$this->medida->PlaceHolder = ew_RemoveHtml($this->medida->FldCaption());
		if (strval($this->medida->EditValue) <> "" && is_numeric($this->medida->EditValue)) $this->medida->EditValue = ew_FormatNumber($this->medida->EditValue, -2, -1, -2, 0);

		// nombreProducto
		$this->nombreProducto->EditAttrs["class"] = "form-control";
		$this->nombreProducto->EditCustomAttributes = "";
		$this->nombreProducto->EditValue = $this->nombreProducto->CurrentValue;
		$this->nombreProducto->PlaceHolder = ew_RemoveHtml($this->nombreProducto->FldCaption());

		// importeUnitario
		$this->importeUnitario->EditAttrs["class"] = "form-control";
		$this->importeUnitario->EditCustomAttributes = "";
		$this->importeUnitario->EditValue = $this->importeUnitario->CurrentValue;
		$this->importeUnitario->PlaceHolder = ew_RemoveHtml($this->importeUnitario->FldCaption());
		if (strval($this->importeUnitario->EditValue) <> "" && is_numeric($this->importeUnitario->EditValue)) $this->importeUnitario->EditValue = ew_FormatNumber($this->importeUnitario->EditValue, -2, -1, -2, 0);

		// bonificacion
		$this->bonificacion->EditAttrs["class"] = "form-control";
		$this->bonificacion->EditCustomAttributes = "";
		$this->bonificacion->EditValue = $this->bonificacion->CurrentValue;
		$this->bonificacion->PlaceHolder = ew_RemoveHtml($this->bonificacion->FldCaption());
		if (strval($this->bonificacion->EditValue) <> "" && is_numeric($this->bonificacion->EditValue)) $this->bonificacion->EditValue = ew_FormatNumber($this->bonificacion->EditValue, -2, -1, -2, 0);

		// importeTotal
		$this->importeTotal->EditAttrs["class"] = "form-control";
		$this->importeTotal->EditCustomAttributes = "";
		$this->importeTotal->EditValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());
		if (strval($this->importeTotal->EditValue) <> "" && is_numeric($this->importeTotal->EditValue)) $this->importeTotal->EditValue = ew_FormatNumber($this->importeTotal->EditValue, -2, -1, -2, 0);

		// alicuotaIva
		$this->alicuotaIva->EditAttrs["class"] = "form-control";
		$this->alicuotaIva->EditCustomAttributes = "";
		$this->alicuotaIva->EditValue = $this->alicuotaIva->CurrentValue;
		$this->alicuotaIva->PlaceHolder = ew_RemoveHtml($this->alicuotaIva->FldCaption());

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

		// importePesos
		$this->importePesos->EditAttrs["class"] = "form-control";
		$this->importePesos->EditCustomAttributes = "";
		$this->importePesos->EditValue = $this->importePesos->CurrentValue;
		$this->importePesos->PlaceHolder = ew_RemoveHtml($this->importePesos->FldCaption());
		if (strval($this->importePesos->EditValue) <> "" && is_numeric($this->importePesos->EditValue)) $this->importePesos->EditValue = ew_FormatNumber($this->importePesos->EditValue, -2, -1, -2, 0);

		// estadoImportacion
		$this->estadoImportacion->EditAttrs["class"] = "form-control";
		$this->estadoImportacion->EditCustomAttributes = "";
		$this->estadoImportacion->EditValue = $this->estadoImportacion->CurrentValue;
		$this->estadoImportacion->PlaceHolder = ew_RemoveHtml($this->estadoImportacion->FldCaption());

		// origenImportacion
		$this->origenImportacion->EditAttrs["class"] = "form-control";
		$this->origenImportacion->EditCustomAttributes = "";
		$this->origenImportacion->EditValue = $this->origenImportacion->CurrentValue;
		$this->origenImportacion->PlaceHolder = ew_RemoveHtml($this->origenImportacion->FldCaption());

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
					if ($this->idMovimientos->Exportable) $Doc->ExportCaption($this->idMovimientos);
					if ($this->cant->Exportable) $Doc->ExportCaption($this->cant);
					if ($this->idUnidadMedida->Exportable) $Doc->ExportCaption($this->idUnidadMedida);
					if ($this->codProducto->Exportable) $Doc->ExportCaption($this->codProducto);
					if ($this->medida->Exportable) $Doc->ExportCaption($this->medida);
					if ($this->nombreProducto->Exportable) $Doc->ExportCaption($this->nombreProducto);
					if ($this->importeUnitario->Exportable) $Doc->ExportCaption($this->importeUnitario);
					if ($this->bonificacion->Exportable) $Doc->ExportCaption($this->bonificacion);
					if ($this->importeTotal->Exportable) $Doc->ExportCaption($this->importeTotal);
					if ($this->alicuotaIva->Exportable) $Doc->ExportCaption($this->alicuotaIva);
					if ($this->importeIva->Exportable) $Doc->ExportCaption($this->importeIva);
					if ($this->importeNeto->Exportable) $Doc->ExportCaption($this->importeNeto);
					if ($this->importePesos->Exportable) $Doc->ExportCaption($this->importePesos);
					if ($this->estadoImportacion->Exportable) $Doc->ExportCaption($this->estadoImportacion);
					if ($this->origenImportacion->Exportable) $Doc->ExportCaption($this->origenImportacion);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->idMovimientos->Exportable) $Doc->ExportCaption($this->idMovimientos);
					if ($this->cant->Exportable) $Doc->ExportCaption($this->cant);
					if ($this->idUnidadMedida->Exportable) $Doc->ExportCaption($this->idUnidadMedida);
					if ($this->codProducto->Exportable) $Doc->ExportCaption($this->codProducto);
					if ($this->medida->Exportable) $Doc->ExportCaption($this->medida);
					if ($this->nombreProducto->Exportable) $Doc->ExportCaption($this->nombreProducto);
					if ($this->importeUnitario->Exportable) $Doc->ExportCaption($this->importeUnitario);
					if ($this->bonificacion->Exportable) $Doc->ExportCaption($this->bonificacion);
					if ($this->importeTotal->Exportable) $Doc->ExportCaption($this->importeTotal);
					if ($this->alicuotaIva->Exportable) $Doc->ExportCaption($this->alicuotaIva);
					if ($this->importeIva->Exportable) $Doc->ExportCaption($this->importeIva);
					if ($this->importeNeto->Exportable) $Doc->ExportCaption($this->importeNeto);
					if ($this->importePesos->Exportable) $Doc->ExportCaption($this->importePesos);
					if ($this->estadoImportacion->Exportable) $Doc->ExportCaption($this->estadoImportacion);
					if ($this->origenImportacion->Exportable) $Doc->ExportCaption($this->origenImportacion);
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
						if ($this->idMovimientos->Exportable) $Doc->ExportField($this->idMovimientos);
						if ($this->cant->Exportable) $Doc->ExportField($this->cant);
						if ($this->idUnidadMedida->Exportable) $Doc->ExportField($this->idUnidadMedida);
						if ($this->codProducto->Exportable) $Doc->ExportField($this->codProducto);
						if ($this->medida->Exportable) $Doc->ExportField($this->medida);
						if ($this->nombreProducto->Exportable) $Doc->ExportField($this->nombreProducto);
						if ($this->importeUnitario->Exportable) $Doc->ExportField($this->importeUnitario);
						if ($this->bonificacion->Exportable) $Doc->ExportField($this->bonificacion);
						if ($this->importeTotal->Exportable) $Doc->ExportField($this->importeTotal);
						if ($this->alicuotaIva->Exportable) $Doc->ExportField($this->alicuotaIva);
						if ($this->importeIva->Exportable) $Doc->ExportField($this->importeIva);
						if ($this->importeNeto->Exportable) $Doc->ExportField($this->importeNeto);
						if ($this->importePesos->Exportable) $Doc->ExportField($this->importePesos);
						if ($this->estadoImportacion->Exportable) $Doc->ExportField($this->estadoImportacion);
						if ($this->origenImportacion->Exportable) $Doc->ExportField($this->origenImportacion);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->idMovimientos->Exportable) $Doc->ExportField($this->idMovimientos);
						if ($this->cant->Exportable) $Doc->ExportField($this->cant);
						if ($this->idUnidadMedida->Exportable) $Doc->ExportField($this->idUnidadMedida);
						if ($this->codProducto->Exportable) $Doc->ExportField($this->codProducto);
						if ($this->medida->Exportable) $Doc->ExportField($this->medida);
						if ($this->nombreProducto->Exportable) $Doc->ExportField($this->nombreProducto);
						if ($this->importeUnitario->Exportable) $Doc->ExportField($this->importeUnitario);
						if ($this->bonificacion->Exportable) $Doc->ExportField($this->bonificacion);
						if ($this->importeTotal->Exportable) $Doc->ExportField($this->importeTotal);
						if ($this->alicuotaIva->Exportable) $Doc->ExportField($this->alicuotaIva);
						if ($this->importeIva->Exportable) $Doc->ExportField($this->importeIva);
						if ($this->importeNeto->Exportable) $Doc->ExportField($this->importeNeto);
						if ($this->importePesos->Exportable) $Doc->ExportField($this->importePesos);
						if ($this->estadoImportacion->Exportable) $Doc->ExportField($this->estadoImportacion);
						if ($this->origenImportacion->Exportable) $Doc->ExportField($this->origenImportacion);
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
