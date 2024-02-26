<?php

// denominacion
// denominacionCorta

?>
<?php if ($condiciones2Diva->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $condiciones2Diva->TableCaption() ?></h4> -->
<table id="tbl_condiciones2Divamaster" class="table table-bordered table-striped ewViewTable">
<?php echo $condiciones2Diva->TableCustomInnerHtml ?>
	<tbody>
<?php if ($condiciones2Diva->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td><?php echo $condiciones2Diva->denominacion->FldCaption() ?></td>
			<td<?php echo $condiciones2Diva->denominacion->CellAttributes() ?>>
<span id="el_condiciones2Diva_denominacion">
<span<?php echo $condiciones2Diva->denominacion->ViewAttributes() ?>>
<?php echo $condiciones2Diva->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($condiciones2Diva->denominacionCorta->Visible) { // denominacionCorta ?>
		<tr id="r_denominacionCorta">
			<td><?php echo $condiciones2Diva->denominacionCorta->FldCaption() ?></td>
			<td<?php echo $condiciones2Diva->denominacionCorta->CellAttributes() ?>>
<span id="el_condiciones2Diva_denominacionCorta">
<span<?php echo $condiciones2Diva->denominacionCorta->ViewAttributes() ?>>
<?php echo $condiciones2Diva->denominacionCorta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
