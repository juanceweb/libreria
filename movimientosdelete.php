<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "movimientosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$movimientos_delete = NULL; // Initialize page object first

class cmovimientos_delete extends cmovimientos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos';

	// Page object name
	var $PageObjName = 'movimientos_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (movimientos)
		if (!isset($GLOBALS["movimientos"]) || get_class($GLOBALS["movimientos"]) == "cmovimientos") {
			$GLOBALS["movimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["movimientos"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'movimientos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("movimientoslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nroComprobanteCompleto->SetVisibility();
		$this->tipoMovimiento->SetVisibility();
		$this->fecha->SetVisibility();
		$this->codTercero->SetVisibility();
		$this->idTercero->SetVisibility();
		$this->idComprobante->SetVisibility();
		$this->importeTotal->SetVisibility();
		$this->importeIva->SetVisibility();
		$this->importeNeto->SetVisibility();
		$this->importeCancelado->SetVisibility();
		$this->idEstado->SetVisibility();
		$this->movimientosAsociados->SetVisibility();
		$this->condicionVenta->SetVisibility();
		$this->vigencia->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $movimientos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($movimientos);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("movimientoslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in movimientos class, movimientosinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("movimientoslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nroComprobanteCompleto->DbValue = $row['nroComprobanteCompleto'];
		$this->tipoMovimiento->DbValue = $row['tipoMovimiento'];
		$this->fecha->DbValue = $row['fecha'];
		$this->idSociedad->DbValue = $row['idSociedad'];
		$this->codTercero->DbValue = $row['codTercero'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->idComprobante->DbValue = $row['idComprobante'];
		$this->importeTotal->DbValue = $row['importeTotal'];
		$this->importeIva->DbValue = $row['importeIva'];
		$this->importeNeto->DbValue = $row['importeNeto'];
		$this->importeCancelado->DbValue = $row['importeCancelado'];
		$this->nombreTercero->DbValue = $row['nombreTercero'];
		$this->idDocTercero->DbValue = $row['idDocTercero'];
		$this->nroDocTercero->DbValue = $row['nroDocTercero'];
		$this->ptoVenta->DbValue = $row['ptoVenta'];
		$this->nroComprobante->DbValue = $row['nroComprobante'];
		$this->cae->DbValue = $row['cae'];
		$this->vtoCae->DbValue = $row['vtoCae'];
		$this->idEstado->DbValue = $row['idEstado'];
		$this->idUsuarioAlta->DbValue = $row['idUsuarioAlta'];
		$this->fechaAlta->DbValue = $row['fechaAlta'];
		$this->idUsuarioModificacion->DbValue = $row['idUsuarioModificacion'];
		$this->fechaModificacion->DbValue = $row['fechaModificacion'];
		$this->contable->DbValue = $row['contable'];
		$this->archivo->DbValue = $row['archivo'];
		$this->valorDolar->DbValue = $row['valorDolar'];
		$this->comentarios->DbValue = $row['comentarios'];
		$this->articulosAsociados->DbValue = $row['articulosAsociados'];
		$this->movimientosAsociados->DbValue = $row['movimientosAsociados'];
		$this->condicionVenta->DbValue = $row['condicionVenta'];
		$this->vigencia->DbValue = $row['vigencia'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->importeTotal->FormValue == $this->importeTotal->CurrentValue && is_numeric(ew_StrToFloat($this->importeTotal->CurrentValue)))
			$this->importeTotal->CurrentValue = ew_StrToFloat($this->importeTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeIva->FormValue == $this->importeIva->CurrentValue && is_numeric(ew_StrToFloat($this->importeIva->CurrentValue)))
			$this->importeIva->CurrentValue = ew_StrToFloat($this->importeIva->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeNeto->FormValue == $this->importeNeto->CurrentValue && is_numeric(ew_StrToFloat($this->importeNeto->CurrentValue)))
			$this->importeNeto->CurrentValue = ew_StrToFloat($this->importeNeto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeCancelado->FormValue == $this->importeCancelado->CurrentValue && is_numeric(ew_StrToFloat($this->importeCancelado->CurrentValue)))
			$this->importeCancelado->CurrentValue = ew_StrToFloat($this->importeCancelado->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// idEstado
		if (strval($this->idEstado->CurrentValue) <> "") {
			$this->idEstado->ViewValue = $this->idEstado->OptionCaption($this->idEstado->CurrentValue);
		} else {
			$this->idEstado->ViewValue = NULL;
		}
		$this->idEstado->ViewCustomAttributes = "";

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

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
			$this->idEstado->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($movimientos_delete)) $movimientos_delete = new cmovimientos_delete();

// Page init
$movimientos_delete->Page_Init();

// Page main
$movimientos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fmovimientosdelete = new ew_Form("fmovimientosdelete", "delete");

// Form_CustomValidate event
fmovimientosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientosdelete.ValidateRequired = true;
<?php } else { ?>
fmovimientosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmovimientosdelete.Lists["x_tipoMovimiento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosdelete.Lists["x_tipoMovimiento"].Options = <?php echo json_encode($movimientos->tipoMovimiento->Options()) ?>;
fmovimientosdelete.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fmovimientosdelete.Lists["x_idComprobante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fmovimientosdelete.Lists["x_idEstado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosdelete.Lists["x_idEstado"].Options = <?php echo json_encode($movimientos->idEstado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $movimientos_delete->ShowPageHeader(); ?>
<?php
$movimientos_delete->ShowMessage();
?>
<form name="fmovimientosdelete" id="fmovimientosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($movimientos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $movimientos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($movimientos->id->Visible) { // id ?>
		<th><span id="elh_movimientos_id" class="movimientos_id"><?php echo $movimientos->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
		<th><span id="elh_movimientos_nroComprobanteCompleto" class="movimientos_nroComprobanteCompleto"><?php echo $movimientos->nroComprobanteCompleto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
		<th><span id="elh_movimientos_tipoMovimiento" class="movimientos_tipoMovimiento"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->fecha->Visible) { // fecha ?>
		<th><span id="elh_movimientos_fecha" class="movimientos_fecha"><?php echo $movimientos->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
		<th><span id="elh_movimientos_codTercero" class="movimientos_codTercero"><?php echo $movimientos->codTercero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
		<th><span id="elh_movimientos_idTercero" class="movimientos_idTercero"><?php echo $movimientos->idTercero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
		<th><span id="elh_movimientos_idComprobante" class="movimientos_idComprobante"><?php echo $movimientos->idComprobante->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
		<th><span id="elh_movimientos_importeTotal" class="movimientos_importeTotal"><?php echo $movimientos->importeTotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
		<th><span id="elh_movimientos_importeIva" class="movimientos_importeIva"><?php echo $movimientos->importeIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
		<th><span id="elh_movimientos_importeNeto" class="movimientos_importeNeto"><?php echo $movimientos->importeNeto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
		<th><span id="elh_movimientos_importeCancelado" class="movimientos_importeCancelado"><?php echo $movimientos->importeCancelado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
		<th><span id="elh_movimientos_idEstado" class="movimientos_idEstado"><?php echo $movimientos->idEstado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
		<th><span id="elh_movimientos_movimientosAsociados" class="movimientos_movimientosAsociados"><?php echo $movimientos->movimientosAsociados->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
		<th><span id="elh_movimientos_condicionVenta" class="movimientos_condicionVenta"><?php echo $movimientos->condicionVenta->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
		<th><span id="elh_movimientos_vigencia" class="movimientos_vigencia"><?php echo $movimientos->vigencia->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$movimientos_delete->RecCnt = 0;
$i = 0;
while (!$movimientos_delete->Recordset->EOF) {
	$movimientos_delete->RecCnt++;
	$movimientos_delete->RowCnt++;

	// Set row properties
	$movimientos->ResetAttrs();
	$movimientos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$movimientos_delete->LoadRowValues($movimientos_delete->Recordset);

	// Render row
	$movimientos_delete->RenderRow();
?>
	<tr<?php echo $movimientos->RowAttributes() ?>>
<?php if ($movimientos->id->Visible) { // id ?>
		<td<?php echo $movimientos->id->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_id" class="movimientos_id">
<span<?php echo $movimientos->id->ViewAttributes() ?>>
<?php echo $movimientos->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
		<td<?php echo $movimientos->nroComprobanteCompleto->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_nroComprobanteCompleto" class="movimientos_nroComprobanteCompleto">
<span<?php echo $movimientos->nroComprobanteCompleto->ViewAttributes() ?>>
<?php echo $movimientos->nroComprobanteCompleto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
		<td<?php echo $movimientos->tipoMovimiento->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_tipoMovimiento" class="movimientos_tipoMovimiento">
<span<?php echo $movimientos->tipoMovimiento->ViewAttributes() ?>>
<?php echo $movimientos->tipoMovimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->fecha->Visible) { // fecha ?>
		<td<?php echo $movimientos->fecha->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_fecha" class="movimientos_fecha">
<span<?php echo $movimientos->fecha->ViewAttributes() ?>>
<?php echo $movimientos->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
		<td<?php echo $movimientos->codTercero->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_codTercero" class="movimientos_codTercero">
<span<?php echo $movimientos->codTercero->ViewAttributes() ?>>
<?php echo $movimientos->codTercero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
		<td<?php echo $movimientos->idTercero->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_idTercero" class="movimientos_idTercero">
<span<?php echo $movimientos->idTercero->ViewAttributes() ?>>
<?php echo $movimientos->idTercero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
		<td<?php echo $movimientos->idComprobante->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_idComprobante" class="movimientos_idComprobante">
<span<?php echo $movimientos->idComprobante->ViewAttributes() ?>>
<?php echo $movimientos->idComprobante->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
		<td<?php echo $movimientos->importeTotal->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_importeTotal" class="movimientos_importeTotal">
<span<?php echo $movimientos->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos->importeTotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
		<td<?php echo $movimientos->importeIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_importeIva" class="movimientos_importeIva">
<span<?php echo $movimientos->importeIva->ViewAttributes() ?>>
<?php echo $movimientos->importeIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
		<td<?php echo $movimientos->importeNeto->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_importeNeto" class="movimientos_importeNeto">
<span<?php echo $movimientos->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos->importeNeto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
		<td<?php echo $movimientos->importeCancelado->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_importeCancelado" class="movimientos_importeCancelado">
<span<?php echo $movimientos->importeCancelado->ViewAttributes() ?>>
<?php echo $movimientos->importeCancelado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
		<td<?php echo $movimientos->idEstado->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_idEstado" class="movimientos_idEstado">
<span<?php echo $movimientos->idEstado->ViewAttributes() ?>>
<?php echo $movimientos->idEstado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
		<td<?php echo $movimientos->movimientosAsociados->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_movimientosAsociados" class="movimientos_movimientosAsociados">
<span<?php echo $movimientos->movimientosAsociados->ViewAttributes() ?>>
<?php echo $movimientos->movimientosAsociados->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
		<td<?php echo $movimientos->condicionVenta->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_condicionVenta" class="movimientos_condicionVenta">
<span<?php echo $movimientos->condicionVenta->ViewAttributes() ?>>
<?php echo $movimientos->condicionVenta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
		<td<?php echo $movimientos->vigencia->CellAttributes() ?>>
<span id="el<?php echo $movimientos_delete->RowCnt ?>_movimientos_vigencia" class="movimientos_vigencia">
<span<?php echo $movimientos->vigencia->ViewAttributes() ?>>
<?php echo $movimientos->vigencia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$movimientos_delete->Recordset->MoveNext();
}
$movimientos_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $movimientos_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fmovimientosdelete.Init();
</script>
<?php
$movimientos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$movimientos_delete->Page_Terminate();
?>
