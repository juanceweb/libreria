<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($categorias2Dterceros2Ddescuentos_grid)) $categorias2Dterceros2Ddescuentos_grid = new ccategorias2Dterceros2Ddescuentos_grid();

// Page init
$categorias2Dterceros2Ddescuentos_grid->Page_Init();

// Page main
$categorias2Dterceros2Ddescuentos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$categorias2Dterceros2Ddescuentos_grid->Page_Render();
?>
<?php if ($categorias2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">

// Form object
var fcategorias2Dterceros2Ddescuentosgrid = new ew_Form("fcategorias2Dterceros2Ddescuentosgrid", "grid");
fcategorias2Dterceros2Ddescuentosgrid.FormKeyCountName = '<?php echo $categorias2Dterceros2Ddescuentos_grid->FormKeyCountName ?>';

// Validate form
fcategorias2Dterceros2Ddescuentosgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($categorias2Dterceros2Ddescuentos->descuento->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fcategorias2Dterceros2Ddescuentosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idCategoria", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idTercero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "descuento", false)) return false;
	return true;
}

// Form_CustomValidate event
fcategorias2Dterceros2Ddescuentosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategorias2Dterceros2Ddescuentosgrid.ValidateRequired = true;
<?php } else { ?>
fcategorias2Dterceros2Ddescuentosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcategorias2Dterceros2Ddescuentosgrid.Lists["x_idCategoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categorias2Darticulos"};
fcategorias2Dterceros2Ddescuentosgrid.Lists["x_idTercero"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"terceros"};

// Form object for search
</script>
<?php } ?>
<?php
if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
	if ($categorias2Dterceros2Ddescuentos->CurrentMode == "copy") {
		$bSelectLimit = $categorias2Dterceros2Ddescuentos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$categorias2Dterceros2Ddescuentos_grid->TotalRecs = $categorias2Dterceros2Ddescuentos->SelectRecordCount();
			$categorias2Dterceros2Ddescuentos_grid->Recordset = $categorias2Dterceros2Ddescuentos_grid->LoadRecordset($categorias2Dterceros2Ddescuentos_grid->StartRec-1, $categorias2Dterceros2Ddescuentos_grid->DisplayRecs);
		} else {
			if ($categorias2Dterceros2Ddescuentos_grid->Recordset = $categorias2Dterceros2Ddescuentos_grid->LoadRecordset())
				$categorias2Dterceros2Ddescuentos_grid->TotalRecs = $categorias2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
		}
		$categorias2Dterceros2Ddescuentos_grid->StartRec = 1;
		$categorias2Dterceros2Ddescuentos_grid->DisplayRecs = $categorias2Dterceros2Ddescuentos_grid->TotalRecs;
	} else {
		$categorias2Dterceros2Ddescuentos->CurrentFilter = "0=1";
		$categorias2Dterceros2Ddescuentos_grid->StartRec = 1;
		$categorias2Dterceros2Ddescuentos_grid->DisplayRecs = $categorias2Dterceros2Ddescuentos->GridAddRowCount;
	}
	$categorias2Dterceros2Ddescuentos_grid->TotalRecs = $categorias2Dterceros2Ddescuentos_grid->DisplayRecs;
	$categorias2Dterceros2Ddescuentos_grid->StopRec = $categorias2Dterceros2Ddescuentos_grid->DisplayRecs;
} else {
	$bSelectLimit = $categorias2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($categorias2Dterceros2Ddescuentos_grid->TotalRecs <= 0)
			$categorias2Dterceros2Ddescuentos_grid->TotalRecs = $categorias2Dterceros2Ddescuentos->SelectRecordCount();
	} else {
		if (!$categorias2Dterceros2Ddescuentos_grid->Recordset && ($categorias2Dterceros2Ddescuentos_grid->Recordset = $categorias2Dterceros2Ddescuentos_grid->LoadRecordset()))
			$categorias2Dterceros2Ddescuentos_grid->TotalRecs = $categorias2Dterceros2Ddescuentos_grid->Recordset->RecordCount();
	}
	$categorias2Dterceros2Ddescuentos_grid->StartRec = 1;
	$categorias2Dterceros2Ddescuentos_grid->DisplayRecs = $categorias2Dterceros2Ddescuentos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$categorias2Dterceros2Ddescuentos_grid->Recordset = $categorias2Dterceros2Ddescuentos_grid->LoadRecordset($categorias2Dterceros2Ddescuentos_grid->StartRec-1, $categorias2Dterceros2Ddescuentos_grid->DisplayRecs);

	// Set no record found message
	if ($categorias2Dterceros2Ddescuentos->CurrentAction == "" && $categorias2Dterceros2Ddescuentos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$categorias2Dterceros2Ddescuentos_grid->setWarningMessage(ew_DeniedMsg());
		if ($categorias2Dterceros2Ddescuentos_grid->SearchWhere == "0=101")
			$categorias2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$categorias2Dterceros2Ddescuentos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$categorias2Dterceros2Ddescuentos_grid->RenderOtherOptions();
?>
<?php $categorias2Dterceros2Ddescuentos_grid->ShowPageHeader(); ?>
<?php
$categorias2Dterceros2Ddescuentos_grid->ShowMessage();
?>
<?php if ($categorias2Dterceros2Ddescuentos_grid->TotalRecs > 0 || $categorias2Dterceros2Ddescuentos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid categorias2Dterceros2Ddescuentos">
<div id="fcategorias2Dterceros2Ddescuentosgrid" class="ewForm form-inline">
<?php if ($categorias2Dterceros2Ddescuentos_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($categorias2Dterceros2Ddescuentos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_categorias2Dterceros2Ddescuentos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_categorias2Dterceros2Ddescuentosgrid" class="table ewTable">
<?php echo $categorias2Dterceros2Ddescuentos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$categorias2Dterceros2Ddescuentos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$categorias2Dterceros2Ddescuentos_grid->RenderListOptions();

// Render list options (header, left)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "left");
?>
<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->Visible) { // idCategoria ?>
	<?php if ($categorias2Dterceros2Ddescuentos->SortUrl($categorias2Dterceros2Ddescuentos->idCategoria) == "") { ?>
		<th data-name="idCategoria"><div id="elh_categorias2Dterceros2Ddescuentos_idCategoria" class="categorias2Dterceros2Ddescuentos_idCategoria"><div class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCategoria"><div><div id="elh_categorias2Dterceros2Ddescuentos_idCategoria" class="categorias2Dterceros2Ddescuentos_idCategoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($categorias2Dterceros2Ddescuentos->idCategoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($categorias2Dterceros2Ddescuentos->idCategoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($categorias2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
	<?php if ($categorias2Dterceros2Ddescuentos->SortUrl($categorias2Dterceros2Ddescuentos->idTercero) == "") { ?>
		<th data-name="idTercero"><div id="elh_categorias2Dterceros2Ddescuentos_idTercero" class="categorias2Dterceros2Ddescuentos_idTercero"><div class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idTercero"><div><div id="elh_categorias2Dterceros2Ddescuentos_idTercero" class="categorias2Dterceros2Ddescuentos_idTercero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($categorias2Dterceros2Ddescuentos->idTercero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($categorias2Dterceros2Ddescuentos->idTercero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($categorias2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
	<?php if ($categorias2Dterceros2Ddescuentos->SortUrl($categorias2Dterceros2Ddescuentos->descuento) == "") { ?>
		<th data-name="descuento"><div id="elh_categorias2Dterceros2Ddescuentos_descuento" class="categorias2Dterceros2Ddescuentos_descuento"><div class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->descuento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descuento"><div><div id="elh_categorias2Dterceros2Ddescuentos_descuento" class="categorias2Dterceros2Ddescuentos_descuento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $categorias2Dterceros2Ddescuentos->descuento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($categorias2Dterceros2Ddescuentos->descuento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($categorias2Dterceros2Ddescuentos->descuento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$categorias2Dterceros2Ddescuentos_grid->StartRec = 1;
$categorias2Dterceros2Ddescuentos_grid->StopRec = $categorias2Dterceros2Ddescuentos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($categorias2Dterceros2Ddescuentos_grid->FormKeyCountName) && ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $categorias2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $categorias2Dterceros2Ddescuentos->CurrentAction == "F")) {
		$categorias2Dterceros2Ddescuentos_grid->KeyCount = $objForm->GetValue($categorias2Dterceros2Ddescuentos_grid->FormKeyCountName);
		$categorias2Dterceros2Ddescuentos_grid->StopRec = $categorias2Dterceros2Ddescuentos_grid->StartRec + $categorias2Dterceros2Ddescuentos_grid->KeyCount - 1;
	}
}
$categorias2Dterceros2Ddescuentos_grid->RecCnt = $categorias2Dterceros2Ddescuentos_grid->StartRec - 1;
if ($categorias2Dterceros2Ddescuentos_grid->Recordset && !$categorias2Dterceros2Ddescuentos_grid->Recordset->EOF) {
	$categorias2Dterceros2Ddescuentos_grid->Recordset->MoveFirst();
	$bSelectLimit = $categorias2Dterceros2Ddescuentos_grid->UseSelectLimit;
	if (!$bSelectLimit && $categorias2Dterceros2Ddescuentos_grid->StartRec > 1)
		$categorias2Dterceros2Ddescuentos_grid->Recordset->Move($categorias2Dterceros2Ddescuentos_grid->StartRec - 1);
} elseif (!$categorias2Dterceros2Ddescuentos->AllowAddDeleteRow && $categorias2Dterceros2Ddescuentos_grid->StopRec == 0) {
	$categorias2Dterceros2Ddescuentos_grid->StopRec = $categorias2Dterceros2Ddescuentos->GridAddRowCount;
}

// Initialize aggregate
$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$categorias2Dterceros2Ddescuentos->ResetAttrs();
$categorias2Dterceros2Ddescuentos_grid->RenderRow();
if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd")
	$categorias2Dterceros2Ddescuentos_grid->RowIndex = 0;
if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridedit")
	$categorias2Dterceros2Ddescuentos_grid->RowIndex = 0;
while ($categorias2Dterceros2Ddescuentos_grid->RecCnt < $categorias2Dterceros2Ddescuentos_grid->StopRec) {
	$categorias2Dterceros2Ddescuentos_grid->RecCnt++;
	if (intval($categorias2Dterceros2Ddescuentos_grid->RecCnt) >= intval($categorias2Dterceros2Ddescuentos_grid->StartRec)) {
		$categorias2Dterceros2Ddescuentos_grid->RowCnt++;
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd" || $categorias2Dterceros2Ddescuentos->CurrentAction == "gridedit" || $categorias2Dterceros2Ddescuentos->CurrentAction == "F") {
			$categorias2Dterceros2Ddescuentos_grid->RowIndex++;
			$objForm->Index = $categorias2Dterceros2Ddescuentos_grid->RowIndex;
			if ($objForm->HasValue($categorias2Dterceros2Ddescuentos_grid->FormActionName))
				$categorias2Dterceros2Ddescuentos_grid->RowAction = strval($objForm->GetValue($categorias2Dterceros2Ddescuentos_grid->FormActionName));
			elseif ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd")
				$categorias2Dterceros2Ddescuentos_grid->RowAction = "insert";
			else
				$categorias2Dterceros2Ddescuentos_grid->RowAction = "";
		}

		// Set up key count
		$categorias2Dterceros2Ddescuentos_grid->KeyCount = $categorias2Dterceros2Ddescuentos_grid->RowIndex;

		// Init row class and style
		$categorias2Dterceros2Ddescuentos->ResetAttrs();
		$categorias2Dterceros2Ddescuentos->CssClass = "";
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd") {
			if ($categorias2Dterceros2Ddescuentos->CurrentMode == "copy") {
				$categorias2Dterceros2Ddescuentos_grid->LoadRowValues($categorias2Dterceros2Ddescuentos_grid->Recordset); // Load row values
				$categorias2Dterceros2Ddescuentos_grid->SetRecordKey($categorias2Dterceros2Ddescuentos_grid->RowOldKey, $categorias2Dterceros2Ddescuentos_grid->Recordset); // Set old record key
			} else {
				$categorias2Dterceros2Ddescuentos_grid->LoadDefaultValues(); // Load default values
				$categorias2Dterceros2Ddescuentos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$categorias2Dterceros2Ddescuentos_grid->LoadRowValues($categorias2Dterceros2Ddescuentos_grid->Recordset); // Load row values
		}
		$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd") // Grid add
			$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridadd" && $categorias2Dterceros2Ddescuentos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$categorias2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($categorias2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridedit") { // Grid edit
			if ($categorias2Dterceros2Ddescuentos->EventCancelled) {
				$categorias2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($categorias2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
			}
			if ($categorias2Dterceros2Ddescuentos_grid->RowAction == "insert")
				$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "gridedit" && ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) && $categorias2Dterceros2Ddescuentos->EventCancelled) // Update failed
			$categorias2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($categorias2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values
		if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$categorias2Dterceros2Ddescuentos_grid->EditRowCnt++;
		if ($categorias2Dterceros2Ddescuentos->CurrentAction == "F") // Confirm row
			$categorias2Dterceros2Ddescuentos_grid->RestoreCurrentRowFormValues($categorias2Dterceros2Ddescuentos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$categorias2Dterceros2Ddescuentos->RowAttrs = array_merge($categorias2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$categorias2Dterceros2Ddescuentos_grid->RowCnt, 'id'=>'r' . $categorias2Dterceros2Ddescuentos_grid->RowCnt . '_categorias2Dterceros2Ddescuentos', 'data-rowtype'=>$categorias2Dterceros2Ddescuentos->RowType));

		// Render row
		$categorias2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$categorias2Dterceros2Ddescuentos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($categorias2Dterceros2Ddescuentos_grid->RowAction <> "delete" && $categorias2Dterceros2Ddescuentos_grid->RowAction <> "insertdelete" && !($categorias2Dterceros2Ddescuentos_grid->RowAction == "insert" && $categorias2Dterceros2Ddescuentos->CurrentAction == "F" && $categorias2Dterceros2Ddescuentos_grid->EmptyRow())) {
?>
	<tr<?php echo $categorias2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $categorias2Dterceros2Ddescuentos_grid->RowCnt);
?>
	<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->Visible) { // idCategoria ?>
		<td data-name="idCategoria"<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->CellAttributes() ?>>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->getSessionValue() <> "") { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<span<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria"<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->OldValue) ?>">
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->getSessionValue() <> "") { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<span<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria"<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idCategoria" class="categorias2Dterceros2Ddescuentos_idCategoria">
<span<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->FormValue) ?>">
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->OldValue) ?>">
<?php } ?>
<a id="<?php echo $categorias2Dterceros2Ddescuentos_grid->PageObjName . "_row_" . $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_id" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->id->OldValue) ?>">
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT || $categorias2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_id" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($categorias2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero"<?php echo $categorias2Dterceros2Ddescuentos->idTercero->CellAttributes() ?>>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($categorias2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<span<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $categorias2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($categorias2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<span<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $categorias2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_idTercero" class="categorias2Dterceros2Ddescuentos_idTercero">
<span<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($categorias2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento"<?php echo $categorias2Dterceros2Ddescuentos->descuento->CellAttributes() ?>>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_descuento" class="form-group categorias2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_descuento" class="form-group categorias2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $categorias2Dterceros2Ddescuentos_grid->RowCnt ?>_categorias2Dterceros2Ddescuentos_descuento" class="categorias2Dterceros2Ddescuentos_descuento">
<span<?php echo $categorias2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->descuento->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $categorias2Dterceros2Ddescuentos_grid->RowCnt);
?>
	</tr>
<?php if ($categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_ADD || $categorias2Dterceros2Ddescuentos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcategorias2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($categorias2Dterceros2Ddescuentos->CurrentAction <> "gridadd" || $categorias2Dterceros2Ddescuentos->CurrentMode == "copy")
		if (!$categorias2Dterceros2Ddescuentos_grid->Recordset->EOF) $categorias2Dterceros2Ddescuentos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($categorias2Dterceros2Ddescuentos->CurrentMode == "add" || $categorias2Dterceros2Ddescuentos->CurrentMode == "copy" || $categorias2Dterceros2Ddescuentos->CurrentMode == "edit") {
		$categorias2Dterceros2Ddescuentos_grid->RowIndex = '$rowindex$';
		$categorias2Dterceros2Ddescuentos_grid->LoadDefaultValues();

		// Set row properties
		$categorias2Dterceros2Ddescuentos->ResetAttrs();
		$categorias2Dterceros2Ddescuentos->RowAttrs = array_merge($categorias2Dterceros2Ddescuentos->RowAttrs, array('data-rowindex'=>$categorias2Dterceros2Ddescuentos_grid->RowIndex, 'id'=>'r0_categorias2Dterceros2Ddescuentos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($categorias2Dterceros2Ddescuentos->RowAttrs["class"], "ewTemplate");
		$categorias2Dterceros2Ddescuentos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$categorias2Dterceros2Ddescuentos_grid->RenderRow();

		// Render list options
		$categorias2Dterceros2Ddescuentos_grid->RenderListOptions();
		$categorias2Dterceros2Ddescuentos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $categorias2Dterceros2Ddescuentos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "left", $categorias2Dterceros2Ddescuentos_grid->RowIndex);
?>
	<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->Visible) { // idCategoria ?>
		<td data-name="idCategoria">
<?php if ($categorias2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($categorias2Dterceros2Ddescuentos->idCategoria->getSessionValue() <> "") { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<span<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria"<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idCategoria" class="form-group categorias2Dterceros2Ddescuentos_idCategoria">
<span<?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idCategoria->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idCategoria" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idCategoria" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idCategoria->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($categorias2Dterceros2Ddescuentos->idTercero->Visible) { // idTercero ?>
		<td data-name="idTercero">
<?php if ($categorias2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<?php if ($categorias2Dterceros2Ddescuentos->idTercero->getSessionValue() <> "") { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<span<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<select data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" data-value-separator="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero"<?php echo $categorias2Dterceros2Ddescuentos->idTercero->EditAttributes() ?>>
<?php echo $categorias2Dterceros2Ddescuentos->idTercero->SelectOptionListHtml("x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero") ?>
</select>
<input type="hidden" name="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="s_x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo $categorias2Dterceros2Ddescuentos->idTercero->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_idTercero" class="form-group categorias2Dterceros2Ddescuentos_idTercero">
<span<?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->idTercero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_idTercero" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_idTercero" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->idTercero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($categorias2Dterceros2Ddescuentos->descuento->Visible) { // descuento ?>
		<td data-name="descuento">
<?php if ($categorias2Dterceros2Ddescuentos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_descuento" class="form-group categorias2Dterceros2Ddescuentos_descuento">
<input type="text" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" size="30" placeholder="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->getPlaceHolder()) ?>" value="<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditValue ?>"<?php echo $categorias2Dterceros2Ddescuentos->descuento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_categorias2Dterceros2Ddescuentos_descuento" class="form-group categorias2Dterceros2Ddescuentos_descuento">
<span<?php echo $categorias2Dterceros2Ddescuentos->descuento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias2Dterceros2Ddescuentos->descuento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="x<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="categorias2Dterceros2Ddescuentos" data-field="x_descuento" name="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" id="o<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>_descuento" value="<?php echo ew_HtmlEncode($categorias2Dterceros2Ddescuentos->descuento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$categorias2Dterceros2Ddescuentos_grid->ListOptions->Render("body", "right", $categorias2Dterceros2Ddescuentos_grid->RowCnt);
?>
<script type="text/javascript">
fcategorias2Dterceros2Ddescuentosgrid.UpdateOpts(<?php echo $categorias2Dterceros2Ddescuentos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($categorias2Dterceros2Ddescuentos->CurrentMode == "add" || $categorias2Dterceros2Ddescuentos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $categorias2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $categorias2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $categorias2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $categorias2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $categorias2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" id="<?php echo $categorias2Dterceros2Ddescuentos_grid->FormKeyCountName ?>" value="<?php echo $categorias2Dterceros2Ddescuentos_grid->KeyCount ?>">
<?php echo $categorias2Dterceros2Ddescuentos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcategorias2Dterceros2Ddescuentosgrid">
</div>
<?php

// Close recordset
if ($categorias2Dterceros2Ddescuentos_grid->Recordset)
	$categorias2Dterceros2Ddescuentos_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos_grid->TotalRecs == 0 && $categorias2Dterceros2Ddescuentos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($categorias2Dterceros2Ddescuentos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($categorias2Dterceros2Ddescuentos->Export == "") { ?>
<script type="text/javascript">
fcategorias2Dterceros2Ddescuentosgrid.Init();
</script>
<?php } ?>
<?php
$categorias2Dterceros2Ddescuentos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$categorias2Dterceros2Ddescuentos_grid->Page_Terminate();
?>
