ALTER TABLE `adelantos`
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `adelantos-recibos-pagos`
	ADD INDEX `idRecibo-Pago` (`idRecibo-Pago`),
	ADD INDEX `idAdelanto` (`idAdelanto`);

ALTER TABLE `articulos`
	ADD INDEX `idAlicuotaIva` (`idAlicuotaIva`),
	ADD INDEX `idCategoria` (`idCategoria`),
	ADD INDEX `idSubcateogoria` (`idSubcateogoria`),
	ADD INDEX `idRubro` (`idRubro`),
	ADD INDEX `idMarca` (`idMarca`),
	ADD INDEX `idPrecioCompra` (`idPrecioCompra`),
	ADD INDEX `idUnidadMedidaCompra` (`idUnidadMedidaCompra`),
	ADD INDEX `idUnidadMedidaVenta` (`idUnidadMedidaVenta`);

ALTER TABLE `articulos-proveedores`
	ADD INDEX `idArticulo` (`idArticulo`),
	ADD INDEX `idAlicuotaIva` (`idAlicuotaIva`),
	ADD INDEX `idMoneda` (`idMoneda`),
	ADD INDEX `idUnidadMedida` (`idUnidadMedida`),
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `articulos-stock`
	ADD INDEX `idArticulo` (`idArticulo`),
	ADD INDEX `idUnidadMedida` (`idUnidadMedida`);

ALTER TABLE `articulos-terceros-descuentos`
	ADD INDEX `idArticulo` (`idArticulo`),
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `categorias-terceros-descuentos`
	ADD INDEX `idCategoria` (`idCategoria`),
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `cheques-detalle`
	ADD INDEX `idCheque` (`idCheque`);

ALTER TABLE `comprobantes`
	ADD INDEX `contracomprobante` (`contracomprobante`);

ALTER TABLE `comprobantes-bloqueados-condiciones-iva`
	ADD INDEX `idCondicionIva` (`idCondicionIva`),
	ADD INDEX `idComprobanteBloqueado` (`idComprobanteBloqueado`);

ALTER TABLE `comprobantes-numeracion`
	ADD INDEX `idComprobante` (`idComprobante`);

ALTER TABLE `configuracion`
	ADD INDEX `idTipoDoc` (`idTipoDoc`),
	ADD INDEX `idPais` (`idPais`),
	ADD INDEX `idProvincia` (`idProvincia`),
	ADD INDEX `idPartido` (`idPartido`),
	ADD INDEX `idLocalidad` (`idLocalidad`);

ALTER TABLE `cotizaciones`
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `cotizaciones_detalles`
	ADD INDEX `idCotizacion` (`idCotizacion`),
	ADD INDEX `idArticulo` (`idArticulo`),
	ADD INDEX `idAlicuota` (`idAlicuota`);

ALTER TABLE `localidades`
	ADD INDEX `idProvincia` (`idProvincia`),
	ADD INDEX `idPartido` (`idPartido`);

ALTER TABLE `monedas`
	ADD INDEX `idMonedaActualizacion` (`idMonedaActualizacion`),
	ADD INDEX `idTipoDolar` (`idTipoDolar`);

ALTER TABLE `movimientos`
	ADD INDEX `idSociedad` (`idSociedad`),
	ADD INDEX `idTercero` (`idTercero`),
	ADD INDEX `idComprobante` (`idComprobante`),
	ADD INDEX `idDocTercero` (`idDocTercero`),
	ADD INDEX `idUsuarioAlta` (`idUsuarioAlta`),
	ADD INDEX `idUsuarioModificacion` (`idUsuarioModificacion`);

ALTER TABLE `movimientos-detalle`
	ADD INDEX `idMovimientos` (`idMovimientos`),
	ADD INDEX `idUnidadMedida` (`idUnidadMedida`),
	ADD INDEX `codProducto` (`codProducto`),
	ADD INDEX `alicuotaIva` (`alicuotaIva`),
	ADD INDEX `origenImportacion` (`origenImportacion`);

ALTER TABLE `movimientos-recibos-pagos`
	ADD INDEX `idRecibo-Pago` (`idRecibo-Pago`),
	ADD INDEX `idMovimientos` (`idMovimientos`);

ALTER TABLE `partidos`
	ADD INDEX `idProvincia` (`idProvincia`);

ALTER TABLE `provincias`
	ADD INDEX `idPais` (`idPais`);

ALTER TABLE `recibos-pagos`
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `recibos-pagos-detalle`
	ADD INDEX `idRecibo-Pago` (`idRecibo-Pago`),
	ADD INDEX `idMedioPago` (`idMedioPago`);

ALTER TABLE `subcategoria-terceros-descuentos`
	ADD INDEX `idSubcategoria` (`idSubcategoria`),
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `sucursales`
	ADD INDEX `idTerceroPadre` (`idTerceroPadre`),
	ADD INDEX `idTerceroSucursal` (`idTerceroSucursal`);

ALTER TABLE `terceros`
	ADD INDEX `idPais` (`idPais`),
	ADD INDEX `idProvincia` (`idProvincia`),
	ADD INDEX `idPartido` (`idPartido`),
	ADD INDEX `idLocalidad` (`idLocalidad`),
	ADD INDEX `tipoDoc` (`tipoDoc`),
	ADD INDEX `condicionIva` (`condicionIva`),
	ADD INDEX `idTipoTercero` (`idTipoTercero`),
	ADD INDEX `idTransporte` (`idTransporte`),
	ADD INDEX `idVendedor` (`idVendedor`),
	ADD INDEX `idCobrador` (`idCobrador`),
	ADD INDEX `idPaisFiscal` (`idPaisFiscal`),
	ADD INDEX `idProvinciaFiscal` (`idProvinciaFiscal`),
	ADD INDEX `idPartidoFiscal` (`idPartidoFiscal`),
	ADD INDEX `idLocalidadFiscal` (`idLocalidadFiscal`),
	ADD INDEX `idListaPrecios` (`idListaPrecios`);

ALTER TABLE `terceros-contactos`
	ADD INDEX `idTercero` (`idTercero`),
	ADD INDEX `idPersona` (`idPersona`);

ALTER TABLE `terceros-horarios`
	ADD INDEX `idTercero` (`idTercero`);

ALTER TABLE `terceros-medios-contactos`
	ADD INDEX `idTercero` (`idTercero`),
	ADD INDEX `idTipoContacto` (`idTipoContacto`);

ALTER TABLE `usuarios`
	ADD INDEX `idNivel` (`idNivel`);















