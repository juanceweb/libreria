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

$actualizacionmasiva_php = NULL; // Initialize page object first

class cactualizacionmasiva_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'actualizacionmasiva.php';

	// Page object name
	var $PageObjName = 'actualizacionmasiva_php';

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
			define("EW_TABLE_NAME", 'actualizacionmasiva.php', TRUE);

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
		$Breadcrumb->Add("custom", "actualizacionmasiva_php", $url, "", "actualizacionmasiva_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($actualizacionmasiva_php)) $actualizacionmasiva_php = new cactualizacionmasiva_php();

// Page init
$actualizacionmasiva_php->Page_Init();

// Page main
$actualizacionmasiva_php->Page_Main();

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

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" class="inputfile" accept=".csv"/ style="
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;">
	<label class="btn btn-primary ewButton" for="file"><span>Seleccionar Archivo</span></label>
	<input type="submit" name="submit" class="btn btn-primary ewButton" />
	<input type="button"class="btn btn-primary ewButton" onclick="actualizacionMasivaPrecios()" value="Actualizar"/>
</form>

<?php 

$phpFileUploadErrors = array(
	0 => 'There is no error, the file uploaded with success',
	1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
	2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	3 => 'The uploaded file was only partially uploaded',
	4 => 'No file was uploaded',
	6 => 'Missing a temporary folder',
	7 => 'Failed to write file to disk.',
	8 => 'A PHP extension stopped the file upload.',
);

if ( isset($_POST["submit"]) ) {

	if ( isset($_FILES["file"])) {

		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $phpFileUploadErrors[$_FILES["file"]["error"]] . "<br />";
		}
		else {

			$ext = end((explode(".", $_FILES["file"]["name"])));

			if ($ext == "csv") {

				?>

				<table class="table ewTable" style="margin-top:20px;width:50%;min-width:400px">
					<tbody>
						<tr>
							<td>Nombre de Archivo</td>
							<td><?php echo $_FILES["file"]["name"] ?></td>
						</tr>
						<tr>
							<td>Tipo</td>
							<td><?php echo $_FILES["file"]["type"] ?></td>
						</tr>
						<tr>
							<td>Tamaño</td>
							<td><?php echo ($_FILES["file"]["size"] / 1024) . " Kb" ?></td>
						</tr>

						<?php

						$storagename = uniqid().".csv";
						move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);

						?>

						<tr>
							<td>Guardado como:</td>
							<td><?php echo "upload/" . $storagename ?></td>
						</tr>																			
					</tbody>
				</table>				

				<?php

				$fila = 1;
				$registros = array();
				$codigos = array();

				if (($gestor = fopen("upload/" . $storagename, "r")) !== FALSE) {
				    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {

			        $fila++;
			        $registros[$datos[0]] = $datos[1];
			        array_push($codigos, $datos[0]);

				    }

				  fclose($gestor);

				  //var_dump($registros);

				  $sql="SELECT `articulos-proveedores`.codExterno,
					  monedas.simbolo,
					  `articulos-proveedores`.precio,
					  terceros.denominacion,
					  `articulos-proveedores`.precioPesos,
					  `articulos-proveedores`.ultimaActualizacion,
					  articulos.id,
					  articulos.denominacionExterna,
					  articulos.denominacionInterna,
					  articulos.calculoPrecio,
					  articulos.rentabilidad,
					  articulos.precioVenta
					FROM articulos
					  INNER JOIN `articulos-proveedores`
					    ON articulos.id = `articulos-proveedores`.idArticulo
					  INNER JOIN monedas ON `articulos-proveedores`.idMoneda = monedas.id
					  INNER JOIN terceros ON terceros.id = `articulos-proveedores`.idTercero
					  WHERE `articulos-proveedores`.codExterno IN('".implode("','", $codigos)."')";

					$rs = ew_LoadRecordset($sql);
					$rows = $rs->GetRows();

					//var_dump($rows);

				}

								

				?>
				<div class="ewGrid">
					
				<table class="table ewTable">
					<thead>
						<tr>
							<th>
								Seleccionar
								<input type="checkbox" checked onclick="seleccionarTodosArticulos(this)">
							</th>
							<th>
								Código Externo
							</th>
							<th>
								Denominación Externa
							</th>
							<th>
								Denominación Interna
							</th>
							<th>
								Proveedor
							</th>
							<th>
								Cálculo
							</th>		
							<th>
								Precio Actual
							</th>
							<th>
								Nuevo Precio
							</th>
							<th>
								Diferencia Precio
							</th>									
							<th>
								Rentabilidad
							</th>
							<th>
								Precio Venta
							</th>
							<th>
								Nuevo Precio Venta
							</th>
							<th>
								Nueva Rentabilidad
							</th>																																																		
						</tr>
					</thead>
					<tbody>
						<?php

							for ($i=0; $i < count($rows); $i++) { 
								?>
								<tr>
									<td><input checked class="seleccionar-articulo" type="checkbox" data-id="<?php echo $rows[$i]["codExterno"] ?>" data-precio="<?php echo $registros[$rows[$i]["codExterno"]] ?>"></td>
									<td><?php echo $rows[$i]["codExterno"] ?></td>
									<td><?php echo $rows[$i]["denominacionExterna"] ?></td>
									<td><?php echo $rows[$i]["denominacionInterna"] ?></td>
									<td><?php echo $rows[$i]["denominacion"] ?></td>
									<td><?php echo $rows[$i]["calculoPrecio"] == 1?"Porcentual":"Precio Fijo" ?></td>
									<td><?php echo $rows[$i]["simbolo"]." ".$rows[$i]["precio"] ?></td>
									<td><?php echo $rows[$i]["simbolo"]." ". $registros[$rows[$i]["codExterno"]] ?></td>

									
									<?php

										$diferencia = round($registros[$rows[$i]["codExterno"]] - $rows[$i]["precio"],2);
										$porcentaje = round( (($registros[$rows[$i]["codExterno"]]/$rows[$i]["precio"])-1)*100 ,2);

										if ($diferencia > 0) {
											$color = "red";
										}else if ($diferencia < 0){
											$color = "green";
										}else{
											$color = "orange";
										}

									?>

									<td style="color:<?php echo $color	?>"><?php echo $rows[$i]["simbolo"]." ". $diferencia." (%".$porcentaje.")"  ?></td>
									<td><?php echo $rows[$i]["rentabilidad"] ?> %</td>
									<td><?php echo $rows[$i]["precioVenta"] ?></td>
									<td><?php echo $rows[$i]["calculoPrecio"] == 1?$registros[$rows[$i]["codExterno"]] + $registros[$rows[$i]["codExterno"]] * $rows[$i]["rentabilidad"] / 100:$registros[$rows[$i]["codExterno"]] ?></td>
									<td style="<?php echo $rows[$i]["calculoPrecio"] == 2?'color:'.$color:""?>"><?php echo $rows[$i]["calculoPrecio"] == 2? round((($rows[$i]["precioVenta"] / $registros[$rows[$i]["codExterno"]]) - 1) * 100 ,2) :$rows[$i]["rentabilidad"] ?> %</td>

								</tr>
								<?php
							}		

						?>
					</tbody>
				</table>

				</div>

				<?php
			
			}else{
				echo "Solo se permiten archivos con extensión csv";
			}

		}
	} else {
		echo "No se seleccionó ningún archivo <br />";
	}
}

?>

<script>
	
var inputs = document.querySelectorAll( '.inputfile' );
Array.prototype.forEach.call( inputs, function( input )
{
	var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		else
			fileName = e.target.value.split( '\\' ).pop();

		if( fileName )
			label.querySelector( 'span' ).innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
});


function seleccionarTodosArticulos(elemento){
	if ($(elemento).is(":checked")) {
		$(".seleccionar-articulo").prop('checked', true);
	}else{
		$(".seleccionar-articulo").prop('checked', false);
	};
}

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
    type: "get",
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

</script>


<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$actualizacionmasiva_php->Page_Terminate();
?>
