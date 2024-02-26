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

$articulos2Dstock_delete = NULL; // Initialize page object first

class carticulos2Dstock_delete extends carticulos2Dstock {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos-stock';

	// Page object name
	var $PageObjName = 'articulos2Dstock_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("articulos2Dstocklist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in articulos2Dstock class, articulos2Dstockinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("articulos2Dstocklist.php"); // Return to list
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

		$this->id->CellCssStyle = "white-space: nowrap;";

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
if (!isset($articulos2Dstock_delete)) $articulos2Dstock_delete = new carticulos2Dstock_delete();

// Page init
$articulos2Dstock_delete->Page_Init();

// Page main
$articulos2Dstock_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dstock_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = farticulos2Dstockdelete = new ew_Form("farticulos2Dstockdelete", "delete");

// Form_CustomValidate event
farticulos2Dstockdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dstockdelete.ValidateRequired = true;
<?php } else { ?>
farticulos2Dstockdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dstockdelete.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dstockdelete.Lists["x_idUnidadMedida"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidades2Dmedida"};

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
<?php $articulos2Dstock_delete->ShowPageHeader(); ?>
<?php
$articulos2Dstock_delete->ShowMessage();
?>
<form name="farticulos2Dstockdelete" id="farticulos2Dstockdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos2Dstock_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos2Dstock_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos2Dstock">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($articulos2Dstock_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $articulos2Dstock->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
		<th><span id="elh_articulos2Dstock_idArticulo" class="articulos2Dstock_idArticulo"><?php echo $articulos2Dstock->idArticulo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<th><span id="elh_articulos2Dstock_idUnidadMedida" class="articulos2Dstock_idUnidadMedida"><?php echo $articulos2Dstock->idUnidadMedida->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
		<th><span id="elh_articulos2Dstock_indiceConversion" class="articulos2Dstock_indiceConversion"><?php echo $articulos2Dstock->indiceConversion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
		<th><span id="elh_articulos2Dstock_stock" class="articulos2Dstock_stock"><?php echo $articulos2Dstock->stock->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
		<th><span id="elh_articulos2Dstock_stockReservadoVenta" class="articulos2Dstock_stockReservadoVenta"><?php echo $articulos2Dstock->stockReservadoVenta->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
		<th><span id="elh_articulos2Dstock_stockReservadoCompra" class="articulos2Dstock_stockReservadoCompra"><?php echo $articulos2Dstock->stockReservadoCompra->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
		<th><span id="elh_articulos2Dstock_stockMinimo" class="articulos2Dstock_stockMinimo"><?php echo $articulos2Dstock->stockMinimo->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$articulos2Dstock_delete->RecCnt = 0;
$i = 0;
while (!$articulos2Dstock_delete->Recordset->EOF) {
	$articulos2Dstock_delete->RecCnt++;
	$articulos2Dstock_delete->RowCnt++;

	// Set row properties
	$articulos2Dstock->ResetAttrs();
	$articulos2Dstock->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$articulos2Dstock_delete->LoadRowValues($articulos2Dstock_delete->Recordset);

	// Render row
	$articulos2Dstock_delete->RenderRow();
?>
	<tr<?php echo $articulos2Dstock->RowAttributes() ?>>
<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
		<td<?php echo $articulos2Dstock->idArticulo->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_idArticulo" class="articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td<?php echo $articulos2Dstock->idUnidadMedida->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_idUnidadMedida" class="articulos2Dstock_idUnidadMedida">
<span<?php echo $articulos2Dstock->idUnidadMedida->ViewAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
		<td<?php echo $articulos2Dstock->indiceConversion->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_indiceConversion" class="articulos2Dstock_indiceConversion">
<span<?php echo $articulos2Dstock->indiceConversion->ViewAttributes() ?>>
<?php echo $articulos2Dstock->indiceConversion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
		<td<?php echo $articulos2Dstock->stock->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_stock" class="articulos2Dstock_stock">
<span<?php echo $articulos2Dstock->stock->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stock->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
		<td<?php echo $articulos2Dstock->stockReservadoVenta->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_stockReservadoVenta" class="articulos2Dstock_stockReservadoVenta">
<span<?php echo $articulos2Dstock->stockReservadoVenta->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockReservadoVenta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
		<td<?php echo $articulos2Dstock->stockReservadoCompra->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_stockReservadoCompra" class="articulos2Dstock_stockReservadoCompra">
<span<?php echo $articulos2Dstock->stockReservadoCompra->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockReservadoCompra->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
		<td<?php echo $articulos2Dstock->stockMinimo->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dstock_delete->RowCnt ?>_articulos2Dstock_stockMinimo" class="articulos2Dstock_stockMinimo">
<span<?php echo $articulos2Dstock->stockMinimo->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockMinimo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$articulos2Dstock_delete->Recordset->MoveNext();
}
$articulos2Dstock_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos2Dstock_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
farticulos2Dstockdelete.Init();
</script>
<?php
$articulos2Dstock_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$articulos2Dstock_delete->Page_Terminate();
?>
