<?php

// denominacion
// denominacionCorta
// discriminaIVA
// seAutoriza
// letra
// activo
// ventaStock
// ventaStockReservadoVenta
// ventaStockReservadoCompra
// compraStock
// compraStockReservadoVenta
// compraStockReservadoCompra
// muestraPendientes
// comprobanteDefaultImportacion
// preimpreso
// cantidadRegistros
// limitarModo

?>
<?php if ($comprobantes->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $comprobantes->TableCaption() ?></h4> -->
<table id="tbl_comprobantesmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $comprobantes->TableCustomInnerHtml ?>
	<tbody>
<?php if ($comprobantes->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td><?php echo $comprobantes->denominacion->FldCaption() ?></td>
			<td<?php echo $comprobantes->denominacion->CellAttributes() ?>>
<span id="el_comprobantes_denominacion">
<span<?php echo $comprobantes->denominacion->ViewAttributes() ?>>
<?php echo $comprobantes->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->denominacionCorta->Visible) { // denominacionCorta ?>
		<tr id="r_denominacionCorta">
			<td><?php echo $comprobantes->denominacionCorta->FldCaption() ?></td>
			<td<?php echo $comprobantes->denominacionCorta->CellAttributes() ?>>
<span id="el_comprobantes_denominacionCorta">
<span<?php echo $comprobantes->denominacionCorta->ViewAttributes() ?>>
<?php echo $comprobantes->denominacionCorta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->discriminaIVA->Visible) { // discriminaIVA ?>
		<tr id="r_discriminaIVA">
			<td><?php echo $comprobantes->discriminaIVA->FldCaption() ?></td>
			<td<?php echo $comprobantes->discriminaIVA->CellAttributes() ?>>
<span id="el_comprobantes_discriminaIVA">
<span<?php echo $comprobantes->discriminaIVA->ViewAttributes() ?>>
<?php echo $comprobantes->discriminaIVA->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->seAutoriza->Visible) { // seAutoriza ?>
		<tr id="r_seAutoriza">
			<td><?php echo $comprobantes->seAutoriza->FldCaption() ?></td>
			<td<?php echo $comprobantes->seAutoriza->CellAttributes() ?>>
<span id="el_comprobantes_seAutoriza">
<span<?php echo $comprobantes->seAutoriza->ViewAttributes() ?>>
<?php echo $comprobantes->seAutoriza->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->letra->Visible) { // letra ?>
		<tr id="r_letra">
			<td><?php echo $comprobantes->letra->FldCaption() ?></td>
			<td<?php echo $comprobantes->letra->CellAttributes() ?>>
<span id="el_comprobantes_letra">
<span<?php echo $comprobantes->letra->ViewAttributes() ?>>
<?php echo $comprobantes->letra->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->activo->Visible) { // activo ?>
		<tr id="r_activo">
			<td><?php echo $comprobantes->activo->FldCaption() ?></td>
			<td<?php echo $comprobantes->activo->CellAttributes() ?>>
<span id="el_comprobantes_activo">
<span<?php echo $comprobantes->activo->ViewAttributes() ?>>
<?php echo $comprobantes->activo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->ventaStock->Visible) { // ventaStock ?>
		<tr id="r_ventaStock">
			<td><?php echo $comprobantes->ventaStock->FldCaption() ?></td>
			<td<?php echo $comprobantes->ventaStock->CellAttributes() ?>>
<span id="el_comprobantes_ventaStock">
<span<?php echo $comprobantes->ventaStock->ViewAttributes() ?>>
<?php echo $comprobantes->ventaStock->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->ventaStockReservadoVenta->Visible) { // ventaStockReservadoVenta ?>
		<tr id="r_ventaStockReservadoVenta">
			<td><?php echo $comprobantes->ventaStockReservadoVenta->FldCaption() ?></td>
			<td<?php echo $comprobantes->ventaStockReservadoVenta->CellAttributes() ?>>
<span id="el_comprobantes_ventaStockReservadoVenta">
<span<?php echo $comprobantes->ventaStockReservadoVenta->ViewAttributes() ?>>
<?php echo $comprobantes->ventaStockReservadoVenta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->ventaStockReservadoCompra->Visible) { // ventaStockReservadoCompra ?>
		<tr id="r_ventaStockReservadoCompra">
			<td><?php echo $comprobantes->ventaStockReservadoCompra->FldCaption() ?></td>
			<td<?php echo $comprobantes->ventaStockReservadoCompra->CellAttributes() ?>>
<span id="el_comprobantes_ventaStockReservadoCompra">
<span<?php echo $comprobantes->ventaStockReservadoCompra->ViewAttributes() ?>>
<?php echo $comprobantes->ventaStockReservadoCompra->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->compraStock->Visible) { // compraStock ?>
		<tr id="r_compraStock">
			<td><?php echo $comprobantes->compraStock->FldCaption() ?></td>
			<td<?php echo $comprobantes->compraStock->CellAttributes() ?>>
<span id="el_comprobantes_compraStock">
<span<?php echo $comprobantes->compraStock->ViewAttributes() ?>>
<?php echo $comprobantes->compraStock->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->compraStockReservadoVenta->Visible) { // compraStockReservadoVenta ?>
		<tr id="r_compraStockReservadoVenta">
			<td><?php echo $comprobantes->compraStockReservadoVenta->FldCaption() ?></td>
			<td<?php echo $comprobantes->compraStockReservadoVenta->CellAttributes() ?>>
<span id="el_comprobantes_compraStockReservadoVenta">
<span<?php echo $comprobantes->compraStockReservadoVenta->ViewAttributes() ?>>
<?php echo $comprobantes->compraStockReservadoVenta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->compraStockReservadoCompra->Visible) { // compraStockReservadoCompra ?>
		<tr id="r_compraStockReservadoCompra">
			<td><?php echo $comprobantes->compraStockReservadoCompra->FldCaption() ?></td>
			<td<?php echo $comprobantes->compraStockReservadoCompra->CellAttributes() ?>>
<span id="el_comprobantes_compraStockReservadoCompra">
<span<?php echo $comprobantes->compraStockReservadoCompra->ViewAttributes() ?>>
<?php echo $comprobantes->compraStockReservadoCompra->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->muestraPendientes->Visible) { // muestraPendientes ?>
		<tr id="r_muestraPendientes">
			<td><?php echo $comprobantes->muestraPendientes->FldCaption() ?></td>
			<td<?php echo $comprobantes->muestraPendientes->CellAttributes() ?>>
<span id="el_comprobantes_muestraPendientes">
<span<?php echo $comprobantes->muestraPendientes->ViewAttributes() ?>>
<?php echo $comprobantes->muestraPendientes->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->comprobanteDefaultImportacion->Visible) { // comprobanteDefaultImportacion ?>
		<tr id="r_comprobanteDefaultImportacion">
			<td><?php echo $comprobantes->comprobanteDefaultImportacion->FldCaption() ?></td>
			<td<?php echo $comprobantes->comprobanteDefaultImportacion->CellAttributes() ?>>
<span id="el_comprobantes_comprobanteDefaultImportacion">
<span<?php echo $comprobantes->comprobanteDefaultImportacion->ViewAttributes() ?>>
<?php echo $comprobantes->comprobanteDefaultImportacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->preimpreso->Visible) { // preimpreso ?>
		<tr id="r_preimpreso">
			<td><?php echo $comprobantes->preimpreso->FldCaption() ?></td>
			<td<?php echo $comprobantes->preimpreso->CellAttributes() ?>>
<span id="el_comprobantes_preimpreso">
<span<?php echo $comprobantes->preimpreso->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($comprobantes->preimpreso, $comprobantes->preimpreso->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->cantidadRegistros->Visible) { // cantidadRegistros ?>
		<tr id="r_cantidadRegistros">
			<td><?php echo $comprobantes->cantidadRegistros->FldCaption() ?></td>
			<td<?php echo $comprobantes->cantidadRegistros->CellAttributes() ?>>
<span id="el_comprobantes_cantidadRegistros">
<span<?php echo $comprobantes->cantidadRegistros->ViewAttributes() ?>>
<?php echo $comprobantes->cantidadRegistros->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($comprobantes->limitarModo->Visible) { // limitarModo ?>
		<tr id="r_limitarModo">
			<td><?php echo $comprobantes->limitarModo->FldCaption() ?></td>
			<td<?php echo $comprobantes->limitarModo->CellAttributes() ?>>
<span id="el_comprobantes_limitarModo">
<span<?php echo $comprobantes->limitarModo->ViewAttributes() ?>>
<?php echo $comprobantes->limitarModo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
