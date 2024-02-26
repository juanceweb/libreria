<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "recibos2Dpagosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$recibos2Dpagos_list = NULL; // Initialize page object first

class crecibos2Dpagos_list extends crecibos2Dpagos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'recibos-pagos';

	// Page object name
	var $PageObjName = 'recibos2Dpagos_list';

	// Grid form hidden field names
	var $FormName = 'frecibos2Dpagoslist';
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

		// Table object (recibos2Dpagos)
		if (!isset($GLOBALS["recibos2Dpagos"]) || get_class($GLOBALS["recibos2Dpagos"]) == "crecibos2Dpagos") {
			$GLOBALS["recibos2Dpagos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["recibos2Dpagos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "recibos2Dpagosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "recibos2Dpagosdelete.php";
		$this->MultiUpdateUrl = "recibos2Dpagosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'recibos-pagos', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption frecibos2Dpagoslistsrch";

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
		$this->tipoFlujo->SetVisibility();
		$this->fecha->SetVisibility();
		$this->idTercero->SetVisibility();
		$this->importe->SetVisibility();
		$this->importeMovimientos->SetVisibility();
		$this->importeAdelantos->SetVisibility();
		$this->nroComprobante->SetVisibility();

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
		global $EW_EXPORT, $recibos2Dpagos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($recibos2Dpagos);
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

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

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

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "frecibos2Dpagoslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->tipoFlujo->AdvancedSearch->ToJSON(), ","); // Field tipoFlujo
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->idTercero->AdvancedSearch->ToJSON(), ","); // Field idTercero
		$sFilterList = ew_Concat($sFilterList, $this->importe->AdvancedSearch->ToJSON(), ","); // Field importe
		$sFilterList = ew_Concat($sFilterList, $this->importeMovimientos->AdvancedSearch->ToJSON(), ","); // Field importeMovimientos
		$sFilterList = ew_Concat($sFilterList, $this->importeAdelantos->AdvancedSearch->ToJSON(), ","); // Field importeAdelantos
		$sFilterList = ew_Concat($sFilterList, $this->valorDolar->AdvancedSearch->ToJSON(), ","); // Field valorDolar
		$sFilterList = ew_Concat($sFilterList, $this->nroComprobante->AdvancedSearch->ToJSON(), ","); // Field nroComprobante
		$sFilterList = ew_Concat($sFilterList, $this->contable->AdvancedSearch->ToJSON(), ","); // Field contable
		$sFilterList = ew_Concat($sFilterList, $this->idEstado->AdvancedSearch->ToJSON(), ","); // Field idEstado
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["cmd"] == "savefilters") {
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "frecibos2Dpagoslistsrch", $filters);
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field tipoFlujo
		$this->tipoFlujo->AdvancedSearch->SearchValue = @$filter["x_tipoFlujo"];
		$this->tipoFlujo->AdvancedSearch->SearchOperator = @$filter["z_tipoFlujo"];
		$this->tipoFlujo->AdvancedSearch->SearchCondition = @$filter["v_tipoFlujo"];
		$this->tipoFlujo->AdvancedSearch->SearchValue2 = @$filter["y_tipoFlujo"];
		$this->tipoFlujo->AdvancedSearch->SearchOperator2 = @$filter["w_tipoFlujo"];
		$this->tipoFlujo->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field idTercero
		$this->idTercero->AdvancedSearch->SearchValue = @$filter["x_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator = @$filter["z_idTercero"];
		$this->idTercero->AdvancedSearch->SearchCondition = @$filter["v_idTercero"];
		$this->idTercero->AdvancedSearch->SearchValue2 = @$filter["y_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator2 = @$filter["w_idTercero"];
		$this->idTercero->AdvancedSearch->Save();

		// Field importe
		$this->importe->AdvancedSearch->SearchValue = @$filter["x_importe"];
		$this->importe->AdvancedSearch->SearchOperator = @$filter["z_importe"];
		$this->importe->AdvancedSearch->SearchCondition = @$filter["v_importe"];
		$this->importe->AdvancedSearch->SearchValue2 = @$filter["y_importe"];
		$this->importe->AdvancedSearch->SearchOperator2 = @$filter["w_importe"];
		$this->importe->AdvancedSearch->Save();

		// Field importeMovimientos
		$this->importeMovimientos->AdvancedSearch->SearchValue = @$filter["x_importeMovimientos"];
		$this->importeMovimientos->AdvancedSearch->SearchOperator = @$filter["z_importeMovimientos"];
		$this->importeMovimientos->AdvancedSearch->SearchCondition = @$filter["v_importeMovimientos"];
		$this->importeMovimientos->AdvancedSearch->SearchValue2 = @$filter["y_importeMovimientos"];
		$this->importeMovimientos->AdvancedSearch->SearchOperator2 = @$filter["w_importeMovimientos"];
		$this->importeMovimientos->AdvancedSearch->Save();

		// Field importeAdelantos
		$this->importeAdelantos->AdvancedSearch->SearchValue = @$filter["x_importeAdelantos"];
		$this->importeAdelantos->AdvancedSearch->SearchOperator = @$filter["z_importeAdelantos"];
		$this->importeAdelantos->AdvancedSearch->SearchCondition = @$filter["v_importeAdelantos"];
		$this->importeAdelantos->AdvancedSearch->SearchValue2 = @$filter["y_importeAdelantos"];
		$this->importeAdelantos->AdvancedSearch->SearchOperator2 = @$filter["w_importeAdelantos"];
		$this->importeAdelantos->AdvancedSearch->Save();

		// Field valorDolar
		$this->valorDolar->AdvancedSearch->SearchValue = @$filter["x_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchOperator = @$filter["z_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchCondition = @$filter["v_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchValue2 = @$filter["y_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchOperator2 = @$filter["w_valorDolar"];
		$this->valorDolar->AdvancedSearch->Save();

		// Field nroComprobante
		$this->nroComprobante->AdvancedSearch->SearchValue = @$filter["x_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchOperator = @$filter["z_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchCondition = @$filter["v_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchValue2 = @$filter["y_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchOperator2 = @$filter["w_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->Save();

		// Field contable
		$this->contable->AdvancedSearch->SearchValue = @$filter["x_contable"];
		$this->contable->AdvancedSearch->SearchOperator = @$filter["z_contable"];
		$this->contable->AdvancedSearch->SearchCondition = @$filter["v_contable"];
		$this->contable->AdvancedSearch->SearchValue2 = @$filter["y_contable"];
		$this->contable->AdvancedSearch->SearchOperator2 = @$filter["w_contable"];
		$this->contable->AdvancedSearch->Save();

		// Field idEstado
		$this->idEstado->AdvancedSearch->SearchValue = @$filter["x_idEstado"];
		$this->idEstado->AdvancedSearch->SearchOperator = @$filter["z_idEstado"];
		$this->idEstado->AdvancedSearch->SearchCondition = @$filter["v_idEstado"];
		$this->idEstado->AdvancedSearch->SearchValue2 = @$filter["y_idEstado"];
		$this->idEstado->AdvancedSearch->SearchOperator2 = @$filter["w_idEstado"];
		$this->idEstado->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->tipoFlujo, $Default, FALSE); // tipoFlujo
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->idTercero, $Default, FALSE); // idTercero
		$this->BuildSearchSql($sWhere, $this->importe, $Default, FALSE); // importe
		$this->BuildSearchSql($sWhere, $this->importeMovimientos, $Default, FALSE); // importeMovimientos
		$this->BuildSearchSql($sWhere, $this->importeAdelantos, $Default, FALSE); // importeAdelantos
		$this->BuildSearchSql($sWhere, $this->valorDolar, $Default, FALSE); // valorDolar
		$this->BuildSearchSql($sWhere, $this->nroComprobante, $Default, FALSE); // nroComprobante
		$this->BuildSearchSql($sWhere, $this->contable, $Default, FALSE); // contable
		$this->BuildSearchSql($sWhere, $this->idEstado, $Default, FALSE); // idEstado

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->tipoFlujo->AdvancedSearch->Save(); // tipoFlujo
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->idTercero->AdvancedSearch->Save(); // idTercero
			$this->importe->AdvancedSearch->Save(); // importe
			$this->importeMovimientos->AdvancedSearch->Save(); // importeMovimientos
			$this->importeAdelantos->AdvancedSearch->Save(); // importeAdelantos
			$this->valorDolar->AdvancedSearch->Save(); // valorDolar
			$this->nroComprobante->AdvancedSearch->Save(); // nroComprobante
			$this->contable->AdvancedSearch->Save(); // contable
			$this->idEstado->AdvancedSearch->Save(); // idEstado
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nroComprobante, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->tipoFlujo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importe->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeMovimientos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeAdelantos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->valorDolar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nroComprobante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contable->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idEstado->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->tipoFlujo->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->idTercero->AdvancedSearch->UnsetSession();
		$this->importe->AdvancedSearch->UnsetSession();
		$this->importeMovimientos->AdvancedSearch->UnsetSession();
		$this->importeAdelantos->AdvancedSearch->UnsetSession();
		$this->valorDolar->AdvancedSearch->UnsetSession();
		$this->nroComprobante->AdvancedSearch->UnsetSession();
		$this->contable->AdvancedSearch->UnsetSession();
		$this->idEstado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->tipoFlujo->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->importe->AdvancedSearch->Load();
		$this->importeMovimientos->AdvancedSearch->Load();
		$this->importeAdelantos->AdvancedSearch->Load();
		$this->valorDolar->AdvancedSearch->Load();
		$this->nroComprobante->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->idEstado->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->tipoFlujo, $bCtrl); // tipoFlujo
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->idTercero, $bCtrl); // idTercero
			$this->UpdateSort($this->importe, $bCtrl); // importe
			$this->UpdateSort($this->importeMovimientos, $bCtrl); // importeMovimientos
			$this->UpdateSort($this->importeAdelantos, $bCtrl); // importeAdelantos
			$this->UpdateSort($this->nroComprobante, $bCtrl); // nroComprobante
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->tipoFlujo->setSort("");
				$this->fecha->setSort("");
				$this->idTercero->setSort("");
				$this->importe->setSort("");
				$this->importeMovimientos->setSort("");
				$this->importeAdelantos->setSort("");
				$this->nroComprobante->setSort("");
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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
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

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"frecibos2Dpagoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"frecibos2Dpagoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.frecibos2Dpagoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"frecibos2Dpagoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// tipoFlujo

		$this->tipoFlujo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoFlujo"]);
		if ($this->tipoFlujo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoFlujo->AdvancedSearch->SearchOperator = @$_GET["z_tipoFlujo"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$_GET["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha"];

		// idTercero
		$this->idTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTercero"]);
		if ($this->idTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTercero->AdvancedSearch->SearchOperator = @$_GET["z_idTercero"];

		// importe
		$this->importe->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importe"]);
		if ($this->importe->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importe->AdvancedSearch->SearchOperator = @$_GET["z_importe"];

		// importeMovimientos
		$this->importeMovimientos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeMovimientos"]);
		if ($this->importeMovimientos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeMovimientos->AdvancedSearch->SearchOperator = @$_GET["z_importeMovimientos"];

		// importeAdelantos
		$this->importeAdelantos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeAdelantos"]);
		if ($this->importeAdelantos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeAdelantos->AdvancedSearch->SearchOperator = @$_GET["z_importeAdelantos"];

		// valorDolar
		$this->valorDolar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_valorDolar"]);
		if ($this->valorDolar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->valorDolar->AdvancedSearch->SearchOperator = @$_GET["z_valorDolar"];

		// nroComprobante
		$this->nroComprobante->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nroComprobante"]);
		if ($this->nroComprobante->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nroComprobante->AdvancedSearch->SearchOperator = @$_GET["z_nroComprobante"];

		// contable
		$this->contable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contable"]);
		if ($this->contable->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contable->AdvancedSearch->SearchOperator = @$_GET["z_contable"];

		// idEstado
		$this->idEstado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idEstado"]);
		if ($this->idEstado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idEstado->AdvancedSearch->SearchOperator = @$_GET["z_idEstado"];
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
		$this->tipoFlujo->setDbValue($rs->fields('tipoFlujo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->importe->setDbValue($rs->fields('importe'));
		$this->importeMovimientos->setDbValue($rs->fields('importeMovimientos'));
		$this->importeAdelantos->setDbValue($rs->fields('importeAdelantos'));
		$this->valorDolar->setDbValue($rs->fields('valorDolar'));
		$this->nroComprobante->setDbValue($rs->fields('nroComprobante'));
		$this->contable->setDbValue($rs->fields('contable'));
		$this->idEstado->setDbValue($rs->fields('idEstado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tipoFlujo->DbValue = $row['tipoFlujo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->importe->DbValue = $row['importe'];
		$this->importeMovimientos->DbValue = $row['importeMovimientos'];
		$this->importeAdelantos->DbValue = $row['importeAdelantos'];
		$this->valorDolar->DbValue = $row['valorDolar'];
		$this->nroComprobante->DbValue = $row['nroComprobante'];
		$this->contable->DbValue = $row['contable'];
		$this->idEstado->DbValue = $row['idEstado'];
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
		if ($this->importe->FormValue == $this->importe->CurrentValue && is_numeric(ew_StrToFloat($this->importe->CurrentValue)))
			$this->importe->CurrentValue = ew_StrToFloat($this->importe->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeMovimientos->FormValue == $this->importeMovimientos->CurrentValue && is_numeric(ew_StrToFloat($this->importeMovimientos->CurrentValue)))
			$this->importeMovimientos->CurrentValue = ew_StrToFloat($this->importeMovimientos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeAdelantos->FormValue == $this->importeAdelantos->CurrentValue && is_numeric(ew_StrToFloat($this->importeAdelantos->CurrentValue)))
			$this->importeAdelantos->CurrentValue = ew_StrToFloat($this->importeAdelantos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// tipoFlujo
		// fecha
		// idTercero
		// importe
		// importeMovimientos
		// importeAdelantos
		// valorDolar

		$this->valorDolar->CellCssStyle = "white-space: nowrap;";

		// nroComprobante
		// contable
		// idEstado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// tipoFlujo
		if (strval($this->tipoFlujo->CurrentValue) <> "") {
			$this->tipoFlujo->ViewValue = $this->tipoFlujo->OptionCaption($this->tipoFlujo->CurrentValue);
		} else {
			$this->tipoFlujo->ViewValue = NULL;
		}
		$this->tipoFlujo->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// importe
		$this->importe->ViewValue = $this->importe->CurrentValue;
		$this->importe->ViewCustomAttributes = "";

		// importeMovimientos
		$this->importeMovimientos->ViewValue = $this->importeMovimientos->CurrentValue;
		$this->importeMovimientos->ViewCustomAttributes = "";

		// importeAdelantos
		$this->importeAdelantos->ViewValue = $this->importeAdelantos->CurrentValue;
		$this->importeAdelantos->ViewCustomAttributes = "";

		// nroComprobante
		$this->nroComprobante->ViewValue = $this->nroComprobante->CurrentValue;
		$this->nroComprobante->ViewCustomAttributes = "";

			// tipoFlujo
			$this->tipoFlujo->LinkCustomAttributes = "";
			$this->tipoFlujo->HrefValue = "";
			$this->tipoFlujo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// importe
			$this->importe->LinkCustomAttributes = "";
			$this->importe->HrefValue = "";
			$this->importe->TooltipValue = "";

			// importeMovimientos
			$this->importeMovimientos->LinkCustomAttributes = "";
			$this->importeMovimientos->HrefValue = "";
			$this->importeMovimientos->TooltipValue = "";

			// importeAdelantos
			$this->importeAdelantos->LinkCustomAttributes = "";
			$this->importeAdelantos->HrefValue = "";
			$this->importeAdelantos->TooltipValue = "";

			// nroComprobante
			$this->nroComprobante->LinkCustomAttributes = "";
			$this->nroComprobante->HrefValue = "";
			$this->nroComprobante->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// tipoFlujo
			$this->tipoFlujo->EditAttrs["class"] = "form-control";
			$this->tipoFlujo->EditCustomAttributes = "";
			$this->tipoFlujo->EditValue = $this->tipoFlujo->Options(TRUE);

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = "";
			if (trim(strval($this->idTercero->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;

			// importe
			$this->importe->EditAttrs["class"] = "form-control";
			$this->importe->EditCustomAttributes = "";
			$this->importe->EditValue = ew_HtmlEncode($this->importe->AdvancedSearch->SearchValue);
			$this->importe->PlaceHolder = ew_RemoveHtml($this->importe->FldCaption());

			// importeMovimientos
			$this->importeMovimientos->EditAttrs["class"] = "form-control";
			$this->importeMovimientos->EditCustomAttributes = "";
			$this->importeMovimientos->EditValue = ew_HtmlEncode($this->importeMovimientos->AdvancedSearch->SearchValue);
			$this->importeMovimientos->PlaceHolder = ew_RemoveHtml($this->importeMovimientos->FldCaption());

			// importeAdelantos
			$this->importeAdelantos->EditAttrs["class"] = "form-control";
			$this->importeAdelantos->EditCustomAttributes = "";
			$this->importeAdelantos->EditValue = ew_HtmlEncode($this->importeAdelantos->AdvancedSearch->SearchValue);
			$this->importeAdelantos->PlaceHolder = ew_RemoveHtml($this->importeAdelantos->FldCaption());

			// nroComprobante
			$this->nroComprobante->EditAttrs["class"] = "form-control";
			$this->nroComprobante->EditCustomAttributes = "";
			$this->nroComprobante->EditValue = ew_HtmlEncode($this->nroComprobante->AdvancedSearch->SearchValue);
			$this->nroComprobante->PlaceHolder = ew_RemoveHtml($this->nroComprobante->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckDateDef($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fecha->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->tipoFlujo->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->importe->AdvancedSearch->Load();
		$this->importeMovimientos->AdvancedSearch->Load();
		$this->importeAdelantos->AdvancedSearch->Load();
		$this->valorDolar->AdvancedSearch->Load();
		$this->nroComprobante->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->idEstado->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_recibos2Dpagos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_recibos2Dpagos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.frecibos2Dpagoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
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
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
			}
		} 
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		} 
	}

	// Page Load event
	function Page_Load() {
		$this->AddUrl='recibosaddcustom.php';	
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
	?>
	<!-- Modal -->
	<div class="modal fade" id="detalle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Detalle</h4>
		  </div>
		  <div class="modal-body">
			<table class="table table-bordered">
				<thead>
					<tr>
					  <th>Medio</th>
					  <th>Importe</th>
					  <th>Banco</th>
					  <th>Número</th>
					  <th>Fecha</th>
					  <th>Código</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
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
			$opt = &$this->ListOptions->Add("auditar");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
			$opt->MoveTo(0); // Move to first column
			$opt->ShowInButtonGroup = FALSE;
			$opt->ShowInDropDown = FALSE;
			$opt = &$this->ListOptions->Add("detalle");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
			$opt->MoveTo(1); // Move to first column
			$opt->ShowInButtonGroup = FALSE;
			$opt->ShowInDropDown = FALSE;
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {
					if($this->Recordset->fields["idEstado"]!=1){
						$this->ListOptions->Items["auditar"]->Body = '';
						$this->ListOptions->Items["edit"]->Body='';
						$this->ListOptions->Items["delete"]->Body='';
					}else{
						$this->ListOptions->Items["auditar"]->Body = '<div class="btn-group"><a onclick="auditarrecibo('.$this->id->DbValue.')" class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Auditar" href="#"><span class="glyphicon glyphicon-ok-circle ewIcon" aria-hidden="true"></span></a></div>';
						$this->ListOptions->Items["edit"]->Body='<a class="ewRowLink ewEdit" title="Modificar" data-caption="Modificar" href="reciboseditcustom.php?id='.$this->id->DbValue.'"><span data-phrase="EditLink" class="icon-edit ewIcon" data-caption="Modificar"></span></a>';	
					};
	$this->ListOptions->Items["detalle"]->Body = '<div class="btn-group"><a onclick="detallerecibo('.$this->id->DbValue.')" class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Detalle" href="#"><span class="glyphicon glyphicon-list-alt ewIcon" aria-hidden="true"></span></a></div>';
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
if (!isset($recibos2Dpagos_list)) $recibos2Dpagos_list = new crecibos2Dpagos_list();

// Page init
$recibos2Dpagos_list->Page_Init();

// Page main
$recibos2Dpagos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$recibos2Dpagos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($recibos2Dpagos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = frecibos2Dpagoslist = new ew_Form("frecibos2Dpagoslist", "list");
frecibos2Dpagoslist.FormKeyCountName = '<?php echo $recibos2Dpagos_list->FormKeyCountName ?>';

// Form_CustomValidate event
frecibos2Dpagoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecibos2Dpagoslist.ValidateRequired = true;
<?php } else { ?>
frecibos2Dpagoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecibos2Dpagoslist.Lists["x_tipoFlujo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frecibos2Dpagoslist.Lists["x_tipoFlujo"].Options = <?php echo json_encode($recibos2Dpagos->tipoFlujo->Options()) ?>;
frecibos2Dpagoslist.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
var CurrentSearchForm = frecibos2Dpagoslistsrch = new ew_Form("frecibos2Dpagoslistsrch");

// Validate function for search
frecibos2Dpagoslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->fecha->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
frecibos2Dpagoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecibos2Dpagoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frecibos2Dpagoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
frecibos2Dpagoslistsrch.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Init search panel as collapsed
if (frecibos2Dpagoslistsrch) frecibos2Dpagoslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($recibos2Dpagos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($recibos2Dpagos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($recibos2Dpagos_list->TotalRecs > 0 && $recibos2Dpagos_list->ExportOptions->Visible()) { ?>
<?php $recibos2Dpagos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($recibos2Dpagos_list->SearchOptions->Visible()) { ?>
<?php $recibos2Dpagos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($recibos2Dpagos_list->FilterOptions->Visible()) { ?>
<?php $recibos2Dpagos_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($recibos2Dpagos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $recibos2Dpagos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($recibos2Dpagos_list->TotalRecs <= 0)
			$recibos2Dpagos_list->TotalRecs = $recibos2Dpagos->SelectRecordCount();
	} else {
		if (!$recibos2Dpagos_list->Recordset && ($recibos2Dpagos_list->Recordset = $recibos2Dpagos_list->LoadRecordset()))
			$recibos2Dpagos_list->TotalRecs = $recibos2Dpagos_list->Recordset->RecordCount();
	}
	$recibos2Dpagos_list->StartRec = 1;
	if ($recibos2Dpagos_list->DisplayRecs <= 0 || ($recibos2Dpagos->Export <> "" && $recibos2Dpagos->ExportAll)) // Display all records
		$recibos2Dpagos_list->DisplayRecs = $recibos2Dpagos_list->TotalRecs;
	if (!($recibos2Dpagos->Export <> "" && $recibos2Dpagos->ExportAll))
		$recibos2Dpagos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$recibos2Dpagos_list->Recordset = $recibos2Dpagos_list->LoadRecordset($recibos2Dpagos_list->StartRec-1, $recibos2Dpagos_list->DisplayRecs);

	// Set no record found message
	if ($recibos2Dpagos->CurrentAction == "" && $recibos2Dpagos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$recibos2Dpagos_list->setWarningMessage(ew_DeniedMsg());
		if ($recibos2Dpagos_list->SearchWhere == "0=101")
			$recibos2Dpagos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$recibos2Dpagos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$recibos2Dpagos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($recibos2Dpagos->Export == "" && $recibos2Dpagos->CurrentAction == "") { ?>
<form name="frecibos2Dpagoslistsrch" id="frecibos2Dpagoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($recibos2Dpagos_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="frecibos2Dpagoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="recibos2Dpagos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$recibos2Dpagos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$recibos2Dpagos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$recibos2Dpagos->ResetAttrs();
$recibos2Dpagos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($recibos2Dpagos->fecha->Visible) { // fecha ?>
	<div id="xsc_fecha" class="ewCell form-group">
		<label for="x_fecha" class="ewSearchCaption ewLabel"><?php echo $recibos2Dpagos->fecha->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fecha" id="z_fecha" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="recibos2Dpagos" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->fecha->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->fecha->EditValue ?>"<?php echo $recibos2Dpagos->fecha->EditAttributes() ?>>
<?php if (!$recibos2Dpagos->fecha->ReadOnly && !$recibos2Dpagos->fecha->Disabled && !isset($recibos2Dpagos->fecha->EditAttrs["readonly"]) && !isset($recibos2Dpagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("frecibos2Dpagoslistsrch", "x_fecha", 0);
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha">
<input type="text" data-table="recibos2Dpagos" data-field="x_fecha" name="y_fecha" id="y_fecha" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->fecha->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->fecha->EditValue2 ?>"<?php echo $recibos2Dpagos->fecha->EditAttributes() ?>>
<?php if (!$recibos2Dpagos->fecha->ReadOnly && !$recibos2Dpagos->fecha->Disabled && !isset($recibos2Dpagos->fecha->EditAttrs["readonly"]) && !isset($recibos2Dpagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("frecibos2Dpagoslistsrch", "y_fecha", 0);
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($recibos2Dpagos->idTercero->Visible) { // idTercero ?>
	<div id="xsc_idTercero" class="ewCell form-group">
		<label for="x_idTercero" class="ewSearchCaption ewLabel"><?php echo $recibos2Dpagos->idTercero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTercero" id="z_idTercero" value="="></span>
		<span class="ewSearchField">
<select data-table="recibos2Dpagos" data-field="x_idTercero" data-value-separator="<?php echo $recibos2Dpagos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $recibos2Dpagos->idTercero->EditAttributes() ?>>
<?php echo $recibos2Dpagos->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $recibos2Dpagos->idTercero->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($recibos2Dpagos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($recibos2Dpagos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $recibos2Dpagos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($recibos2Dpagos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($recibos2Dpagos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($recibos2Dpagos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($recibos2Dpagos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $recibos2Dpagos_list->ShowPageHeader(); ?>
<?php
$recibos2Dpagos_list->ShowMessage();
?>
<?php if ($recibos2Dpagos_list->TotalRecs > 0 || $recibos2Dpagos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid recibos2Dpagos">
<?php if ($recibos2Dpagos->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($recibos2Dpagos->CurrentAction <> "gridadd" && $recibos2Dpagos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($recibos2Dpagos_list->Pager)) $recibos2Dpagos_list->Pager = new cPrevNextPager($recibos2Dpagos_list->StartRec, $recibos2Dpagos_list->DisplayRecs, $recibos2Dpagos_list->TotalRecs) ?>
<?php if ($recibos2Dpagos_list->Pager->RecordCount > 0 && $recibos2Dpagos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($recibos2Dpagos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $recibos2Dpagos_list->PageUrl() ?>start=<?php echo $recibos2Dpagos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($recibos2Dpagos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $recibos2Dpagos_list->PageUrl() ?>start=<?php echo $recibos2Dpagos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $recibos2Dpagos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($recibos2Dpagos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $recibos2Dpagos_list->PageUrl() ?>start=<?php echo $recibos2Dpagos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($recibos2Dpagos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $recibos2Dpagos_list->PageUrl() ?>start=<?php echo $recibos2Dpagos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $recibos2Dpagos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $recibos2Dpagos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $recibos2Dpagos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $recibos2Dpagos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($recibos2Dpagos_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="recibos2Dpagos">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($recibos2Dpagos_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($recibos2Dpagos_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($recibos2Dpagos_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($recibos2Dpagos_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($recibos2Dpagos_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($recibos2Dpagos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="frecibos2Dpagoslist" id="frecibos2Dpagoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($recibos2Dpagos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $recibos2Dpagos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="recibos2Dpagos">
<div id="gmp_recibos2Dpagos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($recibos2Dpagos_list->TotalRecs > 0) { ?>
<table id="tbl_recibos2Dpagoslist" class="table ewTable">
<?php echo $recibos2Dpagos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$recibos2Dpagos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$recibos2Dpagos_list->RenderListOptions();

// Render list options (header, left)
$recibos2Dpagos_list->ListOptions->Render("header", "left");
?>
<?php if ($recibos2Dpagos->tipoFlujo->Visible) { // tipoFlujo ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->tipoFlujo) == "") { ?>
		<th data-name="tipoFlujo"><div id="elh_recibos2Dpagos_tipoFlujo" class="recibos2Dpagos_tipoFlujo"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->tipoFlujo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoFlujo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->tipoFlujo) ?>',2);"><div id="elh_recibos2Dpagos_tipoFlujo" class="recibos2Dpagos_tipoFlujo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->tipoFlujo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->tipoFlujo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->tipoFlujo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->fecha->Visible) { // fecha ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_recibos2Dpagos_fecha" class="recibos2Dpagos_fecha"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->fecha) ?>',2);"><div id="elh_recibos2Dpagos_fecha" class="recibos2Dpagos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->idTercero->Visible) { // idTercero ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_recibos2Dpagos_idTercero" class="recibos2Dpagos_idTercero"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->idTercero) ?>',2);"><div id="elh_recibos2Dpagos_idTercero" class="recibos2Dpagos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->importe->Visible) { // importe ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->importe) == "") { ?>
		<th data-name="importe"><div id="elh_recibos2Dpagos_importe" class="recibos2Dpagos_importe"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->importe) ?>',2);"><div id="elh_recibos2Dpagos_importe" class="recibos2Dpagos_importe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->importe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->importe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->importeMovimientos->Visible) { // importeMovimientos ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->importeMovimientos) == "") { ?>
		<th data-name="importeMovimientos"><div id="elh_recibos2Dpagos_importeMovimientos" class="recibos2Dpagos_importeMovimientos"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importeMovimientos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeMovimientos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->importeMovimientos) ?>',2);"><div id="elh_recibos2Dpagos_importeMovimientos" class="recibos2Dpagos_importeMovimientos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importeMovimientos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->importeMovimientos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->importeMovimientos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->importeAdelantos->Visible) { // importeAdelantos ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->importeAdelantos) == "") { ?>
		<th data-name="importeAdelantos"><div id="elh_recibos2Dpagos_importeAdelantos" class="recibos2Dpagos_importeAdelantos"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importeAdelantos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeAdelantos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->importeAdelantos) ?>',2);"><div id="elh_recibos2Dpagos_importeAdelantos" class="recibos2Dpagos_importeAdelantos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->importeAdelantos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->importeAdelantos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->importeAdelantos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos2Dpagos->nroComprobante->Visible) { // nroComprobante ?>
	<?php if ($recibos2Dpagos->SortUrl($recibos2Dpagos->nroComprobante) == "") { ?>
		<th data-name="nroComprobante"><div id="elh_recibos2Dpagos_nroComprobante" class="recibos2Dpagos_nroComprobante"><div class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->nroComprobante->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nroComprobante"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos2Dpagos->SortUrl($recibos2Dpagos->nroComprobante) ?>',2);"><div id="elh_recibos2Dpagos_nroComprobante" class="recibos2Dpagos_nroComprobante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos2Dpagos->nroComprobante->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($recibos2Dpagos->nroComprobante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos2Dpagos->nroComprobante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$recibos2Dpagos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($recibos2Dpagos->ExportAll && $recibos2Dpagos->Export <> "") {
	$recibos2Dpagos_list->StopRec = $recibos2Dpagos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($recibos2Dpagos_list->TotalRecs > $recibos2Dpagos_list->StartRec + $recibos2Dpagos_list->DisplayRecs - 1)
		$recibos2Dpagos_list->StopRec = $recibos2Dpagos_list->StartRec + $recibos2Dpagos_list->DisplayRecs - 1;
	else
		$recibos2Dpagos_list->StopRec = $recibos2Dpagos_list->TotalRecs;
}
$recibos2Dpagos_list->RecCnt = $recibos2Dpagos_list->StartRec - 1;
if ($recibos2Dpagos_list->Recordset && !$recibos2Dpagos_list->Recordset->EOF) {
	$recibos2Dpagos_list->Recordset->MoveFirst();
	$bSelectLimit = $recibos2Dpagos_list->UseSelectLimit;
	if (!$bSelectLimit && $recibos2Dpagos_list->StartRec > 1)
		$recibos2Dpagos_list->Recordset->Move($recibos2Dpagos_list->StartRec - 1);
} elseif (!$recibos2Dpagos->AllowAddDeleteRow && $recibos2Dpagos_list->StopRec == 0) {
	$recibos2Dpagos_list->StopRec = $recibos2Dpagos->GridAddRowCount;
}

// Initialize aggregate
$recibos2Dpagos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$recibos2Dpagos->ResetAttrs();
$recibos2Dpagos_list->RenderRow();
while ($recibos2Dpagos_list->RecCnt < $recibos2Dpagos_list->StopRec) {
	$recibos2Dpagos_list->RecCnt++;
	if (intval($recibos2Dpagos_list->RecCnt) >= intval($recibos2Dpagos_list->StartRec)) {
		$recibos2Dpagos_list->RowCnt++;

		// Set up key count
		$recibos2Dpagos_list->KeyCount = $recibos2Dpagos_list->RowIndex;

		// Init row class and style
		$recibos2Dpagos->ResetAttrs();
		$recibos2Dpagos->CssClass = "";
		if ($recibos2Dpagos->CurrentAction == "gridadd") {
		} else {
			$recibos2Dpagos_list->LoadRowValues($recibos2Dpagos_list->Recordset); // Load row values
		}
		$recibos2Dpagos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$recibos2Dpagos->RowAttrs = array_merge($recibos2Dpagos->RowAttrs, array('data-rowindex'=>$recibos2Dpagos_list->RowCnt, 'id'=>'r' . $recibos2Dpagos_list->RowCnt . '_recibos2Dpagos', 'data-rowtype'=>$recibos2Dpagos->RowType));

		// Render row
		$recibos2Dpagos_list->RenderRow();

		// Render list options
		$recibos2Dpagos_list->RenderListOptions();
?>
	<tr<?php echo $recibos2Dpagos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$recibos2Dpagos_list->ListOptions->Render("body", "left", $recibos2Dpagos_list->RowCnt);
?>
	<?php if ($recibos2Dpagos->tipoFlujo->Visible) { // tipoFlujo ?>
		<td data-name="tipoFlujo"<?php echo $recibos2Dpagos->tipoFlujo->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_tipoFlujo" class="recibos2Dpagos_tipoFlujo">
<span<?php echo $recibos2Dpagos->tipoFlujo->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->tipoFlujo->ListViewValue() ?></span>
</span>
<a id="<?php echo $recibos2Dpagos_list->PageObjName . "_row_" . $recibos2Dpagos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($recibos2Dpagos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $recibos2Dpagos->fecha->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_fecha" class="recibos2Dpagos_fecha">
<span<?php echo $recibos2Dpagos->fecha->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($recibos2Dpagos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $recibos2Dpagos->idTercero->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_idTercero" class="recibos2Dpagos_idTercero">
<span<?php echo $recibos2Dpagos->idTercero->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->idTercero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($recibos2Dpagos->importe->Visible) { // importe ?>
		<td data-name="importe"<?php echo $recibos2Dpagos->importe->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_importe" class="recibos2Dpagos_importe">
<span<?php echo $recibos2Dpagos->importe->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->importe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($recibos2Dpagos->importeMovimientos->Visible) { // importeMovimientos ?>
		<td data-name="importeMovimientos"<?php echo $recibos2Dpagos->importeMovimientos->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_importeMovimientos" class="recibos2Dpagos_importeMovimientos">
<span<?php echo $recibos2Dpagos->importeMovimientos->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->importeMovimientos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($recibos2Dpagos->importeAdelantos->Visible) { // importeAdelantos ?>
		<td data-name="importeAdelantos"<?php echo $recibos2Dpagos->importeAdelantos->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_importeAdelantos" class="recibos2Dpagos_importeAdelantos">
<span<?php echo $recibos2Dpagos->importeAdelantos->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->importeAdelantos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($recibos2Dpagos->nroComprobante->Visible) { // nroComprobante ?>
		<td data-name="nroComprobante"<?php echo $recibos2Dpagos->nroComprobante->CellAttributes() ?>>
<span id="el<?php echo $recibos2Dpagos_list->RowCnt ?>_recibos2Dpagos_nroComprobante" class="recibos2Dpagos_nroComprobante">
<span<?php echo $recibos2Dpagos->nroComprobante->ViewAttributes() ?>>
<?php echo $recibos2Dpagos->nroComprobante->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$recibos2Dpagos_list->ListOptions->Render("body", "right", $recibos2Dpagos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($recibos2Dpagos->CurrentAction <> "gridadd")
		$recibos2Dpagos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($recibos2Dpagos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($recibos2Dpagos_list->Recordset)
	$recibos2Dpagos_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($recibos2Dpagos_list->TotalRecs == 0 && $recibos2Dpagos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($recibos2Dpagos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($recibos2Dpagos->Export == "") { ?>
<script type="text/javascript">
frecibos2Dpagoslistsrch.FilterList = <?php echo $recibos2Dpagos_list->GetFilterList() ?>;
frecibos2Dpagoslistsrch.Init();
frecibos2Dpagoslist.Init();
</script>
<?php } ?>
<?php
$recibos2Dpagos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($recibos2Dpagos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$recibos2Dpagos_list->Page_Terminate();
?>
