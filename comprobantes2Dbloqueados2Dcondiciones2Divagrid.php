<?php include_once "usuariosinfo.php" ?>
<?php

// Create page object
if (!isset($comprobantes2Dbloqueados2Dcondiciones2Diva_grid)) $comprobantes2Dbloqueados2Dcondiciones2Diva_grid = new ccomprobantes2Dbloqueados2Dcondiciones2Diva_grid();

// Page init
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Page_Init();

// Page main
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Page_Render();
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<script type="text/javascript">

// Form object
var fcomprobantes2Dbloqueados2Dcondiciones2Divagrid = new ew_Form("fcomprobantes2Dbloqueados2Dcondiciones2Divagrid", "grid");
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.FormKeyCountName = '<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName ?>';

// Validate form
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.Validate = function() {
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
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idCondicionIva", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idComprobanteBloqueado", false)) return false;
	return true;
}

// Form_CustomValidate event
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.ValidateRequired = true;
<?php } else { ?>
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.Lists["x_idCondicionIva"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"condiciones2Diva"};
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.Lists["x_idComprobanteBloqueado"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"comprobantes"};

// Form object for search
</script>
<?php } ?>
<?php
if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd") {
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "copy") {
		$bSelectLimit = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva->SelectRecordCount();
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRecordset($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec-1, $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs);
		} else {
			if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRecordset())
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->RecordCount();
		}
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec = 1;
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs;
	} else {
		$comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentFilter = "0=1";
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec = 1;
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva->GridAddRowCount;
	}
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs;
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs;
} else {
	$bSelectLimit = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs <= 0)
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva->SelectRecordCount();
	} else {
		if (!$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset && ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRecordset()))
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->RecordCount();
	}
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec = 1;
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRecordset($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec-1, $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->DisplayRecs);

	// Set no record found message
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "" && $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->setWarningMessage(ew_DeniedMsg());
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->SearchWhere == "0=101")
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderOtherOptions();
?>
<?php $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ShowPageHeader(); ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ShowMessage();
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs > 0 || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid comprobantes2Dbloqueados2Dcondiciones2Diva">
<div id="fcomprobantes2Dbloqueados2Dcondiciones2Divagrid" class="ewForm form-inline">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_comprobantes2Dbloqueados2Dcondiciones2Diva" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_comprobantes2Dbloqueados2Dcondiciones2Divagrid" class="table ewTable">
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderListOptions();

// Render list options (header, left)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("header", "left");
?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva) == "") { ?>
		<th data-name="idCondicionIva"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCondicionIva"><div><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->SortUrl($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado) == "") { ?>
		<th data-name="idComprobanteBloqueado"><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado"><div class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idComprobanteBloqueado"><div><div id="elh_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec = 1;
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName) && ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridedit" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "F")) {
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->KeyCount = $objForm->GetValue($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName);
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec + $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->KeyCount - 1;
	}
}
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RecCnt = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec - 1;
if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset && !$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->EOF) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->MoveFirst();
	$bSelectLimit = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->UseSelectLimit;
	if (!$bSelectLimit && $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec > 1)
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->Move($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec - 1);
} elseif (!$comprobantes2Dbloqueados2Dcondiciones2Diva->AllowAddDeleteRow && $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec == 0) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec = $comprobantes2Dbloqueados2Dcondiciones2Diva->GridAddRowCount;
}

// Initialize aggregate
$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_AGGREGATEINIT;
$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderRow();
if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd")
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex = 0;
if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridedit")
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex = 0;
while ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RecCnt < $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StopRec) {
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RecCnt++;
	if (intval($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RecCnt) >= intval($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRec)) {
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt++;
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridedit" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "F") {
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex++;
			$objForm->Index = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex;
			if ($objForm->HasValue($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormActionName))
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction = strval($objForm->GetValue($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormActionName));
			elseif ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd")
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction = "insert";
			else
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction = "";
		}

		// Set up key count
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->KeyCount = $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex;

		// Init row class and style
		$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
		$comprobantes2Dbloqueados2Dcondiciones2Diva->CssClass = "";
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd") {
			if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "copy") {
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRowValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset); // Load row values
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->SetRecordKey($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowOldKey, $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset); // Set old record key
			} else {
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadDefaultValues(); // Load default values
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadRowValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset); // Load row values
		}
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd") // Grid add
			$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_ADD; // Render add
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridadd" && $comprobantes2Dbloqueados2Dcondiciones2Diva->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RestoreCurrentRowFormValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex); // Restore form values
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridedit") { // Grid edit
			if ($comprobantes2Dbloqueados2Dcondiciones2Diva->EventCancelled) {
				$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RestoreCurrentRowFormValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex); // Restore form values
			}
			if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction == "insert")
				$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "gridedit" && ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT || $comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_ADD) && $comprobantes2Dbloqueados2Dcondiciones2Diva->EventCancelled) // Update failed
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RestoreCurrentRowFormValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex); // Restore form values
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT) // Edit row
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->EditRowCnt++;
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "F") // Confirm row
			$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RestoreCurrentRowFormValues($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs = array_merge($comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs, array('data-rowindex'=>$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt, 'id'=>'r' . $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt . '_comprobantes2Dbloqueados2Dcondiciones2Diva', 'data-rowtype'=>$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType));

		// Render row
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderRow();

		// Render list options
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction <> "delete" && $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction <> "insertdelete" && !($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowAction == "insert" && $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "F" && $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->EmptyRow())) {
?>
	<tr<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttributes() ?>>
<?php

// Render list options (body, left)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("body", "left", $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt);
?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
		<td data-name="idCondicionIva"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CellAttributes() ?>>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSessionValue() <> "") { ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSessionValue() <> "") { ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FormValue) ?>">
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->OldValue) ?>">
<?php } ?>
<a id="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->PageObjName . "_row_" . $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_id" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->id->CurrentValue) ?>">
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_id" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->id->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "edit") { ?>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_id" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
		<td data-name="idComprobanteBloqueado"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->CellAttributes() ?>>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->OldValue) ?>">
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt ?>_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ViewAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FormValue) ?>">
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("body", "right", $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt);
?>
	</tr>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_ADD || $comprobantes2Dbloqueados2Dcondiciones2Diva->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.UpdateOpts(<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "gridadd" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "copy")
		if (!$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->EOF) $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->MoveNext();
}
?>
<?php
	if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "add" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "copy" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "edit") {
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex = '$rowindex$';
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->LoadDefaultValues();

		// Set row properties
		$comprobantes2Dbloqueados2Dcondiciones2Diva->ResetAttrs();
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs = array_merge($comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs, array('data-rowindex'=>$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex, 'id'=>'r0_comprobantes2Dbloqueados2Dcondiciones2Diva', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttrs["class"], "ewTemplate");
		$comprobantes2Dbloqueados2Dcondiciones2Diva->RowType = EW_ROWTYPE_ADD;

		// Render row
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderRow();

		// Render list options
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RenderListOptions();
		$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->StartRowCnt = 0;
?>
	<tr<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->RowAttributes() ?>>
<?php

// Render list options (body, left)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("body", "left", $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex);
?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->Visible) { // idCondicionIva ?>
		<td data-name="idCondicionIva">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "F") { ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->getSessionValue() <> "") { ?>
<span id="el$rowindex$_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idCondicionIva">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idCondicionIva" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idCondicionIva" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idCondicionIva->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->Visible) { // idComprobanteBloqueado ?>
		<td data-name="idComprobanteBloqueado">
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction <> "F") { ?>
<span id="el$rowindex$_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<select data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" data-value-separator="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado"<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->EditAttributes() ?>>
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->SelectOptionListHtml("x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado") ?>
</select>
<input type="hidden" name="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="s_x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado" class="form-group comprobantes2Dbloqueados2Dcondiciones2Diva_idComprobanteBloqueado">
<span<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" name="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="x<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="comprobantes2Dbloqueados2Dcondiciones2Diva" data-field="x_idComprobanteBloqueado" name="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" id="o<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>_idComprobanteBloqueado" value="<?php echo ew_HtmlEncode($comprobantes2Dbloqueados2Dcondiciones2Diva->idComprobanteBloqueado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ListOptions->Render("body", "right", $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowCnt);
?>
<script type="text/javascript">
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.UpdateOpts(<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "add" || $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName ?>" id="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName ?>" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->KeyCount ?>">
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName ?>" id="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->FormKeyCountName ?>" value="<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->KeyCount ?>">
<?php echo $comprobantes2Dbloqueados2Dcondiciones2Diva_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcomprobantes2Dbloqueados2Dcondiciones2Divagrid">
</div>
<?php

// Close recordset
if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset)
	$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->TotalRecs == 0 && $comprobantes2Dbloqueados2Dcondiciones2Diva->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($comprobantes2Dbloqueados2Dcondiciones2Diva_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($comprobantes2Dbloqueados2Dcondiciones2Diva->Export == "") { ?>
<script type="text/javascript">
fcomprobantes2Dbloqueados2Dcondiciones2Divagrid.Init();
</script>
<?php } ?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$comprobantes2Dbloqueados2Dcondiciones2Diva_grid->Page_Terminate();
?>
