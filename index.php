<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'articulos'))
		$this->Page_Terminate("articuloslist.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'niveles'))
			$this->Page_Terminate("niveleslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'permisos'))
			$this->Page_Terminate("permisoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'usuarios'))
			$this->Page_Terminate("usuarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'localidades'))
			$this->Page_Terminate("localidadeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'paises'))
			$this->Page_Terminate("paiseslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'partidos'))
			$this->Page_Terminate("partidoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'provincias'))
			$this->Page_Terminate("provinciaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'condiciones-iva'))
			$this->Page_Terminate("condiciones2Divalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'terceros'))
			$this->Page_Terminate("terceroslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'terceros-medios-contactos'))
			$this->Page_Terminate("terceros2Dmedios2Dcontactoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipos-contactos'))
			$this->Page_Terminate("tipos2Dcontactoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipos-documentos'))
			$this->Page_Terminate("tipos2Ddocumentoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'tipos-terceros'))
			$this->Page_Terminate("tipos2Dterceroslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'api.php'))
			$this->Page_Terminate("api.php");
		if ($Security->AllowList(CurrentProjectID() . 'terceroshorarios.php'))
			$this->Page_Terminate("terceroshorarios.php");
		if ($Security->AllowList(CurrentProjectID() . 'terceros-horarios'))
			$this->Page_Terminate("terceros2Dhorarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'funciones.php'))
			$this->Page_Terminate("funciones.php");
		if ($Security->AllowList(CurrentProjectID() . 'feriados'))
			$this->Page_Terminate("feriadoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'terceros-contactos'))
			$this->Page_Terminate("terceros2Dcontactoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'articulos-proveedores'))
			$this->Page_Terminate("articulos2Dproveedoreslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'articulos-terceros-descuentos'))
			$this->Page_Terminate("articulos2Dterceros2Ddescuentoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'categorias-terceros-descuentos'))
			$this->Page_Terminate("categorias2Dterceros2Ddescuentoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'lista-precios'))
			$this->Page_Terminate("lista2Dprecioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'categorias-articulos'))
			$this->Page_Terminate("categorias2Darticuloslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'subcategorias-articulos'))
			$this->Page_Terminate("subcategorias2Darticuloslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'alicuotas-iva'))
			$this->Page_Terminate("alicuotas2Divalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'monedas'))
			$this->Page_Terminate("monedaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'precios-compra'))
			$this->Page_Terminate("precios2Dcompralist.php");
		if ($Security->AllowList(CurrentProjectID() . 'subcategoria-terceros-descuentos'))
			$this->Page_Terminate("subcategoria2Dterceros2Ddescuentoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'comprobantes'))
			$this->Page_Terminate("comprobanteslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'movimientos'))
			$this->Page_Terminate("movimientoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'movimientos-detalle'))
			$this->Page_Terminate("movimientos2Ddetallelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'unidades-medida'))
			$this->Page_Terminate("unidades2Dmedidalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'movimientosaddcustom.php'))
			$this->Page_Terminate("movimientosaddcustom.php");
		if ($Security->AllowList(CurrentProjectID() . 'connect.php'))
			$this->Page_Terminate("connect.php");
		if ($Security->AllowList(CurrentProjectID() . 'configuracion'))
			$this->Page_Terminate("configuracionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'consultas.php'))
			$this->Page_Terminate("consultas.php");
		if ($Security->AllowList(CurrentProjectID() . 'movimientoseditcustom.php'))
			$this->Page_Terminate("movimientoseditcustom.php");
		if ($Security->AllowList(CurrentProjectID() . 'medios-pagos'))
			$this->Page_Terminate("medios2Dpagoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'recibos-pagos'))
			$this->Page_Terminate("recibos2Dpagoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'recibosaddcustom.php'))
			$this->Page_Terminate("recibosaddcustom.php");
		if ($Security->AllowList(CurrentProjectID() . 'reciboseditcustom.php'))
			$this->Page_Terminate("reciboseditcustom.php");
		if ($Security->AllowList(CurrentProjectID() . 'articulos-stock'))
			$this->Page_Terminate("articulos2Dstocklist.php");
		if ($Security->AllowList(CurrentProjectID() . 'impresionmovimiento.php'))
			$this->Page_Terminate("impresionmovimiento.php");
		if ($Security->AllowList(CurrentProjectID() . 'impresionremito.php'))
			$this->Page_Terminate("impresionremito.php");
		if ($Security->AllowList(CurrentProjectID() . 'impresionfacturaiva.php'))
			$this->Page_Terminate("impresionfacturaiva.php");
		if ($Security->AllowList(CurrentProjectID() . 'impresionfactura.php'))
			$this->Page_Terminate("impresionfactura.php");
		if ($Security->AllowList(CurrentProjectID() . 'estadoscuentas.php'))
			$this->Page_Terminate("estadoscuentas.php");
		if ($Security->AllowList(CurrentProjectID() . 'marcas'))
			$this->Page_Terminate("marcaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'rubros'))
			$this->Page_Terminate("rubroslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'actualizacionconarchivo.php'))
			$this->Page_Terminate("actualizacionconarchivo.php");
		if ($Security->AllowList(CurrentProjectID() . 'actualizacionconfiltro.php'))
			$this->Page_Terminate("actualizacionconfiltro.php");
		if ($Security->AllowList(CurrentProjectID() . 'comprobantes-numeracion'))
			$this->Page_Terminate("comprobantes2Dnumeracionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'observaciones-cheques'))
			$this->Page_Terminate("observaciones2Dchequeslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'comprobantes-bloqueados-condiciones-iva'))
			$this->Page_Terminate("comprobantes2Dbloqueados2Dcondiciones2Divalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'accesos-directos'))
			$this->Page_Terminate("accesos2Ddirectoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'sucursales'))
			$this->Page_Terminate("sucursaleslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'modulocotizaciones.php'))
			$this->Page_Terminate("modulocotizaciones.php");
		if ($Security->AllowList(CurrentProjectID() . 'saldos.php'))
			$this->Page_Terminate("saldos.php");
		if ($Security->AllowList(CurrentProjectID() . 'movimientospendientes.php'))
			$this->Page_Terminate("movimientospendientes.php");
		if ($Security->AllowList(CurrentProjectID() . 'modulocotizacion2.php'))
			$this->Page_Terminate("modulocotizacion2.php");
		if ($Security->AllowList(CurrentProjectID() . 'cotizaciones'))
			$this->Page_Terminate("cotizacioneslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'cotizaciones_detalles'))
			$this->Page_Terminate("cotizaciones_detalleslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pedidosredes.php'))
			$this->Page_Terminate("pedidosredes.php");
		if ($Security->AllowList(CurrentProjectID() . 'generacionmasivacomprobantes.php'))
			$this->Page_Terminate("generacionmasivacomprobantes.php");
		if ($Security->AllowList(CurrentProjectID() . 'descuentosasociados'))
			$this->Page_Terminate("descuentosasociadoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'articulospendientes.php'))
			$this->Page_Terminate("articulospendientes.php");
		if ($Security->AllowList(CurrentProjectID() . 'panelcontrol.php'))
			$this->Page_Terminate("panelcontrol.php");
		if ($Security->AllowList(CurrentProjectID() . 'listaprecios.php'))
			$this->Page_Terminate("listaprecios.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage(ew_DeniedMsg() . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
