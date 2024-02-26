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

$generacionmasivacomprobantes_php = NULL; // Initialize page object first

class cgeneracionmasivacomprobantes_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'generacionmasivacomprobantes.php';

	// Page object name
	var $PageObjName = 'generacionmasivacomprobantes_php';

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
			define("EW_TABLE_NAME", 'generacionmasivacomprobantes.php', TRUE);

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
		$Breadcrumb->Add("custom", "generacionmasivacomprobantes_php", $url, "", "generacionmasivacomprobantes_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($generacionmasivacomprobantes_php)) $generacionmasivacomprobantes_php = new cgeneracionmasivacomprobantes_php();

// Page init
$generacionmasivacomprobantes_php->Page_Init();

// Page main
$generacionmasivacomprobantes_php->Page_Main();

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


<div class="container">

	<div class="row">
		<form class="form-horizontal">
			<fieldset>
				<!-- Primera columna-->
				<div class="col-md-3">

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="control-label" for="casa-central">Central</label>
					  <div class="">
						<select id="casa-central" name="casa-central" class="form-control" data-s2="true" onchange="obtenerSucursales()">
							<option value="0">Seleccione</option>
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

				</div>
				<!-- Fin Primera Columna-->

				<!-- Segunda Columna-->
				<div class="col-md-5">

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="control-label" for="tercero">Tercero</label>
					  <div class="">
					    <select id="tercero" onchange="ejecutar()" class="form-control form-control-custom">
								
					    </select>
					  </div>
					</div>

				</div>
				<!-- Fin Segunda Columna-->

				<!-- Tercera Columna-->
				<div class="col-md-4">

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="control-label" for="comprobante">Comprobante</label>
					  <div class="">
					    <select id="comprobante" onchange="ejecutar()" class="form-control form-control-custom">
								
					    </select>
					  </div>
					</div>

				</div>
				<!-- Fin Tercera Columna-->				


			</fieldset>
		</form>
	</div>

	
	<div class="row">
		<form class="form-horizontal">
			<fieldset>
				<div class="col-md-3">
					<div class="form-group">
					  <label class="col-md-8 control-label" for="seleccionar-todos">Seleccionar Todos</label>
					  <div class="col-md-4">
					    <input type="checkbox" id="seleccionar-todos" checked onclick="seleccionarTodos(this)">
					  </div>
					</div>					
				</div>
				<!-- Segunda columna-->
				<div class="col-md-3">

					<div class="form-group">
					  <label class="col-md-4 control-label" for="fecha-desde">Fecha Desde</label>
					  <div class="col-md-8">
					    <input onchange="ejecutar()" value="1990-01-01" type="date" class="form-control form-control-custom" id="fecha-desde">
					  </div>
					</div>

				</div>
				<!-- Fin Primera Columna-->

				<!-- Tercera columna-->
				<div class="col-md-3">

					<div class="form-group">
					  <label class="col-md-4 control-label" for="fecha-hasta">Fecha Hasta</label>
					  <div class="col-md-8">
					    <input onchange="ejecutar()" value="<?php echo date("Y-m-d") ?>" type="date" class="form-control form-control-custom" id="fecha-hasta">
					  </div>
					</div>

				</div>

				<div class="col-md-3">
					<a onclick="$('#modal-generar-comprobantes').modal()" class="btn btn-primary">Generar</a>
				</div>
				<!-- Fin Segunda Columna-->


				<div class="modal" id="modal-generar-comprobantes" tabindex="-1">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 style="margin-botton: 0" class="modal-title">Generar Comprobantes</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">

				      	<form class="p-3">

							<div class="">
							  <label class="control-label" for="nuevo-comprobante">Nuevo Comprobante</label>
							  <div class="">
							    <select id="nuevo-comprobante" class="form-control form-control-custom">
										
							    </select>
							  </div>
							</div>

							<div class="">
							  <label class="control-label" for="nueva-fecha">Nueva Fecha</label>
							  <div class="">
							  	<input value="<?php echo date("Y-m-d") ?>" type="date" class="form-control form-control-custom" id="nueva-fecha">
							  </div>
							</div>

							<!--

							<div class="">
							  <label class="control-label" for="nuevo-precio">Qué hacer con los precios</label>
							  <div class="">
							    <select id="nuevo-precio" class="form-control form-control-custom">
									<option value="1">Mantener precios viejos</option>
									<option value="2">Actualizar a precios nuevos</option>
									<option value="3">Preguntar</option>
							    </select>
							  </div>
							</div>							

							-->

							<div class="">
							  <label class="control-label" for="nueva-fecha">Contable</label>
							  <div class="">
							  	<input type="checkbox" id="nuevo-contable" checked="">
							  </div>
							</div>																

							<!-- Select Basic -->
				      	</form>


				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				        <button type="button" onclick="generar()" class="btn btn-primary">Generar!</button>
				      </div>
				    </div>
				  </div>
				</div>		


			</fieldset>
		</form>
	</div>


	<div class="row" >
		<div class="col-12">
			<table id="resultado" class="table table-striped">
			</table>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){
		obtenerSucursales();
		obtenerComprobantes();
		$("#tercero").select2({
			width:"100%"
		})
	})

	function generar(){

		var fecha = $("#nueva-fecha").val();
		var comprobante = $("#nuevo-comprobante").val();

		if(comprobante == 0){
			alert("Debe seleccionar un comprobante");
			return;
		}

		var ids = [];

		$( ".seleccionar" ).each(function() {
			if ($(this).is(":checked")) {
			    ids.push($( this ).data("id"));
			}    
		});

		if(ids.length == 0){
			alert("Debe seleccionar al menos un comprobante");
			return;
		}

		if ($("#nuevo-contable").is(":checked")) {
			var contable = 1;
		}else{
			var contable = 0;
		};

		var accion = "generacion-masiva-movimientos";

		$.ajax({
		  url: "api.php",
		  type: "get",
		  crossDomain: true,
		  data: {
		  	accion:accion,
		  	comprobante : comprobante,
		  	fecha : fecha,
		  	contable : contable,
		  	ids : ids
		  },
		  dataType: "json",
		  success: function (response) {
		  	ejecutar();
		  	$('#modal-generar-comprobantes').modal('hide')

		  },
		  error: function(jqXHR, textStatus, errorThrown) {
		     console.log(textStatus, errorThrown);
		  }
		});		


	}

	function seleccionarTodos(elemento){

		if ($(elemento).is(":checked")) {
			$(".seleccionar").prop('checked', true);
		}else{
			$(".seleccionar").prop('checked', false);
		};

	}	

	function ejecutar(){

		$("#resultado").empty();

		var accion = "obtener-movimientos-central";
		//var tipomovimiento = $("#tipomovimiento").val();
		var tercero = $("#tercero").val();
		var central = $("#casa-central").val();
		var comprobante = $("#comprobante").val();
		var fechadesde = $("#fecha-desde").val();
		var fechahasta = $("#fecha-hasta").val();

	  $.ajax({
	      url: "api.php",
	      type: "get",
	      crossDomain: true,
	      data: {
	      	accion:accion,
	      	tercero:tercero,
	      	central:central,
	      	comprobante:comprobante,
	      	fechadesde:fechadesde,
	      	fechahasta:fechahasta
	      },
	      dataType: "json",
	      success: function (response) {
	      	
	      	var ultimotercero = "";
	      	var importe = 0;
	      	var cantidad = 0;
	      	var importetotal = 0;
	      	var cantidadtotal = 0;		  			           
	  			var html='';

	    		for (var i = 0; i< Object.keys(response["exito"]).length ; i++) {

	    			if (response["exito"][i]["tercero"] != ultimotercero) {
	    				
	    				if (i != 0) {

			    			html += '<tr style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;">';
							html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"></td>'			    			
			    			html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"><b>Total Tercero</b></td>'
	    					html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" colspan="2"></td>'
	    					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+cantidad.toFixed(2)+'</td>';
	    					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+importe.toFixed(2)+'</td>';		    					
	    					html += '</tr>';
	    					html += '<tr>';
	    					html += '<td colspan=3></td>';
	    					html += '</tr>';	
	    				};

	    				ultimotercero = response["exito"][i]["tercero"];

	    				html += '<tr style="font-weight: bold; background-color:#666; color:white">';
		 	   			html +='<td style="font-weight: bold; background-color:#666; color:white"><h4>Tercero</h4></td>';
		 	   			html +='<td style="font-weight: bold; background-color:#666; color:white" colspan=5><h4>'+response["exito"][i]["denominaciontercero"]+'</h4></td>';
		 	   			html += '</tr>';
	    				html += '<tr style="font-weight: bold; background-color:#666; color:white">';
	    				html += '<td style="font-weight: bold; background-color:#666; color:white"></td>'
		 	   			html +='<td style="font-weight: bold; background-color:#666; color:white">Fecha</td>';
		 	   			html +='<td style="font-weight: bold; background-color:#666; color:white">Comprobante</td>';
		 	   			html +='<td style="font-weight: bold; background-color:#666; color:white">Número</td>';
		 	   			html +='<td align="right" style="font-weight: bold; background-color:#666; color:white">Cantidad</td>';
		 	   			html +='<td align="right" style="font-weight: bold; background-color:#666; color:white">Importe</td>';
		 	   			html += '</tr>';

			      	importe = 0;
			      	cantidad = 0;	
		 	   			
	    			}

	    			html += '<tr>';

	    			html += '<td><input class="seleccionar" checked data-id="'+response["exito"][i]["id"]+'" type="checkbox"/></td>';
	    			html += '<td>'+response["exito"][i]["fecha"]+'</td>';
	    			html += '<td>'+response["exito"][i]["comprobante"]+'</td>';
	    			html += '<td>'+response["exito"][i]["numeroComprobante"]+'</td>';
	    			html += '<td align="right">'+response["exito"][i]["cantPendiente"]+'</td>';
	    			html += '<td align="right">'+response["exito"][i]["importePendiente"]+'</td>';

	    			html += '</tr>';

		      	importe += parseFloat(response["exito"][i]["importePendiente"]);
		      	cantidad += parseFloat(response["exito"][i]["cantPendiente"]);

		      	importetotal += parseFloat(response["exito"][i]["importePendiente"]);
		      	cantidadtotal += parseFloat(response["exito"][i]["cantPendiente"]);			      	

    				if (i+1 == Object.keys(response["exito"]).length) {
			    			html += '<tr style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;">';
							html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"></td>'	    			
			    			html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"><b>Total Tercero</b></td>'
	    					html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" colspan="2"></td>'
	    					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+cantidad.toFixed(2)+'</td>';
	    					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+importe.toFixed(2)+'</td>';		    					
	    					html += '</tr>';
	    					html += '<tr>';
	    					html += '<td colspan=3></td>';
	    					html += '</tr>';		    					
    				};		    			

	    		};

    			html += '<tr style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;">';
				html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"></td>'    			
    			html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;"><b>Total General</b></td>'
					html += '<td style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" colspan="2"></td>'
					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+cantidadtotal.toFixed(2)+'</td>';
					html += '<td align="right" style="font-weight: bold;background-color:#888;color:white;border-top-style: double;border-color: black;border-width: medium;" >'+importetotal.toFixed(2)+'</td>';		    					
					html += '</tr>';
					html += '<tr>';
					html += '<td colspan=3></td>';
					html += '</tr>';	

	    		$("#resultado").append(html);

	      },
	      error: function(jqXHR, textStatus, errorThrown) {
	         console.log(textStatus, errorThrown);
	      }
	  });

	}

	function obtenerComprobantes(){

	  var accion="comprobantes-muestra-pendientes";

	  $.ajax({
	      url: "api.php",
	      type: "post",
	      crossDomain: true,
	      data: {accion:accion},
	      dataType: "json",
	      success: function (response) {
	  			           
	  			var html='<option value="0">Seleccione</option>';
	    		for (var i = 0; i< Object.keys(response["exito"]).length ; i++) {
	    			html +='<option value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
	    		};
	    		$("#comprobante").append(html);
	    		$("#nuevo-comprobante").append(html);
	      },
	      error: function(jqXHR, textStatus, errorThrown) {
	         console.log(textStatus, errorThrown);
	      }
	  });			
	}


	function obtenerSucursales(){

		var central = $("#casa-central").val();

		$("#tercero").empty();

		var accion="retornarsucursales";

		$.ajax({
			url: "api.php",
			type: "get",
			crossDomain: true,
			data: {accion:accion,central:central},
			dataType: "json",
			success: function (response) {

				var html='<option selected value="0">Todos</option>';

				for (var i = 0; i< Object.keys(response["exito"]).length ; i++) {
					html +='<option value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
				};
				$("#tercero").append(html);

				ejecutar();

			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	}	


</script>


<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$generacionmasivacomprobantes_php->Page_Terminate();
?>
