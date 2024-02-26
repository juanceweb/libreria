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

$modulocotizacion2_php = NULL; // Initialize page object first

class cmodulocotizacion2_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'modulocotizacion2.php';

	// Page object name
	var $PageObjName = 'modulocotizacion2_php';

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
			define("EW_TABLE_NAME", 'modulocotizacion2.php', TRUE);

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
		$Breadcrumb->Add("custom", "modulocotizacion2_php", $url, "", "modulocotizacion2_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($modulocotizacion2_php)) $modulocotizacion2_php = new cmodulocotizacion2_php();

// Page init
$modulocotizacion2_php->Page_Init();

// Page main
$modulocotizacion2_php->Page_Main();

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
include_once("funciones.php");

$terceros=obtieneTerceros();
$alicuotasiva=obtieneAlicuotasIva();
$unidadesmedida=obtieneUnidadesMedida();

if (isset($_SESSION["modo"])) {
	$modo=$_SESSION["modo"];
}else{
	header("Location: ../login.php");
	exit();
};

 ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <link rel="stylesheet" href="librerias/css/font-awesome-4.7.0/font-awesome.min.css">

    <link href="librerias/css/select2-4.0.4/select2.min.css" rel="stylesheet" />
    <script src="librerias/js/select2-4.0.4/select2.full.min.js"></script>


    <style>
    .form-control {
        max-width: 100%;
        width: 100%;
    }

    .resultado {
        margin-right: 10px;
    }

    .codigo {
        float: left;
        width: 100px;
    }

    .oculto {
        display: none;
    }

    .form-control {
        padding: 1px 1px !important;
    }

    #contenedor-contador-detalles {
        padding: 3px;
        border: thin #999;
        position: fixed;
        top: 381px;
        left: 15px;
        border-style: none none dotted none;
    }

    #contenedor-contador-detalles h4 {
        margin: 0;
    }

    .detalle .row:first-child {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .detalle:nth-child(2n) .row {
        background: rgb(230, 230, 230);
    }

    @media (min-width: 768px) {

        input[type=text]:not([size]):not([name=pageno]):not(.cke_dialog_ui_input_text),
        input[type=password]:not([size]) {
            min-width: 100% !important;
        }
    }

    .bold {
        font-weight: bold;
    }

    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 30px;
        height: 17px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 13px;
        width: 13px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(13px);
        -ms-transform: translateX(13px);
        transform: translateX(13px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 17px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    </style>

</head>



<body>


    <div class="container" style="width:100%; margin-bottom: 200px;">

        <input type="hidden" id="idcotizacion" value="">

        <!-- Encabezado-->
        <div class="row">
            <form class="form-horizontal">
                <fieldset>
                    <!-- Primera columna-->
                    <div class="col-sm-4">

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">Fecha</label>
                            <div class="col-sm-8">
                                <input id="fecha" name="fecha" type="date" placeholder="Fecha"
                                    class="form-control input-md" value="<?php echo date('Y-m-d') ?>">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="vigencia">Días de vigencia</label>
                            <div class="col-sm-8">
                                <input id="vigencia" name="vigencia" type="number" placeholder="Vigencia"
                                    class="form-control input-md" value="7">
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
                            <label class="col-sm-4 control-label" for="contable">Contable</label>
                            <div class="col-sm-8">
                                <label class="checkbox-inline" for="contable">
                                    <input type="checkbox" checked name="contable" id="contable" value="1">
                                    SI
                                </label>
                            </div>
                        </div>
                        <?php 
						}
					?>

                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="selectbasic">Tercero <a id="ficha-tercero"
                                    style="display:none" target="_blank" title="Ver Ficha" href="">Ver Ficha <i
                                        class="fa fa-table" aria-hidden="true"></i></a></label>

                            <div class="col-sm-8">
                                <select id="tercero" name="tercero" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <?php
					      	$defaultval=0; 
					      	for ($i=0; $i < count($terceros); $i++) {
					      		if ($terceros[$i]["id"]==$defaultval) {
					      		 	?>
                                    <option selected value="<?php echo $terceros[$i]["id"]?>">
                                        <?php echo $terceros[$i]["denominacion"] ?></option>
                                    <?php
					      		 }else{
					      		 	?>
                                    <option value="<?php echo $terceros[$i]["id"]?>">
                                        <?php echo $terceros[$i]["denominacion"] ?></option>
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
                            <label class="col-sm-4 control-label" for="prependedtext">Estado de Cuenta</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="estadocuenta" disabled readonly name="estadocuenta" class="form-control"
                                        placeholder="Estado de Cuenta" type="text">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="prependedtext">Límite Descubierto</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="limitedescubierto" disabled readonly name="limitedescubierto"
                                        class="form-control" placeholder="Límite Descubierto" type="text">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="prependedtext">Cond Venta</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input style="min-width: 250px;" id="condicionventa" name="condicionventa"
                                        class="form-control" placeholder="Condición de Venta" type="number">
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="discriminaiva">Discrimina IVA <span
                                        id="condicion-iva"></span></label>
                                <div class="col-sm-8">
                                    <label class="checkbox-inline" for="discriminaiva">
                                        <input type="checkbox" checked name="discriminaiva" id="discriminaiva"
                                            value="1">
                                        SI
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Fin Segunda Columna-->

                    <!-- Tercera Columna-->
                    <div class="col-sm-4">
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="prependedtext">Neto</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="importeneto" disabled readonly name="importeneto" class="form-control"
                                        placeholder="Importe Neto" type="text">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="prependedtext">Importe IVA</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="importeiva" disabled readonly name="importeiva" class="form-control"
                                        placeholder="Importe IVA" type="text">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="prependedtext">Total</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="importetotal" disabled readonly name="importetotal" class="form-control"
                                        placeholder="Importe Total" type="text">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="dto-cliente">
                        <input type="hidden" id="dto-lista">


                    </div>
                    <!-- Fin Tercera Columna-->

                </fieldset>
            </form>
        </div>

        <!-- Panel central-->
        <div class="row">
            <div style="padding:10px" class="col-md-6 d-inline-flex">
                <button id="agregardetalle" class="btn btn-primary">Agregar Detalle</button>
                <button onclick="guardar('guardar')" id="guardar" class="btn btn-primary">Guardar</button>
                <button id="pegar-contenido" onclick="abrirModalPegar()" class="btn btn-primary">Pegar
                    Contenido</button>
                <button onclick="$('#modal-agregar').modal()" class="btn btn-primary">Agregar
                    Múltiples Artículos</button>
                <div style="padding-top: 7px; padding-right: 5px">
                    <label style="margin-left: 5px">Aprobado Todos</label>
                    <label class="switch">
                        <input onchange="aprobadoTodo(this)" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div style="padding:10px" class="col-md-4">
                <p id="articulos-pendientes">

                </p>
            </div>
            <div style="padding:10px" class="col-md-6">
                <div id="mensaje" style="width:100%" class="alert oculto alert-dismissible" role="alert">
                    <button onclick="ocultarmensaje()" type="button" class="close"><span>&times;</span></button>
                    <p id="textomensaje">

                    </p>
                </div>
            </div>
        </div>
        <div id="detalles">
            <div style="display:none" class="detalle">
                <div class="row">
                    <form>
                        <div class="form-row">
                            <div class="col-md-2 controles" style="display: inline-flex;">
                                <input type="hidden" class="origenimportacion" value="" />
                                <a href="javascript:void(0);" onclick="eliminardetalle(this)"><i
                                        class="fa fa-2x fa-trash-o" title="Eliminar Registro"
                                        aria-hidden="true"></i></a>
                                <a class="ver-historial" style="display: none; margin-left: 5px; margin-right: 5px"
                                    href="javascript:void(0);" onclick="verHistorial(this)"><i
                                        class="fa fa-2x fa-history" title="Ver Historial" aria-hidden="true"></i></a>
                                <div style="padding-top: 7px; padding-right: 5px">
                                    <label style="margin-left: 5px">Aprobado</label>
                                    <label class="switch">
                                        <input onchange="aprobado(this)" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div style="display: none" class="contenedor-cantidad-aprobada">
                                    <label for="">Cant</label>
                                    <input type="number" style="width: 60px" class="form-control cantidad-aprobada"
                                        value="0" />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <input type="text" placeholder="Referencia" class="form-control referencia">
                            </div>
                            <div class="col-md-1">
                                <input type="number" placeholder="$ Ref" class="form-control precio-referencia">
                            </div>
                            <div class="col-md-1">
                                <input type="number" placeholder="Cant" oninput="cambia('cantidad', this)" value="1"
                                    class="form-control cantidad">
                            </div>

                            <div class="col-md-4">
                                <select onchange="cambiaproducto(this)" class="producto form-control"></select>
                            </div>

                            <div class="col-md-1">
                                <select onchange="importearticulo(this)" class="unidadmedida form-control">
                                    <option value="1">Unidades</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <select disabled readonly onchange="cambia('iva', this)"
                                    class="alicuotaiva form-control">
                                    <option value="">IVA</option>
                                    <?php
					      	$defaultval=""; 
					      	for ($i=0; $i < count($alicuotasiva); $i++) {
					      		if ($alicuotasiva[$i]["id"]==$defaultval) {
					      		 	?>
                                    <option selected value="<?php echo $alicuotasiva[$i]["id"]?>">
                                        <?php echo $alicuotasiva[$i]["valor"] ?></option>
                                    <?php
					      		 }else{
					      		 	?>
                                    <option value="<?php echo $alicuotasiva[$i]["id"]?>">
                                        <?php echo $alicuotasiva[$i]["valor"] ?></option>
                                    <?php
					      		 } 
					      		?>
                                    <?php
					      	}
					       ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>$ Unit</th>
                                <th>dto lista</th>
                                <th>dto cliente</th>
                                <th>dto categoría</th>
                                <th>dto subcategoría</th>
                                <th>dto artículo</th>
                                <th>costo</th>
                                <th>margen</th>
                                <th>margen final</th>
                                <th>nuevo $ Unit</th>
                                <th>$ Iva</th>
                                <th>subtotal</th>
                                <th>$ Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="precio-unitario">0</td>
                                <td class="dto-lista">0</td>
                                <td class="dto-cliente">0</td>
                                <td class="dto-categoria">0</td>
                                <td class="dto-subcategoria">0</td>
                                <td><input class="dto-articulo" oninput="cambia('dtoarticulo', this)" type="number">
                                </td>
                                <td class="costo">0</td>
                                <td class="margen-actual">0</td>
                                <td><input class="nuevo-margen" oninput="cambia('margenfinal', this)" type="number">
                                </td>
                                <td><input class="nuevo-precio-unitario" oninput="cambia('preciounitario', this)"
                                        type="number" value="0"></td>
                                <td class="importe-iva">0</td>
                                <td class="subtotal">0</td>
                                <td class="importe-total">0</td>

                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-pegar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pegar información recibida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label class="control-label" for="pegar-articulos">Pegar Artículos</label>
                                <textarea placeholder="Ingresar un ítem por línea" style="resize:vertical"
                                    class="form-control" id="pegar-articulos" cols="30" rows="10"></textarea>
                            </div>
                            <div class="col-xs-6 form-group">
                                <label class="control-label" for="pegar-precios">Pegar Precios</label>
                                <textarea placeholder="Ingresar un ítem por línea" style="resize:vertical"
                                    class="form-control" id="pegar-precios" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button onclick="pegar()" type="button" class="btn btn-primary">Pegar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" style="width:100%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
                        </button>
						<h4 class="modal-title">Agregar Múltiples Artículos</h4>
                    </div>
                    <div class="modal-body" style="max-height:70vh; overflow-y:scroll">

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
                                        <option <?php echo $seleccionar?"selected":"" ?>
                                            value="<?php echo $value["id"] ?>"><?php echo $value["denominacion"] ?>
                                        </option>
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
                                    <select multiple id="subcategoria" name="subcategoria" class="form-control"
                                        data-s2="true" onchange="filtrosDependientes(this)">

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
						
						<form class="form-inline" style="margin-top:10px" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						
							<div class="form-group" style="width: 300px">
								<label class="control-label" for="marca">Descuento</label>
								<div class="">
									<input type="number" min="0" value="0" id="descuento" name="descuento" class="form-control"></input>
								</div>
							</div>
						</form>

                        <table id="articulos" class="table table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th style="width: 60px">
                                        <input type="checkbox" checked onclick="seleccionarTodosArticulos(this)">
                                    </th>
                                    <th style="width: 350px">Denominación</th>
                                    <th>Proveedor</th>
                                    <th>Lista</th>
                                    <th>Descuentos</th>
                                    <th>Costo Final ($)</th>
                                    <th>Margen</th>
                                    <th>Precio Venta</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button onclick="agregarMultiplesArticulos()" type="button" class="btn btn-primary">Agregar Seleccionados</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-historico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Histórico de cotizaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="tabla-historico" class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cantidad (pedida / aprobada)</th>
                                    <th>Precio ref</th>
                                    <th>Precio Cot</th>
                                    <th>Margen</th>
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

        <div id="contenedor-contador-detalles">
            <h4>Cant detalles: <span id="contador-detalles">0</span></h4>
        </div>


        <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
        <script src="librerias/js/fileupload/vendor/jquery.ui.widget.js"></script>

        <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
        <script src="librerias/js/fileupload/jquery.iframe-transport.js"></script>

        <!-- The basic File Upload plugin -->
        <script src="librerias/js/fileupload/jquery.fileupload.js"></script>

        <script src="librerias/js/misc/cotizaciones.js"></script>


        <script>
        $(document).ready(function() {
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
                        success: function(response) {

                            $("#categoria").empty();
                            $("#subcategoria").empty();
                            $("#rubro").empty();
                            $("#marca").empty();

                            var html = '';

                            $.each(response.exito, function(index, value) {
                                html += '<option value="' + value.id + '">' + value.denominacion +
                                    '</option>'
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
                        data: {
                            accion: accion,
                            proveedores: proveedores,
                            categorias: categorias
                        },
                        dataType: "json",
                        success: function(response) {

                            $("#subcategoria").empty();
                            $("#rubro").empty();
                            $("#marca").empty();

                            var html = '';

                            $.each(response.exito, function(index, value) {
                                html += '<option value="' + value.id + '">' + value.denominacion +
                                    '</option>'
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
                        data: {
                            accion: accion,
                            proveedores: proveedores,
                            categorias: categorias,
                            subcategorias: subcategorias
                        },
                        dataType: "json",
                        success: function(response) {

                            $("#rubro").empty();
                            $("#marca").empty();

                            var html = '';

                            $.each(response.exito, function(index, value) {
                                html += '<option value="' + value.id + '">' + value.denominacion +
                                    '</option>'
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
                            accion: accion,
                            proveedores: proveedores,
                            categorias: categorias,
                            subcategorias: subcategorias,
                            rubros: rubros
                        },
                        dataType: "json",
                        success: function(response) {

                            $("#marca").empty();

                            var html = '';

                            $.each(response.exito, function(index, value) {
                                html += '<option value="' + value.id + '">' + value.denominacion +
                                    '</option>'
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

                    listarArticulos();

                    break;

            }
        }

		function agregarMultiplesArticulos() {
            //Por cada registro
            $("#articulos tbody tr").each(function( index ) {
                //Si está tildado
				if ($(this).find(".seleccionar-articulo").is(":checked")) {
                    agregardetalle(true, true, true);
                    var idarticulo = $(this).data("id-articulo");
                    var denominacionarticulo = $(this).data("denominacion");
                    $("#detalles .detalle:last-child").find(".dto-articulo").val(parseInt($("#descuento").val()));
                    $("#detalles .detalle:last-child .producto").append('<option selected value="'+idarticulo+'">'+denominacionarticulo+'</option>').attr("disabled",true).trigger("change");
				}
			})

            $('#modal-agregar').modal('hide')
		}

        function listarArticulos() {

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
            } else {

                $("#mensajes").html("").hide();

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
                        marcas: marcas
                    },
                    dataType: "json",
                    success: function(response) {

                        $("#articulos tbody").empty();

                        $.each(response.exito, function(index, value) {

                            var html = '';

                            if (value.calculoprecio != 1) {
                                var clase = "text-danger";
                                var rentabilidad = "FIJO";
                            } else {
                                var clase = "";
                                var rentabilidad = value.rentabilidad;
                            }

                            if (value.idpreciocompra == "SI") {
                                var claseboton = "btn-primary";
                                var titleboton = "Es el proveedor";
                                var esproveedor = true;
                            } else {
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

                            var dtototal = parseFloat(value.dtoproveedor1) + parseFloat(value
                                    .dtoproveedor2) + parseFloat(value.dtoproveedor3) + parseFloat(
                                    value.dtoarticulo1) + parseFloat(value.dtoarticulo2) +
                                parseFloat(value.dtoarticulo3);

                            var dtos = '(<span data-valor-original="' + value.dtoproveedor1 +
                                '" class="dtoproveedor1">' + value.dtoproveedor1 + '</span>' +
                                '+<span data-valor-original="' + value.dtoproveedor2 +
                                '" class="dtoproveedor2">' + value.dtoproveedor2 + '</span>' +
                                '+<span data-valor-original="' + value.dtoproveedor3 +
                                '" class="dtoproveedor3">' + value.dtoproveedor3 + '</span>' + ')' +
                                '(<span data-valor-original="' + value.dtoarticulo1 +
                                '" class="dtoarticulo1">' + value.dtoarticulo1 + '</span>' +
                                '+<span data-valor-original="' + value.dtoarticulo2 +
                                '" class="dtoarticulo2">' + value.dtoarticulo2 + '</span>' +
                                '+<span data-valor-original="' + value.dtoarticulo3 +
                                '" class="dtoarticulo3">' + value.dtoarticulo3 + '</span>' + ')'

                            html += '<tr data-dtoproveedor1="' + value.dtoproveedor1 +
                                '" data-dtoproveedor2="' + value.dtoproveedor2 +
                                '" data-dtoproveedor3="' + value.dtoproveedor3 +
                                '" data-dtoarticulo1="' + value.dtoarticulo1 +
                                '" data-dtoarticulo2="' + value.dtoarticulo2 +
                                '" data-dtoarticulo3="' + value.dtoarticulo3 +
                                '" data-id-articulo="' + value.id + '" data-es-proveedor="' +
                                esproveedor + '" data-id-articulo-proveedor="' + value
                                .idarticuloproveedor + '" data-categoria="' + value.categoria +
                                '" data-subcategoria="' + value.subcategoria + '" data-rubro="' +
                                value.rubro + '" data-marca="' + value.marca + '" class="' + clase +
                                '" data-calculo-precio="' + value.calculoprecio +
                                '" data-indice-venta="' + value.indiceventa +
                                '" data-indice-compra="' + value.indicecompra +
                                '" data-costo-pesos="" data-unidad-compra="' + value.unidadcompra +
                                '" data-denominacion="' + value.denominacionexterna +
                                '" data-unidad-venta="' + value.unidadventa + '" data-simbolo="' +
                                value.simbolo + '" data-cotizacion="' + parseFloat(value
                                    .cotizacion) + '" data-costo="' + parseFloat(value.precio) +
                                '" data-margen="' + parseFloat(value.rentabilidad) +
                                '" data-nuevo-costo="" data-precio-venta="' + parseFloat(value
                                    .precioVenta) +
                                '" data-nuevo-precio-venta=""><td><input style="width:20px;height:20px;" class="seleccionar-articulo" type="checkbox" checked data-id="' +
                                value.id +
                                '" /></td><td>' +
                                value.denominacioninterna + " " + value.denominacionexterna +
                                '</td><td>' + value.proveedor + '</td><td class="costo">' + value
                                .simbolo + " " + parseFloat(value.precio).toFixed(2) +
                                '</td><td class="descuentos">' + dtos +
                                '</td><td class="costo-pesos">$ ' + parseFloat(value.precioPesos)
                                .toFixed(2) +
                                '</td><td class="margen">' +
                                rentabilidad + '</td><td class="precio-venta">' + "$ " + parseFloat(
                                    value.precioVenta).toFixed(2) + " (" + value.unidadventa + ")" +
                                '</td></tr>';

                            $("#articulos tbody").append(html);

                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }

        }

        function aprobado(elemento) {
            if ($(elemento).prop("checked") == true) {
                $(elemento).parents(".controles").find(".contenedor-cantidad-aprobada").show();
                $(elemento).parents(".controles").find(".cantidad-aprobada").val($(elemento).parents(".detalle").find(
                    ".cantidad").val()).select();
            } else {
                $(elemento).parents(".controles").find(".contenedor-cantidad-aprobada").hide();
                $(elemento).parents(".controles").find(".cantidad-aprobada").val(0);
            }
        }

        function aprobadoTodo(elemento) {

            $(".switch input").each(function(index) {

                if ($(elemento).prop("checked") == true) {
                    $(this).prop("checked", true);
                    $(this).parents(".controles").find(".contenedor-cantidad-aprobada").show();
                    $(this).parents(".controles").find(".cantidad-aprobada").val($(this).parents(".detalle")
                        .find(".cantidad").val()).select();
                } else {
                    $(this).prop("checked", false);
                    $(this).parents(".controles").find(".contenedor-cantidad-aprobada").hide();
                    $(this).parents(".controles").find(".cantidad-aprobada").val(0);
                }

            });

        }

        function abrirModalPegar() {
            $("#modal-pegar").modal();
        }

		function seleccionarTodosArticulos(elemento){

			if ($(elemento).is(":checked")) {
				$(".seleccionar-articulo").prop('checked', true);
			}else{
				$(".seleccionar-articulo").prop('checked', false);
			};

		}		

        function pegar() {
            var textoarticulos = $("#pegar-articulos").val();
            var textoprecios = $("#pegar-precios").val();

            var arrarticulos = [];
            var arrprecios = [];

            var arrpreciosok = [];

            //borro tabulaciones

            textoarticulos = textoarticulos.replace('\t', '');
            textoprecios = textoprecios.replace('\t', '');

            //Armo array

            arrarticulos = textoarticulos.split('\n');
            arrprecios = textoprecios.split('\n');

            arrprecios.forEach(function(value, index) {
                if (value != undefined && value != "") {
                    //borro caracteres no numéricos
                    value = value.replace(/[^\d.-]/g, '');
                    arrpreciosok.push(value);
                };
            });

            var indice = 0;

            arrarticulos.forEach(function(value, index) {
                if (value != undefined && value != "") {
                    console.log(value);
                    console.log(arrpreciosok[indice]);
                    console.log("-------");
                    if ($("#tercero").val() == "0") {
                        mostrarmensajes("Antes debe seleccionar un tercero", "danger");
                    } else {
                        agregardetalle();
                        $(".detalle:last-child .referencia").val(value);
                        $(".detalle:last-child .precio-referencia").val(arrpreciosok[indice]);

                        indice++;
                    }
                };
            });

        }
        </script>

        <?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
        <?php include_once "footer.php" ?>
        <?php
$modulocotizacion2_php->Page_Terminate();
?>