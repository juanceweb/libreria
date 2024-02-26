<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tercerosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "terceros2Dmedios2Dcontactosgridcls.php" ?>
<?php include_once "articulos2Dterceros2Ddescuentosgridcls.php" ?>
<?php include_once "articulos2Dproveedoresgridcls.php" ?>
<?php include_once "subcategoria2Dterceros2Ddescuentosgridcls.php" ?>
<?php include_once "categorias2Dterceros2Ddescuentosgridcls.php" ?>
<?php include_once "sucursalesgridcls.php" ?>
<?php include_once "descuentosasociadosgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$terceros_list = NULL; // Initialize page object first

class cterceros_list extends cterceros {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros';

	// Page object name
	var $PageObjName = 'terceros_list';

	// Grid form hidden field names
	var $FormName = 'fterceroslist';
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

		// Table object (terceros)
		if (!isset($GLOBALS["terceros"]) || get_class($GLOBALS["terceros"]) == "cterceros") {
			$GLOBALS["terceros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["terceros"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tercerosadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tercerosdelete.php";
		$this->MultiUpdateUrl = "tercerosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'terceros', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fterceroslistsrch";

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
		$this->idTipoTercero->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->direccion->SetVisibility();
		$this->documento->SetVisibility();
		$this->idTransporte->SetVisibility();
		$this->limiteDescubierto->SetVisibility();
		$this->codigoPostal->SetVisibility();
		$this->codigoPostalFiscal->SetVisibility();
		$this->condicionVenta->SetVisibility();

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

			// Process auto fill for detail table 'terceros-medios-contactos'
			if (@$_POST["grid"] == "fterceros2Dmedios2Dcontactosgrid") {
				if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"])) $GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid;
				$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->Page_Init();
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

			// Process auto fill for detail table 'articulos-proveedores'
			if (@$_POST["grid"] == "farticulos2Dproveedoresgrid") {
				if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;
				$GLOBALS["articulos2Dproveedores_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'subcategoria-terceros-descuentos'
			if (@$_POST["grid"] == "fsubcategoria2Dterceros2Ddescuentosgrid") {
				if (!isset($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"])) $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] = new csubcategoria2Dterceros2Ddescuentos_grid;
				$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'categorias-terceros-descuentos'
			if (@$_POST["grid"] == "fcategorias2Dterceros2Ddescuentosgrid") {
				if (!isset($GLOBALS["categorias2Dterceros2Ddescuentos_grid"])) $GLOBALS["categorias2Dterceros2Ddescuentos_grid"] = new ccategorias2Dterceros2Ddescuentos_grid;
				$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'sucursales'
			if (@$_POST["grid"] == "fsucursalesgrid") {
				if (!isset($GLOBALS["sucursales_grid"])) $GLOBALS["sucursales_grid"] = new csucursales_grid;
				$GLOBALS["sucursales_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'descuentosasociados'
			if (@$_POST["grid"] == "fdescuentosasociadosgrid") {
				if (!isset($GLOBALS["descuentosasociados_grid"])) $GLOBALS["descuentosasociados_grid"] = new cdescuentosasociados_grid;
				$GLOBALS["descuentosasociados_grid"]->Page_Init();
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
		global $EW_EXPORT, $terceros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($terceros);
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fterceroslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idTipoTercero->AdvancedSearch->ToJSON(), ","); // Field idTipoTercero
		$sFilterList = ew_Concat($sFilterList, $this->denominacion->AdvancedSearch->ToJSON(), ","); // Field denominacion
		$sFilterList = ew_Concat($sFilterList, $this->razonSocial->AdvancedSearch->ToJSON(), ","); // Field razonSocial
		$sFilterList = ew_Concat($sFilterList, $this->denominacionCorta->AdvancedSearch->ToJSON(), ","); // Field denominacionCorta
		$sFilterList = ew_Concat($sFilterList, $this->idPais->AdvancedSearch->ToJSON(), ","); // Field idPais
		$sFilterList = ew_Concat($sFilterList, $this->idProvincia->AdvancedSearch->ToJSON(), ","); // Field idProvincia
		$sFilterList = ew_Concat($sFilterList, $this->idPartido->AdvancedSearch->ToJSON(), ","); // Field idPartido
		$sFilterList = ew_Concat($sFilterList, $this->idLocalidad->AdvancedSearch->ToJSON(), ","); // Field idLocalidad
		$sFilterList = ew_Concat($sFilterList, $this->calle->AdvancedSearch->ToJSON(), ","); // Field calle
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJSON(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->domicilioFiscal->AdvancedSearch->ToJSON(), ","); // Field domicilioFiscal
		$sFilterList = ew_Concat($sFilterList, $this->idPaisFiscal->AdvancedSearch->ToJSON(), ","); // Field idPaisFiscal
		$sFilterList = ew_Concat($sFilterList, $this->idProvinciaFiscal->AdvancedSearch->ToJSON(), ","); // Field idProvinciaFiscal
		$sFilterList = ew_Concat($sFilterList, $this->idPartidoFiscal->AdvancedSearch->ToJSON(), ","); // Field idPartidoFiscal
		$sFilterList = ew_Concat($sFilterList, $this->idLocalidadFiscal->AdvancedSearch->ToJSON(), ","); // Field idLocalidadFiscal
		$sFilterList = ew_Concat($sFilterList, $this->calleFiscal->AdvancedSearch->ToJSON(), ","); // Field calleFiscal
		$sFilterList = ew_Concat($sFilterList, $this->direccionFiscal->AdvancedSearch->ToJSON(), ","); // Field direccionFiscal
		$sFilterList = ew_Concat($sFilterList, $this->tipoDoc->AdvancedSearch->ToJSON(), ","); // Field tipoDoc
		$sFilterList = ew_Concat($sFilterList, $this->documento->AdvancedSearch->ToJSON(), ","); // Field documento
		$sFilterList = ew_Concat($sFilterList, $this->condicionIva->AdvancedSearch->ToJSON(), ","); // Field condicionIva
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJSON(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->idTransporte->AdvancedSearch->ToJSON(), ","); // Field idTransporte
		$sFilterList = ew_Concat($sFilterList, $this->idVendedor->AdvancedSearch->ToJSON(), ","); // Field idVendedor
		$sFilterList = ew_Concat($sFilterList, $this->idCobrador->AdvancedSearch->ToJSON(), ","); // Field idCobrador
		$sFilterList = ew_Concat($sFilterList, $this->comision->AdvancedSearch->ToJSON(), ","); // Field comision
		$sFilterList = ew_Concat($sFilterList, $this->idListaPrecios->AdvancedSearch->ToJSON(), ","); // Field idListaPrecios
		$sFilterList = ew_Concat($sFilterList, $this->dtoCliente->AdvancedSearch->ToJSON(), ","); // Field dtoCliente
		$sFilterList = ew_Concat($sFilterList, $this->dto1->AdvancedSearch->ToJSON(), ","); // Field dto1
		$sFilterList = ew_Concat($sFilterList, $this->dto2->AdvancedSearch->ToJSON(), ","); // Field dto2
		$sFilterList = ew_Concat($sFilterList, $this->dto3->AdvancedSearch->ToJSON(), ","); // Field dto3
		$sFilterList = ew_Concat($sFilterList, $this->limiteDescubierto->AdvancedSearch->ToJSON(), ","); // Field limiteDescubierto
		$sFilterList = ew_Concat($sFilterList, $this->codigoPostal->AdvancedSearch->ToJSON(), ","); // Field codigoPostal
		$sFilterList = ew_Concat($sFilterList, $this->codigoPostalFiscal->AdvancedSearch->ToJSON(), ","); // Field codigoPostalFiscal
		$sFilterList = ew_Concat($sFilterList, $this->condicionVenta->AdvancedSearch->ToJSON(), ","); // Field condicionVenta
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fterceroslistsrch", $filters);
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

		// Field idTipoTercero
		$this->idTipoTercero->AdvancedSearch->SearchValue = @$filter["x_idTipoTercero"];
		$this->idTipoTercero->AdvancedSearch->SearchOperator = @$filter["z_idTipoTercero"];
		$this->idTipoTercero->AdvancedSearch->SearchCondition = @$filter["v_idTipoTercero"];
		$this->idTipoTercero->AdvancedSearch->SearchValue2 = @$filter["y_idTipoTercero"];
		$this->idTipoTercero->AdvancedSearch->SearchOperator2 = @$filter["w_idTipoTercero"];
		$this->idTipoTercero->AdvancedSearch->Save();

		// Field denominacion
		$this->denominacion->AdvancedSearch->SearchValue = @$filter["x_denominacion"];
		$this->denominacion->AdvancedSearch->SearchOperator = @$filter["z_denominacion"];
		$this->denominacion->AdvancedSearch->SearchCondition = @$filter["v_denominacion"];
		$this->denominacion->AdvancedSearch->SearchValue2 = @$filter["y_denominacion"];
		$this->denominacion->AdvancedSearch->SearchOperator2 = @$filter["w_denominacion"];
		$this->denominacion->AdvancedSearch->Save();

		// Field razonSocial
		$this->razonSocial->AdvancedSearch->SearchValue = @$filter["x_razonSocial"];
		$this->razonSocial->AdvancedSearch->SearchOperator = @$filter["z_razonSocial"];
		$this->razonSocial->AdvancedSearch->SearchCondition = @$filter["v_razonSocial"];
		$this->razonSocial->AdvancedSearch->SearchValue2 = @$filter["y_razonSocial"];
		$this->razonSocial->AdvancedSearch->SearchOperator2 = @$filter["w_razonSocial"];
		$this->razonSocial->AdvancedSearch->Save();

		// Field denominacionCorta
		$this->denominacionCorta->AdvancedSearch->SearchValue = @$filter["x_denominacionCorta"];
		$this->denominacionCorta->AdvancedSearch->SearchOperator = @$filter["z_denominacionCorta"];
		$this->denominacionCorta->AdvancedSearch->SearchCondition = @$filter["v_denominacionCorta"];
		$this->denominacionCorta->AdvancedSearch->SearchValue2 = @$filter["y_denominacionCorta"];
		$this->denominacionCorta->AdvancedSearch->SearchOperator2 = @$filter["w_denominacionCorta"];
		$this->denominacionCorta->AdvancedSearch->Save();

		// Field idPais
		$this->idPais->AdvancedSearch->SearchValue = @$filter["x_idPais"];
		$this->idPais->AdvancedSearch->SearchOperator = @$filter["z_idPais"];
		$this->idPais->AdvancedSearch->SearchCondition = @$filter["v_idPais"];
		$this->idPais->AdvancedSearch->SearchValue2 = @$filter["y_idPais"];
		$this->idPais->AdvancedSearch->SearchOperator2 = @$filter["w_idPais"];
		$this->idPais->AdvancedSearch->Save();

		// Field idProvincia
		$this->idProvincia->AdvancedSearch->SearchValue = @$filter["x_idProvincia"];
		$this->idProvincia->AdvancedSearch->SearchOperator = @$filter["z_idProvincia"];
		$this->idProvincia->AdvancedSearch->SearchCondition = @$filter["v_idProvincia"];
		$this->idProvincia->AdvancedSearch->SearchValue2 = @$filter["y_idProvincia"];
		$this->idProvincia->AdvancedSearch->SearchOperator2 = @$filter["w_idProvincia"];
		$this->idProvincia->AdvancedSearch->Save();

		// Field idPartido
		$this->idPartido->AdvancedSearch->SearchValue = @$filter["x_idPartido"];
		$this->idPartido->AdvancedSearch->SearchOperator = @$filter["z_idPartido"];
		$this->idPartido->AdvancedSearch->SearchCondition = @$filter["v_idPartido"];
		$this->idPartido->AdvancedSearch->SearchValue2 = @$filter["y_idPartido"];
		$this->idPartido->AdvancedSearch->SearchOperator2 = @$filter["w_idPartido"];
		$this->idPartido->AdvancedSearch->Save();

		// Field idLocalidad
		$this->idLocalidad->AdvancedSearch->SearchValue = @$filter["x_idLocalidad"];
		$this->idLocalidad->AdvancedSearch->SearchOperator = @$filter["z_idLocalidad"];
		$this->idLocalidad->AdvancedSearch->SearchCondition = @$filter["v_idLocalidad"];
		$this->idLocalidad->AdvancedSearch->SearchValue2 = @$filter["y_idLocalidad"];
		$this->idLocalidad->AdvancedSearch->SearchOperator2 = @$filter["w_idLocalidad"];
		$this->idLocalidad->AdvancedSearch->Save();

		// Field calle
		$this->calle->AdvancedSearch->SearchValue = @$filter["x_calle"];
		$this->calle->AdvancedSearch->SearchOperator = @$filter["z_calle"];
		$this->calle->AdvancedSearch->SearchCondition = @$filter["v_calle"];
		$this->calle->AdvancedSearch->SearchValue2 = @$filter["y_calle"];
		$this->calle->AdvancedSearch->SearchOperator2 = @$filter["w_calle"];
		$this->calle->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field domicilioFiscal
		$this->domicilioFiscal->AdvancedSearch->SearchValue = @$filter["x_domicilioFiscal"];
		$this->domicilioFiscal->AdvancedSearch->SearchOperator = @$filter["z_domicilioFiscal"];
		$this->domicilioFiscal->AdvancedSearch->SearchCondition = @$filter["v_domicilioFiscal"];
		$this->domicilioFiscal->AdvancedSearch->SearchValue2 = @$filter["y_domicilioFiscal"];
		$this->domicilioFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_domicilioFiscal"];
		$this->domicilioFiscal->AdvancedSearch->Save();

		// Field idPaisFiscal
		$this->idPaisFiscal->AdvancedSearch->SearchValue = @$filter["x_idPaisFiscal"];
		$this->idPaisFiscal->AdvancedSearch->SearchOperator = @$filter["z_idPaisFiscal"];
		$this->idPaisFiscal->AdvancedSearch->SearchCondition = @$filter["v_idPaisFiscal"];
		$this->idPaisFiscal->AdvancedSearch->SearchValue2 = @$filter["y_idPaisFiscal"];
		$this->idPaisFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_idPaisFiscal"];
		$this->idPaisFiscal->AdvancedSearch->Save();

		// Field idProvinciaFiscal
		$this->idProvinciaFiscal->AdvancedSearch->SearchValue = @$filter["x_idProvinciaFiscal"];
		$this->idProvinciaFiscal->AdvancedSearch->SearchOperator = @$filter["z_idProvinciaFiscal"];
		$this->idProvinciaFiscal->AdvancedSearch->SearchCondition = @$filter["v_idProvinciaFiscal"];
		$this->idProvinciaFiscal->AdvancedSearch->SearchValue2 = @$filter["y_idProvinciaFiscal"];
		$this->idProvinciaFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_idProvinciaFiscal"];
		$this->idProvinciaFiscal->AdvancedSearch->Save();

		// Field idPartidoFiscal
		$this->idPartidoFiscal->AdvancedSearch->SearchValue = @$filter["x_idPartidoFiscal"];
		$this->idPartidoFiscal->AdvancedSearch->SearchOperator = @$filter["z_idPartidoFiscal"];
		$this->idPartidoFiscal->AdvancedSearch->SearchCondition = @$filter["v_idPartidoFiscal"];
		$this->idPartidoFiscal->AdvancedSearch->SearchValue2 = @$filter["y_idPartidoFiscal"];
		$this->idPartidoFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_idPartidoFiscal"];
		$this->idPartidoFiscal->AdvancedSearch->Save();

		// Field idLocalidadFiscal
		$this->idLocalidadFiscal->AdvancedSearch->SearchValue = @$filter["x_idLocalidadFiscal"];
		$this->idLocalidadFiscal->AdvancedSearch->SearchOperator = @$filter["z_idLocalidadFiscal"];
		$this->idLocalidadFiscal->AdvancedSearch->SearchCondition = @$filter["v_idLocalidadFiscal"];
		$this->idLocalidadFiscal->AdvancedSearch->SearchValue2 = @$filter["y_idLocalidadFiscal"];
		$this->idLocalidadFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_idLocalidadFiscal"];
		$this->idLocalidadFiscal->AdvancedSearch->Save();

		// Field calleFiscal
		$this->calleFiscal->AdvancedSearch->SearchValue = @$filter["x_calleFiscal"];
		$this->calleFiscal->AdvancedSearch->SearchOperator = @$filter["z_calleFiscal"];
		$this->calleFiscal->AdvancedSearch->SearchCondition = @$filter["v_calleFiscal"];
		$this->calleFiscal->AdvancedSearch->SearchValue2 = @$filter["y_calleFiscal"];
		$this->calleFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_calleFiscal"];
		$this->calleFiscal->AdvancedSearch->Save();

		// Field direccionFiscal
		$this->direccionFiscal->AdvancedSearch->SearchValue = @$filter["x_direccionFiscal"];
		$this->direccionFiscal->AdvancedSearch->SearchOperator = @$filter["z_direccionFiscal"];
		$this->direccionFiscal->AdvancedSearch->SearchCondition = @$filter["v_direccionFiscal"];
		$this->direccionFiscal->AdvancedSearch->SearchValue2 = @$filter["y_direccionFiscal"];
		$this->direccionFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_direccionFiscal"];
		$this->direccionFiscal->AdvancedSearch->Save();

		// Field tipoDoc
		$this->tipoDoc->AdvancedSearch->SearchValue = @$filter["x_tipoDoc"];
		$this->tipoDoc->AdvancedSearch->SearchOperator = @$filter["z_tipoDoc"];
		$this->tipoDoc->AdvancedSearch->SearchCondition = @$filter["v_tipoDoc"];
		$this->tipoDoc->AdvancedSearch->SearchValue2 = @$filter["y_tipoDoc"];
		$this->tipoDoc->AdvancedSearch->SearchOperator2 = @$filter["w_tipoDoc"];
		$this->tipoDoc->AdvancedSearch->Save();

		// Field documento
		$this->documento->AdvancedSearch->SearchValue = @$filter["x_documento"];
		$this->documento->AdvancedSearch->SearchOperator = @$filter["z_documento"];
		$this->documento->AdvancedSearch->SearchCondition = @$filter["v_documento"];
		$this->documento->AdvancedSearch->SearchValue2 = @$filter["y_documento"];
		$this->documento->AdvancedSearch->SearchOperator2 = @$filter["w_documento"];
		$this->documento->AdvancedSearch->Save();

		// Field condicionIva
		$this->condicionIva->AdvancedSearch->SearchValue = @$filter["x_condicionIva"];
		$this->condicionIva->AdvancedSearch->SearchOperator = @$filter["z_condicionIva"];
		$this->condicionIva->AdvancedSearch->SearchCondition = @$filter["v_condicionIva"];
		$this->condicionIva->AdvancedSearch->SearchValue2 = @$filter["y_condicionIva"];
		$this->condicionIva->AdvancedSearch->SearchOperator2 = @$filter["w_condicionIva"];
		$this->condicionIva->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field idTransporte
		$this->idTransporte->AdvancedSearch->SearchValue = @$filter["x_idTransporte"];
		$this->idTransporte->AdvancedSearch->SearchOperator = @$filter["z_idTransporte"];
		$this->idTransporte->AdvancedSearch->SearchCondition = @$filter["v_idTransporte"];
		$this->idTransporte->AdvancedSearch->SearchValue2 = @$filter["y_idTransporte"];
		$this->idTransporte->AdvancedSearch->SearchOperator2 = @$filter["w_idTransporte"];
		$this->idTransporte->AdvancedSearch->Save();

		// Field idVendedor
		$this->idVendedor->AdvancedSearch->SearchValue = @$filter["x_idVendedor"];
		$this->idVendedor->AdvancedSearch->SearchOperator = @$filter["z_idVendedor"];
		$this->idVendedor->AdvancedSearch->SearchCondition = @$filter["v_idVendedor"];
		$this->idVendedor->AdvancedSearch->SearchValue2 = @$filter["y_idVendedor"];
		$this->idVendedor->AdvancedSearch->SearchOperator2 = @$filter["w_idVendedor"];
		$this->idVendedor->AdvancedSearch->Save();

		// Field idCobrador
		$this->idCobrador->AdvancedSearch->SearchValue = @$filter["x_idCobrador"];
		$this->idCobrador->AdvancedSearch->SearchOperator = @$filter["z_idCobrador"];
		$this->idCobrador->AdvancedSearch->SearchCondition = @$filter["v_idCobrador"];
		$this->idCobrador->AdvancedSearch->SearchValue2 = @$filter["y_idCobrador"];
		$this->idCobrador->AdvancedSearch->SearchOperator2 = @$filter["w_idCobrador"];
		$this->idCobrador->AdvancedSearch->Save();

		// Field comision
		$this->comision->AdvancedSearch->SearchValue = @$filter["x_comision"];
		$this->comision->AdvancedSearch->SearchOperator = @$filter["z_comision"];
		$this->comision->AdvancedSearch->SearchCondition = @$filter["v_comision"];
		$this->comision->AdvancedSearch->SearchValue2 = @$filter["y_comision"];
		$this->comision->AdvancedSearch->SearchOperator2 = @$filter["w_comision"];
		$this->comision->AdvancedSearch->Save();

		// Field idListaPrecios
		$this->idListaPrecios->AdvancedSearch->SearchValue = @$filter["x_idListaPrecios"];
		$this->idListaPrecios->AdvancedSearch->SearchOperator = @$filter["z_idListaPrecios"];
		$this->idListaPrecios->AdvancedSearch->SearchCondition = @$filter["v_idListaPrecios"];
		$this->idListaPrecios->AdvancedSearch->SearchValue2 = @$filter["y_idListaPrecios"];
		$this->idListaPrecios->AdvancedSearch->SearchOperator2 = @$filter["w_idListaPrecios"];
		$this->idListaPrecios->AdvancedSearch->Save();

		// Field dtoCliente
		$this->dtoCliente->AdvancedSearch->SearchValue = @$filter["x_dtoCliente"];
		$this->dtoCliente->AdvancedSearch->SearchOperator = @$filter["z_dtoCliente"];
		$this->dtoCliente->AdvancedSearch->SearchCondition = @$filter["v_dtoCliente"];
		$this->dtoCliente->AdvancedSearch->SearchValue2 = @$filter["y_dtoCliente"];
		$this->dtoCliente->AdvancedSearch->SearchOperator2 = @$filter["w_dtoCliente"];
		$this->dtoCliente->AdvancedSearch->Save();

		// Field dto1
		$this->dto1->AdvancedSearch->SearchValue = @$filter["x_dto1"];
		$this->dto1->AdvancedSearch->SearchOperator = @$filter["z_dto1"];
		$this->dto1->AdvancedSearch->SearchCondition = @$filter["v_dto1"];
		$this->dto1->AdvancedSearch->SearchValue2 = @$filter["y_dto1"];
		$this->dto1->AdvancedSearch->SearchOperator2 = @$filter["w_dto1"];
		$this->dto1->AdvancedSearch->Save();

		// Field dto2
		$this->dto2->AdvancedSearch->SearchValue = @$filter["x_dto2"];
		$this->dto2->AdvancedSearch->SearchOperator = @$filter["z_dto2"];
		$this->dto2->AdvancedSearch->SearchCondition = @$filter["v_dto2"];
		$this->dto2->AdvancedSearch->SearchValue2 = @$filter["y_dto2"];
		$this->dto2->AdvancedSearch->SearchOperator2 = @$filter["w_dto2"];
		$this->dto2->AdvancedSearch->Save();

		// Field dto3
		$this->dto3->AdvancedSearch->SearchValue = @$filter["x_dto3"];
		$this->dto3->AdvancedSearch->SearchOperator = @$filter["z_dto3"];
		$this->dto3->AdvancedSearch->SearchCondition = @$filter["v_dto3"];
		$this->dto3->AdvancedSearch->SearchValue2 = @$filter["y_dto3"];
		$this->dto3->AdvancedSearch->SearchOperator2 = @$filter["w_dto3"];
		$this->dto3->AdvancedSearch->Save();

		// Field limiteDescubierto
		$this->limiteDescubierto->AdvancedSearch->SearchValue = @$filter["x_limiteDescubierto"];
		$this->limiteDescubierto->AdvancedSearch->SearchOperator = @$filter["z_limiteDescubierto"];
		$this->limiteDescubierto->AdvancedSearch->SearchCondition = @$filter["v_limiteDescubierto"];
		$this->limiteDescubierto->AdvancedSearch->SearchValue2 = @$filter["y_limiteDescubierto"];
		$this->limiteDescubierto->AdvancedSearch->SearchOperator2 = @$filter["w_limiteDescubierto"];
		$this->limiteDescubierto->AdvancedSearch->Save();

		// Field codigoPostal
		$this->codigoPostal->AdvancedSearch->SearchValue = @$filter["x_codigoPostal"];
		$this->codigoPostal->AdvancedSearch->SearchOperator = @$filter["z_codigoPostal"];
		$this->codigoPostal->AdvancedSearch->SearchCondition = @$filter["v_codigoPostal"];
		$this->codigoPostal->AdvancedSearch->SearchValue2 = @$filter["y_codigoPostal"];
		$this->codigoPostal->AdvancedSearch->SearchOperator2 = @$filter["w_codigoPostal"];
		$this->codigoPostal->AdvancedSearch->Save();

		// Field codigoPostalFiscal
		$this->codigoPostalFiscal->AdvancedSearch->SearchValue = @$filter["x_codigoPostalFiscal"];
		$this->codigoPostalFiscal->AdvancedSearch->SearchOperator = @$filter["z_codigoPostalFiscal"];
		$this->codigoPostalFiscal->AdvancedSearch->SearchCondition = @$filter["v_codigoPostalFiscal"];
		$this->codigoPostalFiscal->AdvancedSearch->SearchValue2 = @$filter["y_codigoPostalFiscal"];
		$this->codigoPostalFiscal->AdvancedSearch->SearchOperator2 = @$filter["w_codigoPostalFiscal"];
		$this->codigoPostalFiscal->AdvancedSearch->Save();

		// Field condicionVenta
		$this->condicionVenta->AdvancedSearch->SearchValue = @$filter["x_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchOperator = @$filter["z_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchCondition = @$filter["v_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchValue2 = @$filter["y_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->SearchOperator2 = @$filter["w_condicionVenta"];
		$this->condicionVenta->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->idTipoTercero, $Default, FALSE); // idTipoTercero
		$this->BuildSearchSql($sWhere, $this->denominacion, $Default, FALSE); // denominacion
		$this->BuildSearchSql($sWhere, $this->razonSocial, $Default, FALSE); // razonSocial
		$this->BuildSearchSql($sWhere, $this->denominacionCorta, $Default, FALSE); // denominacionCorta
		$this->BuildSearchSql($sWhere, $this->idPais, $Default, FALSE); // idPais
		$this->BuildSearchSql($sWhere, $this->idProvincia, $Default, FALSE); // idProvincia
		$this->BuildSearchSql($sWhere, $this->idPartido, $Default, FALSE); // idPartido
		$this->BuildSearchSql($sWhere, $this->idLocalidad, $Default, FALSE); // idLocalidad
		$this->BuildSearchSql($sWhere, $this->calle, $Default, FALSE); // calle
		$this->BuildSearchSql($sWhere, $this->direccion, $Default, FALSE); // direccion
		$this->BuildSearchSql($sWhere, $this->domicilioFiscal, $Default, FALSE); // domicilioFiscal
		$this->BuildSearchSql($sWhere, $this->idPaisFiscal, $Default, FALSE); // idPaisFiscal
		$this->BuildSearchSql($sWhere, $this->idProvinciaFiscal, $Default, FALSE); // idProvinciaFiscal
		$this->BuildSearchSql($sWhere, $this->idPartidoFiscal, $Default, FALSE); // idPartidoFiscal
		$this->BuildSearchSql($sWhere, $this->idLocalidadFiscal, $Default, FALSE); // idLocalidadFiscal
		$this->BuildSearchSql($sWhere, $this->calleFiscal, $Default, FALSE); // calleFiscal
		$this->BuildSearchSql($sWhere, $this->direccionFiscal, $Default, FALSE); // direccionFiscal
		$this->BuildSearchSql($sWhere, $this->tipoDoc, $Default, FALSE); // tipoDoc
		$this->BuildSearchSql($sWhere, $this->documento, $Default, FALSE); // documento
		$this->BuildSearchSql($sWhere, $this->condicionIva, $Default, FALSE); // condicionIva
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->idTransporte, $Default, FALSE); // idTransporte
		$this->BuildSearchSql($sWhere, $this->idVendedor, $Default, FALSE); // idVendedor
		$this->BuildSearchSql($sWhere, $this->idCobrador, $Default, FALSE); // idCobrador
		$this->BuildSearchSql($sWhere, $this->comision, $Default, FALSE); // comision
		$this->BuildSearchSql($sWhere, $this->idListaPrecios, $Default, FALSE); // idListaPrecios
		$this->BuildSearchSql($sWhere, $this->dtoCliente, $Default, FALSE); // dtoCliente
		$this->BuildSearchSql($sWhere, $this->dto1, $Default, FALSE); // dto1
		$this->BuildSearchSql($sWhere, $this->dto2, $Default, FALSE); // dto2
		$this->BuildSearchSql($sWhere, $this->dto3, $Default, FALSE); // dto3
		$this->BuildSearchSql($sWhere, $this->limiteDescubierto, $Default, FALSE); // limiteDescubierto
		$this->BuildSearchSql($sWhere, $this->codigoPostal, $Default, FALSE); // codigoPostal
		$this->BuildSearchSql($sWhere, $this->codigoPostalFiscal, $Default, FALSE); // codigoPostalFiscal
		$this->BuildSearchSql($sWhere, $this->condicionVenta, $Default, FALSE); // condicionVenta

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idTipoTercero->AdvancedSearch->Save(); // idTipoTercero
			$this->denominacion->AdvancedSearch->Save(); // denominacion
			$this->razonSocial->AdvancedSearch->Save(); // razonSocial
			$this->denominacionCorta->AdvancedSearch->Save(); // denominacionCorta
			$this->idPais->AdvancedSearch->Save(); // idPais
			$this->idProvincia->AdvancedSearch->Save(); // idProvincia
			$this->idPartido->AdvancedSearch->Save(); // idPartido
			$this->idLocalidad->AdvancedSearch->Save(); // idLocalidad
			$this->calle->AdvancedSearch->Save(); // calle
			$this->direccion->AdvancedSearch->Save(); // direccion
			$this->domicilioFiscal->AdvancedSearch->Save(); // domicilioFiscal
			$this->idPaisFiscal->AdvancedSearch->Save(); // idPaisFiscal
			$this->idProvinciaFiscal->AdvancedSearch->Save(); // idProvinciaFiscal
			$this->idPartidoFiscal->AdvancedSearch->Save(); // idPartidoFiscal
			$this->idLocalidadFiscal->AdvancedSearch->Save(); // idLocalidadFiscal
			$this->calleFiscal->AdvancedSearch->Save(); // calleFiscal
			$this->direccionFiscal->AdvancedSearch->Save(); // direccionFiscal
			$this->tipoDoc->AdvancedSearch->Save(); // tipoDoc
			$this->documento->AdvancedSearch->Save(); // documento
			$this->condicionIva->AdvancedSearch->Save(); // condicionIva
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->idTransporte->AdvancedSearch->Save(); // idTransporte
			$this->idVendedor->AdvancedSearch->Save(); // idVendedor
			$this->idCobrador->AdvancedSearch->Save(); // idCobrador
			$this->comision->AdvancedSearch->Save(); // comision
			$this->idListaPrecios->AdvancedSearch->Save(); // idListaPrecios
			$this->dtoCliente->AdvancedSearch->Save(); // dtoCliente
			$this->dto1->AdvancedSearch->Save(); // dto1
			$this->dto2->AdvancedSearch->Save(); // dto2
			$this->dto3->AdvancedSearch->Save(); // dto3
			$this->limiteDescubierto->AdvancedSearch->Save(); // limiteDescubierto
			$this->codigoPostal->AdvancedSearch->Save(); // codigoPostal
			$this->codigoPostalFiscal->AdvancedSearch->Save(); // codigoPostalFiscal
			$this->condicionVenta->AdvancedSearch->Save(); // condicionVenta
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
		$this->BuildBasicSearchSQL($sWhere, $this->idTipoTercero, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->denominacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->razonSocial, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->denominacionCorta, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idPais, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idProvincia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idPartido, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->idLocalidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->calle, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->calleFiscal, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccionFiscal, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipoDoc, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->documento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->condicionIva, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->observaciones, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigoPostal, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->codigoPostalFiscal, $arKeywords, $type);
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
		if ($this->idTipoTercero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denominacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->razonSocial->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denominacionCorta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idPais->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idProvincia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idPartido->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idLocalidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->calle->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->domicilioFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idPaisFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idProvinciaFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idPartidoFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idLocalidadFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->calleFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccionFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipoDoc->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->documento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->condicionIva->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idTransporte->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idVendedor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idCobrador->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comision->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idListaPrecios->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dtoCliente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dto1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dto2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dto3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->limiteDescubierto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigoPostal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->codigoPostalFiscal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->condicionVenta->AdvancedSearch->IssetSession())
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
		$this->idTipoTercero->AdvancedSearch->UnsetSession();
		$this->denominacion->AdvancedSearch->UnsetSession();
		$this->razonSocial->AdvancedSearch->UnsetSession();
		$this->denominacionCorta->AdvancedSearch->UnsetSession();
		$this->idPais->AdvancedSearch->UnsetSession();
		$this->idProvincia->AdvancedSearch->UnsetSession();
		$this->idPartido->AdvancedSearch->UnsetSession();
		$this->idLocalidad->AdvancedSearch->UnsetSession();
		$this->calle->AdvancedSearch->UnsetSession();
		$this->direccion->AdvancedSearch->UnsetSession();
		$this->domicilioFiscal->AdvancedSearch->UnsetSession();
		$this->idPaisFiscal->AdvancedSearch->UnsetSession();
		$this->idProvinciaFiscal->AdvancedSearch->UnsetSession();
		$this->idPartidoFiscal->AdvancedSearch->UnsetSession();
		$this->idLocalidadFiscal->AdvancedSearch->UnsetSession();
		$this->calleFiscal->AdvancedSearch->UnsetSession();
		$this->direccionFiscal->AdvancedSearch->UnsetSession();
		$this->tipoDoc->AdvancedSearch->UnsetSession();
		$this->documento->AdvancedSearch->UnsetSession();
		$this->condicionIva->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->idTransporte->AdvancedSearch->UnsetSession();
		$this->idVendedor->AdvancedSearch->UnsetSession();
		$this->idCobrador->AdvancedSearch->UnsetSession();
		$this->comision->AdvancedSearch->UnsetSession();
		$this->idListaPrecios->AdvancedSearch->UnsetSession();
		$this->dtoCliente->AdvancedSearch->UnsetSession();
		$this->dto1->AdvancedSearch->UnsetSession();
		$this->dto2->AdvancedSearch->UnsetSession();
		$this->dto3->AdvancedSearch->UnsetSession();
		$this->limiteDescubierto->AdvancedSearch->UnsetSession();
		$this->codigoPostal->AdvancedSearch->UnsetSession();
		$this->codigoPostalFiscal->AdvancedSearch->UnsetSession();
		$this->condicionVenta->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->idTipoTercero->AdvancedSearch->Load();
		$this->denominacion->AdvancedSearch->Load();
		$this->razonSocial->AdvancedSearch->Load();
		$this->denominacionCorta->AdvancedSearch->Load();
		$this->idPais->AdvancedSearch->Load();
		$this->idProvincia->AdvancedSearch->Load();
		$this->idPartido->AdvancedSearch->Load();
		$this->idLocalidad->AdvancedSearch->Load();
		$this->calle->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->domicilioFiscal->AdvancedSearch->Load();
		$this->idPaisFiscal->AdvancedSearch->Load();
		$this->idProvinciaFiscal->AdvancedSearch->Load();
		$this->idPartidoFiscal->AdvancedSearch->Load();
		$this->idLocalidadFiscal->AdvancedSearch->Load();
		$this->calleFiscal->AdvancedSearch->Load();
		$this->direccionFiscal->AdvancedSearch->Load();
		$this->tipoDoc->AdvancedSearch->Load();
		$this->documento->AdvancedSearch->Load();
		$this->condicionIva->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->idTransporte->AdvancedSearch->Load();
		$this->idVendedor->AdvancedSearch->Load();
		$this->idCobrador->AdvancedSearch->Load();
		$this->comision->AdvancedSearch->Load();
		$this->idListaPrecios->AdvancedSearch->Load();
		$this->dtoCliente->AdvancedSearch->Load();
		$this->dto1->AdvancedSearch->Load();
		$this->dto2->AdvancedSearch->Load();
		$this->dto3->AdvancedSearch->Load();
		$this->limiteDescubierto->AdvancedSearch->Load();
		$this->codigoPostal->AdvancedSearch->Load();
		$this->codigoPostalFiscal->AdvancedSearch->Load();
		$this->condicionVenta->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idTipoTercero, $bCtrl); // idTipoTercero
			$this->UpdateSort($this->denominacion, $bCtrl); // denominacion
			$this->UpdateSort($this->direccion, $bCtrl); // direccion
			$this->UpdateSort($this->documento, $bCtrl); // documento
			$this->UpdateSort($this->idTransporte, $bCtrl); // idTransporte
			$this->UpdateSort($this->limiteDescubierto, $bCtrl); // limiteDescubierto
			$this->UpdateSort($this->codigoPostal, $bCtrl); // codigoPostal
			$this->UpdateSort($this->codigoPostalFiscal, $bCtrl); // codigoPostalFiscal
			$this->UpdateSort($this->condicionVenta, $bCtrl); // condicionVenta
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
				$this->setSessionOrderByList($sOrderBy);
				$this->idTipoTercero->setSort("");
				$this->denominacion->setSort("");
				$this->direccion->setSort("");
				$this->documento->setSort("");
				$this->idTransporte->setSort("");
				$this->limiteDescubierto->setSort("");
				$this->codigoPostal->setSort("");
				$this->codigoPostalFiscal->setSort("");
				$this->condicionVenta->setSort("");
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

		// "detail_terceros2Dmedios2Dcontactos"
		$item = &$this->ListOptions->Add("detail_terceros2Dmedios2Dcontactos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'terceros-medios-contactos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"])) $GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid;

		// "detail_articulos2Dterceros2Ddescuentos"
		$item = &$this->ListOptions->Add("detail_articulos2Dterceros2Ddescuentos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-terceros-descuentos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid;

		// "detail_articulos2Dproveedores"
		$item = &$this->ListOptions->Add("detail_articulos2Dproveedores");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-proveedores') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;

		// "detail_subcategoria2Dterceros2Ddescuentos"
		$item = &$this->ListOptions->Add("detail_subcategoria2Dterceros2Ddescuentos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'subcategoria-terceros-descuentos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"])) $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] = new csubcategoria2Dterceros2Ddescuentos_grid;

		// "detail_categorias2Dterceros2Ddescuentos"
		$item = &$this->ListOptions->Add("detail_categorias2Dterceros2Ddescuentos");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'categorias-terceros-descuentos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["categorias2Dterceros2Ddescuentos_grid"])) $GLOBALS["categorias2Dterceros2Ddescuentos_grid"] = new ccategorias2Dterceros2Ddescuentos_grid;

		// "detail_sucursales"
		$item = &$this->ListOptions->Add("detail_sucursales");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'sucursales') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["sucursales_grid"])) $GLOBALS["sucursales_grid"] = new csucursales_grid;

		// "detail_descuentosasociados"
		$item = &$this->ListOptions->Add("detail_descuentosasociados");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'descuentosasociados') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["descuentosasociados_grid"])) $GLOBALS["descuentosasociados_grid"] = new cdescuentosasociados_grid;

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
		$pages->Add("terceros2Dmedios2Dcontactos");
		$pages->Add("articulos2Dterceros2Ddescuentos");
		$pages->Add("articulos2Dproveedores");
		$pages->Add("subcategoria2Dterceros2Ddescuentos");
		$pages->Add("categorias2Dterceros2Ddescuentos");
		$pages->Add("sucursales");
		$pages->Add("descuentosasociados");
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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_terceros2Dmedios2Dcontactos"
		$oListOpt = &$this->ListOptions->Items["detail_terceros2Dmedios2Dcontactos"];
		if ($Security->AllowList(CurrentProjectID() . 'terceros-medios-contactos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("terceros2Dmedios2Dcontactos", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("terceros2Dmedios2Dcontactoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'terceros-medios-contactos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=terceros2Dmedios2Dcontactos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "terceros2Dmedios2Dcontactos";
			}
			if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'terceros-medios-contactos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=terceros2Dmedios2Dcontactos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "terceros2Dmedios2Dcontactos";
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
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'articulos-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "articulos2Dterceros2Ddescuentos";
			}
			if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "articulos2Dterceros2Ddescuentos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_articulos2Dproveedores"
		$oListOpt = &$this->ListOptions->Items["detail_articulos2Dproveedores"];
		if ($Security->AllowList(CurrentProjectID() . 'articulos-proveedores')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("articulos2Dproveedores", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dproveedoreslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["articulos2Dproveedores_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'articulos-proveedores')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "articulos2Dproveedores";
			}
			if ($GLOBALS["articulos2Dproveedores_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-proveedores')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "articulos2Dproveedores";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_subcategoria2Dterceros2Ddescuentos"
		$oListOpt = &$this->ListOptions->Items["detail_subcategoria2Dterceros2Ddescuentos"];
		if ($Security->AllowList(CurrentProjectID() . 'subcategoria-terceros-descuentos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("subcategoria2Dterceros2Ddescuentos", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("subcategoria2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'subcategoria-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=subcategoria2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "subcategoria2Dterceros2Ddescuentos";
			}
			if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'subcategoria-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=subcategoria2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "subcategoria2Dterceros2Ddescuentos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_categorias2Dterceros2Ddescuentos"
		$oListOpt = &$this->ListOptions->Items["detail_categorias2Dterceros2Ddescuentos"];
		if ($Security->AllowList(CurrentProjectID() . 'categorias-terceros-descuentos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("categorias2Dterceros2Ddescuentos", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("categorias2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'categorias-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=categorias2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "categorias2Dterceros2Ddescuentos";
			}
			if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'categorias-terceros-descuentos')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=categorias2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "categorias2Dterceros2Ddescuentos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_sucursales"
		$oListOpt = &$this->ListOptions->Items["detail_sucursales"];
		if ($Security->AllowList(CurrentProjectID() . 'sucursales')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("sucursales", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("sucursaleslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["sucursales_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'sucursales')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=sucursales")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "sucursales";
			}
			if ($GLOBALS["sucursales_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'sucursales')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=sucursales")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "sucursales";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_descuentosasociados"
		$oListOpt = &$this->ListOptions->Items["detail_descuentosasociados"];
		if ($Security->AllowList(CurrentProjectID() . 'descuentosasociados')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("descuentosasociados", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("descuentosasociadoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["descuentosasociados_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'descuentosasociados')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=descuentosasociados")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "descuentosasociados";
			}
			if ($GLOBALS["descuentosasociados_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'descuentosasociados')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=descuentosasociados")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "descuentosasociados";
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
		$item = &$option->Add("detailadd_terceros2Dmedios2Dcontactos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=terceros2Dmedios2Dcontactos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["terceros2Dmedios2Dcontactos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["terceros2Dmedios2Dcontactos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'terceros-medios-contactos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "terceros2Dmedios2Dcontactos";
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
		$item = &$option->Add("detailadd_articulos2Dproveedores");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["articulos2Dproveedores"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["articulos2Dproveedores"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'articulos-proveedores') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dproveedores";
		}
		$item = &$option->Add("detailadd_subcategoria2Dterceros2Ddescuentos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=subcategoria2Dterceros2Ddescuentos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["subcategoria2Dterceros2Ddescuentos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["subcategoria2Dterceros2Ddescuentos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'subcategoria-terceros-descuentos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "subcategoria2Dterceros2Ddescuentos";
		}
		$item = &$option->Add("detailadd_categorias2Dterceros2Ddescuentos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=categorias2Dterceros2Ddescuentos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["categorias2Dterceros2Ddescuentos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["categorias2Dterceros2Ddescuentos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'categorias-terceros-descuentos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "categorias2Dterceros2Ddescuentos";
		}
		$item = &$option->Add("detailadd_sucursales");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=sucursales");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["sucursales"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["sucursales"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'sucursales') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "sucursales";
		}
		$item = &$option->Add("detailadd_descuentosasociados");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=descuentosasociados");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["descuentosasociados"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["descuentosasociados"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'descuentosasociados') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "descuentosasociados";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fterceroslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fterceroslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fterceroslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fterceroslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fterceroslistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

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
		// idTipoTercero

		$this->idTipoTercero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTipoTercero"]);
		if ($this->idTipoTercero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTipoTercero->AdvancedSearch->SearchOperator = @$_GET["z_idTipoTercero"];

		// denominacion
		$this->denominacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denominacion"]);
		if ($this->denominacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denominacion->AdvancedSearch->SearchOperator = @$_GET["z_denominacion"];

		// razonSocial
		$this->razonSocial->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_razonSocial"]);
		if ($this->razonSocial->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->razonSocial->AdvancedSearch->SearchOperator = @$_GET["z_razonSocial"];

		// denominacionCorta
		$this->denominacionCorta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denominacionCorta"]);
		if ($this->denominacionCorta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denominacionCorta->AdvancedSearch->SearchOperator = @$_GET["z_denominacionCorta"];

		// idPais
		$this->idPais->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idPais"]);
		if ($this->idPais->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idPais->AdvancedSearch->SearchOperator = @$_GET["z_idPais"];

		// idProvincia
		$this->idProvincia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idProvincia"]);
		if ($this->idProvincia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idProvincia->AdvancedSearch->SearchOperator = @$_GET["z_idProvincia"];

		// idPartido
		$this->idPartido->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idPartido"]);
		if ($this->idPartido->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idPartido->AdvancedSearch->SearchOperator = @$_GET["z_idPartido"];

		// idLocalidad
		$this->idLocalidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idLocalidad"]);
		if ($this->idLocalidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idLocalidad->AdvancedSearch->SearchOperator = @$_GET["z_idLocalidad"];

		// calle
		$this->calle->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_calle"]);
		if ($this->calle->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->calle->AdvancedSearch->SearchOperator = @$_GET["z_calle"];

		// direccion
		$this->direccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_direccion"]);
		if ($this->direccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->direccion->AdvancedSearch->SearchOperator = @$_GET["z_direccion"];

		// domicilioFiscal
		$this->domicilioFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_domicilioFiscal"]);
		if ($this->domicilioFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->domicilioFiscal->AdvancedSearch->SearchOperator = @$_GET["z_domicilioFiscal"];

		// idPaisFiscal
		$this->idPaisFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idPaisFiscal"]);
		if ($this->idPaisFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idPaisFiscal->AdvancedSearch->SearchOperator = @$_GET["z_idPaisFiscal"];

		// idProvinciaFiscal
		$this->idProvinciaFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idProvinciaFiscal"]);
		if ($this->idProvinciaFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idProvinciaFiscal->AdvancedSearch->SearchOperator = @$_GET["z_idProvinciaFiscal"];

		// idPartidoFiscal
		$this->idPartidoFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idPartidoFiscal"]);
		if ($this->idPartidoFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idPartidoFiscal->AdvancedSearch->SearchOperator = @$_GET["z_idPartidoFiscal"];

		// idLocalidadFiscal
		$this->idLocalidadFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idLocalidadFiscal"]);
		if ($this->idLocalidadFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idLocalidadFiscal->AdvancedSearch->SearchOperator = @$_GET["z_idLocalidadFiscal"];

		// calleFiscal
		$this->calleFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_calleFiscal"]);
		if ($this->calleFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->calleFiscal->AdvancedSearch->SearchOperator = @$_GET["z_calleFiscal"];

		// direccionFiscal
		$this->direccionFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_direccionFiscal"]);
		if ($this->direccionFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->direccionFiscal->AdvancedSearch->SearchOperator = @$_GET["z_direccionFiscal"];

		// tipoDoc
		$this->tipoDoc->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipoDoc"]);
		if ($this->tipoDoc->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipoDoc->AdvancedSearch->SearchOperator = @$_GET["z_tipoDoc"];

		// documento
		$this->documento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_documento"]);
		if ($this->documento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->documento->AdvancedSearch->SearchOperator = @$_GET["z_documento"];

		// condicionIva
		$this->condicionIva->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_condicionIva"]);
		if ($this->condicionIva->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->condicionIva->AdvancedSearch->SearchOperator = @$_GET["z_condicionIva"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_observaciones"]);
		if ($this->observaciones->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// idTransporte
		$this->idTransporte->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idTransporte"]);
		if ($this->idTransporte->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idTransporte->AdvancedSearch->SearchOperator = @$_GET["z_idTransporte"];

		// idVendedor
		$this->idVendedor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idVendedor"]);
		if ($this->idVendedor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idVendedor->AdvancedSearch->SearchOperator = @$_GET["z_idVendedor"];

		// idCobrador
		$this->idCobrador->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idCobrador"]);
		if ($this->idCobrador->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idCobrador->AdvancedSearch->SearchOperator = @$_GET["z_idCobrador"];

		// comision
		$this->comision->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comision"]);
		if ($this->comision->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comision->AdvancedSearch->SearchOperator = @$_GET["z_comision"];

		// idListaPrecios
		$this->idListaPrecios->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idListaPrecios"]);
		if ($this->idListaPrecios->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idListaPrecios->AdvancedSearch->SearchOperator = @$_GET["z_idListaPrecios"];

		// dtoCliente
		$this->dtoCliente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dtoCliente"]);
		if ($this->dtoCliente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dtoCliente->AdvancedSearch->SearchOperator = @$_GET["z_dtoCliente"];

		// dto1
		$this->dto1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dto1"]);
		if ($this->dto1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dto1->AdvancedSearch->SearchOperator = @$_GET["z_dto1"];

		// dto2
		$this->dto2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dto2"]);
		if ($this->dto2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dto2->AdvancedSearch->SearchOperator = @$_GET["z_dto2"];

		// dto3
		$this->dto3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dto3"]);
		if ($this->dto3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dto3->AdvancedSearch->SearchOperator = @$_GET["z_dto3"];

		// limiteDescubierto
		$this->limiteDescubierto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_limiteDescubierto"]);
		if ($this->limiteDescubierto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->limiteDescubierto->AdvancedSearch->SearchOperator = @$_GET["z_limiteDescubierto"];

		// codigoPostal
		$this->codigoPostal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codigoPostal"]);
		if ($this->codigoPostal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codigoPostal->AdvancedSearch->SearchOperator = @$_GET["z_codigoPostal"];

		// codigoPostalFiscal
		$this->codigoPostalFiscal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_codigoPostalFiscal"]);
		if ($this->codigoPostalFiscal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->codigoPostalFiscal->AdvancedSearch->SearchOperator = @$_GET["z_codigoPostalFiscal"];

		// condicionVenta
		$this->condicionVenta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_condicionVenta"]);
		if ($this->condicionVenta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->condicionVenta->AdvancedSearch->SearchOperator = @$_GET["z_condicionVenta"];
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->idTipoTercero->setDbValue($rs->fields('idTipoTercero'));
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->razonSocial->setDbValue($rs->fields('razonSocial'));
		$this->denominacionCorta->setDbValue($rs->fields('denominacionCorta'));
		$this->idPais->setDbValue($rs->fields('idPais'));
		if (array_key_exists('EV__idPais', $rs->fields)) {
			$this->idPais->VirtualValue = $rs->fields('EV__idPais'); // Set up virtual field value
		} else {
			$this->idPais->VirtualValue = ""; // Clear value
		}
		$this->idProvincia->setDbValue($rs->fields('idProvincia'));
		$this->idPartido->setDbValue($rs->fields('idPartido'));
		$this->idLocalidad->setDbValue($rs->fields('idLocalidad'));
		$this->calle->setDbValue($rs->fields('calle'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->domicilioFiscal->setDbValue($rs->fields('domicilioFiscal'));
		$this->idPaisFiscal->setDbValue($rs->fields('idPaisFiscal'));
		$this->idProvinciaFiscal->setDbValue($rs->fields('idProvinciaFiscal'));
		$this->idPartidoFiscal->setDbValue($rs->fields('idPartidoFiscal'));
		$this->idLocalidadFiscal->setDbValue($rs->fields('idLocalidadFiscal'));
		$this->calleFiscal->setDbValue($rs->fields('calleFiscal'));
		$this->direccionFiscal->setDbValue($rs->fields('direccionFiscal'));
		$this->tipoDoc->setDbValue($rs->fields('tipoDoc'));
		$this->documento->setDbValue($rs->fields('documento'));
		$this->condicionIva->setDbValue($rs->fields('condicionIva'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->idTransporte->setDbValue($rs->fields('idTransporte'));
		$this->idVendedor->setDbValue($rs->fields('idVendedor'));
		$this->idCobrador->setDbValue($rs->fields('idCobrador'));
		$this->comision->setDbValue($rs->fields('comision'));
		$this->idListaPrecios->setDbValue($rs->fields('idListaPrecios'));
		$this->dtoCliente->setDbValue($rs->fields('dtoCliente'));
		$this->dto1->setDbValue($rs->fields('dto1'));
		$this->dto2->setDbValue($rs->fields('dto2'));
		$this->dto3->setDbValue($rs->fields('dto3'));
		$this->limiteDescubierto->setDbValue($rs->fields('limiteDescubierto'));
		$this->codigoPostal->setDbValue($rs->fields('codigoPostal'));
		$this->codigoPostalFiscal->setDbValue($rs->fields('codigoPostalFiscal'));
		$this->condicionVenta->setDbValue($rs->fields('condicionVenta'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTipoTercero->DbValue = $row['idTipoTercero'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->razonSocial->DbValue = $row['razonSocial'];
		$this->denominacionCorta->DbValue = $row['denominacionCorta'];
		$this->idPais->DbValue = $row['idPais'];
		$this->idProvincia->DbValue = $row['idProvincia'];
		$this->idPartido->DbValue = $row['idPartido'];
		$this->idLocalidad->DbValue = $row['idLocalidad'];
		$this->calle->DbValue = $row['calle'];
		$this->direccion->DbValue = $row['direccion'];
		$this->domicilioFiscal->DbValue = $row['domicilioFiscal'];
		$this->idPaisFiscal->DbValue = $row['idPaisFiscal'];
		$this->idProvinciaFiscal->DbValue = $row['idProvinciaFiscal'];
		$this->idPartidoFiscal->DbValue = $row['idPartidoFiscal'];
		$this->idLocalidadFiscal->DbValue = $row['idLocalidadFiscal'];
		$this->calleFiscal->DbValue = $row['calleFiscal'];
		$this->direccionFiscal->DbValue = $row['direccionFiscal'];
		$this->tipoDoc->DbValue = $row['tipoDoc'];
		$this->documento->DbValue = $row['documento'];
		$this->condicionIva->DbValue = $row['condicionIva'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->idTransporte->DbValue = $row['idTransporte'];
		$this->idVendedor->DbValue = $row['idVendedor'];
		$this->idCobrador->DbValue = $row['idCobrador'];
		$this->comision->DbValue = $row['comision'];
		$this->idListaPrecios->DbValue = $row['idListaPrecios'];
		$this->dtoCliente->DbValue = $row['dtoCliente'];
		$this->dto1->DbValue = $row['dto1'];
		$this->dto2->DbValue = $row['dto2'];
		$this->dto3->DbValue = $row['dto3'];
		$this->limiteDescubierto->DbValue = $row['limiteDescubierto'];
		$this->codigoPostal->DbValue = $row['codigoPostal'];
		$this->codigoPostalFiscal->DbValue = $row['codigoPostalFiscal'];
		$this->condicionVenta->DbValue = $row['condicionVenta'];
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
		if ($this->limiteDescubierto->FormValue == $this->limiteDescubierto->CurrentValue && is_numeric(ew_StrToFloat($this->limiteDescubierto->CurrentValue)))
			$this->limiteDescubierto->CurrentValue = ew_StrToFloat($this->limiteDescubierto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// idTipoTercero
		// denominacion
		// razonSocial
		// denominacionCorta
		// idPais
		// idProvincia
		// idPartido
		// idLocalidad
		// calle
		// direccion
		// domicilioFiscal
		// idPaisFiscal
		// idProvinciaFiscal
		// idPartidoFiscal
		// idLocalidadFiscal
		// calleFiscal
		// direccionFiscal
		// tipoDoc
		// documento
		// condicionIva
		// observaciones
		// idTransporte
		// idVendedor
		// idCobrador
		// comision
		// idListaPrecios
		// dtoCliente
		// dto1
		// dto2
		// dto3
		// limiteDescubierto
		// codigoPostal
		// codigoPostalFiscal
		// condicionVenta

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idTipoTercero
		if (strval($this->idTipoTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-terceros`";
		$sWhereWrk = "";
		$this->idTipoTercero->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTipoTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTipoTercero->ViewValue = $this->idTipoTercero->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTipoTercero->ViewValue = $this->idTipoTercero->CurrentValue;
			}
		} else {
			$this->idTipoTercero->ViewValue = NULL;
		}
		$this->idTipoTercero->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// razonSocial
		$this->razonSocial->ViewValue = $this->razonSocial->CurrentValue;
		$this->razonSocial->ViewCustomAttributes = "";

		// denominacionCorta
		$this->denominacionCorta->ViewValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->ViewCustomAttributes = "";

		// idPais
		if ($this->idPais->VirtualValue <> "") {
			$this->idPais->ViewValue = $this->idPais->VirtualValue;
		} else {
		if (strval($this->idPais->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPais->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
		$sWhereWrk = "";
		$this->idPais->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPais->ViewValue = $this->idPais->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPais->ViewValue = $this->idPais->CurrentValue;
			}
		} else {
			$this->idPais->ViewValue = NULL;
		}
		}
		$this->idPais->ViewCustomAttributes = "";

		// idProvincia
		if (strval($this->idProvincia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvincia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
		$sWhereWrk = "";
		$this->idProvincia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idProvincia->ViewValue = $this->idProvincia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idProvincia->ViewValue = $this->idProvincia->CurrentValue;
			}
		} else {
			$this->idProvincia->ViewValue = NULL;
		}
		$this->idProvincia->ViewCustomAttributes = "";

		// idPartido
		if (strval($this->idPartido->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartido->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
		$sWhereWrk = "";
		$this->idPartido->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPartido->ViewValue = $this->idPartido->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPartido->ViewValue = $this->idPartido->CurrentValue;
			}
		} else {
			$this->idPartido->ViewValue = NULL;
		}
		$this->idPartido->ViewCustomAttributes = "";

		// idLocalidad
		if (strval($this->idLocalidad->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->idLocalidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idLocalidad->ViewValue = $this->idLocalidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idLocalidad->ViewValue = $this->idLocalidad->CurrentValue;
			}
		} else {
			$this->idLocalidad->ViewValue = NULL;
		}
		$this->idLocalidad->ViewCustomAttributes = "";

		// calle
		$this->calle->ViewValue = $this->calle->CurrentValue;
		$this->calle->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// domicilioFiscal
		if (strval($this->domicilioFiscal->CurrentValue) <> "") {
			$this->domicilioFiscal->ViewValue = $this->domicilioFiscal->OptionCaption($this->domicilioFiscal->CurrentValue);
		} else {
			$this->domicilioFiscal->ViewValue = NULL;
		}
		$this->domicilioFiscal->ViewCustomAttributes = "";

		// idPaisFiscal
		if (strval($this->idPaisFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPaisFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
		$sWhereWrk = "";
		$this->idPaisFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPaisFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPaisFiscal->ViewValue = $this->idPaisFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPaisFiscal->ViewValue = $this->idPaisFiscal->CurrentValue;
			}
		} else {
			$this->idPaisFiscal->ViewValue = NULL;
		}
		$this->idPaisFiscal->ViewCustomAttributes = "";

		// idProvinciaFiscal
		if (strval($this->idProvinciaFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvinciaFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
		$sWhereWrk = "";
		$this->idProvinciaFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idProvinciaFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idProvinciaFiscal->ViewValue = $this->idProvinciaFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idProvinciaFiscal->ViewValue = $this->idProvinciaFiscal->CurrentValue;
			}
		} else {
			$this->idProvinciaFiscal->ViewValue = NULL;
		}
		$this->idProvinciaFiscal->ViewCustomAttributes = "";

		// idPartidoFiscal
		if (strval($this->idPartidoFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartidoFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
		$sWhereWrk = "";
		$this->idPartidoFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPartidoFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idPartidoFiscal->ViewValue = $this->idPartidoFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPartidoFiscal->ViewValue = $this->idPartidoFiscal->CurrentValue;
			}
		} else {
			$this->idPartidoFiscal->ViewValue = NULL;
		}
		$this->idPartidoFiscal->ViewCustomAttributes = "";

		// idLocalidadFiscal
		if (strval($this->idLocalidadFiscal->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidadFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->idLocalidadFiscal->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idLocalidadFiscal, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idLocalidadFiscal->ViewValue = $this->idLocalidadFiscal->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idLocalidadFiscal->ViewValue = $this->idLocalidadFiscal->CurrentValue;
			}
		} else {
			$this->idLocalidadFiscal->ViewValue = NULL;
		}
		$this->idLocalidadFiscal->ViewCustomAttributes = "";

		// calleFiscal
		$this->calleFiscal->ViewValue = $this->calleFiscal->CurrentValue;
		$this->calleFiscal->ViewCustomAttributes = "";

		// direccionFiscal
		$this->direccionFiscal->ViewValue = $this->direccionFiscal->CurrentValue;
		$this->direccionFiscal->ViewCustomAttributes = "";

		// tipoDoc
		if (strval($this->tipoDoc->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
		$sWhereWrk = "";
		$this->tipoDoc->LookupFilters = array();
		$lookuptblfilter = "`activo`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipoDoc, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipoDoc->ViewValue = $this->tipoDoc->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipoDoc->ViewValue = $this->tipoDoc->CurrentValue;
			}
		} else {
			$this->tipoDoc->ViewValue = NULL;
		}
		$this->tipoDoc->ViewCustomAttributes = "";

		// documento
		$this->documento->ViewValue = $this->documento->CurrentValue;
		$this->documento->ViewCustomAttributes = "";

		// condicionIva
		if (strval($this->condicionIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->condicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
		$sWhereWrk = "";
		$this->condicionIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->condicionIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->condicionIva->ViewValue = $this->condicionIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->condicionIva->ViewValue = $this->condicionIva->CurrentValue;
			}
		} else {
			$this->condicionIva->ViewValue = NULL;
		}
		$this->condicionIva->ViewCustomAttributes = "";

		// idTransporte
		if (strval($this->idTransporte->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTransporte->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTransporte->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`=3";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTransporte, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTransporte->ViewValue = $this->idTransporte->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTransporte->ViewValue = $this->idTransporte->CurrentValue;
			}
		} else {
			$this->idTransporte->ViewValue = NULL;
		}
		$this->idTransporte->ViewCustomAttributes = "";

		// idVendedor
		if (strval($this->idVendedor->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idVendedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idVendedor->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`='4'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idVendedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idVendedor->ViewValue = $this->idVendedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idVendedor->ViewValue = $this->idVendedor->CurrentValue;
			}
		} else {
			$this->idVendedor->ViewValue = NULL;
		}
		$this->idVendedor->ViewCustomAttributes = "";

		// idCobrador
		if (strval($this->idCobrador->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCobrador->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idCobrador->LookupFilters = array();
		$lookuptblfilter = "`idTipoTercero`='4'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCobrador, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` DESC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCobrador->ViewValue = $this->idCobrador->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCobrador->ViewValue = $this->idCobrador->CurrentValue;
			}
		} else {
			$this->idCobrador->ViewValue = NULL;
		}
		$this->idCobrador->ViewCustomAttributes = "";

		// comision
		$this->comision->ViewValue = $this->comision->CurrentValue;
		$this->comision->ViewCustomAttributes = "";

		// idListaPrecios
		if (strval($this->idListaPrecios->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idListaPrecios->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `descuento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lista-precios`";
		$sWhereWrk = "";
		$this->idListaPrecios->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idListaPrecios, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `descuento` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->idListaPrecios->ViewValue = $this->idListaPrecios->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idListaPrecios->ViewValue = $this->idListaPrecios->CurrentValue;
			}
		} else {
			$this->idListaPrecios->ViewValue = NULL;
		}
		$this->idListaPrecios->ViewCustomAttributes = "";

		// dtoCliente
		$this->dtoCliente->ViewValue = $this->dtoCliente->CurrentValue;
		$this->dtoCliente->ViewCustomAttributes = "";

		// dto1
		$this->dto1->ViewValue = $this->dto1->CurrentValue;
		$this->dto1->ViewCustomAttributes = "";

		// dto2
		$this->dto2->ViewValue = $this->dto2->CurrentValue;
		$this->dto2->ViewCustomAttributes = "";

		// dto3
		$this->dto3->ViewValue = $this->dto3->CurrentValue;
		$this->dto3->ViewCustomAttributes = "";

		// limiteDescubierto
		$this->limiteDescubierto->ViewValue = $this->limiteDescubierto->CurrentValue;
		$this->limiteDescubierto->ViewCustomAttributes = "";

		// codigoPostal
		$this->codigoPostal->ViewValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->ViewCustomAttributes = "";

		// codigoPostalFiscal
		$this->codigoPostalFiscal->ViewValue = $this->codigoPostalFiscal->CurrentValue;
		$this->codigoPostalFiscal->ViewCustomAttributes = "";

		// condicionVenta
		$this->condicionVenta->ViewValue = $this->condicionVenta->CurrentValue;
		$this->condicionVenta->ViewCustomAttributes = "";

			// idTipoTercero
			$this->idTipoTercero->LinkCustomAttributes = "";
			$this->idTipoTercero->HrefValue = "";
			$this->idTipoTercero->TooltipValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";
			if ($this->Export == "")
				$this->denominacion->ViewValue = ew_Highlight($this->HighlightName(), $this->denominacion->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";
			if ($this->Export == "")
				$this->direccion->ViewValue = ew_Highlight($this->HighlightName(), $this->direccion->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";
			$this->documento->TooltipValue = "";
			if ($this->Export == "")
				$this->documento->ViewValue = ew_Highlight($this->HighlightName(), $this->documento->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// idTransporte
			$this->idTransporte->LinkCustomAttributes = "";
			$this->idTransporte->HrefValue = "";
			$this->idTransporte->TooltipValue = "";

			// limiteDescubierto
			$this->limiteDescubierto->LinkCustomAttributes = "";
			$this->limiteDescubierto->HrefValue = "";
			$this->limiteDescubierto->TooltipValue = "";

			// codigoPostal
			$this->codigoPostal->LinkCustomAttributes = "";
			$this->codigoPostal->HrefValue = "";
			$this->codigoPostal->TooltipValue = "";
			if ($this->Export == "")
				$this->codigoPostal->ViewValue = ew_Highlight($this->HighlightName(), $this->codigoPostal->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// codigoPostalFiscal
			$this->codigoPostalFiscal->LinkCustomAttributes = "";
			$this->codigoPostalFiscal->HrefValue = "";
			$this->codigoPostalFiscal->TooltipValue = "";
			if ($this->Export == "")
				$this->codigoPostalFiscal->ViewValue = ew_Highlight($this->HighlightName(), $this->codigoPostalFiscal->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

			// condicionVenta
			$this->condicionVenta->LinkCustomAttributes = "";
			$this->condicionVenta->HrefValue = "";
			$this->condicionVenta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// idTipoTercero
			$this->idTipoTercero->EditAttrs["class"] = "form-control";
			$this->idTipoTercero->EditCustomAttributes = 'data-elemento-dependiente="true"';
			if (trim(strval($this->idTipoTercero->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoTercero->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-terceros`";
			$sWhereWrk = "";
			$this->idTipoTercero->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTipoTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTipoTercero->EditValue = $arwrk;

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->AdvancedSearch->SearchValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->AdvancedSearch->SearchValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// documento
			$this->documento->EditAttrs["class"] = "form-control";
			$this->documento->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->documento->EditValue = ew_HtmlEncode($this->documento->AdvancedSearch->SearchValue);
			$this->documento->PlaceHolder = ew_RemoveHtml($this->documento->FldCaption());

			// idTransporte
			$this->idTransporte->EditAttrs["class"] = "form-control";
			$this->idTransporte->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';

			// limiteDescubierto
			$this->limiteDescubierto->EditAttrs["class"] = "form-control";
			$this->limiteDescubierto->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->limiteDescubierto->EditValue = ew_HtmlEncode($this->limiteDescubierto->AdvancedSearch->SearchValue);
			$this->limiteDescubierto->PlaceHolder = ew_RemoveHtml($this->limiteDescubierto->FldCaption());

			// codigoPostal
			$this->codigoPostal->EditAttrs["class"] = "form-control";
			$this->codigoPostal->EditCustomAttributes = "";
			$this->codigoPostal->EditValue = ew_HtmlEncode($this->codigoPostal->AdvancedSearch->SearchValue);
			$this->codigoPostal->PlaceHolder = ew_RemoveHtml($this->codigoPostal->FldCaption());

			// codigoPostalFiscal
			$this->codigoPostalFiscal->EditAttrs["class"] = "form-control";
			$this->codigoPostalFiscal->EditCustomAttributes = "";
			$this->codigoPostalFiscal->EditValue = ew_HtmlEncode($this->codigoPostalFiscal->AdvancedSearch->SearchValue);
			$this->codigoPostalFiscal->PlaceHolder = ew_RemoveHtml($this->codigoPostalFiscal->FldCaption());

			// condicionVenta
			$this->condicionVenta->EditAttrs["class"] = "form-control";
			$this->condicionVenta->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->condicionVenta->EditValue = ew_HtmlEncode($this->condicionVenta->AdvancedSearch->SearchValue);
			$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());
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
		$this->idTipoTercero->AdvancedSearch->Load();
		$this->denominacion->AdvancedSearch->Load();
		$this->razonSocial->AdvancedSearch->Load();
		$this->denominacionCorta->AdvancedSearch->Load();
		$this->idPais->AdvancedSearch->Load();
		$this->idProvincia->AdvancedSearch->Load();
		$this->idPartido->AdvancedSearch->Load();
		$this->idLocalidad->AdvancedSearch->Load();
		$this->calle->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->domicilioFiscal->AdvancedSearch->Load();
		$this->idPaisFiscal->AdvancedSearch->Load();
		$this->idProvinciaFiscal->AdvancedSearch->Load();
		$this->idPartidoFiscal->AdvancedSearch->Load();
		$this->idLocalidadFiscal->AdvancedSearch->Load();
		$this->calleFiscal->AdvancedSearch->Load();
		$this->direccionFiscal->AdvancedSearch->Load();
		$this->tipoDoc->AdvancedSearch->Load();
		$this->documento->AdvancedSearch->Load();
		$this->condicionIva->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->idTransporte->AdvancedSearch->Load();
		$this->idVendedor->AdvancedSearch->Load();
		$this->idCobrador->AdvancedSearch->Load();
		$this->comision->AdvancedSearch->Load();
		$this->idListaPrecios->AdvancedSearch->Load();
		$this->dtoCliente->AdvancedSearch->Load();
		$this->dto1->AdvancedSearch->Load();
		$this->dto2->AdvancedSearch->Load();
		$this->dto3->AdvancedSearch->Load();
		$this->limiteDescubierto->AdvancedSearch->Load();
		$this->codigoPostal->AdvancedSearch->Load();
		$this->codigoPostalFiscal->AdvancedSearch->Load();
		$this->condicionVenta->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_terceros\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_terceros',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fterceroslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		case "x_idTipoTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-terceros`";
			$sWhereWrk = "";
			$this->idTipoTercero->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTipoTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPais":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
			$sWhereWrk = "";
			$this->idPais->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idProvincia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
			$sWhereWrk = "{filter}";
			$this->idProvincia->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idPais` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPartido":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
			$sWhereWrk = "{filter}";
			$this->idPartido->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idProvincia` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idLocalidad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
			$sWhereWrk = "{filter}";
			$this->idLocalidad->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idPartido` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_tipoDoc":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
			$sWhereWrk = "";
			$this->tipoDoc->LookupFilters = array();
			$lookuptblfilter = "`activo`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->tipoDoc, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_condicionIva":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
			$sWhereWrk = "";
			$this->condicionIva->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->condicionIva, $sWhereWrk); // Call Lookup selecting
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
		include_once("funciones.php");
		$_SESSION["hoy"]=obtenerDiaSemana();
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
			$opt = &$this->ListOptions->Add("horarios");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left
			$opt = &$this->ListOptions->Add("listaPrecios");
			$opt->Header = "";
			$opt->OnLeft = TRUE; // Link on left				
			$this->ListOptions->Items["detail_categorias2Dterceros2Ddescuentos"]->Visible=FALSE;
			$this->ListOptions->Items["detail_subcategoria2Dterceros2Ddescuentos"]->Visible=FALSE;
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {
			$sql="SELECT * FROM `terceros-horarios`
				WHERE idTercero='".$this->id->DbValue."'
				AND dia='".$_SESSION["hoy"]."'
				AND horaDesde<'".date("H:i")."'
				AND horaHasta>'".date("H:i")."'";
			$rs = ew_Execute($sql);
			if ($rs && $rs->RecordCount() === 1){
				$color = "rgb(0,150,0)";
				$tooltip="Horarios (Abierto)";
			}else{
				$color = "rgb(150,0,0)";
				$tooltip="Horarios (Cerrado)";		
			}				
			$this->ListOptions->Items["detail_terceros2Dmedios2Dcontactos"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Medios de Contacto" href="terceros2Dmedios2Dcontactoslist.php?showmaster=terceros&amp;fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-phone-alt ewIcon" aria-hidden="true"></span></a></div>';

			//$this->ListOptions->Items["detail_terceros2Dcontactos"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Personas de Contacto" href="terceros2Dcontactoslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-user ewIcon" aria-hidden="true"></span></a></div>';
			$this->ListOptions->Items["horarios"]->Body='<a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="'.$tooltip.'" href="terceroshorarios.php?idTercero='.$this->id->DbValue.'"><span style="color:'.$color.'" class="glyphicon glyphicon-time ewIcon" aria-hidden="true"></span></a>';
					if ($this->Recordset->fields["idTipoTercero"]==1) {
						$this->ListOptions->Items["detail_articulos2Dproveedores"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Precios de Compra" href="articulos2Dproveedoreslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-shopping-cart ewIcon" aria-hidden="true"></span></a></div>';			
						$this->ListOptions->Items["listaPrecios"]->Body='';						
					}else{
						$this->ListOptions->Items["detail_articulos2Dproveedores"]->Body='';
						$this->ListOptions->Items["listaPrecios"]->Body='<div class="btn-group"><a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Lista de precios" href="listaprecios.php?idTercero='.$this->id->DbValue.'"><span class="glyphicon glyphicon-usd ewIcon" aria-hidden="true"></span></a></div>';			
					}
				if ($this->Recordset->fields["idTipoTercero"]==2) {
					$this->ListOptions->Items["detail_articulos2Dterceros2Ddescuentos"]->Body='
					<div class="" data-name="button">
						<div class="btn-group">
							<button class="dropdown-toggle btn btn-sm btn-default" title="Descuentos" data-toggle="dropdown" data-original-title="Descuentos">
								<span data-phrase="Filters" class="glyphicon glyphicon-piggy-bank ewIcon" data-caption="Filtros"></span>
								<b class="caret"></b>
								</button>
									<ul class="dropdown-menu ewMenu">
										<li><a href="categorias2Dterceros2Ddescuentoslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'">Por Categoría</a></li>
										<li><a href="subcategoria2Dterceros2Ddescuentoslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'">Por Subcategoría</a></li>
										<li><a href="articulos2Dterceros2Ddescuentoslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'">Por Artículo</a></li>								
									</ul>
								</div>
							</div>';
					$this->ListOptions->Items["detail_descuentosasociados"]->Body='<a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Descuentos Asociados" href="descuentosasociadoslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-magnet ewIcon" aria-hidden="true"></span></a>';		
				}else{
					$this->ListOptions->Items["detail_articulos2Dterceros2Ddescuentos"]->Body='';
					$this->ListOptions->Items["detail_descuentosasociados"]->Body='';
				}
				$this->ListOptions->Items["detail_sucursales"]->Body='<a class="btn btn-default btn-sm ewRowLink ewDetail" data-action="list" data-toggle="tooltip" data-placement="bottom" title="Sucursales" href="sucursaleslist.php?showmaster=terceros&fk_id='.$this->id->DbValue.'"><span class="glyphicon glyphicon-home ewIcon" aria-hidden="true"></span></a>';		
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
if (!isset($terceros_list)) $terceros_list = new cterceros_list();

// Page init
$terceros_list->Page_Init();

// Page main
$terceros_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fterceroslist = new ew_Form("fterceroslist", "list");
fterceroslist.FormKeyCountName = '<?php echo $terceros_list->FormKeyCountName ?>';

// Form_CustomValidate event
fterceroslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceroslist.ValidateRequired = true;
<?php } else { ?>
fterceroslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fterceroslist.Lists["x_idTipoTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dterceros"};
fterceroslist.Lists["x_idTransporte"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
var CurrentSearchForm = fterceroslistsrch = new ew_Form("fterceroslistsrch");

// Validate function for search
fterceroslistsrch.Validate = function(fobj) {
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
fterceroslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceroslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fterceroslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fterceroslistsrch.Lists["x_idTipoTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dterceros"};

// Init search panel as collapsed
if (fterceroslistsrch) fterceroslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($terceros->Export == "") { ?>
<div class="ewToolbar">
<?php if ($terceros->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($terceros_list->TotalRecs > 0 && $terceros_list->ExportOptions->Visible()) { ?>
<?php $terceros_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros_list->SearchOptions->Visible()) { ?>
<?php $terceros_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros_list->FilterOptions->Visible()) { ?>
<?php $terceros_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($terceros->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $terceros_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($terceros_list->TotalRecs <= 0)
			$terceros_list->TotalRecs = $terceros->SelectRecordCount();
	} else {
		if (!$terceros_list->Recordset && ($terceros_list->Recordset = $terceros_list->LoadRecordset()))
			$terceros_list->TotalRecs = $terceros_list->Recordset->RecordCount();
	}
	$terceros_list->StartRec = 1;
	if ($terceros_list->DisplayRecs <= 0 || ($terceros->Export <> "" && $terceros->ExportAll)) // Display all records
		$terceros_list->DisplayRecs = $terceros_list->TotalRecs;
	if (!($terceros->Export <> "" && $terceros->ExportAll))
		$terceros_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$terceros_list->Recordset = $terceros_list->LoadRecordset($terceros_list->StartRec-1, $terceros_list->DisplayRecs);

	// Set no record found message
	if ($terceros->CurrentAction == "" && $terceros_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$terceros_list->setWarningMessage(ew_DeniedMsg());
		if ($terceros_list->SearchWhere == "0=101")
			$terceros_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$terceros_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$terceros_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($terceros->Export == "" && $terceros->CurrentAction == "") { ?>
<form name="fterceroslistsrch" id="fterceroslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($terceros_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fterceroslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="terceros">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$terceros_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$terceros->RowType = EW_ROWTYPE_SEARCH;

// Render row
$terceros->ResetAttrs();
$terceros_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
	<div id="xsc_idTipoTercero" class="ewCell form-group">
		<label for="x_idTipoTercero" class="ewSearchCaption ewLabel"><?php echo $terceros->idTipoTercero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idTipoTercero" id="z_idTipoTercero" value="="></span>
		<span class="ewSearchField">
<select data-table="terceros" data-field="x_idTipoTercero" data-value-separator="<?php echo $terceros->idTipoTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTipoTercero" name="x_idTipoTercero"<?php echo $terceros->idTipoTercero->EditAttributes() ?>>
<?php echo $terceros->idTipoTercero->SelectOptionListHtml("x_idTipoTercero") ?>
</select>
<input type="hidden" name="s_x_idTipoTercero" id="s_x_idTipoTercero" value="<?php echo $terceros->idTipoTercero->LookupFilterQuery(false, "extbs") ?>">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($terceros_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($terceros_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $terceros_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($terceros_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($terceros_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($terceros_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($terceros_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $terceros_list->ShowPageHeader(); ?>
<?php
$terceros_list->ShowMessage();
?>
<?php if ($terceros_list->TotalRecs > 0 || $terceros->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid terceros">
<?php if ($terceros->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($terceros->CurrentAction <> "gridadd" && $terceros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($terceros_list->Pager)) $terceros_list->Pager = new cPrevNextPager($terceros_list->StartRec, $terceros_list->DisplayRecs, $terceros_list->TotalRecs) ?>
<?php if ($terceros_list->Pager->RecordCount > 0 && $terceros_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($terceros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $terceros_list->PageUrl() ?>start=<?php echo $terceros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($terceros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $terceros_list->PageUrl() ?>start=<?php echo $terceros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $terceros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($terceros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $terceros_list->PageUrl() ?>start=<?php echo $terceros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($terceros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $terceros_list->PageUrl() ?>start=<?php echo $terceros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $terceros_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $terceros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $terceros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $terceros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($terceros_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="terceros">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($terceros_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($terceros_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($terceros_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($terceros_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($terceros_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fterceroslist" id="fterceroslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros">
<div id="gmp_terceros" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($terceros_list->TotalRecs > 0) { ?>
<table id="tbl_terceroslist" class="table ewTable">
<?php echo $terceros->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$terceros_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$terceros_list->RenderListOptions();

// Render list options (header, left)
$terceros_list->ListOptions->Render("header", "left");
?>
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
	<?php if ($terceros->SortUrl($terceros->idTipoTercero) == "") { ?>
		<th data-name="idTipoTercero"><div id="elh_terceros_idTipoTercero" class="terceros_idTipoTercero"><div class="ewTableHeaderCaption"><?php echo $terceros->idTipoTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTipoTercero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->idTipoTercero) ?>',2);"><div id="elh_terceros_idTipoTercero" class="terceros_idTipoTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->idTipoTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros->idTipoTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->idTipoTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
	<?php if ($terceros->SortUrl($terceros->denominacion) == "") { ?>
		<th data-name="denominacion"><div id="elh_terceros_denominacion" class="terceros_denominacion"><div class="ewTableHeaderCaption"><?php echo $terceros->denominacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->denominacion) ?>',2);"><div id="elh_terceros_denominacion" class="terceros_denominacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->denominacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros->denominacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->denominacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->direccion->Visible) { // direccion ?>
	<?php if ($terceros->SortUrl($terceros->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_terceros_direccion" class="terceros_direccion"><div class="ewTableHeaderCaption"><?php echo $terceros->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->direccion) ?>',2);"><div id="elh_terceros_direccion" class="terceros_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->direccion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->documento->Visible) { // documento ?>
	<?php if ($terceros->SortUrl($terceros->documento) == "") { ?>
		<th data-name="documento"><div id="elh_terceros_documento" class="terceros_documento"><div class="ewTableHeaderCaption"><?php echo $terceros->documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="documento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->documento) ?>',2);"><div id="elh_terceros_documento" class="terceros_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->documento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros->documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
	<?php if ($terceros->SortUrl($terceros->idTransporte) == "") { ?>
		<th data-name="idTransporte"><div id="elh_terceros_idTransporte" class="terceros_idTransporte"><div class="ewTableHeaderCaption"><?php echo $terceros->idTransporte->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTransporte"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->idTransporte) ?>',2);"><div id="elh_terceros_idTransporte" class="terceros_idTransporte">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->idTransporte->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros->idTransporte->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->idTransporte->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
	<?php if ($terceros->SortUrl($terceros->limiteDescubierto) == "") { ?>
		<th data-name="limiteDescubierto"><div id="elh_terceros_limiteDescubierto" class="terceros_limiteDescubierto"><div class="ewTableHeaderCaption"><?php echo $terceros->limiteDescubierto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="limiteDescubierto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->limiteDescubierto) ?>',2);"><div id="elh_terceros_limiteDescubierto" class="terceros_limiteDescubierto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->limiteDescubierto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros->limiteDescubierto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->limiteDescubierto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
	<?php if ($terceros->SortUrl($terceros->codigoPostal) == "") { ?>
		<th data-name="codigoPostal"><div id="elh_terceros_codigoPostal" class="terceros_codigoPostal"><div class="ewTableHeaderCaption"><?php echo $terceros->codigoPostal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigoPostal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->codigoPostal) ?>',2);"><div id="elh_terceros_codigoPostal" class="terceros_codigoPostal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->codigoPostal->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros->codigoPostal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->codigoPostal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
	<?php if ($terceros->SortUrl($terceros->codigoPostalFiscal) == "") { ?>
		<th data-name="codigoPostalFiscal"><div id="elh_terceros_codigoPostalFiscal" class="terceros_codigoPostalFiscal"><div class="ewTableHeaderCaption"><?php echo $terceros->codigoPostalFiscal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigoPostalFiscal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->codigoPostalFiscal) ?>',2);"><div id="elh_terceros_codigoPostalFiscal" class="terceros_codigoPostalFiscal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->codigoPostalFiscal->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($terceros->codigoPostalFiscal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->codigoPostalFiscal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
	<?php if ($terceros->SortUrl($terceros->condicionVenta) == "") { ?>
		<th data-name="condicionVenta"><div id="elh_terceros_condicionVenta" class="terceros_condicionVenta"><div class="ewTableHeaderCaption"><?php echo $terceros->condicionVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="condicionVenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $terceros->SortUrl($terceros->condicionVenta) ?>',2);"><div id="elh_terceros_condicionVenta" class="terceros_condicionVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros->condicionVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros->condicionVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros->condicionVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$terceros_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($terceros->ExportAll && $terceros->Export <> "") {
	$terceros_list->StopRec = $terceros_list->TotalRecs;
} else {

	// Set the last record to display
	if ($terceros_list->TotalRecs > $terceros_list->StartRec + $terceros_list->DisplayRecs - 1)
		$terceros_list->StopRec = $terceros_list->StartRec + $terceros_list->DisplayRecs - 1;
	else
		$terceros_list->StopRec = $terceros_list->TotalRecs;
}
$terceros_list->RecCnt = $terceros_list->StartRec - 1;
if ($terceros_list->Recordset && !$terceros_list->Recordset->EOF) {
	$terceros_list->Recordset->MoveFirst();
	$bSelectLimit = $terceros_list->UseSelectLimit;
	if (!$bSelectLimit && $terceros_list->StartRec > 1)
		$terceros_list->Recordset->Move($terceros_list->StartRec - 1);
} elseif (!$terceros->AllowAddDeleteRow && $terceros_list->StopRec == 0) {
	$terceros_list->StopRec = $terceros->GridAddRowCount;
}

// Initialize aggregate
$terceros->RowType = EW_ROWTYPE_AGGREGATEINIT;
$terceros->ResetAttrs();
$terceros_list->RenderRow();
while ($terceros_list->RecCnt < $terceros_list->StopRec) {
	$terceros_list->RecCnt++;
	if (intval($terceros_list->RecCnt) >= intval($terceros_list->StartRec)) {
		$terceros_list->RowCnt++;

		// Set up key count
		$terceros_list->KeyCount = $terceros_list->RowIndex;

		// Init row class and style
		$terceros->ResetAttrs();
		$terceros->CssClass = "";
		if ($terceros->CurrentAction == "gridadd") {
		} else {
			$terceros_list->LoadRowValues($terceros_list->Recordset); // Load row values
		}
		$terceros->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$terceros->RowAttrs = array_merge($terceros->RowAttrs, array('data-rowindex'=>$terceros_list->RowCnt, 'id'=>'r' . $terceros_list->RowCnt . '_terceros', 'data-rowtype'=>$terceros->RowType));

		// Render row
		$terceros_list->RenderRow();

		// Render list options
		$terceros_list->RenderListOptions();
?>
	<tr<?php echo $terceros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros_list->ListOptions->Render("body", "left", $terceros_list->RowCnt);
?>
	<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
		<td data-name="idTipoTercero"<?php echo $terceros->idTipoTercero->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_idTipoTercero" class="terceros_idTipoTercero">
<span<?php echo $terceros->idTipoTercero->ViewAttributes() ?>>
<?php echo $terceros->idTipoTercero->ListViewValue() ?></span>
</span>
<a id="<?php echo $terceros_list->PageObjName . "_row_" . $terceros_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($terceros->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion"<?php echo $terceros->denominacion->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_denominacion" class="terceros_denominacion">
<span<?php echo $terceros->denominacion->ViewAttributes() ?>>
<?php echo $terceros->denominacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $terceros->direccion->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_direccion" class="terceros_direccion">
<span<?php echo $terceros->direccion->ViewAttributes() ?>>
<?php echo $terceros->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->documento->Visible) { // documento ?>
		<td data-name="documento"<?php echo $terceros->documento->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_documento" class="terceros_documento">
<span<?php echo $terceros->documento->ViewAttributes() ?>>
<?php echo $terceros->documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
		<td data-name="idTransporte"<?php echo $terceros->idTransporte->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_idTransporte" class="terceros_idTransporte">
<span<?php echo $terceros->idTransporte->ViewAttributes() ?>>
<?php echo $terceros->idTransporte->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
		<td data-name="limiteDescubierto"<?php echo $terceros->limiteDescubierto->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_limiteDescubierto" class="terceros_limiteDescubierto">
<span<?php echo $terceros->limiteDescubierto->ViewAttributes() ?>>
<?php echo $terceros->limiteDescubierto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
		<td data-name="codigoPostal"<?php echo $terceros->codigoPostal->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_codigoPostal" class="terceros_codigoPostal">
<span<?php echo $terceros->codigoPostal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
		<td data-name="codigoPostalFiscal"<?php echo $terceros->codigoPostalFiscal->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_codigoPostalFiscal" class="terceros_codigoPostalFiscal">
<span<?php echo $terceros->codigoPostalFiscal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostalFiscal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
		<td data-name="condicionVenta"<?php echo $terceros->condicionVenta->CellAttributes() ?>>
<span id="el<?php echo $terceros_list->RowCnt ?>_terceros_condicionVenta" class="terceros_condicionVenta">
<span<?php echo $terceros->condicionVenta->ViewAttributes() ?>>
<?php echo $terceros->condicionVenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros_list->ListOptions->Render("body", "right", $terceros_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($terceros->CurrentAction <> "gridadd")
		$terceros_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($terceros->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($terceros_list->Recordset)
	$terceros_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($terceros_list->TotalRecs == 0 && $terceros->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">
fterceroslistsrch.FilterList = <?php echo $terceros_list->GetFilterList() ?>;
fterceroslistsrch.Init();
fterceroslist.Init();
</script>
<?php } ?>
<?php
$terceros_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	ocultarMostrarCampos();	
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$terceros_list->Page_Terminate();
?>
