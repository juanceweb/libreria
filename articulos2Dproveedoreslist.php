<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "articulos2Dproveedoresinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "tercerosinfo.php" ?>
<?php include_once "articulosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$articulos2Dproveedores_list = NULL; // Initialize page object first

class carticulos2Dproveedores_list extends carticulos2Dproveedores {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos-proveedores';

	// Page object name
	var $PageObjName = 'articulos2Dproveedores_list';

	// Grid form hidden field names
	var $FormName = 'farticulos2Dproveedoreslist';
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

		// Table object (articulos2Dproveedores)
		if (!isset($GLOBALS["articulos2Dproveedores"]) || get_class($GLOBALS["articulos2Dproveedores"]) == "carticulos2Dproveedores") {
			$GLOBALS["articulos2Dproveedores"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos2Dproveedores"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "articulos2Dproveedoresadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "articulos2Dproveedoresdelete.php";
		$this->MultiUpdateUrl = "articulos2Dproveedoresupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (terceros)
		if (!isset($GLOBALS['terceros'])) $GLOBALS['terceros'] = new cterceros();

		// Table object (articulos)
		if (!isset($GLOBALS['articulos'])) $GLOBALS['articulos'] = new carticulos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'articulos-proveedores', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption farticulos2Dproveedoreslistsrch";

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
		$this->idArticulo->SetVisibility();
		$this->codExterno->SetVisibility();
		$this->idAlicuotaIva->SetVisibility();
		$this->idMoneda->SetVisibility();
		$this->precio->SetVisibility();
		$this->idUnidadMedida->SetVisibility();
		$this->dto1->SetVisibility();
		$this->dto2->SetVisibility();
		$this->dto3->SetVisibility();
		$this->idTercero->SetVisibility();
		$this->precioPesos->SetVisibility();
		$this->ultimaActualizacion->SetVisibility();
		$this->ultimaActualizacion->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $articulos2Dproveedores;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($articulos2Dproveedores);
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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "articulos") {
			global $articulos;
			$rsmaster = $articulos->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("articuloslist.php"); // Return to master page
			} else {
				$articulos->LoadListRowValues($rsmaster);
				$articulos->RowType = EW_ROWTYPE_MASTER; // Master row
				$articulos->RenderListRow();
				$rsmaster->Close();
			}
		}

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
			$this->UpdateSort($this->idArticulo, $bCtrl); // idArticulo
			$this->UpdateSort($this->codExterno, $bCtrl); // codExterno
			$this->UpdateSort($this->idAlicuotaIva, $bCtrl); // idAlicuotaIva
			$this->UpdateSort($this->idMoneda, $bCtrl); // idMoneda
			$this->UpdateSort($this->precio, $bCtrl); // precio
			$this->UpdateSort($this->idUnidadMedida, $bCtrl); // idUnidadMedida
			$this->UpdateSort($this->dto1, $bCtrl); // dto1
			$this->UpdateSort($this->dto2, $bCtrl); // dto2
			$this->UpdateSort($this->dto3, $bCtrl); // dto3
			$this->UpdateSort($this->idTercero, $bCtrl); // idTercero
			$this->UpdateSort($this->precioPesos, $bCtrl); // precioPesos
			$this->UpdateSort($this->ultimaActualizacion, $bCtrl); // ultimaActualizacion
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
				$this->precioPesos->setSort("ASC");
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
				$this->idArticulo->setSessionValue("");
				$this->idTercero->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idArticulo->setSort("");
				$this->codExterno->setSort("");
				$this->idAlicuotaIva->setSort("");
				$this->idMoneda->setSort("");
				$this->precio->setSort("");
				$this->idUnidadMedida->setSort("");
				$this->dto1->setSort("");
				$this->dto2->setSort("");
				$this->dto3->setSort("");
				$this->idTercero->setSort("");
				$this->precioPesos->setSort("");
				$this->ultimaActualizacion->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"farticulos2Dproveedoreslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"farticulos2Dproveedoreslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.farticulos2Dproveedoreslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->idArticulo->setDbValue($rs->fields('idArticulo'));
		$this->codExterno->setDbValue($rs->fields('codExterno'));
		$this->idAlicuotaIva->setDbValue($rs->fields('idAlicuotaIva'));
		$this->idMoneda->setDbValue($rs->fields('idMoneda'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->idUnidadMedida->setDbValue($rs->fields('idUnidadMedida'));
		$this->dto1->setDbValue($rs->fields('dto1'));
		$this->dto2->setDbValue($rs->fields('dto2'));
		$this->dto3->setDbValue($rs->fields('dto3'));
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->precioPesos->setDbValue($rs->fields('precioPesos'));
		$this->ultimaActualizacion->setDbValue($rs->fields('ultimaActualizacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idArticulo->DbValue = $row['idArticulo'];
		$this->codExterno->DbValue = $row['codExterno'];
		$this->idAlicuotaIva->DbValue = $row['idAlicuotaIva'];
		$this->idMoneda->DbValue = $row['idMoneda'];
		$this->precio->DbValue = $row['precio'];
		$this->idUnidadMedida->DbValue = $row['idUnidadMedida'];
		$this->dto1->DbValue = $row['dto1'];
		$this->dto2->DbValue = $row['dto2'];
		$this->dto3->DbValue = $row['dto3'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->precioPesos->DbValue = $row['precioPesos'];
		$this->ultimaActualizacion->DbValue = $row['ultimaActualizacion'];
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
		if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ew_StrToFloat($this->precio->CurrentValue)))
			$this->precio->CurrentValue = ew_StrToFloat($this->precio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dto1->FormValue == $this->dto1->CurrentValue && is_numeric(ew_StrToFloat($this->dto1->CurrentValue)))
			$this->dto1->CurrentValue = ew_StrToFloat($this->dto1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dto2->FormValue == $this->dto2->CurrentValue && is_numeric(ew_StrToFloat($this->dto2->CurrentValue)))
			$this->dto2->CurrentValue = ew_StrToFloat($this->dto2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dto3->FormValue == $this->dto3->CurrentValue && is_numeric(ew_StrToFloat($this->dto3->CurrentValue)))
			$this->dto3->CurrentValue = ew_StrToFloat($this->dto3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->precioPesos->FormValue == $this->precioPesos->CurrentValue && is_numeric(ew_StrToFloat($this->precioPesos->CurrentValue)))
			$this->precioPesos->CurrentValue = ew_StrToFloat($this->precioPesos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idArticulo
		// codExterno
		// idAlicuotaIva
		// idMoneda
		// precio
		// idUnidadMedida
		// dto1
		// dto2
		// dto3
		// idTercero
		// precioPesos
		// ultimaActualizacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idArticulo
		if (strval($this->idArticulo->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idArticulo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacionExterna` AS `DispFld`, `denominacionInterna` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articulos`";
		$sWhereWrk = "";
		$this->idArticulo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idArticulo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->idArticulo->ViewValue = $this->idArticulo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idArticulo->ViewValue = $this->idArticulo->CurrentValue;
			}
		} else {
			$this->idArticulo->ViewValue = NULL;
		}
		$this->idArticulo->ViewCustomAttributes = "";

		// codExterno
		$this->codExterno->ViewValue = $this->codExterno->CurrentValue;
		$this->codExterno->ViewCustomAttributes = "";

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

		// idMoneda
		if (strval($this->idMoneda->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMoneda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `simbolo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `monedas`";
		$sWhereWrk = "";
		$this->idMoneda->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMoneda, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMoneda->ViewValue = $this->idMoneda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMoneda->ViewValue = $this->idMoneda->CurrentValue;
			}
		} else {
			$this->idMoneda->ViewValue = NULL;
		}
		$this->idMoneda->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewCustomAttributes = "";

		// idUnidadMedida
		$this->idUnidadMedida->ViewCustomAttributes = "";

		// dto1
		$this->dto1->ViewValue = $this->dto1->CurrentValue;
		$this->dto1->ViewCustomAttributes = "";

		// dto2
		$this->dto2->ViewValue = $this->dto2->CurrentValue;
		$this->dto2->ViewCustomAttributes = "";

		// dto3
		$this->dto3->ViewValue = $this->dto3->CurrentValue;
		$this->dto3->ViewCustomAttributes = "";

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `dto1` AS `Disp2Fld`, `dto2` AS `Disp3Fld`, `dto3` AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->idTercero->ViewValue = $this->idTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTercero->ViewValue = $this->idTercero->CurrentValue;
			}
		} else {
			$this->idTercero->ViewValue = NULL;
		}
		$this->idTercero->ViewCustomAttributes = "";

		// precioPesos
		$this->precioPesos->ViewValue = $this->precioPesos->CurrentValue;
		$this->precioPesos->ViewCustomAttributes = "";

		// ultimaActualizacion
		$this->ultimaActualizacion->ViewValue = $this->ultimaActualizacion->CurrentValue;
		$this->ultimaActualizacion->ViewValue = ew_FormatDateTime($this->ultimaActualizacion->ViewValue, 0);
		$this->ultimaActualizacion->ViewCustomAttributes = "";

			// idArticulo
			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";
			$this->idArticulo->TooltipValue = "";

			// codExterno
			$this->codExterno->LinkCustomAttributes = "";
			$this->codExterno->HrefValue = "";
			$this->codExterno->TooltipValue = "";

			// idAlicuotaIva
			$this->idAlicuotaIva->LinkCustomAttributes = "";
			$this->idAlicuotaIva->HrefValue = "";
			$this->idAlicuotaIva->TooltipValue = "";

			// idMoneda
			$this->idMoneda->LinkCustomAttributes = "";
			$this->idMoneda->HrefValue = "";
			$this->idMoneda->TooltipValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";
			$this->precio->TooltipValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";
			$this->idUnidadMedida->TooltipValue = "";

			// dto1
			$this->dto1->LinkCustomAttributes = "";
			$this->dto1->HrefValue = "";
			$this->dto1->TooltipValue = "";

			// dto2
			$this->dto2->LinkCustomAttributes = "";
			$this->dto2->HrefValue = "";
			$this->dto2->TooltipValue = "";

			// dto3
			$this->dto3->LinkCustomAttributes = "";
			$this->dto3->HrefValue = "";
			$this->dto3->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// precioPesos
			$this->precioPesos->LinkCustomAttributes = "";
			$this->precioPesos->HrefValue = "";
			$this->precioPesos->TooltipValue = "";

			// ultimaActualizacion
			$this->ultimaActualizacion->LinkCustomAttributes = "";
			$this->ultimaActualizacion->HrefValue = "";
			$this->ultimaActualizacion->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_articulos2Dproveedores\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_articulos2Dproveedores',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.farticulos2Dproveedoreslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "articulos") {
			global $articulos;
			if (!isset($articulos)) $articulos = new carticulos;
			$rsmaster = $articulos->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$articulos;
					$articulos->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}

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
			if ($sMasterTblVar == "articulos") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["articulos"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->idArticulo->setQueryStringValue($GLOBALS["articulos"]->id->QueryStringValue);
					$this->idArticulo->setSessionValue($this->idArticulo->QueryStringValue);
					if (!is_numeric($GLOBALS["articulos"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
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
			if ($sMasterTblVar == "articulos") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["articulos"]->id->setFormValue($_POST["fk_id"]);
					$this->idArticulo->setFormValue($GLOBALS["articulos"]->id->FormValue);
					$this->idArticulo->setSessionValue($this->idArticulo->FormValue);
					if (!is_numeric($GLOBALS["articulos"]->id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
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
			if ($sMasterTblVar <> "articulos") {
				if ($this->idArticulo->CurrentValue == "") $this->idArticulo->setSessionValue("");
			}
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
			$opt = &$this->ListOptions->Add("asignarpreciocompra");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

		$sql="SELECT idPrecioCompra FROM articulos WHERE id = '".$_GET["fk_id"]."'";
		$rs = ew_LoadRecordset($sql);
		$rows = $rs->GetRows();
		if($rows[0]["idPrecioCompra"]==$this->id->DbValue){
			$this->ListOptions->Items["asignarpreciocompra"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Asignar Precio de Compra" onclick="asignarpreciocompra('.$this->id->DbValue.','.ltrim($_GET["fk_id"], '0').')" href="javascript:void(0)"><span style="color: green;" class="glyphicon glyphicon-ok ewIcon" aria-hidden="true"></span></a></div>';	
		}else{
			$this->ListOptions->Items["asignarpreciocompra"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Asignar Precio de Compra" onclick="asignarpreciocompra('.$this->id->DbValue.','.ltrim($_GET["fk_id"], '0').')" href="javascript:void(0)"><span class="glyphicon glyphicon-ok ewIcon" aria-hidden="true"></span></a></div>';
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
if (!isset($articulos2Dproveedores_list)) $articulos2Dproveedores_list = new carticulos2Dproveedores_list();

// Page init
$articulos2Dproveedores_list->Page_Init();

// Page main
$articulos2Dproveedores_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dproveedores_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = farticulos2Dproveedoreslist = new ew_Form("farticulos2Dproveedoreslist", "list");
farticulos2Dproveedoreslist.FormKeyCountName = '<?php echo $articulos2Dproveedores_list->FormKeyCountName ?>';

// Form_CustomValidate event
farticulos2Dproveedoreslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dproveedoreslist.ValidateRequired = true;
<?php } else { ?>
farticulos2Dproveedoreslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dproveedoreslist.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dproveedoreslist.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulos2Dproveedoreslist.Lists["x_idMoneda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_simbolo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"monedas"};
farticulos2Dproveedoreslist.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_dto1","x_dto2","x_dto3"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<div class="ewToolbar">
<?php if ($articulos2Dproveedores->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($articulos2Dproveedores_list->TotalRecs > 0 && $articulos2Dproveedores_list->ExportOptions->Visible()) { ?>
<?php $articulos2Dproveedores_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($articulos2Dproveedores->Export == "") || (EW_EXPORT_MASTER_RECORD && $articulos2Dproveedores->Export == "print")) { ?>
<?php
if ($articulos2Dproveedores_list->DbMasterFilter <> "" && $articulos2Dproveedores->getCurrentMasterTable() == "articulos") {
	if ($articulos2Dproveedores_list->MasterRecordExists) {
?>
<?php include_once "articulosmaster.php" ?>
<?php
	}
}
?>
<?php
if ($articulos2Dproveedores_list->DbMasterFilter <> "" && $articulos2Dproveedores->getCurrentMasterTable() == "terceros") {
	if ($articulos2Dproveedores_list->MasterRecordExists) {
?>
<?php include_once "tercerosmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $articulos2Dproveedores_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($articulos2Dproveedores_list->TotalRecs <= 0)
			$articulos2Dproveedores_list->TotalRecs = $articulos2Dproveedores->SelectRecordCount();
	} else {
		if (!$articulos2Dproveedores_list->Recordset && ($articulos2Dproveedores_list->Recordset = $articulos2Dproveedores_list->LoadRecordset()))
			$articulos2Dproveedores_list->TotalRecs = $articulos2Dproveedores_list->Recordset->RecordCount();
	}
	$articulos2Dproveedores_list->StartRec = 1;
	if ($articulos2Dproveedores_list->DisplayRecs <= 0 || ($articulos2Dproveedores->Export <> "" && $articulos2Dproveedores->ExportAll)) // Display all records
		$articulos2Dproveedores_list->DisplayRecs = $articulos2Dproveedores_list->TotalRecs;
	if (!($articulos2Dproveedores->Export <> "" && $articulos2Dproveedores->ExportAll))
		$articulos2Dproveedores_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$articulos2Dproveedores_list->Recordset = $articulos2Dproveedores_list->LoadRecordset($articulos2Dproveedores_list->StartRec-1, $articulos2Dproveedores_list->DisplayRecs);

	// Set no record found message
	if ($articulos2Dproveedores->CurrentAction == "" && $articulos2Dproveedores_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$articulos2Dproveedores_list->setWarningMessage(ew_DeniedMsg());
		if ($articulos2Dproveedores_list->SearchWhere == "0=101")
			$articulos2Dproveedores_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$articulos2Dproveedores_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$articulos2Dproveedores_list->RenderOtherOptions();
?>
<?php $articulos2Dproveedores_list->ShowPageHeader(); ?>
<?php
$articulos2Dproveedores_list->ShowMessage();
?>
<?php if ($articulos2Dproveedores_list->TotalRecs > 0 || $articulos2Dproveedores->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid articulos2Dproveedores">
<?php if ($articulos2Dproveedores->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($articulos2Dproveedores->CurrentAction <> "gridadd" && $articulos2Dproveedores->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($articulos2Dproveedores_list->Pager)) $articulos2Dproveedores_list->Pager = new cPrevNextPager($articulos2Dproveedores_list->StartRec, $articulos2Dproveedores_list->DisplayRecs, $articulos2Dproveedores_list->TotalRecs) ?>
<?php if ($articulos2Dproveedores_list->Pager->RecordCount > 0 && $articulos2Dproveedores_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($articulos2Dproveedores_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $articulos2Dproveedores_list->PageUrl() ?>start=<?php echo $articulos2Dproveedores_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($articulos2Dproveedores_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $articulos2Dproveedores_list->PageUrl() ?>start=<?php echo $articulos2Dproveedores_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $articulos2Dproveedores_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($articulos2Dproveedores_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $articulos2Dproveedores_list->PageUrl() ?>start=<?php echo $articulos2Dproveedores_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($articulos2Dproveedores_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $articulos2Dproveedores_list->PageUrl() ?>start=<?php echo $articulos2Dproveedores_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $articulos2Dproveedores_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $articulos2Dproveedores_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $articulos2Dproveedores_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $articulos2Dproveedores_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($articulos2Dproveedores_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="articulos2Dproveedores">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($articulos2Dproveedores_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($articulos2Dproveedores_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($articulos2Dproveedores_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($articulos2Dproveedores_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($articulos2Dproveedores_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos2Dproveedores_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="farticulos2Dproveedoreslist" id="farticulos2Dproveedoreslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos2Dproveedores_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos2Dproveedores_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos2Dproveedores">
<?php if ($articulos2Dproveedores->getCurrentMasterTable() == "articulos" && $articulos2Dproveedores->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="articulos">
<input type="hidden" name="fk_id" value="<?php echo $articulos2Dproveedores->idArticulo->getSessionValue() ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->getCurrentMasterTable() == "terceros" && $articulos2Dproveedores->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="terceros">
<input type="hidden" name="fk_id" value="<?php echo $articulos2Dproveedores->idTercero->getSessionValue() ?>">
<?php } ?>
<div id="gmp_articulos2Dproveedores" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($articulos2Dproveedores_list->TotalRecs > 0) { ?>
<table id="tbl_articulos2Dproveedoreslist" class="table ewTable">
<?php echo $articulos2Dproveedores->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$articulos2Dproveedores_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$articulos2Dproveedores_list->RenderListOptions();

// Render list options (header, left)
$articulos2Dproveedores_list->ListOptions->Render("header", "left");
?>
<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idArticulo) == "") { ?>
		<th data-name="idArticulo"><div id="elh_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idArticulo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->idArticulo) ?>',2);"><div id="elh_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idArticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idArticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->codExterno) == "") { ?>
		<th data-name="codExterno"><div id="elh_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codExterno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->codExterno) ?>',2);"><div id="elh_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->codExterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->codExterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idAlicuotaIva) == "") { ?>
		<th data-name="idAlicuotaIva"><div id="elh_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idAlicuotaIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->idAlicuotaIva) ?>',2);"><div id="elh_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idAlicuotaIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idAlicuotaIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idMoneda) == "") { ?>
		<th data-name="idMoneda"><div id="elh_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idMoneda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->idMoneda) ?>',2);"><div id="elh_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idMoneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idMoneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->precio) == "") { ?>
		<th data-name="precio"><div id="elh_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->precio) ?>',2);"><div id="elh_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->precio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->precio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idUnidadMedida) == "") { ?>
		<th data-name="idUnidadMedida"><div id="elh_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idUnidadMedida"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->idUnidadMedida) ?>',2);"><div id="elh_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idUnidadMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idUnidadMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto1) == "") { ?>
		<th data-name="dto1"><div id="elh_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto1) ?>',2);"><div id="elh_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto2) == "") { ?>
		<th data-name="dto2"><div id="elh_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto2) ?>',2);"><div id="elh_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto3) == "") { ?>
		<th data-name="dto3"><div id="elh_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto3) ?>',2);"><div id="elh_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->idTercero) ?>',2);"><div id="elh_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->precioPesos) == "") { ?>
		<th data-name="precioPesos"><div id="elh_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precioPesos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precioPesos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->precioPesos) ?>',2);"><div id="elh_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precioPesos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->precioPesos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->precioPesos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->ultimaActualizacion) == "") { ?>
		<th data-name="ultimaActualizacion"><div id="elh_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->ultimaActualizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ultimaActualizacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $articulos2Dproveedores->SortUrl($articulos2Dproveedores->ultimaActualizacion) ?>',2);"><div id="elh_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->ultimaActualizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->ultimaActualizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->ultimaActualizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$articulos2Dproveedores_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($articulos2Dproveedores->ExportAll && $articulos2Dproveedores->Export <> "") {
	$articulos2Dproveedores_list->StopRec = $articulos2Dproveedores_list->TotalRecs;
} else {

	// Set the last record to display
	if ($articulos2Dproveedores_list->TotalRecs > $articulos2Dproveedores_list->StartRec + $articulos2Dproveedores_list->DisplayRecs - 1)
		$articulos2Dproveedores_list->StopRec = $articulos2Dproveedores_list->StartRec + $articulos2Dproveedores_list->DisplayRecs - 1;
	else
		$articulos2Dproveedores_list->StopRec = $articulos2Dproveedores_list->TotalRecs;
}
$articulos2Dproveedores_list->RecCnt = $articulos2Dproveedores_list->StartRec - 1;
if ($articulos2Dproveedores_list->Recordset && !$articulos2Dproveedores_list->Recordset->EOF) {
	$articulos2Dproveedores_list->Recordset->MoveFirst();
	$bSelectLimit = $articulos2Dproveedores_list->UseSelectLimit;
	if (!$bSelectLimit && $articulos2Dproveedores_list->StartRec > 1)
		$articulos2Dproveedores_list->Recordset->Move($articulos2Dproveedores_list->StartRec - 1);
} elseif (!$articulos2Dproveedores->AllowAddDeleteRow && $articulos2Dproveedores_list->StopRec == 0) {
	$articulos2Dproveedores_list->StopRec = $articulos2Dproveedores->GridAddRowCount;
}

// Initialize aggregate
$articulos2Dproveedores->RowType = EW_ROWTYPE_AGGREGATEINIT;
$articulos2Dproveedores->ResetAttrs();
$articulos2Dproveedores_list->RenderRow();
while ($articulos2Dproveedores_list->RecCnt < $articulos2Dproveedores_list->StopRec) {
	$articulos2Dproveedores_list->RecCnt++;
	if (intval($articulos2Dproveedores_list->RecCnt) >= intval($articulos2Dproveedores_list->StartRec)) {
		$articulos2Dproveedores_list->RowCnt++;

		// Set up key count
		$articulos2Dproveedores_list->KeyCount = $articulos2Dproveedores_list->RowIndex;

		// Init row class and style
		$articulos2Dproveedores->ResetAttrs();
		$articulos2Dproveedores->CssClass = "";
		if ($articulos2Dproveedores->CurrentAction == "gridadd") {
		} else {
			$articulos2Dproveedores_list->LoadRowValues($articulos2Dproveedores_list->Recordset); // Load row values
		}
		$articulos2Dproveedores->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$articulos2Dproveedores->RowAttrs = array_merge($articulos2Dproveedores->RowAttrs, array('data-rowindex'=>$articulos2Dproveedores_list->RowCnt, 'id'=>'r' . $articulos2Dproveedores_list->RowCnt . '_articulos2Dproveedores', 'data-rowtype'=>$articulos2Dproveedores->RowType));

		// Render row
		$articulos2Dproveedores_list->RenderRow();

		// Render list options
		$articulos2Dproveedores_list->RenderListOptions();
?>
	<tr<?php echo $articulos2Dproveedores->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dproveedores_list->ListOptions->Render("body", "left", $articulos2Dproveedores_list->RowCnt);
?>
	<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo"<?php echo $articulos2Dproveedores->idArticulo->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->ListViewValue() ?></span>
</span>
<a id="<?php echo $articulos2Dproveedores_list->PageObjName . "_row_" . $articulos2Dproveedores_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
		<td data-name="codExterno"<?php echo $articulos2Dproveedores->codExterno->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno">
<span<?php echo $articulos2Dproveedores->codExterno->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->codExterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td data-name="idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva">
<span<?php echo $articulos2Dproveedores->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
		<td data-name="idMoneda"<?php echo $articulos2Dproveedores->idMoneda->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda">
<span<?php echo $articulos2Dproveedores->idMoneda->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
		<td data-name="precio"<?php echo $articulos2Dproveedores->precio->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio">
<span<?php echo $articulos2Dproveedores->precio->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida">
<span<?php echo $articulos2Dproveedores->idUnidadMedida->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
		<td data-name="dto1"<?php echo $articulos2Dproveedores->dto1->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1">
<span<?php echo $articulos2Dproveedores->dto1->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
		<td data-name="dto2"<?php echo $articulos2Dproveedores->dto2->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2">
<span<?php echo $articulos2Dproveedores->dto2->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
		<td data-name="dto3"<?php echo $articulos2Dproveedores->dto3->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3">
<span<?php echo $articulos2Dproveedores->dto3->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $articulos2Dproveedores->idTercero->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
		<td data-name="precioPesos"<?php echo $articulos2Dproveedores->precioPesos->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos">
<span<?php echo $articulos2Dproveedores->precioPesos->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precioPesos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
		<td data-name="ultimaActualizacion"<?php echo $articulos2Dproveedores->ultimaActualizacion->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_list->RowCnt ?>_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion">
<span<?php echo $articulos2Dproveedores->ultimaActualizacion->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->ultimaActualizacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dproveedores_list->ListOptions->Render("body", "right", $articulos2Dproveedores_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($articulos2Dproveedores->CurrentAction <> "gridadd")
		$articulos2Dproveedores_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($articulos2Dproveedores->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($articulos2Dproveedores_list->Recordset)
	$articulos2Dproveedores_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($articulos2Dproveedores_list->TotalRecs == 0 && $articulos2Dproveedores->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos2Dproveedores_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<script type="text/javascript">
farticulos2Dproveedoreslist.Init();
</script>
<?php } ?>
<?php
$articulos2Dproveedores_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$articulos2Dproveedores_list->Page_Terminate();
?>
