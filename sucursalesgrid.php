<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($sucursales_grid)) $sucursales_grid = new csucursales_grid();

// Page init
$sucursales_grid->Page_Init();

// Page main
$sucursales_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sucursales_grid->Page_Render();
?>
<?php if ($sucursales->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsucursalesgrid = new ew_Form("fsucursalesgrid", "grid");
fsucursalesgrid.FormKeyCountName = '<?php echo $sucursales_grid->FormKeyCountName ?>';

// Validate form
fsucursalesgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_orden");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sucursales->orden->FldCaption(), $sucursales->orden->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_orden");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sucursales->orden->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsucursalesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idTerceroPadre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTerceroSucursal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "orden", false)) return false;
	return true;
}

// Form_CustomValidate event
fsucursalesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsucursalesgrid.ValidateRequired = true;
<?php } else { ?>
fsucursalesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsucursalesgrid.Lists["x_idTerceroPadre"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fsucursalesgrid.Lists["x_idTerceroSucursal"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($sucursales->CurrentAction == "gridadd") {
	if ($sucursales->CurrentMode == "copy") {
		$bSelectLimit = $sucursales_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$sucursales_grid->TotalRecs = $sucursales->SelectRecordCount();
			$sucursales_grid->Recordset = $sucursales_grid->LoadRecordset($sucursales_grid->StartRec-1, $sucursales_grid->DisplayRecs);
		} else {
			if ($sucursales_grid->Recordset = $sucursales_grid->LoadRecordset())
				$sucursales_grid->TotalRecs = $sucursales_grid->Recordset->RecordCount();
		}
		$sucursales_grid->StartRec = 1;
		$sucursales_grid->DisplayRecs = $sucursales_grid->TotalRecs;
	} else {
		$sucursales->CurrentFilter = "0=1";
		$sucursales_grid->StartRec = 1;
		$sucursales_grid->DisplayRecs = $sucursales->GridAddRowCount;
	}
	$sucursales_grid->TotalRecs = $sucursales_grid->DisplayRecs;
	$sucursales_grid->StopRec = $sucursales_grid->DisplayRecs;
} else {
	$bSelectLimit = $sucursales_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sucursales_grid->TotalRecs <= 0)
			$sucursales_grid->TotalRecs = $sucursales->SelectRecordCount();
	} else {
		if (!$sucursales_grid->Recordset && ($sucursales_grid->Recordset = $sucursales_grid->LoadRecordset()))
			$sucursales_grid->TotalRecs = $sucursales_grid->Recordset->RecordCount();
	}
	$sucursales_grid->StartRec = 1;
	$sucursales_grid->DisplayRecs = $sucursales_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$sucursales_grid->Recordset = $sucursales_grid->LoadRecordset($sucursales_grid->StartRec-1, $sucursales_grid->DisplayRecs);

	// Set no record found message
	if ($sucursales->CurrentAction == "" && $sucursales_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$sucursales_grid->setWarningMessage(ew_DeniedMsg());
		if ($sucursales_grid->SearchWhere == "0=101")
			$sucursales_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sucursales_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$sucursales_grid->RenderOtherOptions();
?>
<?php $sucursales_grid->ShowPageHeader(); ?>
<?php
$sucursales_grid->ShowMessage();
?>
<?php if ($sucursales_grid->TotalRecs > 0 || $sucursales->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid sucursales">
<div id="fsucursalesgrid" class="ewForm form-inline">
<?php if ($sucursales_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($sucursales_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_sucursales" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_sucursalesgrid" class="table ewTable">
<?php echo $sucursales->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sucursales_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sucursales_grid->RenderListOptions();

// Render list options (header, left)
$sucursales_grid->ListOptions->Render("header", "left");
?>
<?php if ($sucursales->idTerceroPadre->Visible) { // idTerceroPadre ?>
	<?php if ($sucursales->SortUrl($sucursales->idTerceroPadre) == "") { ?>
		<th data-name="idTerceroPadre"><div id="elh_sucursales_idTerceroPadre" class="sucursales_idTerceroPadre"><div class="ewTableHeaderCaption"><?php echo $sucursales->idTerceroPadre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTerceroPadre"><div><div id="elh_sucursales_idTerceroPadre" class="sucursales_idTerceroPadre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sucursales->idTerceroPadre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sucursales->idTerceroPadre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sucursales->idTerceroPadre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sucursales->idTerceroSucursal->Visible) { // idTerceroSucursal ?>
	<?php if ($sucursales->SortUrl($sucursales->idTerceroSucursal) == "") { ?>
		<th data-name="idTerceroSucursal"><div id="elh_sucursales_idTerceroSucursal" class="sucursales_idTerceroSucursal"><div class="ewTableHeaderCaption"><?php echo $sucursales->idTerceroSucursal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTerceroSucursal"><div><div id="elh_sucursales_idTerceroSucursal" class="sucursales_idTerceroSucursal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sucursales->idTerceroSucursal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sucursales->idTerceroSucursal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sucursales->idTerceroSucursal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sucursales->orden->Visible) { // orden ?>
	<?php if ($sucursales->SortUrl($sucursales->orden) == "") { ?>
		<th data-name="orden"><div id="elh_sucursales_orden" class="sucursales_orden"><div class="ewTableHeaderCaption"><?php echo $sucursales->orden->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="orden"><div><div id="elh_sucursales_orden" class="sucursales_orden">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sucursales->orden->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sucursales->orden->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sucursales->orden->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sucursales_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$sucursales_grid->StartRec = 1;
$sucursales_grid->StopRec = $sucursales_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($sucursales_grid->FormKeyCountName) && ($sucursales->CurrentAction == "gridadd" || $sucursales->CurrentAction == "gridedit" || $sucursales->CurrentAction == "F")) {
		$sucursales_grid->KeyCount = $objForm->GetValue($sucursales_grid->FormKeyCountName);
		$sucursales_grid->StopRec = $sucursales_grid->StartRec + $sucursales_grid->KeyCount - 1;
	}
}
$sucursales_grid->RecCnt = $sucursales_grid->StartRec - 1;
if ($sucursales_grid->Recordset && !$sucursales_grid->Recordset->EOF) {
	$sucursales_grid->Recordset->MoveFirst();
	$bSelectLimit = $sucursales_grid->UseSelectLimit;
	if (!$bSelectLimit && $sucursales_grid->StartRec > 1)
		$sucursales_grid->Recordset->Move($sucursales_grid->StartRec - 1);
} elseif (!$sucursales->AllowAddDeleteRow && $sucursales_grid->StopRec == 0) {
	$sucursales_grid->StopRec = $sucursales->GridAddRowCount;
}

// Initialize aggregate
$sucursales->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sucursales->ResetAttrs();
$sucursales_grid->RenderRow();
if ($sucursales->CurrentAction == "gridadd")
	$sucursales_grid->RowIndex = 0;
if ($sucursales->CurrentAction == "gridedit")
	$sucursales_grid->RowIndex = 0;
while ($sucursales_grid->RecCnt < $sucursales_grid->StopRec) {
	$sucursales_grid->RecCnt++;
	if (intval($sucursales_grid->RecCnt) >= intval($sucursales_grid->StartRec)) {
		$sucursales_grid->RowCnt++;
		if ($sucursales->CurrentAction == "gridadd" || $sucursales->CurrentAction == "gridedit" || $sucursales->CurrentAction == "F") {
			$sucursales_grid->RowIndex++;
			$objForm->Index = $sucursales_grid->RowIndex;
			if ($objForm->HasValue($sucursales_grid->FormActionName))
				$sucursales_grid->RowAction = strval($objForm->GetValue($sucursales_grid->FormActionName));
			elseif ($sucursales->CurrentAction == "gridadd")
				$sucursales_grid->RowAction = "insert";
			else
				$sucursales_grid->RowAction = "";
		}

		// Set up key count
		$sucursales_grid->KeyCount = $sucursales_grid->RowIndex;

		// Init row class and style
		$sucursales->ResetAttrs();
		$sucursales->CssClass = "";
		if ($sucursales->CurrentAction == "gridadd") {
			if ($sucursales->CurrentMode == "copy") {
				$sucursales_grid->LoadRowValues($sucursales_grid->Recordset); // Load row values
				$sucursales_grid->SetRecordKey($sucursales_grid->RowOldKey, $sucursales_grid->Recordset); // Set old record key
			} else {
				$sucursales_grid->LoadDefaultValues(); // Load default values
				$sucursales_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$sucursales_grid->LoadRowValues($sucursales_grid->Recordset); // Load row values
		}
		$sucursales->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($sucursales->CurrentAction == "gridadd") // Grid add
			$sucursales->RowType = EW_ROWTYPE_ADD; // Render add
		if ($sucursales->CurrentAction == "gridadd" && $sucursales->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$sucursales_grid->RestoreCurrentRowFormValues($sucursales_grid->RowIndex); // Restore form values
		if ($sucursales->CurrentAction == "gridedit") { // Grid edit
			if ($sucursales->EventCancelled) {
				$sucursales_grid->RestoreCurrentRowFormValues($sucursales_grid->RowIndex); // Restore form values
			}
			if ($sucursales_grid->RowAction == "insert")
				$sucursales->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$sucursales->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($sucursales->CurrentAction == "gridedit" && ($sucursales->RowType == EW_ROWTYPE_EDIT || $sucursales->RowType == EW_ROWTYPE_ADD) && $sucursales->EventCancelled) // Update failed
			$sucursales_grid->RestoreCurrentRowFormValues($sucursales_grid->RowIndex); // Restore form values
		if ($sucursales->RowType == EW_ROWTYPE_EDIT) // Edit row
			$sucursales_grid->EditRowCnt++;
		if ($sucursales->CurrentAction == "F") // Confirm row
			$sucursales_grid->RestoreCurrentRowFormValues($sucursales_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$sucursales->RowAttrs = array_merge($sucursales->RowAttrs, array('data-rowindex'=>$sucursales_grid->RowCnt, 'id'=>'r' . $sucursales_grid->RowCnt . '_sucursales', 'data-rowtype'=>$sucursales->RowType));

		// Render row
		$sucursales_grid->RenderRow();

		// Render list options
		$sucursales_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($sucursales_grid->RowAction <> "delete" && $sucursales_grid->RowAction <> "insertdelete" && !($sucursales_grid->RowAction == "insert" && $sucursales->CurrentAction == "F" && $sucursales_grid->EmptyRow())) {
?>
	<tr<?php echo $sucursales->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sucursales_grid->ListOptions->Render("body", "left", $sucursales_grid->RowCnt);
?>
	<?php if ($sucursales->idTerceroPadre->Visible) { // idTerceroPadre ?>
		<td data-name="idTerceroPadre"<?php echo $sucursales->idTerceroPadre->CellAttributes() ?>>
<?php if ($sucursales->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($sucursales->idTerceroPadre->getSessionValue() <> "") { ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<span<?php echo $sucursales->idTerceroPadre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->idTerceroPadre->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<select data-table="sucursales" data-field="x_idTerceroPadre" data-value-separator="<?php echo $sucursales->idTerceroPadre->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre"<?php echo $sucursales->idTerceroPadre->EditAttributes() ?>>
<?php echo $sucursales->idTerceroPadre->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo $sucursales->idTerceroPadre->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroPadre" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->OldValue) ?>">
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($sucursales->idTerceroPadre->getSessionValue() <> "") { ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<span<?php echo $sucursales->idTerceroPadre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->idTerceroPadre->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<select data-table="sucursales" data-field="x_idTerceroPadre" data-value-separator="<?php echo $sucursales->idTerceroPadre->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre"<?php echo $sucursales->idTerceroPadre->EditAttributes() ?>>
<?php echo $sucursales->idTerceroPadre->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo $sucursales->idTerceroPadre->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroPadre" class="sucursales_idTerceroPadre">
<span<?php echo $sucursales->idTerceroPadre->ViewAttributes() ?>>
<?php echo $sucursales->idTerceroPadre->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->FormValue) ?>">
<input type="hidden" data-table="sucursales" data-field="x_idTerceroPadre" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $sucursales_grid->PageObjName . "_row_" . $sucursales_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="sucursales" data-field="x_id" name="x<?php echo $sucursales_grid->RowIndex ?>_id" id="x<?php echo $sucursales_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($sucursales->id->CurrentValue) ?>">
<input type="hidden" data-table="sucursales" data-field="x_id" name="o<?php echo $sucursales_grid->RowIndex ?>_id" id="o<?php echo $sucursales_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($sucursales->id->OldValue) ?>">
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_EDIT || $sucursales->CurrentMode == "edit") { ?>
<input type="hidden" data-table="sucursales" data-field="x_id" name="x<?php echo $sucursales_grid->RowIndex ?>_id" id="x<?php echo $sucursales_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($sucursales->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($sucursales->idTerceroSucursal->Visible) { // idTerceroSucursal ?>
		<td data-name="idTerceroSucursal"<?php echo $sucursales->idTerceroSucursal->CellAttributes() ?>>
<?php if ($sucursales->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroSucursal" class="form-group sucursales_idTerceroSucursal">
<select data-table="sucursales" data-field="x_idTerceroSucursal" data-value-separator="<?php echo $sucursales->idTerceroSucursal->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal"<?php echo $sucursales->idTerceroSucursal->EditAttributes() ?>>
<?php echo $sucursales->idTerceroSucursal->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo $sucursales->idTerceroSucursal->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroSucursal" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo ew_HtmlEncode($sucursales->idTerceroSucursal->OldValue) ?>">
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroSucursal" class="form-group sucursales_idTerceroSucursal">
<select data-table="sucursales" data-field="x_idTerceroSucursal" data-value-separator="<?php echo $sucursales->idTerceroSucursal->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal"<?php echo $sucursales->idTerceroSucursal->EditAttributes() ?>>
<?php echo $sucursales->idTerceroSucursal->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo $sucursales->idTerceroSucursal->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_idTerceroSucursal" class="sucursales_idTerceroSucursal">
<span<?php echo $sucursales->idTerceroSucursal->ViewAttributes() ?>>
<?php echo $sucursales->idTerceroSucursal->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroSucursal" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo ew_HtmlEncode($sucursales->idTerceroSucursal->FormValue) ?>">
<input type="hidden" data-table="sucursales" data-field="x_idTerceroSucursal" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo ew_HtmlEncode($sucursales->idTerceroSucursal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sucursales->orden->Visible) { // orden ?>
		<td data-name="orden"<?php echo $sucursales->orden->CellAttributes() ?>>
<?php if ($sucursales->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_orden" class="form-group sucursales_orden">
<input type="text" data-table="sucursales" data-field="x_orden" name="x<?php echo $sucursales_grid->RowIndex ?>_orden" id="x<?php echo $sucursales_grid->RowIndex ?>_orden" size="30" placeholder="<?php echo ew_HtmlEncode($sucursales->orden->getPlaceHolder()) ?>" value="<?php echo $sucursales->orden->EditValue ?>"<?php echo $sucursales->orden->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sucursales" data-field="x_orden" name="o<?php echo $sucursales_grid->RowIndex ?>_orden" id="o<?php echo $sucursales_grid->RowIndex ?>_orden" value="<?php echo ew_HtmlEncode($sucursales->orden->OldValue) ?>">
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_orden" class="form-group sucursales_orden">
<input type="text" data-table="sucursales" data-field="x_orden" name="x<?php echo $sucursales_grid->RowIndex ?>_orden" id="x<?php echo $sucursales_grid->RowIndex ?>_orden" size="30" placeholder="<?php echo ew_HtmlEncode($sucursales->orden->getPlaceHolder()) ?>" value="<?php echo $sucursales->orden->EditValue ?>"<?php echo $sucursales->orden->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($sucursales->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sucursales_grid->RowCnt ?>_sucursales_orden" class="sucursales_orden">
<span<?php echo $sucursales->orden->ViewAttributes() ?>>
<?php echo $sucursales->orden->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_orden" name="x<?php echo $sucursales_grid->RowIndex ?>_orden" id="x<?php echo $sucursales_grid->RowIndex ?>_orden" value="<?php echo ew_HtmlEncode($sucursales->orden->FormValue) ?>">
<input type="hidden" data-table="sucursales" data-field="x_orden" name="o<?php echo $sucursales_grid->RowIndex ?>_orden" id="o<?php echo $sucursales_grid->RowIndex ?>_orden" value="<?php echo ew_HtmlEncode($sucursales->orden->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sucursales_grid->ListOptions->Render("body", "right", $sucursales_grid->RowCnt);
?>
	</tr>
<?php if ($sucursales->RowType == EW_ROWTYPE_ADD || $sucursales->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsucursalesgrid.UpdateOpts(<?php echo $sucursales_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($sucursales->CurrentAction <> "gridadd" || $sucursales->CurrentMode == "copy")
		if (!$sucursales_grid->Recordset->EOF) $sucursales_grid->Recordset->MoveNext();
}
?>
<?php
	if ($sucursales->CurrentMode == "add" || $sucursales->CurrentMode == "copy" || $sucursales->CurrentMode == "edit") {
		$sucursales_grid->RowIndex = '$rowindex$';
		$sucursales_grid->LoadDefaultValues();

		// Set row properties
		$sucursales->ResetAttrs();
		$sucursales->RowAttrs = array_merge($sucursales->RowAttrs, array('data-rowindex'=>$sucursales_grid->RowIndex, 'id'=>'r0_sucursales', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($sucursales->RowAttrs["class"], "ewTemplate");
		$sucursales->RowType = EW_ROWTYPE_ADD;

		// Render row
		$sucursales_grid->RenderRow();

		// Render list options
		$sucursales_grid->RenderListOptions();
		$sucursales_grid->StartRowCnt = 0;
?>
	<tr<?php echo $sucursales->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sucursales_grid->ListOptions->Render("body", "left", $sucursales_grid->RowIndex);
?>
	<?php if ($sucursales->idTerceroPadre->Visible) { // idTerceroPadre ?>
		<td data-name="idTerceroPadre">
<?php if ($sucursales->CurrentAction <> "F") { ?>
<?php if ($sucursales->idTerceroPadre->getSessionValue() <> "") { ?>
<span id="el$rowindex$_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<span<?php echo $sucursales->idTerceroPadre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->idTerceroPadre->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<select data-table="sucursales" data-field="x_idTerceroPadre" data-value-separator="<?php echo $sucursales->idTerceroPadre->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre"<?php echo $sucursales->idTerceroPadre->EditAttributes() ?>>
<?php echo $sucursales->idTerceroPadre->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo $sucursales->idTerceroPadre->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_sucursales_idTerceroPadre" class="form-group sucursales_idTerceroPadre">
<span<?php echo $sucursales->idTerceroPadre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->idTerceroPadre->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroPadre" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroPadre" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroPadre" value="<?php echo ew_HtmlEncode($sucursales->idTerceroPadre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sucursales->idTerceroSucursal->Visible) { // idTerceroSucursal ?>
		<td data-name="idTerceroSucursal">
<?php if ($sucursales->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sucursales_idTerceroSucursal" class="form-group sucursales_idTerceroSucursal">
<select data-table="sucursales" data-field="x_idTerceroSucursal" data-value-separator="<?php echo $sucursales->idTerceroSucursal->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal"<?php echo $sucursales->idTerceroSucursal->EditAttributes() ?>>
<?php echo $sucursales->idTerceroSucursal->SelectOptionListHtml("x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal") ?>
</select>
<input type="hidden" name="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="s_x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo $sucursales->idTerceroSucursal->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_sucursales_idTerceroSucursal" class="form-group sucursales_idTerceroSucursal">
<span<?php echo $sucursales->idTerceroSucursal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->idTerceroSucursal->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroSucursal" name="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="x<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo ew_HtmlEncode($sucursales->idTerceroSucursal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sucursales" data-field="x_idTerceroSucursal" name="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" id="o<?php echo $sucursales_grid->RowIndex ?>_idTerceroSucursal" value="<?php echo ew_HtmlEncode($sucursales->idTerceroSucursal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sucursales->orden->Visible) { // orden ?>
		<td data-name="orden">
<?php if ($sucursales->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sucursales_orden" class="form-group sucursales_orden">
<input type="text" data-table="sucursales" data-field="x_orden" name="x<?php echo $sucursales_grid->RowIndex ?>_orden" id="x<?php echo $sucursales_grid->RowIndex ?>_orden" size="30" placeholder="<?php echo ew_HtmlEncode($sucursales->orden->getPlaceHolder()) ?>" value="<?php echo $sucursales->orden->EditValue ?>"<?php echo $sucursales->orden->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_sucursales_orden" class="form-group sucursales_orden">
<span<?php echo $sucursales->orden->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sucursales->orden->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sucursales" data-field="x_orden" name="x<?php echo $sucursales_grid->RowIndex ?>_orden" id="x<?php echo $sucursales_grid->RowIndex ?>_orden" value="<?php echo ew_HtmlEncode($sucursales->orden->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sucursales" data-field="x_orden" name="o<?php echo $sucursales_grid->RowIndex ?>_orden" id="o<?php echo $sucursales_grid->RowIndex ?>_orden" value="<?php echo ew_HtmlEncode($sucursales->orden->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sucursales_grid->ListOptions->Render("body", "right", $sucursales_grid->RowCnt);
?>
<script type="text/javascript">
fsucursalesgrid.UpdateOpts(<?php echo $sucursales_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($sucursales->CurrentMode == "add" || $sucursales->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $sucursales_grid->FormKeyCountName ?>" id="<?php echo $sucursales_grid->FormKeyCountName ?>" value="<?php echo $sucursales_grid->KeyCount ?>">
<?php echo $sucursales_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sucursales->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $sucursales_grid->FormKeyCountName ?>" id="<?php echo $sucursales_grid->FormKeyCountName ?>" value="<?php echo $sucursales_grid->KeyCount ?>">
<?php echo $sucursales_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sucursales->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsucursalesgrid">
</div>
<?php

// Close recordset
if ($sucursales_grid->Recordset)
	$sucursales_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($sucursales_grid->TotalRecs == 0 && $sucursales->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sucursales_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($sucursales->Export == "") { ?>
<script type="text/javascript">
fsucursalesgrid.Init();
</script>
<?php } ?>
<?php
$sucursales_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$sucursales_grid->Page_Terminate();
?>
