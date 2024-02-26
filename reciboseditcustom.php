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

$reciboseditcustom_php = NULL; // Initialize page object first

class creciboseditcustom_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'reciboseditcustom.php';

	// Page object name
	var $PageObjName = 'reciboseditcustom_php';

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
			define("EW_TABLE_NAME", 'reciboseditcustom.php', TRUE);

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
		$Breadcrumb->Add("custom", "reciboseditcustom_php", $url, "", "reciboseditcustom_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($reciboseditcustom_php)) $reciboseditcustom_php = new creciboseditcustom_php();

// Page init
$reciboseditcustom_php->Page_Init();

// Page main
$reciboseditcustom_php->Page_Main();

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

if (isset($_SESSION["modo"])) {
	$modo=$_SESSION["modo"];
}else{
	header("Location: ../login.php");
	exit();
};

$todo=obtieneRecibo($_GET["id"]);
$adelantos=obtieneAdelantos($_GET["id"]);
$movimientos=obtieneMovimientosRecibos($_GET["id"]);
$terceros=obtieneTerceros();
$mediosdepago=obtieneMediosPagos();


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Recibos-Pagos</title>

	<link rel="stylesheet" href="librerias/css/font-awesome-4.7.0/font-awesome.min.css">

	<link href="librerias/css/select2-4.0.4/select2.min.css" rel="stylesheet" />
	<script src="librerias/js/select2-4.0.4/select2.full.min.js"></script>


	<style>
	
	.form-control{
		max-width: 100%;
		width: 100%;
	}

	.select2-container .select2-selection--single{
		height: 35px;
	}
	.resultado{
		margin-right: 10px;
	}
	.codigo{
		float: left;
		width: 50px;
	}
	.cantidad{
		max-width: 100px;
	}
	.impunit, .imptotal, .impiva, .impneto, .bonif{
		max-width: 120px;
	}
	.oculto{
		display: none;
	}

	.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
		padding: 3px;
	}

	</style>

</head>



<body>

	<input type="hidden" id="idcabecera" value="<?php echo $_GET["id"] ?>">

	<!-- Encabezado-->
	<div class="row">
		<div class="">
			
			<form class="form-horizontal">
				<fieldset>
					<!-- Primera columna-->
					<div class="col-sm-4">


						<!-- Select Basic -->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="selectbasic">Tipo Recibo/Pago</label>
							<div class="col-sm-8">
								<select disabled readonly onchange="codcheque()" id="tiporecibopago" name="tiporecibopago" class="form-control">
									<?php 
									if ($todo[0]["tipoFlujo"]==1) {
										?>
										<option selected value="1">Recibo</option>
										<option value="2">Pago</option>
										<?php									
									}else{
										?>
										<option value="1">Recibo</option>
										<option selected value="2">Pago</option>
										<?php											
									}

									?>
								</select>
							</div>
						</div>

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="textinput">Nro Comprobante</label>  
							<div class="col-sm-8">
								<input id="nrocomprobante" name="nrocomprobante" type="number" placeholder="Nro Comprobante" class="form-control input-md" value="<?php echo $todo[0]["nroComprobante"] ?>">
							</div>
						</div>														

						<!-- Select Basic -->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="selectbasic">Tercero</label>
							<div class="col-sm-8">
								<select disabled readonly id="tercero" name="tercero" class="form-control">
									<option value="0">Seleccione</option>
									<?php
									$defaultval=$todo[0]["idTercero"]; 
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

			



					</div>
					<!-- Fin Primera Columna-->

					<!-- Segunda Columna-->
					<div class="col-sm-4">

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="textinput">Fecha</label>  
							<div class="col-sm-8">
								<input id="fecha" name="fecha" type="date" placeholder="Fecha" class="form-control input-md" value="<?php echo date('Y-m-d', strtotime($todo[0]["fecha"])) ?>">
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

										if ($todo[0]["contable"]==1) {
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

					</div>
					<!-- Fin Segunda Columna-->

					<!-- Tercera Columna-->	
					<div class="col-sm-4">

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="prependedtext">Importe Total</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input disabled  id="importetotal"  name="importetotal" class="form-control" placeholder="Importe Total" type="number" value="<?php echo $todo[0]["importe"] ?>">
								</div>
							</div>
						</div>

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="prependedtext">Adelantos</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input id="sumaadelantos"  disabled readonly name="sumaadelantos" class="form-control" placeholder="Suma Adelantos" type="number" value="0">
								</div>
							</div>
						</div>

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="prependedtext">Movimientos</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input id="sumamovimientos"  disabled readonly name="sumamovimientos" class="form-control" placeholder="Suma Movimientos" type="number" value="0">
								</div>
							</div>
						</div>						

						<!-- Text input-->
						<div class="form-group">
							<label class="col-sm-4 control-label" for="prependedtext">Diferencia</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input id="diferencia"  disabled readonly name="diferencia" class="form-control" placeholder="Diferencia" type="number" value="0">
								</div>
							</div>
						</div>							

					</div>
					<!-- Fin Tercera Columna-->

				</fieldset>
			</form>
		</div>
	</div>
	<!-- Fin Encabezado-->


	<div class="">

		<div class="row">
			<div style="padding:10px" class="col-md-6">
				<button id="listar" class="btn btn-primary">Listar Adelantos y Movimientos</button>
				<button onclick="editarrecibo()" id="guardar" class="btn btn-primary">Guardar</button>
			</div>
			<div style="padding:10px" class="col-md-6">
				<div id="mensaje" style="width:100%" class="alert oculto alert-dismissible" role="alert">
					<button onclick="ocultarmensaje()" type="button" class="close"><span>&times;</span></button>
					<p id="textomensaje">
						
					</p>
				</div>			
			</div>		
		</div>

		<!-- Movimientos-->
		<div class="row">
			<div class="col-xs-12">
			
				<h4>Movimientos</h4>			
				<table id="movimientos" class="table table-condensed" style="">
					<thead>
						<tr>
							<th></th>
							<th>Código</th>
							<th>Tercero</th>
							<th>Tipo Comp</th>
							<th>Fecha</th>
							<th>Nro Comp</th>
							<th>Importe Total</th>
							<th>Importe Cancelado</th>
							<th>Saldo</th>						
							<th>Importe A Cancelar</th>					
						</tr>
					</thead>
					<tbody>

						<?php 

						for ($i=0; $i < count($movimientos) ; $i++) {
							?> 
							<tr data-orden-detallemovimientos="<?php echo $i ?>">
								<td><a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a></td>
								<td><input disabled readonly value="<?php echo $movimientos[$i]["id1"] ?>" class="codigocomp form-control input-sm" type="number"></td>
								<td><input disabled readonly value="<?php echo $movimientos[$i]["denominacion"] ?>" class="tipocomp form-control input-sm" type="text"></td>
								<td><input disabled readonly value="<?php echo date('Y-m-d', strtotime($movimientos[$i]["fecha"])) ?>" class="fechacomp form-control input-sm" type="date"></td>
								<td><input disabled readonly value="<?php echo $movimientos[$i]["ptoVenta"]."-".$movimientos[$i]["nroComprobante"] ?>" class="nrocomp form-control input-sm" type="text"></td>
								<td><input disabled readonly value="<?php echo $movimientos[$i]["importeNeto"] ?>" class="importetotalcomp form-control input-sm" type="number"></td>
								<td><input disabled value="<?php echo $movimientos[$i]["importeCancelado"] ?>" min="<?php echo $movimientos[$i]["importeCancelado"] ?>" max="<?php echo $movimientos[$i]["importeCancelado"] ?>" class="importeyacancelado form-control input-sm" type="number"></td>									
								<td><input disabled value="<?php echo $movimientos[$i]["importeNeto"]-$movimientos[$i]["importeCancelado"] ?>" class="saldo form-control input-sm" type="number"></td>								
								<td><input value="<?php echo $movimientos[$i]["importeACancelar"] ?>" data-comportamiento="<?php echo $movimientos[$i]["comportamiento"] ?>" max="<?php echo $movimientos[$i]["importeNeto"]-$movimientos[$i]["importeCancelado"] ?>" onchange="sumamovimientos(this)" class="importecancelado form-control input-sm" type="number"></td>
							</tr>
							<?php
						}

						?>

					</tbody>
				</table>				
			</div>
		</div>
		<!-- Fin Movimientos-->

		<!-- Adelantos-->
		<div class="row">
			<div class="col-xs-12">
				<h4>Adelantos</h4>

				<table id="adelantos" class="table table-condensed" style="" >
					<thead>
						<tr>
							<th></th>
							<th>Código</th>
							<th>Fecha</th>
							<th>Importe Total</th>
						</tr>
					</thead>
					<tbody>

						<?php 

						for ($i=0; $i < count($adelantos); $i++) { 
							?>

							<tr data-orden-detalleadelanto="<?php echo $i ?>">
								<td><a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a></td>
								<td><input disabled readonly value="<?php echo $adelantos[$i]["idadelanto"] ?>" class="codigoadelanto form-control input-sm" type="number"></td>
								<td><input disabled readonly value="<?php echo date('Y-m-d', strtotime($adelantos[$i]["fecha"])) ?>" class="fechaadelanto form-control input-sm" type="date"></td>
								<td><input disabled readonly value="<?php echo $adelantos[$i]["importe"] ?>" class="importetotaladelanto form-control input-sm" type="number"></td>
							</tr>

							<?php
						}

						?>

					</tbody>
				</table>				
			</div>

		</div>
		<!-- Fin Adelantos-->


		<!-- Panel central-->
		<div class="row">
			<div class="col-xs-12">
				<h4>Pagos</h4>

				<!-- Pagos-->
				<div class="">
					<table id="pagos" class="table table-condensed" >
						<thead>
							<tr>
								<th>Medio Pago</th>
								<th>Importe</th>
								<th class="columnacodcheque" style="display:none">Cód cheque</th>
								<th>Banco</th>
								<th>Nro</th>
								<th>Fecha</th>
								<th>Observaciones</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$observaciones = obtieneObservacionesCheques();

							$cantidaddetalles=10;
							for ($detalle=0; $detalle < $cantidaddetalles; $detalle++) { 
								?>
								<tr data-detalle-orden="<?php echo $detalle ?>">
									<td>
										<select onchange="mediosdepagos(this)" id="" name="tercero" class="mediopago form-control">
											<option value="0">Seleccione</option>
											<?php
											$defaultval= (isset($todo[$detalle]["idMedioPago"]) ? $todo[$detalle]["idMedioPago"] : 1);

											for ($i=0; $i < count($mediosdepago); $i++) {
												if ($mediosdepago[$i]["id"]==$defaultval) {
													?>
													<option selected value="<?php echo $mediosdepago[$i]["id"]?>"><?php echo $mediosdepago[$i]["denominacion"] ?></option>					      		 	
													<?php
												}else{
													?>
													<option value="<?php echo $mediosdepago[$i]["id"]?>"><?php echo $mediosdepago[$i]["denominacion"] ?></option>
													<?php
												} 
												?>
												<?php
											}
											?>					      
										</select>
									</td>
									<td>
										<input id="" onchange="calculavaloresindividuales()" name="importetotal" class="importetotal form-control" placeholder="Importe Total" type="number" value="<?php echo (isset($todo[$detalle]["importe1"]) ? $todo[$detalle]["importe1"] : 0) ?>">
									</td>
									<td class="columnacodcheque" style="display:none">
										<input type="number" disabled class="codcheque form-control" value="<?php echo (isset($todo[$detalle]["codcheque"]) ? $todo[$detalle]["codcheque"] : 0) ?>">
									</td>
									<td>
										<input id="" disabled name="banco" class="banco form-control" placeholder="Banco" type="text" value="<?php echo (isset($todo[$detalle]["banco"]) ? $todo[$detalle]["banco"] : 0) ?>">
									</td>
									<td>
										<input id="" disabled name="nro" class="nro form-control" placeholder="nro" type="text" value="<?php echo (isset($todo[$detalle]["numero"]) ? $todo[$detalle]["numero"] : 0) ?>">
									</td>						
									<td>
										<input id="" disabled name="fecha" class="fecha form-control" placeholder="Fecha" type="date" value="<?php echo (isset($todo[$detalle]["fecha1"]) ? date('Y-m-d', strtotime($todo[$detalle]["fecha1"])) : 0) ?>">
									</td>
									<td>
										<select disabled id="" name="observaciones" class="observaciones form-control">
											<option value="">Seleccione</option>
											<?php
											$defaultval= (isset($todo[$detalle]["observaciones"]) ? $todo[$detalle]["observaciones"] : 1);

											for ($i=0; $i < count($mediosdepago); $i++) {
												if ($observaciones[$i]["id"]==$defaultval) {
													?>
													<option selected value="<?php echo $observaciones[$i]["id"]?>"><?php echo $observaciones[$i]["observacion"] ?></option>					      		 	
													<?php
												}else{
													?>
													<option value="<?php echo $observaciones[$i]["id"]?>"><?php echo $observaciones[$i]["observacion"] ?></option>
													<?php
												} 
												?>
												<?php
											}
											?>					      
										</select>									</td>							
								</tr>
								<?php 
							}
							?>
						</tbody>
					</table>
				</div>
				<!-- Fin Pagos-->					
			</div>
		</div>








		<!-- Modal -->
		<div class="modal fade" id="modalcheques" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Cheques disponibles</h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th></th>
									<th>Cód</th>
									<th>Nro</th>
									<th>Banco</th>
									<th>Importe</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="cancelarmodal()" class="btn btn-default" data-dismiss="modal">Cancelar</button>      	
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
	<script src="librerias/js/fileupload/vendor/jquery.ui.widget.js"></script>    

	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="librerias/js/fileupload/jquery.iframe-transport.js"></script>

	<!-- The basic File Upload plugin -->
	<script src="librerias/js/fileupload/jquery.fileupload.js"></script>

	<script src="librerias/js/misc/recibos.js"></script>

	<script>

	$(document).ready(function(){
		chequesdisponibles();

		localStorage.setItem("preguntar",1);


	if ($("#tiporecibopago").val()==2) {
		$(".columnacodcheque").css("display","block");

	}else{
		$(".columnacodcheque").css("display","none");

	};

	calculavaloresindividuales();
	sumamovimientos();


})


	</script>

</body>
</html>


<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$reciboseditcustom_php->Page_Terminate();
?>
