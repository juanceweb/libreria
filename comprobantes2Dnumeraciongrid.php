<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($comprobantes2Dnumeracion_grid)) $comprobantes2Dnumeracion_grid = new ccomprobantes2Dnumeracion_grid();

// Page init
$comprobantes2Dnumeracion_grid->Page_Init();

// Page main
$comprobantes2Dnumeracion_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes2Dnumeracion_grid->Page_Render();
?>
<?php if ($comprobantes2Dnumeracion->Export == "") { ?>
<script type="text/javascript">

// Form object
var fcomprobantes2Dnumeraciongrid = new ew_Form("fcomprobantes2Dnumeraciongrid", "grid");
fcomprobantes2Dnumeraciongrid.FormKeyCountName = '<?php echo $comprobantes2Dnumeracion_grid->FormKeyCountName ?>';

// Validate form
fcomprobantes2Dnumeraciongrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_puntoVenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->puntoVenta->FldCaption(), $comprobantes2Dnumeracion->puntoVenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_puntoVenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->puntoVenta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->ultimoNumero->FldCaption(), $comprobantes2Dnumeracion->ultimoNumero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumero");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->ultimoNumero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumeroContable");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $comprobantes2Dnumeracion->ultimoNumeroContable->FldCaption(), $comprobantes2Dnumeracion->ultimoNumeroContable->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ultimoNumeroContable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($comprobantes2Dnumeracion->ultimoNumeroContable->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fcomprobantes2Dnumeraciongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "puntoVenta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ultimoNumero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ultimoNumeroContable", false)) return false;
	return true;
}

// Form_CustomValidate event
fcomprobantes2Dnumeraciongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantes2Dnumeraciongrid.ValidateRequired = true;
<?php } else { ?>
fcomprobantes2Dnumeraciongrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($comprobantes2Dnumeracion->CurrentAction == "gridadd") {
	if ($comprobantes2Dnumeracion->CurrentMode == "copy") {
		$bSelectLimit = $comprobantes2Dnumeracion_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$comprobantes2Dnumeracion_grid->TotalRecs = $comprobantes2Dnumeracion->SelectRecordCount();
			$comprobantes2Dnumeracion_grid->Recordset = $comprobantes2Dnumeracion_grid->LoadRecordset($comprobantes2Dnumeracion_grid->StartRec-1, $comprobantes2Dnumeracion_grid->DisplayRecs);
		} else {
			if ($comprobantes2Dnumeracion_grid->Recordset = $comprobantes2Dnumeracion_grid->LoadRecordset())
				$comprobantes2Dnumeracion_grid->TotalRecs = $comprobantes2Dnumeracion_grid->Recordset->RecordCount();
		}
		$comprobantes2Dnumeracion_grid->StartRec = 1;
		$comprobantes2Dnumeracion_grid->DisplayRecs = $comprobantes2Dnumeracion_grid->TotalRecs;
	} else {
		$comprobantes2Dnumeracion->CurrentFilter = "0=1";
		$comprobantes2Dnumeracion_grid->StartRec = 1;
		$comprobantes2Dnumeracion_grid->DisplayRecs = $comprobantes2Dnumeracion->GridAddRowCount;
	}
	$comprobantes2Dnumeracion_grid->TotalRecs = $comprobantes2Dnumeracion_grid->DisplayRecs;
	$comprobantes2Dnumeracion_grid->StopRec = $comprobantes2Dnumeracion_grid->DisplayRecs;
} else {
	$bSelectLimit = $comprobantes2Dnumeracion_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($comprobantes2Dnumeracion_grid->TotalRecs <= 0)
			$comprobantes2Dnumeracion_grid->TotalRecs = $comprobantes2Dnumeracion->SelectRecordCount();
	} else {
		if (!$comprobantes2Dnumeracion_grid->Recordset && ($comprobantes2Dnumeracion_grid->Recordset = $comprobantes2Dnumeracion_grid->LoadRecordset()))
			$comprobantes2Dnumeracion_grid->TotalRecs = $comprobantes2Dnumeracion_grid->Recordset->RecordCount();
	}
	$comprobantes2Dnumeracion_grid->StartRec = 1;
	$comprobantes2Dnumeracion_grid->DisplayRecs = $comprobantes2Dnumeracion_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$comprobantes2Dnumeracion_grid->Recordset = $comprobantes2Dnumeracion_grid->LoadRecordset($comprobantes2Dnumeracion_grid->StartRec-1, $comprobantes2Dnumeracion_grid->DisplayRecs);

	// Set no record found message
	if ($comprobantes2Dnumeracion->CurrentAction == "" && $comprobantes2Dnumeracion_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$comprobantes2Dnumeracion_grid->setWarningMessage(ew_DeniedMsg());
		if ($comprobantes2Dnumeracion_grid->SearchWhere == "0=101")
			$comprobantes2Dnumeracion_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$comprobantes2Dnumeracion_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$comprobantes2Dnumeracion_grid->RenderOtherOptions();
?>
<?php $comprobantes2Dnumeracion_grid->ShowPageHeader(); ?>
<?php
$comprobantes2Dnumeracion_grid->ShowMessage();
?>
<?php if ($comprobantes2Dnumeracion_grid->TotalRecs > 0 || $comprobantes2Dnumeracion->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid comprobantes2Dnumeracion">
<div id="fcomprobantes2Dnumeraciongrid" class="ewForm form-inline">
<?php if ($comprobantes2Dnumeracion_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($comprobantes2Dnumeracion_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_comprobantes2Dnumeracion" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_comprobantes2Dnumeraciongrid" class="table ewTable">
<?php echo $comprobantes2Dnumeracion->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$comprobantes2Dnumeracion_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$comprobantes2Dnumeracion_grid->RenderListOptions();

// Render list options (header, left)
$comprobantes2Dnumeracion_grid->ListOptions->Render("header", "left");
?>
<?php if ($comprobantes2Dnumeracion->puntoVenta->Visible) { // puntoVenta ?>
	<?php if ($comprobantes2Dnumeracion->SortUrl($comprobantes2Dnumeracion->puntoVenta) == "") { ?>
		<th data-name="puntoVenta"><div id="elh_comprobantes2Dnumeracion_puntoVenta" class="comprobantes2Dnumeracion_puntoVenta"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->puntoVenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="puntoVenta"><div><div id="elh_comprobantes2Dnumeracion_puntoVenta" class="comprobantes2Dnumeracion_puntoVenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->puntoVenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dnumeracion->puntoVenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dnumeracion->puntoVenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($comprobantes2Dnumeracion->ultimoNumero->Visible) { // ultimoNumero ?>
	<?php if ($comprobantes2Dnumeracion->SortUrl($comprobantes2Dnumeracion->ultimoNumero) == "") { ?>
		<th data-name="ultimoNumero"><div id="elh_comprobantes2Dnumeracion_ultimoNumero" class="comprobantes2Dnumeracion_ultimoNumero"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->ultimoNumero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ultimoNumero"><div><div id="elh_comprobantes2Dnumeracion_ultimoNumero" class="comprobantes2Dnumeracion_ultimoNumero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->ultimoNumero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dnumeracion->ultimoNumero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dnumeracion->ultimoNumero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($comprobantes2Dnumeracion->ultimoNumeroContable->Visible) { // ultimoNumeroContable ?>
	<?php if ($comprobantes2Dnumeracion->SortUrl($comprobantes2Dnumeracion->ultimoNumeroContable) == "") { ?>
		<th data-name="ultimoNumeroContable"><div id="elh_comprobantes2Dnumeracion_ultimoNumeroContable" class="comprobantes2Dnumeracion_ultimoNumeroContable"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ultimoNumeroContable"><div><div id="elh_comprobantes2Dnumeracion_ultimoNumeroContable" class="comprobantes2Dnumeracion_ultimoNumeroContable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dnumeracion->ultimoNumeroContable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dnumeracion->ultimoNumeroContable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$comprobantes2Dnumeracion_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$comprobantes2Dnumeracion_grid->StartRec = 1;
$comprobantes2Dnumeracion_grid->StopRec = $comprobantes2Dnumeracion_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($comprobantes2Dnumeracion_grid->FormKeyCountName) && ($comprobantes2Dnumeracion->CurrentAction == "gridadd" || $comprobantes2Dnumeracion->CurrentAction == "gridedit" || $comprobantes2Dnumeracion->CurrentAction == "F")) {
		$comprobantes2Dnumeracion_grid->KeyCount = $objForm->GetValue($comprobantes2Dnumeracion_grid->FormKeyCountName);
		$comprobantes2Dnumeracion_grid->StopRec = $comprobantes2Dnumeracion_grid->StartRec + $comprobantes2Dnumeracion_grid->KeyCount - 1;
	}
}
$comprobantes2Dnumeracion_grid->RecCnt = $comprobantes2Dnumeracion_grid->StartRec - 1;
if ($comprobantes2Dnumeracion_grid->Recordset && !$comprobantes2Dnumeracion_grid->Recordset->EOF) {
	$comprobantes2Dnumeracion_grid->Recordset->MoveFirst();
	$bSelectLimit = $comprobantes2Dnumeracion_grid->UseSelectLimit;
	if (!$bSelectLimit && $comprobantes2Dnumeracion_grid->StartRec > 1)
		$comprobantes2Dnumeracion_grid->Recordset->Move($comprobantes2Dnumeracion_grid->StartRec - 1);
} elseif (!$comprobantes2Dnumeracion->AllowAddDeleteRow && $comprobantes2Dnumeracion_grid->StopRec == 0) {
	$comprobantes2Dnumeracion_grid->StopRec = $comprobantes2Dnumeracion->GridAddRowCount;
}

// Initialize aggregate
$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_AGGREGATEINIT;
$comprobantes2Dnumeracion->ResetAttrs();
$comprobantes2Dnumeracion_grid->RenderRow();
if ($comprobantes2Dnumeracion->CurrentAction == "gridadd")
	$comprobantes2Dnumeracion_grid->RowIndex = 0;
if ($comprobantes2Dnumeracion->CurrentAction == "gridedit")
	$comprobantes2Dnumeracion_grid->RowIndex = 0;
while ($comprobantes2Dnumeracion_grid->RecCnt < $comprobantes2Dnumeracion_grid->StopRec) {
	$comprobantes2Dnumeracion_grid->RecCnt++;
	if (intval($comprobantes2Dnumeracion_grid->RecCnt) >= intval($comprobantes2Dnumeracion_grid->StartRec)) {
		$comprobantes2Dnumeracion_grid->RowCnt++;
		if ($comprobantes2Dnumeracion->CurrentAction == "gridadd" || $comprobantes2Dnumeracion->CurrentAction == "gridedit" || $comprobantes2Dnumeracion->CurrentAction == "F") {
			$comprobantes2Dnumeracion_grid->RowIndex++;
			$objForm->Index = $comprobantes2Dnumeracion_grid->RowIndex;
			if ($objForm->HasValue($comprobantes2Dnumeracion_grid->FormActionName))
				$comprobantes2Dnumeracion_grid->RowAction = strval($objForm->GetValue($comprobantes2Dnumeracion_grid->FormActionName));
			elseif ($comprobantes2Dnumeracion->CurrentAction == "gridadd")
				$comprobantes2Dnumeracion_grid->RowAction = "insert";
			else
				$comprobantes2Dnumeracion_grid->RowAction = "";
		}

		// Set up key count
		$comprobantes2Dnumeracion_grid->KeyCount = $comprobantes2Dnumeracion_grid->RowIndex;

		// Init row class and style
		$comprobantes2Dnumeracion->ResetAttrs();
		$comprobantes2Dnumeracion->CssClass = "";
		if ($comprobantes2Dnumeracion->CurrentAction == "gridadd") {
			if ($comprobantes2Dnumeracion->CurrentMode == "copy") {
				$comprobantes2Dnumeracion_grid->LoadRowValues($comprobantes2Dnumeracion_grid->Recordset); // Load row values
				$comprobantes2Dnumeracion_grid->SetRecordKey($comprobantes2Dnumeracion_grid->RowOldKey, $comprobantes2Dnumeracion_grid->Recordset); // Set old record key
			} else {
				$comprobantes2Dnumeracion_grid->LoadDefaultValues(); // Load default values
				$comprobantes2Dnumeracion_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$comprobantes2Dnumeracion_grid->LoadRowValues($comprobantes2Dnumeracion_grid->Recordset); // Load row values
		}
		$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($comprobantes2Dnumeracion->CurrentAction == "gridadd") // Grid add
			$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_ADD; // Render add
		if ($comprobantes2Dnumeracion->CurrentAction == "gridadd" && $comprobantes2Dnumeracion->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$comprobantes2Dnumeracion_grid->RestoreCurrentRowFormValues($comprobantes2Dnumeracion_grid->RowIndex); // Restore form values
		if ($comprobantes2Dnumeracion->CurrentAction == "gridedit") { // Grid edit
			if ($comprobantes2Dnumeracion->EventCancelled) {
				$comprobantes2Dnumeracion_grid->RestoreCurrentRowFormValues($comprobantes2Dnumeracion_grid->RowIndex); // Restore form values
			}
			if ($comprobantes2Dnumeracion_grid->RowAction == "insert")
				$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($comprobantes2Dnumeracion->CurrentAction == "gridedit" && ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT || $comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD) && $comprobantes2Dnumeracion->EventCancelled) // Update failed
			$comprobantes2Dnumeracion_grid->RestoreCurrentRowFormValues($comprobantes2Dnumeracion_grid->RowIndex); // Restore form values
		if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT) // Edit row
			$comprobantes2Dnumeracion_grid->EditRowCnt++;
		if ($comprobantes2Dnumeracion->CurrentAction == "F") // Confirm row
			$comprobantes2Dnumeracion_grid->RestoreCurrentRowFormValues($comprobantes2Dnumeracion_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$comprobantes2Dnumeracion->RowAttrs = array_merge($comprobantes2Dnumeracion->RowAttrs, array('data-rowindex'=>$comprobantes2Dnumeracion_grid->RowCnt, 'id'=>'r' . $comprobantes2Dnumeracion_grid->RowCnt . '_comprobantes2Dnumeracion', 'data-rowtype'=>$comprobantes2Dnumeracion->RowType));

		// Render row
		$comprobantes2Dnumeracion_grid->RenderRow();

		// Render list options
		$comprobantes2Dnumeracion_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($comprobantes2Dnumeracion_grid->RowAction <> "delete" && $comprobantes2Dnumeracion_grid->RowAction <> "insertdelete" && !($comprobantes2Dnumeracion_grid->RowAction == "insert" && $comprobantes2Dnumeracion->CurrentAction == "F" && $comprobantes2Dnumeracion_grid->EmptyRow())) {
?>
	<tr<?php echo $comprobantes2Dnumeracion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$comprobantes2Dnumeracion_grid->ListOptions->Render("body", "left", $comprobantes2Dnumeracion_grid->RowCnt);
?>
	<?php if ($comprobantes2Dnumeracion->puntoVenta->Visible) { // puntoVenta ?>
		<td data-name="puntoVenta"<?php echo $comprobantes2Dnumeracion->puntoVenta->CellAttributes() ?>>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_puntoVenta" class="form-group comprobantes2Dnumeracion_puntoVenta">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->puntoVenta->EditValue ?>"<?php echo $comprobantes2Dnumeracion->puntoVenta->EditAttributes() ?>>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_puntoVenta" class="form-group comprobantes2Dnumeracion_puntoVenta">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->puntoVenta->EditValue ?>"<?php echo $comprobantes2Dnumeracion->puntoVenta->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_puntoVenta" class="comprobantes2Dnumeracion_puntoVenta">
<span<?php echo $comprobantes2Dnumeracion->puntoVenta->ViewAttributes() ?>>
<?php echo $comprobantes2Dnumeracion->puntoVenta->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->FormValue) ?>">
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->OldValue) ?>">
<?php } ?>
<a id="<?php echo $comprobantes2Dnumeracion_grid->PageObjName . "_row_" . $comprobantes2Dnumeracion_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_id" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->id->CurrentValue) ?>">
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_id" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->id->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT || $comprobantes2Dnumeracion->CurrentMode == "edit") { ?>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_id" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($comprobantes2Dnumeracion->ultimoNumero->Visible) { // ultimoNumero ?>
		<td data-name="ultimoNumero"<?php echo $comprobantes2Dnumeracion->ultimoNumero->CellAttributes() ?>>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumero" class="form-group comprobantes2Dnumeracion_ultimoNumero">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditAttributes() ?>>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumero" class="form-group comprobantes2Dnumeracion_ultimoNumero">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumero" class="comprobantes2Dnumeracion_ultimoNumero">
<span<?php echo $comprobantes2Dnumeracion->ultimoNumero->ViewAttributes() ?>>
<?php echo $comprobantes2Dnumeracion->ultimoNumero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->FormValue) ?>">
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($comprobantes2Dnumeracion->ultimoNumeroContable->Visible) { // ultimoNumeroContable ?>
		<td data-name="ultimoNumeroContable"<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->CellAttributes() ?>>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumeroContable" class="form-group comprobantes2Dnumeracion_ultimoNumeroContable">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditAttributes() ?>>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumeroContable" class="form-group comprobantes2Dnumeracion_ultimoNumeroContable">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $comprobantes2Dnumeracion_grid->RowCnt ?>_comprobantes2Dnumeracion_ultimoNumeroContable" class="comprobantes2Dnumeracion_ultimoNumeroContable">
<span<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->ViewAttributes() ?>>
<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->FormValue) ?>">
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$comprobantes2Dnumeracion_grid->ListOptions->Render("body", "right", $comprobantes2Dnumeracion_grid->RowCnt);
?>
	</tr>
<?php if ($comprobantes2Dnumeracion->RowType == EW_ROWTYPE_ADD || $comprobantes2Dnumeracion->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcomprobantes2Dnumeraciongrid.UpdateOpts(<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($comprobantes2Dnumeracion->CurrentAction <> "gridadd" || $comprobantes2Dnumeracion->CurrentMode == "copy")
		if (!$comprobantes2Dnumeracion_grid->Recordset->EOF) $comprobantes2Dnumeracion_grid->Recordset->MoveNext();
}
?>
<?php
	if ($comprobantes2Dnumeracion->CurrentMode == "add" || $comprobantes2Dnumeracion->CurrentMode == "copy" || $comprobantes2Dnumeracion->CurrentMode == "edit") {
		$comprobantes2Dnumeracion_grid->RowIndex = '$rowindex$';
		$comprobantes2Dnumeracion_grid->LoadDefaultValues();

		// Set row properties
		$comprobantes2Dnumeracion->ResetAttrs();
		$comprobantes2Dnumeracion->RowAttrs = array_merge($comprobantes2Dnumeracion->RowAttrs, array('data-rowindex'=>$comprobantes2Dnumeracion_grid->RowIndex, 'id'=>'r0_comprobantes2Dnumeracion', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($comprobantes2Dnumeracion->RowAttrs["class"], "ewTemplate");
		$comprobantes2Dnumeracion->RowType = EW_ROWTYPE_ADD;

		// Render row
		$comprobantes2Dnumeracion_grid->RenderRow();

		// Render list options
		$comprobantes2Dnumeracion_grid->RenderListOptions();
		$comprobantes2Dnumeracion_grid->StartRowCnt = 0;
?>
	<tr<?php echo $comprobantes2Dnumeracion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$comprobantes2Dnumeracion_grid->ListOptions->Render("body", "left", $comprobantes2Dnumeracion_grid->RowIndex);
?>
	<?php if ($comprobantes2Dnumeracion->puntoVenta->Visible) { // puntoVenta ?>
		<td data-name="puntoVenta">
<?php if ($comprobantes2Dnumeracion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_puntoVenta" class="form-group comprobantes2Dnumeracion_puntoVenta">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->puntoVenta->EditValue ?>"<?php echo $comprobantes2Dnumeracion->puntoVenta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_puntoVenta" class="form-group comprobantes2Dnumeracion_puntoVenta">
<span<?php echo $comprobantes2Dnumeracion->puntoVenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dnumeracion->puntoVenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_puntoVenta" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_puntoVenta" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->puntoVenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($comprobantes2Dnumeracion->ultimoNumero->Visible) { // ultimoNumero ?>
		<td data-name="ultimoNumero">
<?php if ($comprobantes2Dnumeracion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_ultimoNumero" class="form-group comprobantes2Dnumeracion_ultimoNumero">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumero->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_ultimoNumero" class="form-group comprobantes2Dnumeracion_ultimoNumero">
<span<?php echo $comprobantes2Dnumeracion->ultimoNumero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dnumeracion->ultimoNumero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumero" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumero" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($comprobantes2Dnumeracion->ultimoNumeroContable->Visible) { // ultimoNumeroContable ?>
		<td data-name="ultimoNumeroContable">
<?php if ($comprobantes2Dnumeracion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_ultimoNumeroContable" class="form-group comprobantes2Dnumeracion_ultimoNumeroContable">
<input type="text" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" size="30" placeholder="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->getPlaceHolder()) ?>" value="<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditValue ?>"<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dnumeracion_ultimoNumeroContable" class="form-group comprobantes2Dnumeracion_ultimoNumeroContable">
<span<?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dnumeracion->ultimoNumeroContable->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="x<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="comprobantes2Dnumeracion" data-field="x_ultimoNumeroContable" name="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" id="o<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>_ultimoNumeroContable" value="<?php echo ew_HtmlEncode($comprobantes2Dnumeracion->ultimoNumeroContable->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$comprobantes2Dnumeracion_grid->ListOptions->Render("body", "right", $comprobantes2Dnumeracion_grid->RowCnt);
?>
<script type="text/javascript">
fcomprobantes2Dnumeraciongrid.UpdateOpts(<?php echo $comprobantes2Dnumeracion_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($comprobantes2Dnumeracion->CurrentMode == "add" || $comprobantes2Dnumeracion->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $comprobantes2Dnumeracion_grid->FormKeyCountName ?>" id="<?php echo $comprobantes2Dnumeracion_grid->FormKeyCountName ?>" value="<?php echo $comprobantes2Dnumeracion_grid->KeyCount ?>">
<?php echo $comprobantes2Dnumeracion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $comprobantes2Dnumeracion_grid->FormKeyCountName ?>" id="<?php echo $comprobantes2Dnumeracion_grid->FormKeyCountName ?>" value="<?php echo $comprobantes2Dnumeracion_grid->KeyCount ?>">
<?php echo $comprobantes2Dnumeracion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcomprobantes2Dnumeraciongrid">
</div>
<?php

// Close recordset
if ($comprobantes2Dnumeracion_grid->Recordset)
	$comprobantes2Dnumeracion_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion_grid->TotalRecs == 0 && $comprobantes2Dnumeracion->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($comprobantes2Dnumeracion_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($comprobantes2Dnumeracion->Export == "") { ?>
<script type="text/javascript">
fcomprobantes2Dnumeraciongrid.Init();
</script>
<?php } ?>
<?php
$comprobantes2Dnumeracion_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$comprobantes2Dnumeracion_grid->Page_Terminate();
?>
