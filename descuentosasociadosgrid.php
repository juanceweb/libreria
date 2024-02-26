<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($descuentosasociados_grid)) $descuentosasociados_grid = new cdescuentosasociados_grid();

// Page init
$descuentosasociados_grid->Page_Init();

// Page main
$descuentosasociados_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$descuentosasociados_grid->Page_Render();
?>
<?php if ($descuentosasociados->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdescuentosasociadosgrid = new ew_Form("fdescuentosasociadosgrid", "grid");
fdescuentosasociadosgrid.FormKeyCountName = '<?php echo $descuentosasociados_grid->FormKeyCountName ?>';

// Validate form
fdescuentosasociadosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idTercero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $descuentosasociados->idTercero->FldCaption(), $descuentosasociados->idTercero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idTerceroBase");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $descuentosasociados->idTerceroBase->FldCaption(), $descuentosasociados->idTerceroBase->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdescuentosasociadosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTerceroBase", false)) return false;
	return true;
}

// Form_CustomValidate event
fdescuentosasociadosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdescuentosasociadosgrid.ValidateRequired = true;
<?php } else { ?>
fdescuentosasociadosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdescuentosasociadosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fdescuentosasociadosgrid.Lists["x_idTerceroBase"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($descuentosasociados->CurrentAction == "gridadd") {
	if ($descuentosasociados->CurrentMode == "copy") {
		$bSelectLimit = $descuentosasociados_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$descuentosasociados_grid->TotalRecs = $descuentosasociados->SelectRecordCount();
			$descuentosasociados_grid->Recordset = $descuentosasociados_grid->LoadRecordset($descuentosasociados_grid->StartRec-1, $descuentosasociados_grid->DisplayRecs);
		} else {
			if ($descuentosasociados_grid->Recordset = $descuentosasociados_grid->LoadRecordset())
				$descuentosasociados_grid->TotalRecs = $descuentosasociados_grid->Recordset->RecordCount();
		}
		$descuentosasociados_grid->StartRec = 1;
		$descuentosasociados_grid->DisplayRecs = $descuentosasociados_grid->TotalRecs;
	} else {
		$descuentosasociados->CurrentFilter = "0=1";
		$descuentosasociados_grid->StartRec = 1;
		$descuentosasociados_grid->DisplayRecs = $descuentosasociados->GridAddRowCount;
	}
	$descuentosasociados_grid->TotalRecs = $descuentosasociados_grid->DisplayRecs;
	$descuentosasociados_grid->StopRec = $descuentosasociados_grid->DisplayRecs;
} else {
	$bSelectLimit = $descuentosasociados_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($descuentosasociados_grid->TotalRecs <= 0)
			$descuentosasociados_grid->TotalRecs = $descuentosasociados->SelectRecordCount();
	} else {
		if (!$descuentosasociados_grid->Recordset && ($descuentosasociados_grid->Recordset = $descuentosasociados_grid->LoadRecordset()))
			$descuentosasociados_grid->TotalRecs = $descuentosasociados_grid->Recordset->RecordCount();
	}
	$descuentosasociados_grid->StartRec = 1;
	$descuentosasociados_grid->DisplayRecs = $descuentosasociados_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$descuentosasociados_grid->Recordset = $descuentosasociados_grid->LoadRecordset($descuentosasociados_grid->StartRec-1, $descuentosasociados_grid->DisplayRecs);

	// Set no record found message
	if ($descuentosasociados->CurrentAction == "" && $descuentosasociados_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$descuentosasociados_grid->setWarningMessage(ew_DeniedMsg());
		if ($descuentosasociados_grid->SearchWhere == "0=101")
			$descuentosasociados_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$descuentosasociados_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$descuentosasociados_grid->RenderOtherOptions();
?>
<?php $descuentosasociados_grid->ShowPageHeader(); ?>
<?php
$descuentosasociados_grid->ShowMessage();
?>
<?php if ($descuentosasociados_grid->TotalRecs > 0 || $descuentosasociados->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid descuentosasociados">
<div id="fdescuentosasociadosgrid" class="ewForm form-inline">
<?php if ($descuentosasociados_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($descuentosasociados_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_descuentosasociados" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_descuentosasociadosgrid" class="table ewTable">
<?php echo $descuentosasociados->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$descuentosasociados_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$descuentosasociados_grid->RenderListOptions();

// Render list options (header, left)
$descuentosasociados_grid->ListOptions->Render("header", "left");
?>
<?php if ($descuentosasociados->idTercero->Visible) { // idTercero ?>
	<?php if ($descuentosasociados->SortUrl($descuentosasociados->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_descuentosasociados_idTercero" class="descuentosasociados_idTercero"><div class="ewTableHeaderCaption"><?php echo $descuentosasociados->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_descuentosasociados_idTercero" class="descuentosasociados_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $descuentosasociados->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($descuentosasociados->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($descuentosasociados->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($descuentosasociados->idTerceroBase->Visible) { // idTerceroBase ?>
	<?php if ($descuentosasociados->SortUrl($descuentosasociados->idTerceroBase) == "") { ?>
		<th data-name="idTerceroBase"><div id="elh_descuentosasociados_idTerceroBase" class="descuentosasociados_idTerceroBase"><div class="ewTableHeaderCaption"><?php echo $descuentosasociados->idTerceroBase->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTerceroBase"><div><div id="elh_descuentosasociados_idTerceroBase" class="descuentosasociados_idTerceroBase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $descuentosasociados->idTerceroBase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($descuentosasociados->idTerceroBase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($descuentosasociados->idTerceroBase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$descuentosasociados_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$descuentosasociados_grid->StartRec = 1;
$descuentosasociados_grid->StopRec = $descuentosasociados_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($descuentosasociados_grid->FormKeyCountName) && ($descuentosasociados->CurrentAction == "gridadd" || $descuentosasociados->CurrentAction == "gridedit" || $descuentosasociados->CurrentAction == "F")) {
		$descuentosasociados_grid->KeyCount = $objForm->GetValue($descuentosasociados_grid->FormKeyCountName);
		$descuentosasociados_grid->StopRec = $descuentosasociados_grid->StartRec + $descuentosasociados_grid->KeyCount - 1;
	}
}
$descuentosasociados_grid->RecCnt = $descuentosasociados_grid->StartRec - 1;
if ($descuentosasociados_grid->Recordset && !$descuentosasociados_grid->Recordset->EOF) {
	$descuentosasociados_grid->Recordset->MoveFirst();
	$bSelectLimit = $descuentosasociados_grid->UseSelectLimit;
	if (!$bSelectLimit && $descuentosasociados_grid->StartRec > 1)
		$descuentosasociados_grid->Recordset->Move($descuentosasociados_grid->StartRec - 1);
} elseif (!$descuentosasociados->AllowAddDeleteRow && $descuentosasociados_grid->StopRec == 0) {
	$descuentosasociados_grid->StopRec = $descuentosasociados->GridAddRowCount;
}

// Initialize aggregate
$descuentosasociados->RowType = EW_ROWTYPE_AGGREGATEINIT;
$descuentosasociados->ResetAttrs();
$descuentosasociados_grid->RenderRow();
if ($descuentosasociados->CurrentAction == "gridadd")
	$descuentosasociados_grid->RowIndex = 0;
if ($descuentosasociados->CurrentAction == "gridedit")
	$descuentosasociados_grid->RowIndex = 0;
while ($descuentosasociados_grid->RecCnt < $descuentosasociados_grid->StopRec) {
	$descuentosasociados_grid->RecCnt++;
	if (intval($descuentosasociados_grid->RecCnt) >= intval($descuentosasociados_grid->StartRec)) {
		$descuentosasociados_grid->RowCnt++;
		if ($descuentosasociados->CurrentAction == "gridadd" || $descuentosasociados->CurrentAction == "gridedit" || $descuentosasociados->CurrentAction == "F") {
			$descuentosasociados_grid->RowIndex++;
			$objForm->Index = $descuentosasociados_grid->RowIndex;
			if ($objForm->HasValue($descuentosasociados_grid->FormActionName))
				$descuentosasociados_grid->RowAction = strval($objForm->GetValue($descuentosasociados_grid->FormActionName));
			elseif ($descuentosasociados->CurrentAction == "gridadd")
				$descuentosasociados_grid->RowAction = "insert";
			else
				$descuentosasociados_grid->RowAction = "";
		}

		// Set up key count
		$descuentosasociados_grid->KeyCount = $descuentosasociados_grid->RowIndex;

		// Init row class and style
		$descuentosasociados->ResetAttrs();
		$descuentosasociados->CssClass = "";
		if ($descuentosasociados->CurrentAction == "gridadd") {
			if ($descuentosasociados->CurrentMode == "copy") {
				$descuentosasociados_grid->LoadRowValues($descuentosasociados_grid->Recordset); // Load row values
				$descuentosasociados_grid->SetRecordKey($descuentosasociados_grid->RowOldKey, $descuentosasociados_grid->Recordset); // Set old record key
			} else {
				$descuentosasociados_grid->LoadDefaultValues(); // Load default values
				$descuentosasociados_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$descuentosasociados_grid->LoadRowValues($descuentosasociados_grid->Recordset); // Load row values
		}
		$descuentosasociados->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($descuentosasociados->CurrentAction == "gridadd") // Grid add
			$descuentosasociados->RowType = EW_ROWTYPE_ADD; // Render add
		if ($descuentosasociados->CurrentAction == "gridadd" && $descuentosasociados->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$descuentosasociados_grid->RestoreCurrentRowFormValues($descuentosasociados_grid->RowIndex); // Restore form values
		if ($descuentosasociados->CurrentAction == "gridedit") { // Grid edit
			if ($descuentosasociados->EventCancelled) {
				$descuentosasociados_grid->RestoreCurrentRowFormValues($descuentosasociados_grid->RowIndex); // Restore form values
			}
			if ($descuentosasociados_grid->RowAction == "insert")
				$descuentosasociados->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$descuentosasociados->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($descuentosasociados->CurrentAction == "gridedit" && ($descuentosasociados->RowType == EW_ROWTYPE_EDIT || $descuentosasociados->RowType == EW_ROWTYPE_ADD) && $descuentosasociados->EventCancelled) // Update failed
			$descuentosasociados_grid->RestoreCurrentRowFormValues($descuentosasociados_grid->RowIndex); // Restore form values
		if ($descuentosasociados->RowType == EW_ROWTYPE_EDIT) // Edit row
			$descuentosasociados_grid->EditRowCnt++;
		if ($descuentosasociados->CurrentAction == "F") // Confirm row
			$descuentosasociados_grid->RestoreCurrentRowFormValues($descuentosasociados_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$descuentosasociados->RowAttrs = array_merge($descuentosasociados->RowAttrs, array('data-rowindex'=>$descuentosasociados_grid->RowCnt, 'id'=>'r' . $descuentosasociados_grid->RowCnt . '_descuentosasociados', 'data-rowtype'=>$descuentosasociados->RowType));

		// Render row
		$descuentosasociados_grid->RenderRow();

		// Render list options
		$descuentosasociados_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($descuentosasociados_grid->RowAction <> "delete" && $descuentosasociados_grid->RowAction <> "insertdelete" && !($descuentosasociados_grid->RowAction == "insert" && $descuentosasociados->CurrentAction == "F" && $descuentosasociados_grid->EmptyRow())) {
?>
	<tr<?php echo $descuentosasociados->RowAttributes() ?>>
<?php

// Render list options (body, left)
$descuentosasociados_grid->ListOptions->Render("body", "left", $descuentosasociados_grid->RowCnt);
?>
	<?php if ($descuentosasociados->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $descuentosasociados->idTercero->CellAttributes() ?>>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($descuentosasociados->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<span<?php echo $descuentosasociados->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $descuentosasociados->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<select data-table="descuentosasociados" data-field="x_idTercero" data-value-separator="<?php echo $descuentosasociados->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero"<?php echo $descuentosasociados->idTercero->EditAttributes() ?>>
<?php echo $descuentosasociados->idTercero->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo $descuentosasociados->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTercero" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($descuentosasociados->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<span<?php echo $descuentosasociados->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $descuentosasociados->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<select data-table="descuentosasociados" data-field="x_idTercero" data-value-separator="<?php echo $descuentosasociados->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero"<?php echo $descuentosasociados->idTercero->EditAttributes() ?>>
<?php echo $descuentosasociados->idTercero->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo $descuentosasociados->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTercero" class="descuentosasociados_idTercero">
<span<?php echo $descuentosasociados->idTercero->ViewAttributes() ?>>
<?php echo $descuentosasociados->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->FormValue) ?>">
<input type="hidden" data-table="descuentosasociados" data-field="x_idTercero" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->OldValue) ?>">
<?php } ?>
<a id="<?php echo $descuentosasociados_grid->PageObjName . "_row_" . $descuentosasociados_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="descuentosasociados" data-field="x_id" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_id" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($descuentosasociados->id->CurrentValue) ?>">
<input type="hidden" data-table="descuentosasociados" data-field="x_id" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_id" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($descuentosasociados->id->OldValue) ?>">
<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_EDIT || $descuentosasociados->CurrentMode == "edit") { ?>
<input type="hidden" data-table="descuentosasociados" data-field="x_id" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_id" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($descuentosasociados->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($descuentosasociados->idTerceroBase->Visible) { // idTerceroBase ?>
		<td data-name="idTerceroBase"<?php echo $descuentosasociados->idTerceroBase->CellAttributes() ?>>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTerceroBase" class="form-group descuentosasociados_idTerceroBase">
<select data-table="descuentosasociados" data-field="x_idTerceroBase" data-value-separator="<?php echo $descuentosasociados->idTerceroBase->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase"<?php echo $descuentosasociados->idTerceroBase->EditAttributes() ?>>
<?php echo $descuentosasociados->idTerceroBase->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo $descuentosasociados->idTerceroBase->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTerceroBase" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo ew_HtmlEncode($descuentosasociados->idTerceroBase->OldValue) ?>">
<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTerceroBase" class="form-group descuentosasociados_idTerceroBase">
<select data-table="descuentosasociados" data-field="x_idTerceroBase" data-value-separator="<?php echo $descuentosasociados->idTerceroBase->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase"<?php echo $descuentosasociados->idTerceroBase->EditAttributes() ?>>
<?php echo $descuentosasociados->idTerceroBase->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo $descuentosasociados->idTerceroBase->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $descuentosasociados_grid->RowCnt ?>_descuentosasociados_idTerceroBase" class="descuentosasociados_idTerceroBase">
<span<?php echo $descuentosasociados->idTerceroBase->ViewAttributes() ?>>
<?php echo $descuentosasociados->idTerceroBase->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTerceroBase" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo ew_HtmlEncode($descuentosasociados->idTerceroBase->FormValue) ?>">
<input type="hidden" data-table="descuentosasociados" data-field="x_idTerceroBase" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo ew_HtmlEncode($descuentosasociados->idTerceroBase->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$descuentosasociados_grid->ListOptions->Render("body", "right", $descuentosasociados_grid->RowCnt);
?>
	</tr>
<?php if ($descuentosasociados->RowType == EW_ROWTYPE_ADD || $descuentosasociados->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdescuentosasociadosgrid.UpdateOpts(<?php echo $descuentosasociados_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($descuentosasociados->CurrentAction <> "gridadd" || $descuentosasociados->CurrentMode == "copy")
		if (!$descuentosasociados_grid->Recordset->EOF) $descuentosasociados_grid->Recordset->MoveNext();
}
?>
<?php
	if ($descuentosasociados->CurrentMode == "add" || $descuentosasociados->CurrentMode == "copy" || $descuentosasociados->CurrentMode == "edit") {
		$descuentosasociados_grid->RowIndex = '$rowindex$';
		$descuentosasociados_grid->LoadDefaultValues();

		// Set row properties
		$descuentosasociados->ResetAttrs();
		$descuentosasociados->RowAttrs = array_merge($descuentosasociados->RowAttrs, array('data-rowindex'=>$descuentosasociados_grid->RowIndex, 'id'=>'r0_descuentosasociados', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($descuentosasociados->RowAttrs["class"], "ewTemplate");
		$descuentosasociados->RowType = EW_ROWTYPE_ADD;

		// Render row
		$descuentosasociados_grid->RenderRow();

		// Render list options
		$descuentosasociados_grid->RenderListOptions();
		$descuentosasociados_grid->StartRowCnt = 0;
?>
	<tr<?php echo $descuentosasociados->RowAttributes() ?>>
<?php

// Render list options (body, left)
$descuentosasociados_grid->ListOptions->Render("body", "left", $descuentosasociados_grid->RowIndex);
?>
	<?php if ($descuentosasociados->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($descuentosasociados->CurrentAction <> "F") { ?>
<?php if ($descuentosasociados->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<span<?php echo $descuentosasociados->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $descuentosasociados->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<select data-table="descuentosasociados" data-field="x_idTercero" data-value-separator="<?php echo $descuentosasociados->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero"<?php echo $descuentosasociados->idTercero->EditAttributes() ?>>
<?php echo $descuentosasociados->idTercero->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo $descuentosasociados->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_descuentosasociados_idTercero" class="form-group descuentosasociados_idTercero">
<span<?php echo $descuentosasociados->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $descuentosasociados->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTercero" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTercero" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($descuentosasociados->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($descuentosasociados->idTerceroBase->Visible) { // idTerceroBase ?>
		<td data-name="idTerceroBase">
<?php if ($descuentosasociados->CurrentAction <> "F") { ?>
<span id="el$rowindex$_descuentosasociados_idTerceroBase" class="form-group descuentosasociados_idTerceroBase">
<select data-table="descuentosasociados" data-field="x_idTerceroBase" data-value-separator="<?php echo $descuentosasociados->idTerceroBase->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase"<?php echo $descuentosasociados->idTerceroBase->EditAttributes() ?>>
<?php echo $descuentosasociados->idTerceroBase->SelectOptionListHtml("x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase") ?>
</select>
<input type="hidden" name="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="s_x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo $descuentosasociados->idTerceroBase->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_descuentosasociados_idTerceroBase" class="form-group descuentosasociados_idTerceroBase">
<span<?php echo $descuentosasociados->idTerceroBase->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $descuentosasociados->idTerceroBase->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTerceroBase" name="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="x<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo ew_HtmlEncode($descuentosasociados->idTerceroBase->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="descuentosasociados" data-field="x_idTerceroBase" name="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" id="o<?php echo $descuentosasociados_grid->RowIndex ?>_idTerceroBase" value="<?php echo ew_HtmlEncode($descuentosasociados->idTerceroBase->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$descuentosasociados_grid->ListOptions->Render("body", "right", $descuentosasociados_grid->RowCnt);
?>
<script type="text/javascript">
fdescuentosasociadosgrid.UpdateOpts(<?php echo $descuentosasociados_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($descuentosasociados->CurrentMode == "add" || $descuentosasociados->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $descuentosasociados_grid->FormKeyCountName ?>" id="<?php echo $descuentosasociados_grid->FormKeyCountName ?>" value="<?php echo $descuentosasociados_grid->KeyCount ?>">
<?php echo $descuentosasociados_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($descuentosasociados->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $descuentosasociados_grid->FormKeyCountName ?>" id="<?php echo $descuentosasociados_grid->FormKeyCountName ?>" value="<?php echo $descuentosasociados_grid->KeyCount ?>">
<?php echo $descuentosasociados_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($descuentosasociados->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdescuentosasociadosgrid">
</div>
<?php

// Close recordset
if ($descuentosasociados_grid->Recordset)
	$descuentosasociados_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($descuentosasociados_grid->TotalRecs == 0 && $descuentosasociados->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($descuentosasociados_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($descuentosasociados->Export == "") { ?>
<script type="text/javascript">
fdescuentosasociadosgrid.Init();
</script>
<?php } ?>
<?php
$descuentosasociados_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$descuentosasociados_grid->Page_Terminate();
?>
