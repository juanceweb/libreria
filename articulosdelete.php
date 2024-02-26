<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "articulosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$articulos_delete = NULL; // Initialize page object first

class carticulos_delete extends carticulos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos';

	// Page object name
	var $PageObjName = 'articulos_delete';

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

		// Table object (articulos)
		if (!isset($GLOBALS["articulos"]) || get_class($GLOBALS["articulos"]) == "carticulos") {
			$GLOBALS["articulos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["articulos"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'articulos', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("articuloslist.php"));
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
		$this->denominacionExterna->SetVisibility();
		$this->denominacionInterna->SetVisibility();
		$this->idAlicuotaIva->SetVisibility();
		$this->idCategoria->SetVisibility();
		$this->idSubcateogoria->SetVisibility();
		$this->idRubro->SetVisibility();
		$this->idMarca->SetVisibility();
		$this->idPrecioCompra->SetVisibility();
		$this->proveedor->SetVisibility();
		$this->calculoPrecio->SetVisibility();
		$this->rentabilidad->SetVisibility();
		$this->precioVenta->SetVisibility();

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
		global $EW_EXPORT, $articulos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($articulos);
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
			$this->Page_Terminate("articuloslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in articulos class, articulosinfo.php

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
				$this->Page_Terminate("articuloslist.php"); // Return to list
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
		$this->denominacionExterna->setDbValue($rs->fields('denominacionExterna'));
		$this->denominacionInterna->setDbValue($rs->fields('denominacionInterna'));
		$this->idAlicuotaIva->setDbValue($rs->fields('idAlicuotaIva'));
		$this->idCategoria->setDbValue($rs->fields('idCategoria'));
		$this->idSubcateogoria->setDbValue($rs->fields('idSubcateogoria'));
		$this->idRubro->setDbValue($rs->fields('idRubro'));
		$this->idMarca->setDbValue($rs->fields('idMarca'));
		$this->fabricante->setDbValue($rs->fields('fabricante'));
		$this->codigoBarras->setDbValue($rs->fields('codigoBarras'));
		$this->imagenes->Upload->DbValue = $rs->fields('imagenes');
		$this->imagenes->CurrentValue = $this->imagenes->Upload->DbValue;
		$this->idPrecioCompra->setDbValue($rs->fields('idPrecioCompra'));
		$this->proveedor->setDbValue($rs->fields('proveedor'));
		$this->calculoPrecio->setDbValue($rs->fields('calculoPrecio'));
		$this->rentabilidad->setDbValue($rs->fields('rentabilidad'));
		$this->precioVenta->setDbValue($rs->fields('precioVenta'));
		$this->tags->setDbValue($rs->fields('tags'));
		$this->detalle->setDbValue($rs->fields('detalle'));
		$this->idUnidadMedidaCompra->setDbValue($rs->fields('idUnidadMedidaCompra'));
		$this->idUnidadMedidaVenta->setDbValue($rs->fields('idUnidadMedidaVenta'));
		$this->codigosExternos->setDbValue($rs->fields('codigosExternos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->denominacionExterna->DbValue = $row['denominacionExterna'];
		$this->denominacionInterna->DbValue = $row['denominacionInterna'];
		$this->idAlicuotaIva->DbValue = $row['idAlicuotaIva'];
		$this->idCategoria->DbValue = $row['idCategoria'];
		$this->idSubcateogoria->DbValue = $row['idSubcateogoria'];
		$this->idRubro->DbValue = $row['idRubro'];
		$this->idMarca->DbValue = $row['idMarca'];
		$this->fabricante->DbValue = $row['fabricante'];
		$this->codigoBarras->DbValue = $row['codigoBarras'];
		$this->imagenes->Upload->DbValue = $row['imagenes'];
		$this->idPrecioCompra->DbValue = $row['idPrecioCompra'];
		$this->proveedor->DbValue = $row['proveedor'];
		$this->calculoPrecio->DbValue = $row['calculoPrecio'];
		$this->rentabilidad->DbValue = $row['rentabilidad'];
		$this->precioVenta->DbValue = $row['precioVenta'];
		$this->tags->DbValue = $row['tags'];
		$this->detalle->DbValue = $row['detalle'];
		$this->idUnidadMedidaCompra->DbValue = $row['idUnidadMedidaCompra'];
		$this->idUnidadMedidaVenta->DbValue = $row['idUnidadMedidaVenta'];
		$this->codigosExternos->DbValue = $row['codigosExternos'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->rentabilidad->FormValue == $this->rentabilidad->CurrentValue && is_numeric(ew_StrToFloat($this->rentabilidad->CurrentValue)))
			$this->rentabilidad->CurrentValue = ew_StrToFloat($this->rentabilidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->precioVenta->FormValue == $this->precioVenta->CurrentValue && is_numeric(ew_StrToFloat($this->precioVenta->CurrentValue)))
			$this->precioVenta->CurrentValue = ew_StrToFloat($this->precioVenta->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// denominacionExterna
		// denominacionInterna
		// idAlicuotaIva
		// idCategoria
		// idSubcateogoria
		// idRubro
		// idMarca
		// fabricante

		$this->fabricante->CellCssStyle = "white-space: nowrap;";

		// codigoBarras
		// imagenes
		// idPrecioCompra
		// proveedor
		// calculoPrecio
		// rentabilidad
		// precioVenta
		// tags
		// detalle
		// idUnidadMedidaCompra
		// idUnidadMedidaVenta
		// codigosExternos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// denominacionExterna
		$this->denominacionExterna->ViewValue = $this->denominacionExterna->CurrentValue;
		$this->denominacionExterna->ViewCustomAttributes = "";

		// denominacionInterna
		$this->denominacionInterna->ViewValue = $this->denominacionInterna->CurrentValue;
		$this->denominacionInterna->ViewCustomAttributes = "";

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

		// idCategoria
		if (strval($this->idCategoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias-articulos`";
		$sWhereWrk = "";
		$this->idCategoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idCategoria->ViewValue = $this->idCategoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idCategoria->ViewValue = $this->idCategoria->CurrentValue;
			}
		} else {
			$this->idCategoria->ViewValue = NULL;
		}
		$this->idCategoria->ViewCustomAttributes = "";

		// idSubcateogoria
		if (strval($this->idSubcateogoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subcategorias-articulos`";
		$sWhereWrk = "";
		$this->idSubcateogoria->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idSubcateogoria->ViewValue = $this->idSubcateogoria->CurrentValue;
			}
		} else {
			$this->idSubcateogoria->ViewValue = NULL;
		}
		$this->idSubcateogoria->ViewCustomAttributes = "";

		// idRubro
		if (strval($this->idRubro->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
		$sWhereWrk = "";
		$this->idRubro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idRubro->ViewValue = $this->idRubro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idRubro->ViewValue = $this->idRubro->CurrentValue;
			}
		} else {
			$this->idRubro->ViewValue = NULL;
		}
		$this->idRubro->ViewCustomAttributes = "";

		// idMarca
		if (strval($this->idMarca->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
		$sWhereWrk = "";
		$this->idMarca->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->idMarca->ViewValue = $this->idMarca->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idMarca->ViewValue = $this->idMarca->CurrentValue;
			}
		} else {
			$this->idMarca->ViewValue = NULL;
		}
		$this->idMarca->ViewCustomAttributes = "";

		// codigoBarras
		$this->codigoBarras->ViewValue = $this->codigoBarras->CurrentValue;
		$this->codigoBarras->ViewCustomAttributes = "";

		// idPrecioCompra
		if (strval($this->idPrecioCompra->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `precios-compra`";
		$sWhereWrk = "";
		$this->idPrecioCompra->LookupFilters = array();
		$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = ew_FormatDateTime($rswrk->fields('Disp3Fld'), 0);
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->idPrecioCompra->ViewValue = $this->idPrecioCompra->CurrentValue;
			}
		} else {
			$this->idPrecioCompra->ViewValue = NULL;
		}
		$this->idPrecioCompra->ViewCustomAttributes = "";

		// proveedor
		if (strval($this->proveedor->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->proveedor->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
		$sWhereWrk = "";
		$this->proveedor->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->proveedor->ViewValue = $this->proveedor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->proveedor->ViewValue = $this->proveedor->CurrentValue;
			}
		} else {
			$this->proveedor->ViewValue = NULL;
		}
		$this->proveedor->ViewCustomAttributes = "";

		// calculoPrecio
		if (strval($this->calculoPrecio->CurrentValue) <> "") {
			$this->calculoPrecio->ViewValue = $this->calculoPrecio->OptionCaption($this->calculoPrecio->CurrentValue);
		} else {
			$this->calculoPrecio->ViewValue = NULL;
		}
		$this->calculoPrecio->ViewCustomAttributes = "";

		// rentabilidad
		$this->rentabilidad->ViewValue = $this->rentabilidad->CurrentValue;
		$this->rentabilidad->ViewCustomAttributes = "";

		// precioVenta
		$this->precioVenta->ViewValue = $this->precioVenta->CurrentValue;
		$this->precioVenta->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// denominacionExterna
			$this->denominacionExterna->LinkCustomAttributes = "";
			$this->denominacionExterna->HrefValue = "";
			$this->denominacionExterna->TooltipValue = "";

			// denominacionInterna
			$this->denominacionInterna->LinkCustomAttributes = "";
			$this->denominacionInterna->HrefValue = "";
			$this->denominacionInterna->TooltipValue = "";

			// idAlicuotaIva
			$this->idAlicuotaIva->LinkCustomAttributes = "";
			$this->idAlicuotaIva->HrefValue = "";
			$this->idAlicuotaIva->TooltipValue = "";

			// idCategoria
			$this->idCategoria->LinkCustomAttributes = "";
			$this->idCategoria->HrefValue = "";
			$this->idCategoria->TooltipValue = "";

			// idSubcateogoria
			$this->idSubcateogoria->LinkCustomAttributes = "";
			$this->idSubcateogoria->HrefValue = "";
			$this->idSubcateogoria->TooltipValue = "";

			// idRubro
			$this->idRubro->LinkCustomAttributes = "";
			$this->idRubro->HrefValue = "";
			$this->idRubro->TooltipValue = "";

			// idMarca
			$this->idMarca->LinkCustomAttributes = "";
			$this->idMarca->HrefValue = "";
			$this->idMarca->TooltipValue = "";

			// idPrecioCompra
			$this->idPrecioCompra->LinkCustomAttributes = "";
			$this->idPrecioCompra->HrefValue = "";
			$this->idPrecioCompra->TooltipValue = "";

			// proveedor
			$this->proveedor->LinkCustomAttributes = "";
			$this->proveedor->HrefValue = "";
			$this->proveedor->TooltipValue = "";

			// calculoPrecio
			$this->calculoPrecio->LinkCustomAttributes = "";
			$this->calculoPrecio->HrefValue = "";
			$this->calculoPrecio->TooltipValue = "";

			// rentabilidad
			$this->rentabilidad->LinkCustomAttributes = "";
			$this->rentabilidad->HrefValue = "";
			$this->rentabilidad->TooltipValue = "";

			// precioVenta
			$this->precioVenta->LinkCustomAttributes = "";
			$this->precioVenta->HrefValue = "";
			$this->precioVenta->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("articuloslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($articulos_delete)) $articulos_delete = new carticulos_delete();

// Page init
$articulos_delete->Page_Init();

// Page main
$articulos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = farticulosdelete = new ew_Form("farticulosdelete", "delete");

// Form_CustomValidate event
farticulosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulosdelete.ValidateRequired = true;
<?php } else { ?>
farticulosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulosdelete.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulosdelete.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
farticulosdelete.Lists["x_idSubcateogoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
farticulosdelete.Lists["x_idRubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rubros"};
farticulosdelete.Lists["x_idMarca"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
farticulosdelete.Lists["x_idPrecioCompra"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_precioPesos","x_denominacion","x_ultimaActualizacion",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"precios2Dcompra"};
farticulosdelete.Lists["x_proveedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
farticulosdelete.Lists["x_calculoPrecio"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
farticulosdelete.Lists["x_calculoPrecio"].Options = <?php echo json_encode($articulos->calculoPrecio->Options()) ?>;

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
<?php $articulos_delete->ShowPageHeader(); ?>
<?php
$articulos_delete->ShowMessage();
?>
<form name="farticulosdelete" id="farticulosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($articulos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $articulos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($articulos->id->Visible) { // id ?>
		<th><span id="elh_articulos_id" class="articulos_id"><?php echo $articulos->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
		<th><span id="elh_articulos_denominacionExterna" class="articulos_denominacionExterna"><?php echo $articulos->denominacionExterna->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
		<th><span id="elh_articulos_denominacionInterna" class="articulos_denominacionInterna"><?php echo $articulos->denominacionInterna->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<th><span id="elh_articulos_idAlicuotaIva" class="articulos_idAlicuotaIva"><?php echo $articulos->idAlicuotaIva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
		<th><span id="elh_articulos_idCategoria" class="articulos_idCategoria"><?php echo $articulos->idCategoria->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
		<th><span id="elh_articulos_idSubcateogoria" class="articulos_idSubcateogoria"><?php echo $articulos->idSubcateogoria->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
		<th><span id="elh_articulos_idRubro" class="articulos_idRubro"><?php echo $articulos->idRubro->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
		<th><span id="elh_articulos_idMarca" class="articulos_idMarca"><?php echo $articulos->idMarca->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
		<th><span id="elh_articulos_idPrecioCompra" class="articulos_idPrecioCompra"><?php echo $articulos->idPrecioCompra->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
		<th><span id="elh_articulos_proveedor" class="articulos_proveedor"><?php echo $articulos->proveedor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
		<th><span id="elh_articulos_calculoPrecio" class="articulos_calculoPrecio"><?php echo $articulos->calculoPrecio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
		<th><span id="elh_articulos_rentabilidad" class="articulos_rentabilidad"><?php echo $articulos->rentabilidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
		<th><span id="elh_articulos_precioVenta" class="articulos_precioVenta"><?php echo $articulos->precioVenta->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$articulos_delete->RecCnt = 0;
$i = 0;
while (!$articulos_delete->Recordset->EOF) {
	$articulos_delete->RecCnt++;
	$articulos_delete->RowCnt++;

	// Set row properties
	$articulos->ResetAttrs();
	$articulos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$articulos_delete->LoadRowValues($articulos_delete->Recordset);

	// Render row
	$articulos_delete->RenderRow();
?>
	<tr<?php echo $articulos->RowAttributes() ?>>
<?php if ($articulos->id->Visible) { // id ?>
		<td<?php echo $articulos->id->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_id" class="articulos_id">
<span<?php echo $articulos->id->ViewAttributes() ?>>
<?php echo $articulos->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
		<td<?php echo $articulos->denominacionExterna->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_denominacionExterna" class="articulos_denominacionExterna">
<span<?php echo $articulos->denominacionExterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionExterna->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
		<td<?php echo $articulos->denominacionInterna->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_denominacionInterna" class="articulos_denominacionInterna">
<span<?php echo $articulos->denominacionInterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionInterna->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td<?php echo $articulos->idAlicuotaIva->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idAlicuotaIva" class="articulos_idAlicuotaIva">
<span<?php echo $articulos->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos->idAlicuotaIva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
		<td<?php echo $articulos->idCategoria->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idCategoria" class="articulos_idCategoria">
<span<?php echo $articulos->idCategoria->ViewAttributes() ?>>
<?php echo $articulos->idCategoria->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
		<td<?php echo $articulos->idSubcateogoria->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idSubcateogoria" class="articulos_idSubcateogoria">
<span<?php echo $articulos->idSubcateogoria->ViewAttributes() ?>>
<?php echo $articulos->idSubcateogoria->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
		<td<?php echo $articulos->idRubro->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idRubro" class="articulos_idRubro">
<span<?php echo $articulos->idRubro->ViewAttributes() ?>>
<?php echo $articulos->idRubro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
		<td<?php echo $articulos->idMarca->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idMarca" class="articulos_idMarca">
<span<?php echo $articulos->idMarca->ViewAttributes() ?>>
<?php echo $articulos->idMarca->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
		<td<?php echo $articulos->idPrecioCompra->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_idPrecioCompra" class="articulos_idPrecioCompra">
<span<?php echo $articulos->idPrecioCompra->ViewAttributes() ?>>
<?php echo $articulos->idPrecioCompra->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
		<td<?php echo $articulos->proveedor->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_proveedor" class="articulos_proveedor">
<span<?php echo $articulos->proveedor->ViewAttributes() ?>>
<?php echo $articulos->proveedor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
		<td<?php echo $articulos->calculoPrecio->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_calculoPrecio" class="articulos_calculoPrecio">
<span<?php echo $articulos->calculoPrecio->ViewAttributes() ?>>
<?php echo $articulos->calculoPrecio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
		<td<?php echo $articulos->rentabilidad->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_rentabilidad" class="articulos_rentabilidad">
<span<?php echo $articulos->rentabilidad->ViewAttributes() ?>>
<?php echo $articulos->rentabilidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
		<td<?php echo $articulos->precioVenta->CellAttributes() ?>>
<span id="el<?php echo $articulos_delete->RowCnt ?>_articulos_precioVenta" class="articulos_precioVenta">
<span<?php echo $articulos->precioVenta->ViewAttributes() ?>>
<?php echo $articulos->precioVenta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$articulos_delete->Recordset->MoveNext();
}
$articulos_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $articulos_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
farticulosdelete.Init();
</script>
<?php
$articulos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$articulos_delete->Page_Terminate();
?>
