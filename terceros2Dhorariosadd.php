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

$terceros2Dhorarios_add = NULL; // Initialize page object first

class cterceros2Dhorarios_add extends cterceros2Dhorarios {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros-horarios';

	// Page object name
	var $PageObjName = 'terceros2Dhorarios_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("terceros2Dhorarioslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "terceros2Dhorarioslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "terceros2Dhorariosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->idTercero->CurrentValue = NULL;
		$this->idTercero->OldValue = $this->idTercero->CurrentValue;
		$this->dia->CurrentValue = NULL;
		$this->dia->OldValue = $this->dia->CurrentValue;
		$this->horaDesde->CurrentValue = NULL;
		$this->horaDesde->OldValue = $this->horaDesde->CurrentValue;
		$this->horaHasta->CurrentValue = NULL;
		$this->horaHasta->OldValue = $this->horaHasta->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		$this->LoadOldRecord();
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idTercero
		$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, FALSE);

		// dia
		$this->dia->SetDbValueDef($rsnew, $this->dia->CurrentValue, NULL, FALSE);

		// horaDesde
		$this->horaDesde->SetDbValueDef($rsnew, $this->horaDesde->CurrentValue, NULL, FALSE);

		// horaHasta
		$this->horaHasta->SetDbValueDef($rsnew, $this->horaHasta->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("terceros2Dhorarioslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($terceros2Dhorarios_add)) $terceros2Dhorarios_add = new cterceros2Dhorarios_add();

// Page init
$terceros2Dhorarios_add->Page_Init();

// Page main
$terceros2Dhorarios_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros2Dhorarios_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fterceros2Dhorariosadd = new ew_Form("fterceros2Dhorariosadd", "add");

// Validate form
fterceros2Dhorariosadd.Validate = function() {
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
fterceros2Dhorariosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dhorariosadd.ValidateRequired = true;
<?php } else { ?>
fterceros2Dhorariosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$terceros2Dhorarios_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $terceros2Dhorarios_add->ShowPageHeader(); ?>
<?php
$terceros2Dhorarios_add->ShowMessage();
?>
<form name="fterceros2Dhorariosadd" id="fterceros2Dhorariosadd" class="<?php echo $terceros2Dhorarios_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros2Dhorarios_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros2Dhorarios_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros2Dhorarios">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($terceros2Dhorarios_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
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
<?php if (!$terceros2Dhorarios_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $terceros2Dhorarios_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fterceros2Dhorariosadd.Init();
</script>
<?php
$terceros2Dhorarios_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$terceros2Dhorarios_add->Page_Terminate();
?>
