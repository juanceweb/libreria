<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "cotizaciones_detallesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cotizaciones_detalles_edit = NULL; // Initialize page object first

class ccotizaciones_detalles_edit extends ccotizaciones_detalles {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'cotizaciones_detalles';

	// Page object name
	var $PageObjName = 'cotizaciones_detalles_edit';

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

		// Table object (cotizaciones_detalles)
		if (!isset($GLOBALS["cotizaciones_detalles"]) || get_class($GLOBALS["cotizaciones_detalles"]) == "ccotizaciones_detalles") {
			$GLOBALS["cotizaciones_detalles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cotizaciones_detalles"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cotizaciones_detalles', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cotizaciones_detalleslist.php"));
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
		$this->idCotizacion->SetVisibility();
		$this->idArticulo->SetVisibility();
		$this->cantidad->SetVisibility();
		$this->referencia->SetVisibility();
		$this->precioReferencia->SetVisibility();
		$this->idAlicuota->SetVisibility();

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
		global $EW_EXPORT, $cotizaciones_detalles;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cotizaciones_detalles);
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
			$this->Page_Terminate("cotizaciones_detalleslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("cotizaciones_detalleslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cotizaciones_detalleslist.php")
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
		if (!$this->idCotizacion->FldIsDetailKey) {
			$this->idCotizacion->setFormValue($objForm->GetValue("x_idCotizacion"));
		}
		if (!$this->idArticulo->FldIsDetailKey) {
			$this->idArticulo->setFormValue($objForm->GetValue("x_idArticulo"));
		}
		if (!$this->cantidad->FldIsDetailKey) {
			$this->cantidad->setFormValue($objForm->GetValue("x_cantidad"));
		}
		if (!$this->referencia->FldIsDetailKey) {
			$this->referencia->setFormValue($objForm->GetValue("x_referencia"));
		}
		if (!$this->precioReferencia->FldIsDetailKey) {
			$this->precioReferencia->setFormValue($objForm->GetValue("x_precioReferencia"));
		}
		if (!$this->idAlicuota->FldIsDetailKey) {
			$this->idAlicuota->setFormValue($objForm->GetValue("x_idAlicuota"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idCotizacion->CurrentValue = $this->idCotizacion->FormValue;
		$this->idArticulo->CurrentValue = $this->idArticulo->FormValue;
		$this->cantidad->CurrentValue = $this->cantidad->FormValue;
		$this->referencia->CurrentValue = $this->referencia->FormValue;
		$this->precioReferencia->CurrentValue = $this->precioReferencia->FormValue;
		$this->idAlicuota->CurrentValue = $this->idAlicuota->FormValue;
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
		$this->idCotizacion->setDbValue($rs->fields('idCotizacion'));
		$this->idArticulo->setDbValue($rs->fields('idArticulo'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->referencia->setDbValue($rs->fields('referencia'));
		$this->precioReferencia->setDbValue($rs->fields('precioReferencia'));
		$this->idAlicuota->setDbValue($rs->fields('idAlicuota'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idCotizacion->DbValue = $row['idCotizacion'];
		$this->idArticulo->DbValue = $row['idArticulo'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->referencia->DbValue = $row['referencia'];
		$this->precioReferencia->DbValue = $row['precioReferencia'];
		$this->idAlicuota->DbValue = $row['idAlicuota'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idCotizacion
		// idArticulo
		// cantidad
		// referencia
		// precioReferencia
		// idAlicuota

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idCotizacion
		$this->idCotizacion->ViewValue = $this->idCotizacion->CurrentValue;
		$this->idCotizacion->ViewCustomAttributes = "";

		// idArticulo
		$this->idArticulo->ViewValue = $this->idArticulo->CurrentValue;
		$this->idArticulo->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// referencia
		$this->referencia->ViewValue = $this->referencia->CurrentValue;
		$this->referencia->ViewCustomAttributes = "";

		// precioReferencia
		$this->precioReferencia->ViewValue = $this->precioReferencia->CurrentValue;
		$this->precioReferencia->ViewCustomAttributes = "";

		// idAlicuota
		$this->idAlicuota->ViewValue = $this->idAlicuota->CurrentValue;
		$this->idAlicuota->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idCotizacion
			$this->idCotizacion->LinkCustomAttributes = "";
			$this->idCotizacion->HrefValue = "";
			$this->idCotizacion->TooltipValue = "";

			// idArticulo
			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";
			$this->idArticulo->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// referencia
			$this->referencia->LinkCustomAttributes = "";
			$this->referencia->HrefValue = "";
			$this->referencia->TooltipValue = "";

			// precioReferencia
			$this->precioReferencia->LinkCustomAttributes = "";
			$this->precioReferencia->HrefValue = "";
			$this->precioReferencia->TooltipValue = "";

			// idAlicuota
			$this->idAlicuota->LinkCustomAttributes = "";
			$this->idAlicuota->HrefValue = "";
			$this->idAlicuota->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// idCotizacion
			$this->idCotizacion->EditAttrs["class"] = "form-control";
			$this->idCotizacion->EditCustomAttributes = "";
			$this->idCotizacion->EditValue = ew_HtmlEncode($this->idCotizacion->CurrentValue);
			$this->idCotizacion->PlaceHolder = ew_RemoveHtml($this->idCotizacion->FldCaption());

			// idArticulo
			$this->idArticulo->EditAttrs["class"] = "form-control";
			$this->idArticulo->EditCustomAttributes = "";
			$this->idArticulo->EditValue = ew_HtmlEncode($this->idArticulo->CurrentValue);
			$this->idArticulo->PlaceHolder = ew_RemoveHtml($this->idArticulo->FldCaption());

			// cantidad
			$this->cantidad->EditAttrs["class"] = "form-control";
			$this->cantidad->EditCustomAttributes = "";
			$this->cantidad->EditValue = ew_HtmlEncode($this->cantidad->CurrentValue);
			$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

			// referencia
			$this->referencia->EditAttrs["class"] = "form-control";
			$this->referencia->EditCustomAttributes = "";
			$this->referencia->EditValue = ew_HtmlEncode($this->referencia->CurrentValue);
			$this->referencia->PlaceHolder = ew_RemoveHtml($this->referencia->FldCaption());

			// precioReferencia
			$this->precioReferencia->EditAttrs["class"] = "form-control";
			$this->precioReferencia->EditCustomAttributes = "";
			$this->precioReferencia->EditValue = ew_HtmlEncode($this->precioReferencia->CurrentValue);
			$this->precioReferencia->PlaceHolder = ew_RemoveHtml($this->precioReferencia->FldCaption());

			// idAlicuota
			$this->idAlicuota->EditAttrs["class"] = "form-control";
			$this->idAlicuota->EditCustomAttributes = "";
			$this->idAlicuota->EditValue = ew_HtmlEncode($this->idAlicuota->CurrentValue);
			$this->idAlicuota->PlaceHolder = ew_RemoveHtml($this->idAlicuota->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// idCotizacion
			$this->idCotizacion->LinkCustomAttributes = "";
			$this->idCotizacion->HrefValue = "";

			// idArticulo
			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";

			// referencia
			$this->referencia->LinkCustomAttributes = "";
			$this->referencia->HrefValue = "";

			// precioReferencia
			$this->precioReferencia->LinkCustomAttributes = "";
			$this->precioReferencia->HrefValue = "";

			// idAlicuota
			$this->idAlicuota->LinkCustomAttributes = "";
			$this->idAlicuota->HrefValue = "";
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
		if (!ew_CheckInteger($this->idCotizacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->idCotizacion->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idArticulo->FormValue)) {
			ew_AddMessage($gsFormError, $this->idArticulo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->cantidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idAlicuota->FormValue)) {
			ew_AddMessage($gsFormError, $this->idAlicuota->FldErrMsg());
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

			// idCotizacion
			$this->idCotizacion->SetDbValueDef($rsnew, $this->idCotizacion->CurrentValue, NULL, $this->idCotizacion->ReadOnly);

			// idArticulo
			$this->idArticulo->SetDbValueDef($rsnew, $this->idArticulo->CurrentValue, NULL, $this->idArticulo->ReadOnly);

			// cantidad
			$this->cantidad->SetDbValueDef($rsnew, $this->cantidad->CurrentValue, NULL, $this->cantidad->ReadOnly);

			// referencia
			$this->referencia->SetDbValueDef($rsnew, $this->referencia->CurrentValue, NULL, $this->referencia->ReadOnly);

			// precioReferencia
			$this->precioReferencia->SetDbValueDef($rsnew, $this->precioReferencia->CurrentValue, NULL, $this->precioReferencia->ReadOnly);

			// idAlicuota
			$this->idAlicuota->SetDbValueDef($rsnew, $this->idAlicuota->CurrentValue, NULL, $this->idAlicuota->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cotizaciones_detalleslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cotizaciones_detalles_edit)) $cotizaciones_detalles_edit = new ccotizaciones_detalles_edit();

// Page init
$cotizaciones_detalles_edit->Page_Init();

// Page main
$cotizaciones_detalles_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cotizaciones_detalles_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcotizaciones_detallesedit = new ew_Form("fcotizaciones_detallesedit", "edit");

// Validate form
fcotizaciones_detallesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idCotizacion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones_detalles->idCotizacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idArticulo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones_detalles->idArticulo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones_detalles->cantidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idAlicuota");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones_detalles->idAlicuota->FldErrMsg()) ?>");

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
fcotizaciones_detallesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcotizaciones_detallesedit.ValidateRequired = true;
<?php } else { ?>
fcotizaciones_detallesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cotizaciones_detalles_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cotizaciones_detalles_edit->ShowPageHeader(); ?>
<?php
$cotizaciones_detalles_edit->ShowMessage();
?>
<form name="fcotizaciones_detallesedit" id="fcotizaciones_detallesedit" class="<?php echo $cotizaciones_detalles_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cotizaciones_detalles_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cotizaciones_detalles_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cotizaciones_detalles">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($cotizaciones_detalles_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cotizaciones_detalles->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_cotizaciones_detalles_id" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->id->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_id">
<span<?php echo $cotizaciones_detalles->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cotizaciones_detalles->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="cotizaciones_detalles" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($cotizaciones_detalles->id->CurrentValue) ?>">
<?php echo $cotizaciones_detalles->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->idCotizacion->Visible) { // idCotizacion ?>
	<div id="r_idCotizacion" class="form-group">
		<label id="elh_cotizaciones_detalles_idCotizacion" for="x_idCotizacion" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->idCotizacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->idCotizacion->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_idCotizacion">
<input type="text" data-table="cotizaciones_detalles" data-field="x_idCotizacion" name="x_idCotizacion" id="x_idCotizacion" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->idCotizacion->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->idCotizacion->EditValue ?>"<?php echo $cotizaciones_detalles->idCotizacion->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->idCotizacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->idArticulo->Visible) { // idArticulo ?>
	<div id="r_idArticulo" class="form-group">
		<label id="elh_cotizaciones_detalles_idArticulo" for="x_idArticulo" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->idArticulo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->idArticulo->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_idArticulo">
<input type="text" data-table="cotizaciones_detalles" data-field="x_idArticulo" name="x_idArticulo" id="x_idArticulo" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->idArticulo->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->idArticulo->EditValue ?>"<?php echo $cotizaciones_detalles->idArticulo->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->idArticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->cantidad->Visible) { // cantidad ?>
	<div id="r_cantidad" class="form-group">
		<label id="elh_cotizaciones_detalles_cantidad" for="x_cantidad" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->cantidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->cantidad->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_cantidad">
<input type="text" data-table="cotizaciones_detalles" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->cantidad->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->cantidad->EditValue ?>"<?php echo $cotizaciones_detalles->cantidad->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->cantidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->referencia->Visible) { // referencia ?>
	<div id="r_referencia" class="form-group">
		<label id="elh_cotizaciones_detalles_referencia" for="x_referencia" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->referencia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->referencia->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_referencia">
<input type="text" data-table="cotizaciones_detalles" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->referencia->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->referencia->EditValue ?>"<?php echo $cotizaciones_detalles->referencia->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->referencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->precioReferencia->Visible) { // precioReferencia ?>
	<div id="r_precioReferencia" class="form-group">
		<label id="elh_cotizaciones_detalles_precioReferencia" for="x_precioReferencia" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->precioReferencia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->precioReferencia->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_precioReferencia">
<input type="text" data-table="cotizaciones_detalles" data-field="x_precioReferencia" name="x_precioReferencia" id="x_precioReferencia" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->precioReferencia->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->precioReferencia->EditValue ?>"<?php echo $cotizaciones_detalles->precioReferencia->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->precioReferencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones_detalles->idAlicuota->Visible) { // idAlicuota ?>
	<div id="r_idAlicuota" class="form-group">
		<label id="elh_cotizaciones_detalles_idAlicuota" for="x_idAlicuota" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones_detalles->idAlicuota->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones_detalles->idAlicuota->CellAttributes() ?>>
<span id="el_cotizaciones_detalles_idAlicuota">
<input type="text" data-table="cotizaciones_detalles" data-field="x_idAlicuota" name="x_idAlicuota" id="x_idAlicuota" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones_detalles->idAlicuota->getPlaceHolder()) ?>" value="<?php echo $cotizaciones_detalles->idAlicuota->EditValue ?>"<?php echo $cotizaciones_detalles->idAlicuota->EditAttributes() ?>>
</span>
<?php echo $cotizaciones_detalles->idAlicuota->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$cotizaciones_detalles_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cotizaciones_detalles_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcotizaciones_detallesedit.Init();
</script>
<?php
$cotizaciones_detalles_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cotizaciones_detalles_edit->Page_Terminate();
?>
