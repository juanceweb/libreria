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

$movimientoseditcustom_php = NULL; // Initialize page object first

class cmovimientoseditcustom_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'movimientoseditcustom.php';

	// Page object name
	var $PageObjName = 'movimientoseditcustom_php';

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
			define("EW_TABLE_NAME", 'movimientoseditcustom.php', TRUE);

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
		$Breadcrumb->Add("custom", "movimientoseditcustom_php", $url, "", "movimientoseditcustom_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($movimientoseditcustom_php)) $movimientoseditcustom_php = new cmovimientoseditcustom_php();

// Page init
$movimientoseditcustom_php->Page_Init();

// Page main
$movimientoseditcustom_php->Page_Main();

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

include_once("consultas.php");

$terceros=obtieneTerceros();
$alicuotasiva=obtieneAlicuotasIva();
$unidadesmedida=obtieneUnidadesMedida();

if (isset($_GET["id"])) {
	$movimiento=obtieneMovimiento($_GET["id"]);
}else{
	header("Location: login.php");
	exit();	
}

if (isset($_SESSION["modo"])) {
	$modo=$_SESSION["modo"];
}else{
	header("Location: login.php");
	exit();
};

$tiposdocumentos=obtieneTiposDocumentos($movimiento[0]["contable"]);
$comprobantes=obtieneComprobantes($movimiento[0]["contable"]);

 ?>
<!DOCTYPE html>
<html lang="es">
<head>

<link rel="stylesheet" href="librerias/css/font-awesome-4.7.0/font-awesome.min.css">

<link href="librerias/css/select2-4.0.4/select2.min.css" rel="stylesheet" />
<script src="librerias/js/select2-4.0.4/select2.full.min.js"></script>


<style>
	.form-control{
		max-width: 100%;
		width: 100%;		
	}

	.resultado{
		margin-right: 10px;
	}
	.codigo{
		float: left;
		width: 100px;
	}

	.oculto{
		display: none;
	}

	.form-control{
		padding: 1px 1px!important;
	}

	#contenedor-contador-detalles{
	padding: 3px;
	border: thin #999;
	position: fixed;
	top: 381px;
	left: 15px;
	border-style: none none dotted none;		
	}

	#contenedor-contador-detalles h4{
		margin: 0;
	}		

</style>

</head>



<body>


<div class="container" style="width:100%; margin-bottom: 200px;">
	

	<!-- Encabezado-->

	<input id="idcabecera" type="hidden" value="<?php echo $_GET["id"] ?>">

	<div class="row">
		<form class="form-horizontal">
			<fieldset>
				<!-- Primera columna-->
				<div class="col-sm-4">


					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="selectbasic">Movimiento</label>
					  <div class="col-sm-8">
					    <select disabled readonly id="tipomovimiento" name="tipomovimiento" class="form-control">
					      <?php
					      	if ($movimiento[0]["tipoMovimiento"]==1) {
					      		?>
								      <option selected value="1">Venta</option>
								      <option value="2">Compra</option>
					      		<?php
					      	}else{
					      		?>
								      <option value="1">Venta</option>
								      <option selected value="2">Compra</option>					      		
					      		<?php
					      	}
					      ?>
					    </select>
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="textinput">Fecha</label>  
					  <div class="col-sm-8">
					  	<input id="fecha" name="fecha" type="date" placeholder="Fecha" class="form-control input-md" value="<?php echo $movimiento[0]["fecha"] ?>">
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="vigencia">Días de vigencia</label>  
					  <div class="col-sm-8">
					  	<input id="vigencia" name="vigencia" type="number" placeholder="Vigencia" class="form-control input-md" value="<?php echo $movimiento[0]["vigencia"] ?>">
					  </div>
					</div>						

					<?php 
						if ($modo==0) {
							?>
							<input type="hidden" value="0" id="contable">
							<?php
						}else if($modo==1){
							?>
							<input type="hidden" value="1" id="contable">
							<?php
						}else{

					 ?>										

					<!-- Checkbox -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="checkboxes">Contable</label>
					  <div class="col-sm-8">
					    <label class="checkbox-inline" for="checkboxes-0">
   							<?php 
								if ($movimiento[0]["contable"]==1) {
									?>
									<input type="checkbox" checked name="contable" id="contable" value="1">
									<?php 
								}else{
									?>
									<input type="checkbox" name="contable" id="contable" value="1">									
									<?php
								}
							 ?>
					      SI
					    </label>
					  </div>
					</div>
					<?php 
						}
					?>							

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Neto</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input id="importetotal" disabled readonly name="importetotal" class="form-control" placeholder="Importe Total" type="text" value="<?php echo $movimiento[0]["importeTotal"] ?>">
					    </div>
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Importe IVA</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input id="importeiva" disabled readonly name="importeiva" class="form-control" placeholder="Importe IVA" type="text" value="<?php echo $movimiento[0]["importeIva"] ?>">
					    </div>
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Total</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input id="importeneto" disabled readonly name="importeneto" class="form-control" placeholder="Importe Neto" type="text" value="<?php echo $movimiento[0]["importeNeto"] ?>">
					    </div>
					  </div>
					</div>					

				</div>
				<!-- Fin Primera Columna-->

				<!-- Segunda Columna-->
				<div class="col-sm-4">
					
					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="selectbasic">Comprobante</label>
					  <div class="col-sm-8">
					    <select id="comprobante" name="comprobante" class="form-control comprobante">
							<option value="0">Seleccione</option>
					      <?php
					      	$defaultval=$movimiento[0]["idComprobante"]; 
					      	for ($i=0; $i < count($comprobantes); $i++) {
					      		if ($comprobantes[$i]["id"]==$defaultval) {
					      		 	?>
					      				<option selected value="<?php echo $comprobantes[$i]["id"]?>"><?php echo $comprobantes[$i]["denominacion"] ?></option>					      		 	
					      		 	<?php
					      		 }else{
					      		 	?>
					      				<option value="<?php echo $comprobantes[$i]["id"]?>"><?php echo $comprobantes[$i]["denominacion"] ?></option>
					      		 	<?php
					      		 } 
					      		?>
					      		<?php
					      	}
					       ?>							
					    </select>
					  </div>
					</div>

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="selectbasic">Tercero</label>
					  <div class="col-sm-8">
					    <select disabled readonly id="tercero" name="tercero" class="form-control">
					      <option value="0">Seleccione</option>
					      <?php
					      	$defaultval=$movimiento[0]["idTercero"]; 
					      	for ($i=0; $i < count($terceros); $i++) {
					      		if ($terceros[$i]["id"]==$defaultval) {
					      		 	?>
					      				<option selected value="<?php echo $terceros[$i]["id"]?>"><?php echo $terceros[$i]["denominacion"] ?></option>					      		 	
					      		 	<?php
					      		 }else{
					      		 	?>
					      				<option value="<?php echo $terceros[$i]["id"]?>"><?php echo $terceros[$i]["denominacion"] ?></option>
					      		 	<?php
					      		 } 
					      		?>
					      		<?php
					      	}
					       ?>					      
					    </select>
					  </div>
					</div>

					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="selectbasic">Tipo Doc</label>
					  <div class="col-sm-8">
					    <select disabled readonly id="tipodoctercero" name="tipodoctercero" class="form-control">
					      <option value="">Seleccione</option>
					      <?php
					      	$defaultval=$movimiento[0]["idDocTercero"]; 
					      	for ($i=0; $i < count($tiposdocumentos); $i++) {
					      		if ($tiposdocumentos[$i]["id"]==$defaultval) {
					      		 	?>
					      				<option selected value="<?php echo $tiposdocumentos[$i]["id"]?>"><?php echo $tiposdocumentos[$i]["denominacion"] ?></option>					      		 	
					      		 	<?php
					      		 }else{
					      		 	?>
					      				<option value="<?php echo $tiposdocumentos[$i]["id"]?>"><?php echo $tiposdocumentos[$i]["denominacion"] ?></option>
					      		 	<?php
					      		 } 
					      		?>
					      		<?php
					      	}
					       ?>					      
					    </select>
					  </div>
					</div>		

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="textinput">Doc Tercero</label>  
					  <div class="col-sm-8">
					  	<input disabled readonly id="doctercero" name="doctercero" type="number" placeholder="Doc Tercero" class="form-control input-md" value="<?php echo $movimiento[0]["nroDocTercero"] ?>">
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="textinput">Nom Tercero</label>  
					  <div class="col-sm-8">
					  	<input disabled readonly id="nomtercero" name="nomtercero" type="text" placeholder="Nom Tercero" class="form-control input-md" value="<?php echo $movimiento[0]["nombreTercero"] ?>">
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Estado de Cuenta</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input id="estadocuenta" disabled readonly name="estadocuenta" class="form-control" placeholder="Estado de Cuenta" type="text">
					    </div>
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Límite Descubierto</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input id="limitedescubierto" disabled readonly name="limitedescubierto" class="form-control" placeholder="Límite Descubierto" type="text">
					    </div>
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Cond Venta</label>
					  <div class="col-sm-8">
					    <div class="input-group">
					      <span class="input-group-addon">$</span>
					      <input data-edita="true" style="min-width: 250px;" id="condicionventa" name="condicionventa" class="form-control w-100" placeholder="Condición de Venta" type="number" value="<?php echo $movimiento[0]["condicionVenta"] ?>">
					    </div>
					  </div>
					</div>																


				</div>
				<!-- Fin Segunda Columna-->

				<!-- Tercera Columna-->	
				<div class="col-sm-4">

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Pto de Vta</label>
					  <div class="col-sm-8">
					      <input id="puntoventa"   name="puntoventa" class="form-control" placeholder="Pto de Vta" type="text" value="<?php echo $movimiento[0]["ptoVenta"] ?>">
					  </div>
					</div>		

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Nro Comp</label>
					  <div class="col-sm-8">
					      <input id="numerocomprobante"   name="numerocomprobante" class="form-control" placeholder="Nro Comprobante" type="text" value="<?php echo $movimiento[0]["nroComprobante"] ?>">
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">CAE</label>
					  <div class="col-sm-8">
					      <input id="cae"   name="cae" class="form-control" placeholder="CAE" type="text" value="<?php echo $movimiento[0]["cae"] ?>">
					  </div>
					</div>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Vto CAE</label>
					  <div class="col-sm-8">
					      <input id="vtocae"   name="vtocae" class="form-control" placeholder="Vto CAE" type="date" value="<?php echo $movimiento[0]["vtoCae"] ?>" >
					  </div>
					</div>


					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Comentarios</label>
					  <div class="col-sm-8">
					  	<textarea id="comentarios" class="form-control" placeholder="Comentarios" cols="30" rows="3"><?php echo $movimiento[0]["comentarios"] ?></textarea>
					  </div>
					</div>					


					<!-- Text input-->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="prependedtext">Archivo</label>
					  <div class="col-sm-8">
							<span class="btn btn-primary fileinput-button" data-orden="1">
			                    <span>Seleccionar Archivo</span>
			                    <input id="fileupload1" type="file" name="files[]" >
			                </span>
			                <br>
			                <br>
			                <div id="progress1" class="progress">
								<?php 
									if ($movimiento[0]["archivo"]!="") {
										?>
			                    		<div width="100%" class="progress-bar progress-bar-success"></div>

										<?php
									}else{
										?>
			                    		<div  class="progress-bar progress-bar-success"></div>

										<?php
									}

								 ?>			                	
			                </div>
			                <div id="files1" class="files">
								<?php 
									if ($movimiento[0]["archivo"]!="") {
										?>
					                    <img src="upload/thumbnail/<?php echo $movimiento[0]["archivo"] ?>" alt=""><a style="margin-left:10px" class="btn btn-default" onclick="eliminar(1)">Eliminar</a><p id="archivotext"><?php echo $movimiento[0]["archivo"] ?></p><input type="hidden" id="archivo" value="<?php echo $movimiento[0]['archivo'] ?>">
										<?php
									}

								 ?>
			                </div>
			                <div id="alert1" class="alert alert-danger" style="display:none;">
			                    <p id="mensaje-error1"></p>
			                </div>
					  </div>
					</div>				


				</div>
				<!-- Fin Tercera Columna-->

			</fieldset>
		</form>
	</div>
	<!-- Fin Encabezado-->
	<!-- Panel central-->
	<div class="row">
		<div style="padding:10px" class="col-md-4">
			<button id="agregardetalle" class="btn btn-primary">Agregar Detalle</button>
			<button onclick="guardar('editar')" id="guardar" class="btn btn-primary">Guardar</button>
			<button onclick="movimientosparaimportar()" class="btn btn-primary">Importar</button>					
		</div>
		<div style="padding:10px" class="col-md-4">
		  <p id="articulos-pendientes">
		  	
		  </p>
		</div>			
		<div style="padding:10px" class="col-md-4">
			<div id="mensaje" style="width:100%" class="alert oculto alert-dismissible" role="alert">
			  <button onclick="ocultarmensaje()" type="button" class="close"><span>&times;</span></button>
			  <p id="textomensaje">
			  	
			  </p>
			</div>			
		</div>		
	</div>

	<!-- Detalle-->
	<div class="row">
		<table class="table table-condensed" id="detalle">
			<thead>
				<tr>
					<th></th>
					<th>Cantidad</th>
					<th>Producto</th>
					<th>Unid Med</th>
					<th>Imp Unit</th>
					<th>Subtotal</th>
					<th>IVA
						<select id="iva-todos" onchange="cambiaIvaTodos(this)">
							<option value="">-</option>
					      	<?php
					      	for ($i=0; $i < count($alicuotasiva); $i++) {
								?>
									<option value="<?php echo $alicuotasiva[$i]["id"]?>"><?php echo $alicuotasiva[$i]["valor"] ?></option>
								<?php
					      	}
					       ?>
					    </select>
					</th>					
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<tr data-orden-detalle="0">
					<td>
						<input type="hidden" class="origenimportacion" value=""/>
						<a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a>
						<a class="articulocustom" href="javascript:void(0);" onclick="articulocustom(this)"><i class="fa fa-2x fa-keyboard-o" title="Ingresar Artículo" aria-hidden="true"></i></a>
						<a class="articulotabulado" style="display:none" href="javascript:void(0);" onclick="articulotabulado(this)"><i class="fa fa-2x fa-list-alt" title="Buscar Artículo" aria-hidden="true"></i></a>						
					</td>
					<td>
						<input onchange="calculavaloresindividuales(this)" min="0" class="form-control cantidad" value="1" type="number">
					</td>
					<td>
						<select onchange="cambiaproducto(this)" class="producto form-control">
						</select>
						<input style="display:none" class="productocustom form-control" type="text">
					</td>				
					<td>
						<select class="unidadmedida form-control">
				    	<option value="0">0</option>
				      <?php
				      	$defaultval=1; 
				      	for ($i=0; $i < count($unidadesmedida); $i++) {
				      		if ($unidadesmedida[$i]["id"]==$defaultval) {
				      		 	?>
				      				<option selected value="<?php echo $unidadesmedida[$i]["id"]?>"><?php echo $unidadesmedida[$i]["denominacionCorta"] ?></option>					      		 	
				      		 	<?php
				      		 }else{
				      		 	?>
				      				<option value="<?php echo $unidadesmedida[$i]["id"]?>"><?php echo $unidadesmedida[$i]["denominacionCorta"] ?></option>
				      		 	<?php
				      		 } 
				      		?>
				      		<?php
				      	}
				       ?>							
						</select>
					</td>					
					<td>
						<input onchange="calculavaloresindividuales(this)" value="0" class="impunit form-control" type="number">
					</td>				
					<td>
						<input disabled readonly class="imptotal form-control" type="number" value="0" min="0">
						<input disabled readonly class="impiva form-control" type="hidden" value="0" min="0">
					</td>
					<td>
					    <select disabled readonly onchange="calculavaloresindividuales(this)" class="alicuotaiva form-control">
					      <?php
					      	$defaultval=21; 
					      	for ($i=0; $i < count($alicuotasiva); $i++) {
					      		if ($alicuotasiva[$i]["id"]==$defaultval) {
					      		 	?>
					      				<option selected value="<?php echo $alicuotasiva[$i]["id"]?>"><?php echo $alicuotasiva[$i]["valor"] ?></option>					      		 	
					      		 	<?php
					      		 }else{
					      		 	?>
					      				<option value="<?php echo $alicuotasiva[$i]["id"]?>"><?php echo $alicuotasiva[$i]["valor"] ?></option>
					      		 	<?php
					      		 } 
					      		?>
					      		<?php
					      	}
					       ?>					    	
					    </select>
					</td>					
					<td>
						<input disabled readonly class="impneto form-control" type="number" value="0" min="0">
					</td>			
				</tr>

				<?php 

				for ($i=0; $i < count($movimiento); $i++) {
					if ($movimiento[$i]["detalleorigenimportacion"]!=0 && $movimiento[$i]["detalleorigenimportacion"]!="") {
						?>
						<tr 
							data-recuperar="true"
							data-cantidad-importada="<?php echo $movimiento[$i]["detallecantidadimporto"] ?>"
							data-origen="<?php echo $movimiento[$i]["detalleorigenimportacion"] ?>"
							data-orden-detalle="<?php echo $i+1 ?>">
							<td>
								<input
									type="hidden"
									class="cantidadimporto"
									value="<?php echo $movimiento[$i]["detallecantidadimporto"] ?>"/>
								<input
									type="hidden" class="origenimportacion"
									value="<?php echo $movimiento[$i]["detalleorigenimportacion"] ?>"/>
								<a
									href="javascript:void(0);"
									onclick="eliminardetalle(this)">
										<i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i>
								</a>
							</td>
							<td>
								<input
									min="0"
									disabled readonly
									class="form-control cantidad"
									value="<?php echo $movimiento[$i]["detallecant"] ?>"
									type="number"/>
							</td>
							<td>
								<input
									disabled
									read-only
									class="form-control producto"
									value="<?php echo $movimiento[$i]["detallecodproducto"] ?>"
									type="hidden"/>
								<input
									disabled
									read-only
									class="form-control productocustom"
									value="<?php echo $movimiento[$i]["detallenombreproducto"] ?>"
									type="hidden"/>
								<input
									disabled
									read-only
									class="form-control"
									value="<?php echo $movimiento[$i]["detallenombreproducto"]." ".$movimiento[$i]["articulo"] ?>"
									type="text"/>
							</td>
							<td>
								<input
									disabled
									read-only
									class="form-control unidadmedida"
									value="<?php echo $movimiento[$i]["detalleidunidadmedida"] ?>"
									type="hidden"/>
								<input
									disabled
									read-only
									class="form-control"
									value="<?php echo $movimiento[$i]["denominacionCorta"] ?>"
									type="text"/>
							</td>
							<td>
								<input
									onchange="calculavaloresindividuales(this)"
									class="form-control impunit"
									value="<?php echo $movimiento[$i]["detalleimporteunitario"] ?>"
									type="number"/>
							</td>
							<td>
								<input
									disabled
									read-only
									class="form-control imptotal"
									value="<?php echo $movimiento[$i]["detalleimportetotal"] ?>"
									type="number"/>
								<input
									disabled
									read-only
									class="form-control impiva"
									value="<?php echo $movimiento[$i]["detalleimporteiva"] ?>"
									type="hidden"/>
							</td>
							<td>
								<select
									disabled
									read-only
									class="form-control alicuotaiva"
									id="">
									<option
										value="<?php echo $movimiento[$i]["detallealicuotaiva"] ?>">
										<?php echo $movimiento[$i]["iva"] ?>
									</option>
								</select>
							</td>
							<td>
								<input
									disabled
									read-only
									class="form-control impneto"
									value="<?php echo $movimiento[$i]["detalleimporteneto"] ?>"
									type="number"/>
							</td>
						</tr>							
						<?php 	
					}else{
						?>
							<tr
								data-orden-detalle="<?php echo $i+1 ?>">
								<td>
									<input
										type="hidden"
										class="origenimportacion"
										value=""/>
									<a
										href="javascript:void(0);"
										onclick="eliminardetalle(this)">
										<i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i>
									</a>
									<a
										class="articulocustom"
										href="javascript:void(0);"
										onclick="articulocustom(this)">
										<i class="fa fa-2x fa-keyboard-o" title="Ingresar Artículo" aria-hidden="true"></i>
									</a>
									<a
										class="articulotabulado"
										style="display:none"
										href="javascript:void(0);"
										onclick="articulotabulado(this)">
										<i class="fa fa-2x fa-list-alt" title="Buscar Artículo" aria-hidden="true"></i>
									</a>						
								</td>
								<td>
									<input
										onchange="calculavaloresindividuales(this)"
										min="0"
										class="form-control cantidad"
										value="<?php echo $movimiento[$i]["detallecant"] ?>"
										type="number"/>
								</td>					
								<td>
									<?php 
									if ($movimiento[$i]["detallecodproducto"]==0) {
									?>									
										<select
											style="display:none"
											onchange="importearticulo(this)"
											class="producto form-control">
											<option value="0"></option>
										</select>
										<input
											class="productocustom form-control"
											type="text"
											value=" <?php echo $movimiento[$i]['detallenombreproducto'] ?>"/>
									</td>
									<td>
										<select
											onchange="importearticulo(this)"
											class="unidadmedida form-control">
									    	<option value="0">0</option>
									      <?php
									      	$defaultval=$movimiento[$i]["detalleidunidadmedida"]; 
									      	for ($i2=0; $i2 < count($unidadesmedida); $i2++) {
									      		if ($unidadesmedida[$i2]["id"]==$defaultval) {
									      		 	?>
									      				<option
									      					selected
									      					value="<?php echo $unidadesmedida[$i2]["id"]?>">
									      					<?php echo $unidadesmedida[$i2]["denominacionCorta"] ?>
									      				</option>					      		 	
									      		 	<?php
									      		 }else{
									      		 	?>
									      				<option
									      					value="<?php echo $unidadesmedida[$i2]["id"]?>">
									      					<?php echo $unidadesmedida[$i2]["denominacionCorta"] ?>
									      				</option>
									      		 	<?php
									      		 } 
									      		?>
									      		<?php
									      	}
									       ?>							
										</select>
									</td>														
									<td>
										<input
											onchange="calculavaloresindividuales(this)"
											class="impunit form-control"
											type="number"
											value="<?php echo $movimiento[$i]['detalleimporteunitario'] ?>"/>
									<?php
									}else{
									?>									
										<select
											disabled
											onchange="importearticulo(this)"
											class="producto form-control">
											<option
												value="<?php echo $movimiento[$i]["detallecodproducto"] ?>">
												<?php echo $movimiento[$i]["detallecodproducto"] ." ".$movimiento[$i]["articulo"] ?>
											</option>
										</select>
										<input
											style="display:none"
											class="productocustom form-control"
											type="text"
											value=""/>
									</td>
									<td>
										<select
											onchange="importearticulo(this)"
											class="unidadmedida form-control">
									    	<option value="0">0</option>
									      <?php
									      	$defaultval=$movimiento[$i]["detalleidunidadmedida"]; 
									      	for ($i2=0; $i2 < count($unidadesmedida); $i2++) {
									      		if ($unidadesmedida[$i2]["id"]==$defaultval) {
									      		 	?>
									      				<option
									      					selected
									      					value="<?php echo $unidadesmedida[$i2]["id"]?>">
									      					<?php echo $unidadesmedida[$i2]["denominacionCorta"] ?>
									      				</option>					      		 	
									      		 	<?php
									      		 }else{
									      		 	?>
									      				<option
									      					value="<?php echo $unidadesmedida[$i2]["id"]?>">
									      					<?php echo $unidadesmedida[$i2]["denominacionCorta"] ?>
									      				</option>
									      		 	<?php
									      		 } 
									      		?>
									      		<?php
									      	}
									       ?>							
										</select>
									</td>																		
									<td>
										<input
											onchange="calculavaloresindividuales(this)"
											class="impunit form-control"
											type="number"
											value="<?php echo $movimiento[$i]['detalleimporteunitario'] ?>"/>
									<?php
									}
									 ?>

								</td>
								<td>
									<input
										disabled
										readonly
										class="imptotal form-control"
										type="number"
										value="<?php echo $movimiento[$i]['detalleimportetotal'] ?>"
										min="0"/>
									<input
										disabled
										readonly
										class="impiva form-control"
										type="hidden"
										value="<?php echo $movimiento[$i]['detalleimporteiva'] ?>"
										min="0"/>
								</td>												
								<td>
								    <select
								    	onchange="calculavaloresindividuales(this)"
								    	class="alicuotaiva form-control">
								      <?php
								      	$defaultval= $movimiento[$i]["detallealicuotaiva"]; 
								      	for ($i2=0; $i2 < count($alicuotasiva); $i2++) {
								      		if ($alicuotasiva[$i2]["id"]==$defaultval) {
								      		 	?>
								      				<option
								      					selected
								      					value="<?php echo $alicuotasiva[$i2]["id"]?>">
								      					<?php echo $alicuotasiva[$i2]["valor"] ?>
								      				</option>					      		 	
								      		 	<?php
								      		 }else{
								      		 	?>
								      				<option
								      					value="<?php echo $alicuotasiva[$i2]["id"]?>">
								      					<?php echo $alicuotasiva[$i2]["valor"] ?>
								      				</option>
								      		 	<?php
								      		 } 
								      		?>
								      		<?php
								      	}
								       ?>					    	
								    </select>
								</td>

								<td>
									<input
										disabled
										readonly
										class="impneto form-control"
										type="number"
										value="<?php echo $movimiento[$i]['detalleimporteneto'] ?>"
										min="0"/>
								</td>					
							</tr>
						<?php
					} 
				}
				?>
			</tbody>
		</table>
	</div>
	<!-- Fin Detalle-->


	<!-- Modal -->
	<div class="modal fade" id="modalimportar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					
					<!-- Select Basic -->
					<div class="form-group">
					  <label class="col-sm-4 control-label" for="selectbasic">Importar de Comprobante</label>
					  <div class="col-sm-8">
					    <select id="importar-comprobante" name="comprobante" class="form-control comprobante-importar" onchange="movimientosparaimportar()">
							<option value="0">Seleccione</option>
					      <?php
					      	$defaultval=1; 
					      	for ($i=0; $i < count($comprobantes); $i++) {
					      		if ($comprobantes[$i]["id"]==$defaultval) {
					      		 	?>
					      				<option selected value="<?php echo $comprobantes[$i]["id"]?>"><?php echo $comprobantes[$i]["denominacion"] ?></option>					      		 	
					      		 	<?php
					      		 }else{
					      		 	?>
					      				<option value="<?php echo $comprobantes[$i]["id"]?>"><?php echo $comprobantes[$i]["denominacion"] ?></option>
					      		 	<?php
					      		 } 
					      		?>
					      		<?php
					      	}
					       ?>							
					    </select>
					  </div>
					</div>					
				</div>
				<div class="modal-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<th></th>
								<th>Nro</th>
								<th>Fecha</th>
								<th>Importe</th>
								<th>Items Disponibles</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>      	
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modal-pendientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
	        <h5 class="modal-title">Movimientos con items pendientes</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
				</div>
				<div class="modal-body">

					<table id="movimientos-pendientes" class="table">
						<thead>
							<tr>
								<th></th>
								<th>Nro</th>
								<th>Fecha</th>
								<th>Importe</th>
								<th>Items Disponibles</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>			

	
</div>

<div id="contenedor-contador-detalles">
	<h4 >Cant detalles: <span id="contador-detalles">0</span></h4>
</div>


<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="librerias/js/fileupload/vendor/jquery.ui.widget.js"></script>    

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="librerias/js/fileupload/jquery.iframe-transport.js"></script>

<!-- The basic File Upload plugin -->
<script src="librerias/js/fileupload/jquery.fileupload.js"></script>

<script src="librerias/js/misc/movimientos.js"></script>

</body>
</html>



<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$movimientoseditcustom_php->Page_Terminate();
?>
