<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "comprobantes2Dnumeracioninfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "comprobantesinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$comprobantes2Dnumeracion_edit = NULL; // Initialize page object first

class ccomprobantes2Dnumeracion_edit extends ccomprobantes2Dnumeracion {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'comprobantes-numeracion';

	// Page object name
	var $PageObjName = 'comprobantes2Dnumeracion_edit';

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

		// Table object (comprobantes2Dnumeracion)
		if (!isset($GLOBALS["comprobantes2Dnumeracion"]) || get_class($GLOBALS["comprobantes2Dnumeracion"]) == "ccomprobantes2Dnumeracion") {
			$GLOBALS["comprobantes2Dnumeracion"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["comprobantes2Dnumeracion"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (comprobantes)
		if (!isset($GLOBALS['comprobantes'])) $GLOBALS['comprobantes'] = new ccomprobantes();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'comprobantes-numeracion', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("comprobantes2Dnumeracionlist.php"));
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
		$this->idComprobante->SetVisibility();
		$this->puntoVenta->SetVisibility();
		$this->ultimoNumero->SetVisibility();
		$this->ultimoNumeroContable->SetVisibility();

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
		global $EW_EXPORT, $comprobantes2Dnumeracion;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($comprobantes2Dnumeracion);
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

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
			$this->Page_Terminate("comprobantes2Dnumeracionlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("comprobantes2Dnumeracionlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "comprobantes2Dnumeracionlist.php")
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
		if (!$this->idComprobante->FldIsDetailKey) {
			$this->idComprobante->setFormValue($objForm->GetValue("x_idComprobante"));
		}
		if (!$this->puntoVenta->FldIsDetailKey) {
			$this->puntoVenta->setFormValue($objForm->GetValue("x_puntoVenta"));
		}
		if (!$this->ultimoNumero->FldIsDetailKey) {
			$this->ultimoNumero->setFormValue($objForm->GetValue("x_ultimoNumero"));
		}
		if (!$this->ultimoNumeroContable->FldIsDetailKey) {
			$this->ultimoNumeroContable->setFormValue($objForm->GetValue("x_ultimoNumeroContable"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idComprobante->CurrentValue = $this->idComprobante->FormValue;
		$this->puntoVenta->CurrentValue = $this->puntoVenta->FormValue;
		$this->ultimoNumero->CurrentValue = $this->ultimoNumero->FormValue;
		$this->ultimoNumeroContable->CurrentValue = $this->ultimoNumeroContable->FormValue;
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
		$this->idComprobante->setDbValue($rs->fields('idComprobante'));
		$this->puntoVenta->setDbValue($rs->fields('puntoVenta'));
		$this->ultimoNumero->setDbValue($rs->fields('ultimoNumero'));
		$this->ultimoNumeroContable->setDbValue($rs->fields('ultimoNumeroContable'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idComprobante->DbValue = $row['idComprobante'];
		$this->puntoVenta->DbValue = $row['puntoVenta'];
		$this->ultimoNumero->DbValue = $row['ultimoNumero'];
		$this->ultimoNumeroContable->DbValue = $row['ultimoNumeroContable'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idComprobante
		// puntoVenta
		// ultimoNumero
		// ultimoNumeroContable

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idComprobante
		$this->idComprobante->ViewValue = $this->idComprobante->CurrentValue;
		$this->idComprobante->ViewCustomAttributes = "";

		// puntoVenta
		$this->puntoVenta->ViewValue = $this->puntoVenta->CurrentValue;
		$this->puntoVenta->ViewCustomAttributes = "";

		// ultimoNumero
		$this->ultimoNumero->ViewValue = $this->ultimoNumero->CurrentValue;
		$this->ultimoNumero->ViewCustomAttributes = "";

		// ultimoNumeroContable
		$this->ultimoNumeroContable->ViewValue = $this->ultimoNumeroContable->CurrentValue;
		$this->ultimoNumeroContable->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idComprobante
			$this->idComprobante->LinkCustomAttributes = "";
			$this->idComprobante->HrefValue = "";
			$this->idComprobante->TooltipValue = "";

			// puntoVenta
			$this->puntoVenta->LinkCustomAttributes = "";
			$this->puntoVenta->HrefValue = "";
			$this->puntoVenta->TooltipValue = "";

			// ultimoNumero
			$this->ultimoNumero->LinkCustomAttributes = "";
			$this->ultimoNumero->HrefValue = "";
			$this->ultimoNumero->TooltipValue = "";

			// ultimoNumeroContable
			$this->ultimoNumeroContable->LinkCustomAttributes = "";
			$this->ultimoNumeroContable->HrefValue = "";
			$this->ultimoNumeroContable->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// idComprobante
			$this->idComprobante->EditAttrs["class"] = "form-control";
			$this->idComprobante->EditCustomAttributes = "";
			if ($this->idComprobante->getSessionValue() <> "") {
				$this->idComprobante->CurrentValue = $this->idComprobante->getSessionValue();
			$this->idComprobante->ViewValue = $this->idComprobante->CurrentValue;
			$this->idComprobante->ViewCustomAttributes = "";
			} else {
			$this->idComprobante->EditValue = ew_HtmlEncode($this->idComprobante->CurrentValue);
			$this->idComprobante->PlaceHolder = ew_RemoveHtml($this->idComprobante->FldCaption());
			}

			// puntoVenta
			$this->puntoVenta->EditAttrs["class"] = "form-control";
			$this->puntoVenta->EditCustomAttributes = "";
			$this->puntoVenta->EditValue = ew_HtmlEncode($this->puntoVenta->CurrentValue);
			$this->puntoVenta->PlaceHolder = ew_RemoveHtml($this->puntoVenta->FldCaption());

			// ultimoNumero
			$this->ultimoNumero->EditAttrs["class"] = "form-control";
			$this->ultimoNumero->EditCustomAttributes = "";
			$this->ultimoNumero->EditValue = ew_HtmlEncode($this->ultimoNumero->CurrentValue);
			$this->ultimoNumero->PlaceHolder = ew_RemoveHtml($this->ultimoNumero->FldCaption());

			// ultimoNumeroContable
			$this->ultimoNumeroContable->EditAttrs["class"] = "form-control";
			$this->ultimoNumeroContable->EditCustomAttributes = "";
			$this->ultimoNumeroContable->EditValue = ew_HtmlEncode($this->ultimoNumeroContable->CurrentValue);
			$this->ultimoNumeroContable->PlaceHolder = ew_RemoveHtml($this->ultimoNumeroContable->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// idComprobante
			$this->idComprobante->LinkCustomAttributes = "";
			$this->idComprobante->HrefValue = "";

			// puntoVenta
			$this->puntoVenta->LinkCustomAttributes = "";
			$this->puntoVenta->HrefValue = "";

			// ultimoNumero
			$this->ultimoNumero->LinkCustomAttributes = "";
			$this->ultimoNumero->HrefValue = "";

			// ultimoNumeroContable
			$this->ultimoNumeroContable->LinkCustomAttributes = "";
			$this->ultimoNumeroContable->HrefValue = "";
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
		if (!$this->idComprobante->FldIsDetailKey && !is_null($this->idComprobante->FormValue) && $this->idComprobante->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idComprobante->FldCaption(), $this->idComprobante->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->idComprobante->FormValue)) {
			ew_AddMessage($gsFormError, $this->idComprobante->FldErrMsg());
		}
		if (!$this->puntoVenta->FldIsDetailKey && !is_null($this->puntoVenta->FormValue) && $this->puntoVenta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->puntoVenta->FldCaption(), $this->puntoVenta->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->puntoVenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->puntoVenta->FldErrMsg());
		}
		if (!$this->ultimoNumero->FldIsDetailKey && !is_null($this->ultimoNumero->FormValue) && $this->ultimoNumero->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ultimoNumero->FldCaption(), $this->ultimoNumero->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ultimoNumero->FormValue)) {
			ew_AddMessage($gsFormError, $this->ultimoNumero->FldErrMsg());
		}
		if (!$this->ultimoNumeroContable->FldIsDetailKey && !is_null($this->ultimoNumeroContable->FormValue) && $this->ultimoNumeroContable->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ultimoNumeroContable->FldCaption(), $this->ultimoNumeroContable->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ultimoNumeroContable->FormValue)) {
			ew_AddMessage($gsFormError, $this->ultimoNumeroContable->FldErrMsg());
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

			// idComprobante
			$this->idComprobante->SetDbValueDef($rsnew, $this->idComprobante->CurrentValue, 0, $this->idComprobante->ReadOnly);

			// puntoVenta
			$this->puntoVenta->SetDbValueDef($rsnew, $this->puntoVenta->CurrentValue, 0, $this->puntoVenta->ReadOnly);

			// ultimoNumero
			$this->ultimoNumero->SetDbValueDef($rsnew, $this->ultimoNumero->CurrentValue, 0, $this->ultimoNumero->ReadOnly);

			// ultimoNumeroContable
			$this->ultimoNumeroContable->SetDbValueDef($rsnew, $this->ultimoNumeroContable->CurrentValue, 0, $this->ultimoNumeroContable->ReadOnly);

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "comprobantes") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["comprobantes"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->idComprobante->setQueryStringValue($GLOBALS["comprobantes"]->id->QueryStringValue);
					$this->idComprobante->setSessionValue($this->idComprobante->QueryStringValue);
					if (!is_numeric($GLOBALS["comprobantes"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "comprobantes") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["comprobantes"]->id->setFormValue($_POST["fk_id"]);
					$this->idComprobante->setFormValue($GLOBALS["comprobantes"]->id->FormValue);
					$this->idComprobante->setSessionValue($this->idComprobante->FormValue);
					if (!is_numeric($GLOBALS["comprobantes"]->id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "comprobantes") {
				if ($this->idComprobante->CurrentValue == "") $this->idComprobante->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("comprobantes2Dnumeracionlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($comprobantes2Dnumeracion_edit)) $comprobantes2Dnumeracion_edit = new ccomprobantes2Dnumeracion_edit();

// Page init
$comprobantes2Dnumeracion_edit->Page_Init();

// Page main
$comprobantes2Dnumeracion_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes2Dnumeracion_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcomprobantes2Dnumeracionedit = new ew_Form("fcomprobantes2Dnumeracionedit", "edit");

// Validate form
fcomprobantes2Dnumeracionedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idComprobante");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->idComprobante->FldCaption(), $comprobantes2Dnumeracion->idComprobante->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idComprobante");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->idComprobante->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_puntoVenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->puntoVenta->FldCaption(), $comprobantes2Dnumeracion->puntoVenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_puntoVenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->puntoVenta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->ultimoNumero->FldCaption(), $comprobantes2Dnumeracion->ultimoNumero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->ultimoNumero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumeroContable");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->ultimoNumeroContable->FldCaption(), $comprobantes2Dnumeracion->ultimoNumeroContable->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumeroContable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->ultimoNumeroContable->FldErrMsg()) ?>");

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
fcomprobantes2Dnumeracionedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantes2Dnumeracionedit.ValidateRequired = true;
<?php } else { ?>
fcomprobantes2Dnumeracionedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$comprobantes2Dnumeracion_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $comprobantes2Dnumeracion_edit->ShowPageHeader(); ?>
<?php
$comprobantes2Dnumeracion_edit->ShowMessage();
?>
<form name="fcomprobantes2Dnumeracionedit" id="fcomprobantes2Dnumeracionedit" class="<?php echo $comprobantes2Dnumeracion_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($comprobantes2Dnumeracion_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $comprobantes2Dnumeracion_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="comprobantes2Dnumeracion">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($comprobantes2Dnumeracion_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($comprobantes2Dnumeracion->getCurrentMasterTable() == "comprobantes") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="comprobantes">
<input type="hidden" name="fk_id" value="<?php echo $comprobantes2Dnumeracion->idComprobante->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($comprobantes2Dnumeracion->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_comprobantes2Dnumeracion_id" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes2Dnumeracion->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes2Dnumeracion->id->CellAttributes() ?>>
<span id="el_comprobantes2Dnumeracion_id">
<span<?php echo $comprobantes2Dnumeracion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dnumeracion->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->id->CurrentValue) ?>">
<?php echo $comprobantes2Dnumeracion->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->idComprobante->Visible) { // idComprobante ?>
	<div id="r_idComprobante" class="form-group">
		<label id="elh_comprobantes2Dnumeracion_idComprobante" for="x_idComprobante" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes2Dnumeracion->idComprobante->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes2Dnumeracion->idComprobante->CellAttributes() ?>>
<?php if ($comprobantes2Dnumeracion->idComprobante->getSessionValue() <> "") { ?>
<span id="el_comprobantes2Dnumeracion_idComprobante">
<span<?php echo $comprobantes2Dnumeracion->idComprobante->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dnumeracion->idComprobante->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idComprobante" name="x_idComprobante" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->idComprobante->CurrentValue) ?>">
<?php } else { ?>
<span id="el_comprobantes2Dnumeracion_idComprobante">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_idComprobante" name="x_idComprobante" id="x_idComprobante" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->idComprobante->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->idComprobante->EditValue ?>"<?php echo $comprobantes2Dnumeracion->idComprobante->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $comprobantes2Dnumeracion->idComprobante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->puntoVenta->Visible) { // puntoVenta ?>
	<div id="r_puntoVenta" class="form-group">
		<label id="elh_comprobantes2Dnumeracion_puntoVenta" for="x_puntoVenta" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes2Dnumeracion->puntoVenta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes2Dnumeracion->puntoVenta->CellAttributes() ?>>
<span id="el_comprobantes2Dnumeracion_puntoVenta">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x_puntoVenta" id="x_puntoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->puntoVenta->EditValue ?>"<?php echo $comprobantes2Dnumeracion->puntoVenta->EditAttributes() ?>>
</span>
<?php echo $comprobantes2Dnumeracion->puntoVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->ultimoNumero->Visible) { // ultimoNumero ?>
	<div id="r_ultimoNumero" class="form-group">
		<label id="elh_comprobantes2Dnumeracion_ultimoNumero" for="x_ultimoNumero" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes2Dnumeracion->ultimoNumero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes2Dnumeracion->ultimoNumero->CellAttributes() ?>>
<span id="el_comprobantes2Dnumeracion_ultimoNumero">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x_ultimoNumero" id="x_ultimoNumero" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditAttributes() ?>>
</span>
<?php echo $comprobantes2Dnumeracion->ultimoNumero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->ultimoNumeroContable->Visible) { // ultimoNumeroContable ?>
	<div id="r_ultimoNumeroContable" class="form-group">
		<label id="elh_comprobantes2Dnumeracion_ultimoNumeroContable" for="x_ultimoNumeroContable" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->CellAttributes() ?>>
<span id="el_comprobantes2Dnumeracion_ultimoNumeroContable">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x_ultimoNumeroContable" id="x_ultimoNumeroContable" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditAttributes() ?>>
</span>
<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$comprobantes2Dnumeracion_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $comprobantes2Dnumeracion_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcomprobantes2Dnumeracionedit.Init();
</script>
<?php
$comprobantes2Dnumeracion_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$comprobantes2Dnumeracion_edit->Page_Terminate();
?>
