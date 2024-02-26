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

$movimientos2Ddetalle_list = NULL; // Initialize page object first

class cmovimientos2Ddetalle_list extends cmovimientos2Ddetalle {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos-detalle';

	// Page object name
	var $PageObjName = 'movimientos2Ddetalle_list';

	// Grid form hidden field names
	var $FormName = 'fmovimientos2Ddetallelist';
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

		// Table object (movimientos2Ddetalle)
		if (!isset($GLOBALS["movimientos2Ddetalle"]) || get_class($GLOBALS["movimientos2Ddetalle"]) == "cmovimientos2Ddetalle") {
			$GLOBALS["movimientos2Ddetalle"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["movimientos2Ddetalle"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "movimientos2Ddetalleadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "movimientos2Ddetalledelete.php";
		$this->MultiUpdateUrl = "movimientos2Ddetalleupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fmovimientos2Ddetallelistsrch";

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fmovimientos2Ddetallelistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->idMovimientos->AdvancedSearch->ToJSON(), ","); // Field idMovimientos
		$sFilterList = ew_Concat($sFilterList, $this->cant->AdvancedSearch->ToJSON(), ","); // Field cant
		$sFilterList = ew_Concat($sFilterList, $this->idUnidadMedida->AdvancedSearch->ToJSON(), ","); // Field idUnidadMedida
		$sFilterList = ew_Concat($sFilterList, $this->codProducto->AdvancedSearch->ToJSON(), ","); // Field codProducto
		$sFilterList = ew_Concat($sFilterList, $this->medida->AdvancedSearch->ToJSON(), ","); // Field medida
		$sFilterList = ew_Concat($sFilterList, $this->nombreProducto->AdvancedSearch->ToJSON(), ","); // Field nombreProducto
		$sFilterList = ew_Concat($sFilterList, $this->importeUnitario->AdvancedSearch->ToJSON(), ","); // Field importeUnitario
		$sFilterList = ew_Concat($sFilterList, $this->bonificacion->AdvancedSearch->ToJSON(), ","); // Field bonificacion
		$sFilterList = ew_Concat($sFilterList, $this->importeTotal->AdvancedSearch->ToJSON(), ","); // Field importeTotal
		$sFilterList = ew_Concat($sFilterList, $this->alicuotaIva->AdvancedSearch->ToJSON(), ","); // Field alicuotaIva
		$sFilterList = ew_Concat($sFilterList, $this->importeIva->AdvancedSearch->ToJSON(), ","); // Field importeIva
		$sFilterList = ew_Concat($sFilterList, $this->importeNeto->AdvancedSearch->ToJSON(), ","); // Field importeNeto
		$sFilterList = ew_Concat($sFilterList, $this->importePesos->AdvancedSearch->ToJSON(), ","); // Field importePesos
		$sFilterList = ew_Concat($sFilterList, $this->estadoImportacion->AdvancedSearch->ToJSON(), ","); // Field estadoImportacion
		$sFilterList = ew_Concat($sFilterList, $this->origenImportacion->AdvancedSearch->ToJSON(), ","); // Field origenImportacion
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fmovimientos2Ddetallelistsrch", $filters);
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

		// Field idMovimientos
		$this->idMovimientos->AdvancedSearch->SearchValue = @$filter["x_idMovimientos"];
		$this->idMovimientos->AdvancedSearch->SearchOperator = @$filter["z_idMovimientos"];
		$this->idMovimientos->AdvancedSearch->SearchCondition = @$filter["v_idMovimientos"];
		$this->idMovimientos->AdvancedSearch->SearchValue2 = @$filter["y_idMovimientos"];
		$this->idMovimientos->AdvancedSearch->SearchOperator2 = @$filter["w_idMovimientos"];
		$this->idMovimientos->AdvancedSearch->Save();

		// Field cant
		$this->cant->AdvancedSearch->SearchValue = @$filter["x_cant"];
		$this->cant->AdvancedSearch->SearchOperator = @$filter["z_cant"];
		$this->cant->AdvancedSearch->SearchCondition = @$filter["v_cant"];
		$this->cant->AdvancedSearch->SearchValue2 = @$filter["y_cant"];
		$this->cant->AdvancedSearch->SearchOperator2 = @$filter["w_cant"];
		$this->cant->AdvancedSearch->Save();

		// Field idUnidadMedida
		$this->idUnidadMedida->AdvancedSearch->SearchValue = @$filter["x_idUnidadMedida"];
		$this->idUnidadMedida->AdvancedSearch->SearchOperator = @$filter["z_idUnidadMedida"];
		$this->idUnidadMedida->AdvancedSearch->SearchCondition = @$filter["v_idUnidadMedida"];
		$this->idUnidadMedida->AdvancedSearch->SearchValue2 = @$filter["y_idUnidadMedida"];
		$this->idUnidadMedida->AdvancedSearch->SearchOperator2 = @$filter["w_idUnidadMedida"];
		$this->idUnidadMedida->AdvancedSearch->Save();

		// Field codProducto
		$this->codProducto->AdvancedSearch->SearchValue = @$filter["x_codProducto"];
		$this->codProducto->AdvancedSearch->SearchOperator = @$filter["z_codProducto"];
		$this->codProducto->AdvancedSearch->SearchCondition = @$filter["v_codProducto"];
		$this->codProducto->AdvancedSearch->SearchValue2 = @$filter["y_codProducto"];
		$this->codProducto->AdvancedSearch->SearchOperator2 = @$filter["w_codProducto"];
		$this->codProducto->AdvancedSearch->Save();

		// Field medida
		$this->medida->AdvancedSearch->SearchValue = @$filter["x_medida"];
		$this->medida->AdvancedSearch->SearchOperator = @$filter["z_medida"];
		$this->medida->AdvancedSearch->SearchCondition = @$filter["v_medida"];
		$this->medida->AdvancedSearch->SearchValue2 = @$filter["y_medida"];
		$this->medida->AdvancedSearch->SearchOperator2 = @$filter["w_medida"];
		$this->medida->AdvancedSearch->Save();

		// Field nombreProducto
		$this->nombreProducto->AdvancedSearch->SearchValue = @$filter["x_nombreProducto"];
		$this->nombreProducto->AdvancedSearch->SearchOperator = @$filter["z_nombreProducto"];
		$this->nombreProducto->AdvancedSearch->SearchCondition = @$filter["v_nombreProducto"];
		$this->nombreProducto->AdvancedSearch->SearchValue2 = @$filter["y_nombreProducto"];
		$this->nombreProducto->AdvancedSearch->SearchOperator2 = @$filter["w_nombreProducto"];
		$this->nombreProducto->AdvancedSearch->Save();

		// Field importeUnitario
		$this->importeUnitario->AdvancedSearch->SearchValue = @$filter["x_importeUnitario"];
		$this->importeUnitario->AdvancedSearch->SearchOperator = @$filter["z_importeUnitario"];
		$this->importeUnitario->AdvancedSearch->SearchCondition = @$filter["v_importeUnitario"];
		$this->importeUnitario->AdvancedSearch->SearchValue2 = @$filter["y_importeUnitario"];
		$this->importeUnitario->AdvancedSearch->SearchOperator2 = @$filter["w_importeUnitario"];
		$this->importeUnitario->AdvancedSearch->Save();

		// Field bonificacion
		$this->bonificacion->AdvancedSearch->SearchValue = @$filter["x_bonificacion"];
		$this->bonificacion->AdvancedSearch->SearchOperator = @$filter["z_bonificacion"];
		$this->bonificacion->AdvancedSearch->SearchCondition = @$filter["v_bonificacion"];
		$this->bonificacion->AdvancedSearch->SearchValue2 = @$filter["y_bonificacion"];
		$this->bonificacion->AdvancedSearch->SearchOperator2 = @$filter["w_bonificacion"];
		$this->bonificacion->AdvancedSearch->Save();

		// Field importeTotal
		$this->importeTotal->AdvancedSearch->SearchValue = @$filter["x_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator = @$filter["z_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchCondition = @$filter["v_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchValue2 = @$filter["y_importeTotal"];
		$this->importeTotal->AdvancedSearch->SearchOperator2 = @$filter["w_importeTotal"];
		$this->importeTotal->AdvancedSearch->Save();

		// Field alicuotaIva
		$this->alicuotaIva->AdvancedSearch->SearchValue = @$filter["x_alicuotaIva"];
		$this->alicuotaIva->AdvancedSearch->SearchOperator = @$filter["z_alicuotaIva"];
		$this->alicuotaIva->AdvancedSearch->SearchCondition = @$filter["v_alicuotaIva"];
		$this->alicuotaIva->AdvancedSearch->SearchValue2 = @$filter["y_alicuotaIva"];
		$this->alicuotaIva->AdvancedSearch->SearchOperator2 = @$filter["w_alicuotaIva"];
		$this->alicuotaIva->AdvancedSearch->Save();

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

		// Field importePesos
		$this->importePesos->AdvancedSearch->SearchValue = @$filter["x_importePesos"];
		$this->importePesos->AdvancedSearch->SearchOperator = @$filter["z_importePesos"];
		$this->importePesos->AdvancedSearch->SearchCondition = @$filter["v_importePesos"];
		$this->importePesos->AdvancedSearch->SearchValue2 = @$filter["y_importePesos"];
		$this->importePesos->AdvancedSearch->SearchOperator2 = @$filter["w_importePesos"];
		$this->importePesos->AdvancedSearch->Save();

		// Field estadoImportacion
		$this->estadoImportacion->AdvancedSearch->SearchValue = @$filter["x_estadoImportacion"];
		$this->estadoImportacion->AdvancedSearch->SearchOperator = @$filter["z_estadoImportacion"];
		$this->estadoImportacion->AdvancedSearch->SearchCondition = @$filter["v_estadoImportacion"];
		$this->estadoImportacion->AdvancedSearch->SearchValue2 = @$filter["y_estadoImportacion"];
		$this->estadoImportacion->AdvancedSearch->SearchOperator2 = @$filter["w_estadoImportacion"];
		$this->estadoImportacion->AdvancedSearch->Save();

		// Field origenImportacion
		$this->origenImportacion->AdvancedSearch->SearchValue = @$filter["x_origenImportacion"];
		$this->origenImportacion->AdvancedSearch->SearchOperator = @$filter["z_origenImportacion"];
		$this->origenImportacion->AdvancedSearch->SearchCondition = @$filter["v_origenImportacion"];
		$this->origenImportacion->AdvancedSearch->SearchValue2 = @$filter["y_origenImportacion"];
		$this->origenImportacion->AdvancedSearch->SearchOperator2 = @$filter["w_origenImportacion"];
		$this->origenImportacion->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombreProducto, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
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
			$this->UpdateSort($this->idMovimientos, $bCtrl); // idMovimientos
			$this->UpdateSort($this->cant, $bCtrl); // cant
			$this->UpdateSort($this->idUnidadMedida, $bCtrl); // idUnidadMedida
			$this->UpdateSort($this->codProducto, $bCtrl); // codProducto
			$this->UpdateSort($this->medida, $bCtrl); // medida
			$this->UpdateSort($this->nombreProducto, $bCtrl); // nombreProducto
			$this->UpdateSort($this->importeUnitario, $bCtrl); // importeUnitario
			$this->UpdateSort($this->bonificacion, $bCtrl); // bonificacion
			$this->UpdateSort($this->importeTotal, $bCtrl); // importeTotal
			$this->UpdateSort($this->alicuotaIva, $bCtrl); // alicuotaIva
			$this->UpdateSort($this->importeIva, $bCtrl); // importeIva
			$this->UpdateSort($this->importeNeto, $bCtrl); // importeNeto
			$this->UpdateSort($this->importePesos, $bCtrl); // importePesos
			$this->UpdateSort($this->estadoImportacion, $bCtrl); // estadoImportacion
			$this->UpdateSort($this->origenImportacion, $bCtrl); // origenImportacion
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
				$this->id->setSort("");
				$this->idMovimientos->setSort("");
				$this->cant->setSort("");
				$this->idUnidadMedida->setSort("");
				$this->codProducto->setSort("");
				$this->medida->setSort("");
				$this->nombreProducto->setSort("");
				$this->importeUnitario->setSort("");
				$this->bonificacion->setSort("");
				$this->importeTotal->setSort("");
				$this->alicuotaIva->setSort("");
				$this->importeIva->setSort("");
				$this->importeNeto->setSort("");
				$this->importePesos->setSort("");
				$this->estadoImportacion->setSort("");
				$this->origenImportacion->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
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

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmovimientos2Ddetallelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmovimientos2Ddetallelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmovimientos2Ddetallelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmovimientos2Ddetallelistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$item->Body = "<button id=\"emf_movimientos2Ddetalle\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_movimientos2Ddetalle',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmovimientos2Ddetallelist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

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
if (!isset($movimientos2Ddetalle_list)) $movimientos2Ddetalle_list = new cmovimientos2Ddetalle_list();

// Page init
$movimientos2Ddetalle_list->Page_Init();

// Page main
$movimientos2Ddetalle_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos2Ddetalle_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmovimientos2Ddetallelist = new ew_Form("fmovimientos2Ddetallelist", "list");
fmovimientos2Ddetallelist.FormKeyCountName = '<?php echo $movimientos2Ddetalle_list->FormKeyCountName ?>';

// Form_CustomValidate event
fmovimientos2Ddetallelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientos2Ddetallelist.ValidateRequired = true;
<?php } else { ?>
fmovimientos2Ddetallelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fmovimientos2Ddetallelistsrch = new ew_Form("fmovimientos2Ddetallelistsrch");

// Init search panel as collapsed
if (fmovimientos2Ddetallelistsrch) fmovimientos2Ddetallelistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<div class="ewToolbar">
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($movimientos2Ddetalle_list->TotalRecs > 0 && $movimientos2Ddetalle_list->ExportOptions->Visible()) { ?>
<?php $movimientos2Ddetalle_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos2Ddetalle_list->SearchOptions->Visible()) { ?>
<?php $movimientos2Ddetalle_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos2Ddetalle_list->FilterOptions->Visible()) { ?>
<?php $movimientos2Ddetalle_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $movimientos2Ddetalle_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($movimientos2Ddetalle_list->TotalRecs <= 0)
			$movimientos2Ddetalle_list->TotalRecs = $movimientos2Ddetalle->SelectRecordCount();
	} else {
		if (!$movimientos2Ddetalle_list->Recordset && ($movimientos2Ddetalle_list->Recordset = $movimientos2Ddetalle_list->LoadRecordset()))
			$movimientos2Ddetalle_list->TotalRecs = $movimientos2Ddetalle_list->Recordset->RecordCount();
	}
	$movimientos2Ddetalle_list->StartRec = 1;
	if ($movimientos2Ddetalle_list->DisplayRecs <= 0 || ($movimientos2Ddetalle->Export <> "" && $movimientos2Ddetalle->ExportAll)) // Display all records
		$movimientos2Ddetalle_list->DisplayRecs = $movimientos2Ddetalle_list->TotalRecs;
	if (!($movimientos2Ddetalle->Export <> "" && $movimientos2Ddetalle->ExportAll))
		$movimientos2Ddetalle_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$movimientos2Ddetalle_list->Recordset = $movimientos2Ddetalle_list->LoadRecordset($movimientos2Ddetalle_list->StartRec-1, $movimientos2Ddetalle_list->DisplayRecs);

	// Set no record found message
	if ($movimientos2Ddetalle->CurrentAction == "" && $movimientos2Ddetalle_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$movimientos2Ddetalle_list->setWarningMessage(ew_DeniedMsg());
		if ($movimientos2Ddetalle_list->SearchWhere == "0=101")
			$movimientos2Ddetalle_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$movimientos2Ddetalle_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$movimientos2Ddetalle_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($movimientos2Ddetalle->Export == "" && $movimientos2Ddetalle->CurrentAction == "") { ?>
<form name="fmovimientos2Ddetallelistsrch" id="fmovimientos2Ddetallelistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($movimientos2Ddetalle_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fmovimientos2Ddetallelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="movimientos2Ddetalle">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($movimientos2Ddetalle_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($movimientos2Ddetalle_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $movimientos2Ddetalle_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($movimientos2Ddetalle_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($movimientos2Ddetalle_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($movimientos2Ddetalle_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($movimientos2Ddetalle_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $movimientos2Ddetalle_list->ShowPageHeader(); ?>
<?php
$movimientos2Ddetalle_list->ShowMessage();
?>
<?php if ($movimientos2Ddetalle_list->TotalRecs > 0 || $movimientos2Ddetalle->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid movimientos2Ddetalle">
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($movimientos2Ddetalle->CurrentAction <> "gridadd" && $movimientos2Ddetalle->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($movimientos2Ddetalle_list->Pager)) $movimientos2Ddetalle_list->Pager = new cPrevNextPager($movimientos2Ddetalle_list->StartRec, $movimientos2Ddetalle_list->DisplayRecs, $movimientos2Ddetalle_list->TotalRecs) ?>
<?php if ($movimientos2Ddetalle_list->Pager->RecordCount > 0 && $movimientos2Ddetalle_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($movimientos2Ddetalle_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $movimientos2Ddetalle_list->PageUrl() ?>start=<?php echo $movimientos2Ddetalle_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($movimientos2Ddetalle_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $movimientos2Ddetalle_list->PageUrl() ?>start=<?php echo $movimientos2Ddetalle_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $movimientos2Ddetalle_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($movimientos2Ddetalle_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $movimientos2Ddetalle_list->PageUrl() ?>start=<?php echo $movimientos2Ddetalle_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($movimientos2Ddetalle_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $movimientos2Ddetalle_list->PageUrl() ?>start=<?php echo $movimientos2Ddetalle_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $movimientos2Ddetalle_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $movimientos2Ddetalle_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $movimientos2Ddetalle_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $movimientos2Ddetalle_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($movimientos2Ddetalle_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="movimientos2Ddetalle">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($movimientos2Ddetalle_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($movimientos2Ddetalle_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($movimientos2Ddetalle_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($movimientos2Ddetalle_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($movimientos2Ddetalle_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($movimientos2Ddetalle_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmovimientos2Ddetallelist" id="fmovimientos2Ddetallelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos2Ddetalle_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos2Ddetalle_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos2Ddetalle">
<div id="gmp_movimientos2Ddetalle" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($movimientos2Ddetalle_list->TotalRecs > 0) { ?>
<table id="tbl_movimientos2Ddetallelist" class="table ewTable">
<?php echo $movimientos2Ddetalle->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$movimientos2Ddetalle_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$movimientos2Ddetalle_list->RenderListOptions();

// Render list options (header, left)
$movimientos2Ddetalle_list->ListOptions->Render("header", "left");
?>
<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->id) == "") { ?>
		<th data-name="id"><div id="elh_movimientos2Ddetalle_id" class="movimientos2Ddetalle_id"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->id) ?>',2);"><div id="elh_movimientos2Ddetalle_id" class="movimientos2Ddetalle_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->idMovimientos) == "") { ?>
		<th data-name="idMovimientos"><div id="elh_movimientos2Ddetalle_idMovimientos" class="movimientos2Ddetalle_idMovimientos"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->idMovimientos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idMovimientos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->idMovimientos) ?>',2);"><div id="elh_movimientos2Ddetalle_idMovimientos" class="movimientos2Ddetalle_idMovimientos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->idMovimientos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->idMovimientos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->idMovimientos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->cant) == "") { ?>
		<th data-name="cant"><div id="elh_movimientos2Ddetalle_cant" class="movimientos2Ddetalle_cant"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->cant->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cant"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->cant) ?>',2);"><div id="elh_movimientos2Ddetalle_cant" class="movimientos2Ddetalle_cant">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->cant->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->cant->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->cant->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->idUnidadMedida) == "") { ?>
		<th data-name="idUnidadMedida"><div id="elh_movimientos2Ddetalle_idUnidadMedida" class="movimientos2Ddetalle_idUnidadMedida"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->idUnidadMedida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idUnidadMedida"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->idUnidadMedida) ?>',2);"><div id="elh_movimientos2Ddetalle_idUnidadMedida" class="movimientos2Ddetalle_idUnidadMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->idUnidadMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->idUnidadMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->idUnidadMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->codProducto) == "") { ?>
		<th data-name="codProducto"><div id="elh_movimientos2Ddetalle_codProducto" class="movimientos2Ddetalle_codProducto"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->codProducto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codProducto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->codProducto) ?>',2);"><div id="elh_movimientos2Ddetalle_codProducto" class="movimientos2Ddetalle_codProducto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->codProducto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->codProducto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->codProducto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->medida) == "") { ?>
		<th data-name="medida"><div id="elh_movimientos2Ddetalle_medida" class="movimientos2Ddetalle_medida"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->medida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="medida"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->medida) ?>',2);"><div id="elh_movimientos2Ddetalle_medida" class="movimientos2Ddetalle_medida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->medida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->medida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->medida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->nombreProducto) == "") { ?>
		<th data-name="nombreProducto"><div id="elh_movimientos2Ddetalle_nombreProducto" class="movimientos2Ddetalle_nombreProducto"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->nombreProducto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombreProducto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->nombreProducto) ?>',2);"><div id="elh_movimientos2Ddetalle_nombreProducto" class="movimientos2Ddetalle_nombreProducto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->nombreProducto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->nombreProducto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->nombreProducto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeUnitario) == "") { ?>
		<th data-name="importeUnitario"><div id="elh_movimientos2Ddetalle_importeUnitario" class="movimientos2Ddetalle_importeUnitario"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeUnitario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeUnitario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeUnitario) ?>',2);"><div id="elh_movimientos2Ddetalle_importeUnitario" class="movimientos2Ddetalle_importeUnitario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeUnitario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->importeUnitario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->importeUnitario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->bonificacion) == "") { ?>
		<th data-name="bonificacion"><div id="elh_movimientos2Ddetalle_bonificacion" class="movimientos2Ddetalle_bonificacion"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->bonificacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bonificacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->bonificacion) ?>',2);"><div id="elh_movimientos2Ddetalle_bonificacion" class="movimientos2Ddetalle_bonificacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->bonificacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->bonificacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->bonificacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeTotal) == "") { ?>
		<th data-name="importeTotal"><div id="elh_movimientos2Ddetalle_importeTotal" class="movimientos2Ddetalle_importeTotal"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeTotal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeTotal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeTotal) ?>',2);"><div id="elh_movimientos2Ddetalle_importeTotal" class="movimientos2Ddetalle_importeTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->importeTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->importeTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->alicuotaIva) == "") { ?>
		<th data-name="alicuotaIva"><div id="elh_movimientos2Ddetalle_alicuotaIva" class="movimientos2Ddetalle_alicuotaIva"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->alicuotaIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alicuotaIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->alicuotaIva) ?>',2);"><div id="elh_movimientos2Ddetalle_alicuotaIva" class="movimientos2Ddetalle_alicuotaIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->alicuotaIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->alicuotaIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->alicuotaIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeIva) == "") { ?>
		<th data-name="importeIva"><div id="elh_movimientos2Ddetalle_importeIva" class="movimientos2Ddetalle_importeIva"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeIva) ?>',2);"><div id="elh_movimientos2Ddetalle_importeIva" class="movimientos2Ddetalle_importeIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->importeIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->importeIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeNeto) == "") { ?>
		<th data-name="importeNeto"><div id="elh_movimientos2Ddetalle_importeNeto" class="movimientos2Ddetalle_importeNeto"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeNeto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importeNeto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importeNeto) ?>',2);"><div id="elh_movimientos2Ddetalle_importeNeto" class="movimientos2Ddetalle_importeNeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importeNeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->importeNeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->importeNeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importePesos) == "") { ?>
		<th data-name="importePesos"><div id="elh_movimientos2Ddetalle_importePesos" class="movimientos2Ddetalle_importePesos"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importePesos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="importePesos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->importePesos) ?>',2);"><div id="elh_movimientos2Ddetalle_importePesos" class="movimientos2Ddetalle_importePesos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->importePesos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->importePesos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->importePesos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->estadoImportacion) == "") { ?>
		<th data-name="estadoImportacion"><div id="elh_movimientos2Ddetalle_estadoImportacion" class="movimientos2Ddetalle_estadoImportacion"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->estadoImportacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estadoImportacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->estadoImportacion) ?>',2);"><div id="elh_movimientos2Ddetalle_estadoImportacion" class="movimientos2Ddetalle_estadoImportacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->estadoImportacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->estadoImportacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->estadoImportacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
	<?php if ($movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->origenImportacion) == "") { ?>
		<th data-name="origenImportacion"><div id="elh_movimientos2Ddetalle_origenImportacion" class="movimientos2Ddetalle_origenImportacion"><div class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->origenImportacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="origenImportacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $movimientos2Ddetalle->SortUrl($movimientos2Ddetalle->origenImportacion) ?>',2);"><div id="elh_movimientos2Ddetalle_origenImportacion" class="movimientos2Ddetalle_origenImportacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $movimientos2Ddetalle->origenImportacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($movimientos2Ddetalle->origenImportacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($movimientos2Ddetalle->origenImportacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$movimientos2Ddetalle_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($movimientos2Ddetalle->ExportAll && $movimientos2Ddetalle->Export <> "") {
	$movimientos2Ddetalle_list->StopRec = $movimientos2Ddetalle_list->TotalRecs;
} else {

	// Set the last record to display
	if ($movimientos2Ddetalle_list->TotalRecs > $movimientos2Ddetalle_list->StartRec + $movimientos2Ddetalle_list->DisplayRecs - 1)
		$movimientos2Ddetalle_list->StopRec = $movimientos2Ddetalle_list->StartRec + $movimientos2Ddetalle_list->DisplayRecs - 1;
	else
		$movimientos2Ddetalle_list->StopRec = $movimientos2Ddetalle_list->TotalRecs;
}
$movimientos2Ddetalle_list->RecCnt = $movimientos2Ddetalle_list->StartRec - 1;
if ($movimientos2Ddetalle_list->Recordset && !$movimientos2Ddetalle_list->Recordset->EOF) {
	$movimientos2Ddetalle_list->Recordset->MoveFirst();
	$bSelectLimit = $movimientos2Ddetalle_list->UseSelectLimit;
	if (!$bSelectLimit && $movimientos2Ddetalle_list->StartRec > 1)
		$movimientos2Ddetalle_list->Recordset->Move($movimientos2Ddetalle_list->StartRec - 1);
} elseif (!$movimientos2Ddetalle->AllowAddDeleteRow && $movimientos2Ddetalle_list->StopRec == 0) {
	$movimientos2Ddetalle_list->StopRec = $movimientos2Ddetalle->GridAddRowCount;
}

// Initialize aggregate
$movimientos2Ddetalle->RowType = EW_ROWTYPE_AGGREGATEINIT;
$movimientos2Ddetalle->ResetAttrs();
$movimientos2Ddetalle_list->RenderRow();
while ($movimientos2Ddetalle_list->RecCnt < $movimientos2Ddetalle_list->StopRec) {
	$movimientos2Ddetalle_list->RecCnt++;
	if (intval($movimientos2Ddetalle_list->RecCnt) >= intval($movimientos2Ddetalle_list->StartRec)) {
		$movimientos2Ddetalle_list->RowCnt++;

		// Set up key count
		$movimientos2Ddetalle_list->KeyCount = $movimientos2Ddetalle_list->RowIndex;

		// Init row class and style
		$movimientos2Ddetalle->ResetAttrs();
		$movimientos2Ddetalle->CssClass = "";
		if ($movimientos2Ddetalle->CurrentAction == "gridadd") {
		} else {
			$movimientos2Ddetalle_list->LoadRowValues($movimientos2Ddetalle_list->Recordset); // Load row values
		}
		$movimientos2Ddetalle->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$movimientos2Ddetalle->RowAttrs = array_merge($movimientos2Ddetalle->RowAttrs, array('data-rowindex'=>$movimientos2Ddetalle_list->RowCnt, 'id'=>'r' . $movimientos2Ddetalle_list->RowCnt . '_movimientos2Ddetalle', 'data-rowtype'=>$movimientos2Ddetalle->RowType));

		// Render row
		$movimientos2Ddetalle_list->RenderRow();

		// Render list options
		$movimientos2Ddetalle_list->RenderListOptions();
?>
	<tr<?php echo $movimientos2Ddetalle->RowAttributes() ?>>
<?php

// Render list options (body, left)
$movimientos2Ddetalle_list->ListOptions->Render("body", "left", $movimientos2Ddetalle_list->RowCnt);
?>
	<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
		<td data-name="id"<?php echo $movimientos2Ddetalle->id->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_id" class="movimientos2Ddetalle_id">
<span<?php echo $movimientos2Ddetalle->id->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $movimientos2Ddetalle_list->PageObjName . "_row_" . $movimientos2Ddetalle_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
		<td data-name="idMovimientos"<?php echo $movimientos2Ddetalle->idMovimientos->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_idMovimientos" class="movimientos2Ddetalle_idMovimientos">
<span<?php echo $movimientos2Ddetalle->idMovimientos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idMovimientos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
		<td data-name="cant"<?php echo $movimientos2Ddetalle->cant->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_cant" class="movimientos2Ddetalle_cant">
<span<?php echo $movimientos2Ddetalle->cant->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->cant->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida"<?php echo $movimientos2Ddetalle->idUnidadMedida->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_idUnidadMedida" class="movimientos2Ddetalle_idUnidadMedida">
<span<?php echo $movimientos2Ddetalle->idUnidadMedida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idUnidadMedida->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
		<td data-name="codProducto"<?php echo $movimientos2Ddetalle->codProducto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_codProducto" class="movimientos2Ddetalle_codProducto">
<span<?php echo $movimientos2Ddetalle->codProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->codProducto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
		<td data-name="medida"<?php echo $movimientos2Ddetalle->medida->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_medida" class="movimientos2Ddetalle_medida">
<span<?php echo $movimientos2Ddetalle->medida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->medida->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
		<td data-name="nombreProducto"<?php echo $movimientos2Ddetalle->nombreProducto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_nombreProducto" class="movimientos2Ddetalle_nombreProducto">
<span<?php echo $movimientos2Ddetalle->nombreProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->nombreProducto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
		<td data-name="importeUnitario"<?php echo $movimientos2Ddetalle->importeUnitario->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_importeUnitario" class="movimientos2Ddetalle_importeUnitario">
<span<?php echo $movimientos2Ddetalle->importeUnitario->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeUnitario->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
		<td data-name="bonificacion"<?php echo $movimientos2Ddetalle->bonificacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_bonificacion" class="movimientos2Ddetalle_bonificacion">
<span<?php echo $movimientos2Ddetalle->bonificacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->bonificacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
		<td data-name="importeTotal"<?php echo $movimientos2Ddetalle->importeTotal->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_importeTotal" class="movimientos2Ddetalle_importeTotal">
<span<?php echo $movimientos2Ddetalle->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeTotal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
		<td data-name="alicuotaIva"<?php echo $movimientos2Ddetalle->alicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_alicuotaIva" class="movimientos2Ddetalle_alicuotaIva">
<span<?php echo $movimientos2Ddetalle->alicuotaIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->alicuotaIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
		<td data-name="importeIva"<?php echo $movimientos2Ddetalle->importeIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_importeIva" class="movimientos2Ddetalle_importeIva">
<span<?php echo $movimientos2Ddetalle->importeIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
		<td data-name="importeNeto"<?php echo $movimientos2Ddetalle->importeNeto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_importeNeto" class="movimientos2Ddetalle_importeNeto">
<span<?php echo $movimientos2Ddetalle->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeNeto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
		<td data-name="importePesos"<?php echo $movimientos2Ddetalle->importePesos->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_importePesos" class="movimientos2Ddetalle_importePesos">
<span<?php echo $movimientos2Ddetalle->importePesos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importePesos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
		<td data-name="estadoImportacion"<?php echo $movimientos2Ddetalle->estadoImportacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_estadoImportacion" class="movimientos2Ddetalle_estadoImportacion">
<span<?php echo $movimientos2Ddetalle->estadoImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->estadoImportacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
		<td data-name="origenImportacion"<?php echo $movimientos2Ddetalle->origenImportacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_list->RowCnt ?>_movimientos2Ddetalle_origenImportacion" class="movimientos2Ddetalle_origenImportacion">
<span<?php echo $movimientos2Ddetalle->origenImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->origenImportacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$movimientos2Ddetalle_list->ListOptions->Render("body", "right", $movimientos2Ddetalle_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($movimientos2Ddetalle->CurrentAction <> "gridadd")
		$movimientos2Ddetalle_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($movimientos2Ddetalle->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($movimientos2Ddetalle_list->Recordset)
	$movimientos2Ddetalle_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($movimientos2Ddetalle_list->TotalRecs == 0 && $movimientos2Ddetalle->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($movimientos2Ddetalle_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($movimientos2Ddetalle->Export == "") { ?>
<script type="text/javascript">
fmovimientos2Ddetallelistsrch.FilterList = <?php echo $movimientos2Ddetalle_list->GetFilterList() ?>;
fmovimientos2Ddetallelistsrch.Init();
fmovimientos2Ddetallelist.Init();
</script>
<?php } ?>
<?php
$movimientos2Ddetalle_list->ShowPageFooter();
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
$movimientos2Ddetalle_list->Page_Terminate();
?>
