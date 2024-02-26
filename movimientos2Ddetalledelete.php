<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "movimientos2Ddetalleinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$movimientos2Ddetalle_delete = NULL; // Initialize page object first

class cmovimientos2Ddetalle_delete extends cmovimientos2Ddetalle {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientos-detalle';

	// Page object name
	var $PageObjName = 'movimientos2Ddetalle_delete';

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

		// Table object (movimientos2Ddetalle)
		if (!isset($GLOBALS["movimientos2Ddetalle"]) || get_class($GLOBALS["movimientos2Ddetalle"]) == "cmovimientos2Ddetalle") {
			$GLOBALS["movimientos2Ddetalle"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["movimientos2Ddetalle"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'movimientos-detalle', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("movimientos2Ddetallelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->idMovimientos->SetVisibility();
		$this->cant->SetVisibility();
		$this->idUnidadMedida->SetVisibility();
		$this->codProducto->SetVisibility();
		$this->medida->SetVisibility();
		$this->nombreProducto->SetVisibility();
		$this->importeUnitario->SetVisibility();
		$this->bonificacion->SetVisibility();
		$this->importeTotal->SetVisibility();
		$this->alicuotaIva->SetVisibility();
		$this->importeIva->SetVisibility();
		$this->importeNeto->SetVisibility();
		$this->importePesos->SetVisibility();
		$this->estadoImportacion->SetVisibility();
		$this->origenImportacion->SetVisibility();

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
		global $EW_EXPORT, $movimientos2Ddetalle;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($movimientos2Ddetalle);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("movimientos2Ddetallelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in movimientos2Ddetalle class, movimientos2Ddetalleinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("movimientos2Ddetallelist.php"); // Return to list
			}
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
		$this->idMovimientos->setDbValue($rs->fields('idMovimientos'));
		$this->cant->setDbValue($rs->fields('cant'));
		$this->idUnidadMedida->setDbValue($rs->fields('idUnidadMedida'));
		$this->codProducto->setDbValue($rs->fields('codProducto'));
		$this->medida->setDbValue($rs->fields('medida'));
		$this->nombreProducto->setDbValue($rs->fields('nombreProducto'));
		$this->importeUnitario->setDbValue($rs->fields('importeUnitario'));
		$this->bonificacion->setDbValue($rs->fields('bonificacion'));
		$this->importeTotal->setDbValue($rs->fields('importeTotal'));
		$this->alicuotaIva->setDbValue($rs->fields('alicuotaIva'));
		$this->importeIva->setDbValue($rs->fields('importeIva'));
		$this->importeNeto->setDbValue($rs->fields('importeNeto'));
		$this->importePesos->setDbValue($rs->fields('importePesos'));
		$this->estadoImportacion->setDbValue($rs->fields('estadoImportacion'));
		$this->origenImportacion->setDbValue($rs->fields('origenImportacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idMovimientos->DbValue = $row['idMovimientos'];
		$this->cant->DbValue = $row['cant'];
		$this->idUnidadMedida->DbValue = $row['idUnidadMedida'];
		$this->codProducto->DbValue = $row['codProducto'];
		$this->medida->DbValue = $row['medida'];
		$this->nombreProducto->DbValue = $row['nombreProducto'];
		$this->importeUnitario->DbValue = $row['importeUnitario'];
		$this->bonificacion->DbValue = $row['bonificacion'];
		$this->importeTotal->DbValue = $row['importeTotal'];
		$this->alicuotaIva->DbValue = $row['alicuotaIva'];
		$this->importeIva->DbValue = $row['importeIva'];
		$this->importeNeto->DbValue = $row['importeNeto'];
		$this->importePesos->DbValue = $row['importePesos'];
		$this->estadoImportacion->DbValue = $row['estadoImportacion'];
		$this->origenImportacion->DbValue = $row['origenImportacion'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->cant->FormValue == $this->cant->CurrentValue && is_numeric(ew_StrToFloat($this->cant->CurrentValue)))
			$this->cant->CurrentValue = ew_StrToFloat($this->cant->CurrentValue);

		// Convert decimal values if posted back
		if ($this->medida->FormValue == $this->medida->CurrentValue && is_numeric(ew_StrToFloat($this->medida->CurrentValue)))
			$this->medida->CurrentValue = ew_StrToFloat($this->medida->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeUnitario->FormValue == $this->importeUnitario->CurrentValue && is_numeric(ew_StrToFloat($this->importeUnitario->CurrentValue)))
			$this->importeUnitario->CurrentValue = ew_StrToFloat($this->importeUnitario->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bonificacion->FormValue == $this->bonificacion->CurrentValue && is_numeric(ew_StrToFloat($this->bonificacion->CurrentValue)))
			$this->bonificacion->CurrentValue = ew_StrToFloat($this->bonificacion->CurrentValue);

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
		if ($this->importePesos->FormValue == $this->importePesos->CurrentValue && is_numeric(ew_StrToFloat($this->importePesos->CurrentValue)))
			$this->importePesos->CurrentValue = ew_StrToFloat($this->importePesos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idMovimientos
		// cant
		// idUnidadMedida
		// codProducto
		// medida
		// nombreProducto
		// importeUnitario
		// bonificacion
		// importeTotal
		// alicuotaIva
		// importeIva
		// importeNeto
		// importePesos
		// estadoImportacion
		// origenImportacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idMovimientos
		$this->idMovimientos->ViewValue = $this->idMovimientos->CurrentValue;
		$this->idMovimientos->ViewCustomAttributes = "";

		// cant
		$this->cant->ViewValue = $this->cant->CurrentValue;
		$this->cant->ViewCustomAttributes = "";

		// idUnidadMedida
		$this->idUnidadMedida->ViewValue = $this->idUnidadMedida->CurrentValue;
		$this->idUnidadMedida->ViewCustomAttributes = "";

		// codProducto
		$this->codProducto->ViewValue = $this->codProducto->CurrentValue;
		$this->codProducto->ViewCustomAttributes = "";

		// medida
		$this->medida->ViewValue = $this->medida->CurrentValue;
		$this->medida->ViewCustomAttributes = "";

		// nombreProducto
		$this->nombreProducto->ViewValue = $this->nombreProducto->CurrentValue;
		$this->nombreProducto->ViewCustomAttributes = "";

		// importeUnitario
		$this->importeUnitario->ViewValue = $this->importeUnitario->CurrentValue;
		$this->importeUnitario->ViewCustomAttributes = "";

		// bonificacion
		$this->bonificacion->ViewValue = $this->bonificacion->CurrentValue;
		$this->bonificacion->ViewCustomAttributes = "";

		// importeTotal
		$this->importeTotal->ViewValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->ViewCustomAttributes = "";

		// alicuotaIva
		$this->alicuotaIva->ViewValue = $this->alicuotaIva->CurrentValue;
		$this->alicuotaIva->ViewCustomAttributes = "";

		// importeIva
		$this->importeIva->ViewValue = $this->importeIva->CurrentValue;
		$this->importeIva->ViewCustomAttributes = "";

		// importeNeto
		$this->importeNeto->ViewValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->ViewCustomAttributes = "";

		// importePesos
		$this->importePesos->ViewValue = $this->importePesos->CurrentValue;
		$this->importePesos->ViewCustomAttributes = "";

		// estadoImportacion
		$this->estadoImportacion->ViewValue = $this->estadoImportacion->CurrentValue;
		$this->estadoImportacion->ViewCustomAttributes = "";

		// origenImportacion
		$this->origenImportacion->ViewValue = $this->origenImportacion->CurrentValue;
		$this->origenImportacion->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idMovimientos
			$this->idMovimientos->LinkCustomAttributes = "";
			$this->idMovimientos->HrefValue = "";
			$this->idMovimientos->TooltipValue = "";

			// cant
			$this->cant->LinkCustomAttributes = "";
			$this->cant->HrefValue = "";
			$this->cant->TooltipValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";
			$this->idUnidadMedida->TooltipValue = "";

			// codProducto
			$this->codProducto->LinkCustomAttributes = "";
			$this->codProducto->HrefValue = "";
			$this->codProducto->TooltipValue = "";

			// medida
			$this->medida->LinkCustomAttributes = "";
			$this->medida->HrefValue = "";
			$this->medida->TooltipValue = "";

			// nombreProducto
			$this->nombreProducto->LinkCustomAttributes = "";
			$this->nombreProducto->HrefValue = "";
			$this->nombreProducto->TooltipValue = "";

			// importeUnitario
			$this->importeUnitario->LinkCustomAttributes = "";
			$this->importeUnitario->HrefValue = "";
			$this->importeUnitario->TooltipValue = "";

			// bonificacion
			$this->bonificacion->LinkCustomAttributes = "";
			$this->bonificacion->HrefValue = "";
			$this->bonificacion->TooltipValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";
			$this->importeTotal->TooltipValue = "";

			// alicuotaIva
			$this->alicuotaIva->LinkCustomAttributes = "";
			$this->alicuotaIva->HrefValue = "";
			$this->alicuotaIva->TooltipValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";
			$this->importeIva->TooltipValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";
			$this->importeNeto->TooltipValue = "";

			// importePesos
			$this->importePesos->LinkCustomAttributes = "";
			$this->importePesos->HrefValue = "";
			$this->importePesos->TooltipValue = "";

			// estadoImportacion
			$this->estadoImportacion->LinkCustomAttributes = "";
			$this->estadoImportacion->HrefValue = "";
			$this->estadoImportacion->TooltipValue = "";

			// origenImportacion
			$this->origenImportacion->LinkCustomAttributes = "";
			$this->origenImportacion->HrefValue = "";
			$this->origenImportacion->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("movimientos2Ddetallelist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($movimientos2Ddetalle_delete)) $movimientos2Ddetalle_delete = new cmovimientos2Ddetalle_delete();

// Page init
$movimientos2Ddetalle_delete->Page_Init();

// Page main
$movimientos2Ddetalle_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$movimientos2Ddetalle_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fmovimientos2Ddetalledelete = new ew_Form("fmovimientos2Ddetalledelete", "delete");

// Form_CustomValidate event
fmovimientos2Ddetalledelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmovimientos2Ddetalledelete.ValidateRequired = true;
<?php } else { ?>
fmovimientos2Ddetalledelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $movimientos2Ddetalle_delete->ShowPageHeader(); ?>
<?php
$movimientos2Ddetalle_delete->ShowMessage();
?>
<form name="fmovimientos2Ddetalledelete" id="fmovimientos2Ddetalledelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($movimientos2Ddetalle_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $movimientos2Ddetalle_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="movimientos2Ddetalle">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($movimientos2Ddetalle_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $movimientos2Ddetalle->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
		<th><span id="elh_movimientos2Ddetalle_id" class="movimientos2Ddetalle_id"><?php echo $movimientos2Ddetalle->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
		<th><span id="elh_movimientos2Ddetalle_idMovimientos" class="movimientos2Ddetalle_idMovimientos"><?php echo $movimientos2Ddetalle->idMovimientos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
		<th><span id="elh_movimientos2Ddetalle_cant" class="movimientos2Ddetalle_cant"><?php echo $movimientos2Ddetalle->cant->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<th><span id="elh_movimientos2Ddetalle_idUnidadMedida" class="movimientos2Ddetalle_idUnidadMedida"><?php echo $movimientos2Ddetalle->idUnidadMedida->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
		<th><span id="elh_movimientos2Ddetalle_codProducto" class="movimientos2Ddetalle_codProducto"><?php echo $movimientos2Ddetalle->codProducto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
		<th><span id="elh_movimientos2Ddetalle_medida" class="movimientos2Ddetalle_medida"><?php echo $movimientos2Ddetalle->medida->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
		<th><span id="elh_movimientos2Ddetalle_nombreProducto" class="movimientos2Ddetalle_nombreProducto"><?php echo $movimientos2Ddetalle->nombreProducto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
		<th><span id="elh_movimientos2Ddetalle_importeUnitario" class="movimientos2Ddetalle_importeUnitario"><?php echo $movimientos2Ddetalle->importeUnitario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
		<th><span id="elh_movimientos2Ddetalle_bonificacion" class="movimientos2Ddetalle_bonificacion"><?php echo $movimientos2Ddetalle->bonificacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
		<th><span id="elh_movimientos2Ddetalle_importeTotal" class="movimientos2Ddetalle_importeTotal"><?php echo $movimientos2Ddetalle->importeTotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
		<th><span id="elh_movimientos2Ddetalle_alicuotaIva" class="movimientos2Ddetalle_alicuotaIva"><?php echo $movimientos2Ddetalle->alicuotaIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
		<th><span id="elh_movimientos2Ddetalle_importeIva" class="movimientos2Ddetalle_importeIva"><?php echo $movimientos2Ddetalle->importeIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
		<th><span id="elh_movimientos2Ddetalle_importeNeto" class="movimientos2Ddetalle_importeNeto"><?php echo $movimientos2Ddetalle->importeNeto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
		<th><span id="elh_movimientos2Ddetalle_importePesos" class="movimientos2Ddetalle_importePesos"><?php echo $movimientos2Ddetalle->importePesos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
		<th><span id="elh_movimientos2Ddetalle_estadoImportacion" class="movimientos2Ddetalle_estadoImportacion"><?php echo $movimientos2Ddetalle->estadoImportacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
		<th><span id="elh_movimientos2Ddetalle_origenImportacion" class="movimientos2Ddetalle_origenImportacion"><?php echo $movimientos2Ddetalle->origenImportacion->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$movimientos2Ddetalle_delete->RecCnt = 0;
$i = 0;
while (!$movimientos2Ddetalle_delete->Recordset->EOF) {
	$movimientos2Ddetalle_delete->RecCnt++;
	$movimientos2Ddetalle_delete->RowCnt++;

	// Set row properties
	$movimientos2Ddetalle->ResetAttrs();
	$movimientos2Ddetalle->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$movimientos2Ddetalle_delete->LoadRowValues($movimientos2Ddetalle_delete->Recordset);

	// Render row
	$movimientos2Ddetalle_delete->RenderRow();
?>
	<tr<?php echo $movimientos2Ddetalle->RowAttributes() ?>>
<?php if ($movimientos2Ddetalle->id->Visible) { // id ?>
		<td<?php echo $movimientos2Ddetalle->id->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_id" class="movimientos2Ddetalle_id">
<span<?php echo $movimientos2Ddetalle->id->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->idMovimientos->Visible) { // idMovimientos ?>
		<td<?php echo $movimientos2Ddetalle->idMovimientos->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_idMovimientos" class="movimientos2Ddetalle_idMovimientos">
<span<?php echo $movimientos2Ddetalle->idMovimientos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idMovimientos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->cant->Visible) { // cant ?>
		<td<?php echo $movimientos2Ddetalle->cant->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_cant" class="movimientos2Ddetalle_cant">
<span<?php echo $movimientos2Ddetalle->cant->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->cant->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td<?php echo $movimientos2Ddetalle->idUnidadMedida->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_idUnidadMedida" class="movimientos2Ddetalle_idUnidadMedida">
<span<?php echo $movimientos2Ddetalle->idUnidadMedida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->idUnidadMedida->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->codProducto->Visible) { // codProducto ?>
		<td<?php echo $movimientos2Ddetalle->codProducto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_codProducto" class="movimientos2Ddetalle_codProducto">
<span<?php echo $movimientos2Ddetalle->codProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->codProducto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->medida->Visible) { // medida ?>
		<td<?php echo $movimientos2Ddetalle->medida->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_medida" class="movimientos2Ddetalle_medida">
<span<?php echo $movimientos2Ddetalle->medida->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->medida->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->nombreProducto->Visible) { // nombreProducto ?>
		<td<?php echo $movimientos2Ddetalle->nombreProducto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_nombreProducto" class="movimientos2Ddetalle_nombreProducto">
<span<?php echo $movimientos2Ddetalle->nombreProducto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->nombreProducto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeUnitario->Visible) { // importeUnitario ?>
		<td<?php echo $movimientos2Ddetalle->importeUnitario->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_importeUnitario" class="movimientos2Ddetalle_importeUnitario">
<span<?php echo $movimientos2Ddetalle->importeUnitario->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeUnitario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->bonificacion->Visible) { // bonificacion ?>
		<td<?php echo $movimientos2Ddetalle->bonificacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_bonificacion" class="movimientos2Ddetalle_bonificacion">
<span<?php echo $movimientos2Ddetalle->bonificacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->bonificacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeTotal->Visible) { // importeTotal ?>
		<td<?php echo $movimientos2Ddetalle->importeTotal->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_importeTotal" class="movimientos2Ddetalle_importeTotal">
<span<?php echo $movimientos2Ddetalle->importeTotal->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeTotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->alicuotaIva->Visible) { // alicuotaIva ?>
		<td<?php echo $movimientos2Ddetalle->alicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_alicuotaIva" class="movimientos2Ddetalle_alicuotaIva">
<span<?php echo $movimientos2Ddetalle->alicuotaIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->alicuotaIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeIva->Visible) { // importeIva ?>
		<td<?php echo $movimientos2Ddetalle->importeIva->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_importeIva" class="movimientos2Ddetalle_importeIva">
<span<?php echo $movimientos2Ddetalle->importeIva->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->importeNeto->Visible) { // importeNeto ?>
		<td<?php echo $movimientos2Ddetalle->importeNeto->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_importeNeto" class="movimientos2Ddetalle_importeNeto">
<span<?php echo $movimientos2Ddetalle->importeNeto->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importeNeto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->importePesos->Visible) { // importePesos ?>
		<td<?php echo $movimientos2Ddetalle->importePesos->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_importePesos" class="movimientos2Ddetalle_importePesos">
<span<?php echo $movimientos2Ddetalle->importePesos->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->importePesos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->estadoImportacion->Visible) { // estadoImportacion ?>
		<td<?php echo $movimientos2Ddetalle->estadoImportacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_estadoImportacion" class="movimientos2Ddetalle_estadoImportacion">
<span<?php echo $movimientos2Ddetalle->estadoImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->estadoImportacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($movimientos2Ddetalle->origenImportacion->Visible) { // origenImportacion ?>
		<td<?php echo $movimientos2Ddetalle->origenImportacion->CellAttributes() ?>>
<span id="el<?php echo $movimientos2Ddetalle_delete->RowCnt ?>_movimientos2Ddetalle_origenImportacion" class="movimientos2Ddetalle_origenImportacion">
<span<?php echo $movimientos2Ddetalle->origenImportacion->ViewAttributes() ?>>
<?php echo $movimientos2Ddetalle->origenImportacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$movimientos2Ddetalle_delete->Recordset->MoveNext();
}
$movimientos2Ddetalle_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $movimientos2Ddetalle_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fmovimientos2Ddetalledelete.Init();
</script>
<?php
$movimientos2Ddetalle_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$movimientos2Ddetalle_delete->Page_Terminate();
?>
