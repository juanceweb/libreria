<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "comprobantes2Dbloqueados2Dcondiciones2Divainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "condiciones2Divainfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$comprobantes2Dbloqueados2Dcondiciones2Diva_list = NULL; // Initialize page object first

class ccomprobantes2Dbloqueados2Dcondiciones2Diva_list extends ccomprobantes2Dbloqueados2Dcondiciones2Diva {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'comprobantes-bloqueados-condiciones-iva';

	// Page object name
	var $PageObjName = 'comprobantes2Dbloqueados2Dcondiciones2Diva_list';

	// Grid form hidden field names
	var $FormName = 'fcomprobantes2Dbloqueados2Dcondiciones2Divalist';
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

		// Table object (comprobantes2Dbloqueados2Dcondiciones2Diva)
		if (!isset($GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"]) || get_class($GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"]) == "ccomprobantes2Dbloqueados2Dcondiciones2Diva") {
			$GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "comprobantes2Dbloqueados2Dcondiciones2Divaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "comprobantes2Dbloqueados2Dcondiciones2Divadelete.php";
		$this->MultiUpdateUrl = "comprobantes2Dbloqueados2Dcondiciones2Divaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (condiciones2Diva)
		if (!isset($GLOBALS['condiciones2Diva'])) $GLOBALS['condiciones2Diva'] = new ccondiciones2Diva();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'comprobantes-bloqueados-condiciones-iva', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fcomprobantes2Dbloqueados2Dcondiciones2Divalistsrch";

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
		$this->idCondicionIva->SetVisibility();
		$this->idComprobanteBloqueado->SetVisibility();

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
		global $EW_EXPORT, $comprobantes2Dbloqueados2Dcondiciones2Diva;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($comprobantes2Dbloqueados2Dcondiciones2Diva);
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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "condiciones2Diva") {
			global $condiciones2Diva;
			$rsmaster = $condiciones2Diva->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("condiciones2Divalist.php"); // Return to master page
			} else {
				$condiciones2Diva->LoadListRowValues($rsmaster);
				$condiciones2Diva->RowType = EW_ROWTYPE_MASTER; // Master row
				$condiciones2Diva->RenderListRow();
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
			$this->UpdateSort($this->idCondicionIva, $bCtrl); // idCondicionIva
			$this->UpdateSort($this->idComprobanteBloqueado, $bCtrl); // idComprobanteBloqueado
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->idCondicionIva->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idCondicionIva->setSort("");
				$this->idComprobanteBloqueado->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fcomprobantes2Dbloqueados2Dcondiciones2Divalistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fcomprobantes2Dbloqueados2Dcondiciones2Divalistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fcomprobantes2Dbloqueados2Dcondiciones2Divalist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->idCondicionIva->setDbValue($rs->fields('idCondicionIva'));
		$this->idComprobanteBloqueado->setDbValue($rs->fields('idComprobanteBloqueado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idCondicionIva->DbValue = $row['idCondicionIva'];
		$this->idComprobanteBloqueado->DbValue = $row['idComprobanteBloqueado'];
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
		// idCondicionIva
		// idComprobanteBloqueado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idCondicionIva
		if (strval($this->idCondicionIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCondicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
		$sWhereWrk = "";
		$this->idCondicionIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCondicionIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->CurrentValue;
			}
		} else {
			$this->idCondicionIva->ViewValue = NULL;
		}
		$this->idCondicionIva->ViewCustomAttributes = "";

		// idComprobanteBloqueado
		if (strval($this->idComprobanteBloqueado->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idComprobanteBloqueado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
		$sWhereWrk = "";
		$this->idComprobanteBloqueado->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idComprobanteBloqueado, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idComprobanteBloqueado->ViewValue = $this->idComprobanteBloqueado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idComprobanteBloqueado->ViewValue = $this->idComprobanteBloqueado->CurrentValue;
			}
		} else {
			$this->idComprobanteBloqueado->ViewValue = NULL;
		}
		$this->idComprobanteBloqueado->ViewCustomAttributes = "";

			// idCondicionIva
			$this->idCondicionIva->LinkCustomAttributes = "";
			$this->idCondicionIva->HrefValue = "";
			$this->idCondicionIva->TooltipValue = "";

			// idComprobanteBloqueado
			$this->idComprobanteBloqueado->LinkCustomAttributes = "";
			$this->idComprobanteBloqueado->HrefValue = "";
			$this->idComprobanteBloqueado->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_comprobantes2Dbloqueados2Dcondiciones2Diva\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_comprobantes2Dbloqueados2Dcondiciones2Diva',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fcomprobantes2Dbloqueados2Dcondiciones2Divalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "condiciones2Diva") {
			global $condiciones2Diva;
			if (!isset($condiciones2Diva)) $condiciones2Diva = new ccondiciones2Diva;
			$rsmaster = $condiciones2Diva->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$condiciones2Diva;
					$condiciones2Diva->ExportDocument($Doc, $rsmaster, 1, 1);
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
			if ($sMasterTblVar == "condiciones2Diva") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["condiciones2Diva"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->idCondicionIva->setQueryStringValue($GLOBALS["condiciones2Diva"]->id->QueryStringValue);
					$this->idCondicionIva->setSessionValue($this->idCondicionIva->QueryStringValue);
					if (!is_numeric($GLOBALS["condiciones2Diva"]->id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "condiciones2Diva") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["condiciones2Diva"]->id->setFormValue($_POST["fk_id"]);
					$this->idCondicionIva->setFormValue($GLOBALS["condiciones2Diva"]->id->FormValue);
					$this->idCondicionIva->setSessionValue($this->idCondicionIva->FormValue);
					if (!is_numeric($GLOBALS["condiciones2Diva"]->id->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "condiciones2Diva") {
				if ($this->idCondicionIva->CurrentValue == "") $this->idCondicionIva->setSessionValue("");
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
if (!isset($comprobantes2Dbloqueados2Dcondiciones2Diva_list)) $comprobantes2Dbloqueados2Dcondiciones2Diva_list = new ccomprobantes2Dbloqueados2Dcondiciones2Diva_list();

// Page init
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Page_Init();

// Page main
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fcomprobantes2Dbloqueados2Dcondiciones2Divalist = new ew_Form("fcomprobantes2Dbloqueados2Dcondiciones2Divalist", "list");
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.FormKeyCountName = '<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.ValidateRequired = true;
<?php } else { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.Lists["x_idCondicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.Lists["x_idComprobanteBloqueado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<div class="ewToolbar">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs > 0 && $comprobantes2Dbloqueados2Dcondiciones2Diva_list->ExportOptions->Visible()) { ?>
<?php $comprobantes2Dbloqueados2Dcondiciones2Diva_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") || (EW_EXPORT_MASTER_RECORD && $comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "print")) { ?>
<?php
if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DbMasterFilter <> "" && $comprobantes2Dbloqueados2Dcondiciones2Diva->getCurrentMasterTable() == "condiciones2Diva") {
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->MasterRecordExists) {
?>
<?php include_once "condiciones2Divamaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs <= 0)
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva->SelectRecordCount();
	} else {
		if (!$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset && ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->LoadRecordset()))
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->RecordCount();
	}
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec = 1;
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs <= 0 || ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export <> "" && $comprobantes2Dbloqueados2Dcondiciones2Diva->ExportAll)) // Display all records
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs;
	if (!($comprobantes2Dbloqueados2Dcondiciones2Diva->Export <> "" && $comprobantes2Dbloqueados2Dcondiciones2Diva->ExportAll))
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->LoadRecordset($comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec-1, $comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs);

	// Set no record found message
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "" && $comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->setWarningMessage(ew_DeniedMsg());
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->SearchWhere == "0=101")
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RenderOtherOptions();
?>
<?php $comprobantes2Dbloqueados2Dcondiciones2Diva_list->ShowPageHeader(); ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ShowMessage();
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs > 0 || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid comprobantes2Dbloqueados2Dcondiciones2Diva">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "gridadd" && $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager)) $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager = new cPrevNextPager($comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec, $comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs, $comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs) ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->RecordCount > 0 && $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->PageUrl() ?>start=<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->PageUrl() ?>start=<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->PageUrl() ?>start=<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->PageUrl() ?>start=<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="comprobantes2Dbloqueados2Dcondiciones2Diva">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fcomprobantes2Dbloqueados2Dcondiciones2Divalist" id="fcomprobantes2Dbloqueados2Dcondiciones2Divalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="comprobantes2Dbloqueados2Dcondiciones2Diva">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->getCurrentMasterTable() == "condiciones2Diva" && $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="condiciones2Diva">
<input type="hidden" name="fk_id" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSessionValue() ?>">
<?php } ?>
<div id="gmp_comprobantes2Dbloqueados2Dcondiciones2Diva" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs > 0) { ?>
<table id="tbl_comprobantes2Dbloqueados2Dcondiciones2Divalist" class="table ewTable">
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RenderListOptions();

// Render list options (header, left)
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ListOptions->Render("header", "left");
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva) == "") { ?>
		<th data-name="idCondicionIva"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCondicionIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva) ?>',2);"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado) == "") { ?>
		<th data-name="idComprobanteBloqueado"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idComprobanteBloqueado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado) ?>',2);"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($comprobantes2Dbloqueados2Dcondiciones2Diva->ExportAll && $comprobantes2Dbloqueados2Dcondiciones2Diva->Export <> "") {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs;
} else {

	// Set the last record to display
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs > $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec + $comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs - 1)
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec + $comprobantes2Dbloqueados2Dcondiciones2Diva_list->DisplayRecs - 1;
	else
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs;
}
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RecCnt = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec - 1;
if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset && !$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->EOF) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->MoveFirst();
	$bSelectLimit = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->UseSelectLimit;
	if (!$bSelectLimit && $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec > 1)
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->Move($comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec - 1);
} elseif (!$comprobantes2Dbloqueados2Dcondiciones2Diva->AllowAddDeleteRow && $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec == 0) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva->GridAddRowCount;
}

// Initialize aggregate
$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_AGGREGATEINIT;
$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RenderRow();
while ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->RecCnt < $comprobantes2Dbloqueados2Dcondiciones2Diva_list->StopRec) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RecCnt++;
	if (intval($comprobantes2Dbloqueados2Dcondiciones2Diva_list->RecCnt) >= intval($comprobantes2Dbloqueados2Dcondiciones2Diva_list->StartRec)) {
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt++;

		// Set up key count
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->KeyCount = $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowIndex;

		// Init row class and style
		$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
		$comprobantes2Dbloqueados2Dcondiciones2Diva->CssClass = "";
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd") {
		} else {
			$comprobantes2Dbloqueados2Dcondiciones2Diva_list->LoadRowValues($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset); // Load row values
		}
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs = array_merge($comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs, array('data-rowindex'=>$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt, 'id'=>'r' . $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt . '_comprobantes2Dbloqueados2Dcondiciones2Diva', 'data-rowtype'=>$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType));

		// Render row
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RenderRow();

		// Render list options
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->RenderListOptions();
?>
	<tr<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttributes() ?>>
<?php

// Render list options (body, left)
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ListOptions->Render("body", "left", $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt);
?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
		<td data-name="idCondicionIva"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CellAttributes() ?>>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ListViewValue() ?></span>
</span>
<a id="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->PageObjName . "_row_" . $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
		<td data-name="idComprobanteBloqueado"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->CellAttributes() ?>>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ListOptions->Render("body", "right", $comprobantes2Dbloqueados2Dcondiciones2Diva_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "gridadd")
		$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset)
	$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->TotalRecs == 0 && $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($comprobantes2Dbloqueados2Dcondiciones2Diva_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<script type="text/javascript">
fcomprobantes2Dbloqueados2Dcondiciones2Divalist.Init();
</script>
<?php } ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_list->Page_Terminate();
?>
