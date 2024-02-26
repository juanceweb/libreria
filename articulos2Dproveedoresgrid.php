<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($articulos2Dproveedores_grid)) $articulos2Dproveedores_grid = new carticulos2Dproveedores_grid();

// Page init
$articulos2Dproveedores_grid->Page_Init();

// Page main
$articulos2Dproveedores_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dproveedores_grid->Page_Render();
?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<script type="text/javascript">

// Form object
var farticulos2Dproveedoresgrid = new ew_Form("farticulos2Dproveedoresgrid", "grid");
farticulos2Dproveedoresgrid.FormKeyCountName = '<?php echo $articulos2Dproveedores_grid->FormKeyCountName ?>';

// Validate form
farticulos2Dproveedoresgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_idArticulo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idArticulo->FldCaption(), $articulos2Dproveedores->idArticulo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idAlicuotaIva");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idAlicuotaIva->FldCaption(), $articulos2Dproveedores->idAlicuotaIva->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idMoneda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idMoneda->FldCaption(), $articulos2Dproveedores->idMoneda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->precio->FldCaption(), $articulos2Dproveedores->precio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->precio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dto3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->dto3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idTercero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $articulos2Dproveedores->idTercero->FldCaption(), $articulos2Dproveedores->idTercero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precioPesos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dproveedores->precioPesos->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
farticulos2Dproveedoresgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idArticulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codExterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idAlicuotaIva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idMoneda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "precio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idUnidadMedida", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dto1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dto2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dto3", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "precioPesos", false)) return false;
	return true;
}

// Form_CustomValidate event
farticulos2Dproveedoresgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dproveedoresgrid.ValidateRequired = true;
<?php } else { ?>
farticulos2Dproveedoresgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dproveedoresgrid.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dproveedoresgrid.Lists["x_idAlicuotaIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valor","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"alicuotas2Diva"};
farticulos2Dproveedoresgrid.Lists["x_idMoneda"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_simbolo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"monedas"};
farticulos2Dproveedoresgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","x_dto1","x_dto2","x_dto3"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($articulos2Dproveedores->CurrentAction == "gridadd") {
	if ($articulos2Dproveedores->CurrentMode == "copy") {
		$bSelectLimit = $articulos2Dproveedores_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$articulos2Dproveedores_grid->TotalRecs = $articulos2Dproveedores->SelectRecordCount();
			$articulos2Dproveedores_grid->Recordset = $articulos2Dproveedores_grid->LoadRecordset($articulos2Dproveedores_grid->StartRec-1, $articulos2Dproveedores_grid->DisplayRecs);
		} else {
			if ($articulos2Dproveedores_grid->Recordset = $articulos2Dproveedores_grid->LoadRecordset())
				$articulos2Dproveedores_grid->TotalRecs = $articulos2Dproveedores_grid->Recordset->RecordCount();
		}
		$articulos2Dproveedores_grid->StartRec = 1;
		$articulos2Dproveedores_grid->DisplayRecs = $articulos2Dproveedores_grid->TotalRecs;
	} else {
		$articulos2Dproveedores->CurrentFilter = "0=1";
		$articulos2Dproveedores_grid->StartRec = 1;
		$articulos2Dproveedores_grid->DisplayRecs = $articulos2Dproveedores->GridAddRowCount;
	}
	$articulos2Dproveedores_grid->TotalRecs = $articulos2Dproveedores_grid->DisplayRecs;
	$articulos2Dproveedores_grid->StopRec = $articulos2Dproveedores_grid->DisplayRecs;
} else {
	$bSelectLimit = $articulos2Dproveedores_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($articulos2Dproveedores_grid->TotalRecs <= 0)
			$articulos2Dproveedores_grid->TotalRecs = $articulos2Dproveedores->SelectRecordCount();
	} else {
		if (!$articulos2Dproveedores_grid->Recordset && ($articulos2Dproveedores_grid->Recordset = $articulos2Dproveedores_grid->LoadRecordset()))
			$articulos2Dproveedores_grid->TotalRecs = $articulos2Dproveedores_grid->Recordset->RecordCount();
	}
	$articulos2Dproveedores_grid->StartRec = 1;
	$articulos2Dproveedores_grid->DisplayRecs = $articulos2Dproveedores_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$articulos2Dproveedores_grid->Recordset = $articulos2Dproveedores_grid->LoadRecordset($articulos2Dproveedores_grid->StartRec-1, $articulos2Dproveedores_grid->DisplayRecs);

	// Set no record found message
	if ($articulos2Dproveedores->CurrentAction == "" && $articulos2Dproveedores_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$articulos2Dproveedores_grid->setWarningMessage(ew_DeniedMsg());
		if ($articulos2Dproveedores_grid->SearchWhere == "0=101")
			$articulos2Dproveedores_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$articulos2Dproveedores_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$articulos2Dproveedores_grid->RenderOtherOptions();
?>
<?php $articulos2Dproveedores_grid->ShowPageHeader(); ?>
<?php
$articulos2Dproveedores_grid->ShowMessage();
?>
<?php if ($articulos2Dproveedores_grid->TotalRecs > 0 || $articulos2Dproveedores->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid articulos2Dproveedores">
<div id="farticulos2Dproveedoresgrid" class="ewForm form-inline">
<?php if ($articulos2Dproveedores_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($articulos2Dproveedores_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_articulos2Dproveedores" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_articulos2Dproveedoresgrid" class="table ewTable">
<?php echo $articulos2Dproveedores->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$articulos2Dproveedores_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$articulos2Dproveedores_grid->RenderListOptions();

// Render list options (header, left)
$articulos2Dproveedores_grid->ListOptions->Render("header", "left");
?>
<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idArticulo) == "") { ?>
		<th data-name="idArticulo"><div id="elh_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idArticulo"><div><div id="elh_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idArticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idArticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idArticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->codExterno) == "") { ?>
		<th data-name="codExterno"><div id="elh_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codExterno"><div><div id="elh_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->codExterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->codExterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->codExterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idAlicuotaIva) == "") { ?>
		<th data-name="idAlicuotaIva"><div id="elh_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idAlicuotaIva"><div><div id="elh_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idAlicuotaIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idAlicuotaIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idAlicuotaIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idMoneda) == "") { ?>
		<th data-name="idMoneda"><div id="elh_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idMoneda"><div><div id="elh_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idMoneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idMoneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idMoneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->precio) == "") { ?>
		<th data-name="precio"><div id="elh_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio"><div><div id="elh_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->precio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->precio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idUnidadMedida) == "") { ?>
		<th data-name="idUnidadMedida"><div id="elh_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idUnidadMedida"><div><div id="elh_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idUnidadMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idUnidadMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idUnidadMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto1) == "") { ?>
		<th data-name="dto1"><div id="elh_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto1"><div><div id="elh_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto2) == "") { ?>
		<th data-name="dto2"><div id="elh_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto2"><div><div id="elh_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->dto3) == "") { ?>
		<th data-name="dto3"><div id="elh_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dto3"><div><div id="elh_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->dto3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->dto3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->dto3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->precioPesos) == "") { ?>
		<th data-name="precioPesos"><div id="elh_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precioPesos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precioPesos"><div><div id="elh_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->precioPesos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->precioPesos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->precioPesos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
	<?php if ($articulos2Dproveedores->SortUrl($articulos2Dproveedores->ultimaActualizacion) == "") { ?>
		<th data-name="ultimaActualizacion"><div id="elh_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion"><div class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->ultimaActualizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ultimaActualizacion"><div><div id="elh_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dproveedores->ultimaActualizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dproveedores->ultimaActualizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dproveedores->ultimaActualizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$articulos2Dproveedores_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$articulos2Dproveedores_grid->StartRec = 1;
$articulos2Dproveedores_grid->StopRec = $articulos2Dproveedores_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($articulos2Dproveedores_grid->FormKeyCountName) && ($articulos2Dproveedores->CurrentAction == "gridadd" || $articulos2Dproveedores->CurrentAction == "gridedit" || $articulos2Dproveedores->CurrentAction == "F")) {
		$articulos2Dproveedores_grid->KeyCount = $objForm->GetValue($articulos2Dproveedores_grid->FormKeyCountName);
		$articulos2Dproveedores_grid->StopRec = $articulos2Dproveedores_grid->StartRec + $articulos2Dproveedores_grid->KeyCount - 1;
	}
}
$articulos2Dproveedores_grid->RecCnt = $articulos2Dproveedores_grid->StartRec - 1;
if ($articulos2Dproveedores_grid->Recordset && !$articulos2Dproveedores_grid->Recordset->EOF) {
	$articulos2Dproveedores_grid->Recordset->MoveFirst();
	$bSelectLimit = $articulos2Dproveedores_grid->UseSelectLimit;
	if (!$bSelectLimit && $articulos2Dproveedores_grid->StartRec > 1)
		$articulos2Dproveedores_grid->Recordset->Move($articulos2Dproveedores_grid->StartRec - 1);
} elseif (!$articulos2Dproveedores->AllowAddDeleteRow && $articulos2Dproveedores_grid->StopRec == 0) {
	$articulos2Dproveedores_grid->StopRec = $articulos2Dproveedores->GridAddRowCount;
}

// Initialize aggregate
$articulos2Dproveedores->RowType = EW_ROWTYPE_AGGREGATEINIT;
$articulos2Dproveedores->ResetAttrs();
$articulos2Dproveedores_grid->RenderRow();
if ($articulos2Dproveedores->CurrentAction == "gridadd")
	$articulos2Dproveedores_grid->RowIndex = 0;
if ($articulos2Dproveedores->CurrentAction == "gridedit")
	$articulos2Dproveedores_grid->RowIndex = 0;
while ($articulos2Dproveedores_grid->RecCnt < $articulos2Dproveedores_grid->StopRec) {
	$articulos2Dproveedores_grid->RecCnt++;
	if (intval($articulos2Dproveedores_grid->RecCnt) >= intval($articulos2Dproveedores_grid->StartRec)) {
		$articulos2Dproveedores_grid->RowCnt++;
		if ($articulos2Dproveedores->CurrentAction == "gridadd" || $articulos2Dproveedores->CurrentAction == "gridedit" || $articulos2Dproveedores->CurrentAction == "F") {
			$articulos2Dproveedores_grid->RowIndex++;
			$objForm->Index = $articulos2Dproveedores_grid->RowIndex;
			if ($objForm->HasValue($articulos2Dproveedores_grid->FormActionName))
				$articulos2Dproveedores_grid->RowAction = strval($objForm->GetValue($articulos2Dproveedores_grid->FormActionName));
			elseif ($articulos2Dproveedores->CurrentAction == "gridadd")
				$articulos2Dproveedores_grid->RowAction = "insert";
			else
				$articulos2Dproveedores_grid->RowAction = "";
		}

		// Set up key count
		$articulos2Dproveedores_grid->KeyCount = $articulos2Dproveedores_grid->RowIndex;

		// Init row class and style
		$articulos2Dproveedores->ResetAttrs();
		$articulos2Dproveedores->CssClass = "";
		if ($articulos2Dproveedores->CurrentAction == "gridadd") {
			if ($articulos2Dproveedores->CurrentMode == "copy") {
				$articulos2Dproveedores_grid->LoadRowValues($articulos2Dproveedores_grid->Recordset); // Load row values
				$articulos2Dproveedores_grid->SetRecordKey($articulos2Dproveedores_grid->RowOldKey, $articulos2Dproveedores_grid->Recordset); // Set old record key
			} else {
				$articulos2Dproveedores_grid->LoadDefaultValues(); // Load default values
				$articulos2Dproveedores_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$articulos2Dproveedores_grid->LoadRowValues($articulos2Dproveedores_grid->Recordset); // Load row values
		}
		$articulos2Dproveedores->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($articulos2Dproveedores->CurrentAction == "gridadd") // Grid add
			$articulos2Dproveedores->RowType = EW_ROWTYPE_ADD; // Render add
		if ($articulos2Dproveedores->CurrentAction == "gridadd" && $articulos2Dproveedores->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$articulos2Dproveedores_grid->RestoreCurrentRowFormValues($articulos2Dproveedores_grid->RowIndex); // Restore form values
		if ($articulos2Dproveedores->CurrentAction == "gridedit") { // Grid edit
			if ($articulos2Dproveedores->EventCancelled) {
				$articulos2Dproveedores_grid->RestoreCurrentRowFormValues($articulos2Dproveedores_grid->RowIndex); // Restore form values
			}
			if ($articulos2Dproveedores_grid->RowAction == "insert")
				$articulos2Dproveedores->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$articulos2Dproveedores->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($articulos2Dproveedores->CurrentAction == "gridedit" && ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT || $articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) && $articulos2Dproveedores->EventCancelled) // Update failed
			$articulos2Dproveedores_grid->RestoreCurrentRowFormValues($articulos2Dproveedores_grid->RowIndex); // Restore form values
		if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) // Edit row
			$articulos2Dproveedores_grid->EditRowCnt++;
		if ($articulos2Dproveedores->CurrentAction == "F") // Confirm row
			$articulos2Dproveedores_grid->RestoreCurrentRowFormValues($articulos2Dproveedores_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$articulos2Dproveedores->RowAttrs = array_merge($articulos2Dproveedores->RowAttrs, array('data-rowindex'=>$articulos2Dproveedores_grid->RowCnt, 'id'=>'r' . $articulos2Dproveedores_grid->RowCnt . '_articulos2Dproveedores', 'data-rowtype'=>$articulos2Dproveedores->RowType));

		// Render row
		$articulos2Dproveedores_grid->RenderRow();

		// Render list options
		$articulos2Dproveedores_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($articulos2Dproveedores_grid->RowAction <> "delete" && $articulos2Dproveedores_grid->RowAction <> "insertdelete" && !($articulos2Dproveedores_grid->RowAction == "insert" && $articulos2Dproveedores->CurrentAction == "F" && $articulos2Dproveedores_grid->EmptyRow())) {
?>
	<tr<?php echo $articulos2Dproveedores->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dproveedores_grid->ListOptions->Render("body", "left", $articulos2Dproveedores_grid->RowCnt);
?>
	<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo"<?php echo $articulos2Dproveedores->idArticulo->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($articulos2Dproveedores->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<select data-table="articulos2Dproveedores" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dproveedores->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dproveedores->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dproveedores->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idArticulo" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($articulos2Dproveedores->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<select data-table="articulos2Dproveedores" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dproveedores->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dproveedores->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dproveedores->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idArticulo" class="articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idArticulo" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $articulos2Dproveedores_grid->PageObjName . "_row_" . $articulos2Dproveedores_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_id" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->id->CurrentValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_id" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->id->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT || $articulos2Dproveedores->CurrentMode == "edit") { ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_id" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
		<td data-name="codExterno"<?php echo $articulos2Dproveedores->codExterno->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_codExterno" class="form-group articulos2Dproveedores_codExterno">
<input type="text" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->codExterno->EditValue ?>"<?php echo $articulos2Dproveedores->codExterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_codExterno" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_codExterno" class="form-group articulos2Dproveedores_codExterno">
<input type="text" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->codExterno->EditValue ?>"<?php echo $articulos2Dproveedores->codExterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_codExterno" class="articulos2Dproveedores_codExterno">
<span<?php echo $articulos2Dproveedores->codExterno->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->codExterno->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_codExterno" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td data-name="idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idAlicuotaIva" class="form-group articulos2Dproveedores_idAlicuotaIva">
<select data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos2Dproveedores->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo $articulos2Dproveedores->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idAlicuotaIva->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idAlicuotaIva" class="form-group articulos2Dproveedores_idAlicuotaIva">
<select data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos2Dproveedores->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo $articulos2Dproveedores->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idAlicuotaIva" class="articulos2Dproveedores_idAlicuotaIva">
<span<?php echo $articulos2Dproveedores->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idAlicuotaIva->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idAlicuotaIva->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
		<td data-name="idMoneda"<?php echo $articulos2Dproveedores->idMoneda->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idMoneda" class="form-group articulos2Dproveedores_idMoneda">
<select data-table="articulos2Dproveedores" data-field="x_idMoneda" data-value-separator="<?php echo $articulos2Dproveedores->idMoneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda"<?php echo $articulos2Dproveedores->idMoneda->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo $articulos2Dproveedores->idMoneda->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idMoneda" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idMoneda->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idMoneda" class="form-group articulos2Dproveedores_idMoneda">
<select data-table="articulos2Dproveedores" data-field="x_idMoneda" data-value-separator="<?php echo $articulos2Dproveedores->idMoneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda"<?php echo $articulos2Dproveedores->idMoneda->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo $articulos2Dproveedores->idMoneda->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idMoneda" class="articulos2Dproveedores_idMoneda">
<span<?php echo $articulos2Dproveedores->idMoneda->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idMoneda" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idMoneda->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idMoneda" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idMoneda->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
		<td data-name="precio"<?php echo $articulos2Dproveedores->precio->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precio" class="form-group articulos2Dproveedores_precio">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precio" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precio->EditValue ?>"<?php echo $articulos2Dproveedores->precio->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precio" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precio" class="form-group articulos2Dproveedores_precio">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precio" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precio->EditValue ?>"<?php echo $articulos2Dproveedores->precio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precio" class="articulos2Dproveedores_precio">
<span<?php echo $articulos2Dproveedores->precio->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precio->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precio" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precio" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idUnidadMedida" class="form-group articulos2Dproveedores_idUnidadMedida">
<select data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dproveedores->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idUnidadMedida->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idUnidadMedida" class="form-group articulos2Dproveedores_idUnidadMedida">
<select data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dproveedores->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idUnidadMedida" class="articulos2Dproveedores_idUnidadMedida">
<span<?php echo $articulos2Dproveedores->idUnidadMedida->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idUnidadMedida->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idUnidadMedida->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
		<td data-name="dto1"<?php echo $articulos2Dproveedores->dto1->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto1" class="form-group articulos2Dproveedores_dto1">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto1" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto1->EditValue ?>"<?php echo $articulos2Dproveedores->dto1->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto1" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto1" class="form-group articulos2Dproveedores_dto1">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto1" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto1->EditValue ?>"<?php echo $articulos2Dproveedores->dto1->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto1" class="articulos2Dproveedores_dto1">
<span<?php echo $articulos2Dproveedores->dto1->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto1->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto1" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto1" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
		<td data-name="dto2"<?php echo $articulos2Dproveedores->dto2->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto2" class="form-group articulos2Dproveedores_dto2">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto2" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto2->EditValue ?>"<?php echo $articulos2Dproveedores->dto2->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto2" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto2" class="form-group articulos2Dproveedores_dto2">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto2" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto2->EditValue ?>"<?php echo $articulos2Dproveedores->dto2->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto2" class="articulos2Dproveedores_dto2">
<span<?php echo $articulos2Dproveedores->dto2->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto2->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto2" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto2" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
		<td data-name="dto3"<?php echo $articulos2Dproveedores->dto3->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto3" class="form-group articulos2Dproveedores_dto3">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto3" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto3->EditValue ?>"<?php echo $articulos2Dproveedores->dto3->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto3" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto3" class="form-group articulos2Dproveedores_dto3">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto3" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto3->EditValue ?>"<?php echo $articulos2Dproveedores->dto3->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_dto3" class="articulos2Dproveedores_dto3">
<span<?php echo $articulos2Dproveedores->dto3->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->dto3->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto3" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto3" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $articulos2Dproveedores->idTercero->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($articulos2Dproveedores->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<select data-table="articulos2Dproveedores" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dproveedores->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dproveedores->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dproveedores->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idTercero" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($articulos2Dproveedores->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<select data-table="articulos2Dproveedores" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dproveedores->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dproveedores->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dproveedores->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_idTercero" class="articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idTercero" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
		<td data-name="precioPesos"<?php echo $articulos2Dproveedores->precioPesos->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precioPesos" class="form-group articulos2Dproveedores_precioPesos">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precioPesos->EditValue ?>"<?php echo $articulos2Dproveedores->precioPesos->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precioPesos" class="form-group articulos2Dproveedores_precioPesos">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precioPesos->EditValue ?>"<?php echo $articulos2Dproveedores->precioPesos->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_precioPesos" class="articulos2Dproveedores_precioPesos">
<span<?php echo $articulos2Dproveedores->precioPesos->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->precioPesos->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
		<td data-name="ultimaActualizacion"<?php echo $articulos2Dproveedores->ultimaActualizacion->CellAttributes() ?>>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_ultimaActualizacion" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->ultimaActualizacion->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dproveedores_grid->RowCnt ?>_articulos2Dproveedores_ultimaActualizacion" class="articulos2Dproveedores_ultimaActualizacion">
<span<?php echo $articulos2Dproveedores->ultimaActualizacion->ViewAttributes() ?>>
<?php echo $articulos2Dproveedores->ultimaActualizacion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_ultimaActualizacion" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->ultimaActualizacion->FormValue) ?>">
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_ultimaActualizacion" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->ultimaActualizacion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dproveedores_grid->ListOptions->Render("body", "right", $articulos2Dproveedores_grid->RowCnt);
?>
	</tr>
<?php if ($articulos2Dproveedores->RowType == EW_ROWTYPE_ADD || $articulos2Dproveedores->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
farticulos2Dproveedoresgrid.UpdateOpts(<?php echo $articulos2Dproveedores_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($articulos2Dproveedores->CurrentAction <> "gridadd" || $articulos2Dproveedores->CurrentMode == "copy")
		if (!$articulos2Dproveedores_grid->Recordset->EOF) $articulos2Dproveedores_grid->Recordset->MoveNext();
}
?>
<?php
	if ($articulos2Dproveedores->CurrentMode == "add" || $articulos2Dproveedores->CurrentMode == "copy" || $articulos2Dproveedores->CurrentMode == "edit") {
		$articulos2Dproveedores_grid->RowIndex = '$rowindex$';
		$articulos2Dproveedores_grid->LoadDefaultValues();

		// Set row properties
		$articulos2Dproveedores->ResetAttrs();
		$articulos2Dproveedores->RowAttrs = array_merge($articulos2Dproveedores->RowAttrs, array('data-rowindex'=>$articulos2Dproveedores_grid->RowIndex, 'id'=>'r0_articulos2Dproveedores', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($articulos2Dproveedores->RowAttrs["class"], "ewTemplate");
		$articulos2Dproveedores->RowType = EW_ROWTYPE_ADD;

		// Render row
		$articulos2Dproveedores_grid->RenderRow();

		// Render list options
		$articulos2Dproveedores_grid->RenderListOptions();
		$articulos2Dproveedores_grid->StartRowCnt = 0;
?>
	<tr<?php echo $articulos2Dproveedores->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dproveedores_grid->ListOptions->Render("body", "left", $articulos2Dproveedores_grid->RowIndex);
?>
	<?php if ($articulos2Dproveedores->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<?php if ($articulos2Dproveedores->idArticulo->getSessionValue() <> "") { ?>
<span id="el$rowindex$_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<select data-table="articulos2Dproveedores" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dproveedores->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dproveedores->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dproveedores->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idArticulo" class="form-group articulos2Dproveedores_idArticulo">
<span<?php echo $articulos2Dproveedores->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idArticulo" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idArticulo" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idArticulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->codExterno->Visible) { // codExterno ?>
		<td data-name="codExterno">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_codExterno" class="form-group articulos2Dproveedores_codExterno">
<input type="text" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->codExterno->EditValue ?>"<?php echo $articulos2Dproveedores->codExterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_codExterno" class="form-group articulos2Dproveedores_codExterno">
<span<?php echo $articulos2Dproveedores->codExterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->codExterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_codExterno" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_codExterno" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_codExterno" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->codExterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<td data-name="idAlicuotaIva">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_idAlicuotaIva" class="form-group articulos2Dproveedores_idAlicuotaIva">
<select data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" data-value-separator="<?php echo $articulos2Dproveedores->idAlicuotaIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva"<?php echo $articulos2Dproveedores->idAlicuotaIva->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idAlicuotaIva->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo $articulos2Dproveedores->idAlicuotaIva->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idAlicuotaIva" class="form-group articulos2Dproveedores_idAlicuotaIva">
<span<?php echo $articulos2Dproveedores->idAlicuotaIva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idAlicuotaIva->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idAlicuotaIva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idAlicuotaIva" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idAlicuotaIva" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idAlicuotaIva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idMoneda->Visible) { // idMoneda ?>
		<td data-name="idMoneda">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_idMoneda" class="form-group articulos2Dproveedores_idMoneda">
<select data-table="articulos2Dproveedores" data-field="x_idMoneda" data-value-separator="<?php echo $articulos2Dproveedores->idMoneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda"<?php echo $articulos2Dproveedores->idMoneda->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idMoneda->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo $articulos2Dproveedores->idMoneda->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idMoneda" class="form-group articulos2Dproveedores_idMoneda">
<span<?php echo $articulos2Dproveedores->idMoneda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idMoneda->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idMoneda" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idMoneda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idMoneda" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idMoneda" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idMoneda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precio->Visible) { // precio ?>
		<td data-name="precio">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_precio" class="form-group articulos2Dproveedores_precio">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precio" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precio->EditValue ?>"<?php echo $articulos2Dproveedores->precio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_precio" class="form-group articulos2Dproveedores_precio">
<span<?php echo $articulos2Dproveedores->precio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->precio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precio" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precio" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precio" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_idUnidadMedida" class="form-group articulos2Dproveedores_idUnidadMedida">
<select data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dproveedores->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dproveedores->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idUnidadMedida" class="form-group articulos2Dproveedores_idUnidadMedida">
<span<?php echo $articulos2Dproveedores->idUnidadMedida->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idUnidadMedida->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idUnidadMedida->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idUnidadMedida->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto1->Visible) { // dto1 ?>
		<td data-name="dto1">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto1" class="form-group articulos2Dproveedores_dto1">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto1" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto1->EditValue ?>"<?php echo $articulos2Dproveedores->dto1->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto1" class="form-group articulos2Dproveedores_dto1">
<span<?php echo $articulos2Dproveedores->dto1->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->dto1->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto1" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto1" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto1" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto2->Visible) { // dto2 ?>
		<td data-name="dto2">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto2" class="form-group articulos2Dproveedores_dto2">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto2" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto2->EditValue ?>"<?php echo $articulos2Dproveedores->dto2->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto2" class="form-group articulos2Dproveedores_dto2">
<span<?php echo $articulos2Dproveedores->dto2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->dto2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto2" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto2" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto2" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->dto3->Visible) { // dto3 ?>
		<td data-name="dto3">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto3" class="form-group articulos2Dproveedores_dto3">
<input type="text" data-table="articulos2Dproveedores" data-field="x_dto3" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->dto3->EditValue ?>"<?php echo $articulos2Dproveedores->dto3->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_dto3" class="form-group articulos2Dproveedores_dto3">
<span<?php echo $articulos2Dproveedores->dto3->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->dto3->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto3" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_dto3" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_dto3" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->dto3->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<?php if ($articulos2Dproveedores->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<select data-table="articulos2Dproveedores" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dproveedores->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dproveedores->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dproveedores->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dproveedores->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_idTercero" class="form-group articulos2Dproveedores_idTercero">
<span<?php echo $articulos2Dproveedores->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idTercero" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_idTercero" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->precioPesos->Visible) { // precioPesos ?>
		<td data-name="precioPesos">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dproveedores_precioPesos" class="form-group articulos2Dproveedores_precioPesos">
<input type="text" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->getPlaceHolder()) ?>" value="<?php echo $articulos2Dproveedores->precioPesos->EditValue ?>"<?php echo $articulos2Dproveedores->precioPesos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_precioPesos" class="form-group articulos2Dproveedores_precioPesos">
<span<?php echo $articulos2Dproveedores->precioPesos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->precioPesos->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_precioPesos" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_precioPesos" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->precioPesos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dproveedores->ultimaActualizacion->Visible) { // ultimaActualizacion ?>
		<td data-name="ultimaActualizacion">
<?php if ($articulos2Dproveedores->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dproveedores_ultimaActualizacion" class="form-group articulos2Dproveedores_ultimaActualizacion">
<span<?php echo $articulos2Dproveedores->ultimaActualizacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dproveedores->ultimaActualizacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_ultimaActualizacion" name="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" id="x<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->ultimaActualizacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dproveedores" data-field="x_ultimaActualizacion" name="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" id="o<?php echo $articulos2Dproveedores_grid->RowIndex ?>_ultimaActualizacion" value="<?php echo ew_HtmlEncode($articulos2Dproveedores->ultimaActualizacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dproveedores_grid->ListOptions->Render("body", "right", $articulos2Dproveedores_grid->RowCnt);
?>
<script type="text/javascript">
farticulos2Dproveedoresgrid.UpdateOpts(<?php echo $articulos2Dproveedores_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($articulos2Dproveedores->CurrentMode == "add" || $articulos2Dproveedores->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $articulos2Dproveedores_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dproveedores_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dproveedores_grid->KeyCount ?>">
<?php echo $articulos2Dproveedores_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dproveedores->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $articulos2Dproveedores_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dproveedores_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dproveedores_grid->KeyCount ?>">
<?php echo $articulos2Dproveedores_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dproveedores->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="farticulos2Dproveedoresgrid">
</div>
<?php

// Close recordset
if ($articulos2Dproveedores_grid->Recordset)
	$articulos2Dproveedores_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($articulos2Dproveedores_grid->TotalRecs == 0 && $articulos2Dproveedores->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos2Dproveedores_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($articulos2Dproveedores->Export == "") { ?>
<script type="text/javascript">
farticulos2Dproveedoresgrid.Init();
</script>
<?php } ?>
<?php
$articulos2Dproveedores_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$articulos2Dproveedores_grid->Page_Terminate();
?>
