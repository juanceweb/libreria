<?php

// idTipoTercero
// denominacion
// direccion
// documento
// idTransporte
// limiteDescubierto
// codigoPostal
// codigoPostalFiscal
// condicionVenta

?>
<?php if ($terceros->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $terceros->TableCaption() ?></h4> -->
<table id="tbl_tercerosmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $terceros->TableCustomInnerHtml ?>
	<tbody>
<?php if ($terceros->idTipoTercero->Visible) { // idTipoTercero ?>
		<tr id="r_idTipoTercero">
			<td><?php echo $terceros->idTipoTercero->FldCaption() ?></td>
			<td<?php echo $terceros->idTipoTercero->CellAttributes() ?>>
<span id="el_terceros_idTipoTercero">
<span<?php echo $terceros->idTipoTercero->ViewAttributes() ?>>
<?php echo $terceros->idTipoTercero->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td><?php echo $terceros->denominacion->FldCaption() ?></td>
			<td<?php echo $terceros->denominacion->CellAttributes() ?>>
<span id="el_terceros_denominacion">
<span<?php echo $terceros->denominacion->ViewAttributes() ?>>
<?php echo $terceros->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->direccion->Visible) { // direccion ?>
		<tr id="r_direccion">
			<td><?php echo $terceros->direccion->FldCaption() ?></td>
			<td<?php echo $terceros->direccion->CellAttributes() ?>>
<span id="el_terceros_direccion">
<span<?php echo $terceros->direccion->ViewAttributes() ?>>
<?php echo $terceros->direccion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->documento->Visible) { // documento ?>
		<tr id="r_documento">
			<td><?php echo $terceros->documento->FldCaption() ?></td>
			<td<?php echo $terceros->documento->CellAttributes() ?>>
<span id="el_terceros_documento">
<span<?php echo $terceros->documento->ViewAttributes() ?>>
<?php echo $terceros->documento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->idTransporte->Visible) { // idTransporte ?>
		<tr id="r_idTransporte">
			<td><?php echo $terceros->idTransporte->FldCaption() ?></td>
			<td<?php echo $terceros->idTransporte->CellAttributes() ?>>
<span id="el_terceros_idTransporte">
<span<?php echo $terceros->idTransporte->ViewAttributes() ?>>
<?php echo $terceros->idTransporte->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->limiteDescubierto->Visible) { // limiteDescubierto ?>
		<tr id="r_limiteDescubierto">
			<td><?php echo $terceros->limiteDescubierto->FldCaption() ?></td>
			<td<?php echo $terceros->limiteDescubierto->CellAttributes() ?>>
<span id="el_terceros_limiteDescubierto">
<span<?php echo $terceros->limiteDescubierto->ViewAttributes() ?>>
<?php echo $terceros->limiteDescubierto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->codigoPostal->Visible) { // codigoPostal ?>
		<tr id="r_codigoPostal">
			<td><?php echo $terceros->codigoPostal->FldCaption() ?></td>
			<td<?php echo $terceros->codigoPostal->CellAttributes() ?>>
<span id="el_terceros_codigoPostal">
<span<?php echo $terceros->codigoPostal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->codigoPostalFiscal->Visible) { // codigoPostalFiscal ?>
		<tr id="r_codigoPostalFiscal">
			<td><?php echo $terceros->codigoPostalFiscal->FldCaption() ?></td>
			<td<?php echo $terceros->codigoPostalFiscal->CellAttributes() ?>>
<span id="el_terceros_codigoPostalFiscal">
<span<?php echo $terceros->codigoPostalFiscal->ViewAttributes() ?>>
<?php echo $terceros->codigoPostalFiscal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($terceros->condicionVenta->Visible) { // condicionVenta ?>
		<tr id="r_condicionVenta">
			<td><?php echo $terceros->condicionVenta->FldCaption() ?></td>
			<td<?php echo $terceros->condicionVenta->CellAttributes() ?>>
<span id="el_terceros_condicionVenta">
<span<?php echo $terceros->condicionVenta->ViewAttributes() ?>>
<?php echo $terceros->condicionVenta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
