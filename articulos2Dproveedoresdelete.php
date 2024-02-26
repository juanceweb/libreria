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

$articulos2Dproveedores_delete = NULL; // Initialize page object first

class carticulos2Dproveedores_delete extends carticulos2Dproveedores {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos-proveedores';

	// Page object name
	var $PageObjName = 'articulos2Dproveedores_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
		$this->precioPesos->SetVisibility();
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
			$this->Page_Terminate("articulos2Dproveedoreslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in articulos2Dproveedores class, articulos2Dproveedoresinfo.php

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
				$this->Page_Terminate("articulos2Dproveedoreslist.php"); // Return to list
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

		// Convert decimal values if posted back
		if ($this->precioPesos->FormValue == $this->precioPesos->CurrentValue && is_numeric(ew_StrToFloat($this->precioPesos->CurrentValue)))
			$this->precioPesos->CurrentValue = ew_StrToFloat($this->precioPesos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

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

			// precioPesos
			$this->precioPesos->LinkCustomAttributes = "";
			$this->precioPesos->HrefValue = "";
			$this->precioPesos->TooltipValue = "";

			// ultimaActualizacion
			$this->ultimaActualizacion->LinkCustomAttributes = "";
			$this->ultimaActualizacion->HrefValue = "";
			$this->ultimaActualizacion->TooltipValue = "";
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
if (!isset($articulos2Dproveedores_delete)) $articulos2Dproveedores_delete = new carticulos2Dproveedores_delete();

// Page init
$articulos2Dproveedores_delete->Page_Init();

// Page main
$articulos2Dproveedores_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dproveedores_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = farticulos2Dproveedoresdelete = new ew_Form("farticulos2Dproveedoresdelete", "delete");

// Form_CustomValidate event
farticulos2Dproveedoresdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dproveedoresdelete.ValidateRequired = true;
<?php } else { ?>
farticulos2Dproveedoresdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dproveedoresdelete.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dproveedoresdelete.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulos2Dproveedoresdelete.Lists["x_idMoneda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_simbolo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"monedas"};
farticulos2Dproveedoresdelete.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_dto1","x_dto2","x_dto3"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

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
<?php $articulos2Dproveedores_delete->ShowPageHeader(); ?>
<?php
$articulos2Dproveedores_delete->ShowMessage();
?>
<form name="farticulos2Dproveedoresdelete" id="farticulos2Dproveedoresdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos2Dproveedores_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos2Dproveedores_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos2Dproveedores">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($articulos2Dproveedores_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $articulos2Dproveedores->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
		<th><span id="elh_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
		<th><span id="elh_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<th><span id="elh_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
		<th><span id="elh_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
		<th><span id="elh_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio"><?php echo $articulos2Dproveedores->precio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<th><span id="elh_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
		<th><span id="elh_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
		<th><span id="elh_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
		<th><span id="elh_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
		<th><span id="elh_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
		<th><span id="elh_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos"><?php echo $articulos2Dproveedores->precioPesos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
		<th><span id="elh_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion"><?php echo $articulos2Dproveedores->ultimaActualizacion->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$articulos2Dproveedores_delete->RecCnt = 0;
$i = 0;
while (!$articulos2Dproveedores_delete->Recordset->EOF) {
	$articulos2Dproveedores_delete->RecCnt++;
	$articulos2Dproveedores_delete->RowCnt++;

	// Set row properties
	$articulos2Dproveedores->ResetAttrs();
	$articulos2Dproveedores->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$articulos2Dproveedores_delete->LoadRowValues($articulos2Dproveedores_delete->Recordset);

	// Render row
	$articulos2Dproveedores_delete->RenderRow();
?>
	<tr<?php echo $articulos2Dproveedores->RowAttributes() ?>>
<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
		<td<?php echo $articulos2Dproveedores->idArticulo->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
		<td<?php echo $articulos2Dproveedores->codExterno->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno">
<span<?php echo $articulos2Dproveedores->codExterno->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->codExterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td<?php echo $articulos2Dproveedores->idAlicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva">
<span<?php echo $articulos2Dproveedores->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
		<td<?php echo $articulos2Dproveedores->idMoneda->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda">
<span<?php echo $articulos2Dproveedores->idMoneda->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
		<td<?php echo $articulos2Dproveedores->precio->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio">
<span<?php echo $articulos2Dproveedores->precio->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td<?php echo $articulos2Dproveedores->idUnidadMedida->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida">
<span<?php echo $articulos2Dproveedores->idUnidadMedida->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
		<td<?php echo $articulos2Dproveedores->dto1->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1">
<span<?php echo $articulos2Dproveedores->dto1->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
		<td<?php echo $articulos2Dproveedores->dto2->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2">
<span<?php echo $articulos2Dproveedores->dto2->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
		<td<?php echo $articulos2Dproveedores->dto3->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3">
<span<?php echo $articulos2Dproveedores->dto3->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
		<td<?php echo $articulos2Dproveedores->idTercero->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
		<td<?php echo $articulos2Dproveedores->precioPesos->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos">
<span<?php echo $articulos2Dproveedores->precioPesos->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precioPesos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
		<td<?php echo $articulos2Dproveedores->ultimaActualizacion->CellAttributes() ?>>
<span id="el<?php echo $articulos2Dproveedores_delete->RowCnt ?>_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion">
<span<?php echo $articulos2Dproveedores->ultimaActualizacion->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->ultimaActualizacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$articulos2Dproveedores_delete->Recordset->MoveNext();
}
$articulos2Dproveedores_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos2Dproveedores_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
farticulos2Dproveedoresdelete.Init();
</script>
<?php
$articulos2Dproveedores_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$articulos2Dproveedores_delete->Page_Terminate();
?>
