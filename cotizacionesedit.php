<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "cotizacionesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cotizaciones_edit = NULL; // Initialize page object first

class ccotizaciones_edit extends ccotizaciones {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'cotizaciones';

	// Page object name
	var $PageObjName = 'cotizaciones_edit';

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

		// Table object (cotizaciones)
		if (!isset($GLOBALS["cotizaciones"]) || get_class($GLOBALS["cotizaciones"]) == "ccotizaciones") {
			$GLOBALS["cotizaciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cotizaciones"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cotizaciones', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cotizacioneslist.php"));
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
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->idTercero->SetVisibility();
		$this->fecha->SetVisibility();
		$this->contable->SetVisibility();
		$this->importeNeto->SetVisibility();
		$this->importeIva->SetVisibility();
		$this->importeTotal->SetVisibility();
		$this->estado->SetVisibility();
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
		global $EW_EXPORT, $cotizaciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cotizaciones);
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
			$this->Page_Terminate("cotizacioneslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("cotizacioneslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "cotizacioneslist.php")
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
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->idTercero->FldIsDetailKey) {
			$this->idTercero->setFormValue($objForm->GetValue("x_idTercero"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		}
		if (!$this->contable->FldIsDetailKey) {
			$this->contable->setFormValue($objForm->GetValue("x_contable"));
		}
		if (!$this->importeNeto->FldIsDetailKey) {
			$this->importeNeto->setFormValue($objForm->GetValue("x_importeNeto"));
		}
		if (!$this->importeIva->FldIsDetailKey) {
			$this->importeIva->setFormValue($objForm->GetValue("x_importeIva"));
		}
		if (!$this->importeTotal->FldIsDetailKey) {
			$this->importeTotal->setFormValue($objForm->GetValue("x_importeTotal"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->vigencia->FldIsDetailKey) {
			$this->vigencia->setFormValue($objForm->GetValue("x_vigencia"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idTercero->CurrentValue = $this->idTercero->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 0);
		$this->contable->CurrentValue = $this->contable->FormValue;
		$this->importeNeto->CurrentValue = $this->importeNeto->FormValue;
		$this->importeIva->CurrentValue = $this->importeIva->FormValue;
		$this->importeTotal->CurrentValue = $this->importeTotal->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->vigencia->CurrentValue = $this->vigencia->FormValue;
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
		$this->idTercero->setDbValue($rs->fields('idTercero'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->contable->setDbValue($rs->fields('contable'));
		$this->importeNeto->setDbValue($rs->fields('importeNeto'));
		$this->importeIva->setDbValue($rs->fields('importeIva'));
		$this->importeTotal->setDbValue($rs->fields('importeTotal'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->vigencia->setDbValue($rs->fields('vigencia'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idTercero->DbValue = $row['idTercero'];
		$this->fecha->DbValue = $row['fecha'];
		$this->contable->DbValue = $row['contable'];
		$this->importeNeto->DbValue = $row['importeNeto'];
		$this->importeIva->DbValue = $row['importeIva'];
		$this->importeTotal->DbValue = $row['importeTotal'];
		$this->estado->DbValue = $row['estado'];
		$this->vigencia->DbValue = $row['vigencia'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->importeNeto->FormValue == $this->importeNeto->CurrentValue && is_numeric(ew_StrToFloat($this->importeNeto->CurrentValue)))
			$this->importeNeto->CurrentValue = ew_StrToFloat($this->importeNeto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeIva->FormValue == $this->importeIva->CurrentValue && is_numeric(ew_StrToFloat($this->importeIva->CurrentValue)))
			$this->importeIva->CurrentValue = ew_StrToFloat($this->importeIva->CurrentValue);

		// Convert decimal values if posted back
		if ($this->importeTotal->FormValue == $this->importeTotal->CurrentValue && is_numeric(ew_StrToFloat($this->importeTotal->CurrentValue)))
			$this->importeTotal->CurrentValue = ew_StrToFloat($this->importeTotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idTercero
		// fecha
		// contable
		// importeNeto
		// importeIva
		// importeTotal
		// estado
		// vigencia

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

		// contable
		if (strval($this->contable->CurrentValue) <> "") {
			$this->contable->ViewValue = "";
			$arwrk = explode(",", strval($this->contable->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->contable->ViewValue .= $this->contable->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->contable->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->contable->ViewValue = NULL;
		}
		$this->contable->ViewCustomAttributes = "";

		// importeNeto
		$this->importeNeto->ViewValue = $this->importeNeto->CurrentValue;
		$this->importeNeto->ViewCustomAttributes = "";

		// importeIva
		$this->importeIva->ViewValue = $this->importeIva->CurrentValue;
		$this->importeIva->ViewCustomAttributes = "";

		// importeTotal
		$this->importeTotal->ViewValue = $this->importeTotal->CurrentValue;
		$this->importeTotal->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// vigencia
		$this->vigencia->ViewValue = $this->vigencia->CurrentValue;
		$this->vigencia->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";
			$this->idTercero->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";
			$this->contable->TooltipValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";
			$this->importeNeto->TooltipValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";
			$this->importeIva->TooltipValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";
			$this->importeTotal->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// vigencia
			$this->vigencia->LinkCustomAttributes = "";
			$this->vigencia->HrefValue = "";
			$this->vigencia->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

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

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// contable
			$this->contable->EditCustomAttributes = "";
			$this->contable->EditValue = $this->contable->Options(FALSE);

			// importeNeto
			$this->importeNeto->EditAttrs["class"] = "form-control";
			$this->importeNeto->EditCustomAttributes = "";
			$this->importeNeto->EditValue = ew_HtmlEncode($this->importeNeto->CurrentValue);
			$this->importeNeto->PlaceHolder = ew_RemoveHtml($this->importeNeto->FldCaption());
			if (strval($this->importeNeto->EditValue) <> "" && is_numeric($this->importeNeto->EditValue)) $this->importeNeto->EditValue = ew_FormatNumber($this->importeNeto->EditValue, -2, -1, -2, 0);

			// importeIva
			$this->importeIva->EditAttrs["class"] = "form-control";
			$this->importeIva->EditCustomAttributes = "";
			$this->importeIva->EditValue = ew_HtmlEncode($this->importeIva->CurrentValue);
			$this->importeIva->PlaceHolder = ew_RemoveHtml($this->importeIva->FldCaption());
			if (strval($this->importeIva->EditValue) <> "" && is_numeric($this->importeIva->EditValue)) $this->importeIva->EditValue = ew_FormatNumber($this->importeIva->EditValue, -2, -1, -2, 0);

			// importeTotal
			$this->importeTotal->EditAttrs["class"] = "form-control";
			$this->importeTotal->EditCustomAttributes = "";
			$this->importeTotal->EditValue = ew_HtmlEncode($this->importeTotal->CurrentValue);
			$this->importeTotal->PlaceHolder = ew_RemoveHtml($this->importeTotal->FldCaption());
			if (strval($this->importeTotal->EditValue) <> "" && is_numeric($this->importeTotal->EditValue)) $this->importeTotal->EditValue = ew_FormatNumber($this->importeTotal->EditValue, -2, -1, -2, 0);

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = ew_HtmlEncode($this->estado->CurrentValue);
			$this->estado->PlaceHolder = ew_RemoveHtml($this->estado->FldCaption());

			// vigencia
			$this->vigencia->EditAttrs["class"] = "form-control";
			$this->vigencia->EditCustomAttributes = "";
			$this->vigencia->EditValue = ew_HtmlEncode($this->vigencia->CurrentValue);
			$this->vigencia->PlaceHolder = ew_RemoveHtml($this->vigencia->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// idTercero
			$this->idTercero->LinkCustomAttributes = "";
			$this->idTercero->HrefValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";

			// contable
			$this->contable->LinkCustomAttributes = "";
			$this->contable->HrefValue = "";

			// importeNeto
			$this->importeNeto->LinkCustomAttributes = "";
			$this->importeNeto->HrefValue = "";

			// importeIva
			$this->importeIva->LinkCustomAttributes = "";
			$this->importeIva->HrefValue = "";

			// importeTotal
			$this->importeTotal->LinkCustomAttributes = "";
			$this->importeTotal->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";

			// vigencia
			$this->vigencia->LinkCustomAttributes = "";
			$this->vigencia->HrefValue = "";
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
		if (!ew_CheckNumber($this->importeNeto->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeNeto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeIva->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeIva->FldErrMsg());
		}
		if (!ew_CheckNumber($this->importeTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->importeTotal->FldErrMsg());
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->estado->FormValue)) {
			ew_AddMessage($gsFormError, $this->estado->FldErrMsg());
		}
		if (!ew_CheckInteger($this->vigencia->FormValue)) {
			ew_AddMessage($gsFormError, $this->vigencia->FldErrMsg());
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

			// idTercero
			$this->idTercero->SetDbValueDef($rsnew, $this->idTercero->CurrentValue, NULL, $this->idTercero->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 0), NULL, $this->fecha->ReadOnly);

			// contable
			$this->contable->SetDbValueDef($rsnew, $this->contable->CurrentValue, NULL, $this->contable->ReadOnly);

			// importeNeto
			$this->importeNeto->SetDbValueDef($rsnew, $this->importeNeto->CurrentValue, NULL, $this->importeNeto->ReadOnly);

			// importeIva
			$this->importeIva->SetDbValueDef($rsnew, $this->importeIva->CurrentValue, NULL, $this->importeIva->ReadOnly);

			// importeTotal
			$this->importeTotal->SetDbValueDef($rsnew, $this->importeTotal->CurrentValue, NULL, $this->importeTotal->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, 0, $this->estado->ReadOnly);

			// vigencia
			$this->vigencia->SetDbValueDef($rsnew, $this->vigencia->CurrentValue, NULL, $this->vigencia->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cotizacioneslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cotizaciones_edit)) $cotizaciones_edit = new ccotizaciones_edit();

// Page init
$cotizaciones_edit->Page_Init();

// Page main
$cotizaciones_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cotizaciones_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcotizacionesedit = new ew_Form("fcotizacionesedit", "edit");

// Validate form
fcotizacionesedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeNeto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->importeNeto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeIva");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->importeIva->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_importeTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->importeTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cotizaciones->estado->FldCaption(), $cotizaciones->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->estado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vigencia");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cotizaciones->vigencia->FldErrMsg()) ?>");

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
fcotizacionesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcotizacionesedit.ValidateRequired = true;
<?php } else { ?>
fcotizacionesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcotizacionesedit.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fcotizacionesedit.Lists["x_contable[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcotizacionesedit.Lists["x_contable[]"].Options = <?php echo json_encode($cotizaciones->contable->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cotizaciones_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cotizaciones_edit->ShowPageHeader(); ?>
<?php
$cotizaciones_edit->ShowMessage();
?>
<form name="fcotizacionesedit" id="fcotizacionesedit" class="<?php echo $cotizaciones_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cotizaciones_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cotizaciones_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cotizaciones">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($cotizaciones_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cotizaciones->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_cotizaciones_id" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->id->CellAttributes() ?>>
<span id="el_cotizaciones_id">
<span<?php echo $cotizaciones->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cotizaciones->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="cotizaciones" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($cotizaciones->id->CurrentValue) ?>">
<?php echo $cotizaciones->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->idTercero->Visible) { // idTercero ?>
	<div id="r_idTercero" class="form-group">
		<label id="elh_cotizaciones_idTercero" for="x_idTercero" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->idTercero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->idTercero->CellAttributes() ?>>
<span id="el_cotizaciones_idTercero">
<select data-table="cotizaciones" data-field="x_idTercero" data-value-separator="<?php echo $cotizaciones->idTercero->DisplayValueSeparatorAttribute() ?>" id="x_idTercero" name="x_idTercero"<?php echo $cotizaciones->idTercero->EditAttributes() ?>>
<?php echo $cotizaciones->idTercero->SelectOptionListHtml("x_idTercero") ?>
</select>
<input type="hidden" name="s_x_idTercero" id="s_x_idTercero" value="<?php echo $cotizaciones->idTercero->LookupFilterQuery() ?>">
</span>
<?php echo $cotizaciones->idTercero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_cotizaciones_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->fecha->CellAttributes() ?>>
<span id="el_cotizaciones_fecha">
<input type="text" data-table="cotizaciones" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($cotizaciones->fecha->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->fecha->EditValue ?>"<?php echo $cotizaciones->fecha->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->contable->Visible) { // contable ?>
	<div id="r_contable" class="form-group">
		<label id="elh_cotizaciones_contable" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->contable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->contable->CellAttributes() ?>>
<span id="el_cotizaciones_contable">
<div id="tp_x_contable" class="ewTemplate"><input type="checkbox" data-table="cotizaciones" data-field="x_contable" data-value-separator="<?php echo $cotizaciones->contable->DisplayValueSeparatorAttribute() ?>" name="x_contable[]" id="x_contable[]" value="{value}"<?php echo $cotizaciones->contable->EditAttributes() ?>></div>
<div id="dsl_x_contable" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $cotizaciones->contable->CheckBoxListHtml(FALSE, "x_contable[]") ?>
</div></div>
</span>
<?php echo $cotizaciones->contable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->importeNeto->Visible) { // importeNeto ?>
	<div id="r_importeNeto" class="form-group">
		<label id="elh_cotizaciones_importeNeto" for="x_importeNeto" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->importeNeto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->importeNeto->CellAttributes() ?>>
<span id="el_cotizaciones_importeNeto">
<input type="text" data-table="cotizaciones" data-field="x_importeNeto" name="x_importeNeto" id="x_importeNeto" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones->importeNeto->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->importeNeto->EditValue ?>"<?php echo $cotizaciones->importeNeto->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->importeNeto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->importeIva->Visible) { // importeIva ?>
	<div id="r_importeIva" class="form-group">
		<label id="elh_cotizaciones_importeIva" for="x_importeIva" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->importeIva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->importeIva->CellAttributes() ?>>
<span id="el_cotizaciones_importeIva">
<input type="text" data-table="cotizaciones" data-field="x_importeIva" name="x_importeIva" id="x_importeIva" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones->importeIva->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->importeIva->EditValue ?>"<?php echo $cotizaciones->importeIva->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->importeIva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->importeTotal->Visible) { // importeTotal ?>
	<div id="r_importeTotal" class="form-group">
		<label id="elh_cotizaciones_importeTotal" for="x_importeTotal" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->importeTotal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->importeTotal->CellAttributes() ?>>
<span id="el_cotizaciones_importeTotal">
<input type="text" data-table="cotizaciones" data-field="x_importeTotal" name="x_importeTotal" id="x_importeTotal" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones->importeTotal->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->importeTotal->EditValue ?>"<?php echo $cotizaciones->importeTotal->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->importeTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_cotizaciones_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->estado->CellAttributes() ?>>
<span id="el_cotizaciones_estado">
<input type="text" data-table="cotizaciones" data-field="x_estado" name="x_estado" id="x_estado" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones->estado->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->estado->EditValue ?>"<?php echo $cotizaciones->estado->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cotizaciones->vigencia->Visible) { // vigencia ?>
	<div id="r_vigencia" class="form-group">
		<label id="elh_cotizaciones_vigencia" for="x_vigencia" class="col-sm-2 control-label ewLabel"><?php echo $cotizaciones->vigencia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cotizaciones->vigencia->CellAttributes() ?>>
<span id="el_cotizaciones_vigencia">
<input type="text" data-table="cotizaciones" data-field="x_vigencia" name="x_vigencia" id="x_vigencia" size="30" placeholder="<?php echo ew_HtmlEncode($cotizaciones->vigencia->getPlaceHolder()) ?>" value="<?php echo $cotizaciones->vigencia->EditValue ?>"<?php echo $cotizaciones->vigencia->EditAttributes() ?>>
</span>
<?php echo $cotizaciones->vigencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$cotizaciones_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cotizaciones_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcotizacionesedit.Init();
</script>
<?php
$cotizaciones_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cotizaciones_edit->Page_Terminate();
?>
