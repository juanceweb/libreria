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

$terceros_add = NULL; // Initialize page object first

class cterceros_add extends cterceros {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceros';

	// Page object name
	var $PageObjName = 'terceros_add';

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

		// Table object (terceros)
		if (!isset($GLOBALS["terceros"]) || get_class($GLOBALS["terceros"]) == "cterceros") {
			$GLOBALS["terceros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["terceros"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("terceroslist.php"));
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
		$this->idTipoTercero->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->razonSocial->SetVisibility();
		$this->denominacionCorta->SetVisibility();
		$this->idPais->SetVisibility();
		$this->idProvincia->SetVisibility();
		$this->idPartido->SetVisibility();
		$this->idLocalidad->SetVisibility();
		$this->calle->SetVisibility();
		$this->direccion->SetVisibility();
		$this->domicilioFiscal->SetVisibility();
		$this->idPaisFiscal->SetVisibility();
		$this->idProvinciaFiscal->SetVisibility();
		$this->idPartidoFiscal->SetVisibility();
		$this->idLocalidadFiscal->SetVisibility();
		$this->calleFiscal->SetVisibility();
		$this->direccionFiscal->SetVisibility();
		$this->tipoDoc->SetVisibility();
		$this->documento->SetVisibility();
		$this->condicionIva->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->idTransporte->SetVisibility();
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
					$this->Page_Terminate("terceroslist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "terceroslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tercerosview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->idTipoTercero->CurrentValue = NULL;
		$this->idTipoTercero->OldValue = $this->idTipoTercero->CurrentValue;
		$this->denominacion->CurrentValue = NULL;
		$this->denominacion->OldValue = $this->denominacion->CurrentValue;
		$this->razonSocial->CurrentValue = NULL;
		$this->razonSocial->OldValue = $this->razonSocial->CurrentValue;
		$this->denominacionCorta->CurrentValue = NULL;
		$this->denominacionCorta->OldValue = $this->denominacionCorta->CurrentValue;
		$this->idPais->CurrentValue = NULL;
		$this->idPais->OldValue = $this->idPais->CurrentValue;
		$this->idProvincia->CurrentValue = NULL;
		$this->idProvincia->OldValue = $this->idProvincia->CurrentValue;
		$this->idPartido->CurrentValue = NULL;
		$this->idPartido->OldValue = $this->idPartido->CurrentValue;
		$this->idLocalidad->CurrentValue = NULL;
		$this->idLocalidad->OldValue = $this->idLocalidad->CurrentValue;
		$this->calle->CurrentValue = NULL;
		$this->calle->OldValue = $this->calle->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->domicilioFiscal->CurrentValue = NULL;
		$this->domicilioFiscal->OldValue = $this->domicilioFiscal->CurrentValue;
		$this->idPaisFiscal->CurrentValue = NULL;
		$this->idPaisFiscal->OldValue = $this->idPaisFiscal->CurrentValue;
		$this->idProvinciaFiscal->CurrentValue = NULL;
		$this->idProvinciaFiscal->OldValue = $this->idProvinciaFiscal->CurrentValue;
		$this->idPartidoFiscal->CurrentValue = NULL;
		$this->idPartidoFiscal->OldValue = $this->idPartidoFiscal->CurrentValue;
		$this->idLocalidadFiscal->CurrentValue = NULL;
		$this->idLocalidadFiscal->OldValue = $this->idLocalidadFiscal->CurrentValue;
		$this->calleFiscal->CurrentValue = NULL;
		$this->calleFiscal->OldValue = $this->calleFiscal->CurrentValue;
		$this->direccionFiscal->CurrentValue = NULL;
		$this->direccionFiscal->OldValue = $this->direccionFiscal->CurrentValue;
		$this->tipoDoc->CurrentValue = NULL;
		$this->tipoDoc->OldValue = $this->tipoDoc->CurrentValue;
		$this->documento->CurrentValue = NULL;
		$this->documento->OldValue = $this->documento->CurrentValue;
		$this->condicionIva->CurrentValue = NULL;
		$this->condicionIva->OldValue = $this->condicionIva->CurrentValue;
		$this->observaciones->CurrentValue = NULL;
		$this->observaciones->OldValue = $this->observaciones->CurrentValue;
		$this->idTransporte->CurrentValue = NULL;
		$this->idTransporte->OldValue = $this->idTransporte->CurrentValue;
		$this->idVendedor->CurrentValue = NULL;
		$this->idVendedor->OldValue = $this->idVendedor->CurrentValue;
		$this->idCobrador->CurrentValue = NULL;
		$this->idCobrador->OldValue = $this->idCobrador->CurrentValue;
		$this->comision->CurrentValue = NULL;
		$this->comision->OldValue = $this->comision->CurrentValue;
		$this->idListaPrecios->CurrentValue = NULL;
		$this->idListaPrecios->OldValue = $this->idListaPrecios->CurrentValue;
		$this->dtoCliente->CurrentValue = NULL;
		$this->dtoCliente->OldValue = $this->dtoCliente->CurrentValue;
		$this->dto1->CurrentValue = NULL;
		$this->dto1->OldValue = $this->dto1->CurrentValue;
		$this->dto2->CurrentValue = NULL;
		$this->dto2->OldValue = $this->dto2->CurrentValue;
		$this->dto3->CurrentValue = NULL;
		$this->dto3->OldValue = $this->dto3->CurrentValue;
		$this->limiteDescubierto->CurrentValue = NULL;
		$this->limiteDescubierto->OldValue = $this->limiteDescubierto->CurrentValue;
		$this->codigoPostal->CurrentValue = NULL;
		$this->codigoPostal->OldValue = $this->codigoPostal->CurrentValue;
		$this->codigoPostalFiscal->CurrentValue = NULL;
		$this->codigoPostalFiscal->OldValue = $this->codigoPostalFiscal->CurrentValue;
		$this->condicionVenta->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idTipoTercero->FldIsDetailKey) {
			$this->idTipoTercero->setFormValue($objForm->GetValue("x_idTipoTercero"));
		}
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->razonSocial->FldIsDetailKey) {
			$this->razonSocial->setFormValue($objForm->GetValue("x_razonSocial"));
		}
		if (!$this->denominacionCorta->FldIsDetailKey) {
			$this->denominacionCorta->setFormValue($objForm->GetValue("x_denominacionCorta"));
		}
		if (!$this->idPais->FldIsDetailKey) {
			$this->idPais->setFormValue($objForm->GetValue("x_idPais"));
		}
		if (!$this->idProvincia->FldIsDetailKey) {
			$this->idProvincia->setFormValue($objForm->GetValue("x_idProvincia"));
		}
		if (!$this->idPartido->FldIsDetailKey) {
			$this->idPartido->setFormValue($objForm->GetValue("x_idPartido"));
		}
		if (!$this->idLocalidad->FldIsDetailKey) {
			$this->idLocalidad->setFormValue($objForm->GetValue("x_idLocalidad"));
		}
		if (!$this->calle->FldIsDetailKey) {
			$this->calle->setFormValue($objForm->GetValue("x_calle"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->domicilioFiscal->FldIsDetailKey) {
			$this->domicilioFiscal->setFormValue($objForm->GetValue("x_domicilioFiscal"));
		}
		if (!$this->idPaisFiscal->FldIsDetailKey) {
			$this->idPaisFiscal->setFormValue($objForm->GetValue("x_idPaisFiscal"));
		}
		if (!$this->idProvinciaFiscal->FldIsDetailKey) {
			$this->idProvinciaFiscal->setFormValue($objForm->GetValue("x_idProvinciaFiscal"));
		}
		if (!$this->idPartidoFiscal->FldIsDetailKey) {
			$this->idPartidoFiscal->setFormValue($objForm->GetValue("x_idPartidoFiscal"));
		}
		if (!$this->idLocalidadFiscal->FldIsDetailKey) {
			$this->idLocalidadFiscal->setFormValue($objForm->GetValue("x_idLocalidadFiscal"));
		}
		if (!$this->calleFiscal->FldIsDetailKey) {
			$this->calleFiscal->setFormValue($objForm->GetValue("x_calleFiscal"));
		}
		if (!$this->direccionFiscal->FldIsDetailKey) {
			$this->direccionFiscal->setFormValue($objForm->GetValue("x_direccionFiscal"));
		}
		if (!$this->tipoDoc->FldIsDetailKey) {
			$this->tipoDoc->setFormValue($objForm->GetValue("x_tipoDoc"));
		}
		if (!$this->documento->FldIsDetailKey) {
			$this->documento->setFormValue($objForm->GetValue("x_documento"));
		}
		if (!$this->condicionIva->FldIsDetailKey) {
			$this->condicionIva->setFormValue($objForm->GetValue("x_condicionIva"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->idTransporte->FldIsDetailKey) {
			$this->idTransporte->setFormValue($objForm->GetValue("x_idTransporte"));
		}
		if (!$this->idVendedor->FldIsDetailKey) {
			$this->idVendedor->setFormValue($objForm->GetValue("x_idVendedor"));
		}
		if (!$this->idCobrador->FldIsDetailKey) {
			$this->idCobrador->setFormValue($objForm->GetValue("x_idCobrador"));
		}
		if (!$this->comision->FldIsDetailKey) {
			$this->comision->setFormValue($objForm->GetValue("x_comision"));
		}
		if (!$this->idListaPrecios->FldIsDetailKey) {
			$this->idListaPrecios->setFormValue($objForm->GetValue("x_idListaPrecios"));
		}
		if (!$this->dtoCliente->FldIsDetailKey) {
			$this->dtoCliente->setFormValue($objForm->GetValue("x_dtoCliente"));
		}
		if (!$this->dto1->FldIsDetailKey) {
			$this->dto1->setFormValue($objForm->GetValue("x_dto1"));
		}
		if (!$this->dto2->FldIsDetailKey) {
			$this->dto2->setFormValue($objForm->GetValue("x_dto2"));
		}
		if (!$this->dto3->FldIsDetailKey) {
			$this->dto3->setFormValue($objForm->GetValue("x_dto3"));
		}
		if (!$this->limiteDescubierto->FldIsDetailKey) {
			$this->limiteDescubierto->setFormValue($objForm->GetValue("x_limiteDescubierto"));
		}
		if (!$this->codigoPostal->FldIsDetailKey) {
			$this->codigoPostal->setFormValue($objForm->GetValue("x_codigoPostal"));
		}
		if (!$this->codigoPostalFiscal->FldIsDetailKey) {
			$this->codigoPostalFiscal->setFormValue($objForm->GetValue("x_codigoPostalFiscal"));
		}
		if (!$this->condicionVenta->FldIsDetailKey) {
			$this->condicionVenta->setFormValue($objForm->GetValue("x_condicionVenta"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idTipoTercero->CurrentValue = $this->idTipoTercero->FormValue;
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->razonSocial->CurrentValue = $this->razonSocial->FormValue;
		$this->denominacionCorta->CurrentValue = $this->denominacionCorta->FormValue;
		$this->idPais->CurrentValue = $this->idPais->FormValue;
		$this->idProvincia->CurrentValue = $this->idProvincia->FormValue;
		$this->idPartido->CurrentValue = $this->idPartido->FormValue;
		$this->idLocalidad->CurrentValue = $this->idLocalidad->FormValue;
		$this->calle->CurrentValue = $this->calle->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->domicilioFiscal->CurrentValue = $this->domicilioFiscal->FormValue;
		$this->idPaisFiscal->CurrentValue = $this->idPaisFiscal->FormValue;
		$this->idProvinciaFiscal->CurrentValue = $this->idProvinciaFiscal->FormValue;
		$this->idPartidoFiscal->CurrentValue = $this->idPartidoFiscal->FormValue;
		$this->idLocalidadFiscal->CurrentValue = $this->idLocalidadFiscal->FormValue;
		$this->calleFiscal->CurrentValue = $this->calleFiscal->FormValue;
		$this->direccionFiscal->CurrentValue = $this->direccionFiscal->FormValue;
		$this->tipoDoc->CurrentValue = $this->tipoDoc->FormValue;
		$this->documento->CurrentValue = $this->documento->FormValue;
		$this->condicionIva->CurrentValue = $this->condicionIva->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->idTransporte->CurrentValue = $this->idTransporte->FormValue;
		$this->idVendedor->CurrentValue = $this->idVendedor->FormValue;
		$this->idCobrador->CurrentValue = $this->idCobrador->FormValue;
		$this->comision->CurrentValue = $this->comision->FormValue;
		$this->idListaPrecios->CurrentValue = $this->idListaPrecios->FormValue;
		$this->dtoCliente->CurrentValue = $this->dtoCliente->FormValue;
		$this->dto1->CurrentValue = $this->dto1->FormValue;
		$this->dto2->CurrentValue = $this->dto2->FormValue;
		$this->dto3->CurrentValue = $this->dto3->FormValue;
		$this->limiteDescubierto->CurrentValue = $this->limiteDescubierto->FormValue;
		$this->codigoPostal->CurrentValue = $this->codigoPostal->FormValue;
		$this->codigoPostalFiscal->CurrentValue = $this->codigoPostalFiscal->FormValue;
		$this->condicionVenta->CurrentValue = $this->condicionVenta->FormValue;
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

			// denominacionCorta
			$this->denominacionCorta->LinkCustomAttributes = "";
			$this->denominacionCorta->HrefValue = "";
			$this->denominacionCorta->TooltipValue = "";

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

			// tipoDoc
			$this->tipoDoc->LinkCustomAttributes = "";
			$this->tipoDoc->HrefValue = "";
			$this->tipoDoc->TooltipValue = "";

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

			// idTransporte
			$this->idTransporte->LinkCustomAttributes = "";
			$this->idTransporte->HrefValue = "";
			$this->idTransporte->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idTipoTercero
			$this->idTipoTercero->EditAttrs["class"] = "form-control";
			$this->idTipoTercero->EditCustomAttributes = 'data-elemento-dependiente="true"';
			if (trim(strval($this->idTipoTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTipoTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// razonSocial
			$this->razonSocial->EditAttrs["class"] = "form-control";
			$this->razonSocial->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->razonSocial->EditValue = ew_HtmlEncode($this->razonSocial->CurrentValue);
			$this->razonSocial->PlaceHolder = ew_RemoveHtml($this->razonSocial->FldCaption());

			// denominacionCorta
			$this->denominacionCorta->EditAttrs["class"] = "form-control";
			$this->denominacionCorta->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
			$this->denominacionCorta->EditValue = ew_HtmlEncode($this->denominacionCorta->CurrentValue);
			$this->denominacionCorta->PlaceHolder = ew_RemoveHtml($this->denominacionCorta->FldCaption());

			// idPais
			$this->idPais->EditAttrs["class"] = "form-control";
			$this->idPais->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->idPais->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPais->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			$this->idPais->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPais, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPais->EditValue = $arwrk;

			// idProvincia
			$this->idProvincia->EditAttrs["class"] = "form-control";
			$this->idProvincia->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->idProvincia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvincia->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idPais` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincias`";
			$sWhereWrk = "";
			$this->idProvincia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idProvincia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idProvincia->EditValue = $arwrk;

			// idPartido
			$this->idPartido->EditAttrs["class"] = "form-control";
			$this->idPartido->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->idPartido->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartido->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idProvincia` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `partidos`";
			$sWhereWrk = "";
			$this->idPartido->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPartido, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPartido->EditValue = $arwrk;

			// idLocalidad
			$this->idLocalidad->EditAttrs["class"] = "form-control";
			$this->idLocalidad->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->idLocalidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idPartido` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `localidades`";
			$sWhereWrk = "";
			$this->idLocalidad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idLocalidad, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idLocalidad->EditValue = $arwrk;

			// calle
			$this->calle->EditAttrs["class"] = "form-control";
			$this->calle->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->calle->EditValue = ew_HtmlEncode($this->calle->CurrentValue);
			$this->calle->PlaceHolder = ew_RemoveHtml($this->calle->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// domicilioFiscal
			$this->domicilioFiscal->EditAttrs["class"] = "form-control";
			$this->domicilioFiscal->EditCustomAttributes = 'data-elemento-dependiente="true" data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->domicilioFiscal->EditValue = $this->domicilioFiscal->Options(TRUE);

			// idPaisFiscal
			$this->idPaisFiscal->EditAttrs["class"] = "form-control";
			$this->idPaisFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			if (trim(strval($this->idPaisFiscal->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPaisFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			$this->idPaisFiscal->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPaisFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPaisFiscal->EditValue = $arwrk;

			// idProvinciaFiscal
			$this->idProvinciaFiscal->EditAttrs["class"] = "form-control";
			$this->idProvinciaFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			if (trim(strval($this->idProvinciaFiscal->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idProvinciaFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idPais` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincias`";
			$sWhereWrk = "";
			$this->idProvinciaFiscal->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idProvinciaFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idProvinciaFiscal->EditValue = $arwrk;

			// idPartidoFiscal
			$this->idPartidoFiscal->EditAttrs["class"] = "form-control";
			$this->idPartidoFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			if (trim(strval($this->idPartidoFiscal->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPartidoFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idProvincia` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `partidos`";
			$sWhereWrk = "";
			$this->idPartidoFiscal->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPartidoFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idPartidoFiscal->EditValue = $arwrk;

			// idLocalidadFiscal
			$this->idLocalidadFiscal->EditAttrs["class"] = "form-control";
			$this->idLocalidadFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			if (trim(strval($this->idLocalidadFiscal->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idLocalidadFiscal->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `idPartido` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `localidades`";
			$sWhereWrk = "";
			$this->idLocalidadFiscal->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idLocalidadFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idLocalidadFiscal->EditValue = $arwrk;

			// calleFiscal
			$this->calleFiscal->EditAttrs["class"] = "form-control";
			$this->calleFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			$this->calleFiscal->EditValue = ew_HtmlEncode($this->calleFiscal->CurrentValue);
			$this->calleFiscal->PlaceHolder = ew_RemoveHtml($this->calleFiscal->FldCaption());

			// direccionFiscal
			$this->direccionFiscal->EditAttrs["class"] = "form-control";
			$this->direccionFiscal->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false},"x_domicilioFiscal":{"":false,"1":true}}\'';
			$this->direccionFiscal->EditValue = ew_HtmlEncode($this->direccionFiscal->CurrentValue);
			$this->direccionFiscal->PlaceHolder = ew_RemoveHtml($this->direccionFiscal->FldCaption());

			// tipoDoc
			$this->tipoDoc->EditAttrs["class"] = "form-control";
			$this->tipoDoc->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->tipoDoc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipoDoc->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipos-documentos`";
			$sWhereWrk = "";
			$this->tipoDoc->LookupFilters = array();
			$lookuptblfilter = "`activo`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipoDoc, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->tipoDoc->EditValue = $arwrk;

			// documento
			$this->documento->EditAttrs["class"] = "form-control";
			$this->documento->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			$this->documento->EditValue = ew_HtmlEncode($this->documento->CurrentValue);
			$this->documento->PlaceHolder = ew_RemoveHtml($this->documento->FldCaption());

			// condicionIva
			$this->condicionIva->EditAttrs["class"] = "form-control";
			$this->condicionIva->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":false}}\'';
			if (trim(strval($this->condicionIva->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->condicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `condiciones-iva`";
			$sWhereWrk = "";
			$this->condicionIva->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->condicionIva, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->condicionIva->EditValue = $arwrk;

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":true,"3":true,"4":true}}\'';
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// idTransporte
			$this->idTransporte->EditAttrs["class"] = "form-control";
			$this->idTransporte->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			if (trim(strval($this->idTransporte->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTransporte->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTransporte->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`=3";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTransporte, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTransporte->EditValue = $arwrk;

			// idVendedor
			$this->idVendedor->EditAttrs["class"] = "form-control";
			$this->idVendedor->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			if (trim(strval($this->idVendedor->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idVendedor->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idVendedor->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`='4'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idVendedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idVendedor->EditValue = $arwrk;

			// idCobrador
			$this->idCobrador->EditAttrs["class"] = "form-control";
			$this->idCobrador->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			if (trim(strval($this->idCobrador->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCobrador->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idCobrador->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`='4'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idCobrador, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` DESC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idCobrador->EditValue = $arwrk;

			// comision
			$this->comision->EditAttrs["class"] = "form-control";
			$this->comision->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->comision->EditValue = ew_HtmlEncode($this->comision->CurrentValue);
			$this->comision->PlaceHolder = ew_RemoveHtml($this->comision->FldCaption());
			if (strval($this->comision->EditValue) <> "" && is_numeric($this->comision->EditValue)) $this->comision->EditValue = ew_FormatNumber($this->comision->EditValue, -2, -1, -2, 0);

			// idListaPrecios
			$this->idListaPrecios->EditAttrs["class"] = "form-control";
			$this->idListaPrecios->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			if (trim(strval($this->idListaPrecios->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idListaPrecios->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `descuento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lista-precios`";
			$sWhereWrk = "";
			$this->idListaPrecios->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idListaPrecios, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descuento` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idListaPrecios->EditValue = $arwrk;

			// dtoCliente
			$this->dtoCliente->EditAttrs["class"] = "form-control";
			$this->dtoCliente->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->dtoCliente->EditValue = ew_HtmlEncode($this->dtoCliente->CurrentValue);
			$this->dtoCliente->PlaceHolder = ew_RemoveHtml($this->dtoCliente->FldCaption());
			if (strval($this->dtoCliente->EditValue) <> "" && is_numeric($this->dtoCliente->EditValue)) $this->dtoCliente->EditValue = ew_FormatNumber($this->dtoCliente->EditValue, -2, -1, -2, 0);

			// dto1
			$this->dto1->EditAttrs["class"] = "form-control";
			$this->dto1->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
			$this->dto1->EditValue = ew_HtmlEncode($this->dto1->CurrentValue);
			$this->dto1->PlaceHolder = ew_RemoveHtml($this->dto1->FldCaption());
			if (strval($this->dto1->EditValue) <> "" && is_numeric($this->dto1->EditValue)) $this->dto1->EditValue = ew_FormatNumber($this->dto1->EditValue, -2, -1, -2, 0);

			// dto2
			$this->dto2->EditAttrs["class"] = "form-control";
			$this->dto2->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
			$this->dto2->EditValue = ew_HtmlEncode($this->dto2->CurrentValue);
			$this->dto2->PlaceHolder = ew_RemoveHtml($this->dto2->FldCaption());
			if (strval($this->dto2->EditValue) <> "" && is_numeric($this->dto2->EditValue)) $this->dto2->EditValue = ew_FormatNumber($this->dto2->EditValue, -2, -1, -2, 0);

			// dto3
			$this->dto3->EditAttrs["class"] = "form-control";
			$this->dto3->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":true,"2":false,"3":false,"4":false}}\'';
			$this->dto3->EditValue = ew_HtmlEncode($this->dto3->CurrentValue);
			$this->dto3->PlaceHolder = ew_RemoveHtml($this->dto3->FldCaption());
			if (strval($this->dto3->EditValue) <> "" && is_numeric($this->dto3->EditValue)) $this->dto3->EditValue = ew_FormatNumber($this->dto3->EditValue, -2, -1, -2, 0);

			// limiteDescubierto
			$this->limiteDescubierto->EditAttrs["class"] = "form-control";
			$this->limiteDescubierto->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->limiteDescubierto->EditValue = ew_HtmlEncode($this->limiteDescubierto->CurrentValue);
			$this->limiteDescubierto->PlaceHolder = ew_RemoveHtml($this->limiteDescubierto->FldCaption());
			if (strval($this->limiteDescubierto->EditValue) <> "" && is_numeric($this->limiteDescubierto->EditValue)) $this->limiteDescubierto->EditValue = ew_FormatNumber($this->limiteDescubierto->EditValue, -2, -1, -2, 0);

			// codigoPostal
			$this->codigoPostal->EditAttrs["class"] = "form-control";
			$this->codigoPostal->EditCustomAttributes = "";
			$this->codigoPostal->EditValue = ew_HtmlEncode($this->codigoPostal->CurrentValue);
			$this->codigoPostal->PlaceHolder = ew_RemoveHtml($this->codigoPostal->FldCaption());

			// codigoPostalFiscal
			$this->codigoPostalFiscal->EditAttrs["class"] = "form-control";
			$this->codigoPostalFiscal->EditCustomAttributes = "";
			$this->codigoPostalFiscal->EditValue = ew_HtmlEncode($this->codigoPostalFiscal->CurrentValue);
			$this->codigoPostalFiscal->PlaceHolder = ew_RemoveHtml($this->codigoPostalFiscal->FldCaption());

			// condicionVenta
			$this->condicionVenta->EditAttrs["class"] = "form-control";
			$this->condicionVenta->EditCustomAttributes = 'data-visible=\'{"x_idTipoTercero":{"":false,"1":false,"2":true,"3":false,"4":false}}\'';
			$this->condicionVenta->EditValue = ew_HtmlEncode($this->condicionVenta->CurrentValue);
			$this->condicionVenta->PlaceHolder = ew_RemoveHtml($this->condicionVenta->FldCaption());

			// Add refer script
			// idTipoTercero

			$this->idTipoTercero->LinkCustomAttributes = "";
			$this->idTipoTercero->HrefValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// razonSocial
			$this->razonSocial->LinkCustomAttributes = "";
			$this->razonSocial->HrefValue = "";

			// denominacionCorta
			$this->denominacionCorta->LinkCustomAttributes = "";
			$this->denominacionCorta->HrefValue = "";

			// idPais
			$this->idPais->LinkCustomAttributes = "";
			$this->idPais->HrefValue = "";

			// idProvincia
			$this->idProvincia->LinkCustomAttributes = "";
			$this->idProvincia->HrefValue = "";

			// idPartido
			$this->idPartido->LinkCustomAttributes = "";
			$this->idPartido->HrefValue = "";

			// idLocalidad
			$this->idLocalidad->LinkCustomAttributes = "";
			$this->idLocalidad->HrefValue = "";

			// calle
			$this->calle->LinkCustomAttributes = "";
			$this->calle->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// domicilioFiscal
			$this->domicilioFiscal->LinkCustomAttributes = "";
			$this->domicilioFiscal->HrefValue = "";

			// idPaisFiscal
			$this->idPaisFiscal->LinkCustomAttributes = "";
			$this->idPaisFiscal->HrefValue = "";

			// idProvinciaFiscal
			$this->idProvinciaFiscal->LinkCustomAttributes = "";
			$this->idProvinciaFiscal->HrefValue = "";

			// idPartidoFiscal
			$this->idPartidoFiscal->LinkCustomAttributes = "";
			$this->idPartidoFiscal->HrefValue = "";

			// idLocalidadFiscal
			$this->idLocalidadFiscal->LinkCustomAttributes = "";
			$this->idLocalidadFiscal->HrefValue = "";

			// calleFiscal
			$this->calleFiscal->LinkCustomAttributes = "";
			$this->calleFiscal->HrefValue = "";

			// direccionFiscal
			$this->direccionFiscal->LinkCustomAttributes = "";
			$this->direccionFiscal->HrefValue = "";

			// tipoDoc
			$this->tipoDoc->LinkCustomAttributes = "";
			$this->tipoDoc->HrefValue = "";

			// documento
			$this->documento->LinkCustomAttributes = "";
			$this->documento->HrefValue = "";

			// condicionIva
			$this->condicionIva->LinkCustomAttributes = "";
			$this->condicionIva->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

			// idTransporte
			$this->idTransporte->LinkCustomAttributes = "";
			$this->idTransporte->HrefValue = "";

			// idVendedor
			$this->idVendedor->LinkCustomAttributes = "";
			$this->idVendedor->HrefValue = "";

			// idCobrador
			$this->idCobrador->LinkCustomAttributes = "";
			$this->idCobrador->HrefValue = "";

			// comision
			$this->comision->LinkCustomAttributes = "";
			$this->comision->HrefValue = "";

			// idListaPrecios
			$this->idListaPrecios->LinkCustomAttributes = "";
			$this->idListaPrecios->HrefValue = "";

			// dtoCliente
			$this->dtoCliente->LinkCustomAttributes = "";
			$this->dtoCliente->HrefValue = "";

			// dto1
			$this->dto1->LinkCustomAttributes = "";
			$this->dto1->HrefValue = "";

			// dto2
			$this->dto2->LinkCustomAttributes = "";
			$this->dto2->HrefValue = "";

			// dto3
			$this->dto3->LinkCustomAttributes = "";
			$this->dto3->HrefValue = "";

			// limiteDescubierto
			$this->limiteDescubierto->LinkCustomAttributes = "";
			$this->limiteDescubierto->HrefValue = "";

			// codigoPostal
			$this->codigoPostal->LinkCustomAttributes = "";
			$this->codigoPostal->HrefValue = "";

			// codigoPostalFiscal
			$this->codigoPostalFiscal->LinkCustomAttributes = "";
			$this->codigoPostalFiscal->HrefValue = "";

			// condicionVenta
			$this->condicionVenta->LinkCustomAttributes = "";
			$this->condicionVenta->HrefValue = "";
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
		if (!$this->idTipoTercero->FldIsDetailKey && !is_null($this->idTipoTercero->FormValue) && $this->idTipoTercero->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idTipoTercero->FldCaption(), $this->idTipoTercero->ReqErrMsg));
		}
		if (!$this->denominacion->FldIsDetailKey && !is_null($this->denominacion->FormValue) && $this->denominacion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->denominacion->FldCaption(), $this->denominacion->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->comision->FormValue)) {
			ew_AddMessage($gsFormError, $this->comision->FldErrMsg());
		}
		if (!ew_CheckNumber($this->dtoCliente->FormValue)) {
			ew_AddMessage($gsFormError, $this->dtoCliente->FldErrMsg());
		}
		if (!ew_CheckNumber($this->dto1->FormValue)) {
			ew_AddMessage($gsFormError, $this->dto1->FldErrMsg());
		}
		if (!ew_CheckNumber($this->dto2->FormValue)) {
			ew_AddMessage($gsFormError, $this->dto2->FldErrMsg());
		}
		if (!ew_CheckNumber($this->dto3->FormValue)) {
			ew_AddMessage($gsFormError, $this->dto3->FldErrMsg());
		}
		if (!ew_CheckNumber($this->limiteDescubierto->FormValue)) {
			ew_AddMessage($gsFormError, $this->limiteDescubierto->FldErrMsg());
		}
		if (!ew_CheckInteger($this->condicionVenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->condicionVenta->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("terceros2Dmedios2Dcontactos", $DetailTblVar) && $GLOBALS["terceros2Dmedios2Dcontactos"]->DetailAdd) {
			if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"])) $GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid(); // get detail page object
			$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->ValidateGridForm();
		}
		if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["articulos2Dterceros2Ddescuentos"]->DetailAdd) {
			if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid(); // get detail page object
			$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->ValidateGridForm();
		}
		if (in_array("articulos2Dproveedores", $DetailTblVar) && $GLOBALS["articulos2Dproveedores"]->DetailAdd) {
			if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid(); // get detail page object
			$GLOBALS["articulos2Dproveedores_grid"]->ValidateGridForm();
		}
		if (in_array("subcategoria2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["subcategoria2Dterceros2Ddescuentos"]->DetailAdd) {
			if (!isset($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"])) $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] = new csubcategoria2Dterceros2Ddescuentos_grid(); // get detail page object
			$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->ValidateGridForm();
		}
		if (in_array("categorias2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["categorias2Dterceros2Ddescuentos"]->DetailAdd) {
			if (!isset($GLOBALS["categorias2Dterceros2Ddescuentos_grid"])) $GLOBALS["categorias2Dterceros2Ddescuentos_grid"] = new ccategorias2Dterceros2Ddescuentos_grid(); // get detail page object
			$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->ValidateGridForm();
		}
		if (in_array("sucursales", $DetailTblVar) && $GLOBALS["sucursales"]->DetailAdd) {
			if (!isset($GLOBALS["sucursales_grid"])) $GLOBALS["sucursales_grid"] = new csucursales_grid(); // get detail page object
			$GLOBALS["sucursales_grid"]->ValidateGridForm();
		}
		if (in_array("descuentosasociados", $DetailTblVar) && $GLOBALS["descuentosasociados"]->DetailAdd) {
			if (!isset($GLOBALS["descuentosasociados_grid"])) $GLOBALS["descuentosasociados_grid"] = new cdescuentosasociados_grid(); // get detail page object
			$GLOBALS["descuentosasociados_grid"]->ValidateGridForm();
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

		// idTipoTercero
		$this->idTipoTercero->SetDbValueDef($rsnew, $this->idTipoTercero->CurrentValue, NULL, FALSE);

		// denominacion
		$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, NULL, FALSE);

		// razonSocial
		$this->razonSocial->SetDbValueDef($rsnew, $this->razonSocial->CurrentValue, NULL, FALSE);

		// denominacionCorta
		$this->denominacionCorta->SetDbValueDef($rsnew, $this->denominacionCorta->CurrentValue, NULL, FALSE);

		// idPais
		$this->idPais->SetDbValueDef($rsnew, $this->idPais->CurrentValue, NULL, FALSE);

		// idProvincia
		$this->idProvincia->SetDbValueDef($rsnew, $this->idProvincia->CurrentValue, NULL, FALSE);

		// idPartido
		$this->idPartido->SetDbValueDef($rsnew, $this->idPartido->CurrentValue, NULL, FALSE);

		// idLocalidad
		$this->idLocalidad->SetDbValueDef($rsnew, $this->idLocalidad->CurrentValue, NULL, FALSE);

		// calle
		$this->calle->SetDbValueDef($rsnew, $this->calle->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// domicilioFiscal
		$this->domicilioFiscal->SetDbValueDef($rsnew, $this->domicilioFiscal->CurrentValue, NULL, FALSE);

		// idPaisFiscal
		$this->idPaisFiscal->SetDbValueDef($rsnew, $this->idPaisFiscal->CurrentValue, NULL, FALSE);

		// idProvinciaFiscal
		$this->idProvinciaFiscal->SetDbValueDef($rsnew, $this->idProvinciaFiscal->CurrentValue, NULL, FALSE);

		// idPartidoFiscal
		$this->idPartidoFiscal->SetDbValueDef($rsnew, $this->idPartidoFiscal->CurrentValue, NULL, FALSE);

		// idLocalidadFiscal
		$this->idLocalidadFiscal->SetDbValueDef($rsnew, $this->idLocalidadFiscal->CurrentValue, NULL, FALSE);

		// calleFiscal
		$this->calleFiscal->SetDbValueDef($rsnew, $this->calleFiscal->CurrentValue, NULL, FALSE);

		// direccionFiscal
		$this->direccionFiscal->SetDbValueDef($rsnew, $this->direccionFiscal->CurrentValue, NULL, FALSE);

		// tipoDoc
		$this->tipoDoc->SetDbValueDef($rsnew, $this->tipoDoc->CurrentValue, NULL, FALSE);

		// documento
		$this->documento->SetDbValueDef($rsnew, $this->documento->CurrentValue, NULL, FALSE);

		// condicionIva
		$this->condicionIva->SetDbValueDef($rsnew, $this->condicionIva->CurrentValue, NULL, FALSE);

		// observaciones
		$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, FALSE);

		// idTransporte
		$this->idTransporte->SetDbValueDef($rsnew, $this->idTransporte->CurrentValue, NULL, FALSE);

		// idVendedor
		$this->idVendedor->SetDbValueDef($rsnew, $this->idVendedor->CurrentValue, NULL, FALSE);

		// idCobrador
		$this->idCobrador->SetDbValueDef($rsnew, $this->idCobrador->CurrentValue, NULL, FALSE);

		// comision
		$this->comision->SetDbValueDef($rsnew, $this->comision->CurrentValue, NULL, FALSE);

		// idListaPrecios
		$this->idListaPrecios->SetDbValueDef($rsnew, $this->idListaPrecios->CurrentValue, NULL, FALSE);

		// dtoCliente
		$this->dtoCliente->SetDbValueDef($rsnew, $this->dtoCliente->CurrentValue, NULL, FALSE);

		// dto1
		$this->dto1->SetDbValueDef($rsnew, $this->dto1->CurrentValue, NULL, FALSE);

		// dto2
		$this->dto2->SetDbValueDef($rsnew, $this->dto2->CurrentValue, NULL, FALSE);

		// dto3
		$this->dto3->SetDbValueDef($rsnew, $this->dto3->CurrentValue, NULL, FALSE);

		// limiteDescubierto
		$this->limiteDescubierto->SetDbValueDef($rsnew, $this->limiteDescubierto->CurrentValue, NULL, FALSE);

		// codigoPostal
		$this->codigoPostal->SetDbValueDef($rsnew, $this->codigoPostal->CurrentValue, NULL, FALSE);

		// codigoPostalFiscal
		$this->codigoPostalFiscal->SetDbValueDef($rsnew, $this->codigoPostalFiscal->CurrentValue, NULL, FALSE);

		// condicionVenta
		$this->condicionVenta->SetDbValueDef($rsnew, $this->condicionVenta->CurrentValue, NULL, strval($this->condicionVenta->CurrentValue) == "");

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("terceros2Dmedios2Dcontactos", $DetailTblVar) && $GLOBALS["terceros2Dmedios2Dcontactos"]->DetailAdd) {
				$GLOBALS["terceros2Dmedios2Dcontactos"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"])) $GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "terceros-medios-contactos"); // Load user level of detail table
				$AddRow = $GLOBALS["terceros2Dmedios2Dcontactos_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["terceros2Dmedios2Dcontactos"]->idTercero->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("articulos2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["articulos2Dterceros2Ddescuentos"]->DetailAdd) {
				$GLOBALS["articulos2Dterceros2Ddescuentos"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["articulos2Dterceros2Ddescuentos_grid"])) $GLOBALS["articulos2Dterceros2Ddescuentos_grid"] = new carticulos2Dterceros2Ddescuentos_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "articulos-terceros-descuentos"); // Load user level of detail table
				$AddRow = $GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["articulos2Dterceros2Ddescuentos"]->idTercero->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("articulos2Dproveedores", $DetailTblVar) && $GLOBALS["articulos2Dproveedores"]->DetailAdd) {
				$GLOBALS["articulos2Dproveedores"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["articulos2Dproveedores_grid"])) $GLOBALS["articulos2Dproveedores_grid"] = new carticulos2Dproveedores_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "articulos-proveedores"); // Load user level of detail table
				$AddRow = $GLOBALS["articulos2Dproveedores_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["articulos2Dproveedores"]->idTercero->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("subcategoria2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["subcategoria2Dterceros2Ddescuentos"]->DetailAdd) {
				$GLOBALS["subcategoria2Dterceros2Ddescuentos"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"])) $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"] = new csubcategoria2Dterceros2Ddescuentos_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "subcategoria-terceros-descuentos"); // Load user level of detail table
				$AddRow = $GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["subcategoria2Dterceros2Ddescuentos"]->idTercero->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("categorias2Dterceros2Ddescuentos", $DetailTblVar) && $GLOBALS["categorias2Dterceros2Ddescuentos"]->DetailAdd) {
				$GLOBALS["categorias2Dterceros2Ddescuentos"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["categorias2Dterceros2Ddescuentos_grid"])) $GLOBALS["categorias2Dterceros2Ddescuentos_grid"] = new ccategorias2Dterceros2Ddescuentos_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "categorias-terceros-descuentos"); // Load user level of detail table
				$AddRow = $GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["categorias2Dterceros2Ddescuentos"]->idTercero->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("sucursales", $DetailTblVar) && $GLOBALS["sucursales"]->DetailAdd) {
				$GLOBALS["sucursales"]->idTerceroPadre->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["sucursales_grid"])) $GLOBALS["sucursales_grid"] = new csucursales_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "sucursales"); // Load user level of detail table
				$AddRow = $GLOBALS["sucursales_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["sucursales"]->idTerceroPadre->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("descuentosasociados", $DetailTblVar) && $GLOBALS["descuentosasociados"]->DetailAdd) {
				$GLOBALS["descuentosasociados"]->idTercero->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["descuentosasociados_grid"])) $GLOBALS["descuentosasociados_grid"] = new cdescuentosasociados_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "descuentosasociados"); // Load user level of detail table
				$AddRow = $GLOBALS["descuentosasociados_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["descuentosasociados"]->idTercero->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("terceros2Dmedios2Dcontactos", $DetailTblVar)) {
				if (!isset($GLOBALS["terceros2Dmedios2Dcontactos_grid"]))
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"] = new cterceros2Dmedios2Dcontactos_grid;
				if ($GLOBALS["terceros2Dmedios2Dcontactos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->CurrentMode = "add";
					$GLOBALS["terceros2Dmedios2Dcontactos_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentMode = "add";
					$GLOBALS["articulos2Dterceros2Ddescuentos_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["articulos2Dproveedores_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["articulos2Dproveedores_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["articulos2Dproveedores_grid"]->CurrentMode = "add";
					$GLOBALS["articulos2Dproveedores_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->CurrentMode = "add";
					$GLOBALS["subcategoria2Dterceros2Ddescuentos_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->CurrentMode = "add";
					$GLOBALS["categorias2Dterceros2Ddescuentos_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["sucursales_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["sucursales_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["sucursales_grid"]->CurrentMode = "add";
					$GLOBALS["sucursales_grid"]->CurrentAction = "gridadd";

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
				if ($GLOBALS["descuentosasociados_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["descuentosasociados_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["descuentosasociados_grid"]->CurrentMode = "add";
					$GLOBALS["descuentosasociados_grid"]->CurrentAction = "gridadd";

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
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
		case "x_idPaisFiscal":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
			$sWhereWrk = "";
			$this->idPaisFiscal->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPaisFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idProvinciaFiscal":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
			$sWhereWrk = "{filter}";
			$this->idProvinciaFiscal->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idPais` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idProvinciaFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPartidoFiscal":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `partidos`";
			$sWhereWrk = "{filter}";
			$this->idPartidoFiscal->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idProvincia` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPartidoFiscal, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idLocalidadFiscal":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
			$sWhereWrk = "{filter}";
			$this->idLocalidadFiscal->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "", "f1" => "`idPartido` IN ({filter_value})", "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idLocalidadFiscal, $sWhereWrk); // Call Lookup selecting
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
		case "x_idTransporte":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTransporte->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`=3";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTransporte, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idVendedor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idVendedor->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`='4'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idVendedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idCobrador":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idCobrador->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`='4'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idCobrador, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` DESC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idListaPrecios":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, `descuento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lista-precios`";
			$sWhereWrk = "";
			$this->idListaPrecios->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idListaPrecios, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `descuento` ASC";
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
if (!isset($terceros_add)) $terceros_add = new cterceros_add();

// Page init
$terceros_add->Page_Init();

// Page main
$terceros_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftercerosadd = new ew_Form("ftercerosadd", "add");

// Validate form
ftercerosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idTipoTercero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $terceros->idTipoTercero->FldCaption(), $terceros->idTipoTercero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denominacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $terceros->denominacion->FldCaption(), $terceros->denominacion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comision");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->comision->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dtoCliente");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->dtoCliente->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->dto1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->dto2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->dto3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_limiteDescubierto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->limiteDescubierto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_condicionVenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($terceros->condicionVenta->FldErrMsg()) ?>");

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
ftercerosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftercerosadd.ValidateRequired = true;
<?php } else { ?>
ftercerosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftercerosadd.Lists["x_idTipoTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dterceros"};
ftercerosadd.Lists["x_idPais"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":["x_idProvincia"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"paises"};
ftercerosadd.Lists["x_idProvincia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idPais"],"ChildFields":["x_idPartido"],"FilterFields":["x_idPais"],"Options":[],"Template":"","LinkTable":"provincias"};
ftercerosadd.Lists["x_idPartido"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idProvincia"],"ChildFields":["x_idLocalidad"],"FilterFields":["x_idProvincia"],"Options":[],"Template":"","LinkTable":"partidos"};
ftercerosadd.Lists["x_idLocalidad"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idPartido"],"ChildFields":[],"FilterFields":["x_idPartido"],"Options":[],"Template":"","LinkTable":"localidades"};
ftercerosadd.Lists["x_domicilioFiscal"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftercerosadd.Lists["x_domicilioFiscal"].Options = <?php echo json_encode($terceros->domicilioFiscal->Options()) ?>;
ftercerosadd.Lists["x_idPaisFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":["x_idProvinciaFiscal"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"paises"};
ftercerosadd.Lists["x_idProvinciaFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idPaisFiscal"],"ChildFields":["x_idPartidoFiscal"],"FilterFields":["x_idPais"],"Options":[],"Template":"","LinkTable":"provincias"};
ftercerosadd.Lists["x_idPartidoFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idProvinciaFiscal"],"ChildFields":["x_idLocalidadFiscal"],"FilterFields":["x_idProvincia"],"Options":[],"Template":"","LinkTable":"partidos"};
ftercerosadd.Lists["x_idLocalidadFiscal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":["x_idPartidoFiscal"],"ChildFields":[],"FilterFields":["x_idPartido"],"Options":[],"Template":"","LinkTable":"localidades"};
ftercerosadd.Lists["x_tipoDoc"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Ddocumentos"};
ftercerosadd.Lists["x_condicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};
ftercerosadd.Lists["x_idTransporte"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
ftercerosadd.Lists["x_idVendedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
ftercerosadd.Lists["x_idCobrador"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
ftercerosadd.Lists["x_idListaPrecios"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_descuento","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"lista2Dprecios"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$terceros_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $terceros_add->ShowPageHeader(); ?>
<?php
$terceros_add->ShowMessage();
?>
<form name="ftercerosadd" id="ftercerosadd" class="<?php echo $terceros_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($terceros_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $terceros_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="terceros">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($terceros_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
	<div id="r_idTipoTercero" class="form-group">
		<label id="elh_terceros_idTipoTercero" for="x_idTipoTercero" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idTipoTercero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idTipoTercero->CellAttributes() ?>>
<span id="el_terceros_idTipoTercero">
<select data-table="terceros" data-field="x_idTipoTercero" data-value-separator="<?php echo $terceros->idTipoTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTipoTercero" name="x_idTipoTercero"<?php echo $terceros->idTipoTercero->EditAttributes() ?>>
<?php echo $terceros->idTipoTercero->SelectOptionListHtml("x_idTipoTercero") ?>
</select>
<input type="hidden" name="s_x_idTipoTercero" id="s_x_idTipoTercero" value="<?php echo $terceros->idTipoTercero->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idTipoTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
	<div id="r_denominacion" class="form-group">
		<label id="elh_terceros_denominacion" for="x_denominacion" class="col-sm-2 control-label ewLabel"><?php echo $terceros->denominacion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->denominacion->CellAttributes() ?>>
<span id="el_terceros_denominacion">
<input type="text" data-table="terceros" data-field="x_denominacion" name="x_denominacion" id="x_denominacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros->denominacion->EditValue ?>"<?php echo $terceros->denominacion->EditAttributes() ?>>
</span>
<?php echo $terceros->denominacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->razonSocial->Visible) { // razonSocial ?>
	<div id="r_razonSocial" class="form-group">
		<label id="elh_terceros_razonSocial" for="x_razonSocial" class="col-sm-2 control-label ewLabel"><?php echo $terceros->razonSocial->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->razonSocial->CellAttributes() ?>>
<span id="el_terceros_razonSocial">
<input type="text" data-table="terceros" data-field="x_razonSocial" name="x_razonSocial" id="x_razonSocial" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros->razonSocial->getPlaceHolder()) ?>" value="<?php echo $terceros->razonSocial->EditValue ?>"<?php echo $terceros->razonSocial->EditAttributes() ?>>
</span>
<?php echo $terceros->razonSocial->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->denominacionCorta->Visible) { // denominacionCorta ?>
	<div id="r_denominacionCorta" class="form-group">
		<label id="elh_terceros_denominacionCorta" for="x_denominacionCorta" class="col-sm-2 control-label ewLabel"><?php echo $terceros->denominacionCorta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->denominacionCorta->CellAttributes() ?>>
<span id="el_terceros_denominacionCorta">
<input type="text" data-table="terceros" data-field="x_denominacionCorta" name="x_denominacionCorta" id="x_denominacionCorta" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros->denominacionCorta->getPlaceHolder()) ?>" value="<?php echo $terceros->denominacionCorta->EditValue ?>"<?php echo $terceros->denominacionCorta->EditAttributes() ?>>
</span>
<?php echo $terceros->denominacionCorta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idPais->Visible) { // idPais ?>
	<div id="r_idPais" class="form-group">
		<label id="elh_terceros_idPais" for="x_idPais" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idPais->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idPais->CellAttributes() ?>>
<span id="el_terceros_idPais">
<?php $terceros->idPais->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idPais->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idPais" data-value-separator="<?php echo $terceros->idPais->DisplayValueSeparatorAttribute() ?>" id="x_idPais" name="x_idPais"<?php echo $terceros->idPais->EditAttributes() ?>>
<?php echo $terceros->idPais->SelectOptionListHtml("x_idPais") ?>
</select>
<input type="hidden" name="s_x_idPais" id="s_x_idPais" value="<?php echo $terceros->idPais->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idPais->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idProvincia->Visible) { // idProvincia ?>
	<div id="r_idProvincia" class="form-group">
		<label id="elh_terceros_idProvincia" for="x_idProvincia" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idProvincia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idProvincia->CellAttributes() ?>>
<span id="el_terceros_idProvincia">
<?php $terceros->idProvincia->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idProvincia->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idProvincia" data-value-separator="<?php echo $terceros->idProvincia->DisplayValueSeparatorAttribute() ?>" id="x_idProvincia" name="x_idProvincia"<?php echo $terceros->idProvincia->EditAttributes() ?>>
<?php echo $terceros->idProvincia->SelectOptionListHtml("x_idProvincia") ?>
</select>
<input type="hidden" name="s_x_idProvincia" id="s_x_idProvincia" value="<?php echo $terceros->idProvincia->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idProvincia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idPartido->Visible) { // idPartido ?>
	<div id="r_idPartido" class="form-group">
		<label id="elh_terceros_idPartido" for="x_idPartido" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idPartido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idPartido->CellAttributes() ?>>
<span id="el_terceros_idPartido">
<?php $terceros->idPartido->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idPartido->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idPartido" data-value-separator="<?php echo $terceros->idPartido->DisplayValueSeparatorAttribute() ?>" id="x_idPartido" name="x_idPartido"<?php echo $terceros->idPartido->EditAttributes() ?>>
<?php echo $terceros->idPartido->SelectOptionListHtml("x_idPartido") ?>
</select>
<input type="hidden" name="s_x_idPartido" id="s_x_idPartido" value="<?php echo $terceros->idPartido->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idPartido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idLocalidad->Visible) { // idLocalidad ?>
	<div id="r_idLocalidad" class="form-group">
		<label id="elh_terceros_idLocalidad" for="x_idLocalidad" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idLocalidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idLocalidad->CellAttributes() ?>>
<span id="el_terceros_idLocalidad">
<select data-table="terceros" data-field="x_idLocalidad" data-value-separator="<?php echo $terceros->idLocalidad->DisplayValueSeparatorAttribute() ?>" id="x_idLocalidad" name="x_idLocalidad"<?php echo $terceros->idLocalidad->EditAttributes() ?>>
<?php echo $terceros->idLocalidad->SelectOptionListHtml("x_idLocalidad") ?>
</select>
<input type="hidden" name="s_x_idLocalidad" id="s_x_idLocalidad" value="<?php echo $terceros->idLocalidad->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idLocalidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->calle->Visible) { // calle ?>
	<div id="r_calle" class="form-group">
		<label id="elh_terceros_calle" for="x_calle" class="col-sm-2 control-label ewLabel"><?php echo $terceros->calle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->calle->CellAttributes() ?>>
<span id="el_terceros_calle">
<input type="text" data-table="terceros" data-field="x_calle" name="x_calle" id="x_calle" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros->calle->getPlaceHolder()) ?>" value="<?php echo $terceros->calle->EditValue ?>"<?php echo $terceros->calle->EditAttributes() ?>>
</span>
<?php echo $terceros->calle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_terceros_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $terceros->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->direccion->CellAttributes() ?>>
<span id="el_terceros_direccion">
<input type="text" data-table="terceros" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros->direccion->getPlaceHolder()) ?>" value="<?php echo $terceros->direccion->EditValue ?>"<?php echo $terceros->direccion->EditAttributes() ?>>
</span>
<?php echo $terceros->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->domicilioFiscal->Visible) { // domicilioFiscal ?>
	<div id="r_domicilioFiscal" class="form-group">
		<label id="elh_terceros_domicilioFiscal" for="x_domicilioFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->domicilioFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->domicilioFiscal->CellAttributes() ?>>
<span id="el_terceros_domicilioFiscal">
<select data-table="terceros" data-field="x_domicilioFiscal" data-value-separator="<?php echo $terceros->domicilioFiscal->DisplayValueSeparatorAttribute() ?>" id="x_domicilioFiscal" name="x_domicilioFiscal"<?php echo $terceros->domicilioFiscal->EditAttributes() ?>>
<?php echo $terceros->domicilioFiscal->SelectOptionListHtml("x_domicilioFiscal") ?>
</select>
</span>
<?php echo $terceros->domicilioFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idPaisFiscal->Visible) { // idPaisFiscal ?>
	<div id="r_idPaisFiscal" class="form-group">
		<label id="elh_terceros_idPaisFiscal" for="x_idPaisFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idPaisFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idPaisFiscal->CellAttributes() ?>>
<span id="el_terceros_idPaisFiscal">
<?php $terceros->idPaisFiscal->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idPaisFiscal->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idPaisFiscal" data-value-separator="<?php echo $terceros->idPaisFiscal->DisplayValueSeparatorAttribute() ?>" id="x_idPaisFiscal" name="x_idPaisFiscal"<?php echo $terceros->idPaisFiscal->EditAttributes() ?>>
<?php echo $terceros->idPaisFiscal->SelectOptionListHtml("x_idPaisFiscal") ?>
</select>
<input type="hidden" name="s_x_idPaisFiscal" id="s_x_idPaisFiscal" value="<?php echo $terceros->idPaisFiscal->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idPaisFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idProvinciaFiscal->Visible) { // idProvinciaFiscal ?>
	<div id="r_idProvinciaFiscal" class="form-group">
		<label id="elh_terceros_idProvinciaFiscal" for="x_idProvinciaFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idProvinciaFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idProvinciaFiscal->CellAttributes() ?>>
<span id="el_terceros_idProvinciaFiscal">
<?php $terceros->idProvinciaFiscal->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idProvinciaFiscal->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idProvinciaFiscal" data-value-separator="<?php echo $terceros->idProvinciaFiscal->DisplayValueSeparatorAttribute() ?>" id="x_idProvinciaFiscal" name="x_idProvinciaFiscal"<?php echo $terceros->idProvinciaFiscal->EditAttributes() ?>>
<?php echo $terceros->idProvinciaFiscal->SelectOptionListHtml("x_idProvinciaFiscal") ?>
</select>
<input type="hidden" name="s_x_idProvinciaFiscal" id="s_x_idProvinciaFiscal" value="<?php echo $terceros->idProvinciaFiscal->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idProvinciaFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idPartidoFiscal->Visible) { // idPartidoFiscal ?>
	<div id="r_idPartidoFiscal" class="form-group">
		<label id="elh_terceros_idPartidoFiscal" for="x_idPartidoFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idPartidoFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idPartidoFiscal->CellAttributes() ?>>
<span id="el_terceros_idPartidoFiscal">
<?php $terceros->idPartidoFiscal->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$terceros->idPartidoFiscal->EditAttrs["onchange"]; ?>
<select data-table="terceros" data-field="x_idPartidoFiscal" data-value-separator="<?php echo $terceros->idPartidoFiscal->DisplayValueSeparatorAttribute() ?>" id="x_idPartidoFiscal" name="x_idPartidoFiscal"<?php echo $terceros->idPartidoFiscal->EditAttributes() ?>>
<?php echo $terceros->idPartidoFiscal->SelectOptionListHtml("x_idPartidoFiscal") ?>
</select>
<input type="hidden" name="s_x_idPartidoFiscal" id="s_x_idPartidoFiscal" value="<?php echo $terceros->idPartidoFiscal->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idPartidoFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idLocalidadFiscal->Visible) { // idLocalidadFiscal ?>
	<div id="r_idLocalidadFiscal" class="form-group">
		<label id="elh_terceros_idLocalidadFiscal" for="x_idLocalidadFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idLocalidadFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idLocalidadFiscal->CellAttributes() ?>>
<span id="el_terceros_idLocalidadFiscal">
<select data-table="terceros" data-field="x_idLocalidadFiscal" data-value-separator="<?php echo $terceros->idLocalidadFiscal->DisplayValueSeparatorAttribute() ?>" id="x_idLocalidadFiscal" name="x_idLocalidadFiscal"<?php echo $terceros->idLocalidadFiscal->EditAttributes() ?>>
<?php echo $terceros->idLocalidadFiscal->SelectOptionListHtml("x_idLocalidadFiscal") ?>
</select>
<input type="hidden" name="s_x_idLocalidadFiscal" id="s_x_idLocalidadFiscal" value="<?php echo $terceros->idLocalidadFiscal->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idLocalidadFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->calleFiscal->Visible) { // calleFiscal ?>
	<div id="r_calleFiscal" class="form-group">
		<label id="elh_terceros_calleFiscal" for="x_calleFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->calleFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->calleFiscal->CellAttributes() ?>>
<span id="el_terceros_calleFiscal">
<input type="text" data-table="terceros" data-field="x_calleFiscal" name="x_calleFiscal" id="x_calleFiscal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros->calleFiscal->getPlaceHolder()) ?>" value="<?php echo $terceros->calleFiscal->EditValue ?>"<?php echo $terceros->calleFiscal->EditAttributes() ?>>
</span>
<?php echo $terceros->calleFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->direccionFiscal->Visible) { // direccionFiscal ?>
	<div id="r_direccionFiscal" class="form-group">
		<label id="elh_terceros_direccionFiscal" for="x_direccionFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->direccionFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->direccionFiscal->CellAttributes() ?>>
<span id="el_terceros_direccionFiscal">
<input type="text" data-table="terceros" data-field="x_direccionFiscal" name="x_direccionFiscal" id="x_direccionFiscal" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros->direccionFiscal->getPlaceHolder()) ?>" value="<?php echo $terceros->direccionFiscal->EditValue ?>"<?php echo $terceros->direccionFiscal->EditAttributes() ?>>
</span>
<?php echo $terceros->direccionFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->tipoDoc->Visible) { // tipoDoc ?>
	<div id="r_tipoDoc" class="form-group">
		<label id="elh_terceros_tipoDoc" for="x_tipoDoc" class="col-sm-2 control-label ewLabel"><?php echo $terceros->tipoDoc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->tipoDoc->CellAttributes() ?>>
<span id="el_terceros_tipoDoc">
<select data-table="terceros" data-field="x_tipoDoc" data-value-separator="<?php echo $terceros->tipoDoc->DisplayValueSeparatorAttribute() ?>" id="x_tipoDoc" name="x_tipoDoc"<?php echo $terceros->tipoDoc->EditAttributes() ?>>
<?php echo $terceros->tipoDoc->SelectOptionListHtml("x_tipoDoc") ?>
</select>
<input type="hidden" name="s_x_tipoDoc" id="s_x_tipoDoc" value="<?php echo $terceros->tipoDoc->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->tipoDoc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->documento->Visible) { // documento ?>
	<div id="r_documento" class="form-group">
		<label id="elh_terceros_documento" for="x_documento" class="col-sm-2 control-label ewLabel"><?php echo $terceros->documento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->documento->CellAttributes() ?>>
<span id="el_terceros_documento">
<input type="text" data-table="terceros" data-field="x_documento" name="x_documento" id="x_documento" size="30" maxlength="11" placeholder="<?php echo ew_HtmlEncode($terceros->documento->getPlaceHolder()) ?>" value="<?php echo $terceros->documento->EditValue ?>"<?php echo $terceros->documento->EditAttributes() ?>>
</span>
<?php echo $terceros->documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->condicionIva->Visible) { // condicionIva ?>
	<div id="r_condicionIva" class="form-group">
		<label id="elh_terceros_condicionIva" for="x_condicionIva" class="col-sm-2 control-label ewLabel"><?php echo $terceros->condicionIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->condicionIva->CellAttributes() ?>>
<span id="el_terceros_condicionIva">
<select data-table="terceros" data-field="x_condicionIva" data-value-separator="<?php echo $terceros->condicionIva->DisplayValueSeparatorAttribute() ?>" id="x_condicionIva" name="x_condicionIva"<?php echo $terceros->condicionIva->EditAttributes() ?>>
<?php echo $terceros->condicionIva->SelectOptionListHtml("x_condicionIva") ?>
</select>
<input type="hidden" name="s_x_condicionIva" id="s_x_condicionIva" value="<?php echo $terceros->condicionIva->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->condicionIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_terceros_observaciones" for="x_observaciones" class="col-sm-2 control-label ewLabel"><?php echo $terceros->observaciones->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->observaciones->CellAttributes() ?>>
<span id="el_terceros_observaciones">
<textarea data-table="terceros" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($terceros->observaciones->getPlaceHolder()) ?>"<?php echo $terceros->observaciones->EditAttributes() ?>><?php echo $terceros->observaciones->EditValue ?></textarea>
</span>
<?php echo $terceros->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
	<div id="r_idTransporte" class="form-group">
		<label id="elh_terceros_idTransporte" for="x_idTransporte" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idTransporte->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idTransporte->CellAttributes() ?>>
<span id="el_terceros_idTransporte">
<select data-table="terceros" data-field="x_idTransporte" data-value-separator="<?php echo $terceros->idTransporte->DisplayValueSeparatorAttribute() ?>" id="x_idTransporte" name="x_idTransporte"<?php echo $terceros->idTransporte->EditAttributes() ?>>
<?php echo $terceros->idTransporte->SelectOptionListHtml("x_idTransporte") ?>
</select>
<input type="hidden" name="s_x_idTransporte" id="s_x_idTransporte" value="<?php echo $terceros->idTransporte->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idTransporte->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idVendedor->Visible) { // idVendedor ?>
	<div id="r_idVendedor" class="form-group">
		<label id="elh_terceros_idVendedor" for="x_idVendedor" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idVendedor->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idVendedor->CellAttributes() ?>>
<span id="el_terceros_idVendedor">
<select data-table="terceros" data-field="x_idVendedor" data-value-separator="<?php echo $terceros->idVendedor->DisplayValueSeparatorAttribute() ?>" id="x_idVendedor" name="x_idVendedor"<?php echo $terceros->idVendedor->EditAttributes() ?>>
<?php echo $terceros->idVendedor->SelectOptionListHtml("x_idVendedor") ?>
</select>
<input type="hidden" name="s_x_idVendedor" id="s_x_idVendedor" value="<?php echo $terceros->idVendedor->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idVendedor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idCobrador->Visible) { // idCobrador ?>
	<div id="r_idCobrador" class="form-group">
		<label id="elh_terceros_idCobrador" for="x_idCobrador" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idCobrador->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idCobrador->CellAttributes() ?>>
<span id="el_terceros_idCobrador">
<select data-table="terceros" data-field="x_idCobrador" data-value-separator="<?php echo $terceros->idCobrador->DisplayValueSeparatorAttribute() ?>" id="x_idCobrador" name="x_idCobrador"<?php echo $terceros->idCobrador->EditAttributes() ?>>
<?php echo $terceros->idCobrador->SelectOptionListHtml("x_idCobrador") ?>
</select>
<input type="hidden" name="s_x_idCobrador" id="s_x_idCobrador" value="<?php echo $terceros->idCobrador->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idCobrador->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->comision->Visible) { // comision ?>
	<div id="r_comision" class="form-group">
		<label id="elh_terceros_comision" for="x_comision" class="col-sm-2 control-label ewLabel"><?php echo $terceros->comision->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->comision->CellAttributes() ?>>
<span id="el_terceros_comision">
<input type="text" data-table="terceros" data-field="x_comision" name="x_comision" id="x_comision" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->comision->getPlaceHolder()) ?>" value="<?php echo $terceros->comision->EditValue ?>"<?php echo $terceros->comision->EditAttributes() ?>>
</span>
<?php echo $terceros->comision->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->idListaPrecios->Visible) { // idListaPrecios ?>
	<div id="r_idListaPrecios" class="form-group">
		<label id="elh_terceros_idListaPrecios" for="x_idListaPrecios" class="col-sm-2 control-label ewLabel"><?php echo $terceros->idListaPrecios->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->idListaPrecios->CellAttributes() ?>>
<span id="el_terceros_idListaPrecios">
<select data-table="terceros" data-field="x_idListaPrecios" data-value-separator="<?php echo $terceros->idListaPrecios->DisplayValueSeparatorAttribute() ?>" id="x_idListaPrecios" name="x_idListaPrecios"<?php echo $terceros->idListaPrecios->EditAttributes() ?>>
<?php echo $terceros->idListaPrecios->SelectOptionListHtml("x_idListaPrecios") ?>
</select>
<input type="hidden" name="s_x_idListaPrecios" id="s_x_idListaPrecios" value="<?php echo $terceros->idListaPrecios->LookupFilterQuery() ?>">
</span>
<?php echo $terceros->idListaPrecios->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->dtoCliente->Visible) { // dtoCliente ?>
	<div id="r_dtoCliente" class="form-group">
		<label id="elh_terceros_dtoCliente" for="x_dtoCliente" class="col-sm-2 control-label ewLabel"><?php echo $terceros->dtoCliente->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->dtoCliente->CellAttributes() ?>>
<span id="el_terceros_dtoCliente">
<input type="text" data-table="terceros" data-field="x_dtoCliente" name="x_dtoCliente" id="x_dtoCliente" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->dtoCliente->getPlaceHolder()) ?>" value="<?php echo $terceros->dtoCliente->EditValue ?>"<?php echo $terceros->dtoCliente->EditAttributes() ?>>
</span>
<?php echo $terceros->dtoCliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->dto1->Visible) { // dto1 ?>
	<div id="r_dto1" class="form-group">
		<label id="elh_terceros_dto1" for="x_dto1" class="col-sm-2 control-label ewLabel"><?php echo $terceros->dto1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->dto1->CellAttributes() ?>>
<span id="el_terceros_dto1">
<input type="text" data-table="terceros" data-field="x_dto1" name="x_dto1" id="x_dto1" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->dto1->getPlaceHolder()) ?>" value="<?php echo $terceros->dto1->EditValue ?>"<?php echo $terceros->dto1->EditAttributes() ?>>
</span>
<?php echo $terceros->dto1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->dto2->Visible) { // dto2 ?>
	<div id="r_dto2" class="form-group">
		<label id="elh_terceros_dto2" for="x_dto2" class="col-sm-2 control-label ewLabel"><?php echo $terceros->dto2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->dto2->CellAttributes() ?>>
<span id="el_terceros_dto2">
<input type="text" data-table="terceros" data-field="x_dto2" name="x_dto2" id="x_dto2" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->dto2->getPlaceHolder()) ?>" value="<?php echo $terceros->dto2->EditValue ?>"<?php echo $terceros->dto2->EditAttributes() ?>>
</span>
<?php echo $terceros->dto2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->dto3->Visible) { // dto3 ?>
	<div id="r_dto3" class="form-group">
		<label id="elh_terceros_dto3" for="x_dto3" class="col-sm-2 control-label ewLabel"><?php echo $terceros->dto3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->dto3->CellAttributes() ?>>
<span id="el_terceros_dto3">
<input type="text" data-table="terceros" data-field="x_dto3" name="x_dto3" id="x_dto3" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->dto3->getPlaceHolder()) ?>" value="<?php echo $terceros->dto3->EditValue ?>"<?php echo $terceros->dto3->EditAttributes() ?>>
</span>
<?php echo $terceros->dto3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
	<div id="r_limiteDescubierto" class="form-group">
		<label id="elh_terceros_limiteDescubierto" for="x_limiteDescubierto" class="col-sm-2 control-label ewLabel"><?php echo $terceros->limiteDescubierto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->limiteDescubierto->CellAttributes() ?>>
<span id="el_terceros_limiteDescubierto">
<input type="text" data-table="terceros" data-field="x_limiteDescubierto" name="x_limiteDescubierto" id="x_limiteDescubierto" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->limiteDescubierto->getPlaceHolder()) ?>" value="<?php echo $terceros->limiteDescubierto->EditValue ?>"<?php echo $terceros->limiteDescubierto->EditAttributes() ?>>
</span>
<?php echo $terceros->limiteDescubierto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
	<div id="r_codigoPostal" class="form-group">
		<label id="elh_terceros_codigoPostal" for="x_codigoPostal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->codigoPostal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->codigoPostal->CellAttributes() ?>>
<span id="el_terceros_codigoPostal">
<input type="text" data-table="terceros" data-field="x_codigoPostal" name="x_codigoPostal" id="x_codigoPostal" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros->codigoPostal->getPlaceHolder()) ?>" value="<?php echo $terceros->codigoPostal->EditValue ?>"<?php echo $terceros->codigoPostal->EditAttributes() ?>>
</span>
<?php echo $terceros->codigoPostal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
	<div id="r_codigoPostalFiscal" class="form-group">
		<label id="elh_terceros_codigoPostalFiscal" for="x_codigoPostalFiscal" class="col-sm-2 control-label ewLabel"><?php echo $terceros->codigoPostalFiscal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->codigoPostalFiscal->CellAttributes() ?>>
<span id="el_terceros_codigoPostalFiscal">
<input type="text" data-table="terceros" data-field="x_codigoPostalFiscal" name="x_codigoPostalFiscal" id="x_codigoPostalFiscal" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($terceros->codigoPostalFiscal->getPlaceHolder()) ?>" value="<?php echo $terceros->codigoPostalFiscal->EditValue ?>"<?php echo $terceros->codigoPostalFiscal->EditAttributes() ?>>
</span>
<?php echo $terceros->codigoPostalFiscal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
	<div id="r_condicionVenta" class="form-group">
		<label id="elh_terceros_condicionVenta" for="x_condicionVenta" class="col-sm-2 control-label ewLabel"><?php echo $terceros->condicionVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $terceros->condicionVenta->CellAttributes() ?>>
<span id="el_terceros_condicionVenta">
<input type="text" data-table="terceros" data-field="x_condicionVenta" name="x_condicionVenta" id="x_condicionVenta" size="30" placeholder="<?php echo ew_HtmlEncode($terceros->condicionVenta->getPlaceHolder()) ?>" value="<?php echo $terceros->condicionVenta->EditValue ?>"<?php echo $terceros->condicionVenta->EditAttributes() ?>>
</span>
<?php echo $terceros->condicionVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("terceros2Dmedios2Dcontactos", explode(",", $terceros->getCurrentDetailTable())) && $terceros2Dmedios2Dcontactos->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("terceros2Dmedios2Dcontactos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "terceros2Dmedios2Dcontactosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $articulos2Dterceros2Ddescuentos->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("articulos2Dproveedores", explode(",", $terceros->getCurrentDetailTable())) && $articulos2Dproveedores->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("articulos2Dproveedores", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "articulos2Dproveedoresgrid.php" ?>
<?php } ?>
<?php
	if (in_array("subcategoria2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $subcategoria2Dterceros2Ddescuentos->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("subcategoria2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "subcategoria2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("categorias2Dterceros2Ddescuentos", explode(",", $terceros->getCurrentDetailTable())) && $categorias2Dterceros2Ddescuentos->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("categorias2Dterceros2Ddescuentos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "categorias2Dterceros2Ddescuentosgrid.php" ?>
<?php } ?>
<?php
	if (in_array("sucursales", explode(",", $terceros->getCurrentDetailTable())) && $sucursales->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("sucursales", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "sucursalesgrid.php" ?>
<?php } ?>
<?php
	if (in_array("descuentosasociados", explode(",", $terceros->getCurrentDetailTable())) && $descuentosasociados->DetailAdd) {
?>
<?php if ($terceros->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("descuentosasociados", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "descuentosasociadosgrid.php" ?>
<?php } ?>
<?php if (!$terceros_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $terceros_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftercerosadd.Init();
</script>
<?php
$terceros_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
	ocultarMostrarCampos();
	agregarCheckboxCamposDuplica();
</script>
<?php include_once "footer.php" ?>
<?php
$terceros_add->Page_Terminate();
?>
