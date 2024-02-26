<?php

// Global variable for table object
$monedas = NULL;

//
// Table class for monedas
//
class cmonedas extends cTable {
	var $id;
	var $denominacion;
	var $simbolo;
	var $cotizacion;
	var $idMonedaActualizacion;
	var $idTipoDolar;
	var $porcentajeVariacion;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'monedas';
		$this->TableName = 'monedas';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`monedas`";
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
		$this->id = new cField('monedas', 'monedas', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// denominacion
		$this->denominacion = new cField('monedas', 'monedas', 'x_denominacion', 'denominacion', '`denominacion`', '`denominacion`', 200, -1, FALSE, '`denominacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacion->Sortable = TRUE; // Allow sort
		$this->fields['denominacion'] = &$this->denominacion;

		// simbolo
		$this->simbolo = new cField('monedas', 'monedas', 'x_simbolo', 'simbolo', '`simbolo`', '`simbolo`', 200, -1, FALSE, '`simbolo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->simbolo->Sortable = TRUE; // Allow sort
		$this->fields['simbolo'] = &$this->simbolo;

		// cotizacion
		$this->cotizacion = new cField('monedas', 'monedas', 'x_cotizacion', 'cotizacion', '`cotizacion`', '`cotizacion`', 4, -1, FALSE, '`cotizacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cotizacion->Sortable = TRUE; // Allow sort
		$this->cotizacion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['cotizacion'] = &$this->cotizacion;

		// idMonedaActualizacion
		$this->idMonedaActualizacion = new cField('monedas', 'monedas', 'x_idMonedaActualizacion', 'idMonedaActualizacion', '`idMonedaActualizacion`', '`idMonedaActualizacion`', 3, -1, FALSE, '`idMonedaActualizacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idMonedaActualizacion->Sortable = TRUE; // Allow sort
		$this->idMonedaActualizacion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idMonedaActualizacion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idMonedaActualizacion->OptionCount = 7;
		$this->idMonedaActualizacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idMonedaActualizacion'] = &$this->idMonedaActualizacion;

		// idTipoDolar
		$this->idTipoDolar = new cField('monedas', 'monedas', 'x_idTipoDolar', 'idTipoDolar', '`idTipoDolar`', '`idTipoDolar`', 3, -1, FALSE, '`idTipoDolar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTipoDolar->Sortable = TRUE; // Allow sort
		$this->idTipoDolar->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTipoDolar->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTipoDolar->OptionCount = 19;
		$this->idTipoDolar->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTipoDolar'] = &$this->idTipoDolar;

		// porcentajeVariacion
		$this->porcentajeVariacion = new cField('monedas', 'monedas', 'x_porcentajeVariacion', 'porcentajeVariacion', '`porcentajeVariacion`', '`porcentajeVariacion`', 4, -1, FALSE, '`porcentajeVariacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->porcentajeVariacion->Sortable = TRUE; // Allow sort
		$this->porcentajeVariacion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['porcentajeVariacion'] = &$this->porcentajeVariacion;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`monedas`";
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
			return "monedaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "monedaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("monedasview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("monedasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "monedasadd.php?" . $this->UrlParm($parm);
		else
			$url = "monedasadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("monedasedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("monedasadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("monedasdelete.php", $this->UrlParm());
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
		$this->simbolo->setDbValue($rs->fields('simbolo'));
		$this->cotizacion->setDbValue($rs->fields('cotizacion'));
		$this->idMonedaActualizacion->setDbValue($rs->fields('idMonedaActualizacion'));
		$this->idTipoDolar->setDbValue($rs->fields('idTipoDolar'));
		$this->porcentajeVariacion->setDbValue($rs->fields('porcentajeVariacion'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// denominacion
		// simbolo
		// cotizacion
		// idMonedaActualizacion
		// idTipoDolar
		// porcentajeVariacion
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// simbolo
		$this->simbolo->ViewValue = $this->simbolo->CurrentValue;
		$this->simbolo->ViewCustomAttributes = "";

		// cotizacion
		$this->cotizacion->ViewValue = $this->cotizacion->CurrentValue;
		$this->cotizacion->ViewCustomAttributes = "";

		// idMonedaActualizacion
		if (strval($this->idMonedaActualizacion->CurrentValue) <> "") {
			$this->idMonedaActualizacion->ViewValue = $this->idMonedaActualizacion->OptionCaption($this->idMonedaActualizacion->CurrentValue);
		} else {
			$this->idMonedaActualizacion->ViewValue = NULL;
		}
		$this->idMonedaActualizacion->ViewCustomAttributes = "";

		// idTipoDolar
		if (strval($this->idTipoDolar->CurrentValue) <> "") {
			$this->idTipoDolar->ViewValue = $this->idTipoDolar->OptionCaption($this->idTipoDolar->CurrentValue);
		} else {
			$this->idTipoDolar->ViewValue = NULL;
		}
		$this->idTipoDolar->ViewCustomAttributes = "";

		// porcentajeVariacion
		$this->porcentajeVariacion->ViewValue = $this->porcentajeVariacion->CurrentValue;
		$this->porcentajeVariacion->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// denominacion
		$this->denominacion->LinkCustomAttributes = "";
		$this->denominacion->HrefValue = "";
		$this->denominacion->TooltipValue = "";

		// simbolo
		$this->simbolo->LinkCustomAttributes = "";
		$this->simbolo->HrefValue = "";
		$this->simbolo->TooltipValue = "";

		// cotizacion
		$this->cotizacion->LinkCustomAttributes = "";
		$this->cotizacion->HrefValue = "";
		$this->cotizacion->TooltipValue = "";

		// idMonedaActualizacion
		$this->idMonedaActualizacion->LinkCustomAttributes = "";
		$this->idMonedaActualizacion->HrefValue = "";
		$this->idMonedaActualizacion->TooltipValue = "";

		// idTipoDolar
		$this->idTipoDolar->LinkCustomAttributes = "";
		$this->idTipoDolar->HrefValue = "";
		$this->idTipoDolar->TooltipValue = "";

		// porcentajeVariacion
		$this->porcentajeVariacion->LinkCustomAttributes = "";
		$this->porcentajeVariacion->HrefValue = "";
		$this->porcentajeVariacion->TooltipValue = "";

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

		// simbolo
		$this->simbolo->EditAttrs["class"] = "form-control";
		$this->simbolo->EditCustomAttributes = "";
		$this->simbolo->EditValue = $this->simbolo->CurrentValue;
		$this->simbolo->PlaceHolder = ew_RemoveHtml($this->simbolo->FldCaption());

		// cotizacion
		$this->cotizacion->EditAttrs["class"] = "form-control";
		$this->cotizacion->EditCustomAttributes = "";
		$this->cotizacion->EditValue = $this->cotizacion->CurrentValue;
		$this->cotizacion->PlaceHolder = ew_RemoveHtml($this->cotizacion->FldCaption());
		if (strval($this->cotizacion->EditValue) <> "" && is_numeric($this->cotizacion->EditValue)) $this->cotizacion->EditValue = ew_FormatNumber($this->cotizacion->EditValue, -2, -1, -2, 0);

		// idMonedaActualizacion
		$this->idMonedaActualizacion->EditAttrs["class"] = "form-control";
		$this->idMonedaActualizacion->EditCustomAttributes = 'data-elemento-dependiente="true"';
		$this->idMonedaActualizacion->EditValue = $this->idMonedaActualizacion->Options(TRUE);

		// idTipoDolar
		$this->idTipoDolar->EditAttrs["class"] = "form-control";
		$this->idTipoDolar->EditCustomAttributes = 'data-visible=\'{"x_idMonedaActualizacion":{"":false,"0":true,"1":false,"2":false,"3":false,"4":false,"5":false,"6":false}}\'';
		$this->idTipoDolar->EditValue = $this->idTipoDolar->Options(TRUE);

		// porcentajeVariacion
		$this->porcentajeVariacion->EditAttrs["class"] = "form-control";
		$this->porcentajeVariacion->EditCustomAttributes = "";
		$this->porcentajeVariacion->EditValue = $this->porcentajeVariacion->CurrentValue;
		$this->porcentajeVariacion->PlaceHolder = ew_RemoveHtml($this->porcentajeVariacion->FldCaption());
		if (strval($this->porcentajeVariacion->EditValue) <> "" && is_numeric($this->porcentajeVariacion->EditValue)) $this->porcentajeVariacion->EditValue = ew_FormatNumber($this->porcentajeVariacion->EditValue, -2, -1, -2, 0);

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
					if ($this->simbolo->Exportable) $Doc->ExportCaption($this->simbolo);
					if ($this->cotizacion->Exportable) $Doc->ExportCaption($this->cotizacion);
					if ($this->idMonedaActualizacion->Exportable) $Doc->ExportCaption($this->idMonedaActualizacion);
					if ($this->idTipoDolar->Exportable) $Doc->ExportCaption($this->idTipoDolar);
					if ($this->porcentajeVariacion->Exportable) $Doc->ExportCaption($this->porcentajeVariacion);
				} else {
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->simbolo->Exportable) $Doc->ExportCaption($this->simbolo);
					if ($this->cotizacion->Exportable) $Doc->ExportCaption($this->cotizacion);
					if ($this->idMonedaActualizacion->Exportable) $Doc->ExportCaption($this->idMonedaActualizacion);
					if ($this->idTipoDolar->Exportable) $Doc->ExportCaption($this->idTipoDolar);
					if ($this->porcentajeVariacion->Exportable) $Doc->ExportCaption($this->porcentajeVariacion);
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
						if ($this->simbolo->Exportable) $Doc->ExportField($this->simbolo);
						if ($this->cotizacion->Exportable) $Doc->ExportField($this->cotizacion);
						if ($this->idMonedaActualizacion->Exportable) $Doc->ExportField($this->idMonedaActualizacion);
						if ($this->idTipoDolar->Exportable) $Doc->ExportField($this->idTipoDolar);
						if ($this->porcentajeVariacion->Exportable) $Doc->ExportField($this->porcentajeVariacion);
					} else {
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->simbolo->Exportable) $Doc->ExportField($this->simbolo);
						if ($this->cotizacion->Exportable) $Doc->ExportField($this->cotizacion);
						if ($this->idMonedaActualizacion->Exportable) $Doc->ExportField($this->idMonedaActualizacion);
						if ($this->idTipoDolar->Exportable) $Doc->ExportField($this->idTipoDolar);
						if ($this->porcentajeVariacion->Exportable) $Doc->ExportField($this->porcentajeVariacion);
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
			if ($rsold["cotizacion"]!=$rsnew["cotizacion"]) {
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
				monedas.cotizacion
				FROM `articulos-proveedores`
				INNER JOIN monedas
				ON `articulos-proveedores`.idMoneda = monedas.id
				INNER JOIN terceros
				ON `terceros`.id = `articulos-proveedores`.idTercero
				WHERE monedas.id='".$rsold["id"]."'";
				$rs = ew_LoadRecordset($sql);
				$rows = $rs->GetRows();
				if (count($rows)>0) {
					$ids=array();
					for ($i=0; $i < count($rows); $i++) {
					$precio=$rows[$i]["precio"]; 
					if ($rows[$i]["dto1"]==NULL && $rows[$i]["dto2"]==NULL && $rows[$i]["dto3"]==NULL) {	
						if ($rows[$i]["dto1tercero"]!=NULL && $rows[$i]["dto1tercero"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto1tercero"]/100);
						}
						if ($rows[$i]["dto2tercero"]!=NULL && $rows[$i]["dto2tercero"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto2tercero"]/100);
						}
						if ($rows[$i]["dto3tercero"]!=NULL && $rows[$i]["dto3tercero"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto3tercero"]/100);
						}										
					}else{
						if ($rows[$i]["dto1"]!=NULL && $rows[$i]["dto1"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto1"]/100);
						}
						if ($rows[$i]["dto2"]!=NULL && $rows[$i]["dto2"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto2"]/100);
						}
						if ($rows[$i]["dto3"]!=NULL && $rows[$i]["dto3"]!=0) {
							$precio=$precio-($precio*$rows[$i]["dto3"]/100);
						}	
					}
					$precio=$precio*$rows[$i]["cotizacion"];
					$sql="UPDATE
					`articulos-proveedores`
					SET
					precioPesos='".$precio."',
					ultimaActualizacion='".date("Y-m-d")."'
					WHERE
					id='".$rows[$i]["id"]."'";
					ew_Execute($sql);
					array_push($ids, $rows[$i]["idArticulo"]);			
					}
				actualizarPrecio($ids);
				}
			}
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
