<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tercerosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$terceros_delete = NULL; // Initialize page object first

class cterceros_delete extends cterceros {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros';

	// Page object name
	var $PageObjName = 'terceros_delete';

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

		// Table object (terceros)
		if (!isset($GLOBALS["terceros"]) || get_class($GLOBALS["terceros"]) == "cterceros") {
			$GLOBALS["terceros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["terceros"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'terceros', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("terceroslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idTipoTercero->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->direccion->SetVisibility();
		$this->documento->SetVisibility();
		$this->idTransporte->SetVisibility();
		$this->limiteDescubierto->SetVisibility();
		$this->codigoPostal->SetVisibility();
		$this->codigoPostalFiscal->SetVisibility();
		$this->condicionVenta->SetVisibility();

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
		global $EW_EXPORT, $terceros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($terceros);
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
			$this->Page_Terminate("terceroslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in terceros class, tercerosinfo.php

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
				$this->Page_Terminate("terceroslist.php"); // Return to list
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->idTipoTercero->setDbValue($rs->fields('idTipoTercero'));
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->razonSocial->setDbValue($rs->fields('razonSocial'));
		$this->denominacionCorta->setDbValue($rs->fields('denominacionCorta'));
		$this->idPais->setDbValue($rs->fields('idPais'));
		if (array_key_exists('EV__idPais', $rs->fields)) {
			$this->idPais->VirtualValue = $rs->fields('EV__idPais'); // Set up virtual field value
		} else {
			$this->idPais->VirtualValue = ""; // Clear value
		}
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTipoTercero->DbValue = $row['idTipoTercero'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->razonSocial->DbValue = $row['razonSocial'];
		$this->denominacionCorta->DbValue = $row['denominacionCorta'];
		$this->idPais->DbValue = $row['idPais'];
		$this->idProvincia->DbValue = $row['idProvincia'];
		$this->idPartido->DbValue = $row['idPartido'];
		$this->idLocalidad->DbValue = $row['idLocalidad'];
		$this->calle->DbValue = $row['calle'];
		$this->direccion->DbValue = $row['direccion'];
		$this->domicilioFiscal->DbValue = $row['domicilioFiscal'];
		$this->idPaisFiscal->DbValue = $row['idPaisFiscal'];
		$this->idProvinciaFiscal->DbValue = $row['idProvinciaFiscal'];
		$this->idPartidoFiscal->DbValue = $row['idPartidoFiscal'];
		$this->idLocalidadFiscal->DbValue = $row['idLocalidadFiscal'];
		$this->calleFiscal->DbValue = $row['calleFiscal'];
		$this->direccionFiscal->DbValue = $row['direccionFiscal'];
		$this->tipoDoc->DbValue = $row['tipoDoc'];
		$this->documento->DbValue = $row['documento'];
		$this->condicionIva->DbValue = $row['condicionIva'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->idTransporte->DbValue = $row['idTransporte'];
		$this->idVendedor->DbValue = $row['idVendedor'];
		$this->idCobrador->DbValue = $row['idCobrador'];
		$this->comision->DbValue = $row['comision'];
		$this->idListaPrecios->DbValue = $row['idListaPrecios'];
		$this->dtoCliente->DbValue = $row['dtoCliente'];
		$this->dto1->DbValue = $row['dto1'];
		$this->dto2->DbValue = $row['dto2'];
		$this->dto3->DbValue = $row['dto3'];
		$this->limiteDescubierto->DbValue = $row['limiteDescubierto'];
		$this->codigoPostal->DbValue = $row['codigoPostal'];
		$this->codigoPostalFiscal->DbValue = $row['codigoPostalFiscal'];
		$this->condicionVenta->DbValue = $row['condicionVenta'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->limiteDescubierto->FormValue == $this->limiteDescubierto->CurrentValue && is_numeric(ew_StrToFloat($this->limiteDescubierto->CurrentValue)))
			$this->limiteDescubierto->CurrentValue = ew_StrToFloat($this->limiteDescubierto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// idTipoTercero
			$this->idTipoTercero->LinkCustomAttributes = "";
			$this->idTipoTercero->HrefValue = "";
			$this->idTipoTercero->TooltipValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";
			$this->documento->TooltipValue = "";

			// idTransporte
			$this->idTransporte->LinkCustomAttributes = "";
			$this->idTransporte->HrefValue = "";
			$this->idTransporte->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("terceroslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($terceros_delete)) $terceros_delete = new cterceros_delete();

// Page init
$terceros_delete->Page_Init();

// Page main
$terceros_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftercerosdelete = new ew_Form("ftercerosdelete", "delete");

// Form_CustomValidate event
ftercerosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftercerosdelete.ValidateRequired = true;
<?php } else { ?>
ftercerosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftercerosdelete.Lists["x_idTipoTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dterceros"};
ftercerosdelete.Lists["x_idTransporte"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

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
<?php $terceros_delete->ShowPageHeader(); ?>
<?php
$terceros_delete->ShowMessage();
?>
<form name="ftercerosdelete" id="ftercerosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($terceros_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $terceros->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
		<th><span id="elh_terceros_idTipoTercero" class="terceros_idTipoTercero"><?php echo $terceros->idTipoTercero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
		<th><span id="elh_terceros_denominacion" class="terceros_denominacion"><?php echo $terceros->denominacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->direccion->Visible) { // direccion ?>
		<th><span id="elh_terceros_direccion" class="terceros_direccion"><?php echo $terceros->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->documento->Visible) { // documento ?>
		<th><span id="elh_terceros_documento" class="terceros_documento"><?php echo $terceros->documento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
		<th><span id="elh_terceros_idTransporte" class="terceros_idTransporte"><?php echo $terceros->idTransporte->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
		<th><span id="elh_terceros_limiteDescubierto" class="terceros_limiteDescubierto"><?php echo $terceros->limiteDescubierto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
		<th><span id="elh_terceros_codigoPostal" class="terceros_codigoPostal"><?php echo $terceros->codigoPostal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
		<th><span id="elh_terceros_codigoPostalFiscal" class="terceros_codigoPostalFiscal"><?php echo $terceros->codigoPostalFiscal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
		<th><span id="elh_terceros_condicionVenta" class="terceros_condicionVenta"><?php echo $terceros->condicionVenta->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$terceros_delete->RecCnt = 0;
$i = 0;
while (!$terceros_delete->Recordset->EOF) {
	$terceros_delete->RecCnt++;
	$terceros_delete->RowCnt++;

	// Set row properties
	$terceros->ResetAttrs();
	$terceros->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$terceros_delete->LoadRowValues($terceros_delete->Recordset);

	// Render row
	$terceros_delete->RenderRow();
?>
	<tr<?php echo $terceros->RowAttributes() ?>>
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
		<td<?php echo $terceros->idTipoTercero->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_idTipoTercero" class="terceros_idTipoTercero">
<span<?php echo $terceros->idTipoTercero->ViewAttributes() ?>>
<?php echo $terceros->idTipoTercero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
		<td<?php echo $terceros->denominacion->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_denominacion" class="terceros_denominacion">
<span<?php echo $terceros->denominacion->ViewAttributes() ?>>
<?php echo $terceros->denominacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->direccion->Visible) { // direccion ?>
		<td<?php echo $terceros->direccion->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_direccion" class="terceros_direccion">
<span<?php echo $terceros->direccion->ViewAttributes() ?>>
<?php echo $terceros->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->documento->Visible) { // documento ?>
		<td<?php echo $terceros->documento->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_documento" class="terceros_documento">
<span<?php echo $terceros->documento->ViewAttributes() ?>>
<?php echo $terceros->documento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
		<td<?php echo $terceros->idTransporte->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_idTransporte" class="terceros_idTransporte">
<span<?php echo $terceros->idTransporte->ViewAttributes() ?>>
<?php echo $terceros->idTransporte->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
		<td<?php echo $terceros->limiteDescubierto->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_limiteDescubierto" class="terceros_limiteDescubierto">
<span<?php echo $terceros->limiteDescubierto->ViewAttributes() ?>>
<?php echo $terceros->limiteDescubierto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
		<td<?php echo $terceros->codigoPostal->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_codigoPostal" class="terceros_codigoPostal">
<span<?php echo $terceros->codigoPostal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
		<td<?php echo $terceros->codigoPostalFiscal->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_codigoPostalFiscal" class="terceros_codigoPostalFiscal">
<span<?php echo $terceros->codigoPostalFiscal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostalFiscal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
		<td<?php echo $terceros->condicionVenta->CellAttributes() ?>>
<span id="el<?php echo $terceros_delete->RowCnt ?>_terceros_condicionVenta" class="terceros_condicionVenta">
<span<?php echo $terceros->condicionVenta->ViewAttributes() ?>>
<?php echo $terceros->condicionVenta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$terceros_delete->Recordset->MoveNext();
}
$terceros_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $terceros_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftercerosdelete.Init();
</script>
<?php
$terceros_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	ocultarMostrarCampos();
</script>
<?php include_once "footer.php" ?>
<?php
$terceros_delete->Page_Terminate();
?>
