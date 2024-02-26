<?php

// denominacion
?>
<?php if ($subcategorias2Darticulos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $subcategorias2Darticulos->TableCaption() ?></h4> -->
<table id="tbl_subcategorias2Darticulosmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $subcategorias2Darticulos->TableCustomInnerHtml ?>
	<tbody>
<?php if ($subcategorias2Darticulos->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td><?php echo $subcategorias2Darticulos->denominacion->FldCaption() ?></td>
			<td<?php echo $subcategorias2Darticulos->denominacion->CellAttributes() ?>>
<span id="el_subcategorias2Darticulos_denominacion">
<span<?php echo $subcategorias2Darticulos->denominacion->ViewAttributes() ?>>
<?php echo $subcategorias2Darticulos->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
