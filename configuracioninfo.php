<?php

// Global variable for table object
$configuracion = NULL;

//
// Table class for configuracion
//
class cconfiguracion extends cTable {
	var $id;
	var $denominacion;
	var $idTipoDoc;
	var $documento;
	var $idPais;
	var $idProvincia;
	var $idPartido;
	var $idLocalidad;
	var $calle;
	var $direccion;
	var $codigoPostal;
	var $telefono;
	var $_email;
	var $idCondicionIva;
	var $logo;
	var $ta;
	var $cert;
	var $privatekey;
	var $inicioActividades;
	var $ingresosBrutos;
	var $puntoVenta;
	var $puntoVentaElectronico;
	var $contable;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'configuracion';
		$this->TableName = 'configuracion';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`configuracion`";
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
		$this->id = new cField('configuracion', 'configuracion', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = FALSE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// denominacion
		$this->denominacion = new cField('configuracion', 'configuracion', 'x_denominacion', 'denominacion', '`denominacion`', '`denominacion`', 200, -1, FALSE, '`denominacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denominacion->Sortable = TRUE; // Allow sort
		$this->fields['denominacion'] = &$this->denominacion;

		// idTipoDoc
		$this->idTipoDoc = new cField('configuracion', 'configuracion', 'x_idTipoDoc', 'idTipoDoc', '`idTipoDoc`', '`idTipoDoc`', 3, -1, FALSE, '`idTipoDoc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idTipoDoc->Sortable = TRUE; // Allow sort
		$this->idTipoDoc->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idTipoDoc->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idTipoDoc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idTipoDoc'] = &$this->idTipoDoc;

		// documento
		$this->documento = new cField('configuracion', 'configuracion', 'x_documento', 'documento', '`documento`', '`documento`', 200, -1, FALSE, '`documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->documento->Sortable = TRUE; // Allow sort
		$this->fields['documento'] = &$this->documento;

		// idPais
		$this->idPais = new cField('configuracion', 'configuracion', 'x_idPais', 'idPais', '`idPais`', '`idPais`', 3, -1, FALSE, '`idPais`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPais->Sortable = TRUE; // Allow sort
		$this->idPais->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPais->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPais->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPais'] = &$this->idPais;

		// idProvincia
		$this->idProvincia = new cField('configuracion', 'configuracion', 'x_idProvincia', 'idProvincia', '`idProvincia`', '`idProvincia`', 3, -1, FALSE, '`idProvincia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idProvincia->Sortable = TRUE; // Allow sort
		$this->idProvincia->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idProvincia->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idProvincia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idProvincia'] = &$this->idProvincia;

		// idPartido
		$this->idPartido = new cField('configuracion', 'configuracion', 'x_idPartido', 'idPartido', '`idPartido`', '`idPartido`', 3, -1, FALSE, '`idPartido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idPartido->Sortable = TRUE; // Allow sort
		$this->idPartido->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idPartido->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idPartido->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idPartido'] = &$this->idPartido;

		// idLocalidad
		$this->idLocalidad = new cField('configuracion', 'configuracion', 'x_idLocalidad', 'idLocalidad', '`idLocalidad`', '`idLocalidad`', 3, -1, FALSE, '`idLocalidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idLocalidad->Sortable = TRUE; // Allow sort
		$this->idLocalidad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idLocalidad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idLocalidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idLocalidad'] = &$this->idLocalidad;

		// calle
		$this->calle = new cField('configuracion', 'configuracion', 'x_calle', 'calle', '`calle`', '`calle`', 200, -1, FALSE, '`calle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->calle->Sortable = TRUE; // Allow sort
		$this->fields['calle'] = &$this->calle;

		// direccion
		$this->direccion = new cField('configuracion', 'configuracion', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, -1, FALSE, '`direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion->Sortable = TRUE; // Allow sort
		$this->fields['direccion'] = &$this->direccion;

		// codigoPostal
		$this->codigoPostal = new cField('configuracion', 'configuracion', 'x_codigoPostal', 'codigoPostal', '`codigoPostal`', '`codigoPostal`', 200, -1, FALSE, '`codigoPostal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->codigoPostal->Sortable = TRUE; // Allow sort
		$this->fields['codigoPostal'] = &$this->codigoPostal;

		// telefono
		$this->telefono = new cField('configuracion', 'configuracion', 'x_telefono', 'telefono', '`telefono`', '`telefono`', 200, -1, FALSE, '`telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telefono->Sortable = TRUE; // Allow sort
		$this->fields['telefono'] = &$this->telefono;

		// email
		$this->_email = new cField('configuracion', 'configuracion', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// idCondicionIva
		$this->idCondicionIva = new cField('configuracion', 'configuracion', 'x_idCondicionIva', 'idCondicionIva', '`idCondicionIva`', '`idCondicionIva`', 19, -1, FALSE, '`idCondicionIva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->idCondicionIva->Sortable = TRUE; // Allow sort
		$this->idCondicionIva->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->idCondicionIva->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->idCondicionIva->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idCondicionIva'] = &$this->idCondicionIva;

		// logo
		$this->logo = new cField('configuracion', 'configuracion', 'x_logo', 'logo', '`logo`', '`logo`', 200, -1, TRUE, '`logo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->logo->Sortable = TRUE; // Allow sort
		$this->fields['logo'] = &$this->logo;

		// ta
		$this->ta = new cField('configuracion', 'configuracion', 'x_ta', 'ta', '`ta`', '`ta`', 200, -1, FALSE, '`ta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ta->Sortable = FALSE; // Allow sort
		$this->fields['ta'] = &$this->ta;

		// cert
		$this->cert = new cField('configuracion', 'configuracion', 'x_cert', 'cert', '`cert`', '`cert`', 200, -1, FALSE, '`cert`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cert->Sortable = FALSE; // Allow sort
		$this->fields['cert'] = &$this->cert;

		// privatekey
		$this->privatekey = new cField('configuracion', 'configuracion', 'x_privatekey', 'privatekey', '`privatekey`', '`privatekey`', 200, -1, FALSE, '`privatekey`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->privatekey->Sortable = FALSE; // Allow sort
		$this->fields['privatekey'] = &$this->privatekey;

		// inicioActividades
		$this->inicioActividades = new cField('configuracion', 'configuracion', 'x_inicioActividades', 'inicioActividades', '`inicioActividades`', '`inicioActividades`', 200, -1, FALSE, '`inicioActividades`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->inicioActividades->Sortable = TRUE; // Allow sort
		$this->inicioActividades->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['inicioActividades'] = &$this->inicioActividades;

		// ingresosBrutos
		$this->ingresosBrutos = new cField('configuracion', 'configuracion', 'x_ingresosBrutos', 'ingresosBrutos', '`ingresosBrutos`', '`ingresosBrutos`', 200, -1, FALSE, '`ingresosBrutos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ingresosBrutos->Sortable = TRUE; // Allow sort
		$this->fields['ingresosBrutos'] = &$this->ingresosBrutos;

		// puntoVenta
		$this->puntoVenta = new cField('configuracion', 'configuracion', 'x_puntoVenta', 'puntoVenta', '`puntoVenta`', '`puntoVenta`', 200, -1, FALSE, '`puntoVenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->puntoVenta->Sortable = TRUE; // Allow sort
		$this->fields['puntoVenta'] = &$this->puntoVenta;

		// puntoVentaElectronico
		$this->puntoVentaElectronico = new cField('configuracion', 'configuracion', 'x_puntoVentaElectronico', 'puntoVentaElectronico', '`puntoVentaElectronico`', '`puntoVentaElectronico`', 200, -1, FALSE, '`puntoVentaElectronico`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->puntoVentaElectronico->Sortable = TRUE; // Allow sort
		$this->fields['puntoVentaElectronico'] = &$this->puntoVentaElectronico;

		// contable
		$this->contable = new cField('configuracion', 'configuracion', 'x_contable', 'contable', '`contable`', '`contable`', 3, -1, FALSE, '`contable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->contable->Sortable = FALSE; // Allow sort
		$this->contable->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['contable'] = &$this->contable;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`configuracion`";
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
			return "configuracionlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "configuracionlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("configuracionview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("configuracionview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "configuracionadd.php?" . $this->UrlParm($parm);
		else
			$url = "configuracionadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("configuracionedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("configuracionadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("configuraciondelete.php", $this->UrlParm());
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
		$this->idTipoDoc->setDbValue($rs->fields('idTipoDoc'));
		$this->documento->setDbValue($rs->fields('documento'));
		$this->idPais->setDbValue($rs->fields('idPais'));
		$this->idProvincia->setDbValue($rs->fields('idProvincia'));
		$this->idPartido->setDbValue($rs->fields('idPartido'));
		$this->idLocalidad->setDbValue($rs->fields('idLocalidad'));
		$this->calle->setDbValue($rs->fields('calle'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->codigoPostal->setDbValue($rs->fields('codigoPostal'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->idCondicionIva->setDbValue($rs->fields('idCondicionIva'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->ta->setDbValue($rs->fields('ta'));
		$this->cert->setDbValue($rs->fields('cert'));
		$this->privatekey->setDbValue($rs->fields('privatekey'));
		$this->inicioActividades->setDbValue($rs->fields('inicioActividades'));
		$this->ingresosBrutos->setDbValue($rs->fields('ingresosBrutos'));
		$this->puntoVenta->setDbValue($rs->fields('puntoVenta'));
		$this->puntoVentaElectronico->setDbValue($rs->fields('puntoVentaElectronico'));
		$this->contable->setDbValue($rs->fields('contable'));
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
		// idTipoDoc
		// documento
		// idPais
		// idProvincia
		// idPartido
		// idLocalidad
		// calle
		// direccion
		// codigoPostal
		// telefono
		// email
		// idCondicionIva
		// logo
		// ta

		$this->ta->CellCssStyle = "white-space: nowrap;";

		// cert
		$this->cert->CellCssStyle = "white-space: nowrap;";

		// privatekey
		$this->privatekey->CellCssStyle = "white-space: nowrap;";

		// inicioActividades
		// ingresosBrutos
		// puntoVenta
		// puntoVentaElectronico
		// contable

		$this->contable->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// idTipoDoc
		if (strval($this->idTipoDoc->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
		$sWhereWrk = "";
		$this->idTipoDoc->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTipoDoc, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTipoDoc->ViewValue = $this->idTipoDoc->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTipoDoc->ViewValue = $this->idTipoDoc->CurrentValue;
			}
		} else {
			$this->idTipoDoc->ViewValue = NULL;
		}
		$this->idTipoDoc->ViewCustomAttributes = "";

		// documento
		$this->documento->ViewValue = $this->documento->CurrentValue;
		$this->documento->ViewCustomAttributes = "";

		// idPais
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

		// codigoPostal
		$this->codigoPostal->ViewValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// idCondicionIva
		if (strval($this->idCondicionIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCondicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
		$sWhereWrk = "";
		$this->idCondicionIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCondicionIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->CurrentValue;
			}
		} else {
			$this->idCondicionIva->ViewValue = NULL;
		}
		$this->idCondicionIva->ViewCustomAttributes = "";

		// logo
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->ViewValue = $this->logo->Upload->DbValue;
		} else {
			$this->logo->ViewValue = "";
		}
		$this->logo->ViewCustomAttributes = "";

		// ta
		$this->ta->ViewValue = $this->ta->CurrentValue;
		$this->ta->ViewCustomAttributes = "";

		// cert
		$this->cert->ViewValue = $this->cert->CurrentValue;
		$this->cert->ViewCustomAttributes = "";

		// privatekey
		$this->privatekey->ViewValue = $this->privatekey->CurrentValue;
		$this->privatekey->ViewCustomAttributes = "";

		// inicioActividades
		$this->inicioActividades->ViewValue = $this->inicioActividades->CurrentValue;
		$this->inicioActividades->ViewCustomAttributes = "";

		// ingresosBrutos
		$this->ingresosBrutos->ViewValue = $this->ingresosBrutos->CurrentValue;
		$this->ingresosBrutos->ViewCustomAttributes = "";

		// puntoVenta
		$this->puntoVenta->ViewValue = $this->puntoVenta->CurrentValue;
		$this->puntoVenta->ViewCustomAttributes = "";

		// puntoVentaElectronico
		$this->puntoVentaElectronico->ViewValue = $this->puntoVentaElectronico->CurrentValue;
		$this->puntoVentaElectronico->ViewCustomAttributes = "";

		// contable
		$this->contable->ViewValue = $this->contable->CurrentValue;
		$this->contable->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// denominacion
		$this->denominacion->LinkCustomAttributes = "";
		$this->denominacion->HrefValue = "";
		$this->denominacion->TooltipValue = "";

		// idTipoDoc
		$this->idTipoDoc->LinkCustomAttributes = "";
		$this->idTipoDoc->HrefValue = "";
		$this->idTipoDoc->TooltipValue = "";

		// documento
		$this->documento->LinkCustomAttributes = "";
		$this->documento->HrefValue = "";
		$this->documento->TooltipValue = "";

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

		// codigoPostal
		$this->codigoPostal->LinkCustomAttributes = "";
		$this->codigoPostal->HrefValue = "";
		$this->codigoPostal->TooltipValue = "";

		// telefono
		$this->telefono->LinkCustomAttributes = "";
		$this->telefono->HrefValue = "";
		$this->telefono->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// idCondicionIva
		$this->idCondicionIva->LinkCustomAttributes = "";
		$this->idCondicionIva->HrefValue = "";
		$this->idCondicionIva->TooltipValue = "";

		// logo
		$this->logo->LinkCustomAttributes = "";
		$this->logo->HrefValue = "";
		$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;
		$this->logo->TooltipValue = "";

		// ta
		$this->ta->LinkCustomAttributes = "";
		$this->ta->HrefValue = "";
		$this->ta->TooltipValue = "";

		// cert
		$this->cert->LinkCustomAttributes = "";
		$this->cert->HrefValue = "";
		$this->cert->TooltipValue = "";

		// privatekey
		$this->privatekey->LinkCustomAttributes = "";
		$this->privatekey->HrefValue = "";
		$this->privatekey->TooltipValue = "";

		// inicioActividades
		$this->inicioActividades->LinkCustomAttributes = "";
		$this->inicioActividades->HrefValue = "";
		$this->inicioActividades->TooltipValue = "";

		// ingresosBrutos
		$this->ingresosBrutos->LinkCustomAttributes = "";
		$this->ingresosBrutos->HrefValue = "";
		$this->ingresosBrutos->TooltipValue = "";

		// puntoVenta
		$this->puntoVenta->LinkCustomAttributes = "";
		$this->puntoVenta->HrefValue = "";
		$this->puntoVenta->TooltipValue = "";

		// puntoVentaElectronico
		$this->puntoVentaElectronico->LinkCustomAttributes = "";
		$this->puntoVentaElectronico->HrefValue = "";
		$this->puntoVentaElectronico->TooltipValue = "";

		// contable
		$this->contable->LinkCustomAttributes = "";
		$this->contable->HrefValue = "";
		$this->contable->TooltipValue = "";

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

		// idTipoDoc
		$this->idTipoDoc->EditAttrs["class"] = "form-control";
		$this->idTipoDoc->EditCustomAttributes = "";

		// documento
		$this->documento->EditAttrs["class"] = "form-control";
		$this->documento->EditCustomAttributes = "";
		$this->documento->EditValue = $this->documento->CurrentValue;
		$this->documento->PlaceHolder = ew_RemoveHtml($this->documento->FldCaption());

		// idPais
		$this->idPais->EditAttrs["class"] = "form-control";
		$this->idPais->EditCustomAttributes = "";

		// idProvincia
		$this->idProvincia->EditAttrs["class"] = "form-control";
		$this->idProvincia->EditCustomAttributes = "";

		// idPartido
		$this->idPartido->EditAttrs["class"] = "form-control";
		$this->idPartido->EditCustomAttributes = "";

		// idLocalidad
		$this->idLocalidad->EditAttrs["class"] = "form-control";
		$this->idLocalidad->EditCustomAttributes = "";

		// calle
		$this->calle->EditAttrs["class"] = "form-control";
		$this->calle->EditCustomAttributes = "";
		$this->calle->EditValue = $this->calle->CurrentValue;
		$this->calle->PlaceHolder = ew_RemoveHtml($this->calle->FldCaption());

		// direccion
		$this->direccion->EditAttrs["class"] = "form-control";
		$this->direccion->EditCustomAttributes = "";
		$this->direccion->EditValue = $this->direccion->CurrentValue;
		$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

		// codigoPostal
		$this->codigoPostal->EditAttrs["class"] = "form-control";
		$this->codigoPostal->EditCustomAttributes = "";
		$this->codigoPostal->EditValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->PlaceHolder = ew_RemoveHtml($this->codigoPostal->FldCaption());

		// telefono
		$this->telefono->EditAttrs["class"] = "form-control";
		$this->telefono->EditCustomAttributes = "";
		$this->telefono->EditValue = $this->telefono->CurrentValue;
		$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// idCondicionIva
		$this->idCondicionIva->EditAttrs["class"] = "form-control";
		$this->idCondicionIva->EditCustomAttributes = "";

		// logo
		$this->logo->EditAttrs["class"] = "form-control";
		$this->logo->EditCustomAttributes = "";
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->EditValue = $this->logo->Upload->DbValue;
		} else {
			$this->logo->EditValue = "";
		}
		if (!ew_Empty($this->logo->CurrentValue))
			$this->logo->Upload->FileName = $this->logo->CurrentValue;

		// ta
		$this->ta->EditAttrs["class"] = "form-control";
		$this->ta->EditCustomAttributes = "";
		$this->ta->EditValue = $this->ta->CurrentValue;
		$this->ta->PlaceHolder = ew_RemoveHtml($this->ta->FldCaption());

		// cert
		$this->cert->EditAttrs["class"] = "form-control";
		$this->cert->EditCustomAttributes = "";
		$this->cert->EditValue = $this->cert->CurrentValue;
		$this->cert->PlaceHolder = ew_RemoveHtml($this->cert->FldCaption());

		// privatekey
		$this->privatekey->EditAttrs["class"] = "form-control";
		$this->privatekey->EditCustomAttributes = "";
		$this->privatekey->EditValue = $this->privatekey->CurrentValue;
		$this->privatekey->PlaceHolder = ew_RemoveHtml($this->privatekey->FldCaption());

		// inicioActividades
		$this->inicioActividades->EditAttrs["class"] = "form-control";
		$this->inicioActividades->EditCustomAttributes = "";
		$this->inicioActividades->EditValue = $this->inicioActividades->CurrentValue;
		$this->inicioActividades->PlaceHolder = ew_RemoveHtml($this->inicioActividades->FldCaption());

		// ingresosBrutos
		$this->ingresosBrutos->EditAttrs["class"] = "form-control";
		$this->ingresosBrutos->EditCustomAttributes = "";
		$this->ingresosBrutos->EditValue = $this->ingresosBrutos->CurrentValue;
		$this->ingresosBrutos->PlaceHolder = ew_RemoveHtml($this->ingresosBrutos->FldCaption());

		// puntoVenta
		$this->puntoVenta->EditAttrs["class"] = "form-control";
		$this->puntoVenta->EditCustomAttributes = "";
		$this->puntoVenta->EditValue = $this->puntoVenta->CurrentValue;
		$this->puntoVenta->PlaceHolder = ew_RemoveHtml($this->puntoVenta->FldCaption());

		// puntoVentaElectronico
		$this->puntoVentaElectronico->EditAttrs["class"] = "form-control";
		$this->puntoVentaElectronico->EditCustomAttributes = "";
		$this->puntoVentaElectronico->EditValue = $this->puntoVentaElectronico->CurrentValue;
		$this->puntoVentaElectronico->PlaceHolder = ew_RemoveHtml($this->puntoVentaElectronico->FldCaption());

		// contable
		$this->contable->EditAttrs["class"] = "form-control";
		$this->contable->EditCustomAttributes = "";
		$this->contable->EditValue = $this->contable->CurrentValue;
		$this->contable->PlaceHolder = ew_RemoveHtml($this->contable->FldCaption());

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
					if ($this->idTipoDoc->Exportable) $Doc->ExportCaption($this->idTipoDoc);
					if ($this->documento->Exportable) $Doc->ExportCaption($this->documento);
					if ($this->idPais->Exportable) $Doc->ExportCaption($this->idPais);
					if ($this->idProvincia->Exportable) $Doc->ExportCaption($this->idProvincia);
					if ($this->idPartido->Exportable) $Doc->ExportCaption($this->idPartido);
					if ($this->idLocalidad->Exportable) $Doc->ExportCaption($this->idLocalidad);
					if ($this->calle->Exportable) $Doc->ExportCaption($this->calle);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->codigoPostal->Exportable) $Doc->ExportCaption($this->codigoPostal);
					if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->idCondicionIva->Exportable) $Doc->ExportCaption($this->idCondicionIva);
					if ($this->logo->Exportable) $Doc->ExportCaption($this->logo);
					if ($this->inicioActividades->Exportable) $Doc->ExportCaption($this->inicioActividades);
					if ($this->ingresosBrutos->Exportable) $Doc->ExportCaption($this->ingresosBrutos);
					if ($this->puntoVenta->Exportable) $Doc->ExportCaption($this->puntoVenta);
					if ($this->puntoVentaElectronico->Exportable) $Doc->ExportCaption($this->puntoVentaElectronico);
				} else {
					if ($this->denominacion->Exportable) $Doc->ExportCaption($this->denominacion);
					if ($this->idTipoDoc->Exportable) $Doc->ExportCaption($this->idTipoDoc);
					if ($this->documento->Exportable) $Doc->ExportCaption($this->documento);
					if ($this->idPais->Exportable) $Doc->ExportCaption($this->idPais);
					if ($this->idProvincia->Exportable) $Doc->ExportCaption($this->idProvincia);
					if ($this->idPartido->Exportable) $Doc->ExportCaption($this->idPartido);
					if ($this->idLocalidad->Exportable) $Doc->ExportCaption($this->idLocalidad);
					if ($this->calle->Exportable) $Doc->ExportCaption($this->calle);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->codigoPostal->Exportable) $Doc->ExportCaption($this->codigoPostal);
					if ($this->telefono->Exportable) $Doc->ExportCaption($this->telefono);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->idCondicionIva->Exportable) $Doc->ExportCaption($this->idCondicionIva);
					if ($this->logo->Exportable) $Doc->ExportCaption($this->logo);
					if ($this->inicioActividades->Exportable) $Doc->ExportCaption($this->inicioActividades);
					if ($this->ingresosBrutos->Exportable) $Doc->ExportCaption($this->ingresosBrutos);
					if ($this->puntoVenta->Exportable) $Doc->ExportCaption($this->puntoVenta);
					if ($this->puntoVentaElectronico->Exportable) $Doc->ExportCaption($this->puntoVentaElectronico);
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
						if ($this->idTipoDoc->Exportable) $Doc->ExportField($this->idTipoDoc);
						if ($this->documento->Exportable) $Doc->ExportField($this->documento);
						if ($this->idPais->Exportable) $Doc->ExportField($this->idPais);
						if ($this->idProvincia->Exportable) $Doc->ExportField($this->idProvincia);
						if ($this->idPartido->Exportable) $Doc->ExportField($this->idPartido);
						if ($this->idLocalidad->Exportable) $Doc->ExportField($this->idLocalidad);
						if ($this->calle->Exportable) $Doc->ExportField($this->calle);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->codigoPostal->Exportable) $Doc->ExportField($this->codigoPostal);
						if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->idCondicionIva->Exportable) $Doc->ExportField($this->idCondicionIva);
						if ($this->logo->Exportable) $Doc->ExportField($this->logo);
						if ($this->inicioActividades->Exportable) $Doc->ExportField($this->inicioActividades);
						if ($this->ingresosBrutos->Exportable) $Doc->ExportField($this->ingresosBrutos);
						if ($this->puntoVenta->Exportable) $Doc->ExportField($this->puntoVenta);
						if ($this->puntoVentaElectronico->Exportable) $Doc->ExportField($this->puntoVentaElectronico);
					} else {
						if ($this->denominacion->Exportable) $Doc->ExportField($this->denominacion);
						if ($this->idTipoDoc->Exportable) $Doc->ExportField($this->idTipoDoc);
						if ($this->documento->Exportable) $Doc->ExportField($this->documento);
						if ($this->idPais->Exportable) $Doc->ExportField($this->idPais);
						if ($this->idProvincia->Exportable) $Doc->ExportField($this->idProvincia);
						if ($this->idPartido->Exportable) $Doc->ExportField($this->idPartido);
						if ($this->idLocalidad->Exportable) $Doc->ExportField($this->idLocalidad);
						if ($this->calle->Exportable) $Doc->ExportField($this->calle);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->codigoPostal->Exportable) $Doc->ExportField($this->codigoPostal);
						if ($this->telefono->Exportable) $Doc->ExportField($this->telefono);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->idCondicionIva->Exportable) $Doc->ExportField($this->idCondicionIva);
						if ($this->logo->Exportable) $Doc->ExportField($this->logo);
						if ($this->inicioActividades->Exportable) $Doc->ExportField($this->inicioActividades);
						if ($this->ingresosBrutos->Exportable) $Doc->ExportField($this->ingresosBrutos);
						if ($this->puntoVenta->Exportable) $Doc->ExportField($this->puntoVenta);
						if ($this->puntoVentaElectronico->Exportable) $Doc->ExportField($this->puntoVentaElectronico);
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
