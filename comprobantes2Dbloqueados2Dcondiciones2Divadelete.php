<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "comprobantes2Dbloqueados2Dcondiciones2Divainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "condiciones2Divainfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$comprobantes2Dbloqueados2Dcondiciones2Diva_delete = NULL; // Initialize page object first

class ccomprobantes2Dbloqueados2Dcondiciones2Diva_delete extends ccomprobantes2Dbloqueados2Dcondiciones2Diva {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'comprobantes-bloqueados-condiciones-iva';

	// Page object name
	var $PageObjName = 'comprobantes2Dbloqueados2Dcondiciones2Diva_delete';

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

		// Table object (comprobantes2Dbloqueados2Dcondiciones2Diva)
		if (!isset($GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"]) || get_class($GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"]) == "ccomprobantes2Dbloqueados2Dcondiciones2Diva") {
			$GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["comprobantes2Dbloqueados2Dcondiciones2Diva"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Table object (condiciones2Diva)
		if (!isset($GLOBALS['condiciones2Diva'])) $GLOBALS['condiciones2Diva'] = new ccondiciones2Diva();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'comprobantes-bloqueados-condiciones-iva', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("comprobantes2Dbloqueados2Dcondiciones2Divalist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idCondicionIva->SetVisibility();
		$this->idComprobanteBloqueado->SetVisibility();

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
		global $EW_EXPORT, $comprobantes2Dbloqueados2Dcondiciones2Diva;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($comprobantes2Dbloqueados2Dcondiciones2Diva);
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
			$this->Page_Terminate("comprobantes2Dbloqueados2Dcondiciones2Divalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in comprobantes2Dbloqueados2Dcondiciones2Diva class, comprobantes2Dbloqueados2Dcondiciones2Divainfo.php

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
				$this->Page_Terminate("comprobantes2Dbloqueados2Dcondiciones2Divalist.php"); // Return to list
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
		$this->idCondicionIva->setDbValue($rs->fields('idCondicionIva'));
		$this->idComprobanteBloqueado->setDbValue($rs->fields('idComprobanteBloqueado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idCondicionIva->DbValue = $row['idCondicionIva'];
		$this->idComprobanteBloqueado->DbValue = $row['idComprobanteBloqueado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idCondicionIva
		// idComprobanteBloqueado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idCondicionIva
		if (strval($this->idCondicionIva->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCondicionIva->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `condiciones-iva`";
		$sWhereWrk = "";
		$this->idCondicionIva->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCondicionIva, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCondicionIva->ViewValue = $this->idCondicionIva->CurrentValue;
			}
		} else {
			$this->idCondicionIva->ViewValue = NULL;
		}
		$this->idCondicionIva->ViewCustomAttributes = "";

		// idComprobanteBloqueado
		if (strval($this->idComprobanteBloqueado->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idComprobanteBloqueado->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `comprobantes`";
		$sWhereWrk = "";
		$this->idComprobanteBloqueado->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idComprobanteBloqueado, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idComprobanteBloqueado->ViewValue = $this->idComprobanteBloqueado->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idComprobanteBloqueado->ViewValue = $this->idComprobanteBloqueado->CurrentValue;
			}
		} else {
			$this->idComprobanteBloqueado->ViewValue = NULL;
		}
		$this->idComprobanteBloqueado->ViewCustomAttributes = "";

			// idCondicionIva
			$this->idCondicionIva->LinkCustomAttributes = "";
			$this->idCondicionIva->HrefValue = "";
			$this->idCondicionIva->TooltipValue = "";

			// idComprobanteBloqueado
			$this->idComprobanteBloqueado->LinkCustomAttributes = "";
			$this->idComprobanteBloqueado->HrefValue = "";
			$this->idComprobanteBloqueado->TooltipValue = "";
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
			if ($sMasterTblVar == "condiciones2Diva") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["condiciones2Diva"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->idCondicionIva->setQueryStringValue($GLOBALS["condiciones2Diva"]->id->QueryStringValue);
					$this->idCondicionIva->setSessionValue($this->idCondicionIva->QueryStringValue);
					if (!is_numeric($GLOBALS["condiciones2Diva"]->id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "condiciones2Diva") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id"] <> "") {
					$GLOBALS["condiciones2Diva"]->id->setFormValue($_POST["fk_id"]);
					$this->idCondicionIva->setFormValue($GLOBALS["condiciones2Diva"]->id->FormValue);
					$this->idCondicionIva->setSessionValue($this->idCondicionIva->FormValue);
					if (!is_numeric($GLOBALS["condiciones2Diva"]->id->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "condiciones2Diva") {
				if ($this->idCondicionIva->CurrentValue == "") $this->idCondicionIva->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("comprobantes2Dbloqueados2Dcondiciones2Divalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($comprobantes2Dbloqueados2Dcondiciones2Diva_delete)) $comprobantes2Dbloqueados2Dcondiciones2Diva_delete = new ccomprobantes2Dbloqueados2Dcondiciones2Diva_delete();

// Page init
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Page_Init();

// Page main
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fcomprobantes2Dbloqueados2Dcondiciones2Divadelete = new ew_Form("fcomprobantes2Dbloqueados2Dcondiciones2Divadelete", "delete");

// Form_CustomValidate event
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.ValidateRequired = true;
<?php } else { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.Lists["x_idCondicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.Lists["x_idComprobanteBloqueado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};

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
<?php $comprobantes2Dbloqueados2Dcondiciones2Diva_delete->ShowPageHeader(); ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->ShowMessage();
?>
<form name="fcomprobantes2Dbloqueados2Dcondiciones2Divadelete" id="fcomprobantes2Dbloqueados2Dcondiciones2Divadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="comprobantes2Dbloqueados2Dcondiciones2Diva">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
		<th><span id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
		<th><span id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RecCnt = 0;
$i = 0;
while (!$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Recordset->EOF) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RecCnt++;
	$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RowCnt++;

	// Set row properties
	$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
	$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->LoadRowValues($comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Recordset);

	// Render row
	$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RenderRow();
?>
	<tr<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttributes() ?>>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
		<td<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CellAttributes() ?>>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
		<td<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->CellAttributes() ?>>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_delete->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Recordset->MoveNext();
}
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fcomprobantes2Dbloqueados2Dcondiciones2Divadelete.Init();
</script>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_delete->Page_Terminate();
?>
