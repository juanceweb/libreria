<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($terceros2Dmedios2Dcontactos_grid)) $terceros2Dmedios2Dcontactos_grid = new cterceros2Dmedios2Dcontactos_grid();

// Page init
$terceros2Dmedios2Dcontactos_grid->Page_Init();

// Page main
$terceros2Dmedios2Dcontactos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$terceros2Dmedios2Dcontactos_grid->Page_Render();
?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<script type="text/javascript">

// Form object
var fterceros2Dmedios2Dcontactosgrid = new ew_Form("fterceros2Dmedios2Dcontactosgrid", "grid");
fterceros2Dmedios2Dcontactosgrid.FormKeyCountName = '<?php echo $terceros2Dmedios2Dcontactos_grid->FormKeyCountName ?>';

// Validate form
fterceros2Dmedios2Dcontactosgrid.Validate = function() {
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
fterceros2Dmedios2Dcontactosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "denominacion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTipoContacto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "contacto", false)) return false;
	return true;
}

// Form_CustomValidate event
fterceros2Dmedios2Dcontactosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fterceros2Dmedios2Dcontactosgrid.ValidateRequired = true;
<?php } else { ?>
fterceros2Dmedios2Dcontactosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fterceros2Dmedios2Dcontactosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};
fterceros2Dmedios2Dcontactosgrid.Lists["x_idTipoContacto"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipos2Dcontactos"};

// Form object for search
</script>
<?php } ?>
<?php
if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd") {
	if ($terceros2Dmedios2Dcontactos->CurrentMode == "copy") {
		$bSelectLimit = $terceros2Dmedios2Dcontactos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$terceros2Dmedios2Dcontactos_grid->TotalRecs = $terceros2Dmedios2Dcontactos->SelectRecordCount();
			$terceros2Dmedios2Dcontactos_grid->Recordset = $terceros2Dmedios2Dcontactos_grid->LoadRecordset($terceros2Dmedios2Dcontactos_grid->StartRec-1, $terceros2Dmedios2Dcontactos_grid->DisplayRecs);
		} else {
			if ($terceros2Dmedios2Dcontactos_grid->Recordset = $terceros2Dmedios2Dcontactos_grid->LoadRecordset())
				$terceros2Dmedios2Dcontactos_grid->TotalRecs = $terceros2Dmedios2Dcontactos_grid->Recordset->RecordCount();
		}
		$terceros2Dmedios2Dcontactos_grid->StartRec = 1;
		$terceros2Dmedios2Dcontactos_grid->DisplayRecs = $terceros2Dmedios2Dcontactos_grid->TotalRecs;
	} else {
		$terceros2Dmedios2Dcontactos->CurrentFilter = "0=1";
		$terceros2Dmedios2Dcontactos_grid->StartRec = 1;
		$terceros2Dmedios2Dcontactos_grid->DisplayRecs = $terceros2Dmedios2Dcontactos->GridAddRowCount;
	}
	$terceros2Dmedios2Dcontactos_grid->TotalRecs = $terceros2Dmedios2Dcontactos_grid->DisplayRecs;
	$terceros2Dmedios2Dcontactos_grid->StopRec = $terceros2Dmedios2Dcontactos_grid->DisplayRecs;
} else {
	$bSelectLimit = $terceros2Dmedios2Dcontactos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($terceros2Dmedios2Dcontactos_grid->TotalRecs <= 0)
			$terceros2Dmedios2Dcontactos_grid->TotalRecs = $terceros2Dmedios2Dcontactos->SelectRecordCount();
	} else {
		if (!$terceros2Dmedios2Dcontactos_grid->Recordset && ($terceros2Dmedios2Dcontactos_grid->Recordset = $terceros2Dmedios2Dcontactos_grid->LoadRecordset()))
			$terceros2Dmedios2Dcontactos_grid->TotalRecs = $terceros2Dmedios2Dcontactos_grid->Recordset->RecordCount();
	}
	$terceros2Dmedios2Dcontactos_grid->StartRec = 1;
	$terceros2Dmedios2Dcontactos_grid->DisplayRecs = $terceros2Dmedios2Dcontactos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$terceros2Dmedios2Dcontactos_grid->Recordset = $terceros2Dmedios2Dcontactos_grid->LoadRecordset($terceros2Dmedios2Dcontactos_grid->StartRec-1, $terceros2Dmedios2Dcontactos_grid->DisplayRecs);

	// Set no record found message
	if ($terceros2Dmedios2Dcontactos->CurrentAction == "" && $terceros2Dmedios2Dcontactos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$terceros2Dmedios2Dcontactos_grid->setWarningMessage(ew_DeniedMsg());
		if ($terceros2Dmedios2Dcontactos_grid->SearchWhere == "0=101")
			$terceros2Dmedios2Dcontactos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$terceros2Dmedios2Dcontactos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$terceros2Dmedios2Dcontactos_grid->RenderOtherOptions();
?>
<?php $terceros2Dmedios2Dcontactos_grid->ShowPageHeader(); ?>
<?php
$terceros2Dmedios2Dcontactos_grid->ShowMessage();
?>
<?php if ($terceros2Dmedios2Dcontactos_grid->TotalRecs > 0 || $terceros2Dmedios2Dcontactos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid terceros2Dmedios2Dcontactos">
<div id="fterceros2Dmedios2Dcontactosgrid" class="ewForm form-inline">
<?php if ($terceros2Dmedios2Dcontactos_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($terceros2Dmedios2Dcontactos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_terceros2Dmedios2Dcontactos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_terceros2Dmedios2Dcontactosgrid" class="table ewTable">
<?php echo $terceros2Dmedios2Dcontactos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$terceros2Dmedios2Dcontactos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$terceros2Dmedios2Dcontactos_grid->RenderListOptions();

// Render list options (header, left)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("header", "left");
?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->denominacion) == "") { ?>
		<th data-name="denominacion"><div id="elh_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->denominacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denominacion"><div><div id="elh_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->denominacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->denominacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->denominacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->idTipoContacto) == "") { ?>
		<th data-name="idTipoContacto"><div id="elh_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTipoContacto"><div><div id="elh_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->idTipoContacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
	<?php if ($terceros2Dmedios2Dcontactos->SortUrl($terceros2Dmedios2Dcontactos->contacto) == "") { ?>
		<th data-name="contacto"><div id="elh_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto"><div class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->contacto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contacto"><div><div id="elh_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $terceros2Dmedios2Dcontactos->contacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($terceros2Dmedios2Dcontactos->contacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($terceros2Dmedios2Dcontactos->contacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$terceros2Dmedios2Dcontactos_grid->StartRec = 1;
$terceros2Dmedios2Dcontactos_grid->StopRec = $terceros2Dmedios2Dcontactos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($terceros2Dmedios2Dcontactos_grid->FormKeyCountName) && ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd" || $terceros2Dmedios2Dcontactos->CurrentAction == "gridedit" || $terceros2Dmedios2Dcontactos->CurrentAction == "F")) {
		$terceros2Dmedios2Dcontactos_grid->KeyCount = $objForm->GetValue($terceros2Dmedios2Dcontactos_grid->FormKeyCountName);
		$terceros2Dmedios2Dcontactos_grid->StopRec = $terceros2Dmedios2Dcontactos_grid->StartRec + $terceros2Dmedios2Dcontactos_grid->KeyCount - 1;
	}
}
$terceros2Dmedios2Dcontactos_grid->RecCnt = $terceros2Dmedios2Dcontactos_grid->StartRec - 1;
if ($terceros2Dmedios2Dcontactos_grid->Recordset && !$terceros2Dmedios2Dcontactos_grid->Recordset->EOF) {
	$terceros2Dmedios2Dcontactos_grid->Recordset->MoveFirst();
	$bSelectLimit = $terceros2Dmedios2Dcontactos_grid->UseSelectLimit;
	if (!$bSelectLimit && $terceros2Dmedios2Dcontactos_grid->StartRec > 1)
		$terceros2Dmedios2Dcontactos_grid->Recordset->Move($terceros2Dmedios2Dcontactos_grid->StartRec - 1);
} elseif (!$terceros2Dmedios2Dcontactos->AllowAddDeleteRow && $terceros2Dmedios2Dcontactos_grid->StopRec == 0) {
	$terceros2Dmedios2Dcontactos_grid->StopRec = $terceros2Dmedios2Dcontactos->GridAddRowCount;
}

// Initialize aggregate
$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$terceros2Dmedios2Dcontactos->ResetAttrs();
$terceros2Dmedios2Dcontactos_grid->RenderRow();
if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd")
	$terceros2Dmedios2Dcontactos_grid->RowIndex = 0;
if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridedit")
	$terceros2Dmedios2Dcontactos_grid->RowIndex = 0;
while ($terceros2Dmedios2Dcontactos_grid->RecCnt < $terceros2Dmedios2Dcontactos_grid->StopRec) {
	$terceros2Dmedios2Dcontactos_grid->RecCnt++;
	if (intval($terceros2Dmedios2Dcontactos_grid->RecCnt) >= intval($terceros2Dmedios2Dcontactos_grid->StartRec)) {
		$terceros2Dmedios2Dcontactos_grid->RowCnt++;
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd" || $terceros2Dmedios2Dcontactos->CurrentAction == "gridedit" || $terceros2Dmedios2Dcontactos->CurrentAction == "F") {
			$terceros2Dmedios2Dcontactos_grid->RowIndex++;
			$objForm->Index = $terceros2Dmedios2Dcontactos_grid->RowIndex;
			if ($objForm->HasValue($terceros2Dmedios2Dcontactos_grid->FormActionName))
				$terceros2Dmedios2Dcontactos_grid->RowAction = strval($objForm->GetValue($terceros2Dmedios2Dcontactos_grid->FormActionName));
			elseif ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd")
				$terceros2Dmedios2Dcontactos_grid->RowAction = "insert";
			else
				$terceros2Dmedios2Dcontactos_grid->RowAction = "";
		}

		// Set up key count
		$terceros2Dmedios2Dcontactos_grid->KeyCount = $terceros2Dmedios2Dcontactos_grid->RowIndex;

		// Init row class and style
		$terceros2Dmedios2Dcontactos->ResetAttrs();
		$terceros2Dmedios2Dcontactos->CssClass = "";
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd") {
			if ($terceros2Dmedios2Dcontactos->CurrentMode == "copy") {
				$terceros2Dmedios2Dcontactos_grid->LoadRowValues($terceros2Dmedios2Dcontactos_grid->Recordset); // Load row values
				$terceros2Dmedios2Dcontactos_grid->SetRecordKey($terceros2Dmedios2Dcontactos_grid->RowOldKey, $terceros2Dmedios2Dcontactos_grid->Recordset); // Set old record key
			} else {
				$terceros2Dmedios2Dcontactos_grid->LoadDefaultValues(); // Load default values
				$terceros2Dmedios2Dcontactos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$terceros2Dmedios2Dcontactos_grid->LoadRowValues($terceros2Dmedios2Dcontactos_grid->Recordset); // Load row values
		}
		$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd") // Grid add
			$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridadd" && $terceros2Dmedios2Dcontactos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$terceros2Dmedios2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dmedios2Dcontactos_grid->RowIndex); // Restore form values
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridedit") { // Grid edit
			if ($terceros2Dmedios2Dcontactos->EventCancelled) {
				$terceros2Dmedios2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dmedios2Dcontactos_grid->RowIndex); // Restore form values
			}
			if ($terceros2Dmedios2Dcontactos_grid->RowAction == "insert")
				$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "gridedit" && ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT || $terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) && $terceros2Dmedios2Dcontactos->EventCancelled) // Update failed
			$terceros2Dmedios2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dmedios2Dcontactos_grid->RowIndex); // Restore form values
		if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$terceros2Dmedios2Dcontactos_grid->EditRowCnt++;
		if ($terceros2Dmedios2Dcontactos->CurrentAction == "F") // Confirm row
			$terceros2Dmedios2Dcontactos_grid->RestoreCurrentRowFormValues($terceros2Dmedios2Dcontactos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$terceros2Dmedios2Dcontactos->RowAttrs = array_merge($terceros2Dmedios2Dcontactos->RowAttrs, array('data-rowindex'=>$terceros2Dmedios2Dcontactos_grid->RowCnt, 'id'=>'r' . $terceros2Dmedios2Dcontactos_grid->RowCnt . '_terceros2Dmedios2Dcontactos', 'data-rowtype'=>$terceros2Dmedios2Dcontactos->RowType));

		// Render row
		$terceros2Dmedios2Dcontactos_grid->RenderRow();

		// Render list options
		$terceros2Dmedios2Dcontactos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($terceros2Dmedios2Dcontactos_grid->RowAction <> "delete" && $terceros2Dmedios2Dcontactos_grid->RowAction <> "insertdelete" && !($terceros2Dmedios2Dcontactos_grid->RowAction == "insert" && $terceros2Dmedios2Dcontactos->CurrentAction == "F" && $terceros2Dmedios2Dcontactos_grid->EmptyRow())) {
?>
	<tr<?php echo $terceros2Dmedios2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("body", "left", $terceros2Dmedios2Dcontactos_grid->RowCnt);
?>
	<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $terceros2Dmedios2Dcontactos->idTercero->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->CurrentValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->CurrentValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTercero" class="terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->FormValue) ?>">
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->OldValue) ?>">
<?php } ?>
<a id="<?php echo $terceros2Dmedios2Dcontactos_grid->PageObjName . "_row_" . $terceros2Dmedios2Dcontactos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_id" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->id->CurrentValue) ?>">
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_id" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->id->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT || $terceros2Dmedios2Dcontactos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_id" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion"<?php echo $terceros2Dmedios2Dcontactos->denominacion->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_denominacion" class="terceros2Dmedios2Dcontactos_denominacion">
<span<?php echo $terceros2Dmedios2Dcontactos->denominacion->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->denominacion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->FormValue) ?>">
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
		<td data-name="idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipos-contactos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto',url:'tipos2Dcontactosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipos-contactos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto',url:'tipos2Dcontactosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_idTipoContacto" class="terceros2Dmedios2Dcontactos_idTipoContacto">
<span<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->FormValue) ?>">
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
		<td data-name="contacto"<?php echo $terceros2Dmedios2Dcontactos->contacto->CellAttributes() ?>>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->contacto->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->contacto->EditAttributes() ?>>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->OldValue) ?>">
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->contacto->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->contacto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $terceros2Dmedios2Dcontactos_grid->RowCnt ?>_terceros2Dmedios2Dcontactos_contacto" class="terceros2Dmedios2Dcontactos_contacto">
<span<?php echo $terceros2Dmedios2Dcontactos->contacto->ViewAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->contacto->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->FormValue) ?>">
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("body", "right", $terceros2Dmedios2Dcontactos_grid->RowCnt);
?>
	</tr>
<?php if ($terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_ADD || $terceros2Dmedios2Dcontactos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactosgrid.UpdateOpts(<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($terceros2Dmedios2Dcontactos->CurrentAction <> "gridadd" || $terceros2Dmedios2Dcontactos->CurrentMode == "copy")
		if (!$terceros2Dmedios2Dcontactos_grid->Recordset->EOF) $terceros2Dmedios2Dcontactos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($terceros2Dmedios2Dcontactos->CurrentMode == "add" || $terceros2Dmedios2Dcontactos->CurrentMode == "copy" || $terceros2Dmedios2Dcontactos->CurrentMode == "edit") {
		$terceros2Dmedios2Dcontactos_grid->RowIndex = '$rowindex$';
		$terceros2Dmedios2Dcontactos_grid->LoadDefaultValues();

		// Set row properties
		$terceros2Dmedios2Dcontactos->ResetAttrs();
		$terceros2Dmedios2Dcontactos->RowAttrs = array_merge($terceros2Dmedios2Dcontactos->RowAttrs, array('data-rowindex'=>$terceros2Dmedios2Dcontactos_grid->RowIndex, 'id'=>'r0_terceros2Dmedios2Dcontactos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($terceros2Dmedios2Dcontactos->RowAttrs["class"], "ewTemplate");
		$terceros2Dmedios2Dcontactos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$terceros2Dmedios2Dcontactos_grid->RenderRow();

		// Render list options
		$terceros2Dmedios2Dcontactos_grid->RenderListOptions();
		$terceros2Dmedios2Dcontactos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $terceros2Dmedios2Dcontactos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("body", "left", $terceros2Dmedios2Dcontactos_grid->RowIndex);
?>
	<?php if ($terceros2Dmedios2Dcontactos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction <> "F") { ?>
<?php if ($terceros2Dmedios2Dcontactos->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero"><?php echo (strval($terceros2Dmedios2Dcontactos->idTercero->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $terceros2Dmedios2Dcontactos->idTercero->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($terceros2Dmedios2Dcontactos->idTercero->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTercero->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->CurrentValue ?>"<?php echo $terceros2Dmedios2Dcontactos->idTercero->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo $terceros2Dmedios2Dcontactos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_idTercero" class="form-group terceros2Dmedios2Dcontactos_idTercero">
<span<?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTercero" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->denominacion->Visible) { // denominacion ?>
		<td data-name="denominacion">
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->denominacion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_denominacion" class="form-group terceros2Dmedios2Dcontactos_denominacion">
<span<?php echo $terceros2Dmedios2Dcontactos->denominacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->denominacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_denominacion" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_denominacion" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->denominacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->idTipoContacto->Visible) { // idTipoContacto ?>
		<td data-name="idTipoContacto">
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<select data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" data-value-separator="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->EditAttributes() ?>>
<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->SelectOptionListHtml("x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipos-contactos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto',url:'tipos2Dcontactosaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="s_x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_idTipoContacto" class="form-group terceros2Dmedios2Dcontactos_idTipoContacto">
<span<?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->idTipoContacto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_idTipoContacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_idTipoContacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->idTipoContacto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($terceros2Dmedios2Dcontactos->contacto->Visible) { // contacto ?>
		<td data-name="contacto">
<?php if ($terceros2Dmedios2Dcontactos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<input type="text" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->getPlaceHolder()) ?>" value="<?php echo $terceros2Dmedios2Dcontactos->contacto->EditValue ?>"<?php echo $terceros2Dmedios2Dcontactos->contacto->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_terceros2Dmedios2Dcontactos_contacto" class="form-group terceros2Dmedios2Dcontactos_contacto">
<span<?php echo $terceros2Dmedios2Dcontactos->contacto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $terceros2Dmedios2Dcontactos->contacto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="x<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="terceros2Dmedios2Dcontactos" data-field="x_contacto" name="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" id="o<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>_contacto" value="<?php echo ew_HtmlEncode($terceros2Dmedios2Dcontactos->contacto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$terceros2Dmedios2Dcontactos_grid->ListOptions->Render("body", "right", $terceros2Dmedios2Dcontactos_grid->RowCnt);
?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactosgrid.UpdateOpts(<?php echo $terceros2Dmedios2Dcontactos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($terceros2Dmedios2Dcontactos->CurrentMode == "add" || $terceros2Dmedios2Dcontactos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $terceros2Dmedios2Dcontactos_grid->FormKeyCountName ?>" id="<?php echo $terceros2Dmedios2Dcontactos_grid->FormKeyCountName ?>" value="<?php echo $terceros2Dmedios2Dcontactos_grid->KeyCount ?>">
<?php echo $terceros2Dmedios2Dcontactos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $terceros2Dmedios2Dcontactos_grid->FormKeyCountName ?>" id="<?php echo $terceros2Dmedios2Dcontactos_grid->FormKeyCountName ?>" value="<?php echo $terceros2Dmedios2Dcontactos_grid->KeyCount ?>">
<?php echo $terceros2Dmedios2Dcontactos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fterceros2Dmedios2Dcontactosgrid">
</div>
<?php

// Close recordset
if ($terceros2Dmedios2Dcontactos_grid->Recordset)
	$terceros2Dmedios2Dcontactos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos_grid->TotalRecs == 0 && $terceros2Dmedios2Dcontactos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($terceros2Dmedios2Dcontactos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($terceros2Dmedios2Dcontactos->Export == "") { ?>
<script type="text/javascript">
fterceros2Dmedios2Dcontactosgrid.Init();
</script>
<?php } ?>
<?php
$terceros2Dmedios2Dcontactos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$terceros2Dmedios2Dcontactos_grid->Page_Terminate();
?>
