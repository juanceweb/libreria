// Field event handlers
(function($) {

	// Table 'terceros' Field 'idTipoTercero'
	$('[data-table=terceros][data-field=x_idTipoTercero]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {
				ocultarMostrarCampos();
			}
		}
	);

	// Table 'terceros' Field 'domicilioFiscal'
	$('[data-table=terceros][data-field=x_domicilioFiscal]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {
				ocultarMostrarCampos();
			}
		}
	);

	// Table 'articulos' Field 'idPrecioCompra'
	$('[data-table=articulos][data-field=x_idPrecioCompra]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {
				precioVenta(e["target"]);
			}
		}
	);

	// Table 'articulos2Dproveedores' Field 'idMoneda'
	$('[data-table=articulos2Dproveedores][data-field=x_idMoneda]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {
				precioPesos();
			}
		}
	);

	// Table 'articulos2Dproveedores' Field 'precio'
	$('[data-table=articulos2Dproveedores][data-field=x_precio]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {
				precioPesos();
			}
		}
	);

	// Table 'monedas' Field 'idMonedaActualizacion'
	$('[data-table=monedas][data-field=x_idMonedaActualizacion]').on(
		{ // keys = event types, values = handler functions
			"change": function(e) {

				// Your code
				ocultarMostrarCampos();		
			}
		}
	);
})(jQuery);
