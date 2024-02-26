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

$terceroshorarios_php = NULL; // Initialize page object first

class cterceroshorarios_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'terceroshorarios.php';

	// Page object name
	var $PageObjName = 'terceroshorarios_php';

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
			define("EW_TABLE_NAME", 'terceroshorarios.php', TRUE);

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
		$Breadcrumb->Add("custom", "terceroshorarios_php", $url, "", "terceroshorarios_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($terceroshorarios_php)) $terceroshorarios_php = new cterceroshorarios_php();

// Page init
$terceroshorarios_php->Page_Init();

// Page main
$terceroshorarios_php->Page_Main();

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

$hoy=obtenerDiaSemana();

$sql="SELECT * FROM `terceros-horarios`
WHERE idTercero='".$_GET["idTercero"]."'
ORDER BY 'horaDesde'";

$rs = ew_LoadRecordset($sql);
$rows = $rs->GetRows();

$resultado=array();

if (count($rows)>0) {
	for ($i=0; $i < count($rows); $i++) {
		$rows[$i]["horaDesde"]=date("H:i",strtotime($rows[$i]["horaDesde"]));
		$rows[$i]["horaHasta"]=date("H:i",strtotime($rows[$i]["horaHasta"]));

		if (!array_key_exists($rows[$i]["dia"], $resultado)) {
			$resultado[$rows[$i]["dia"]]=array(); 	
		}
		array_push($resultado[$rows[$i]["dia"]], $rows[$i]);
	}
}

$cantidadrepeticiones=2;

?>
<div class="ewGrid" id="horarios">
	<table class="table ewTable">
		<thead>
			<tr class="ewTableHeader">
				<th></th>
				<?php 
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<th>Desde</th>
							<th>Hasta</th>
						<?php
					}
				?>			
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<label id="" for="" class="control-label ewLabel">Todos los días</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="todos" data-tipo="desde" data-orden="<?php echo $i ?>" value="" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="todos" data-tipo="hasta" data-orden="<?php echo $i ?>" value="" class="form-control">				
							</td>
						<?php
					}
				?>																					
			</tr>
			<tr>
				<td>
					<label id="" for="" class="control-label ewLabel">De Lun a Vie</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="lav" data-todos="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="lav" data-todos="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="" class="form-control">				
							</td>
						<?php
					}
				?>																					
			</tr>
			<?php
				$dia="Monday";
			?>
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Lunes</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																										
			</tr>
			<?php
				$dia="Tuesday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Martes</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																				
			</tr>
			<?php
				$dia="Wednesday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Miércoles</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																					
			</tr>
			<?php
				$dia="Thursday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Jueves</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																					
			</tr>
			<?php
				$dia="Friday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Viernes</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-lav="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																				
			</tr>
			<?php
				$dia="Saturday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Sábados</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																				
			</tr>
			<?php
				$dia="Sunday";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Domingos</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																					
			</tr>
			<?php
				$dia="Feriado";
			?>			
			<tr <?php echo $dia==$hoy?'style="background-color:rgba(0,200,0,0.3)"':''?>>
				<td>
					<label id="" for="" class="control-label ewLabel">Feriados</label>			
				</td>
				<?php
					for ($i=0; $i < $cantidadrepeticiones; $i++) { 
						?>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="desde" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaDesde"]:'' ?>" class="form-control">				
							</td>
							<td>
								<input type="time" data-dia="<?php echo $dia ?>" data-todos="true" data-tipo="hasta" data-orden="<?php echo $i ?>" value="<?php echo isset($resultado[$dia][$i]["horaDesde"])? $resultado[$dia][$i]["horaHasta"]:'' ?>" class="form-control">				
							</td>
						<?php
					}
				?>																							
			</tr>																	
		</tbody>
	</table>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<button class="btn btn-primary ewButton" onclick="guardar()">Guardar</button>
		<a class="btn btn-default ewButton" href="terceroslist.php">Cancelar</a>
	</div>
</div>

<script>

	$("#horarios input").change(function(){
		$(this).removeClass("input-error");
	})

	$("[data-dia='todos']").change(function(){
		$("[data-todos='true'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val($(this).val());
		$("[data-todos='true'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").removeClass("input-error");		
	})

	$("[data-dia='lav']").change(function(){
		$("[data-lav='true'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val($(this).val());
		$("[data-lav='true'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").removeClass("input-error");		
	})

	$("[data-lav='true']").change(function(){
		if(comprobarcamposlav($(this).attr('data-tipo'),$(this).attr('data-orden'),$(this).val())===true){
			$("[data-dia='lav'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val($(this).val());			
		}else{
			$("[data-dia='lav'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val('');			
		};
	})

	$("[data-todos='true']").change(function(){
		if(comprobarcampostodos($(this).attr('data-tipo'),$(this).attr('data-orden'),$(this).val())===true){
			$("[data-dia='todos'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val($(this).val());			
		}else{
			$("[data-dia='todos'][data-tipo="+$(this).attr('data-tipo')+"][data-orden="+$(this).attr('data-orden')+"]").val('');			
		};
	})

	function comprobarcamposlav(tipo, orden, valor){
		var diferencias=0;
		$("[data-lav='true'][data-tipo="+tipo+"][data-orden="+orden+"]").each(function(i, obj) {
			if (valor !== $(this).val()) {
				diferencias ++;
			};
		});

		if (diferencias > 0) {
			return false;
		}else{
			return true;
		};
	}		

	function comprobarcampostodos(tipo, orden, valor){
		var diferencias=0;
		$("[data-todos='true'][data-tipo="+tipo+"][data-orden="+orden+"]").each(function(i, obj) {
			if (valor !== $(this).val()) {
				diferencias ++;
			};
		});

		if (diferencias > 0) {
			return false;
		}else{
			return true;
		};
	}

	function guardar(){
		var guardar={};
		var errores=0;
		var numerador=0;
		$("#horarios tbody input[data-tipo='desde']").each(function(i, obj) {
			if ($(this).attr("data-dia") != "todos" && $(this).attr("data-dia") != "lav") {
				if ($(this).val()!="") {
					var registro={};
					registro["dia"]=$(this).attr("data-dia");
					registro["horaDesde"]=$(this).val();
					if ($("[data-tipo='hasta'][data-dia="+$(this).attr('data-dia')+"][data-orden="+$(this).attr('data-orden')+"]").val() != "" && $("[data-tipo='hasta'][data-dia="+$(this).attr('data-dia')+"][data-orden="+$(this).attr('data-orden')+"]").val() > $(this).val()) {
						registro["horaHasta"]=$("[data-tipo='hasta'][data-dia="+$(this).attr('data-dia')+"][data-orden="+$(this).attr('data-orden')+"]").val();
					}else{
						$("[data-tipo='hasta'][data-dia="+$(this).attr('data-dia')+"][data-orden="+$(this).attr('data-orden')+"]").addClass("input-error");
						errores ++;
					};
					guardar[numerador]=registro;
					numerador ++;
				};
			};
		});

		if (errores === 0) {
		$(".spinner").show();

		var accion="horarios-terceros";
		var idtercero=<?php echo $_GET["idTercero"] ?>

		$.ajax({
			url: "./api.php",
			type: "get",
			//crossDomain: true,
			data: {accion:accion,guardar:guardar,idtercero:idtercero},
			//dataType: "json",
			success: function (response) {
				window.location = "terceroslist.php";
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});			
		};
	}

	$(document).ready(function(){
		$("[data-dia='Monday']").trigger("change");
	})



</script>


<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$terceroshorarios_php->Page_Terminate();
?>
