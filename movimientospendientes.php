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

$movimientospendientes_php = NULL; // Initialize page object first

class cmovimientospendientes_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientospendientes.php';

	// Page object name
	var $PageObjName = 'movimientospendientes_php';

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
			define("EW_TABLE_NAME", 'movimientospendientes.php', TRUE);

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
		$Breadcrumb->Add("custom", "movimientospendientes_php", $url, "", "movimientospendientes_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($movimientospendientes_php)) $movimientospendientes_php = new cmovimientospendientes_php();

// Page init
$movimientospendientes_php->Page_Init();

// Page main
$movimientospendientes_php->Page_Main();

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
 

<div class="container">

	<div class="row">
		<form class="form-horizontal">
			<fieldset>
				<!-- Primera columna-->
				<div class="col-md-3">

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-md-2 control-label" for="tipomovimiento">Tipo</label>
					  <div class="col-md-10">
					    <select id="tipomovimiento" onchange="actualizarTerceros()" class="form-control form-control-custom">
					      <option value="1">Ventas</option>
					      <option value="2">Compras</option>
					    </select>
					  </div>
					</div>


				</div>
				<!-- Fin Primera Columna-->

				<!-- Segunda Columna-->
				<div class="col-md-5">

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-md-2 control-label" for="tercero">Tercero</label>
					  <div class="col-md-10">
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
					  <label class="col-md-4 control-label" for="comprobante">Comprobante</label>
					  <div class="col-md-8">
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
				<!-- Primera columna-->
				<div class="col-md-6">

					<div class="form-group">
					  <label class="col-md-4 control-label" for="fecha-desde">Fecha Desde</label>
					  <div class="col-md-8">
					    <input onchange="ejecutar()" value="1990-01-01" type="date" class="form-control form-control-custom" id="fecha-desde">
					  </div>
					</div>

				</div>
				<!-- Fin Primera Columna-->

				<!-- Segunda columna-->
				<div class="col-md-6">

					<div class="form-group">
					  <label class="col-md-4 control-label" for="fecha-hasta">Fecha Hasta</label>
					  <div class="col-md-8">
					    <input onchange="ejecutar()" value="<?php echo date("Y-m-d") ?>" type="date" class="form-control form-control-custom" id="fecha-hasta">
					  </div>
					</div>

				</div>
				<!-- Fin Segunda Columna-->
		


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
			actualizarTerceros();
			obtenerComprobantesMuestraPendientes();
			$("#tercero").select2({
				width:"100%"
			})
		})

		function ejecutar(){

			$("#resultado").empty();

			var accion = "movimientos-pendientes";
			var tipomovimiento = $("#tipomovimiento").val();
			var tercero = $("#tercero").val();
			var comprobante = $("#comprobante").val();
			var fechadesde = $("#fecha-desde").val();
			var fechahasta = $("#fecha-hasta").val();

		  $.ajax({
		      url: "api.php",
		      type: "get",
		      crossDomain: true,
		      data: {
		      	accion:accion,tercero:tercero,
		      	tipomovimiento:tipomovimiento,
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

		function obtenerComprobantesMuestraPendientes(){

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
		      },
		      error: function(jqXHR, textStatus, errorThrown) {
		         console.log(textStatus, errorThrown);
		      }
		  });			
		}

		function actualizarTerceros(){

			var seleccionado=$("#tercero").val();

			$("#tercero").empty();

			if ($("#tipomovimiento").val()==1) {//es venta
				var tipotercero=2;
			}else{//es compra
				var tipotercero=1;
			};

		  var accion="retornarterceros";

		  $.ajax({
		      url: "api.php",
		      type: "get",
		      crossDomain: true,
		      data: {accion:accion,tipotercero:tipotercero},
		      dataType: "json",
		      success: function (response) {
		  			           
		  			var html='<option value="0">Seleccione</option>';
		    		for (var i = 0; i< Object.keys(response["exito"]).length ; i++) {
		    			if (response["exito"][i]["id"]==seleccionado) {
			 	   			html +='<option selected value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
		    			}else{
		    				html +='<option value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
		    			};
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
$movimientospendientes_php->Page_Terminate();
?>
