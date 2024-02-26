<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "terceros2Dmedios2Dcontactosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "tercerosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$terceros2Dmedios2Dcontactos_list = NULL; // Initialize page object first

class cterceros2Dmedios2Dcontactos_list extends cterceros2Dmedios2Dcontactos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros-medios-contactos';

	// Page object name
	var $PageObjName = 'terceros2Dmedios2Dcontactos_list';

	// Grid form hidden field names
	var $FormName = 'fterceros2Dmedios2Dcontactoslist';
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

		// Table object (terceros2Dmedios2Dcontactos)
		if (!isset($GLOBALS["terceros2Dmedios2Dcontactos"]) || get_class($GLOBALS["terceros2Dmedios2Dcontactos"]) == "cterceros2Dmedios2Dcontactos") {
			$GLOBALS["terceros2Dmedios2Dcontactos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["terceros2Dmedios2Dcontactos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "terceros2Dmedios2Dcontactosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "terceros2Dmedios2Dcontactosdelete.php";
		$this->MultiUpdateUrl = "terceros2Dmedios2Dcontactosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (terceros)
		if (!isset($GLOBALS['terceros'])) $GLOBALS['terceros'] = new cterceros();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'terceros-medios-contactos', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fterceros2Dmedios2Dcontactoslistsrch";

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
		$this->idTercero->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->idTipoContacto->SetVisibility();
		$this->contacto->SetVisibility();

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

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
		global $EW_EXPORT, $terceros2Dmedios2Dcontactos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($terceros2Dmedios2Dcontactos);
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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "terceros") {
			global $terceros;
			$rsmaster = $terceros->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("terceroslist.php"); // Return to master page
			} else {
				$terceros->LoadListRowValues($rsmaster);
				$terceros->RowType = EW_ROWTYPE_MASTER; // Master row
				$terceros->RenderListRow();
				$rsmaster->Close();
			}
		}

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

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fterceros2Dmedios2Dcontactoslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idTercero->AdvancedSearch->ToJSON(), ","); // Field idTercero
		$sFilterList = ew_Concat($sFilterList, $this->denominacion->AdvancedSearch->ToJSON(), ","); // Field denominacion
		$sFilterList = ew_Concat($sFilterList, $this->idTipoContacto->AdvancedSearch->ToJSON(), ","); // Field idTipoContacto
		$sFilterList = ew_Concat($sFilterList, $this->contacto->AdvancedSearch->ToJSON(), ","); // Field contacto
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fterceros2Dmedios2Dcontactoslistsrch", $filters);
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

		// Field idTercero
		$this->idTercero->AdvancedSearch->SearchValue = @$filter["x_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator = @$filter["z_idTercero"];
		$this->idTercero->AdvancedSearch->SearchCondition = @$filter["v_idTercero"];
		$this->idTercero->AdvancedSearch->SearchValue2 = @$filter["y_idTercero"];
		$this->idTercero->AdvancedSearch->SearchOperator2 = @$filter["w_idTercero"];
		$this->idTercero->AdvancedSearch->Save();

		// Field denominacion
		$this->denominacion->AdvancedSearch->SearchValue = @$filter["x_denominacion"];
		$this->denominacion->AdvancedSearch->SearchOperator = @$filter["z_denominacion"];
		$this->denominacion->AdvancedSearch->SearchCondition = @$filter["v_denominacion"];
		$this->denominacion->AdvancedSearch->SearchValue2 = @$filter["y_denominacion"];
		$this->denominacion->AdvancedSearch->SearchOperator2 = @$filter["w_denominacion"];
		$this->denominacion->AdvancedSearch->Save();

		// Field idTipoContacto
		$this->idTipoContacto->AdvancedSearch->SearchValue = @$filter["x_idTipoContacto"];
		$this->idTipoContacto->AdvancedSearch->SearchOperator = @$filter["z_idTipoContacto"];
		$this->idTipoContacto->AdvancedSearch->SearchCondition = @$filter["v_idTipoContacto"];
		$this->idTipoContacto->AdvancedSearch->SearchValue2 = @$filter["y_idTipoContacto"];
		$this->idTipoContacto->AdvancedSearch->SearchOperator2 = @$filter["w_idTipoContacto"];
		$this->idTipoContacto->AdvancedSearch->Save();

		// Field contacto
		$this->contacto->AdvancedSearch->SearchValue = @$filter["x_contacto"];
		$this->contacto->AdvancedSearch->SearchOperator = @$filter["z_contacto"];
		$this->contacto->AdvancedSearch->SearchCondition = @$filter["v_contacto"];
		$this->contacto->AdvancedSearch->SearchValue2 = @$filter["y_contacto"];
		$this->contacto->AdvancedSearch->SearchOperator2 = @$filter["w_contacto"];
		$this->contacto->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->idTercero, $Default, FALSE); // idTercero
		$this->BuildSearchSql($sWhere, $this->denominacion, $Default, FALSE); // denominacion
		$this->BuildSearchSql($sWhere, $this->idTipoContacto, $Default, FALSE); // idTipoContacto
		$this->BuildSearchSql($sWhere, $this->contacto, $Default, FALSE); // contacto

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idTercero->AdvancedSearch->Save(); // idTercero
			$this->denominacion->AdvancedSearch->Save(); // denominacion
			$this->idTipoContacto->AdvancedSearch->Save(); // idTipoContacto
			$this->contacto->AdvancedSearch->Save(); // contacto
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
		$this->BuildBasicSearchSQL($sWhere, $this->denominacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contacto, $arKeywords, $type);
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
		if ($this->idTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denominacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idTipoContacto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contacto->AdvancedSearch->IssetSession())
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
		$this->idTercero->AdvancedSearch->UnsetSession();
		$this->denominacion->AdvancedSearch->UnsetSession();
		$this->idTipoContacto->AdvancedSearch->UnsetSession();
		$this->contacto->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->idTercero->AdvancedSearch->Load();
		$this->denominacion->AdvancedSearch->Load();
		$this->idTipoContacto->AdvancedSearch->Load();
		$this->contacto->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idTercero, $bCtrl); // idTercero
			$this->UpdateSort($this->denominacion, $bCtrl); // denominacion
			$this->UpdateSort($this->idTipoContacto, $bCtrl); // idTipoContacto
			$this->UpdateSort($this->contacto, $bCtrl); // contacto
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->idTercero->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idTercero->setSort("");
				$this->denominacion->setSort("");
				$this->idTipoContacto->setSort("");
				$this->contacto->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fterceros2Dmedios2Dcontactoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fterceros2Dmedios2Dcontactoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fterceros2Dmedios2Dcontactoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fterceros2Dmedios2Dcontactoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->idTercero->CurrentValue = NULL;
		$this->idTercero->OldValue = $this->idTercero->CurrentValue;
		$this->denominacion->CurrentValue = NULL;
		$this->denominacion->OldValue = $this->denominacion->CurrentValue;
		$this->idTipoContacto->CurrentValue = NULL;
		$this->idTipoContacto->OldValue = $this->idTipoContacto->CurrentValue;
		$this->contacto->CurrentValue = NULL;
		$this->contacto->OldValue = $this->contacto->CurrentValue;
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
		// idTercero

		$this->idTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTercero"]);
		if ($this->idTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTercero->AdvancedSearch->SearchOperator = @$_GET["z_idTercero"];

		// denominacion
		$this->denominacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denominacion"]);
		if ($this->denominacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denominacion->AdvancedSearch->SearchOperator = @$_GET["z_denominacion"];

		// idTipoContacto
		$this->idTipoContacto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTipoContacto"]);
		if ($this->idTipoContacto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTipoContacto->AdvancedSearch->SearchOperator = @$_GET["z_idTipoContacto"];

		// contacto
		$this->contacto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contacto"]);
		if ($this->contacto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contacto->AdvancedSearch->SearchOperator = @$_GET["z_contacto"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->idTipoContacto->FldIsDetailKey) {
			$this->idTipoContacto->setFormValue($objForm->GetValue("x_idTipoContacto"));
		}
		if (!$this->contacto->FldIsDetailKey) {
			$this->contacto->setFormValue($objForm->GetValue("x_contacto"));
		}
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->idTipoContacto->CurrentValue = $this->idTipoContacto->FormValue;
		$this->contacto->CurrentValue = $this->contacto->FormValue;
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
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->idTipoContacto->setDbValue($rs->fields('idTipoContacto'));
		$this->contacto->setDbValue($rs->fields('contacto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->idTipoContacto->DbValue = $row['idTipoContacto'];
		$this->contacto->DbValue = $row['contacto'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idTercero
		// denominacion
		// idTipoContacto
		// contacto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
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

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// idTipoContacto
		if (strval($this->idTipoContacto->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoContacto->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-contactos`";
		$sWhereWrk = "";
		$this->idTipoContacto->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTipoContacto->ViewValue = $this->idTipoContacto->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTipoContacto->ViewValue = $this->idTipoContacto->CurrentValue;
			}
		} else {
			$this->idTipoContacto->ViewValue = NULL;
		}
		$this->idTipoContacto->ViewCustomAttributes = "";

		// contacto
		$this->contacto->ViewValue = $this->contacto->CurrentValue;
		$this->contacto->ViewCustomAttributes = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// idTipoContacto
			$this->idTipoContacto->LinkCustomAttributes = "";
			$this->idTipoContacto->HrefValue = "";
			$this->idTipoContacto->TooltipValue = "";

			// contacto
			$this->contacto->LinkCustomAttributes = "";
			$this->contacto->HrefValue = "";
			$this->contacto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idTercero
			$this->idTercero->EditCustomAttributes = "";
			if ($this->idTercero->getSessionValue() <> "") {
				$this->idTercero->CurrentValue = $this->idTercero->getSessionValue();
			if (strval($this->idTercero->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
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
			} else {
			if (trim(strval($this->idTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
			} else {
				$this->idTercero->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;
			}

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// idTipoContacto
			$this->idTipoContacto->EditAttrs["class"] = "form-control";
			$this->idTipoContacto->EditCustomAttributes = "";
			if (trim(strval($this->idTipoContacto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoContacto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-contactos`";
			$sWhereWrk = "";
			$this->idTipoContacto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTipoContacto->EditValue = $arwrk;

			// contacto
			$this->contacto->EditAttrs["class"] = "form-control";
			$this->contacto->EditCustomAttributes = "";
			$this->contacto->EditValue = ew_HtmlEncode($this->contacto->CurrentValue);
			$this->contacto->PlaceHolder = ew_RemoveHtml($this->contacto->FldCaption());

			// Add refer script
			// idTercero

			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// idTipoContacto
			$this->idTipoContacto->LinkCustomAttributes = "";
			$this->idTipoContacto->HrefValue = "";

			// contacto
			$this->contacto->LinkCustomAttributes = "";
			$this->contacto->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idTercero
			$this->idTercero->EditCustomAttributes = "";
			if ($this->idTercero->getSessionValue() <> "") {
				$this->idTercero->CurrentValue = $this->idTercero->getSessionValue();
			if (strval($this->idTercero->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
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
			} else {
			if (trim(strval($this->idTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
			} else {
				$this->idTercero->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;
			}

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// idTipoContacto
			$this->idTipoContacto->EditAttrs["class"] = "form-control";
			$this->idTipoContacto->EditCustomAttributes = "";
			if (trim(strval($this->idTipoContacto->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoContacto->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-contactos`";
			$sWhereWrk = "";
			$this->idTipoContacto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTipoContacto->EditValue = $arwrk;

			// contacto
			$this->contacto->EditAttrs["class"] = "form-control";
			$this->contacto->EditCustomAttributes = "";
			$this->contacto->EditValue = ew_HtmlEncode($this->contacto->CurrentValue);
			$this->contacto->PlaceHolder = ew_RemoveHtml($this->contacto->FldCaption());

			// Edit refer script
			// idTercero

			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// idTipoContacto
			$this->idTipoContacto->LinkCustomAttributes = "";
			$this->idTipoContacto->HrefValue = "";

			// contacto
			$this->contacto->LinkCustomAttributes = "";
			$this->contacto->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// idTercero
			$this->idTercero->EditCustomAttributes = "";
			if (trim(strval($this->idTercero->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->idTercero->AdvancedSearch->ViewValue = $this->idTercero->DisplayValue($arwrk);
			} else {
				$this->idTercero->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->AdvancedSearch->SearchValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// idTipoContacto
			$this->idTipoContacto->EditAttrs["class"] = "form-control";
			$this->idTipoContacto->EditCustomAttributes = "";
			if (trim(strval($this->idTipoContacto->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoContacto->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-contactos`";
			$sWhereWrk = "";
			$this->idTipoContacto->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTipoContacto->EditValue = $arwrk;

			// contacto
			$this->contacto->EditAttrs["class"] = "form-control";
			$this->contacto->EditCustomAttributes = "";
			$this->contacto->EditValue = ew_HtmlEncode($this->contacto->AdvancedSearch->SearchValue);
			$this->contacto->PlaceHolder = ew_RemoveHtml($this->contacto->FldCaption());
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

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

			// denominacion
			$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, $this->denominacion->ReadOnly);

			// idTipoContacto
			$this->idTipoContacto->SetDbValueDef($rsnew, $this->idTipoContacto->CurrentValue, NULL, $this->idTipoContacto->ReadOnly);

			// contacto
			$this->contacto->SetDbValueDef($rsnew, $this->contacto->CurrentValue, NULL, $this->contacto->ReadOnly);

			// Check referential integrity for master table 'terceros'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_terceros();
			$KeyValue = isset($rsnew['idTercero']) ? $rsnew['idTercero'] : $rsold['idTercero'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["terceros"])) $GLOBALS["terceros"] = new cterceros();
				$rsmaster = $GLOBALS["terceros"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "terceros", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

		// Check referential integrity for master table 'terceros'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_terceros();
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id@", ew_AdjustSql($this->idTercero->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["terceros"])) $GLOBALS["terceros"] = new cterceros();
			$rsmaster = $GLOBALS["terceros"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "terceros", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idTercero
		$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, FALSE);

		// denominacion
		$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, FALSE);

		// idTipoContacto
		$this->idTipoContacto->SetDbValueDef($rsnew, $this->idTipoContacto->CurrentValue, NULL, FALSE);

		// contacto
		$this->contacto->SetDbValueDef($rsnew, $this->contacto->CurrentValue, NULL, FALSE);

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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->idTercero->AdvancedSearch->Load();
		$this->denominacion->AdvancedSearch->Load();
		$this->idTipoContacto->AdvancedSearch->Load();
		$this->contacto->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_terceros2Dmedios2Dcontactos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_terceros2Dmedios2Dcontactos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fterceros2Dmedios2Dcontactoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "terceros") {
			global $terceros;
			if (!isset($terceros)) $terceros = new cterceros;
			$rsmaster = $terceros->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$terceros;
					$terceros->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "terceros") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["terceros"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->idTercero->setQueryStringValue($GLOBALS["terceros"]->id->QueryStringValue);
					$this->idTercero->setSessionValue($this->idTercero->QueryStringValue);
					if (!is_numeric($GLOBALS["terceros"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "terceros") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["terceros"]->id->setFormValue($_POST["fk_id"]);
					$this->idTercero->setFormValue($GLOBALS["terceros"]->id->FormValue);
					$this->idTercero->setSessionValue($this->idTercero->FormValue);
					if (!is_numeric($GLOBALS["terceros"]->id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "terceros") {
				if ($this->idTercero->CurrentValue == "") $this->idTercero->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
		case "x_idTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "{filter}";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idTipoContacto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-contactos`";
			$sWhereWrk = "";
			$this->idTipoContacto->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
		case "x_idTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "{filter}";
			$this->idTercero->LookupFilters = array("dx1" => "`denominacion`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idTipoContacto":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-contactos`";
			$sWhereWrk = "";
			$this->idTipoContacto->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTipoContacto, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
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
if (!isset($terceros2Dmedios2Dcontactos_list)) $terceros2Dmedios2Dcontactos_list = new cterceros2Dmedios2Dcontactos_list();

// Page init
$terceros2Dmedios2Dcontactos_list->Page_Init();

// Page main
$terceros2Dmedios2Dcontactos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros2Dmedios2Dcontactos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fterceros2Dmedios2Dcontactoslist = new ew_Form("fterceros2Dmedios2Dcontactoslist", "list");
fterceros2Dmedios2Dcontactoslist.FormKeyCountName = '<?php echo $terceros2Dmedios2Dcontactos_list->FormKeyCountName ?>';

// Validate form
fterceros2Dmedios2Dcontactoslist.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fterceros2Dmedios2Dcontactoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dmedios2Dcontactoslist.ValidateRequired = true;
<?php } else { ?>
fterceros2Dmedios2Dcontactoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fterceros2Dmedios2Dcontactoslist.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fterceros2Dmedios2Dcontactoslist.Lists["x_idTipoContacto"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dcontactos"};

// Form object for search
var CurrentSearchForm = fterceros2Dmedios2Dcontactoslistsrch = new ew_Form("fterceros2Dmedios2Dcontactoslistsrch");

// Validate function for search
fterceros2Dmedios2Dcontactoslistsrch.Validate = function(fobj) {
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
fterceros2Dmedios2Dcontactoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dmedios2Dcontactoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fterceros2Dmedios2Dcontactoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fterceros2Dmedios2Dcontactoslistsrch.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fterceros2Dmedios2Dcontactoslistsrch.Lists["x_idTipoContacto"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dcontactos"};

// Init search panel as collapsed
if (fterceros2Dmedios2Dcontactoslistsrch) fterceros2Dmedios2Dcontactoslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_list->TotalRecs > 0 && $terceros2Dmedios2Dcontactos_list->ExportOptions->Visible()) { ?>
<?php $terceros2Dmedios2Dcontactos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_list->SearchOptions->Visible()) { ?>
<?php $terceros2Dmedios2Dcontactos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_list->FilterOptions->Visible()) { ?>
<?php $terceros2Dmedios2Dcontactos_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($terceros2Dmedios2Dcontactos->Export == "") || (EW_EXPORT_MASTER_RECORD && $terceros2Dmedios2Dcontactos->Export == "print")) { ?>
<?php
if ($terceros2Dmedios2Dcontactos_list->DbMasterFilter <> "" && $terceros2Dmedios2Dcontactos->getCurrentMasterTable() == "terceros") {
	if ($terceros2Dmedios2Dcontactos_list->MasterRecordExists) {
?>
<?php include_once "tercerosmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $terceros2Dmedios2Dcontactos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($terceros2Dmedios2Dcontactos_list->TotalRecs <= 0)
			$terceros2Dmedios2Dcontactos_list->TotalRecs = $terceros2Dmedios2Dcontactos->SelectRecordCount();
	} else {
		if (!$terceros2Dmedios2Dcontactos_list->Recordset && ($terceros2Dmedios2Dcontactos_list->Recordset = $terceros2Dmedios2Dcontactos_list->LoadRecordset()))
			$terceros2Dmedios2Dcontactos_list->TotalRecs = $terceros2Dmedios2Dcontactos_list->Recordset->RecordCount();
	}
	$terceros2Dmedios2Dcontactos_list->StartRec = 1;
	if ($terceros2Dmedios2Dcontactos_list->DisplayRecs <= 0 || ($terceros2Dmedios2Dcontactos->Export <> "" && $terceros2Dmedios2Dcontactos->ExportAll)) // Display all records
		$terceros2Dmedios2Dcontactos_list->DisplayRecs = $terceros2Dmedios2Dcontactos_list->TotalRecs;
	if (!($terceros2Dmedios2Dcontactos->Export <> "" && $terceros2Dmedios2Dcontactos->ExportAll))
		$terceros2Dmedios2Dcontactos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$terceros2Dmedios2Dcontactos_list->Recordset = $terceros2Dmedios2Dcontactos_list->LoadRecordset($terceros2Dmedios2Dcontactos_list->StartRec-1, $terceros2Dmedios2Dcontactos_list->DisplayRecs);

	// Set no record found message
	if ($terceros2Dmedios2Dcontactos->CurrentAction == "" && $terceros2Dmedios2Dcontactos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$terceros2Dmedios2Dcontactos_list->setWarningMessage(ew_DeniedMsg());
		if ($terceros2Dmedios2Dcontactos_list->SearchWhere == "0=101")
			$terceros2Dmedios2Dcontactos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$terceros2Dmedios2Dcontactos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$terceros2Dmedios2Dcontactos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "" && $terceros2Dmedios2Dcontactos->CurrentAction == "") { ?>
<form name="fterceros2Dmedios2Dcontactoslistsrch" id="fterceros2Dmedios2Dcontactoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($terceros2Dmedios2Dcontactos_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fterceros2Dmedios2Dcontactoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="terceros2Dmedios2Dcontactos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$terceros2Dmedios2Dcontactos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$terceros2Dmedios2Dcontactos->ResetAttrs();
$terceros2Dmedios2Dcontactos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
	<div id="xsc_idTercero" class="ewCell form-group">
		<label for="x_idTercero" class="ewSearchCaption ewLabel"><?php echo $terceros2Dmedios2Dcontactos->idTercero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTercero" id="z_idTercero" value="="></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x_idTercero" id="x_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->AdvancedSearch->SearchValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
	<div id="xsc_idTipoContacto" class="ewCell form-group">
		<label for="x_idTipoContacto" class="ewSearchCaption ewLabel"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTipoContacto" id="z_idTipoContacto" value="="></span>
		<span class="ewSearchField">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x_idTipoContacto" name="x_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x_idTipoContacto") ?>
</select>
<input type="hidden" name="s_x_idTipoContacto" id="s_x_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $terceros2Dmedios2Dcontactos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($terceros2Dmedios2Dcontactos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($terceros2Dmedios2Dcontactos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($terceros2Dmedios2Dcontactos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($terceros2Dmedios2Dcontactos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $terceros2Dmedios2Dcontactos_list->ShowPageHeader(); ?>
<?php
$terceros2Dmedios2Dcontactos_list->ShowMessage();
?>
<?php if ($terceros2Dmedios2Dcontactos_list->TotalRecs > 0 || $terceros2Dmedios2Dcontactos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid terceros2Dmedios2Dcontactos">
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction <> "gridadd" && $terceros2Dmedios2Dcontactos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($terceros2Dmedios2Dcontactos_list->Pager)) $terceros2Dmedios2Dcontactos_list->Pager = new cPrevNextPager($terceros2Dmedios2Dcontactos_list->StartRec, $terceros2Dmedios2Dcontactos_list->DisplayRecs, $terceros2Dmedios2Dcontactos_list->TotalRecs) ?>
<?php if ($terceros2Dmedios2Dcontactos_list->Pager->RecordCount > 0 && $terceros2Dmedios2Dcontactos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($terceros2Dmedios2Dcontactos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $terceros2Dmedios2Dcontactos_list->PageUrl() ?>start=<?php echo $terceros2Dmedios2Dcontactos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($terceros2Dmedios2Dcontactos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $terceros2Dmedios2Dcontactos_list->PageUrl() ?>start=<?php echo $terceros2Dmedios2Dcontactos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $terceros2Dmedios2Dcontactos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($terceros2Dmedios2Dcontactos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $terceros2Dmedios2Dcontactos_list->PageUrl() ?>start=<?php echo $terceros2Dmedios2Dcontactos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($terceros2Dmedios2Dcontactos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $terceros2Dmedios2Dcontactos_list->PageUrl() ?>start=<?php echo $terceros2Dmedios2Dcontactos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="terceros2Dmedios2Dcontactos">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($terceros2Dmedios2Dcontactos_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($terceros2Dmedios2Dcontactos_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($terceros2Dmedios2Dcontactos_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($terceros2Dmedios2Dcontactos_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($terceros2Dmedios2Dcontactos_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros2Dmedios2Dcontactos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fterceros2Dmedios2Dcontactoslist" id="fterceros2Dmedios2Dcontactoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros2Dmedios2Dcontactos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros2Dmedios2Dcontactos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros2Dmedios2Dcontactos">
<?php if ($terceros2Dmedios2Dcontactos->getCurrentMasterTable() == "terceros" && $terceros2Dmedios2Dcontactos->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="terceros">
<input type="hidden" name="fk_id" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->getSessionValue() ?>">
<?php } ?>
<div id="gmp_terceros2Dmedios2Dcontactos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($terceros2Dmedios2Dcontactos_list->TotalRecs > 0 || $terceros2Dmedios2Dcontactos->CurrentAction == "add" || $terceros2Dmedios2Dcontactos->CurrentAction == "copy") { ?>
<table id="tbl_terceros2Dmedios2Dcontactoslist" class="table ewTable">
<?php echo $terceros2Dmedios2Dcontactos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$terceros2Dmedios2Dcontactos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$terceros2Dmedios2Dcontactos_list->RenderListOptions();

// Render list options (header, left)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("header", "left");
?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTercero) ?>',2);"><div id="elh_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->denominacion) == "") { ?>
		<th data-name="denominacion"><div id="elh_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->denominacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->denominacion) ?>',2);"><div id="elh_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->denominacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->denominacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->denominacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTipoContacto) == "") { ?>
		<th data-name="idTipoContacto"><div id="elh_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTipoContacto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTipoContacto) ?>',2);"><div id="elh_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->idTipoContacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->contacto) == "") { ?>
		<th data-name="contacto"><div id="elh_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->contacto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contacto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->contacto) ?>',2);"><div id="elh_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->contacto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->contacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->contacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($terceros2Dmedios2Dcontactos->CurrentAction == "add" || $terceros2Dmedios2Dcontactos->CurrentAction == "copy") {
		$terceros2Dmedios2Dcontactos_list->RowIndex = 0;
		$terceros2Dmedios2Dcontactos_list->KeyCount = $terceros2Dmedios2Dcontactos_list->RowIndex;
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "add")
			$terceros2Dmedios2Dcontactos_list->LoadDefaultValues();
		if ($terceros2Dmedios2Dcontactos->EventCancelled) // Insert failed
			$terceros2Dmedios2Dcontactos_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$terceros2Dmedios2Dcontactos->ResetAttrs();
		$terceros2Dmedios2Dcontactos->RowAttrs = array_merge($terceros2Dmedios2Dcontactos->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_terceros2Dmedios2Dcontactos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$terceros2Dmedios2Dcontactos_list->RenderRow();

		// Render list options
		$terceros2Dmedios2Dcontactos_list->RenderListOptions();
		$terceros2Dmedios2Dcontactos_list->StartRowCnt = 0;
?>
	<tr<?php echo $terceros2Dmedios2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("body", "left", $terceros2Dmedios2Dcontactos_list->RowCnt);
?>
	<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($terceros2Dmedios2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->CurrentValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion">
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" id="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
		<td data-name="idTipoContacto">
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipos-contactos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto',url:'tipos2Dcontactosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" id="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" id="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
		<td data-name="contacto">
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->contacto->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->contacto->EditAttributes() ?>>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" id="o<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("body", "right", $terceros2Dmedios2Dcontactos_list->RowCnt);
?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactoslist.UpdateOpts(<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($terceros2Dmedios2Dcontactos->ExportAll && $terceros2Dmedios2Dcontactos->Export <> "") {
	$terceros2Dmedios2Dcontactos_list->StopRec = $terceros2Dmedios2Dcontactos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($terceros2Dmedios2Dcontactos_list->TotalRecs > $terceros2Dmedios2Dcontactos_list->StartRec + $terceros2Dmedios2Dcontactos_list->DisplayRecs - 1)
		$terceros2Dmedios2Dcontactos_list->StopRec = $terceros2Dmedios2Dcontactos_list->StartRec + $terceros2Dmedios2Dcontactos_list->DisplayRecs - 1;
	else
		$terceros2Dmedios2Dcontactos_list->StopRec = $terceros2Dmedios2Dcontactos_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($terceros2Dmedios2Dcontactos_list->FormKeyCountName) && ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd" || $terceros2Dmedios2Dcontactos->CurrentAction == "gridedit" || $terceros2Dmedios2Dcontactos->CurrentAction == "F")) {
		$terceros2Dmedios2Dcontactos_list->KeyCount = $objForm->GetValue($terceros2Dmedios2Dcontactos_list->FormKeyCountName);
		$terceros2Dmedios2Dcontactos_list->StopRec = $terceros2Dmedios2Dcontactos_list->StartRec + $terceros2Dmedios2Dcontactos_list->KeyCount - 1;
	}
}
$terceros2Dmedios2Dcontactos_list->RecCnt = $terceros2Dmedios2Dcontactos_list->StartRec - 1;
if ($terceros2Dmedios2Dcontactos_list->Recordset && !$terceros2Dmedios2Dcontactos_list->Recordset->EOF) {
	$terceros2Dmedios2Dcontactos_list->Recordset->MoveFirst();
	$bSelectLimit = $terceros2Dmedios2Dcontactos_list->UseSelectLimit;
	if (!$bSelectLimit && $terceros2Dmedios2Dcontactos_list->StartRec > 1)
		$terceros2Dmedios2Dcontactos_list->Recordset->Move($terceros2Dmedios2Dcontactos_list->StartRec - 1);
} elseif (!$terceros2Dmedios2Dcontactos->AllowAddDeleteRow && $terceros2Dmedios2Dcontactos_list->StopRec == 0) {
	$terceros2Dmedios2Dcontactos_list->StopRec = $terceros2Dmedios2Dcontactos->GridAddRowCount;
}

// Initialize aggregate
$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$terceros2Dmedios2Dcontactos->ResetAttrs();
$terceros2Dmedios2Dcontactos_list->RenderRow();
$terceros2Dmedios2Dcontactos_list->EditRowCnt = 0;
if ($terceros2Dmedios2Dcontactos->CurrentAction == "edit")
	$terceros2Dmedios2Dcontactos_list->RowIndex = 1;
while ($terceros2Dmedios2Dcontactos_list->RecCnt < $terceros2Dmedios2Dcontactos_list->StopRec) {
	$terceros2Dmedios2Dcontactos_list->RecCnt++;
	if (intval($terceros2Dmedios2Dcontactos_list->RecCnt) >= intval($terceros2Dmedios2Dcontactos_list->StartRec)) {
		$terceros2Dmedios2Dcontactos_list->RowCnt++;

		// Set up key count
		$terceros2Dmedios2Dcontactos_list->KeyCount = $terceros2Dmedios2Dcontactos_list->RowIndex;

		// Init row class and style
		$terceros2Dmedios2Dcontactos->ResetAttrs();
		$terceros2Dmedios2Dcontactos->CssClass = "";
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd") {
			$terceros2Dmedios2Dcontactos_list->LoadDefaultValues(); // Load default values
		} else {
			$terceros2Dmedios2Dcontactos_list->LoadRowValues($terceros2Dmedios2Dcontactos_list->Recordset); // Load row values
		}
		$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "edit") {
			if ($terceros2Dmedios2Dcontactos_list->CheckInlineEditKey() && $terceros2Dmedios2Dcontactos_list->EditRowCnt == 0) { // Inline edit
				$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "edit" && $terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT && $terceros2Dmedios2Dcontactos->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$terceros2Dmedios2Dcontactos_list->RestoreFormValues(); // Restore form values
		}
		if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$terceros2Dmedios2Dcontactos_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$terceros2Dmedios2Dcontactos->RowAttrs = array_merge($terceros2Dmedios2Dcontactos->RowAttrs, array('data-rowindex'=>$terceros2Dmedios2Dcontactos_list->RowCnt, 'id'=>'r' . $terceros2Dmedios2Dcontactos_list->RowCnt . '_terceros2Dmedios2Dcontactos', 'data-rowtype'=>$terceros2Dmedios2Dcontactos->RowType));

		// Render row
		$terceros2Dmedios2Dcontactos_list->RenderRow();

		// Render list options
		$terceros2Dmedios2Dcontactos_list->RenderListOptions();
?>
	<tr<?php echo $terceros2Dmedios2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("body", "left", $terceros2Dmedios2Dcontactos_list->RowCnt);
?>
	<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $terceros2Dmedios2Dcontactos->idTercero->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->CurrentValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTercero->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $terceros2Dmedios2Dcontactos_list->PageObjName . "_row_" . $terceros2Dmedios2Dcontactos_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT || $terceros2Dmedios2Dcontactos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_id" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_id" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion"<?php echo $terceros2Dmedios2Dcontactos->denominacion->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_denominacion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion">
<span<?php echo $terceros2Dmedios2Dcontactos->denominacion->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->denominacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
		<td data-name="idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipos-contactos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto',url:'tipos2Dcontactosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" id="s_x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto">
<span<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
		<td data-name="contacto"<?php echo $terceros2Dmedios2Dcontactos->contacto->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>_contacto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->contacto->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->contacto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_list->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto">
<span<?php echo $terceros2Dmedios2Dcontactos->contacto->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->contacto->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dmedios2Dcontactos_list->ListOptions->Render("body", "right", $terceros2Dmedios2Dcontactos_list->RowCnt);
?>
	</tr>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD || $terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactoslist.UpdateOpts(<?php echo $terceros2Dmedios2Dcontactos_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($terceros2Dmedios2Dcontactos->CurrentAction <> "gridadd")
		$terceros2Dmedios2Dcontactos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction == "add" || $terceros2Dmedios2Dcontactos->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $terceros2Dmedios2Dcontactos_list->FormKeyCountName ?>" id="<?php echo $terceros2Dmedios2Dcontactos_list->FormKeyCountName ?>" value="<?php echo $terceros2Dmedios2Dcontactos_list->KeyCount ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $terceros2Dmedios2Dcontactos_list->FormKeyCountName ?>" id="<?php echo $terceros2Dmedios2Dcontactos_list->FormKeyCountName ?>" value="<?php echo $terceros2Dmedios2Dcontactos_list->KeyCount ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($terceros2Dmedios2Dcontactos_list->Recordset)
	$terceros2Dmedios2Dcontactos_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_list->TotalRecs == 0 && $terceros2Dmedios2Dcontactos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros2Dmedios2Dcontactos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactoslistsrch.FilterList = <?php echo $terceros2Dmedios2Dcontactos_list->GetFilterList() ?>;
fterceros2Dmedios2Dcontactoslistsrch.Init();
fterceros2Dmedios2Dcontactoslist.Init();
</script>
<?php } ?>
<?php
$terceros2Dmedios2Dcontactos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$terceros2Dmedios2Dcontactos_list->Page_Terminate();
?>
