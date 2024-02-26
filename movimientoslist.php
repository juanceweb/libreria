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

$movimientos_list = NULL; // Initialize page object first

class cmovimientos_list extends cmovimientos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos';

	// Page object name
	var $PageObjName = 'movimientos_list';

	// Grid form hidden field names
	var $FormName = 'fmovimientoslist';
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

		// Table object (movimientos)
		if (!isset($GLOBALS["movimientos"]) || get_class($GLOBALS["movimientos"]) == "cmovimientos") {
			$GLOBALS["movimientos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["movimientos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "movimientosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "movimientosdelete.php";
		$this->MultiUpdateUrl = "movimientosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fmovimientoslistsrch";

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
		$this->idEstado->SetVisibility();
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fmovimientoslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->nroComprobanteCompleto->AdvancedSearch->ToJSON(), ","); // Field nroComprobanteCompleto
		$sFilterList = ew_Concat($sFilterList, $this->tipoMovimiento->AdvancedSearch->ToJSON(), ","); // Field tipoMovimiento
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->codTercero->AdvancedSearch->ToJSON(), ","); // Field codTercero
		$sFilterList = ew_Concat($sFilterList, $this->idTercero->AdvancedSearch->ToJSON(), ","); // Field idTercero
		$sFilterList = ew_Concat($sFilterList, $this->idComprobante->AdvancedSearch->ToJSON(), ","); // Field idComprobante
		$sFilterList = ew_Concat($sFilterList, $this->importeTotal->AdvancedSearch->ToJSON(), ","); // Field importeTotal
		$sFilterList = ew_Concat($sFilterList, $this->importeIva->AdvancedSearch->ToJSON(), ","); // Field importeIva
		$sFilterList = ew_Concat($sFilterList, $this->importeNeto->AdvancedSearch->ToJSON(), ","); // Field importeNeto
		$sFilterList = ew_Concat($sFilterList, $this->importeCancelado->AdvancedSearch->ToJSON(), ","); // Field importeCancelado
		$sFilterList = ew_Concat($sFilterList, $this->nroComprobante->AdvancedSearch->ToJSON(), ","); // Field nroComprobante
		$sFilterList = ew_Concat($sFilterList, $this->cae->AdvancedSearch->ToJSON(), ","); // Field cae
		$sFilterList = ew_Concat($sFilterList, $this->vtoCae->AdvancedSearch->ToJSON(), ","); // Field vtoCae
		$sFilterList = ew_Concat($sFilterList, $this->idEstado->AdvancedSearch->ToJSON(), ","); // Field idEstado
		$sFilterList = ew_Concat($sFilterList, $this->idUsuarioAlta->AdvancedSearch->ToJSON(), ","); // Field idUsuarioAlta
		$sFilterList = ew_Concat($sFilterList, $this->fechaAlta->AdvancedSearch->ToJSON(), ","); // Field fechaAlta
		$sFilterList = ew_Concat($sFilterList, $this->idUsuarioModificacion->AdvancedSearch->ToJSON(), ","); // Field idUsuarioModificacion
		$sFilterList = ew_Concat($sFilterList, $this->fechaModificacion->AdvancedSearch->ToJSON(), ","); // Field fechaModificacion
		$sFilterList = ew_Concat($sFilterList, $this->contable->AdvancedSearch->ToJSON(), ","); // Field contable
		$sFilterList = ew_Concat($sFilterList, $this->archivo->AdvancedSearch->ToJSON(), ","); // Field archivo
		$sFilterList = ew_Concat($sFilterList, $this->valorDolar->AdvancedSearch->ToJSON(), ","); // Field valorDolar
		$sFilterList = ew_Concat($sFilterList, $this->comentarios->AdvancedSearch->ToJSON(), ","); // Field comentarios
		$sFilterList = ew_Concat($sFilterList, $this->articulosAsociados->AdvancedSearch->ToJSON(), ","); // Field articulosAsociados
		$sFilterList = ew_Concat($sFilterList, $this->movimientosAsociados->AdvancedSearch->ToJSON(), ","); // Field movimientosAsociados
		$sFilterList = ew_Concat($sFilterList, $this->condicionVenta->AdvancedSearch->ToJSON(), ","); // Field condicionVenta
		$sFilterList = ew_Concat($sFilterList, $this->vigencia->AdvancedSearch->ToJSON(), ","); // Field vigencia
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fmovimientoslistsrch", $filters);
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

		// Field nroComprobanteCompleto
		$this->nroComprobanteCompleto->AdvancedSearch->SearchValue = @$filter["x_nroComprobanteCompleto"];
		$this->nroComprobanteCompleto->AdvancedSearch->SearchOperator = @$filter["z_nroComprobanteCompleto"];
		$this->nroComprobanteCompleto->AdvancedSearch->SearchCondition = @$filter["v_nroComprobanteCompleto"];
		$this->nroComprobanteCompleto->AdvancedSearch->SearchValue2 = @$filter["y_nroComprobanteCompleto"];
		$this->nroComprobanteCompleto->AdvancedSearch->SearchOperator2 = @$filter["w_nroComprobanteCompleto"];
		$this->nroComprobanteCompleto->AdvancedSearch->Save();

		// Field tipoMovimiento
		$this->tipoMovimiento->AdvancedSearch->SearchValue = @$filter["x_tipoMovimiento"];
		$this->tipoMovimiento->AdvancedSearch->SearchOperator = @$filter["z_tipoMovimiento"];
		$this->tipoMovimiento->AdvancedSearch->SearchCondition = @$filter["v_tipoMovimiento"];
		$this->tipoMovimiento->AdvancedSearch->SearchValue2 = @$filter["y_tipoMovimiento"];
		$this->tipoMovimiento->AdvancedSearch->SearchOperator2 = @$filter["w_tipoMovimiento"];
		$this->tipoMovimiento->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field codTercero
		$this->codTercero->AdvancedSearch->SearchValue = @$filter["x_codTercero"];
		$this->codTercero->AdvancedSearch->SearchOperator = @$filter["z_codTercero"];
		$this->codTercero->AdvancedSearch->SearchCondition = @$filter["v_codTercero"];
		$this->codTercero->AdvancedSearch->SearchValue2 = @$filter["y_codTercero"];
		$this->codTercero->AdvancedSearch->SearchOperator2 = @$filter["w_codTercero"];
		$this->codTercero->AdvancedSearch->Save();

		// Field idTercero
		$this->idTercero->AdvancedSearch->SearchValue = @$filter["x_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator = @$filter["z_idTercero"];
		$this->idTercero->AdvancedSearch->SearchCondition = @$filter["v_idTercero"];
		$this->idTercero->AdvancedSearch->SearchValue2 = @$filter["y_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator2 = @$filter["w_idTercero"];
		$this->idTercero->AdvancedSearch->Save();

		// Field idComprobante
		$this->idComprobante->AdvancedSearch->SearchValue = @$filter["x_idComprobante"];
		$this->idComprobante->AdvancedSearch->SearchOperator = @$filter["z_idComprobante"];
		$this->idComprobante->AdvancedSearch->SearchCondition = @$filter["v_idComprobante"];
		$this->idComprobante->AdvancedSearch->SearchValue2 = @$filter["y_idComprobante"];
		$this->idComprobante->AdvancedSearch->SearchOperator2 = @$filter["w_idComprobante"];
		$this->idComprobante->AdvancedSearch->Save();

		// Field importeTotal
		$this->importeTotal->AdvancedSearch->SearchValue = @$filter["x_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator = @$filter["z_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchCondition = @$filter["v_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchValue2 = @$filter["y_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator2 = @$filter["w_importeTotal"];
		$this->importeTotal->AdvancedSearch->Save();

		// Field importeIva
		$this->importeIva->AdvancedSearch->SearchValue = @$filter["x_importeIva"];
		$this->importeIva->AdvancedSearch->SearchOperator = @$filter["z_importeIva"];
		$this->importeIva->AdvancedSearch->SearchCondition = @$filter["v_importeIva"];
		$this->importeIva->AdvancedSearch->SearchValue2 = @$filter["y_importeIva"];
		$this->importeIva->AdvancedSearch->SearchOperator2 = @$filter["w_importeIva"];
		$this->importeIva->AdvancedSearch->Save();

		// Field importeNeto
		$this->importeNeto->AdvancedSearch->SearchValue = @$filter["x_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchOperator = @$filter["z_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchCondition = @$filter["v_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchValue2 = @$filter["y_importeNeto"];
		$this->importeNeto->AdvancedSearch->SearchOperator2 = @$filter["w_importeNeto"];
		$this->importeNeto->AdvancedSearch->Save();

		// Field importeCancelado
		$this->importeCancelado->AdvancedSearch->SearchValue = @$filter["x_importeCancelado"];
		$this->importeCancelado->AdvancedSearch->SearchOperator = @$filter["z_importeCancelado"];
		$this->importeCancelado->AdvancedSearch->SearchCondition = @$filter["v_importeCancelado"];
		$this->importeCancelado->AdvancedSearch->SearchValue2 = @$filter["y_importeCancelado"];
		$this->importeCancelado->AdvancedSearch->SearchOperator2 = @$filter["w_importeCancelado"];
		$this->importeCancelado->AdvancedSearch->Save();

		// Field nroComprobante
		$this->nroComprobante->AdvancedSearch->SearchValue = @$filter["x_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchOperator = @$filter["z_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchCondition = @$filter["v_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchValue2 = @$filter["y_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->SearchOperator2 = @$filter["w_nroComprobante"];
		$this->nroComprobante->AdvancedSearch->Save();

		// Field cae
		$this->cae->AdvancedSearch->SearchValue = @$filter["x_cae"];
		$this->cae->AdvancedSearch->SearchOperator = @$filter["z_cae"];
		$this->cae->AdvancedSearch->SearchCondition = @$filter["v_cae"];
		$this->cae->AdvancedSearch->SearchValue2 = @$filter["y_cae"];
		$this->cae->AdvancedSearch->SearchOperator2 = @$filter["w_cae"];
		$this->cae->AdvancedSearch->Save();

		// Field vtoCae
		$this->vtoCae->AdvancedSearch->SearchValue = @$filter["x_vtoCae"];
		$this->vtoCae->AdvancedSearch->SearchOperator = @$filter["z_vtoCae"];
		$this->vtoCae->AdvancedSearch->SearchCondition = @$filter["v_vtoCae"];
		$this->vtoCae->AdvancedSearch->SearchValue2 = @$filter["y_vtoCae"];
		$this->vtoCae->AdvancedSearch->SearchOperator2 = @$filter["w_vtoCae"];
		$this->vtoCae->AdvancedSearch->Save();

		// Field idEstado
		$this->idEstado->AdvancedSearch->SearchValue = @$filter["x_idEstado"];
		$this->idEstado->AdvancedSearch->SearchOperator = @$filter["z_idEstado"];
		$this->idEstado->AdvancedSearch->SearchCondition = @$filter["v_idEstado"];
		$this->idEstado->AdvancedSearch->SearchValue2 = @$filter["y_idEstado"];
		$this->idEstado->AdvancedSearch->SearchOperator2 = @$filter["w_idEstado"];
		$this->idEstado->AdvancedSearch->Save();

		// Field idUsuarioAlta
		$this->idUsuarioAlta->AdvancedSearch->SearchValue = @$filter["x_idUsuarioAlta"];
		$this->idUsuarioAlta->AdvancedSearch->SearchOperator = @$filter["z_idUsuarioAlta"];
		$this->idUsuarioAlta->AdvancedSearch->SearchCondition = @$filter["v_idUsuarioAlta"];
		$this->idUsuarioAlta->AdvancedSearch->SearchValue2 = @$filter["y_idUsuarioAlta"];
		$this->idUsuarioAlta->AdvancedSearch->SearchOperator2 = @$filter["w_idUsuarioAlta"];
		$this->idUsuarioAlta->AdvancedSearch->Save();

		// Field fechaAlta
		$this->fechaAlta->AdvancedSearch->SearchValue = @$filter["x_fechaAlta"];
		$this->fechaAlta->AdvancedSearch->SearchOperator = @$filter["z_fechaAlta"];
		$this->fechaAlta->AdvancedSearch->SearchCondition = @$filter["v_fechaAlta"];
		$this->fechaAlta->AdvancedSearch->SearchValue2 = @$filter["y_fechaAlta"];
		$this->fechaAlta->AdvancedSearch->SearchOperator2 = @$filter["w_fechaAlta"];
		$this->fechaAlta->AdvancedSearch->Save();

		// Field idUsuarioModificacion
		$this->idUsuarioModificacion->AdvancedSearch->SearchValue = @$filter["x_idUsuarioModificacion"];
		$this->idUsuarioModificacion->AdvancedSearch->SearchOperator = @$filter["z_idUsuarioModificacion"];
		$this->idUsuarioModificacion->AdvancedSearch->SearchCondition = @$filter["v_idUsuarioModificacion"];
		$this->idUsuarioModificacion->AdvancedSearch->SearchValue2 = @$filter["y_idUsuarioModificacion"];
		$this->idUsuarioModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_idUsuarioModificacion"];
		$this->idUsuarioModificacion->AdvancedSearch->Save();

		// Field fechaModificacion
		$this->fechaModificacion->AdvancedSearch->SearchValue = @$filter["x_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator = @$filter["z_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchCondition = @$filter["v_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchValue2 = @$filter["y_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->SearchOperator2 = @$filter["w_fechaModificacion"];
		$this->fechaModificacion->AdvancedSearch->Save();

		// Field contable
		$this->contable->AdvancedSearch->SearchValue = @$filter["x_contable"];
		$this->contable->AdvancedSearch->SearchOperator = @$filter["z_contable"];
		$this->contable->AdvancedSearch->SearchCondition = @$filter["v_contable"];
		$this->contable->AdvancedSearch->SearchValue2 = @$filter["y_contable"];
		$this->contable->AdvancedSearch->SearchOperator2 = @$filter["w_contable"];
		$this->contable->AdvancedSearch->Save();

		// Field archivo
		$this->archivo->AdvancedSearch->SearchValue = @$filter["x_archivo"];
		$this->archivo->AdvancedSearch->SearchOperator = @$filter["z_archivo"];
		$this->archivo->AdvancedSearch->SearchCondition = @$filter["v_archivo"];
		$this->archivo->AdvancedSearch->SearchValue2 = @$filter["y_archivo"];
		$this->archivo->AdvancedSearch->SearchOperator2 = @$filter["w_archivo"];
		$this->archivo->AdvancedSearch->Save();

		// Field valorDolar
		$this->valorDolar->AdvancedSearch->SearchValue = @$filter["x_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchOperator = @$filter["z_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchCondition = @$filter["v_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchValue2 = @$filter["y_valorDolar"];
		$this->valorDolar->AdvancedSearch->SearchOperator2 = @$filter["w_valorDolar"];
		$this->valorDolar->AdvancedSearch->Save();

		// Field comentarios
		$this->comentarios->AdvancedSearch->SearchValue = @$filter["x_comentarios"];
		$this->comentarios->AdvancedSearch->SearchOperator = @$filter["z_comentarios"];
		$this->comentarios->AdvancedSearch->SearchCondition = @$filter["v_comentarios"];
		$this->comentarios->AdvancedSearch->SearchValue2 = @$filter["y_comentarios"];
		$this->comentarios->AdvancedSearch->SearchOperator2 = @$filter["w_comentarios"];
		$this->comentarios->AdvancedSearch->Save();

		// Field articulosAsociados
		$this->articulosAsociados->AdvancedSearch->SearchValue = @$filter["x_articulosAsociados"];
		$this->articulosAsociados->AdvancedSearch->SearchOperator = @$filter["z_articulosAsociados"];
		$this->articulosAsociados->AdvancedSearch->SearchCondition = @$filter["v_articulosAsociados"];
		$this->articulosAsociados->AdvancedSearch->SearchValue2 = @$filter["y_articulosAsociados"];
		$this->articulosAsociados->AdvancedSearch->SearchOperator2 = @$filter["w_articulosAsociados"];
		$this->articulosAsociados->AdvancedSearch->Save();

		// Field movimientosAsociados
		$this->movimientosAsociados->AdvancedSearch->SearchValue = @$filter["x_movimientosAsociados"];
		$this->movimientosAsociados->AdvancedSearch->SearchOperator = @$filter["z_movimientosAsociados"];
		$this->movimientosAsociados->AdvancedSearch->SearchCondition = @$filter["v_movimientosAsociados"];
		$this->movimientosAsociados->AdvancedSearch->SearchValue2 = @$filter["y_movimientosAsociados"];
		$this->movimientosAsociados->AdvancedSearch->SearchOperator2 = @$filter["w_movimientosAsociados"];
		$this->movimientosAsociados->AdvancedSearch->Save();

		// Field condicionVenta
		$this->condicionVenta->AdvancedSearch->SearchValue = @$filter["x_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchOperator = @$filter["z_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchCondition = @$filter["v_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchValue2 = @$filter["y_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchOperator2 = @$filter["w_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->Save();

		// Field vigencia
		$this->vigencia->AdvancedSearch->SearchValue = @$filter["x_vigencia"];
		$this->vigencia->AdvancedSearch->SearchOperator = @$filter["z_vigencia"];
		$this->vigencia->AdvancedSearch->SearchCondition = @$filter["v_vigencia"];
		$this->vigencia->AdvancedSearch->SearchValue2 = @$filter["y_vigencia"];
		$this->vigencia->AdvancedSearch->SearchOperator2 = @$filter["w_vigencia"];
		$this->vigencia->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->nroComprobanteCompleto, $Default, FALSE); // nroComprobanteCompleto
		$this->BuildSearchSql($sWhere, $this->tipoMovimiento, $Default, FALSE); // tipoMovimiento
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->codTercero, $Default, FALSE); // codTercero
		$this->BuildSearchSql($sWhere, $this->idTercero, $Default, FALSE); // idTercero
		$this->BuildSearchSql($sWhere, $this->idComprobante, $Default, FALSE); // idComprobante
		$this->BuildSearchSql($sWhere, $this->importeTotal, $Default, FALSE); // importeTotal
		$this->BuildSearchSql($sWhere, $this->importeIva, $Default, FALSE); // importeIva
		$this->BuildSearchSql($sWhere, $this->importeNeto, $Default, FALSE); // importeNeto
		$this->BuildSearchSql($sWhere, $this->importeCancelado, $Default, FALSE); // importeCancelado
		$this->BuildSearchSql($sWhere, $this->nroComprobante, $Default, FALSE); // nroComprobante
		$this->BuildSearchSql($sWhere, $this->cae, $Default, FALSE); // cae
		$this->BuildSearchSql($sWhere, $this->vtoCae, $Default, FALSE); // vtoCae
		$this->BuildSearchSql($sWhere, $this->idEstado, $Default, FALSE); // idEstado
		$this->BuildSearchSql($sWhere, $this->idUsuarioAlta, $Default, FALSE); // idUsuarioAlta
		$this->BuildSearchSql($sWhere, $this->fechaAlta, $Default, FALSE); // fechaAlta
		$this->BuildSearchSql($sWhere, $this->idUsuarioModificacion, $Default, FALSE); // idUsuarioModificacion
		$this->BuildSearchSql($sWhere, $this->fechaModificacion, $Default, FALSE); // fechaModificacion
		$this->BuildSearchSql($sWhere, $this->contable, $Default, FALSE); // contable
		$this->BuildSearchSql($sWhere, $this->archivo, $Default, FALSE); // archivo
		$this->BuildSearchSql($sWhere, $this->valorDolar, $Default, FALSE); // valorDolar
		$this->BuildSearchSql($sWhere, $this->comentarios, $Default, FALSE); // comentarios
		$this->BuildSearchSql($sWhere, $this->articulosAsociados, $Default, FALSE); // articulosAsociados
		$this->BuildSearchSql($sWhere, $this->movimientosAsociados, $Default, FALSE); // movimientosAsociados
		$this->BuildSearchSql($sWhere, $this->condicionVenta, $Default, FALSE); // condicionVenta
		$this->BuildSearchSql($sWhere, $this->vigencia, $Default, FALSE); // vigencia

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->nroComprobanteCompleto->AdvancedSearch->Save(); // nroComprobanteCompleto
			$this->tipoMovimiento->AdvancedSearch->Save(); // tipoMovimiento
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->codTercero->AdvancedSearch->Save(); // codTercero
			$this->idTercero->AdvancedSearch->Save(); // idTercero
			$this->idComprobante->AdvancedSearch->Save(); // idComprobante
			$this->importeTotal->AdvancedSearch->Save(); // importeTotal
			$this->importeIva->AdvancedSearch->Save(); // importeIva
			$this->importeNeto->AdvancedSearch->Save(); // importeNeto
			$this->importeCancelado->AdvancedSearch->Save(); // importeCancelado
			$this->nroComprobante->AdvancedSearch->Save(); // nroComprobante
			$this->cae->AdvancedSearch->Save(); // cae
			$this->vtoCae->AdvancedSearch->Save(); // vtoCae
			$this->idEstado->AdvancedSearch->Save(); // idEstado
			$this->idUsuarioAlta->AdvancedSearch->Save(); // idUsuarioAlta
			$this->fechaAlta->AdvancedSearch->Save(); // fechaAlta
			$this->idUsuarioModificacion->AdvancedSearch->Save(); // idUsuarioModificacion
			$this->fechaModificacion->AdvancedSearch->Save(); // fechaModificacion
			$this->contable->AdvancedSearch->Save(); // contable
			$this->archivo->AdvancedSearch->Save(); // archivo
			$this->valorDolar->AdvancedSearch->Save(); // valorDolar
			$this->comentarios->AdvancedSearch->Save(); // comentarios
			$this->articulosAsociados->AdvancedSearch->Save(); // articulosAsociados
			$this->movimientosAsociados->AdvancedSearch->Save(); // movimientosAsociados
			$this->condicionVenta->AdvancedSearch->Save(); // condicionVenta
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nroComprobanteCompleto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nroComprobante, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cae, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idEstado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->archivo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->comentarios, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->articulosAsociados, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->movimientosAsociados, $arKeywords, $type);
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nroComprobanteCompleto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoMovimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idComprobante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeTotal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeIva->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeNeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->importeCancelado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nroComprobante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cae->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vtoCae->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idEstado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idUsuarioAlta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaAlta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idUsuarioModificacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fechaModificacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contable->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->archivo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->valorDolar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comentarios->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->articulosAsociados->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->movimientosAsociados->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->condicionVenta->AdvancedSearch->IssetSession())
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

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		$this->tipoMovimiento->AdvancedSearch->LoadDefault();
		$this->idEstado->AdvancedSearch->LoadDefault();
		return TRUE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->nroComprobanteCompleto->AdvancedSearch->UnsetSession();
		$this->tipoMovimiento->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->codTercero->AdvancedSearch->UnsetSession();
		$this->idTercero->AdvancedSearch->UnsetSession();
		$this->idComprobante->AdvancedSearch->UnsetSession();
		$this->importeTotal->AdvancedSearch->UnsetSession();
		$this->importeIva->AdvancedSearch->UnsetSession();
		$this->importeNeto->AdvancedSearch->UnsetSession();
		$this->importeCancelado->AdvancedSearch->UnsetSession();
		$this->nroComprobante->AdvancedSearch->UnsetSession();
		$this->cae->AdvancedSearch->UnsetSession();
		$this->vtoCae->AdvancedSearch->UnsetSession();
		$this->idEstado->AdvancedSearch->UnsetSession();
		$this->idUsuarioAlta->AdvancedSearch->UnsetSession();
		$this->fechaAlta->AdvancedSearch->UnsetSession();
		$this->idUsuarioModificacion->AdvancedSearch->UnsetSession();
		$this->fechaModificacion->AdvancedSearch->UnsetSession();
		$this->contable->AdvancedSearch->UnsetSession();
		$this->archivo->AdvancedSearch->UnsetSession();
		$this->valorDolar->AdvancedSearch->UnsetSession();
		$this->comentarios->AdvancedSearch->UnsetSession();
		$this->articulosAsociados->AdvancedSearch->UnsetSession();
		$this->movimientosAsociados->AdvancedSearch->UnsetSession();
		$this->condicionVenta->AdvancedSearch->UnsetSession();
		$this->vigencia->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->nroComprobanteCompleto->AdvancedSearch->Load();
		$this->tipoMovimiento->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->codTercero->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->idComprobante->AdvancedSearch->Load();
		$this->importeTotal->AdvancedSearch->Load();
		$this->importeIva->AdvancedSearch->Load();
		$this->importeNeto->AdvancedSearch->Load();
		$this->importeCancelado->AdvancedSearch->Load();
		$this->nroComprobante->AdvancedSearch->Load();
		$this->cae->AdvancedSearch->Load();
		$this->vtoCae->AdvancedSearch->Load();
		$this->idEstado->AdvancedSearch->Load();
		$this->idUsuarioAlta->AdvancedSearch->Load();
		$this->fechaAlta->AdvancedSearch->Load();
		$this->idUsuarioModificacion->AdvancedSearch->Load();
		$this->fechaModificacion->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->archivo->AdvancedSearch->Load();
		$this->valorDolar->AdvancedSearch->Load();
		$this->comentarios->AdvancedSearch->Load();
		$this->articulosAsociados->AdvancedSearch->Load();
		$this->movimientosAsociados->AdvancedSearch->Load();
		$this->condicionVenta->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nroComprobanteCompleto, $bCtrl); // nroComprobanteCompleto
			$this->UpdateSort($this->tipoMovimiento, $bCtrl); // tipoMovimiento
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->codTercero, $bCtrl); // codTercero
			$this->UpdateSort($this->idTercero, $bCtrl); // idTercero
			$this->UpdateSort($this->idComprobante, $bCtrl); // idComprobante
			$this->UpdateSort($this->importeTotal, $bCtrl); // importeTotal
			$this->UpdateSort($this->importeIva, $bCtrl); // importeIva
			$this->UpdateSort($this->importeNeto, $bCtrl); // importeNeto
			$this->UpdateSort($this->importeCancelado, $bCtrl); // importeCancelado
			$this->UpdateSort($this->idEstado, $bCtrl); // idEstado
			$this->UpdateSort($this->movimientosAsociados, $bCtrl); // movimientosAsociados
			$this->UpdateSort($this->condicionVenta, $bCtrl); // condicionVenta
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
				$this->nroComprobanteCompleto->setSort("");
				$this->tipoMovimiento->setSort("");
				$this->fecha->setSort("");
				$this->codTercero->setSort("");
				$this->idTercero->setSort("");
				$this->idComprobante->setSort("");
				$this->importeTotal->setSort("");
				$this->importeIva->setSort("");
				$this->importeNeto->setSort("");
				$this->importeCancelado->setSort("");
				$this->idEstado->setSort("");
				$this->movimientosAsociados->setSort("");
				$this->condicionVenta->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmovimientoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmovimientoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmovimientoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmovimientoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
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
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// nroComprobanteCompleto
		$this->nroComprobanteCompleto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nroComprobanteCompleto"]);
		if ($this->nroComprobanteCompleto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nroComprobanteCompleto->AdvancedSearch->SearchOperator = @$_GET["z_nroComprobanteCompleto"];

		// tipoMovimiento
		$this->tipoMovimiento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoMovimiento"]);
		if ($this->tipoMovimiento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoMovimiento->AdvancedSearch->SearchOperator = @$_GET["z_tipoMovimiento"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$_GET["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha"];

		// codTercero
		$this->codTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codTercero"]);
		if ($this->codTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codTercero->AdvancedSearch->SearchOperator = @$_GET["z_codTercero"];

		// idTercero
		$this->idTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTercero"]);
		if ($this->idTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTercero->AdvancedSearch->SearchOperator = @$_GET["z_idTercero"];

		// idComprobante
		$this->idComprobante->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idComprobante"]);
		if ($this->idComprobante->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idComprobante->AdvancedSearch->SearchOperator = @$_GET["z_idComprobante"];

		// importeTotal
		$this->importeTotal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeTotal"]);
		if ($this->importeTotal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeTotal->AdvancedSearch->SearchOperator = @$_GET["z_importeTotal"];

		// importeIva
		$this->importeIva->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeIva"]);
		if ($this->importeIva->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeIva->AdvancedSearch->SearchOperator = @$_GET["z_importeIva"];

		// importeNeto
		$this->importeNeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeNeto"]);
		if ($this->importeNeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeNeto->AdvancedSearch->SearchOperator = @$_GET["z_importeNeto"];

		// importeCancelado
		$this->importeCancelado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_importeCancelado"]);
		if ($this->importeCancelado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->importeCancelado->AdvancedSearch->SearchOperator = @$_GET["z_importeCancelado"];

		// cae
		$this->cae->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_cae"]);
		if ($this->cae->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->cae->AdvancedSearch->SearchOperator = @$_GET["z_cae"];

		// vtoCae
		$this->vtoCae->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vtoCae"]);
		if ($this->vtoCae->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vtoCae->AdvancedSearch->SearchOperator = @$_GET["z_vtoCae"];

		// idEstado
		$this->idEstado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idEstado"]);
		if ($this->idEstado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idEstado->AdvancedSearch->SearchOperator = @$_GET["z_idEstado"];

		// idUsuarioAlta
		$this->idUsuarioAlta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idUsuarioAlta"]);
		if ($this->idUsuarioAlta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idUsuarioAlta->AdvancedSearch->SearchOperator = @$_GET["z_idUsuarioAlta"];

		// fechaAlta
		$this->fechaAlta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaAlta"]);
		if ($this->fechaAlta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaAlta->AdvancedSearch->SearchOperator = @$_GET["z_fechaAlta"];

		// idUsuarioModificacion
		$this->idUsuarioModificacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idUsuarioModificacion"]);
		if ($this->idUsuarioModificacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idUsuarioModificacion->AdvancedSearch->SearchOperator = @$_GET["z_idUsuarioModificacion"];

		// fechaModificacion
		$this->fechaModificacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fechaModificacion"]);
		if ($this->fechaModificacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fechaModificacion->AdvancedSearch->SearchOperator = @$_GET["z_fechaModificacion"];

		// contable
		$this->contable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contable"]);
		if ($this->contable->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contable->AdvancedSearch->SearchOperator = @$_GET["z_contable"];

		// archivo
		$this->archivo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_archivo"]);
		if ($this->archivo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->archivo->AdvancedSearch->SearchOperator = @$_GET["z_archivo"];

		// valorDolar
		$this->valorDolar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_valorDolar"]);
		if ($this->valorDolar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->valorDolar->AdvancedSearch->SearchOperator = @$_GET["z_valorDolar"];

		// comentarios
		$this->comentarios->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comentarios"]);
		if ($this->comentarios->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comentarios->AdvancedSearch->SearchOperator = @$_GET["z_comentarios"];

		// articulosAsociados
		$this->articulosAsociados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_articulosAsociados"]);
		if ($this->articulosAsociados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->articulosAsociados->AdvancedSearch->SearchOperator = @$_GET["z_articulosAsociados"];

		// movimientosAsociados
		$this->movimientosAsociados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_movimientosAsociados"]);
		if ($this->movimientosAsociados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->movimientosAsociados->AdvancedSearch->SearchOperator = @$_GET["z_movimientosAsociados"];

		// condicionVenta
		$this->condicionVenta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_condicionVenta"]);
		if ($this->condicionVenta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->condicionVenta->AdvancedSearch->SearchOperator = @$_GET["z_condicionVenta"];

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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// nroComprobanteCompleto
		// tipoMovimiento
		// fecha
		// idSociedad

		$this->idSociedad->CellCssStyle = "white-space: nowrap;";

		// codTercero
		// idTercero
		// idComprobante
		// importeTotal
		// importeIva
		// importeNeto
		// importeCancelado
		// nombreTercero

		$this->nombreTercero->CellCssStyle = "white-space: nowrap;";

		// idDocTercero
		$this->idDocTercero->CellCssStyle = "white-space: nowrap;";

		// nroDocTercero
		$this->nroDocTercero->CellCssStyle = "white-space: nowrap;";

		// ptoVenta
		$this->ptoVenta->CellCssStyle = "white-space: nowrap;";

		// nroComprobante
		$this->nroComprobante->CellCssStyle = "white-space: nowrap;";

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

		$this->articulosAsociados->CellCssStyle = "width: 170px;";

		// movimientosAsociados
		$this->movimientosAsociados->CellCssStyle = "width: 170px;";

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

		// idEstado
		if (strval($this->idEstado->CurrentValue) <> "") {
			$this->idEstado->ViewValue = $this->idEstado->OptionCaption($this->idEstado->CurrentValue);
		} else {
			$this->idEstado->ViewValue = NULL;
		}
		$this->idEstado->ViewCustomAttributes = "";

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

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
			$this->idEstado->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// nroComprobanteCompleto
			$this->nroComprobanteCompleto->EditAttrs["class"] = "form-control";
			$this->nroComprobanteCompleto->EditCustomAttributes = "";
			$this->nroComprobanteCompleto->EditValue = ew_HtmlEncode($this->nroComprobanteCompleto->AdvancedSearch->SearchValue);
			$this->nroComprobanteCompleto->PlaceHolder = ew_RemoveHtml($this->nroComprobanteCompleto->FldCaption());

			// tipoMovimiento
			$this->tipoMovimiento->EditAttrs["class"] = "form-control";
			$this->tipoMovimiento->EditCustomAttributes = "";
			$this->tipoMovimiento->EditValue = $this->tipoMovimiento->Options(TRUE);

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// codTercero
			$this->codTercero->EditAttrs["class"] = "form-control";
			$this->codTercero->EditCustomAttributes = "";
			$this->codTercero->EditValue = ew_HtmlEncode($this->codTercero->AdvancedSearch->SearchValue);
			$this->codTercero->PlaceHolder = ew_RemoveHtml($this->codTercero->FldCaption());

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = 'data-s2="true"';
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
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;

			// idComprobante
			$this->idComprobante->EditAttrs["class"] = "form-control";
			$this->idComprobante->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idComprobante->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idComprobante->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->importeTotal->EditValue = ew_HtmlEncode($this->importeTotal->AdvancedSearch->SearchValue);
			$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());

			// importeIva
			$this->importeIva->EditAttrs["class"] = "form-control";
			$this->importeIva->EditCustomAttributes = "";
			$this->importeIva->EditValue = ew_HtmlEncode($this->importeIva->AdvancedSearch->SearchValue);
			$this->importeIva->PlaceHolder = ew_RemoveHtml($this->importeIva->FldCaption());

			// importeNeto
			$this->importeNeto->EditAttrs["class"] = "form-control";
			$this->importeNeto->EditCustomAttributes = "";
			$this->importeNeto->EditValue = ew_HtmlEncode($this->importeNeto->AdvancedSearch->SearchValue);
			$this->importeNeto->PlaceHolder = ew_RemoveHtml($this->importeNeto->FldCaption());

			// importeCancelado
			$this->importeCancelado->EditAttrs["class"] = "form-control";
			$this->importeCancelado->EditCustomAttributes = "";
			$this->importeCancelado->EditValue = ew_HtmlEncode($this->importeCancelado->AdvancedSearch->SearchValue);
			$this->importeCancelado->PlaceHolder = ew_RemoveHtml($this->importeCancelado->FldCaption());

			// idEstado
			$this->idEstado->EditAttrs["class"] = "form-control";
			$this->idEstado->EditCustomAttributes = "";
			$this->idEstado->EditValue = $this->idEstado->Options(TRUE);

			// movimientosAsociados
			$this->movimientosAsociados->EditAttrs["class"] = "form-control";
			$this->movimientosAsociados->EditCustomAttributes = "";
			$this->movimientosAsociados->EditValue = ew_HtmlEncode($this->movimientosAsociados->AdvancedSearch->SearchValue);
			$this->movimientosAsociados->PlaceHolder = ew_RemoveHtml($this->movimientosAsociados->FldCaption());

			// condicionVenta
			$this->condicionVenta->EditAttrs["class"] = "form-control";
			$this->condicionVenta->EditCustomAttributes = "";
			$this->condicionVenta->EditValue = ew_HtmlEncode($this->condicionVenta->AdvancedSearch->SearchValue);
			$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());

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
		$this->id->AdvancedSearch->Load();
		$this->nroComprobanteCompleto->AdvancedSearch->Load();
		$this->tipoMovimiento->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->codTercero->AdvancedSearch->Load();
		$this->idTercero->AdvancedSearch->Load();
		$this->idComprobante->AdvancedSearch->Load();
		$this->importeTotal->AdvancedSearch->Load();
		$this->importeIva->AdvancedSearch->Load();
		$this->importeNeto->AdvancedSearch->Load();
		$this->importeCancelado->AdvancedSearch->Load();
		$this->cae->AdvancedSearch->Load();
		$this->vtoCae->AdvancedSearch->Load();
		$this->idEstado->AdvancedSearch->Load();
		$this->idUsuarioAlta->AdvancedSearch->Load();
		$this->fechaAlta->AdvancedSearch->Load();
		$this->idUsuarioModificacion->AdvancedSearch->Load();
		$this->fechaModificacion->AdvancedSearch->Load();
		$this->contable->AdvancedSearch->Load();
		$this->archivo->AdvancedSearch->Load();
		$this->valorDolar->AdvancedSearch->Load();
		$this->comentarios->AdvancedSearch->Load();
		$this->articulosAsociados->AdvancedSearch->Load();
		$this->movimientosAsociados->AdvancedSearch->Load();
		$this->condicionVenta->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_movimientos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_movimientos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmovimientoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$this->AddUrl='movimientosaddcustom.php';	
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
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Resultado</h4>
			  </div>
			  <div class="modal-body">
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {
					if($this->Recordset->fields["idEstado"]!=1){
						$this->ListOptions->Items["auditar"]->Body = '';
						$this->ListOptions->Items["edit"]->Body='';
						$this->ListOptions->Items["delete"]->Body='';
					}else{
						$this->ListOptions->Items["auditar"]->Body = '<div class="btn-group"><a onclick="auditar('.$this->id->DbValue.')" class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Auditar" href="#"><span class="glyphicon glyphicon-ok-circle ewIcon" aria-hidden="true"></span></a></div>';
						$this->ListOptions->Items["edit"]->Body='<a class="ewRowLink ewEdit" title="Modificar" data-caption="Modificar" href="movimientoseditcustom.php?id='.$this->id->DbValue.'"><span data-phrase="EditLink" class="icon-edit ewIcon" data-caption="Modificar"></span></a>';	
					};
						if ($this->idComprobante->DbValue) {
							$this->ListOptions->Items["view"]->Body='<a class="ewRowLink ewView" title="Ver" data-caption="Ver" href="imprimirmovimiento.php?id='.$this->id->DbValue.'"><span class="icon-view ewIcon" data-caption="Ver"></span></a>';					
						}else{
							$this->ListOptions->Items["view"]->Body='';					
						}
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
if (!isset($movimientos_list)) $movimientos_list = new cmovimientos_list();

// Page init
$movimientos_list->Page_Init();

// Page main
$movimientos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($movimientos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmovimientoslist = new ew_Form("fmovimientoslist", "list");
fmovimientoslist.FormKeyCountName = '<?php echo $movimientos_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmovimientoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientoslist.ValidateRequired = true;
<?php } else { ?>
fmovimientoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmovimientoslist.Lists["x_tipoMovimiento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientoslist.Lists["x_tipoMovimiento"].Options = <?php echo json_encode($movimientos->tipoMovimiento->Options()) ?>;
fmovimientoslist.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fmovimientoslist.Lists["x_idComprobante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fmovimientoslist.Lists["x_idEstado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientoslist.Lists["x_idEstado"].Options = <?php echo json_encode($movimientos->idEstado->Options()) ?>;

// Form object for search
var CurrentSearchForm = fmovimientoslistsrch = new ew_Form("fmovimientoslistsrch");

// Validate function for search
fmovimientoslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($movimientos->fecha->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fmovimientoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fmovimientoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fmovimientoslistsrch.Lists["x_tipoMovimiento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientoslistsrch.Lists["x_tipoMovimiento"].Options = <?php echo json_encode($movimientos->tipoMovimiento->Options()) ?>;
fmovimientoslistsrch.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fmovimientoslistsrch.Lists["x_idComprobante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fmovimientoslistsrch.Lists["x_idEstado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientoslistsrch.Lists["x_idEstado"].Options = <?php echo json_encode($movimientos->idEstado->Options()) ?>;

// Init search panel as collapsed
if (fmovimientoslistsrch) fmovimientoslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($movimientos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($movimientos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($movimientos_list->TotalRecs > 0 && $movimientos_list->ExportOptions->Visible()) { ?>
<?php $movimientos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos_list->SearchOptions->Visible()) { ?>
<?php $movimientos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos_list->FilterOptions->Visible()) { ?>
<?php $movimientos_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $movimientos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($movimientos_list->TotalRecs <= 0)
			$movimientos_list->TotalRecs = $movimientos->SelectRecordCount();
	} else {
		if (!$movimientos_list->Recordset && ($movimientos_list->Recordset = $movimientos_list->LoadRecordset()))
			$movimientos_list->TotalRecs = $movimientos_list->Recordset->RecordCount();
	}
	$movimientos_list->StartRec = 1;
	if ($movimientos_list->DisplayRecs <= 0 || ($movimientos->Export <> "" && $movimientos->ExportAll)) // Display all records
		$movimientos_list->DisplayRecs = $movimientos_list->TotalRecs;
	if (!($movimientos->Export <> "" && $movimientos->ExportAll))
		$movimientos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$movimientos_list->Recordset = $movimientos_list->LoadRecordset($movimientos_list->StartRec-1, $movimientos_list->DisplayRecs);

	// Set no record found message
	if ($movimientos->CurrentAction == "" && $movimientos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$movimientos_list->setWarningMessage(ew_DeniedMsg());
		if ($movimientos_list->SearchWhere == "0=101")
			$movimientos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$movimientos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$movimientos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($movimientos->Export == "" && $movimientos->CurrentAction == "") { ?>
<form name="fmovimientoslistsrch" id="fmovimientoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($movimientos_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fmovimientoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="movimientos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$movimientos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$movimientos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$movimientos->ResetAttrs();
$movimientos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
	<div id="xsc_tipoMovimiento" class="ewCell form-group">
		<label for="x_tipoMovimiento" class="ewSearchCaption ewLabel"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tipoMovimiento" id="z_tipoMovimiento" value="="></span>
		<span class="ewSearchField">
<select data-table="movimientos" data-field="x_tipoMovimiento" data-value-separator="<?php echo $movimientos->tipoMovimiento->DisplayValueSeparatorAttribute() ?>" id="x_tipoMovimiento" name="x_tipoMovimiento"<?php echo $movimientos->tipoMovimiento->EditAttributes() ?>>
<?php echo $movimientos->tipoMovimiento->SelectOptionListHtml("x_tipoMovimiento") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($movimientos->fecha->Visible) { // fecha ?>
	<div id="xsc_fecha" class="ewCell form-group">
		<label for="x_fecha" class="ewSearchCaption ewLabel"><?php echo $movimientos->fecha->FldCaption() ?></label>
		<span class="ewSearchOperator"><select name="z_fecha" id="z_fecha" class="form-control" onchange="ewForms(this).SrchOprChanged(this);"><option value="="<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "=") ? " selected" : "" ?> ><?php echo $Language->Phrase("EQUAL") ?></option><option value="<>"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "<>") ? " selected" : "" ?> ><?php echo $Language->Phrase("<>") ?></option><option value="<"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "<") ? " selected" : "" ?> ><?php echo $Language->Phrase("<") ?></option><option value="<="<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "<=") ? " selected" : "" ?> ><?php echo $Language->Phrase("<=") ?></option><option value=">"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == ">") ? " selected" : "" ?> ><?php echo $Language->Phrase(">") ?></option><option value=">="<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == ">=") ? " selected" : "" ?> ><?php echo $Language->Phrase(">=") ?></option><option value="IS NULL"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "IS NULL") ? " selected" : "" ?> ><?php echo $Language->Phrase("IS NULL") ?></option><option value="IS NOT NULL"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "IS NOT NULL") ? " selected" : "" ?> ><?php echo $Language->Phrase("IS NOT NULL") ?></option><option value="BETWEEN"<?php echo ($movimientos->fecha->AdvancedSearch->SearchOperator == "BETWEEN") ? " selected" : "" ?> ><?php echo $Language->Phrase("BETWEEN") ?></option></select></span>
		<span class="ewSearchField">
<input type="text" data-table="movimientos" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($movimientos->fecha->getPlaceHolder()) ?>" value="<?php echo $movimientos->fecha->EditValue ?>"<?php echo $movimientos->fecha->EditAttributes() ?>>
<?php if (!$movimientos->fecha->ReadOnly && !$movimientos->fecha->Disabled && !isset($movimientos->fecha->EditAttrs["readonly"]) && !isset($movimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fmovimientoslistsrch", "x_fecha", 0);
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha" style="display: none">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha" style="display: none">
<input type="text" data-table="movimientos" data-field="x_fecha" name="y_fecha" id="y_fecha" placeholder="<?php echo ew_HtmlEncode($movimientos->fecha->getPlaceHolder()) ?>" value="<?php echo $movimientos->fecha->EditValue2 ?>"<?php echo $movimientos->fecha->EditAttributes() ?>>
<?php if (!$movimientos->fecha->ReadOnly && !$movimientos->fecha->Disabled && !isset($movimientos->fecha->EditAttrs["readonly"]) && !isset($movimientos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fmovimientoslistsrch", "y_fecha", 0);
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
	<div id="xsc_idTercero" class="ewCell form-group">
		<label for="x_idTercero" class="ewSearchCaption ewLabel"><?php echo $movimientos->idTercero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTercero" id="z_idTercero" value="="></span>
		<span class="ewSearchField">
<select data-table="movimientos" data-field="x_idTercero" data-value-separator="<?php echo $movimientos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $movimientos->idTercero->EditAttributes() ?>>
<?php echo $movimientos->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $movimientos->idTercero->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
	<div id="xsc_idComprobante" class="ewCell form-group">
		<label for="x_idComprobante" class="ewSearchCaption ewLabel"><?php echo $movimientos->idComprobante->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idComprobante" id="z_idComprobante" value="="></span>
		<span class="ewSearchField">
<select data-table="movimientos" data-field="x_idComprobante" data-value-separator="<?php echo $movimientos->idComprobante->DisplayValueSeparatorAttribute() ?>" id="x_idComprobante" name="x_idComprobante"<?php echo $movimientos->idComprobante->EditAttributes() ?>>
<?php echo $movimientos->idComprobante->SelectOptionListHtml("x_idComprobante") ?>
</select>
<input type="hidden" name="s_x_idComprobante" id="s_x_idComprobante" value="<?php echo $movimientos->idComprobante->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
	<div id="xsc_idEstado" class="ewCell form-group">
		<label for="x_idEstado" class="ewSearchCaption ewLabel"><?php echo $movimientos->idEstado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idEstado" id="z_idEstado" value="="></span>
		<span class="ewSearchField">
<select data-table="movimientos" data-field="x_idEstado" data-value-separator="<?php echo $movimientos->idEstado->DisplayValueSeparatorAttribute() ?>" id="x_idEstado" name="x_idEstado"<?php echo $movimientos->idEstado->EditAttributes() ?>>
<?php echo $movimientos->idEstado->SelectOptionListHtml("x_idEstado") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($movimientos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($movimientos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $movimientos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($movimientos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($movimientos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($movimientos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($movimientos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $movimientos_list->ShowPageHeader(); ?>
<?php
$movimientos_list->ShowMessage();
?>
<?php if ($movimientos_list->TotalRecs > 0 || $movimientos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid movimientos">
<?php if ($movimientos->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($movimientos->CurrentAction <> "gridadd" && $movimientos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($movimientos_list->Pager)) $movimientos_list->Pager = new cPrevNextPager($movimientos_list->StartRec, $movimientos_list->DisplayRecs, $movimientos_list->TotalRecs) ?>
<?php if ($movimientos_list->Pager->RecordCount > 0 && $movimientos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($movimientos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $movimientos_list->PageUrl() ?>start=<?php echo $movimientos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($movimientos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $movimientos_list->PageUrl() ?>start=<?php echo $movimientos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $movimientos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($movimientos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $movimientos_list->PageUrl() ?>start=<?php echo $movimientos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($movimientos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $movimientos_list->PageUrl() ?>start=<?php echo $movimientos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $movimientos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $movimientos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $movimientos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $movimientos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($movimientos_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="movimientos">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($movimientos_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($movimientos_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($movimientos_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($movimientos_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($movimientos_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($movimientos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmovimientoslist" id="fmovimientoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos">
<div id="gmp_movimientos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($movimientos_list->TotalRecs > 0) { ?>
<table id="tbl_movimientoslist" class="table ewTable">
<?php echo $movimientos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$movimientos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$movimientos_list->RenderListOptions();

// Render list options (header, left)
$movimientos_list->ListOptions->Render("header", "left");
?>
<?php if ($movimientos->id->Visible) { // id ?>
	<?php if ($movimientos->SortUrl($movimientos->id) == "") { ?>
		<th data-name="id"><div id="elh_movimientos_id" class="movimientos_id"><div class="ewTableHeaderCaption"><?php echo $movimientos->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->id) ?>',2);"><div id="elh_movimientos_id" class="movimientos_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
	<?php if ($movimientos->SortUrl($movimientos->nroComprobanteCompleto) == "") { ?>
		<th data-name="nroComprobanteCompleto"><div id="elh_movimientos_nroComprobanteCompleto" class="movimientos_nroComprobanteCompleto"><div class="ewTableHeaderCaption"><?php echo $movimientos->nroComprobanteCompleto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nroComprobanteCompleto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->nroComprobanteCompleto) ?>',2);"><div id="elh_movimientos_nroComprobanteCompleto" class="movimientos_nroComprobanteCompleto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->nroComprobanteCompleto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->nroComprobanteCompleto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->nroComprobanteCompleto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
	<?php if ($movimientos->SortUrl($movimientos->tipoMovimiento) == "") { ?>
		<th data-name="tipoMovimiento"><div id="elh_movimientos_tipoMovimiento" class="movimientos_tipoMovimiento"><div class="ewTableHeaderCaption"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoMovimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->tipoMovimiento) ?>',2);"><div id="elh_movimientos_tipoMovimiento" class="movimientos_tipoMovimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->tipoMovimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->tipoMovimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->fecha->Visible) { // fecha ?>
	<?php if ($movimientos->SortUrl($movimientos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_movimientos_fecha" class="movimientos_fecha"><div class="ewTableHeaderCaption"><?php echo $movimientos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->fecha) ?>',2);"><div id="elh_movimientos_fecha" class="movimientos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
	<?php if ($movimientos->SortUrl($movimientos->codTercero) == "") { ?>
		<th data-name="codTercero"><div id="elh_movimientos_codTercero" class="movimientos_codTercero"><div class="ewTableHeaderCaption"><?php echo $movimientos->codTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->codTercero) ?>',2);"><div id="elh_movimientos_codTercero" class="movimientos_codTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->codTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->codTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->codTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
	<?php if ($movimientos->SortUrl($movimientos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_movimientos_idTercero" class="movimientos_idTercero"><div class="ewTableHeaderCaption"><?php echo $movimientos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->idTercero) ?>',2);"><div id="elh_movimientos_idTercero" class="movimientos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
	<?php if ($movimientos->SortUrl($movimientos->idComprobante) == "") { ?>
		<th data-name="idComprobante"><div id="elh_movimientos_idComprobante" class="movimientos_idComprobante"><div class="ewTableHeaderCaption"><?php echo $movimientos->idComprobante->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idComprobante"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->idComprobante) ?>',2);"><div id="elh_movimientos_idComprobante" class="movimientos_idComprobante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->idComprobante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->idComprobante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->idComprobante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
	<?php if ($movimientos->SortUrl($movimientos->importeTotal) == "") { ?>
		<th data-name="importeTotal"><div id="elh_movimientos_importeTotal" class="movimientos_importeTotal"><div class="ewTableHeaderCaption"><?php echo $movimientos->importeTotal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeTotal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->importeTotal) ?>',2);"><div id="elh_movimientos_importeTotal" class="movimientos_importeTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->importeTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->importeTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->importeTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
	<?php if ($movimientos->SortUrl($movimientos->importeIva) == "") { ?>
		<th data-name="importeIva"><div id="elh_movimientos_importeIva" class="movimientos_importeIva"><div class="ewTableHeaderCaption"><?php echo $movimientos->importeIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->importeIva) ?>',2);"><div id="elh_movimientos_importeIva" class="movimientos_importeIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->importeIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->importeIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->importeIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
	<?php if ($movimientos->SortUrl($movimientos->importeNeto) == "") { ?>
		<th data-name="importeNeto"><div id="elh_movimientos_importeNeto" class="movimientos_importeNeto"><div class="ewTableHeaderCaption"><?php echo $movimientos->importeNeto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeNeto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->importeNeto) ?>',2);"><div id="elh_movimientos_importeNeto" class="movimientos_importeNeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->importeNeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->importeNeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->importeNeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
	<?php if ($movimientos->SortUrl($movimientos->importeCancelado) == "") { ?>
		<th data-name="importeCancelado"><div id="elh_movimientos_importeCancelado" class="movimientos_importeCancelado"><div class="ewTableHeaderCaption"><?php echo $movimientos->importeCancelado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeCancelado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->importeCancelado) ?>',2);"><div id="elh_movimientos_importeCancelado" class="movimientos_importeCancelado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->importeCancelado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->importeCancelado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->importeCancelado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
	<?php if ($movimientos->SortUrl($movimientos->idEstado) == "") { ?>
		<th data-name="idEstado"><div id="elh_movimientos_idEstado" class="movimientos_idEstado"><div class="ewTableHeaderCaption"><?php echo $movimientos->idEstado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idEstado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->idEstado) ?>',2);"><div id="elh_movimientos_idEstado" class="movimientos_idEstado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->idEstado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->idEstado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->idEstado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
	<?php if ($movimientos->SortUrl($movimientos->movimientosAsociados) == "") { ?>
		<th data-name="movimientosAsociados"><div id="elh_movimientos_movimientosAsociados" class="movimientos_movimientosAsociados"><div class="ewTableHeaderCaption" style="width: 170px;"><?php echo $movimientos->movimientosAsociados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="movimientosAsociados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->movimientosAsociados) ?>',2);"><div id="elh_movimientos_movimientosAsociados" class="movimientos_movimientosAsociados">
			<div class="ewTableHeaderBtn" style="width: 170px;"><span class="ewTableHeaderCaption"><?php echo $movimientos->movimientosAsociados->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->movimientosAsociados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->movimientosAsociados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
	<?php if ($movimientos->SortUrl($movimientos->condicionVenta) == "") { ?>
		<th data-name="condicionVenta"><div id="elh_movimientos_condicionVenta" class="movimientos_condicionVenta"><div class="ewTableHeaderCaption"><?php echo $movimientos->condicionVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="condicionVenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->condicionVenta) ?>',2);"><div id="elh_movimientos_condicionVenta" class="movimientos_condicionVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->condicionVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->condicionVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->condicionVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
	<?php if ($movimientos->SortUrl($movimientos->vigencia) == "") { ?>
		<th data-name="vigencia"><div id="elh_movimientos_vigencia" class="movimientos_vigencia"><div class="ewTableHeaderCaption"><?php echo $movimientos->vigencia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vigencia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos->SortUrl($movimientos->vigencia) ?>',2);"><div id="elh_movimientos_vigencia" class="movimientos_vigencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos->vigencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos->vigencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos->vigencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$movimientos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($movimientos->ExportAll && $movimientos->Export <> "") {
	$movimientos_list->StopRec = $movimientos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($movimientos_list->TotalRecs > $movimientos_list->StartRec + $movimientos_list->DisplayRecs - 1)
		$movimientos_list->StopRec = $movimientos_list->StartRec + $movimientos_list->DisplayRecs - 1;
	else
		$movimientos_list->StopRec = $movimientos_list->TotalRecs;
}
$movimientos_list->RecCnt = $movimientos_list->StartRec - 1;
if ($movimientos_list->Recordset && !$movimientos_list->Recordset->EOF) {
	$movimientos_list->Recordset->MoveFirst();
	$bSelectLimit = $movimientos_list->UseSelectLimit;
	if (!$bSelectLimit && $movimientos_list->StartRec > 1)
		$movimientos_list->Recordset->Move($movimientos_list->StartRec - 1);
} elseif (!$movimientos->AllowAddDeleteRow && $movimientos_list->StopRec == 0) {
	$movimientos_list->StopRec = $movimientos->GridAddRowCount;
}

// Initialize aggregate
$movimientos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$movimientos->ResetAttrs();
$movimientos_list->RenderRow();
while ($movimientos_list->RecCnt < $movimientos_list->StopRec) {
	$movimientos_list->RecCnt++;
	if (intval($movimientos_list->RecCnt) >= intval($movimientos_list->StartRec)) {
		$movimientos_list->RowCnt++;

		// Set up key count
		$movimientos_list->KeyCount = $movimientos_list->RowIndex;

		// Init row class and style
		$movimientos->ResetAttrs();
		$movimientos->CssClass = "";
		if ($movimientos->CurrentAction == "gridadd") {
		} else {
			$movimientos_list->LoadRowValues($movimientos_list->Recordset); // Load row values
		}
		$movimientos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$movimientos->RowAttrs = array_merge($movimientos->RowAttrs, array('data-rowindex'=>$movimientos_list->RowCnt, 'id'=>'r' . $movimientos_list->RowCnt . '_movimientos', 'data-rowtype'=>$movimientos->RowType));

		// Render row
		$movimientos_list->RenderRow();

		// Render list options
		$movimientos_list->RenderListOptions();
?>
	<tr<?php echo $movimientos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$movimientos_list->ListOptions->Render("body", "left", $movimientos_list->RowCnt);
?>
	<?php if ($movimientos->id->Visible) { // id ?>
		<td data-name="id"<?php echo $movimientos->id->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_id" class="movimientos_id">
<span<?php echo $movimientos->id->ViewAttributes() ?>>
<?php echo $movimientos->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $movimientos_list->PageObjName . "_row_" . $movimientos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
		<td data-name="nroComprobanteCompleto"<?php echo $movimientos->nroComprobanteCompleto->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_nroComprobanteCompleto" class="movimientos_nroComprobanteCompleto">
<span<?php echo $movimientos->nroComprobanteCompleto->ViewAttributes() ?>>
<?php echo $movimientos->nroComprobanteCompleto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
		<td data-name="tipoMovimiento"<?php echo $movimientos->tipoMovimiento->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_tipoMovimiento" class="movimientos_tipoMovimiento">
<span<?php echo $movimientos->tipoMovimiento->ViewAttributes() ?>>
<?php echo $movimientos->tipoMovimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $movimientos->fecha->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_fecha" class="movimientos_fecha">
<span<?php echo $movimientos->fecha->ViewAttributes() ?>>
<?php echo $movimientos->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
		<td data-name="codTercero"<?php echo $movimientos->codTercero->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_codTercero" class="movimientos_codTercero">
<span<?php echo $movimientos->codTercero->ViewAttributes() ?>>
<?php echo $movimientos->codTercero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $movimientos->idTercero->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_idTercero" class="movimientos_idTercero">
<span<?php echo $movimientos->idTercero->ViewAttributes() ?>>
<?php echo $movimientos->idTercero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
		<td data-name="idComprobante"<?php echo $movimientos->idComprobante->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_idComprobante" class="movimientos_idComprobante">
<span<?php echo $movimientos->idComprobante->ViewAttributes() ?>>
<?php echo $movimientos->idComprobante->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
		<td data-name="importeTotal"<?php echo $movimientos->importeTotal->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_importeTotal" class="movimientos_importeTotal">
<span<?php echo $movimientos->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos->importeTotal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
		<td data-name="importeIva"<?php echo $movimientos->importeIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_importeIva" class="movimientos_importeIva">
<span<?php echo $movimientos->importeIva->ViewAttributes() ?>>
<?php echo $movimientos->importeIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
		<td data-name="importeNeto"<?php echo $movimientos->importeNeto->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_importeNeto" class="movimientos_importeNeto">
<span<?php echo $movimientos->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos->importeNeto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
		<td data-name="importeCancelado"<?php echo $movimientos->importeCancelado->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_importeCancelado" class="movimientos_importeCancelado">
<span<?php echo $movimientos->importeCancelado->ViewAttributes() ?>>
<?php echo $movimientos->importeCancelado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
		<td data-name="idEstado"<?php echo $movimientos->idEstado->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_idEstado" class="movimientos_idEstado">
<span<?php echo $movimientos->idEstado->ViewAttributes() ?>>
<?php echo $movimientos->idEstado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
		<td data-name="movimientosAsociados"<?php echo $movimientos->movimientosAsociados->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_movimientosAsociados" class="movimientos_movimientosAsociados">
<span<?php echo $movimientos->movimientosAsociados->ViewAttributes() ?>>
<?php echo $movimientos->movimientosAsociados->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
		<td data-name="condicionVenta"<?php echo $movimientos->condicionVenta->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_condicionVenta" class="movimientos_condicionVenta">
<span<?php echo $movimientos->condicionVenta->ViewAttributes() ?>>
<?php echo $movimientos->condicionVenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
		<td data-name="vigencia"<?php echo $movimientos->vigencia->CellAttributes() ?>>
<span id="el<?php echo $movimientos_list->RowCnt ?>_movimientos_vigencia" class="movimientos_vigencia">
<span<?php echo $movimientos->vigencia->ViewAttributes() ?>>
<?php echo $movimientos->vigencia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$movimientos_list->ListOptions->Render("body", "right", $movimientos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($movimientos->CurrentAction <> "gridadd")
		$movimientos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($movimientos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($movimientos_list->Recordset)
	$movimientos_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($movimientos_list->TotalRecs == 0 && $movimientos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($movimientos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($movimientos->Export == "") { ?>
<script type="text/javascript">
fmovimientoslistsrch.FilterList = <?php echo $movimientos_list->GetFilterList() ?>;
fmovimientoslistsrch.Init();
fmovimientoslist.Init();
</script>
<?php } ?>
<?php
$movimientos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($movimientos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$movimientos_list->Page_Terminate();
?>
