<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "comprobantesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "comprobantes2Dnumeraciongridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$comprobantes_add = NULL; // Initialize page object first

class ccomprobantes_add extends ccomprobantes {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'comprobantes';

	// Page object name
	var $PageObjName = 'comprobantes_add';

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

		// Table object (comprobantes)
		if (!isset($GLOBALS["comprobantes"]) || get_class($GLOBALS["comprobantes"]) == "ccomprobantes") {
			$GLOBALS["comprobantes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["comprobantes"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'comprobantes', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}
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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("comprobanteslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->denominacion->SetVisibility();
		$this->denominacionCorta->SetVisibility();
		$this->discriminaIVA->SetVisibility();
		$this->seAutoriza->SetVisibility();
		$this->letra->SetVisibility();
		$this->activo->SetVisibility();
		$this->ventaStock->SetVisibility();
		$this->ventaStockReservadoVenta->SetVisibility();
		$this->ventaStockReservadoCompra->SetVisibility();
		$this->compraStock->SetVisibility();
		$this->compraStockReservadoVenta->SetVisibility();
		$this->compraStockReservadoCompra->SetVisibility();
		$this->muestraPendientes->SetVisibility();
		$this->comprobanteDefaultImportacion->SetVisibility();
		$this->preimpreso->SetVisibility();
		$this->configuracionImpresion->SetVisibility();
		$this->configuracionImpresionCompra->SetVisibility();
		$this->cantidadRegistros->SetVisibility();
		$this->limitarModo->SetVisibility();

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

			// Process auto fill for detail table 'comprobantes-numeracion'
			if (@$_POST["grid"] == "fcomprobantes2Dnumeraciongrid") {
				if (!isset($GLOBALS["comprobantes2Dnumeracion_grid"])) $GLOBALS["comprobantes2Dnumeracion_grid"] = new ccomprobantes2Dnumeracion_grid;
				$GLOBALS["comprobantes2Dnumeracion_grid"]->Page_Init();
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
		global $EW_EXPORT, $comprobantes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($comprobantes);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("comprobanteslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "comprobanteslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "comprobantesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->preimpreso->Upload->Index = $objForm->Index;
		$this->preimpreso->Upload->UploadFile();
		$this->preimpreso->CurrentValue = $this->preimpreso->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->denominacion->CurrentValue = NULL;
		$this->denominacion->OldValue = $this->denominacion->CurrentValue;
		$this->denominacionCorta->CurrentValue = NULL;
		$this->denominacionCorta->OldValue = $this->denominacionCorta->CurrentValue;
		$this->discriminaIVA->CurrentValue = NULL;
		$this->discriminaIVA->OldValue = $this->discriminaIVA->CurrentValue;
		$this->seAutoriza->CurrentValue = NULL;
		$this->seAutoriza->OldValue = $this->seAutoriza->CurrentValue;
		$this->letra->CurrentValue = NULL;
		$this->letra->OldValue = $this->letra->CurrentValue;
		$this->activo->CurrentValue = NULL;
		$this->activo->OldValue = $this->activo->CurrentValue;
		$this->ventaStock->CurrentValue = NULL;
		$this->ventaStock->OldValue = $this->ventaStock->CurrentValue;
		$this->ventaStockReservadoVenta->CurrentValue = NULL;
		$this->ventaStockReservadoVenta->OldValue = $this->ventaStockReservadoVenta->CurrentValue;
		$this->ventaStockReservadoCompra->CurrentValue = NULL;
		$this->ventaStockReservadoCompra->OldValue = $this->ventaStockReservadoCompra->CurrentValue;
		$this->compraStock->CurrentValue = NULL;
		$this->compraStock->OldValue = $this->compraStock->CurrentValue;
		$this->compraStockReservadoVenta->CurrentValue = NULL;
		$this->compraStockReservadoVenta->OldValue = $this->compraStockReservadoVenta->CurrentValue;
		$this->compraStockReservadoCompra->CurrentValue = NULL;
		$this->compraStockReservadoCompra->OldValue = $this->compraStockReservadoCompra->CurrentValue;
		$this->muestraPendientes->CurrentValue = NULL;
		$this->muestraPendientes->OldValue = $this->muestraPendientes->CurrentValue;
		$this->comprobanteDefaultImportacion->CurrentValue = NULL;
		$this->comprobanteDefaultImportacion->OldValue = $this->comprobanteDefaultImportacion->CurrentValue;
		$this->preimpreso->Upload->DbValue = NULL;
		$this->preimpreso->OldValue = $this->preimpreso->Upload->DbValue;
		$this->preimpreso->CurrentValue = NULL; // Clear file related field
		$this->configuracionImpresion->CurrentValue = NULL;
		$this->configuracionImpresion->OldValue = $this->configuracionImpresion->CurrentValue;
		$this->configuracionImpresionCompra->CurrentValue = NULL;
		$this->configuracionImpresionCompra->OldValue = $this->configuracionImpresionCompra->CurrentValue;
		$this->cantidadRegistros->CurrentValue = 1;
		$this->limitarModo->CurrentValue = NULL;
		$this->limitarModo->OldValue = $this->limitarModo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->denominacionCorta->FldIsDetailKey) {
			$this->denominacionCorta->setFormValue($objForm->GetValue("x_denominacionCorta"));
		}
		if (!$this->discriminaIVA->FldIsDetailKey) {
			$this->discriminaIVA->setFormValue($objForm->GetValue("x_discriminaIVA"));
		}
		if (!$this->seAutoriza->FldIsDetailKey) {
			$this->seAutoriza->setFormValue($objForm->GetValue("x_seAutoriza"));
		}
		if (!$this->letra->FldIsDetailKey) {
			$this->letra->setFormValue($objForm->GetValue("x_letra"));
		}
		if (!$this->activo->FldIsDetailKey) {
			$this->activo->setFormValue($objForm->GetValue("x_activo"));
		}
		if (!$this->ventaStock->FldIsDetailKey) {
			$this->ventaStock->setFormValue($objForm->GetValue("x_ventaStock"));
		}
		if (!$this->ventaStockReservadoVenta->FldIsDetailKey) {
			$this->ventaStockReservadoVenta->setFormValue($objForm->GetValue("x_ventaStockReservadoVenta"));
		}
		if (!$this->ventaStockReservadoCompra->FldIsDetailKey) {
			$this->ventaStockReservadoCompra->setFormValue($objForm->GetValue("x_ventaStockReservadoCompra"));
		}
		if (!$this->compraStock->FldIsDetailKey) {
			$this->compraStock->setFormValue($objForm->GetValue("x_compraStock"));
		}
		if (!$this->compraStockReservadoVenta->FldIsDetailKey) {
			$this->compraStockReservadoVenta->setFormValue($objForm->GetValue("x_compraStockReservadoVenta"));
		}
		if (!$this->compraStockReservadoCompra->FldIsDetailKey) {
			$this->compraStockReservadoCompra->setFormValue($objForm->GetValue("x_compraStockReservadoCompra"));
		}
		if (!$this->muestraPendientes->FldIsDetailKey) {
			$this->muestraPendientes->setFormValue($objForm->GetValue("x_muestraPendientes"));
		}
		if (!$this->comprobanteDefaultImportacion->FldIsDetailKey) {
			$this->comprobanteDefaultImportacion->setFormValue($objForm->GetValue("x_comprobanteDefaultImportacion"));
		}
		if (!$this->configuracionImpresion->FldIsDetailKey) {
			$this->configuracionImpresion->setFormValue($objForm->GetValue("x_configuracionImpresion"));
		}
		if (!$this->configuracionImpresionCompra->FldIsDetailKey) {
			$this->configuracionImpresionCompra->setFormValue($objForm->GetValue("x_configuracionImpresionCompra"));
		}
		if (!$this->cantidadRegistros->FldIsDetailKey) {
			$this->cantidadRegistros->setFormValue($objForm->GetValue("x_cantidadRegistros"));
		}
		if (!$this->limitarModo->FldIsDetailKey) {
			$this->limitarModo->setFormValue($objForm->GetValue("x_limitarModo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->denominacionCorta->CurrentValue = $this->denominacionCorta->FormValue;
		$this->discriminaIVA->CurrentValue = $this->discriminaIVA->FormValue;
		$this->seAutoriza->CurrentValue = $this->seAutoriza->FormValue;
		$this->letra->CurrentValue = $this->letra->FormValue;
		$this->activo->CurrentValue = $this->activo->FormValue;
		$this->ventaStock->CurrentValue = $this->ventaStock->FormValue;
		$this->ventaStockReservadoVenta->CurrentValue = $this->ventaStockReservadoVenta->FormValue;
		$this->ventaStockReservadoCompra->CurrentValue = $this->ventaStockReservadoCompra->FormValue;
		$this->compraStock->CurrentValue = $this->compraStock->FormValue;
		$this->compraStockReservadoVenta->CurrentValue = $this->compraStockReservadoVenta->FormValue;
		$this->compraStockReservadoCompra->CurrentValue = $this->compraStockReservadoCompra->FormValue;
		$this->muestraPendientes->CurrentValue = $this->muestraPendientes->FormValue;
		$this->comprobanteDefaultImportacion->CurrentValue = $this->comprobanteDefaultImportacion->FormValue;
		$this->configuracionImpresion->CurrentValue = $this->configuracionImpresion->FormValue;
		$this->configuracionImpresionCompra->CurrentValue = $this->configuracionImpresionCompra->FormValue;
		$this->cantidadRegistros->CurrentValue = $this->cantidadRegistros->FormValue;
		$this->limitarModo->CurrentValue = $this->limitarModo->FormValue;
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
		$this->denominacionCorta->setDbValue($rs->fields('denominacionCorta'));
		$this->discriminaIVA->setDbValue($rs->fields('discriminaIVA'));
		$this->seAutoriza->setDbValue($rs->fields('seAutoriza'));
		$this->letra->setDbValue($rs->fields('letra'));
		$this->seanula->setDbValue($rs->fields('seanula'));
		$this->contracomprobante->setDbValue($rs->fields('contracomprobante'));
		$this->comportamiento->setDbValue($rs->fields('comportamiento'));
		$this->activo->setDbValue($rs->fields('activo'));
		$this->ventaStock->setDbValue($rs->fields('ventaStock'));
		$this->ventaStockReservadoVenta->setDbValue($rs->fields('ventaStockReservadoVenta'));
		$this->ventaStockReservadoCompra->setDbValue($rs->fields('ventaStockReservadoCompra'));
		$this->compraStock->setDbValue($rs->fields('compraStock'));
		$this->compraStockReservadoVenta->setDbValue($rs->fields('compraStockReservadoVenta'));
		$this->compraStockReservadoCompra->setDbValue($rs->fields('compraStockReservadoCompra'));
		$this->muestraPendientes->setDbValue($rs->fields('muestraPendientes'));
		$this->impresion->setDbValue($rs->fields('impresion'));
		$this->comprobanteDefaultImportacion->setDbValue($rs->fields('comprobanteDefaultImportacion'));
		$this->preimpreso->Upload->DbValue = $rs->fields('preimpreso');
		$this->preimpreso->CurrentValue = $this->preimpreso->Upload->DbValue;
		$this->configuracionImpresion->setDbValue($rs->fields('configuracionImpresion'));
		$this->configuracionImpresionCompra->setDbValue($rs->fields('configuracionImpresionCompra'));
		$this->cantidadRegistros->setDbValue($rs->fields('cantidadRegistros'));
		$this->limitarModo->setDbValue($rs->fields('limitarModo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->denominacionCorta->DbValue = $row['denominacionCorta'];
		$this->discriminaIVA->DbValue = $row['discriminaIVA'];
		$this->seAutoriza->DbValue = $row['seAutoriza'];
		$this->letra->DbValue = $row['letra'];
		$this->seanula->DbValue = $row['seanula'];
		$this->contracomprobante->DbValue = $row['contracomprobante'];
		$this->comportamiento->DbValue = $row['comportamiento'];
		$this->activo->DbValue = $row['activo'];
		$this->ventaStock->DbValue = $row['ventaStock'];
		$this->ventaStockReservadoVenta->DbValue = $row['ventaStockReservadoVenta'];
		$this->ventaStockReservadoCompra->DbValue = $row['ventaStockReservadoCompra'];
		$this->compraStock->DbValue = $row['compraStock'];
		$this->compraStockReservadoVenta->DbValue = $row['compraStockReservadoVenta'];
		$this->compraStockReservadoCompra->DbValue = $row['compraStockReservadoCompra'];
		$this->muestraPendientes->DbValue = $row['muestraPendientes'];
		$this->impresion->DbValue = $row['impresion'];
		$this->comprobanteDefaultImportacion->DbValue = $row['comprobanteDefaultImportacion'];
		$this->preimpreso->Upload->DbValue = $row['preimpreso'];
		$this->configuracionImpresion->DbValue = $row['configuracionImpresion'];
		$this->configuracionImpresionCompra->DbValue = $row['configuracionImpresionCompra'];
		$this->cantidadRegistros->DbValue = $row['cantidadRegistros'];
		$this->limitarModo->DbValue = $row['limitarModo'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// denominacion
		// denominacionCorta
		// discriminaIVA
		// seAutoriza
		// letra
		// seanula
		// contracomprobante
		// comportamiento
		// activo
		// ventaStock
		// ventaStockReservadoVenta
		// ventaStockReservadoCompra
		// compraStock
		// compraStockReservadoVenta
		// compraStockReservadoCompra
		// muestraPendientes
		// impresion
		// comprobanteDefaultImportacion
		// preimpreso
		// configuracionImpresion
		// configuracionImpresionCompra
		// cantidadRegistros
		// limitarModo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// denominacionCorta
		$this->denominacionCorta->ViewValue = $this->denominacionCorta->CurrentValue;
		$this->denominacionCorta->ViewCustomAttributes = "";

		// discriminaIVA
		if (strval($this->discriminaIVA->CurrentValue) <> "") {
			$this->discriminaIVA->ViewValue = "";
			$arwrk = explode(",", strval($this->discriminaIVA->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->discriminaIVA->ViewValue .= $this->discriminaIVA->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->discriminaIVA->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->discriminaIVA->ViewValue = NULL;
		}
		$this->discriminaIVA->ViewCustomAttributes = "";

		// seAutoriza
		if (strval($this->seAutoriza->CurrentValue) <> "") {
			$this->seAutoriza->ViewValue = "";
			$arwrk = explode(",", strval($this->seAutoriza->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->seAutoriza->ViewValue .= $this->seAutoriza->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->seAutoriza->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->seAutoriza->ViewValue = NULL;
		}
		$this->seAutoriza->ViewCustomAttributes = "";

		// letra
		$this->letra->ViewValue = $this->letra->CurrentValue;
		$this->letra->ViewCustomAttributes = "";

		// activo
		if (strval($this->activo->CurrentValue) <> "") {
			$this->activo->ViewValue = "";
			$arwrk = explode(",", strval($this->activo->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->activo->ViewValue .= $this->activo->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->activo->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->activo->ViewValue = NULL;
		}
		$this->activo->ViewCustomAttributes = "";

		// ventaStock
		if (strval($this->ventaStock->CurrentValue) <> "") {
			$this->ventaStock->ViewValue = $this->ventaStock->OptionCaption($this->ventaStock->CurrentValue);
		} else {
			$this->ventaStock->ViewValue = NULL;
		}
		$this->ventaStock->ViewCustomAttributes = "";

		// ventaStockReservadoVenta
		if (strval($this->ventaStockReservadoVenta->CurrentValue) <> "") {
			$this->ventaStockReservadoVenta->ViewValue = $this->ventaStockReservadoVenta->OptionCaption($this->ventaStockReservadoVenta->CurrentValue);
		} else {
			$this->ventaStockReservadoVenta->ViewValue = NULL;
		}
		$this->ventaStockReservadoVenta->ViewCustomAttributes = "";

		// ventaStockReservadoCompra
		if (strval($this->ventaStockReservadoCompra->CurrentValue) <> "") {
			$this->ventaStockReservadoCompra->ViewValue = $this->ventaStockReservadoCompra->OptionCaption($this->ventaStockReservadoCompra->CurrentValue);
		} else {
			$this->ventaStockReservadoCompra->ViewValue = NULL;
		}
		$this->ventaStockReservadoCompra->ViewCustomAttributes = "";

		// compraStock
		if (strval($this->compraStock->CurrentValue) <> "") {
			$this->compraStock->ViewValue = $this->compraStock->OptionCaption($this->compraStock->CurrentValue);
		} else {
			$this->compraStock->ViewValue = NULL;
		}
		$this->compraStock->ViewCustomAttributes = "";

		// compraStockReservadoVenta
		if (strval($this->compraStockReservadoVenta->CurrentValue) <> "") {
			$this->compraStockReservadoVenta->ViewValue = $this->compraStockReservadoVenta->OptionCaption($this->compraStockReservadoVenta->CurrentValue);
		} else {
			$this->compraStockReservadoVenta->ViewValue = NULL;
		}
		$this->compraStockReservadoVenta->ViewCustomAttributes = "";

		// compraStockReservadoCompra
		if (strval($this->compraStockReservadoCompra->CurrentValue) <> "") {
			$this->compraStockReservadoCompra->ViewValue = $this->compraStockReservadoCompra->OptionCaption($this->compraStockReservadoCompra->CurrentValue);
		} else {
			$this->compraStockReservadoCompra->ViewValue = NULL;
		}
		$this->compraStockReservadoCompra->ViewCustomAttributes = "";

		// muestraPendientes
		if (strval($this->muestraPendientes->CurrentValue) <> "") {
			$this->muestraPendientes->ViewValue = "";
			$arwrk = explode(",", strval($this->muestraPendientes->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->muestraPendientes->ViewValue .= $this->muestraPendientes->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->muestraPendientes->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->muestraPendientes->ViewValue = NULL;
		}
		$this->muestraPendientes->ViewCustomAttributes = "";

		// comprobanteDefaultImportacion
		if (strval($this->comprobanteDefaultImportacion->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->comprobanteDefaultImportacion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
		$sWhereWrk = "";
		$this->comprobanteDefaultImportacion->LookupFilters = array();
		$lookuptblfilter = "`activo` = 1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->comprobanteDefaultImportacion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->comprobanteDefaultImportacion->ViewValue = $this->comprobanteDefaultImportacion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->comprobanteDefaultImportacion->ViewValue = $this->comprobanteDefaultImportacion->CurrentValue;
			}
		} else {
			$this->comprobanteDefaultImportacion->ViewValue = NULL;
		}
		$this->comprobanteDefaultImportacion->ViewCustomAttributes = "";

		// preimpreso
		if (!ew_Empty($this->preimpreso->Upload->DbValue)) {
			$this->preimpreso->ViewValue = $this->preimpreso->Upload->DbValue;
		} else {
			$this->preimpreso->ViewValue = "";
		}
		$this->preimpreso->ViewCustomAttributes = "";

		// configuracionImpresion
		$this->configuracionImpresion->ViewValue = $this->configuracionImpresion->CurrentValue;
		$this->configuracionImpresion->ViewCustomAttributes = "";

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra->ViewValue = $this->configuracionImpresionCompra->CurrentValue;
		$this->configuracionImpresionCompra->ViewCustomAttributes = "";

		// cantidadRegistros
		$this->cantidadRegistros->ViewValue = $this->cantidadRegistros->CurrentValue;
		$this->cantidadRegistros->ViewCustomAttributes = "";

		// limitarModo
		if (strval($this->limitarModo->CurrentValue) <> "") {
			$this->limitarModo->ViewValue = $this->limitarModo->OptionCaption($this->limitarModo->CurrentValue);
		} else {
			$this->limitarModo->ViewValue = NULL;
		}
		$this->limitarModo->ViewCustomAttributes = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// denominacionCorta
			$this->denominacionCorta->LinkCustomAttributes = "";
			$this->denominacionCorta->HrefValue = "";
			$this->denominacionCorta->TooltipValue = "";

			// discriminaIVA
			$this->discriminaIVA->LinkCustomAttributes = "";
			$this->discriminaIVA->HrefValue = "";
			$this->discriminaIVA->TooltipValue = "";

			// seAutoriza
			$this->seAutoriza->LinkCustomAttributes = "";
			$this->seAutoriza->HrefValue = "";
			$this->seAutoriza->TooltipValue = "";

			// letra
			$this->letra->LinkCustomAttributes = "";
			$this->letra->HrefValue = "";
			$this->letra->TooltipValue = "";

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";
			$this->activo->TooltipValue = "";

			// ventaStock
			$this->ventaStock->LinkCustomAttributes = "";
			$this->ventaStock->HrefValue = "";
			$this->ventaStock->TooltipValue = "";

			// ventaStockReservadoVenta
			$this->ventaStockReservadoVenta->LinkCustomAttributes = "";
			$this->ventaStockReservadoVenta->HrefValue = "";
			$this->ventaStockReservadoVenta->TooltipValue = "";

			// ventaStockReservadoCompra
			$this->ventaStockReservadoCompra->LinkCustomAttributes = "";
			$this->ventaStockReservadoCompra->HrefValue = "";
			$this->ventaStockReservadoCompra->TooltipValue = "";

			// compraStock
			$this->compraStock->LinkCustomAttributes = "";
			$this->compraStock->HrefValue = "";
			$this->compraStock->TooltipValue = "";

			// compraStockReservadoVenta
			$this->compraStockReservadoVenta->LinkCustomAttributes = "";
			$this->compraStockReservadoVenta->HrefValue = "";
			$this->compraStockReservadoVenta->TooltipValue = "";

			// compraStockReservadoCompra
			$this->compraStockReservadoCompra->LinkCustomAttributes = "";
			$this->compraStockReservadoCompra->HrefValue = "";
			$this->compraStockReservadoCompra->TooltipValue = "";

			// muestraPendientes
			$this->muestraPendientes->LinkCustomAttributes = "";
			$this->muestraPendientes->HrefValue = "";
			$this->muestraPendientes->TooltipValue = "";

			// comprobanteDefaultImportacion
			$this->comprobanteDefaultImportacion->LinkCustomAttributes = "";
			$this->comprobanteDefaultImportacion->HrefValue = "";
			$this->comprobanteDefaultImportacion->TooltipValue = "";

			// preimpreso
			$this->preimpreso->LinkCustomAttributes = "";
			$this->preimpreso->HrefValue = "";
			$this->preimpreso->HrefValue2 = $this->preimpreso->UploadPath . $this->preimpreso->Upload->DbValue;
			$this->preimpreso->TooltipValue = "";

			// configuracionImpresion
			$this->configuracionImpresion->LinkCustomAttributes = "";
			$this->configuracionImpresion->HrefValue = "";
			$this->configuracionImpresion->TooltipValue = "";

			// configuracionImpresionCompra
			$this->configuracionImpresionCompra->LinkCustomAttributes = "";
			$this->configuracionImpresionCompra->HrefValue = "";
			$this->configuracionImpresionCompra->TooltipValue = "";

			// cantidadRegistros
			$this->cantidadRegistros->LinkCustomAttributes = "";
			$this->cantidadRegistros->HrefValue = "";
			$this->cantidadRegistros->TooltipValue = "";

			// limitarModo
			$this->limitarModo->LinkCustomAttributes = "";
			$this->limitarModo->HrefValue = "";
			$this->limitarModo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// denominacionCorta
			$this->denominacionCorta->EditAttrs["class"] = "form-control";
			$this->denominacionCorta->EditCustomAttributes = "";
			$this->denominacionCorta->EditValue = ew_HtmlEncode($this->denominacionCorta->CurrentValue);
			$this->denominacionCorta->PlaceHolder = ew_RemoveHtml($this->denominacionCorta->FldCaption());

			// discriminaIVA
			$this->discriminaIVA->EditCustomAttributes = "";
			$this->discriminaIVA->EditValue = $this->discriminaIVA->Options(FALSE);

			// seAutoriza
			$this->seAutoriza->EditCustomAttributes = "";
			$this->seAutoriza->EditValue = $this->seAutoriza->Options(FALSE);

			// letra
			$this->letra->EditAttrs["class"] = "form-control";
			$this->letra->EditCustomAttributes = "";
			$this->letra->EditValue = ew_HtmlEncode($this->letra->CurrentValue);
			$this->letra->PlaceHolder = ew_RemoveHtml($this->letra->FldCaption());

			// activo
			$this->activo->EditCustomAttributes = "";
			$this->activo->EditValue = $this->activo->Options(FALSE);

			// ventaStock
			$this->ventaStock->EditAttrs["class"] = "form-control";
			$this->ventaStock->EditCustomAttributes = "";
			$this->ventaStock->EditValue = $this->ventaStock->Options(TRUE);

			// ventaStockReservadoVenta
			$this->ventaStockReservadoVenta->EditAttrs["class"] = "form-control";
			$this->ventaStockReservadoVenta->EditCustomAttributes = "";
			$this->ventaStockReservadoVenta->EditValue = $this->ventaStockReservadoVenta->Options(TRUE);

			// ventaStockReservadoCompra
			$this->ventaStockReservadoCompra->EditAttrs["class"] = "form-control";
			$this->ventaStockReservadoCompra->EditCustomAttributes = "";
			$this->ventaStockReservadoCompra->EditValue = $this->ventaStockReservadoCompra->Options(TRUE);

			// compraStock
			$this->compraStock->EditAttrs["class"] = "form-control";
			$this->compraStock->EditCustomAttributes = "";
			$this->compraStock->EditValue = $this->compraStock->Options(TRUE);

			// compraStockReservadoVenta
			$this->compraStockReservadoVenta->EditAttrs["class"] = "form-control";
			$this->compraStockReservadoVenta->EditCustomAttributes = "";
			$this->compraStockReservadoVenta->EditValue = $this->compraStockReservadoVenta->Options(TRUE);

			// compraStockReservadoCompra
			$this->compraStockReservadoCompra->EditAttrs["class"] = "form-control";
			$this->compraStockReservadoCompra->EditCustomAttributes = "";
			$this->compraStockReservadoCompra->EditValue = $this->compraStockReservadoCompra->Options(TRUE);

			// muestraPendientes
			$this->muestraPendientes->EditCustomAttributes = "";
			$this->muestraPendientes->EditValue = $this->muestraPendientes->Options(FALSE);

			// comprobanteDefaultImportacion
			$this->comprobanteDefaultImportacion->EditAttrs["class"] = "form-control";
			$this->comprobanteDefaultImportacion->EditCustomAttributes = "";
			if (trim(strval($this->comprobanteDefaultImportacion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->comprobanteDefaultImportacion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `comprobantes`";
			$sWhereWrk = "";
			$this->comprobanteDefaultImportacion->LookupFilters = array();
			$lookuptblfilter = "`activo` = 1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->comprobanteDefaultImportacion, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->comprobanteDefaultImportacion->EditValue = $arwrk;

			// preimpreso
			$this->preimpreso->EditAttrs["class"] = "form-control";
			$this->preimpreso->EditCustomAttributes = "";
			if (!ew_Empty($this->preimpreso->Upload->DbValue)) {
				$this->preimpreso->EditValue = $this->preimpreso->Upload->DbValue;
			} else {
				$this->preimpreso->EditValue = "";
			}
			if (!ew_Empty($this->preimpreso->CurrentValue))
				$this->preimpreso->Upload->FileName = $this->preimpreso->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->preimpreso);

			// configuracionImpresion
			$this->configuracionImpresion->EditAttrs["class"] = "form-control";
			$this->configuracionImpresion->EditCustomAttributes = "";
			$this->configuracionImpresion->EditValue = ew_HtmlEncode($this->configuracionImpresion->CurrentValue);
			$this->configuracionImpresion->PlaceHolder = ew_RemoveHtml($this->configuracionImpresion->FldCaption());

			// configuracionImpresionCompra
			$this->configuracionImpresionCompra->EditAttrs["class"] = "form-control";
			$this->configuracionImpresionCompra->EditCustomAttributes = "";
			$this->configuracionImpresionCompra->EditValue = ew_HtmlEncode($this->configuracionImpresionCompra->CurrentValue);
			$this->configuracionImpresionCompra->PlaceHolder = ew_RemoveHtml($this->configuracionImpresionCompra->FldCaption());

			// cantidadRegistros
			$this->cantidadRegistros->EditAttrs["class"] = "form-control";
			$this->cantidadRegistros->EditCustomAttributes = "";
			$this->cantidadRegistros->EditValue = ew_HtmlEncode($this->cantidadRegistros->CurrentValue);
			$this->cantidadRegistros->PlaceHolder = ew_RemoveHtml($this->cantidadRegistros->FldCaption());

			// limitarModo
			$this->limitarModo->EditAttrs["class"] = "form-control";
			$this->limitarModo->EditCustomAttributes = "";
			$this->limitarModo->EditValue = $this->limitarModo->Options(TRUE);

			// Add refer script
			// denominacion

			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// denominacionCorta
			$this->denominacionCorta->LinkCustomAttributes = "";
			$this->denominacionCorta->HrefValue = "";

			// discriminaIVA
			$this->discriminaIVA->LinkCustomAttributes = "";
			$this->discriminaIVA->HrefValue = "";

			// seAutoriza
			$this->seAutoriza->LinkCustomAttributes = "";
			$this->seAutoriza->HrefValue = "";

			// letra
			$this->letra->LinkCustomAttributes = "";
			$this->letra->HrefValue = "";

			// activo
			$this->activo->LinkCustomAttributes = "";
			$this->activo->HrefValue = "";

			// ventaStock
			$this->ventaStock->LinkCustomAttributes = "";
			$this->ventaStock->HrefValue = "";

			// ventaStockReservadoVenta
			$this->ventaStockReservadoVenta->LinkCustomAttributes = "";
			$this->ventaStockReservadoVenta->HrefValue = "";

			// ventaStockReservadoCompra
			$this->ventaStockReservadoCompra->LinkCustomAttributes = "";
			$this->ventaStockReservadoCompra->HrefValue = "";

			// compraStock
			$this->compraStock->LinkCustomAttributes = "";
			$this->compraStock->HrefValue = "";

			// compraStockReservadoVenta
			$this->compraStockReservadoVenta->LinkCustomAttributes = "";
			$this->compraStockReservadoVenta->HrefValue = "";

			// compraStockReservadoCompra
			$this->compraStockReservadoCompra->LinkCustomAttributes = "";
			$this->compraStockReservadoCompra->HrefValue = "";

			// muestraPendientes
			$this->muestraPendientes->LinkCustomAttributes = "";
			$this->muestraPendientes->HrefValue = "";

			// comprobanteDefaultImportacion
			$this->comprobanteDefaultImportacion->LinkCustomAttributes = "";
			$this->comprobanteDefaultImportacion->HrefValue = "";

			// preimpreso
			$this->preimpreso->LinkCustomAttributes = "";
			$this->preimpreso->HrefValue = "";
			$this->preimpreso->HrefValue2 = $this->preimpreso->UploadPath . $this->preimpreso->Upload->DbValue;

			// configuracionImpresion
			$this->configuracionImpresion->LinkCustomAttributes = "";
			$this->configuracionImpresion->HrefValue = "";

			// configuracionImpresionCompra
			$this->configuracionImpresionCompra->LinkCustomAttributes = "";
			$this->configuracionImpresionCompra->HrefValue = "";

			// cantidadRegistros
			$this->cantidadRegistros->LinkCustomAttributes = "";
			$this->cantidadRegistros->HrefValue = "";

			// limitarModo
			$this->limitarModo->LinkCustomAttributes = "";
			$this->limitarModo->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->cantidadRegistros->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidadRegistros->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("comprobantes2Dnumeracion", $DetailTblVar) && $GLOBALS["comprobantes2Dnumeracion"]->DetailAdd) {
			if (!isset($GLOBALS["comprobantes2Dnumeracion_grid"])) $GLOBALS["comprobantes2Dnumeracion_grid"] = new ccomprobantes2Dnumeracion_grid(); // get detail page object
			$GLOBALS["comprobantes2Dnumeracion_grid"]->ValidateGridForm();
		}

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// denominacion
		$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, FALSE);

		// denominacionCorta
		$this->denominacionCorta->SetDbValueDef($rsnew, $this->denominacionCorta->CurrentValue, NULL, FALSE);

		// discriminaIVA
		$this->discriminaIVA->SetDbValueDef($rsnew, $this->discriminaIVA->CurrentValue, NULL, FALSE);

		// seAutoriza
		$this->seAutoriza->SetDbValueDef($rsnew, $this->seAutoriza->CurrentValue, NULL, FALSE);

		// letra
		$this->letra->SetDbValueDef($rsnew, $this->letra->CurrentValue, NULL, FALSE);

		// activo
		$this->activo->SetDbValueDef($rsnew, $this->activo->CurrentValue, NULL, FALSE);

		// ventaStock
		$this->ventaStock->SetDbValueDef($rsnew, $this->ventaStock->CurrentValue, NULL, FALSE);

		// ventaStockReservadoVenta
		$this->ventaStockReservadoVenta->SetDbValueDef($rsnew, $this->ventaStockReservadoVenta->CurrentValue, NULL, FALSE);

		// ventaStockReservadoCompra
		$this->ventaStockReservadoCompra->SetDbValueDef($rsnew, $this->ventaStockReservadoCompra->CurrentValue, NULL, FALSE);

		// compraStock
		$this->compraStock->SetDbValueDef($rsnew, $this->compraStock->CurrentValue, NULL, FALSE);

		// compraStockReservadoVenta
		$this->compraStockReservadoVenta->SetDbValueDef($rsnew, $this->compraStockReservadoVenta->CurrentValue, NULL, FALSE);

		// compraStockReservadoCompra
		$this->compraStockReservadoCompra->SetDbValueDef($rsnew, $this->compraStockReservadoCompra->CurrentValue, NULL, FALSE);

		// muestraPendientes
		$this->muestraPendientes->SetDbValueDef($rsnew, $this->muestraPendientes->CurrentValue, NULL, FALSE);

		// comprobanteDefaultImportacion
		$this->comprobanteDefaultImportacion->SetDbValueDef($rsnew, $this->comprobanteDefaultImportacion->CurrentValue, NULL, FALSE);

		// preimpreso
		if ($this->preimpreso->Visible && !$this->preimpreso->Upload->KeepFile) {
			$this->preimpreso->Upload->DbValue = ""; // No need to delete old file
			if ($this->preimpreso->Upload->FileName == "") {
				$rsnew['preimpreso'] = NULL;
			} else {
				$rsnew['preimpreso'] = $this->preimpreso->Upload->FileName;
			}
		}

		// configuracionImpresion
		$this->configuracionImpresion->SetDbValueDef($rsnew, $this->configuracionImpresion->CurrentValue, NULL, FALSE);

		// configuracionImpresionCompra
		$this->configuracionImpresionCompra->SetDbValueDef($rsnew, $this->configuracionImpresionCompra->CurrentValue, NULL, FALSE);

		// cantidadRegistros
		$this->cantidadRegistros->SetDbValueDef($rsnew, $this->cantidadRegistros->CurrentValue, NULL, strval($this->cantidadRegistros->CurrentValue) == "");

		// limitarModo
		$this->limitarModo->SetDbValueDef($rsnew, $this->limitarModo->CurrentValue, NULL, FALSE);
		if ($this->preimpreso->Visible && !$this->preimpreso->Upload->KeepFile) {
			if (!ew_Empty($this->preimpreso->Upload->Value)) {
				$rsnew['preimpreso'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->preimpreso->UploadPath), $rsnew['preimpreso']); // Get new file name
			}
		}

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
				if ($this->preimpreso->Visible && !$this->preimpreso->Upload->KeepFile) {
					if (!ew_Empty($this->preimpreso->Upload->Value)) {
						$this->preimpreso->Upload->SaveToFile($this->preimpreso->UploadPath, $rsnew['preimpreso'], TRUE);
					}
				}
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("comprobantes2Dnumeracion", $DetailTblVar) && $GLOBALS["comprobantes2Dnumeracion"]->DetailAdd) {
				$GLOBALS["comprobantes2Dnumeracion"]->idComprobante->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["comprobantes2Dnumeracion_grid"])) $GLOBALS["comprobantes2Dnumeracion_grid"] = new ccomprobantes2Dnumeracion_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "comprobantes-numeracion"); // Load user level of detail table
				$AddRow = $GLOBALS["comprobantes2Dnumeracion_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["comprobantes2Dnumeracion"]->idComprobante->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// preimpreso
		ew_CleanUploadTempPath($this->preimpreso, $this->preimpreso->Upload->Index);
		return $AddRow;
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
			if (in_array("comprobantes2Dnumeracion", $DetailTblVar)) {
				if (!isset($GLOBALS["comprobantes2Dnumeracion_grid"]))
					$GLOBALS["comprobantes2Dnumeracion_grid"] = new ccomprobantes2Dnumeracion_grid;
				if ($GLOBALS["comprobantes2Dnumeracion_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["comprobantes2Dnumeracion_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["comprobantes2Dnumeracion_grid"]->CurrentMode = "add";
					$GLOBALS["comprobantes2Dnumeracion_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["comprobantes2Dnumeracion_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["comprobantes2Dnumeracion_grid"]->setStartRecordNumber(1);
					$GLOBALS["comprobantes2Dnumeracion_grid"]->idComprobante->FldIsDetailKey = TRUE;
					$GLOBALS["comprobantes2Dnumeracion_grid"]->idComprobante->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["comprobantes2Dnumeracion_grid"]->idComprobante->setSessionValue($GLOBALS["comprobantes2Dnumeracion_grid"]->idComprobante->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("comprobanteslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_comprobanteDefaultImportacion":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
			$sWhereWrk = "";
			$this->comprobanteDefaultImportacion->LookupFilters = array();
			$lookuptblfilter = "`activo` = 1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->comprobanteDefaultImportacion, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($comprobantes_add)) $comprobantes_add = new ccomprobantes_add();

// Page init
$comprobantes_add->Page_Init();

// Page main
$comprobantes_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcomprobantesadd = new ew_Form("fcomprobantesadd", "add");

// Validate form
fcomprobantesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cantidadRegistros");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes->cantidadRegistros->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fcomprobantesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantesadd.ValidateRequired = true;
<?php } else { ?>
fcomprobantesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcomprobantesadd.Lists["x_discriminaIVA[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_discriminaIVA[]"].Options = <?php echo json_encode($comprobantes->discriminaIVA->Options()) ?>;
fcomprobantesadd.Lists["x_seAutoriza[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_seAutoriza[]"].Options = <?php echo json_encode($comprobantes->seAutoriza->Options()) ?>;
fcomprobantesadd.Lists["x_activo[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_activo[]"].Options = <?php echo json_encode($comprobantes->activo->Options()) ?>;
fcomprobantesadd.Lists["x_ventaStock"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_ventaStock"].Options = <?php echo json_encode($comprobantes->ventaStock->Options()) ?>;
fcomprobantesadd.Lists["x_ventaStockReservadoVenta"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_ventaStockReservadoVenta"].Options = <?php echo json_encode($comprobantes->ventaStockReservadoVenta->Options()) ?>;
fcomprobantesadd.Lists["x_ventaStockReservadoCompra"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_ventaStockReservadoCompra"].Options = <?php echo json_encode($comprobantes->ventaStockReservadoCompra->Options()) ?>;
fcomprobantesadd.Lists["x_compraStock"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_compraStock"].Options = <?php echo json_encode($comprobantes->compraStock->Options()) ?>;
fcomprobantesadd.Lists["x_compraStockReservadoVenta"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_compraStockReservadoVenta"].Options = <?php echo json_encode($comprobantes->compraStockReservadoVenta->Options()) ?>;
fcomprobantesadd.Lists["x_compraStockReservadoCompra"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_compraStockReservadoCompra"].Options = <?php echo json_encode($comprobantes->compraStockReservadoCompra->Options()) ?>;
fcomprobantesadd.Lists["x_muestraPendientes[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_muestraPendientes[]"].Options = <?php echo json_encode($comprobantes->muestraPendientes->Options()) ?>;
fcomprobantesadd.Lists["x_comprobanteDefaultImportacion"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};
fcomprobantesadd.Lists["x_limitarModo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcomprobantesadd.Lists["x_limitarModo"].Options = <?php echo json_encode($comprobantes->limitarModo->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$comprobantes_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $comprobantes_add->ShowPageHeader(); ?>
<?php
$comprobantes_add->ShowMessage();
?>
<form name="fcomprobantesadd" id="fcomprobantesadd" class="<?php echo $comprobantes_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($comprobantes_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $comprobantes_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="comprobantes">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($comprobantes_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($comprobantes->denominacion->Visible) { // denominacion ?>
	<div id="r_denominacion" class="form-group">
		<label id="elh_comprobantes_denominacion" for="x_denominacion" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->denominacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->denominacion->CellAttributes() ?>>
<span id="el_comprobantes_denominacion">
<input type="text" data-table="comprobantes" data-field="x_denominacion" name="x_denominacion" id="x_denominacion" size="30" maxlength="57" placeholder="<?php echo ew_HtmlEncode($comprobantes->denominacion->getPlaceHolder()) ?>" value="<?php echo $comprobantes->denominacion->EditValue ?>"<?php echo $comprobantes->denominacion->EditAttributes() ?>>
</span>
<?php echo $comprobantes->denominacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->denominacionCorta->Visible) { // denominacionCorta ?>
	<div id="r_denominacionCorta" class="form-group">
		<label id="elh_comprobantes_denominacionCorta" for="x_denominacionCorta" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->denominacionCorta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->denominacionCorta->CellAttributes() ?>>
<span id="el_comprobantes_denominacionCorta">
<input type="text" data-table="comprobantes" data-field="x_denominacionCorta" name="x_denominacionCorta" id="x_denominacionCorta" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($comprobantes->denominacionCorta->getPlaceHolder()) ?>" value="<?php echo $comprobantes->denominacionCorta->EditValue ?>"<?php echo $comprobantes->denominacionCorta->EditAttributes() ?>>
</span>
<?php echo $comprobantes->denominacionCorta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->discriminaIVA->Visible) { // discriminaIVA ?>
	<div id="r_discriminaIVA" class="form-group">
		<label id="elh_comprobantes_discriminaIVA" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->discriminaIVA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->discriminaIVA->CellAttributes() ?>>
<span id="el_comprobantes_discriminaIVA">
<div id="tp_x_discriminaIVA" class="ewTemplate"><input type="checkbox" data-table="comprobantes" data-field="x_discriminaIVA" data-value-separator="<?php echo $comprobantes->discriminaIVA->DisplayValueSeparatorAttribute() ?>" name="x_discriminaIVA[]" id="x_discriminaIVA[]" value="{value}"<?php echo $comprobantes->discriminaIVA->EditAttributes() ?>></div>
<div id="dsl_x_discriminaIVA" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $comprobantes->discriminaIVA->CheckBoxListHtml(FALSE, "x_discriminaIVA[]") ?>
</div></div>
</span>
<?php echo $comprobantes->discriminaIVA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->seAutoriza->Visible) { // seAutoriza ?>
	<div id="r_seAutoriza" class="form-group">
		<label id="elh_comprobantes_seAutoriza" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->seAutoriza->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->seAutoriza->CellAttributes() ?>>
<span id="el_comprobantes_seAutoriza">
<div id="tp_x_seAutoriza" class="ewTemplate"><input type="checkbox" data-table="comprobantes" data-field="x_seAutoriza" data-value-separator="<?php echo $comprobantes->seAutoriza->DisplayValueSeparatorAttribute() ?>" name="x_seAutoriza[]" id="x_seAutoriza[]" value="{value}"<?php echo $comprobantes->seAutoriza->EditAttributes() ?>></div>
<div id="dsl_x_seAutoriza" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $comprobantes->seAutoriza->CheckBoxListHtml(FALSE, "x_seAutoriza[]") ?>
</div></div>
</span>
<?php echo $comprobantes->seAutoriza->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->letra->Visible) { // letra ?>
	<div id="r_letra" class="form-group">
		<label id="elh_comprobantes_letra" for="x_letra" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->letra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->letra->CellAttributes() ?>>
<span id="el_comprobantes_letra">
<input type="text" data-table="comprobantes" data-field="x_letra" name="x_letra" id="x_letra" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($comprobantes->letra->getPlaceHolder()) ?>" value="<?php echo $comprobantes->letra->EditValue ?>"<?php echo $comprobantes->letra->EditAttributes() ?>>
</span>
<?php echo $comprobantes->letra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->activo->Visible) { // activo ?>
	<div id="r_activo" class="form-group">
		<label id="elh_comprobantes_activo" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->activo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->activo->CellAttributes() ?>>
<span id="el_comprobantes_activo">
<div id="tp_x_activo" class="ewTemplate"><input type="checkbox" data-table="comprobantes" data-field="x_activo" data-value-separator="<?php echo $comprobantes->activo->DisplayValueSeparatorAttribute() ?>" name="x_activo[]" id="x_activo[]" value="{value}"<?php echo $comprobantes->activo->EditAttributes() ?>></div>
<div id="dsl_x_activo" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $comprobantes->activo->CheckBoxListHtml(FALSE, "x_activo[]") ?>
</div></div>
</span>
<?php echo $comprobantes->activo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->ventaStock->Visible) { // ventaStock ?>
	<div id="r_ventaStock" class="form-group">
		<label id="elh_comprobantes_ventaStock" for="x_ventaStock" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->ventaStock->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->ventaStock->CellAttributes() ?>>
<span id="el_comprobantes_ventaStock">
<select data-table="comprobantes" data-field="x_ventaStock" data-value-separator="<?php echo $comprobantes->ventaStock->DisplayValueSeparatorAttribute() ?>" id="x_ventaStock" name="x_ventaStock"<?php echo $comprobantes->ventaStock->EditAttributes() ?>>
<?php echo $comprobantes->ventaStock->SelectOptionListHtml("x_ventaStock") ?>
</select>
</span>
<?php echo $comprobantes->ventaStock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->ventaStockReservadoVenta->Visible) { // ventaStockReservadoVenta ?>
	<div id="r_ventaStockReservadoVenta" class="form-group">
		<label id="elh_comprobantes_ventaStockReservadoVenta" for="x_ventaStockReservadoVenta" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->ventaStockReservadoVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->ventaStockReservadoVenta->CellAttributes() ?>>
<span id="el_comprobantes_ventaStockReservadoVenta">
<select data-table="comprobantes" data-field="x_ventaStockReservadoVenta" data-value-separator="<?php echo $comprobantes->ventaStockReservadoVenta->DisplayValueSeparatorAttribute() ?>" id="x_ventaStockReservadoVenta" name="x_ventaStockReservadoVenta"<?php echo $comprobantes->ventaStockReservadoVenta->EditAttributes() ?>>
<?php echo $comprobantes->ventaStockReservadoVenta->SelectOptionListHtml("x_ventaStockReservadoVenta") ?>
</select>
</span>
<?php echo $comprobantes->ventaStockReservadoVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->ventaStockReservadoCompra->Visible) { // ventaStockReservadoCompra ?>
	<div id="r_ventaStockReservadoCompra" class="form-group">
		<label id="elh_comprobantes_ventaStockReservadoCompra" for="x_ventaStockReservadoCompra" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->ventaStockReservadoCompra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->ventaStockReservadoCompra->CellAttributes() ?>>
<span id="el_comprobantes_ventaStockReservadoCompra">
<select data-table="comprobantes" data-field="x_ventaStockReservadoCompra" data-value-separator="<?php echo $comprobantes->ventaStockReservadoCompra->DisplayValueSeparatorAttribute() ?>" id="x_ventaStockReservadoCompra" name="x_ventaStockReservadoCompra"<?php echo $comprobantes->ventaStockReservadoCompra->EditAttributes() ?>>
<?php echo $comprobantes->ventaStockReservadoCompra->SelectOptionListHtml("x_ventaStockReservadoCompra") ?>
</select>
</span>
<?php echo $comprobantes->ventaStockReservadoCompra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->compraStock->Visible) { // compraStock ?>
	<div id="r_compraStock" class="form-group">
		<label id="elh_comprobantes_compraStock" for="x_compraStock" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->compraStock->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->compraStock->CellAttributes() ?>>
<span id="el_comprobantes_compraStock">
<select data-table="comprobantes" data-field="x_compraStock" data-value-separator="<?php echo $comprobantes->compraStock->DisplayValueSeparatorAttribute() ?>" id="x_compraStock" name="x_compraStock"<?php echo $comprobantes->compraStock->EditAttributes() ?>>
<?php echo $comprobantes->compraStock->SelectOptionListHtml("x_compraStock") ?>
</select>
</span>
<?php echo $comprobantes->compraStock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->compraStockReservadoVenta->Visible) { // compraStockReservadoVenta ?>
	<div id="r_compraStockReservadoVenta" class="form-group">
		<label id="elh_comprobantes_compraStockReservadoVenta" for="x_compraStockReservadoVenta" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->compraStockReservadoVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->compraStockReservadoVenta->CellAttributes() ?>>
<span id="el_comprobantes_compraStockReservadoVenta">
<select data-table="comprobantes" data-field="x_compraStockReservadoVenta" data-value-separator="<?php echo $comprobantes->compraStockReservadoVenta->DisplayValueSeparatorAttribute() ?>" id="x_compraStockReservadoVenta" name="x_compraStockReservadoVenta"<?php echo $comprobantes->compraStockReservadoVenta->EditAttributes() ?>>
<?php echo $comprobantes->compraStockReservadoVenta->SelectOptionListHtml("x_compraStockReservadoVenta") ?>
</select>
</span>
<?php echo $comprobantes->compraStockReservadoVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->compraStockReservadoCompra->Visible) { // compraStockReservadoCompra ?>
	<div id="r_compraStockReservadoCompra" class="form-group">
		<label id="elh_comprobantes_compraStockReservadoCompra" for="x_compraStockReservadoCompra" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->compraStockReservadoCompra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->compraStockReservadoCompra->CellAttributes() ?>>
<span id="el_comprobantes_compraStockReservadoCompra">
<select data-table="comprobantes" data-field="x_compraStockReservadoCompra" data-value-separator="<?php echo $comprobantes->compraStockReservadoCompra->DisplayValueSeparatorAttribute() ?>" id="x_compraStockReservadoCompra" name="x_compraStockReservadoCompra"<?php echo $comprobantes->compraStockReservadoCompra->EditAttributes() ?>>
<?php echo $comprobantes->compraStockReservadoCompra->SelectOptionListHtml("x_compraStockReservadoCompra") ?>
</select>
</span>
<?php echo $comprobantes->compraStockReservadoCompra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->muestraPendientes->Visible) { // muestraPendientes ?>
	<div id="r_muestraPendientes" class="form-group">
		<label id="elh_comprobantes_muestraPendientes" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->muestraPendientes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->muestraPendientes->CellAttributes() ?>>
<span id="el_comprobantes_muestraPendientes">
<div id="tp_x_muestraPendientes" class="ewTemplate"><input type="checkbox" data-table="comprobantes" data-field="x_muestraPendientes" data-value-separator="<?php echo $comprobantes->muestraPendientes->DisplayValueSeparatorAttribute() ?>" name="x_muestraPendientes[]" id="x_muestraPendientes[]" value="{value}"<?php echo $comprobantes->muestraPendientes->EditAttributes() ?>></div>
<div id="dsl_x_muestraPendientes" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $comprobantes->muestraPendientes->CheckBoxListHtml(FALSE, "x_muestraPendientes[]") ?>
</div></div>
</span>
<?php echo $comprobantes->muestraPendientes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->comprobanteDefaultImportacion->Visible) { // comprobanteDefaultImportacion ?>
	<div id="r_comprobanteDefaultImportacion" class="form-group">
		<label id="elh_comprobantes_comprobanteDefaultImportacion" for="x_comprobanteDefaultImportacion" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->comprobanteDefaultImportacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->comprobanteDefaultImportacion->CellAttributes() ?>>
<span id="el_comprobantes_comprobanteDefaultImportacion">
<select data-table="comprobantes" data-field="x_comprobanteDefaultImportacion" data-value-separator="<?php echo $comprobantes->comprobanteDefaultImportacion->DisplayValueSeparatorAttribute() ?>" id="x_comprobanteDefaultImportacion" name="x_comprobanteDefaultImportacion"<?php echo $comprobantes->comprobanteDefaultImportacion->EditAttributes() ?>>
<?php echo $comprobantes->comprobanteDefaultImportacion->SelectOptionListHtml("x_comprobanteDefaultImportacion") ?>
</select>
<input type="hidden" name="s_x_comprobanteDefaultImportacion" id="s_x_comprobanteDefaultImportacion" value="<?php echo $comprobantes->comprobanteDefaultImportacion->LookupFilterQuery() ?>">
</span>
<?php echo $comprobantes->comprobanteDefaultImportacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->preimpreso->Visible) { // preimpreso ?>
	<div id="r_preimpreso" class="form-group">
		<label id="elh_comprobantes_preimpreso" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->preimpreso->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->preimpreso->CellAttributes() ?>>
<span id="el_comprobantes_preimpreso">
<div id="fd_x_preimpreso">
<span title="<?php echo $comprobantes->preimpreso->FldTitle() ? $comprobantes->preimpreso->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($comprobantes->preimpreso->ReadOnly || $comprobantes->preimpreso->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="comprobantes" data-field="x_preimpreso" name="x_preimpreso" id="x_preimpreso"<?php echo $comprobantes->preimpreso->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_preimpreso" id= "fn_x_preimpreso" value="<?php echo $comprobantes->preimpreso->Upload->FileName ?>">
<input type="hidden" name="fa_x_preimpreso" id= "fa_x_preimpreso" value="0">
<input type="hidden" name="fs_x_preimpreso" id= "fs_x_preimpreso" value="255">
<input type="hidden" name="fx_x_preimpreso" id= "fx_x_preimpreso" value="<?php echo $comprobantes->preimpreso->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_preimpreso" id= "fm_x_preimpreso" value="<?php echo $comprobantes->preimpreso->UploadMaxFileSize ?>">
</div>
<table id="ft_x_preimpreso" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $comprobantes->preimpreso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->configuracionImpresion->Visible) { // configuracionImpresion ?>
	<div id="r_configuracionImpresion" class="form-group">
		<label id="elh_comprobantes_configuracionImpresion" for="x_configuracionImpresion" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->configuracionImpresion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->configuracionImpresion->CellAttributes() ?>>
<span id="el_comprobantes_configuracionImpresion">
<textarea data-table="comprobantes" data-field="x_configuracionImpresion" name="x_configuracionImpresion" id="x_configuracionImpresion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($comprobantes->configuracionImpresion->getPlaceHolder()) ?>"<?php echo $comprobantes->configuracionImpresion->EditAttributes() ?>><?php echo $comprobantes->configuracionImpresion->EditValue ?></textarea>
</span>
<?php echo $comprobantes->configuracionImpresion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->configuracionImpresionCompra->Visible) { // configuracionImpresionCompra ?>
	<div id="r_configuracionImpresionCompra" class="form-group">
		<label id="elh_comprobantes_configuracionImpresionCompra" for="x_configuracionImpresionCompra" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->configuracionImpresionCompra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->configuracionImpresionCompra->CellAttributes() ?>>
<span id="el_comprobantes_configuracionImpresionCompra">
<textarea data-table="comprobantes" data-field="x_configuracionImpresionCompra" name="x_configuracionImpresionCompra" id="x_configuracionImpresionCompra" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($comprobantes->configuracionImpresionCompra->getPlaceHolder()) ?>"<?php echo $comprobantes->configuracionImpresionCompra->EditAttributes() ?>><?php echo $comprobantes->configuracionImpresionCompra->EditValue ?></textarea>
</span>
<?php echo $comprobantes->configuracionImpresionCompra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->cantidadRegistros->Visible) { // cantidadRegistros ?>
	<div id="r_cantidadRegistros" class="form-group">
		<label id="elh_comprobantes_cantidadRegistros" for="x_cantidadRegistros" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->cantidadRegistros->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->cantidadRegistros->CellAttributes() ?>>
<span id="el_comprobantes_cantidadRegistros">
<input type="text" data-table="comprobantes" data-field="x_cantidadRegistros" name="x_cantidadRegistros" id="x_cantidadRegistros" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes->cantidadRegistros->getPlaceHolder()) ?>" value="<?php echo $comprobantes->cantidadRegistros->EditValue ?>"<?php echo $comprobantes->cantidadRegistros->EditAttributes() ?>>
</span>
<?php echo $comprobantes->cantidadRegistros->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($comprobantes->limitarModo->Visible) { // limitarModo ?>
	<div id="r_limitarModo" class="form-group">
		<label id="elh_comprobantes_limitarModo" for="x_limitarModo" class="col-sm-2 control-label ewLabel"><?php echo $comprobantes->limitarModo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $comprobantes->limitarModo->CellAttributes() ?>>
<span id="el_comprobantes_limitarModo">
<select data-table="comprobantes" data-field="x_limitarModo" data-value-separator="<?php echo $comprobantes->limitarModo->DisplayValueSeparatorAttribute() ?>" id="x_limitarModo" name="x_limitarModo"<?php echo $comprobantes->limitarModo->EditAttributes() ?>>
<?php echo $comprobantes->limitarModo->SelectOptionListHtml("x_limitarModo") ?>
</select>
</span>
<?php echo $comprobantes->limitarModo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("comprobantes2Dnumeracion", explode(",", $comprobantes->getCurrentDetailTable())) && $comprobantes2Dnumeracion->DetailAdd) {
?>
<?php if ($comprobantes->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("comprobantes2Dnumeracion", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "comprobantes2Dnumeraciongrid.php" ?>
<?php } ?>
<?php if (!$comprobantes_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $comprobantes_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcomprobantesadd.Init();
</script>
<?php
$comprobantes_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$comprobantes_add->Page_Terminate();
?>
