<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($subcategoria2Dterceros2Ddescuentos_grid)) $subcategoria2Dterceros2Ddescuentos_grid = new csubcategoria2Dterceros2Ddescuentos_grid();

// Page init
$subcategoria2Dterceros2Ddescuentos_grid->Page_Init();

// Page main
$subcategoria2Dterceros2Ddescuentos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subcategoria2Dterceros2Ddescuentos_grid->Page_Render();
?>
<?php if ($subcategoria2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsubcategoria2Dterceros2Ddescuentosgrid = new ew_Form("fsubcategoria2Dterceros2Ddescuentosgrid", "grid");
fsubcategoria2Dterceros2Ddescuentosgrid.FormKeyCountName = '<?php echo $subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName ?>';

// Validate form
fsubcategoria2Dterceros2Ddescuentosgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($subcategoria2Dterceros2Ddescuentos->descuento->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsubcategoria2Dterceros2Ddescuentosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idSubcategoria", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "descuento", false)) return false;
	return true;
}

// Form_CustomValidate event
fsubcategoria2Dterceros2Ddescuentosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubcategoria2Dterceros2Ddescuentosgrid.ValidateRequired = true;
<?php } else { ?>
fsubcategoria2Dterceros2Ddescuentosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsubcategoria2Dterceros2Ddescuentosgrid.Lists["x_idSubcategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subcategorias2Darticulos"};
fsubcategoria2Dterceros2Ddescuentosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
	if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "copy") {
		$bSelectLimit = $subcategoria2Dterceros2Ddescuentos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$subcategoria2Dterceros2Ddescuentos_grid->TotalRecs = $subcategoria2Dterceros2Ddescuentos->SelectRecordCount();
			$subcategoria2Dterceros2Ddescuentos_grid->Recordset = $subcategoria2Dterceros2Ddescuentos_grid->LoadRecordset($subcategoria2Dterceros2Ddescuentos_grid->StartRec-1, $subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs);
		} else {
			if ($subcategoria2Dterceros2Ddescuentos_grid->Recordset = $subcategoria2Dterceros2Ddescuentos_grid->LoadRecordset())
				$subcategoria2Dterceros2Ddescuentos_grid->TotalRecs = $subcategoria2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
		}
		$subcategoria2Dterceros2Ddescuentos_grid->StartRec = 1;
		$subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs = $subcategoria2Dterceros2Ddescuentos_grid->TotalRecs;
	} else {
		$subcategoria2Dterceros2Ddescuentos->CurrentFilter = "0=1";
		$subcategoria2Dterceros2Ddescuentos_grid->StartRec = 1;
		$subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs = $subcategoria2Dterceros2Ddescuentos->GridAddRowCount;
	}
	$subcategoria2Dterceros2Ddescuentos_grid->TotalRecs = $subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs;
	$subcategoria2Dterceros2Ddescuentos_grid->StopRec = $subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs;
} else {
	$bSelectLimit = $subcategoria2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($subcategoria2Dterceros2Ddescuentos_grid->TotalRecs <= 0)
			$subcategoria2Dterceros2Ddescuentos_grid->TotalRecs = $subcategoria2Dterceros2Ddescuentos->SelectRecordCount();
	} else {
		if (!$subcategoria2Dterceros2Ddescuentos_grid->Recordset && ($subcategoria2Dterceros2Ddescuentos_grid->Recordset = $subcategoria2Dterceros2Ddescuentos_grid->LoadRecordset()))
			$subcategoria2Dterceros2Ddescuentos_grid->TotalRecs = $subcategoria2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
	}
	$subcategoria2Dterceros2Ddescuentos_grid->StartRec = 1;
	$subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs = $subcategoria2Dterceros2Ddescuentos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$subcategoria2Dterceros2Ddescuentos_grid->Recordset = $subcategoria2Dterceros2Ddescuentos_grid->LoadRecordset($subcategoria2Dterceros2Ddescuentos_grid->StartRec-1, $subcategoria2Dterceros2Ddescuentos_grid->DisplayRecs);

	// Set no record found message
	if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "" && $subcategoria2Dterceros2Ddescuentos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$subcategoria2Dterceros2Ddescuentos_grid->setWarningMessage(ew_DeniedMsg());
		if ($subcategoria2Dterceros2Ddescuentos_grid->SearchWhere == "0=101")
			$subcategoria2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$subcategoria2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$subcategoria2Dterceros2Ddescuentos_grid->RenderOtherOptions();
?>
<?php $subcategoria2Dterceros2Ddescuentos_grid->ShowPageHeader(); ?>
<?php
$subcategoria2Dterceros2Ddescuentos_grid->ShowMessage();
?>
<?php if ($subcategoria2Dterceros2Ddescuentos_grid->TotalRecs > 0 || $subcategoria2Dterceros2Ddescuentos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid subcategoria2Dterceros2Ddescuentos">
<div id="fsubcategoria2Dterceros2Ddescuentosgrid" class="ewForm form-inline">
<?php if ($subcategoria2Dterceros2Ddescuentos_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($subcategoria2Dterceros2Ddescuentos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_subcategoria2Dterceros2Ddescuentos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_subcategoria2Dterceros2Ddescuentosgrid" class="table ewTable">
<?php echo $subcategoria2Dterceros2Ddescuentos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$subcategoria2Dterceros2Ddescuentos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$subcategoria2Dterceros2Ddescuentos_grid->RenderListOptions();

// Render list options (header, left)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "left");
?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->Visible) { // idSubcategoria ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->SortUrl($subcategoria2Dterceros2Ddescuentos->idSubcategoria) == "") { ?>
		<th data-name="idSubcategoria"><div id="elh_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="subcategoria2Dterceros2Ddescuentos_idSubcategoria"><div class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idSubcategoria"><div><div id="elh_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="subcategoria2Dterceros2Ddescuentos_idSubcategoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->SortUrl($subcategoria2Dterceros2Ddescuentos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_subcategoria2Dterceros2Ddescuentos_idTercero" class="subcategoria2Dterceros2Ddescuentos_idTercero"><div class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_subcategoria2Dterceros2Ddescuentos_idTercero" class="subcategoria2Dterceros2Ddescuentos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subcategoria2Dterceros2Ddescuentos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subcategoria2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->SortUrl($subcategoria2Dterceros2Ddescuentos->descuento) == "") { ?>
		<th data-name="descuento"><div id="elh_subcategoria2Dterceros2Ddescuentos_descuento" class="subcategoria2Dterceros2Ddescuentos_descuento"><div class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->descuento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descuento"><div><div id="elh_subcategoria2Dterceros2Ddescuentos_descuento" class="subcategoria2Dterceros2Ddescuentos_descuento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subcategoria2Dterceros2Ddescuentos->descuento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subcategoria2Dterceros2Ddescuentos->descuento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subcategoria2Dterceros2Ddescuentos->descuento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$subcategoria2Dterceros2Ddescuentos_grid->StartRec = 1;
$subcategoria2Dterceros2Ddescuentos_grid->StopRec = $subcategoria2Dterceros2Ddescuentos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName) && ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $subcategoria2Dterceros2Ddescuentos->CurrentAction == "F")) {
		$subcategoria2Dterceros2Ddescuentos_grid->KeyCount = $objForm->GetValue($subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName);
		$subcategoria2Dterceros2Ddescuentos_grid->StopRec = $subcategoria2Dterceros2Ddescuentos_grid->StartRec + $subcategoria2Dterceros2Ddescuentos_grid->KeyCount - 1;
	}
}
$subcategoria2Dterceros2Ddescuentos_grid->RecCnt = $subcategoria2Dterceros2Ddescuentos_grid->StartRec - 1;
if ($subcategoria2Dterceros2Ddescuentos_grid->Recordset && !$subcategoria2Dterceros2Ddescuentos_grid->Recordset->EOF) {
	$subcategoria2Dterceros2Ddescuentos_grid->Recordset->MoveFirst();
	$bSelectLimit = $subcategoria2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if (!$bSelectLimit && $subcategoria2Dterceros2Ddescuentos_grid->StartRec > 1)
		$subcategoria2Dterceros2Ddescuentos_grid->Recordset->Move($subcategoria2Dterceros2Ddescuentos_grid->StartRec - 1);
} elseif (!$subcategoria2Dterceros2Ddescuentos->AllowAddDeleteRow && $subcategoria2Dterceros2Ddescuentos_grid->StopRec == 0) {
	$subcategoria2Dterceros2Ddescuentos_grid->StopRec = $subcategoria2Dterceros2Ddescuentos->GridAddRowCount;
}

// Initialize aggregate
$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$subcategoria2Dterceros2Ddescuentos->ResetAttrs();
$subcategoria2Dterceros2Ddescuentos_grid->RenderRow();
if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd")
	$subcategoria2Dterceros2Ddescuentos_grid->RowIndex = 0;
if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridedit")
	$subcategoria2Dterceros2Ddescuentos_grid->RowIndex = 0;
while ($subcategoria2Dterceros2Ddescuentos_grid->RecCnt < $subcategoria2Dterceros2Ddescuentos_grid->StopRec) {
	$subcategoria2Dterceros2Ddescuentos_grid->RecCnt++;
	if (intval($subcategoria2Dterceros2Ddescuentos_grid->RecCnt) >= intval($subcategoria2Dterceros2Ddescuentos_grid->StartRec)) {
		$subcategoria2Dterceros2Ddescuentos_grid->RowCnt++;
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $subcategoria2Dterceros2Ddescuentos->CurrentAction == "F") {
			$subcategoria2Dterceros2Ddescuentos_grid->RowIndex++;
			$objForm->Index = $subcategoria2Dterceros2Ddescuentos_grid->RowIndex;
			if ($objForm->HasValue($subcategoria2Dterceros2Ddescuentos_grid->FormActionName))
				$subcategoria2Dterceros2Ddescuentos_grid->RowAction = strval($objForm->GetValue($subcategoria2Dterceros2Ddescuentos_grid->FormActionName));
			elseif ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd")
				$subcategoria2Dterceros2Ddescuentos_grid->RowAction = "insert";
			else
				$subcategoria2Dterceros2Ddescuentos_grid->RowAction = "";
		}

		// Set up key count
		$subcategoria2Dterceros2Ddescuentos_grid->KeyCount = $subcategoria2Dterceros2Ddescuentos_grid->RowIndex;

		// Init row class and style
		$subcategoria2Dterceros2Ddescuentos->ResetAttrs();
		$subcategoria2Dterceros2Ddescuentos->CssClass = "";
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
			if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "copy") {
				$subcategoria2Dterceros2Ddescuentos_grid->LoadRowValues($subcategoria2Dterceros2Ddescuentos_grid->Recordset); // Load row values
				$subcategoria2Dterceros2Ddescuentos_grid->SetRecordKey($subcategoria2Dterceros2Ddescuentos_grid->RowOldKey, $subcategoria2Dterceros2Ddescuentos_grid->Recordset); // Set old record key
			} else {
				$subcategoria2Dterceros2Ddescuentos_grid->LoadDefaultValues(); // Load default values
				$subcategoria2Dterceros2Ddescuentos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$subcategoria2Dterceros2Ddescuentos_grid->LoadRowValues($subcategoria2Dterceros2Ddescuentos_grid->Recordset); // Load row values
		}
		$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd") // Grid add
			$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridadd" && $subcategoria2Dterceros2Ddescuentos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$subcategoria2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($subcategoria2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridedit") { // Grid edit
			if ($subcategoria2Dterceros2Ddescuentos->EventCancelled) {
				$subcategoria2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($subcategoria2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
			}
			if ($subcategoria2Dterceros2Ddescuentos_grid->RowAction == "insert")
				$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "gridedit" && ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) && $subcategoria2Dterceros2Ddescuentos->EventCancelled) // Update failed
			$subcategoria2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($subcategoria2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$subcategoria2Dterceros2Ddescuentos_grid->EditRowCnt++;
		if ($subcategoria2Dterceros2Ddescuentos->CurrentAction == "F") // Confirm row
			$subcategoria2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($subcategoria2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$subcategoria2Dterceros2Ddescuentos->RowAttrs = array_merge($subcategoria2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$subcategoria2Dterceros2Ddescuentos_grid->RowCnt, 'id'=>'r' . $subcategoria2Dterceros2Ddescuentos_grid->RowCnt . '_subcategoria2Dterceros2Ddescuentos', 'data-rowtype'=>$subcategoria2Dterceros2Ddescuentos->RowType));

		// Render row
		$subcategoria2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$subcategoria2Dterceros2Ddescuentos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($subcategoria2Dterceros2Ddescuentos_grid->RowAction <> "delete" && $subcategoria2Dterceros2Ddescuentos_grid->RowAction <> "insertdelete" && !($subcategoria2Dterceros2Ddescuentos_grid->RowAction == "insert" && $subcategoria2Dterceros2Ddescuentos->CurrentAction == "F" && $subcategoria2Dterceros2Ddescuentos_grid->EmptyRow())) {
?>
	<tr<?php echo $subcategoria2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $subcategoria2Dterceros2Ddescuentos_grid->RowCnt);
?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->Visible) { // idSubcategoria ?>
		<td data-name="idSubcategoria"<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->CellAttributes() ?>>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->getSessionValue() <> "") { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria"<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->OldValue) ?>">
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->getSessionValue() <> "") { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria"<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->FormValue) ?>">
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->OldValue) ?>">
<?php } ?>
<a id="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->PageObjName . "_row_" . $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_id" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->id->OldValue) ?>">
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $subcategoria2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->CellAttributes() ?>>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_idTercero" class="subcategoria2Dterceros2Ddescuentos_idTercero">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento"<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->CellAttributes() ?>>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_descuento" class="form-group subcategoria2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_descuento" class="form-group subcategoria2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowCnt ?>_subcategoria2Dterceros2Ddescuentos_descuento" class="subcategoria2Dterceros2Ddescuentos_descuento">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $subcategoria2Dterceros2Ddescuentos_grid->RowCnt);
?>
	</tr>
<?php if ($subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD || $subcategoria2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsubcategoria2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($subcategoria2Dterceros2Ddescuentos->CurrentAction <> "gridadd" || $subcategoria2Dterceros2Ddescuentos->CurrentMode == "copy")
		if (!$subcategoria2Dterceros2Ddescuentos_grid->Recordset->EOF) $subcategoria2Dterceros2Ddescuentos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "add" || $subcategoria2Dterceros2Ddescuentos->CurrentMode == "copy" || $subcategoria2Dterceros2Ddescuentos->CurrentMode == "edit") {
		$subcategoria2Dterceros2Ddescuentos_grid->RowIndex = '$rowindex$';
		$subcategoria2Dterceros2Ddescuentos_grid->LoadDefaultValues();

		// Set row properties
		$subcategoria2Dterceros2Ddescuentos->ResetAttrs();
		$subcategoria2Dterceros2Ddescuentos->RowAttrs = array_merge($subcategoria2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$subcategoria2Dterceros2Ddescuentos_grid->RowIndex, 'id'=>'r0_subcategoria2Dterceros2Ddescuentos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($subcategoria2Dterceros2Ddescuentos->RowAttrs["class"], "ewTemplate");
		$subcategoria2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$subcategoria2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$subcategoria2Dterceros2Ddescuentos_grid->RenderListOptions();
		$subcategoria2Dterceros2Ddescuentos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $subcategoria2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $subcategoria2Dterceros2Ddescuentos_grid->RowIndex);
?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->Visible) { // idSubcategoria ?>
		<td data-name="idSubcategoria">
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idSubcategoria->getSessionValue() <> "") { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria"<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idSubcategoria" class="form-group subcategoria2Dterceros2Ddescuentos_idSubcategoria">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idSubcategoria->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idSubcategoria" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idSubcategoria" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idSubcategoria->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<select data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_idTercero" class="form-group subcategoria2Dterceros2Ddescuentos_idTercero">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subcategoria2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento">
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_descuento" class="form-group subcategoria2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_subcategoria2Dterceros2Ddescuentos_descuento" class="form-group subcategoria2Dterceros2Ddescuentos_descuento">
<span<?php echo $subcategoria2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subcategoria2Dterceros2Ddescuentos->descuento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subcategoria2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($subcategoria2Dterceros2Ddescuentos->descuento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subcategoria2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $subcategoria2Dterceros2Ddescuentos_grid->RowCnt);
?>
<script type="text/javascript">
fsubcategoria2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $subcategoria2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "add" || $subcategoria2Dterceros2Ddescuentos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $subcategoria2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $subcategoria2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $subcategoria2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsubcategoria2Dterceros2Ddescuentosgrid">
</div>
<?php

// Close recordset
if ($subcategoria2Dterceros2Ddescuentos_grid->Recordset)
	$subcategoria2Dterceros2Ddescuentos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos_grid->TotalRecs == 0 && $subcategoria2Dterceros2Ddescuentos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subcategoria2Dterceros2Ddescuentos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($subcategoria2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">
fsubcategoria2Dterceros2Ddescuentosgrid.Init();
</script>
<?php } ?>
<?php
$subcategoria2Dterceros2Ddescuentos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$subcategoria2Dterceros2Ddescuentos_grid->Page_Terminate();
?>
