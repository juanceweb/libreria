<?php

// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "ewcfg13.php";
	include_once "ewshared13.php";
	$Language = new cLanguage();
}

// Responsive layout
if (ew_IsResponsiveLayout()) {
	$gsHeaderRowClass = "hidden-xs ewHeaderRow";
	$gsMenuColumnClass = "hidden-xs ewMenuColumn";
	$gsSiteTitleClass = "hidden-xs ewSiteTitle";
} else {
	$gsHeaderRowClass = "ewHeaderRow";
	$gsMenuColumnClass = "ewMenuColumn";
	$gsSiteTitleClass = "ewSiteTitle";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $Language->ProjectPhrase("BodyTitle") ?></title>
<meta charset="utf-8">
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap.css") ?>">
<!-- Optional theme -->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap-theme.css") ?>">
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>colorbox/colorbox.css">
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ew_IsResponsiveLayout()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo ew_CssFile(EW_PROJECT_STYLESHEET_FILENAME) ?>">
<?php if (@$gsCustomExport == "pdf" && EW_PDF_STYLESHEET_FILENAME <> "") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo EW_PDF_STYLESHEET_FILENAME ?>">
<?php } ?>
<link href="phpcss/gestion_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.storageapi.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/pStrength.jquery.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/pGenerator.jquery.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jqueryfileupload/load-image.all.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jqueryfileupload/jqueryfileupload.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/mobile-detect.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/moment.min.js"></script>
<link href="<?php echo $EW_RELATIVE_PATH ?>calendar/calendar.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>calendar/calendar.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>calendar/calendar-setup.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/ewcalendar.js"></script>
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "<?php echo $EW_DATE_SEPARATOR ?>"; // Date separator
var EW_TIME_SEPARATOR = "<?php echo $EW_TIME_SEPARATOR ?>"; // Time separator
var EW_DATE_FORMAT = "<?php echo $EW_DATE_FORMAT ?>"; // Default date format
var EW_DATE_FORMAT_ID = "<?php echo $EW_DATE_FORMAT_ID ?>"; // Default date format ID
var EW_DECIMAL_POINT = "<?php echo $EW_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $EW_THOUSANDS_SEP ?>";
var EW_MIN_PASSWORD_STRENGTH = 60;
var EW_GENERATE_PASSWORD_LENGTH = 16;
var EW_GENERATE_PASSWORD_UPPERCASE = true;
var EW_GENERATE_PASSWORD_LOWERCASE = true;
var EW_GENERATE_PASSWORD_NUMBER = true;
var EW_GENERATE_PASSWORD_SPECIALCHARS = false;
var EW_SESSION_TIMEOUT = <?php echo (EW_SESSION_TIMEOUT > 0) ? ew_SessionTimeoutTime() : 0 ?>; // Session timeout time (seconds)
var EW_SESSION_TIMEOUT_COUNTDOWN = <?php echo EW_SESSION_TIMEOUT_COUNTDOWN ?>; // Count down time to session timeout (seconds)
var EW_SESSION_KEEP_ALIVE_INTERVAL = <?php echo EW_SESSION_KEEP_ALIVE_INTERVAL ?>; // Keep alive interval (seconds)
var EW_RELATIVE_PATH = "<?php echo $EW_RELATIVE_PATH ?>"; // Relative path
var EW_SESSION_URL = EW_RELATIVE_PATH + "ewsession13.php"; // Session URL
var EW_IS_LOGGEDIN = <?php echo IsLoggedIn() ? "true" : "false" ?>; // Is logged in
var EW_IS_SYS_ADMIN = <?php echo IsSysAdmin() ? "true" : "false" ?>; // Is sys admin
var EW_CURRENT_USER_NAME = "<?php echo ew_JsEncode2(CurrentUserName()) ?>"; // Current user name
var EW_IS_AUTOLOGIN = <?php echo IsAutoLogin() ? "true" : "false" ?>; // Is logged in with option "Auto login until I logout explicitly"
var EW_TIMEOUT_URL = EW_RELATIVE_PATH + "logout.php"; // Timeout URL
var EW_LOOKUP_FILE_NAME = "ewlookup13.php"; // Lookup file name
var EW_LOOKUP_FILTER_VALUE_SEPARATOR = "<?php echo EW_LOOKUP_FILTER_VALUE_SEPARATOR ?>"; // Lookup filter value separator
var EW_MODAL_LOOKUP_FILE_NAME = "ewmodallookup13.php"; // Modal lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
var EW_DISABLE_BUTTON_ON_SUBMIT = true;
var EW_IMAGE_FOLDER = "phpimages/"; // Image folder
var EW_UPLOAD_URL = "<?php echo EW_UPLOAD_URL ?>"; // Upload URL
var EW_UPLOAD_THUMBNAIL_WIDTH = <?php echo EW_UPLOAD_THUMBNAIL_WIDTH ?>; // Upload thumbnail width
var EW_UPLOAD_THUMBNAIL_HEIGHT = <?php echo EW_UPLOAD_THUMBNAIL_HEIGHT ?>; // Upload thumbnail height
var EW_MULTIPLE_UPLOAD_SEPARATOR = "<?php echo EW_MULTIPLE_UPLOAD_SEPARATOR ?>"; // Upload multiple separator
var EW_USE_COLORBOX = <?php echo (EW_USE_COLORBOX) ? "true" : "false" ?>;
var EW_USE_JAVASCRIPT_MESSAGE = false;
var EW_MOBILE_DETECT = new MobileDetect(window.navigator.userAgent);
var EW_IS_MOBILE = EW_MOBILE_DETECT.mobile() ? true : false;
var EW_PROJECT_STYLESHEET_FILENAME = "<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>"; // Project style sheet
var EW_PDF_STYLESHEET_FILENAME = "<?php echo EW_PDF_STYLESHEET_FILENAME ?>"; // Pdf style sheet
var EW_TOKEN = "<?php echo @$gsToken ?>";
var EW_CSS_FLIP = <?php echo ($EW_CSS_FLIP) ? "true" : "false" ?>;
var EW_CONFIRM_CANCEL = true;
var EW_SEARCH_FILTER_OPTION = "<?php echo EW_SEARCH_FILTER_OPTION ?>";
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/jsrender.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/ewp13.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.ewjtable.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript">
var ewVar = <?php echo json_encode($EW_CLIENT_VAR); ?>;
<?php echo $Language->ToJSON() ?>
</script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/eweditor.js"></script>
<?php
?>
<link href="librerias/css/select2-4.0.4/select2.min.css" rel="stylesheet" />
<script src="librerias/js/select2-4.0.4/select2.full.min.js"></script>
<link rel="icon" href="favicon.png?v=1.1">
<script src="config.js"></script>
<?php
?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/userfn13.js"></script>
<script type="text/javascript">

/* Ocultar o Mostrar el menú
=============================================*/

function handleMenu(){
	var ocultarMenu = localStorage.getItem("gestion-menu")
	if(ocultarMenu === "true"){
		$("#ewMenuColumn").show();
	}else{
		$("#ewMenuColumn").hide();
	}
}

function changeMenu(){
	var ocultarMenu = localStorage.getItem("gestion-menu")
	if(ocultarMenu === "true"){
		console.log("a")
		localStorage.setItem("gestion-menu", "false");
	}else{
		localStorage.setItem("gestion-menu", "true");
	}
	handleMenu()
}

/* Ocultar y Mostrar campos en un Formulario
=============================================*/

//'data-visible=\'{"[IDCAMPO]":{"VALOR":TRUE o FALSE}}\''
function ocultarMostrarCampos(){
	$('[data-visible]').each(function( index ) {
		var elemento=$(this);
		var contador=0;
		$("[data-elemento-dependiente='true']").each(function( index ) {
			if ($(elemento).data("visible")[$(this).attr("id")]) {
				if ($(elemento).data("visible")[$(this).attr("id")][$(this).val()]==false) {
					contador ++;				
				}
			}
		});
		if (contador>0) {
			$(elemento).parents(".form-group").addClass("no-display");
			$(elemento).val("");				
		}else{
			$(elemento).parents(".form-group").removeClass("no-display");            
		};	
	})	  
}

/* Duplica información de los campos
=============================================*/

//data-duplica="true" data-campo-duplica='[IDCAMPO]'
function agregarCheckboxCamposDuplica(){
	$("[data-duplica='true']").each(function( index ) {
		var html='';
		html+='<span style="font-size:16pt" class="glyphicon glyphicon-copy"></span>'
		html+='<input checked type="checkbox">';
		$(this).parent("span").append(html);
	});	
}

function duplicaInformacion(elemento){
	if ($(elemento).parent("span").find("input[type='checkbox']").is(':checked')) {
		var valor=$(elemento).val();
		for (var i = 0; i < Object.keys($(elemento).data('campo-duplica')).length; i++) {
			$("#"+$(elemento).data('campo-duplica')[i]).val(valor);
		};
	};
}

/* Calcula precio de venta
=============================================*/
/*

function precioVenta(elemento){
	if($("#x_idPrecioCompra").val()!=0){
		var idpreciocompra=$("#x_idPrecioCompra").val();
		var accion="obtiene-costo";
		$.ajax({
			url: "api.php",
			type: "get",

			//crossDomain: true,
			data: {accion:accion, idpreciocompra:idpreciocompra},
			dataType: "json",
			success: function (response) {
				if (response["error"]==false) {
					if($(elemento).attr("id")=="x_rentabilidad"){
						$("#x_precioVenta").val(
							parseFloat(response["exito"][0]["precioPesos"]) +
							parseFloat(($(elemento).val() * response["exito"][0]["precioPesos"] / 100))
						);
					}else if($(elemento).attr("id")=="x_precioVenta"){
						$("#x_rentabilidad").val(
							parseFloat(($(elemento).val() / response["exito"][0]["precioPesos"] * 100) - 100)
						);
					}else if($(elemento).attr("id")=="x_idPrecioCompra"){
						if($("#x_calculoPrecio").val()==1){
							$("#x_precioVenta").val(
								parseFloat(response["exito"][0]["precioPesos"]) +
								parseFloat(($("#x_rentabilidad").val() * response["exito"][0]["precioPesos"] / 100))
							);
						}else if($("#x_calculoPrecio").val()==2){
							$("#x_rentabilidad").val(
								parseFloat(($("#x_precioVenta").val() / response["exito"][0]["precioPesos"] * 100) - 100).toFixed(2)
							);
						}						
					}		                   
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		}); 		
	}
}
*/

/* Audita Movimiento
=============================================*/

function auditar(id){
	var accion="auditar";
	$.ajax({
		url: "api.php",
		type: "get", 

		//crossDomain: true,
		data: {accion:accion,id:id},
		dataType: "json",
		success: function (response) {
			console.log(response);
			$("#modal .modal-body").empty();
			var html='';
			if (response["exito"]["autoriza"]==false) {
				html+='<h3>El movimiento se auditó pero no se procesó</h3>'
			}else{
				if (response["facturaelectronica"]["FeCabResp"]["Resultado"]=='R') {
					html+='<h3>Rechazado</h3>'
				}else{
					html+='<h3>Aprobado</h3>'
				};
				if (response["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]!=undefined){
					html+='<h5>Observaciones</h5>'
					html+='<ul>'
					if (response["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"][0]!=undefined) {
		    		for (var i = 0; i< Object.keys(response["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"]).length ; i++) {
							html+='<li>'+response["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"][i]["Msg"]+'</li>'
						};
					}else{
							html+='<li>'+response["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"]["Msg"]+'</li>'					
					};
					html+='</ul>'				
				}
				if (response["facturaelectronica"]["Errors"]!=undefined){
					html+='<h5>Errores</h5>'
					html+='<ul>'
					if (response["facturaelectronica"]["Errors"]["Err"][0]!=undefined) {
		    		for (var i = 0; i< Object.keys(response["facturaelectronica"]["Errors"]["Err"]).length ; i++) {
							html+='<li>'+response["facturaelectronica"]["Errors"]["Err"][i]["Msg"]+'</li>'
						};
					}else{
							html+='<li>'+response["facturaelectronica"]["Errors"]["Err"]["Msg"]+'</li>'					
					};
					html+='</ul>'				
				}	
			};
			$("#modal .modal-body").append(html);
			$('#modal').modal();
			$('#modal').on('hidden.bs.modal', function (e) {
				window.parent.location='movimientoslist.php';
			})
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
}

/* Audita Recibo
=============================================*/

function auditarrecibo(id){
	var accion="auditarrecibo";
	$.ajax({
		url: "api.php",
		type: "get", 

		//crossDomain: true,
		data: {accion:accion,id:id},
		dataType: "json",
		success: function (response) {
			window.parent.location='recibos2Dpagoslist.php';
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
}

/* Audita Cotizacion
=============================================*/

function auditarCotizacion(id){
	var accion="auditar-cotizacion";
	$.ajax({
		url: "api.php",
		type: "get", 

		//crossDomain: true,
		data: {accion:accion,id:id},
		dataType: "json",
		success: function (response) {
			window.parent.location='cotizacioneslist.php';
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
}

/* Detalle Recibo
=============================================*/

function detallerecibo(id){
	var accion="retornardetallerecibo";
	$.ajax({
		url: "api.php",
		type: "get",

		//crossDomain: true,
		data: {accion:accion,id:id},
		   dataType: "json",
		success: function (response) {
			$("#detalle tbody").empty();
			var html='';   
			for (var i = 0; i < Object.keys(response["exito"]).length; i++) {
				html+='<tr>';
				html+='<td>'+response["exito"][i]["medio"]+'</td>';
				html+='<td>'+response["exito"][i]["importe"]+'</td>';
				html+='<td>'+response["exito"][i]["banco"]+'</td>';
				html+='<td>'+response["exito"][i]["numero"]+'</td>';
				html+='<td>'+response["exito"][i]["fecha"]+'</td>';
				html+='<td>'+response["exito"][i]["codigo"]+'</td>';
				html+='</tr>';
			};
			 $("#detalle tbody").append(html);
			 $("#detalle").modal();          
		},
		error: function(jqXHR, textStatus, errorThrown) {
		   console.log(textStatus, errorThrown);
		}
	});
}

/* Actualizar Cotizacion
=============================================*/

function actualizarCotizacion(id){
		$("#loading").show();
	  var accion="actualizar-cotizacion-moneda";
		if (id != undefined) {
			var data = {accion:accion,idmoneda:id}
		}else{
			var data = {accion:accion}
		};	  
		$.ajax({
			url: "api.php",
			type: "get",

			//crossDomain: true,
			data:data,
			dataType: "json",
			success: function (response) {
				console.log(response);
				if (response["error"] == false) {
					location.reload();
				}else{
					alert(response["errores"]["mensajeerror"][0]);
					$("#loading").hide();
				};
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
}

/* Asignar precio Compra
=============================================*/

function asignarpreciocompra(idpreciocompra, idarticulo){
	$("#loading").show();
	  var accion="asignar-precio-compra";
		$.ajax({
			url: "api.php",
			type: "get",

			//crossDomain: true,
			data:{accion:accion,idpreciocompra:idpreciocompra,idarticulo:idarticulo},
			dataType: "json",
			success: function (response) {
				console.log(response);
				location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
}
</script>
<?php } ?>
<link rel="shortcut icon" type="image/png" href="<?php echo ew_ConvertFullUrl("pmp") ?>"><link rel="icon" type="image/png" href="<?php echo ew_ConvertFullUrl("pmp") ?>">
<meta name="generator" content="PHPMaker v2017">
</head>
<body>
<?php if (@!$gbSkipHeaderFooter) { ?>
<?php if (@$gsExport == "") { ?>
<div class="ewLayout">
	<!-- header (begin) --><!-- ** Note: Only licensed users are allowed to change the logo ** -->
	<div id="ewHeaderRow" class="<?php echo $gsHeaderRowClass ?>"><img src="<?php echo $EW_RELATIVE_PATH ?>phpimages/phpmkrlogo2017.png" alt=""></div>
<?php if (ew_IsResponsiveLayout()) { ?>
<nav id="ewMobileMenu" role="navigation" class="navbar navbar-default visible-xs hidden-print">
	<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button data-target="#ewMenu" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo (EW_MENUBAR_BRAND_HYPERLINK <> "") ? EW_MENUBAR_BRAND_HYPERLINK : "#" ?>"><?php echo (EW_MENUBAR_BRAND <> "") ? EW_MENUBAR_BRAND : $Language->ProjectPhrase("BodyTitle") ?></a>
		</div>
		<div id="ewMenu" class="collapse navbar-collapse" style="height: auto;"><!-- Begin Main Menu -->
<?php
	$RootMenu = new cMenu("MobileMenu");
	$RootMenu->MenuBarClassName = "";
	$RootMenu->MenuClassName = "nav navbar-nav";
	$RootMenu->SubMenuClassName = "dropdown-menu";
	$RootMenu->SubMenuDropdownImage = "";
	$RootMenu->SubMenuDropdownIconClassName = "icon-arrow-down";
	$RootMenu->MenuDividerClassName = "divider";
	$RootMenu->MenuItemClassName = "dropdown";
	$RootMenu->SubMenuItemClassName = "dropdown";
	$RootMenu->MenuActiveItemClassName = "active";
	$RootMenu->SubMenuActiveItemClassName = "active";
	$RootMenu->MenuRootGroupTitleAsSubMenu = TRUE;
	$RootMenu->MenuLinkDropdownClass = "ewDropdown";
	$RootMenu->MenuLinkClassName = "icon-arrow-right";
?>
<?php include_once "ewmobilemenu.php" ?>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<?php } ?>
	<!-- header (end) -->
	<!-- content (begin) -->
	<div id="ewContentTable" class="ewContentTable">
		<div id="ewContentRow">
			<div id="ewMenuColumn" class="<?php echo $gsMenuColumnClass ?>">
				<!-- left column (begin) -->
				<div class="ewMenu">
<?php include_once "ewmenu.php" ?>
				</div>
				<!-- left column (end) -->
			</div>
			<div id="ewContentColumn" class="ewContentColumn">
				<!-- right column (begin) -->
				<h4 class="<?php echo $gsSiteTitleClass ?>"><?php echo $Language->ProjectPhrase("BodyTitle") ?></h4>
<?php } ?>
<?php } ?>
