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

$movimientos2Ddetalle_view = NULL; // Initialize page object first

class cmovimientos2Ddetalle_view extends cmovimientos2Ddetalle {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos-detalle';

	// Page object name
	var $PageObjName = 'movimientos2Ddetalle_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$sReturnUrl = "movimientos2Ddetallelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "movimientos2Ddetallelist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "movimientos2Ddetallelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_movimientos2Ddetalle\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_movimientos2Ddetalle',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmovimientos2Ddetalleview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientos2Ddetallelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($movimientos2Ddetalle_view)) $movimientos2Ddetalle_view = new cmovimientos2Ddetalle_view();

// Page init
$movimientos2Ddetalle_view->Page_Init();

// Page main
$movimientos2Ddetalle_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos2Ddetalle_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fmovimientos2Ddetalleview = new ew_Form("fmovimientos2Ddetalleview", "view");

// Form_CustomValidate event
fmovimientos2Ddetalleview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientos2Ddetalleview.ValidateRequired = true;
<?php } else { ?>
fmovimientos2Ddetalleview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$movimientos2Ddetalle_view->IsModal) { ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $movimientos2Ddetalle_view->ExportOptions->Render("body") ?>
<?php
	foreach ($movimientos2Ddetalle_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$movimientos2Ddetalle_view->IsModal) { ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $movimientos2Ddetalle_view->ShowPageHeader(); ?>
<?php
$movimientos2Ddetalle_view->ShowMessage();
?>
<form name="fmovimientos2Ddetalleview" id="fmovimientos2Ddetalleview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos2Ddetalle_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos2Ddetalle_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos2Ddetalle">
<?php if ($movimientos2Ddetalle_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_movimientos2Ddetalle_id"><?php echo $movimientos2Ddetalle->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $movimientos2Ddetalle->id->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_id">
<span<?php echo $movimientos2Ddetalle->id->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
	<tr id="r_idMovimientos">
		<td><span id="elh_movimientos2Ddetalle_idMovimientos"><?php echo $movimientos2Ddetalle->idMovimientos->FldCaption() ?></span></td>
		<td data-name="idMovimientos"<?php echo $movimientos2Ddetalle->idMovimientos->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_idMovimientos">
<span<?php echo $movimientos2Ddetalle->idMovimientos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idMovimientos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
	<tr id="r_cant">
		<td><span id="elh_movimientos2Ddetalle_cant"><?php echo $movimientos2Ddetalle->cant->FldCaption() ?></span></td>
		<td data-name="cant"<?php echo $movimientos2Ddetalle->cant->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_cant">
<span<?php echo $movimientos2Ddetalle->cant->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->cant->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<tr id="r_idUnidadMedida">
		<td><span id="elh_movimientos2Ddetalle_idUnidadMedida"><?php echo $movimientos2Ddetalle->idUnidadMedida->FldCaption() ?></span></td>
		<td data-name="idUnidadMedida"<?php echo $movimientos2Ddetalle->idUnidadMedida->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_idUnidadMedida">
<span<?php echo $movimientos2Ddetalle->idUnidadMedida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idUnidadMedida->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
	<tr id="r_codProducto">
		<td><span id="elh_movimientos2Ddetalle_codProducto"><?php echo $movimientos2Ddetalle->codProducto->FldCaption() ?></span></td>
		<td data-name="codProducto"<?php echo $movimientos2Ddetalle->codProducto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_codProducto">
<span<?php echo $movimientos2Ddetalle->codProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->codProducto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
	<tr id="r_medida">
		<td><span id="elh_movimientos2Ddetalle_medida"><?php echo $movimientos2Ddetalle->medida->FldCaption() ?></span></td>
		<td data-name="medida"<?php echo $movimientos2Ddetalle->medida->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_medida">
<span<?php echo $movimientos2Ddetalle->medida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->medida->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
	<tr id="r_nombreProducto">
		<td><span id="elh_movimientos2Ddetalle_nombreProducto"><?php echo $movimientos2Ddetalle->nombreProducto->FldCaption() ?></span></td>
		<td data-name="nombreProducto"<?php echo $movimientos2Ddetalle->nombreProducto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_nombreProducto">
<span<?php echo $movimientos2Ddetalle->nombreProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->nombreProducto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
	<tr id="r_importeUnitario">
		<td><span id="elh_movimientos2Ddetalle_importeUnitario"><?php echo $movimientos2Ddetalle->importeUnitario->FldCaption() ?></span></td>
		<td data-name="importeUnitario"<?php echo $movimientos2Ddetalle->importeUnitario->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeUnitario">
<span<?php echo $movimientos2Ddetalle->importeUnitario->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeUnitario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
	<tr id="r_bonificacion">
		<td><span id="elh_movimientos2Ddetalle_bonificacion"><?php echo $movimientos2Ddetalle->bonificacion->FldCaption() ?></span></td>
		<td data-name="bonificacion"<?php echo $movimientos2Ddetalle->bonificacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_bonificacion">
<span<?php echo $movimientos2Ddetalle->bonificacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->bonificacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
	<tr id="r_importeTotal">
		<td><span id="elh_movimientos2Ddetalle_importeTotal"><?php echo $movimientos2Ddetalle->importeTotal->FldCaption() ?></span></td>
		<td data-name="importeTotal"<?php echo $movimientos2Ddetalle->importeTotal->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeTotal">
<span<?php echo $movimientos2Ddetalle->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeTotal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
	<tr id="r_alicuotaIva">
		<td><span id="elh_movimientos2Ddetalle_alicuotaIva"><?php echo $movimientos2Ddetalle->alicuotaIva->FldCaption() ?></span></td>
		<td data-name="alicuotaIva"<?php echo $movimientos2Ddetalle->alicuotaIva->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_alicuotaIva">
<span<?php echo $movimientos2Ddetalle->alicuotaIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->alicuotaIva->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
	<tr id="r_importeIva">
		<td><span id="elh_movimientos2Ddetalle_importeIva"><?php echo $movimientos2Ddetalle->importeIva->FldCaption() ?></span></td>
		<td data-name="importeIva"<?php echo $movimientos2Ddetalle->importeIva->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeIva">
<span<?php echo $movimientos2Ddetalle->importeIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeIva->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
	<tr id="r_importeNeto">
		<td><span id="elh_movimientos2Ddetalle_importeNeto"><?php echo $movimientos2Ddetalle->importeNeto->FldCaption() ?></span></td>
		<td data-name="importeNeto"<?php echo $movimientos2Ddetalle->importeNeto->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importeNeto">
<span<?php echo $movimientos2Ddetalle->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeNeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
	<tr id="r_importePesos">
		<td><span id="elh_movimientos2Ddetalle_importePesos"><?php echo $movimientos2Ddetalle->importePesos->FldCaption() ?></span></td>
		<td data-name="importePesos"<?php echo $movimientos2Ddetalle->importePesos->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_importePesos">
<span<?php echo $movimientos2Ddetalle->importePesos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importePesos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
	<tr id="r_estadoImportacion">
		<td><span id="elh_movimientos2Ddetalle_estadoImportacion"><?php echo $movimientos2Ddetalle->estadoImportacion->FldCaption() ?></span></td>
		<td data-name="estadoImportacion"<?php echo $movimientos2Ddetalle->estadoImportacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_estadoImportacion">
<span<?php echo $movimientos2Ddetalle->estadoImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->estadoImportacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
	<tr id="r_origenImportacion">
		<td><span id="elh_movimientos2Ddetalle_origenImportacion"><?php echo $movimientos2Ddetalle->origenImportacion->FldCaption() ?></span></td>
		<td data-name="origenImportacion"<?php echo $movimientos2Ddetalle->origenImportacion->CellAttributes() ?>>
<span id="el_movimientos2Ddetalle_origenImportacion">
<span<?php echo $movimientos2Ddetalle->origenImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->origenImportacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<script type="text/javascript">
fmovimientos2Ddetalleview.Init();
</script>
<?php } ?>
<?php
$movimientos2Ddetalle_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$movimientos2Ddetalle_view->Page_Terminate();
?>