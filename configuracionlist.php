<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "configuracioninfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$configuracion_list = NULL; // Initialize page object first

class cconfiguracion_list extends cconfiguracion {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'configuracion';

	// Page object name
	var $PageObjName = 'configuracion_list';

	// Grid form hidden field names
	var $FormName = 'fconfiguracionlist';
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

		// Table object (configuracion)
		if (!isset($GLOBALS["configuracion"]) || get_class($GLOBALS["configuracion"]) == "cconfiguracion") {
			$GLOBALS["configuracion"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["configuracion"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "configuracionadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "configuraciondelete.php";
		$this->MultiUpdateUrl = "configuracionupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'configuracion', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fconfiguracionlistsrch";

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
		$this->denominacion->SetVisibility();
		$this->idTipoDoc->SetVisibility();
		$this->documento->SetVisibility();
		$this->idPais->SetVisibility();
		$this->idProvincia->SetVisibility();
		$this->idPartido->SetVisibility();
		$this->idLocalidad->SetVisibility();
		$this->calle->SetVisibility();
		$this->direccion->SetVisibility();
		$this->codigoPostal->SetVisibility();
		$this->telefono->SetVisibility();
		$this->_email->SetVisibility();
		$this->idCondicionIva->SetVisibility();
		$this->logo->SetVisibility();
		$this->inicioActividades->SetVisibility();
		$this->ingresosBrutos->SetVisibility();
		$this->puntoVenta->SetVisibility();
		$this->puntoVentaElectronico->SetVisibility();

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
		global $EW_EXPORT, $configuracion;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($configuracion);
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->denominacion, $bCtrl); // denominacion
			$this->UpdateSort($this->idTipoDoc, $bCtrl); // idTipoDoc
			$this->UpdateSort($this->documento, $bCtrl); // documento
			$this->UpdateSort($this->idPais, $bCtrl); // idPais
			$this->UpdateSort($this->idProvincia, $bCtrl); // idProvincia
			$this->UpdateSort($this->idPartido, $bCtrl); // idPartido
			$this->UpdateSort($this->idLocalidad, $bCtrl); // idLocalidad
			$this->UpdateSort($this->calle, $bCtrl); // calle
			$this->UpdateSort($this->direccion, $bCtrl); // direccion
			$this->UpdateSort($this->codigoPostal, $bCtrl); // codigoPostal
			$this->UpdateSort($this->telefono, $bCtrl); // telefono
			$this->UpdateSort($this->_email, $bCtrl); // email
			$this->UpdateSort($this->idCondicionIva, $bCtrl); // idCondicionIva
			$this->UpdateSort($this->logo, $bCtrl); // logo
			$this->UpdateSort($this->inicioActividades, $bCtrl); // inicioActividades
			$this->UpdateSort($this->ingresosBrutos, $bCtrl); // ingresosBrutos
			$this->UpdateSort($this->puntoVenta, $bCtrl); // puntoVenta
			$this->UpdateSort($this->puntoVentaElectronico, $bCtrl); // puntoVentaElectronico
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
				$this->idTipoDoc->setSort("");
				$this->documento->setSort("");
				$this->idPais->setSort("");
				$this->idProvincia->setSort("");
				$this->idPartido->setSort("");
				$this->idLocalidad->setSort("");
				$this->calle->setSort("");
				$this->direccion->setSort("");
				$this->codigoPostal->setSort("");
				$this->telefono->setSort("");
				$this->_email->setSort("");
				$this->idCondicionIva->setSort("");
				$this->logo->setSort("");
				$this->inicioActividades->setSort("");
				$this->ingresosBrutos->setSort("");
				$this->puntoVenta->setSort("");
				$this->puntoVentaElectronico->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fconfiguracionlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fconfiguracionlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fconfiguracionlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->denominacion->setDbValue($rs->fields('denominacion'));
		$this->idTipoDoc->setDbValue($rs->fields('idTipoDoc'));
		$this->documento->setDbValue($rs->fields('documento'));
		$this->idPais->setDbValue($rs->fields('idPais'));
		$this->idProvincia->setDbValue($rs->fields('idProvincia'));
		$this->idPartido->setDbValue($rs->fields('idPartido'));
		$this->idLocalidad->setDbValue($rs->fields('idLocalidad'));
		$this->calle->setDbValue($rs->fields('calle'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->codigoPostal->setDbValue($rs->fields('codigoPostal'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->idCondicionIva->setDbValue($rs->fields('idCondicionIva'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->logo->CurrentValue = $this->logo->Upload->DbValue;
		$this->ta->setDbValue($rs->fields('ta'));
		$this->cert->setDbValue($rs->fields('cert'));
		$this->privatekey->setDbValue($rs->fields('privatekey'));
		$this->inicioActividades->setDbValue($rs->fields('inicioActividades'));
		$this->ingresosBrutos->setDbValue($rs->fields('ingresosBrutos'));
		$this->puntoVenta->setDbValue($rs->fields('puntoVenta'));
		$this->puntoVentaElectronico->setDbValue($rs->fields('puntoVentaElectronico'));
		$this->contable->setDbValue($rs->fields('contable'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->idTipoDoc->DbValue = $row['idTipoDoc'];
		$this->documento->DbValue = $row['documento'];
		$this->idPais->DbValue = $row['idPais'];
		$this->idProvincia->DbValue = $row['idProvincia'];
		$this->idPartido->DbValue = $row['idPartido'];
		$this->idLocalidad->DbValue = $row['idLocalidad'];
		$this->calle->DbValue = $row['calle'];
		$this->direccion->DbValue = $row['direccion'];
		$this->codigoPostal->DbValue = $row['codigoPostal'];
		$this->telefono->DbValue = $row['telefono'];
		$this->_email->DbValue = $row['email'];
		$this->idCondicionIva->DbValue = $row['idCondicionIva'];
		$this->logo->Upload->DbValue = $row['logo'];
		$this->ta->DbValue = $row['ta'];
		$this->cert->DbValue = $row['cert'];
		$this->privatekey->DbValue = $row['privatekey'];
		$this->inicioActividades->DbValue = $row['inicioActividades'];
		$this->ingresosBrutos->DbValue = $row['ingresosBrutos'];
		$this->puntoVenta->DbValue = $row['puntoVenta'];
		$this->puntoVentaElectronico->DbValue = $row['puntoVentaElectronico'];
		$this->contable->DbValue = $row['contable'];
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

		// denominacion
		// idTipoDoc
		// documento
		// idPais
		// idProvincia
		// idPartido
		// idLocalidad
		// calle
		// direccion
		// codigoPostal
		// telefono
		// email
		// idCondicionIva
		// logo
		// ta

		$this->ta->CellCssStyle = "white-space: nowrap;";

		// cert
		$this->cert->CellCssStyle = "white-space: nowrap;";

		// privatekey
		$this->privatekey->CellCssStyle = "white-space: nowrap;";

		// inicioActividades
		// ingresosBrutos
		// puntoVenta
		// puntoVentaElectronico
		// contable

		$this->contable->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// idTipoDoc
		if (strval($this->idTipoDoc->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipos-documentos`";
		$sWhereWrk = "";
		$this->idTipoDoc->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTipoDoc, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idTipoDoc->ViewValue = $this->idTipoDoc->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idTipoDoc->ViewValue = $this->idTipoDoc->CurrentValue;
			}
		} else {
			$this->idTipoDoc->ViewValue = NULL;
		}
		$this->idTipoDoc->ViewCustomAttributes = "";

		// documento
		$this->documento->ViewValue = $this->documento->CurrentValue;
		$this->documento->ViewCustomAttributes = "";

		// idPais
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

		// codigoPostal
		$this->codigoPostal->ViewValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostal->ViewCustomAttributes = "";

		// telefono
		$this->telefono->ViewValue = $this->telefono->CurrentValue;
		$this->telefono->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

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

		// logo
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->ViewValue = $this->logo->Upload->DbValue;
		} else {
			$this->logo->ViewValue = "";
		}
		$this->logo->ViewCustomAttributes = "";

		// inicioActividades
		$this->inicioActividades->ViewValue = $this->inicioActividades->CurrentValue;
		$this->inicioActividades->ViewCustomAttributes = "";

		// ingresosBrutos
		$this->ingresosBrutos->ViewValue = $this->ingresosBrutos->CurrentValue;
		$this->ingresosBrutos->ViewCustomAttributes = "";

		// puntoVenta
		$this->puntoVenta->ViewValue = $this->puntoVenta->CurrentValue;
		$this->puntoVenta->ViewCustomAttributes = "";

		// puntoVentaElectronico
		$this->puntoVentaElectronico->ViewValue = $this->puntoVentaElectronico->CurrentValue;
		$this->puntoVentaElectronico->ViewCustomAttributes = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// idTipoDoc
			$this->idTipoDoc->LinkCustomAttributes = "";
			$this->idTipoDoc->HrefValue = "";
			$this->idTipoDoc->TooltipValue = "";

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";
			$this->documento->TooltipValue = "";

			// idPais
			$this->idPais->LinkCustomAttributes = "";
			$this->idPais->HrefValue = "";
			$this->idPais->TooltipValue = "";

			// idProvincia
			$this->idProvincia->LinkCustomAttributes = "";
			$this->idProvincia->HrefValue = "";
			$this->idProvincia->TooltipValue = "";

			// idPartido
			$this->idPartido->LinkCustomAttributes = "";
			$this->idPartido->HrefValue = "";
			$this->idPartido->TooltipValue = "";

			// idLocalidad
			$this->idLocalidad->LinkCustomAttributes = "";
			$this->idLocalidad->HrefValue = "";
			$this->idLocalidad->TooltipValue = "";

			// calle
			$this->calle->LinkCustomAttributes = "";
			$this->calle->HrefValue = "";
			$this->calle->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// codigoPostal
			$this->codigoPostal->LinkCustomAttributes = "";
			$this->codigoPostal->HrefValue = "";
			$this->codigoPostal->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// idCondicionIva
			$this->idCondicionIva->LinkCustomAttributes = "";
			$this->idCondicionIva->HrefValue = "";
			$this->idCondicionIva->TooltipValue = "";

			// logo
			$this->logo->LinkCustomAttributes = "";
			$this->logo->HrefValue = "";
			$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;
			$this->logo->TooltipValue = "";

			// inicioActividades
			$this->inicioActividades->LinkCustomAttributes = "";
			$this->inicioActividades->HrefValue = "";
			$this->inicioActividades->TooltipValue = "";

			// ingresosBrutos
			$this->ingresosBrutos->LinkCustomAttributes = "";
			$this->ingresosBrutos->HrefValue = "";
			$this->ingresosBrutos->TooltipValue = "";

			// puntoVenta
			$this->puntoVenta->LinkCustomAttributes = "";
			$this->puntoVenta->HrefValue = "";
			$this->puntoVenta->TooltipValue = "";

			// puntoVentaElectronico
			$this->puntoVentaElectronico->LinkCustomAttributes = "";
			$this->puntoVentaElectronico->HrefValue = "";
			$this->puntoVentaElectronico->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_configuracion\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_configuracion',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fconfiguracionlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($configuracion_list)) $configuracion_list = new cconfiguracion_list();

// Page init
$configuracion_list->Page_Init();

// Page main
$configuracion_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$configuracion_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($configuracion->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fconfiguracionlist = new ew_Form("fconfiguracionlist", "list");
fconfiguracionlist.FormKeyCountName = '<?php echo $configuracion_list->FormKeyCountName ?>';

// Form_CustomValidate event
fconfiguracionlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconfiguracionlist.ValidateRequired = true;
<?php } else { ?>
fconfiguracionlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconfiguracionlist.Lists["x_idTipoDoc"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Ddocumentos"};
fconfiguracionlist.Lists["x_idPais"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"paises"};
fconfiguracionlist.Lists["x_idProvincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincias"};
fconfiguracionlist.Lists["x_idPartido"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"partidos"};
fconfiguracionlist.Lists["x_idLocalidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"localidades"};
fconfiguracionlist.Lists["x_idCondicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($configuracion->Export == "") { ?>
<div class="ewToolbar">
<?php if ($configuracion->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($configuracion_list->TotalRecs > 0 && $configuracion_list->ExportOptions->Visible()) { ?>
<?php $configuracion_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($configuracion->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $configuracion_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($configuracion_list->TotalRecs <= 0)
			$configuracion_list->TotalRecs = $configuracion->SelectRecordCount();
	} else {
		if (!$configuracion_list->Recordset && ($configuracion_list->Recordset = $configuracion_list->LoadRecordset()))
			$configuracion_list->TotalRecs = $configuracion_list->Recordset->RecordCount();
	}
	$configuracion_list->StartRec = 1;
	if ($configuracion_list->DisplayRecs <= 0 || ($configuracion->Export <> "" && $configuracion->ExportAll)) // Display all records
		$configuracion_list->DisplayRecs = $configuracion_list->TotalRecs;
	if (!($configuracion->Export <> "" && $configuracion->ExportAll))
		$configuracion_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$configuracion_list->Recordset = $configuracion_list->LoadRecordset($configuracion_list->StartRec-1, $configuracion_list->DisplayRecs);

	// Set no record found message
	if ($configuracion->CurrentAction == "" && $configuracion_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$configuracion_list->setWarningMessage(ew_DeniedMsg());
		if ($configuracion_list->SearchWhere == "0=101")
			$configuracion_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$configuracion_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$configuracion_list->RenderOtherOptions();
?>
<?php $configuracion_list->ShowPageHeader(); ?>
<?php
$configuracion_list->ShowMessage();
?>
<?php if ($configuracion_list->TotalRecs > 0 || $configuracion->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid configuracion">
<?php if ($configuracion->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($configuracion->CurrentAction <> "gridadd" && $configuracion->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($configuracion_list->Pager)) $configuracion_list->Pager = new cPrevNextPager($configuracion_list->StartRec, $configuracion_list->DisplayRecs, $configuracion_list->TotalRecs) ?>
<?php if ($configuracion_list->Pager->RecordCount > 0 && $configuracion_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($configuracion_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $configuracion_list->PageUrl() ?>start=<?php echo $configuracion_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($configuracion_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $configuracion_list->PageUrl() ?>start=<?php echo $configuracion_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $configuracion_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($configuracion_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $configuracion_list->PageUrl() ?>start=<?php echo $configuracion_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($configuracion_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $configuracion_list->PageUrl() ?>start=<?php echo $configuracion_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $configuracion_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $configuracion_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $configuracion_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $configuracion_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($configuracion_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="configuracion">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($configuracion_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($configuracion_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="40"<?php if ($configuracion_list->DisplayRecs == 40) { ?> selected<?php } ?>>40</option>
<option value="80"<?php if ($configuracion_list->DisplayRecs == 80) { ?> selected<?php } ?>>80</option>
<option value="160"<?php if ($configuracion_list->DisplayRecs == 160) { ?> selected<?php } ?>>160</option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($configuracion_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fconfiguracionlist" id="fconfiguracionlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($configuracion_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $configuracion_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="configuracion">
<div id="gmp_configuracion" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($configuracion_list->TotalRecs > 0) { ?>
<table id="tbl_configuracionlist" class="table ewTable">
<?php echo $configuracion->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$configuracion_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$configuracion_list->RenderListOptions();

// Render list options (header, left)
$configuracion_list->ListOptions->Render("header", "left");
?>
<?php if ($configuracion->denominacion->Visible) { // denominacion ?>
	<?php if ($configuracion->SortUrl($configuracion->denominacion) == "") { ?>
		<th data-name="denominacion"><div id="elh_configuracion_denominacion" class="configuracion_denominacion"><div class="ewTableHeaderCaption"><?php echo $configuracion->denominacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->denominacion) ?>',2);"><div id="elh_configuracion_denominacion" class="configuracion_denominacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->denominacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->denominacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->denominacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idTipoDoc->Visible) { // idTipoDoc ?>
	<?php if ($configuracion->SortUrl($configuracion->idTipoDoc) == "") { ?>
		<th data-name="idTipoDoc"><div id="elh_configuracion_idTipoDoc" class="configuracion_idTipoDoc"><div class="ewTableHeaderCaption"><?php echo $configuracion->idTipoDoc->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTipoDoc"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idTipoDoc) ?>',2);"><div id="elh_configuracion_idTipoDoc" class="configuracion_idTipoDoc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idTipoDoc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idTipoDoc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idTipoDoc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->documento->Visible) { // documento ?>
	<?php if ($configuracion->SortUrl($configuracion->documento) == "") { ?>
		<th data-name="documento"><div id="elh_configuracion_documento" class="configuracion_documento"><div class="ewTableHeaderCaption"><?php echo $configuracion->documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="documento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->documento) ?>',2);"><div id="elh_configuracion_documento" class="configuracion_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idPais->Visible) { // idPais ?>
	<?php if ($configuracion->SortUrl($configuracion->idPais) == "") { ?>
		<th data-name="idPais"><div id="elh_configuracion_idPais" class="configuracion_idPais"><div class="ewTableHeaderCaption"><?php echo $configuracion->idPais->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idPais"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idPais) ?>',2);"><div id="elh_configuracion_idPais" class="configuracion_idPais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idPais->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idPais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idPais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idProvincia->Visible) { // idProvincia ?>
	<?php if ($configuracion->SortUrl($configuracion->idProvincia) == "") { ?>
		<th data-name="idProvincia"><div id="elh_configuracion_idProvincia" class="configuracion_idProvincia"><div class="ewTableHeaderCaption"><?php echo $configuracion->idProvincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idProvincia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idProvincia) ?>',2);"><div id="elh_configuracion_idProvincia" class="configuracion_idProvincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idProvincia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idProvincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idProvincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idPartido->Visible) { // idPartido ?>
	<?php if ($configuracion->SortUrl($configuracion->idPartido) == "") { ?>
		<th data-name="idPartido"><div id="elh_configuracion_idPartido" class="configuracion_idPartido"><div class="ewTableHeaderCaption"><?php echo $configuracion->idPartido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idPartido"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idPartido) ?>',2);"><div id="elh_configuracion_idPartido" class="configuracion_idPartido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idPartido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idPartido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idPartido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idLocalidad->Visible) { // idLocalidad ?>
	<?php if ($configuracion->SortUrl($configuracion->idLocalidad) == "") { ?>
		<th data-name="idLocalidad"><div id="elh_configuracion_idLocalidad" class="configuracion_idLocalidad"><div class="ewTableHeaderCaption"><?php echo $configuracion->idLocalidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idLocalidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idLocalidad) ?>',2);"><div id="elh_configuracion_idLocalidad" class="configuracion_idLocalidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idLocalidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idLocalidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idLocalidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->calle->Visible) { // calle ?>
	<?php if ($configuracion->SortUrl($configuracion->calle) == "") { ?>
		<th data-name="calle"><div id="elh_configuracion_calle" class="configuracion_calle"><div class="ewTableHeaderCaption"><?php echo $configuracion->calle->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="calle"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->calle) ?>',2);"><div id="elh_configuracion_calle" class="configuracion_calle">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->calle->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->calle->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->calle->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->direccion->Visible) { // direccion ?>
	<?php if ($configuracion->SortUrl($configuracion->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_configuracion_direccion" class="configuracion_direccion"><div class="ewTableHeaderCaption"><?php echo $configuracion->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->direccion) ?>',2);"><div id="elh_configuracion_direccion" class="configuracion_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->codigoPostal->Visible) { // codigoPostal ?>
	<?php if ($configuracion->SortUrl($configuracion->codigoPostal) == "") { ?>
		<th data-name="codigoPostal"><div id="elh_configuracion_codigoPostal" class="configuracion_codigoPostal"><div class="ewTableHeaderCaption"><?php echo $configuracion->codigoPostal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigoPostal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->codigoPostal) ?>',2);"><div id="elh_configuracion_codigoPostal" class="configuracion_codigoPostal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->codigoPostal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->codigoPostal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->codigoPostal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->telefono->Visible) { // telefono ?>
	<?php if ($configuracion->SortUrl($configuracion->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_configuracion_telefono" class="configuracion_telefono"><div class="ewTableHeaderCaption"><?php echo $configuracion->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->telefono) ?>',2);"><div id="elh_configuracion_telefono" class="configuracion_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->_email->Visible) { // email ?>
	<?php if ($configuracion->SortUrl($configuracion->_email) == "") { ?>
		<th data-name="_email"><div id="elh_configuracion__email" class="configuracion__email"><div class="ewTableHeaderCaption"><?php echo $configuracion->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->_email) ?>',2);"><div id="elh_configuracion__email" class="configuracion__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->idCondicionIva->Visible) { // idCondicionIva ?>
	<?php if ($configuracion->SortUrl($configuracion->idCondicionIva) == "") { ?>
		<th data-name="idCondicionIva"><div id="elh_configuracion_idCondicionIva" class="configuracion_idCondicionIva"><div class="ewTableHeaderCaption"><?php echo $configuracion->idCondicionIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCondicionIva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->idCondicionIva) ?>',2);"><div id="elh_configuracion_idCondicionIva" class="configuracion_idCondicionIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->idCondicionIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->idCondicionIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->idCondicionIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->logo->Visible) { // logo ?>
	<?php if ($configuracion->SortUrl($configuracion->logo) == "") { ?>
		<th data-name="logo"><div id="elh_configuracion_logo" class="configuracion_logo"><div class="ewTableHeaderCaption"><?php echo $configuracion->logo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="logo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->logo) ?>',2);"><div id="elh_configuracion_logo" class="configuracion_logo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->logo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->logo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->logo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->inicioActividades->Visible) { // inicioActividades ?>
	<?php if ($configuracion->SortUrl($configuracion->inicioActividades) == "") { ?>
		<th data-name="inicioActividades"><div id="elh_configuracion_inicioActividades" class="configuracion_inicioActividades"><div class="ewTableHeaderCaption"><?php echo $configuracion->inicioActividades->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="inicioActividades"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->inicioActividades) ?>',2);"><div id="elh_configuracion_inicioActividades" class="configuracion_inicioActividades">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->inicioActividades->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->inicioActividades->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->inicioActividades->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->ingresosBrutos->Visible) { // ingresosBrutos ?>
	<?php if ($configuracion->SortUrl($configuracion->ingresosBrutos) == "") { ?>
		<th data-name="ingresosBrutos"><div id="elh_configuracion_ingresosBrutos" class="configuracion_ingresosBrutos"><div class="ewTableHeaderCaption"><?php echo $configuracion->ingresosBrutos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ingresosBrutos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->ingresosBrutos) ?>',2);"><div id="elh_configuracion_ingresosBrutos" class="configuracion_ingresosBrutos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->ingresosBrutos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->ingresosBrutos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->ingresosBrutos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->puntoVenta->Visible) { // puntoVenta ?>
	<?php if ($configuracion->SortUrl($configuracion->puntoVenta) == "") { ?>
		<th data-name="puntoVenta"><div id="elh_configuracion_puntoVenta" class="configuracion_puntoVenta"><div class="ewTableHeaderCaption"><?php echo $configuracion->puntoVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="puntoVenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->puntoVenta) ?>',2);"><div id="elh_configuracion_puntoVenta" class="configuracion_puntoVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->puntoVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->puntoVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->puntoVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($configuracion->puntoVentaElectronico->Visible) { // puntoVentaElectronico ?>
	<?php if ($configuracion->SortUrl($configuracion->puntoVentaElectronico) == "") { ?>
		<th data-name="puntoVentaElectronico"><div id="elh_configuracion_puntoVentaElectronico" class="configuracion_puntoVentaElectronico"><div class="ewTableHeaderCaption"><?php echo $configuracion->puntoVentaElectronico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="puntoVentaElectronico"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $configuracion->SortUrl($configuracion->puntoVentaElectronico) ?>',2);"><div id="elh_configuracion_puntoVentaElectronico" class="configuracion_puntoVentaElectronico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $configuracion->puntoVentaElectronico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($configuracion->puntoVentaElectronico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($configuracion->puntoVentaElectronico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$configuracion_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($configuracion->ExportAll && $configuracion->Export <> "") {
	$configuracion_list->StopRec = $configuracion_list->TotalRecs;
} else {

	// Set the last record to display
	if ($configuracion_list->TotalRecs > $configuracion_list->StartRec + $configuracion_list->DisplayRecs - 1)
		$configuracion_list->StopRec = $configuracion_list->StartRec + $configuracion_list->DisplayRecs - 1;
	else
		$configuracion_list->StopRec = $configuracion_list->TotalRecs;
}
$configuracion_list->RecCnt = $configuracion_list->StartRec - 1;
if ($configuracion_list->Recordset && !$configuracion_list->Recordset->EOF) {
	$configuracion_list->Recordset->MoveFirst();
	$bSelectLimit = $configuracion_list->UseSelectLimit;
	if (!$bSelectLimit && $configuracion_list->StartRec > 1)
		$configuracion_list->Recordset->Move($configuracion_list->StartRec - 1);
} elseif (!$configuracion->AllowAddDeleteRow && $configuracion_list->StopRec == 0) {
	$configuracion_list->StopRec = $configuracion->GridAddRowCount;
}

// Initialize aggregate
$configuracion->RowType = EW_ROWTYPE_AGGREGATEINIT;
$configuracion->ResetAttrs();
$configuracion_list->RenderRow();
while ($configuracion_list->RecCnt < $configuracion_list->StopRec) {
	$configuracion_list->RecCnt++;
	if (intval($configuracion_list->RecCnt) >= intval($configuracion_list->StartRec)) {
		$configuracion_list->RowCnt++;

		// Set up key count
		$configuracion_list->KeyCount = $configuracion_list->RowIndex;

		// Init row class and style
		$configuracion->ResetAttrs();
		$configuracion->CssClass = "";
		if ($configuracion->CurrentAction == "gridadd") {
		} else {
			$configuracion_list->LoadRowValues($configuracion_list->Recordset); // Load row values
		}
		$configuracion->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$configuracion->RowAttrs = array_merge($configuracion->RowAttrs, array('data-rowindex'=>$configuracion_list->RowCnt, 'id'=>'r' . $configuracion_list->RowCnt . '_configuracion', 'data-rowtype'=>$configuracion->RowType));

		// Render row
		$configuracion_list->RenderRow();

		// Render list options
		$configuracion_list->RenderListOptions();
?>
	<tr<?php echo $configuracion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$configuracion_list->ListOptions->Render("body", "left", $configuracion_list->RowCnt);
?>
	<?php if ($configuracion->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion"<?php echo $configuracion->denominacion->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_denominacion" class="configuracion_denominacion">
<span<?php echo $configuracion->denominacion->ViewAttributes() ?>>
<?php echo $configuracion->denominacion->ListViewValue() ?></span>
</span>
<a id="<?php echo $configuracion_list->PageObjName . "_row_" . $configuracion_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($configuracion->idTipoDoc->Visible) { // idTipoDoc ?>
		<td data-name="idTipoDoc"<?php echo $configuracion->idTipoDoc->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idTipoDoc" class="configuracion_idTipoDoc">
<span<?php echo $configuracion->idTipoDoc->ViewAttributes() ?>>
<?php echo $configuracion->idTipoDoc->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->documento->Visible) { // documento ?>
		<td data-name="documento"<?php echo $configuracion->documento->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_documento" class="configuracion_documento">
<span<?php echo $configuracion->documento->ViewAttributes() ?>>
<?php echo $configuracion->documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->idPais->Visible) { // idPais ?>
		<td data-name="idPais"<?php echo $configuracion->idPais->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idPais" class="configuracion_idPais">
<span<?php echo $configuracion->idPais->ViewAttributes() ?>>
<?php echo $configuracion->idPais->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->idProvincia->Visible) { // idProvincia ?>
		<td data-name="idProvincia"<?php echo $configuracion->idProvincia->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idProvincia" class="configuracion_idProvincia">
<span<?php echo $configuracion->idProvincia->ViewAttributes() ?>>
<?php echo $configuracion->idProvincia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->idPartido->Visible) { // idPartido ?>
		<td data-name="idPartido"<?php echo $configuracion->idPartido->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idPartido" class="configuracion_idPartido">
<span<?php echo $configuracion->idPartido->ViewAttributes() ?>>
<?php echo $configuracion->idPartido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->idLocalidad->Visible) { // idLocalidad ?>
		<td data-name="idLocalidad"<?php echo $configuracion->idLocalidad->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idLocalidad" class="configuracion_idLocalidad">
<span<?php echo $configuracion->idLocalidad->ViewAttributes() ?>>
<?php echo $configuracion->idLocalidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->calle->Visible) { // calle ?>
		<td data-name="calle"<?php echo $configuracion->calle->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_calle" class="configuracion_calle">
<span<?php echo $configuracion->calle->ViewAttributes() ?>>
<?php echo $configuracion->calle->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $configuracion->direccion->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_direccion" class="configuracion_direccion">
<span<?php echo $configuracion->direccion->ViewAttributes() ?>>
<?php echo $configuracion->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->codigoPostal->Visible) { // codigoPostal ?>
		<td data-name="codigoPostal"<?php echo $configuracion->codigoPostal->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_codigoPostal" class="configuracion_codigoPostal">
<span<?php echo $configuracion->codigoPostal->ViewAttributes() ?>>
<?php echo $configuracion->codigoPostal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $configuracion->telefono->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_telefono" class="configuracion_telefono">
<span<?php echo $configuracion->telefono->ViewAttributes() ?>>
<?php echo $configuracion->telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $configuracion->_email->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion__email" class="configuracion__email">
<span<?php echo $configuracion->_email->ViewAttributes() ?>>
<?php echo $configuracion->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->idCondicionIva->Visible) { // idCondicionIva ?>
		<td data-name="idCondicionIva"<?php echo $configuracion->idCondicionIva->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_idCondicionIva" class="configuracion_idCondicionIva">
<span<?php echo $configuracion->idCondicionIva->ViewAttributes() ?>>
<?php echo $configuracion->idCondicionIva->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->logo->Visible) { // logo ?>
		<td data-name="logo"<?php echo $configuracion->logo->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_logo" class="configuracion_logo">
<span<?php echo $configuracion->logo->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($configuracion->logo, $configuracion->logo->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->inicioActividades->Visible) { // inicioActividades ?>
		<td data-name="inicioActividades"<?php echo $configuracion->inicioActividades->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_inicioActividades" class="configuracion_inicioActividades">
<span<?php echo $configuracion->inicioActividades->ViewAttributes() ?>>
<?php echo $configuracion->inicioActividades->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->ingresosBrutos->Visible) { // ingresosBrutos ?>
		<td data-name="ingresosBrutos"<?php echo $configuracion->ingresosBrutos->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_ingresosBrutos" class="configuracion_ingresosBrutos">
<span<?php echo $configuracion->ingresosBrutos->ViewAttributes() ?>>
<?php echo $configuracion->ingresosBrutos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->puntoVenta->Visible) { // puntoVenta ?>
		<td data-name="puntoVenta"<?php echo $configuracion->puntoVenta->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_puntoVenta" class="configuracion_puntoVenta">
<span<?php echo $configuracion->puntoVenta->ViewAttributes() ?>>
<?php echo $configuracion->puntoVenta->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($configuracion->puntoVentaElectronico->Visible) { // puntoVentaElectronico ?>
		<td data-name="puntoVentaElectronico"<?php echo $configuracion->puntoVentaElectronico->CellAttributes() ?>>
<span id="el<?php echo $configuracion_list->RowCnt ?>_configuracion_puntoVentaElectronico" class="configuracion_puntoVentaElectronico">
<span<?php echo $configuracion->puntoVentaElectronico->ViewAttributes() ?>>
<?php echo $configuracion->puntoVentaElectronico->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$configuracion_list->ListOptions->Render("body", "right", $configuracion_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($configuracion->CurrentAction <> "gridadd")
		$configuracion_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($configuracion->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($configuracion_list->Recordset)
	$configuracion_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($configuracion_list->TotalRecs == 0 && $configuracion->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($configuracion_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($configuracion->Export == "") { ?>
<script type="text/javascript">
fconfiguracionlist.Init();
</script>
<?php } ?>
<?php
$configuracion_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($configuracion->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$configuracion_list->Page_Terminate();
?>
