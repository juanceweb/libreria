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

$articulos2Dproveedores_add = NULL; // Initialize page object first

class carticulos2Dproveedores_add extends carticulos2Dproveedores {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos-proveedores';

	// Page object name
	var $PageObjName = 'articulos2Dproveedores_add';

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

		// Table object (articulos2Dproveedores)
		if (!isset($GLOBALS["articulos2Dproveedores"]) || get_class($GLOBALS["articulos2Dproveedores"]) == "carticulos2Dproveedores") {
			$GLOBALS["articulos2Dproveedores"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos2Dproveedores"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (terceros)
		if (!isset($GLOBALS['terceros'])) $GLOBALS['terceros'] = new cterceros();

		// Table object (articulos)
		if (!isset($GLOBALS['articulos'])) $GLOBALS['articulos'] = new carticulos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("articulos2Dproveedoreslist.php"));
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
		$this->ultimaActualizacion->SetVisibility();

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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

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
					$this->Page_Terminate("articulos2Dproveedoreslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "articulos2Dproveedoreslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "articulos2Dproveedoresview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
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
		$this->idArticulo->CurrentValue = NULL;
		$this->idArticulo->OldValue = $this->idArticulo->CurrentValue;
		$this->codExterno->CurrentValue = NULL;
		$this->codExterno->OldValue = $this->codExterno->CurrentValue;
		$this->idAlicuotaIva->CurrentValue = NULL;
		$this->idAlicuotaIva->OldValue = $this->idAlicuotaIva->CurrentValue;
		$this->idMoneda->CurrentValue = NULL;
		$this->idMoneda->OldValue = $this->idMoneda->CurrentValue;
		$this->precio->CurrentValue = NULL;
		$this->precio->OldValue = $this->precio->CurrentValue;
		$this->idUnidadMedida->CurrentValue = NULL;
		$this->idUnidadMedida->OldValue = $this->idUnidadMedida->CurrentValue;
		$this->dto1->CurrentValue = NULL;
		$this->dto1->OldValue = $this->dto1->CurrentValue;
		$this->dto2->CurrentValue = NULL;
		$this->dto2->OldValue = $this->dto2->CurrentValue;
		$this->dto3->CurrentValue = NULL;
		$this->dto3->OldValue = $this->dto3->CurrentValue;
		$this->idTercero->CurrentValue = NULL;
		$this->idTercero->OldValue = $this->idTercero->CurrentValue;
		$this->ultimaActualizacion->CurrentValue = NULL;
		$this->ultimaActualizacion->OldValue = $this->ultimaActualizacion->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idArticulo->FldIsDetailKey) {
			$this->idArticulo->setFormValue($objForm->GetValue("x_idArticulo"));
		}
		if (!$this->codExterno->FldIsDetailKey) {
			$this->codExterno->setFormValue($objForm->GetValue("x_codExterno"));
		}
		if (!$this->idAlicuotaIva->FldIsDetailKey) {
			$this->idAlicuotaIva->setFormValue($objForm->GetValue("x_idAlicuotaIva"));
		}
		if (!$this->idMoneda->FldIsDetailKey) {
			$this->idMoneda->setFormValue($objForm->GetValue("x_idMoneda"));
		}
		if (!$this->precio->FldIsDetailKey) {
			$this->precio->setFormValue($objForm->GetValue("x_precio"));
		}
		if (!$this->idUnidadMedida->FldIsDetailKey) {
			$this->idUnidadMedida->setFormValue($objForm->GetValue("x_idUnidadMedida"));
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
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->ultimaActualizacion->FldIsDetailKey) {
			$this->ultimaActualizacion->setFormValue($objForm->GetValue("x_ultimaActualizacion"));
			$this->ultimaActualizacion->CurrentValue = ew_UnFormatDateTime($this->ultimaActualizacion->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idArticulo->CurrentValue = $this->idArticulo->FormValue;
		$this->codExterno->CurrentValue = $this->codExterno->FormValue;
		$this->idAlicuotaIva->CurrentValue = $this->idAlicuotaIva->FormValue;
		$this->idMoneda->CurrentValue = $this->idMoneda->FormValue;
		$this->precio->CurrentValue = $this->precio->FormValue;
		$this->idUnidadMedida->CurrentValue = $this->idUnidadMedida->FormValue;
		$this->dto1->CurrentValue = $this->dto1->FormValue;
		$this->dto2->CurrentValue = $this->dto2->FormValue;
		$this->dto3->CurrentValue = $this->dto3->FormValue;
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->ultimaActualizacion->CurrentValue = $this->ultimaActualizacion->FormValue;
		$this->ultimaActualizacion->CurrentValue = ew_UnFormatDateTime($this->ultimaActualizacion->CurrentValue, 0);
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
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

			// ultimaActualizacion
			$this->ultimaActualizacion->LinkCustomAttributes = "";
			$this->ultimaActualizacion->HrefValue = "";
			$this->ultimaActualizacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idArticulo
			$this->idArticulo->EditAttrs["class"] = "form-control";
			$this->idArticulo->EditCustomAttributes = "";
			if ($this->idArticulo->getSessionValue() <> "") {
				$this->idArticulo->CurrentValue = $this->idArticulo->getSessionValue();
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
			} else {
			if (trim(strval($this->idArticulo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idArticulo->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacionExterna` AS `DispFld`, `denominacionInterna` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `articulos`";
			$sWhereWrk = "";
			$this->idArticulo->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idArticulo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idArticulo->EditValue = $arwrk;
			}

			// codExterno
			$this->codExterno->EditAttrs["class"] = "form-control";
			$this->codExterno->EditCustomAttributes = "";
			$this->codExterno->EditValue = ew_HtmlEncode($this->codExterno->CurrentValue);
			$this->codExterno->PlaceHolder = ew_RemoveHtml($this->codExterno->FldCaption());

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

			// idMoneda
			$this->idMoneda->EditAttrs["class"] = "form-control";
			$this->idMoneda->EditCustomAttributes = "";
			if (trim(strval($this->idMoneda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMoneda->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `simbolo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `monedas`";
			$sWhereWrk = "";
			$this->idMoneda->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idMoneda, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idMoneda->EditValue = $arwrk;

			// precio
			$this->precio->EditAttrs["class"] = "form-control";
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->CurrentValue);
			$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());
			if (strval($this->precio->EditValue) <> "" && is_numeric($this->precio->EditValue)) $this->precio->EditValue = ew_FormatNumber($this->precio->EditValue, -2, -1, -2, 0);

			// idUnidadMedida
			$this->idUnidadMedida->EditAttrs["class"] = "form-control";
			$this->idUnidadMedida->EditCustomAttributes = "";

			// dto1
			$this->dto1->EditAttrs["class"] = "form-control";
			$this->dto1->EditCustomAttributes = "";
			$this->dto1->EditValue = ew_HtmlEncode($this->dto1->CurrentValue);
			$this->dto1->PlaceHolder = ew_RemoveHtml($this->dto1->FldCaption());
			if (strval($this->dto1->EditValue) <> "" && is_numeric($this->dto1->EditValue)) $this->dto1->EditValue = ew_FormatNumber($this->dto1->EditValue, -2, -1, -2, 0);

			// dto2
			$this->dto2->EditAttrs["class"] = "form-control";
			$this->dto2->EditCustomAttributes = "";
			$this->dto2->EditValue = ew_HtmlEncode($this->dto2->CurrentValue);
			$this->dto2->PlaceHolder = ew_RemoveHtml($this->dto2->FldCaption());
			if (strval($this->dto2->EditValue) <> "" && is_numeric($this->dto2->EditValue)) $this->dto2->EditValue = ew_FormatNumber($this->dto2->EditValue, -2, -1, -2, 0);

			// dto3
			$this->dto3->EditAttrs["class"] = "form-control";
			$this->dto3->EditCustomAttributes = "";
			$this->dto3->EditValue = ew_HtmlEncode($this->dto3->CurrentValue);
			$this->dto3->PlaceHolder = ew_RemoveHtml($this->dto3->FldCaption());
			if (strval($this->dto3->EditValue) <> "" && is_numeric($this->dto3->EditValue)) $this->dto3->EditValue = ew_FormatNumber($this->dto3->EditValue, -2, -1, -2, 0);

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = "";
			if ($this->idTercero->getSessionValue() <> "") {
				$this->idTercero->CurrentValue = $this->idTercero->getSessionValue();
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
			} else {
			if (trim(strval($this->idTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, `dto1` AS `Disp2Fld`, `dto2` AS `Disp3Fld`, `dto3` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;
			}

			// ultimaActualizacion
			// Add refer script
			// idArticulo

			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";

			// codExterno
			$this->codExterno->LinkCustomAttributes = "";
			$this->codExterno->HrefValue = "";

			// idAlicuotaIva
			$this->idAlicuotaIva->LinkCustomAttributes = "";
			$this->idAlicuotaIva->HrefValue = "";

			// idMoneda
			$this->idMoneda->LinkCustomAttributes = "";
			$this->idMoneda->HrefValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";

			// dto1
			$this->dto1->LinkCustomAttributes = "";
			$this->dto1->HrefValue = "";

			// dto2
			$this->dto2->LinkCustomAttributes = "";
			$this->dto2->HrefValue = "";

			// dto3
			$this->dto3->LinkCustomAttributes = "";
			$this->dto3->HrefValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// ultimaActualizacion
			$this->ultimaActualizacion->LinkCustomAttributes = "";
			$this->ultimaActualizacion->HrefValue = "";
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
		if (!$this->idArticulo->FldIsDetailKey && !is_null($this->idArticulo->FormValue) && $this->idArticulo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idArticulo->FldCaption(), $this->idArticulo->ReqErrMsg));
		}
		if (!$this->idAlicuotaIva->FldIsDetailKey && !is_null($this->idAlicuotaIva->FormValue) && $this->idAlicuotaIva->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idAlicuotaIva->FldCaption(), $this->idAlicuotaIva->ReqErrMsg));
		}
		if (!$this->idMoneda->FldIsDetailKey && !is_null($this->idMoneda->FormValue) && $this->idMoneda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idMoneda->FldCaption(), $this->idMoneda->ReqErrMsg));
		}
		if (!$this->precio->FldIsDetailKey && !is_null($this->precio->FormValue) && $this->precio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->precio->FldCaption(), $this->precio->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->precio->FormValue)) {
			ew_AddMessage($gsFormError, $this->precio->FldErrMsg());
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
		if (!$this->idTercero->FldIsDetailKey && !is_null($this->idTercero->FormValue) && $this->idTercero->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idTercero->FldCaption(), $this->idTercero->ReqErrMsg));
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

		// Check referential integrity for master table 'articulos'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_articulos();
		if (strval($this->idArticulo->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id@", ew_AdjustSql($this->idArticulo->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["articulos"])) $GLOBALS["articulos"] = new carticulos();
			$rsmaster = $GLOBALS["articulos"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "articulos", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idArticulo
		$this->idArticulo->SetDbValueDef($rsnew, $this->idArticulo->CurrentValue, NULL, FALSE);

		// codExterno
		$this->codExterno->SetDbValueDef($rsnew, $this->codExterno->CurrentValue, NULL, FALSE);

		// idAlicuotaIva
		$this->idAlicuotaIva->SetDbValueDef($rsnew, $this->idAlicuotaIva->CurrentValue, NULL, FALSE);

		// idMoneda
		$this->idMoneda->SetDbValueDef($rsnew, $this->idMoneda->CurrentValue, NULL, FALSE);

		// precio
		$this->precio->SetDbValueDef($rsnew, $this->precio->CurrentValue, NULL, FALSE);

		// idUnidadMedida
		$this->idUnidadMedida->SetDbValueDef($rsnew, $this->idUnidadMedida->CurrentValue, NULL, strval($this->idUnidadMedida->CurrentValue) == "");

		// dto1
		$this->dto1->SetDbValueDef($rsnew, $this->dto1->CurrentValue, NULL, FALSE);

		// dto2
		$this->dto2->SetDbValueDef($rsnew, $this->dto2->CurrentValue, NULL, FALSE);

		// dto3
		$this->dto3->SetDbValueDef($rsnew, $this->dto3->CurrentValue, NULL, FALSE);

		// idTercero
		$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, 0, FALSE);

		// ultimaActualizacion
		$this->ultimaActualizacion->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['ultimaActualizacion'] = &$this->ultimaActualizacion->DbValue;

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("articulos2Dproveedoreslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idArticulo":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacionExterna` AS `DispFld`, `denominacionInterna` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `articulos`";
			$sWhereWrk = "";
			$this->idArticulo->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idArticulo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
		case "x_idMoneda":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `simbolo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `monedas`";
			$sWhereWrk = "";
			$this->idMoneda->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idMoneda, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, `dto1` AS `Disp2Fld`, `dto2` AS `Disp3Fld`, `dto3` AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			$lookuptblfilter = "`idTipoTercero`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
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
if (!isset($articulos2Dproveedores_add)) $articulos2Dproveedores_add = new carticulos2Dproveedores_add();

// Page init
$articulos2Dproveedores_add->Page_Init();

// Page main
$articulos2Dproveedores_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dproveedores_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = farticulos2Dproveedoresadd = new ew_Form("farticulos2Dproveedoresadd", "add");

// Validate form
farticulos2Dproveedoresadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idArticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idArticulo->FldCaption(), $articulos2Dproveedores->idArticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idAlicuotaIva");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idAlicuotaIva->FldCaption(), $articulos2Dproveedores->idAlicuotaIva->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idMoneda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idMoneda->FldCaption(), $articulos2Dproveedores->idMoneda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->precio->FldCaption(), $articulos2Dproveedores->precio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->precio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idTercero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idTercero->FldCaption(), $articulos2Dproveedores->idTercero->ReqErrMsg)) ?>");

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
farticulos2Dproveedoresadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dproveedoresadd.ValidateRequired = true;
<?php } else { ?>
farticulos2Dproveedoresadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dproveedoresadd.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dproveedoresadd.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulos2Dproveedoresadd.Lists["x_idMoneda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_simbolo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"monedas"};
farticulos2Dproveedoresadd.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_dto1","x_dto2","x_dto3"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$articulos2Dproveedores_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $articulos2Dproveedores_add->ShowPageHeader(); ?>
<?php
$articulos2Dproveedores_add->ShowMessage();
?>
<form name="farticulos2Dproveedoresadd" id="farticulos2Dproveedoresadd" class="<?php echo $articulos2Dproveedores_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos2Dproveedores_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos2Dproveedores_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos2Dproveedores">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($articulos2Dproveedores_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($articulos2Dproveedores->getCurrentMasterTable() == "articulos") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="articulos">
<input type="hidden" name="fk_id" value="<?php echo $articulos2Dproveedores->idArticulo->getSessionValue() ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->getCurrentMasterTable() == "terceros") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="terceros">
<input type="hidden" name="fk_id" value="<?php echo $articulos2Dproveedores->idTercero->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
	<div id="r_idArticulo" class="form-group">
		<label id="elh_articulos2Dproveedores_idArticulo" for="x_idArticulo" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->idArticulo->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->idArticulo->getSessionValue() <> "") { ?>
<span id="el_articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idArticulo" name="x_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el_articulos2Dproveedores_idArticulo">
<select data-table="articulos2Dproveedores" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dproveedores->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x_idArticulo" name="x_idArticulo"<?php echo $articulos2Dproveedores->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->SelectOptionListHtml("x_idArticulo") ?>
</select>
<input type="hidden" name="s_x_idArticulo" id="s_x_idArticulo" value="<?php echo $articulos2Dproveedores->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $articulos2Dproveedores->idArticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
	<div id="r_codExterno" class="form-group">
		<label id="elh_articulos2Dproveedores_codExterno" for="x_codExterno" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->codExterno->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_codExterno">
<input type="text" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x_codExterno" id="x_codExterno" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->codExterno->EditValue ?>"<?php echo $articulos2Dproveedores->codExterno->EditAttributes() ?>>
</span>
<?php echo $articulos2Dproveedores->codExterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<div id="r_idAlicuotaIva" class="form-group">
		<label id="elh_articulos2Dproveedores_idAlicuotaIva" for="x_idAlicuotaIva" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->idAlicuotaIva->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_idAlicuotaIva">
<select data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos2Dproveedores->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x_idAlicuotaIva" name="x_idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->SelectOptionListHtml("x_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x_idAlicuotaIva" id="s_x_idAlicuotaIva" value="<?php echo $articulos2Dproveedores->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
<?php echo $articulos2Dproveedores->idAlicuotaIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
	<div id="r_idMoneda" class="form-group">
		<label id="elh_articulos2Dproveedores_idMoneda" for="x_idMoneda" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->idMoneda->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_idMoneda">
<select data-table="articulos2Dproveedores" data-field="x_idMoneda" data-value-separator="<?php echo $articulos2Dproveedores->idMoneda->DisplayValueSeparatorAttribute() ?>" id="x_idMoneda" name="x_idMoneda"<?php echo $articulos2Dproveedores->idMoneda->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->SelectOptionListHtml("x_idMoneda") ?>
</select>
<input type="hidden" name="s_x_idMoneda" id="s_x_idMoneda" value="<?php echo $articulos2Dproveedores->idMoneda->LookupFilterQuery() ?>">
</span>
<?php echo $articulos2Dproveedores->idMoneda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
	<div id="r_precio" class="form-group">
		<label id="elh_articulos2Dproveedores_precio" for="x_precio" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->precio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->precio->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_precio">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precio" name="x_precio" id="x_precio" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precio->EditValue ?>"<?php echo $articulos2Dproveedores->precio->EditAttributes() ?>>
</span>
<?php echo $articulos2Dproveedores->precio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<div id="r_idUnidadMedida" class="form-group">
		<label id="elh_articulos2Dproveedores_idUnidadMedida" for="x_idUnidadMedida" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->idUnidadMedida->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_idUnidadMedida">
<select data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dproveedores->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x_idUnidadMedida" name="x_idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->SelectOptionListHtml("x_idUnidadMedida") ?>
</select>
</span>
<?php echo $articulos2Dproveedores->idUnidadMedida->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
	<div id="r_dto1" class="form-group">
		<label id="elh_articulos2Dproveedores_dto1" for="x_dto1" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->dto1->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_dto1">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto1" name="x_dto1" id="x_dto1" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto1->EditValue ?>"<?php echo $articulos2Dproveedores->dto1->EditAttributes() ?>>
</span>
<?php echo $articulos2Dproveedores->dto1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
	<div id="r_dto2" class="form-group">
		<label id="elh_articulos2Dproveedores_dto2" for="x_dto2" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->dto2->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_dto2">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto2" name="x_dto2" id="x_dto2" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto2->EditValue ?>"<?php echo $articulos2Dproveedores->dto2->EditAttributes() ?>>
</span>
<?php echo $articulos2Dproveedores->dto2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
	<div id="r_dto3" class="form-group">
		<label id="elh_articulos2Dproveedores_dto3" for="x_dto3" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->dto3->CellAttributes() ?>>
<span id="el_articulos2Dproveedores_dto3">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto3" name="x_dto3" id="x_dto3" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto3->EditValue ?>"<?php echo $articulos2Dproveedores->dto3->EditAttributes() ?>>
</span>
<?php echo $articulos2Dproveedores->dto3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
	<div id="r_idTercero" class="form-group">
		<label id="elh_articulos2Dproveedores_idTercero" for="x_idTercero" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dproveedores->idTercero->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->idTercero->getSessionValue() <> "") { ?>
<span id="el_articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idTercero" name="x_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el_articulos2Dproveedores_idTercero">
<select data-table="articulos2Dproveedores" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dproveedores->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $articulos2Dproveedores->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $articulos2Dproveedores->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $articulos2Dproveedores->idTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$articulos2Dproveedores_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos2Dproveedores_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
farticulos2Dproveedoresadd.Init();
</script>
<?php
$articulos2Dproveedores_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$(document).ready(function() {
	var idarticulo = $("#x_idArticulo").val();
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
			console.log(response)
			if (response.exito) {
				$("#x_idUnidadMedida").empty();
				var html = ''
				response.exito.forEach(unidad => {
					html += '<option value="' + unidad.id + '">' + unidad.denominacion + '</option>'
				});
				$("#x_idUnidadMedida").html(html);
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
$articulos2Dproveedores_add->Page_Terminate();
?>
