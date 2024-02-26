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

$articulos_search = NULL; // Initialize page object first

class carticulos_search extends carticulos {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'articulos';

	// Page object name
	var $PageObjName = 'articulos_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->denominacionExterna->SetVisibility();
		$this->denominacionInterna->SetVisibility();
		$this->idAlicuotaIva->SetVisibility();
		$this->idCategoria->SetVisibility();
		$this->idSubcateogoria->SetVisibility();
		$this->idRubro->SetVisibility();
		$this->idMarca->SetVisibility();
		$this->codigoBarras->SetVisibility();
		$this->imagenes->SetVisibility();
		$this->idPrecioCompra->SetVisibility();
		$this->proveedor->SetVisibility();
		$this->calculoPrecio->SetVisibility();
		$this->rentabilidad->SetVisibility();
		$this->precioVenta->SetVisibility();
		$this->tags->SetVisibility();
		$this->detalle->SetVisibility();
		$this->idUnidadMedidaCompra->SetVisibility();
		$this->idUnidadMedidaVenta->SetVisibility();
		$this->codigosExternos->SetVisibility();

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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "articuloslist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->denominacionExterna); // denominacionExterna
		$this->BuildSearchUrl($sSrchUrl, $this->denominacionInterna); // denominacionInterna
		$this->BuildSearchUrl($sSrchUrl, $this->idAlicuotaIva); // idAlicuotaIva
		$this->BuildSearchUrl($sSrchUrl, $this->idCategoria); // idCategoria
		$this->BuildSearchUrl($sSrchUrl, $this->idSubcateogoria); // idSubcateogoria
		$this->BuildSearchUrl($sSrchUrl, $this->idRubro); // idRubro
		$this->BuildSearchUrl($sSrchUrl, $this->idMarca); // idMarca
		$this->BuildSearchUrl($sSrchUrl, $this->codigoBarras); // codigoBarras
		$this->BuildSearchUrl($sSrchUrl, $this->imagenes); // imagenes
		$this->BuildSearchUrl($sSrchUrl, $this->idPrecioCompra); // idPrecioCompra
		$this->BuildSearchUrl($sSrchUrl, $this->proveedor); // proveedor
		$this->BuildSearchUrl($sSrchUrl, $this->calculoPrecio); // calculoPrecio
		$this->BuildSearchUrl($sSrchUrl, $this->rentabilidad); // rentabilidad
		$this->BuildSearchUrl($sSrchUrl, $this->precioVenta); // precioVenta
		$this->BuildSearchUrl($sSrchUrl, $this->tags); // tags
		$this->BuildSearchUrl($sSrchUrl, $this->detalle); // detalle
		$this->BuildSearchUrl($sSrchUrl, $this->idUnidadMedidaCompra); // idUnidadMedidaCompra
		$this->BuildSearchUrl($sSrchUrl, $this->idUnidadMedidaVenta); // idUnidadMedidaVenta
		$this->BuildSearchUrl($sSrchUrl, $this->codigosExternos); // codigosExternos
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id"));
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// denominacionExterna
		$this->denominacionExterna->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_denominacionExterna"));
		$this->denominacionExterna->AdvancedSearch->SearchOperator = $objForm->GetValue("z_denominacionExterna");

		// denominacionInterna
		$this->denominacionInterna->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_denominacionInterna"));
		$this->denominacionInterna->AdvancedSearch->SearchOperator = $objForm->GetValue("z_denominacionInterna");

		// idAlicuotaIva
		$this->idAlicuotaIva->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idAlicuotaIva"));
		$this->idAlicuotaIva->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idAlicuotaIva");

		// idCategoria
		$this->idCategoria->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idCategoria"));
		$this->idCategoria->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idCategoria");

		// idSubcateogoria
		$this->idSubcateogoria->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idSubcateogoria"));
		$this->idSubcateogoria->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idSubcateogoria");

		// idRubro
		$this->idRubro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idRubro"));
		$this->idRubro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idRubro");

		// idMarca
		$this->idMarca->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idMarca"));
		$this->idMarca->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idMarca");

		// codigoBarras
		$this->codigoBarras->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_codigoBarras"));
		$this->codigoBarras->AdvancedSearch->SearchOperator = $objForm->GetValue("z_codigoBarras");

		// imagenes
		$this->imagenes->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_imagenes"));
		$this->imagenes->AdvancedSearch->SearchOperator = $objForm->GetValue("z_imagenes");

		// idPrecioCompra
		$this->idPrecioCompra->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idPrecioCompra"));
		$this->idPrecioCompra->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idPrecioCompra");
		$this->idPrecioCompra->AdvancedSearch->SearchCondition = $objForm->GetValue("v_idPrecioCompra");
		$this->idPrecioCompra->AdvancedSearch->SearchValue2 = ew_StripSlashes($objForm->GetValue("y_idPrecioCompra"));
		$this->idPrecioCompra->AdvancedSearch->SearchOperator2 = $objForm->GetValue("w_idPrecioCompra");

		// proveedor
		$this->proveedor->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_proveedor"));
		$this->proveedor->AdvancedSearch->SearchOperator = $objForm->GetValue("z_proveedor");

		// calculoPrecio
		$this->calculoPrecio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_calculoPrecio"));
		$this->calculoPrecio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_calculoPrecio");

		// rentabilidad
		$this->rentabilidad->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_rentabilidad"));
		$this->rentabilidad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_rentabilidad");

		// precioVenta
		$this->precioVenta->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_precioVenta"));
		$this->precioVenta->AdvancedSearch->SearchOperator = $objForm->GetValue("z_precioVenta");

		// tags
		$this->tags->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_tags"));
		$this->tags->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tags");

		// detalle
		$this->detalle->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_detalle"));
		$this->detalle->AdvancedSearch->SearchOperator = $objForm->GetValue("z_detalle");

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idUnidadMedidaCompra"));
		$this->idUnidadMedidaCompra->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idUnidadMedidaCompra");

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_idUnidadMedidaVenta"));
		$this->idUnidadMedidaVenta->AdvancedSearch->SearchOperator = $objForm->GetValue("z_idUnidadMedidaVenta");

		// codigosExternos
		$this->codigosExternos->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_codigosExternos"));
		$this->codigosExternos->AdvancedSearch->SearchOperator = $objForm->GetValue("z_codigosExternos");
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
		// denominacionExterna
		// denominacionInterna
		// idAlicuotaIva
		// idCategoria
		// idSubcateogoria
		// idRubro
		// idMarca
		// fabricante
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

		// imagenes
		if (!ew_Empty($this->imagenes->Upload->DbValue)) {
			$this->imagenes->ViewValue = $this->imagenes->Upload->DbValue;
		} else {
			$this->imagenes->ViewValue = "";
		}
		$this->imagenes->ViewCustomAttributes = "";

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

		// tags
		$this->tags->ViewValue = $this->tags->CurrentValue;
		$this->tags->ViewCustomAttributes = "";

		// detalle
		$this->detalle->ViewValue = $this->detalle->CurrentValue;
		$this->detalle->ViewCustomAttributes = "";

		// idUnidadMedidaCompra
		$this->idUnidadMedidaCompra->ViewCustomAttributes = "";

		// idUnidadMedidaVenta
		$this->idUnidadMedidaVenta->ViewCustomAttributes = "";

		// codigosExternos
		$this->codigosExternos->ViewValue = $this->codigosExternos->CurrentValue;
		$this->codigosExternos->ViewCustomAttributes = "";

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

			// codigoBarras
			$this->codigoBarras->LinkCustomAttributes = "";
			$this->codigoBarras->HrefValue = "";
			$this->codigoBarras->TooltipValue = "";

			// imagenes
			$this->imagenes->LinkCustomAttributes = "";
			$this->imagenes->HrefValue = "";
			$this->imagenes->HrefValue2 = $this->imagenes->UploadPath . $this->imagenes->Upload->DbValue;
			$this->imagenes->TooltipValue = "";

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

			// tags
			$this->tags->LinkCustomAttributes = "";
			$this->tags->HrefValue = "";
			$this->tags->TooltipValue = "";

			// detalle
			$this->detalle->LinkCustomAttributes = "";
			$this->detalle->HrefValue = "";
			$this->detalle->TooltipValue = "";

			// idUnidadMedidaCompra
			$this->idUnidadMedidaCompra->LinkCustomAttributes = "";
			$this->idUnidadMedidaCompra->HrefValue = "";
			$this->idUnidadMedidaCompra->TooltipValue = "";

			// idUnidadMedidaVenta
			$this->idUnidadMedidaVenta->LinkCustomAttributes = "";
			$this->idUnidadMedidaVenta->HrefValue = "";
			$this->idUnidadMedidaVenta->TooltipValue = "";

			// codigosExternos
			$this->codigosExternos->LinkCustomAttributes = "";
			$this->codigosExternos->HrefValue = "";
			$this->codigosExternos->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// denominacionExterna
			$this->denominacionExterna->EditAttrs["class"] = "form-control";
			$this->denominacionExterna->EditCustomAttributes = "";
			$this->denominacionExterna->EditValue = ew_HtmlEncode($this->denominacionExterna->AdvancedSearch->SearchValue);
			$this->denominacionExterna->PlaceHolder = ew_RemoveHtml($this->denominacionExterna->FldCaption());

			// denominacionInterna
			$this->denominacionInterna->EditAttrs["class"] = "form-control";
			$this->denominacionInterna->EditCustomAttributes = "";
			$this->denominacionInterna->EditValue = ew_HtmlEncode($this->denominacionInterna->AdvancedSearch->SearchValue);
			$this->denominacionInterna->PlaceHolder = ew_RemoveHtml($this->denominacionInterna->FldCaption());

			// idAlicuotaIva
			$this->idAlicuotaIva->EditAttrs["class"] = "form-control";
			$this->idAlicuotaIva->EditCustomAttributes = "";
			if (trim(strval($this->idAlicuotaIva->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idAlicuotaIva->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// idCategoria
			$this->idCategoria->EditAttrs["class"] = "form-control";
			$this->idCategoria->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idCategoria->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategoria->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categorias-articulos`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idCategoria->EditValue = $arwrk;

			// idSubcateogoria
			$this->idSubcateogoria->EditAttrs["class"] = "form-control";
			$this->idSubcateogoria->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idSubcateogoria->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idSubcateogoria->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `subcategorias-articulos`";
			$sWhereWrk = "";
			$this->idSubcateogoria->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idSubcateogoria->EditValue = $arwrk;

			// idRubro
			$this->idRubro->EditAttrs["class"] = "form-control";
			$this->idRubro->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idRubro->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idRubro->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `rubros`";
			$sWhereWrk = "";
			$this->idRubro->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idRubro->EditValue = $arwrk;

			// idMarca
			$this->idMarca->EditAttrs["class"] = "form-control";
			$this->idMarca->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->idMarca->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idMarca->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `marcas`";
			$sWhereWrk = "";
			$this->idMarca->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->idMarca->EditValue = $arwrk;

			// codigoBarras
			$this->codigoBarras->EditAttrs["class"] = "form-control";
			$this->codigoBarras->EditCustomAttributes = "";
			$this->codigoBarras->EditValue = ew_HtmlEncode($this->codigoBarras->AdvancedSearch->SearchValue);
			$this->codigoBarras->PlaceHolder = ew_RemoveHtml($this->codigoBarras->FldCaption());

			// imagenes
			$this->imagenes->EditAttrs["class"] = "form-control";
			$this->imagenes->EditCustomAttributes = "";
			$this->imagenes->EditValue = ew_HtmlEncode($this->imagenes->AdvancedSearch->SearchValue);
			$this->imagenes->PlaceHolder = ew_RemoveHtml($this->imagenes->FldCaption());

			// idPrecioCompra
			$this->idPrecioCompra->EditAttrs["class"] = "form-control";
			$this->idPrecioCompra->EditCustomAttributes = "";
			if (trim(strval($this->idPrecioCompra->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `precios-compra`";
			$sWhereWrk = "";
			$this->idPrecioCompra->LookupFilters = array();
			$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][3] = ew_FormatDateTime($arwrk[$rowcntwrk][3], 0);
			}
			$this->idPrecioCompra->EditValue = $arwrk;
			$this->idPrecioCompra->EditAttrs["class"] = "form-control";
			$this->idPrecioCompra->EditCustomAttributes = "";
			if (trim(strval($this->idPrecioCompra->AdvancedSearch->SearchValue2)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idPrecioCompra->AdvancedSearch->SearchValue2, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `precios-compra`";
			$sWhereWrk = "";
			$this->idPrecioCompra->LookupFilters = array();
			$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][3] = ew_FormatDateTime($arwrk[$rowcntwrk][3], 0);
			}
			$this->idPrecioCompra->EditValue2 = $arwrk;

			// proveedor
			$this->proveedor->EditAttrs["class"] = "form-control";
			$this->proveedor->EditCustomAttributes = 'data-s2="true"';
			if (trim(strval($this->proveedor->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->proveedor->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `terceros`";
			$sWhereWrk = "";
			$this->proveedor->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->proveedor->EditValue = $arwrk;

			// calculoPrecio
			$this->calculoPrecio->EditAttrs["class"] = "form-control";
			$this->calculoPrecio->EditCustomAttributes = "";
			$this->calculoPrecio->EditValue = $this->calculoPrecio->Options(TRUE);

			// rentabilidad
			$this->rentabilidad->EditAttrs["class"] = "form-control";
			$this->rentabilidad->EditCustomAttributes = "";
			$this->rentabilidad->EditValue = ew_HtmlEncode($this->rentabilidad->AdvancedSearch->SearchValue);
			$this->rentabilidad->PlaceHolder = ew_RemoveHtml($this->rentabilidad->FldCaption());

			// precioVenta
			$this->precioVenta->EditAttrs["class"] = "form-control";
			$this->precioVenta->EditCustomAttributes = "";
			$this->precioVenta->EditValue = ew_HtmlEncode($this->precioVenta->AdvancedSearch->SearchValue);
			$this->precioVenta->PlaceHolder = ew_RemoveHtml($this->precioVenta->FldCaption());

			// tags
			$this->tags->EditAttrs["class"] = "form-control";
			$this->tags->EditCustomAttributes = "";
			$this->tags->EditValue = ew_HtmlEncode($this->tags->AdvancedSearch->SearchValue);
			$this->tags->PlaceHolder = ew_RemoveHtml($this->tags->FldCaption());

			// detalle
			$this->detalle->EditAttrs["class"] = "form-control";
			$this->detalle->EditCustomAttributes = "";
			$this->detalle->EditValue = ew_HtmlEncode($this->detalle->AdvancedSearch->SearchValue);
			$this->detalle->PlaceHolder = ew_RemoveHtml($this->detalle->FldCaption());

			// idUnidadMedidaCompra
			$this->idUnidadMedidaCompra->EditAttrs["class"] = "form-control";
			$this->idUnidadMedidaCompra->EditCustomAttributes = "";

			// idUnidadMedidaVenta
			$this->idUnidadMedidaVenta->EditAttrs["class"] = "form-control";
			$this->idUnidadMedidaVenta->EditCustomAttributes = "";

			// codigosExternos
			$this->codigosExternos->EditAttrs["class"] = "form-control";
			$this->codigosExternos->EditCustomAttributes = "";
			$this->codigosExternos->EditValue = ew_HtmlEncode($this->codigosExternos->AdvancedSearch->SearchValue);
			$this->codigosExternos->PlaceHolder = ew_RemoveHtml($this->codigosExternos->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckNumber($this->rentabilidad->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->rentabilidad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->precioVenta->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->precioVenta->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->denominacionExterna->AdvancedSearch->Load();
		$this->denominacionInterna->AdvancedSearch->Load();
		$this->idAlicuotaIva->AdvancedSearch->Load();
		$this->idCategoria->AdvancedSearch->Load();
		$this->idSubcateogoria->AdvancedSearch->Load();
		$this->idRubro->AdvancedSearch->Load();
		$this->idMarca->AdvancedSearch->Load();
		$this->codigoBarras->AdvancedSearch->Load();
		$this->imagenes->AdvancedSearch->Load();
		$this->idPrecioCompra->AdvancedSearch->Load();
		$this->proveedor->AdvancedSearch->Load();
		$this->calculoPrecio->AdvancedSearch->Load();
		$this->rentabilidad->AdvancedSearch->Load();
		$this->precioVenta->AdvancedSearch->Load();
		$this->tags->AdvancedSearch->Load();
		$this->detalle->AdvancedSearch->Load();
		$this->idUnidadMedidaCompra->AdvancedSearch->Load();
		$this->idUnidadMedidaVenta->AdvancedSearch->Load();
		$this->codigosExternos->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("articuloslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
		case "x_idCategoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categorias-articulos`";
			$sWhereWrk = "";
			$this->idCategoria->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idCategoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idSubcateogoria":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subcategorias-articulos`";
			$sWhereWrk = "";
			$this->idSubcateogoria->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idSubcateogoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idRubro":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
			$sWhereWrk = "";
			$this->idRubro->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idRubro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idMarca":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `marcas`";
			$sWhereWrk = "";
			$this->idMarca->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idMarca, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_idPrecioCompra":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `precioPesos` AS `DispFld`, `denominacion` AS `Disp2Fld`, `ultimaActualizacion` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `precios-compra`";
			$sWhereWrk = "";
			$this->idPrecioCompra->LookupFilters = array();
			$lookuptblfilter = (isset($_GET["id"]) ? "`idArticulo`=".$_GET["id"]:'');
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->idPrecioCompra, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `precioPesos` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_proveedor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `terceros`";
			$sWhereWrk = "";
			$this->proveedor->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`id` = {filter_value}", "t0" => "19", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->proveedor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
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
if (!isset($articulos_search)) $articulos_search = new carticulos_search();

// Page init
$articulos_search->Page_Init();

// Page main
$articulos_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($articulos_search->IsModal) { ?>
var CurrentAdvancedSearchForm = farticulossearch = new ew_Form("farticulossearch", "search");
<?php } else { ?>
var CurrentForm = farticulossearch = new ew_Form("farticulossearch", "search");
<?php } ?>

// Form_CustomValidate event
farticulossearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulossearch.ValidateRequired = true;
<?php } else { ?>
farticulossearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulossearch.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulossearch.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
farticulossearch.Lists["x_idSubcateogoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
farticulossearch.Lists["x_idRubro"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rubros"};
farticulossearch.Lists["x_idMarca"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"marcas"};
farticulossearch.Lists["x_idPrecioCompra"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_precioPesos","x_denominacion","x_ultimaActualizacion",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"precios2Dcompra"};
farticulossearch.Lists["x_proveedor"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
farticulossearch.Lists["x_calculoPrecio"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
farticulossearch.Lists["x_calculoPrecio"].Options = <?php echo json_encode($articulos->calculoPrecio->Options()) ?>;

// Form object for search
// Validate function for search

farticulossearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_rentabilidad");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->rentabilidad->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_precioVenta");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($articulos->precioVenta->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$articulos_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $articulos_search->ShowPageHeader(); ?>
<?php
$articulos_search->ShowMessage();
?>
<form name="farticulossearch" id="farticulossearch" class="<?php echo $articulos_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($articulos_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $articulos_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="articulos">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($articulos_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
	<div id="r_denominacionExterna" class="form-group">
		<label for="x_denominacionExterna" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_denominacionExterna"><?php echo $articulos->denominacionExterna->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_denominacionExterna" id="z_denominacionExterna" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->denominacionExterna->CellAttributes() ?>>
			<span id="el_articulos_denominacionExterna">
<input type="text" data-table="articulos" data-field="x_denominacionExterna" name="x_denominacionExterna" id="x_denominacionExterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionExterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionExterna->EditValue ?>"<?php echo $articulos->denominacionExterna->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
	<div id="r_denominacionInterna" class="form-group">
		<label for="x_denominacionInterna" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_denominacionInterna"><?php echo $articulos->denominacionInterna->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_denominacionInterna" id="z_denominacionInterna" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->denominacionInterna->CellAttributes() ?>>
			<span id="el_articulos_denominacionInterna">
<input type="text" data-table="articulos" data-field="x_denominacionInterna" name="x_denominacionInterna" id="x_denominacionInterna" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($articulos->denominacionInterna->getPlaceHolder()) ?>" value="<?php echo $articulos->denominacionInterna->EditValue ?>"<?php echo $articulos->denominacionInterna->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<div id="r_idAlicuotaIva" class="form-group">
		<label for="x_idAlicuotaIva" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idAlicuotaIva"><?php echo $articulos->idAlicuotaIva->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idAlicuotaIva" id="z_idAlicuotaIva" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idAlicuotaIva->CellAttributes() ?>>
			<span id="el_articulos_idAlicuotaIva">
<select data-table="articulos" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x_idAlicuotaIva" name="x_idAlicuotaIva"<?php echo $articulos->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos->idAlicuotaIva->SelectOptionListHtml("x_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x_idAlicuotaIva" id="s_x_idAlicuotaIva" value="<?php echo $articulos->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
	<div id="r_idCategoria" class="form-group">
		<label for="x_idCategoria" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idCategoria"><?php echo $articulos->idCategoria->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idCategoria" id="z_idCategoria" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idCategoria->CellAttributes() ?>>
			<span id="el_articulos_idCategoria">
<select data-table="articulos" data-field="x_idCategoria" data-value-separator="<?php echo $articulos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x_idCategoria" name="x_idCategoria"<?php echo $articulos->idCategoria->EditAttributes() ?>>
<?php echo $articulos->idCategoria->SelectOptionListHtml("x_idCategoria") ?>
</select>
<input type="hidden" name="s_x_idCategoria" id="s_x_idCategoria" value="<?php echo $articulos->idCategoria->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
	<div id="r_idSubcateogoria" class="form-group">
		<label for="x_idSubcateogoria" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idSubcateogoria"><?php echo $articulos->idSubcateogoria->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idSubcateogoria" id="z_idSubcateogoria" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idSubcateogoria->CellAttributes() ?>>
			<span id="el_articulos_idSubcateogoria">
<select data-table="articulos" data-field="x_idSubcateogoria" data-value-separator="<?php echo $articulos->idSubcateogoria->DisplayValueSeparatorAttribute() ?>" id="x_idSubcateogoria" name="x_idSubcateogoria"<?php echo $articulos->idSubcateogoria->EditAttributes() ?>>
<?php echo $articulos->idSubcateogoria->SelectOptionListHtml("x_idSubcateogoria") ?>
</select>
<input type="hidden" name="s_x_idSubcateogoria" id="s_x_idSubcateogoria" value="<?php echo $articulos->idSubcateogoria->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
	<div id="r_idRubro" class="form-group">
		<label for="x_idRubro" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idRubro"><?php echo $articulos->idRubro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idRubro" id="z_idRubro" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idRubro->CellAttributes() ?>>
			<span id="el_articulos_idRubro">
<select data-table="articulos" data-field="x_idRubro" data-value-separator="<?php echo $articulos->idRubro->DisplayValueSeparatorAttribute() ?>" id="x_idRubro" name="x_idRubro"<?php echo $articulos->idRubro->EditAttributes() ?>>
<?php echo $articulos->idRubro->SelectOptionListHtml("x_idRubro") ?>
</select>
<input type="hidden" name="s_x_idRubro" id="s_x_idRubro" value="<?php echo $articulos->idRubro->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
	<div id="r_idMarca" class="form-group">
		<label for="x_idMarca" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idMarca"><?php echo $articulos->idMarca->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idMarca" id="z_idMarca" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idMarca->CellAttributes() ?>>
			<span id="el_articulos_idMarca">
<select data-table="articulos" data-field="x_idMarca" data-value-separator="<?php echo $articulos->idMarca->DisplayValueSeparatorAttribute() ?>" id="x_idMarca" name="x_idMarca"<?php echo $articulos->idMarca->EditAttributes() ?>>
<?php echo $articulos->idMarca->SelectOptionListHtml("x_idMarca") ?>
</select>
<input type="hidden" name="s_x_idMarca" id="s_x_idMarca" value="<?php echo $articulos->idMarca->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->codigoBarras->Visible) { // codigoBarras ?>
	<div id="r_codigoBarras" class="form-group">
		<label for="x_codigoBarras" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_codigoBarras"><?php echo $articulos->codigoBarras->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigoBarras" id="z_codigoBarras" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->codigoBarras->CellAttributes() ?>>
			<span id="el_articulos_codigoBarras">
<input type="text" data-table="articulos" data-field="x_codigoBarras" name="x_codigoBarras" id="x_codigoBarras" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($articulos->codigoBarras->getPlaceHolder()) ?>" value="<?php echo $articulos->codigoBarras->EditValue ?>"<?php echo $articulos->codigoBarras->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->imagenes->Visible) { // imagenes ?>
	<div id="r_imagenes" class="form-group">
		<label class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_imagenes"><?php echo $articulos->imagenes->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_imagenes" id="z_imagenes" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->imagenes->CellAttributes() ?>>
			<span id="el_articulos_imagenes">
<input type="text" data-table="articulos" data-field="x_imagenes" name="x_imagenes" id="x_imagenes" placeholder="<?php echo ew_HtmlEncode($articulos->imagenes->getPlaceHolder()) ?>" value="<?php echo $articulos->imagenes->EditValue ?>"<?php echo $articulos->imagenes->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
	<div id="r_idPrecioCompra" class="form-group">
		<label for="x_idPrecioCompra" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idPrecioCompra"><?php echo $articulos->idPrecioCompra->FldCaption() ?></span>	
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idPrecioCompra->CellAttributes() ?>>
		<span class="ewSearchOperator"><select name="z_idPrecioCompra" id="z_idPrecioCompra" class="form-control" onchange="ewForms(this).SrchOprChanged(this);"><option value="="<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "=") ? " selected" : "" ?> ><?php echo $Language->Phrase("EQUAL") ?></option><option value="<>"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "<>") ? " selected" : "" ?> ><?php echo $Language->Phrase("<>") ?></option><option value="<"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "<") ? " selected" : "" ?> ><?php echo $Language->Phrase("<") ?></option><option value="<="<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "<=") ? " selected" : "" ?> ><?php echo $Language->Phrase("<=") ?></option><option value=">"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == ">") ? " selected" : "" ?> ><?php echo $Language->Phrase(">") ?></option><option value=">="<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == ">=") ? " selected" : "" ?> ><?php echo $Language->Phrase(">=") ?></option><option value="IS NULL"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "IS NULL") ? " selected" : "" ?> ><?php echo $Language->Phrase("IS NULL") ?></option><option value="IS NOT NULL"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "IS NOT NULL") ? " selected" : "" ?> ><?php echo $Language->Phrase("IS NOT NULL") ?></option><option value="BETWEEN"<?php echo ($articulos->idPrecioCompra->AdvancedSearch->SearchOperator == "BETWEEN") ? " selected" : "" ?> ><?php echo $Language->Phrase("BETWEEN") ?></option></select></span>
			<span id="el_articulos_idPrecioCompra">
<select data-table="articulos" data-field="x_idPrecioCompra" data-value-separator="<?php echo $articulos->idPrecioCompra->DisplayValueSeparatorAttribute() ?>" id="x_idPrecioCompra" name="x_idPrecioCompra"<?php echo $articulos->idPrecioCompra->EditAttributes() ?>>
<?php echo $articulos->idPrecioCompra->SelectOptionListHtml("x_idPrecioCompra") ?>
</select>
<input type="hidden" name="s_x_idPrecioCompra" id="s_x_idPrecioCompra" value="<?php echo $articulos->idPrecioCompra->LookupFilterQuery() ?>">
</span>
			<span class="ewSearchCond btw1_idPrecioCompra" style="display: none">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
			<span id="e2_articulos_idPrecioCompra" class="btw1_idPrecioCompra" style="display: none">
<select data-table="articulos" data-field="x_idPrecioCompra" data-value-separator="<?php echo $articulos->idPrecioCompra->DisplayValueSeparatorAttribute() ?>" id="y_idPrecioCompra" name="y_idPrecioCompra"<?php echo $articulos->idPrecioCompra->EditAttributes() ?>>
<?php echo $articulos->idPrecioCompra->SelectOptionListHtml("y_idPrecioCompra") ?>
</select>
<input type="hidden" name="s_y_idPrecioCompra" id="s_y_idPrecioCompra" value="<?php echo $articulos->idPrecioCompra->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
	<div id="r_proveedor" class="form-group">
		<label for="x_proveedor" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_proveedor"><?php echo $articulos->proveedor->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_proveedor" id="z_proveedor" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->proveedor->CellAttributes() ?>>
			<span id="el_articulos_proveedor">
<select data-table="articulos" data-field="x_proveedor" data-value-separator="<?php echo $articulos->proveedor->DisplayValueSeparatorAttribute() ?>" id="x_proveedor" name="x_proveedor"<?php echo $articulos->proveedor->EditAttributes() ?>>
<?php echo $articulos->proveedor->SelectOptionListHtml("x_proveedor") ?>
</select>
<input type="hidden" name="s_x_proveedor" id="s_x_proveedor" value="<?php echo $articulos->proveedor->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
	<div id="r_calculoPrecio" class="form-group">
		<label for="x_calculoPrecio" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_calculoPrecio"><?php echo $articulos->calculoPrecio->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_calculoPrecio" id="z_calculoPrecio" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->calculoPrecio->CellAttributes() ?>>
			<span id="el_articulos_calculoPrecio">
<select data-table="articulos" data-field="x_calculoPrecio" data-value-separator="<?php echo $articulos->calculoPrecio->DisplayValueSeparatorAttribute() ?>" id="x_calculoPrecio" name="x_calculoPrecio"<?php echo $articulos->calculoPrecio->EditAttributes() ?>>
<?php echo $articulos->calculoPrecio->SelectOptionListHtml("x_calculoPrecio") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
	<div id="r_rentabilidad" class="form-group">
		<label for="x_rentabilidad" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_rentabilidad"><?php echo $articulos->rentabilidad->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_rentabilidad" id="z_rentabilidad" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->rentabilidad->CellAttributes() ?>>
			<span id="el_articulos_rentabilidad">
<input type="text" data-table="articulos" data-field="x_rentabilidad" name="x_rentabilidad" id="x_rentabilidad" size="30" placeholder="<?php echo ew_HtmlEncode($articulos->rentabilidad->getPlaceHolder()) ?>" value="<?php echo $articulos->rentabilidad->EditValue ?>"<?php echo $articulos->rentabilidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
	<div id="r_precioVenta" class="form-group">
		<label for="x_precioVenta" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_precioVenta"><?php echo $articulos->precioVenta->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_precioVenta" id="z_precioVenta" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->precioVenta->CellAttributes() ?>>
			<span id="el_articulos_precioVenta">
<input type="text" data-table="articulos" data-field="x_precioVenta" name="x_precioVenta" id="x_precioVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos->precioVenta->getPlaceHolder()) ?>" value="<?php echo $articulos->precioVenta->EditValue ?>"<?php echo $articulos->precioVenta->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->tags->Visible) { // tags ?>
	<div id="r_tags" class="form-group">
		<label for="x_tags" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_tags"><?php echo $articulos->tags->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tags" id="z_tags" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->tags->CellAttributes() ?>>
			<span id="el_articulos_tags">
<input type="text" data-table="articulos" data-field="x_tags" name="x_tags" id="x_tags" size="35" placeholder="<?php echo ew_HtmlEncode($articulos->tags->getPlaceHolder()) ?>" value="<?php echo $articulos->tags->EditValue ?>"<?php echo $articulos->tags->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->detalle->Visible) { // detalle ?>
	<div id="r_detalle" class="form-group">
		<label class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_detalle"><?php echo $articulos->detalle->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_detalle" id="z_detalle" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->detalle->CellAttributes() ?>>
			<span id="el_articulos_detalle">
<input type="text" data-table="articulos" data-field="x_detalle" name="x_detalle" id="x_detalle" size="35" placeholder="<?php echo ew_HtmlEncode($articulos->detalle->getPlaceHolder()) ?>" value="<?php echo $articulos->detalle->EditValue ?>"<?php echo $articulos->detalle->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idUnidadMedidaCompra->Visible) { // idUnidadMedidaCompra ?>
	<div id="r_idUnidadMedidaCompra" class="form-group">
		<label for="x_idUnidadMedidaCompra" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idUnidadMedidaCompra"><?php echo $articulos->idUnidadMedidaCompra->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idUnidadMedidaCompra" id="z_idUnidadMedidaCompra" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idUnidadMedidaCompra->CellAttributes() ?>>
			<span id="el_articulos_idUnidadMedidaCompra">
<select data-table="articulos" data-field="x_idUnidadMedidaCompra" data-value-separator="<?php echo $articulos->idUnidadMedidaCompra->DisplayValueSeparatorAttribute() ?>" id="x_idUnidadMedidaCompra" name="x_idUnidadMedidaCompra"<?php echo $articulos->idUnidadMedidaCompra->EditAttributes() ?>>
<?php echo $articulos->idUnidadMedidaCompra->SelectOptionListHtml("x_idUnidadMedidaCompra") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->idUnidadMedidaVenta->Visible) { // idUnidadMedidaVenta ?>
	<div id="r_idUnidadMedidaVenta" class="form-group">
		<label for="x_idUnidadMedidaVenta" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_idUnidadMedidaVenta"><?php echo $articulos->idUnidadMedidaVenta->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idUnidadMedidaVenta" id="z_idUnidadMedidaVenta" value="="></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->idUnidadMedidaVenta->CellAttributes() ?>>
			<span id="el_articulos_idUnidadMedidaVenta">
<select data-table="articulos" data-field="x_idUnidadMedidaVenta" data-value-separator="<?php echo $articulos->idUnidadMedidaVenta->DisplayValueSeparatorAttribute() ?>" id="x_idUnidadMedidaVenta" name="x_idUnidadMedidaVenta"<?php echo $articulos->idUnidadMedidaVenta->EditAttributes() ?>>
<?php echo $articulos->idUnidadMedidaVenta->SelectOptionListHtml("x_idUnidadMedidaVenta") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($articulos->codigosExternos->Visible) { // codigosExternos ?>
	<div id="r_codigosExternos" class="form-group">
		<label for="x_codigosExternos" class="<?php echo $articulos_search->SearchLabelClass ?>"><span id="elh_articulos_codigosExternos"><?php echo $articulos->codigosExternos->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_codigosExternos" id="z_codigosExternos" value="LIKE"></p>
		</label>
		<div class="<?php echo $articulos_search->SearchRightColumnClass ?>"><div<?php echo $articulos->codigosExternos->CellAttributes() ?>>
			<span id="el_articulos_codigosExternos">
<input type="text" data-table="articulos" data-field="x_codigosExternos" name="x_codigosExternos" id="x_codigosExternos" size="35" placeholder="<?php echo ew_HtmlEncode($articulos->codigosExternos->getPlaceHolder()) ?>" value="<?php echo $articulos->codigosExternos->EditValue ?>"<?php echo $articulos->codigosExternos->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$articulos_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
farticulossearch.Init();
</script>
<?php
$articulos_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$articulos_search->Page_Terminate();
?>
