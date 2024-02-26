<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "configuracioninfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$configuracion_edit = NULL; // Initialize page object first

class cconfiguracion_edit extends cconfiguracion {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'configuracion';

	// Page object name
	var $PageObjName = 'configuracion_edit';

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

		// Table object (configuracion)
		if (!isset($GLOBALS["configuracion"]) || get_class($GLOBALS["configuracion"]) == "cconfiguracion") {
			$GLOBALS["configuracion"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["configuracion"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'configuracion', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("configuracionlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->denominacion->SetVisibility();
		$this->idTipoDoc->SetVisibility();
		$this->documento->SetVisibility();
		$this->idPais->SetVisibility();
		$this->idProvincia->SetVisibility();
		$this->idPartido->SetVisibility();
		$this->idLocalidad->SetVisibility();
		$this->calle->SetVisibility();
		$this->direccion->SetVisibility();
		$this->codigoPostal->SetVisibility();
		$this->telefono->SetVisibility();
		$this->_email->SetVisibility();
		$this->idCondicionIva->SetVisibility();
		$this->logo->SetVisibility();
		$this->inicioActividades->SetVisibility();
		$this->ingresosBrutos->SetVisibility();
		$this->puntoVenta->SetVisibility();
		$this->puntoVentaElectronico->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $configuracion;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($configuracion);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("configuracionlist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("configuracionlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "configuracionlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->logo->Upload->Index = $objForm->Index;
		$this->logo->Upload->UploadFile();
		$this->logo->CurrentValue = $this->logo->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->idTipoDoc->FldIsDetailKey) {
			$this->idTipoDoc->setFormValue($objForm->GetValue("x_idTipoDoc"));
		}
		if (!$this->documento->FldIsDetailKey) {
			$this->documento->setFormValue($objForm->GetValue("x_documento"));
		}
		if (!$this->idPais->FldIsDetailKey) {
			$this->idPais->setFormValue($objForm->GetValue("x_idPais"));
		}
		if (!$this->idProvincia->FldIsDetailKey) {
			$this->idProvincia->setFormValue($objForm->GetValue("x_idProvincia"));
		}
		if (!$this->idPartido->FldIsDetailKey) {
			$this->idPartido->setFormValue($objForm->GetValue("x_idPartido"));
		}
		if (!$this->idLocalidad->FldIsDetailKey) {
			$this->idLocalidad->setFormValue($objForm->GetValue("x_idLocalidad"));
		}
		if (!$this->calle->FldIsDetailKey) {
			$this->calle->setFormValue($objForm->GetValue("x_calle"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->codigoPostal->FldIsDetailKey) {
			$this->codigoPostal->setFormValue($objForm->GetValue("x_codigoPostal"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->idCondicionIva->FldIsDetailKey) {
			$this->idCondicionIva->setFormValue($objForm->GetValue("x_idCondicionIva"));
		}
		if (!$this->inicioActividades->FldIsDetailKey) {
			$this->inicioActividades->setFormValue($objForm->GetValue("x_inicioActividades"));
		}
		if (!$this->ingresosBrutos->FldIsDetailKey) {
			$this->ingresosBrutos->setFormValue($objForm->GetValue("x_ingresosBrutos"));
		}
		if (!$this->puntoVenta->FldIsDetailKey) {
			$this->puntoVenta->setFormValue($objForm->GetValue("x_puntoVenta"));
		}
		if (!$this->puntoVentaElectronico->FldIsDetailKey) {
			$this->puntoVentaElectronico->setFormValue($objForm->GetValue("x_puntoVentaElectronico"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->idTipoDoc->CurrentValue = $this->idTipoDoc->FormValue;
		$this->documento->CurrentValue = $this->documento->FormValue;
		$this->idPais->CurrentValue = $this->idPais->FormValue;
		$this->idProvincia->CurrentValue = $this->idProvincia->FormValue;
		$this->idPartido->CurrentValue = $this->idPartido->FormValue;
		$this->idLocalidad->CurrentValue = $this->idLocalidad->FormValue;
		$this->calle->CurrentValue = $this->calle->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->codigoPostal->CurrentValue = $this->codigoPostal->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->idCondicionIva->CurrentValue = $this->idCondicionIva->FormValue;
		$this->inicioActividades->CurrentValue = $this->inicioActividades->FormValue;
		$this->ingresosBrutos->CurrentValue = $this->ingresosBrutos->FormValue;
		$this->puntoVenta->CurrentValue = $this->puntoVenta->FormValue;
		$this->puntoVentaElectronico->CurrentValue = $this->puntoVentaElectronico->FormValue;
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
		$this->logo->CurrentValue = $this->logo->Upload->DbValue;
		$this->ta->setDbValue($rs->fields('ta'));
		$this->cert->setDbValue($rs->fields('cert'));
		$this->privatekey->setDbValue($rs->fields('privatekey'));
		$this->inicioActividades->setDbValue($rs->fields('inicioActividades'));
		$this->ingresosBrutos->setDbValue($rs->fields('ingresosBrutos'));
		$this->puntoVenta->setDbValue($rs->fields('puntoVenta'));
		$this->puntoVentaElectronico->setDbValue($rs->fields('puntoVentaElectronico'));
		$this->contable->setDbValue($rs->fields('contable'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->idTipoDoc->DbValue = $row['idTipoDoc'];
		$this->documento->DbValue = $row['documento'];
		$this->idPais->DbValue = $row['idPais'];
		$this->idProvincia->DbValue = $row['idProvincia'];
		$this->idPartido->DbValue = $row['idPartido'];
		$this->idLocalidad->DbValue = $row['idLocalidad'];
		$this->calle->DbValue = $row['calle'];
		$this->direccion->DbValue = $row['direccion'];
		$this->codigoPostal->DbValue = $row['codigoPostal'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->idCondicionIva->DbValue = $row['idCondicionIva'];
		$this->logo->Upload->DbValue = $row['logo'];
		$this->ta->DbValue = $row['ta'];
		$this->cert->DbValue = $row['cert'];
		$this->privatekey->DbValue = $row['privatekey'];
		$this->inicioActividades->DbValue = $row['inicioActividades'];
		$this->ingresosBrutos->DbValue = $row['ingresosBrutos'];
		$this->puntoVenta->DbValue = $row['puntoVenta'];
		$this->puntoVentaElectronico->DbValue = $row['puntoVentaElectronico'];
		$this->contable->DbValue = $row['contable'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
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
		// cert
		// privatekey
		// inicioActividades
		// ingresosBrutos
		// puntoVenta
		// puntoVentaElectronico
		// contable

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// idTipoDoc
			$this->idTipoDoc->EditAttrs["class"] = "form-control";
			$this->idTipoDoc->EditCustomAttributes = "";
			if (trim(strval($this->idTipoDoc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-documentos`";
			$sWhereWrk = "";
			$this->idTipoDoc->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTipoDoc, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTipoDoc->EditValue = $arwrk;

			// documento
			$this->documento->EditAttrs["class"] = "form-control";
			$this->documento->EditCustomAttributes = "";
			$this->documento->EditValue = ew_HtmlEncode($this->documento->CurrentValue);
			$this->documento->PlaceHolder = ew_RemoveHtml($this->documento->FldCaption());

			// idPais
			$this->idPais->EditAttrs["class"] = "form-control";
			$this->idPais->EditCustomAttributes = "";
			if (trim(strval($this->idPais->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPais->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			$this->idPais->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPais->EditValue = $arwrk;

			// idProvincia
			$this->idProvincia->EditAttrs["class"] = "form-control";
			$this->idProvincia->EditCustomAttributes = "";
			if (trim(strval($this->idProvincia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvincia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincias`";
			$sWhereWrk = "";
			$this->idProvincia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idProvincia->EditValue = $arwrk;

			// idPartido
			$this->idPartido->EditAttrs["class"] = "form-control";
			$this->idPartido->EditCustomAttributes = "";
			if (trim(strval($this->idPartido->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartido->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `partidos`";
			$sWhereWrk = "";
			$this->idPartido->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPartido->EditValue = $arwrk;

			// idLocalidad
			$this->idLocalidad->EditAttrs["class"] = "form-control";
			$this->idLocalidad->EditCustomAttributes = "";
			if (trim(strval($this->idLocalidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `localidades`";
			$sWhereWrk = "";
			$this->idLocalidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idLocalidad->EditValue = $arwrk;

			// calle
			$this->calle->EditAttrs["class"] = "form-control";
			$this->calle->EditCustomAttributes = "";
			$this->calle->EditValue = ew_HtmlEncode($this->calle->CurrentValue);
			$this->calle->PlaceHolder = ew_RemoveHtml($this->calle->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// codigoPostal
			$this->codigoPostal->EditAttrs["class"] = "form-control";
			$this->codigoPostal->EditCustomAttributes = "";
			$this->codigoPostal->EditValue = ew_HtmlEncode($this->codigoPostal->CurrentValue);
			$this->codigoPostal->PlaceHolder = ew_RemoveHtml($this->codigoPostal->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// idCondicionIva
			$this->idCondicionIva->EditAttrs["class"] = "form-control";
			$this->idCondicionIva->EditCustomAttributes = "";
			if (trim(strval($this->idCondicionIva->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCondicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `condiciones-iva`";
			$sWhereWrk = "";
			$this->idCondicionIva->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idCondicionIva, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idCondicionIva->EditValue = $arwrk;

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->logo);

			// inicioActividades
			$this->inicioActividades->EditAttrs["class"] = "form-control";
			$this->inicioActividades->EditCustomAttributes = "";
			$this->inicioActividades->EditValue = ew_HtmlEncode($this->inicioActividades->CurrentValue);
			$this->inicioActividades->PlaceHolder = ew_RemoveHtml($this->inicioActividades->FldCaption());

			// ingresosBrutos
			$this->ingresosBrutos->EditAttrs["class"] = "form-control";
			$this->ingresosBrutos->EditCustomAttributes = "";
			$this->ingresosBrutos->EditValue = ew_HtmlEncode($this->ingresosBrutos->CurrentValue);
			$this->ingresosBrutos->PlaceHolder = ew_RemoveHtml($this->ingresosBrutos->FldCaption());

			// puntoVenta
			$this->puntoVenta->EditAttrs["class"] = "form-control";
			$this->puntoVenta->EditCustomAttributes = "";
			$this->puntoVenta->EditValue = ew_HtmlEncode($this->puntoVenta->CurrentValue);
			$this->puntoVenta->PlaceHolder = ew_RemoveHtml($this->puntoVenta->FldCaption());

			// puntoVentaElectronico
			$this->puntoVentaElectronico->EditAttrs["class"] = "form-control";
			$this->puntoVentaElectronico->EditCustomAttributes = "";
			$this->puntoVentaElectronico->EditValue = ew_HtmlEncode($this->puntoVentaElectronico->CurrentValue);
			$this->puntoVentaElectronico->PlaceHolder = ew_RemoveHtml($this->puntoVentaElectronico->FldCaption());

			// Edit refer script
			// denominacion

			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// idTipoDoc
			$this->idTipoDoc->LinkCustomAttributes = "";
			$this->idTipoDoc->HrefValue = "";

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";

			// idPais
			$this->idPais->LinkCustomAttributes = "";
			$this->idPais->HrefValue = "";

			// idProvincia
			$this->idProvincia->LinkCustomAttributes = "";
			$this->idProvincia->HrefValue = "";

			// idPartido
			$this->idPartido->LinkCustomAttributes = "";
			$this->idPartido->HrefValue = "";

			// idLocalidad
			$this->idLocalidad->LinkCustomAttributes = "";
			$this->idLocalidad->HrefValue = "";

			// calle
			$this->calle->LinkCustomAttributes = "";
			$this->calle->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// codigoPostal
			$this->codigoPostal->LinkCustomAttributes = "";
			$this->codigoPostal->HrefValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// idCondicionIva
			$this->idCondicionIva->LinkCustomAttributes = "";
			$this->idCondicionIva->HrefValue = "";

			// logo
			$this->logo->LinkCustomAttributes = "";
			$this->logo->HrefValue = "";
			$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;

			// inicioActividades
			$this->inicioActividades->LinkCustomAttributes = "";
			$this->inicioActividades->HrefValue = "";

			// ingresosBrutos
			$this->ingresosBrutos->LinkCustomAttributes = "";
			$this->ingresosBrutos->HrefValue = "";

			// puntoVenta
			$this->puntoVenta->LinkCustomAttributes = "";
			$this->puntoVenta->HrefValue = "";

			// puntoVentaElectronico
			$this->puntoVentaElectronico->LinkCustomAttributes = "";
			$this->puntoVentaElectronico->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckDateDef($this->inicioActividades->FormValue)) {
			ew_AddMessage($gsFormError, $this->inicioActividades->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// denominacion
			$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, $this->denominacion->ReadOnly);

			// idTipoDoc
			$this->idTipoDoc->SetDbValueDef($rsnew, $this->idTipoDoc->CurrentValue, NULL, $this->idTipoDoc->ReadOnly);

			// documento
			$this->documento->SetDbValueDef($rsnew, $this->documento->CurrentValue, NULL, $this->documento->ReadOnly);

			// idPais
			$this->idPais->SetDbValueDef($rsnew, $this->idPais->CurrentValue, NULL, $this->idPais->ReadOnly);

			// idProvincia
			$this->idProvincia->SetDbValueDef($rsnew, $this->idProvincia->CurrentValue, NULL, $this->idProvincia->ReadOnly);

			// idPartido
			$this->idPartido->SetDbValueDef($rsnew, $this->idPartido->CurrentValue, NULL, $this->idPartido->ReadOnly);

			// idLocalidad
			$this->idLocalidad->SetDbValueDef($rsnew, $this->idLocalidad->CurrentValue, NULL, $this->idLocalidad->ReadOnly);

			// calle
			$this->calle->SetDbValueDef($rsnew, $this->calle->CurrentValue, NULL, $this->calle->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, $this->direccion->ReadOnly);

			// codigoPostal
			$this->codigoPostal->SetDbValueDef($rsnew, $this->codigoPostal->CurrentValue, NULL, $this->codigoPostal->ReadOnly);

			// telefono
			$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, $this->telefono->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// idCondicionIva
			$this->idCondicionIva->SetDbValueDef($rsnew, $this->idCondicionIva->CurrentValue, NULL, $this->idCondicionIva->ReadOnly);

			// logo
			if ($this->logo->Visible && !$this->logo->ReadOnly && !$this->logo->Upload->KeepFile) {
				$this->logo->Upload->DbValue = $rsold['logo']; // Get original value
				if ($this->logo->Upload->FileName == "") {
					$rsnew['logo'] = NULL;
				} else {
					$rsnew['logo'] = $this->logo->Upload->FileName;
				}
			}

			// inicioActividades
			$this->inicioActividades->SetDbValueDef($rsnew, $this->inicioActividades->CurrentValue, NULL, $this->inicioActividades->ReadOnly);

			// ingresosBrutos
			$this->ingresosBrutos->SetDbValueDef($rsnew, $this->ingresosBrutos->CurrentValue, NULL, $this->ingresosBrutos->ReadOnly);

			// puntoVenta
			$this->puntoVenta->SetDbValueDef($rsnew, $this->puntoVenta->CurrentValue, NULL, $this->puntoVenta->ReadOnly);

			// puntoVentaElectronico
			$this->puntoVentaElectronico->SetDbValueDef($rsnew, $this->puntoVentaElectronico->CurrentValue, NULL, $this->puntoVentaElectronico->ReadOnly);
			if ($this->logo->Visible && !$this->logo->Upload->KeepFile) {
				if (!ew_Empty($this->logo->Upload->Value)) {
					$rsnew['logo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->logo->UploadPath), $rsnew['logo']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->logo->Visible && !$this->logo->Upload->KeepFile) {
						if (!ew_Empty($this->logo->Upload->Value)) {
							$this->logo->Upload->SaveToFile($this->logo->UploadPath, $rsnew['logo'], TRUE);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// logo
		ew_CleanUploadTempPath($this->logo, $this->logo->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("configuracionlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idTipoDoc":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
			$sWhereWrk = "";
			$this->idTipoDoc->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTipoDoc, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPais":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
			$sWhereWrk = "";
			$this->idPais->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idProvincia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
			$sWhereWrk = "";
			$this->idProvincia->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPartido":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
			$sWhereWrk = "";
			$this->idPartido->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idLocalidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
			$sWhereWrk = "";
			$this->idLocalidad->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idCondicionIva":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
			$sWhereWrk = "";
			$this->idCondicionIva->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idCondicionIva, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($configuracion_edit)) $configuracion_edit = new cconfiguracion_edit();

// Page init
$configuracion_edit->Page_Init();

// Page main
$configuracion_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$configuracion_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fconfiguracionedit = new ew_Form("fconfiguracionedit", "edit");

// Validate form
fconfiguracionedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_inicioActividades");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($configuracion->inicioActividades->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fconfiguracionedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconfiguracionedit.ValidateRequired = true;
<?php } else { ?>
fconfiguracionedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconfiguracionedit.Lists["x_idTipoDoc"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Ddocumentos"};
fconfiguracionedit.Lists["x_idPais"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"paises"};
fconfiguracionedit.Lists["x_idProvincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincias"};
fconfiguracionedit.Lists["x_idPartido"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"partidos"};
fconfiguracionedit.Lists["x_idLocalidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"localidades"};
fconfiguracionedit.Lists["x_idCondicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$configuracion_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $configuracion_edit->ShowPageHeader(); ?>
<?php
$configuracion_edit->ShowMessage();
?>
<form name="fconfiguracionedit" id="fconfiguracionedit" class="<?php echo $configuracion_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($configuracion_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $configuracion_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="configuracion">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($configuracion_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($configuracion->denominacion->Visible) { // denominacion ?>
	<div id="r_denominacion" class="form-group">
		<label id="elh_configuracion_denominacion" for="x_denominacion" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->denominacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->denominacion->CellAttributes() ?>>
<span id="el_configuracion_denominacion">
<input type="text" data-table="configuracion" data-field="x_denominacion" name="x_denominacion" id="x_denominacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($configuracion->denominacion->getPlaceHolder()) ?>" value="<?php echo $configuracion->denominacion->EditValue ?>"<?php echo $configuracion->denominacion->EditAttributes() ?>>
</span>
<?php echo $configuracion->denominacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idTipoDoc->Visible) { // idTipoDoc ?>
	<div id="r_idTipoDoc" class="form-group">
		<label id="elh_configuracion_idTipoDoc" for="x_idTipoDoc" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idTipoDoc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idTipoDoc->CellAttributes() ?>>
<span id="el_configuracion_idTipoDoc">
<select data-table="configuracion" data-field="x_idTipoDoc" data-value-separator="<?php echo $configuracion->idTipoDoc->DisplayValueSeparatorAttribute() ?>" id="x_idTipoDoc" name="x_idTipoDoc"<?php echo $configuracion->idTipoDoc->EditAttributes() ?>>
<?php echo $configuracion->idTipoDoc->SelectOptionListHtml("x_idTipoDoc") ?>
</select>
<input type="hidden" name="s_x_idTipoDoc" id="s_x_idTipoDoc" value="<?php echo $configuracion->idTipoDoc->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idTipoDoc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->documento->Visible) { // documento ?>
	<div id="r_documento" class="form-group">
		<label id="elh_configuracion_documento" for="x_documento" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->documento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->documento->CellAttributes() ?>>
<span id="el_configuracion_documento">
<input type="text" data-table="configuracion" data-field="x_documento" name="x_documento" id="x_documento" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($configuracion->documento->getPlaceHolder()) ?>" value="<?php echo $configuracion->documento->EditValue ?>"<?php echo $configuracion->documento->EditAttributes() ?>>
</span>
<?php echo $configuracion->documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idPais->Visible) { // idPais ?>
	<div id="r_idPais" class="form-group">
		<label id="elh_configuracion_idPais" for="x_idPais" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idPais->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idPais->CellAttributes() ?>>
<span id="el_configuracion_idPais">
<select data-table="configuracion" data-field="x_idPais" data-value-separator="<?php echo $configuracion->idPais->DisplayValueSeparatorAttribute() ?>" id="x_idPais" name="x_idPais"<?php echo $configuracion->idPais->EditAttributes() ?>>
<?php echo $configuracion->idPais->SelectOptionListHtml("x_idPais") ?>
</select>
<input type="hidden" name="s_x_idPais" id="s_x_idPais" value="<?php echo $configuracion->idPais->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idPais->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idProvincia->Visible) { // idProvincia ?>
	<div id="r_idProvincia" class="form-group">
		<label id="elh_configuracion_idProvincia" for="x_idProvincia" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idProvincia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idProvincia->CellAttributes() ?>>
<span id="el_configuracion_idProvincia">
<select data-table="configuracion" data-field="x_idProvincia" data-value-separator="<?php echo $configuracion->idProvincia->DisplayValueSeparatorAttribute() ?>" id="x_idProvincia" name="x_idProvincia"<?php echo $configuracion->idProvincia->EditAttributes() ?>>
<?php echo $configuracion->idProvincia->SelectOptionListHtml("x_idProvincia") ?>
</select>
<input type="hidden" name="s_x_idProvincia" id="s_x_idProvincia" value="<?php echo $configuracion->idProvincia->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idProvincia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idPartido->Visible) { // idPartido ?>
	<div id="r_idPartido" class="form-group">
		<label id="elh_configuracion_idPartido" for="x_idPartido" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idPartido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idPartido->CellAttributes() ?>>
<span id="el_configuracion_idPartido">
<select data-table="configuracion" data-field="x_idPartido" data-value-separator="<?php echo $configuracion->idPartido->DisplayValueSeparatorAttribute() ?>" id="x_idPartido" name="x_idPartido"<?php echo $configuracion->idPartido->EditAttributes() ?>>
<?php echo $configuracion->idPartido->SelectOptionListHtml("x_idPartido") ?>
</select>
<input type="hidden" name="s_x_idPartido" id="s_x_idPartido" value="<?php echo $configuracion->idPartido->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idPartido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idLocalidad->Visible) { // idLocalidad ?>
	<div id="r_idLocalidad" class="form-group">
		<label id="elh_configuracion_idLocalidad" for="x_idLocalidad" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idLocalidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idLocalidad->CellAttributes() ?>>
<span id="el_configuracion_idLocalidad">
<select data-table="configuracion" data-field="x_idLocalidad" data-value-separator="<?php echo $configuracion->idLocalidad->DisplayValueSeparatorAttribute() ?>" id="x_idLocalidad" name="x_idLocalidad"<?php echo $configuracion->idLocalidad->EditAttributes() ?>>
<?php echo $configuracion->idLocalidad->SelectOptionListHtml("x_idLocalidad") ?>
</select>
<input type="hidden" name="s_x_idLocalidad" id="s_x_idLocalidad" value="<?php echo $configuracion->idLocalidad->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idLocalidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->calle->Visible) { // calle ?>
	<div id="r_calle" class="form-group">
		<label id="elh_configuracion_calle" for="x_calle" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->calle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->calle->CellAttributes() ?>>
<span id="el_configuracion_calle">
<input type="text" data-table="configuracion" data-field="x_calle" name="x_calle" id="x_calle" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($configuracion->calle->getPlaceHolder()) ?>" value="<?php echo $configuracion->calle->EditValue ?>"<?php echo $configuracion->calle->EditAttributes() ?>>
</span>
<?php echo $configuracion->calle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_configuracion_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->direccion->CellAttributes() ?>>
<span id="el_configuracion_direccion">
<input type="text" data-table="configuracion" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($configuracion->direccion->getPlaceHolder()) ?>" value="<?php echo $configuracion->direccion->EditValue ?>"<?php echo $configuracion->direccion->EditAttributes() ?>>
</span>
<?php echo $configuracion->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->codigoPostal->Visible) { // codigoPostal ?>
	<div id="r_codigoPostal" class="form-group">
		<label id="elh_configuracion_codigoPostal" for="x_codigoPostal" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->codigoPostal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->codigoPostal->CellAttributes() ?>>
<span id="el_configuracion_codigoPostal">
<input type="text" data-table="configuracion" data-field="x_codigoPostal" name="x_codigoPostal" id="x_codigoPostal" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($configuracion->codigoPostal->getPlaceHolder()) ?>" value="<?php echo $configuracion->codigoPostal->EditValue ?>"<?php echo $configuracion->codigoPostal->EditAttributes() ?>>
</span>
<?php echo $configuracion->codigoPostal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_configuracion_telefono" for="x_telefono" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->telefono->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->telefono->CellAttributes() ?>>
<span id="el_configuracion_telefono">
<input type="text" data-table="configuracion" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($configuracion->telefono->getPlaceHolder()) ?>" value="<?php echo $configuracion->telefono->EditValue ?>"<?php echo $configuracion->telefono->EditAttributes() ?>>
</span>
<?php echo $configuracion->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_configuracion__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->_email->CellAttributes() ?>>
<span id="el_configuracion__email">
<input type="text" data-table="configuracion" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($configuracion->_email->getPlaceHolder()) ?>" value="<?php echo $configuracion->_email->EditValue ?>"<?php echo $configuracion->_email->EditAttributes() ?>>
</span>
<?php echo $configuracion->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->idCondicionIva->Visible) { // idCondicionIva ?>
	<div id="r_idCondicionIva" class="form-group">
		<label id="elh_configuracion_idCondicionIva" for="x_idCondicionIva" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->idCondicionIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->idCondicionIva->CellAttributes() ?>>
<span id="el_configuracion_idCondicionIva">
<select data-table="configuracion" data-field="x_idCondicionIva" data-value-separator="<?php echo $configuracion->idCondicionIva->DisplayValueSeparatorAttribute() ?>" id="x_idCondicionIva" name="x_idCondicionIva"<?php echo $configuracion->idCondicionIva->EditAttributes() ?>>
<?php echo $configuracion->idCondicionIva->SelectOptionListHtml("x_idCondicionIva") ?>
</select>
<input type="hidden" name="s_x_idCondicionIva" id="s_x_idCondicionIva" value="<?php echo $configuracion->idCondicionIva->LookupFilterQuery() ?>">
</span>
<?php echo $configuracion->idCondicionIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->logo->Visible) { // logo ?>
	<div id="r_logo" class="form-group">
		<label id="elh_configuracion_logo" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->logo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->logo->CellAttributes() ?>>
<span id="el_configuracion_logo">
<div id="fd_x_logo">
<span title="<?php echo $configuracion->logo->FldTitle() ? $configuracion->logo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($configuracion->logo->ReadOnly || $configuracion->logo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="configuracion" data-field="x_logo" name="x_logo" id="x_logo"<?php echo $configuracion->logo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_logo" id= "fn_x_logo" value="<?php echo $configuracion->logo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_logo"] == "0") { ?>
<input type="hidden" name="fa_x_logo" id= "fa_x_logo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_logo" id= "fa_x_logo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_logo" id= "fs_x_logo" value="255">
<input type="hidden" name="fx_x_logo" id= "fx_x_logo" value="<?php echo $configuracion->logo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_logo" id= "fm_x_logo" value="<?php echo $configuracion->logo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_logo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $configuracion->logo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->inicioActividades->Visible) { // inicioActividades ?>
	<div id="r_inicioActividades" class="form-group">
		<label id="elh_configuracion_inicioActividades" for="x_inicioActividades" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->inicioActividades->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->inicioActividades->CellAttributes() ?>>
<span id="el_configuracion_inicioActividades">
<input type="text" data-table="configuracion" data-field="x_inicioActividades" name="x_inicioActividades" id="x_inicioActividades" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($configuracion->inicioActividades->getPlaceHolder()) ?>" value="<?php echo $configuracion->inicioActividades->EditValue ?>"<?php echo $configuracion->inicioActividades->EditAttributes() ?>>
<?php if (!$configuracion->inicioActividades->ReadOnly && !$configuracion->inicioActividades->Disabled && !isset($configuracion->inicioActividades->EditAttrs["readonly"]) && !isset($configuracion->inicioActividades->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fconfiguracionedit", "x_inicioActividades", 0);
</script>
<?php } ?>
</span>
<?php echo $configuracion->inicioActividades->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->ingresosBrutos->Visible) { // ingresosBrutos ?>
	<div id="r_ingresosBrutos" class="form-group">
		<label id="elh_configuracion_ingresosBrutos" for="x_ingresosBrutos" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->ingresosBrutos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->ingresosBrutos->CellAttributes() ?>>
<span id="el_configuracion_ingresosBrutos">
<input type="text" data-table="configuracion" data-field="x_ingresosBrutos" name="x_ingresosBrutos" id="x_ingresosBrutos" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($configuracion->ingresosBrutos->getPlaceHolder()) ?>" value="<?php echo $configuracion->ingresosBrutos->EditValue ?>"<?php echo $configuracion->ingresosBrutos->EditAttributes() ?>>
</span>
<?php echo $configuracion->ingresosBrutos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->puntoVenta->Visible) { // puntoVenta ?>
	<div id="r_puntoVenta" class="form-group">
		<label id="elh_configuracion_puntoVenta" for="x_puntoVenta" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->puntoVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->puntoVenta->CellAttributes() ?>>
<span id="el_configuracion_puntoVenta">
<input type="text" data-table="configuracion" data-field="x_puntoVenta" name="x_puntoVenta" id="x_puntoVenta" size="30" maxlength="4" placeholder="<?php echo ew_HtmlEncode($configuracion->puntoVenta->getPlaceHolder()) ?>" value="<?php echo $configuracion->puntoVenta->EditValue ?>"<?php echo $configuracion->puntoVenta->EditAttributes() ?>>
</span>
<?php echo $configuracion->puntoVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($configuracion->puntoVentaElectronico->Visible) { // puntoVentaElectronico ?>
	<div id="r_puntoVentaElectronico" class="form-group">
		<label id="elh_configuracion_puntoVentaElectronico" for="x_puntoVentaElectronico" class="col-sm-2 control-label ewLabel"><?php echo $configuracion->puntoVentaElectronico->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $configuracion->puntoVentaElectronico->CellAttributes() ?>>
<span id="el_configuracion_puntoVentaElectronico">
<input type="text" data-table="configuracion" data-field="x_puntoVentaElectronico" name="x_puntoVentaElectronico" id="x_puntoVentaElectronico" size="30" maxlength="4" placeholder="<?php echo ew_HtmlEncode($configuracion->puntoVentaElectronico->getPlaceHolder()) ?>" value="<?php echo $configuracion->puntoVentaElectronico->EditValue ?>"<?php echo $configuracion->puntoVentaElectronico->EditAttributes() ?>>
</span>
<?php echo $configuracion->puntoVentaElectronico->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="configuracion" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($configuracion->id->CurrentValue) ?>">
<?php if (!$configuracion_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $configuracion_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fconfiguracionedit.Init();
</script>
<?php
$configuracion_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$configuracion_edit->Page_Terminate();
?>
