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

$pedidosredes_php = NULL; // Initialize page object first

class cpedidosredes_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'pedidosredes.php';

	// Page object name
	var $PageObjName = 'pedidosredes_php';

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
			define("EW_TABLE_NAME", 'pedidosredes.php', TRUE);

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
		$Breadcrumb->Add("custom", "pedidosredes_php", $url, "", "pedidosredes_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pedidosredes_php)) $pedidosredes_php = new cpedidosredes_php();

// Page init
$pedidosredes_php->Page_Init();

// Page main
$pedidosredes_php->Page_Main();

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

<?php include_once("funciones.php") ?>

<?php 

//Obtengo las casas centrales
$casascentrales = obtenerCasasCentrales();

//Obtengo los comprobantes activos
$comprobantes = obtenerComprobantesActivos();

 ?>

<form class="form-inline no-print" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" >

<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="casa-central">Central</label>
  <div class="">
	<select id="casa-central" name="casa-central" class="form-control" data-s2="true" onchange="obtenerComprobantesCentral()">
		<?php 

			foreach ($casascentrales as $key => $value) {

				$seleccionar = FALSE;

				if (isset($_POST["casa-central"])) {
					if (!empty($_POST["casa-central"]) && $_POST["casa-central"] == $value["id"]) {
						$seleccionar = TRUE;
					}
				}

				?>
					
					<option <?php echo $seleccionar?"selected":"" ?>  value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?></option>

				<?php 
			}

		?>
	</select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="comprobante">Comprobante</label>
  <div class="">
	<select id="comprobante" name="comprobante" class="form-control" data-s2="true" onchange="obtenerComprobantesCentral()">
		<?php 

			foreach ($comprobantes as $key => $value) {

				$seleccionar = FALSE;

				if (isset($_POST["comprobante"])) {
					if (!empty($_POST["comprobante"]) && $_POST["comprobante"] == $value["id"]) {
						$seleccionar = TRUE;
					}
				}

				?>
					
					<option <?php echo $seleccionar?"selected":"" ?>  value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?></option>

				<?php 
			}

		?>
	</select>
  </div>
</div>	

<div class="form-group">
  <label class="control-label" for="comprobante">Fecha Desde</label>
  <div>
	<input oninput="obtenerComprobantesCentral()" class="form-control" id="fecha-desde" name="fecha-desde" type="date" value="<?php echo date("Y-m-d") ?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label" for="comprobante">Fecha Hasta</label>
  <div>
	<input oninput="obtenerComprobantesCentral()" class="form-control" id="fecha-hasta" name="fecha-hasta" type="date" value="<?php echo date("Y-m-d") ?>">
  </div>
</div>

<div style="margin: 10px 0" class="col-12">
	<a target="_blank" id="imprimir-comprobantes" class="btn btn-primary" href="">Imprimir Comprobantes</a>
	<a target="_blank" id="imprimir-caratulas" class="btn btn-primary" href="">Imprimir Carátulas</a>
	<a target="_blank" id="imprimir-ruta" class="btn btn-primary" href="">Imprimir Hoja de ruta</a>
	<a target="_blank" id="imprimir-consolidado" class="btn btn-primary" href="">Imprimir Consolidado</a>
	<button type="button" onclick="window.print()" class="btn btn-primary navbar-btn">
		Imprimir este reporte
	</button>	
</div>

</form>


<table class="table table-striped table-condensed" id="comprobantes">
	<thead>
		<tr>
			<th class="no-print" style="width: 60px">
				<input type="checkbox" id="seleccionar-todos" checked onclick="seleccionarTodos(this)">
			</th>
			<th>
				Fecha
			</th>
			<th>
				Sucursal
			</th>
			<th>
				Comprobante
			</th>
			<th>
				Total
			</th>
		</tr>
	</thead>
	<tbody>
		
	</tbody>

	<tfoot>
	</tfoot>
	
</table>

<script>
	
	$(document).ready(function(){
		obtenerComprobantesCentral();
	})

	function obtenerComprobantesCentral(){

		var central = $("#casa-central").val();
		var comprobante = $("#comprobante").val();
		var fechadesde = $("#fecha-desde").val();
		var fechahasta = $("#fecha-hasta").val();	

		var accion = "obtener-comprobantes-central";

		$.ajax({
			url: "api.php",
			type: "get",
			crossDomain: true,
			data: {
				accion : accion,
				idcentral : central,
				idcomprobante : comprobante,
				fechadesde : fechadesde,
				fechahasta : fechahasta
			},
			dataType: "json",
			success: function (response) {

			  	$("#comprobantes tbody").empty();						

				var html = '';

				var totalImporteTotal = 0
				var totalImporteIva = 0
				var totalImpoerteNeto = 0

				$.each(response.exito, function( index, value ) {

					html += '<tr data-importe-neto='+value.importeNeto+'>'
					html += '<td class="no-print"><input onchange="cambiaCheck()" class="seleccionar" type="checkbox" checked data-id="'+value.idmovimiento+'" /></td>'
					html += '<td>'+value.fecha+'</td>'
					html += '<td>'+value.tercero+'</td>'
					html += '<td>'+value.comprobante + ' ' +value.ptoVenta+'-'+value.nroComprobante+'</td>'
					html += '<td>'+value.importeNeto+'</td>'
					html += '</tr>'

					totalImporteTotal += parseFloat(value.importeTotal)
					totalImporteIva += parseFloat(value.importeIva)
					totalImpoerteNeto += parseFloat(value.importeNeto)					

				});

				
				$("#comprobantes tbody").html(html)
				
				html = ''
				html += '<tr style="font-weight: bold">'
				html += '<td class="no-print"></td>'
				html += '<td colspan=3 class="text-right">TOTAL</td>'
				html += '<td id="total-importe-neto">'+totalImpoerteNeto.toFixed(2)+'</td>'
				html += '</tr>'

				$("#comprobantes tfoot").html(html)

				$("#seleccionar-todos").prop('checked', true);
				cambiaCheck();

			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	}

function cambiaCheck(){

	var ids = [];

	var totalImporteNeto = 0;

	$( ".seleccionar" ).each(function( index ) {
		if ($(this).is(":checked")) {
			ids.push($(this).data('id'));
			$("#comprobantes tbody tr").eq(index).removeClass('no-print');
			totalImporteNeto += parseFloat($("#comprobantes tbody tr").eq(index).data('importe-neto'));
		}else{
			$("#comprobantes tbody tr").eq(index).addClass('no-print');
		}
	});

	$("#total-importe-neto").html(totalImporteNeto.toFixed(2));

	if (ids.length > 0) {

		var idsstring = ids.join(',');

		$("#imprimir-comprobantes").attr('href', 'imprimirmovimiento.php?id='+idsstring).show();
		$("#imprimir-caratulas").attr('href', 'imprimircaratulas.php?id='+idsstring).show();
		$("#imprimir-ruta").attr('href', 'imprimirruta.php?id='+idsstring).show();
		$("#imprimir-consolidado").attr('href', 'imprimirconsolidado.php?id='+idsstring).show();

	}else{
		$("#imprimir-comprobantes").hide();
		$("#imprimir-caratulas").hide();
		$("#imprimir-ruta").hide();
		$("#imprimir-consolidado").hide();		
	}


}	

function seleccionarTodos(elemento){

	if ($(elemento).is(":checked")) {
		$(".seleccionar").prop('checked', true);
	}else{
		$(".seleccionar").prop('checked', false);
	};
	cambiaCheck();

}

</script>

<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$pedidosredes_php->Page_Terminate();
?>
