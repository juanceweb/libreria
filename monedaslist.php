<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "monedasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$monedas_list = NULL; // Initialize page object first

class cmonedas_list extends cmonedas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'monedas';

	// Page object name
	var $PageObjName = 'monedas_list';

	// Grid form hidden field names
	var $FormName = 'fmonedaslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (monedas)
		if (!isset($GLOBALS["monedas"]) || get_class($GLOBALS["monedas"]) == "cmonedas") {
			$GLOBALS["monedas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["monedas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "monedasadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "monedasdelete.php";
		$this->MultiUpdateUrl = "monedasupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'monedas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fmonedaslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->denominacion->SetVisibility();
		$this->simbolo->SetVisibility();
		$this->cotizacion->SetVisibility();
		$this->idMonedaActualizacion->SetVisibility();
		$this->idTipoDolar->SetVisibility();
		$this->porcentajeVariacion->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $monedas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($monedas);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->cotizacion->FormValue = ""; // Clear form value
		$this->porcentajeVariacion->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->denominacion, $bCtrl); // denominacion
			$this->UpdateSort($this->simbolo, $bCtrl); // simbolo
			$this->UpdateSort($this->cotizacion, $bCtrl); // cotizacion
			$this->UpdateSort($this->idMonedaActualizacion, $bCtrl); // idMonedaActualizacion
			$this->UpdateSort($this->idTipoDolar, $bCtrl); // idTipoDolar
			$this->UpdateSort($this->porcentajeVariacion, $bCtrl); // porcentajeVariacion
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->denominacion->setSort("");
				$this->simbolo->setSort("");
				$this->cotizacion->setSort("");
				$this->idMonedaActualizacion->setSort("");
				$this->idTipoDolar->setSort("");
				$this->porcentajeVariacion->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd() && ($this->CurrentAction == "add");
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"return ew_ConfirmDelete(this);\"" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmonedaslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmonedaslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmonedaslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->denominacion->CurrentValue = NULL;
		$this->denominacion->OldValue = $this->denominacion->CurrentValue;
		$this->simbolo->CurrentValue = NULL;
		$this->simbolo->OldValue = $this->simbolo->CurrentValue;
		$this->cotizacion->CurrentValue = NULL;
		$this->cotizacion->OldValue = $this->cotizacion->CurrentValue;
		$this->idMonedaActualizacion->CurrentValue = NULL;
		$this->idMonedaActualizacion->OldValue = $this->idMonedaActualizacion->CurrentValue;
		$this->idTipoDolar->CurrentValue = NULL;
		$this->idTipoDolar->OldValue = $this->idTipoDolar->CurrentValue;
		$this->porcentajeVariacion->CurrentValue = NULL;
		$this->porcentajeVariacion->OldValue = $this->porcentajeVariacion->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->simbolo->FldIsDetailKey) {
			$this->simbolo->setFormValue($objForm->GetValue("x_simbolo"));
		}
		if (!$this->cotizacion->FldIsDetailKey) {
			$this->cotizacion->setFormValue($objForm->GetValue("x_cotizacion"));
		}
		if (!$this->idMonedaActualizacion->FldIsDetailKey) {
			$this->idMonedaActualizacion->setFormValue($objForm->GetValue("x_idMonedaActualizacion"));
		}
		if (!$this->idTipoDolar->FldIsDetailKey) {
			$this->idTipoDolar->setFormValue($objForm->GetValue("x_idTipoDolar"));
		}
		if (!$this->porcentajeVariacion->FldIsDetailKey) {
			$this->porcentajeVariacion->setFormValue($objForm->GetValue("x_porcentajeVariacion"));
		}
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->simbolo->CurrentValue = $this->simbolo->FormValue;
		$this->cotizacion->CurrentValue = $this->cotizacion->FormValue;
		$this->idMonedaActualizacion->CurrentValue = $this->idMonedaActualizacion->FormValue;
		$this->idTipoDolar->CurrentValue = $this->idTipoDolar->FormValue;
		$this->porcentajeVariacion->CurrentValue = $this->porcentajeVariacion->FormValue;
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
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->simbolo->setDbValue($rs->fields('simbolo'));
		$this->cotizacion->setDbValue($rs->fields('cotizacion'));
		$this->idMonedaActualizacion->setDbValue($rs->fields('idMonedaActualizacion'));
		$this->idTipoDolar->setDbValue($rs->fields('idTipoDolar'));
		$this->porcentajeVariacion->setDbValue($rs->fields('porcentajeVariacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->simbolo->DbValue = $row['simbolo'];
		$this->cotizacion->DbValue = $row['cotizacion'];
		$this->idMonedaActualizacion->DbValue = $row['idMonedaActualizacion'];
		$this->idTipoDolar->DbValue = $row['idTipoDolar'];
		$this->porcentajeVariacion->DbValue = $row['porcentajeVariacion'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->cotizacion->FormValue == $this->cotizacion->CurrentValue && is_numeric(ew_StrToFloat($this->cotizacion->CurrentValue)))
			$this->cotizacion->CurrentValue = ew_StrToFloat($this->cotizacion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcentajeVariacion->FormValue == $this->porcentajeVariacion->CurrentValue && is_numeric(ew_StrToFloat($this->porcentajeVariacion->CurrentValue)))
			$this->porcentajeVariacion->CurrentValue = ew_StrToFloat($this->porcentajeVariacion->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// denominacion
		// simbolo
		// cotizacion
		// idMonedaActualizacion
		// idTipoDolar
		// porcentajeVariacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// simbolo
		$this->simbolo->ViewValue = $this->simbolo->CurrentValue;
		$this->simbolo->ViewCustomAttributes = "";

		// cotizacion
		$this->cotizacion->ViewValue = $this->cotizacion->CurrentValue;
		$this->cotizacion->ViewCustomAttributes = "";

		// idMonedaActualizacion
		if (strval($this->idMonedaActualizacion->CurrentValue) <> "") {
			$this->idMonedaActualizacion->ViewValue = $this->idMonedaActualizacion->OptionCaption($this->idMonedaActualizacion->CurrentValue);
		} else {
			$this->idMonedaActualizacion->ViewValue = NULL;
		}
		$this->idMonedaActualizacion->ViewCustomAttributes = "";

		// idTipoDolar
		if (strval($this->idTipoDolar->CurrentValue) <> "") {
			$this->idTipoDolar->ViewValue = $this->idTipoDolar->OptionCaption($this->idTipoDolar->CurrentValue);
		} else {
			$this->idTipoDolar->ViewValue = NULL;
		}
		$this->idTipoDolar->ViewCustomAttributes = "";

		// porcentajeVariacion
		$this->porcentajeVariacion->ViewValue = $this->porcentajeVariacion->CurrentValue;
		$this->porcentajeVariacion->ViewCustomAttributes = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// simbolo
			$this->simbolo->LinkCustomAttributes = "";
			$this->simbolo->HrefValue = "";
			$this->simbolo->TooltipValue = "";

			// cotizacion
			$this->cotizacion->LinkCustomAttributes = "";
			$this->cotizacion->HrefValue = "";
			$this->cotizacion->TooltipValue = "";

			// idMonedaActualizacion
			$this->idMonedaActualizacion->LinkCustomAttributes = "";
			$this->idMonedaActualizacion->HrefValue = "";
			$this->idMonedaActualizacion->TooltipValue = "";

			// idTipoDolar
			$this->idTipoDolar->LinkCustomAttributes = "";
			$this->idTipoDolar->HrefValue = "";
			$this->idTipoDolar->TooltipValue = "";

			// porcentajeVariacion
			$this->porcentajeVariacion->LinkCustomAttributes = "";
			$this->porcentajeVariacion->HrefValue = "";
			$this->porcentajeVariacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// simbolo
			$this->simbolo->EditAttrs["class"] = "form-control";
			$this->simbolo->EditCustomAttributes = "";
			$this->simbolo->EditValue = ew_HtmlEncode($this->simbolo->CurrentValue);
			$this->simbolo->PlaceHolder = ew_RemoveHtml($this->simbolo->FldCaption());

			// cotizacion
			$this->cotizacion->EditAttrs["class"] = "form-control";
			$this->cotizacion->EditCustomAttributes = "";
			$this->cotizacion->EditValue = ew_HtmlEncode($this->cotizacion->CurrentValue);
			$this->cotizacion->PlaceHolder = ew_RemoveHtml($this->cotizacion->FldCaption());
			if (strval($this->cotizacion->EditValue) <> "" && is_numeric($this->cotizacion->EditValue)) $this->cotizacion->EditValue = ew_FormatNumber($this->cotizacion->EditValue, -2, -1, -2, 0);

			// idMonedaActualizacion
			$this->idMonedaActualizacion->EditAttrs["class"] = "form-control";
			$this->idMonedaActualizacion->EditCustomAttributes = 'data-elemento-dependiente="true"';
			$this->idMonedaActualizacion->EditValue = $this->idMonedaActualizacion->Options(TRUE);

			// idTipoDolar
			$this->idTipoDolar->EditAttrs["class"] = "form-control";
			$this->idTipoDolar->EditCustomAttributes = 'data-visible=\'{"x_idMonedaActualizacion":{"":false,"0":true,"1":false,"2":false,"3":false,"4":false,"5":false,"6":false}}\'';
			$this->idTipoDolar->EditValue = $this->idTipoDolar->Options(TRUE);

			// porcentajeVariacion
			$this->porcentajeVariacion->EditAttrs["class"] = "form-control";
			$this->porcentajeVariacion->EditCustomAttributes = "";
			$this->porcentajeVariacion->EditValue = ew_HtmlEncode($this->porcentajeVariacion->CurrentValue);
			$this->porcentajeVariacion->PlaceHolder = ew_RemoveHtml($this->porcentajeVariacion->FldCaption());
			if (strval($this->porcentajeVariacion->EditValue) <> "" && is_numeric($this->porcentajeVariacion->EditValue)) $this->porcentajeVariacion->EditValue = ew_FormatNumber($this->porcentajeVariacion->EditValue, -2, -1, -2, 0);

			// Add refer script
			// denominacion

			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// simbolo
			$this->simbolo->LinkCustomAttributes = "";
			$this->simbolo->HrefValue = "";

			// cotizacion
			$this->cotizacion->LinkCustomAttributes = "";
			$this->cotizacion->HrefValue = "";

			// idMonedaActualizacion
			$this->idMonedaActualizacion->LinkCustomAttributes = "";
			$this->idMonedaActualizacion->HrefValue = "";

			// idTipoDolar
			$this->idTipoDolar->LinkCustomAttributes = "";
			$this->idTipoDolar->HrefValue = "";

			// porcentajeVariacion
			$this->porcentajeVariacion->LinkCustomAttributes = "";
			$this->porcentajeVariacion->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// simbolo
			$this->simbolo->EditAttrs["class"] = "form-control";
			$this->simbolo->EditCustomAttributes = "";
			$this->simbolo->EditValue = ew_HtmlEncode($this->simbolo->CurrentValue);
			$this->simbolo->PlaceHolder = ew_RemoveHtml($this->simbolo->FldCaption());

			// cotizacion
			$this->cotizacion->EditAttrs["class"] = "form-control";
			$this->cotizacion->EditCustomAttributes = "";
			$this->cotizacion->EditValue = ew_HtmlEncode($this->cotizacion->CurrentValue);
			$this->cotizacion->PlaceHolder = ew_RemoveHtml($this->cotizacion->FldCaption());
			if (strval($this->cotizacion->EditValue) <> "" && is_numeric($this->cotizacion->EditValue)) $this->cotizacion->EditValue = ew_FormatNumber($this->cotizacion->EditValue, -2, -1, -2, 0);

			// idMonedaActualizacion
			$this->idMonedaActualizacion->EditAttrs["class"] = "form-control";
			$this->idMonedaActualizacion->EditCustomAttributes = 'data-elemento-dependiente="true"';
			$this->idMonedaActualizacion->EditValue = $this->idMonedaActualizacion->Options(TRUE);

			// idTipoDolar
			$this->idTipoDolar->EditAttrs["class"] = "form-control";
			$this->idTipoDolar->EditCustomAttributes = 'data-visible=\'{"x_idMonedaActualizacion":{"":false,"0":true,"1":false,"2":false,"3":false,"4":false,"5":false,"6":false}}\'';
			$this->idTipoDolar->EditValue = $this->idTipoDolar->Options(TRUE);

			// porcentajeVariacion
			$this->porcentajeVariacion->EditAttrs["class"] = "form-control";
			$this->porcentajeVariacion->EditCustomAttributes = "";
			$this->porcentajeVariacion->EditValue = ew_HtmlEncode($this->porcentajeVariacion->CurrentValue);
			$this->porcentajeVariacion->PlaceHolder = ew_RemoveHtml($this->porcentajeVariacion->FldCaption());
			if (strval($this->porcentajeVariacion->EditValue) <> "" && is_numeric($this->porcentajeVariacion->EditValue)) $this->porcentajeVariacion->EditValue = ew_FormatNumber($this->porcentajeVariacion->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// denominacion

			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// simbolo
			$this->simbolo->LinkCustomAttributes = "";
			$this->simbolo->HrefValue = "";

			// cotizacion
			$this->cotizacion->LinkCustomAttributes = "";
			$this->cotizacion->HrefValue = "";

			// idMonedaActualizacion
			$this->idMonedaActualizacion->LinkCustomAttributes = "";
			$this->idMonedaActualizacion->HrefValue = "";

			// idTipoDolar
			$this->idTipoDolar->LinkCustomAttributes = "";
			$this->idTipoDolar->HrefValue = "";

			// porcentajeVariacion
			$this->porcentajeVariacion->LinkCustomAttributes = "";
			$this->porcentajeVariacion->HrefValue = "";
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
		if (!ew_CheckNumber($this->cotizacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->cotizacion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->porcentajeVariacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->porcentajeVariacion->FldErrMsg());
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

			// simbolo
			$this->simbolo->SetDbValueDef($rsnew, $this->simbolo->CurrentValue, NULL, $this->simbolo->ReadOnly);

			// cotizacion
			$this->cotizacion->SetDbValueDef($rsnew, $this->cotizacion->CurrentValue, NULL, $this->cotizacion->ReadOnly);

			// idMonedaActualizacion
			$this->idMonedaActualizacion->SetDbValueDef($rsnew, $this->idMonedaActualizacion->CurrentValue, NULL, $this->idMonedaActualizacion->ReadOnly);

			// idTipoDolar
			$this->idTipoDolar->SetDbValueDef($rsnew, $this->idTipoDolar->CurrentValue, NULL, $this->idTipoDolar->ReadOnly);

			// porcentajeVariacion
			$this->porcentajeVariacion->SetDbValueDef($rsnew, $this->porcentajeVariacion->CurrentValue, NULL, $this->porcentajeVariacion->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// denominacion
		$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, FALSE);

		// simbolo
		$this->simbolo->SetDbValueDef($rsnew, $this->simbolo->CurrentValue, NULL, FALSE);

		// cotizacion
		$this->cotizacion->SetDbValueDef($rsnew, $this->cotizacion->CurrentValue, NULL, FALSE);

		// idMonedaActualizacion
		$this->idMonedaActualizacion->SetDbValueDef($rsnew, $this->idMonedaActualizacion->CurrentValue, NULL, FALSE);

		// idTipoDolar
		$this->idTipoDolar->SetDbValueDef($rsnew, $this->idTipoDolar->CurrentValue, NULL, FALSE);

		// porcentajeVariacion
		$this->porcentajeVariacion->SetDbValueDef($rsnew, $this->porcentajeVariacion->CurrentValue, NULL, FALSE);

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
		$item->Body = "<button id=\"emf_monedas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_monedas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmonedaslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

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

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
		$options = &$this->OtherOptions;
		$option = $options["addedit"];	
		$item = &$option->Add("actualizar"); //add
		$item->Body = '<div class="btn-group"><a onclick="actualizarCotizacion()" class="btn btn-default btn-sm ewRowLink" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Actualizar Todo"><span class="glyphicon glyphicon-refresh ewIcon" aria-hidden="true"></span></a></div>'; // add your a tag content here
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {
				?>
				<div class="row">
					<div style="padding:15px" class="col-md-6">
						<h4>Cotización de principales monedas</h4>
						<div id="contenedor-monedas" class="ewGrid col-xs-12">
						</div>
					</div>
					<div style="padding:15px" class="col-md-6">
						<h4>Cotización de dolar</h4>
						<div id="contenedor-dolar" class="ewGrid col-xs-12">
						</div>
					</div>
				</div>
					<div id="loading" style="display:none;background-color: rgba(255,255,255,0.7);width:100%;height: 100%;position: fixed;z-index: 99999999999;top: 0;left: 0;text-align:center">
						<h1>Cargando...</h1>
					</div>
				<?php
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

			$opt = &$this->ListOptions->Add("actualizar");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
			$opt->MoveTo(0); // Move to first column	
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

		$this->ListOptions->Items["actualizar"]->Body='<div class="btn-group"><a onclick="actualizarCotizacion('.$this->id->DbValue.')" class="btn btn-default btn-sm ewRowLink" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Actualizar cotización"><span class="glyphicon glyphicon-refresh ewIcon" aria-hidden="true"></span></a></div>';	
	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($monedas_list)) $monedas_list = new cmonedas_list();

// Page init
$monedas_list->Page_Init();

// Page main
$monedas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$monedas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($monedas->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmonedaslist = new ew_Form("fmonedaslist", "list");
fmonedaslist.FormKeyCountName = '<?php echo $monedas_list->FormKeyCountName ?>';

// Validate form
fmonedaslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cotizacion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($monedas->cotizacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_porcentajeVariacion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($monedas->porcentajeVariacion->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fmonedaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmonedaslist.ValidateRequired = true;
<?php } else { ?>
fmonedaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmonedaslist.Lists["x_idMonedaActualizacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmonedaslist.Lists["x_idMonedaActualizacion"].Options = <?php echo json_encode($monedas->idMonedaActualizacion->Options()) ?>;
fmonedaslist.Lists["x_idTipoDolar"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmonedaslist.Lists["x_idTipoDolar"].Options = <?php echo json_encode($monedas->idTipoDolar->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($monedas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($monedas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($monedas_list->TotalRecs > 0 && $monedas_list->ExportOptions->Visible()) { ?>
<?php $monedas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($monedas->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $monedas_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($monedas_list->TotalRecs <= 0)
			$monedas_list->TotalRecs = $monedas->SelectRecordCount();
	} else {
		if (!$monedas_list->Recordset && ($monedas_list->Recordset = $monedas_list->LoadRecordset()))
			$monedas_list->TotalRecs = $monedas_list->Recordset->RecordCount();
	}
	$monedas_list->StartRec = 1;
	if ($monedas_list->DisplayRecs <= 0 || ($monedas->Export <> "" && $monedas->ExportAll)) // Display all records
		$monedas_list->DisplayRecs = $monedas_list->TotalRecs;
	if (!($monedas->Export <> "" && $monedas->ExportAll))
		$monedas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$monedas_list->Recordset = $monedas_list->LoadRecordset($monedas_list->StartRec-1, $monedas_list->DisplayRecs);

	// Set no record found message
	if ($monedas->CurrentAction == "" && $monedas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$monedas_list->setWarningMessage(ew_DeniedMsg());
		if ($monedas_list->SearchWhere == "0=101")
			$monedas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$monedas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$monedas_list->RenderOtherOptions();
?>
<?php $monedas_list->ShowPageHeader(); ?>
<?php
$monedas_list->ShowMessage();
?>
<?php if ($monedas_list->TotalRecs > 0 || $monedas->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid monedas">
<?php if ($monedas->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($monedas->CurrentAction <> "gridadd" && $monedas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($monedas_list->Pager)) $monedas_list->Pager = new cPrevNextPager($monedas_list->StartRec, $monedas_list->DisplayRecs, $monedas_list->TotalRecs) ?>
<?php if ($monedas_list->Pager->RecordCount > 0 && $monedas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($monedas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $monedas_list->PageUrl() ?>start=<?php echo $monedas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($monedas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $monedas_list->PageUrl() ?>start=<?php echo $monedas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $monedas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($monedas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $monedas_list->PageUrl() ?>start=<?php echo $monedas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($monedas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $monedas_list->PageUrl() ?>start=<?php echo $monedas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $monedas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $monedas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $monedas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $monedas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($monedas_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="monedas">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($monedas_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($monedas_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($monedas_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($monedas_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($monedas_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($monedas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmonedaslist" id="fmonedaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($monedas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $monedas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="monedas">
<div id="gmp_monedas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($monedas_list->TotalRecs > 0 || $monedas->CurrentAction == "add" || $monedas->CurrentAction == "copy") { ?>
<table id="tbl_monedaslist" class="table ewTable">
<?php echo $monedas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$monedas_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$monedas_list->RenderListOptions();

// Render list options (header, left)
$monedas_list->ListOptions->Render("header", "left");
?>
<?php if ($monedas->denominacion->Visible) { // denominacion ?>
	<?php if ($monedas->SortUrl($monedas->denominacion) == "") { ?>
		<th data-name="denominacion"><div id="elh_monedas_denominacion" class="monedas_denominacion"><div class="ewTableHeaderCaption"><?php echo $monedas->denominacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->denominacion) ?>',2);"><div id="elh_monedas_denominacion" class="monedas_denominacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->denominacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->denominacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->denominacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($monedas->simbolo->Visible) { // simbolo ?>
	<?php if ($monedas->SortUrl($monedas->simbolo) == "") { ?>
		<th data-name="simbolo"><div id="elh_monedas_simbolo" class="monedas_simbolo"><div class="ewTableHeaderCaption"><?php echo $monedas->simbolo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="simbolo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->simbolo) ?>',2);"><div id="elh_monedas_simbolo" class="monedas_simbolo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->simbolo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->simbolo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->simbolo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($monedas->cotizacion->Visible) { // cotizacion ?>
	<?php if ($monedas->SortUrl($monedas->cotizacion) == "") { ?>
		<th data-name="cotizacion"><div id="elh_monedas_cotizacion" class="monedas_cotizacion"><div class="ewTableHeaderCaption"><?php echo $monedas->cotizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cotizacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->cotizacion) ?>',2);"><div id="elh_monedas_cotizacion" class="monedas_cotizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->cotizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->cotizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->cotizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($monedas->idMonedaActualizacion->Visible) { // idMonedaActualizacion ?>
	<?php if ($monedas->SortUrl($monedas->idMonedaActualizacion) == "") { ?>
		<th data-name="idMonedaActualizacion"><div id="elh_monedas_idMonedaActualizacion" class="monedas_idMonedaActualizacion"><div class="ewTableHeaderCaption"><?php echo $monedas->idMonedaActualizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idMonedaActualizacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->idMonedaActualizacion) ?>',2);"><div id="elh_monedas_idMonedaActualizacion" class="monedas_idMonedaActualizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->idMonedaActualizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->idMonedaActualizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->idMonedaActualizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($monedas->idTipoDolar->Visible) { // idTipoDolar ?>
	<?php if ($monedas->SortUrl($monedas->idTipoDolar) == "") { ?>
		<th data-name="idTipoDolar"><div id="elh_monedas_idTipoDolar" class="monedas_idTipoDolar"><div class="ewTableHeaderCaption"><?php echo $monedas->idTipoDolar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTipoDolar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->idTipoDolar) ?>',2);"><div id="elh_monedas_idTipoDolar" class="monedas_idTipoDolar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->idTipoDolar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->idTipoDolar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->idTipoDolar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($monedas->porcentajeVariacion->Visible) { // porcentajeVariacion ?>
	<?php if ($monedas->SortUrl($monedas->porcentajeVariacion) == "") { ?>
		<th data-name="porcentajeVariacion"><div id="elh_monedas_porcentajeVariacion" class="monedas_porcentajeVariacion"><div class="ewTableHeaderCaption"><?php echo $monedas->porcentajeVariacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porcentajeVariacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $monedas->SortUrl($monedas->porcentajeVariacion) ?>',2);"><div id="elh_monedas_porcentajeVariacion" class="monedas_porcentajeVariacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $monedas->porcentajeVariacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($monedas->porcentajeVariacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($monedas->porcentajeVariacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$monedas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($monedas->CurrentAction == "add" || $monedas->CurrentAction == "copy") {
		$monedas_list->RowIndex = 0;
		$monedas_list->KeyCount = $monedas_list->RowIndex;
		if ($monedas->CurrentAction == "add")
			$monedas_list->LoadDefaultValues();
		if ($monedas->EventCancelled) // Insert failed
			$monedas_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$monedas->ResetAttrs();
		$monedas->RowAttrs = array_merge($monedas->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_monedas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$monedas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$monedas_list->RenderRow();

		// Render list options
		$monedas_list->RenderListOptions();
		$monedas_list->StartRowCnt = 0;
?>
	<tr<?php echo $monedas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$monedas_list->ListOptions->Render("body", "left", $monedas_list->RowCnt);
?>
	<?php if ($monedas->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_denominacion" class="form-group monedas_denominacion">
<input type="text" data-table="monedas" data-field="x_denominacion" name="x<?php echo $monedas_list->RowIndex ?>_denominacion" id="x<?php echo $monedas_list->RowIndex ?>_denominacion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($monedas->denominacion->getPlaceHolder()) ?>" value="<?php echo $monedas->denominacion->EditValue ?>"<?php echo $monedas->denominacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="monedas" data-field="x_denominacion" name="o<?php echo $monedas_list->RowIndex ?>_denominacion" id="o<?php echo $monedas_list->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($monedas->denominacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($monedas->simbolo->Visible) { // simbolo ?>
		<td data-name="simbolo">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_simbolo" class="form-group monedas_simbolo">
<input type="text" data-table="monedas" data-field="x_simbolo" name="x<?php echo $monedas_list->RowIndex ?>_simbolo" id="x<?php echo $monedas_list->RowIndex ?>_simbolo" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($monedas->simbolo->getPlaceHolder()) ?>" value="<?php echo $monedas->simbolo->EditValue ?>"<?php echo $monedas->simbolo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="monedas" data-field="x_simbolo" name="o<?php echo $monedas_list->RowIndex ?>_simbolo" id="o<?php echo $monedas_list->RowIndex ?>_simbolo" value="<?php echo ew_HtmlEncode($monedas->simbolo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($monedas->cotizacion->Visible) { // cotizacion ?>
		<td data-name="cotizacion">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_cotizacion" class="form-group monedas_cotizacion">
<input type="text" data-table="monedas" data-field="x_cotizacion" name="x<?php echo $monedas_list->RowIndex ?>_cotizacion" id="x<?php echo $monedas_list->RowIndex ?>_cotizacion" size="30" placeholder="<?php echo ew_HtmlEncode($monedas->cotizacion->getPlaceHolder()) ?>" value="<?php echo $monedas->cotizacion->EditValue ?>"<?php echo $monedas->cotizacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="monedas" data-field="x_cotizacion" name="o<?php echo $monedas_list->RowIndex ?>_cotizacion" id="o<?php echo $monedas_list->RowIndex ?>_cotizacion" value="<?php echo ew_HtmlEncode($monedas->cotizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($monedas->idMonedaActualizacion->Visible) { // idMonedaActualizacion ?>
		<td data-name="idMonedaActualizacion">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idMonedaActualizacion" class="form-group monedas_idMonedaActualizacion">
<select data-table="monedas" data-field="x_idMonedaActualizacion" data-value-separator="<?php echo $monedas->idMonedaActualizacion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion" name="x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion"<?php echo $monedas->idMonedaActualizacion->EditAttributes() ?>>
<?php echo $monedas->idMonedaActualizacion->SelectOptionListHtml("x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion") ?>
</select>
</span>
<input type="hidden" data-table="monedas" data-field="x_idMonedaActualizacion" name="o<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion" id="o<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion" value="<?php echo ew_HtmlEncode($monedas->idMonedaActualizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($monedas->idTipoDolar->Visible) { // idTipoDolar ?>
		<td data-name="idTipoDolar">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idTipoDolar" class="form-group monedas_idTipoDolar">
<select data-table="monedas" data-field="x_idTipoDolar" data-value-separator="<?php echo $monedas->idTipoDolar->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $monedas_list->RowIndex ?>_idTipoDolar" name="x<?php echo $monedas_list->RowIndex ?>_idTipoDolar"<?php echo $monedas->idTipoDolar->EditAttributes() ?>>
<?php echo $monedas->idTipoDolar->SelectOptionListHtml("x<?php echo $monedas_list->RowIndex ?>_idTipoDolar") ?>
</select>
</span>
<input type="hidden" data-table="monedas" data-field="x_idTipoDolar" name="o<?php echo $monedas_list->RowIndex ?>_idTipoDolar" id="o<?php echo $monedas_list->RowIndex ?>_idTipoDolar" value="<?php echo ew_HtmlEncode($monedas->idTipoDolar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($monedas->porcentajeVariacion->Visible) { // porcentajeVariacion ?>
		<td data-name="porcentajeVariacion">
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_porcentajeVariacion" class="form-group monedas_porcentajeVariacion">
<input type="text" data-table="monedas" data-field="x_porcentajeVariacion" name="x<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" id="x<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" size="30" placeholder="<?php echo ew_HtmlEncode($monedas->porcentajeVariacion->getPlaceHolder()) ?>" value="<?php echo $monedas->porcentajeVariacion->EditValue ?>"<?php echo $monedas->porcentajeVariacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="monedas" data-field="x_porcentajeVariacion" name="o<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" id="o<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" value="<?php echo ew_HtmlEncode($monedas->porcentajeVariacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$monedas_list->ListOptions->Render("body", "right", $monedas_list->RowCnt);
?>
<script type="text/javascript">
fmonedaslist.UpdateOpts(<?php echo $monedas_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($monedas->ExportAll && $monedas->Export <> "") {
	$monedas_list->StopRec = $monedas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($monedas_list->TotalRecs > $monedas_list->StartRec + $monedas_list->DisplayRecs - 1)
		$monedas_list->StopRec = $monedas_list->StartRec + $monedas_list->DisplayRecs - 1;
	else
		$monedas_list->StopRec = $monedas_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($monedas_list->FormKeyCountName) && ($monedas->CurrentAction == "gridadd" || $monedas->CurrentAction == "gridedit" || $monedas->CurrentAction == "F")) {
		$monedas_list->KeyCount = $objForm->GetValue($monedas_list->FormKeyCountName);
		$monedas_list->StopRec = $monedas_list->StartRec + $monedas_list->KeyCount - 1;
	}
}
$monedas_list->RecCnt = $monedas_list->StartRec - 1;
if ($monedas_list->Recordset && !$monedas_list->Recordset->EOF) {
	$monedas_list->Recordset->MoveFirst();
	$bSelectLimit = $monedas_list->UseSelectLimit;
	if (!$bSelectLimit && $monedas_list->StartRec > 1)
		$monedas_list->Recordset->Move($monedas_list->StartRec - 1);
} elseif (!$monedas->AllowAddDeleteRow && $monedas_list->StopRec == 0) {
	$monedas_list->StopRec = $monedas->GridAddRowCount;
}

// Initialize aggregate
$monedas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$monedas->ResetAttrs();
$monedas_list->RenderRow();
$monedas_list->EditRowCnt = 0;
if ($monedas->CurrentAction == "edit")
	$monedas_list->RowIndex = 1;
while ($monedas_list->RecCnt < $monedas_list->StopRec) {
	$monedas_list->RecCnt++;
	if (intval($monedas_list->RecCnt) >= intval($monedas_list->StartRec)) {
		$monedas_list->RowCnt++;

		// Set up key count
		$monedas_list->KeyCount = $monedas_list->RowIndex;

		// Init row class and style
		$monedas->ResetAttrs();
		$monedas->CssClass = "";
		if ($monedas->CurrentAction == "gridadd") {
			$monedas_list->LoadDefaultValues(); // Load default values
		} else {
			$monedas_list->LoadRowValues($monedas_list->Recordset); // Load row values
		}
		$monedas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($monedas->CurrentAction == "edit") {
			if ($monedas_list->CheckInlineEditKey() && $monedas_list->EditRowCnt == 0) { // Inline edit
				$monedas->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($monedas->CurrentAction == "edit" && $monedas->RowType == EW_ROWTYPE_EDIT && $monedas->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$monedas_list->RestoreFormValues(); // Restore form values
		}
		if ($monedas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$monedas_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$monedas->RowAttrs = array_merge($monedas->RowAttrs, array('data-rowindex'=>$monedas_list->RowCnt, 'id'=>'r' . $monedas_list->RowCnt . '_monedas', 'data-rowtype'=>$monedas->RowType));

		// Render row
		$monedas_list->RenderRow();

		// Render list options
		$monedas_list->RenderListOptions();
?>
	<tr<?php echo $monedas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$monedas_list->ListOptions->Render("body", "left", $monedas_list->RowCnt);
?>
	<?php if ($monedas->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion"<?php echo $monedas->denominacion->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_denominacion" class="form-group monedas_denominacion">
<input type="text" data-table="monedas" data-field="x_denominacion" name="x<?php echo $monedas_list->RowIndex ?>_denominacion" id="x<?php echo $monedas_list->RowIndex ?>_denominacion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($monedas->denominacion->getPlaceHolder()) ?>" value="<?php echo $monedas->denominacion->EditValue ?>"<?php echo $monedas->denominacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_denominacion" class="monedas_denominacion">
<span<?php echo $monedas->denominacion->ViewAttributes() ?>>
<?php echo $monedas->denominacion->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $monedas_list->PageObjName . "_row_" . $monedas_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT || $monedas->CurrentMode == "edit") { ?>
<input type="hidden" data-table="monedas" data-field="x_id" name="x<?php echo $monedas_list->RowIndex ?>_id" id="x<?php echo $monedas_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($monedas->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($monedas->simbolo->Visible) { // simbolo ?>
		<td data-name="simbolo"<?php echo $monedas->simbolo->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_simbolo" class="form-group monedas_simbolo">
<input type="text" data-table="monedas" data-field="x_simbolo" name="x<?php echo $monedas_list->RowIndex ?>_simbolo" id="x<?php echo $monedas_list->RowIndex ?>_simbolo" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($monedas->simbolo->getPlaceHolder()) ?>" value="<?php echo $monedas->simbolo->EditValue ?>"<?php echo $monedas->simbolo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_simbolo" class="monedas_simbolo">
<span<?php echo $monedas->simbolo->ViewAttributes() ?>>
<?php echo $monedas->simbolo->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($monedas->cotizacion->Visible) { // cotizacion ?>
		<td data-name="cotizacion"<?php echo $monedas->cotizacion->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_cotizacion" class="form-group monedas_cotizacion">
<input type="text" data-table="monedas" data-field="x_cotizacion" name="x<?php echo $monedas_list->RowIndex ?>_cotizacion" id="x<?php echo $monedas_list->RowIndex ?>_cotizacion" size="30" placeholder="<?php echo ew_HtmlEncode($monedas->cotizacion->getPlaceHolder()) ?>" value="<?php echo $monedas->cotizacion->EditValue ?>"<?php echo $monedas->cotizacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_cotizacion" class="monedas_cotizacion">
<span<?php echo $monedas->cotizacion->ViewAttributes() ?>>
<?php echo $monedas->cotizacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($monedas->idMonedaActualizacion->Visible) { // idMonedaActualizacion ?>
		<td data-name="idMonedaActualizacion"<?php echo $monedas->idMonedaActualizacion->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idMonedaActualizacion" class="form-group monedas_idMonedaActualizacion">
<select data-table="monedas" data-field="x_idMonedaActualizacion" data-value-separator="<?php echo $monedas->idMonedaActualizacion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion" name="x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion"<?php echo $monedas->idMonedaActualizacion->EditAttributes() ?>>
<?php echo $monedas->idMonedaActualizacion->SelectOptionListHtml("x<?php echo $monedas_list->RowIndex ?>_idMonedaActualizacion") ?>
</select>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idMonedaActualizacion" class="monedas_idMonedaActualizacion">
<span<?php echo $monedas->idMonedaActualizacion->ViewAttributes() ?>>
<?php echo $monedas->idMonedaActualizacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($monedas->idTipoDolar->Visible) { // idTipoDolar ?>
		<td data-name="idTipoDolar"<?php echo $monedas->idTipoDolar->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idTipoDolar" class="form-group monedas_idTipoDolar">
<select data-table="monedas" data-field="x_idTipoDolar" data-value-separator="<?php echo $monedas->idTipoDolar->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $monedas_list->RowIndex ?>_idTipoDolar" name="x<?php echo $monedas_list->RowIndex ?>_idTipoDolar"<?php echo $monedas->idTipoDolar->EditAttributes() ?>>
<?php echo $monedas->idTipoDolar->SelectOptionListHtml("x<?php echo $monedas_list->RowIndex ?>_idTipoDolar") ?>
</select>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_idTipoDolar" class="monedas_idTipoDolar">
<span<?php echo $monedas->idTipoDolar->ViewAttributes() ?>>
<?php echo $monedas->idTipoDolar->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($monedas->porcentajeVariacion->Visible) { // porcentajeVariacion ?>
		<td data-name="porcentajeVariacion"<?php echo $monedas->porcentajeVariacion->CellAttributes() ?>>
<?php if ($monedas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_porcentajeVariacion" class="form-group monedas_porcentajeVariacion">
<input type="text" data-table="monedas" data-field="x_porcentajeVariacion" name="x<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" id="x<?php echo $monedas_list->RowIndex ?>_porcentajeVariacion" size="30" placeholder="<?php echo ew_HtmlEncode($monedas->porcentajeVariacion->getPlaceHolder()) ?>" value="<?php echo $monedas->porcentajeVariacion->EditValue ?>"<?php echo $monedas->porcentajeVariacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($monedas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $monedas_list->RowCnt ?>_monedas_porcentajeVariacion" class="monedas_porcentajeVariacion">
<span<?php echo $monedas->porcentajeVariacion->ViewAttributes() ?>>
<?php echo $monedas->porcentajeVariacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$monedas_list->ListOptions->Render("body", "right", $monedas_list->RowCnt);
?>
	</tr>
<?php if ($monedas->RowType == EW_ROWTYPE_ADD || $monedas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmonedaslist.UpdateOpts(<?php echo $monedas_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($monedas->CurrentAction <> "gridadd")
		$monedas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($monedas->CurrentAction == "add" || $monedas->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $monedas_list->FormKeyCountName ?>" id="<?php echo $monedas_list->FormKeyCountName ?>" value="<?php echo $monedas_list->KeyCount ?>">
<?php } ?>
<?php if ($monedas->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $monedas_list->FormKeyCountName ?>" id="<?php echo $monedas_list->FormKeyCountName ?>" value="<?php echo $monedas_list->KeyCount ?>">
<?php } ?>
<?php if ($monedas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($monedas_list->Recordset)
	$monedas_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($monedas_list->TotalRecs == 0 && $monedas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($monedas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($monedas->Export == "") { ?>
<script type="text/javascript">
fmonedaslist.Init();
</script>
<?php } ?>
<?php
$monedas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($monedas->Export == "") { ?>
<script type="text/javascript">
var accion="monedas";
var html = "true";
	  $.ajax({
		  url: "librerias/divisas.php",
		  type: "get",
		  data: {accion:accion,html:html},
		  dataType: "html",
		  success: function (response) {
		  	$("#contenedor-monedas").empty();
		  	$("#contenedor-monedas").html(response);
		  },
		  error: function(jqXHR, textStatus, errorThrown) {
			 console.log(textStatus, errorThrown);
		  }
	  });
var accion="dolar";
var html = "true";
	  $.ajax({
		  url: "librerias/divisas.php",
		  type: "get",
		  data: {accion:accion,html:html},
		  dataType: "html",
		  success: function (response) {
		  	$("#contenedor-dolar").empty();
		  	$("#contenedor-dolar").html(response);
		  },
		  error: function(jqXHR, textStatus, errorThrown) {
			 console.log(textStatus, errorThrown);
		  }
	  });
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$monedas_list->Page_Terminate();
?>
