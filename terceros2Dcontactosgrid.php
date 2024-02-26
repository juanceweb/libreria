<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($terceros2Dcontactos_grid)) $terceros2Dcontactos_grid = new cterceros2Dcontactos_grid();

// Page init
$terceros2Dcontactos_grid->Page_Init();

// Page main
$terceros2Dcontactos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros2Dcontactos_grid->Page_Render();
?>
<?php if ($terceros2Dcontactos->Export == "") { ?>
<script type="text/javascript">

// Form object
var fterceros2Dcontactosgrid = new ew_Form("fterceros2Dcontactosgrid", "grid");
fterceros2Dcontactosgrid.FormKeyCountName = '<?php echo $terceros2Dcontactos_grid->FormKeyCountName ?>';

// Validate form
fterceros2Dcontactosgrid.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fterceros2Dcontactosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idPersona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "principal[]", false)) return false;
	return true;
}

// Form_CustomValidate event
fterceros2Dcontactosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dcontactosgrid.ValidateRequired = true;
<?php } else { ?>
fterceros2Dcontactosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fterceros2Dcontactosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fterceros2Dcontactosgrid.Lists["x_idPersona"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fterceros2Dcontactosgrid.Lists["x_principal[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fterceros2Dcontactosgrid.Lists["x_principal[]"].Options = <?php echo json_encode($terceros2Dcontactos->principal->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($terceros2Dcontactos->CurrentAction == "gridadd") {
	if ($terceros2Dcontactos->CurrentMode == "copy") {
		$bSelectLimit = $terceros2Dcontactos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$terceros2Dcontactos_grid->TotalRecs = $terceros2Dcontactos->SelectRecordCount();
			$terceros2Dcontactos_grid->Recordset = $terceros2Dcontactos_grid->LoadRecordset($terceros2Dcontactos_grid->StartRec-1, $terceros2Dcontactos_grid->DisplayRecs);
		} else {
			if ($terceros2Dcontactos_grid->Recordset = $terceros2Dcontactos_grid->LoadRecordset())
				$terceros2Dcontactos_grid->TotalRecs = $terceros2Dcontactos_grid->Recordset->RecordCount();
		}
		$terceros2Dcontactos_grid->StartRec = 1;
		$terceros2Dcontactos_grid->DisplayRecs = $terceros2Dcontactos_grid->TotalRecs;
	} else {
		$terceros2Dcontactos->CurrentFilter = "0=1";
		$terceros2Dcontactos_grid->StartRec = 1;
		$terceros2Dcontactos_grid->DisplayRecs = $terceros2Dcontactos->GridAddRowCount;
	}
	$terceros2Dcontactos_grid->TotalRecs = $terceros2Dcontactos_grid->DisplayRecs;
	$terceros2Dcontactos_grid->StopRec = $terceros2Dcontactos_grid->DisplayRecs;
} else {
	$bSelectLimit = $terceros2Dcontactos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($terceros2Dcontactos_grid->TotalRecs <= 0)
			$terceros2Dcontactos_grid->TotalRecs = $terceros2Dcontactos->SelectRecordCount();
	} else {
		if (!$terceros2Dcontactos_grid->Recordset && ($terceros2Dcontactos_grid->Recordset = $terceros2Dcontactos_grid->LoadRecordset()))
			$terceros2Dcontactos_grid->TotalRecs = $terceros2Dcontactos_grid->Recordset->RecordCount();
	}
	$terceros2Dcontactos_grid->StartRec = 1;
	$terceros2Dcontactos_grid->DisplayRecs = $terceros2Dcontactos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$terceros2Dcontactos_grid->Recordset = $terceros2Dcontactos_grid->LoadRecordset($terceros2Dcontactos_grid->StartRec-1, $terceros2Dcontactos_grid->DisplayRecs);

	// Set no record found message
	if ($terceros2Dcontactos->CurrentAction == "" && $terceros2Dcontactos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$terceros2Dcontactos_grid->setWarningMessage(ew_DeniedMsg());
		if ($terceros2Dcontactos_grid->SearchWhere == "0=101")
			$terceros2Dcontactos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$terceros2Dcontactos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$terceros2Dcontactos_grid->RenderOtherOptions();
?>
<?php $terceros2Dcontactos_grid->ShowPageHeader(); ?>
<?php
$terceros2Dcontactos_grid->ShowMessage();
?>
<?php if ($terceros2Dcontactos_grid->TotalRecs > 0 || $terceros2Dcontactos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid terceros2Dcontactos">
<div id="fterceros2Dcontactosgrid" class="ewForm form-inline">
<?php if ($terceros2Dcontactos_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($terceros2Dcontactos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_terceros2Dcontactos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_terceros2Dcontactosgrid" class="table ewTable">
<?php echo $terceros2Dcontactos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$terceros2Dcontactos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$terceros2Dcontactos_grid->RenderListOptions();

// Render list options (header, left)
$terceros2Dcontactos_grid->ListOptions->Render("header", "left");
?>
<?php if ($terceros2Dcontactos->idTercero->Visible) { // idTercero ?>
	<?php if ($terceros2Dcontactos->SortUrl($terceros2Dcontactos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_terceros2Dcontactos_idTercero" class="terceros2Dcontactos_idTercero"><div class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_terceros2Dcontactos_idTercero" class="terceros2Dcontactos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dcontactos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dcontactos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dcontactos->idPersona->Visible) { // idPersona ?>
	<?php if ($terceros2Dcontactos->SortUrl($terceros2Dcontactos->idPersona) == "") { ?>
		<th data-name="idPersona"><div id="elh_terceros2Dcontactos_idPersona" class="terceros2Dcontactos_idPersona"><div class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->idPersona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idPersona"><div><div id="elh_terceros2Dcontactos_idPersona" class="terceros2Dcontactos_idPersona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->idPersona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dcontactos->idPersona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dcontactos->idPersona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dcontactos->principal->Visible) { // principal ?>
	<?php if ($terceros2Dcontactos->SortUrl($terceros2Dcontactos->principal) == "") { ?>
		<th data-name="principal"><div id="elh_terceros2Dcontactos_principal" class="terceros2Dcontactos_principal"><div class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->principal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="principal"><div><div id="elh_terceros2Dcontactos_principal" class="terceros2Dcontactos_principal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dcontactos->principal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dcontactos->principal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dcontactos->principal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$terceros2Dcontactos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$terceros2Dcontactos_grid->StartRec = 1;
$terceros2Dcontactos_grid->StopRec = $terceros2Dcontactos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($terceros2Dcontactos_grid->FormKeyCountName) && ($terceros2Dcontactos->CurrentAction == "gridadd" || $terceros2Dcontactos->CurrentAction == "gridedit" || $terceros2Dcontactos->CurrentAction == "F")) {
		$terceros2Dcontactos_grid->KeyCount = $objForm->GetValue($terceros2Dcontactos_grid->FormKeyCountName);
		$terceros2Dcontactos_grid->StopRec = $terceros2Dcontactos_grid->StartRec + $terceros2Dcontactos_grid->KeyCount - 1;
	}
}
$terceros2Dcontactos_grid->RecCnt = $terceros2Dcontactos_grid->StartRec - 1;
if ($terceros2Dcontactos_grid->Recordset && !$terceros2Dcontactos_grid->Recordset->EOF) {
	$terceros2Dcontactos_grid->Recordset->MoveFirst();
	$bSelectLimit = $terceros2Dcontactos_grid->UseSelectLimit;
	if (!$bSelectLimit && $terceros2Dcontactos_grid->StartRec > 1)
		$terceros2Dcontactos_grid->Recordset->Move($terceros2Dcontactos_grid->StartRec - 1);
} elseif (!$terceros2Dcontactos->AllowAddDeleteRow && $terceros2Dcontactos_grid->StopRec == 0) {
	$terceros2Dcontactos_grid->StopRec = $terceros2Dcontactos->GridAddRowCount;
}

// Initialize aggregate
$terceros2Dcontactos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$terceros2Dcontactos->ResetAttrs();
$terceros2Dcontactos_grid->RenderRow();
if ($terceros2Dcontactos->CurrentAction == "gridadd")
	$terceros2Dcontactos_grid->RowIndex = 0;
if ($terceros2Dcontactos->CurrentAction == "gridedit")
	$terceros2Dcontactos_grid->RowIndex = 0;
while ($terceros2Dcontactos_grid->RecCnt < $terceros2Dcontactos_grid->StopRec) {
	$terceros2Dcontactos_grid->RecCnt++;
	if (intval($terceros2Dcontactos_grid->RecCnt) >= intval($terceros2Dcontactos_grid->StartRec)) {
		$terceros2Dcontactos_grid->RowCnt++;
		if ($terceros2Dcontactos->CurrentAction == "gridadd" || $terceros2Dcontactos->CurrentAction == "gridedit" || $terceros2Dcontactos->CurrentAction == "F") {
			$terceros2Dcontactos_grid->RowIndex++;
			$objForm->Index = $terceros2Dcontactos_grid->RowIndex;
			if ($objForm->HasValue($terceros2Dcontactos_grid->FormActionName))
				$terceros2Dcontactos_grid->RowAction = strval($objForm->GetValue($terceros2Dcontactos_grid->FormActionName));
			elseif ($terceros2Dcontactos->CurrentAction == "gridadd")
				$terceros2Dcontactos_grid->RowAction = "insert";
			else
				$terceros2Dcontactos_grid->RowAction = "";
		}

		// Set up key count
		$terceros2Dcontactos_grid->KeyCount = $terceros2Dcontactos_grid->RowIndex;

		// Init row class and style
		$terceros2Dcontactos->ResetAttrs();
		$terceros2Dcontactos->CssClass = "";
		if ($terceros2Dcontactos->CurrentAction == "gridadd") {
			if ($terceros2Dcontactos->CurrentMode == "copy") {
				$terceros2Dcontactos_grid->LoadRowValues($terceros2Dcontactos_grid->Recordset); // Load row values
				$terceros2Dcontactos_grid->SetRecordKey($terceros2Dcontactos_grid->RowOldKey, $terceros2Dcontactos_grid->Recordset); // Set old record key
			} else {
				$terceros2Dcontactos_grid->LoadDefaultValues(); // Load default values
				$terceros2Dcontactos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$terceros2Dcontactos_grid->LoadRowValues($terceros2Dcontactos_grid->Recordset); // Load row values
		}
		$terceros2Dcontactos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($terceros2Dcontactos->CurrentAction == "gridadd") // Grid add
			$terceros2Dcontactos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($terceros2Dcontactos->CurrentAction == "gridadd" && $terceros2Dcontactos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$terceros2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dcontactos_grid->RowIndex); // Restore form values
		if ($terceros2Dcontactos->CurrentAction == "gridedit") { // Grid edit
			if ($terceros2Dcontactos->EventCancelled) {
				$terceros2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dcontactos_grid->RowIndex); // Restore form values
			}
			if ($terceros2Dcontactos_grid->RowAction == "insert")
				$terceros2Dcontactos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$terceros2Dcontactos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($terceros2Dcontactos->CurrentAction == "gridedit" && ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT || $terceros2Dcontactos->RowType == EW_ROWTYPE_ADD) && $terceros2Dcontactos->EventCancelled) // Update failed
			$terceros2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dcontactos_grid->RowIndex); // Restore form values
		if ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$terceros2Dcontactos_grid->EditRowCnt++;
		if ($terceros2Dcontactos->CurrentAction == "F") // Confirm row
			$terceros2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dcontactos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$terceros2Dcontactos->RowAttrs = array_merge($terceros2Dcontactos->RowAttrs, array('data-rowindex'=>$terceros2Dcontactos_grid->RowCnt, 'id'=>'r' . $terceros2Dcontactos_grid->RowCnt . '_terceros2Dcontactos', 'data-rowtype'=>$terceros2Dcontactos->RowType));

		// Render row
		$terceros2Dcontactos_grid->RenderRow();

		// Render list options
		$terceros2Dcontactos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($terceros2Dcontactos_grid->RowAction <> "delete" && $terceros2Dcontactos_grid->RowAction <> "insertdelete" && !($terceros2Dcontactos_grid->RowAction == "insert" && $terceros2Dcontactos->CurrentAction == "F" && $terceros2Dcontactos_grid->EmptyRow())) {
?>
	<tr<?php echo $terceros2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dcontactos_grid->ListOptions->Render("body", "left", $terceros2Dcontactos_grid->RowCnt);
?>
	<?php if ($terceros2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $terceros2Dcontactos->idTercero->CellAttributes() ?>>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($terceros2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<span<?php echo $terceros2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<select data-table="terceros2Dcontactos" data-field="x_idTercero" data-value-separator="<?php echo $terceros2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero"<?php echo $terceros2Dcontactos->idTercero->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idTercero->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($terceros2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<span<?php echo $terceros2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<select data-table="terceros2Dcontactos" data-field="x_idTercero" data-value-separator="<?php echo $terceros2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero"<?php echo $terceros2Dcontactos->idTercero->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idTercero->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idTercero" class="terceros2Dcontactos_idTercero">
<span<?php echo $terceros2Dcontactos->idTercero->ViewAttributes() ?>>
<?php echo $terceros2Dcontactos->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->FormValue) ?>">
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->OldValue) ?>">
<?php } ?>
<a id="<?php echo $terceros2Dcontactos_grid->PageObjName . "_row_" . $terceros2Dcontactos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_id" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->id->CurrentValue) ?>">
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_id" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->id->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT || $terceros2Dcontactos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_id" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($terceros2Dcontactos->idPersona->Visible) { // idPersona ?>
		<td data-name="idPersona"<?php echo $terceros2Dcontactos->idPersona->CellAttributes() ?>>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idPersona" class="form-group terceros2Dcontactos_idPersona">
<select data-table="terceros2Dcontactos" data-field="x_idPersona" data-value-separator="<?php echo $terceros2Dcontactos->idPersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona"<?php echo $terceros2Dcontactos->idPersona->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idPersona->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo $terceros2Dcontactos->idPersona->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idPersona" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idPersona->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idPersona" class="form-group terceros2Dcontactos_idPersona">
<select data-table="terceros2Dcontactos" data-field="x_idPersona" data-value-separator="<?php echo $terceros2Dcontactos->idPersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona"<?php echo $terceros2Dcontactos->idPersona->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idPersona->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo $terceros2Dcontactos->idPersona->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_idPersona" class="terceros2Dcontactos_idPersona">
<span<?php echo $terceros2Dcontactos->idPersona->ViewAttributes() ?>>
<?php echo $terceros2Dcontactos->idPersona->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idPersona" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idPersona->FormValue) ?>">
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idPersona" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idPersona->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($terceros2Dcontactos->principal->Visible) { // principal ?>
		<td data-name="principal"<?php echo $terceros2Dcontactos->principal->CellAttributes() ?>>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_principal" class="form-group terceros2Dcontactos_principal">
<div id="tp_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" class="ewTemplate"><input type="checkbox" data-table="terceros2Dcontactos" data-field="x_principal" data-value-separator="<?php echo $terceros2Dcontactos->principal->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="{value}"<?php echo $terceros2Dcontactos->principal->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $terceros2Dcontactos->principal->CheckBoxListHtml(FALSE, "x{$terceros2Dcontactos_grid->RowIndex}_principal[]") ?>
</div></div>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_principal" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->principal->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_principal" class="form-group terceros2Dcontactos_principal">
<div id="tp_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" class="ewTemplate"><input type="checkbox" data-table="terceros2Dcontactos" data-field="x_principal" data-value-separator="<?php echo $terceros2Dcontactos->principal->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="{value}"<?php echo $terceros2Dcontactos->principal->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $terceros2Dcontactos->principal->CheckBoxListHtml(FALSE, "x{$terceros2Dcontactos_grid->RowIndex}_principal[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dcontactos_grid->RowCnt ?>_terceros2Dcontactos_principal" class="terceros2Dcontactos_principal">
<span<?php echo $terceros2Dcontactos->principal->ViewAttributes() ?>>
<?php echo $terceros2Dcontactos->principal->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_principal" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->principal->FormValue) ?>">
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_principal" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->principal->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dcontactos_grid->ListOptions->Render("body", "right", $terceros2Dcontactos_grid->RowCnt);
?>
	</tr>
<?php if ($terceros2Dcontactos->RowType == EW_ROWTYPE_ADD || $terceros2Dcontactos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fterceros2Dcontactosgrid.UpdateOpts(<?php echo $terceros2Dcontactos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($terceros2Dcontactos->CurrentAction <> "gridadd" || $terceros2Dcontactos->CurrentMode == "copy")
		if (!$terceros2Dcontactos_grid->Recordset->EOF) $terceros2Dcontactos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($terceros2Dcontactos->CurrentMode == "add" || $terceros2Dcontactos->CurrentMode == "copy" || $terceros2Dcontactos->CurrentMode == "edit") {
		$terceros2Dcontactos_grid->RowIndex = '$rowindex$';
		$terceros2Dcontactos_grid->LoadDefaultValues();

		// Set row properties
		$terceros2Dcontactos->ResetAttrs();
		$terceros2Dcontactos->RowAttrs = array_merge($terceros2Dcontactos->RowAttrs, array('data-rowindex'=>$terceros2Dcontactos_grid->RowIndex, 'id'=>'r0_terceros2Dcontactos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($terceros2Dcontactos->RowAttrs["class"], "ewTemplate");
		$terceros2Dcontactos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$terceros2Dcontactos_grid->RenderRow();

		// Render list options
		$terceros2Dcontactos_grid->RenderListOptions();
		$terceros2Dcontactos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $terceros2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dcontactos_grid->ListOptions->Render("body", "left", $terceros2Dcontactos_grid->RowIndex);
?>
	<?php if ($terceros2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($terceros2Dcontactos->CurrentAction <> "F") { ?>
<?php if ($terceros2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<span<?php echo $terceros2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<select data-table="terceros2Dcontactos" data-field="x_idTercero" data-value-separator="<?php echo $terceros2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero"<?php echo $terceros2Dcontactos->idTercero->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idTercero->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dcontactos_idTercero" class="form-group terceros2Dcontactos_idTercero">
<span<?php echo $terceros2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idTercero" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dcontactos->idPersona->Visible) { // idPersona ?>
		<td data-name="idPersona">
<?php if ($terceros2Dcontactos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_terceros2Dcontactos_idPersona" class="form-group terceros2Dcontactos_idPersona">
<select data-table="terceros2Dcontactos" data-field="x_idPersona" data-value-separator="<?php echo $terceros2Dcontactos->idPersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona"<?php echo $terceros2Dcontactos->idPersona->EditAttributes() ?>>
<?php echo $terceros2Dcontactos->idPersona->SelectOptionListHtml("x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona") ?>
</select>
<input type="hidden" name="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="s_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo $terceros2Dcontactos->idPersona->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dcontactos_idPersona" class="form-group terceros2Dcontactos_idPersona">
<span<?php echo $terceros2Dcontactos->idPersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->idPersona->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idPersona" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idPersona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_idPersona" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_idPersona" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->idPersona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dcontactos->principal->Visible) { // principal ?>
		<td data-name="principal">
<?php if ($terceros2Dcontactos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_terceros2Dcontactos_principal" class="form-group terceros2Dcontactos_principal">
<div id="tp_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" class="ewTemplate"><input type="checkbox" data-table="terceros2Dcontactos" data-field="x_principal" data-value-separator="<?php echo $terceros2Dcontactos->principal->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="{value}"<?php echo $terceros2Dcontactos->principal->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $terceros2Dcontactos->principal->CheckBoxListHtml(FALSE, "x{$terceros2Dcontactos_grid->RowIndex}_principal[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dcontactos_principal" class="form-group terceros2Dcontactos_principal">
<span<?php echo $terceros2Dcontactos->principal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dcontactos->principal->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_principal" name="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" id="x<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->principal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dcontactos" data-field="x_principal" name="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" id="o<?php echo $terceros2Dcontactos_grid->RowIndex ?>_principal[]" value="<?php echo ew_HtmlEncode($terceros2Dcontactos->principal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dcontactos_grid->ListOptions->Render("body", "right", $terceros2Dcontactos_grid->RowCnt);
?>
<script type="text/javascript">
fterceros2Dcontactosgrid.UpdateOpts(<?php echo $terceros2Dcontactos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($terceros2Dcontactos->CurrentMode == "add" || $terceros2Dcontactos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $terceros2Dcontactos_grid->FormKeyCountName ?>" id="<?php echo $terceros2Dcontactos_grid->FormKeyCountName ?>" value="<?php echo $terceros2Dcontactos_grid->KeyCount ?>">
<?php echo $terceros2Dcontactos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($terceros2Dcontactos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $terceros2Dcontactos_grid->FormKeyCountName ?>" id="<?php echo $terceros2Dcontactos_grid->FormKeyCountName ?>" value="<?php echo $terceros2Dcontactos_grid->KeyCount ?>">
<?php echo $terceros2Dcontactos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($terceros2Dcontactos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fterceros2Dcontactosgrid">
</div>
<?php

// Close recordset
if ($terceros2Dcontactos_grid->Recordset)
	$terceros2Dcontactos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($terceros2Dcontactos_grid->TotalRecs == 0 && $terceros2Dcontactos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros2Dcontactos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($terceros2Dcontactos->Export == "") { ?>
<script type="text/javascript">
fterceros2Dcontactosgrid.Init();
</script>
<?php } ?>
<?php
$terceros2Dcontactos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$terceros2Dcontactos_grid->Page_Terminate();
?>
