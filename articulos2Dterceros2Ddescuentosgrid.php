<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($articulos2Dterceros2Ddescuentos_grid)) $articulos2Dterceros2Ddescuentos_grid = new carticulos2Dterceros2Ddescuentos_grid();

// Page init
$articulos2Dterceros2Ddescuentos_grid->Page_Init();

// Page main
$articulos2Dterceros2Ddescuentos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$articulos2Dterceros2Ddescuentos_grid->Page_Render();
?>
<?php if ($articulos2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">

// Form object
var farticulos2Dterceros2Ddescuentosgrid = new ew_Form("farticulos2Dterceros2Ddescuentosgrid", "grid");
farticulos2Dterceros2Ddescuentosgrid.FormKeyCountName = '<?php echo $articulos2Dterceros2Ddescuentos_grid->FormKeyCountName ?>';

// Validate form
farticulos2Dterceros2Ddescuentosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_descuento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($articulos2Dterceros2Ddescuentos->descuento->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
farticulos2Dterceros2Ddescuentosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idArticulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "descuento", false)) return false;
	return true;
}

// Form_CustomValidate event
farticulos2Dterceros2Ddescuentosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
farticulos2Dterceros2Ddescuentosgrid.ValidateRequired = true;
<?php } else { ?>
farticulos2Dterceros2Ddescuentosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
farticulos2Dterceros2Ddescuentosgrid.Lists["x_idArticulo"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacionExterna","x_denominacionInterna","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"articulos"};
farticulos2Dterceros2Ddescuentosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
	if ($articulos2Dterceros2Ddescuentos->CurrentMode == "copy") {
		$bSelectLimit = $articulos2Dterceros2Ddescuentos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$articulos2Dterceros2Ddescuentos_grid->TotalRecs = $articulos2Dterceros2Ddescuentos->SelectRecordCount();
			$articulos2Dterceros2Ddescuentos_grid->Recordset = $articulos2Dterceros2Ddescuentos_grid->LoadRecordset($articulos2Dterceros2Ddescuentos_grid->StartRec-1, $articulos2Dterceros2Ddescuentos_grid->DisplayRecs);
		} else {
			if ($articulos2Dterceros2Ddescuentos_grid->Recordset = $articulos2Dterceros2Ddescuentos_grid->LoadRecordset())
				$articulos2Dterceros2Ddescuentos_grid->TotalRecs = $articulos2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
		}
		$articulos2Dterceros2Ddescuentos_grid->StartRec = 1;
		$articulos2Dterceros2Ddescuentos_grid->DisplayRecs = $articulos2Dterceros2Ddescuentos_grid->TotalRecs;
	} else {
		$articulos2Dterceros2Ddescuentos->CurrentFilter = "0=1";
		$articulos2Dterceros2Ddescuentos_grid->StartRec = 1;
		$articulos2Dterceros2Ddescuentos_grid->DisplayRecs = $articulos2Dterceros2Ddescuentos->GridAddRowCount;
	}
	$articulos2Dterceros2Ddescuentos_grid->TotalRecs = $articulos2Dterceros2Ddescuentos_grid->DisplayRecs;
	$articulos2Dterceros2Ddescuentos_grid->StopRec = $articulos2Dterceros2Ddescuentos_grid->DisplayRecs;
} else {
	$bSelectLimit = $articulos2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($articulos2Dterceros2Ddescuentos_grid->TotalRecs <= 0)
			$articulos2Dterceros2Ddescuentos_grid->TotalRecs = $articulos2Dterceros2Ddescuentos->SelectRecordCount();
	} else {
		if (!$articulos2Dterceros2Ddescuentos_grid->Recordset && ($articulos2Dterceros2Ddescuentos_grid->Recordset = $articulos2Dterceros2Ddescuentos_grid->LoadRecordset()))
			$articulos2Dterceros2Ddescuentos_grid->TotalRecs = $articulos2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
	}
	$articulos2Dterceros2Ddescuentos_grid->StartRec = 1;
	$articulos2Dterceros2Ddescuentos_grid->DisplayRecs = $articulos2Dterceros2Ddescuentos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$articulos2Dterceros2Ddescuentos_grid->Recordset = $articulos2Dterceros2Ddescuentos_grid->LoadRecordset($articulos2Dterceros2Ddescuentos_grid->StartRec-1, $articulos2Dterceros2Ddescuentos_grid->DisplayRecs);

	// Set no record found message
	if ($articulos2Dterceros2Ddescuentos->CurrentAction == "" && $articulos2Dterceros2Ddescuentos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$articulos2Dterceros2Ddescuentos_grid->setWarningMessage(ew_DeniedMsg());
		if ($articulos2Dterceros2Ddescuentos_grid->SearchWhere == "0=101")
			$articulos2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$articulos2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$articulos2Dterceros2Ddescuentos_grid->RenderOtherOptions();
?>
<?php $articulos2Dterceros2Ddescuentos_grid->ShowPageHeader(); ?>
<?php
$articulos2Dterceros2Ddescuentos_grid->ShowMessage();
?>
<?php if ($articulos2Dterceros2Ddescuentos_grid->TotalRecs > 0 || $articulos2Dterceros2Ddescuentos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid articulos2Dterceros2Ddescuentos">
<div id="farticulos2Dterceros2Ddescuentosgrid" class="ewForm form-inline">
<?php if ($articulos2Dterceros2Ddescuentos_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($articulos2Dterceros2Ddescuentos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_articulos2Dterceros2Ddescuentos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_articulos2Dterceros2Ddescuentosgrid" class="table ewTable">
<?php echo $articulos2Dterceros2Ddescuentos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$articulos2Dterceros2Ddescuentos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$articulos2Dterceros2Ddescuentos_grid->RenderListOptions();

// Render list options (header, left)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "left");
?>
<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->Visible) { // idArticulo ?>
	<?php if ($articulos2Dterceros2Ddescuentos->SortUrl($articulos2Dterceros2Ddescuentos->idArticulo) == "") { ?>
		<th data-name="idArticulo"><div id="elh_articulos2Dterceros2Ddescuentos_idArticulo" class="articulos2Dterceros2Ddescuentos_idArticulo"><div class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idArticulo"><div><div id="elh_articulos2Dterceros2Ddescuentos_idArticulo" class="articulos2Dterceros2Ddescuentos_idArticulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dterceros2Ddescuentos->idArticulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dterceros2Ddescuentos->idArticulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
	<?php if ($articulos2Dterceros2Ddescuentos->SortUrl($articulos2Dterceros2Ddescuentos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_articulos2Dterceros2Ddescuentos_idTercero" class="articulos2Dterceros2Ddescuentos_idTercero"><div class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_articulos2Dterceros2Ddescuentos_idTercero" class="articulos2Dterceros2Ddescuentos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dterceros2Ddescuentos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dterceros2Ddescuentos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($articulos2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
	<?php if ($articulos2Dterceros2Ddescuentos->SortUrl($articulos2Dterceros2Ddescuentos->descuento) == "") { ?>
		<th data-name="descuento"><div id="elh_articulos2Dterceros2Ddescuentos_descuento" class="articulos2Dterceros2Ddescuentos_descuento"><div class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->descuento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descuento"><div><div id="elh_articulos2Dterceros2Ddescuentos_descuento" class="articulos2Dterceros2Ddescuentos_descuento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $articulos2Dterceros2Ddescuentos->descuento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($articulos2Dterceros2Ddescuentos->descuento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($articulos2Dterceros2Ddescuentos->descuento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$articulos2Dterceros2Ddescuentos_grid->StartRec = 1;
$articulos2Dterceros2Ddescuentos_grid->StopRec = $articulos2Dterceros2Ddescuentos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($articulos2Dterceros2Ddescuentos_grid->FormKeyCountName) && ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $articulos2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $articulos2Dterceros2Ddescuentos->CurrentAction == "F")) {
		$articulos2Dterceros2Ddescuentos_grid->KeyCount = $objForm->GetValue($articulos2Dterceros2Ddescuentos_grid->FormKeyCountName);
		$articulos2Dterceros2Ddescuentos_grid->StopRec = $articulos2Dterceros2Ddescuentos_grid->StartRec + $articulos2Dterceros2Ddescuentos_grid->KeyCount - 1;
	}
}
$articulos2Dterceros2Ddescuentos_grid->RecCnt = $articulos2Dterceros2Ddescuentos_grid->StartRec - 1;
if ($articulos2Dterceros2Ddescuentos_grid->Recordset && !$articulos2Dterceros2Ddescuentos_grid->Recordset->EOF) {
	$articulos2Dterceros2Ddescuentos_grid->Recordset->MoveFirst();
	$bSelectLimit = $articulos2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if (!$bSelectLimit && $articulos2Dterceros2Ddescuentos_grid->StartRec > 1)
		$articulos2Dterceros2Ddescuentos_grid->Recordset->Move($articulos2Dterceros2Ddescuentos_grid->StartRec - 1);
} elseif (!$articulos2Dterceros2Ddescuentos->AllowAddDeleteRow && $articulos2Dterceros2Ddescuentos_grid->StopRec == 0) {
	$articulos2Dterceros2Ddescuentos_grid->StopRec = $articulos2Dterceros2Ddescuentos->GridAddRowCount;
}

// Initialize aggregate
$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$articulos2Dterceros2Ddescuentos->ResetAttrs();
$articulos2Dterceros2Ddescuentos_grid->RenderRow();
if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd")
	$articulos2Dterceros2Ddescuentos_grid->RowIndex = 0;
if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridedit")
	$articulos2Dterceros2Ddescuentos_grid->RowIndex = 0;
while ($articulos2Dterceros2Ddescuentos_grid->RecCnt < $articulos2Dterceros2Ddescuentos_grid->StopRec) {
	$articulos2Dterceros2Ddescuentos_grid->RecCnt++;
	if (intval($articulos2Dterceros2Ddescuentos_grid->RecCnt) >= intval($articulos2Dterceros2Ddescuentos_grid->StartRec)) {
		$articulos2Dterceros2Ddescuentos_grid->RowCnt++;
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $articulos2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $articulos2Dterceros2Ddescuentos->CurrentAction == "F") {
			$articulos2Dterceros2Ddescuentos_grid->RowIndex++;
			$objForm->Index = $articulos2Dterceros2Ddescuentos_grid->RowIndex;
			if ($objForm->HasValue($articulos2Dterceros2Ddescuentos_grid->FormActionName))
				$articulos2Dterceros2Ddescuentos_grid->RowAction = strval($objForm->GetValue($articulos2Dterceros2Ddescuentos_grid->FormActionName));
			elseif ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd")
				$articulos2Dterceros2Ddescuentos_grid->RowAction = "insert";
			else
				$articulos2Dterceros2Ddescuentos_grid->RowAction = "";
		}

		// Set up key count
		$articulos2Dterceros2Ddescuentos_grid->KeyCount = $articulos2Dterceros2Ddescuentos_grid->RowIndex;

		// Init row class and style
		$articulos2Dterceros2Ddescuentos->ResetAttrs();
		$articulos2Dterceros2Ddescuentos->CssClass = "";
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
			if ($articulos2Dterceros2Ddescuentos->CurrentMode == "copy") {
				$articulos2Dterceros2Ddescuentos_grid->LoadRowValues($articulos2Dterceros2Ddescuentos_grid->Recordset); // Load row values
				$articulos2Dterceros2Ddescuentos_grid->SetRecordKey($articulos2Dterceros2Ddescuentos_grid->RowOldKey, $articulos2Dterceros2Ddescuentos_grid->Recordset); // Set old record key
			} else {
				$articulos2Dterceros2Ddescuentos_grid->LoadDefaultValues(); // Load default values
				$articulos2Dterceros2Ddescuentos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$articulos2Dterceros2Ddescuentos_grid->LoadRowValues($articulos2Dterceros2Ddescuentos_grid->Recordset); // Load row values
		}
		$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd") // Grid add
			$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridadd" && $articulos2Dterceros2Ddescuentos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$articulos2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($articulos2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridedit") { // Grid edit
			if ($articulos2Dterceros2Ddescuentos->EventCancelled) {
				$articulos2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($articulos2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
			}
			if ($articulos2Dterceros2Ddescuentos_grid->RowAction == "insert")
				$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "gridedit" && ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) && $articulos2Dterceros2Ddescuentos->EventCancelled) // Update failed
			$articulos2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($articulos2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$articulos2Dterceros2Ddescuentos_grid->EditRowCnt++;
		if ($articulos2Dterceros2Ddescuentos->CurrentAction == "F") // Confirm row
			$articulos2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($articulos2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$articulos2Dterceros2Ddescuentos->RowAttrs = array_merge($articulos2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$articulos2Dterceros2Ddescuentos_grid->RowCnt, 'id'=>'r' . $articulos2Dterceros2Ddescuentos_grid->RowCnt . '_articulos2Dterceros2Ddescuentos', 'data-rowtype'=>$articulos2Dterceros2Ddescuentos->RowType));

		// Render row
		$articulos2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$articulos2Dterceros2Ddescuentos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($articulos2Dterceros2Ddescuentos_grid->RowAction <> "delete" && $articulos2Dterceros2Ddescuentos_grid->RowAction <> "insertdelete" && !($articulos2Dterceros2Ddescuentos_grid->RowAction == "insert" && $articulos2Dterceros2Ddescuentos->CurrentAction == "F" && $articulos2Dterceros2Ddescuentos_grid->EmptyRow())) {
?>
	<tr<?php echo $articulos2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $articulos2Dterceros2Ddescuentos_grid->RowCnt);
?>
	<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo"<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->CellAttributes() ?>>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<span<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<span<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idArticulo" class="articulos2Dterceros2Ddescuentos_idArticulo">
<span<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->FormValue) ?>">
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $articulos2Dterceros2Ddescuentos_grid->PageObjName . "_row_" . $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_id" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->id->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $articulos2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($articulos2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $articulos2Dterceros2Ddescuentos->idTercero->CellAttributes() ?>>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($articulos2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<span<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($articulos2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<span<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_idTercero" class="articulos2Dterceros2Ddescuentos_idTercero">
<span<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($articulos2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento"<?php echo $articulos2Dterceros2Ddescuentos->descuento->CellAttributes() ?>>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_descuento" class="form-group articulos2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_descuento" class="form-group articulos2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $articulos2Dterceros2Ddescuentos_grid->RowCnt ?>_articulos2Dterceros2Ddescuentos_descuento" class="articulos2Dterceros2Ddescuentos_descuento">
<span<?php echo $articulos2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->descuento->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $articulos2Dterceros2Ddescuentos_grid->RowCnt);
?>
	</tr>
<?php if ($articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD || $articulos2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
farticulos2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($articulos2Dterceros2Ddescuentos->CurrentAction <> "gridadd" || $articulos2Dterceros2Ddescuentos->CurrentMode == "copy")
		if (!$articulos2Dterceros2Ddescuentos_grid->Recordset->EOF) $articulos2Dterceros2Ddescuentos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($articulos2Dterceros2Ddescuentos->CurrentMode == "add" || $articulos2Dterceros2Ddescuentos->CurrentMode == "copy" || $articulos2Dterceros2Ddescuentos->CurrentMode == "edit") {
		$articulos2Dterceros2Ddescuentos_grid->RowIndex = '$rowindex$';
		$articulos2Dterceros2Ddescuentos_grid->LoadDefaultValues();

		// Set row properties
		$articulos2Dterceros2Ddescuentos->ResetAttrs();
		$articulos2Dterceros2Ddescuentos->RowAttrs = array_merge($articulos2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$articulos2Dterceros2Ddescuentos_grid->RowIndex, 'id'=>'r0_articulos2Dterceros2Ddescuentos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($articulos2Dterceros2Ddescuentos->RowAttrs["class"], "ewTemplate");
		$articulos2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$articulos2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$articulos2Dterceros2Ddescuentos_grid->RenderListOptions();
		$articulos2Dterceros2Ddescuentos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $articulos2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $articulos2Dterceros2Ddescuentos_grid->RowIndex);
?>
	<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->Visible) { // idArticulo ?>
		<td data-name="idArticulo">
<?php if ($articulos2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($articulos2Dterceros2Ddescuentos->idArticulo->getSessionValue() <> "") { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<span<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo"<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idArticulo" class="form-group articulos2Dterceros2Ddescuentos_idArticulo">
<span<?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idArticulo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idArticulo" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idArticulo" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idArticulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($articulos2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($articulos2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<span<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<select data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $articulos2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $articulos2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $articulos2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_idTercero" class="form-group articulos2Dterceros2Ddescuentos_idTercero">
<span<?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($articulos2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento">
<?php if ($articulos2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_descuento" class="form-group articulos2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $articulos2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulos2Dterceros2Ddescuentos_descuento" class="form-group articulos2Dterceros2Ddescuentos_descuento">
<span<?php echo $articulos2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $articulos2Dterceros2Ddescuentos->descuento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulos2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($articulos2Dterceros2Ddescuentos->descuento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$articulos2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $articulos2Dterceros2Ddescuentos_grid->RowCnt);
?>
<script type="text/javascript">
farticulos2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $articulos2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($articulos2Dterceros2Ddescuentos->CurrentMode == "add" || $articulos2Dterceros2Ddescuentos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $articulos2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $articulos2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $articulos2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $articulos2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $articulos2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $articulos2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="farticulos2Dterceros2Ddescuentosgrid">
</div>
<?php

// Close recordset
if ($articulos2Dterceros2Ddescuentos_grid->Recordset)
	$articulos2Dterceros2Ddescuentos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos_grid->TotalRecs == 0 && $articulos2Dterceros2Ddescuentos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($articulos2Dterceros2Ddescuentos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($articulos2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">
farticulos2Dterceros2Ddescuentosgrid.Init();
</script>
<?php } ?>
<?php
$articulos2Dterceros2Ddescuentos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$articulos2Dterceros2Ddescuentos_grid->Page_Terminate();
?>
