<?php

// id
// denominacionExterna
// denominacionInterna
// idAlicuotaIva
// idCategoria
// idSubcateogoria
// idRubro
// idMarca
// idPrecioCompra
// proveedor
// calculoPrecio
// rentabilidad
// precioVenta

?>
<?php if ($articulos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $articulos->TableCaption() ?></h4> -->
<table id="tbl_articulosmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $articulos->TableCustomInnerHtml ?>
	<tbody>
<?php if ($articulos->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $articulos->id->FldCaption() ?></td>
			<td<?php echo $articulos->id->CellAttributes() ?>>
<span id="el_articulos_id">
<span<?php echo $articulos->id->ViewAttributes() ?>>
<?php echo $articulos->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->denominacionExterna->Visible) { // denominacionExterna ?>
		<tr id="r_denominacionExterna">
			<td><?php echo $articulos->denominacionExterna->FldCaption() ?></td>
			<td<?php echo $articulos->denominacionExterna->CellAttributes() ?>>
<span id="el_articulos_denominacionExterna">
<span<?php echo $articulos->denominacionExterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionExterna->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->denominacionInterna->Visible) { // denominacionInterna ?>
		<tr id="r_denominacionInterna">
			<td><?php echo $articulos->denominacionInterna->FldCaption() ?></td>
			<td<?php echo $articulos->denominacionInterna->CellAttributes() ?>>
<span id="el_articulos_denominacionInterna">
<span<?php echo $articulos->denominacionInterna->ViewAttributes() ?>>
<?php echo $articulos->denominacionInterna->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idAlicuotaIva->Visible) { // idAlicuotaIva ?>
		<tr id="r_idAlicuotaIva">
			<td><?php echo $articulos->idAlicuotaIva->FldCaption() ?></td>
			<td<?php echo $articulos->idAlicuotaIva->CellAttributes() ?>>
<span id="el_articulos_idAlicuotaIva">
<span<?php echo $articulos->idAlicuotaIva->ViewAttributes() ?>>
<?php echo $articulos->idAlicuotaIva->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idCategoria->Visible) { // idCategoria ?>
		<tr id="r_idCategoria">
			<td><?php echo $articulos->idCategoria->FldCaption() ?></td>
			<td<?php echo $articulos->idCategoria->CellAttributes() ?>>
<span id="el_articulos_idCategoria">
<span<?php echo $articulos->idCategoria->ViewAttributes() ?>>
<?php echo $articulos->idCategoria->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idSubcateogoria->Visible) { // idSubcateogoria ?>
		<tr id="r_idSubcateogoria">
			<td><?php echo $articulos->idSubcateogoria->FldCaption() ?></td>
			<td<?php echo $articulos->idSubcateogoria->CellAttributes() ?>>
<span id="el_articulos_idSubcateogoria">
<span<?php echo $articulos->idSubcateogoria->ViewAttributes() ?>>
<?php echo $articulos->idSubcateogoria->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idRubro->Visible) { // idRubro ?>
		<tr id="r_idRubro">
			<td><?php echo $articulos->idRubro->FldCaption() ?></td>
			<td<?php echo $articulos->idRubro->CellAttributes() ?>>
<span id="el_articulos_idRubro">
<span<?php echo $articulos->idRubro->ViewAttributes() ?>>
<?php echo $articulos->idRubro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idMarca->Visible) { // idMarca ?>
		<tr id="r_idMarca">
			<td><?php echo $articulos->idMarca->FldCaption() ?></td>
			<td<?php echo $articulos->idMarca->CellAttributes() ?>>
<span id="el_articulos_idMarca">
<span<?php echo $articulos->idMarca->ViewAttributes() ?>>
<?php echo $articulos->idMarca->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->idPrecioCompra->Visible) { // idPrecioCompra ?>
		<tr id="r_idPrecioCompra">
			<td><?php echo $articulos->idPrecioCompra->FldCaption() ?></td>
			<td<?php echo $articulos->idPrecioCompra->CellAttributes() ?>>
<span id="el_articulos_idPrecioCompra">
<span<?php echo $articulos->idPrecioCompra->ViewAttributes() ?>>
<?php echo $articulos->idPrecioCompra->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->proveedor->Visible) { // proveedor ?>
		<tr id="r_proveedor">
			<td><?php echo $articulos->proveedor->FldCaption() ?></td>
			<td<?php echo $articulos->proveedor->CellAttributes() ?>>
<span id="el_articulos_proveedor">
<span<?php echo $articulos->proveedor->ViewAttributes() ?>>
<?php echo $articulos->proveedor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->calculoPrecio->Visible) { // calculoPrecio ?>
		<tr id="r_calculoPrecio">
			<td><?php echo $articulos->calculoPrecio->FldCaption() ?></td>
			<td<?php echo $articulos->calculoPrecio->CellAttributes() ?>>
<span id="el_articulos_calculoPrecio">
<span<?php echo $articulos->calculoPrecio->ViewAttributes() ?>>
<?php echo $articulos->calculoPrecio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->rentabilidad->Visible) { // rentabilidad ?>
		<tr id="r_rentabilidad">
			<td><?php echo $articulos->rentabilidad->FldCaption() ?></td>
			<td<?php echo $articulos->rentabilidad->CellAttributes() ?>>
<span id="el_articulos_rentabilidad">
<span<?php echo $articulos->rentabilidad->ViewAttributes() ?>>
<?php echo $articulos->rentabilidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($articulos->precioVenta->Visible) { // precioVenta ?>
		<tr id="r_precioVenta">
			<td><?php echo $articulos->precioVenta->FldCaption() ?></td>
			<td<?php echo $articulos->precioVenta->CellAttributes() ?>>
<span id="el_articulos_precioVenta">
<span<?php echo $articulos->precioVenta->ViewAttributes() ?>>
<?php echo $articulos->precioVenta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
