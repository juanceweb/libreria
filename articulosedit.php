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

$articulos_edit = NULL; // Initialize page object first

class carticulos_edit extends carticulos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos';

	// Page object name
	var $PageObjName = 'articulos_edit';

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

		// Table object (articulos)
		if (!isset($GLOBALS["articulos"]) || get_class($GLOBALS["articulos"]) == "carticulos") {
			$GLOBALS["articulos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("articuloslist.php"));
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
		$this->denominacionExterna->SetVisibility();
		$this->denominacionInterna->SetVisibility();
		$this->idAlicuotaIva->SetVisibility();
		$this->idCategoria->SetVisibility();
		$this->idSubcateogoria->SetVisibility();
		$this->idRubro->SetVisibility();
		$this->idMarca->SetVisibility();
		$this->codigoBarras->SetVisibility();
		$this->imagenes->SetVisibility();
		$this->idPrecioCompra->SetVisibility();
		$this->calculoPrecio->SetVisibility();
		$this->rentabilidad->SetVisibility();
		$this->precioVenta->SetVisibility();
		$this->tags->SetVisibility();
		$this->detalle->SetVisibility();

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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("articuloslist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("articuloslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "articuloslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->imagenes->Upload->Index = $objForm->Index;
		$this->imagenes->Upload->UploadFile();
		$this->imagenes->CurrentValue = $this->imagenes->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->denominacionExterna->FldIsDetailKey) {
			$this->denominacionExterna->setFormValue($objForm->GetValue("x_denominacionExterna"));
		}
		if (!$this->denominacionInterna->FldIsDetailKey) {
			$this->denominacionInterna->setFormValue($objForm->GetValue("x_denominacionInterna"));
		}
		if (!$this->idAlicuotaIva->FldIsDetailKey) {
			$this->idAlicuotaIva->setFormValue($objForm->GetValue("x_idAlicuotaIva"));
		}
		if (!$this->idCategoria->FldIsDetailKey) {
			$this->idCategoria->setFormValue($objForm->GetValue("x_idCategoria"));
		}
		if (!$this->idSubcateogoria->FldIsDetailKey) {
			$this->idSubcateogoria->setFormValue($objForm->GetValue("x_idSubcateogoria"));
		}
		if (!$this->idRubro->FldIsDetailKey) {
			$this->idRubro->setFormValue($objForm->GetValue("x_idRubro"));
		}
		if (!$this->idMarca->FldIsDetailKey) {
			$this->idMarca->setFormValue($objForm->GetValue("x_idMarca"));
		}
		if (!$this->codigoBarras->FldIsDetailKey) {
			$this->codigoBarras->setFormValue($objForm->GetValue("x_codigoBarras"));
		}
		if (!$this->idPrecioCompra->FldIsDetailKey) {
			$this->idPrecioCompra->setFormValue($objForm->GetValue("x_idPrecioCompra"));
		}
		if (!$this->calculoPrecio->FldIsDetailKey) {
			$this->calculoPrecio->setFormValue($objForm->GetValue("x_calculoPrecio"));
		}
		if (!$this->rentabilidad->FldIsDetailKey) {
			$this->rentabilidad->setFormValue($objForm->GetValue("x_rentabilidad"));
		}
		if (!$this->precioVenta->FldIsDetailKey) {
			$this->precioVenta->setFormValue($objForm->GetValue("x_precioVenta"));
		}
		if (!$this->tags->FldIsDetailKey) {
			$this->tags->setFormValue($objForm->GetValue("x_tags"));
		}
		if (!$this->detalle->FldIsDetailKey) {
			$this->detalle->setFormValue($objForm->GetValue("x_detalle"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->denominacionExterna->CurrentValue = $this->denominacionExterna->FormValue;
		$this->denominacionInterna->CurrentValue = $this->denominacionInterna->FormValue;
		$this->idAlicuotaIva->CurrentValue = $this->idAlicuotaIva->FormValue;
		$this->idCategoria->CurrentValue = $this->idCategoria->FormValue;
		$this->idSubcateogoria->CurrentValue = $this->idSubcateogoria->FormValue;
		$this->idRubro->CurrentValue = $this->idRubro->FormValue;
		$this->idMarca->CurrentValue = $this->idMarca->FormValue;
		$this->codigoBarras->CurrentValue = $this->codigoBarras->FormValue;
		$this->idPrecioCompra->CurrentValue = $this->idPrecioCompra->FormValue;
		$this->calculoPrecio->CurrentValue = $this->calculoPrecio->FormValue;
		$this->rentabilidad->CurrentValue = $this->rentabilidad->FormValue;
		$this->precioVenta->CurrentValue = $this->precioVenta->FormValue;
		$this->tags->CurrentValue = $this->tags->FormValue;
		$this->detalle->CurrentValue = $this->detalle->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
		// denominacionExterna
		// denominacionInterna
		// idAlicuotaIva
		// idCategoria
		// idSubcateogoria
		// idRubro
		// idMarca
		// fabricante
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

		// imagenes
		if (!ew_Empty($this->imagenes->Upload->DbValue)) {
			$this->imagenes->ViewValue = $this->imagenes->Upload->DbValue;
		} else {
			$this->imagenes->ViewValue = "";
		}
		$this->imagenes->ViewCustomAttributes = "";

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

		// tags
		$this->tags->ViewValue = $this->tags->CurrentValue;
		$this->tags->ViewCustomAttributes = "";

		// detalle
		$this->detalle->ViewValue = $this->detalle->CurrentValue;
		$this->detalle->ViewCustomAttributes = "";

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

			// codigoBarras
			$this->codigoBarras->LinkCustomAttributes = "";
			$this->codigoBarras->HrefValue = "";
			$this->codigoBarras->TooltipValue = "";

			// imagenes
			$this->imagenes->LinkCustomAttributes = "";
			$this->imagenes->HrefValue = "";
			$this->imagenes->HrefValue2 = $this->imagenes->UploadPath . $this->imagenes->Upload->DbValue;
			$this->imagenes->TooltipValue = "";

			// idPrecioCompra
			$this->idPrecioCompra->LinkCustomAttributes = "";
			$this->idPrecioCompra->HrefValue = "";
			$this->idPrecioCompra->TooltipValue = "";

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

			// tags
			$this->tags->LinkCustomAttributes = "";
			$this->tags->HrefValue = "";
			$this->tags->TooltipValue = "";

			// detalle
			$this->detalle->LinkCustomAttributes = "";
			$this->detalle->HrefValue = "";
			$this->detalle->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// denominacionExterna
			$this->denominacionExterna->EditAttrs["class"] = "form-control";
			$this->denominacionExterna->EditCustomAttributes = "";
			$this->denominacionExterna->EditValue = ew_HtmlEncode($this->denominacionExterna->CurrentValue);
			$this->denominacionExterna->PlaceHolder = ew_RemoveHtml($this->denominacionExterna->FldCaption());

			// denominacionInterna
			$this->denominacionInterna->EditAttrs["class"] = "form-control";
			$this->denominacionInterna->EditCustomAttributes = "";
			$this->denominacionInterna->EditValue = ew_HtmlEncode($this->denominacionInterna->CurrentValue);
			$this->denominacionInterna->PlaceHolder = ew_RemoveHtml($this->denominacionInterna->FldCaption());

			// idAlicuotaIva
			$this->idAlicuotaIva->EditAttrs["class"] = "form-control";
			$this->idAlicuotaIva->EditCustomAttributes = "";
			if (trim(strval($this->idAlicuotaIva->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idAlicuotaIva->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `valor` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `alicuotas-iva`";
			$sWhereWrk = "";
			$this->idAlicuotaIva->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idAlicuotaIva, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `valor` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idAlicuotaIva->EditValue = $arwrk;

			// idCategoria
			$this->idCategoria->EditAttrs["class"] = "form-control";
			$this->idCategoria->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idCategoria->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->idSubcateogoria->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->idRubro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->idMarca->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->CurrentValue, EW_DATATYPE_NUMBER, "");
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

			// codigoBarras
			$this->codigoBarras->EditAttrs["class"] = "form-control";
			$this->codigoBarras->EditCustomAttributes = "";
			$this->codigoBarras->EditValue = ew_HtmlEncode($this->codigoBarras->CurrentValue);
			$this->codigoBarras->PlaceHolder = ew_RemoveHtml($this->codigoBarras->FldCaption());

			// imagenes
			$this->imagenes->EditAttrs["class"] = "form-control";
			$this->imagenes->EditCustomAttributes = "";
			if (!ew_Empty($this->imagenes->Upload->DbValue)) {
				$this->imagenes->EditValue = $this->imagenes->Upload->DbValue;
			} else {
				$this->imagenes->EditValue = "";
			}
			if (!ew_Empty($this->imagenes->CurrentValue))
				$this->imagenes->Upload->FileName = $this->imagenes->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagenes);

			// idPrecioCompra
			$this->idPrecioCompra->EditAttrs["class"] = "form-control";
			$this->idPrecioCompra->EditCustomAttributes = "";
			if (trim(strval($this->idPrecioCompra->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `precios-compra`";
			$sWhereWrk = "";
			$this->idPrecioCompra->LookupFilters = array();
			$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][3] = ew_FormatDateTime($arwrk[$rowcntwrk][3], 0);
			}
			$this->idPrecioCompra->EditValue = $arwrk;

			// calculoPrecio
			$this->calculoPrecio->EditAttrs["class"] = "form-control";
			$this->calculoPrecio->EditCustomAttributes = "";
			$this->calculoPrecio->EditValue = $this->calculoPrecio->Options(TRUE);

			// rentabilidad
			$this->rentabilidad->EditAttrs["class"] = "form-control";
			$this->rentabilidad->EditCustomAttributes = "";
			$this->rentabilidad->EditValue = ew_HtmlEncode($this->rentabilidad->CurrentValue);
			$this->rentabilidad->PlaceHolder = ew_RemoveHtml($this->rentabilidad->FldCaption());
			if (strval($this->rentabilidad->EditValue) <> "" && is_numeric($this->rentabilidad->EditValue)) $this->rentabilidad->EditValue = ew_FormatNumber($this->rentabilidad->EditValue, -2, -1, -2, 0);

			// precioVenta
			$this->precioVenta->EditAttrs["class"] = "form-control";
			$this->precioVenta->EditCustomAttributes = "";
			$this->precioVenta->EditValue = ew_HtmlEncode($this->precioVenta->CurrentValue);
			$this->precioVenta->PlaceHolder = ew_RemoveHtml($this->precioVenta->FldCaption());
			if (strval($this->precioVenta->EditValue) <> "" && is_numeric($this->precioVenta->EditValue)) $this->precioVenta->EditValue = ew_FormatNumber($this->precioVenta->EditValue, -2, -1, -2, 0);

			// tags
			$this->tags->EditAttrs["class"] = "form-control";
			$this->tags->EditCustomAttributes = "";
			$this->tags->EditValue = ew_HtmlEncode($this->tags->CurrentValue);
			$this->tags->PlaceHolder = ew_RemoveHtml($this->tags->FldCaption());

			// detalle
			$this->detalle->EditAttrs["class"] = "form-control";
			$this->detalle->EditCustomAttributes = "";
			$this->detalle->EditValue = ew_HtmlEncode($this->detalle->CurrentValue);
			$this->detalle->PlaceHolder = ew_RemoveHtml($this->detalle->FldCaption());

			// Edit refer script
			// denominacionExterna

			$this->denominacionExterna->LinkCustomAttributes = "";
			$this->denominacionExterna->HrefValue = "";

			// denominacionInterna
			$this->denominacionInterna->LinkCustomAttributes = "";
			$this->denominacionInterna->HrefValue = "";

			// idAlicuotaIva
			$this->idAlicuotaIva->LinkCustomAttributes = "";
			$this->idAlicuotaIva->HrefValue = "";

			// idCategoria
			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";

			// idSubcateogoria
			$this->idSubcateogoria->LinkCustomAttributes = "";
			$this->idSubcateogoria->HrefValue = "";

			// idRubro
			$this->idRubro->LinkCustomAttributes = "";
			$this->idRubro->HrefValue = "";

			// idMarca
			$this->idMarca->LinkCustomAttributes = "";
			$this->idMarca->HrefValue = "";

			// codigoBarras
			$this->codigoBarras->LinkCustomAttributes = "";
			$this->codigoBarras->HrefValue = "";

			// imagenes
			$this->imagenes->LinkCustomAttributes = "";
			$this->imagenes->HrefValue = "";
			$this->imagenes->HrefValue2 = $this->imagenes->UploadPath . $this->imagenes->Upload->DbValue;

			// idPrecioCompra
			$this->idPrecioCompra->LinkCustomAttributes = "";
			$this->idPrecioCompra->HrefValue = "";

			// calculoPrecio
			$this->calculoPrecio->LinkCustomAttributes = "";
			$this->calculoPrecio->HrefValue = "";

			// rentabilidad
			$this->rentabilidad->LinkCustomAttributes = "";
			$this->rentabilidad->HrefValue = "";

			// precioVenta
			$this->precioVenta->LinkCustomAttributes = "";
			$this->precioVenta->HrefValue = "";

			// tags
			$this->tags->LinkCustomAttributes = "";
			$this->tags->HrefValue = "";

			// detalle
			$this->detalle->LinkCustomAttributes = "";
			$this->detalle->HrefValue = "";
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
		if (!$this->denominacionExterna->FldIsDetailKey && !is_null($this->denominacionExterna->FormValue) && $this->denominacionExterna->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->denominacionExterna->FldCaption(), $this->denominacionExterna->ReqErrMsg));
		}
		if (!$this->idAlicuotaIva->FldIsDetailKey && !is_null($this->idAlicuotaIva->FormValue) && $this->idAlicuotaIva->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idAlicuotaIva->FldCaption(), $this->idAlicuotaIva->ReqErrMsg));
		}
		if (!$this->calculoPrecio->FldIsDetailKey && !is_null($this->calculoPrecio->FormValue) && $this->calculoPrecio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->calculoPrecio->FldCaption(), $this->calculoPrecio->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->rentabilidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->rentabilidad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->precioVenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->precioVenta->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("articulos2Dproveedores", $DetailTblVar) && $GLOBALS["articulos2Dproveedores"]->DetailEdit) {
			if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid(); // get detail page object
			$GLOBALS["articulos2Dproveedores_grid"]->ValidateGridForm();
		}
		if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["articulos2Dterceros2Ddescuentos"]->DetailEdit) {
			if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid(); // get detail page object
			$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->ValidateGridForm();
		}
		if (in_array("articulos2Dstock", $DetailTblVar) && $GLOBALS["articulos2Dstock"]->DetailEdit) {
			if (!isset($GLOBALS["articulos2Dstock_grid"])) $GLOBALS["articulos2Dstock_grid"] = new carticulos2Dstock_grid(); // get detail page object
			$GLOBALS["articulos2Dstock_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// denominacionExterna
			$this->denominacionExterna->SetDbValueDef($rsnew, $this->denominacionExterna->CurrentValue, NULL, $this->denominacionExterna->ReadOnly);

			// denominacionInterna
			$this->denominacionInterna->SetDbValueDef($rsnew, $this->denominacionInterna->CurrentValue, NULL, $this->denominacionInterna->ReadOnly);

			// idAlicuotaIva
			$this->idAlicuotaIva->SetDbValueDef($rsnew, $this->idAlicuotaIva->CurrentValue, NULL, $this->idAlicuotaIva->ReadOnly);

			// idCategoria
			$this->idCategoria->SetDbValueDef($rsnew, $this->idCategoria->CurrentValue, NULL, $this->idCategoria->ReadOnly);

			// idSubcateogoria
			$this->idSubcateogoria->SetDbValueDef($rsnew, $this->idSubcateogoria->CurrentValue, NULL, $this->idSubcateogoria->ReadOnly);

			// idRubro
			$this->idRubro->SetDbValueDef($rsnew, $this->idRubro->CurrentValue, NULL, $this->idRubro->ReadOnly);

			// idMarca
			$this->idMarca->SetDbValueDef($rsnew, $this->idMarca->CurrentValue, NULL, $this->idMarca->ReadOnly);

			// codigoBarras
			$this->codigoBarras->SetDbValueDef($rsnew, $this->codigoBarras->CurrentValue, NULL, $this->codigoBarras->ReadOnly);

			// imagenes
			if ($this->imagenes->Visible && !$this->imagenes->ReadOnly && !$this->imagenes->Upload->KeepFile) {
				$this->imagenes->Upload->DbValue = $rsold['imagenes']; // Get original value
				if ($this->imagenes->Upload->FileName == "") {
					$rsnew['imagenes'] = NULL;
				} else {
					$rsnew['imagenes'] = $this->imagenes->Upload->FileName;
				}
			}

			// idPrecioCompra
			$this->idPrecioCompra->SetDbValueDef($rsnew, $this->idPrecioCompra->CurrentValue, NULL, $this->idPrecioCompra->ReadOnly);

			// calculoPrecio
			$this->calculoPrecio->SetDbValueDef($rsnew, $this->calculoPrecio->CurrentValue, NULL, $this->calculoPrecio->ReadOnly);

			// rentabilidad
			$this->rentabilidad->SetDbValueDef($rsnew, $this->rentabilidad->CurrentValue, NULL, $this->rentabilidad->ReadOnly);

			// precioVenta
			$this->precioVenta->SetDbValueDef($rsnew, $this->precioVenta->CurrentValue, NULL, $this->precioVenta->ReadOnly);

			// tags
			$this->tags->SetDbValueDef($rsnew, $this->tags->CurrentValue, NULL, $this->tags->ReadOnly);

			// detalle
			$this->detalle->SetDbValueDef($rsnew, $this->detalle->CurrentValue, NULL, $this->detalle->ReadOnly);
			if ($this->imagenes->Visible && !$this->imagenes->Upload->KeepFile) {
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->imagenes->Upload->DbValue);
				if (!ew_Empty($this->imagenes->Upload->FileName)) {
					$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->imagenes->Upload->FileName);
					$FileCount = count($NewFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						$fldvar = ($this->imagenes->Upload->Index < 0) ? $this->imagenes->FldVar : substr($this->imagenes->FldVar, 0, 1) . $this->imagenes->Upload->Index . substr($this->imagenes->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->imagenes->TblVar) . EW_PATH_DELIMITER . $file)) {
								if (!in_array($file, $OldFiles)) {
									$file1 = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagenes->UploadPath), $file); // Get new file name
									if ($file1 <> $file) { // Rename temp file
										while (file_exists(ew_UploadTempPath($fldvar, $this->imagenes->TblVar) . EW_PATH_DELIMITER . $file1)) // Make sure did not clash with existing upload file
											$file1 = ew_UniqueFilename(ew_UploadPathEx(TRUE, $this->imagenes->UploadPath), $file1, TRUE); // Use indexed name
										rename(ew_UploadTempPath($fldvar, $this->imagenes->TblVar) . EW_PATH_DELIMITER . $file, ew_UploadTempPath($fldvar, $this->imagenes->TblVar) . EW_PATH_DELIMITER . $file1);
										$NewFiles[$i] = $file1;
									}
								}
							}
						}
					}
					$this->imagenes->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$rsnew['imagenes'] = $this->imagenes->Upload->FileName;
				} else {
					$NewFiles = array();
				}
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
					if ($this->imagenes->Visible && !$this->imagenes->Upload->KeepFile) {
						$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->imagenes->Upload->DbValue);
						if (!ew_Empty($this->imagenes->Upload->FileName)) {
							$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->imagenes->Upload->FileName);
							$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['imagenes']);
							$FileCount = count($NewFiles);
							for ($i = 0; $i < $FileCount; $i++) {
								$fldvar = ($this->imagenes->Upload->Index < 0) ? $this->imagenes->FldVar : substr($this->imagenes->FldVar, 0, 1) . $this->imagenes->Upload->Index . substr($this->imagenes->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->imagenes->TblVar) . EW_PATH_DELIMITER . $NewFiles[$i];
									if (file_exists($file)) {
										$this->imagenes->Upload->SaveToFile($this->imagenes->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i); // Just replace
									}
								}
							}
						} else {
							$NewFiles = array();
						}
					}
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("articulos2Dproveedores", $DetailTblVar) && $GLOBALS["articulos2Dproveedores"]->DetailEdit) {
						if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "articulos-proveedores"); // Load user level of detail table
						$EditRow = $GLOBALS["articulos2Dproveedores_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["articulos2Dterceros2Ddescuentos"]->DetailEdit) {
						if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "articulos-terceros-descuentos"); // Load user level of detail table
						$EditRow = $GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("articulos2Dstock", $DetailTblVar) && $GLOBALS["articulos2Dstock"]->DetailEdit) {
						if (!isset($GLOBALS["articulos2Dstock_grid"])) $GLOBALS["articulos2Dstock_grid"] = new carticulos2Dstock_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "articulos-stock"); // Load user level of detail table
						$EditRow = $GLOBALS["articulos2Dstock_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

		// imagenes
		ew_CleanUploadTempPath($this->imagenes, $this->imagenes->Upload->Index);
		return $EditRow;
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
			if (in_array("articulos2Dproveedores", $DetailTblVar)) {
				if (!isset($GLOBALS["articulos2Dproveedores_grid"]))
					$GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid;
				if ($GLOBALS["articulos2Dproveedores_grid"]->DetailEdit) {
					$GLOBALS["articulos2Dproveedores_grid"]->CurrentMode = "edit";
					$GLOBALS["articulos2Dproveedores_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["articulos2Dproveedores_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["articulos2Dproveedores_grid"]->setStartRecordNumber(1);
					$GLOBALS["articulos2Dproveedores_grid"]->idArticulo->FldIsDetailKey = TRUE;
					$GLOBALS["articulos2Dproveedores_grid"]->idArticulo->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["articulos2Dproveedores_grid"]->idArticulo->setSessionValue($GLOBALS["articulos2Dproveedores_grid"]->idArticulo->CurrentValue);
				}
			}
			if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar)) {
				if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]))
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid;
				if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailEdit) {
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentMode = "edit";
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->setStartRecordNumber(1);
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idArticulo->FldIsDetailKey = TRUE;
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idArticulo->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idArticulo->setSessionValue($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->idArticulo->CurrentValue);
				}
			}
			if (in_array("articulos2Dstock", $DetailTblVar)) {
				if (!isset($GLOBALS["articulos2Dstock_grid"]))
					$GLOBALS["articulos2Dstock_grid"] = new carticulos2Dstock_grid;
				if ($GLOBALS["articulos2Dstock_grid"]->DetailEdit) {
					$GLOBALS["articulos2Dstock_grid"]->CurrentMode = "edit";
					$GLOBALS["articulos2Dstock_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["articulos2Dstock_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["articulos2Dstock_grid"]->setStartRecordNumber(1);
					$GLOBALS["articulos2Dstock_grid"]->idArticulo->FldIsDetailKey = TRUE;
					$GLOBALS["articulos2Dstock_grid"]->idArticulo->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["articulos2Dstock_grid"]->idArticulo->setSessionValue($GLOBALS["articulos2Dstock_grid"]->idArticulo->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("articuloslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idAlicuotaIva":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `valor` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `alicuotas-iva`";
			$sWhereWrk = "";
			$this->idAlicuotaIva->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idAlicuotaIva, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `valor` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
		case "x_idPrecioCompra":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `precios-compra`";
			$sWhereWrk = "";
			$this->idPrecioCompra->LookupFilters = array();
			$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `precioPesos` ASC";
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
if (!isset($articulos_edit)) $articulos_edit = new carticulos_edit();

// Page init
$articulos_edit->Page_Init();

// Page main
$articulos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = farticulosedit = new ew_Form("farticulosedit", "edit");

// Validate form
farticulosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_denominacionExterna");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos->denominacionExterna->FldCaption(), $articulos->denominacionExterna->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idAlicuotaIva");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos->idAlicuotaIva->FldCaption(), $articulos->idAlicuotaIva->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_calculoPrecio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos->calculoPrecio->FldCaption(), $articulos->calculoPrecio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rentabilidad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->rentabilidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_precioVenta");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->precioVenta->FldErrMsg()) ?>");

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
farticulosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulosedit.ValidateRequired = true;
<?php } else { ?>
farticulosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulosedit.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulosedit.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
farticulosedit.Lists["x_idSubcateogoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
farticulosedit.Lists["x_idRubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rubros"};
farticulosedit.Lists["x_idMarca"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
farticulosedit.Lists["x_idPrecioCompra"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_precioPesos","x_denominacion","x_ultimaActualizacion",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"precios2Dcompra"};
farticulosedit.Lists["x_calculoPrecio"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
farticulosedit.Lists["x_calculoPrecio"].Options = <?php echo json_encode($articulos->calculoPrecio->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$articulos_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $articulos_edit->ShowPageHeader(); ?>
<?php
$articulos_edit->ShowMessage();
?>
<form name="farticulosedit" id="farticulosedit" class="<?php echo $articulos_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($articulos_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
	<div id="r_denominacionExterna" class="form-group">
		<label id="elh_articulos_denominacionExterna" for="x_denominacionExterna" class="col-sm-2 control-label ewLabel"><?php echo $articulos->denominacionExterna->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->denominacionExterna->CellAttributes() ?>>
<span id="el_articulos_denominacionExterna">
<input type="text" data-table="articulos" data-field="x_denominacionExterna" name="x_denominacionExterna" id="x_denominacionExterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionExterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionExterna->EditValue ?>"<?php echo $articulos->denominacionExterna->EditAttributes() ?>>
</span>
<?php echo $articulos->denominacionExterna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
	<div id="r_denominacionInterna" class="form-group">
		<label id="elh_articulos_denominacionInterna" for="x_denominacionInterna" class="col-sm-2 control-label ewLabel"><?php echo $articulos->denominacionInterna->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->denominacionInterna->CellAttributes() ?>>
<span id="el_articulos_denominacionInterna">
<input type="text" data-table="articulos" data-field="x_denominacionInterna" name="x_denominacionInterna" id="x_denominacionInterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionInterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionInterna->EditValue ?>"<?php echo $articulos->denominacionInterna->EditAttributes() ?>>
</span>
<?php echo $articulos->denominacionInterna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<div id="r_idAlicuotaIva" class="form-group">
		<label id="elh_articulos_idAlicuotaIva" for="x_idAlicuotaIva" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idAlicuotaIva->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idAlicuotaIva->CellAttributes() ?>>
<span id="el_articulos_idAlicuotaIva">
<select data-table="articulos" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x_idAlicuotaIva" name="x_idAlicuotaIva"<?php echo $articulos->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos->idAlicuotaIva->SelectOptionListHtml("x_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x_idAlicuotaIva" id="s_x_idAlicuotaIva" value="<?php echo $articulos->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idAlicuotaIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
	<div id="r_idCategoria" class="form-group">
		<label id="elh_articulos_idCategoria" for="x_idCategoria" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idCategoria->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idCategoria->CellAttributes() ?>>
<span id="el_articulos_idCategoria">
<select data-table="articulos" data-field="x_idCategoria" data-value-separator="<?php echo $articulos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x_idCategoria" name="x_idCategoria"<?php echo $articulos->idCategoria->EditAttributes() ?>>
<?php echo $articulos->idCategoria->SelectOptionListHtml("x_idCategoria") ?>
</select>
<input type="hidden" name="s_x_idCategoria" id="s_x_idCategoria" value="<?php echo $articulos->idCategoria->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idCategoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
	<div id="r_idSubcateogoria" class="form-group">
		<label id="elh_articulos_idSubcateogoria" for="x_idSubcateogoria" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idSubcateogoria->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idSubcateogoria->CellAttributes() ?>>
<span id="el_articulos_idSubcateogoria">
<select data-table="articulos" data-field="x_idSubcateogoria" data-value-separator="<?php echo $articulos->idSubcateogoria->DisplayValueSeparatorAttribute() ?>" id="x_idSubcateogoria" name="x_idSubcateogoria"<?php echo $articulos->idSubcateogoria->EditAttributes() ?>>
<?php echo $articulos->idSubcateogoria->SelectOptionListHtml("x_idSubcateogoria") ?>
</select>
<input type="hidden" name="s_x_idSubcateogoria" id="s_x_idSubcateogoria" value="<?php echo $articulos->idSubcateogoria->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idSubcateogoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
	<div id="r_idRubro" class="form-group">
		<label id="elh_articulos_idRubro" for="x_idRubro" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idRubro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idRubro->CellAttributes() ?>>
<span id="el_articulos_idRubro">
<select data-table="articulos" data-field="x_idRubro" data-value-separator="<?php echo $articulos->idRubro->DisplayValueSeparatorAttribute() ?>" id="x_idRubro" name="x_idRubro"<?php echo $articulos->idRubro->EditAttributes() ?>>
<?php echo $articulos->idRubro->SelectOptionListHtml("x_idRubro") ?>
</select>
<input type="hidden" name="s_x_idRubro" id="s_x_idRubro" value="<?php echo $articulos->idRubro->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idRubro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
	<div id="r_idMarca" class="form-group">
		<label id="elh_articulos_idMarca" for="x_idMarca" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idMarca->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idMarca->CellAttributes() ?>>
<span id="el_articulos_idMarca">
<select data-table="articulos" data-field="x_idMarca" data-value-separator="<?php echo $articulos->idMarca->DisplayValueSeparatorAttribute() ?>" id="x_idMarca" name="x_idMarca"<?php echo $articulos->idMarca->EditAttributes() ?>>
<?php echo $articulos->idMarca->SelectOptionListHtml("x_idMarca") ?>
</select>
<input type="hidden" name="s_x_idMarca" id="s_x_idMarca" value="<?php echo $articulos->idMarca->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idMarca->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->codigoBarras->Visible) { // codigoBarras ?>
	<div id="r_codigoBarras" class="form-group">
		<label id="elh_articulos_codigoBarras" for="x_codigoBarras" class="col-sm-2 control-label ewLabel"><?php echo $articulos->codigoBarras->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->codigoBarras->CellAttributes() ?>>
<span id="el_articulos_codigoBarras">
<input type="text" data-table="articulos" data-field="x_codigoBarras" name="x_codigoBarras" id="x_codigoBarras" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($articulos->codigoBarras->getPlaceHolder()) ?>" value="<?php echo $articulos->codigoBarras->EditValue ?>"<?php echo $articulos->codigoBarras->EditAttributes() ?>>
</span>
<?php echo $articulos->codigoBarras->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->imagenes->Visible) { // imagenes ?>
	<div id="r_imagenes" class="form-group">
		<label id="elh_articulos_imagenes" class="col-sm-2 control-label ewLabel"><?php echo $articulos->imagenes->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->imagenes->CellAttributes() ?>>
<span id="el_articulos_imagenes">
<div id="fd_x_imagenes">
<span title="<?php echo $articulos->imagenes->FldTitle() ? $articulos->imagenes->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($articulos->imagenes->ReadOnly || $articulos->imagenes->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="articulos" data-field="x_imagenes" name="x_imagenes" id="x_imagenes" multiple="multiple"<?php echo $articulos->imagenes->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_imagenes" id= "fn_x_imagenes" value="<?php echo $articulos->imagenes->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagenes"] == "0") { ?>
<input type="hidden" name="fa_x_imagenes" id= "fa_x_imagenes" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagenes" id= "fa_x_imagenes" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagenes" id= "fs_x_imagenes" value="65535">
<input type="hidden" name="fx_x_imagenes" id= "fx_x_imagenes" value="<?php echo $articulos->imagenes->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_imagenes" id= "fm_x_imagenes" value="<?php echo $articulos->imagenes->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_imagenes" id= "fc_x_imagenes" value="<?php echo $articulos->imagenes->UploadMaxFileCount ?>">
</div>
<table id="ft_x_imagenes" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $articulos->imagenes->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
	<div id="r_idPrecioCompra" class="form-group">
		<label id="elh_articulos_idPrecioCompra" for="x_idPrecioCompra" class="col-sm-2 control-label ewLabel"><?php echo $articulos->idPrecioCompra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->idPrecioCompra->CellAttributes() ?>>
<span id="el_articulos_idPrecioCompra">
<select data-table="articulos" data-field="x_idPrecioCompra" data-value-separator="<?php echo $articulos->idPrecioCompra->DisplayValueSeparatorAttribute() ?>" id="x_idPrecioCompra" name="x_idPrecioCompra"<?php echo $articulos->idPrecioCompra->EditAttributes() ?>>
<?php echo $articulos->idPrecioCompra->SelectOptionListHtml("x_idPrecioCompra") ?>
</select>
<input type="hidden" name="s_x_idPrecioCompra" id="s_x_idPrecioCompra" value="<?php echo $articulos->idPrecioCompra->LookupFilterQuery() ?>">
</span>
<?php echo $articulos->idPrecioCompra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
	<div id="r_calculoPrecio" class="form-group">
		<label id="elh_articulos_calculoPrecio" for="x_calculoPrecio" class="col-sm-2 control-label ewLabel"><?php echo $articulos->calculoPrecio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->calculoPrecio->CellAttributes() ?>>
<span id="el_articulos_calculoPrecio">
<select data-table="articulos" data-field="x_calculoPrecio" data-value-separator="<?php echo $articulos->calculoPrecio->DisplayValueSeparatorAttribute() ?>" id="x_calculoPrecio" name="x_calculoPrecio"<?php echo $articulos->calculoPrecio->EditAttributes() ?>>
<?php echo $articulos->calculoPrecio->SelectOptionListHtml("x_calculoPrecio") ?>
</select>
</span>
<?php echo $articulos->calculoPrecio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
	<div id="r_rentabilidad" class="form-group">
		<label id="elh_articulos_rentabilidad" for="x_rentabilidad" class="col-sm-2 control-label ewLabel"><?php echo $articulos->rentabilidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->rentabilidad->CellAttributes() ?>>
<span id="el_articulos_rentabilidad">
<input type="text" data-table="articulos" data-field="x_rentabilidad" name="x_rentabilidad" id="x_rentabilidad" size="30" placeholder="<?php echo ew_HtmlEncode($articulos->rentabilidad->getPlaceHolder()) ?>" value="<?php echo $articulos->rentabilidad->EditValue ?>"<?php echo $articulos->rentabilidad->EditAttributes() ?>>
</span>
<?php echo $articulos->rentabilidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
	<div id="r_precioVenta" class="form-group">
		<label id="elh_articulos_precioVenta" for="x_precioVenta" class="col-sm-2 control-label ewLabel"><?php echo $articulos->precioVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->precioVenta->CellAttributes() ?>>
<span id="el_articulos_precioVenta">
<input type="text" data-table="articulos" data-field="x_precioVenta" name="x_precioVenta" id="x_precioVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos->precioVenta->getPlaceHolder()) ?>" value="<?php echo $articulos->precioVenta->EditValue ?>"<?php echo $articulos->precioVenta->EditAttributes() ?>>
</span>
<?php echo $articulos->precioVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->tags->Visible) { // tags ?>
	<div id="r_tags" class="form-group">
		<label id="elh_articulos_tags" for="x_tags" class="col-sm-2 control-label ewLabel"><?php echo $articulos->tags->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->tags->CellAttributes() ?>>
<span id="el_articulos_tags">
<textarea data-table="articulos" data-field="x_tags" name="x_tags" id="x_tags" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($articulos->tags->getPlaceHolder()) ?>"<?php echo $articulos->tags->EditAttributes() ?>><?php echo $articulos->tags->EditValue ?></textarea>
</span>
<?php echo $articulos->tags->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos->detalle->Visible) { // detalle ?>
	<div id="r_detalle" class="form-group">
		<label id="elh_articulos_detalle" class="col-sm-2 control-label ewLabel"><?php echo $articulos->detalle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos->detalle->CellAttributes() ?>>
<span id="el_articulos_detalle">
<?php ew_AppendClass($articulos->detalle->EditAttrs["class"], "editor"); ?>
<textarea data-table="articulos" data-field="x_detalle" name="x_detalle" id="x_detalle" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($articulos->detalle->getPlaceHolder()) ?>"<?php echo $articulos->detalle->EditAttributes() ?>><?php echo $articulos->detalle->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("farticulosedit", "x_detalle", 35, 4, <?php echo ($articulos->detalle->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $articulos->detalle->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="articulos" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($articulos->id->CurrentValue) ?>">
<?php
	if (in_array("articulos2Dproveedores", explode(",", $articulos->getCurrentDetailTable())) && $articulos2Dproveedores->DetailEdit) {
?>
<?php if ($articulos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dproveedores", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dproveedoresgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dterceros2Ddescuentos", explode(",", $articulos->getCurrentDetailTable())) && $articulos2Dterceros2Ddescuentos->DetailEdit) {
?>
<?php if ($articulos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dstock", explode(",", $articulos->getCurrentDetailTable())) && $articulos2Dstock->DetailEdit) {
?>
<?php if ($articulos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dstock", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dstockgrid.php" ?>
<?php } ?>
<?php if (!$articulos_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
farticulosedit.Init();
</script>
<?php
$articulos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");
function calcularPrecio() {
	var id = $("#x_idPrecioCompra").val();
	var accion = "obtener-registro-articulos-proveedores";
	if (id != "") {
		$.ajax({
			type: "GET",
			url: "api.php",
			data: {
				id: id,
				accion: accion
			},
			dataType: 'json',
			success: function(data) {
				if ($("#x_calculoPrecio").val() == 1) {
					var precioVenta = ($("#x_rentabilidad").val() * parseFloat(data.exito.precioPesos) / 100) + parseFloat(data.exito.precioPesos);
					$("#x_precioVenta").val(parseFloat(precioVenta).toFixed(2));
				} else {
					var rentabilidad = ($("#x_precioVenta").val() / (data.exito.precioPesos) * 100) - 100;
					$("#x_rentabilidad").val(parseFloat(rentabilidad).toFixed(2));
				}
			}
		});
	}else{
		if ($("#x_calculoPrecio").val() == 1) {
			var precioVenta = 0;
			$("#x_precioVenta").val(parseFloat(precioVenta).toFixed(2));
		} else {
			var rentabilidad = 0
			$("#x_rentabilidad").val(parseFloat(rentabilidad).toFixed(2));
		}
	}
	if ($("#x_calculoPrecio").val() == 1) {
		$("#x_precioVenta").attr('readonly', true).attr('disabled', true);
		$("#x_rentabilidad").attr('readonly', false).attr('disabled', false);
	} else {
		$("#x_precioVenta").attr('readonly', false).attr('disabled', false);
		$("#x_rentabilidad").attr('readonly', true).attr('disabled', true);
	}
}
$("#x_calculoPrecio, #x_idPrecioCompra").change(function() {
	calcularPrecio();
})

//al escribir en el campo de precio de venta o rentabilidad
$("#x_precioVenta, #x_rentabilidad").keyup(function() {
	calcularPrecio();
})
$(document).ready(function() {
	calcularPrecio();
	var queryString = window.location.search;
	var urlParams = new URLSearchParams(queryString);
	var id = urlParams.get('id')
	var idarticulo = id;
	var accion = "obtenerunidadesporarticulo";
	$.ajax({
		url: "api.php",
		type: "post",

		//crossDomain: true,
		data: {
			accion: accion,
			idarticulo: idarticulo
		},
		dataType: "json",
		success: function(response) {
			if (response.exito) {
				$("#x_idUnidadMedidaCompra").empty();
				$("#x_idUnidadMedidaVenta").empty();
				var html = ''
				response.exito.forEach(unidad => {
					if (unidad.id == response.adicionales.unidadCompra) {
						html += '<option selected value="' + unidad.id + '">' + unidad
							.denominacion + '</option>'
					} else {
						html += '<option value="' + unidad.id + '">' + unidad.denominacion +
							'</option>'
					}
				});
				$("#x_idUnidadMedidaCompra").html(html);
				var html = ''
				response.exito.forEach(unidad => {
					if (unidad.id == response.adicionales.unidadVenta) {
						html += '<option selected value="' + unidad.id + '">' + unidad
							.denominacion + '</option>'
					} else {
						html += '<option value="' + unidad.id + '">' + unidad.denominacion +
							'</option>'
					}
				});
				$("#x_idUnidadMedidaVenta").html(html);
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
})
</script>
<?php include_once "footer.php" ?>
<?php
$articulos_edit->Page_Terminate();
?>
