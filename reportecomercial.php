<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$reportecomercial_php = NULL; // Initialize page object first

class creportecomercial_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'reportecomercial.php';

	// Page object name
	var $PageObjName = 'reportecomercial_php';

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
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'reportecomercial.php', TRUE);

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
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
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

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "reportecomercial_php", $url, "", "reportecomercial_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($reportecomercial_php)) $reportecomercial_php = new creportecomercial_php();

// Page init
$reportecomercial_php->Page_Init();

// Page main
$reportecomercial_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>

<?php 

include_once("connect.php");

$rankingarticulos = array();
$contactos = array();
$articulospendientes = array();

if (isset($_REQUEST["idtercero"])) {
	if (!empty($_REQUEST["idtercero"])) {


		$sql="SELECT articulos.denominacionExterna,
		  articulos.denominacionInterna,
		  articulos.rentabilidad,
		  Sum(`movimientos-detalle`.cant) AS cantidadcomprada,
		  Count(`movimientos-detalle`.id) AS vecescompradas,
		  movimientos.idTercero
		FROM articulos
		  INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.codProducto =
		    articulos.id
		  INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
		WHERE  movimientos.idTercero = '".$_REQUEST["idtercero"]."' AND movimientos.idComprobante IN (1,6,11)
		GROUP BY articulos.id
   ORDER BY vecescompradas DESC, cantidadcomprada DESC, rentabilidad DESC";

	$resultado = mysqli_query($mysqli, $sql);

	if ($resultado) {
		if (mysqli_num_rows($resultado)>0) {
			for ($i=0; $i < mysqli_num_rows($resultado); $i++) { 
				$registro = mysqli_fetch_assoc($resultado);
				$registro = array_map('utf8_encode', $registro);
				array_push($rankingarticulos, $registro);

			}
		}
	}


		$sql="SELECT
		SUM(`movimientos-detalle`.cant) - SUM(`movimientos-detalle`.cantidadImportada) AS cantidad,
		articulos.denominacionExterna,
		  articulos.denominacionInterna,
		  articulos.rentabilidad,
			movimientos.fecha,
		  movimientos.idTercero
		FROM articulos
		  INNER JOIN `movimientos-detalle` ON `movimientos-detalle`.codProducto =
		    articulos.id
		  INNER JOIN movimientos ON `movimientos-detalle`.idMovimientos = movimientos.id
		WHERE  movimientos.idTercero = '".$_REQUEST["idtercero"]."'
		AND movimientos.idComprobante IN (66)
		AND `movimientos-detalle`.cantidadImportada < `movimientos-detalle`.cant 
		GROUP BY articulos.id
   ORDER BY fecha";

	$resultado = mysqli_query($mysqli, $sql);

	if ($resultado) {
		if (mysqli_num_rows($resultado)>0) {
			for ($i=0; $i < mysqli_num_rows($resultado); $i++) { 
				$registro = mysqli_fetch_assoc($resultado);
				$registro = array_map('utf8_encode', $registro);
				array_push($articulospendientes, $registro);

			}
		}
	}

		$sql="SELECT `terceros-medios-contactos`.*, `tipos-contactos`.denominacion AS tipocontacto FROM `terceros-medios-contactos`
		INNER JOIN `tipos-contactos`
		ON `terceros-medios-contactos`.idTipoContacto = `tipos-contactos`.id
		WHERE idTercero = '".$_REQUEST["idtercero"]."'";

	$resultado = mysqli_query($mysqli, $sql);

	if ($resultado) {
		if (mysqli_num_rows($resultado)>0) {
			for ($i=0; $i < mysqli_num_rows($resultado); $i++) { 
				$registro = mysqli_fetch_assoc($resultado);
				$registro = array_map('utf8_encode', $registro);
				array_push($contactos, $registro);

			}
		}
	}

	}
}

?>

<div class="row">
	<div class="col-md-6" style="padding:15px">
		<h3>Contactos</h3>
		<div class="col-sm-12 ewGrid ">
			<table class="table ewTable">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Tipo de Contacto</th>
						<th>Contacto</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($contactos as $key => $value) {
							?>
							<tr>
								<td><?php echo $value["denominacion"] ?></td>
								<td><?php echo $value["tipocontacto"] ?></td>
								<td><?php echo $value["contacto"] ?></td>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>		
	</div>
</div>

<div class="row">
	<div class="col-md-6" style="padding:15px">
		<h3>Ranking de artículos</h3>
		<div class="col-sm-12 ewGrid ">
			<table class="table ewTable">
				<thead>
					<tr>
						<th>Denominación Externa</th>
						<th>Denominación Interna</th>
						<th>Veces pedido</th>
						<th>Cantidad pedida</th>
						<th>Rentabilidad</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($rankingarticulos as $key => $value) {
							?>
							<tr>
								<td><?php echo $value["denominacionExterna"] ?></td>
								<td><?php echo $value["denominacionInterna"] ?></td>
								<td><?php echo $value["vecescompradas"] ?></td>
								<td><?php echo $value["cantidadcomprada"] ?></td>
								<td><?php echo $value["rentabilidad"] ?></td>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
			
		</div>

	</div>

	<div class="col-md-6" style="padding:15px">
		<h3>Artículos Pendientes de entrega</h3>
		<div class="col-sm-12 ewGrid ">
			<table class="table ewTable">
				<thead>
					<tr>
						<th>Cantidad</th>
						<th>Denominación Externa</th>
						<th>Denominación Interna</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($articulospendientes as $key => $value) {
							?>
							<tr>
								<td><?php echo $value["cantidad"] ?></td>
								<td><?php echo $value["denominacionExterna"] ?></td>
								<td><?php echo $value["denominacionInterna"] ?></td>
								<td><?php echo $value["fecha"] ?></td>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
			
		</div>

	</div>
</div>

<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$reportecomercial_php->Page_Terminate();
?>
