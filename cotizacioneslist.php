<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "cotizacionesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cotizaciones_list = NULL; // Initialize page object first

class ccotizaciones_list extends ccotizaciones {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'cotizaciones';

	// Page object name
	var $PageObjName = 'cotizaciones_list';

	// Grid form hidden field names
	var $FormName = 'fcotizacioneslist';
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

		// Table object (cotizaciones)
		if (!isset($GLOBALS["cotizaciones"]) || get_class($GLOBALS["cotizaciones"]) == "ccotizaciones") {
			$GLOBALS["cotizaciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cotizaciones"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cotizacionesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cotizacionesdelete.php";
		$this->MultiUpdateUrl = "cotizacionesupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cotizaciones', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fcotizacioneslistsrch";

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
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->idTercero->SetVisibility();
		$this->fecha->SetVisibility();
		$this->contable->SetVisibility();
		$this->importeNeto->SetVisibility();
		$this->importeIva->SetVisibility();
		$this->importeTotal->SetVisibility();
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
		global $EW_EXPORT, $cotizaciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cotizaciones);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fcotizacioneslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->idTercero->AdvancedSearch->ToJSON(), ","); // Field idTercero
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->contable->AdvancedSearch->ToJSON(), ","); // Field contable
		$sFilterList = ew_Concat($sFilterList, $this->importeNeto->AdvancedSearch->ToJSON(), ","); // Field importeNeto
		$sFilterList = ew_Concat($sFilterList, $this->importeIva->AdvancedSearch->ToJSON(), ","); // Field importeIva
		$sFilterList = ew_Concat($sFilterList, $this->importeTotal->AdvancedSearch->ToJSON(), ","); // Field importeTotal
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJSON(), ","); // Field estado
		$sFilterList = ew_Concat($sFilterList, $this->vigencia->AdvancedSearch->ToJSON(), ","); // Field vigencia
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fcotizacioneslistsrch", $filters);
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

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field idTercero
		$this->idTercero->AdvancedSearch->SearchValue = @$filter["x_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator = @$filter["z_idTercero"];
		$this->idTercero->AdvancedSearch->SearchCondition = @$filter["v_idTercero"];
		$this->idTercero->AdvancedSearch->SearchValue2 = @$filter["y_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator2 = @$filter["w_idTercero"];
		$this->idTercero->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field contable
		$this->contable->AdvancedSearch->SearchValue = @$filter["x_contable"];
		$this->contable->AdvancedSearch->SearchOperator = @$filter["z_contable"];
		$this->contable->AdvancedSearch->SearchCondition = @$filter["v_contable"];
		$this->contable->AdvancedSearch->SearchValue2 = @$filter["y_contable"];
		$this->contable->AdvancedSearch->SearchOperator2 = @$filter["w_contable"];
		$this->contable->AdvancedSearch->Save();

		// Field importeNeto
		$this->importeNeto->AdvancedSearch->SearchValue = @$filter["x_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchOperator = @$filter["z_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchCondition = @$filter["v_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchValue2 = @$filter["y_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchOperator2 = @$filter["w_importeNeto"];
		$this->importeNeto->AdvancedSearch->Save();

		// Field importeIva
		$this->importeIva->AdvancedSearch->SearchValue = @$filter["x_importeIva"];
		$this->importeIva->AdvancedSearch->SearchOperator = @$filter["z_importeIva"];
		$this->importeIva->AdvancedSearch->SearchCondition = @$filter["v_importeIva"];
		$this->importeIva->AdvancedSearch->SearchValue2 = @$filter["y_importeIva"];
		$this->importeIva->AdvancedSearch->SearchOperator2 = @$filter["w_importeIva"];
		$this->importeIva->AdvancedSearch->Save();

		// Field importeTotal
		$this->importeTotal->AdvancedSearch->SearchValue = @$filter["x_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator = @$filter["z_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchCondition = @$filter["v_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchValue2 = @$filter["y_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator2 = @$filter["w_importeTotal"];
		$this->importeTotal->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();

		// Field vigencia
		$this->vigencia->AdvancedSearch->SearchValue = @$filter["x_vigencia"];
		$this->vigencia->AdvancedSearch->SearchOperator = @$filter["z_vigencia"];
		$this->vigencia->AdvancedSearch->SearchCondition = @$filter["v_vigencia"];
		$this->vigencia->AdvancedSearch->SearchValue2 = @$filter["y_vigencia"];
		$this->vigencia->AdvancedSearch->SearchOperator2 = @$filter["w_vigencia"];
		$this->vigencia->AdvancedSearch->Save();
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->idTercero, $Default, FALSE); // idTercero
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->contable, $Default, FALSE); // contable
		$this->BuildSearchSql($sWhere, $this->importeNeto, $Default, FALSE); // importeNeto
		$this->BuildSearchSql($sWhere, $this->importeIva, $Default, FALSE); // importeIva
		$this->BuildSearchSql($sWhere, $this->importeTotal, $Default, FALSE); // importeTotal
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado
		$this->BuildSearchSql($sWhere, $this->vigencia, $Default, FALSE); // vigencia

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->idTercero->AdvancedSearch->Save(); // idTercero
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->contable->AdvancedSearch->Save(); // contable
			$this->importeNeto->AdvancedSearch->Save(); // importeNeto
			$this->importeIva->AdvancedSearch->Save(); // importeIva
			$this->importeTotal->AdvancedSearch->Save(); // importeTotal
			$this->estado->AdvancedSearch->Save(); // estado
			$this->vigencia->AdvancedSearch->Save(); // vigencia
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

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contable->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeNeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeIva->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeTotal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vigencia->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->idTercero->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->contable->AdvancedSearch->UnsetSession();
		$this->importeNeto->AdvancedSearch->UnsetSession();
		$this->importeIva->AdvancedSearch->UnsetSession();
		$this->importeTotal->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
		$this->vigencia->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->importeNeto->AdvancedSearch->Load();
		$this->importeIva->AdvancedSearch->Load();
		$this->importeTotal->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->vigencia->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->idTercero, $bCtrl); // idTercero
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->contable, $bCtrl); // contable
			$this->UpdateSort($this->importeNeto, $bCtrl); // importeNeto
			$this->UpdateSort($this->importeIva, $bCtrl); // importeIva
			$this->UpdateSort($this->importeTotal, $bCtrl); // importeTotal
			$this->UpdateSort($this->vigencia, $bCtrl); // vigencia
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
				$this->id->setSort("DESC");
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
				$this->id->setSort("");
				$this->idTercero->setSort("");
				$this->fecha->setSort("");
				$this->contable->setSort("");
				$this->importeNeto->setSort("");
				$this->importeIva->setSort("");
				$this->importeTotal->setSort("");
				$this->vigencia->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

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
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fcotizacioneslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fcotizacioneslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fcotizacioneslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcotizacioneslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// idTercero
		$this->idTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTercero"]);
		if ($this->idTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTercero->AdvancedSearch->SearchOperator = @$_GET["z_idTercero"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];

		// contable
		$this->contable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contable"]);
		if ($this->contable->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contable->AdvancedSearch->SearchOperator = @$_GET["z_contable"];
		if (is_array($this->contable->AdvancedSearch->SearchValue)) $this->contable->AdvancedSearch->SearchValue = implode(",", $this->contable->AdvancedSearch->SearchValue);
		if (is_array($this->contable->AdvancedSearch->SearchValue2)) $this->contable->AdvancedSearch->SearchValue2 = implode(",", $this->contable->AdvancedSearch->SearchValue2);

		// importeNeto
		$this->importeNeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeNeto"]);
		if ($this->importeNeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeNeto->AdvancedSearch->SearchOperator = @$_GET["z_importeNeto"];

		// importeIva
		$this->importeIva->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeIva"]);
		if ($this->importeIva->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeIva->AdvancedSearch->SearchOperator = @$_GET["z_importeIva"];

		// importeTotal
		$this->importeTotal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeTotal"]);
		if ($this->importeTotal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeTotal->AdvancedSearch->SearchOperator = @$_GET["z_importeTotal"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];

		// vigencia
		$this->vigencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vigencia"]);
		if ($this->vigencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vigencia->AdvancedSearch->SearchOperator = @$_GET["z_vigencia"];
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
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->contable->setDbValue($rs->fields('contable'));
		$this->importeNeto->setDbValue($rs->fields('importeNeto'));
		$this->importeIva->setDbValue($rs->fields('importeIva'));
		$this->importeTotal->setDbValue($rs->fields('importeTotal'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->vigencia->setDbValue($rs->fields('vigencia'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->fecha->DbValue = $row['fecha'];
		$this->contable->DbValue = $row['contable'];
		$this->importeNeto->DbValue = $row['importeNeto'];
		$this->importeIva->DbValue = $row['importeIva'];
		$this->importeTotal->DbValue = $row['importeTotal'];
		$this->estado->DbValue = $row['estado'];
		$this->vigencia->DbValue = $row['vigencia'];
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
		if ($this->importeNeto->FormValue == $this->importeNeto->CurrentValue && is_numeric(ew_StrToFloat($this->importeNeto->CurrentValue)))
			$this->importeNeto->CurrentValue = ew_StrToFloat($this->importeNeto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeIva->FormValue == $this->importeIva->CurrentValue && is_numeric(ew_StrToFloat($this->importeIva->CurrentValue)))
			$this->importeIva->CurrentValue = ew_StrToFloat($this->importeIva->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeTotal->FormValue == $this->importeTotal->CurrentValue && is_numeric(ew_StrToFloat($this->importeTotal->CurrentValue)))
			$this->importeTotal->CurrentValue = ew_StrToFloat($this->importeTotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idTercero
		// fecha
		// contable
		// importeNeto
		// importeIva
		// importeTotal
		// estado

		$this->estado->CellCssStyle = "white-space: nowrap;";

		// vigencia
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// contable
		if (strval($this->contable->CurrentValue) <> "") {
			$this->contable->ViewValue = "";
			$arwrk = explode(",", strval($this->contable->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->contable->ViewValue .= $this->contable->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->contable->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->contable->ViewValue = NULL;
		}
		$this->contable->ViewCustomAttributes = "";

		// importeNeto
		$this->importeNeto->ViewValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->ViewCustomAttributes = "";

		// importeIva
		$this->importeIva->ViewValue = $this->importeIva->CurrentValue;
		$this->importeIva->ViewCustomAttributes = "";

		// importeTotal
		$this->importeTotal->ViewValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// vigencia
		$this->vigencia->ViewValue = $this->vigencia->CurrentValue;
		$this->vigencia->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";
			$this->contable->TooltipValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";
			$this->importeNeto->TooltipValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";
			$this->importeIva->TooltipValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";
			$this->importeTotal->TooltipValue = "";

			// vigencia
			$this->vigencia->LinkCustomAttributes = "";
			$this->vigencia->HrefValue = "";
			$this->vigencia->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

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

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// contable
			$this->contable->EditCustomAttributes = "";
			$this->contable->EditValue = $this->contable->Options(FALSE);

			// importeNeto
			$this->importeNeto->EditAttrs["class"] = "form-control";
			$this->importeNeto->EditCustomAttributes = "";
			$this->importeNeto->EditValue = ew_HtmlEncode($this->importeNeto->AdvancedSearch->SearchValue);
			$this->importeNeto->PlaceHolder = ew_RemoveHtml($this->importeNeto->FldCaption());

			// importeIva
			$this->importeIva->EditAttrs["class"] = "form-control";
			$this->importeIva->EditCustomAttributes = "";
			$this->importeIva->EditValue = ew_HtmlEncode($this->importeIva->AdvancedSearch->SearchValue);
			$this->importeIva->PlaceHolder = ew_RemoveHtml($this->importeIva->FldCaption());

			// importeTotal
			$this->importeTotal->EditAttrs["class"] = "form-control";
			$this->importeTotal->EditCustomAttributes = "";
			$this->importeTotal->EditValue = ew_HtmlEncode($this->importeTotal->AdvancedSearch->SearchValue);
			$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());

			// vigencia
			$this->vigencia->EditAttrs["class"] = "form-control";
			$this->vigencia->EditCustomAttributes = "";
			$this->vigencia->EditValue = ew_HtmlEncode($this->vigencia->AdvancedSearch->SearchValue);
			$this->vigencia->PlaceHolder = ew_RemoveHtml($this->vigencia->FldCaption());
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
		$this->id->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->importeNeto->AdvancedSearch->Load();
		$this->importeIva->AdvancedSearch->Load();
		$this->importeTotal->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->vigencia->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_cotizaciones\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_cotizaciones',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fcotizacioneslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$this->AddUrl='modulocotizacion2.php';	
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column
		//Auditar

			$opt = &$this->ListOptions->Add("auditar");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
			$opt->MoveTo(0); // Move to first column
			$opt->ShowInButtonGroup = FALSE;
			$opt->ShowInDropDown = FALSE;
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

					if($this->Recordset->fields["estado"]==1){

					//Está Auditado
						$this->ListOptions->Items["auditar"]->Body = '';
						$this->ListOptions->Items["edit"]->Body='';
						$this->ListOptions->Items["delete"]->Body='';
					}else{

					//No está Auditado
						$this->ListOptions->Items["auditar"]->Body = '<div class="btn-group"><a onclick="auditarCotizacion('.$this->id->DbValue.')" class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Auditar" href="#"><span class="glyphicon glyphicon-ok-circle ewIcon" aria-hidden="true"></span></a></div>';
						$this->ListOptions->Items["edit"]->Body='<a class="ewRowLink ewEdit" title="Modificar" data-caption="Modificar" href="modulocotizacion2.php?id='.$this->id->DbValue.'"><span data-phrase="EditLink" class="icon-edit ewIcon" data-caption="Modificar"></span></a>';		
					};
		$this->ListOptions->Items["view"]->Body='<a class="ewRowLink ewView" title="Ver" data-caption="Ver" href="imprimircotizacion.php?id='.$this->id->DbValue.'"><span class="icon-view ewIcon" data-caption="Ver"></span></a>';
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
if (!isset($cotizaciones_list)) $cotizaciones_list = new ccotizaciones_list();

// Page init
$cotizaciones_list->Page_Init();

// Page main
$cotizaciones_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cotizaciones_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($cotizaciones->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fcotizacioneslist = new ew_Form("fcotizacioneslist", "list");
fcotizacioneslist.FormKeyCountName = '<?php echo $cotizaciones_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcotizacioneslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcotizacioneslist.ValidateRequired = true;
<?php } else { ?>
fcotizacioneslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcotizacioneslist.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fcotizacioneslist.Lists["x_contable[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcotizacioneslist.Lists["x_contable[]"].Options = <?php echo json_encode($cotizaciones->contable->Options()) ?>;

// Form object for search
var CurrentSearchForm = fcotizacioneslistsrch = new ew_Form("fcotizacioneslistsrch");

// Validate function for search
fcotizacioneslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fcotizacioneslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcotizacioneslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fcotizacioneslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fcotizacioneslistsrch.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Init search panel as collapsed
if (fcotizacioneslistsrch) fcotizacioneslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($cotizaciones->Export == "") { ?>
<div class="ewToolbar">
<?php if ($cotizaciones->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($cotizaciones_list->TotalRecs > 0 && $cotizaciones_list->ExportOptions->Visible()) { ?>
<?php $cotizaciones_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($cotizaciones_list->SearchOptions->Visible()) { ?>
<?php $cotizaciones_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($cotizaciones_list->FilterOptions->Visible()) { ?>
<?php $cotizaciones_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($cotizaciones->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $cotizaciones_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($cotizaciones_list->TotalRecs <= 0)
			$cotizaciones_list->TotalRecs = $cotizaciones->SelectRecordCount();
	} else {
		if (!$cotizaciones_list->Recordset && ($cotizaciones_list->Recordset = $cotizaciones_list->LoadRecordset()))
			$cotizaciones_list->TotalRecs = $cotizaciones_list->Recordset->RecordCount();
	}
	$cotizaciones_list->StartRec = 1;
	if ($cotizaciones_list->DisplayRecs <= 0 || ($cotizaciones->Export <> "" && $cotizaciones->ExportAll)) // Display all records
		$cotizaciones_list->DisplayRecs = $cotizaciones_list->TotalRecs;
	if (!($cotizaciones->Export <> "" && $cotizaciones->ExportAll))
		$cotizaciones_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$cotizaciones_list->Recordset = $cotizaciones_list->LoadRecordset($cotizaciones_list->StartRec-1, $cotizaciones_list->DisplayRecs);

	// Set no record found message
	if ($cotizaciones->CurrentAction == "" && $cotizaciones_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$cotizaciones_list->setWarningMessage(ew_DeniedMsg());
		if ($cotizaciones_list->SearchWhere == "0=101")
			$cotizaciones_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$cotizaciones_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$cotizaciones_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($cotizaciones->Export == "" && $cotizaciones->CurrentAction == "") { ?>
<form name="fcotizacioneslistsrch" id="fcotizacioneslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($cotizaciones_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fcotizacioneslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cotizaciones">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$cotizaciones_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$cotizaciones->RowType = EW_ROWTYPE_SEARCH;

// Render row
$cotizaciones->ResetAttrs();
$cotizaciones_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($cotizaciones->idTercero->Visible) { // idTercero ?>
	<div id="xsc_idTercero" class="ewCell form-group">
		<label for="x_idTercero" class="ewSearchCaption ewLabel"><?php echo $cotizaciones->idTercero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTercero" id="z_idTercero" value="="></span>
		<span class="ewSearchField">
<select data-table="cotizaciones" data-field="x_idTercero" data-value-separator="<?php echo $cotizaciones->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $cotizaciones->idTercero->EditAttributes() ?>>
<?php echo $cotizaciones->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $cotizaciones->idTercero->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $cotizaciones_list->ShowPageHeader(); ?>
<?php
$cotizaciones_list->ShowMessage();
?>
<?php if ($cotizaciones_list->TotalRecs > 0 || $cotizaciones->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid cotizaciones">
<?php if ($cotizaciones->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($cotizaciones->CurrentAction <> "gridadd" && $cotizaciones->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($cotizaciones_list->Pager)) $cotizaciones_list->Pager = new cPrevNextPager($cotizaciones_list->StartRec, $cotizaciones_list->DisplayRecs, $cotizaciones_list->TotalRecs) ?>
<?php if ($cotizaciones_list->Pager->RecordCount > 0 && $cotizaciones_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($cotizaciones_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $cotizaciones_list->PageUrl() ?>start=<?php echo $cotizaciones_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($cotizaciones_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $cotizaciones_list->PageUrl() ?>start=<?php echo $cotizaciones_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $cotizaciones_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($cotizaciones_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $cotizaciones_list->PageUrl() ?>start=<?php echo $cotizaciones_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($cotizaciones_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $cotizaciones_list->PageUrl() ?>start=<?php echo $cotizaciones_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $cotizaciones_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $cotizaciones_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $cotizaciones_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $cotizaciones_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($cotizaciones_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="cotizaciones">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($cotizaciones_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($cotizaciones_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($cotizaciones_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($cotizaciones_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($cotizaciones_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cotizaciones_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fcotizacioneslist" id="fcotizacioneslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cotizaciones_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cotizaciones_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cotizaciones">
<div id="gmp_cotizaciones" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($cotizaciones_list->TotalRecs > 0) { ?>
<table id="tbl_cotizacioneslist" class="table ewTable">
<?php echo $cotizaciones->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$cotizaciones_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$cotizaciones_list->RenderListOptions();

// Render list options (header, left)
$cotizaciones_list->ListOptions->Render("header", "left");
?>
<?php if ($cotizaciones->id->Visible) { // id ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->id) == "") { ?>
		<th data-name="id"><div id="elh_cotizaciones_id" class="cotizaciones_id"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->id) ?>',2);"><div id="elh_cotizaciones_id" class="cotizaciones_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->idTercero->Visible) { // idTercero ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_cotizaciones_idTercero" class="cotizaciones_idTercero"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->idTercero) ?>',2);"><div id="elh_cotizaciones_idTercero" class="cotizaciones_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->fecha->Visible) { // fecha ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_cotizaciones_fecha" class="cotizaciones_fecha"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->fecha) ?>',2);"><div id="elh_cotizaciones_fecha" class="cotizaciones_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->contable->Visible) { // contable ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->contable) == "") { ?>
		<th data-name="contable"><div id="elh_cotizaciones_contable" class="cotizaciones_contable"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->contable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contable"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->contable) ?>',2);"><div id="elh_cotizaciones_contable" class="cotizaciones_contable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->contable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->contable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->contable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->importeNeto->Visible) { // importeNeto ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->importeNeto) == "") { ?>
		<th data-name="importeNeto"><div id="elh_cotizaciones_importeNeto" class="cotizaciones_importeNeto"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->importeNeto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeNeto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->importeNeto) ?>',2);"><div id="elh_cotizaciones_importeNeto" class="cotizaciones_importeNeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->importeNeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->importeNeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->importeNeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->importeIva->Visible) { // importeIva ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->importeIva) == "") { ?>
		<th data-name="importeIva"><div id="elh_cotizaciones_importeIva" class="cotizaciones_importeIva"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->importeIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->importeIva) ?>',2);"><div id="elh_cotizaciones_importeIva" class="cotizaciones_importeIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->importeIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->importeIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->importeIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->importeTotal->Visible) { // importeTotal ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->importeTotal) == "") { ?>
		<th data-name="importeTotal"><div id="elh_cotizaciones_importeTotal" class="cotizaciones_importeTotal"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->importeTotal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeTotal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->importeTotal) ?>',2);"><div id="elh_cotizaciones_importeTotal" class="cotizaciones_importeTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->importeTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->importeTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->importeTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cotizaciones->vigencia->Visible) { // vigencia ?>
	<?php if ($cotizaciones->SortUrl($cotizaciones->vigencia) == "") { ?>
		<th data-name="vigencia"><div id="elh_cotizaciones_vigencia" class="cotizaciones_vigencia"><div class="ewTableHeaderCaption"><?php echo $cotizaciones->vigencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vigencia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cotizaciones->SortUrl($cotizaciones->vigencia) ?>',2);"><div id="elh_cotizaciones_vigencia" class="cotizaciones_vigencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cotizaciones->vigencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cotizaciones->vigencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cotizaciones->vigencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$cotizaciones_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($cotizaciones->ExportAll && $cotizaciones->Export <> "") {
	$cotizaciones_list->StopRec = $cotizaciones_list->TotalRecs;
} else {

	// Set the last record to display
	if ($cotizaciones_list->TotalRecs > $cotizaciones_list->StartRec + $cotizaciones_list->DisplayRecs - 1)
		$cotizaciones_list->StopRec = $cotizaciones_list->StartRec + $cotizaciones_list->DisplayRecs - 1;
	else
		$cotizaciones_list->StopRec = $cotizaciones_list->TotalRecs;
}
$cotizaciones_list->RecCnt = $cotizaciones_list->StartRec - 1;
if ($cotizaciones_list->Recordset && !$cotizaciones_list->Recordset->EOF) {
	$cotizaciones_list->Recordset->MoveFirst();
	$bSelectLimit = $cotizaciones_list->UseSelectLimit;
	if (!$bSelectLimit && $cotizaciones_list->StartRec > 1)
		$cotizaciones_list->Recordset->Move($cotizaciones_list->StartRec - 1);
} elseif (!$cotizaciones->AllowAddDeleteRow && $cotizaciones_list->StopRec == 0) {
	$cotizaciones_list->StopRec = $cotizaciones->GridAddRowCount;
}

// Initialize aggregate
$cotizaciones->RowType = EW_ROWTYPE_AGGREGATEINIT;
$cotizaciones->ResetAttrs();
$cotizaciones_list->RenderRow();
while ($cotizaciones_list->RecCnt < $cotizaciones_list->StopRec) {
	$cotizaciones_list->RecCnt++;
	if (intval($cotizaciones_list->RecCnt) >= intval($cotizaciones_list->StartRec)) {
		$cotizaciones_list->RowCnt++;

		// Set up key count
		$cotizaciones_list->KeyCount = $cotizaciones_list->RowIndex;

		// Init row class and style
		$cotizaciones->ResetAttrs();
		$cotizaciones->CssClass = "";
		if ($cotizaciones->CurrentAction == "gridadd") {
		} else {
			$cotizaciones_list->LoadRowValues($cotizaciones_list->Recordset); // Load row values
		}
		$cotizaciones->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$cotizaciones->RowAttrs = array_merge($cotizaciones->RowAttrs, array('data-rowindex'=>$cotizaciones_list->RowCnt, 'id'=>'r' . $cotizaciones_list->RowCnt . '_cotizaciones', 'data-rowtype'=>$cotizaciones->RowType));

		// Render row
		$cotizaciones_list->RenderRow();

		// Render list options
		$cotizaciones_list->RenderListOptions();
?>
	<tr<?php echo $cotizaciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cotizaciones_list->ListOptions->Render("body", "left", $cotizaciones_list->RowCnt);
?>
	<?php if ($cotizaciones->id->Visible) { // id ?>
		<td data-name="id"<?php echo $cotizaciones->id->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_id" class="cotizaciones_id">
<span<?php echo $cotizaciones->id->ViewAttributes() ?>>
<?php echo $cotizaciones->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $cotizaciones_list->PageObjName . "_row_" . $cotizaciones_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($cotizaciones->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $cotizaciones->idTercero->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_idTercero" class="cotizaciones_idTercero">
<span<?php echo $cotizaciones->idTercero->ViewAttributes() ?>>
<?php echo $cotizaciones->idTercero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $cotizaciones->fecha->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_fecha" class="cotizaciones_fecha">
<span<?php echo $cotizaciones->fecha->ViewAttributes() ?>>
<?php echo $cotizaciones->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->contable->Visible) { // contable ?>
		<td data-name="contable"<?php echo $cotizaciones->contable->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_contable" class="cotizaciones_contable">
<span<?php echo $cotizaciones->contable->ViewAttributes() ?>>
<?php echo $cotizaciones->contable->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->importeNeto->Visible) { // importeNeto ?>
		<td data-name="importeNeto"<?php echo $cotizaciones->importeNeto->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_importeNeto" class="cotizaciones_importeNeto">
<span<?php echo $cotizaciones->importeNeto->ViewAttributes() ?>>
<?php echo $cotizaciones->importeNeto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->importeIva->Visible) { // importeIva ?>
		<td data-name="importeIva"<?php echo $cotizaciones->importeIva->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_importeIva" class="cotizaciones_importeIva">
<span<?php echo $cotizaciones->importeIva->ViewAttributes() ?>>
<?php echo $cotizaciones->importeIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->importeTotal->Visible) { // importeTotal ?>
		<td data-name="importeTotal"<?php echo $cotizaciones->importeTotal->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_importeTotal" class="cotizaciones_importeTotal">
<span<?php echo $cotizaciones->importeTotal->ViewAttributes() ?>>
<?php echo $cotizaciones->importeTotal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cotizaciones->vigencia->Visible) { // vigencia ?>
		<td data-name="vigencia"<?php echo $cotizaciones->vigencia->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_list->RowCnt ?>_cotizaciones_vigencia" class="cotizaciones_vigencia">
<span<?php echo $cotizaciones->vigencia->ViewAttributes() ?>>
<?php echo $cotizaciones->vigencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cotizaciones_list->ListOptions->Render("body", "right", $cotizaciones_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($cotizaciones->CurrentAction <> "gridadd")
		$cotizaciones_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($cotizaciones->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($cotizaciones_list->Recordset)
	$cotizaciones_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($cotizaciones_list->TotalRecs == 0 && $cotizaciones->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cotizaciones_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($cotizaciones->Export == "") { ?>
<script type="text/javascript">
fcotizacioneslistsrch.FilterList = <?php echo $cotizaciones_list->GetFilterList() ?>;
fcotizacioneslistsrch.Init();
fcotizacioneslist.Init();
</script>
<?php } ?>
<?php
$cotizaciones_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($cotizaciones->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$cotizaciones_list->Page_Terminate();
?>
