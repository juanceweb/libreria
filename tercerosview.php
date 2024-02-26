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

$terceros_view = NULL; // Initialize page object first

class cterceros_view extends cterceros {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros';

	// Page object name
	var $PageObjName = 'terceros_view';

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
				$this->Page_Terminate(ew_GetUrl("terceroslist.php"));
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
		$this->idTipoTercero->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->razonSocial->SetVisibility();
		$this->direccion->SetVisibility();
		$this->domicilioFiscal->SetVisibility();
		$this->idPaisFiscal->SetVisibility();
		$this->idProvinciaFiscal->SetVisibility();
		$this->idPartidoFiscal->SetVisibility();
		$this->idLocalidadFiscal->SetVisibility();
		$this->calleFiscal->SetVisibility();
		$this->direccionFiscal->SetVisibility();
		$this->documento->SetVisibility();
		$this->condicionIva->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->idVendedor->SetVisibility();
		$this->idCobrador->SetVisibility();
		$this->comision->SetVisibility();
		$this->idListaPrecios->SetVisibility();
		$this->dtoCliente->SetVisibility();
		$this->dto1->SetVisibility();
		$this->dto2->SetVisibility();
		$this->dto3->SetVisibility();
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
				$sReturnUrl = "terceroslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "terceroslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "terceroslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_terceros2Dmedios2Dcontactos"
		$item = &$option->Add("detail_terceros2Dmedios2Dcontactos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("terceros2Dmedios2Dcontactos", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("terceros2Dmedios2Dcontactoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"] && $GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'terceros-medios-contactos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=terceros2Dmedios2Dcontactos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "terceros2Dmedios2Dcontactos";
		}
		if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"] && $GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'terceros-medios-contactos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=terceros2Dmedios2Dcontactos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "terceros2Dmedios2Dcontactos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'terceros-medios-contactos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "terceros2Dmedios2Dcontactos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_articulos2Dterceros2Ddescuentos"
		$item = &$option->Add("detail_articulos2Dterceros2Ddescuentos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("articulos2Dterceros2Ddescuentos", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"] && $GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'articulos-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "articulos2Dterceros2Ddescuentos";
		}
		if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"] && $GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "articulos2Dterceros2Ddescuentos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-terceros-descuentos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dterceros2Ddescuentos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_articulos2Dproveedores"
		$item = &$option->Add("detail_articulos2Dproveedores");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("articulos2Dproveedores", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("articulos2Dproveedoreslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["articulos2Dproveedores_grid"] && $GLOBALS["articulos2Dproveedores_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'articulos-proveedores')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "articulos2Dproveedores";
		}
		if ($GLOBALS["articulos2Dproveedores_grid"] && $GLOBALS["articulos2Dproveedores_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'articulos-proveedores')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=articulos2Dproveedores")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "articulos2Dproveedores";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'articulos-proveedores');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "articulos2Dproveedores";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_subcategoria2Dterceros2Ddescuentos"
		$item = &$option->Add("detail_subcategoria2Dterceros2Ddescuentos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("subcategoria2Dterceros2Ddescuentos", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("subcategoria2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] && $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'subcategoria-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=subcategoria2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "subcategoria2Dterceros2Ddescuentos";
		}
		if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] && $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'subcategoria-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=subcategoria2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "subcategoria2Dterceros2Ddescuentos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'subcategoria-terceros-descuentos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "subcategoria2Dterceros2Ddescuentos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_categorias2Dterceros2Ddescuentos"
		$item = &$option->Add("detail_categorias2Dterceros2Ddescuentos");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("categorias2Dterceros2Ddescuentos", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("categorias2Dterceros2Ddescuentoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"] && $GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'categorias-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=categorias2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "categorias2Dterceros2Ddescuentos";
		}
		if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"] && $GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'categorias-terceros-descuentos')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=categorias2Dterceros2Ddescuentos")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "categorias2Dterceros2Ddescuentos";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'categorias-terceros-descuentos');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "categorias2Dterceros2Ddescuentos";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_sucursales"
		$item = &$option->Add("detail_sucursales");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("sucursales", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("sucursaleslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["sucursales_grid"] && $GLOBALS["sucursales_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'sucursales')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=sucursales")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "sucursales";
		}
		if ($GLOBALS["sucursales_grid"] && $GLOBALS["sucursales_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'sucursales')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=sucursales")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "sucursales";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'sucursales');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "sucursales";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_descuentosasociados"
		$item = &$option->Add("detail_descuentosasociados");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("descuentosasociados", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("descuentosasociadoslist.php?" . EW_TABLE_SHOW_MASTER . "=terceros&fk_id=" . urlencode(strval($this->id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["descuentosasociados_grid"] && $GLOBALS["descuentosasociados_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'descuentosasociados')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=descuentosasociados")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "descuentosasociados";
		}
		if ($GLOBALS["descuentosasociados_grid"] && $GLOBALS["descuentosasociados_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'descuentosasociados')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=descuentosasociados")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "descuentosasociados";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'descuentosasociados');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "descuentosasociados";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		if ($this->comision->FormValue == $this->comision->CurrentValue && is_numeric(ew_StrToFloat($this->comision->CurrentValue)))
			$this->comision->CurrentValue = ew_StrToFloat($this->comision->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dtoCliente->FormValue == $this->dtoCliente->CurrentValue && is_numeric(ew_StrToFloat($this->dtoCliente->CurrentValue)))
			$this->dtoCliente->CurrentValue = ew_StrToFloat($this->dtoCliente->CurrentValue);

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
		if ($this->limiteDescubierto->FormValue == $this->limiteDescubierto->CurrentValue && is_numeric(ew_StrToFloat($this->limiteDescubierto->CurrentValue)))
			$this->limiteDescubierto->CurrentValue = ew_StrToFloat($this->limiteDescubierto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
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

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

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

			// razonSocial
			$this->razonSocial->LinkCustomAttributes = "";
			$this->razonSocial->HrefValue = "";
			$this->razonSocial->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// domicilioFiscal
			$this->domicilioFiscal->LinkCustomAttributes = "";
			$this->domicilioFiscal->HrefValue = "";
			$this->domicilioFiscal->TooltipValue = "";

			// idPaisFiscal
			$this->idPaisFiscal->LinkCustomAttributes = "";
			$this->idPaisFiscal->HrefValue = "";
			$this->idPaisFiscal->TooltipValue = "";

			// idProvinciaFiscal
			$this->idProvinciaFiscal->LinkCustomAttributes = "";
			$this->idProvinciaFiscal->HrefValue = "";
			$this->idProvinciaFiscal->TooltipValue = "";

			// idPartidoFiscal
			$this->idPartidoFiscal->LinkCustomAttributes = "";
			$this->idPartidoFiscal->HrefValue = "";
			$this->idPartidoFiscal->TooltipValue = "";

			// idLocalidadFiscal
			$this->idLocalidadFiscal->LinkCustomAttributes = "";
			$this->idLocalidadFiscal->HrefValue = "";
			$this->idLocalidadFiscal->TooltipValue = "";

			// calleFiscal
			$this->calleFiscal->LinkCustomAttributes = "";
			$this->calleFiscal->HrefValue = "";
			$this->calleFiscal->TooltipValue = "";

			// direccionFiscal
			$this->direccionFiscal->LinkCustomAttributes = "";
			$this->direccionFiscal->HrefValue = "";
			$this->direccionFiscal->TooltipValue = "";

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";
			$this->documento->TooltipValue = "";

			// condicionIva
			$this->condicionIva->LinkCustomAttributes = "";
			$this->condicionIva->HrefValue = "";
			$this->condicionIva->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// idVendedor
			$this->idVendedor->LinkCustomAttributes = "";
			$this->idVendedor->HrefValue = "";
			$this->idVendedor->TooltipValue = "";

			// idCobrador
			$this->idCobrador->LinkCustomAttributes = "";
			$this->idCobrador->HrefValue = "";
			$this->idCobrador->TooltipValue = "";

			// comision
			$this->comision->LinkCustomAttributes = "";
			$this->comision->HrefValue = "";
			$this->comision->TooltipValue = "";

			// idListaPrecios
			$this->idListaPrecios->LinkCustomAttributes = "";
			$this->idListaPrecios->HrefValue = "";
			$this->idListaPrecios->TooltipValue = "";

			// dtoCliente
			$this->dtoCliente->LinkCustomAttributes = "";
			$this->dtoCliente->HrefValue = "";
			$this->dtoCliente->TooltipValue = "";

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

			// limiteDescubierto
			$this->limiteDescubierto->LinkCustomAttributes = "";
			$this->limiteDescubierto->HrefValue = "";
			$this->limiteDescubierto->TooltipValue = "";

			// codigoPostal
			$this->codigoPostal->LinkCustomAttributes = "";
			$this->codigoPostal->HrefValue = "";
			$this->codigoPostal->TooltipValue = "";

			// codigoPostalFiscal
			$this->codigoPostalFiscal->LinkCustomAttributes = "";
			$this->codigoPostalFiscal->HrefValue = "";
			$this->codigoPostalFiscal->TooltipValue = "";

			// condicionVenta
			$this->condicionVenta->LinkCustomAttributes = "";
			$this->condicionVenta->HrefValue = "";
			$this->condicionVenta->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_terceros\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_terceros',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ftercerosview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (terceros2Dmedios2Dcontactos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("terceros2Dmedios2Dcontactos", explode(",", $this->getCurrentDetailTable()))) {
			global $terceros2Dmedios2Dcontactos;
			if (!isset($terceros2Dmedios2Dcontactos)) $terceros2Dmedios2Dcontactos = new cterceros2Dmedios2Dcontactos;
			$rsdetail = $terceros2Dmedios2Dcontactos->LoadRs($terceros2Dmedios2Dcontactos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$terceros2Dmedios2Dcontactos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (articulos2Dterceros2Ddescuentos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("articulos2Dterceros2Ddescuentos", explode(",", $this->getCurrentDetailTable()))) {
			global $articulos2Dterceros2Ddescuentos;
			if (!isset($articulos2Dterceros2Ddescuentos)) $articulos2Dterceros2Ddescuentos = new carticulos2Dterceros2Ddescuentos;
			$rsdetail = $articulos2Dterceros2Ddescuentos->LoadRs($articulos2Dterceros2Ddescuentos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$articulos2Dterceros2Ddescuentos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (articulos2Dproveedores)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("articulos2Dproveedores", explode(",", $this->getCurrentDetailTable()))) {
			global $articulos2Dproveedores;
			if (!isset($articulos2Dproveedores)) $articulos2Dproveedores = new carticulos2Dproveedores;
			$rsdetail = $articulos2Dproveedores->LoadRs($articulos2Dproveedores->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$articulos2Dproveedores->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (subcategoria2Dterceros2Ddescuentos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("subcategoria2Dterceros2Ddescuentos", explode(",", $this->getCurrentDetailTable()))) {
			global $subcategoria2Dterceros2Ddescuentos;
			if (!isset($subcategoria2Dterceros2Ddescuentos)) $subcategoria2Dterceros2Ddescuentos = new csubcategoria2Dterceros2Ddescuentos;
			$rsdetail = $subcategoria2Dterceros2Ddescuentos->LoadRs($subcategoria2Dterceros2Ddescuentos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$subcategoria2Dterceros2Ddescuentos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (categorias2Dterceros2Ddescuentos)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("categorias2Dterceros2Ddescuentos", explode(",", $this->getCurrentDetailTable()))) {
			global $categorias2Dterceros2Ddescuentos;
			if (!isset($categorias2Dterceros2Ddescuentos)) $categorias2Dterceros2Ddescuentos = new ccategorias2Dterceros2Ddescuentos;
			$rsdetail = $categorias2Dterceros2Ddescuentos->LoadRs($categorias2Dterceros2Ddescuentos->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$categorias2Dterceros2Ddescuentos->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (sucursales)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("sucursales", explode(",", $this->getCurrentDetailTable()))) {
			global $sucursales;
			if (!isset($sucursales)) $sucursales = new csucursales;
			$rsdetail = $sucursales->LoadRs($sucursales->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$sucursales->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (descuentosasociados)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("descuentosasociados", explode(",", $this->getCurrentDetailTable()))) {
			global $descuentosasociados;
			if (!isset($descuentosasociados)) $descuentosasociados = new cdescuentosasociados;
			$rsdetail = $descuentosasociados->LoadRs($descuentosasociados->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$descuentosasociados->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("terceros2Dmedios2Dcontactos", $DetailTblVar)) {
				if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"]))
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid;
				if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailView) {
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->setStartRecordNumber(1);
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->idTercero->setSessionValue($GLOBALS["terceros2Dmedios2Dcontactos_grid"]->idTercero->CurrentValue);
				}
			}
			if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar)) {
				if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]))
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid;
				if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailView) {
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->setStartRecordNumber(1);
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idTercero->setSessionValue($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue);
				}
			}
			if (in_array("articulos2Dproveedores", $DetailTblVar)) {
				if (!isset($GLOBALS["articulos2Dproveedores_grid"]))
					$GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;
				if ($GLOBALS["articulos2Dproveedores_grid"]->DetailView) {
					$GLOBALS["articulos2Dproveedores_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["articulos2Dproveedores_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["articulos2Dproveedores_grid"]->setStartRecordNumber(1);
					$GLOBALS["articulos2Dproveedores_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["articulos2Dproveedores_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["articulos2Dproveedores_grid"]->idTercero->setSessionValue($GLOBALS["articulos2Dproveedores_grid"]->idTercero->CurrentValue);
				}
			}
			if (in_array("subcategoria2Dterceros2Ddescuentos", $DetailTblVar)) {
				if (!isset($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]))
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] = new csubcategoria2Dterceros2Ddescuentos_grid;
				if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailView) {
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->setStartRecordNumber(1);
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->idTercero->setSessionValue($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue);
				}
			}
			if (in_array("categorias2Dterceros2Ddescuentos", $DetailTblVar)) {
				if (!isset($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]))
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"] = new ccategorias2Dterceros2Ddescuentos_grid;
				if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailView) {
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->setStartRecordNumber(1);
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->idTercero->setSessionValue($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->idTercero->CurrentValue);
				}
			}
			if (in_array("sucursales", $DetailTblVar)) {
				if (!isset($GLOBALS["sucursales_grid"]))
					$GLOBALS["sucursales_grid"] = new csucursales_grid;
				if ($GLOBALS["sucursales_grid"]->DetailView) {
					$GLOBALS["sucursales_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["sucursales_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["sucursales_grid"]->setStartRecordNumber(1);
					$GLOBALS["sucursales_grid"]->idTerceroPadre->FldIsDetailKey = TRUE;
					$GLOBALS["sucursales_grid"]->idTerceroPadre->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["sucursales_grid"]->idTerceroPadre->setSessionValue($GLOBALS["sucursales_grid"]->idTerceroPadre->CurrentValue);
				}
			}
			if (in_array("descuentosasociados", $DetailTblVar)) {
				if (!isset($GLOBALS["descuentosasociados_grid"]))
					$GLOBALS["descuentosasociados_grid"] = new cdescuentosasociados_grid;
				if ($GLOBALS["descuentosasociados_grid"]->DetailView) {
					$GLOBALS["descuentosasociados_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["descuentosasociados_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["descuentosasociados_grid"]->setStartRecordNumber(1);
					$GLOBALS["descuentosasociados_grid"]->idTercero->FldIsDetailKey = TRUE;
					$GLOBALS["descuentosasociados_grid"]->idTercero->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["descuentosasociados_grid"]->idTercero->setSessionValue($GLOBALS["descuentosasociados_grid"]->idTercero->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("terceroslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($terceros_view)) $terceros_view = new cterceros_view();

// Page init
$terceros_view->Page_Init();

// Page main
$terceros_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftercerosview = new ew_Form("ftercerosview", "view");

// Form_CustomValidate event
ftercerosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftercerosview.ValidateRequired = true;
<?php } else { ?>
ftercerosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftercerosview.Lists["x_idTipoTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dterceros"};
ftercerosview.Lists["x_domicilioFiscal"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftercerosview.Lists["x_domicilioFiscal"].Options = <?php echo json_encode($terceros->domicilioFiscal->Options()) ?>;
ftercerosview.Lists["x_idPaisFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":["x_idProvinciaFiscal"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"paises"};
ftercerosview.Lists["x_idProvinciaFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":["x_idPartidoFiscal"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"provincias"};
ftercerosview.Lists["x_idPartidoFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":["x_idLocalidadFiscal"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"partidos"};
ftercerosview.Lists["x_idLocalidadFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"localidades"};
ftercerosview.Lists["x_condicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};
ftercerosview.Lists["x_idVendedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
ftercerosview.Lists["x_idCobrador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
ftercerosview.Lists["x_idListaPrecios"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_descuento","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"lista2Dprecios"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($terceros->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$terceros_view->IsModal) { ?>
<?php if ($terceros->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $terceros_view->ExportOptions->Render("body") ?>
<?php
	foreach ($terceros_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$terceros_view->IsModal) { ?>
<?php if ($terceros->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $terceros_view->ShowPageHeader(); ?>
<?php
$terceros_view->ShowMessage();
?>
<form name="ftercerosview" id="ftercerosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros">
<?php if ($terceros_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
	<tr id="r_idTipoTercero">
		<td><span id="elh_terceros_idTipoTercero"><?php echo $terceros->idTipoTercero->FldCaption() ?></span></td>
		<td data-name="idTipoTercero"<?php echo $terceros->idTipoTercero->CellAttributes() ?>>
<span id="el_terceros_idTipoTercero">
<span<?php echo $terceros->idTipoTercero->ViewAttributes() ?>>
<?php echo $terceros->idTipoTercero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
	<tr id="r_denominacion">
		<td><span id="elh_terceros_denominacion"><?php echo $terceros->denominacion->FldCaption() ?></span></td>
		<td data-name="denominacion"<?php echo $terceros->denominacion->CellAttributes() ?>>
<span id="el_terceros_denominacion">
<span<?php echo $terceros->denominacion->ViewAttributes() ?>>
<?php echo $terceros->denominacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->razonSocial->Visible) { // razonSocial ?>
	<tr id="r_razonSocial">
		<td><span id="elh_terceros_razonSocial"><?php echo $terceros->razonSocial->FldCaption() ?></span></td>
		<td data-name="razonSocial"<?php echo $terceros->razonSocial->CellAttributes() ?>>
<span id="el_terceros_razonSocial">
<span<?php echo $terceros->razonSocial->ViewAttributes() ?>>
<?php echo $terceros->razonSocial->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_terceros_direccion"><?php echo $terceros->direccion->FldCaption() ?></span></td>
		<td data-name="direccion"<?php echo $terceros->direccion->CellAttributes() ?>>
<span id="el_terceros_direccion">
<span<?php echo $terceros->direccion->ViewAttributes() ?>>
<?php echo $terceros->direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->domicilioFiscal->Visible) { // domicilioFiscal ?>
	<tr id="r_domicilioFiscal">
		<td><span id="elh_terceros_domicilioFiscal"><?php echo $terceros->domicilioFiscal->FldCaption() ?></span></td>
		<td data-name="domicilioFiscal"<?php echo $terceros->domicilioFiscal->CellAttributes() ?>>
<span id="el_terceros_domicilioFiscal">
<span<?php echo $terceros->domicilioFiscal->ViewAttributes() ?>>
<?php echo $terceros->domicilioFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idPaisFiscal->Visible) { // idPaisFiscal ?>
	<tr id="r_idPaisFiscal">
		<td><span id="elh_terceros_idPaisFiscal"><?php echo $terceros->idPaisFiscal->FldCaption() ?></span></td>
		<td data-name="idPaisFiscal"<?php echo $terceros->idPaisFiscal->CellAttributes() ?>>
<span id="el_terceros_idPaisFiscal">
<span<?php echo $terceros->idPaisFiscal->ViewAttributes() ?>>
<?php echo $terceros->idPaisFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idProvinciaFiscal->Visible) { // idProvinciaFiscal ?>
	<tr id="r_idProvinciaFiscal">
		<td><span id="elh_terceros_idProvinciaFiscal"><?php echo $terceros->idProvinciaFiscal->FldCaption() ?></span></td>
		<td data-name="idProvinciaFiscal"<?php echo $terceros->idProvinciaFiscal->CellAttributes() ?>>
<span id="el_terceros_idProvinciaFiscal">
<span<?php echo $terceros->idProvinciaFiscal->ViewAttributes() ?>>
<?php echo $terceros->idProvinciaFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idPartidoFiscal->Visible) { // idPartidoFiscal ?>
	<tr id="r_idPartidoFiscal">
		<td><span id="elh_terceros_idPartidoFiscal"><?php echo $terceros->idPartidoFiscal->FldCaption() ?></span></td>
		<td data-name="idPartidoFiscal"<?php echo $terceros->idPartidoFiscal->CellAttributes() ?>>
<span id="el_terceros_idPartidoFiscal">
<span<?php echo $terceros->idPartidoFiscal->ViewAttributes() ?>>
<?php echo $terceros->idPartidoFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idLocalidadFiscal->Visible) { // idLocalidadFiscal ?>
	<tr id="r_idLocalidadFiscal">
		<td><span id="elh_terceros_idLocalidadFiscal"><?php echo $terceros->idLocalidadFiscal->FldCaption() ?></span></td>
		<td data-name="idLocalidadFiscal"<?php echo $terceros->idLocalidadFiscal->CellAttributes() ?>>
<span id="el_terceros_idLocalidadFiscal">
<span<?php echo $terceros->idLocalidadFiscal->ViewAttributes() ?>>
<?php echo $terceros->idLocalidadFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->calleFiscal->Visible) { // calleFiscal ?>
	<tr id="r_calleFiscal">
		<td><span id="elh_terceros_calleFiscal"><?php echo $terceros->calleFiscal->FldCaption() ?></span></td>
		<td data-name="calleFiscal"<?php echo $terceros->calleFiscal->CellAttributes() ?>>
<span id="el_terceros_calleFiscal">
<span<?php echo $terceros->calleFiscal->ViewAttributes() ?>>
<?php echo $terceros->calleFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->direccionFiscal->Visible) { // direccionFiscal ?>
	<tr id="r_direccionFiscal">
		<td><span id="elh_terceros_direccionFiscal"><?php echo $terceros->direccionFiscal->FldCaption() ?></span></td>
		<td data-name="direccionFiscal"<?php echo $terceros->direccionFiscal->CellAttributes() ?>>
<span id="el_terceros_direccionFiscal">
<span<?php echo $terceros->direccionFiscal->ViewAttributes() ?>>
<?php echo $terceros->direccionFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->documento->Visible) { // documento ?>
	<tr id="r_documento">
		<td><span id="elh_terceros_documento"><?php echo $terceros->documento->FldCaption() ?></span></td>
		<td data-name="documento"<?php echo $terceros->documento->CellAttributes() ?>>
<span id="el_terceros_documento">
<span<?php echo $terceros->documento->ViewAttributes() ?>>
<?php echo $terceros->documento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->condicionIva->Visible) { // condicionIva ?>
	<tr id="r_condicionIva">
		<td><span id="elh_terceros_condicionIva"><?php echo $terceros->condicionIva->FldCaption() ?></span></td>
		<td data-name="condicionIva"<?php echo $terceros->condicionIva->CellAttributes() ?>>
<span id="el_terceros_condicionIva">
<span<?php echo $terceros->condicionIva->ViewAttributes() ?>>
<?php echo $terceros->condicionIva->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->observaciones->Visible) { // observaciones ?>
	<tr id="r_observaciones">
		<td><span id="elh_terceros_observaciones"><?php echo $terceros->observaciones->FldCaption() ?></span></td>
		<td data-name="observaciones"<?php echo $terceros->observaciones->CellAttributes() ?>>
<span id="el_terceros_observaciones">
<span<?php echo $terceros->observaciones->ViewAttributes() ?>>
<?php echo $terceros->observaciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idVendedor->Visible) { // idVendedor ?>
	<tr id="r_idVendedor">
		<td><span id="elh_terceros_idVendedor"><?php echo $terceros->idVendedor->FldCaption() ?></span></td>
		<td data-name="idVendedor"<?php echo $terceros->idVendedor->CellAttributes() ?>>
<span id="el_terceros_idVendedor">
<span<?php echo $terceros->idVendedor->ViewAttributes() ?>>
<?php echo $terceros->idVendedor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idCobrador->Visible) { // idCobrador ?>
	<tr id="r_idCobrador">
		<td><span id="elh_terceros_idCobrador"><?php echo $terceros->idCobrador->FldCaption() ?></span></td>
		<td data-name="idCobrador"<?php echo $terceros->idCobrador->CellAttributes() ?>>
<span id="el_terceros_idCobrador">
<span<?php echo $terceros->idCobrador->ViewAttributes() ?>>
<?php echo $terceros->idCobrador->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->comision->Visible) { // comision ?>
	<tr id="r_comision">
		<td><span id="elh_terceros_comision"><?php echo $terceros->comision->FldCaption() ?></span></td>
		<td data-name="comision"<?php echo $terceros->comision->CellAttributes() ?>>
<span id="el_terceros_comision">
<span<?php echo $terceros->comision->ViewAttributes() ?>>
<?php echo $terceros->comision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->idListaPrecios->Visible) { // idListaPrecios ?>
	<tr id="r_idListaPrecios">
		<td><span id="elh_terceros_idListaPrecios"><?php echo $terceros->idListaPrecios->FldCaption() ?></span></td>
		<td data-name="idListaPrecios"<?php echo $terceros->idListaPrecios->CellAttributes() ?>>
<span id="el_terceros_idListaPrecios">
<span<?php echo $terceros->idListaPrecios->ViewAttributes() ?>>
<?php echo $terceros->idListaPrecios->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->dtoCliente->Visible) { // dtoCliente ?>
	<tr id="r_dtoCliente">
		<td><span id="elh_terceros_dtoCliente"><?php echo $terceros->dtoCliente->FldCaption() ?></span></td>
		<td data-name="dtoCliente"<?php echo $terceros->dtoCliente->CellAttributes() ?>>
<span id="el_terceros_dtoCliente">
<span<?php echo $terceros->dtoCliente->ViewAttributes() ?>>
<?php echo $terceros->dtoCliente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->dto1->Visible) { // dto1 ?>
	<tr id="r_dto1">
		<td><span id="elh_terceros_dto1"><?php echo $terceros->dto1->FldCaption() ?></span></td>
		<td data-name="dto1"<?php echo $terceros->dto1->CellAttributes() ?>>
<span id="el_terceros_dto1">
<span<?php echo $terceros->dto1->ViewAttributes() ?>>
<?php echo $terceros->dto1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->dto2->Visible) { // dto2 ?>
	<tr id="r_dto2">
		<td><span id="elh_terceros_dto2"><?php echo $terceros->dto2->FldCaption() ?></span></td>
		<td data-name="dto2"<?php echo $terceros->dto2->CellAttributes() ?>>
<span id="el_terceros_dto2">
<span<?php echo $terceros->dto2->ViewAttributes() ?>>
<?php echo $terceros->dto2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->dto3->Visible) { // dto3 ?>
	<tr id="r_dto3">
		<td><span id="elh_terceros_dto3"><?php echo $terceros->dto3->FldCaption() ?></span></td>
		<td data-name="dto3"<?php echo $terceros->dto3->CellAttributes() ?>>
<span id="el_terceros_dto3">
<span<?php echo $terceros->dto3->ViewAttributes() ?>>
<?php echo $terceros->dto3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
	<tr id="r_limiteDescubierto">
		<td><span id="elh_terceros_limiteDescubierto"><?php echo $terceros->limiteDescubierto->FldCaption() ?></span></td>
		<td data-name="limiteDescubierto"<?php echo $terceros->limiteDescubierto->CellAttributes() ?>>
<span id="el_terceros_limiteDescubierto">
<span<?php echo $terceros->limiteDescubierto->ViewAttributes() ?>>
<?php echo $terceros->limiteDescubierto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
	<tr id="r_codigoPostal">
		<td><span id="elh_terceros_codigoPostal"><?php echo $terceros->codigoPostal->FldCaption() ?></span></td>
		<td data-name="codigoPostal"<?php echo $terceros->codigoPostal->CellAttributes() ?>>
<span id="el_terceros_codigoPostal">
<span<?php echo $terceros->codigoPostal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
	<tr id="r_codigoPostalFiscal">
		<td><span id="elh_terceros_codigoPostalFiscal"><?php echo $terceros->codigoPostalFiscal->FldCaption() ?></span></td>
		<td data-name="codigoPostalFiscal"<?php echo $terceros->codigoPostalFiscal->CellAttributes() ?>>
<span id="el_terceros_codigoPostalFiscal">
<span<?php echo $terceros->codigoPostalFiscal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostalFiscal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
	<tr id="r_condicionVenta">
		<td><span id="elh_terceros_condicionVenta"><?php echo $terceros->condicionVenta->FldCaption() ?></span></td>
		<td data-name="condicionVenta"<?php echo $terceros->condicionVenta->CellAttributes() ?>>
<span id="el_terceros_condicionVenta">
<span<?php echo $terceros->condicionVenta->ViewAttributes() ?>>
<?php echo $terceros->condicionVenta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("terceros2Dmedios2Dcontactos", explode(",", $terceros->getCurrentDetailTable())) && $terceros2Dmedios2Dcontactos->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("terceros2Dmedios2Dcontactos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "terceros2Dmedios2Dcontactosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $articulos2Dterceros2Ddescuentos->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dproveedores", explode(",", $terceros->getCurrentDetailTable())) && $articulos2Dproveedores->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dproveedores", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dproveedoresgrid.php" ?>
<?php } ?>
<?php
	if (in_array("subcategoria2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $subcategoria2Dterceros2Ddescuentos->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("subcategoria2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "subcategoria2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("categorias2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $categorias2Dterceros2Ddescuentos->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("categorias2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "categorias2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("sucursales", explode(",", $terceros->getCurrentDetailTable())) && $sucursales->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("sucursales", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "sucursalesgrid.php" ?>
<?php } ?>
<?php
	if (in_array("descuentosasociados", explode(",", $terceros->getCurrentDetailTable())) && $descuentosasociados->DetailView) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("descuentosasociados", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "descuentosasociadosgrid.php" ?>
<?php } ?>
</form>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">
ftercerosview.Init();
</script>
<?php } ?>
<?php
$terceros_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($terceros->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$terceros_view->Page_Terminate();
?>
