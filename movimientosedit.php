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

$movimientos_edit = NULL; // Initialize page object first

class cmovimientos_edit extends cmovimientos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos';

	// Page object name
	var $PageObjName = 'movimientos_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Create form object
		$objForm = new cFormObj();
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
		$this->cae->SetVisibility();
		$this->vtoCae->SetVisibility();
		$this->idEstado->SetVisibility();
		$this->idUsuarioAlta->SetVisibility();
		$this->fechaAlta->SetVisibility();
		$this->idUsuarioModificacion->SetVisibility();
		$this->fechaModificacion->SetVisibility();
		$this->contable->SetVisibility();
		$this->archivo->SetVisibility();
		$this->valorDolar->SetVisibility();
		$this->comentarios->SetVisibility();
		$this->articulosAsociados->SetVisibility();
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
			$this->Page_Terminate("movimientoslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("movimientoslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "movimientoslist.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->nroComprobanteCompleto->FldIsDetailKey) {
			$this->nroComprobanteCompleto->setFormValue($objForm->GetValue("x_nroComprobanteCompleto"));
		}
		if (!$this->tipoMovimiento->FldIsDetailKey) {
			$this->tipoMovimiento->setFormValue($objForm->GetValue("x_tipoMovimiento"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		}
		if (!$this->codTercero->FldIsDetailKey) {
			$this->codTercero->setFormValue($objForm->GetValue("x_codTercero"));
		}
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->idComprobante->FldIsDetailKey) {
			$this->idComprobante->setFormValue($objForm->GetValue("x_idComprobante"));
		}
		if (!$this->importeTotal->FldIsDetailKey) {
			$this->importeTotal->setFormValue($objForm->GetValue("x_importeTotal"));
		}
		if (!$this->importeIva->FldIsDetailKey) {
			$this->importeIva->setFormValue($objForm->GetValue("x_importeIva"));
		}
		if (!$this->importeNeto->FldIsDetailKey) {
			$this->importeNeto->setFormValue($objForm->GetValue("x_importeNeto"));
		}
		if (!$this->importeCancelado->FldIsDetailKey) {
			$this->importeCancelado->setFormValue($objForm->GetValue("x_importeCancelado"));
		}
		if (!$this->cae->FldIsDetailKey) {
			$this->cae->setFormValue($objForm->GetValue("x_cae"));
		}
		if (!$this->vtoCae->FldIsDetailKey) {
			$this->vtoCae->setFormValue($objForm->GetValue("x_vtoCae"));
			$this->vtoCae->CurrentValue = ew_UnFormatDateTime($this->vtoCae->CurrentValue, 0);
		}
		if (!$this->idEstado->FldIsDetailKey) {
			$this->idEstado->setFormValue($objForm->GetValue("x_idEstado"));
		}
		if (!$this->idUsuarioAlta->FldIsDetailKey) {
			$this->idUsuarioAlta->setFormValue($objForm->GetValue("x_idUsuarioAlta"));
		}
		if (!$this->fechaAlta->FldIsDetailKey) {
			$this->fechaAlta->setFormValue($objForm->GetValue("x_fechaAlta"));
			$this->fechaAlta->CurrentValue = ew_UnFormatDateTime($this->fechaAlta->CurrentValue, 0);
		}
		if (!$this->idUsuarioModificacion->FldIsDetailKey) {
			$this->idUsuarioModificacion->setFormValue($objForm->GetValue("x_idUsuarioModificacion"));
		}
		if (!$this->fechaModificacion->FldIsDetailKey) {
			$this->fechaModificacion->setFormValue($objForm->GetValue("x_fechaModificacion"));
			$this->fechaModificacion->CurrentValue = ew_UnFormatDateTime($this->fechaModificacion->CurrentValue, 0);
		}
		if (!$this->contable->FldIsDetailKey) {
			$this->contable->setFormValue($objForm->GetValue("x_contable"));
		}
		if (!$this->archivo->FldIsDetailKey) {
			$this->archivo->setFormValue($objForm->GetValue("x_archivo"));
		}
		if (!$this->valorDolar->FldIsDetailKey) {
			$this->valorDolar->setFormValue($objForm->GetValue("x_valorDolar"));
		}
		if (!$this->comentarios->FldIsDetailKey) {
			$this->comentarios->setFormValue($objForm->GetValue("x_comentarios"));
		}
		if (!$this->articulosAsociados->FldIsDetailKey) {
			$this->articulosAsociados->setFormValue($objForm->GetValue("x_articulosAsociados"));
		}
		if (!$this->movimientosAsociados->FldIsDetailKey) {
			$this->movimientosAsociados->setFormValue($objForm->GetValue("x_movimientosAsociados"));
		}
		if (!$this->condicionVenta->FldIsDetailKey) {
			$this->condicionVenta->setFormValue($objForm->GetValue("x_condicionVenta"));
		}
		if (!$this->vigencia->FldIsDetailKey) {
			$this->vigencia->setFormValue($objForm->GetValue("x_vigencia"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->nroComprobanteCompleto->CurrentValue = $this->nroComprobanteCompleto->FormValue;
		$this->tipoMovimiento->CurrentValue = $this->tipoMovimiento->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->codTercero->CurrentValue = $this->codTercero->FormValue;
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->idComprobante->CurrentValue = $this->idComprobante->FormValue;
		$this->importeTotal->CurrentValue = $this->importeTotal->FormValue;
		$this->importeIva->CurrentValue = $this->importeIva->FormValue;
		$this->importeNeto->CurrentValue = $this->importeNeto->FormValue;
		$this->importeCancelado->CurrentValue = $this->importeCancelado->FormValue;
		$this->cae->CurrentValue = $this->cae->FormValue;
		$this->vtoCae->CurrentValue = $this->vtoCae->FormValue;
		$this->vtoCae->CurrentValue = ew_UnFormatDateTime($this->vtoCae->CurrentValue, 0);
		$this->idEstado->CurrentValue = $this->idEstado->FormValue;
		$this->idUsuarioAlta->CurrentValue = $this->idUsuarioAlta->FormValue;
		$this->fechaAlta->CurrentValue = $this->fechaAlta->FormValue;
		$this->fechaAlta->CurrentValue = ew_UnFormatDateTime($this->fechaAlta->CurrentValue, 0);
		$this->idUsuarioModificacion->CurrentValue = $this->idUsuarioModificacion->FormValue;
		$this->fechaModificacion->CurrentValue = $this->fechaModificacion->FormValue;
		$this->fechaModificacion->CurrentValue = ew_UnFormatDateTime($this->fechaModificacion->CurrentValue, 0);
		$this->contable->CurrentValue = $this->contable->FormValue;
		$this->archivo->CurrentValue = $this->archivo->FormValue;
		$this->valorDolar->CurrentValue = $this->valorDolar->FormValue;
		$this->comentarios->CurrentValue = $this->comentarios->FormValue;
		$this->articulosAsociados->CurrentValue = $this->articulosAsociados->FormValue;
		$this->movimientosAsociados->CurrentValue = $this->movimientosAsociados->FormValue;
		$this->condicionVenta->CurrentValue = $this->condicionVenta->FormValue;
		$this->vigencia->CurrentValue = $this->vigencia->FormValue;
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

		// Convert decimal values if posted back
		if ($this->valorDolar->FormValue == $this->valorDolar->CurrentValue && is_numeric(ew_StrToFloat($this->valorDolar->CurrentValue)))
			$this->valorDolar->CurrentValue = ew_StrToFloat($this->valorDolar->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// nroComprobanteCompleto
		// tipoMovimiento
		// fecha
		// idSociedad
		// codTercero
		// idTercero
		// idComprobante
		// importeTotal
		// importeIva
		// importeNeto
		// importeCancelado
		// nombreTercero
		// idDocTercero
		// nroDocTercero
		// ptoVenta
		// nroComprobante
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
		// movimientosAsociados
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

		// cae
		$this->cae->ViewValue = $this->cae->CurrentValue;
		$this->cae->ViewCustomAttributes = "";

		// vtoCae
		$this->vtoCae->ViewValue = $this->vtoCae->CurrentValue;
		$this->vtoCae->ViewValue = ew_FormatDateTime($this->vtoCae->ViewValue, 0);
		$this->vtoCae->ViewCustomAttributes = "";

		// idEstado
		if (strval($this->idEstado->CurrentValue) <> "") {
			$this->idEstado->ViewValue = $this->idEstado->OptionCaption($this->idEstado->CurrentValue);
		} else {
			$this->idEstado->ViewValue = NULL;
		}
		$this->idEstado->ViewCustomAttributes = "";

		// idUsuarioAlta
		$this->idUsuarioAlta->ViewValue = $this->idUsuarioAlta->CurrentValue;
		$this->idUsuarioAlta->ViewCustomAttributes = "";

		// fechaAlta
		$this->fechaAlta->ViewValue = $this->fechaAlta->CurrentValue;
		$this->fechaAlta->ViewValue = ew_FormatDateTime($this->fechaAlta->ViewValue, 0);
		$this->fechaAlta->ViewCustomAttributes = "";

		// idUsuarioModificacion
		$this->idUsuarioModificacion->ViewValue = $this->idUsuarioModificacion->CurrentValue;
		$this->idUsuarioModificacion->ViewCustomAttributes = "";

		// fechaModificacion
		$this->fechaModificacion->ViewValue = $this->fechaModificacion->CurrentValue;
		$this->fechaModificacion->ViewValue = ew_FormatDateTime($this->fechaModificacion->ViewValue, 0);
		$this->fechaModificacion->ViewCustomAttributes = "";

		// contable
		$this->contable->ViewValue = $this->contable->CurrentValue;
		$this->contable->ViewCustomAttributes = "";

		// archivo
		$this->archivo->ViewValue = $this->archivo->CurrentValue;
		$this->archivo->ViewCustomAttributes = "";

		// valorDolar
		$this->valorDolar->ViewValue = $this->valorDolar->CurrentValue;
		$this->valorDolar->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

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

			// cae
			$this->cae->LinkCustomAttributes = "";
			$this->cae->HrefValue = "";
			$this->cae->TooltipValue = "";

			// vtoCae
			$this->vtoCae->LinkCustomAttributes = "";
			$this->vtoCae->HrefValue = "";
			$this->vtoCae->TooltipValue = "";

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
			$this->idEstado->TooltipValue = "";

			// idUsuarioAlta
			$this->idUsuarioAlta->LinkCustomAttributes = "";
			$this->idUsuarioAlta->HrefValue = "";
			$this->idUsuarioAlta->TooltipValue = "";

			// fechaAlta
			$this->fechaAlta->LinkCustomAttributes = "";
			$this->fechaAlta->HrefValue = "";
			$this->fechaAlta->TooltipValue = "";

			// idUsuarioModificacion
			$this->idUsuarioModificacion->LinkCustomAttributes = "";
			$this->idUsuarioModificacion->HrefValue = "";
			$this->idUsuarioModificacion->TooltipValue = "";

			// fechaModificacion
			$this->fechaModificacion->LinkCustomAttributes = "";
			$this->fechaModificacion->HrefValue = "";
			$this->fechaModificacion->TooltipValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";
			$this->contable->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->HrefValue = "";
			$this->archivo->TooltipValue = "";

			// valorDolar
			$this->valorDolar->LinkCustomAttributes = "";
			$this->valorDolar->HrefValue = "";
			$this->valorDolar->TooltipValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";
			$this->comentarios->TooltipValue = "";

			// articulosAsociados
			$this->articulosAsociados->LinkCustomAttributes = "";
			$this->articulosAsociados->HrefValue = "";
			$this->articulosAsociados->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// nroComprobanteCompleto
			$this->nroComprobanteCompleto->EditAttrs["class"] = "form-control";
			$this->nroComprobanteCompleto->EditCustomAttributes = "";
			$this->nroComprobanteCompleto->EditValue = ew_HtmlEncode($this->nroComprobanteCompleto->CurrentValue);
			$this->nroComprobanteCompleto->PlaceHolder = ew_RemoveHtml($this->nroComprobanteCompleto->FldCaption());

			// tipoMovimiento
			$this->tipoMovimiento->EditAttrs["class"] = "form-control";
			$this->tipoMovimiento->EditCustomAttributes = "";
			$this->tipoMovimiento->EditValue = $this->tipoMovimiento->Options(TRUE);

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// codTercero
			$this->codTercero->EditAttrs["class"] = "form-control";
			$this->codTercero->EditCustomAttributes = "";
			$this->codTercero->EditValue = ew_HtmlEncode($this->codTercero->CurrentValue);
			$this->codTercero->PlaceHolder = ew_RemoveHtml($this->codTercero->FldCaption());

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;

			// idComprobante
			$this->idComprobante->EditAttrs["class"] = "form-control";
			$this->idComprobante->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idComprobante->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idComprobante->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `comprobantes`";
			$sWhereWrk = "";
			$this->idComprobante->LookupFilters = array();
			$lookuptblfilter = "`activo` = 1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idComprobante, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idComprobante->EditValue = $arwrk;

			// importeTotal
			$this->importeTotal->EditAttrs["class"] = "form-control";
			$this->importeTotal->EditCustomAttributes = "";
			$this->importeTotal->EditValue = ew_HtmlEncode($this->importeTotal->CurrentValue);
			$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());
			if (strval($this->importeTotal->EditValue) <> "" && is_numeric($this->importeTotal->EditValue)) $this->importeTotal->EditValue = ew_FormatNumber($this->importeTotal->EditValue, -2, -1, -2, 0);

			// importeIva
			$this->importeIva->EditAttrs["class"] = "form-control";
			$this->importeIva->EditCustomAttributes = "";
			$this->importeIva->EditValue = ew_HtmlEncode($this->importeIva->CurrentValue);
			$this->importeIva->PlaceHolder = ew_RemoveHtml($this->importeIva->FldCaption());
			if (strval($this->importeIva->EditValue) <> "" && is_numeric($this->importeIva->EditValue)) $this->importeIva->EditValue = ew_FormatNumber($this->importeIva->EditValue, -2, -1, -2, 0);

			// importeNeto
			$this->importeNeto->EditAttrs["class"] = "form-control";
			$this->importeNeto->EditCustomAttributes = "";
			$this->importeNeto->EditValue = ew_HtmlEncode($this->importeNeto->CurrentValue);
			$this->importeNeto->PlaceHolder = ew_RemoveHtml($this->importeNeto->FldCaption());
			if (strval($this->importeNeto->EditValue) <> "" && is_numeric($this->importeNeto->EditValue)) $this->importeNeto->EditValue = ew_FormatNumber($this->importeNeto->EditValue, -2, -1, -2, 0);

			// importeCancelado
			$this->importeCancelado->EditAttrs["class"] = "form-control";
			$this->importeCancelado->EditCustomAttributes = "";
			$this->importeCancelado->EditValue = ew_HtmlEncode($this->importeCancelado->CurrentValue);
			$this->importeCancelado->PlaceHolder = ew_RemoveHtml($this->importeCancelado->FldCaption());
			if (strval($this->importeCancelado->EditValue) <> "" && is_numeric($this->importeCancelado->EditValue)) $this->importeCancelado->EditValue = ew_FormatNumber($this->importeCancelado->EditValue, -2, -1, -2, 0);

			// cae
			$this->cae->EditAttrs["class"] = "form-control";
			$this->cae->EditCustomAttributes = "";
			$this->cae->EditValue = ew_HtmlEncode($this->cae->CurrentValue);
			$this->cae->PlaceHolder = ew_RemoveHtml($this->cae->FldCaption());

			// vtoCae
			$this->vtoCae->EditAttrs["class"] = "form-control";
			$this->vtoCae->EditCustomAttributes = "";
			$this->vtoCae->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->vtoCae->CurrentValue, 8));
			$this->vtoCae->PlaceHolder = ew_RemoveHtml($this->vtoCae->FldCaption());

			// idEstado
			$this->idEstado->EditAttrs["class"] = "form-control";
			$this->idEstado->EditCustomAttributes = "";
			$this->idEstado->EditValue = $this->idEstado->Options(TRUE);

			// idUsuarioAlta
			$this->idUsuarioAlta->EditAttrs["class"] = "form-control";
			$this->idUsuarioAlta->EditCustomAttributes = "";
			$this->idUsuarioAlta->EditValue = ew_HtmlEncode($this->idUsuarioAlta->CurrentValue);
			$this->idUsuarioAlta->PlaceHolder = ew_RemoveHtml($this->idUsuarioAlta->FldCaption());

			// fechaAlta
			$this->fechaAlta->EditAttrs["class"] = "form-control";
			$this->fechaAlta->EditCustomAttributes = "";
			$this->fechaAlta->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaAlta->CurrentValue, 8));
			$this->fechaAlta->PlaceHolder = ew_RemoveHtml($this->fechaAlta->FldCaption());

			// idUsuarioModificacion
			$this->idUsuarioModificacion->EditAttrs["class"] = "form-control";
			$this->idUsuarioModificacion->EditCustomAttributes = "";
			$this->idUsuarioModificacion->EditValue = ew_HtmlEncode($this->idUsuarioModificacion->CurrentValue);
			$this->idUsuarioModificacion->PlaceHolder = ew_RemoveHtml($this->idUsuarioModificacion->FldCaption());

			// fechaModificacion
			$this->fechaModificacion->EditAttrs["class"] = "form-control";
			$this->fechaModificacion->EditCustomAttributes = "";
			$this->fechaModificacion->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaModificacion->CurrentValue, 8));
			$this->fechaModificacion->PlaceHolder = ew_RemoveHtml($this->fechaModificacion->FldCaption());

			// contable
			$this->contable->EditAttrs["class"] = "form-control";
			$this->contable->EditCustomAttributes = "";
			$this->contable->EditValue = ew_HtmlEncode($this->contable->CurrentValue);
			$this->contable->PlaceHolder = ew_RemoveHtml($this->contable->FldCaption());

			// archivo
			$this->archivo->EditAttrs["class"] = "form-control";
			$this->archivo->EditCustomAttributes = "";
			$this->archivo->EditValue = ew_HtmlEncode($this->archivo->CurrentValue);
			$this->archivo->PlaceHolder = ew_RemoveHtml($this->archivo->FldCaption());

			// valorDolar
			$this->valorDolar->EditAttrs["class"] = "form-control";
			$this->valorDolar->EditCustomAttributes = "";
			$this->valorDolar->EditValue = ew_HtmlEncode($this->valorDolar->CurrentValue);
			$this->valorDolar->PlaceHolder = ew_RemoveHtml($this->valorDolar->FldCaption());
			if (strval($this->valorDolar->EditValue) <> "" && is_numeric($this->valorDolar->EditValue)) $this->valorDolar->EditValue = ew_FormatNumber($this->valorDolar->EditValue, -2, -1, -2, 0);

			// comentarios
			$this->comentarios->EditAttrs["class"] = "form-control";
			$this->comentarios->EditCustomAttributes = "";
			$this->comentarios->EditValue = ew_HtmlEncode($this->comentarios->CurrentValue);
			$this->comentarios->PlaceHolder = ew_RemoveHtml($this->comentarios->FldCaption());

			// articulosAsociados
			$this->articulosAsociados->EditAttrs["class"] = "form-control";
			$this->articulosAsociados->EditCustomAttributes = "";
			$this->articulosAsociados->EditValue = ew_HtmlEncode($this->articulosAsociados->CurrentValue);
			$this->articulosAsociados->PlaceHolder = ew_RemoveHtml($this->articulosAsociados->FldCaption());

			// movimientosAsociados
			$this->movimientosAsociados->EditAttrs["class"] = "form-control";
			$this->movimientosAsociados->EditCustomAttributes = "";
			$this->movimientosAsociados->EditValue = ew_HtmlEncode($this->movimientosAsociados->CurrentValue);
			$this->movimientosAsociados->PlaceHolder = ew_RemoveHtml($this->movimientosAsociados->FldCaption());

			// condicionVenta
			$this->condicionVenta->EditAttrs["class"] = "form-control";
			$this->condicionVenta->EditCustomAttributes = "";
			$this->condicionVenta->EditValue = ew_HtmlEncode($this->condicionVenta->CurrentValue);
			$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());

			// vigencia
			$this->vigencia->EditAttrs["class"] = "form-control";
			$this->vigencia->EditCustomAttributes = "";
			$this->vigencia->EditValue = ew_HtmlEncode($this->vigencia->CurrentValue);
			$this->vigencia->PlaceHolder = ew_RemoveHtml($this->vigencia->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// nroComprobanteCompleto
			$this->nroComprobanteCompleto->LinkCustomAttributes = "";
			$this->nroComprobanteCompleto->HrefValue = "";

			// tipoMovimiento
			$this->tipoMovimiento->LinkCustomAttributes = "";
			$this->tipoMovimiento->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// codTercero
			$this->codTercero->LinkCustomAttributes = "";
			$this->codTercero->HrefValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// idComprobante
			$this->idComprobante->LinkCustomAttributes = "";
			$this->idComprobante->HrefValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";

			// importeCancelado
			$this->importeCancelado->LinkCustomAttributes = "";
			$this->importeCancelado->HrefValue = "";

			// cae
			$this->cae->LinkCustomAttributes = "";
			$this->cae->HrefValue = "";

			// vtoCae
			$this->vtoCae->LinkCustomAttributes = "";
			$this->vtoCae->HrefValue = "";

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";

			// idUsuarioAlta
			$this->idUsuarioAlta->LinkCustomAttributes = "";
			$this->idUsuarioAlta->HrefValue = "";

			// fechaAlta
			$this->fechaAlta->LinkCustomAttributes = "";
			$this->fechaAlta->HrefValue = "";

			// idUsuarioModificacion
			$this->idUsuarioModificacion->LinkCustomAttributes = "";
			$this->idUsuarioModificacion->HrefValue = "";

			// fechaModificacion
			$this->fechaModificacion->LinkCustomAttributes = "";
			$this->fechaModificacion->HrefValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->HrefValue = "";

			// valorDolar
			$this->valorDolar->LinkCustomAttributes = "";
			$this->valorDolar->HrefValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";

			// articulosAsociados
			$this->articulosAsociados->LinkCustomAttributes = "";
			$this->articulosAsociados->HrefValue = "";

			// movimientosAsociados
			$this->movimientosAsociados->LinkCustomAttributes = "";
			$this->movimientosAsociados->HrefValue = "";

			// condicionVenta
			$this->condicionVenta->LinkCustomAttributes = "";
			$this->condicionVenta->HrefValue = "";

			// vigencia
			$this->vigencia->LinkCustomAttributes = "";
			$this->vigencia->HrefValue = "";
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
		if (!ew_CheckDateDef($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->codTercero->FormValue)) {
			ew_AddMessage($gsFormError, $this->codTercero->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeTotal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeIva->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeIva->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeNeto->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeNeto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeCancelado->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeCancelado->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->vtoCae->FormValue)) {
			ew_AddMessage($gsFormError, $this->vtoCae->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idUsuarioAlta->FormValue)) {
			ew_AddMessage($gsFormError, $this->idUsuarioAlta->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fechaAlta->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaAlta->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idUsuarioModificacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->idUsuarioModificacion->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fechaModificacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaModificacion->FldErrMsg());
		}
		if (!ew_CheckInteger($this->contable->FormValue)) {
			ew_AddMessage($gsFormError, $this->contable->FldErrMsg());
		}
		if (!ew_CheckNumber($this->valorDolar->FormValue)) {
			ew_AddMessage($gsFormError, $this->valorDolar->FldErrMsg());
		}
		if (!ew_CheckInteger($this->condicionVenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->condicionVenta->FldErrMsg());
		}
		if (!ew_CheckInteger($this->vigencia->FormValue)) {
			ew_AddMessage($gsFormError, $this->vigencia->FldErrMsg());
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

			// nroComprobanteCompleto
			$this->nroComprobanteCompleto->SetDbValueDef($rsnew, $this->nroComprobanteCompleto->CurrentValue, NULL, $this->nroComprobanteCompleto->ReadOnly);

			// tipoMovimiento
			$this->tipoMovimiento->SetDbValueDef($rsnew, $this->tipoMovimiento->CurrentValue, NULL, $this->tipoMovimiento->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 0), NULL, $this->fecha->ReadOnly);

			// codTercero
			$this->codTercero->SetDbValueDef($rsnew, $this->codTercero->CurrentValue, NULL, $this->codTercero->ReadOnly);

			// idTercero
			$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, $this->idTercero->ReadOnly);

			// idComprobante
			$this->idComprobante->SetDbValueDef($rsnew, $this->idComprobante->CurrentValue, NULL, $this->idComprobante->ReadOnly);

			// importeTotal
			$this->importeTotal->SetDbValueDef($rsnew, $this->importeTotal->CurrentValue, NULL, $this->importeTotal->ReadOnly);

			// importeIva
			$this->importeIva->SetDbValueDef($rsnew, $this->importeIva->CurrentValue, NULL, $this->importeIva->ReadOnly);

			// importeNeto
			$this->importeNeto->SetDbValueDef($rsnew, $this->importeNeto->CurrentValue, NULL, $this->importeNeto->ReadOnly);

			// importeCancelado
			$this->importeCancelado->SetDbValueDef($rsnew, $this->importeCancelado->CurrentValue, NULL, $this->importeCancelado->ReadOnly);

			// cae
			$this->cae->SetDbValueDef($rsnew, $this->cae->CurrentValue, NULL, $this->cae->ReadOnly);

			// vtoCae
			$this->vtoCae->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->vtoCae->CurrentValue, 0), NULL, $this->vtoCae->ReadOnly);

			// idEstado
			$this->idEstado->SetDbValueDef($rsnew, $this->idEstado->CurrentValue, NULL, $this->idEstado->ReadOnly);

			// idUsuarioAlta
			$this->idUsuarioAlta->SetDbValueDef($rsnew, $this->idUsuarioAlta->CurrentValue, NULL, $this->idUsuarioAlta->ReadOnly);

			// fechaAlta
			$this->fechaAlta->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaAlta->CurrentValue, 0), NULL, $this->fechaAlta->ReadOnly);

			// idUsuarioModificacion
			$this->idUsuarioModificacion->SetDbValueDef($rsnew, $this->idUsuarioModificacion->CurrentValue, NULL, $this->idUsuarioModificacion->ReadOnly);

			// fechaModificacion
			$this->fechaModificacion->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaModificacion->CurrentValue, 0), NULL, $this->fechaModificacion->ReadOnly);

			// contable
			$this->contable->SetDbValueDef($rsnew, $this->contable->CurrentValue, NULL, $this->contable->ReadOnly);

			// archivo
			$this->archivo->SetDbValueDef($rsnew, $this->archivo->CurrentValue, NULL, $this->archivo->ReadOnly);

			// valorDolar
			$this->valorDolar->SetDbValueDef($rsnew, $this->valorDolar->CurrentValue, NULL, $this->valorDolar->ReadOnly);

			// comentarios
			$this->comentarios->SetDbValueDef($rsnew, $this->comentarios->CurrentValue, NULL, $this->comentarios->ReadOnly);

			// articulosAsociados
			$this->articulosAsociados->SetDbValueDef($rsnew, $this->articulosAsociados->CurrentValue, NULL, $this->articulosAsociados->ReadOnly);

			// movimientosAsociados
			$this->movimientosAsociados->SetDbValueDef($rsnew, $this->movimientosAsociados->CurrentValue, NULL, $this->movimientosAsociados->ReadOnly);

			// condicionVenta
			$this->condicionVenta->SetDbValueDef($rsnew, $this->condicionVenta->CurrentValue, NULL, $this->condicionVenta->ReadOnly);

			// vigencia
			$this->vigencia->SetDbValueDef($rsnew, $this->vigencia->CurrentValue, NULL, $this->vigencia->ReadOnly);

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
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idComprobante":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
			$sWhereWrk = "";
			$this->idComprobante->LookupFilters = array();
			$lookuptblfilter = "`activo` = 1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idComprobante, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
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
if (!isset($movimientos_edit)) $movimientos_edit = new cmovimientos_edit();

// Page init
$movimientos_edit->Page_Init();

// Page main
$movimientos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmovimientosedit = new ew_Form("fmovimientosedit", "edit");

// Validate form
fmovimientosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codTercero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->codTercero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->importeTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeIva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->importeIva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeNeto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->importeNeto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeCancelado");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->importeCancelado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vtoCae");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->vtoCae->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idUsuarioAlta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->idUsuarioAlta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaAlta");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->fechaAlta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idUsuarioModificacion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->idUsuarioModificacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaModificacion");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->fechaModificacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_contable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->contable->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_valorDolar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->valorDolar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_condicionVenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->condicionVenta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vigencia");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->vigencia->FldErrMsg()) ?>");

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
fmovimientosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientosedit.ValidateRequired = true;
<?php } else { ?>
fmovimientosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmovimientosedit.Lists["x_tipoMovimiento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosedit.Lists["x_tipoMovimiento"].Options = <?php echo json_encode($movimientos->tipoMovimiento->Options()) ?>;
fmovimientosedit.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fmovimientosedit.Lists["x_idComprobante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fmovimientosedit.Lists["x_idEstado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosedit.Lists["x_idEstado"].Options = <?php echo json_encode($movimientos->idEstado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$movimientos_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $movimientos_edit->ShowPageHeader(); ?>
<?php
$movimientos_edit->ShowMessage();
?>
<form name="fmovimientosedit" id="fmovimientosedit" class="<?php echo $movimientos_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($movimientos_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($movimientos->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_movimientos_id" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->id->CellAttributes() ?>>
<span id="el_movimientos_id">
<span<?php echo $movimientos->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $movimientos->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="movimientos" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($movimientos->id->CurrentValue) ?>">
<?php echo $movimientos->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
	<div id="r_nroComprobanteCompleto" class="form-group">
		<label id="elh_movimientos_nroComprobanteCompleto" for="x_nroComprobanteCompleto" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->nroComprobanteCompleto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->nroComprobanteCompleto->CellAttributes() ?>>
<span id="el_movimientos_nroComprobanteCompleto">
<input type="text" data-table="movimientos" data-field="x_nroComprobanteCompleto" name="x_nroComprobanteCompleto" id="x_nroComprobanteCompleto" size="30" maxlength="21" placeholder="<?php echo ew_HtmlEncode($movimientos->nroComprobanteCompleto->getPlaceHolder()) ?>" value="<?php echo $movimientos->nroComprobanteCompleto->EditValue ?>"<?php echo $movimientos->nroComprobanteCompleto->EditAttributes() ?>>
</span>
<?php echo $movimientos->nroComprobanteCompleto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
	<div id="r_tipoMovimiento" class="form-group">
		<label id="elh_movimientos_tipoMovimiento" for="x_tipoMovimiento" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->tipoMovimiento->CellAttributes() ?>>
<span id="el_movimientos_tipoMovimiento">
<select data-table="movimientos" data-field="x_tipoMovimiento" data-value-separator="<?php echo $movimientos->tipoMovimiento->DisplayValueSeparatorAttribute() ?>" id="x_tipoMovimiento" name="x_tipoMovimiento"<?php echo $movimientos->tipoMovimiento->EditAttributes() ?>>
<?php echo $movimientos->tipoMovimiento->SelectOptionListHtml("x_tipoMovimiento") ?>
</select>
</span>
<?php echo $movimientos->tipoMovimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_movimientos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->fecha->CellAttributes() ?>>
<span id="el_movimientos_fecha">
<input type="text" data-table="movimientos" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($movimientos->fecha->getPlaceHolder()) ?>" value="<?php echo $movimientos->fecha->EditValue ?>"<?php echo $movimientos->fecha->EditAttributes() ?>>
<?php if (!$movimientos->fecha->ReadOnly && !$movimientos->fecha->Disabled && !isset($movimientos->fecha->EditAttrs["readonly"]) && !isset($movimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fmovimientosedit", "x_fecha", 0);
</script>
<?php } ?>
</span>
<?php echo $movimientos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
	<div id="r_codTercero" class="form-group">
		<label id="elh_movimientos_codTercero" for="x_codTercero" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->codTercero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->codTercero->CellAttributes() ?>>
<span id="el_movimientos_codTercero">
<input type="text" data-table="movimientos" data-field="x_codTercero" name="x_codTercero" id="x_codTercero" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->codTercero->getPlaceHolder()) ?>" value="<?php echo $movimientos->codTercero->EditValue ?>"<?php echo $movimientos->codTercero->EditAttributes() ?>>
</span>
<?php echo $movimientos->codTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
	<div id="r_idTercero" class="form-group">
		<label id="elh_movimientos_idTercero" for="x_idTercero" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->idTercero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->idTercero->CellAttributes() ?>>
<span id="el_movimientos_idTercero">
<select data-table="movimientos" data-field="x_idTercero" data-value-separator="<?php echo $movimientos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $movimientos->idTercero->EditAttributes() ?>>
<?php echo $movimientos->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $movimientos->idTercero->LookupFilterQuery() ?>">
</span>
<?php echo $movimientos->idTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
	<div id="r_idComprobante" class="form-group">
		<label id="elh_movimientos_idComprobante" for="x_idComprobante" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->idComprobante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->idComprobante->CellAttributes() ?>>
<span id="el_movimientos_idComprobante">
<select data-table="movimientos" data-field="x_idComprobante" data-value-separator="<?php echo $movimientos->idComprobante->DisplayValueSeparatorAttribute() ?>" id="x_idComprobante" name="x_idComprobante"<?php echo $movimientos->idComprobante->EditAttributes() ?>>
<?php echo $movimientos->idComprobante->SelectOptionListHtml("x_idComprobante") ?>
</select>
<input type="hidden" name="s_x_idComprobante" id="s_x_idComprobante" value="<?php echo $movimientos->idComprobante->LookupFilterQuery() ?>">
</span>
<?php echo $movimientos->idComprobante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
	<div id="r_importeTotal" class="form-group">
		<label id="elh_movimientos_importeTotal" for="x_importeTotal" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->importeTotal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->importeTotal->CellAttributes() ?>>
<span id="el_movimientos_importeTotal">
<input type="text" data-table="movimientos" data-field="x_importeTotal" name="x_importeTotal" id="x_importeTotal" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->importeTotal->getPlaceHolder()) ?>" value="<?php echo $movimientos->importeTotal->EditValue ?>"<?php echo $movimientos->importeTotal->EditAttributes() ?>>
</span>
<?php echo $movimientos->importeTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
	<div id="r_importeIva" class="form-group">
		<label id="elh_movimientos_importeIva" for="x_importeIva" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->importeIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->importeIva->CellAttributes() ?>>
<span id="el_movimientos_importeIva">
<input type="text" data-table="movimientos" data-field="x_importeIva" name="x_importeIva" id="x_importeIva" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->importeIva->getPlaceHolder()) ?>" value="<?php echo $movimientos->importeIva->EditValue ?>"<?php echo $movimientos->importeIva->EditAttributes() ?>>
</span>
<?php echo $movimientos->importeIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
	<div id="r_importeNeto" class="form-group">
		<label id="elh_movimientos_importeNeto" for="x_importeNeto" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->importeNeto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->importeNeto->CellAttributes() ?>>
<span id="el_movimientos_importeNeto">
<input type="text" data-table="movimientos" data-field="x_importeNeto" name="x_importeNeto" id="x_importeNeto" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->importeNeto->getPlaceHolder()) ?>" value="<?php echo $movimientos->importeNeto->EditValue ?>"<?php echo $movimientos->importeNeto->EditAttributes() ?>>
</span>
<?php echo $movimientos->importeNeto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
	<div id="r_importeCancelado" class="form-group">
		<label id="elh_movimientos_importeCancelado" for="x_importeCancelado" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->importeCancelado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->importeCancelado->CellAttributes() ?>>
<span id="el_movimientos_importeCancelado">
<input type="text" data-table="movimientos" data-field="x_importeCancelado" name="x_importeCancelado" id="x_importeCancelado" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->importeCancelado->getPlaceHolder()) ?>" value="<?php echo $movimientos->importeCancelado->EditValue ?>"<?php echo $movimientos->importeCancelado->EditAttributes() ?>>
</span>
<?php echo $movimientos->importeCancelado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->cae->Visible) { // cae ?>
	<div id="r_cae" class="form-group">
		<label id="elh_movimientos_cae" for="x_cae" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->cae->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->cae->CellAttributes() ?>>
<span id="el_movimientos_cae">
<input type="text" data-table="movimientos" data-field="x_cae" name="x_cae" id="x_cae" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($movimientos->cae->getPlaceHolder()) ?>" value="<?php echo $movimientos->cae->EditValue ?>"<?php echo $movimientos->cae->EditAttributes() ?>>
</span>
<?php echo $movimientos->cae->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->vtoCae->Visible) { // vtoCae ?>
	<div id="r_vtoCae" class="form-group">
		<label id="elh_movimientos_vtoCae" for="x_vtoCae" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->vtoCae->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->vtoCae->CellAttributes() ?>>
<span id="el_movimientos_vtoCae">
<input type="text" data-table="movimientos" data-field="x_vtoCae" name="x_vtoCae" id="x_vtoCae" placeholder="<?php echo ew_HtmlEncode($movimientos->vtoCae->getPlaceHolder()) ?>" value="<?php echo $movimientos->vtoCae->EditValue ?>"<?php echo $movimientos->vtoCae->EditAttributes() ?>>
</span>
<?php echo $movimientos->vtoCae->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
	<div id="r_idEstado" class="form-group">
		<label id="elh_movimientos_idEstado" for="x_idEstado" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->idEstado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->idEstado->CellAttributes() ?>>
<span id="el_movimientos_idEstado">
<select data-table="movimientos" data-field="x_idEstado" data-value-separator="<?php echo $movimientos->idEstado->DisplayValueSeparatorAttribute() ?>" id="x_idEstado" name="x_idEstado"<?php echo $movimientos->idEstado->EditAttributes() ?>>
<?php echo $movimientos->idEstado->SelectOptionListHtml("x_idEstado") ?>
</select>
</span>
<?php echo $movimientos->idEstado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->idUsuarioAlta->Visible) { // idUsuarioAlta ?>
	<div id="r_idUsuarioAlta" class="form-group">
		<label id="elh_movimientos_idUsuarioAlta" for="x_idUsuarioAlta" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->idUsuarioAlta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->idUsuarioAlta->CellAttributes() ?>>
<span id="el_movimientos_idUsuarioAlta">
<input type="text" data-table="movimientos" data-field="x_idUsuarioAlta" name="x_idUsuarioAlta" id="x_idUsuarioAlta" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->idUsuarioAlta->getPlaceHolder()) ?>" value="<?php echo $movimientos->idUsuarioAlta->EditValue ?>"<?php echo $movimientos->idUsuarioAlta->EditAttributes() ?>>
</span>
<?php echo $movimientos->idUsuarioAlta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->fechaAlta->Visible) { // fechaAlta ?>
	<div id="r_fechaAlta" class="form-group">
		<label id="elh_movimientos_fechaAlta" for="x_fechaAlta" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->fechaAlta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->fechaAlta->CellAttributes() ?>>
<span id="el_movimientos_fechaAlta">
<input type="text" data-table="movimientos" data-field="x_fechaAlta" name="x_fechaAlta" id="x_fechaAlta" placeholder="<?php echo ew_HtmlEncode($movimientos->fechaAlta->getPlaceHolder()) ?>" value="<?php echo $movimientos->fechaAlta->EditValue ?>"<?php echo $movimientos->fechaAlta->EditAttributes() ?>>
</span>
<?php echo $movimientos->fechaAlta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->idUsuarioModificacion->Visible) { // idUsuarioModificacion ?>
	<div id="r_idUsuarioModificacion" class="form-group">
		<label id="elh_movimientos_idUsuarioModificacion" for="x_idUsuarioModificacion" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->idUsuarioModificacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->idUsuarioModificacion->CellAttributes() ?>>
<span id="el_movimientos_idUsuarioModificacion">
<input type="text" data-table="movimientos" data-field="x_idUsuarioModificacion" name="x_idUsuarioModificacion" id="x_idUsuarioModificacion" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->idUsuarioModificacion->getPlaceHolder()) ?>" value="<?php echo $movimientos->idUsuarioModificacion->EditValue ?>"<?php echo $movimientos->idUsuarioModificacion->EditAttributes() ?>>
</span>
<?php echo $movimientos->idUsuarioModificacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->fechaModificacion->Visible) { // fechaModificacion ?>
	<div id="r_fechaModificacion" class="form-group">
		<label id="elh_movimientos_fechaModificacion" for="x_fechaModificacion" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->fechaModificacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->fechaModificacion->CellAttributes() ?>>
<span id="el_movimientos_fechaModificacion">
<input type="text" data-table="movimientos" data-field="x_fechaModificacion" name="x_fechaModificacion" id="x_fechaModificacion" placeholder="<?php echo ew_HtmlEncode($movimientos->fechaModificacion->getPlaceHolder()) ?>" value="<?php echo $movimientos->fechaModificacion->EditValue ?>"<?php echo $movimientos->fechaModificacion->EditAttributes() ?>>
</span>
<?php echo $movimientos->fechaModificacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->contable->Visible) { // contable ?>
	<div id="r_contable" class="form-group">
		<label id="elh_movimientos_contable" for="x_contable" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->contable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->contable->CellAttributes() ?>>
<span id="el_movimientos_contable">
<input type="text" data-table="movimientos" data-field="x_contable" name="x_contable" id="x_contable" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->contable->getPlaceHolder()) ?>" value="<?php echo $movimientos->contable->EditValue ?>"<?php echo $movimientos->contable->EditAttributes() ?>>
</span>
<?php echo $movimientos->contable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->archivo->Visible) { // archivo ?>
	<div id="r_archivo" class="form-group">
		<label id="elh_movimientos_archivo" for="x_archivo" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->archivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->archivo->CellAttributes() ?>>
<span id="el_movimientos_archivo">
<input type="text" data-table="movimientos" data-field="x_archivo" name="x_archivo" id="x_archivo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($movimientos->archivo->getPlaceHolder()) ?>" value="<?php echo $movimientos->archivo->EditValue ?>"<?php echo $movimientos->archivo->EditAttributes() ?>>
</span>
<?php echo $movimientos->archivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->valorDolar->Visible) { // valorDolar ?>
	<div id="r_valorDolar" class="form-group">
		<label id="elh_movimientos_valorDolar" for="x_valorDolar" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->valorDolar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->valorDolar->CellAttributes() ?>>
<span id="el_movimientos_valorDolar">
<input type="text" data-table="movimientos" data-field="x_valorDolar" name="x_valorDolar" id="x_valorDolar" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->valorDolar->getPlaceHolder()) ?>" value="<?php echo $movimientos->valorDolar->EditValue ?>"<?php echo $movimientos->valorDolar->EditAttributes() ?>>
</span>
<?php echo $movimientos->valorDolar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->comentarios->Visible) { // comentarios ?>
	<div id="r_comentarios" class="form-group">
		<label id="elh_movimientos_comentarios" for="x_comentarios" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->comentarios->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->comentarios->CellAttributes() ?>>
<span id="el_movimientos_comentarios">
<textarea data-table="movimientos" data-field="x_comentarios" name="x_comentarios" id="x_comentarios" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($movimientos->comentarios->getPlaceHolder()) ?>"<?php echo $movimientos->comentarios->EditAttributes() ?>><?php echo $movimientos->comentarios->EditValue ?></textarea>
</span>
<?php echo $movimientos->comentarios->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->articulosAsociados->Visible) { // articulosAsociados ?>
	<div id="r_articulosAsociados" class="form-group">
		<label id="elh_movimientos_articulosAsociados" for="x_articulosAsociados" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->articulosAsociados->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->articulosAsociados->CellAttributes() ?>>
<span id="el_movimientos_articulosAsociados">
<textarea data-table="movimientos" data-field="x_articulosAsociados" name="x_articulosAsociados" id="x_articulosAsociados" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($movimientos->articulosAsociados->getPlaceHolder()) ?>"<?php echo $movimientos->articulosAsociados->EditAttributes() ?>><?php echo $movimientos->articulosAsociados->EditValue ?></textarea>
</span>
<?php echo $movimientos->articulosAsociados->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
	<div id="r_movimientosAsociados" class="form-group">
		<label id="elh_movimientos_movimientosAsociados" for="x_movimientosAsociados" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->movimientosAsociados->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->movimientosAsociados->CellAttributes() ?>>
<span id="el_movimientos_movimientosAsociados">
<textarea data-table="movimientos" data-field="x_movimientosAsociados" name="x_movimientosAsociados" id="x_movimientosAsociados" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($movimientos->movimientosAsociados->getPlaceHolder()) ?>"<?php echo $movimientos->movimientosAsociados->EditAttributes() ?>><?php echo $movimientos->movimientosAsociados->EditValue ?></textarea>
</span>
<?php echo $movimientos->movimientosAsociados->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
	<div id="r_condicionVenta" class="form-group">
		<label id="elh_movimientos_condicionVenta" for="x_condicionVenta" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->condicionVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->condicionVenta->CellAttributes() ?>>
<span id="el_movimientos_condicionVenta">
<input type="text" data-table="movimientos" data-field="x_condicionVenta" name="x_condicionVenta" id="x_condicionVenta" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->condicionVenta->getPlaceHolder()) ?>" value="<?php echo $movimientos->condicionVenta->EditValue ?>"<?php echo $movimientos->condicionVenta->EditAttributes() ?>>
</span>
<?php echo $movimientos->condicionVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
	<div id="r_vigencia" class="form-group">
		<label id="elh_movimientos_vigencia" for="x_vigencia" class="col-sm-2 control-label ewLabel"><?php echo $movimientos->vigencia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos->vigencia->CellAttributes() ?>>
<span id="el_movimientos_vigencia">
<input type="text" data-table="movimientos" data-field="x_vigencia" name="x_vigencia" id="x_vigencia" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos->vigencia->getPlaceHolder()) ?>" value="<?php echo $movimientos->vigencia->EditValue ?>"<?php echo $movimientos->vigencia->EditAttributes() ?>>
</span>
<?php echo $movimientos->vigencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$movimientos_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $movimientos_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fmovimientosedit.Init();
</script>
<?php
$movimientos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$movimientos_edit->Page_Terminate();
?>
