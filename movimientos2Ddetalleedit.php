<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "movimientos2Ddetalleinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$movimientos2Ddetalle_edit = NULL; // Initialize page object first

class cmovimientos2Ddetalle_edit extends cmovimientos2Ddetalle {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos-detalle';

	// Page object name
	var $PageObjName = 'movimientos2Ddetalle_edit';

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

		// Table object (movimientos2Ddetalle)
		if (!isset($GLOBALS["movimientos2Ddetalle"]) || get_class($GLOBALS["movimientos2Ddetalle"]) == "cmovimientos2Ddetalle") {
			$GLOBALS["movimientos2Ddetalle"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["movimientos2Ddetalle"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'movimientos-detalle', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("movimientos2Ddetallelist.php"));
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
		$this->idMovimientos->SetVisibility();
		$this->cant->SetVisibility();
		$this->idUnidadMedida->SetVisibility();
		$this->codProducto->SetVisibility();
		$this->medida->SetVisibility();
		$this->nombreProducto->SetVisibility();
		$this->importeUnitario->SetVisibility();
		$this->bonificacion->SetVisibility();
		$this->importeTotal->SetVisibility();
		$this->alicuotaIva->SetVisibility();
		$this->importeIva->SetVisibility();
		$this->importeNeto->SetVisibility();
		$this->importePesos->SetVisibility();
		$this->estadoImportacion->SetVisibility();
		$this->origenImportacion->SetVisibility();

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
		global $EW_EXPORT, $movimientos2Ddetalle;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($movimientos2Ddetalle);
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
			$this->Page_Terminate("movimientos2Ddetallelist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("movimientos2Ddetallelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "movimientos2Ddetallelist.php")
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
		if (!$this->idMovimientos->FldIsDetailKey) {
			$this->idMovimientos->setFormValue($objForm->GetValue("x_idMovimientos"));
		}
		if (!$this->cant->FldIsDetailKey) {
			$this->cant->setFormValue($objForm->GetValue("x_cant"));
		}
		if (!$this->idUnidadMedida->FldIsDetailKey) {
			$this->idUnidadMedida->setFormValue($objForm->GetValue("x_idUnidadMedida"));
		}
		if (!$this->codProducto->FldIsDetailKey) {
			$this->codProducto->setFormValue($objForm->GetValue("x_codProducto"));
		}
		if (!$this->medida->FldIsDetailKey) {
			$this->medida->setFormValue($objForm->GetValue("x_medida"));
		}
		if (!$this->nombreProducto->FldIsDetailKey) {
			$this->nombreProducto->setFormValue($objForm->GetValue("x_nombreProducto"));
		}
		if (!$this->importeUnitario->FldIsDetailKey) {
			$this->importeUnitario->setFormValue($objForm->GetValue("x_importeUnitario"));
		}
		if (!$this->bonificacion->FldIsDetailKey) {
			$this->bonificacion->setFormValue($objForm->GetValue("x_bonificacion"));
		}
		if (!$this->importeTotal->FldIsDetailKey) {
			$this->importeTotal->setFormValue($objForm->GetValue("x_importeTotal"));
		}
		if (!$this->alicuotaIva->FldIsDetailKey) {
			$this->alicuotaIva->setFormValue($objForm->GetValue("x_alicuotaIva"));
		}
		if (!$this->importeIva->FldIsDetailKey) {
			$this->importeIva->setFormValue($objForm->GetValue("x_importeIva"));
		}
		if (!$this->importeNeto->FldIsDetailKey) {
			$this->importeNeto->setFormValue($objForm->GetValue("x_importeNeto"));
		}
		if (!$this->importePesos->FldIsDetailKey) {
			$this->importePesos->setFormValue($objForm->GetValue("x_importePesos"));
		}
		if (!$this->estadoImportacion->FldIsDetailKey) {
			$this->estadoImportacion->setFormValue($objForm->GetValue("x_estadoImportacion"));
		}
		if (!$this->origenImportacion->FldIsDetailKey) {
			$this->origenImportacion->setFormValue($objForm->GetValue("x_origenImportacion"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idMovimientos->CurrentValue = $this->idMovimientos->FormValue;
		$this->cant->CurrentValue = $this->cant->FormValue;
		$this->idUnidadMedida->CurrentValue = $this->idUnidadMedida->FormValue;
		$this->codProducto->CurrentValue = $this->codProducto->FormValue;
		$this->medida->CurrentValue = $this->medida->FormValue;
		$this->nombreProducto->CurrentValue = $this->nombreProducto->FormValue;
		$this->importeUnitario->CurrentValue = $this->importeUnitario->FormValue;
		$this->bonificacion->CurrentValue = $this->bonificacion->FormValue;
		$this->importeTotal->CurrentValue = $this->importeTotal->FormValue;
		$this->alicuotaIva->CurrentValue = $this->alicuotaIva->FormValue;
		$this->importeIva->CurrentValue = $this->importeIva->FormValue;
		$this->importeNeto->CurrentValue = $this->importeNeto->FormValue;
		$this->importePesos->CurrentValue = $this->importePesos->FormValue;
		$this->estadoImportacion->CurrentValue = $this->estadoImportacion->FormValue;
		$this->origenImportacion->CurrentValue = $this->origenImportacion->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idMovimientos->DbValue = $row['idMovimientos'];
		$this->cant->DbValue = $row['cant'];
		$this->idUnidadMedida->DbValue = $row['idUnidadMedida'];
		$this->codProducto->DbValue = $row['codProducto'];
		$this->medida->DbValue = $row['medida'];
		$this->nombreProducto->DbValue = $row['nombreProducto'];
		$this->importeUnitario->DbValue = $row['importeUnitario'];
		$this->bonificacion->DbValue = $row['bonificacion'];
		$this->importeTotal->DbValue = $row['importeTotal'];
		$this->alicuotaIva->DbValue = $row['alicuotaIva'];
		$this->importeIva->DbValue = $row['importeIva'];
		$this->importeNeto->DbValue = $row['importeNeto'];
		$this->importePesos->DbValue = $row['importePesos'];
		$this->estadoImportacion->DbValue = $row['estadoImportacion'];
		$this->origenImportacion->DbValue = $row['origenImportacion'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->cant->FormValue == $this->cant->CurrentValue && is_numeric(ew_StrToFloat($this->cant->CurrentValue)))
			$this->cant->CurrentValue = ew_StrToFloat($this->cant->CurrentValue);

		// Convert decimal values if posted back
		if ($this->medida->FormValue == $this->medida->CurrentValue && is_numeric(ew_StrToFloat($this->medida->CurrentValue)))
			$this->medida->CurrentValue = ew_StrToFloat($this->medida->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeUnitario->FormValue == $this->importeUnitario->CurrentValue && is_numeric(ew_StrToFloat($this->importeUnitario->CurrentValue)))
			$this->importeUnitario->CurrentValue = ew_StrToFloat($this->importeUnitario->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bonificacion->FormValue == $this->bonificacion->CurrentValue && is_numeric(ew_StrToFloat($this->bonificacion->CurrentValue)))
			$this->bonificacion->CurrentValue = ew_StrToFloat($this->bonificacion->CurrentValue);

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
		if ($this->importePesos->FormValue == $this->importePesos->CurrentValue && is_numeric(ew_StrToFloat($this->importePesos->CurrentValue)))
			$this->importePesos->CurrentValue = ew_StrToFloat($this->importePesos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// idMovimientos
			$this->idMovimientos->EditAttrs["class"] = "form-control";
			$this->idMovimientos->EditCustomAttributes = "";
			$this->idMovimientos->EditValue = ew_HtmlEncode($this->idMovimientos->CurrentValue);
			$this->idMovimientos->PlaceHolder = ew_RemoveHtml($this->idMovimientos->FldCaption());

			// cant
			$this->cant->EditAttrs["class"] = "form-control";
			$this->cant->EditCustomAttributes = "";
			$this->cant->EditValue = ew_HtmlEncode($this->cant->CurrentValue);
			$this->cant->PlaceHolder = ew_RemoveHtml($this->cant->FldCaption());
			if (strval($this->cant->EditValue) <> "" && is_numeric($this->cant->EditValue)) $this->cant->EditValue = ew_FormatNumber($this->cant->EditValue, -2, -1, -2, 0);

			// idUnidadMedida
			$this->idUnidadMedida->EditAttrs["class"] = "form-control";
			$this->idUnidadMedida->EditCustomAttributes = "";
			$this->idUnidadMedida->EditValue = ew_HtmlEncode($this->idUnidadMedida->CurrentValue);
			$this->idUnidadMedida->PlaceHolder = ew_RemoveHtml($this->idUnidadMedida->FldCaption());

			// codProducto
			$this->codProducto->EditAttrs["class"] = "form-control";
			$this->codProducto->EditCustomAttributes = "";
			$this->codProducto->EditValue = ew_HtmlEncode($this->codProducto->CurrentValue);
			$this->codProducto->PlaceHolder = ew_RemoveHtml($this->codProducto->FldCaption());

			// medida
			$this->medida->EditAttrs["class"] = "form-control";
			$this->medida->EditCustomAttributes = "";
			$this->medida->EditValue = ew_HtmlEncode($this->medida->CurrentValue);
			$this->medida->PlaceHolder = ew_RemoveHtml($this->medida->FldCaption());
			if (strval($this->medida->EditValue) <> "" && is_numeric($this->medida->EditValue)) $this->medida->EditValue = ew_FormatNumber($this->medida->EditValue, -2, -1, -2, 0);

			// nombreProducto
			$this->nombreProducto->EditAttrs["class"] = "form-control";
			$this->nombreProducto->EditCustomAttributes = "";
			$this->nombreProducto->EditValue = ew_HtmlEncode($this->nombreProducto->CurrentValue);
			$this->nombreProducto->PlaceHolder = ew_RemoveHtml($this->nombreProducto->FldCaption());

			// importeUnitario
			$this->importeUnitario->EditAttrs["class"] = "form-control";
			$this->importeUnitario->EditCustomAttributes = "";
			$this->importeUnitario->EditValue = ew_HtmlEncode($this->importeUnitario->CurrentValue);
			$this->importeUnitario->PlaceHolder = ew_RemoveHtml($this->importeUnitario->FldCaption());
			if (strval($this->importeUnitario->EditValue) <> "" && is_numeric($this->importeUnitario->EditValue)) $this->importeUnitario->EditValue = ew_FormatNumber($this->importeUnitario->EditValue, -2, -1, -2, 0);

			// bonificacion
			$this->bonificacion->EditAttrs["class"] = "form-control";
			$this->bonificacion->EditCustomAttributes = "";
			$this->bonificacion->EditValue = ew_HtmlEncode($this->bonificacion->CurrentValue);
			$this->bonificacion->PlaceHolder = ew_RemoveHtml($this->bonificacion->FldCaption());
			if (strval($this->bonificacion->EditValue) <> "" && is_numeric($this->bonificacion->EditValue)) $this->bonificacion->EditValue = ew_FormatNumber($this->bonificacion->EditValue, -2, -1, -2, 0);

			// importeTotal
			$this->importeTotal->EditAttrs["class"] = "form-control";
			$this->importeTotal->EditCustomAttributes = "";
			$this->importeTotal->EditValue = ew_HtmlEncode($this->importeTotal->CurrentValue);
			$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());
			if (strval($this->importeTotal->EditValue) <> "" && is_numeric($this->importeTotal->EditValue)) $this->importeTotal->EditValue = ew_FormatNumber($this->importeTotal->EditValue, -2, -1, -2, 0);

			// alicuotaIva
			$this->alicuotaIva->EditAttrs["class"] = "form-control";
			$this->alicuotaIva->EditCustomAttributes = "";
			$this->alicuotaIva->EditValue = ew_HtmlEncode($this->alicuotaIva->CurrentValue);
			$this->alicuotaIva->PlaceHolder = ew_RemoveHtml($this->alicuotaIva->FldCaption());

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

			// importePesos
			$this->importePesos->EditAttrs["class"] = "form-control";
			$this->importePesos->EditCustomAttributes = "";
			$this->importePesos->EditValue = ew_HtmlEncode($this->importePesos->CurrentValue);
			$this->importePesos->PlaceHolder = ew_RemoveHtml($this->importePesos->FldCaption());
			if (strval($this->importePesos->EditValue) <> "" && is_numeric($this->importePesos->EditValue)) $this->importePesos->EditValue = ew_FormatNumber($this->importePesos->EditValue, -2, -1, -2, 0);

			// estadoImportacion
			$this->estadoImportacion->EditAttrs["class"] = "form-control";
			$this->estadoImportacion->EditCustomAttributes = "";
			$this->estadoImportacion->EditValue = ew_HtmlEncode($this->estadoImportacion->CurrentValue);
			$this->estadoImportacion->PlaceHolder = ew_RemoveHtml($this->estadoImportacion->FldCaption());

			// origenImportacion
			$this->origenImportacion->EditAttrs["class"] = "form-control";
			$this->origenImportacion->EditCustomAttributes = "";
			$this->origenImportacion->EditValue = ew_HtmlEncode($this->origenImportacion->CurrentValue);
			$this->origenImportacion->PlaceHolder = ew_RemoveHtml($this->origenImportacion->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// idMovimientos
			$this->idMovimientos->LinkCustomAttributes = "";
			$this->idMovimientos->HrefValue = "";

			// cant
			$this->cant->LinkCustomAttributes = "";
			$this->cant->HrefValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";

			// codProducto
			$this->codProducto->LinkCustomAttributes = "";
			$this->codProducto->HrefValue = "";

			// medida
			$this->medida->LinkCustomAttributes = "";
			$this->medida->HrefValue = "";

			// nombreProducto
			$this->nombreProducto->LinkCustomAttributes = "";
			$this->nombreProducto->HrefValue = "";

			// importeUnitario
			$this->importeUnitario->LinkCustomAttributes = "";
			$this->importeUnitario->HrefValue = "";

			// bonificacion
			$this->bonificacion->LinkCustomAttributes = "";
			$this->bonificacion->HrefValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";

			// alicuotaIva
			$this->alicuotaIva->LinkCustomAttributes = "";
			$this->alicuotaIva->HrefValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";

			// importePesos
			$this->importePesos->LinkCustomAttributes = "";
			$this->importePesos->HrefValue = "";

			// estadoImportacion
			$this->estadoImportacion->LinkCustomAttributes = "";
			$this->estadoImportacion->HrefValue = "";

			// origenImportacion
			$this->origenImportacion->LinkCustomAttributes = "";
			$this->origenImportacion->HrefValue = "";
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
		if (!ew_CheckInteger($this->idMovimientos->FormValue)) {
			ew_AddMessage($gsFormError, $this->idMovimientos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->cant->FormValue)) {
			ew_AddMessage($gsFormError, $this->cant->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idUnidadMedida->FormValue)) {
			ew_AddMessage($gsFormError, $this->idUnidadMedida->FldErrMsg());
		}
		if (!ew_CheckInteger($this->codProducto->FormValue)) {
			ew_AddMessage($gsFormError, $this->codProducto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->medida->FormValue)) {
			ew_AddMessage($gsFormError, $this->medida->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeUnitario->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeUnitario->FldErrMsg());
		}
		if (!ew_CheckNumber($this->bonificacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->bonificacion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeTotal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->alicuotaIva->FormValue)) {
			ew_AddMessage($gsFormError, $this->alicuotaIva->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeIva->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeIva->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeNeto->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeNeto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importePesos->FormValue)) {
			ew_AddMessage($gsFormError, $this->importePesos->FldErrMsg());
		}
		if (!ew_CheckInteger($this->estadoImportacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->estadoImportacion->FldErrMsg());
		}
		if (!ew_CheckInteger($this->origenImportacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->origenImportacion->FldErrMsg());
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

			// idMovimientos
			$this->idMovimientos->SetDbValueDef($rsnew, $this->idMovimientos->CurrentValue, NULL, $this->idMovimientos->ReadOnly);

			// cant
			$this->cant->SetDbValueDef($rsnew, $this->cant->CurrentValue, NULL, $this->cant->ReadOnly);

			// idUnidadMedida
			$this->idUnidadMedida->SetDbValueDef($rsnew, $this->idUnidadMedida->CurrentValue, NULL, $this->idUnidadMedida->ReadOnly);

			// codProducto
			$this->codProducto->SetDbValueDef($rsnew, $this->codProducto->CurrentValue, NULL, $this->codProducto->ReadOnly);

			// medida
			$this->medida->SetDbValueDef($rsnew, $this->medida->CurrentValue, NULL, $this->medida->ReadOnly);

			// nombreProducto
			$this->nombreProducto->SetDbValueDef($rsnew, $this->nombreProducto->CurrentValue, NULL, $this->nombreProducto->ReadOnly);

			// importeUnitario
			$this->importeUnitario->SetDbValueDef($rsnew, $this->importeUnitario->CurrentValue, NULL, $this->importeUnitario->ReadOnly);

			// bonificacion
			$this->bonificacion->SetDbValueDef($rsnew, $this->bonificacion->CurrentValue, NULL, $this->bonificacion->ReadOnly);

			// importeTotal
			$this->importeTotal->SetDbValueDef($rsnew, $this->importeTotal->CurrentValue, NULL, $this->importeTotal->ReadOnly);

			// alicuotaIva
			$this->alicuotaIva->SetDbValueDef($rsnew, $this->alicuotaIva->CurrentValue, NULL, $this->alicuotaIva->ReadOnly);

			// importeIva
			$this->importeIva->SetDbValueDef($rsnew, $this->importeIva->CurrentValue, NULL, $this->importeIva->ReadOnly);

			// importeNeto
			$this->importeNeto->SetDbValueDef($rsnew, $this->importeNeto->CurrentValue, NULL, $this->importeNeto->ReadOnly);

			// importePesos
			$this->importePesos->SetDbValueDef($rsnew, $this->importePesos->CurrentValue, NULL, $this->importePesos->ReadOnly);

			// estadoImportacion
			$this->estadoImportacion->SetDbValueDef($rsnew, $this->estadoImportacion->CurrentValue, NULL, $this->estadoImportacion->ReadOnly);

			// origenImportacion
			$this->origenImportacion->SetDbValueDef($rsnew, $this->origenImportacion->CurrentValue, NULL, $this->origenImportacion->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientos2Ddetallelist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($movimientos2Ddetalle_edit)) $movimientos2Ddetalle_edit = new cmovimientos2Ddetalle_edit();

// Page init
$movimientos2Ddetalle_edit->Page_Init();

// Page main
$movimientos2Ddetalle_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos2Ddetalle_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fmovimientos2Ddetalleedit = new ew_Form("fmovimientos2Ddetalleedit", "edit");

// Validate form
fmovimientos2Ddetalleedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idMovimientos");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->idMovimientos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cant");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->cant->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idUnidadMedida");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->idUnidadMedida->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_codProducto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->codProducto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_medida");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->medida->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeUnitario");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->importeUnitario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bonificacion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->bonificacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->importeTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_alicuotaIva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->alicuotaIva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeIva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->importeIva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeNeto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->importeNeto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importePesos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->importePesos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estadoImportacion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->estadoImportacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_origenImportacion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos2Ddetalle->origenImportacion->FldErrMsg()) ?>");

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
fmovimientos2Ddetalleedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientos2Ddetalleedit.ValidateRequired = true;
<?php } else { ?>
fmovimientos2Ddetalleedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$movimientos2Ddetalle_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $movimientos2Ddetalle_edit->ShowPageHeader(); ?>
<?php
$movimientos2Ddetalle_edit->ShowMessage();
?>
<form name="fmovimientos2Ddetalleedit" id="fmovimientos2Ddetalleedit" class="<?php echo $movimientos2Ddetalle_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos2Ddetalle_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos2Ddetalle_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos2Ddetalle">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($movimientos2Ddetalle_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_movimientos2Ddetalle_id" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->id->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_id">
<span<?php echo $movimientos2Ddetalle->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $movimientos2Ddetalle->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="movimientos2Ddetalle" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($movimientos2Ddetalle->id->CurrentValue) ?>">
<?php echo $movimientos2Ddetalle->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
	<div id="r_idMovimientos" class="form-group">
		<label id="elh_movimientos2Ddetalle_idMovimientos" for="x_idMovimientos" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->idMovimientos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->idMovimientos->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_idMovimientos">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_idMovimientos" name="x_idMovimientos" id="x_idMovimientos" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->idMovimientos->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->idMovimientos->EditValue ?>"<?php echo $movimientos2Ddetalle->idMovimientos->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->idMovimientos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
	<div id="r_cant" class="form-group">
		<label id="elh_movimientos2Ddetalle_cant" for="x_cant" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->cant->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->cant->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_cant">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_cant" name="x_cant" id="x_cant" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->cant->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->cant->EditValue ?>"<?php echo $movimientos2Ddetalle->cant->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->cant->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<div id="r_idUnidadMedida" class="form-group">
		<label id="elh_movimientos2Ddetalle_idUnidadMedida" for="x_idUnidadMedida" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->idUnidadMedida->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->idUnidadMedida->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_idUnidadMedida">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_idUnidadMedida" name="x_idUnidadMedida" id="x_idUnidadMedida" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->idUnidadMedida->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->idUnidadMedida->EditValue ?>"<?php echo $movimientos2Ddetalle->idUnidadMedida->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->idUnidadMedida->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
	<div id="r_codProducto" class="form-group">
		<label id="elh_movimientos2Ddetalle_codProducto" for="x_codProducto" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->codProducto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->codProducto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_codProducto">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_codProducto" name="x_codProducto" id="x_codProducto" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->codProducto->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->codProducto->EditValue ?>"<?php echo $movimientos2Ddetalle->codProducto->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->codProducto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
	<div id="r_medida" class="form-group">
		<label id="elh_movimientos2Ddetalle_medida" for="x_medida" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->medida->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->medida->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_medida">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_medida" name="x_medida" id="x_medida" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->medida->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->medida->EditValue ?>"<?php echo $movimientos2Ddetalle->medida->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->medida->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
	<div id="r_nombreProducto" class="form-group">
		<label id="elh_movimientos2Ddetalle_nombreProducto" for="x_nombreProducto" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->nombreProducto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->nombreProducto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_nombreProducto">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_nombreProducto" name="x_nombreProducto" id="x_nombreProducto" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->nombreProducto->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->nombreProducto->EditValue ?>"<?php echo $movimientos2Ddetalle->nombreProducto->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->nombreProducto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
	<div id="r_importeUnitario" class="form-group">
		<label id="elh_movimientos2Ddetalle_importeUnitario" for="x_importeUnitario" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->importeUnitario->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->importeUnitario->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeUnitario">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_importeUnitario" name="x_importeUnitario" id="x_importeUnitario" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->importeUnitario->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->importeUnitario->EditValue ?>"<?php echo $movimientos2Ddetalle->importeUnitario->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->importeUnitario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
	<div id="r_bonificacion" class="form-group">
		<label id="elh_movimientos2Ddetalle_bonificacion" for="x_bonificacion" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->bonificacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->bonificacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_bonificacion">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_bonificacion" name="x_bonificacion" id="x_bonificacion" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->bonificacion->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->bonificacion->EditValue ?>"<?php echo $movimientos2Ddetalle->bonificacion->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->bonificacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
	<div id="r_importeTotal" class="form-group">
		<label id="elh_movimientos2Ddetalle_importeTotal" for="x_importeTotal" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->importeTotal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->importeTotal->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeTotal">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_importeTotal" name="x_importeTotal" id="x_importeTotal" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->importeTotal->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->importeTotal->EditValue ?>"<?php echo $movimientos2Ddetalle->importeTotal->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->importeTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
	<div id="r_alicuotaIva" class="form-group">
		<label id="elh_movimientos2Ddetalle_alicuotaIva" for="x_alicuotaIva" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->alicuotaIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->alicuotaIva->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_alicuotaIva">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_alicuotaIva" name="x_alicuotaIva" id="x_alicuotaIva" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->alicuotaIva->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->alicuotaIva->EditValue ?>"<?php echo $movimientos2Ddetalle->alicuotaIva->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->alicuotaIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
	<div id="r_importeIva" class="form-group">
		<label id="elh_movimientos2Ddetalle_importeIva" for="x_importeIva" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->importeIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->importeIva->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeIva">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_importeIva" name="x_importeIva" id="x_importeIva" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->importeIva->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->importeIva->EditValue ?>"<?php echo $movimientos2Ddetalle->importeIva->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->importeIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
	<div id="r_importeNeto" class="form-group">
		<label id="elh_movimientos2Ddetalle_importeNeto" for="x_importeNeto" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->importeNeto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->importeNeto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeNeto">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_importeNeto" name="x_importeNeto" id="x_importeNeto" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->importeNeto->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->importeNeto->EditValue ?>"<?php echo $movimientos2Ddetalle->importeNeto->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->importeNeto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
	<div id="r_importePesos" class="form-group">
		<label id="elh_movimientos2Ddetalle_importePesos" for="x_importePesos" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->importePesos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->importePesos->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importePesos">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_importePesos" name="x_importePesos" id="x_importePesos" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->importePesos->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->importePesos->EditValue ?>"<?php echo $movimientos2Ddetalle->importePesos->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->importePesos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
	<div id="r_estadoImportacion" class="form-group">
		<label id="elh_movimientos2Ddetalle_estadoImportacion" for="x_estadoImportacion" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->estadoImportacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->estadoImportacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_estadoImportacion">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_estadoImportacion" name="x_estadoImportacion" id="x_estadoImportacion" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->estadoImportacion->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->estadoImportacion->EditValue ?>"<?php echo $movimientos2Ddetalle->estadoImportacion->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->estadoImportacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
	<div id="r_origenImportacion" class="form-group">
		<label id="elh_movimientos2Ddetalle_origenImportacion" for="x_origenImportacion" class="col-sm-2 control-label ewLabel"><?php echo $movimientos2Ddetalle->origenImportacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $movimientos2Ddetalle->origenImportacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_origenImportacion">
<input type="text" data-table="movimientos2Ddetalle" data-field="x_origenImportacion" name="x_origenImportacion" id="x_origenImportacion" size="30" placeholder="<?php echo ew_HtmlEncode($movimientos2Ddetalle->origenImportacion->getPlaceHolder()) ?>" value="<?php echo $movimientos2Ddetalle->origenImportacion->EditValue ?>"<?php echo $movimientos2Ddetalle->origenImportacion->EditAttributes() ?>>
</span>
<?php echo $movimientos2Ddetalle->origenImportacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$movimientos2Ddetalle_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $movimientos2Ddetalle_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fmovimientos2Ddetalleedit.Init();
</script>
<?php
$movimientos2Ddetalle_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$movimientos2Ddetalle_edit->Page_Terminate();
?>
