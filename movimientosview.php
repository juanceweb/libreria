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

$movimientos_view = NULL; // Initialize page object first

class cmovimientos_view extends cmovimientos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos';

	// Page object name
	var $PageObjName = 'movimientos_view';

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("movimientoslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
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
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}

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
		$this->cae->SetVisibility();
		$this->vtoCae->SetVisibility();
		$this->idEstado->SetVisibility();
		$this->idUsuarioAlta->SetVisibility();
		$this->fechaAlta->SetVisibility();
		$this->idUsuarioModificacion->SetVisibility();
		$this->fechaModificacion->SetVisibility();
		$this->contable->SetVisibility();
		$this->archivo->SetVisibility();
		$this->valorDolar->SetVisibility();
		$this->comentarios->SetVisibility();
		$this->articulosAsociados->SetVisibility();
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

		// Create Token
		$this->CreateToken();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$sReturnUrl = "movimientoslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "movimientoslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "movimientoslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

		// Convert decimal values if posted back
		if ($this->valorDolar->FormValue == $this->valorDolar->CurrentValue && is_numeric(ew_StrToFloat($this->valorDolar->CurrentValue)))
			$this->valorDolar->CurrentValue = ew_StrToFloat($this->valorDolar->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// nroComprobanteCompleto
		// tipoMovimiento
		// fecha
		// idSociedad
		// codTercero
		// idTercero
		// idComprobante
		// importeTotal
		// importeIva
		// importeNeto
		// importeCancelado
		// nombreTercero
		// idDocTercero
		// nroDocTercero
		// ptoVenta
		// nroComprobante
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
		// movimientosAsociados
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

		// cae
		$this->cae->ViewValue = $this->cae->CurrentValue;
		$this->cae->ViewCustomAttributes = "";

		// vtoCae
		$this->vtoCae->ViewValue = $this->vtoCae->CurrentValue;
		$this->vtoCae->ViewValue = ew_FormatDateTime($this->vtoCae->ViewValue, 0);
		$this->vtoCae->ViewCustomAttributes = "";

		// idEstado
		if (strval($this->idEstado->CurrentValue) <> "") {
			$this->idEstado->ViewValue = $this->idEstado->OptionCaption($this->idEstado->CurrentValue);
		} else {
			$this->idEstado->ViewValue = NULL;
		}
		$this->idEstado->ViewCustomAttributes = "";

		// idUsuarioAlta
		$this->idUsuarioAlta->ViewValue = $this->idUsuarioAlta->CurrentValue;
		$this->idUsuarioAlta->ViewCustomAttributes = "";

		// fechaAlta
		$this->fechaAlta->ViewValue = $this->fechaAlta->CurrentValue;
		$this->fechaAlta->ViewValue = ew_FormatDateTime($this->fechaAlta->ViewValue, 0);
		$this->fechaAlta->ViewCustomAttributes = "";

		// idUsuarioModificacion
		$this->idUsuarioModificacion->ViewValue = $this->idUsuarioModificacion->CurrentValue;
		$this->idUsuarioModificacion->ViewCustomAttributes = "";

		// fechaModificacion
		$this->fechaModificacion->ViewValue = $this->fechaModificacion->CurrentValue;
		$this->fechaModificacion->ViewValue = ew_FormatDateTime($this->fechaModificacion->ViewValue, 0);
		$this->fechaModificacion->ViewCustomAttributes = "";

		// contable
		$this->contable->ViewValue = $this->contable->CurrentValue;
		$this->contable->ViewCustomAttributes = "";

		// archivo
		$this->archivo->ViewValue = $this->archivo->CurrentValue;
		$this->archivo->ViewCustomAttributes = "";

		// valorDolar
		$this->valorDolar->ViewValue = $this->valorDolar->CurrentValue;
		$this->valorDolar->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

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

			// cae
			$this->cae->LinkCustomAttributes = "";
			$this->cae->HrefValue = "";
			$this->cae->TooltipValue = "";

			// vtoCae
			$this->vtoCae->LinkCustomAttributes = "";
			$this->vtoCae->HrefValue = "";
			$this->vtoCae->TooltipValue = "";

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
			$this->idEstado->TooltipValue = "";

			// idUsuarioAlta
			$this->idUsuarioAlta->LinkCustomAttributes = "";
			$this->idUsuarioAlta->HrefValue = "";
			$this->idUsuarioAlta->TooltipValue = "";

			// fechaAlta
			$this->fechaAlta->LinkCustomAttributes = "";
			$this->fechaAlta->HrefValue = "";
			$this->fechaAlta->TooltipValue = "";

			// idUsuarioModificacion
			$this->idUsuarioModificacion->LinkCustomAttributes = "";
			$this->idUsuarioModificacion->HrefValue = "";
			$this->idUsuarioModificacion->TooltipValue = "";

			// fechaModificacion
			$this->fechaModificacion->LinkCustomAttributes = "";
			$this->fechaModificacion->HrefValue = "";
			$this->fechaModificacion->TooltipValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";
			$this->contable->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->HrefValue = "";
			$this->archivo->TooltipValue = "";

			// valorDolar
			$this->valorDolar->LinkCustomAttributes = "";
			$this->valorDolar->HrefValue = "";
			$this->valorDolar->TooltipValue = "";

			// comentarios
			$this->comentarios->LinkCustomAttributes = "";
			$this->comentarios->HrefValue = "";
			$this->comentarios->TooltipValue = "";

			// articulosAsociados
			$this->articulosAsociados->LinkCustomAttributes = "";
			$this->articulosAsociados->HrefValue = "";
			$this->articulosAsociados->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_movimientos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_movimientos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmovimientosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

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
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($movimientos_view)) $movimientos_view = new cmovimientos_view();

// Page init
$movimientos_view->Page_Init();

// Page main
$movimientos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($movimientos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fmovimientosview = new ew_Form("fmovimientosview", "view");

// Form_CustomValidate event
fmovimientosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientosview.ValidateRequired = true;
<?php } else { ?>
fmovimientosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmovimientosview.Lists["x_tipoMovimiento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosview.Lists["x_tipoMovimiento"].Options = <?php echo json_encode($movimientos->tipoMovimiento->Options()) ?>;
fmovimientosview.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fmovimientosview.Lists["x_idComprobante"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fmovimientosview.Lists["x_idEstado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fmovimientosview.Lists["x_idEstado"].Options = <?php echo json_encode($movimientos->idEstado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($movimientos->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$movimientos_view->IsModal) { ?>
<?php if ($movimientos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $movimientos_view->ExportOptions->Render("body") ?>
<?php
	foreach ($movimientos_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$movimientos_view->IsModal) { ?>
<?php if ($movimientos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $movimientos_view->ShowPageHeader(); ?>
<?php
$movimientos_view->ShowMessage();
?>
<form name="fmovimientosview" id="fmovimientosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos">
<?php if ($movimientos_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($movimientos->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_movimientos_id"><?php echo $movimientos->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $movimientos->id->CellAttributes() ?>>
<span id="el_movimientos_id">
<span<?php echo $movimientos->id->ViewAttributes() ?>>
<?php echo $movimientos->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->nroComprobanteCompleto->Visible) { // nroComprobanteCompleto ?>
	<tr id="r_nroComprobanteCompleto">
		<td><span id="elh_movimientos_nroComprobanteCompleto"><?php echo $movimientos->nroComprobanteCompleto->FldCaption() ?></span></td>
		<td data-name="nroComprobanteCompleto"<?php echo $movimientos->nroComprobanteCompleto->CellAttributes() ?>>
<span id="el_movimientos_nroComprobanteCompleto">
<span<?php echo $movimientos->nroComprobanteCompleto->ViewAttributes() ?>>
<?php echo $movimientos->nroComprobanteCompleto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->tipoMovimiento->Visible) { // tipoMovimiento ?>
	<tr id="r_tipoMovimiento">
		<td><span id="elh_movimientos_tipoMovimiento"><?php echo $movimientos->tipoMovimiento->FldCaption() ?></span></td>
		<td data-name="tipoMovimiento"<?php echo $movimientos->tipoMovimiento->CellAttributes() ?>>
<span id="el_movimientos_tipoMovimiento">
<span<?php echo $movimientos->tipoMovimiento->ViewAttributes() ?>>
<?php echo $movimientos->tipoMovimiento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_movimientos_fecha"><?php echo $movimientos->fecha->FldCaption() ?></span></td>
		<td data-name="fecha"<?php echo $movimientos->fecha->CellAttributes() ?>>
<span id="el_movimientos_fecha">
<span<?php echo $movimientos->fecha->ViewAttributes() ?>>
<?php echo $movimientos->fecha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->codTercero->Visible) { // codTercero ?>
	<tr id="r_codTercero">
		<td><span id="elh_movimientos_codTercero"><?php echo $movimientos->codTercero->FldCaption() ?></span></td>
		<td data-name="codTercero"<?php echo $movimientos->codTercero->CellAttributes() ?>>
<span id="el_movimientos_codTercero">
<span<?php echo $movimientos->codTercero->ViewAttributes() ?>>
<?php echo $movimientos->codTercero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->idTercero->Visible) { // idTercero ?>
	<tr id="r_idTercero">
		<td><span id="elh_movimientos_idTercero"><?php echo $movimientos->idTercero->FldCaption() ?></span></td>
		<td data-name="idTercero"<?php echo $movimientos->idTercero->CellAttributes() ?>>
<span id="el_movimientos_idTercero">
<span<?php echo $movimientos->idTercero->ViewAttributes() ?>>
<?php echo $movimientos->idTercero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->idComprobante->Visible) { // idComprobante ?>
	<tr id="r_idComprobante">
		<td><span id="elh_movimientos_idComprobante"><?php echo $movimientos->idComprobante->FldCaption() ?></span></td>
		<td data-name="idComprobante"<?php echo $movimientos->idComprobante->CellAttributes() ?>>
<span id="el_movimientos_idComprobante">
<span<?php echo $movimientos->idComprobante->ViewAttributes() ?>>
<?php echo $movimientos->idComprobante->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->importeTotal->Visible) { // importeTotal ?>
	<tr id="r_importeTotal">
		<td><span id="elh_movimientos_importeTotal"><?php echo $movimientos->importeTotal->FldCaption() ?></span></td>
		<td data-name="importeTotal"<?php echo $movimientos->importeTotal->CellAttributes() ?>>
<span id="el_movimientos_importeTotal">
<span<?php echo $movimientos->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos->importeTotal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->importeIva->Visible) { // importeIva ?>
	<tr id="r_importeIva">
		<td><span id="elh_movimientos_importeIva"><?php echo $movimientos->importeIva->FldCaption() ?></span></td>
		<td data-name="importeIva"<?php echo $movimientos->importeIva->CellAttributes() ?>>
<span id="el_movimientos_importeIva">
<span<?php echo $movimientos->importeIva->ViewAttributes() ?>>
<?php echo $movimientos->importeIva->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->importeNeto->Visible) { // importeNeto ?>
	<tr id="r_importeNeto">
		<td><span id="elh_movimientos_importeNeto"><?php echo $movimientos->importeNeto->FldCaption() ?></span></td>
		<td data-name="importeNeto"<?php echo $movimientos->importeNeto->CellAttributes() ?>>
<span id="el_movimientos_importeNeto">
<span<?php echo $movimientos->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos->importeNeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->importeCancelado->Visible) { // importeCancelado ?>
	<tr id="r_importeCancelado">
		<td><span id="elh_movimientos_importeCancelado"><?php echo $movimientos->importeCancelado->FldCaption() ?></span></td>
		<td data-name="importeCancelado"<?php echo $movimientos->importeCancelado->CellAttributes() ?>>
<span id="el_movimientos_importeCancelado">
<span<?php echo $movimientos->importeCancelado->ViewAttributes() ?>>
<?php echo $movimientos->importeCancelado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->cae->Visible) { // cae ?>
	<tr id="r_cae">
		<td><span id="elh_movimientos_cae"><?php echo $movimientos->cae->FldCaption() ?></span></td>
		<td data-name="cae"<?php echo $movimientos->cae->CellAttributes() ?>>
<span id="el_movimientos_cae">
<span<?php echo $movimientos->cae->ViewAttributes() ?>>
<?php echo $movimientos->cae->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->vtoCae->Visible) { // vtoCae ?>
	<tr id="r_vtoCae">
		<td><span id="elh_movimientos_vtoCae"><?php echo $movimientos->vtoCae->FldCaption() ?></span></td>
		<td data-name="vtoCae"<?php echo $movimientos->vtoCae->CellAttributes() ?>>
<span id="el_movimientos_vtoCae">
<span<?php echo $movimientos->vtoCae->ViewAttributes() ?>>
<?php echo $movimientos->vtoCae->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->idEstado->Visible) { // idEstado ?>
	<tr id="r_idEstado">
		<td><span id="elh_movimientos_idEstado"><?php echo $movimientos->idEstado->FldCaption() ?></span></td>
		<td data-name="idEstado"<?php echo $movimientos->idEstado->CellAttributes() ?>>
<span id="el_movimientos_idEstado">
<span<?php echo $movimientos->idEstado->ViewAttributes() ?>>
<?php echo $movimientos->idEstado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->idUsuarioAlta->Visible) { // idUsuarioAlta ?>
	<tr id="r_idUsuarioAlta">
		<td><span id="elh_movimientos_idUsuarioAlta"><?php echo $movimientos->idUsuarioAlta->FldCaption() ?></span></td>
		<td data-name="idUsuarioAlta"<?php echo $movimientos->idUsuarioAlta->CellAttributes() ?>>
<span id="el_movimientos_idUsuarioAlta">
<span<?php echo $movimientos->idUsuarioAlta->ViewAttributes() ?>>
<?php echo $movimientos->idUsuarioAlta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->fechaAlta->Visible) { // fechaAlta ?>
	<tr id="r_fechaAlta">
		<td><span id="elh_movimientos_fechaAlta"><?php echo $movimientos->fechaAlta->FldCaption() ?></span></td>
		<td data-name="fechaAlta"<?php echo $movimientos->fechaAlta->CellAttributes() ?>>
<span id="el_movimientos_fechaAlta">
<span<?php echo $movimientos->fechaAlta->ViewAttributes() ?>>
<?php echo $movimientos->fechaAlta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->idUsuarioModificacion->Visible) { // idUsuarioModificacion ?>
	<tr id="r_idUsuarioModificacion">
		<td><span id="elh_movimientos_idUsuarioModificacion"><?php echo $movimientos->idUsuarioModificacion->FldCaption() ?></span></td>
		<td data-name="idUsuarioModificacion"<?php echo $movimientos->idUsuarioModificacion->CellAttributes() ?>>
<span id="el_movimientos_idUsuarioModificacion">
<span<?php echo $movimientos->idUsuarioModificacion->ViewAttributes() ?>>
<?php echo $movimientos->idUsuarioModificacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->fechaModificacion->Visible) { // fechaModificacion ?>
	<tr id="r_fechaModificacion">
		<td><span id="elh_movimientos_fechaModificacion"><?php echo $movimientos->fechaModificacion->FldCaption() ?></span></td>
		<td data-name="fechaModificacion"<?php echo $movimientos->fechaModificacion->CellAttributes() ?>>
<span id="el_movimientos_fechaModificacion">
<span<?php echo $movimientos->fechaModificacion->ViewAttributes() ?>>
<?php echo $movimientos->fechaModificacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->contable->Visible) { // contable ?>
	<tr id="r_contable">
		<td><span id="elh_movimientos_contable"><?php echo $movimientos->contable->FldCaption() ?></span></td>
		<td data-name="contable"<?php echo $movimientos->contable->CellAttributes() ?>>
<span id="el_movimientos_contable">
<span<?php echo $movimientos->contable->ViewAttributes() ?>>
<?php echo $movimientos->contable->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->archivo->Visible) { // archivo ?>
	<tr id="r_archivo">
		<td><span id="elh_movimientos_archivo"><?php echo $movimientos->archivo->FldCaption() ?></span></td>
		<td data-name="archivo"<?php echo $movimientos->archivo->CellAttributes() ?>>
<span id="el_movimientos_archivo">
<span<?php echo $movimientos->archivo->ViewAttributes() ?>>
<?php echo $movimientos->archivo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->valorDolar->Visible) { // valorDolar ?>
	<tr id="r_valorDolar">
		<td><span id="elh_movimientos_valorDolar"><?php echo $movimientos->valorDolar->FldCaption() ?></span></td>
		<td data-name="valorDolar"<?php echo $movimientos->valorDolar->CellAttributes() ?>>
<span id="el_movimientos_valorDolar">
<span<?php echo $movimientos->valorDolar->ViewAttributes() ?>>
<?php echo $movimientos->valorDolar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->comentarios->Visible) { // comentarios ?>
	<tr id="r_comentarios">
		<td><span id="elh_movimientos_comentarios"><?php echo $movimientos->comentarios->FldCaption() ?></span></td>
		<td data-name="comentarios"<?php echo $movimientos->comentarios->CellAttributes() ?>>
<span id="el_movimientos_comentarios">
<span<?php echo $movimientos->comentarios->ViewAttributes() ?>>
<?php echo $movimientos->comentarios->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->articulosAsociados->Visible) { // articulosAsociados ?>
	<tr id="r_articulosAsociados">
		<td><span id="elh_movimientos_articulosAsociados"><?php echo $movimientos->articulosAsociados->FldCaption() ?></span></td>
		<td data-name="articulosAsociados"<?php echo $movimientos->articulosAsociados->CellAttributes() ?>>
<span id="el_movimientos_articulosAsociados">
<span<?php echo $movimientos->articulosAsociados->ViewAttributes() ?>>
<?php echo $movimientos->articulosAsociados->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->movimientosAsociados->Visible) { // movimientosAsociados ?>
	<tr id="r_movimientosAsociados">
		<td><span id="elh_movimientos_movimientosAsociados"><?php echo $movimientos->movimientosAsociados->FldCaption() ?></span></td>
		<td data-name="movimientosAsociados"<?php echo $movimientos->movimientosAsociados->CellAttributes() ?>>
<span id="el_movimientos_movimientosAsociados">
<span<?php echo $movimientos->movimientosAsociados->ViewAttributes() ?>>
<?php echo $movimientos->movimientosAsociados->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->condicionVenta->Visible) { // condicionVenta ?>
	<tr id="r_condicionVenta">
		<td><span id="elh_movimientos_condicionVenta"><?php echo $movimientos->condicionVenta->FldCaption() ?></span></td>
		<td data-name="condicionVenta"<?php echo $movimientos->condicionVenta->CellAttributes() ?>>
<span id="el_movimientos_condicionVenta">
<span<?php echo $movimientos->condicionVenta->ViewAttributes() ?>>
<?php echo $movimientos->condicionVenta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($movimientos->vigencia->Visible) { // vigencia ?>
	<tr id="r_vigencia">
		<td><span id="elh_movimientos_vigencia"><?php echo $movimientos->vigencia->FldCaption() ?></span></td>
		<td data-name="vigencia"<?php echo $movimientos->vigencia->CellAttributes() ?>>
<span id="el_movimientos_vigencia">
<span<?php echo $movimientos->vigencia->ViewAttributes() ?>>
<?php echo $movimientos->vigencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($movimientos->Export == "") { ?>
<script type="text/javascript">
fmovimientosview.Init();
</script>
<?php } ?>
<?php
$movimientos_view->ShowPageFooter();
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
$movimientos_view->Page_Terminate();
?>
