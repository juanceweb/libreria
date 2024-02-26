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

$actualizacionconfiltro_php = NULL; // Initialize page object first

class cactualizacionconfiltro_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'actualizacionconfiltro.php';

	// Page object name
	var $PageObjName = 'actualizacionconfiltro_php';

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
			define("EW_TABLE_NAME", 'actualizacionconfiltro.php', TRUE);

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
		$Breadcrumb->Add("custom", "actualizacionconfiltro_php", $url, "", "actualizacionconfiltro_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($actualizacionconfiltro_php)) $actualizacionconfiltro_php = new cactualizacionconfiltro_php();

// Page init
$actualizacionconfiltro_php->Page_Init();

// Page main
$actualizacionconfiltro_php->Page_Main();

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

<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" >

<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="proveedor">Proveedor</label>
  <div class="">
	<select multiple id="proveedor" name="proveedor" class="form-control" data-s2="true" onchange="filtrosDependientes(this)">
		<?php 

			foreach (obtenerProveedores() as $key => $value) {

				$seleccionar = FALSE;

				if (isset($_POST["proveedor"])) {
					if (!empty($_POST["proveedor"]) && $_POST["proveedor"] == $value["id"]) {
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
  <label class="control-label" for="categoria">Categoria</label>
  <div class="">
	<select multiple id="categoria" name="categoria" class="form-control" data-s2="true" onchange="filtrosDependientes(this)">

	</select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="subcategoria">Subcategoría</label>
  <div class="">
	<select multiple id="subcategoria" name="subcategoria" class="form-control" data-s2="true" onchange="filtrosDependientes(this)">

	</select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="rubro">Rubros</label>
  <div class="">
	<select multiple id="rubro" name="rubro" class="form-control" data-s2="true" onchange="filtrosDependientes(this)">

	</select>
  </div>
</div>


<!-- Select Basic -->
<div class="form-group" style="width: 300px">
  <label class="control-label" for="marca">Marcas</label>
  <div class="">
	<select multiple id="marca" name="marca" class="form-control" data-s2="true" onchange="filtrosDependientes(this)">

	</select>
  </div>
</div>

</form>


<div class="row" style="margin-top: 10px">
	<div class="col-xs-4" style="max-width: 300px">

		<label for="basic-url">Modificación de Costo</label>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" class="form-control" id="modificacion-costo" aria-describedby="addon-costo">
		</div>

		<label for="basic-url">Modificación de Margen</label>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-margen">%</span>
		  <input type="number" class="form-control" id="modificacion-margen" aria-describedby="addon-margen">
		</div>

		<a style="width: 200px;margin-top:10px" href="javascript:void(0)" onclick="actualizacionMasiva()" class="btn btn-primary">Aplicar</a>						
		
	</div>

	<div class="col-xs-4" style="max-width: 300px">

		<label for="dtoproveedor1">Descuentos del Proveedor</label>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoproveedor1" placeholder="dto-1" aria-describedby="addon-costo">
		</div>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoproveedor2" placeholder="dto-2" aria-describedby="addon-costo">
		</div>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoproveedor3" placeholder="dto-3" aria-describedby="addon-costo">
		</div>				

	</div>

	<div class="col-xs-4" style="max-width: 300px">

		<label for="dto-articulo-1">Descuentos de los Artículos</label>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoarticulo1" placeholder="dto-1" aria-describedby="addon-costo">
		</div>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoarticulo2" placeholder="dto-2" aria-describedby="addon-costo">
		</div>
		<div class="input-group">
		  <span class="input-group-addon" id="addon-costo">%</span>
		  <input type="number" onkeyup="cambiaDescuento(this)" class="form-control" id="dtoarticulo3" placeholder="dto-3" aria-describedby="addon-costo">
		</div>				

	</div>	
</div>	

<div class="container">
	<div class="row">
		<div class="col-12">
			<div style="margin: 10px 0px" class="text-center alert alert-info" role="alert" id="mensajes"></div>
		</div>
	</div>
</div>

<table id="articulos" class="table table-striped table-condensed">
	<thead>
		<tr>
			<th style="width: 60px">
				<input type="checkbox" checked onclick="seleccionarTodosArticulos(this)">
			</th>
			<th style="width: 350px">
				Denominación
			</th>
			<th>
				Proveedor
			</th>
			<th>
				Lista
			</th>
			<th>
				Nuevo Lista
			</th>
			<th>
				Descuentos
			</th>
			<th>
				Actual ($)
			</th>
			<th>
				Costo Final ($)
			</th>													
			<th>
				Margen
			</th>
			<th>
				Precio Venta
			</th>
			<th>
				Precio Venta Final
			</th>
																																														
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>


<div class="modal fade" id="modal-detalle">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Detalle</h4>
			</div>
			<div class="modal-body">
				<table class="table">
					<tr>
						<td>Categoría</td>
						<td id="modal-categoria"></td>
					</tr>
					<tr>
						<td>Sub-categoría</td>
						<td id="modal-subcategoria"></td>
					</tr>
					<tr>
						<td>Rubro</td>
						<td id="modal-rubro"></td>
					</tr>
					<tr>
						<td>Marca</td>
						<td id="modal-marca"></td>
					</tr>										
				</table>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

	$("#modificacion-costo").keyup(function(){
		actualizarCosto();
	})

	$("#modificacion-margen").keyup(function(){
		actualizarCosto();
	})

	function cambiaDescuento(elemento){

		var id = $(elemento).attr("id");

		if ($(elemento).val() != "") {

			$("#articulos tbody tr").each(function( index ) {

				if ($(this).find(".seleccionar-articulo").is(":checked")) {
					$(this).find($("."+id)).html($(elemento).val()).addClass("cambio");
				}

			});

		}else{

			$("#articulos tbody tr").each(function( index ) {

				if ($(this).find(".seleccionar-articulo").is(":checked")) {
					$(this).find($("."+id)).html($("."+id).data("valor-original")).removeClass("cambio");
				}

			});

		}

		actualizarCosto();
	}

	function actualizacionMasiva(){

		var costos = [];
		var precios = [];

		var proveedores = $("#proveedor").val();
		var dtoproveedor1 = $("#dtoproveedor1").val();
		var dtoproveedor2 = $("#dtoproveedor2").val();
		var dtoproveedor3 = $("#dtoproveedor3").val();

		var accion = "modificar-descuentos-proveedores";

		$.ajax({
			url: "api.php",
			type: "GET",
			crossDomain: true,
			data: {accion:accion,proveedores:proveedores, dtoproveedor1:dtoproveedor1, dtoproveedor2:dtoproveedor2, dtoproveedor3:dtoproveedor3},
			dataType: "json",
			success: function (response) {

				//acá todo
				console.log("Descuentos del proveedor aplicados");

				//Por cada registro
				$("#articulos tbody tr").each(function( index ) {

					//Si está tildado
					if ($(this).find(".seleccionar-articulo").is(":checked")) {

						//armo datos para actualizar costo
						var idarticuloproveedor = $(this).data("id-articulo-proveedor");
						var nuevocosto = $(this).data("nuevo-costo");

						var registro = [idarticuloproveedor, nuevocosto];

						costos.push(registro);

						//Si el cálculo es porcentual y es el proveedor
						if ($(this).data("calculo-precio") == 1 && $(this).data("es-proveedor")) {

							//armo datos para actalizar precio
							var idarticulo = $(this).data("id-articulo");

							//Si modifiqué el margen
							if ($("#modificacion-margen").val() != "") {
								var margennuevo = parseFloat($("#modificacion-margen").val());
							}else{
								var margennuevo = parseFloat($(this).data("margen"));
							}

							var nuevoprecioventa = $(this).data("nuevo-precio-venta");

							var registro = [idarticulo, margennuevo, nuevoprecioventa];

							precios.push(registro);					
							
						}

					}

				});

				var accion = "actualizacion-masiva-costos";

				var dtoarticulo1 = $("#dtoarticulo1").val();
				var dtoarticulo2 = $("#dtoarticulo2").val();
				var dtoarticulo3 = $("#dtoarticulo3").val();

				$.ajax({
					url: "api.php",
					type: "POST",
					crossDomain: true,
					data: {accion:accion,costos:costos, dtoarticulo1:dtoarticulo1, dtoarticulo2:dtoarticulo2, dtoarticulo3:dtoarticulo3},
					dataType: "json",
					success: function (response) {

						console.log("Costos actualizados");

						var accion = "actualizacion-masiva-precios-venta";

						$.ajax({
							url: "api.php",
							type: "POST",
							crossDomain: true,
							data: {accion:accion,precios:precios},
							dataType: "json",
							success: function (response) {

								console.log("Precios actualizados");

								$("#modificacion-costo").val("");
								$("#modificacion-margen").val("");

								$("#proveedor").trigger("change");

							},
							error: function(jqXHR, textStatus, errorThrown) {
								console.log(textStatus, errorThrown);
							}
						});		


					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				});		

				console.log(costos);
				console.log(precios);


			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});			
	}

	function actualizarPrecio(){

		console.log("Actualizar Precio");

		$("#articulos tbody tr").each(function( index ) {

			if ($(this).find(".seleccionar-articulo").is(":checked")) {

				if ($(this).data("calculo-precio") == 1) {

					var margennuevo = parseFloat($("#modificacion-margen").val()/100);

					var costopesos = parseFloat($(this).data("costo-pesos"));
					var margen = parseFloat($(this).data("margen"));
					var precioventa = parseFloat($(this).data("precio-venta"));
					var indicecompra = parseFloat($(this).data("indice-compra"));
					var indiceventa = parseFloat($(this).data("indice-venta"));

					if (margennuevo == 0) {
						margennuevo = margen/100;
					}

					var nuevoprecioventa = parseFloat((costopesos * indicecompra / indiceventa) * margennuevo) + parseFloat(costopesos * indicecompra / indiceventa);

					$(this).data("nuevo-precio-venta", nuevoprecioventa.toFixed(2));

					$(this).find(".nuevo-precio-venta").html(nuevoprecioventa.toFixed(2));
					
				}

			}else{
				var precioventa = parseFloat($(this).data("precio-venta"));
				$(this).find(".nuevo-precio-venta").html(precioventa.toFixed(2));
				$(this).data("nuevo-precio-venta", nuevoprecioventa.toFixed(2));

			}

		});		

	}

	function actualizarCosto(){

		console.log("Actualizar Costo");

		if ($("#modificacion-costo").val() != "") {
			var modificacioncosto = parseFloat($("#modificacion-costo").val()/100);
		}else{
			var modificacioncosto = 0;
		}

		//Por cada registro
		$("#articulos tbody tr").each(function( index ) {

			//Si está tildado
			if ($(this).find(".seleccionar-articulo").is(":checked")) {

				var costo = parseFloat($(this).data("costo"));
				var margen = parseFloat($(this).data("margen"));
				var precioventa = parseFloat($(this).data("precio-venta"));
				var cotizacion = parseFloat($(this).data("cotizacion"));
				var unidadcompra = $(this).data("unidad-compra");

				var nuevocosto = (costo * modificacioncosto) + costo;

				$(this).find(".nuevo-costo").html($(this).data("simbolo")+" "+ parseFloat(nuevocosto).toFixed(2));

				var nuevocostopesos = parseFloat(nuevocosto) * cotizacion;

				if ($("#dtoproveedor1").val() == "") {
					var dtoproveedor1 = parseFloat($(this).data("dtoproveedor1"));
				}else{
					var dtoproveedor1 = parseFloat($("#dtoproveedor1").val());
				}

				$(this).find(".dtoproveedor1").html(dtoproveedor1);

				var dtopesos = nuevocostopesos * dtoproveedor1 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;	

				if ($("#dtoproveedor2").val() == "") {
					var dtoproveedor2 = parseFloat($(this).data("dtoproveedor2"));
				}else{
					var dtoproveedor2 = parseFloat($("#dtoproveedor2").val());
				}

				$(this).find(".dtoproveedor2").html(dtoproveedor2);

				var dtopesos = nuevocostopesos * dtoproveedor2 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;	

				if ($("#dtoproveedor3").val() == "") {
					var dtoproveedor3 = parseFloat($(this).data("dtoproveedor3"));
				}else{
					var dtoproveedor3 = parseFloat($("#dtoproveedor3").val());
				}

				$(this).find(".dtoproveedor3").html(dtoproveedor3);
				
				var dtopesos = nuevocostopesos * dtoproveedor3 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;	

				if ($("#dtoarticulo1").val() == "") {
					var dtoarticulo1 = parseFloat($(this).data("dtoarticulo1"));
				}else{
					var dtoarticulo1 = parseFloat($("#dtoarticulo1").val());
				}

				$(this).find(".dtoarticulo1").html(dtoarticulo1);				

				var dtopesos = nuevocostopesos * dtoarticulo1 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;	
															
				if ($("#dtoarticulo2").val() == "") {
					var dtoarticulo2 = parseFloat($(this).data("dtoarticulo2"));
				}else{
					var dtoarticulo2 = parseFloat($("#dtoarticulo2").val());
				}

				$(this).find(".dtoarticulo2").html(dtoarticulo2);		

				var dtopesos = nuevocostopesos * dtoarticulo2 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;					

				if ($("#dtoarticulo3").val() == "") {
					var dtoarticulo3 = parseFloat($(this).data("dtoarticulo3"));
				}else{
					var dtoarticulo3 = parseFloat($("#dtoarticulo3").val());
				}

				$(this).find(".dtoarticulo3").html(dtoarticulo3);				

				var dtopesos = nuevocostopesos * dtoarticulo3 / 100;

				nuevocostopesos = nuevocostopesos - dtopesos;

				$(this).find(".nuevo-costo-pesos").html("$ "+ parseFloat(nuevocostopesos).toFixed(2)+" ("+unidadcompra+")");

				$(this).data("nuevo-costo", nuevocosto.toFixed(2));

				$(this).data("costo-pesos", nuevocostopesos);

			}else{

				var costo = parseFloat($(this).data("costo"));
				var cotizacion = parseFloat($(this).data("cotizacion"));
				var unidadcompra = $(this).data("unidad-compra");

				var nuevocosto = costo;

				$(this).find(".nuevo-costo").html($(this).data("simbolo")+" "+ parseFloat(nuevocosto).toFixed(2));				

				var nuevocostopesos = parseFloat(nuevocosto) * cotizacion;

				if ($("#dtoproveedor1").val() == "") {
					var dtoproveedor1 = parseFloat($(this).data("dtoproveedor1"));
				}else{
					var dtoproveedor1 = parseFloat($("#dtoproveedor1").val());
				}

				$(this).find(".dtoproveedor1").html(dtoproveedor1);

				var dtopesos = nuevocostopesos * dtoproveedor1 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;

				if ($("#dtoproveedor2").val() == "") {
					var dtoproveedor2 = parseFloat($(this).data("dtoproveedor2"));
				}else{
					var dtoproveedor2 = parseFloat($("#dtoproveedor2").val());
				}

				$(this).find(".dtoproveedor2").html(dtoproveedor2);

				var dtopesos = nuevocostopesos * dtoproveedor2 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;	

				if ($("#dtoproveedor3").val() == "") {
					var dtoproveedor3 = parseFloat($(this).data("dtoproveedor3"));
				}else{
					var dtoproveedor3 = parseFloat($("#dtoproveedor3").val());
				}

				$(this).find(".dtoproveedor3").html(dtoproveedor3);

				var dtopesos = nuevocostopesos * dtoproveedor3 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;	
				
				var dtoarticulo1 = parseFloat($(this).data("dtoarticulo1"));
				var dtopesos = nuevocostopesos * dtoarticulo1 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;	
															
				$(this).find(".dtoarticulo1").html(dtoarticulo1);

				var dtoarticulo2 = parseFloat($(this).data("dtoarticulo2"));
				var dtopesos = nuevocostopesos * dtoarticulo2 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;

				$(this).find(".dtoarticulo2").html(dtoarticulo2);

				var dtoarticulo3 = parseFloat($(this).data("dtoarticulo3"));
				var dtopesos = nuevocostopesos * dtoarticulo3 / 100;
				nuevocostopesos = nuevocostopesos - dtopesos;

				$(this).find(".dtoarticulo3").html(dtoarticulo3);

				$(this).find(".nuevo-costo-pesos").html("$ "+ parseFloat(nuevocostopesos).toFixed(2)+" ("+unidadcompra+")");

				$(this).data("nuevo-costo", nuevocosto.toFixed(2));				

				$(this).data("costo-pesos", nuevocostopesos);				

			}

		});

		actualizarPrecio();			
	}

	$(document).ready(function(){
		$("#proveedor").trigger("change");
	})

	function filtrosDependientes(elemento){

		switch ($(elemento).attr("id")) {

		  case "proveedor":

			var accion = "obtener-categorias-proveedores";
			var proveedores = $(elemento).val();

			$.ajax({
				url: "api.php",
				type: "get",
				crossDomain: true,
				data: {accion:accion,proveedores:proveedores},
				dataType: "json",
				success: function (response) {

				  	$("#categoria").empty();
				  	$("#subcategoria").empty();
				  	$("#rubro").empty();
				  	$("#marca").empty();							

					var html = '';

					$.each(response.exito, function( index, value ) {
						html += '<option value="'+ value.id +'">'+ value.denominacion +'</option>'  
					});

					$("#categoria").append(html).select2({
						width: '100%'
					}).trigger("change");

				},
				error: function(jqXHR, textStatus, errorThrown) {
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
				data: {accion:accion,proveedores:proveedores, categorias:categorias},
				dataType: "json",
				success: function (response) {

				  	$("#subcategoria").empty();
				  	$("#rubro").empty();
				  	$("#marca").empty();							

					var html = '';

					$.each(response.exito, function( index, value ) {
						html += '<option value="'+ value.id +'">'+ value.denominacion +'</option>'  
					});

					$("#subcategoria").append(html).select2({
						width: '100%'
					}).trigger("change");

				},
				error: function(jqXHR, textStatus, errorThrown) {
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
				data: {accion:accion,proveedores:proveedores, categorias:categorias, subcategorias:subcategorias},
				dataType: "json",
				success: function (response) {

				  	$("#rubro").empty();
				  	$("#marca").empty();							

					var html = '';

					$.each(response.exito, function( index, value ) {
						html += '<option value="'+ value.id +'">'+ value.denominacion +'</option>'  
					});

					$("#rubro").append(html).select2({
						width: '100%'
					}).trigger("change");

				},
				error: function(jqXHR, textStatus, errorThrown) {
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
					accion:accion,
					proveedores:proveedores,
					categorias:categorias,
					subcategorias:subcategorias,
					rubros:rubros
				},
				dataType: "json",
				success: function (response) {

				  	$("#marca").empty();							

					var html = '';

					$.each(response.exito, function( index, value ) {
						html += '<option value="'+ value.id +'">'+ value.denominacion +'</option>'  
					});

					$("#marca").append(html).select2({
						width: '100%'
					}).trigger("change");

				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});

		  break;

		  case "marca":

			listarArticulosActualizacion();

		break;			

		}
	}

	function mostrarDetalle(elemento){
		$("#modal-categoria").html($(elemento).parents("tr").data("categoria"));
		$("#modal-subcategoria").html($(elemento).parents("tr").data("subcategoria"));
		$("#modal-rubro").html($(elemento).parents("tr").data("rubro"));
		$("#modal-marca").html($(elemento).parents("tr").data("marca"));

		$("#modal-detalle").modal();
	}

	function listarArticulosActualizacion(){

		var accion = "obtener-articulos-actualizacion";

		var proveedores = $("#proveedor").val();
		var categorias = $("#categoria").val();
		var subcategorias = $("#subcategoria").val();									
		var rubros = $("#rubro").val();
		var marcas = $("#marca").val();

		if (
			proveedores == null &&
			categorias == null &&
			subcategorias == null &&
			rubros == null &&
			marcas == null
		) {
			$("#articulos tbody").empty();
			$("#mensajes").html("Seleccione al menos un filtro").show();
		}else{

			$("#mensajes").html("").hide();

			$.ajax({
				url: "api.php",
				type: "get",
				crossDomain: true,
				data: {
					accion:accion,
					proveedores:proveedores,
					categorias:categorias,
					subcategorias:subcategorias,
					rubros:rubros,
					marcas:marcas
				},
				dataType: "json",
				success: function (response) {

				  	$("#articulos tbody").empty();							

					$.each(response.exito, function( index, value ) {
						
						var html = '';

						if (value.calculoprecio != 1) {
							var clase = "text-danger";
							var rentabilidad = "FIJO";
						}else{
							var clase = "";
							var rentabilidad = value.rentabilidad;
						}

						if (value.idpreciocompra == "SI") {
							var claseboton = "btn-primary";
							var titleboton = "Es el proveedor";
							var esproveedor = true;
						}else{
							var claseboton = "btn-danger";
							var titleboton = "No es el proveedor";
							var esproveedor = false;
						}

						console.log(value);

						var dtosprov = '';

						if (value.dtoproveedor1 == "") {
							value.dtoproveedor1 = 0;
						}

						if (value.dtoproveedor2 == "") {
							value.dtoproveedor2 = 0;
						}

						if (value.dtoproveedor3 == "") {
							value.dtoproveedor3 = 0;
						}

						if (value.dtoarticulo1 == "") {
							value.dtoarticulo1 = 0;
						}

						if (value.dtoarticulo2 == "") {
							value.dtoarticulo2 = 0;
						}

						if (value.dtoarticulo3 == "") {
							value.dtoarticulo3 = 0;
						}

						var dtototal = parseFloat(value.dtoproveedor1) + parseFloat(value.dtoproveedor2) + parseFloat(value.dtoproveedor3) + parseFloat(value.dtoarticulo1) + parseFloat(value.dtoarticulo2) + parseFloat(value.dtoarticulo3);

						var dtos = '(<span data-valor-original="'+value.dtoproveedor1+'" class="dtoproveedor1">'+value.dtoproveedor1+'</span>'+'+<span data-valor-original="'+value.dtoproveedor2+'" class="dtoproveedor2">'+value.dtoproveedor2+'</span>'+'+<span data-valor-original="'+value.dtoproveedor3+'" class="dtoproveedor3">'+value.dtoproveedor3+'</span>'+')'+'(<span data-valor-original="'+value.dtoarticulo1+'" class="dtoarticulo1">'+value.dtoarticulo1+'</span>'+'+<span data-valor-original="'+value.dtoarticulo2+'" class="dtoarticulo2">'+value.dtoarticulo2+'</span>'+'+<span data-valor-original="'+value.dtoarticulo3+'" class="dtoarticulo3">'+value.dtoarticulo3+'</span>'+')'

						html += '<tr data-dtoproveedor1="'+value.dtoproveedor1+'" data-dtoproveedor2="'+value.dtoproveedor2+'" data-dtoproveedor3="'+value.dtoproveedor3+'" data-dtoarticulo1="'+value.dtoarticulo1+'" data-dtoarticulo2="'+value.dtoarticulo2+'" data-dtoarticulo3="'+value.dtoarticulo3+'" data-id-articulo="'+value.id+'" data-es-proveedor="'+esproveedor+'" data-id-articulo-proveedor="'+value.idarticuloproveedor+'" data-categoria="'+value.categoria+'" data-subcategoria="'+value.subcategoria+'" data-rubro="'+value.rubro+'" data-marca="'+value.marca+'" class="'+clase+'" data-calculo-precio="'+value.calculoprecio+'" data-indice-venta="'+value.indiceventa+'" data-indice-compra="'+value.indicecompra+'" data-costo-pesos="" data-unidad-compra="'+value.unidadcompra+'" data-unidad-venta="'+value.unidadventa+'" data-simbolo="'+value.simbolo+'" data-cotizacion="'+parseFloat(value.cotizacion)+'" data-costo="'+parseFloat(value.precio)+'" data-margen="'+parseFloat(value.rentabilidad)+'" data-nuevo-costo="" data-precio-venta="'+parseFloat(value.precioVenta)+'" data-nuevo-precio-venta=""><td><input style="width:20px;height:20px;" class="seleccionar-articulo" type="checkbox" onchange="actualizarCosto()" checked data-id="'+value.id+'" /><a href="javascript:void(0)" onclick="mostrarDetalle(this)" style="margin-left:4px; margin-top:-14px" class="btn '+claseboton+' btn-xs" title="'+titleboton+'"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a></td><td>'+value.denominacioninterna + " " + value.denominacionexterna +'</td><td>'+value.proveedor+'</td><td class="costo">'+value.simbolo+" "+parseFloat(value.precio).toFixed(2)+'</td><td class="nuevo-costo"></td><td class="descuentos">'+dtos+'</td><td class="costo-pesos">$ '+parseFloat(value.precioPesos).toFixed(2)+'</td><td class="nuevo-costo-pesos"></td><td class="margen">'+rentabilidad+'</td><td class="precio-venta">'+"$ "+parseFloat(value.precioVenta).toFixed(2)+" ("+value.unidadventa+")"+'</td><td class="nuevo-precio-venta">'+"$ "+parseFloat(value.precioVenta).toFixed(2)+" ("+value.unidadventa+")"+'</td><td></td></tr>';
						
						$("#articulos tbody").append(html);

					});

					 actualizarCosto();
					
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});			
		}

	}

function actualizarDescuentos(){

	$("#dtoproveedor1").trigger("keyup");
	$("#dtoproveedor2").trigger("keyup");
	$("#dtoproveedor3").trigger("keyup");
	$("#dtoarticulo1").trigger("keyup");
	$("#dtoarticulo2").trigger("keyup");
	$("#dtoarticulo3").trigger("keyup");

}

function seleccionarTodosArticulos(elemento){

	if ($(elemento).is(":checked")) {
		$(".seleccionar-articulo").prop('checked', true);
	}else{
		$(".seleccionar-articulo").prop('checked', false);
	};

	actualizarCosto();

}

/*

function actualizacionMasivaPrecios(){
	
	var registros = {};

	$( ".seleccionar-articulo" ).each(function( index ) {
		if ($(this).is(":checked")) {
			registros[$(this).attr("data-id")] = $(this).attr("data-precio");
		};

	});

	console.log(registros);

  var accion="actualizacion-masiva-precios";

  $.ajax({
	url: "api.php",
	type: "post",
	crossDomain: true,
	data: {accion:accion,registros:registros},
	dataType: "json",
	success: function (response) {
			location.reload();
	},
	error: function(jqXHR, textStatus, errorThrown) {
	   console.log(textStatus, errorThrown);
	}
  });		

}

function actualizacionMasivaRentabilidad(){
	
	var registros = {};

	$( ".seleccionar-articulo" ).each(function( index ) {
		if ($(this).is(":checked")) {
			registros[$(this).attr("data-id")] = $(this).attr("data-precio");
		};

	});

	console.log(registros);

  var accion="actualizacion-masiva-rentabilidad";
  var rentabilidad = $("#rentabilidad").val()

  $.ajax({
	url: "api.php",
	type: "post",
	crossDomain: true,
	data: {accion:accion,registros:registros, rentabilidad:rentabilidad},
	dataType: "json",
	success: function (response) {
		location.reload();
	},
	error: function(jqXHR, textStatus, errorThrown) {
	   console.log(textStatus, errorThrown);
	}
  });		

}

*/

</script>



<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$actualizacionconfiltro_php->Page_Terminate();
?>
