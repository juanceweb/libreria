<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "articulosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "articulos2Dproveedoresgridcls.php" ?>
<?php include_once "articulos2Dterceros2Ddescuentosgridcls.php" ?>
<?php include_once "articulos2Dstockgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$articulos_list = NULL; // Initialize page object first

class carticulos_list extends carticulos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos';

	// Page object name
	var $PageObjName = 'articulos_list';

	// Grid form hidden field names
	var $FormName = 'farticuloslist';
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

		// Table object (articulos)
		if (!isset($GLOBALS["articulos"]) || get_class($GLOBALS["articulos"]) == "carticulos") {
			$GLOBALS["articulos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "articulosadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "articulosdelete.php";
		$this->MultiUpdateUrl = "articulosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'articulos', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption farticuloslistsrch";

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
		$this->denominacionExterna->SetVisibility();
		$this->denominacionInterna->SetVisibility();
		$this->idAlicuotaIva->SetVisibility();
		$this->idCategoria->SetVisibility();
		$this->idSubcateogoria->SetVisibility();
		$this->idRubro->SetVisibility();
		$this->idMarca->SetVisibility();
		$this->idPrecioCompra->SetVisibility();
		$this->proveedor->SetVisibility();
		$this->calculoPrecio->SetVisibility();
		$this->rentabilidad->SetVisibility();
		$this->precioVenta->SetVisibility();

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

			// Process auto fill for detail table 'articulos-proveedores'
			if (@$_POST["grid"] == "farticulos2Dproveedoresgrid") {
				if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;
				$GLOBALS["articulos2Dproveedores_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'articulos-terceros-descuentos'
			if (@$_POST["grid"] == "farticulos2Dterceros2Ddescuentosgrid") {
				if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid;
				$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'articulos-stock'
			if (@$_POST["grid"] == "farticulos2Dstockgrid") {
				if (!isset($GLOBALS["articulos2Dstock_grid"])) $GLOBALS["articulos2Dstock_grid"] = new carticulos2Dstock_grid;
				$GLOBALS["articulos2Dstock_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $articulos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($articulos);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "farticuloslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->denominacionExterna->AdvancedSearch->ToJSON(), ","); // Field denominacionExterna
		$sFilterList = ew_Concat($sFilterList, $this->denominacionInterna->AdvancedSearch->ToJSON(), ","); // Field denominacionInterna
		$sFilterList = ew_Concat($sFilterList, $this->idAlicuotaIva->AdvancedSearch->ToJSON(), ","); // Field idAlicuotaIva
		$sFilterList = ew_Concat($sFilterList, $this->idCategoria->AdvancedSearch->ToJSON(), ","); // Field idCategoria
		$sFilterList = ew_Concat($sFilterList, $this->idSubcateogoria->AdvancedSearch->ToJSON(), ","); // Field idSubcateogoria
		$sFilterList = ew_Concat($sFilterList, $this->idRubro->AdvancedSearch->ToJSON(), ","); // Field idRubro
		$sFilterList = ew_Concat($sFilterList, $this->idMarca->AdvancedSearch->ToJSON(), ","); // Field idMarca
		$sFilterList = ew_Concat($sFilterList, $this->codigoBarras->AdvancedSearch->ToJSON(), ","); // Field codigoBarras
		$sFilterList = ew_Concat($sFilterList, $this->imagenes->AdvancedSearch->ToJSON(), ","); // Field imagenes
		$sFilterList = ew_Concat($sFilterList, $this->idPrecioCompra->AdvancedSearch->ToJSON(), ","); // Field idPrecioCompra
		$sFilterList = ew_Concat($sFilterList, $this->proveedor->AdvancedSearch->ToJSON(), ","); // Field proveedor
		$sFilterList = ew_Concat($sFilterList, $this->calculoPrecio->AdvancedSearch->ToJSON(), ","); // Field calculoPrecio
		$sFilterList = ew_Concat($sFilterList, $this->rentabilidad->AdvancedSearch->ToJSON(), ","); // Field rentabilidad
		$sFilterList = ew_Concat($sFilterList, $this->precioVenta->AdvancedSearch->ToJSON(), ","); // Field precioVenta
		$sFilterList = ew_Concat($sFilterList, $this->tags->AdvancedSearch->ToJSON(), ","); // Field tags
		$sFilterList = ew_Concat($sFilterList, $this->detalle->AdvancedSearch->ToJSON(), ","); // Field detalle
		$sFilterList = ew_Concat($sFilterList, $this->idUnidadMedidaCompra->AdvancedSearch->ToJSON(), ","); // Field idUnidadMedidaCompra
		$sFilterList = ew_Concat($sFilterList, $this->idUnidadMedidaVenta->AdvancedSearch->ToJSON(), ","); // Field idUnidadMedidaVenta
		$sFilterList = ew_Concat($sFilterList, $this->codigosExternos->AdvancedSearch->ToJSON(), ","); // Field codigosExternos
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "farticuloslistsrch", $filters);
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

		// Field denominacionExterna
		$this->denominacionExterna->AdvancedSearch->SearchValue = @$filter["x_denominacionExterna"];
		$this->denominacionExterna->AdvancedSearch->SearchOperator = @$filter["z_denominacionExterna"];
		$this->denominacionExterna->AdvancedSearch->SearchCondition = @$filter["v_denominacionExterna"];
		$this->denominacionExterna->AdvancedSearch->SearchValue2 = @$filter["y_denominacionExterna"];
		$this->denominacionExterna->AdvancedSearch->SearchOperator2 = @$filter["w_denominacionExterna"];
		$this->denominacionExterna->AdvancedSearch->Save();

		// Field denominacionInterna
		$this->denominacionInterna->AdvancedSearch->SearchValue = @$filter["x_denominacionInterna"];
		$this->denominacionInterna->AdvancedSearch->SearchOperator = @$filter["z_denominacionInterna"];
		$this->denominacionInterna->AdvancedSearch->SearchCondition = @$filter["v_denominacionInterna"];
		$this->denominacionInterna->AdvancedSearch->SearchValue2 = @$filter["y_denominacionInterna"];
		$this->denominacionInterna->AdvancedSearch->SearchOperator2 = @$filter["w_denominacionInterna"];
		$this->denominacionInterna->AdvancedSearch->Save();

		// Field idAlicuotaIva
		$this->idAlicuotaIva->AdvancedSearch->SearchValue = @$filter["x_idAlicuotaIva"];
		$this->idAlicuotaIva->AdvancedSearch->SearchOperator = @$filter["z_idAlicuotaIva"];
		$this->idAlicuotaIva->AdvancedSearch->SearchCondition = @$filter["v_idAlicuotaIva"];
		$this->idAlicuotaIva->AdvancedSearch->SearchValue2 = @$filter["y_idAlicuotaIva"];
		$this->idAlicuotaIva->AdvancedSearch->SearchOperator2 = @$filter["w_idAlicuotaIva"];
		$this->idAlicuotaIva->AdvancedSearch->Save();

		// Field idCategoria
		$this->idCategoria->AdvancedSearch->SearchValue = @$filter["x_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchOperator = @$filter["z_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchCondition = @$filter["v_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchValue2 = @$filter["y_idCategoria"];
		$this->idCategoria->AdvancedSearch->SearchOperator2 = @$filter["w_idCategoria"];
		$this->idCategoria->AdvancedSearch->Save();

		// Field idSubcateogoria
		$this->idSubcateogoria->AdvancedSearch->SearchValue = @$filter["x_idSubcateogoria"];
		$this->idSubcateogoria->AdvancedSearch->SearchOperator = @$filter["z_idSubcateogoria"];
		$this->idSubcateogoria->AdvancedSearch->SearchCondition = @$filter["v_idSubcateogoria"];
		$this->idSubcateogoria->AdvancedSearch->SearchValue2 = @$filter["y_idSubcateogoria"];
		$this->idSubcateogoria->AdvancedSearch->SearchOperator2 = @$filter["w_idSubcateogoria"];
		$this->idSubcateogoria->AdvancedSearch->Save();

		// Field idRubro
		$this->idRubro->AdvancedSearch->SearchValue = @$filter["x_idRubro"];
		$this->idRubro->AdvancedSearch->SearchOperator = @$filter["z_idRubro"];
		$this->idRubro->AdvancedSearch->SearchCondition = @$filter["v_idRubro"];
		$this->idRubro->AdvancedSearch->SearchValue2 = @$filter["y_idRubro"];
		$this->idRubro->AdvancedSearch->SearchOperator2 = @$filter["w_idRubro"];
		$this->idRubro->AdvancedSearch->Save();

		// Field idMarca
		$this->idMarca->AdvancedSearch->SearchValue = @$filter["x_idMarca"];
		$this->idMarca->AdvancedSearch->SearchOperator = @$filter["z_idMarca"];
		$this->idMarca->AdvancedSearch->SearchCondition = @$filter["v_idMarca"];
		$this->idMarca->AdvancedSearch->SearchValue2 = @$filter["y_idMarca"];
		$this->idMarca->AdvancedSearch->SearchOperator2 = @$filter["w_idMarca"];
		$this->idMarca->AdvancedSearch->Save();

		// Field codigoBarras
		$this->codigoBarras->AdvancedSearch->SearchValue = @$filter["x_codigoBarras"];
		$this->codigoBarras->AdvancedSearch->SearchOperator = @$filter["z_codigoBarras"];
		$this->codigoBarras->AdvancedSearch->SearchCondition = @$filter["v_codigoBarras"];
		$this->codigoBarras->AdvancedSearch->SearchValue2 = @$filter["y_codigoBarras"];
		$this->codigoBarras->AdvancedSearch->SearchOperator2 = @$filter["w_codigoBarras"];
		$this->codigoBarras->AdvancedSearch->Save();

		// Field imagenes
		$this->imagenes->AdvancedSearch->SearchValue = @$filter["x_imagenes"];
		$this->imagenes->AdvancedSearch->SearchOperator = @$filter["z_imagenes"];
		$this->imagenes->AdvancedSearch->SearchCondition = @$filter["v_imagenes"];
		$this->imagenes->AdvancedSearch->SearchValue2 = @$filter["y_imagenes"];
		$this->imagenes->AdvancedSearch->SearchOperator2 = @$filter["w_imagenes"];
		$this->imagenes->AdvancedSearch->Save();

		// Field idPrecioCompra
		$this->idPrecioCompra->AdvancedSearch->SearchValue = @$filter["x_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchOperator = @$filter["z_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchCondition = @$filter["v_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchValue2 = @$filter["y_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchOperator2 = @$filter["w_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->Save();

		// Field proveedor
		$this->proveedor->AdvancedSearch->SearchValue = @$filter["x_proveedor"];
		$this->proveedor->AdvancedSearch->SearchOperator = @$filter["z_proveedor"];
		$this->proveedor->AdvancedSearch->SearchCondition = @$filter["v_proveedor"];
		$this->proveedor->AdvancedSearch->SearchValue2 = @$filter["y_proveedor"];
		$this->proveedor->AdvancedSearch->SearchOperator2 = @$filter["w_proveedor"];
		$this->proveedor->AdvancedSearch->Save();

		// Field calculoPrecio
		$this->calculoPrecio->AdvancedSearch->SearchValue = @$filter["x_calculoPrecio"];
		$this->calculoPrecio->AdvancedSearch->SearchOperator = @$filter["z_calculoPrecio"];
		$this->calculoPrecio->AdvancedSearch->SearchCondition = @$filter["v_calculoPrecio"];
		$this->calculoPrecio->AdvancedSearch->SearchValue2 = @$filter["y_calculoPrecio"];
		$this->calculoPrecio->AdvancedSearch->SearchOperator2 = @$filter["w_calculoPrecio"];
		$this->calculoPrecio->AdvancedSearch->Save();

		// Field rentabilidad
		$this->rentabilidad->AdvancedSearch->SearchValue = @$filter["x_rentabilidad"];
		$this->rentabilidad->AdvancedSearch->SearchOperator = @$filter["z_rentabilidad"];
		$this->rentabilidad->AdvancedSearch->SearchCondition = @$filter["v_rentabilidad"];
		$this->rentabilidad->AdvancedSearch->SearchValue2 = @$filter["y_rentabilidad"];
		$this->rentabilidad->AdvancedSearch->SearchOperator2 = @$filter["w_rentabilidad"];
		$this->rentabilidad->AdvancedSearch->Save();

		// Field precioVenta
		$this->precioVenta->AdvancedSearch->SearchValue = @$filter["x_precioVenta"];
		$this->precioVenta->AdvancedSearch->SearchOperator = @$filter["z_precioVenta"];
		$this->precioVenta->AdvancedSearch->SearchCondition = @$filter["v_precioVenta"];
		$this->precioVenta->AdvancedSearch->SearchValue2 = @$filter["y_precioVenta"];
		$this->precioVenta->AdvancedSearch->SearchOperator2 = @$filter["w_precioVenta"];
		$this->precioVenta->AdvancedSearch->Save();

		// Field tags
		$this->tags->AdvancedSearch->SearchValue = @$filter["x_tags"];
		$this->tags->AdvancedSearch->SearchOperator = @$filter["z_tags"];
		$this->tags->AdvancedSearch->SearchCondition = @$filter["v_tags"];
		$this->tags->AdvancedSearch->SearchValue2 = @$filter["y_tags"];
		$this->tags->AdvancedSearch->SearchOperator2 = @$filter["w_tags"];
		$this->tags->AdvancedSearch->Save();

		// Field detalle
		$this->detalle->AdvancedSearch->SearchValue = @$filter["x_detalle"];
		$this->detalle->AdvancedSearch->SearchOperator = @$filter["z_detalle"];
		$this->detalle->AdvancedSearch->SearchCondition = @$filter["v_detalle"];
		$this->detalle->AdvancedSearch->SearchValue2 = @$filter["y_detalle"];
		$this->detalle->AdvancedSearch->SearchOperator2 = @$filter["w_detalle"];
		$this->detalle->AdvancedSearch->Save();

		// Field idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchValue = @$filter["x_idUnidadMedidaCompra"];
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchOperator = @$filter["z_idUnidadMedidaCompra"];
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchCondition = @$filter["v_idUnidadMedidaCompra"];
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchValue2 = @$filter["y_idUnidadMedidaCompra"];
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchOperator2 = @$filter["w_idUnidadMedidaCompra"];
		$this->idUnidadMedidaCompra->AdvancedSearch->Save();

		// Field idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchValue = @$filter["x_idUnidadMedidaVenta"];
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchOperator = @$filter["z_idUnidadMedidaVenta"];
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchCondition = @$filter["v_idUnidadMedidaVenta"];
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchValue2 = @$filter["y_idUnidadMedidaVenta"];
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchOperator2 = @$filter["w_idUnidadMedidaVenta"];
		$this->idUnidadMedidaVenta->AdvancedSearch->Save();

		// Field codigosExternos
		$this->codigosExternos->AdvancedSearch->SearchValue = @$filter["x_codigosExternos"];
		$this->codigosExternos->AdvancedSearch->SearchOperator = @$filter["z_codigosExternos"];
		$this->codigosExternos->AdvancedSearch->SearchCondition = @$filter["v_codigosExternos"];
		$this->codigosExternos->AdvancedSearch->SearchValue2 = @$filter["y_codigosExternos"];
		$this->codigosExternos->AdvancedSearch->SearchOperator2 = @$filter["w_codigosExternos"];
		$this->codigosExternos->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->denominacionExterna, $Default, FALSE); // denominacionExterna
		$this->BuildSearchSql($sWhere, $this->denominacionInterna, $Default, FALSE); // denominacionInterna
		$this->BuildSearchSql($sWhere, $this->idAlicuotaIva, $Default, FALSE); // idAlicuotaIva
		$this->BuildSearchSql($sWhere, $this->idCategoria, $Default, FALSE); // idCategoria
		$this->BuildSearchSql($sWhere, $this->idSubcateogoria, $Default, FALSE); // idSubcateogoria
		$this->BuildSearchSql($sWhere, $this->idRubro, $Default, FALSE); // idRubro
		$this->BuildSearchSql($sWhere, $this->idMarca, $Default, FALSE); // idMarca
		$this->BuildSearchSql($sWhere, $this->codigoBarras, $Default, FALSE); // codigoBarras
		$this->BuildSearchSql($sWhere, $this->imagenes, $Default, FALSE); // imagenes
		$this->BuildSearchSql($sWhere, $this->idPrecioCompra, $Default, FALSE); // idPrecioCompra
		$this->BuildSearchSql($sWhere, $this->proveedor, $Default, FALSE); // proveedor
		$this->BuildSearchSql($sWhere, $this->calculoPrecio, $Default, FALSE); // calculoPrecio
		$this->BuildSearchSql($sWhere, $this->rentabilidad, $Default, FALSE); // rentabilidad
		$this->BuildSearchSql($sWhere, $this->precioVenta, $Default, FALSE); // precioVenta
		$this->BuildSearchSql($sWhere, $this->tags, $Default, FALSE); // tags
		$this->BuildSearchSql($sWhere, $this->detalle, $Default, FALSE); // detalle
		$this->BuildSearchSql($sWhere, $this->idUnidadMedidaCompra, $Default, FALSE); // idUnidadMedidaCompra
		$this->BuildSearchSql($sWhere, $this->idUnidadMedidaVenta, $Default, FALSE); // idUnidadMedidaVenta
		$this->BuildSearchSql($sWhere, $this->codigosExternos, $Default, FALSE); // codigosExternos

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->denominacionExterna->AdvancedSearch->Save(); // denominacionExterna
			$this->denominacionInterna->AdvancedSearch->Save(); // denominacionInterna
			$this->idAlicuotaIva->AdvancedSearch->Save(); // idAlicuotaIva
			$this->idCategoria->AdvancedSearch->Save(); // idCategoria
			$this->idSubcateogoria->AdvancedSearch->Save(); // idSubcateogoria
			$this->idRubro->AdvancedSearch->Save(); // idRubro
			$this->idMarca->AdvancedSearch->Save(); // idMarca
			$this->codigoBarras->AdvancedSearch->Save(); // codigoBarras
			$this->imagenes->AdvancedSearch->Save(); // imagenes
			$this->idPrecioCompra->AdvancedSearch->Save(); // idPrecioCompra
			$this->proveedor->AdvancedSearch->Save(); // proveedor
			$this->calculoPrecio->AdvancedSearch->Save(); // calculoPrecio
			$this->rentabilidad->AdvancedSearch->Save(); // rentabilidad
			$this->precioVenta->AdvancedSearch->Save(); // precioVenta
			$this->tags->AdvancedSearch->Save(); // tags
			$this->detalle->AdvancedSearch->Save(); // detalle
			$this->idUnidadMedidaCompra->AdvancedSearch->Save(); // idUnidadMedidaCompra
			$this->idUnidadMedidaVenta->AdvancedSearch->Save(); // idUnidadMedidaVenta
			$this->codigosExternos->AdvancedSearch->Save(); // codigosExternos
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
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->denominacionExterna, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->denominacionInterna, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idCategoria, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idSubcateogoria, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idRubro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idMarca, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigoBarras, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->imagenes, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tags, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->detalle, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigosExternos, $arKeywords, $type);
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
		if ($this->denominacionExterna->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denominacionInterna->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idAlicuotaIva->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idCategoria->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idSubcateogoria->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idRubro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idMarca->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigoBarras->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->imagenes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idPrecioCompra->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->proveedor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->calculoPrecio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rentabilidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->precioVenta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tags->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->detalle->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idUnidadMedidaCompra->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idUnidadMedidaVenta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigosExternos->AdvancedSearch->IssetSession())
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
		$this->id->AdvancedSearch->UnsetSession();
		$this->denominacionExterna->AdvancedSearch->UnsetSession();
		$this->denominacionInterna->AdvancedSearch->UnsetSession();
		$this->idAlicuotaIva->AdvancedSearch->UnsetSession();
		$this->idCategoria->AdvancedSearch->UnsetSession();
		$this->idSubcateogoria->AdvancedSearch->UnsetSession();
		$this->idRubro->AdvancedSearch->UnsetSession();
		$this->idMarca->AdvancedSearch->UnsetSession();
		$this->codigoBarras->AdvancedSearch->UnsetSession();
		$this->imagenes->AdvancedSearch->UnsetSession();
		$this->idPrecioCompra->AdvancedSearch->UnsetSession();
		$this->proveedor->AdvancedSearch->UnsetSession();
		$this->calculoPrecio->AdvancedSearch->UnsetSession();
		$this->rentabilidad->AdvancedSearch->UnsetSession();
		$this->precioVenta->AdvancedSearch->UnsetSession();
		$this->tags->AdvancedSearch->UnsetSession();
		$this->detalle->AdvancedSearch->UnsetSession();
		$this->idUnidadMedidaCompra->AdvancedSearch->UnsetSession();
		$this->idUnidadMedidaVenta->AdvancedSearch->UnsetSession();
		$this->codigosExternos->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->denominacionExterna->AdvancedSearch->Load();
		$this->denominacionInterna->AdvancedSearch->Load();
		$this->idAlicuotaIva->AdvancedSearch->Load();
		$this->idCategoria->AdvancedSearch->Load();
		$this->idSubcateogoria->AdvancedSearch->Load();
		$this->idRubro->AdvancedSearch->Load();
		$this->idMarca->AdvancedSearch->Load();
		$this->codigoBarras->AdvancedSearch->Load();
		$this->imagenes->AdvancedSearch->Load();
		$this->idPrecioCompra->AdvancedSearch->Load();
		$this->proveedor->AdvancedSearch->Load();
		$this->calculoPrecio->AdvancedSearch->Load();
		$this->rentabilidad->AdvancedSearch->Load();
		$this->precioVenta->AdvancedSearch->Load();
		$this->tags->AdvancedSearch->Load();
		$this->detalle->AdvancedSearch->Load();
		$this->idUnidadMedidaCompra->AdvancedSearch->Load();
		$this->idUnidadMedidaVenta->AdvancedSearch->Load();
		$this->codigosExternos->AdvancedSearch->Load();
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
			$this->UpdateSort($this->denominacionExterna, $bCtrl); // denominacionExterna
			$this->UpdateSort($this->denominacionInterna, $bCtrl); // denominacionInterna
			$this->UpdateSort($this->idAlicuotaIva, $bCtrl); // idAlicuotaIva
			$this->UpdateSort($this->idCategoria, $bCtrl); // idCategoria
			$this->UpdateSort($this->idSubcateogoria, $bCtrl); // idSubcateogoria
			$this->UpdateSort($this->idRubro, $bCtrl); // idRubro
			$this->UpdateSort($this->idMarca, $bCtrl); // idMarca
			$this->UpdateSort($this->idPrecioCompra, $bCtrl); // idPrecioCompra
			$this->UpdateSort($this->proveedor, $bCtrl); // proveedor
			$this->UpdateSort($this->calculoPrecio, $bCtrl); // calculoPrecio
			$this->UpdateSort($this->rentabilidad, $bCtrl); // rentabilidad
			$this->UpdateSort($this->precioVenta, $bCtrl); // precioVenta
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
				$this->denominacionExterna->setSort("ASC");
				$this->denominacionInterna->setSort("ASC");
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
				$this->denominacionExterna->setSort("");
				$this->denominacionInterna->setSort("");
				$this->idAlicuotaIva->setSort("");
				$this->idCategoria->setSort("");
				$this->idSubcateogoria->setSort("");
				$this->idRubro->setSort("");
				$this->idMarca->setSort("");
				$this->idPrecioCompra->setSort("");
				$this->proveedor->setSort("");
				$this->calculoPrecio->setSort("");
				$this->rentabilidad->setSort("");
				$this->precioVenta->setSort("");
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
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// "detail_articulos2Dproveedores"
		$item = &$this->ListOptions->Add("detail_articulos2Dproveedores");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-proveedores') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;

		// "detail_articulos2Dterceros2Ddescuentos"
		$item = &$this->ListOptions->Add("detail_articulos2Dterceros2Ddescuentos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-terceros-descuentos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid;

		// "detail_articulos2Dstock"
		$item = &$this->ListOptions->Add("detail_articulos2Dstock");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-stock') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["articulos2Dstock_grid"])) $GLOBALS["articulos2Dstock_grid"] = new carticulos2Dstock_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("articulos2Dproveedores");
		$pages->Add("articulos2Dterceros2Ddescuentos");
		$pages->Add("articulos2Dstock");
		$this->DetailPages = $pages;

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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_articulos2Dproveedores"
		$oListOpt = &$this->ListOptions->Items["detail_articulos2Dproveedores"];
		if ($Security->AllowList(CurrentProjectID() . 'articulos-proveedores')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("articulos2Dproveedores", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dproveedoreslist.php?" . EW_TABLE_SHOW_MASTER . "=articulos&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["articulos2Dproveedores_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-proveedores')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "articulos2Dproveedores";
			}
			if ($GLOBALS["articulos2Dproveedores_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'articulos-proveedores')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "articulos2Dproveedores";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_articulos2Dterceros2Ddescuentos"
		$oListOpt = &$this->ListOptions->Items["detail_articulos2Dterceros2Ddescuentos"];
		if ($Security->AllowList(CurrentProjectID() . 'articulos-terceros-descuentos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("articulos2Dterceros2Ddescuentos", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=articulos&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "articulos2Dterceros2Ddescuentos";
			}
			if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'articulos-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "articulos2Dterceros2Ddescuentos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_articulos2Dstock"
		$oListOpt = &$this->ListOptions->Items["detail_articulos2Dstock"];
		if ($Security->AllowList(CurrentProjectID() . 'articulos-stock')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("articulos2Dstock", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dstocklist.php?" . EW_TABLE_SHOW_MASTER . "=articulos&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["articulos2Dstock_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-stock')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dstock")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "articulos2Dstock";
			}
			if ($GLOBALS["articulos2Dstock_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'articulos-stock')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dstock")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "articulos2Dstock";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
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
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_articulos2Dproveedores");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["articulos2Dproveedores"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["articulos2Dproveedores"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'articulos-proveedores') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dproveedores";
		}
		$item = &$option->Add("detailadd_articulos2Dterceros2Ddescuentos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["articulos2Dterceros2Ddescuentos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["articulos2Dterceros2Ddescuentos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'articulos-terceros-descuentos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dterceros2Ddescuentos";
		}
		$item = &$option->Add("detailadd_articulos2Dstock");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dstock");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["articulos2Dstock"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["articulos2Dstock"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'articulos-stock') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dstock";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"farticuloslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"farticuloslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.farticuloslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"farticuloslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"articulossrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

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

		// denominacionExterna
		$this->denominacionExterna->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denominacionExterna"]);
		if ($this->denominacionExterna->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denominacionExterna->AdvancedSearch->SearchOperator = @$_GET["z_denominacionExterna"];

		// denominacionInterna
		$this->denominacionInterna->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denominacionInterna"]);
		if ($this->denominacionInterna->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denominacionInterna->AdvancedSearch->SearchOperator = @$_GET["z_denominacionInterna"];

		// idAlicuotaIva
		$this->idAlicuotaIva->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idAlicuotaIva"]);
		if ($this->idAlicuotaIva->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idAlicuotaIva->AdvancedSearch->SearchOperator = @$_GET["z_idAlicuotaIva"];

		// idCategoria
		$this->idCategoria->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idCategoria"]);
		if ($this->idCategoria->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idCategoria->AdvancedSearch->SearchOperator = @$_GET["z_idCategoria"];

		// idSubcateogoria
		$this->idSubcateogoria->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idSubcateogoria"]);
		if ($this->idSubcateogoria->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idSubcateogoria->AdvancedSearch->SearchOperator = @$_GET["z_idSubcateogoria"];

		// idRubro
		$this->idRubro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idRubro"]);
		if ($this->idRubro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idRubro->AdvancedSearch->SearchOperator = @$_GET["z_idRubro"];

		// idMarca
		$this->idMarca->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idMarca"]);
		if ($this->idMarca->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idMarca->AdvancedSearch->SearchOperator = @$_GET["z_idMarca"];

		// codigoBarras
		$this->codigoBarras->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codigoBarras"]);
		if ($this->codigoBarras->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codigoBarras->AdvancedSearch->SearchOperator = @$_GET["z_codigoBarras"];

		// imagenes
		$this->imagenes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_imagenes"]);
		if ($this->imagenes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->imagenes->AdvancedSearch->SearchOperator = @$_GET["z_imagenes"];

		// idPrecioCompra
		$this->idPrecioCompra->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idPrecioCompra"]);
		if ($this->idPrecioCompra->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idPrecioCompra->AdvancedSearch->SearchOperator = @$_GET["z_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchCondition = @$_GET["v_idPrecioCompra"];
		$this->idPrecioCompra->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_idPrecioCompra"]);
		if ($this->idPrecioCompra->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->idPrecioCompra->AdvancedSearch->SearchOperator2 = @$_GET["w_idPrecioCompra"];

		// proveedor
		$this->proveedor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_proveedor"]);
		if ($this->proveedor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->proveedor->AdvancedSearch->SearchOperator = @$_GET["z_proveedor"];

		// calculoPrecio
		$this->calculoPrecio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_calculoPrecio"]);
		if ($this->calculoPrecio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->calculoPrecio->AdvancedSearch->SearchOperator = @$_GET["z_calculoPrecio"];

		// rentabilidad
		$this->rentabilidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_rentabilidad"]);
		if ($this->rentabilidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->rentabilidad->AdvancedSearch->SearchOperator = @$_GET["z_rentabilidad"];

		// precioVenta
		$this->precioVenta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_precioVenta"]);
		if ($this->precioVenta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->precioVenta->AdvancedSearch->SearchOperator = @$_GET["z_precioVenta"];

		// tags
		$this->tags->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tags"]);
		if ($this->tags->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tags->AdvancedSearch->SearchOperator = @$_GET["z_tags"];

		// detalle
		$this->detalle->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_detalle"]);
		if ($this->detalle->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->detalle->AdvancedSearch->SearchOperator = @$_GET["z_detalle"];

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idUnidadMedidaCompra"]);
		if ($this->idUnidadMedidaCompra->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchOperator = @$_GET["z_idUnidadMedidaCompra"];

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idUnidadMedidaVenta"]);
		if ($this->idUnidadMedidaVenta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchOperator = @$_GET["z_idUnidadMedidaVenta"];

		// codigosExternos
		$this->codigosExternos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codigosExternos"]);
		if ($this->codigosExternos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codigosExternos->AdvancedSearch->SearchOperator = @$_GET["z_codigosExternos"];
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
		$this->denominacionExterna->setDbValue($rs->fields('denominacionExterna'));
		$this->denominacionInterna->setDbValue($rs->fields('denominacionInterna'));
		$this->idAlicuotaIva->setDbValue($rs->fields('idAlicuotaIva'));
		$this->idCategoria->setDbValue($rs->fields('idCategoria'));
		$this->idSubcateogoria->setDbValue($rs->fields('idSubcateogoria'));
		$this->idRubro->setDbValue($rs->fields('idRubro'));
		$this->idMarca->setDbValue($rs->fields('idMarca'));
		$this->fabricante->setDbValue($rs->fields('fabricante'));
		$this->codigoBarras->setDbValue($rs->fields('codigoBarras'));
		$this->imagenes->Upload->DbValue = $rs->fields('imagenes');
		$this->imagenes->CurrentValue = $this->imagenes->Upload->DbValue;
		$this->idPrecioCompra->setDbValue($rs->fields('idPrecioCompra'));
		$this->proveedor->setDbValue($rs->fields('proveedor'));
		$this->calculoPrecio->setDbValue($rs->fields('calculoPrecio'));
		$this->rentabilidad->setDbValue($rs->fields('rentabilidad'));
		$this->precioVenta->setDbValue($rs->fields('precioVenta'));
		$this->tags->setDbValue($rs->fields('tags'));
		$this->detalle->setDbValue($rs->fields('detalle'));
		$this->idUnidadMedidaCompra->setDbValue($rs->fields('idUnidadMedidaCompra'));
		$this->idUnidadMedidaVenta->setDbValue($rs->fields('idUnidadMedidaVenta'));
		$this->codigosExternos->setDbValue($rs->fields('codigosExternos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacionExterna->DbValue = $row['denominacionExterna'];
		$this->denominacionInterna->DbValue = $row['denominacionInterna'];
		$this->idAlicuotaIva->DbValue = $row['idAlicuotaIva'];
		$this->idCategoria->DbValue = $row['idCategoria'];
		$this->idSubcateogoria->DbValue = $row['idSubcateogoria'];
		$this->idRubro->DbValue = $row['idRubro'];
		$this->idMarca->DbValue = $row['idMarca'];
		$this->fabricante->DbValue = $row['fabricante'];
		$this->codigoBarras->DbValue = $row['codigoBarras'];
		$this->imagenes->Upload->DbValue = $row['imagenes'];
		$this->idPrecioCompra->DbValue = $row['idPrecioCompra'];
		$this->proveedor->DbValue = $row['proveedor'];
		$this->calculoPrecio->DbValue = $row['calculoPrecio'];
		$this->rentabilidad->DbValue = $row['rentabilidad'];
		$this->precioVenta->DbValue = $row['precioVenta'];
		$this->tags->DbValue = $row['tags'];
		$this->detalle->DbValue = $row['detalle'];
		$this->idUnidadMedidaCompra->DbValue = $row['idUnidadMedidaCompra'];
		$this->idUnidadMedidaVenta->DbValue = $row['idUnidadMedidaVenta'];
		$this->codigosExternos->DbValue = $row['codigosExternos'];
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
		if ($this->rentabilidad->FormValue == $this->rentabilidad->CurrentValue && is_numeric(ew_StrToFloat($this->rentabilidad->CurrentValue)))
			$this->rentabilidad->CurrentValue = ew_StrToFloat($this->rentabilidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->precioVenta->FormValue == $this->precioVenta->CurrentValue && is_numeric(ew_StrToFloat($this->precioVenta->CurrentValue)))
			$this->precioVenta->CurrentValue = ew_StrToFloat($this->precioVenta->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// denominacionExterna
		// denominacionInterna
		// idAlicuotaIva
		// idCategoria
		// idSubcateogoria
		// idRubro
		// idMarca
		// fabricante

		$this->fabricante->CellCssStyle = "white-space: nowrap;";

		// codigoBarras
		// imagenes
		// idPrecioCompra
		// proveedor
		// calculoPrecio
		// rentabilidad
		// precioVenta
		// tags
		// detalle
		// idUnidadMedidaCompra
		// idUnidadMedidaVenta
		// codigosExternos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacionExterna
		$this->denominacionExterna->ViewValue = $this->denominacionExterna->CurrentValue;
		$this->denominacionExterna->ViewCustomAttributes = "";

		// denominacionInterna
		$this->denominacionInterna->ViewValue = $this->denominacionInterna->CurrentValue;
		$this->denominacionInterna->ViewCustomAttributes = "";

		// idAlicuotaIva
		if (strval($this->idAlicuotaIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idAlicuotaIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `valor` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `alicuotas-iva`";
		$sWhereWrk = "";
		$this->idAlicuotaIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idAlicuotaIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `valor` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idAlicuotaIva->ViewValue = $this->idAlicuotaIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idAlicuotaIva->ViewValue = $this->idAlicuotaIva->CurrentValue;
			}
		} else {
			$this->idAlicuotaIva->ViewValue = NULL;
		}
		$this->idAlicuotaIva->ViewCustomAttributes = "";

		// idCategoria
		if (strval($this->idCategoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias-articulos`";
		$sWhereWrk = "";
		$this->idCategoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCategoria->ViewValue = $this->idCategoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCategoria->ViewValue = $this->idCategoria->CurrentValue;
			}
		} else {
			$this->idCategoria->ViewValue = NULL;
		}
		$this->idCategoria->ViewCustomAttributes = "";

		// idSubcateogoria
		if (strval($this->idSubcateogoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subcategorias-articulos`";
		$sWhereWrk = "";
		$this->idSubcateogoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->CurrentValue;
			}
		} else {
			$this->idSubcateogoria->ViewValue = NULL;
		}
		$this->idSubcateogoria->ViewCustomAttributes = "";

		// idRubro
		if (strval($this->idRubro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
		$sWhereWrk = "";
		$this->idRubro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idRubro->ViewValue = $this->idRubro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idRubro->ViewValue = $this->idRubro->CurrentValue;
			}
		} else {
			$this->idRubro->ViewValue = NULL;
		}
		$this->idRubro->ViewCustomAttributes = "";

		// idMarca
		if (strval($this->idMarca->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
		$sWhereWrk = "";
		$this->idMarca->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMarca->ViewValue = $this->idMarca->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMarca->ViewValue = $this->idMarca->CurrentValue;
			}
		} else {
			$this->idMarca->ViewValue = NULL;
		}
		$this->idMarca->ViewCustomAttributes = "";

		// codigoBarras
		$this->codigoBarras->ViewValue = $this->codigoBarras->CurrentValue;
		$this->codigoBarras->ViewCustomAttributes = "";

		// idPrecioCompra
		if (strval($this->idPrecioCompra->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `precios-compra`";
		$sWhereWrk = "";
		$this->idPrecioCompra->LookupFilters = array();
		$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = ew_FormatDateTime($rswrk->fields('Disp3Fld'), 0);
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->CurrentValue;
			}
		} else {
			$this->idPrecioCompra->ViewValue = NULL;
		}
		$this->idPrecioCompra->ViewCustomAttributes = "";

		// proveedor
		if (strval($this->proveedor->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->proveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->proveedor->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proveedor->ViewValue = $this->proveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proveedor->ViewValue = $this->proveedor->CurrentValue;
			}
		} else {
			$this->proveedor->ViewValue = NULL;
		}
		$this->proveedor->ViewCustomAttributes = "";

		// calculoPrecio
		if (strval($this->calculoPrecio->CurrentValue) <> "") {
			$this->calculoPrecio->ViewValue = $this->calculoPrecio->OptionCaption($this->calculoPrecio->CurrentValue);
		} else {
			$this->calculoPrecio->ViewValue = NULL;
		}
		$this->calculoPrecio->ViewCustomAttributes = "";

		// rentabilidad
		$this->rentabilidad->ViewValue = $this->rentabilidad->CurrentValue;
		$this->rentabilidad->ViewCustomAttributes = "";

		// precioVenta
		$this->precioVenta->ViewValue = $this->precioVenta->CurrentValue;
		$this->precioVenta->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// denominacionExterna
			$this->denominacionExterna->LinkCustomAttributes = "";
			$this->denominacionExterna->HrefValue = "";
			$this->denominacionExterna->TooltipValue = "";

			// denominacionInterna
			$this->denominacionInterna->LinkCustomAttributes = "";
			$this->denominacionInterna->HrefValue = "";
			$this->denominacionInterna->TooltipValue = "";

			// idAlicuotaIva
			$this->idAlicuotaIva->LinkCustomAttributes = "";
			$this->idAlicuotaIva->HrefValue = "";
			$this->idAlicuotaIva->TooltipValue = "";

			// idCategoria
			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";
			$this->idCategoria->TooltipValue = "";

			// idSubcateogoria
			$this->idSubcateogoria->LinkCustomAttributes = "";
			$this->idSubcateogoria->HrefValue = "";
			$this->idSubcateogoria->TooltipValue = "";

			// idRubro
			$this->idRubro->LinkCustomAttributes = "";
			$this->idRubro->HrefValue = "";
			$this->idRubro->TooltipValue = "";

			// idMarca
			$this->idMarca->LinkCustomAttributes = "";
			$this->idMarca->HrefValue = "";
			$this->idMarca->TooltipValue = "";

			// idPrecioCompra
			$this->idPrecioCompra->LinkCustomAttributes = "";
			$this->idPrecioCompra->HrefValue = "";
			$this->idPrecioCompra->TooltipValue = "";

			// proveedor
			$this->proveedor->LinkCustomAttributes = "";
			$this->proveedor->HrefValue = "";
			$this->proveedor->TooltipValue = "";

			// calculoPrecio
			$this->calculoPrecio->LinkCustomAttributes = "";
			$this->calculoPrecio->HrefValue = "";
			$this->calculoPrecio->TooltipValue = "";

			// rentabilidad
			$this->rentabilidad->LinkCustomAttributes = "";
			$this->rentabilidad->HrefValue = "";
			$this->rentabilidad->TooltipValue = "";

			// precioVenta
			$this->precioVenta->LinkCustomAttributes = "";
			$this->precioVenta->HrefValue = "";
			$this->precioVenta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// denominacionExterna
			$this->denominacionExterna->EditAttrs["class"] = "form-control";
			$this->denominacionExterna->EditCustomAttributes = "";
			$this->denominacionExterna->EditValue = ew_HtmlEncode($this->denominacionExterna->AdvancedSearch->SearchValue);
			$this->denominacionExterna->PlaceHolder = ew_RemoveHtml($this->denominacionExterna->FldCaption());

			// denominacionInterna
			$this->denominacionInterna->EditAttrs["class"] = "form-control";
			$this->denominacionInterna->EditCustomAttributes = "";
			$this->denominacionInterna->EditValue = ew_HtmlEncode($this->denominacionInterna->AdvancedSearch->SearchValue);
			$this->denominacionInterna->PlaceHolder = ew_RemoveHtml($this->denominacionInterna->FldCaption());

			// idAlicuotaIva
			$this->idAlicuotaIva->EditAttrs["class"] = "form-control";
			$this->idAlicuotaIva->EditCustomAttributes = "";

			// idCategoria
			$this->idCategoria->EditAttrs["class"] = "form-control";
			$this->idCategoria->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idCategoria->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categorias-articulos`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idCategoria->EditValue = $arwrk;

			// idSubcateogoria
			$this->idSubcateogoria->EditAttrs["class"] = "form-control";
			$this->idSubcateogoria->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idSubcateogoria->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `subcategorias-articulos`";
			$sWhereWrk = "";
			$this->idSubcateogoria->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idSubcateogoria->EditValue = $arwrk;

			// idRubro
			$this->idRubro->EditAttrs["class"] = "form-control";
			$this->idRubro->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idRubro->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `rubros`";
			$sWhereWrk = "";
			$this->idRubro->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idRubro->EditValue = $arwrk;

			// idMarca
			$this->idMarca->EditAttrs["class"] = "form-control";
			$this->idMarca->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idMarca->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `marcas`";
			$sWhereWrk = "";
			$this->idMarca->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idMarca->EditValue = $arwrk;

			// idPrecioCompra
			$this->idPrecioCompra->EditAttrs["class"] = "form-control";
			$this->idPrecioCompra->EditCustomAttributes = "";
			$this->idPrecioCompra->EditAttrs["class"] = "form-control";
			$this->idPrecioCompra->EditCustomAttributes = "";

			// proveedor
			$this->proveedor->EditAttrs["class"] = "form-control";
			$this->proveedor->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->proveedor->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->proveedor->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->proveedor->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->proveedor->EditValue = $arwrk;

			// calculoPrecio
			$this->calculoPrecio->EditAttrs["class"] = "form-control";
			$this->calculoPrecio->EditCustomAttributes = "";
			$this->calculoPrecio->EditValue = $this->calculoPrecio->Options(TRUE);

			// rentabilidad
			$this->rentabilidad->EditAttrs["class"] = "form-control";
			$this->rentabilidad->EditCustomAttributes = "";
			$this->rentabilidad->EditValue = ew_HtmlEncode($this->rentabilidad->AdvancedSearch->SearchValue);
			$this->rentabilidad->PlaceHolder = ew_RemoveHtml($this->rentabilidad->FldCaption());

			// precioVenta
			$this->precioVenta->EditAttrs["class"] = "form-control";
			$this->precioVenta->EditCustomAttributes = "";
			$this->precioVenta->EditValue = ew_HtmlEncode($this->precioVenta->AdvancedSearch->SearchValue);
			$this->precioVenta->PlaceHolder = ew_RemoveHtml($this->precioVenta->FldCaption());
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
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
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
		$this->denominacionExterna->AdvancedSearch->Load();
		$this->denominacionInterna->AdvancedSearch->Load();
		$this->idAlicuotaIva->AdvancedSearch->Load();
		$this->idCategoria->AdvancedSearch->Load();
		$this->idSubcateogoria->AdvancedSearch->Load();
		$this->idRubro->AdvancedSearch->Load();
		$this->idMarca->AdvancedSearch->Load();
		$this->codigoBarras->AdvancedSearch->Load();
		$this->imagenes->AdvancedSearch->Load();
		$this->idPrecioCompra->AdvancedSearch->Load();
		$this->proveedor->AdvancedSearch->Load();
		$this->calculoPrecio->AdvancedSearch->Load();
		$this->rentabilidad->AdvancedSearch->Load();
		$this->precioVenta->AdvancedSearch->Load();
		$this->tags->AdvancedSearch->Load();
		$this->detalle->AdvancedSearch->Load();
		$this->idUnidadMedidaCompra->AdvancedSearch->Load();
		$this->idUnidadMedidaVenta->AdvancedSearch->Load();
		$this->codigosExternos->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_articulos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_articulos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.farticuloslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_idCategoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias-articulos`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idSubcateogoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subcategorias-articulos`";
			$sWhereWrk = "";
			$this->idSubcateogoria->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idRubro":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
			$sWhereWrk = "";
			$this->idRubro->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idMarca":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			$this->idMarca->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_proveedor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->proveedor->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
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
		$this->ListOptions->Items["detail_articulos2Dproveedores"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Precios de Compra" href="articulos2Dproveedoreslist.php?showmaster=articulos&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-shopping-cart ewIcon" aria-hidden="true"></span></a></div>';
		$this->ListOptions->Items["detail_articulos2Dterceros2Ddescuentos"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Descuentos por Artículo" href="articulos2Dterceros2Ddescuentoslist.php?showmaster=articulos&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-piggy-bank ewIcon" aria-hidden="true"></span></a></div>';
		$this->ListOptions->Items["detail_articulos2Dstock"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Stock" href="articulos2Dstocklist.php?showmaster=articulos&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon glyphicon-equalizer ewIcon" aria-hidden="true"></span></a></div>';
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
if (!isset($articulos_list)) $articulos_list = new carticulos_list();

// Page init
$articulos_list->Page_Init();

// Page main
$articulos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($articulos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = farticuloslist = new ew_Form("farticuloslist", "list");
farticuloslist.FormKeyCountName = '<?php echo $articulos_list->FormKeyCountName ?>';

// Form_CustomValidate event
farticuloslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticuloslist.ValidateRequired = true;
<?php } else { ?>
farticuloslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticuloslist.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticuloslist.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
farticuloslist.Lists["x_idSubcateogoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
farticuloslist.Lists["x_idRubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rubros"};
farticuloslist.Lists["x_idMarca"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
farticuloslist.Lists["x_idPrecioCompra"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_precioPesos","x_denominacion","x_ultimaActualizacion",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"precios2Dcompra"};
farticuloslist.Lists["x_proveedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
farticuloslist.Lists["x_calculoPrecio"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
farticuloslist.Lists["x_calculoPrecio"].Options = <?php echo json_encode($articulos->calculoPrecio->Options()) ?>;

// Form object for search
var CurrentSearchForm = farticuloslistsrch = new ew_Form("farticuloslistsrch");

// Validate function for search
farticuloslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
farticuloslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticuloslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
farticuloslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
farticuloslistsrch.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
farticuloslistsrch.Lists["x_idSubcateogoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
farticuloslistsrch.Lists["x_idRubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rubros"};
farticuloslistsrch.Lists["x_idMarca"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
farticuloslistsrch.Lists["x_proveedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Init search panel as collapsed
if (farticuloslistsrch) farticuloslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($articulos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($articulos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($articulos_list->TotalRecs > 0 && $articulos_list->ExportOptions->Visible()) { ?>
<?php $articulos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($articulos_list->SearchOptions->Visible()) { ?>
<?php $articulos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($articulos_list->FilterOptions->Visible()) { ?>
<?php $articulos_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($articulos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $articulos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($articulos_list->TotalRecs <= 0)
			$articulos_list->TotalRecs = $articulos->SelectRecordCount();
	} else {
		if (!$articulos_list->Recordset && ($articulos_list->Recordset = $articulos_list->LoadRecordset()))
			$articulos_list->TotalRecs = $articulos_list->Recordset->RecordCount();
	}
	$articulos_list->StartRec = 1;
	if ($articulos_list->DisplayRecs <= 0 || ($articulos->Export <> "" && $articulos->ExportAll)) // Display all records
		$articulos_list->DisplayRecs = $articulos_list->TotalRecs;
	if (!($articulos->Export <> "" && $articulos->ExportAll))
		$articulos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$articulos_list->Recordset = $articulos_list->LoadRecordset($articulos_list->StartRec-1, $articulos_list->DisplayRecs);

	// Set no record found message
	if ($articulos->CurrentAction == "" && $articulos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$articulos_list->setWarningMessage(ew_DeniedMsg());
		if ($articulos_list->SearchWhere == "0=101")
			$articulos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$articulos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$articulos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($articulos->Export == "" && $articulos->CurrentAction == "") { ?>
<form name="farticuloslistsrch" id="farticuloslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($articulos_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="farticuloslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="articulos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$articulos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$articulos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$articulos->ResetAttrs();
$articulos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($articulos->id->Visible) { // id ?>
	<div id="xsc_id" class="ewCell form-group">
		<label for="x_id" class="ewSearchCaption ewLabel"><?php echo $articulos->id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="articulos" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($articulos->id->getPlaceHolder()) ?>" value="<?php echo $articulos->id->EditValue ?>"<?php echo $articulos->id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
	<div id="xsc_denominacionExterna" class="ewCell form-group">
		<label for="x_denominacionExterna" class="ewSearchCaption ewLabel"><?php echo $articulos->denominacionExterna->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_denominacionExterna" id="z_denominacionExterna" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="articulos" data-field="x_denominacionExterna" name="x_denominacionExterna" id="x_denominacionExterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionExterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionExterna->EditValue ?>"<?php echo $articulos->denominacionExterna->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
	<div id="xsc_denominacionInterna" class="ewCell form-group">
		<label for="x_denominacionInterna" class="ewSearchCaption ewLabel"><?php echo $articulos->denominacionInterna->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_denominacionInterna" id="z_denominacionInterna" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="articulos" data-field="x_denominacionInterna" name="x_denominacionInterna" id="x_denominacionInterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionInterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionInterna->EditValue ?>"<?php echo $articulos->denominacionInterna->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
	<div id="xsc_idCategoria" class="ewCell form-group">
		<label for="x_idCategoria" class="ewSearchCaption ewLabel"><?php echo $articulos->idCategoria->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idCategoria" id="z_idCategoria" value="="></span>
		<span class="ewSearchField">
<select data-table="articulos" data-field="x_idCategoria" data-value-separator="<?php echo $articulos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x_idCategoria" name="x_idCategoria"<?php echo $articulos->idCategoria->EditAttributes() ?>>
<?php echo $articulos->idCategoria->SelectOptionListHtml("x_idCategoria") ?>
</select>
<input type="hidden" name="s_x_idCategoria" id="s_x_idCategoria" value="<?php echo $articulos->idCategoria->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
	<div id="xsc_idSubcateogoria" class="ewCell form-group">
		<label for="x_idSubcateogoria" class="ewSearchCaption ewLabel"><?php echo $articulos->idSubcateogoria->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idSubcateogoria" id="z_idSubcateogoria" value="="></span>
		<span class="ewSearchField">
<select data-table="articulos" data-field="x_idSubcateogoria" data-value-separator="<?php echo $articulos->idSubcateogoria->DisplayValueSeparatorAttribute() ?>" id="x_idSubcateogoria" name="x_idSubcateogoria"<?php echo $articulos->idSubcateogoria->EditAttributes() ?>>
<?php echo $articulos->idSubcateogoria->SelectOptionListHtml("x_idSubcateogoria") ?>
</select>
<input type="hidden" name="s_x_idSubcateogoria" id="s_x_idSubcateogoria" value="<?php echo $articulos->idSubcateogoria->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
	<div id="xsc_idRubro" class="ewCell form-group">
		<label for="x_idRubro" class="ewSearchCaption ewLabel"><?php echo $articulos->idRubro->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idRubro" id="z_idRubro" value="="></span>
		<span class="ewSearchField">
<select data-table="articulos" data-field="x_idRubro" data-value-separator="<?php echo $articulos->idRubro->DisplayValueSeparatorAttribute() ?>" id="x_idRubro" name="x_idRubro"<?php echo $articulos->idRubro->EditAttributes() ?>>
<?php echo $articulos->idRubro->SelectOptionListHtml("x_idRubro") ?>
</select>
<input type="hidden" name="s_x_idRubro" id="s_x_idRubro" value="<?php echo $articulos->idRubro->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
	<div id="xsc_idMarca" class="ewCell form-group">
		<label for="x_idMarca" class="ewSearchCaption ewLabel"><?php echo $articulos->idMarca->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idMarca" id="z_idMarca" value="="></span>
		<span class="ewSearchField">
<select data-table="articulos" data-field="x_idMarca" data-value-separator="<?php echo $articulos->idMarca->DisplayValueSeparatorAttribute() ?>" id="x_idMarca" name="x_idMarca"<?php echo $articulos->idMarca->EditAttributes() ?>>
<?php echo $articulos->idMarca->SelectOptionListHtml("x_idMarca") ?>
</select>
<input type="hidden" name="s_x_idMarca" id="s_x_idMarca" value="<?php echo $articulos->idMarca->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
	<div id="xsc_proveedor" class="ewCell form-group">
		<label for="x_proveedor" class="ewSearchCaption ewLabel"><?php echo $articulos->proveedor->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_proveedor" id="z_proveedor" value="="></span>
		<span class="ewSearchField">
<select data-table="articulos" data-field="x_proveedor" data-value-separator="<?php echo $articulos->proveedor->DisplayValueSeparatorAttribute() ?>" id="x_proveedor" name="x_proveedor"<?php echo $articulos->proveedor->EditAttributes() ?>>
<?php echo $articulos->proveedor->SelectOptionListHtml("x_proveedor") ?>
</select>
<input type="hidden" name="s_x_proveedor" id="s_x_proveedor" value="<?php echo $articulos->proveedor->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($articulos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($articulos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $articulos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($articulos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($articulos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($articulos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($articulos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $articulos_list->ShowPageHeader(); ?>
<?php
$articulos_list->ShowMessage();
?>
<?php if ($articulos_list->TotalRecs > 0 || $articulos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid articulos">
<?php if ($articulos->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($articulos->CurrentAction <> "gridadd" && $articulos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($articulos_list->Pager)) $articulos_list->Pager = new cPrevNextPager($articulos_list->StartRec, $articulos_list->DisplayRecs, $articulos_list->TotalRecs) ?>
<?php if ($articulos_list->Pager->RecordCount > 0 && $articulos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($articulos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $articulos_list->PageUrl() ?>start=<?php echo $articulos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($articulos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $articulos_list->PageUrl() ?>start=<?php echo $articulos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $articulos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($articulos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $articulos_list->PageUrl() ?>start=<?php echo $articulos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($articulos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $articulos_list->PageUrl() ?>start=<?php echo $articulos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $articulos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $articulos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $articulos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $articulos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($articulos_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="articulos">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($articulos_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($articulos_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($articulos_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($articulos_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($articulos_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="farticuloslist" id="farticuloslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos">
<div id="gmp_articulos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($articulos_list->TotalRecs > 0) { ?>
<table id="tbl_articuloslist" class="table ewTable">
<?php echo $articulos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$articulos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$articulos_list->RenderListOptions();

// Render list options (header, left)
$articulos_list->ListOptions->Render("header", "left");
?>
<?php if ($articulos->id->Visible) { // id ?>
	<?php if ($articulos->SortUrl($articulos->id) == "") { ?>
		<th data-name="id"><div id="elh_articulos_id" class="articulos_id"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $articulos->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->id) ?>',2);"><div id="elh_articulos_id" class="articulos_id">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $articulos->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($articulos->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
	<?php if ($articulos->SortUrl($articulos->denominacionExterna) == "") { ?>
		<th data-name="denominacionExterna"><div id="elh_articulos_denominacionExterna" class="articulos_denominacionExterna"><div class="ewTableHeaderCaption"><?php echo $articulos->denominacionExterna->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacionExterna"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->denominacionExterna) ?>',2);"><div id="elh_articulos_denominacionExterna" class="articulos_denominacionExterna">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->denominacionExterna->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($articulos->denominacionExterna->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->denominacionExterna->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
	<?php if ($articulos->SortUrl($articulos->denominacionInterna) == "") { ?>
		<th data-name="denominacionInterna"><div id="elh_articulos_denominacionInterna" class="articulos_denominacionInterna"><div class="ewTableHeaderCaption"><?php echo $articulos->denominacionInterna->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacionInterna"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->denominacionInterna) ?>',2);"><div id="elh_articulos_denominacionInterna" class="articulos_denominacionInterna">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->denominacionInterna->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($articulos->denominacionInterna->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->denominacionInterna->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<?php if ($articulos->SortUrl($articulos->idAlicuotaIva) == "") { ?>
		<th data-name="idAlicuotaIva"><div id="elh_articulos_idAlicuotaIva" class="articulos_idAlicuotaIva"><div class="ewTableHeaderCaption"><?php echo $articulos->idAlicuotaIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idAlicuotaIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idAlicuotaIva) ?>',2);"><div id="elh_articulos_idAlicuotaIva" class="articulos_idAlicuotaIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idAlicuotaIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idAlicuotaIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idAlicuotaIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
	<?php if ($articulos->SortUrl($articulos->idCategoria) == "") { ?>
		<th data-name="idCategoria"><div id="elh_articulos_idCategoria" class="articulos_idCategoria"><div class="ewTableHeaderCaption"><?php echo $articulos->idCategoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCategoria"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idCategoria) ?>',2);"><div id="elh_articulos_idCategoria" class="articulos_idCategoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idCategoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idCategoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idCategoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
	<?php if ($articulos->SortUrl($articulos->idSubcateogoria) == "") { ?>
		<th data-name="idSubcateogoria"><div id="elh_articulos_idSubcateogoria" class="articulos_idSubcateogoria"><div class="ewTableHeaderCaption"><?php echo $articulos->idSubcateogoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idSubcateogoria"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idSubcateogoria) ?>',2);"><div id="elh_articulos_idSubcateogoria" class="articulos_idSubcateogoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idSubcateogoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idSubcateogoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idSubcateogoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
	<?php if ($articulos->SortUrl($articulos->idRubro) == "") { ?>
		<th data-name="idRubro"><div id="elh_articulos_idRubro" class="articulos_idRubro"><div class="ewTableHeaderCaption"><?php echo $articulos->idRubro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idRubro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idRubro) ?>',2);"><div id="elh_articulos_idRubro" class="articulos_idRubro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idRubro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idRubro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idRubro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
	<?php if ($articulos->SortUrl($articulos->idMarca) == "") { ?>
		<th data-name="idMarca"><div id="elh_articulos_idMarca" class="articulos_idMarca"><div class="ewTableHeaderCaption"><?php echo $articulos->idMarca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idMarca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idMarca) ?>',2);"><div id="elh_articulos_idMarca" class="articulos_idMarca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idMarca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idMarca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idMarca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
	<?php if ($articulos->SortUrl($articulos->idPrecioCompra) == "") { ?>
		<th data-name="idPrecioCompra"><div id="elh_articulos_idPrecioCompra" class="articulos_idPrecioCompra"><div class="ewTableHeaderCaption"><?php echo $articulos->idPrecioCompra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idPrecioCompra"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->idPrecioCompra) ?>',2);"><div id="elh_articulos_idPrecioCompra" class="articulos_idPrecioCompra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->idPrecioCompra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->idPrecioCompra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->idPrecioCompra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
	<?php if ($articulos->SortUrl($articulos->proveedor) == "") { ?>
		<th data-name="proveedor"><div id="elh_articulos_proveedor" class="articulos_proveedor"><div class="ewTableHeaderCaption"><?php echo $articulos->proveedor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="proveedor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->proveedor) ?>',2);"><div id="elh_articulos_proveedor" class="articulos_proveedor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->proveedor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->proveedor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->proveedor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
	<?php if ($articulos->SortUrl($articulos->calculoPrecio) == "") { ?>
		<th data-name="calculoPrecio"><div id="elh_articulos_calculoPrecio" class="articulos_calculoPrecio"><div class="ewTableHeaderCaption"><?php echo $articulos->calculoPrecio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="calculoPrecio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->calculoPrecio) ?>',2);"><div id="elh_articulos_calculoPrecio" class="articulos_calculoPrecio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->calculoPrecio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->calculoPrecio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->calculoPrecio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
	<?php if ($articulos->SortUrl($articulos->rentabilidad) == "") { ?>
		<th data-name="rentabilidad"><div id="elh_articulos_rentabilidad" class="articulos_rentabilidad"><div class="ewTableHeaderCaption"><?php echo $articulos->rentabilidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rentabilidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->rentabilidad) ?>',2);"><div id="elh_articulos_rentabilidad" class="articulos_rentabilidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->rentabilidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->rentabilidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->rentabilidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
	<?php if ($articulos->SortUrl($articulos->precioVenta) == "") { ?>
		<th data-name="precioVenta"><div id="elh_articulos_precioVenta" class="articulos_precioVenta"><div class="ewTableHeaderCaption"><?php echo $articulos->precioVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precioVenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos->SortUrl($articulos->precioVenta) ?>',2);"><div id="elh_articulos_precioVenta" class="articulos_precioVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos->precioVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos->precioVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos->precioVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$articulos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($articulos->ExportAll && $articulos->Export <> "") {
	$articulos_list->StopRec = $articulos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($articulos_list->TotalRecs > $articulos_list->StartRec + $articulos_list->DisplayRecs - 1)
		$articulos_list->StopRec = $articulos_list->StartRec + $articulos_list->DisplayRecs - 1;
	else
		$articulos_list->StopRec = $articulos_list->TotalRecs;
}
$articulos_list->RecCnt = $articulos_list->StartRec - 1;
if ($articulos_list->Recordset && !$articulos_list->Recordset->EOF) {
	$articulos_list->Recordset->MoveFirst();
	$bSelectLimit = $articulos_list->UseSelectLimit;
	if (!$bSelectLimit && $articulos_list->StartRec > 1)
		$articulos_list->Recordset->Move($articulos_list->StartRec - 1);
} elseif (!$articulos->AllowAddDeleteRow && $articulos_list->StopRec == 0) {
	$articulos_list->StopRec = $articulos->GridAddRowCount;
}

// Initialize aggregate
$articulos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$articulos->ResetAttrs();
$articulos_list->RenderRow();
while ($articulos_list->RecCnt < $articulos_list->StopRec) {
	$articulos_list->RecCnt++;
	if (intval($articulos_list->RecCnt) >= intval($articulos_list->StartRec)) {
		$articulos_list->RowCnt++;

		// Set up key count
		$articulos_list->KeyCount = $articulos_list->RowIndex;

		// Init row class and style
		$articulos->ResetAttrs();
		$articulos->CssClass = "";
		if ($articulos->CurrentAction == "gridadd") {
		} else {
			$articulos_list->LoadRowValues($articulos_list->Recordset); // Load row values
		}
		$articulos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$articulos->RowAttrs = array_merge($articulos->RowAttrs, array('data-rowindex'=>$articulos_list->RowCnt, 'id'=>'r' . $articulos_list->RowCnt . '_articulos', 'data-rowtype'=>$articulos->RowType));

		// Render row
		$articulos_list->RenderRow();

		// Render list options
		$articulos_list->RenderListOptions();
?>
	<tr<?php echo $articulos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos_list->ListOptions->Render("body", "left", $articulos_list->RowCnt);
?>
	<?php if ($articulos->id->Visible) { // id ?>
		<td data-name="id"<?php echo $articulos->id->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_id" class="articulos_id">
<span<?php echo $articulos->id->ViewAttributes() ?>>
<?php echo $articulos->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $articulos_list->PageObjName . "_row_" . $articulos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
		<td data-name="denominacionExterna"<?php echo $articulos->denominacionExterna->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_denominacionExterna" class="articulos_denominacionExterna">
<span<?php echo $articulos->denominacionExterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionExterna->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
		<td data-name="denominacionInterna"<?php echo $articulos->denominacionInterna->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_denominacionInterna" class="articulos_denominacionInterna">
<span<?php echo $articulos->denominacionInterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionInterna->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td data-name="idAlicuotaIva"<?php echo $articulos->idAlicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idAlicuotaIva" class="articulos_idAlicuotaIva">
<span<?php echo $articulos->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos->idAlicuotaIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
		<td data-name="idCategoria"<?php echo $articulos->idCategoria->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idCategoria" class="articulos_idCategoria">
<span<?php echo $articulos->idCategoria->ViewAttributes() ?>>
<?php echo $articulos->idCategoria->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
		<td data-name="idSubcateogoria"<?php echo $articulos->idSubcateogoria->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idSubcateogoria" class="articulos_idSubcateogoria">
<span<?php echo $articulos->idSubcateogoria->ViewAttributes() ?>>
<?php echo $articulos->idSubcateogoria->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idRubro->Visible) { // idRubro ?>
		<td data-name="idRubro"<?php echo $articulos->idRubro->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idRubro" class="articulos_idRubro">
<span<?php echo $articulos->idRubro->ViewAttributes() ?>>
<?php echo $articulos->idRubro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idMarca->Visible) { // idMarca ?>
		<td data-name="idMarca"<?php echo $articulos->idMarca->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idMarca" class="articulos_idMarca">
<span<?php echo $articulos->idMarca->ViewAttributes() ?>>
<?php echo $articulos->idMarca->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
		<td data-name="idPrecioCompra"<?php echo $articulos->idPrecioCompra->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_idPrecioCompra" class="articulos_idPrecioCompra">
<span<?php echo $articulos->idPrecioCompra->ViewAttributes() ?>>
<?php echo $articulos->idPrecioCompra->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->proveedor->Visible) { // proveedor ?>
		<td data-name="proveedor"<?php echo $articulos->proveedor->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_proveedor" class="articulos_proveedor">
<span<?php echo $articulos->proveedor->ViewAttributes() ?>>
<?php echo $articulos->proveedor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
		<td data-name="calculoPrecio"<?php echo $articulos->calculoPrecio->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_calculoPrecio" class="articulos_calculoPrecio">
<span<?php echo $articulos->calculoPrecio->ViewAttributes() ?>>
<?php echo $articulos->calculoPrecio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
		<td data-name="rentabilidad"<?php echo $articulos->rentabilidad->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_rentabilidad" class="articulos_rentabilidad">
<span<?php echo $articulos->rentabilidad->ViewAttributes() ?>>
<?php echo $articulos->rentabilidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
		<td data-name="precioVenta"<?php echo $articulos->precioVenta->CellAttributes() ?>>
<span id="el<?php echo $articulos_list->RowCnt ?>_articulos_precioVenta" class="articulos_precioVenta">
<span<?php echo $articulos->precioVenta->ViewAttributes() ?>>
<?php echo $articulos->precioVenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos_list->ListOptions->Render("body", "right", $articulos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($articulos->CurrentAction <> "gridadd")
		$articulos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($articulos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($articulos_list->Recordset)
	$articulos_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($articulos_list->TotalRecs == 0 && $articulos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($articulos->Export == "") { ?>
<script type="text/javascript">
farticuloslistsrch.FilterList = <?php echo $articulos_list->GetFilterList() ?>;
farticuloslistsrch.Init();
farticuloslist.Init();
</script>
<?php } ?>
<?php
$articulos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($articulos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$articulos_list->Page_Terminate();
?>
