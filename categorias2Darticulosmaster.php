<?php

// denominacion
?>
<?php if ($categorias2Darticulos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $categorias2Darticulos->TableCaption() ?></h4> -->
<table id="tbl_categorias2Darticulosmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $categorias2Darticulos->TableCustomInnerHtml ?>
	<tbody>
<?php if ($categorias2Darticulos->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td><?php echo $categorias2Darticulos->denominacion->FldCaption() ?></td>
			<td<?php echo $categorias2Darticulos->denominacion->CellAttributes() ?>>
<span id="el_categorias2Darticulos_denominacion">
<span<?php echo $categorias2Darticulos->denominacion->ViewAttributes() ?>>
<?php echo $categorias2Darticulos->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
