<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(377, "mi_panelcontrol_php", $Language->MenuPhrase("377", "MenuText"), "panelcontrol.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}panelcontrol.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(373, "mi_pedidosredes_php", $Language->MenuPhrase("373", "MenuText"), "pedidosredes.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}pedidosredes.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(374, "mi_generacionmasivacomprobantes_php", $Language->MenuPhrase("374", "MenuText"), "generacionmasivacomprobantes.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}generacionmasivacomprobantes.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(372, "mi_cotizaciones", $Language->MenuPhrase("372", "MenuText"), "cotizacioneslist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}cotizaciones'), FALSE, FALSE);
$RootMenu->AddMenuItem(286, "mi_recibos2Dpagos", $Language->MenuPhrase("286", "MenuText"), "recibos2Dpagoslist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}recibos-pagos'), FALSE, FALSE);
$RootMenu->AddMenuItem(275, "mi_movimientos", $Language->MenuPhrase("275", "MenuText"), "movimientoslist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}movimientos'), FALSE, FALSE);
$RootMenu->AddMenuItem(200, "mi_articulos", $Language->MenuPhrase("200", "MenuText"), "articuloslist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}articulos'), FALSE, FALSE);
$RootMenu->AddMenuItem(178, "mi_terceros", $Language->MenuPhrase("178", "MenuText"), "terceroslist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}terceros'), FALSE, FALSE);
$RootMenu->AddMenuItem(193, "mci_Auxiliares_Administrativos", $Language->MenuPhrase("193", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(234, "mci_Artedculos", $Language->MenuPhrase("234", "MenuText"), "", 193, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(360, "mi_actualizacionconarchivo_php", $Language->MenuPhrase("360", "MenuText"), "actualizacionconarchivo.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}actualizacionconarchivo.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(361, "mi_actualizacionconfiltro_php", $Language->MenuPhrase("361", "MenuText"), "actualizacionconfiltro.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}actualizacionconfiltro.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(205, "mi_categorias2Darticulos", $Language->MenuPhrase("205", "MenuText"), "categorias2Darticuloslist.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}categorias-articulos'), FALSE, FALSE);
$RootMenu->AddMenuItem(357, "mi_marcas", $Language->MenuPhrase("357", "MenuText"), "marcaslist.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}marcas'), FALSE, FALSE);
$RootMenu->AddMenuItem(201, "mi_articulos2Dproveedores", $Language->MenuPhrase("201", "MenuText"), "articulos2Dproveedoreslist.php?cmd=resetall", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}articulos-proveedores'), FALSE, FALSE);
$RootMenu->AddMenuItem(358, "mi_rubros", $Language->MenuPhrase("358", "MenuText"), "rubroslist.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}rubros'), FALSE, FALSE);
$RootMenu->AddMenuItem(206, "mi_subcategorias2Darticulos", $Language->MenuPhrase("206", "MenuText"), "subcategorias2Darticuloslist.php", 234, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}subcategorias-articulos'), FALSE, FALSE);
$RootMenu->AddMenuItem(269, "mci_Descuentos", $Language->MenuPhrase("269", "MenuText"), "", 193, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(204, "mi_lista2Dprecios", $Language->MenuPhrase("204", "MenuText"), "lista2Dprecioslist.php", 269, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}lista-precios'), FALSE, FALSE);
$RootMenu->AddMenuItem(203, "mi_categorias2Dterceros2Ddescuentos", $Language->MenuPhrase("203", "MenuText"), "categorias2Dterceros2Ddescuentoslist.php?cmd=resetall", 269, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}categorias-terceros-descuentos'), FALSE, FALSE);
$RootMenu->AddMenuItem(210, "mi_subcategoria2Dterceros2Ddescuentos", $Language->MenuPhrase("210", "MenuText"), "subcategoria2Dterceros2Ddescuentoslist.php?cmd=resetall", 269, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}subcategoria-terceros-descuentos'), FALSE, FALSE);
$RootMenu->AddMenuItem(202, "mi_articulos2Dterceros2Ddescuentos", $Language->MenuPhrase("202", "MenuText"), "articulos2Dterceros2Ddescuentoslist.php?cmd=resetall", 269, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}articulos-terceros-descuentos'), FALSE, FALSE);
$RootMenu->AddMenuItem(198, "mi_feriados", $Language->MenuPhrase("198", "MenuText"), "feriadoslist.php", 193, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}feriados'), FALSE, FALSE);
$RootMenu->AddMenuItem(176, "mci_Geo", $Language->MenuPhrase("176", "MenuText"), "", 193, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(169, "mi_paises", $Language->MenuPhrase("169", "MenuText"), "paiseslist.php", 176, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}paises'), FALSE, FALSE);
$RootMenu->AddMenuItem(170, "mi_provincias", $Language->MenuPhrase("170", "MenuText"), "provinciaslist.php", 176, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}provincias'), FALSE, FALSE);
$RootMenu->AddMenuItem(171, "mi_partidos", $Language->MenuPhrase("171", "MenuText"), "partidoslist.php", 176, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}partidos'), FALSE, FALSE);
$RootMenu->AddMenuItem(168, "mi_localidades", $Language->MenuPhrase("168", "MenuText"), "localidadeslist.php", 176, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}localidades'), FALSE, FALSE);
$RootMenu->AddMenuItem(179, "mi_terceros2Dmedios2Dcontactos", $Language->MenuPhrase("179", "MenuText"), "terceros2Dmedios2Dcontactoslist.php?cmd=resetall", 193, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}terceros-medios-contactos'), FALSE, FALSE);
$RootMenu->AddMenuItem(180, "mi_tipos2Dcontactos", $Language->MenuPhrase("180", "MenuText"), "tipos2Dcontactoslist.php", 193, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}tipos-contactos'), FALSE, FALSE);
$RootMenu->AddMenuItem(274, "mi_unidades2Dmedida", $Language->MenuPhrase("274", "MenuText"), "unidades2Dmedidalist.php", 193, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}unidades-medida'), FALSE, FALSE);
$RootMenu->AddMenuItem(363, "mi_accesos2Ddirectos", $Language->MenuPhrase("363", "MenuText"), "accesos2Ddirectoslist.php", 193, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}accesos-directos'), FALSE, FALSE);
$RootMenu->AddMenuItem(192, "mci_Auxiliares_Contables", $Language->MenuPhrase("192", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(207, "mi_alicuotas2Diva", $Language->MenuPhrase("207", "MenuText"), "alicuotas2Divalist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}alicuotas-iva'), FALSE, FALSE);
$RootMenu->AddMenuItem(273, "mi_comprobantes", $Language->MenuPhrase("273", "MenuText"), "comprobanteslist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}comprobantes'), FALSE, FALSE);
$RootMenu->AddMenuItem(177, "mi_condiciones2Diva", $Language->MenuPhrase("177", "MenuText"), "condiciones2Divalist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}condiciones-iva'), FALSE, FALSE);
$RootMenu->AddMenuItem(285, "mi_medios2Dpagos", $Language->MenuPhrase("285", "MenuText"), "medios2Dpagoslist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}medios-pagos'), FALSE, FALSE);
$RootMenu->AddMenuItem(208, "mi_monedas", $Language->MenuPhrase("208", "MenuText"), "monedaslist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}monedas'), FALSE, FALSE);
$RootMenu->AddMenuItem(365, "mi_observaciones2Dcheques", $Language->MenuPhrase("365", "MenuText"), "observaciones2Dchequeslist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}observaciones-cheques'), FALSE, FALSE);
$RootMenu->AddMenuItem(181, "mi_tipos2Ddocumentos", $Language->MenuPhrase("181", "MenuText"), "tipos2Ddocumentoslist.php", 192, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}tipos-documentos'), FALSE, FALSE);
$RootMenu->AddMenuItem(356, "mci_Reportes", $Language->MenuPhrase("356", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(376, "mi_articulospendientes_php", $Language->MenuPhrase("376", "MenuText"), "articulospendientes.php", 356, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}articulospendientes.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(296, "mi_estadoscuentas_php", $Language->MenuPhrase("296", "MenuText"), "estadoscuentas.php", 356, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}estadoscuentas.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(369, "mi_movimientospendientes_php", $Language->MenuPhrase("369", "MenuText"), "movimientospendientes.php", 356, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}movimientospendientes.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(368, "mi_saldos_php", $Language->MenuPhrase("368", "MenuText"), "saldos.php", 356, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}saldos.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(283, "mi_configuracion", $Language->MenuPhrase("283", "MenuText"), "configuracionlist.php", -1, "", AllowListMenu('{7FFE1683-B3E8-4940-BA6E-6837E8BA6642}configuracion'), FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
