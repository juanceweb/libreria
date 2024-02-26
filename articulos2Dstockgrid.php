<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($articulos2Dstock_grid)) $articulos2Dstock_grid = new carticulos2Dstock_grid();

// Page init
$articulos2Dstock_grid->Page_Init();

// Page main
$articulos2Dstock_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dstock_grid->Page_Render();
?>
<?php if ($articulos2Dstock->Export == "") { ?>
<script type="text/javascript">

// Form object
var farticulos2Dstockgrid = new ew_Form("farticulos2Dstockgrid", "grid");
farticulos2Dstockgrid.FormKeyCountName = '<?php echo $articulos2Dstock_grid->FormKeyCountName ?>';

// Validate form
farticulos2Dstockgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_indiceConversion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->indiceConversion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stock");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stock->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockReservadoVenta");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockReservadoVenta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockReservadoCompra");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockReservadoCompra->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stockMinimo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dstock->stockMinimo->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
farticulos2Dstockgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idArticulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idUnidadMedida", false)) return false;
	if (ew_ValueChanged(fobj, infix, "indiceConversion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "stock", false)) return false;
	if (ew_ValueChanged(fobj, infix, "stockReservadoVenta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "stockReservadoCompra", false)) return false;
	if (ew_ValueChanged(fobj, infix, "stockMinimo", false)) return false;
	return true;
}

// Form_CustomValidate event
farticulos2Dstockgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dstockgrid.ValidateRequired = true;
<?php } else { ?>
farticulos2Dstockgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dstockgrid.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dstockgrid.Lists["x_idUnidadMedida"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"unidades2Dmedida"};

// Form object for search
</script>
<?php } ?>
<?php
if ($articulos2Dstock->CurrentAction == "gridadd") {
	if ($articulos2Dstock->CurrentMode == "copy") {
		$bSelectLimit = $articulos2Dstock_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$articulos2Dstock_grid->TotalRecs = $articulos2Dstock->SelectRecordCount();
			$articulos2Dstock_grid->Recordset = $articulos2Dstock_grid->LoadRecordset($articulos2Dstock_grid->StartRec-1, $articulos2Dstock_grid->DisplayRecs);
		} else {
			if ($articulos2Dstock_grid->Recordset = $articulos2Dstock_grid->LoadRecordset())
				$articulos2Dstock_grid->TotalRecs = $articulos2Dstock_grid->Recordset->RecordCount();
		}
		$articulos2Dstock_grid->StartRec = 1;
		$articulos2Dstock_grid->DisplayRecs = $articulos2Dstock_grid->TotalRecs;
	} else {
		$articulos2Dstock->CurrentFilter = "0=1";
		$articulos2Dstock_grid->StartRec = 1;
		$articulos2Dstock_grid->DisplayRecs = $articulos2Dstock->GridAddRowCount;
	}
	$articulos2Dstock_grid->TotalRecs = $articulos2Dstock_grid->DisplayRecs;
	$articulos2Dstock_grid->StopRec = $articulos2Dstock_grid->DisplayRecs;
} else {
	$bSelectLimit = $articulos2Dstock_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($articulos2Dstock_grid->TotalRecs <= 0)
			$articulos2Dstock_grid->TotalRecs = $articulos2Dstock->SelectRecordCount();
	} else {
		if (!$articulos2Dstock_grid->Recordset && ($articulos2Dstock_grid->Recordset = $articulos2Dstock_grid->LoadRecordset()))
			$articulos2Dstock_grid->TotalRecs = $articulos2Dstock_grid->Recordset->RecordCount();
	}
	$articulos2Dstock_grid->StartRec = 1;
	$articulos2Dstock_grid->DisplayRecs = $articulos2Dstock_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$articulos2Dstock_grid->Recordset = $articulos2Dstock_grid->LoadRecordset($articulos2Dstock_grid->StartRec-1, $articulos2Dstock_grid->DisplayRecs);

	// Set no record found message
	if ($articulos2Dstock->CurrentAction == "" && $articulos2Dstock_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$articulos2Dstock_grid->setWarningMessage(ew_DeniedMsg());
		if ($articulos2Dstock_grid->SearchWhere == "0=101")
			$articulos2Dstock_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$articulos2Dstock_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$articulos2Dstock_grid->RenderOtherOptions();
?>
<?php $articulos2Dstock_grid->ShowPageHeader(); ?>
<?php
$articulos2Dstock_grid->ShowMessage();
?>
<?php if ($articulos2Dstock_grid->TotalRecs > 0 || $articulos2Dstock->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid articulos2Dstock">
<div id="farticulos2Dstockgrid" class="ewForm form-inline">
<?php if ($articulos2Dstock_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($articulos2Dstock_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_articulos2Dstock" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_articulos2Dstockgrid" class="table ewTable">
<?php echo $articulos2Dstock->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$articulos2Dstock_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$articulos2Dstock_grid->RenderListOptions();

// Render list options (header, left)
$articulos2Dstock_grid->ListOptions->Render("header", "left");
?>
<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->idArticulo) == "") { ?>
		<th data-name="idArticulo"><div id="elh_articulos2Dstock_idArticulo" class="articulos2Dstock_idArticulo"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->idArticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idArticulo"><div><div id="elh_articulos2Dstock_idArticulo" class="articulos2Dstock_idArticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->idArticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->idArticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->idArticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->idUnidadMedida) == "") { ?>
		<th data-name="idUnidadMedida"><div id="elh_articulos2Dstock_idUnidadMedida" class="articulos2Dstock_idUnidadMedida"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->idUnidadMedida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idUnidadMedida"><div><div id="elh_articulos2Dstock_idUnidadMedida" class="articulos2Dstock_idUnidadMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->idUnidadMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->idUnidadMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->idUnidadMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->indiceConversion) == "") { ?>
		<th data-name="indiceConversion"><div id="elh_articulos2Dstock_indiceConversion" class="articulos2Dstock_indiceConversion"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->indiceConversion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="indiceConversion"><div><div id="elh_articulos2Dstock_indiceConversion" class="articulos2Dstock_indiceConversion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->indiceConversion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->indiceConversion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->indiceConversion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->stock) == "") { ?>
		<th data-name="stock"><div id="elh_articulos2Dstock_stock" class="articulos2Dstock_stock"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stock->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stock"><div><div id="elh_articulos2Dstock_stock" class="articulos2Dstock_stock">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stock->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->stock->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->stock->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->stockReservadoVenta) == "") { ?>
		<th data-name="stockReservadoVenta"><div id="elh_articulos2Dstock_stockReservadoVenta" class="articulos2Dstock_stockReservadoVenta"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockReservadoVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stockReservadoVenta"><div><div id="elh_articulos2Dstock_stockReservadoVenta" class="articulos2Dstock_stockReservadoVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockReservadoVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->stockReservadoVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->stockReservadoVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->stockReservadoCompra) == "") { ?>
		<th data-name="stockReservadoCompra"><div id="elh_articulos2Dstock_stockReservadoCompra" class="articulos2Dstock_stockReservadoCompra"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockReservadoCompra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stockReservadoCompra"><div><div id="elh_articulos2Dstock_stockReservadoCompra" class="articulos2Dstock_stockReservadoCompra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockReservadoCompra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->stockReservadoCompra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->stockReservadoCompra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
	<?php if ($articulos2Dstock->SortUrl($articulos2Dstock->stockMinimo) == "") { ?>
		<th data-name="stockMinimo"><div id="elh_articulos2Dstock_stockMinimo" class="articulos2Dstock_stockMinimo"><div class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockMinimo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stockMinimo"><div><div id="elh_articulos2Dstock_stockMinimo" class="articulos2Dstock_stockMinimo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dstock->stockMinimo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dstock->stockMinimo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dstock->stockMinimo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$articulos2Dstock_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$articulos2Dstock_grid->StartRec = 1;
$articulos2Dstock_grid->StopRec = $articulos2Dstock_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($articulos2Dstock_grid->FormKeyCountName) && ($articulos2Dstock->CurrentAction == "gridadd" || $articulos2Dstock->CurrentAction == "gridedit" || $articulos2Dstock->CurrentAction == "F")) {
		$articulos2Dstock_grid->KeyCount = $objForm->GetValue($articulos2Dstock_grid->FormKeyCountName);
		$articulos2Dstock_grid->StopRec = $articulos2Dstock_grid->StartRec + $articulos2Dstock_grid->KeyCount - 1;
	}
}
$articulos2Dstock_grid->RecCnt = $articulos2Dstock_grid->StartRec - 1;
if ($articulos2Dstock_grid->Recordset && !$articulos2Dstock_grid->Recordset->EOF) {
	$articulos2Dstock_grid->Recordset->MoveFirst();
	$bSelectLimit = $articulos2Dstock_grid->UseSelectLimit;
	if (!$bSelectLimit && $articulos2Dstock_grid->StartRec > 1)
		$articulos2Dstock_grid->Recordset->Move($articulos2Dstock_grid->StartRec - 1);
} elseif (!$articulos2Dstock->AllowAddDeleteRow && $articulos2Dstock_grid->StopRec == 0) {
	$articulos2Dstock_grid->StopRec = $articulos2Dstock->GridAddRowCount;
}

// Initialize aggregate
$articulos2Dstock->RowType = EW_ROWTYPE_AGGREGATEINIT;
$articulos2Dstock->ResetAttrs();
$articulos2Dstock_grid->RenderRow();
if ($articulos2Dstock->CurrentAction == "gridadd")
	$articulos2Dstock_grid->RowIndex = 0;
if ($articulos2Dstock->CurrentAction == "gridedit")
	$articulos2Dstock_grid->RowIndex = 0;
while ($articulos2Dstock_grid->RecCnt < $articulos2Dstock_grid->StopRec) {
	$articulos2Dstock_grid->RecCnt++;
	if (intval($articulos2Dstock_grid->RecCnt) >= intval($articulos2Dstock_grid->StartRec)) {
		$articulos2Dstock_grid->RowCnt++;
		if ($articulos2Dstock->CurrentAction == "gridadd" || $articulos2Dstock->CurrentAction == "gridedit" || $articulos2Dstock->CurrentAction == "F") {
			$articulos2Dstock_grid->RowIndex++;
			$objForm->Index = $articulos2Dstock_grid->RowIndex;
			if ($objForm->HasValue($articulos2Dstock_grid->FormActionName))
				$articulos2Dstock_grid->RowAction = strval($objForm->GetValue($articulos2Dstock_grid->FormActionName));
			elseif ($articulos2Dstock->CurrentAction == "gridadd")
				$articulos2Dstock_grid->RowAction = "insert";
			else
				$articulos2Dstock_grid->RowAction = "";
		}

		// Set up key count
		$articulos2Dstock_grid->KeyCount = $articulos2Dstock_grid->RowIndex;

		// Init row class and style
		$articulos2Dstock->ResetAttrs();
		$articulos2Dstock->CssClass = "";
		if ($articulos2Dstock->CurrentAction == "gridadd") {
			if ($articulos2Dstock->CurrentMode == "copy") {
				$articulos2Dstock_grid->LoadRowValues($articulos2Dstock_grid->Recordset); // Load row values
				$articulos2Dstock_grid->SetRecordKey($articulos2Dstock_grid->RowOldKey, $articulos2Dstock_grid->Recordset); // Set old record key
			} else {
				$articulos2Dstock_grid->LoadDefaultValues(); // Load default values
				$articulos2Dstock_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$articulos2Dstock_grid->LoadRowValues($articulos2Dstock_grid->Recordset); // Load row values
		}
		$articulos2Dstock->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($articulos2Dstock->CurrentAction == "gridadd") // Grid add
			$articulos2Dstock->RowType = EW_ROWTYPE_ADD; // Render add
		if ($articulos2Dstock->CurrentAction == "gridadd" && $articulos2Dstock->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$articulos2Dstock_grid->RestoreCurrentRowFormValues($articulos2Dstock_grid->RowIndex); // Restore form values
		if ($articulos2Dstock->CurrentAction == "gridedit") { // Grid edit
			if ($articulos2Dstock->EventCancelled) {
				$articulos2Dstock_grid->RestoreCurrentRowFormValues($articulos2Dstock_grid->RowIndex); // Restore form values
			}
			if ($articulos2Dstock_grid->RowAction == "insert")
				$articulos2Dstock->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$articulos2Dstock->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($articulos2Dstock->CurrentAction == "gridedit" && ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT || $articulos2Dstock->RowType == EW_ROWTYPE_ADD) && $articulos2Dstock->EventCancelled) // Update failed
			$articulos2Dstock_grid->RestoreCurrentRowFormValues($articulos2Dstock_grid->RowIndex); // Restore form values
		if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) // Edit row
			$articulos2Dstock_grid->EditRowCnt++;
		if ($articulos2Dstock->CurrentAction == "F") // Confirm row
			$articulos2Dstock_grid->RestoreCurrentRowFormValues($articulos2Dstock_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$articulos2Dstock->RowAttrs = array_merge($articulos2Dstock->RowAttrs, array('data-rowindex'=>$articulos2Dstock_grid->RowCnt, 'id'=>'r' . $articulos2Dstock_grid->RowCnt . '_articulos2Dstock', 'data-rowtype'=>$articulos2Dstock->RowType));

		// Render row
		$articulos2Dstock_grid->RenderRow();

		// Render list options
		$articulos2Dstock_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($articulos2Dstock_grid->RowAction <> "delete" && $articulos2Dstock_grid->RowAction <> "insertdelete" && !($articulos2Dstock_grid->RowAction == "insert" && $articulos2Dstock->CurrentAction == "F" && $articulos2Dstock_grid->EmptyRow())) {
?>
	<tr<?php echo $articulos2Dstock->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dstock_grid->ListOptions->Render("body", "left", $articulos2Dstock_grid->RowCnt);
?>
	<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo"<?php echo $articulos2Dstock->idArticulo->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($articulos2Dstock->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<select data-table="articulos2Dstock" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dstock->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dstock->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dstock->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idArticulo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($articulos2Dstock->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<select data-table="articulos2Dstock" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dstock->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dstock->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dstock->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idArticulo" class="articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_idArticulo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $articulos2Dstock_grid->PageObjName . "_row_" . $articulos2Dstock_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_id" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dstock->id->CurrentValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_id" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_id" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dstock->id->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT || $articulos2Dstock->CurrentMode == "edit") { ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_id" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dstock->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida"<?php echo $articulos2Dstock->idUnidadMedida->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idUnidadMedida" class="form-group articulos2Dstock_idUnidadMedida">
<select data-table="articulos2Dstock" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dstock->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dstock->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo $articulos2Dstock->idUnidadMedida->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dstock->idUnidadMedida->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idUnidadMedida" class="form-group articulos2Dstock_idUnidadMedida">
<select data-table="articulos2Dstock" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dstock->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dstock->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo $articulos2Dstock->idUnidadMedida->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_idUnidadMedida" class="articulos2Dstock_idUnidadMedida">
<span<?php echo $articulos2Dstock->idUnidadMedida->ViewAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idUnidadMedida" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dstock->idUnidadMedida->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dstock->idUnidadMedida->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
		<td data-name="indiceConversion"<?php echo $articulos2Dstock->indiceConversion->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_indiceConversion" class="form-group articulos2Dstock_indiceConversion">
<input type="text" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->indiceConversion->EditValue ?>"<?php echo $articulos2Dstock->indiceConversion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_indiceConversion" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" value="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_indiceConversion" class="form-group articulos2Dstock_indiceConversion">
<input type="text" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->indiceConversion->EditValue ?>"<?php echo $articulos2Dstock->indiceConversion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_indiceConversion" class="articulos2Dstock_indiceConversion">
<span<?php echo $articulos2Dstock->indiceConversion->ViewAttributes() ?>>
<?php echo $articulos2Dstock->indiceConversion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" value="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_indiceConversion" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" value="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
		<td data-name="stock"<?php echo $articulos2Dstock->stock->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stock" class="form-group articulos2Dstock_stock">
<input type="text" data-table="articulos2Dstock" data-field="x_stock" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stock->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stock->EditValue ?>"<?php echo $articulos2Dstock->stock->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stock" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" value="<?php echo ew_HtmlEncode($articulos2Dstock->stock->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stock" class="form-group articulos2Dstock_stock">
<input type="text" data-table="articulos2Dstock" data-field="x_stock" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stock->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stock->EditValue ?>"<?php echo $articulos2Dstock->stock->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stock" class="articulos2Dstock_stock">
<span<?php echo $articulos2Dstock->stock->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stock->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stock" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" value="<?php echo ew_HtmlEncode($articulos2Dstock->stock->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_stock" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" value="<?php echo ew_HtmlEncode($articulos2Dstock->stock->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
		<td data-name="stockReservadoVenta"<?php echo $articulos2Dstock->stockReservadoVenta->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoVenta" class="form-group articulos2Dstock_stockReservadoVenta">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoVenta->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoVenta->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoVenta" class="form-group articulos2Dstock_stockReservadoVenta">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoVenta->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoVenta->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoVenta" class="articulos2Dstock_stockReservadoVenta">
<span<?php echo $articulos2Dstock->stockReservadoVenta->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockReservadoVenta->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
		<td data-name="stockReservadoCompra"<?php echo $articulos2Dstock->stockReservadoCompra->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoCompra" class="form-group articulos2Dstock_stockReservadoCompra">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoCompra->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoCompra->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoCompra" class="form-group articulos2Dstock_stockReservadoCompra">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoCompra->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoCompra->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockReservadoCompra" class="articulos2Dstock_stockReservadoCompra">
<span<?php echo $articulos2Dstock->stockReservadoCompra->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockReservadoCompra->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
		<td data-name="stockMinimo"<?php echo $articulos2Dstock->stockMinimo->CellAttributes() ?>>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockMinimo" class="form-group articulos2Dstock_stockMinimo">
<input type="text" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockMinimo->EditValue ?>"<?php echo $articulos2Dstock->stockMinimo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockMinimo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockMinimo" class="form-group articulos2Dstock_stockMinimo">
<input type="text" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockMinimo->EditValue ?>"<?php echo $articulos2Dstock->stockMinimo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dstock_grid->RowCnt ?>_articulos2Dstock_stockMinimo" class="articulos2Dstock_stockMinimo">
<span<?php echo $articulos2Dstock->stockMinimo->ViewAttributes() ?>>
<?php echo $articulos2Dstock->stockMinimo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->FormValue) ?>">
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockMinimo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dstock_grid->ListOptions->Render("body", "right", $articulos2Dstock_grid->RowCnt);
?>
	</tr>
<?php if ($articulos2Dstock->RowType == EW_ROWTYPE_ADD || $articulos2Dstock->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
farticulos2Dstockgrid.UpdateOpts(<?php echo $articulos2Dstock_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($articulos2Dstock->CurrentAction <> "gridadd" || $articulos2Dstock->CurrentMode == "copy")
		if (!$articulos2Dstock_grid->Recordset->EOF) $articulos2Dstock_grid->Recordset->MoveNext();
}
?>
<?php
	if ($articulos2Dstock->CurrentMode == "add" || $articulos2Dstock->CurrentMode == "copy" || $articulos2Dstock->CurrentMode == "edit") {
		$articulos2Dstock_grid->RowIndex = '$rowindex$';
		$articulos2Dstock_grid->LoadDefaultValues();

		// Set row properties
		$articulos2Dstock->ResetAttrs();
		$articulos2Dstock->RowAttrs = array_merge($articulos2Dstock->RowAttrs, array('data-rowindex'=>$articulos2Dstock_grid->RowIndex, 'id'=>'r0_articulos2Dstock', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($articulos2Dstock->RowAttrs["class"], "ewTemplate");
		$articulos2Dstock->RowType = EW_ROWTYPE_ADD;

		// Render row
		$articulos2Dstock_grid->RenderRow();

		// Render list options
		$articulos2Dstock_grid->RenderListOptions();
		$articulos2Dstock_grid->StartRowCnt = 0;
?>
	<tr<?php echo $articulos2Dstock->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dstock_grid->ListOptions->Render("body", "left", $articulos2Dstock_grid->RowIndex);
?>
	<?php if ($articulos2Dstock->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<?php if ($articulos2Dstock->idArticulo->getSessionValue() <> "") { ?>
<span id="el$rowindex$_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<select data-table="articulos2Dstock" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dstock->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dstock->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dstock->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dstock->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_idArticulo" class="form-group articulos2Dstock_idArticulo">
<span<?php echo $articulos2Dstock->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idArticulo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idArticulo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dstock->idArticulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->idUnidadMedida->Visible) { // idUnidadMedida ?>
		<td data-name="idUnidadMedida">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_idUnidadMedida" class="form-group articulos2Dstock_idUnidadMedida">
<select data-table="articulos2Dstock" data-field="x_idUnidadMedida" data-value-separator="<?php echo $articulos2Dstock->idUnidadMedida->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida"<?php echo $articulos2Dstock->idUnidadMedida->EditAttributes() ?>>
<?php echo $articulos2Dstock->idUnidadMedida->SelectOptionListHtml("x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="s_x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo $articulos2Dstock->idUnidadMedida->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_idUnidadMedida" class="form-group articulos2Dstock_idUnidadMedida">
<span<?php echo $articulos2Dstock->idUnidadMedida->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->idUnidadMedida->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idUnidadMedida" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dstock->idUnidadMedida->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_idUnidadMedida" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_idUnidadMedida" value="<?php echo ew_HtmlEncode($articulos2Dstock->idUnidadMedida->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->indiceConversion->Visible) { // indiceConversion ?>
		<td data-name="indiceConversion">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_indiceConversion" class="form-group articulos2Dstock_indiceConversion">
<input type="text" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->indiceConversion->EditValue ?>"<?php echo $articulos2Dstock->indiceConversion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_indiceConversion" class="form-group articulos2Dstock_indiceConversion">
<span<?php echo $articulos2Dstock->indiceConversion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->indiceConversion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_indiceConversion" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" value="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_indiceConversion" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_indiceConversion" value="<?php echo ew_HtmlEncode($articulos2Dstock->indiceConversion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stock->Visible) { // stock ?>
		<td data-name="stock">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_stock" class="form-group articulos2Dstock_stock">
<input type="text" data-table="articulos2Dstock" data-field="x_stock" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stock->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stock->EditValue ?>"<?php echo $articulos2Dstock->stock->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_stock" class="form-group articulos2Dstock_stock">
<span<?php echo $articulos2Dstock->stock->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->stock->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stock" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" value="<?php echo ew_HtmlEncode($articulos2Dstock->stock->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stock" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stock" value="<?php echo ew_HtmlEncode($articulos2Dstock->stock->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockReservadoVenta->Visible) { // stockReservadoVenta ?>
		<td data-name="stockReservadoVenta">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_stockReservadoVenta" class="form-group articulos2Dstock_stockReservadoVenta">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoVenta->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoVenta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_stockReservadoVenta" class="form-group articulos2Dstock_stockReservadoVenta">
<span<?php echo $articulos2Dstock->stockReservadoVenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->stockReservadoVenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoVenta" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoVenta" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoVenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockReservadoCompra->Visible) { // stockReservadoCompra ?>
		<td data-name="stockReservadoCompra">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_stockReservadoCompra" class="form-group articulos2Dstock_stockReservadoCompra">
<input type="text" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockReservadoCompra->EditValue ?>"<?php echo $articulos2Dstock->stockReservadoCompra->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_stockReservadoCompra" class="form-group articulos2Dstock_stockReservadoCompra">
<span<?php echo $articulos2Dstock->stockReservadoCompra->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->stockReservadoCompra->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockReservadoCompra" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockReservadoCompra" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockReservadoCompra->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dstock->stockMinimo->Visible) { // stockMinimo ?>
		<td data-name="stockMinimo">
<?php if ($articulos2Dstock->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dstock_stockMinimo" class="form-group articulos2Dstock_stockMinimo">
<input type="text" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->getPlaceHolder()) ?>" value="<?php echo $articulos2Dstock->stockMinimo->EditValue ?>"<?php echo $articulos2Dstock->stockMinimo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dstock_stockMinimo" class="form-group articulos2Dstock_stockMinimo">
<span<?php echo $articulos2Dstock->stockMinimo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dstock->stockMinimo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockMinimo" name="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="x<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dstock" data-field="x_stockMinimo" name="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" id="o<?php echo $articulos2Dstock_grid->RowIndex ?>_stockMinimo" value="<?php echo ew_HtmlEncode($articulos2Dstock->stockMinimo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dstock_grid->ListOptions->Render("body", "right", $articulos2Dstock_grid->RowCnt);
?>
<script type="text/javascript">
farticulos2Dstockgrid.UpdateOpts(<?php echo $articulos2Dstock_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($articulos2Dstock->CurrentMode == "add" || $articulos2Dstock->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $articulos2Dstock_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dstock_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dstock_grid->KeyCount ?>">
<?php echo $articulos2Dstock_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dstock->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $articulos2Dstock_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dstock_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dstock_grid->KeyCount ?>">
<?php echo $articulos2Dstock_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dstock->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="farticulos2Dstockgrid">
</div>
<?php

// Close recordset
if ($articulos2Dstock_grid->Recordset)
	$articulos2Dstock_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($articulos2Dstock_grid->TotalRecs == 0 && $articulos2Dstock->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos2Dstock_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($articulos2Dstock->Export == "") { ?>
<script type="text/javascript">
farticulos2Dstockgrid.Init();
</script>
<?php } ?>
<?php
$articulos2Dstock_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$articulos2Dstock_grid->Page_Terminate();
?>
