<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "articulos2Dstockinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "articulosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$articulos2Dstock_add = NULL; // Initialize page object first

class carticulos2Dstock_add extends carticulos2Dstock {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos-stock';

	// Page object name
	var $PageObjName = 'articulos2Dstock_add';

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

		// Table object (articulos2Dstock)
		if (!isset($GLOBALS["articulos2Dstock"]) || get_class($GLOBALS["articulos2Dstock"]) == "carticulos2Dstock") {
			$GLOBALS["articulos2Dstock"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos2Dstock"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (articulos)
		if (!isset($GLOBALS['articulos'])) $GLOBALS['articulos'] = new carticulos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'articulos-stock', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("articulos2Dstocklist.php"));
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
		$this->idUnidadMedida->SetVisibility();
		$this->indiceConversion->SetVisibility();
		$this->stock->SetVisibility();
		$this->stockReservadoVenta->SetVisibility();
		$this->stockReservadoCompra->SetVisibility();
		$this->stockMinimo->SetVisibility();

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
		global $EW_EXPORT, $articulos2Dstock;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($articulos2Dstock);
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
					$this->Page_Terminate("articulos2Dstocklist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "articulos2Dstocklist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "articulos2Dstockview.php")
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
		$this->idArticulo->CurrentValue = 0;
		$this->idUnidadMedida->CurrentValue = 0;
		$this->indiceConversion->CurrentValue = 0;
		$this->stock->CurrentValue = 0;
		$this->stockReservadoVenta->CurrentValue = 0;
		$this->stockReservadoCompra->CurrentValue = 0;
		$this->stockMinimo->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idArticulo->FldIsDetailKey) {
			$this->idArticulo->setFormValue($objForm->GetValue("x_idArticulo"));
		}
		if (!$this->idUnidadMedida->FldIsDetailKey) {
			$this->idUnidadMedida->setFormValue($objForm->GetValue("x_idUnidadMedida"));
		}
		if (!$this->indiceConversion->FldIsDetailKey) {
			$this->indiceConversion->setFormValue($objForm->GetValue("x_indiceConversion"));
		}
		if (!$this->stock->FldIsDetailKey) {
			$this->stock->setFormValue($objForm->GetValue("x_stock"));
		}
		if (!$this->stockReservadoVenta->FldIsDetailKey) {
			$this->stockReservadoVenta->setFormValue($objForm->GetValue("x_stockReservadoVenta"));
		}
		if (!$this->stockReservadoCompra->FldIsDetailKey) {
			$this->stockReservadoCompra->setFormValue($objForm->GetValue("x_stockReservadoCompra"));
		}
		if (!$this->stockMinimo->FldIsDetailKey) {
			$this->stockMinimo->setFormValue($objForm->GetValue("x_stockMinimo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idArticulo->CurrentValue = $this->idArticulo->FormValue;
		$this->idUnidadMedida->CurrentValue = $this->idUnidadMedida->FormValue;
		$this->indiceConversion->CurrentValue = $this->indiceConversion->FormValue;
		$this->stock->CurrentValue = $this->stock->FormValue;
		$this->stockReservadoVenta->CurrentValue = $this->stockReservadoVenta->FormValue;
		$this->stockReservadoCompra->CurrentValue = $this->stockReservadoCompra->FormValue;
		$this->stockMinimo->CurrentValue = $this->stockMinimo->FormValue;
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
		$this->idUnidadMedida->setDbValue($rs->fields('idUnidadMedida'));
		$this->indiceConversion->setDbValue($rs->fields('indiceConversion'));
		$this->stock->setDbValue($rs->fields('stock'));
		$this->stockReservadoVenta->setDbValue($rs->fields('stockReservadoVenta'));
		$this->stockReservadoCompra->setDbValue($rs->fields('stockReservadoCompra'));
		$this->stockMinimo->setDbValue($rs->fields('stockMinimo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idArticulo->DbValue = $row['idArticulo'];
		$this->idUnidadMedida->DbValue = $row['idUnidadMedida'];
		$this->indiceConversion->DbValue = $row['indiceConversion'];
		$this->stock->DbValue = $row['stock'];
		$this->stockReservadoVenta->DbValue = $row['stockReservadoVenta'];
		$this->stockReservadoCompra->DbValue = $row['stockReservadoCompra'];
		$this->stockMinimo->DbValue = $row['stockMinimo'];
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

		if ($this->indiceConversion->FormValue == $this->indiceConversion->CurrentValue && is_numeric(ew_StrToFloat($this->indiceConversion->CurrentValue)))
			$this->indiceConversion->CurrentValue = ew_StrToFloat($this->indiceConversion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->stock->FormValue == $this->stock->CurrentValue && is_numeric(ew_StrToFloat($this->stock->CurrentValue)))
			$this->stock->CurrentValue = ew_StrToFloat($this->stock->CurrentValue);

		// Convert decimal values if posted back
		if ($this->stockReservadoVenta->FormValue == $this->stockReservadoVenta->CurrentValue && is_numeric(ew_StrToFloat($this->stockReservadoVenta->CurrentValue)))
			$this->stockReservadoVenta->CurrentValue = ew_StrToFloat($this->stockReservadoVenta->CurrentValue);

		// Convert decimal values if posted back
		if ($this->stockReservadoCompra->FormValue == $this->stockReservadoCompra->CurrentValue && is_numeric(ew_StrToFloat($this->stockReservadoCompra->CurrentValue)))
			$this->stockReservadoCompra->CurrentValue = ew_StrToFloat($this->stockReservadoCompra->CurrentValue);

		// Convert decimal values if posted back
		if ($this->stockMinimo->FormValue == $this->stockMinimo->CurrentValue && is_numeric(ew_StrToFloat($this->stockMinimo->CurrentValue)))
			$this->stockMinimo->CurrentValue = ew_StrToFloat($this->stockMinimo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idArticulo
		// idUnidadMedida
		// indiceConversion
		// stock
		// stockReservadoVenta
		// stockReservadoCompra
		// stockMinimo

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
		$sSqlWrk .= " ORDER BY `denominacionExterna` ASC";
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

		// idUnidadMedida
		if (strval($this->idUnidadMedida->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idUnidadMedida->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidades-medida`";
		$sWhereWrk = "";
		$this->idUnidadMedida->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idUnidadMedida, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idUnidadMedida->ViewValue = $this->idUnidadMedida->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idUnidadMedida->ViewValue = $this->idUnidadMedida->CurrentValue;
			}
		} else {
			$this->idUnidadMedida->ViewValue = NULL;
		}
		$this->idUnidadMedida->ViewCustomAttributes = "";

		// indiceConversion
		$this->indiceConversion->ViewValue = $this->indiceConversion->CurrentValue;
		$this->indiceConversion->ViewCustomAttributes = "";

		// stock
		$this->stock->ViewValue = $this->stock->CurrentValue;
		$this->stock->ViewCustomAttributes = "";

		// stockReservadoVenta
		$this->stockReservadoVenta->ViewValue = $this->stockReservadoVenta->CurrentValue;
		$this->stockReservadoVenta->ViewCustomAttributes = "";

		// stockReservadoCompra
		$this->stockReservadoCompra->ViewValue = $this->stockReservadoCompra->CurrentValue;
		$this->stockReservadoCompra->ViewCustomAttributes = "";

		// stockMinimo
		$this->stockMinimo->ViewValue = $this->stockMinimo->CurrentValue;
		$this->stockMinimo->ViewCustomAttributes = "";

			// idArticulo
			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";
			$this->idArticulo->TooltipValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";
			$this->idUnidadMedida->TooltipValue = "";

			// indiceConversion
			$this->indiceConversion->LinkCustomAttributes = "";
			$this->indiceConversion->HrefValue = "";
			$this->indiceConversion->TooltipValue = "";

			// stock
			$this->stock->LinkCustomAttributes = "";
			$this->stock->HrefValue = "";
			$this->stock->TooltipValue = "";

			// stockReservadoVenta
			$this->stockReservadoVenta->LinkCustomAttributes = "";
			$this->stockReservadoVenta->HrefValue = "";
			$this->stockReservadoVenta->TooltipValue = "";

			// stockReservadoCompra
			$this->stockReservadoCompra->LinkCustomAttributes = "";
			$this->stockReservadoCompra->HrefValue = "";
			$this->stockReservadoCompra->TooltipValue = "";

			// stockMinimo
			$this->stockMinimo->LinkCustomAttributes = "";
			$this->stockMinimo->HrefValue = "";
			$this->stockMinimo->TooltipValue = "";
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
			$sSqlWrk .= " ORDER BY `denominacionExterna` ASC";
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
			$sSqlWrk .= " ORDER BY `denominacionExterna` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idArticulo->EditValue = $arwrk;
			}

			// idUnidadMedida
			$this->idUnidadMedida->EditAttrs["class"] = "form-control";
			$this->idUnidadMedida->EditCustomAttributes = "";
			if (trim(strval($this->idUnidadMedida->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idUnidadMedida->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `unidades-medida`";
			$sWhereWrk = "";
			$this->idUnidadMedida->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idUnidadMedida, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idUnidadMedida->EditValue = $arwrk;

			// indiceConversion
			$this->indiceConversion->EditAttrs["class"] = "form-control";
			$this->indiceConversion->EditCustomAttributes = "";
			$this->indiceConversion->EditValue = ew_HtmlEncode($this->indiceConversion->CurrentValue);
			$this->indiceConversion->PlaceHolder = ew_RemoveHtml($this->indiceConversion->FldCaption());
			if (strval($this->indiceConversion->EditValue) <> "" && is_numeric($this->indiceConversion->EditValue)) $this->indiceConversion->EditValue = ew_FormatNumber($this->indiceConversion->EditValue, -2, -1, -2, 0);

			// stock
			$this->stock->EditAttrs["class"] = "form-control";
			$this->stock->EditCustomAttributes = "";
			$this->stock->EditValue = ew_HtmlEncode($this->stock->CurrentValue);
			$this->stock->PlaceHolder = ew_RemoveHtml($this->stock->FldCaption());
			if (strval($this->stock->EditValue) <> "" && is_numeric($this->stock->EditValue)) $this->stock->EditValue = ew_FormatNumber($this->stock->EditValue, -2, -1, -2, 0);

			// stockReservadoVenta
			$this->stockReservadoVenta->EditAttrs["class"] = "form-control";
			$this->stockReservadoVenta->EditCustomAttributes = "";
			$this->stockReservadoVenta->EditValue = ew_HtmlEncode($this->stockReservadoVenta->CurrentValue);
			$this->stockReservadoVenta->PlaceHolder = ew_RemoveHtml($this->stockReservadoVenta->FldCaption());
			if (strval($this->stockReservadoVenta->EditValue) <> "" && is_numeric($this->stockReservadoVenta->EditValue)) $this->stockReservadoVenta->EditValue = ew_FormatNumber($this->stockReservadoVenta->EditValue, -2, -1, -2, 0);

			// stockReservadoCompra
			$this->stockReservadoCompra->EditAttrs["class"] = "form-control";
			$this->stockReservadoCompra->EditCustomAttributes = "";
			$this->stockReservadoCompra->EditValue = ew_HtmlEncode($this->stockReservadoCompra->CurrentValue);
			$this->stockReservadoCompra->PlaceHolder = ew_RemoveHtml($this->stockReservadoCompra->FldCaption());
			if (strval($this->stockReservadoCompra->EditValue) <> "" && is_numeric($this->stockReservadoCompra->EditValue)) $this->stockReservadoCompra->EditValue = ew_FormatNumber($this->stockReservadoCompra->EditValue, -2, -1, -2, 0);

			// stockMinimo
			$this->stockMinimo->EditAttrs["class"] = "form-control";
			$this->stockMinimo->EditCustomAttributes = "";
			$this->stockMinimo->EditValue = ew_HtmlEncode($this->stockMinimo->CurrentValue);
			$this->stockMinimo->PlaceHolder = ew_RemoveHtml($this->stockMinimo->FldCaption());
			if (strval($this->stockMinimo->EditValue) <> "" && is_numeric($this->stockMinimo->EditValue)) $this->stockMinimo->EditValue = ew_FormatNumber($this->stockMinimo->EditValue, -2, -1, -2, 0);

			// Add refer script
			// idArticulo

			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";

			// idUnidadMedida
			$this->idUnidadMedida->LinkCustomAttributes = "";
			$this->idUnidadMedida->HrefValue = "";

			// indiceConversion
			$this->indiceConversion->LinkCustomAttributes = "";
			$this->indiceConversion->HrefValue = "";

			// stock
			$this->stock->LinkCustomAttributes = "";
			$this->stock->HrefValue = "";

			// stockReservadoVenta
			$this->stockReservadoVenta->LinkCustomAttributes = "";
			$this->stockReservadoVenta->HrefValue = "";

			// stockReservadoCompra
			$this->stockReservadoCompra->LinkCustomAttributes = "";
			$this->stockReservadoCompra->HrefValue = "";

			// stockMinimo
			$this->stockMinimo->LinkCustomAttributes = "";
			$this->stockMinimo->HrefValue = "";
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
		if (!ew_CheckNumber($this->indiceConversion->FormValue)) {
			ew_AddMessage($gsFormError, $this->indiceConversion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->stock->FormValue)) {
			ew_AddMessage($gsFormError, $this->stock->FldErrMsg());
		}
		if (!ew_CheckNumber($this->stockReservadoVenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->stockReservadoVenta->FldErrMsg());
		}
		if (!ew_CheckNumber($this->stockReservadoCompra->FormValue)) {
			ew_AddMessage($gsFormError, $this->stockReservadoCompra->FldErrMsg());
		}
		if (!ew_CheckNumber($this->stockMinimo->FormValue)) {
			ew_AddMessage($gsFormError, $this->stockMinimo->FldErrMsg());
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

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idArticulo
		$this->idArticulo->SetDbValueDef($rsnew, $this->idArticulo->CurrentValue, NULL, strval($this->idArticulo->CurrentValue) == "");

		// idUnidadMedida
		$this->idUnidadMedida->SetDbValueDef($rsnew, $this->idUnidadMedida->CurrentValue, NULL, strval($this->idUnidadMedida->CurrentValue) == "");

		// indiceConversion
		$this->indiceConversion->SetDbValueDef($rsnew, $this->indiceConversion->CurrentValue, NULL, strval($this->indiceConversion->CurrentValue) == "");

		// stock
		$this->stock->SetDbValueDef($rsnew, $this->stock->CurrentValue, NULL, strval($this->stock->CurrentValue) == "");

		// stockReservadoVenta
		$this->stockReservadoVenta->SetDbValueDef($rsnew, $this->stockReservadoVenta->CurrentValue, NULL, strval($this->stockReservadoVenta->CurrentValue) == "");

		// stockReservadoCompra
		$this->stockReservadoCompra->SetDbValueDef($rsnew, $this->stockReservadoCompra->CurrentValue, NULL, strval($this->stockReservadoCompra->CurrentValue) == "");

		// stockMinimo
		$this->stockMinimo->SetDbValueDef($rsnew, $this->stockMinimo->CurrentValue, NULL, strval($this->stockMinimo->CurrentValue) == "");

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
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("articulos2Dstocklist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk .= " ORDER BY `denominacionExterna` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idUnidadMedida":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `unidades-medida`";
			$sWhereWrk = "";
			$this->idUnidadMedida->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idUnidadMedida, $sWhereWrk); // Call Lookup selecting
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
if (!isset($articulos2Dstock_add)) $articulos2Dstock_add = new carticulos2Dstock_add();

// Page init
$articulos2Dstock_add->Page_Init();

// Page main
$articulos2Dstock_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dstock_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = farticulos2Dstockadd = new ew_Form("farticulos2Dstockadd", "add");

// Validate form
farticulos2Dstockadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_indiceConversion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->indiceConversion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stock");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stock->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockReservadoVenta");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockReservadoVenta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockReservadoCompra");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockReservadoCompra->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockMinimo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockMinimo->FldErrMsg()) ?>");

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
farticulos2Dstockadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dstockadd.ValidateRequired = true;
<?php } else { ?>
farticulos2Dstockadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dstockadd.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dstockadd.Lists["x_idUnidadMedida"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidades2Dmedida"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$articulos2Dstock_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $articulos2Dstock_add->ShowPageHeader(); ?>
<?php
$articulos2Dstock_add->ShowMessage();
?>
<form name="farticulos2Dstockadd" id="farticulos2Dstockadd" class="<?php echo $articulos2Dstock_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos2Dstock_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos2Dstock_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos2Dstock">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($articulos2Dstock_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($articulos2Dstock->getCurrentMasterTable() == "articulos") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="articulos">
<input type="hidden" name="fk_id" value="<?php echo $articulos2Dstock->idArticulo->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
	<div id="r_idArticulo" class="form-group">
		<label id="elh_articulos2Dstock_idArticulo" for="x_idArticulo" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->idArticulo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->idArticulo->CellAttributes() ?>>
<?php if ($articulos2Dstock->idArticulo->getSessionValue() <> "") { ?>
<span id="el_articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idArticulo" name="x_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el_articulos2Dstock_idArticulo">
<select data-table="articulos2Dstock" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dstock->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x_idArticulo" name="x_idArticulo"<?php echo $articulos2Dstock->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->SelectOptionListHtml("x_idArticulo") ?>
</select>
<input type="hidden" name="s_x_idArticulo" id="s_x_idArticulo" value="<?php echo $articulos2Dstock->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $articulos2Dstock->idArticulo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<div id="r_idUnidadMedida" class="form-group">
		<label id="elh_articulos2Dstock_idUnidadMedida" for="x_idUnidadMedida" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->idUnidadMedida->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->idUnidadMedida->CellAttributes() ?>>
<span id="el_articulos2Dstock_idUnidadMedida">
<select data-table="articulos2Dstock" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dstock->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x_idUnidadMedida" name="x_idUnidadMedida"<?php echo $articulos2Dstock->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->SelectOptionListHtml("x_idUnidadMedida") ?>
</select>
<input type="hidden" name="s_x_idUnidadMedida" id="s_x_idUnidadMedida" value="<?php echo $articulos2Dstock->idUnidadMedida->LookupFilterQuery() ?>">
</span>
<?php echo $articulos2Dstock->idUnidadMedida->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
	<div id="r_indiceConversion" class="form-group">
		<label id="elh_articulos2Dstock_indiceConversion" for="x_indiceConversion" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->indiceConversion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->indiceConversion->CellAttributes() ?>>
<span id="el_articulos2Dstock_indiceConversion">
<input type="text" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x_indiceConversion" id="x_indiceConversion" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->indiceConversion->EditValue ?>"<?php echo $articulos2Dstock->indiceConversion->EditAttributes() ?>>
</span>
<?php echo $articulos2Dstock->indiceConversion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
	<div id="r_stock" class="form-group">
		<label id="elh_articulos2Dstock_stock" for="x_stock" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->stock->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->stock->CellAttributes() ?>>
<span id="el_articulos2Dstock_stock">
<input type="text" data-table="articulos2Dstock" data-field="x_stock" name="x_stock" id="x_stock" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stock->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stock->EditValue ?>"<?php echo $articulos2Dstock->stock->EditAttributes() ?>>
</span>
<?php echo $articulos2Dstock->stock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
	<div id="r_stockReservadoVenta" class="form-group">
		<label id="elh_articulos2Dstock_stockReservadoVenta" for="x_stockReservadoVenta" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->stockReservadoVenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->stockReservadoVenta->CellAttributes() ?>>
<span id="el_articulos2Dstock_stockReservadoVenta">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x_stockReservadoVenta" id="x_stockReservadoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoVenta->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoVenta->EditAttributes() ?>>
</span>
<?php echo $articulos2Dstock->stockReservadoVenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
	<div id="r_stockReservadoCompra" class="form-group">
		<label id="elh_articulos2Dstock_stockReservadoCompra" for="x_stockReservadoCompra" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->stockReservadoCompra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->stockReservadoCompra->CellAttributes() ?>>
<span id="el_articulos2Dstock_stockReservadoCompra">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x_stockReservadoCompra" id="x_stockReservadoCompra" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoCompra->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoCompra->EditAttributes() ?>>
</span>
<?php echo $articulos2Dstock->stockReservadoCompra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
	<div id="r_stockMinimo" class="form-group">
		<label id="elh_articulos2Dstock_stockMinimo" for="x_stockMinimo" class="col-sm-2 control-label ewLabel"><?php echo $articulos2Dstock->stockMinimo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $articulos2Dstock->stockMinimo->CellAttributes() ?>>
<span id="el_articulos2Dstock_stockMinimo">
<input type="text" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x_stockMinimo" id="x_stockMinimo" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockMinimo->EditValue ?>"<?php echo $articulos2Dstock->stockMinimo->EditAttributes() ?>>
</span>
<?php echo $articulos2Dstock->stockMinimo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$articulos2Dstock_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos2Dstock_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
farticulos2Dstockadd.Init();
</script>
<?php
$articulos2Dstock_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$("#x_idUnidadMedida").change(function(){
	var id = $("#x_idUnidadMedida").val();
	var idArticulo = $("#x_idArticulo").val();
	var accion = "obtener-stock-unidad-medida";
	$("#x_idUnidadMedida").attr("disabled", "disabled");
	if(id){
		$.ajax({
			type: "GET",
			crossDomain: true,
			dataType: "json",
			url : "api.php",
			data: {
				id: id,
				idArticulo: idArticulo,
				accion: accion
			},
			success: function(data){
				console.log(data)
				if(!data.error){
					$("#x_indiceConversion").val(data.exito.unidadMedida.indiceConversion);
					$("#x_stock").val(data.exito.stock.stock);
					$("#x_stockReservadoVenta").val(data.exito.stock.stockReservadoVenta);
					$("#x_stockReservadoCompra").val(data.exito.stock.stockReservadoCompra);
					$("#x_stockMinimo").val(data.exito.stock.stockMinimo);
				}
				$("#x_idUnidadMedida").removeAttr("disabled");
			},
			error: function(xhr, status, error) {
				console.log(xhr.responseText);
				$("#x_indiceConversion").val(0);
				$("#x_idUnidadMedida").removeAttr("disabled");
			}
		});
	}else{
		$("#x_indiceConversion").val(0);
		$("#x_idUnidadMedida").removeAttr("disabled");
	}
})
</script>
<?php include_once "footer.php" ?>
<?php
$articulos2Dstock_add->Page_Terminate();
?>
