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

$listaprecios_php = NULL; // Initialize page object first

class clistaprecios_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'listaprecios.php';

	// Page object name
	var $PageObjName = 'listaprecios_php';

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
			define("EW_TABLE_NAME", 'listaprecios.php', TRUE);

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
		global $gbOldSkipHeaderFooter, $gbSkipHeaderFooter;
		$gbOldSkipHeaderFooter = $gbSkipHeaderFooter;
		$gbSkipHeaderFooter = TRUE;

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
		global $gbOldSkipHeaderFooter, $gbSkipHeaderFooter;
		$gbSkipHeaderFooter = $gbOldSkipHeaderFooter;

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
		$Breadcrumb->Add("custom", "listaprecios_php", $url, "", "listaprecios_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($listaprecios_php)) $listaprecios_php = new clistaprecios_php();

// Page init
$listaprecios_php->Page_Init();

// Page main
$listaprecios_php->Page_Main();

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

include_once("funciones.php");

//Obtengo el tercero
$sql = "SELECT
terceros.id AS idTercero,
terceros.denominacion AS denominacionTercero,
terceros.dtoCliente AS dtoCliente,
terceros.condicionIva AS condicionIVA,
`lista-precios`.descuento AS dtoLista
FROM terceros
LEFT JOIN `lista-precios` ON terceros.idListaPrecios = `lista-precios`.id
WHERE terceros.id='".$_REQUEST["idTercero"]."'
LIMIT 1";

$rs = ew_LoadRecordset($sql);
$tercero = $rs->GetRows();

$dtoTercero = $tercero[0]["dtoCliente"];
$dtoLista = $tercero[0]["dtoLista"];
$condicionIVA = $tercero[0]["condicionIVA"];
$denominacionTercero = $tercero[0]["denominacionTercero"];

?>

<div class="container no-print" style="margin-top: 20px; margin-bottom:20px">

<input type="hidden" id="idTercero" value="<?php echo $_REQUEST["idTercero"]; ?>">
<input type="hidden" id="dtoTercero" value="<?php echo $dtoLista ?>">
<input type="hidden" id="dtoLista" value="<?php echo $dtoLista ?>">
<input type="hidden" id="condicionIVA" value="<?php echo $condicionIVA ?>">
<input type="hidden" id="denominacionTercero" value="<?php echo $denominacionTercero ?>">

<div class="row">
		<div class="col-12">
			<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
				enctype="multipart/form-data">

				<!-- Select Basic -->
				<div class="form-group" style="width: 300px">
					<label class="control-label" for="proveedor">Proveedor</label>
					<div class="">
						<select multiple id="proveedor" name="proveedor" class="form-control" data-s2="true"
							onchange="filtrosDependientes(this)">
							<?php 

						foreach (obtenerProveedores() as $key => $value) {

							$seleccionar = FALSE;

							if (isset($_POST["proveedor"])) {
								if (!empty($_POST["proveedor"]) && $_POST["proveedor"] == $value["id"]) {
									$seleccionar = TRUE;
								}
							}

							?>

							<option <?php echo $seleccionar?"selected":"" ?> value="<?php echo $value["id"] ?>">
								<?php echo $value["denominacion"] ?></option>

							<?php 
							}

							?>
						</select>
					</div>
				</div>

				<!-- Select Basic -->
				<div class="form-group" style="width: 300px">
					<label class="control-label" for="categoria">Categoria</label>
					<div class="">
						<select multiple id="categoria" name="categoria" class="form-control" data-s2="true"
							onchange="filtrosDependientes(this)">

						</select>
					</div>
				</div>

				<!-- Select Basic -->
				<div class="form-group" style="width: 300px">
					<label class="control-label" for="subcategoria">Subcategoría</label>
					<div class="">
						<select multiple id="subcategoria" name="subcategoria" class="form-control" data-s2="true"
							onchange="filtrosDependientes(this)">

						</select>
					</div>
				</div>

				<!-- Select Basic -->
				<div class="form-group" style="width: 300px">
					<label class="control-label" for="rubro">Rubros</label>
					<div class="">
						<select multiple id="rubro" name="rubro" class="form-control" data-s2="true"
							onchange="filtrosDependientes(this)">

						</select>
					</div>
				</div>


				<!-- Select Basic -->
				<div class="form-group" style="width: 300px">
					<label class="control-label" for="marca">Marcas</label>
					<div class="">
						<select multiple id="marca" name="marca" class="form-control" data-s2="true"
							onchange="filtrosDependientes(this)">

						</select>
					</div>
				</div>

			</form>
		</div>
	</div>	
</div>

<div class="container">
	<table width="100%">
		<thead>
			<tr>
				<th>
					Lista de precios Librería Valle - Cliente: <?php echo $denominacionTercero;?></br>
					<span class="glyphicon glyphicon-phone"></span> +54 9 11 64907771 - <span
						class="glyphicon glyphicon-envelope"></span> info@libreriavalle.com.ar - <span
						class="glyphicon glyphicon-globe"></span> libreriavalle.com.ar </br>

				</th>
				<th class="text-right">
					<img src="img/imagotipo-rectangular-color.png" style="width:3cm">
				</th>
			</tr>
		</thead>
		<tbody id="lista-precios">

		</tbody>
		<tfoot>
			<tr>
				<th>
					<?php
			if($condicionIVA == 2){
				?>
					Los precios son netos más iva
					<?php
			}
			?>
				</th>
				<th class="text-right">
					<?php
					//Imprimo la fecha actual
					echo date("d/m/Y");
					?>
				</th>
			</tr>
		</tfoot>
	</table>
</div>

<script>
	$(document).ready(function () {
		$("#proveedor").trigger("change");
	})

	function filtrosDependientes(elemento) {

		switch ($(elemento).attr("id")) {

			case "proveedor":

				var accion = "obtener-categorias-proveedores";
				var proveedores = $(elemento).val();

				$.ajax({
					url: "api.php",
					type: "get",
					crossDomain: true,
					data: {
						accion: accion,
						proveedores: proveedores
					},
					dataType: "json",
					success: function (response) {

						$("#categoria").empty();
						$("#subcategoria").empty();
						$("#rubro").empty();
						$("#marca").empty();

						var html = '';

						$.each(response.exito, function (index, value) {
							html += '<option value="' + value.id + '">' + value.denominacion +
								'</option>'
						});

						$("#categoria").append(html).select2({
							width: '100%'
						}).trigger("change");

					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				});

				break;

			case "categoria":

				var accion = "obtener-subcategorias-categorias";
				var proveedores = $("#proveedor").val();
				var categorias = $(elemento).val();

				$.ajax({
					url: "api.php",
					type: "get",
					crossDomain: true,
					data: {
						accion: accion,
						proveedores: proveedores,
						categorias: categorias
					},
					dataType: "json",
					success: function (response) {

						$("#subcategoria").empty();
						$("#rubro").empty();
						$("#marca").empty();

						var html = '';

						$.each(response.exito, function (index, value) {
							html += '<option value="' + value.id + '">' + value.denominacion +
								'</option>'
						});

						$("#subcategoria").append(html).select2({
							width: '100%'
						}).trigger("change");

					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				});

				break;

			case "subcategoria":

				var accion = "obtener-rubros-subcategorias";
				var proveedores = $("#proveedor").val();
				var categorias = $("#categoria").val();
				var subcategorias = $(elemento).val();

				$.ajax({
					url: "api.php",
					type: "get",
					crossDomain: true,
					data: {
						accion: accion,
						proveedores: proveedores,
						categorias: categorias,
						subcategorias: subcategorias
					},
					dataType: "json",
					success: function (response) {

						$("#rubro").empty();
						$("#marca").empty();

						var html = '';

						$.each(response.exito, function (index, value) {
							html += '<option value="' + value.id + '">' + value.denominacion +
								'</option>'
						});

						$("#rubro").append(html).select2({
							width: '100%'
						}).trigger("change");

					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				});

				break;

			case "rubro":

				var accion = "obtener-marcas-rubros";
				var proveedores = $("#proveedor").val();
				var categorias = $("#categoria").val();
				var subcategorias = $("#subcategoria").val();
				var rubros = $(elemento).val();

				$.ajax({
					url: "api.php",
					type: "get",
					crossDomain: true,
					data: {
						accion: accion,
						proveedores: proveedores,
						categorias: categorias,
						subcategorias: subcategorias,
						rubros: rubros
					},
					dataType: "json",
					success: function (response) {

						$("#marca").empty();

						var html = '';

						$.each(response.exito, function (index, value) {
							html += '<option value="' + value.id + '">' + value.denominacion +
								'</option>'
						});

						$("#marca").append(html).select2({
							width: '100%'
						}).trigger("change");

					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				});

				break;

			case "marca":

				obtenerListaPrecios();

				break;

		}
	}

	function obtenerListaPrecios() {

		var accion = "obtener-lista-precios";
		var proveedores = $("#proveedor").val();
		var categorias = $("#categoria").val();
		var subcategorias = $("#subcategoria").val();
		var rubros = $("#rubro").val();
		var marcas = $("#marca").val();
		var idTercero = $("#idTercero").val();
		var dtoTercero = $("#dtoTercero").val();
		var dtoLista = $("#dtoLista").val();
		var condicionIva = $("#condicionIva").val();

		$.ajax({
			url: "api.php",
			type: "get",
			crossDomain: true,
			data: {
				accion: accion,
				proveedores: proveedores,
				categorias: categorias,
				subcategorias: subcategorias,
				rubros: rubros,
				marcas: marcas,
				idTercero: idTercero,
				dtoTercero: dtoTercero,
				dtoLista: dtoLista
			},
			dataType: "json",
			success: function (response) {
				
				$("#lista-precios").empty();

				var html = '';
				html += '<tr>';
				html += '<td colspan="2">';
				html += '<table>';
				html += '<tbody>';

				var categoria = '';
				var subcategoria = '';
				var rubro = '';

				$.each(response.exito, function (index, value) {
					
					var bruto = value.precioCliente;
					var iva = parseFloat(value.precioCliente) * parseFloat(value.iva) / 100;
					var neto = parseFloat(value.precioCliente) + parseFloat(iva);

					if(categoria != value.denominacionCategoria){
						html += '</tbody>';
						html += '</table>';
						
						categoria = value.denominacionCategoria;
						//muestro la categoria en mayuscula
						html += '<strong><h3 style="width:100%; text-align:center;">'+ categoria.toUpperCase() +'</h3></strong>';
						html += '<table>';
						html += '<tbody>';
					}

					if(subcategoria != value.denominacionSubcategoria){
						html += '</tbody>';
						html += '</table>';
						
						subcategoria = value.denominacionSubcategoria;
						//muestro la subcategoria en mayuscula
						html += '<strong><h4 style="width:100%; text-align:center;">'+ subcategoria.toUpperCase() +'</h4></strong>';
						html += '<table>';
						html += '<tbody>';
					}

					if(rubro != value.denominacionRubro){
						html += '</tbody>';
						html += '</table>';
						
						rubro = value.denominacionRubro;
						//muestro la rubro en mayuscula
						html += '<strong><h5>- '+ rubro.toUpperCase() +' -</h5></strong>';
						html += '<table>';
						html += '<tbody>';

						html += '<table class="table table-striped table-condensed">';
						html += '<thead>';
						html += '<tr>';
						html += '<th style="width:2cm;">Código</th>';
						html += '<th>Descripción</th>';
						html += '<th colspan="2" class="text-right" style="width:2cm;">Precio</th>';
						html += '</tr>';
						html += '</thead>';
						html += '<tbody>';
					}

					html += '<tr>';
					//Elimino los primeros 5 caracteres del codigo
					html += '<td>' + value.idArticulo.substring(5) + '</td>';
					html += '<td>' + value.denominacionExterna + '</td>';
					if(condicionIva == 2){ //Es responsable inscripto
						html += '<td style="width:0.2cm;">$</td>';
						html += '<td style="width:1.8cm;" class="text-right">'+ parseFloat(bruto).toFixed(2) +'</td>';
					}else{
						html += '<td style="width:0.2cm;">$</td>';
						html += '<td style="width:1.8cm;" class="text-right">'+ parseFloat(neto).toFixed(2) +'</td>';
					}

					html += '</tr>';

				});

				html += '</tbody>';
				html += '</table>';
				html += '</td>';
				html += '</tr>';

				$("#lista-precios").append(html);

				

			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	}
</script>



<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$listaprecios_php->Page_Terminate();
?>