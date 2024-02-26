<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "cotizaciones_detallesinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cotizaciones_detalles_delete = NULL; // Initialize page object first

class ccotizaciones_detalles_delete extends ccotizaciones_detalles {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'cotizaciones_detalles';

	// Page object name
	var $PageObjName = 'cotizaciones_detalles_delete';

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

		// Table object (cotizaciones_detalles)
		if (!isset($GLOBALS["cotizaciones_detalles"]) || get_class($GLOBALS["cotizaciones_detalles"]) == "ccotizaciones_detalles") {
			$GLOBALS["cotizaciones_detalles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cotizaciones_detalles"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cotizaciones_detalles', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cotizaciones_detalleslist.php"));
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
		$this->idCotizacion->SetVisibility();
		$this->idArticulo->SetVisibility();
		$this->cantidad->SetVisibility();
		$this->referencia->SetVisibility();
		$this->precioReferencia->SetVisibility();
		$this->idAlicuota->SetVisibility();

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
		global $EW_EXPORT, $cotizaciones_detalles;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cotizaciones_detalles);
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
			$this->Page_Terminate("cotizaciones_detalleslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in cotizaciones_detalles class, cotizaciones_detallesinfo.php

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
				$this->Page_Terminate("cotizaciones_detalleslist.php"); // Return to list
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
		$this->idCotizacion->setDbValue($rs->fields('idCotizacion'));
		$this->idArticulo->setDbValue($rs->fields('idArticulo'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->referencia->setDbValue($rs->fields('referencia'));
		$this->precioReferencia->setDbValue($rs->fields('precioReferencia'));
		$this->idAlicuota->setDbValue($rs->fields('idAlicuota'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idCotizacion->DbValue = $row['idCotizacion'];
		$this->idArticulo->DbValue = $row['idArticulo'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->referencia->DbValue = $row['referencia'];
		$this->precioReferencia->DbValue = $row['precioReferencia'];
		$this->idAlicuota->DbValue = $row['idAlicuota'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idCotizacion
		// idArticulo
		// cantidad
		// referencia
		// precioReferencia
		// idAlicuota

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// idCotizacion
		$this->idCotizacion->ViewValue = $this->idCotizacion->CurrentValue;
		$this->idCotizacion->ViewCustomAttributes = "";

		// idArticulo
		$this->idArticulo->ViewValue = $this->idArticulo->CurrentValue;
		$this->idArticulo->ViewCustomAttributes = "";

		// cantidad
		$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
		$this->cantidad->ViewCustomAttributes = "";

		// referencia
		$this->referencia->ViewValue = $this->referencia->CurrentValue;
		$this->referencia->ViewCustomAttributes = "";

		// precioReferencia
		$this->precioReferencia->ViewValue = $this->precioReferencia->CurrentValue;
		$this->precioReferencia->ViewCustomAttributes = "";

		// idAlicuota
		$this->idAlicuota->ViewValue = $this->idAlicuota->CurrentValue;
		$this->idAlicuota->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idCotizacion
			$this->idCotizacion->LinkCustomAttributes = "";
			$this->idCotizacion->HrefValue = "";
			$this->idCotizacion->TooltipValue = "";

			// idArticulo
			$this->idArticulo->LinkCustomAttributes = "";
			$this->idArticulo->HrefValue = "";
			$this->idArticulo->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// referencia
			$this->referencia->LinkCustomAttributes = "";
			$this->referencia->HrefValue = "";
			$this->referencia->TooltipValue = "";

			// precioReferencia
			$this->precioReferencia->LinkCustomAttributes = "";
			$this->precioReferencia->HrefValue = "";
			$this->precioReferencia->TooltipValue = "";

			// idAlicuota
			$this->idAlicuota->LinkCustomAttributes = "";
			$this->idAlicuota->HrefValue = "";
			$this->idAlicuota->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cotizaciones_detalleslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cotizaciones_detalles_delete)) $cotizaciones_detalles_delete = new ccotizaciones_detalles_delete();

// Page init
$cotizaciones_detalles_delete->Page_Init();

// Page main
$cotizaciones_detalles_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cotizaciones_detalles_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fcotizaciones_detallesdelete = new ew_Form("fcotizaciones_detallesdelete", "delete");

// Form_CustomValidate event
fcotizaciones_detallesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcotizaciones_detallesdelete.ValidateRequired = true;
<?php } else { ?>
fcotizaciones_detallesdelete.ValidateRequired = false; 
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
<?php $cotizaciones_detalles_delete->ShowPageHeader(); ?>
<?php
$cotizaciones_detalles_delete->ShowMessage();
?>
<form name="fcotizaciones_detallesdelete" id="fcotizaciones_detallesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cotizaciones_detalles_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cotizaciones_detalles_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cotizaciones_detalles">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($cotizaciones_detalles_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $cotizaciones_detalles->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($cotizaciones_detalles->id->Visible) { // id ?>
		<th><span id="elh_cotizaciones_detalles_id" class="cotizaciones_detalles_id"><?php echo $cotizaciones_detalles->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->idCotizacion->Visible) { // idCotizacion ?>
		<th><span id="elh_cotizaciones_detalles_idCotizacion" class="cotizaciones_detalles_idCotizacion"><?php echo $cotizaciones_detalles->idCotizacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->idArticulo->Visible) { // idArticulo ?>
		<th><span id="elh_cotizaciones_detalles_idArticulo" class="cotizaciones_detalles_idArticulo"><?php echo $cotizaciones_detalles->idArticulo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->cantidad->Visible) { // cantidad ?>
		<th><span id="elh_cotizaciones_detalles_cantidad" class="cotizaciones_detalles_cantidad"><?php echo $cotizaciones_detalles->cantidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->referencia->Visible) { // referencia ?>
		<th><span id="elh_cotizaciones_detalles_referencia" class="cotizaciones_detalles_referencia"><?php echo $cotizaciones_detalles->referencia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->precioReferencia->Visible) { // precioReferencia ?>
		<th><span id="elh_cotizaciones_detalles_precioReferencia" class="cotizaciones_detalles_precioReferencia"><?php echo $cotizaciones_detalles->precioReferencia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($cotizaciones_detalles->idAlicuota->Visible) { // idAlicuota ?>
		<th><span id="elh_cotizaciones_detalles_idAlicuota" class="cotizaciones_detalles_idAlicuota"><?php echo $cotizaciones_detalles->idAlicuota->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$cotizaciones_detalles_delete->RecCnt = 0;
$i = 0;
while (!$cotizaciones_detalles_delete->Recordset->EOF) {
	$cotizaciones_detalles_delete->RecCnt++;
	$cotizaciones_detalles_delete->RowCnt++;

	// Set row properties
	$cotizaciones_detalles->ResetAttrs();
	$cotizaciones_detalles->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$cotizaciones_detalles_delete->LoadRowValues($cotizaciones_detalles_delete->Recordset);

	// Render row
	$cotizaciones_detalles_delete->RenderRow();
?>
	<tr<?php echo $cotizaciones_detalles->RowAttributes() ?>>
<?php if ($cotizaciones_detalles->id->Visible) { // id ?>
		<td<?php echo $cotizaciones_detalles->id->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_id" class="cotizaciones_detalles_id">
<span<?php echo $cotizaciones_detalles->id->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->idCotizacion->Visible) { // idCotizacion ?>
		<td<?php echo $cotizaciones_detalles->idCotizacion->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_idCotizacion" class="cotizaciones_detalles_idCotizacion">
<span<?php echo $cotizaciones_detalles->idCotizacion->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->idCotizacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->idArticulo->Visible) { // idArticulo ?>
		<td<?php echo $cotizaciones_detalles->idArticulo->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_idArticulo" class="cotizaciones_detalles_idArticulo">
<span<?php echo $cotizaciones_detalles->idArticulo->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->idArticulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->cantidad->Visible) { // cantidad ?>
		<td<?php echo $cotizaciones_detalles->cantidad->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_cantidad" class="cotizaciones_detalles_cantidad">
<span<?php echo $cotizaciones_detalles->cantidad->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->cantidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->referencia->Visible) { // referencia ?>
		<td<?php echo $cotizaciones_detalles->referencia->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_referencia" class="cotizaciones_detalles_referencia">
<span<?php echo $cotizaciones_detalles->referencia->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->referencia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->precioReferencia->Visible) { // precioReferencia ?>
		<td<?php echo $cotizaciones_detalles->precioReferencia->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_precioReferencia" class="cotizaciones_detalles_precioReferencia">
<span<?php echo $cotizaciones_detalles->precioReferencia->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->precioReferencia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($cotizaciones_detalles->idAlicuota->Visible) { // idAlicuota ?>
		<td<?php echo $cotizaciones_detalles->idAlicuota->CellAttributes() ?>>
<span id="el<?php echo $cotizaciones_detalles_delete->RowCnt ?>_cotizaciones_detalles_idAlicuota" class="cotizaciones_detalles_idAlicuota">
<span<?php echo $cotizaciones_detalles->idAlicuota->ViewAttributes() ?>>
<?php echo $cotizaciones_detalles->idAlicuota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$cotizaciones_detalles_delete->Recordset->MoveNext();
}
$cotizaciones_detalles_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cotizaciones_detalles_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fcotizaciones_detallesdelete.Init();
</script>
<?php
$cotizaciones_detalles_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cotizaciones_detalles_delete->Page_Terminate();
?>
