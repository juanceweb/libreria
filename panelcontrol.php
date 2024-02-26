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

$panelcontrol_php = NULL; // Initialize page object first

class cpanelcontrol_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}";

	// Table name
	var $TableName = 'panelcontrol.php';

	// Page object name
	var $PageObjName = 'panelcontrol_php';

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
			define("EW_TABLE_NAME", 'panelcontrol.php', TRUE);

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
		$Breadcrumb->Add("custom", "panelcontrol_php", $url, "", "panelcontrol_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($panelcontrol_php)) $panelcontrol_php = new cpanelcontrol_php();

// Page init
$panelcontrol_php->Page_Init();

// Page main
$panelcontrol_php->Page_Main();

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


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"
    integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<?php


	//Controles de usuario

	/*
		Selector de fecha desde y hasta
		Selector de articulo
		Selector de cliente
	*/

	include_once("connect.php");
	global $mysqli;

	//Obtengo los articulos y los clientes para el select

	//De articulos obtengo el id y la denominacionExterna
	//Al id le hago un trim para sacar los ceros a la izquierda

	$sSql = "SELECT TRIM(LEADING '0' FROM id) AS id, denominacionExterna FROM articulos";
	$result = $mysqli->query($sSql);
	$articulos = array();
	while ($row = $result->fetch_assoc()) {
		$articulos[$row['id']] = $row['denominacionExterna'];
	}

	$sSql = "SELECT * FROM terceros WHERE idTipoTercero = 2 ORDER BY denominacion";
	$result = $mysqli->query($sSql);
	$clientes = array();
	while ($row = $result->fetch_assoc()) {
		$clientes[$row['id']] = $row['denominacion'];
	}


?>

<div id="controles" class="container">
    <div class="row" style="
    display: flex;
    align-items: center;
">
        <div class="col-md-2">
            <div class="form-group">
                <label for="fecha_desde">Fecha desde</label>
                <input style="width:100%;" type="date" class="form-control" id="fecha_desde" name="fecha_desde"
                    value="2019-11-01">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="fecha_hasta">Fecha hasta</label>
                <input style="width:100%;" type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"
                    value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="articulo">Articulo</label>
                <select class="form-control" style="width:100%;" id="articulo" name="articulo">
                    <option value="">Todos</option>
                    <?php
						foreach ($articulos as $id => $denominacion) {
							echo "<option value='$id'>$id"."-"."$denominacion</option>";
						}
					?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cliente">Cliente</label>
                <select style="width:100%;" class="form-control w-100" id="cliente" name="cliente">
                    <option value="">Todos</option>
                    <?php
						//Una opcion por cada cliente
						foreach ($clientes as $id => $denominacion) {
							echo "<option value='$id'>$denominacion</option>";
						}
					?>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <button
            onclick="ejecutar()"
            type="button" class="btn btn-primary" id="btn_buscar">Buscar</button>
        </div>
    </div>
</div>

<style>
.graph {
    position: relative;
    height: 100%;
    width: 100% !important;
}

canvas {
    width: 100% !important;
}
</style>

<div style="margin-top: 50px; margin-bottom: 50px" class="container">
    <div class="row">
        <div class="col-12">
            <div class="grafico" style="max-height : 400px">
                <canvas id="ventas-mensuales-importe"></canvas>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 50px; margin-bottom: 50px" class="container">
    <div class="row">
        <div class="col-12">
            <div class="grafico" style="max-height : 400px">
                <canvas id="torta-clientes"></canvas>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 50px; margin-bottom: 50px" class="container">
    <div class="row">
        <div class="col-12">
            <div class="grafico" style="max-height : 400px">
                <canvas id="torta-articulos"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Get the context of the canvas element we want to select
var ventasMensualesImporte = document.getElementById('ventas-mensuales-importe').getContext('2d');
var tortaClientes = document.getElementById('torta-clientes').getContext('2d');
var tortaArticulos = document.getElementById('torta-articulos').getContext('2d');

//random color generator on rgb format
function randomColor() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return {
        r: r,
        g: g,
        b: b
    };
}

//Llamada a la API

var accion = "";

var fechaDesde = "";
var fechaHasta = "";

//Creo un objeto para guardar los datos de la grafica

var datosVentasMensualesImporte = {
    labels: [],
    dataImportes: [],
    dataCantidades: [],
    backgroundColor: [],
    borderColor: []
};

var datosTortaClientes = {
    labels: [],
    data: [],
    backgroundColor: []
};

var datosTortaArticulos = {
    labels: [],
    data: [],
    backgroundColor: []
};

//Creo el grafico

var ventasMensualesImporteGrafico = new Chart(ventasMensualesImporte, {
    type: 'line',
    data: {
        labels: datosVentasMensualesImporte.labels,
        datasets: [{
                label: 'Importe',
                yAxisID: 'Importe',
                data: datosVentasMensualesImporte.dataImportes,
                backgroundColor: datosVentasMensualesImporte.backgroundColor,
                borderColor: datosVentasMensualesImporte.borderColor,
                borderWidth: 3,
            },
            {
                label: 'Cantidad',
                yAxisID: 'Cantidad',
                data: datosVentasMensualesImporte.dataCantidades,
                backgroundColor: datosVentasMensualesImporte.backgroundColor,
                borderColor: datosVentasMensualesImporte.borderColor,
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
    }
});

var tortaClientesGrafico = new Chart(tortaClientes, {
    type: 'pie',
    data: {
        labels: datosTortaClientes.labels,
        datasets: [{
            label: 'Ventas por clientes',
            data: datosTortaClientes.data,
            backgroundColor: datosTortaClientes.backgroundColor,
            hoverOffset: 4,
            datalabels: {
                labels: {
                    index: {
                        align: 'end',
                        anchor: 'end',
                        offset: 4,
                        formatter: function(value, ctx) {
                            return "a"
                        },
                    },
                    name: {
                        align: 'center',
                        formatter: function(value, ctx) {
                            return "b"
                        }
                    },
                }
            }
        }]
    },
    plugins: [ChartDataLabels],
    options: {
        responsive: true,
        maintainAspectRatio: false,
		layout: {
            padding: 20
        },
        plugins: {
            legend: {
                position: 'right',
            }
        }	
    }
})

var tortaArticulosGrafico = new Chart(tortaArticulos, {
    type: 'pie',
    data: {
        labels: datosTortaArticulos.labels,
        datasets: [{
            label: 'Ventas por artículos',
            data: datosTortaArticulos.data,
            backgroundColor: datosTortaArticulos.backgroundColor,
            hoverOffset: 4,
            datalabels: {
                labels: {
                    index: {
                        align: 'end',
                        anchor: 'end',
                        offset: 4,
                        formatter: function(value, ctx) {
                            return "a"
                        },
                    },
                    name: {
                        align: 'center',
                        formatter: function(value, ctx) {
                            return "b"
                        }
                    },
                }
            }
        }]
    },
    plugins: [ChartDataLabels],
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: 20
        },
        plugins: {
            legend: {
                position: 'right',
            }
        }	
    }
})


//onchange fecha desde y fecha hasta formulario llamar a ejecutar
$('#fecha_desde, #fecha_hasta, #articulo, #cliente').change(function() {
    //ejecutar();
});

//Al iniciar
$(document).ready(function() {
    ejecutar();
    $('#articulo').select2();
    $('#cliente').select2();
});

//Función que llama a la API
function ejecutar() {

    //Obtengo los valores del formulario en un objeto
    var form = {
        fecha_desde: document.getElementById("fecha_desde").value,
        fecha_hasta: document.getElementById("fecha_hasta").value,
        articulo: document.getElementById("articulo").value,
        cliente: document.getElementById("cliente").value
    };

    //limpio los datos de la grafica
    datosVentasMensualesImporte.labels = [];
    datosVentasMensualesImporte.dataImportes = [];
    datosVentasMensualesImporte.dataCantidades = [];

    datosTortaClientes.labels = [];
    datosTortaClientes.data = [];
    datosTortaClientes.backgroundColor = [];

    datosTortaArticulos.labels = [];
    datosTortaArticulos.data = [];
    datosTortaArticulos.backgroundColor = [];

    //Llamada a la API
    accion = "ventas-mensuales-importe";

    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {
            accion: accion,
            fechaDesde: form.fecha_desde,
            fechaHasta: form.fecha_hasta,
            articulo: form.articulo,
            cliente: form.cliente
        },
        dataType: "json",
        success: function(response) {

            //foreach para recorrer el array de respuesta
            //labels = key
            //data = value

            //obtengo el tamaño del objeto
            var size = Object.keys(response.datos).length;

            $.each(response.datos, function(index, value) {
                datosVentasMensualesImporte.labels.push(index);
                datosVentasMensualesImporte.dataImportes.push(value.importeTotal);
                datosVentasMensualesImporte.dataCantidades.push(value.cantidad);

                //Si la cantidad de datos es mayor que el numero de colores generados aleatoriamente se genera uno nuevo y se añade al array de colores
                if (size > datosVentasMensualesImporte.backgroundColor.length) {
                    var color = randomColor();
                    datosVentasMensualesImporte.backgroundColor.push("rgba(" + color.r + "," + color
                        .g + "," + color.b +
                        ",0.2)");
                    datosVentasMensualesImporte.borderColor.push("rgba(" + color.r + "," + color.g +
                        "," + color.b + ",1)");
                }

                //Si la cantidad de datos es menor que el numero de colores generados aleatoriamente se elimina el ultimo
                if (size < datosVentasMensualesImporte.backgroundColor.length) {
                    datosVentasMensualesImporte.backgroundColor.pop();
                    datosVentasMensualesImporte.borderColor.pop();
                }
            });

            ventasMensualesImporteGrafico.data.labels = datosVentasMensualesImporte.labels;
            ventasMensualesImporteGrafico.data.datasets[0].data = datosVentasMensualesImporte.dataImportes;
            ventasMensualesImporteGrafico.data.datasets[1].data = datosVentasMensualesImporte
                .dataCantidades;
            ventasMensualesImporteGrafico.data.datasets[0].backgroundColor = datosVentasMensualesImporte
                .backgroundColor;
            ventasMensualesImporteGrafico.data.datasets[0].borderColor = datosVentasMensualesImporte
                .borderColor;
            ventasMensualesImporteGrafico.data.datasets[1].backgroundColor = datosVentasMensualesImporte
                .backgroundColor;
            ventasMensualesImporteGrafico.data.datasets[1].borderColor = datosVentasMensualesImporte
                .borderColor;
            ventasMensualesImporteGrafico.update();

        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
        }
    });

    accion = "ventas-clientes";

    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {
            accion: accion,
            fechaDesde: form.fecha_desde,
            fechaHasta: form.fecha_hasta,
            articulo: form.articulo,
            cliente: form.cliente
        },
        dataType: "json",
        success: function(response) {

            console.log(response);

            //foreach para recorrer el array de respuesta
            //labels = value.denominacion
            //data = value.importeTotal

            //obtengo el tamaño del objeto
            var size = Object.keys(response.datos).length;

            $.each(response.datos, function(index, value) {
                datosTortaClientes.labels.push(value.denominacion);
                datosTortaClientes.data.push(value.importeTotal);

                //Si la cantidad de datos es mayor que el numero de colores generados aleatoriamente se genera uno nuevo y se añade al array de colores
                if (size > datosTortaClientes.backgroundColor.length) {
                    var color = randomColor();
                    datosTortaClientes.backgroundColor.push("rgba(" + color.r + "," + color.g +
                        "," + color.b +
                        ",0.2)");
                }

                //Si la cantidad de datos es menor que el numero de colores generados aleatoriamente se elimina el ultimo
                if (size < datosTortaClientes.backgroundColor.length) {
                    datosTortaClientes.backgroundColor.pop();
                }
            });

            tortaClientesGrafico.data.labels = datosTortaClientes.labels;
            tortaClientesGrafico.data.datasets[0].data = datosTortaClientes.data;
            tortaClientesGrafico.data.datasets[0].backgroundColor = datosTortaClientes.backgroundColor;

            //Actualizo los labels

            //Sumo los importes
            var totalImporte = 0;
            $.each(datosTortaClientes.data, function(index, value) {
                totalImporte += (parseFloat(value));
            });

            //Calculo el porcentaje de cada cliente y los guardo en un array
            var porcentajes = [];
            $.each(datosTortaClientes.data, function(index, value) {
                porcentajes.push(Math.round((parseFloat(value) * 100) / totalImporte));
            });

            tortaClientesGrafico.data.datasets[0].datalabels.labels = {
                index: {
                    align: 'end',
                    anchor: 'end',
                    offset: 4,
                    formatter: function(value, ctx) {
						//denominacion
                        return datosTortaClientes.labels[ctx.dataIndex];
                    },
                },
                name: {
                    align: 'center',
                    formatter: function(value, ctx) {
                        return porcentajes[ctx.dataIndex] + '%';
                    },
                },
            }

            /*
            			tortaClientesGrafico.options.plugins.datalabels.formatter = function(value, ctx) {

            				console.log(value);
            				console.log(ctx);

            				return porcentajes[ctx.dataIndex] + "%";
            			};
            */
            tortaClientesGrafico.update();

        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
        }
    });

    accion = "ventas-articulos";

    $.ajax({
        url: "api.php",
        type: "get",
        //crossDomain: true,
        data: {
            accion: accion,
            fechaDesde: form.fecha_desde,
            fechaHasta: form.fecha_hasta,
            articulo: form.articulo,
            cliente: form.cliente
        },
        dataType: "json",
        success: function(response) {

            console.log(response);

            //foreach para recorrer el array de respuesta
            //labels = value.denominacion
            //data = value.importeTotal

            //obtengo el tamaño del objeto
            var size = Object.keys(response.datos).length;

            $.each(response.datos, function(index, value) {
                datosTortaArticulos.labels.push(value.denominacion);
                datosTortaArticulos.data.push(value.importeTotal);

                //Si la cantidad de datos es mayor que el numero de colores generados aleatoriamente se genera uno nuevo y se añade al array de colores
                if (size > datosTortaArticulos.backgroundColor.length) {
                    var color = randomColor();
                    datosTortaArticulos.backgroundColor.push("rgba(" + color.r + "," + color.g +
                        "," + color.b +
                        ",0.2)");
                }

                //Si la cantidad de datos es menor que el numero de colores generados aleatoriamente se elimina el ultimo
                if (size < datosTortaArticulos.backgroundColor.length) {
                    datosTortaArticulos.backgroundColor.pop();
                }
            });

            tortaArticulosGrafico.data.labels = datosTortaArticulos.labels;
            tortaArticulosGrafico.data.datasets[0].data = datosTortaArticulos.data;
            tortaArticulosGrafico.data.datasets[0].backgroundColor = datosTortaArticulos.backgroundColor;

            //Actualizo los labels

            //Sumo los importes
            var totalImporte = 0;
            $.each(datosTortaArticulos.data, function(index, value) {
                totalImporte += (parseFloat(value));
            });

            //Calculo el porcentaje de cada cliente y los guardo en un array
            var porcentajes = [];
            $.each(datosTortaArticulos.data, function(index, value) {
                porcentajes.push(Math.round((parseFloat(value) * 100) / totalImporte));
            });

            tortaArticulosGrafico.data.datasets[0].datalabels.labels = {
                index: {
                    align: 'end',
                    anchor: 'end',
                    offset: 4,
                    formatter: function(value, ctx) {
                        //denominacion
                        return datosTortaArticulos.labels[ctx.dataIndex].substring(0, 20) + "...";
                    },
                },
                name: {
                    align: 'center',
                    formatter: function(value, ctx) {
                        return porcentajes[ctx.dataIndex] + '%';
                    },
                },
            }

            tortaArticulosGrafico.update();


        },

        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
        }
    });

}
</script>

<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$panelcontrol_php->Page_Terminate();
?>