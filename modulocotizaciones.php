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

$modulocotizaciones_php = NULL; // Initialize page object first

class cmodulocotizaciones_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'modulocotizaciones.php';

	// Page object name
	var $PageObjName = 'modulocotizaciones_php';

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
			define("EW_TABLE_NAME", 'modulocotizaciones.php', TRUE);

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
		$Breadcrumb->Add("custom", "modulocotizaciones_php", $url, "", "modulocotizaciones_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($modulocotizaciones_php)) $modulocotizaciones_php = new cmodulocotizaciones_php();

// Page init
$modulocotizaciones_php->Page_Init();

// Page main
$modulocotizaciones_php->Page_Main();

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
			<div class="form-group" >
			  <label class="control-label" for="cliente">Cliente</label>
			  <div class="">
				<select id="cliente" name="cliente" class="form-control" onchange="seleccionarClienteCotizacion()">
					<option value="">Seleccionar...</option>
						<?php 

							foreach (obtenerClientes() as $key => $value) {
								
								$seleccionar = FALSE;

								if (isset($_POST["cliente"])) {
									if (!empty($_POST["cliente"]) && $_POST["cliente"] == $value["id"]) {
										$seleccionar = TRUE;
									}
								}

								?>
									
									<option <?php echo $seleccionar?"selected":"" ?> value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?></option>

								<?php 
							}

						?>
				</select>
			  </div>
			</div>

			<!-- Select Basic -->
			<div class="form-group" >
			  <label class="control-label" for="lista-precios">Lista de Precios</label>
			  <div class="">
				<select id="lista-precios" name="lista-precios" class="form-control">
					<option value="">Seleccionar...</option>
						<?php 

							foreach (obtenerListasPrecios() as $key => $value) {
								
								$seleccionar = FALSE;

								if (isset($_POST["lista-precios"])) {
									if (!empty($_POST["lista-precios"]) && $_POST["lista-precios"] == $value["id"]) {
										$seleccionar = TRUE;
									}
								}

								?>
									
									<option <?php echo $seleccionar?"selected":"" ?> value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"]." (".$value["descuento"]."%)" ?></option>

								<?php 
							}

						?>
				</select>
			  </div>
			</div>

			<!-- Prepended text-->
			<div class="form-group">
			  <label class="control-label" for="variacion">Descuento Cliente</label>
			  <div class="">
			    <div class="input-group">
			      <span class="input-group-addon">% </span>
			      <input value="" id="descuento-cliente" name="descuento-cliente" class="form-control" placeholder="" type="number">
			    </div>
			  </div>
				
			</div>

			<input style="margin-top: 17px;" type="submit" name="submit-cliente" value="Actualizar" class="btn btn-primary ewButton" />

		</form>




				<div class="" style="margin-top: 17px">

					<div class="row">
						<div class="col-md-6">
							<table id="descuentos-categoria" class="table ewTable" style="width:100%">
								<thead>
									<tr>
										<th>
											Categoría
										</th>
										<th>
											Descuento
										</th>																																																		
									</tr>
								</thead>

								<tbody>
									<tr>

									</tr>
								</tbody>
							</table>
							
						</div>
						<div class="col-md-6">

						<table id="descuentos-subcategoria" class="table ewTable" style="width:100%">
								<thead>
									<tr>
										<th>
											Subcategoría
										</th>
										<th>
											Descuento
										</th>																																																		
									</tr>
								</thead>

								<tbody>

								</tbody>
							</table>					
							
						</div>
					</div>
					

				</div>


	<?php

	if (isset($_POST["submit-cliente"])) {
		$sql="UPDATE terceros
		SET
		idListaPrecios = '".$_POST["lista-precios"]."',
		dtoCliente = '".$_POST["descuento-cliente"]."'
		WHERE id = '".$_POST["cliente"]."'";

		ew_Execute($sql);
	} 

?>

<form class="form-inline">

<!-- Select Basic -->
<div class="form-group" >
  <label class="control-label" for="categoria">Categoria</label>
  <div class="">
	<select id="categoria" name="categoria" class="form-control">
		<option value="">Seleccionar...</option>
			<?php 

				foreach (obtenerCategorias() as $key => $value) {
					
					$seleccionar = FALSE;

					if (isset($_POST["categoria"])) {
						if (!empty($_POST["categoria"]) && $_POST["categoria"] == $value["id"]) {
							$seleccionar = TRUE;
						}
					}

					?>
						
						<option <?php echo $seleccionar?"selected":"" ?> value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?></option>

					<?php 
				}

			?>
	</select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" >
  <label class="control-label" for="subcategoria">Subcategoría</label>
  <div class="">
	<select id="subcategoria" name="subcategoria" class="form-control">
		<option value="">Seleccionar...</option>
			<?php 

				foreach (obtenerSubcategorias() as $key => $value) {

					$seleccionar = FALSE;

					if (isset($_POST["subcategoria"])) {
						if (!empty($_POST["subcategoria"]) && $_POST["subcategoria"] == $value["id"]) {
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
<div class="form-group" >
  <label class="control-label" for="rubro">Rubros</label>
  <div class="">
	<select id="rubro" name="rubro" class="form-control">
		<option value="">Seleccionar...</option>
			<?php 

				foreach (obtenerRubros() as $key => $value) {

					$seleccionar = FALSE;

					if (isset($_POST["rubro"])) {
						if (!empty($_POST["rubro"]) && $_POST["rubro"] == $value["id"]) {
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
<div class="form-group" >
  <label class="control-label" for="marca">Marcas</label>
  <div class="">
	<select id="marca" name="marca" class="form-control">
		<option value="">Seleccionar...</option>
			<?php 

				foreach (obtenerMarcas() as $key => $value) {

					$seleccionar = FALSE;

					if (isset($_POST["marca"])) {
						if (!empty($_POST["marca"]) && $_POST["marca"] == $value["id"]) {
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

	<a style="margin-top: 17px;" class="btn btn-primary" onclick="filtrarArticulos()">Filtrar</a>

	<h6>Pasa asegurar una correcta performance no se listan más de 100 artículos.</h6>

	<a style="margin-top: 17px;" class="btn btn-primary" onclick="actualizarArticulos()">Actualizar Artículos Seleccionados</a>

	<div class="" style="margin-top: 17px;">
		
		<table id="articulos" class="table table-hover table-sm ewTable">
			<thead>
				<tr>
					<th>
						Sel
						<input type="checkbox" checked onclick="seleccionarTodosArticulos(this)">
					</th>
					<th>Artículo</th>
					<th>lista</th>
					<th>cliente</th>
					<th>categoría</th>
					<th>subcat</th>
					<th>dto Art<input style="margin-left:5px; width:70px" oninput="aplicarDescuentoTodos(this)" type="number"></th>
					<th>Dto Total</th>
					<th>Rent</th>
					<th>Rent Total</th>
					<th>Costo</th>
					<th>Imp</th>
					<th>Imp Final</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>

	</div>



<script>

$(document).ready(function(){
	seleccionarClienteCotizacion();
})

function actualizarArticulos(){

	var actualizar = [];

	$.each($(".seleccionar-articulo:checked"),function(){
		var registro = {};
		registro.id = $(this).attr("data-articulo");
		registro.descuento = $(this).parents("tr").find(".descuento-nuevo").val();
		actualizar.push(registro);
		console.log(actualizar);
	})

  var accion = "actualizar-descuentos-articulos";
  var idcliente = $("#cliente").val();

	  $.ajax({
			url: "api.php",
			type: "get",
			crossDomain: true,
			data: {accion:accion, idcliente:idcliente, actualizar:actualizar},
			dataType: "json",
			success: function (response) {
				//location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  console.log(textStatus, errorThrown);
			}
	  });

			
}

function aplicarDescuentoTodos(elemento){
	$(".descuento-nuevo").val($(elemento).val()).trigger("change");
}

function filtrarArticulos(e){

  var accion = "obtener-descuentos-articulos";
  var idcliente = $("#cliente").val();

  $("#articulos tbody").empty();

  if (idcliente != "") {

  	var categoria = $("#categoria").val();
  	var subcategoria = $("#subcategoria").val();
  	var rubro = $("#rubro").val();
  	var marca = $("#marca").val();

	  $.ajax({
			url: "api.php",
			type: "get",
			crossDomain: true,
			data: {accion:accion, idcliente:idcliente, categoria:categoria, subcategoria:subcategoria, rubro:rubro, marca:marca},
			dataType: "json",
			success: function (response) {
				console.log(response);

				var html = '';
				$.each( response, function( key, value ) {
					var dto = 0;
				  html += '<tr>'
				  html += '<td><input checked class="seleccionar-articulo" data-articulo="'+ value["id"] +'" type="checkbox"></td>'
				  html += '<td>'+ value["denominacionExterna"] +'</td>'
				  html += '<td><input type="hidden" class="descuento" value="'+ value["dtolis"] +'"/>'+ value["dtolis"] +'</td>'
				  dto += parseFloat(value["dtolis"]);
				  html += '<td><input type="hidden" class="descuento" value="'+ value["dtocli"] +'"/>'+ value["dtocli"] +'</td>'
				  dto += parseFloat(value["dtocli"]);
				  html += '<td><input type="hidden" class="descuento" value="'+ value["dtocat"] +'"/>'+ value["dtocat"] +'</td>'
				  dto += parseFloat(value["dtocat"]);
				  html += '<td><input type="hidden" class="descuento" value="'+ value["dtosub"] +'"/>'+ value["dtosub"] +'</td>'
				  dto += parseFloat(value["dtosub"]);
				  html += '<td><input oninput="recalcular(this)" onchange="recalcular(this)" class="descuento-nuevo" type="number" value="'+ value["dtoart"] +'"/></td>'
				  dto += parseFloat(value["dtoart"]);
				  html += '<td class="dto">'+ dto +'</td>'
				  html += '<td><input type="hidden" class="rentabilidad" value="'+ value["rentabilidad"] +'" />'+ value["rentabilidad"] +'</td>'
				  html += '<td class="nueva-rentabilidad">'+ (value["rentabilidad"] - dto) +'</td>'
				  var costo = parseFloat(value["precioventa"] / (value["rentabilidad"]/100 + 1)).toFixed(2);
				  html += '<td><input type="hidden" class="costo" value="'+ costo +'" />'+ costo +'</td>'			  
				  html += '<td>'+ value["precioventa"] +'</td>'
				  html += '<td class="importe-final">'+ parseFloat((costo * (value["rentabilidad"] - dto) /100) + costo*1).toFixed(2) +'</td>'			  
				  html += '</tr>';
				});

				$("#articulos tbody").append(html);

				$(".descuento-nuevo").trigger("change");


			},
			error: function(jqXHR, textStatus, errorThrown) {
			  console.log(textStatus, errorThrown);
			}
	  });		
  };



}

function recalcular(elemento){

	var dto = 0;
	$.each($(elemento).parents("tr").find(".descuento"), function( key, value ) {
		dto += parseFloat($(this).val());
	});

	dto += parseFloat($(elemento).val());
	$(elemento).parents("tr").find(".dto").html(dto.toFixed(2));	

	var rentabilidad = $(elemento).parents("tr").find(".rentabilidad").val();

	$(elemento).parents("tr").removeClass("danger").removeClass("warning");

	if (rentabilidad-dto == 0) {
		$(elemento).parents("tr").addClass("warning");		
	};

	if (rentabilidad-dto < 0) {
		$(elemento).parents("tr").addClass("danger");		
	};	

	$(elemento).parents("tr").find(".nueva-rentabilidad").html((rentabilidad-dto).toFixed(2));

	var costo = $(elemento).parents("tr").find(".costo").val();
	$(elemento).parents("tr").find(".importe-final").html(parseFloat((costo * (rentabilidad - dto) /100) + costo*1).toFixed(2));

}


function seleccionarClienteCotizacion(){
	var idcliente = $("#cliente").val();
	$("#descuentos-categoria tbody").empty();
	$("#descuentos-subcategoria tbody").empty();
  $("#articulos tbody").empty();


	if (idcliente == "" ) {
		$("#lista-precios").val("");
		$("#descuento-cliente").val("");
		$("#lista-precios").attr("disabled", true);
		$("#descuento-cliente").attr("disabled", true);
	}else{
		$("#lista-precios").removeAttr("disabled");
		$("#descuento-cliente").removeAttr("disabled");

	  var accion = "obtener-descuentos-cliente";

	  $.ajax({
			url: "api.php",
			type: "post",
			crossDomain: true,
			data: {accion:accion, idcliente:idcliente},
			dataType: "json",
			success: function (response) {
				console.log(response);
				$("#lista-precios").val(response.cabecera["idListaPrecios"]);
				$("#descuento-cliente").val(response.cabecera["dtoCliente"]);

				var html = '';
				$.each( response.descuentoscategorias, function( key, value ) {
				  html += '<tr>'
				  html += '<td>'+ value["denominacion"] +'</td>'
				  html += '<td>'+ value["descuento"] +'</td>'
				  html += '</tr>';
				});

				$("#descuentos-categoria tbody").append(html);

				var html = '';
				$.each( response.descuentossubcategorias, function( key, value ) {
				  html += '<tr>'
				  html += '<td>'+ value["denominacion"] +'</td>'
				  html += '<td>'+ value["descuento"] +'</td>'
				  html += '</tr>';
				});

				$("#descuentos-subcategoria tbody").append(html);				

			},
			error: function(jqXHR, textStatus, errorThrown) {
			  console.log(textStatus, errorThrown);
			}
	  });			

	};

}


function seleccionarTodosArticulos(elemento){
	if ($(elemento).is(":checked")) {
		$(".seleccionar-articulo").prop('checked', true);
	}else{
		$(".seleccionar-articulo").prop('checked', false);
	};
}

</script>

<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$modulocotizaciones_php->Page_Terminate();
?>
