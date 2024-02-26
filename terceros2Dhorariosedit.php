<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "terceros2Dhorariosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$terceros2Dhorarios_edit = NULL; // Initialize page object first

class cterceros2Dhorarios_edit extends cterceros2Dhorarios {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros-horarios';

	// Page object name
	var $PageObjName = 'terceros2Dhorarios_edit';

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

		// Table object (terceros2Dhorarios)
		if (!isset($GLOBALS["terceros2Dhorarios"]) || get_class($GLOBALS["terceros2Dhorarios"]) == "cterceros2Dhorarios") {
			$GLOBALS["terceros2Dhorarios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["terceros2Dhorarios"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'terceros-horarios', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("terceros2Dhorarioslist.php"));
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
		$this->idTercero->SetVisibility();
		$this->dia->SetVisibility();
		$this->horaDesde->SetVisibility();
		$this->horaHasta->SetVisibility();

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
		global $EW_EXPORT, $terceros2Dhorarios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($terceros2Dhorarios);
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
			$this->Page_Terminate("terceros2Dhorarioslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("terceros2Dhorarioslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "terceros2Dhorarioslist.php")
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
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->dia->FldIsDetailKey) {
			$this->dia->setFormValue($objForm->GetValue("x_dia"));
		}
		if (!$this->horaDesde->FldIsDetailKey) {
			$this->horaDesde->setFormValue($objForm->GetValue("x_horaDesde"));
			$this->horaDesde->CurrentValue = ew_UnFormatDateTime($this->horaDesde->CurrentValue, 0);
		}
		if (!$this->horaHasta->FldIsDetailKey) {
			$this->horaHasta->setFormValue($objForm->GetValue("x_horaHasta"));
			$this->horaHasta->CurrentValue = ew_UnFormatDateTime($this->horaHasta->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->dia->CurrentValue = $this->dia->FormValue;
		$this->horaDesde->CurrentValue = $this->horaDesde->FormValue;
		$this->horaDesde->CurrentValue = ew_UnFormatDateTime($this->horaDesde->CurrentValue, 0);
		$this->horaHasta->CurrentValue = $this->horaHasta->FormValue;
		$this->horaHasta->CurrentValue = ew_UnFormatDateTime($this->horaHasta->CurrentValue, 0);
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
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->dia->setDbValue($rs->fields('dia'));
		$this->horaDesde->setDbValue($rs->fields('horaDesde'));
		$this->horaHasta->setDbValue($rs->fields('horaHasta'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->dia->DbValue = $row['dia'];
		$this->horaDesde->DbValue = $row['horaDesde'];
		$this->horaHasta->DbValue = $row['horaHasta'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idTercero
		// dia
		// horaDesde
		// horaHasta

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idTercero
		$this->idTercero->ViewValue = $this->idTercero->CurrentValue;
		$this->idTercero->ViewCustomAttributes = "";

		// dia
		$this->dia->ViewValue = $this->dia->CurrentValue;
		$this->dia->ViewCustomAttributes = "";

		// horaDesde
		$this->horaDesde->ViewValue = $this->horaDesde->CurrentValue;
		$this->horaDesde->ViewCustomAttributes = "";

		// horaHasta
		$this->horaHasta->ViewValue = $this->horaHasta->CurrentValue;
		$this->horaHasta->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// dia
			$this->dia->LinkCustomAttributes = "";
			$this->dia->HrefValue = "";
			$this->dia->TooltipValue = "";

			// horaDesde
			$this->horaDesde->LinkCustomAttributes = "";
			$this->horaDesde->HrefValue = "";
			$this->horaDesde->TooltipValue = "";

			// horaHasta
			$this->horaHasta->LinkCustomAttributes = "";
			$this->horaHasta->HrefValue = "";
			$this->horaHasta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = "";
			$this->idTercero->EditValue = ew_HtmlEncode($this->idTercero->CurrentValue);
			$this->idTercero->PlaceHolder = ew_RemoveHtml($this->idTercero->FldCaption());

			// dia
			$this->dia->EditAttrs["class"] = "form-control";
			$this->dia->EditCustomAttributes = "";
			$this->dia->EditValue = ew_HtmlEncode($this->dia->CurrentValue);
			$this->dia->PlaceHolder = ew_RemoveHtml($this->dia->FldCaption());

			// horaDesde
			$this->horaDesde->EditAttrs["class"] = "form-control";
			$this->horaDesde->EditCustomAttributes = "";
			$this->horaDesde->EditValue = ew_HtmlEncode($this->horaDesde->CurrentValue);
			$this->horaDesde->PlaceHolder = ew_RemoveHtml($this->horaDesde->FldCaption());

			// horaHasta
			$this->horaHasta->EditAttrs["class"] = "form-control";
			$this->horaHasta->EditCustomAttributes = "";
			$this->horaHasta->EditValue = ew_HtmlEncode($this->horaHasta->CurrentValue);
			$this->horaHasta->PlaceHolder = ew_RemoveHtml($this->horaHasta->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// dia
			$this->dia->LinkCustomAttributes = "";
			$this->dia->HrefValue = "";

			// horaDesde
			$this->horaDesde->LinkCustomAttributes = "";
			$this->horaDesde->HrefValue = "";

			// horaHasta
			$this->horaHasta->LinkCustomAttributes = "";
			$this->horaHasta->HrefValue = "";
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
		if (!ew_CheckInteger($this->idTercero->FormValue)) {
			ew_AddMessage($gsFormError, $this->idTercero->FldErrMsg());
		}
		if (!ew_CheckTime($this->horaDesde->FormValue)) {
			ew_AddMessage($gsFormError, $this->horaDesde->FldErrMsg());
		}
		if (!ew_CheckTime($this->horaHasta->FormValue)) {
			ew_AddMessage($gsFormError, $this->horaHasta->FldErrMsg());
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

			// idTercero
			$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, $this->idTercero->ReadOnly);

			// dia
			$this->dia->SetDbValueDef($rsnew, $this->dia->CurrentValue, NULL, $this->dia->ReadOnly);

			// horaDesde
			$this->horaDesde->SetDbValueDef($rsnew, $this->horaDesde->CurrentValue, NULL, $this->horaDesde->ReadOnly);

			// horaHasta
			$this->horaHasta->SetDbValueDef($rsnew, $this->horaHasta->CurrentValue, NULL, $this->horaHasta->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("terceros2Dhorarioslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($terceros2Dhorarios_edit)) $terceros2Dhorarios_edit = new cterceros2Dhorarios_edit();

// Page init
$terceros2Dhorarios_edit->Page_Init();

// Page main
$terceros2Dhorarios_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros2Dhorarios_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fterceros2Dhorariosedit = new ew_Form("fterceros2Dhorariosedit", "edit");

// Validate form
fterceros2Dhorariosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idTercero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros2Dhorarios->idTercero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horaDesde");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros2Dhorarios->horaDesde->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horaHasta");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros2Dhorarios->horaHasta->FldErrMsg()) ?>");

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
fterceros2Dhorariosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dhorariosedit.ValidateRequired = true;
<?php } else { ?>
fterceros2Dhorariosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$terceros2Dhorarios_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $terceros2Dhorarios_edit->ShowPageHeader(); ?>
<?php
$terceros2Dhorarios_edit->ShowMessage();
?>
<form name="fterceros2Dhorariosedit" id="fterceros2Dhorariosedit" class="<?php echo $terceros2Dhorarios_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros2Dhorarios_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros2Dhorarios_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros2Dhorarios">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($terceros2Dhorarios_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($terceros2Dhorarios->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_terceros2Dhorarios_id" class="col-sm-2 control-label ewLabel"><?php echo $terceros2Dhorarios->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros2Dhorarios->id->CellAttributes() ?>>
<span id="el_terceros2Dhorarios_id">
<span<?php echo $terceros2Dhorarios->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dhorarios->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dhorarios" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($terceros2Dhorarios->id->CurrentValue) ?>">
<?php echo $terceros2Dhorarios->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros2Dhorarios->idTercero->Visible) { // idTercero ?>
	<div id="r_idTercero" class="form-group">
		<label id="elh_terceros2Dhorarios_idTercero" for="x_idTercero" class="col-sm-2 control-label ewLabel"><?php echo $terceros2Dhorarios->idTercero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros2Dhorarios->idTercero->CellAttributes() ?>>
<span id="el_terceros2Dhorarios_idTercero">
<input type="text" data-table="terceros2Dhorarios" data-field="x_idTercero" name="x_idTercero" id="x_idTercero" size="30" placeholder="<?php echo ew_HtmlEncode($terceros2Dhorarios->idTercero->getPlaceHolder()) ?>" value="<?php echo $terceros2Dhorarios->idTercero->EditValue ?>"<?php echo $terceros2Dhorarios->idTercero->EditAttributes() ?>>
</span>
<?php echo $terceros2Dhorarios->idTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros2Dhorarios->dia->Visible) { // dia ?>
	<div id="r_dia" class="form-group">
		<label id="elh_terceros2Dhorarios_dia" for="x_dia" class="col-sm-2 control-label ewLabel"><?php echo $terceros2Dhorarios->dia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros2Dhorarios->dia->CellAttributes() ?>>
<span id="el_terceros2Dhorarios_dia">
<input type="text" data-table="terceros2Dhorarios" data-field="x_dia" name="x_dia" id="x_dia" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros2Dhorarios->dia->getPlaceHolder()) ?>" value="<?php echo $terceros2Dhorarios->dia->EditValue ?>"<?php echo $terceros2Dhorarios->dia->EditAttributes() ?>>
</span>
<?php echo $terceros2Dhorarios->dia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros2Dhorarios->horaDesde->Visible) { // horaDesde ?>
	<div id="r_horaDesde" class="form-group">
		<label id="elh_terceros2Dhorarios_horaDesde" for="x_horaDesde" class="col-sm-2 control-label ewLabel"><?php echo $terceros2Dhorarios->horaDesde->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros2Dhorarios->horaDesde->CellAttributes() ?>>
<span id="el_terceros2Dhorarios_horaDesde">
<input type="text" data-table="terceros2Dhorarios" data-field="x_horaDesde" name="x_horaDesde" id="x_horaDesde" size="30" placeholder="<?php echo ew_HtmlEncode($terceros2Dhorarios->horaDesde->getPlaceHolder()) ?>" value="<?php echo $terceros2Dhorarios->horaDesde->EditValue ?>"<?php echo $terceros2Dhorarios->horaDesde->EditAttributes() ?>>
</span>
<?php echo $terceros2Dhorarios->horaDesde->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros2Dhorarios->horaHasta->Visible) { // horaHasta ?>
	<div id="r_horaHasta" class="form-group">
		<label id="elh_terceros2Dhorarios_horaHasta" for="x_horaHasta" class="col-sm-2 control-label ewLabel"><?php echo $terceros2Dhorarios->horaHasta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros2Dhorarios->horaHasta->CellAttributes() ?>>
<span id="el_terceros2Dhorarios_horaHasta">
<input type="text" data-table="terceros2Dhorarios" data-field="x_horaHasta" name="x_horaHasta" id="x_horaHasta" size="30" placeholder="<?php echo ew_HtmlEncode($terceros2Dhorarios->horaHasta->getPlaceHolder()) ?>" value="<?php echo $terceros2Dhorarios->horaHasta->EditValue ?>"<?php echo $terceros2Dhorarios->horaHasta->EditAttributes() ?>>
</span>
<?php echo $terceros2Dhorarios->horaHasta->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$terceros2Dhorarios_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $terceros2Dhorarios_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fterceros2Dhorariosedit.Init();
</script>
<?php
$terceros2Dhorarios_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$terceros2Dhorarios_edit->Page_Terminate();
?>
