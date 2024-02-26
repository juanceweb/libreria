<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "recibos2Dpagosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$recibos2Dpagos_edit = NULL; // Initialize page object first

class crecibos2Dpagos_edit extends crecibos2Dpagos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'recibos-pagos';

	// Page object name
	var $PageObjName = 'recibos2Dpagos_edit';

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

		// Table object (recibos2Dpagos)
		if (!isset($GLOBALS["recibos2Dpagos"]) || get_class($GLOBALS["recibos2Dpagos"]) == "crecibos2Dpagos") {
			$GLOBALS["recibos2Dpagos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["recibos2Dpagos"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'recibos-pagos', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("recibos2Dpagoslist.php"));
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
		$this->tipoFlujo->SetVisibility();
		$this->fecha->SetVisibility();
		$this->idTercero->SetVisibility();
		$this->importe->SetVisibility();
		$this->importeMovimientos->SetVisibility();
		$this->importeAdelantos->SetVisibility();
		$this->nroComprobante->SetVisibility();
		$this->contable->SetVisibility();
		$this->idEstado->SetVisibility();

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
		global $EW_EXPORT, $recibos2Dpagos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($recibos2Dpagos);
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
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("recibos2Dpagoslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("recibos2Dpagoslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "recibos2Dpagoslist.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->tipoFlujo->FldIsDetailKey) {
			$this->tipoFlujo->setFormValue($objForm->GetValue("x_tipoFlujo"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		}
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->importe->FldIsDetailKey) {
			$this->importe->setFormValue($objForm->GetValue("x_importe"));
		}
		if (!$this->importeMovimientos->FldIsDetailKey) {
			$this->importeMovimientos->setFormValue($objForm->GetValue("x_importeMovimientos"));
		}
		if (!$this->importeAdelantos->FldIsDetailKey) {
			$this->importeAdelantos->setFormValue($objForm->GetValue("x_importeAdelantos"));
		}
		if (!$this->nroComprobante->FldIsDetailKey) {
			$this->nroComprobante->setFormValue($objForm->GetValue("x_nroComprobante"));
		}
		if (!$this->contable->FldIsDetailKey) {
			$this->contable->setFormValue($objForm->GetValue("x_contable"));
		}
		if (!$this->idEstado->FldIsDetailKey) {
			$this->idEstado->setFormValue($objForm->GetValue("x_idEstado"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->tipoFlujo->CurrentValue = $this->tipoFlujo->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->importe->CurrentValue = $this->importe->FormValue;
		$this->importeMovimientos->CurrentValue = $this->importeMovimientos->FormValue;
		$this->importeAdelantos->CurrentValue = $this->importeAdelantos->FormValue;
		$this->nroComprobante->CurrentValue = $this->nroComprobante->FormValue;
		$this->contable->CurrentValue = $this->contable->FormValue;
		$this->idEstado->CurrentValue = $this->idEstado->FormValue;
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
		$this->tipoFlujo->setDbValue($rs->fields('tipoFlujo'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->importe->setDbValue($rs->fields('importe'));
		$this->importeMovimientos->setDbValue($rs->fields('importeMovimientos'));
		$this->importeAdelantos->setDbValue($rs->fields('importeAdelantos'));
		$this->valorDolar->setDbValue($rs->fields('valorDolar'));
		$this->nroComprobante->setDbValue($rs->fields('nroComprobante'));
		$this->contable->setDbValue($rs->fields('contable'));
		$this->idEstado->setDbValue($rs->fields('idEstado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tipoFlujo->DbValue = $row['tipoFlujo'];
		$this->fecha->DbValue = $row['fecha'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->importe->DbValue = $row['importe'];
		$this->importeMovimientos->DbValue = $row['importeMovimientos'];
		$this->importeAdelantos->DbValue = $row['importeAdelantos'];
		$this->valorDolar->DbValue = $row['valorDolar'];
		$this->nroComprobante->DbValue = $row['nroComprobante'];
		$this->contable->DbValue = $row['contable'];
		$this->idEstado->DbValue = $row['idEstado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->importe->FormValue == $this->importe->CurrentValue && is_numeric(ew_StrToFloat($this->importe->CurrentValue)))
			$this->importe->CurrentValue = ew_StrToFloat($this->importe->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeMovimientos->FormValue == $this->importeMovimientos->CurrentValue && is_numeric(ew_StrToFloat($this->importeMovimientos->CurrentValue)))
			$this->importeMovimientos->CurrentValue = ew_StrToFloat($this->importeMovimientos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeAdelantos->FormValue == $this->importeAdelantos->CurrentValue && is_numeric(ew_StrToFloat($this->importeAdelantos->CurrentValue)))
			$this->importeAdelantos->CurrentValue = ew_StrToFloat($this->importeAdelantos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tipoFlujo
		// fecha
		// idTercero
		// importe
		// importeMovimientos
		// importeAdelantos
		// valorDolar
		// nroComprobante
		// contable
		// idEstado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// tipoFlujo
		if (strval($this->tipoFlujo->CurrentValue) <> "") {
			$this->tipoFlujo->ViewValue = $this->tipoFlujo->OptionCaption($this->tipoFlujo->CurrentValue);
		} else {
			$this->tipoFlujo->ViewValue = NULL;
		}
		$this->tipoFlujo->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// idTercero
		if (strval($this->idTercero->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->idTercero->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// importe
		$this->importe->ViewValue = $this->importe->CurrentValue;
		$this->importe->ViewCustomAttributes = "";

		// importeMovimientos
		$this->importeMovimientos->ViewValue = $this->importeMovimientos->CurrentValue;
		$this->importeMovimientos->ViewCustomAttributes = "";

		// importeAdelantos
		$this->importeAdelantos->ViewValue = $this->importeAdelantos->CurrentValue;
		$this->importeAdelantos->ViewCustomAttributes = "";

		// nroComprobante
		$this->nroComprobante->ViewValue = $this->nroComprobante->CurrentValue;
		$this->nroComprobante->ViewCustomAttributes = "";

		// contable
		$this->contable->ViewValue = $this->contable->CurrentValue;
		$this->contable->ViewCustomAttributes = "";

		// idEstado
		$this->idEstado->ViewValue = $this->idEstado->CurrentValue;
		$this->idEstado->ViewCustomAttributes = "";

			// tipoFlujo
			$this->tipoFlujo->LinkCustomAttributes = "";
			$this->tipoFlujo->HrefValue = "";
			$this->tipoFlujo->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// importe
			$this->importe->LinkCustomAttributes = "";
			$this->importe->HrefValue = "";
			$this->importe->TooltipValue = "";

			// importeMovimientos
			$this->importeMovimientos->LinkCustomAttributes = "";
			$this->importeMovimientos->HrefValue = "";
			$this->importeMovimientos->TooltipValue = "";

			// importeAdelantos
			$this->importeAdelantos->LinkCustomAttributes = "";
			$this->importeAdelantos->HrefValue = "";
			$this->importeAdelantos->TooltipValue = "";

			// nroComprobante
			$this->nroComprobante->LinkCustomAttributes = "";
			$this->nroComprobante->HrefValue = "";
			$this->nroComprobante->TooltipValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";
			$this->contable->TooltipValue = "";

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
			$this->idEstado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// tipoFlujo
			$this->tipoFlujo->EditAttrs["class"] = "form-control";
			$this->tipoFlujo->EditCustomAttributes = "";
			$this->tipoFlujo->EditValue = $this->tipoFlujo->Options(TRUE);

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// idTercero
			$this->idTercero->EditAttrs["class"] = "form-control";
			$this->idTercero->EditCustomAttributes = "";
			if (trim(strval($this->idTercero->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idTercero->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idTercero->EditValue = $arwrk;

			// importe
			$this->importe->EditAttrs["class"] = "form-control";
			$this->importe->EditCustomAttributes = "";
			$this->importe->EditValue = ew_HtmlEncode($this->importe->CurrentValue);
			$this->importe->PlaceHolder = ew_RemoveHtml($this->importe->FldCaption());
			if (strval($this->importe->EditValue) <> "" && is_numeric($this->importe->EditValue)) $this->importe->EditValue = ew_FormatNumber($this->importe->EditValue, -2, -1, -2, 0);

			// importeMovimientos
			$this->importeMovimientos->EditAttrs["class"] = "form-control";
			$this->importeMovimientos->EditCustomAttributes = "";
			$this->importeMovimientos->EditValue = ew_HtmlEncode($this->importeMovimientos->CurrentValue);
			$this->importeMovimientos->PlaceHolder = ew_RemoveHtml($this->importeMovimientos->FldCaption());
			if (strval($this->importeMovimientos->EditValue) <> "" && is_numeric($this->importeMovimientos->EditValue)) $this->importeMovimientos->EditValue = ew_FormatNumber($this->importeMovimientos->EditValue, -2, -1, -2, 0);

			// importeAdelantos
			$this->importeAdelantos->EditAttrs["class"] = "form-control";
			$this->importeAdelantos->EditCustomAttributes = "";
			$this->importeAdelantos->EditValue = ew_HtmlEncode($this->importeAdelantos->CurrentValue);
			$this->importeAdelantos->PlaceHolder = ew_RemoveHtml($this->importeAdelantos->FldCaption());
			if (strval($this->importeAdelantos->EditValue) <> "" && is_numeric($this->importeAdelantos->EditValue)) $this->importeAdelantos->EditValue = ew_FormatNumber($this->importeAdelantos->EditValue, -2, -1, -2, 0);

			// nroComprobante
			$this->nroComprobante->EditAttrs["class"] = "form-control";
			$this->nroComprobante->EditCustomAttributes = "";
			$this->nroComprobante->EditValue = ew_HtmlEncode($this->nroComprobante->CurrentValue);
			$this->nroComprobante->PlaceHolder = ew_RemoveHtml($this->nroComprobante->FldCaption());

			// contable
			$this->contable->EditAttrs["class"] = "form-control";
			$this->contable->EditCustomAttributes = "";
			$this->contable->EditValue = ew_HtmlEncode($this->contable->CurrentValue);
			$this->contable->PlaceHolder = ew_RemoveHtml($this->contable->FldCaption());

			// idEstado
			$this->idEstado->EditAttrs["class"] = "form-control";
			$this->idEstado->EditCustomAttributes = "";
			$this->idEstado->EditValue = ew_HtmlEncode($this->idEstado->CurrentValue);
			$this->idEstado->PlaceHolder = ew_RemoveHtml($this->idEstado->FldCaption());

			// Edit refer script
			// tipoFlujo

			$this->tipoFlujo->LinkCustomAttributes = "";
			$this->tipoFlujo->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// importe
			$this->importe->LinkCustomAttributes = "";
			$this->importe->HrefValue = "";

			// importeMovimientos
			$this->importeMovimientos->LinkCustomAttributes = "";
			$this->importeMovimientos->HrefValue = "";

			// importeAdelantos
			$this->importeAdelantos->LinkCustomAttributes = "";
			$this->importeAdelantos->HrefValue = "";

			// nroComprobante
			$this->nroComprobante->LinkCustomAttributes = "";
			$this->nroComprobante->HrefValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";

			// idEstado
			$this->idEstado->LinkCustomAttributes = "";
			$this->idEstado->HrefValue = "";
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
		if (!ew_CheckDateDef($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importe->FormValue)) {
			ew_AddMessage($gsFormError, $this->importe->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeMovimientos->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeMovimientos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeAdelantos->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeAdelantos->FldErrMsg());
		}
		if (!ew_CheckInteger($this->contable->FormValue)) {
			ew_AddMessage($gsFormError, $this->contable->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idEstado->FormValue)) {
			ew_AddMessage($gsFormError, $this->idEstado->FldErrMsg());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// tipoFlujo
			$this->tipoFlujo->SetDbValueDef($rsnew, $this->tipoFlujo->CurrentValue, NULL, $this->tipoFlujo->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 0), NULL, $this->fecha->ReadOnly);

			// idTercero
			$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, $this->idTercero->ReadOnly);

			// importe
			$this->importe->SetDbValueDef($rsnew, $this->importe->CurrentValue, NULL, $this->importe->ReadOnly);

			// importeMovimientos
			$this->importeMovimientos->SetDbValueDef($rsnew, $this->importeMovimientos->CurrentValue, NULL, $this->importeMovimientos->ReadOnly);

			// importeAdelantos
			$this->importeAdelantos->SetDbValueDef($rsnew, $this->importeAdelantos->CurrentValue, NULL, $this->importeAdelantos->ReadOnly);

			// nroComprobante
			$this->nroComprobante->SetDbValueDef($rsnew, $this->nroComprobante->CurrentValue, NULL, $this->nroComprobante->ReadOnly);

			// contable
			$this->contable->SetDbValueDef($rsnew, $this->contable->CurrentValue, NULL, $this->contable->ReadOnly);

			// idEstado
			$this->idEstado->SetDbValueDef($rsnew, $this->idEstado->CurrentValue, NULL, $this->idEstado->ReadOnly);

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
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("recibos2Dpagoslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_idTercero":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->idTercero->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idTercero, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
if (!isset($recibos2Dpagos_edit)) $recibos2Dpagos_edit = new crecibos2Dpagos_edit();

// Page init
$recibos2Dpagos_edit->Page_Init();

// Page main
$recibos2Dpagos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$recibos2Dpagos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = frecibos2Dpagosedit = new ew_Form("frecibos2Dpagosedit", "edit");

// Validate form
frecibos2Dpagosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->importe->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeMovimientos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->importeMovimientos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeAdelantos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->importeAdelantos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_contable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->contable->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idEstado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos2Dpagos->idEstado->FldErrMsg()) ?>");

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
frecibos2Dpagosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecibos2Dpagosedit.ValidateRequired = true;
<?php } else { ?>
frecibos2Dpagosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecibos2Dpagosedit.Lists["x_tipoFlujo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
frecibos2Dpagosedit.Lists["x_tipoFlujo"].Options = <?php echo json_encode($recibos2Dpagos->tipoFlujo->Options()) ?>;
frecibos2Dpagosedit.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$recibos2Dpagos_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $recibos2Dpagos_edit->ShowPageHeader(); ?>
<?php
$recibos2Dpagos_edit->ShowMessage();
?>
<form name="frecibos2Dpagosedit" id="frecibos2Dpagosedit" class="<?php echo $recibos2Dpagos_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($recibos2Dpagos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $recibos2Dpagos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="recibos2Dpagos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($recibos2Dpagos_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($recibos2Dpagos->tipoFlujo->Visible) { // tipoFlujo ?>
	<div id="r_tipoFlujo" class="form-group">
		<label id="elh_recibos2Dpagos_tipoFlujo" for="x_tipoFlujo" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->tipoFlujo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->tipoFlujo->CellAttributes() ?>>
<span id="el_recibos2Dpagos_tipoFlujo">
<select data-table="recibos2Dpagos" data-field="x_tipoFlujo" data-value-separator="<?php echo $recibos2Dpagos->tipoFlujo->DisplayValueSeparatorAttribute() ?>" id="x_tipoFlujo" name="x_tipoFlujo"<?php echo $recibos2Dpagos->tipoFlujo->EditAttributes() ?>>
<?php echo $recibos2Dpagos->tipoFlujo->SelectOptionListHtml("x_tipoFlujo") ?>
</select>
</span>
<?php echo $recibos2Dpagos->tipoFlujo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_recibos2Dpagos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->fecha->CellAttributes() ?>>
<span id="el_recibos2Dpagos_fecha">
<input type="text" data-table="recibos2Dpagos" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->fecha->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->fecha->EditValue ?>"<?php echo $recibos2Dpagos->fecha->EditAttributes() ?>>
<?php if (!$recibos2Dpagos->fecha->ReadOnly && !$recibos2Dpagos->fecha->Disabled && !isset($recibos2Dpagos->fecha->EditAttrs["readonly"]) && !isset($recibos2Dpagos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("frecibos2Dpagosedit", "x_fecha", 0);
</script>
<?php } ?>
</span>
<?php echo $recibos2Dpagos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->idTercero->Visible) { // idTercero ?>
	<div id="r_idTercero" class="form-group">
		<label id="elh_recibos2Dpagos_idTercero" for="x_idTercero" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->idTercero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->idTercero->CellAttributes() ?>>
<span id="el_recibos2Dpagos_idTercero">
<select data-table="recibos2Dpagos" data-field="x_idTercero" data-value-separator="<?php echo $recibos2Dpagos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $recibos2Dpagos->idTercero->EditAttributes() ?>>
<?php echo $recibos2Dpagos->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $recibos2Dpagos->idTercero->LookupFilterQuery() ?>">
</span>
<?php echo $recibos2Dpagos->idTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->importe->Visible) { // importe ?>
	<div id="r_importe" class="form-group">
		<label id="elh_recibos2Dpagos_importe" for="x_importe" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->importe->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->importe->CellAttributes() ?>>
<span id="el_recibos2Dpagos_importe">
<input type="text" data-table="recibos2Dpagos" data-field="x_importe" name="x_importe" id="x_importe" size="30" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->importe->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->importe->EditValue ?>"<?php echo $recibos2Dpagos->importe->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->importe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->importeMovimientos->Visible) { // importeMovimientos ?>
	<div id="r_importeMovimientos" class="form-group">
		<label id="elh_recibos2Dpagos_importeMovimientos" for="x_importeMovimientos" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->importeMovimientos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->importeMovimientos->CellAttributes() ?>>
<span id="el_recibos2Dpagos_importeMovimientos">
<input type="text" data-table="recibos2Dpagos" data-field="x_importeMovimientos" name="x_importeMovimientos" id="x_importeMovimientos" size="30" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->importeMovimientos->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->importeMovimientos->EditValue ?>"<?php echo $recibos2Dpagos->importeMovimientos->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->importeMovimientos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->importeAdelantos->Visible) { // importeAdelantos ?>
	<div id="r_importeAdelantos" class="form-group">
		<label id="elh_recibos2Dpagos_importeAdelantos" for="x_importeAdelantos" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->importeAdelantos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->importeAdelantos->CellAttributes() ?>>
<span id="el_recibos2Dpagos_importeAdelantos">
<input type="text" data-table="recibos2Dpagos" data-field="x_importeAdelantos" name="x_importeAdelantos" id="x_importeAdelantos" size="30" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->importeAdelantos->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->importeAdelantos->EditValue ?>"<?php echo $recibos2Dpagos->importeAdelantos->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->importeAdelantos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->nroComprobante->Visible) { // nroComprobante ?>
	<div id="r_nroComprobante" class="form-group">
		<label id="elh_recibos2Dpagos_nroComprobante" for="x_nroComprobante" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->nroComprobante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->nroComprobante->CellAttributes() ?>>
<span id="el_recibos2Dpagos_nroComprobante">
<input type="text" data-table="recibos2Dpagos" data-field="x_nroComprobante" name="x_nroComprobante" id="x_nroComprobante" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->nroComprobante->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->nroComprobante->EditValue ?>"<?php echo $recibos2Dpagos->nroComprobante->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->nroComprobante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->contable->Visible) { // contable ?>
	<div id="r_contable" class="form-group">
		<label id="elh_recibos2Dpagos_contable" for="x_contable" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->contable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->contable->CellAttributes() ?>>
<span id="el_recibos2Dpagos_contable">
<input type="text" data-table="recibos2Dpagos" data-field="x_contable" name="x_contable" id="x_contable" size="30" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->contable->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->contable->EditValue ?>"<?php echo $recibos2Dpagos->contable->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->contable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos2Dpagos->idEstado->Visible) { // idEstado ?>
	<div id="r_idEstado" class="form-group">
		<label id="elh_recibos2Dpagos_idEstado" for="x_idEstado" class="col-sm-2 control-label ewLabel"><?php echo $recibos2Dpagos->idEstado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos2Dpagos->idEstado->CellAttributes() ?>>
<span id="el_recibos2Dpagos_idEstado">
<input type="text" data-table="recibos2Dpagos" data-field="x_idEstado" name="x_idEstado" id="x_idEstado" size="30" placeholder="<?php echo ew_HtmlEncode($recibos2Dpagos->idEstado->getPlaceHolder()) ?>" value="<?php echo $recibos2Dpagos->idEstado->EditValue ?>"<?php echo $recibos2Dpagos->idEstado->EditAttributes() ?>>
</span>
<?php echo $recibos2Dpagos->idEstado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="recibos2Dpagos" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($recibos2Dpagos->id->CurrentValue) ?>">
<?php if (!$recibos2Dpagos_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $recibos2Dpagos_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
frecibos2Dpagosedit.Init();
</script>
<?php
$recibos2Dpagos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$recibos2Dpagos_edit->Page_Terminate();
?>
