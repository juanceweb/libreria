/*Al salir*/

window.addEventListener("beforeunload", function (e) {
  if (localStorage.getItem("preguntar") == 1) {
    cancelarmovimiento();
  }
}, false);

var recuperareliminados = [];

$(document).keypress(function (e) {

  switch (e.charCode) {
    case 43:
      e.preventDefault();
      agregardetalle();
      break;
    case 45:
      e.preventDefault();
      eliminardetalle($(':focus').parents("tr").find("td a"));
      break;
    case 10:
      e.preventDefault();
      var elementoorigen = $(':focus');

      if (e.ctrlKey == true) {

        if ($(':focus').parents("tr").find("td .articulocustom").is(":visible")) {
          articulocustom($(':focus').parents("tr").find("td .articulocustom"));
        } else {
          articulotabulado($(':focus').parents("tr").find("td .articulotabulado"));
        };

      }

      break;
    case 13:
      e.preventDefault();
      var elementoorigen = $(':focus');
      var orden = elementoorigen.parents("tr").attr("data-orden-detalle");

      if (elementoorigen.attr("id") == "tipomovimiento") {
        $("#fecha").focus();
      };

      if (elementoorigen.attr("id") == "fecha") {
        $("#contable").focus();
      };

      if (elementoorigen.attr("id") == "contable") {
        $("#comprobante").focus();
      };

      if (elementoorigen.attr("id") == "comprobante") {
        $("#tercero").select2("open");
      };

      if (elementoorigen.hasClass("cantidad")) {

        if ($("[data-orden-detalle='" + orden + "']").find(".productocustom").is(":visible")) {
          $("[data-orden-detalle='" + orden + "']").find(".productocustom").focus();
        } else {
          $("[data-orden-detalle='" + orden + "']").find(".producto").select2("open");
          $(".select2-search__field").focus();
        };

      };

      if (elementoorigen.hasClass("productocustom")) {
        $("[data-orden-detalle='" + orden + "']").find(".unidadmedida").focus();
      };

      if (elementoorigen.hasClass("unidadmedida")) {
        if ($("[data-orden-detalle='" + orden + "']").find(".impunit").attr("disabled") !== undefined && $("[data-orden-detalle='" + orden + "']").find(".impunit").attr("disabled") !== false) {
          $("[data-orden-detalle='" + orden + "']").find(".alicuotaiva").focus();
        } else {
          $("[data-orden-detalle='" + orden + "']").find(".impunit").focus().select();
        };
      };

      if (elementoorigen.hasClass("impunit")) {
        $("[data-orden-detalle='" + orden + "']").find(".alicuotaiva").focus().select();
      };

      if (elementoorigen.hasClass("alicuotaiva")) {
        $("[data-orden-detalle='" + orden + "']").find(".cantidad").focus();
      };

      break;
    default:
  }

});

$("#comprobante").change(function () {
  obtenerNumeroComprobante();
  obtenerComprobanteImportacionDefault();
})

$("#contable").change(function () {
  obtenerComprobantes();
})

function obtenerComprobantes() {
  
    //si el campo contable es de tipo checkbox y esta tildado enviar 1 sino enviar 0
    //si el campo contable es de tipo hidden enviar el valor del campo

    if($("#contable").is(":checkbox")){
      if($("#contable").is(":checked")){
        var contable = 1;
      }else{
        var contable = 0;
      }
    }else{
      var contable = $("#contable").val();
    }
  
    var accion = "obtener-comprobantes-modo";
  
    $.ajax({
      url: "api.php",
      type: "post",
      //crossDomain: true,
      data: { accion: accion, contable: contable },
      dataType: "json",
      success: function (response) {
        //vacío el select
        $(".comprobante").html("");
        $(".comprobante-importar").html("");
        //agrego las opciones
        $(".comprobante").append('<option value="">Seleccione</option>');
        $(".comprobante-importar").append('<option value="">Seleccione</option>');

        $.each(response.exito, function (index, value) {
          $(".comprobante").append('<option value="' + value.id + '">' + value.denominacion + '</option>');
          $(".comprobante-importar").append('<option value="' + value.id + '">' + value.denominacion + '</option>');
        });

        obtenerNumeroComprobante();

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  
}

function agregarComprobante() {
  var select = $("#comprobante").html()
  $("#comprobantes-wrapper").append(
    '<div class="comprobante-wrapper"><select style="margin-top:5px" class="form-control comprobante">' + select + '</select><a href="javascript:void(0)" onclick="eliminarComprobante(this)">Eliminar</a></div>'
  )
}

function eliminarComprobante(el) {
  $(el).parent(".comprobante-wrapper").remove()
}

function obtenerComprobanteImportacionDefault() {

  var idcomprobante = $("#comprobante").val();

  var accion = "obtener-comprobante-importacion-default";

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, idcomprobante: idcomprobante },
    dataType: "json",
    success: function (response) {
      //console.log(response);
      if (!response.error) {
        if (response.exito[0].comprobanteDefaultImportacion != "") {
          $("#importar-comprobante").val(response.exito[0].comprobanteDefaultImportacion);
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });

}

function obtenerNumeroComprobante() {

  var comprobante = $("#comprobante").val();

  if ($("#contable").attr("type") == 'checkbox') {
    if ($("#contable").is(':checked')) {
      var contable = 1;
    } else {
      var contable = 0;
    };
  } else {
    var contable = $("#contable").val()
  }

  var accion = "obtenerpuntoventa";

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, comprobante: comprobante, contable: contable },
    dataType: "json",
    success: function (response) {

      $("#puntoventa").val(response["exito"]);

      var puntoventa = response["exito"];

      var accion = "numerocomprobante";

      $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: { accion: accion, comprobante: comprobante, contable: contable, puntoventa: puntoventa },
        dataType: "json",
        success: function (response) {
          $("#numerocomprobante").val(response["exito"]);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
        }
      });

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });



}

function cancelarmovimiento() {

  //console.log("cancelarmovimiento");


  //Es edición

  if ($("#idcabecera").val() != undefined) {

    if (window.recuperareliminados.length > 0) {
      var accion = "recuperareliminados";

      $.ajax({
        url: "api.php",
        type: "post",
        async: false,
        //crossDomain: true,
        data: { accion: accion, datos: window.recuperareliminados },
        dataType: "json",
        success: function (response) {
          //console.log("recuperar");
          //console.log(window.recuperareliminados);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
        }
      });

    }

  }

  var data = [];
  var accion = "eliminarorigen";
  //Por cada detalle
  $.each($("[data-orden-detalle]"), function (index, value) {
    //Si no es el primer detalle
    if ($(this).attr("data-orden-detalle") != 0) {
      //Si es un detalle que fue importado
      if ($(this).attr("data-origen") != undefined) {
        //Si no tiene el atributo "data-recuperar"
        if (!$(this).attr("data-recuperar")) {

          var id = $(this).attr("data-origen");

          var cantidadingresada = parseFloat($(this).find(".cantidad").val());
          var cantidadimportada = parseFloat($(this).data("cantidad-importada"));

          if (cantidadingresada < cantidadimportada) {
            var cantidad = cantidadingresada;
          } else {
            var cantidad = cantidadimportada;
          }

          var registro = {
            "id": id,
            "cantidad": cantidad
          }

          data.push(registro);

        }

      }
    };
  });

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, data: data },
    dataType: "json",
    success: function (response) {
      console.log("eliminar");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });


}

$(document).ready(function () {
  camposcompraoventa();
  tercerotabulado();
  importeunitario();
  tipoTercero();
  //Guardo el primer detalle
  window.primerdetalle = $("[data-orden-detalle='0']").html();
  //ocultar primer registro
  $("[data-orden-detalle='0']").css("display", "none");
  localStorage.setItem("preguntar", 1);
  calculavaloresindividuales();

  $("#tipomovimiento").focus();

  actualizarcontadordetalles();

  $(".impunit").tooltip();
  obtenerComprobanteImportacionDefault();

  //es edición
  if ($("#idcabecera").val() != undefined) {

    console.log("acá")

    var tipomovimiento = $("#tipomovimiento").val();
    var idtercero = $("#tercero").val();
    var accion = "datosarticulo";

    //recorro los detalles
    $("#detalle tbody tr").each(function (index, el) {
      var idarticulo = $(el).find(".producto").val();

      if (idarticulo) {

        var orden = $(el).attr("data-orden-detalle");
        var unidadActual = $(el).find(".unidadmedida").val();

        $.ajax({
          url: "api.php",
          type: "post",
          //crossDomain: true,
          data: { accion: accion, idarticulo: idarticulo, tipomovimiento: tipomovimiento, idtercero: idtercero },
          dataType: "json",
          success: function (response) {

            console.log(response);

            if (response.exito) {

              $("[data-orden-detalle='" + orden + "'] .unidadmedida").empty()

              var html = ''
              response.exito.unidades.forEach(unidad => {

                if (unidad.id == unidadActual) {
                  html += '<option selected value="' + unidad.id + '">' + unidad.denominacion + '</option>'
                } else {
                  html += '<option value="' + unidad.id + '">' + unidad.denominacion + '</option>'
                }
              });

              $("[data-orden-detalle='" + orden + "'] .unidadmedida").html(html)

            }

          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
          }
        });


      }

    });

  }

})

/*Agregar y eliminar detalles*/

//Formato de resultados
function resultado(res) {
  if (res.loading) return res.text;
  var markup = "<div><span class='resultado codigo'>" + res.id + "</span><span class='resultado'>" + res.denominacion + "</span></div>";
  return markup;
}

//Formato de selección
function seleccion(res) {
  return res.id + " " + res.denominacion;
}

//aplicar select2 de productos
function aplicarselect2(elemento) {
  var tercero = $("#tercero").val();
  var movimiento = $("#tipomovimiento").val();
  var accion = "productos";
  var limit = 5000; //límite de resultados devueltos
  $(elemento).select2({
    language: "es",
    width: '300px',
    minimumInputLength: 2,//caracteres mínimos para empezar la búsqueda
    ajax: {
      url: "api.php",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          page: params.page,
          limit: limit,
          accion: accion,
          tercero: tercero,
          movimiento: movimiento
        };
      },
      processResults: function (data, params) {
        return {
          results: data["exito"],
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) {
      return markup;
    },

    templateResult: resultado,
    templateSelection: seleccion
  });
}


//Agregar detalle
$("#agregardetalle").click(function () {
  agregardetalle();
})

function actualizarcontadordetalles() {
  var cantidad = parseInt($("[data-orden-detalle]").length) - 1;
  $("#contador-detalles").text(cantidad);
}

function agregardetalle(idarticulo, cantidad, precioviejo, prevenirScroll = false) {
  //Oculto todos los mensajes
  ocultarmensaje();
  //Si no está seleccionado el tercero no permito
  if ($("#tercero").val() == 0) {
    mostrarmensajes("Antes debe seleccionar un tercero", "danger");
  } else {
    //Si se seleccionó un tercero
    //Deshabilito los campos de cabecera que ya no se pueden cambiar
    $("#tercero").attr("disabled", true).attr("readonly", true);
    $("#tipodoctercero").attr("disabled", true).attr("readonly", true);
    $("#doctercero").attr("disabled", true).attr("readonly", true);
    $("#nomtercero").attr("disabled", true).attr("readonly", true);
    $("#tipomovimiento").attr("disabled", true).attr("readonly", true);
    $("#comprobante").attr("disabled", true).attr("readonly", true);
    //Establezco el máximo de detalles en 0
    var max = 0;
    //Por cada detalle
    //Indico cual es el detalle máximo
    $.each($('tbody tr'), function (index, value) {
      if (parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle")) > max) {
        max = parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle"));
      };
    });
    max++;

    //Agrego un registro
    $("#detalle tbody").append('<tr data-orden-detalle="' + (max) + '">' + primerdetalle + '</tr>');
    //Si idarticulo no está indefinido es porque es una importación o estoy pegando contenido
    if (idarticulo != undefined) {
      //Indico la cantidad
      $("[data-orden-detalle='" + (max) + "'] .cantidad").val(cantidad);
      //Obtengo la información del producto
      $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {
          accion: "producto",
          idarticulo: idarticulo,
        },
        dataType: "json",
        success: function (response) {

          if (Object.keys(response["exito"]).length > 0) {

            var data = {
              id: idarticulo,
              text: response["exito"]["denominacion"]
            };
            //Cargo el artículo en el selector
            var newOption = new Option(data.id + " " + data.text, data.id, true, true);
            $("[data-orden-detalle='" + (max) + "'] .producto").append(newOption);

            if (precioviejo != undefined) {

              //Verifico si el precio cambió

              var accion = "importearticulo";
              var idtercero = $("#tercero").val();
              var tipomovimiento = $("#tipomovimiento").val();
              var unidadmedida = 1

              var clasecambioprecio = "";

              $.ajax({
                url: "api.php",
                type: "post",
                //crossDomain: true,
                data: { accion: accion, idarticulo: idarticulo, idtercero: idtercero, tipomovimiento: tipomovimiento, unidadmedida: unidadmedida },
                dataType: "json",
                success: function (responseprecio) {

                  //Cargo los valores en los campos

                  if (parseFloat(responseprecio["exito"]["precio"]) != parseFloat(precioviejo)) {

                    //El precio cambió

                    $("[data-orden-detalle='" + (max) + "'] .impunit").attr("onclick", "infoprecio(this)").attr("data-actualizo-precio", false).attr("data-viejo-importe", precioviejo).attr("data-nuevo-importe", responseprecio["exito"]["precio"]).addClass("cambio-precio").val(precioviejo);
                    calculavaloresindividuales($("[data-orden-detalle='" + (max) + "'] .impunit"))

                  } else {

                    //El precio no cambió

                    $("[data-orden-detalle='" + (max) + "'] .producto").trigger("change")
                  }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                  console.log(textStatus, errorThrown);
                }
              });

            } else {
              $("[data-orden-detalle='" + (max) + "'] .producto").trigger("change")
            }


          } else {
            alert("El código " + idarticulo + " no se encontró. Se insertará un registro vacío");
            aplicarselect2($("[data-orden-detalle='" + (max) + "'] .producto"));
          }

          actualizarcontadordetalles();
          importeunitario();

        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
        }
      });

    } else {
      aplicarselect2($("[data-orden-detalle='" + (max) + "'] .producto"));
      actualizarcontadordetalles();
      importeunitario();
    }
  }

  $("tr[data-orden-detalle='" + max + "'] .cantidad").focus().select();

  if (!prevenirScroll) {
    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
  }

};

//Eliminar detalle
function eliminardetalle(elemento) {
  //console.log(elemento);
  if ($(elemento).parents("tr").find(".producto").is(":visible") && $(elemento).parents("tr").find(".producto").hasClass("select2-hidden-accessible")) {
    $(elemento).parents("tr").find(".producto").select2("destroy");
  };

  $(elemento).parents("tr").remove();

  var max = 0;
  $.each($('tbody tr'), function (index, value) {
    if (parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle")) > max) {
      max = parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle"));
    };
  });

  $("tr[data-orden-detalle='" + max + "'] .cantidad").focus();

  //Hay que recuperar la cantidad en caso de cancelar
  if ($(elemento).parents("tr").data("recuperar")) {

    window.recuperareliminados[$(elemento).parents("tr").data("origen")] = parseFloat($(elemento).parents("tr").data("cantidad-importada"));

  }

  //Si proviene de una importación
  if ($(elemento).parents("tr").attr("data-origen") != undefined) {

    var accion = "eliminarorigen";
    var id = $(elemento).parents("tr").attr("data-origen");

    var cantidadingresada = parseFloat($(elemento).parents("tr").find(".cantidad").val());
    var cantidadimportada = parseFloat($(elemento).parents("tr").data("cantidad-importada"));

    if (cantidadingresada < cantidadimportada) {
      var cantidad = cantidadingresada;
    } else {
      var cantidad = cantidadimportada;
    }

    var data = [{
      "id": id,
      "cantidad": cantidad
    }];

    $.ajax({
      url: "api.php",
      type: "post",
      //crossDomain: true,
      data: { accion: accion, data: data },
      dataType: "json",
      success: function (response) {

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  };
  actualizarcontadordetalles();

  calculavaloresindividuales();
}

/*Fin Agregar y eliminar detalles*/

/*Campos activos e inactivos*/
function activarcampos(elementos) {
  $.each(elementos, function (index, value) {
    $(value).val("");
    $(value).removeAttr("disabled");
    $(value).removeAttr("readonly");
  });
}

function desactivarcampos(elementos) {
  $.each(elementos, function (index, value) {
    $(value).val("");
    $(value).attr("disabled", true);
    $(value).attr("readonly", true);
  });
}

function camposcompraoventa() {
  if ($("#tipomovimiento").val() == 1) {
    //es Venta
    /*
    desactivarcampos([
      $("#puntoventa"),
      $("#numerocomprobante"),
      $("#cae"),
      $("#vtocae"),
      ]);
     */
  } else {
    //es Compra
    activarcampos([
      $("#puntoventa"),
      $("#numerocomprobante"),
      $("#cae"),
      $("#vtocae"),
    ]);
  };
}

$("#tipomovimiento").change(function () {
  importeunitario();
});

function importeunitario() {
  if ($("#tipomovimiento").val() != 1) {
    $(".impunit").removeAttr("disabled").removeAttr("readonly");
    $(".alicuotaiva").removeAttr("disabled").removeAttr("readonly");
  } else {
    //$(".impunit").attr("disabled",true).attr("readonly",true);
    $(".alicuotaiva").removeAttr("disabled").removeAttr("readonly");
  };
}

function tercerotabulado() {
  console.log($("#tercero").val());

  if ($("#tercero").val() != 0) {
    //es tabulado
    desactivarcampos([
      $("#tipodoctercero"),
      $("#doctercero"),
      $("#nomtercero")
    ]);

    if ($("#tercero").val() == config.idConsumidorFinal) {//es consumidor final
      activarcampos([
        $("#tipodoctercero"),
        $("#doctercero"),
        $("#nomtercero")
      ]);
    } else {
      $("#ficha-tercero").attr("href", "reportecomercial.php?idtercero=" + $("#tercero").val());
      $("#ficha-tercero").show();
    };

    var accion = "datosterceros";
    var idtercero = $("#tercero").val();

    $.ajax({
      url: "api.php",
      type: "get",
      //crossDomain: true,
      data: { accion: accion, idtercero: idtercero },
      dataType: "json",
      success: function (response) {

        $("#comprobante option").removeAttr('disabled');

        $("#tipodoctercero").val(response["exito"][0]["tipoDoc"]);
        $("#doctercero").val(response["exito"][0]["documento"]);
        $("#nomtercero").val(response["exito"][0]["denominacion"]);
        $("#limitedescubierto").val(response["exito"][0]["limiteDescubierto"]);

        if (!$("#condicionventa").data("edita")) {
          $("#condicionventa").val(response["exito"][0]["condicionVenta"]);
        };

        $.each(response["exito"]["comprobantesbloqueados"], function (index, value) {

          var valor = parseFloat(value["idComprobanteBloqueado"]);

          $(".comprobante option").each(function (index) {
            if ($(this).val() == valor) {
              $(this).attr('disabled', 'disabled');
              $(this).removeAttr('selected');
            };
          });

          estadodecuenta += valor;

        });

        //console.log(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });

    var accion = "estadodecuenta";
    var tercero = $("#tercero").val();
    var tipomovimiento = $("#tipomovimiento").val();
    $.ajax({
      url: "api.php",
      type: "post",
      //crossDomain: true,
      data: { accion: accion, tercero: tercero, tipomovimiento: tipomovimiento },
      dataType: "json",
      success: function (response) {

        var estadodecuenta = 0;

        $.each(response["exito"], function (index, value) {
          var valor = parseFloat(value["importeNeto"]);
          if (value["comportamiento"] == 2) {
            valor = valor * -1;
          };

          estadodecuenta += valor;

        });

        $("#estadocuenta").val(parseFloat(estadodecuenta).toFixed(2));

        comprobarestadocuenta();

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });

    /* Muestro los artículos pendientes de importación */

    var accion = "articulos-pendientes-movimientos";
    var idtercero = $("#tercero").val();

    if($("#contable").is(":checkbox")){
      if($("#contable").is(":checked")){
        var contable = 1;
      }else{
        var contable = 0;
      }
    }else{
      var contable = $("#contable").val();
    }

    $.ajax({
      url: "api.php",
      type: "post",
      //crossDomain: true,
      data: { accion: accion, idtercero: idtercero, contable: contable },
      dataType: "json",
      success: function (response) {

        //Mostrar modal con movimientos pendientes

        $("#movimientos-pendientes tbody").empty();

        if (!response.error) {

          var html = '';
          var importetotal = 0;
          var cantidadtotal = 0;

          $.each(response["exito"], function (index, value) {

            html += '<tr>'
            html += '<td><a href="javascript:void(0);" onclick="importarmovimiento(' + value.idmovimiento + ', this)"><i class="fa fa-2x fa-check-circle-o" title="Seleccionar movimiento" aria-hidden="true"></i></a></td>'
            html += '<td>' + value.denominacionCorta + ': ' + value.ptoVenta + '-' + value.nroComprobante + '</td>'
            html += '<td>' + value.fecha + '</td>'
            html += '<td>' + value.importeTotal + '</td>'
            html += '<td>' + value.cantdetalles + '</td>'
            html += '</tr>'

            importetotal += parseFloat(value.importePendiente);
            cantidadtotal += parseFloat(value.cantPendiente);

          });

          $("#movimientos-pendientes tbody").html(html);

          $("#modal-pendientes").modal();

        }

        //Se modifica la forma de notificar movimientos pendientes

        /*
        
                      $("#articulos-pendientes").empty();
        
                      if (!response.error) {
        
                        var html = '';
                        var importetotal = 0;
                        var cantidadtotal = 0;
        
                        $.each(response["exito"], function (index, value) {
                          html += 'Comprobante: ' + value.denominacion + ' - ';
                          html += 'Cantidad de artículos: ' + value.cantPendiente + ' - ';
                          html += 'Importe: $' + value.importePendiente + '<br />';
        
                          importetotal += parseFloat(value.importePendiente);
                          cantidadtotal += parseFloat(value.cantPendiente);
        
                        });
        
                        html += '<b>Totales: Cantidad: '+ cantidadtotal + ' - Importe: $'+ importetotal;
        
                        $("#articulos-pendientes").append(html);
                      };
        */

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });

  } else {
    //no es tabulado
    $("#tipodoctercero").removeAttr("disabled");
    $("#tipodoctercero").removeAttr("readonly");

    $("#doctercero").removeAttr("disabled");
    $("#doctercero").removeAttr("readonly");

    $("#nomtercero").removeAttr("disabled");
    $("#nomtercero").removeAttr("readonly");

    $("#limitedescubierto").val("");
    $("#estadocuenta").val("");
    comprobarestadocuenta();

    $("#ficha-tercero").hide();

  }
}

function comprobarestadocuenta() {

  var limitedescubierto = parseFloat($("#limitedescubierto").val());

  if (limitedescubierto != "") {

    var estadocuenta = parseFloat($("#estadocuenta").val());
    var compraactual = parseFloat($("#importeneto").val());

    var credito = limitedescubierto - estadocuenta - compraactual;

    if (credito <= 0) {
      mostrarmensajes("El cliente alcanzó el límite de descubierto", "danger");
    } else {
      ocultarmensaje()
    };

  } else {
    ocultarmensaje();
  };


}


$("#tercero").change(function () {
  tercerotabulado();
})

$("#tipomovimiento").change(function () {
  camposcompraoventa();
  tipoTercero();
})


$(".fileinput-button").click(function () {
  var orden = $(this).attr("data-orden");
  $('#progress' + orden + ' .progress-bar').css('width', 0);
});

function eliminar(orden) {
  $('#progress' + orden + ' .progress-bar').css('width', 0);
  $("#files" + orden).html("");
}

$(function () {
  'use strict';
  var url = '../upload/';
  $('#fileupload1').fileupload({
    url: url,
    dataType: 'json',
    done: function (e, data) {
      $.each(data.result.files, function (index, file) {
        if (typeof file["error"] != 'undefined') {
          $("#files1").html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-exclamation fa-fw"></i>' + file["error"] + '.</div>');
          $('#progress1 .progress-bar').css('width', 0);
        } else {
          $("#files1").html('<img src="' + url + 'thumbnail/' + file.name + '" alt=""><a style="margin-left:10px" class="btn btn-default" onclick="eliminar(1)">Eliminar</a><p id="archivotext">' + file.name + '</p><input type="hidden" id=archivo value="' + file.name + '">');
        };
      });
    },
    progressall: function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);
      $('#progress1 .progress-bar').css(
        'width',
        progress + '%'
      );
    },
  });
});


/*Artículo CUSTOM*/

function articulocustom(elemento) {
  var orden = $(elemento).parents("tr").attr("data-orden-detalle");
  $(elemento).css("display", "none");
  $("[data-orden-detalle='" + orden + "'] .articulotabulado").css("display", "inline-block");
  activarcampos([
    $("[data-orden-detalle='" + orden + "'] .impunit")
  ]);
  $("[data-orden-detalle='" + orden + "'] .alicuotaiva").removeAttr("disabled").removeAttr("readonly");

  $("[data-orden-detalle='" + orden + "'] .producto").val(0);
  $("[data-orden-detalle='" + orden + "'] .producto").empty();
  $("[data-orden-detalle='" + orden + "'] .producto").select2("destroy");
  $("[data-orden-detalle='" + orden + "'] .producto").css("display", "none");
  $("[data-orden-detalle='" + orden + "'] .productocustom").css("display", "block");
}

function articulotabulado(elemento) {
  var orden = $(elemento).parents("tr").attr("data-orden-detalle");
  $(elemento).css("display", "none");
  $("[data-orden-detalle='" + orden + "'] .articulocustom").css("display", "inline-block");
  if ($("#tipomovimiento").val() == 1) {
    desactivarcampos([
      $("[data-orden-detalle='" + orden + "'] .impunit")
    ]);
    $("[data-orden-detalle='" + orden + "'] .alicuotaiva").attr("disabled", true).attr("readonly", true);
    $("[data-orden-detalle='" + orden + "'] .producto").css("display", "block");
  };
  aplicarselect2($("[data-orden-detalle='" + orden + "'] .producto"));
  $("[data-orden-detalle='" + orden + "'] .productocustom").css("display", "none");
  $("[data-orden-detalle='" + orden + "'] .productocustom").val("");
}

function cambiaIvaTodos() {
  if ($("#iva-todos").length) {
    if ($("#iva-todos").val() != "") {
      $(".alicuotaiva").val($("#iva-todos").val())
    } else {
      $(".alicuotaiva").removeAttr("disabled")
    }
  }

  calculatodosindividuales()

}

function cambiaproducto(elemento) {

  var orden = $(elemento).parents("tr").attr("data-orden-detalle");

  var tipomovimiento = $("#tipomovimiento").val();
  var idtercero = $("#tercero").val();
  var idarticulo = $(elemento).val();
  var accion = "datosarticulo";

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, idarticulo: idarticulo, tipomovimiento: tipomovimiento, idtercero: idtercero },
    dataType: "json",
    success: function (response) {

      if (response.exito) {

        $("[data-orden-detalle='" + orden + "'] .alicuotaiva").val(parseInt(response["exito"][0]["iva"]));

        cambiaIvaTodos()

        $("[data-orden-detalle='" + orden + "'] .unidadmedida").empty()

        var html = ''
        response.exito.unidades.forEach(unidad => {

          if (unidad.id == ["exito"][0]["unid"]) {
            html += '<option selected value="' + unidad.id + '">' + unidad.denominacion + '</option>'
          } else {
            html += '<option value="' + unidad.id + '">' + unidad.denominacion + '</option>'
          }
        });

        $("[data-orden-detalle='" + orden + "'] .unidadmedida").html(html)

      }

      importearticulo(elemento);

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });

}


/*Importe de artículo*/

function importearticulo(elemento) {

  var orden = $(elemento).parents("tr").attr("data-orden-detalle");
  var idarticulo = $(elemento).parents("tr").find(".producto").val();

  var accion = "importearticulo";
  var idtercero = $("#tercero").val();
  var tipomovimiento = $("#tipomovimiento").val();
  var unidadmedida = $(elemento).parents("tr").find(".unidadmedida").val();

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, idarticulo: idarticulo, idtercero: idtercero, tipomovimiento: tipomovimiento, unidadmedida: unidadmedida },
    dataType: "json",
    success: function (response) {
      $("[data-orden-detalle='" + orden + "'] .impunit").val(response["exito"]["precio"]);
      //$("[data-orden-detalle='"+orden+"'] .alicuotaiva").val(response["exito"]["iva"]);
      calculavaloresindividuales(elemento)
      $(elemento).parents("tr").find(".unidadmedida").focus();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });


}


/*Calcula valores*/

function calculatodosindividuales() {
  $('.cantidad').trigger('change');
}

function calculavaloresindividuales(elemento) {

  var orden = $(elemento).parents("tr").attr("data-orden-detalle");

  $("[data-orden-detalle='" + orden + "'] .imptotal").val(
    parseFloat(
      $("[data-orden-detalle='" + orden + "'] .cantidad").val() * $("[data-orden-detalle='" + orden + "'] .impunit").val()
    ).toFixed(2)
  );

  $("[data-orden-detalle='" + orden + "'] .impiva").val(
    parseFloat(
      $("[data-orden-detalle='" + orden + "'] .imptotal").val() * ($("[data-orden-detalle='" + orden + "'] .alicuotaiva").val() / 100)
    ).toFixed(2)
  );

  $("[data-orden-detalle='" + orden + "'] .impneto").val(
    parseFloat(
      $("[data-orden-detalle='" + orden + "'] .cantidad").val() * $("[data-orden-detalle='" + orden + "'] .impunit").val() +
      $("[data-orden-detalle='" + orden + "'] .imptotal").val() * (
        $("[data-orden-detalle='" + orden + "'] .alicuotaiva :selected").text() / 100
      )
    ).toFixed(2)
  );

  $("[data-orden-detalle='" + orden + "'] .imppesos").val(
    (
      parseFloat(
        $("[data-orden-detalle='" + orden + "'] .cantidad").val() *
        (
          $("[data-orden-detalle='" + orden + "'] .impunit").val() -
          $("[data-orden-detalle='" + orden + "'] .impunit").val() *
          $("[data-orden-detalle='" + orden + "'] .bonif").val() / 100
        ) +
        $("[data-orden-detalle='" + orden + "'] .imptotal").val() *
        (
          $("[data-orden-detalle='" + orden + "'] .alicuotaiva").val() / 100
        )
      ) *
      parseFloat(
        $("#valordolar").val()
      )
    ).toFixed(2)
  );

  var imptotal = 0;
  var impiva = 0;
  var impneto = 0;

  $.each($("[data-orden-detalle]"), function (index, value) {
    if ($(this).attr("data-orden-detalle") != 0) {
      orden = $(this).attr("data-orden-detalle");
      imptotal += parseFloat($("[data-orden-detalle='" + orden + "'] .imptotal").val());
      impneto += parseFloat($("[data-orden-detalle='" + orden + "'] .impneto").val());
      impiva = parseFloat(impneto - imptotal);
    };
  });

  $("#importetotal").val(imptotal.toFixed(2));
  $("#importeiva").val(impiva.toFixed(2));
  $("#importeneto").val(impneto.toFixed(2));

  comprobarestadocuenta();
}


/*Mensajes*/

function mostrarmensajes(mensaje, tipomensaje) {
  ocultarmensaje();
  switch (tipomensaje) {
    case "success":
      $("#mensaje").addClass("alert-success");
      break;
    case "info":
      $("#mensaje").addClass("alert-info");
      break;
    case "warning":
      $("#mensaje").addClass("alert-warning");
      break;
    case "danger":
      $("#mensaje").addClass("alert-danger");
      break;
    default:

  }
  $("#textomensaje").html(mensaje);
  $("#mensaje").removeClass("oculto");
}

function ocultarmensaje() {
  $("#textomensaje").empty();
  $("#mensaje").addClass("oculto").removeClass("alert-success").removeClass("alert-info").removeClass("alert-warning").removeClass("alert-danger");
}

function tipoTercero() {

  var seleccionado = $("#tercero").val();

  $("#tercero").empty();

  if ($("#tipomovimiento").val() == 1) {//es venta
    var tipotercero = 2;
  } else {//es compra
    var tipotercero = 1;
  };

  var accion = "retornarterceros";

  $.ajax({
    url: "api.php",
    type: "post",
    crossDomain: true,
    data: { accion: accion, tipotercero: tipotercero },
    dataType: "json",
    success: function (response) {

      var html = '<option value="0">Seleccione</option>';
      for (var i = 0; i < Object.keys(response["exito"]).length; i++) {
        if (response["exito"][i]["id"] == seleccionado) {
          html += '<option selected value="' + response["exito"][i]["id"] + '">' + response["exito"][i]["denominacion"] + '</option>';
        } else {
          html += '<option value="' + response["exito"][i]["id"] + '">' + response["exito"][i]["denominacion"] + '</option>';
        };
      };
      $("#tercero").append(html);
      $("#tercero").select2({
        "width": "100%"
      });
      tercerotabulado();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

/*Guardar*/

function guardar(accion) {

  var comprobanteActual = $("#comprobante option:selected").text()

  window.recuperareliminados = [];

  ocultarmensaje();
  mostrarmensajes('Procesando ' + comprobanteActual, 'info');
  var cabecera = {};
  var detalles = {};
  var elementoscabecera = [
    "idcabecera",
    "tipomovimiento",
    "contable",
    "importetotal",
    "importeiva",
    "importeneto",
    "fecha",
    "vigencia",
    "comprobante",
    "tercero",
    "doctercero",
    "nomtercero",
    "tipodoctercero",
    "puntoventa",
    "numerocomprobante",
    "cae",
    "vtocae",
    "archivo",
    "comentarios",
    "condicionventa"
  ];

  var elementosdetalle = [
    "origenimportacion",
    "cantidadimporto",
    "cantidad",
    "unidadmedida",
    "producto",
    "productocustom",
    "impunit",
    "imptotal",
    "alicuotaiva",
    "imptotal",
    "impneto"
  ];

  $.each(elementoscabecera, function (index, value) {
    if ($("#" + value).prop("tagName") == "INPUT") {
      if ($("#" + value).prop("type") == "checkbox") {
        if ($("#" + value).is(':checked')) {
          cabecera[value] = "1";
        } else {
          cabecera[value] = "0";
        };
      } else {
        cabecera[value] = $("#" + value).val();
      };
    } else {
      cabecera[value] = $("#" + value).val();
    };
  });

  var cantdet = $('#detalle tbody tr').length;
  for (var i = 1; i < cantdet; i++) {
    var detalle = {};
    $.each(elementosdetalle, function (index, value) {
      detalle[value] = $("[data-orden-detalle]:eq(" + i + ") ." + value).val();
    });
    detalles[i] = detalle;
  };

  var accion = accion;

  $.ajax({
    url: "api.php",
    type: "post",
    crossDomain: true,
    data: { accion: accion, cabecera: cabecera, detalles: detalles },
    dataType: "json",
    success: function (response) {
      if (Object.keys(response["error"]).length > 0) {
        var errores = "";
        for (var i = 0; i < response["error"].length; i++) {
          errores = errores + response["error"][i] + " </br> ";
        };
        mostrarmensajes(errores, 'danger');
      } else {
        mostrarmensajes("Guardado " + comprobanteActual, 'success');

        //Si hay otro comprobante en cola
        if ($(".comprobante").length > 1) {

          mostrarmensajes('Auditando ' + comprobanteActual, 'info');

          //Audito

          var accion = "auditar";
          $.ajax({
            url: "api.php",
            type: "post",

            //crossDomain: true,
            data: { accion: accion, id: response.exito },
            dataType: "json",
            success: function (response2) {
              if (response2["exito"]["autoriza"] == false) {
                var html = comprobanteActual + " se auditó pero no se procesó";
                var tipo = "success";
              } else {
                if (response2["facturaelectronica"]["FeCabResp"]["Resultado"] == 'R') {
                  var html = "<h6>" + comprobanteActual + " Rechazado</h6>";
                  var tipo = "danger";
                  var parar = true;
                } else {
                  var html = "<h6>" + comprobanteActual + " Aprobado</h6>";
                  var tipo = "success";
                };

                if (response2["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"] != undefined) {

                  html += '<h5>Observaciones</h5>'
                  html += '<ul>'
                  if (response2["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"][0] != undefined) {
                    for (var i = 0; i < Object.keys(response2["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"]).length; i++) {
                      html += '<li>' + response2["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"][i]["Msg"] + '</li>'
                    };
                  } else {
                    html += '<li>' + response2["facturaelectronica"]["FeDetResp"]["FECAEDetResponse"]["Observaciones"]["Obs"]["Msg"] + '</li>'
                  };
                  html += '</ul>'
                }

                if (response2["facturaelectronica"]["Errors"] != undefined) {
                  html += '<h5>Errores</h5>'
                  html += '<ul>'
                  if (response2["facturaelectronica"]["Errors"]["Err"][0] != undefined) {
                    for (var i = 0; i < Object.keys(response2["facturaelectronica"]["Errors"]["Err"]).length; i++) {
                      html += '<li>' + response2["facturaelectronica"]["Errors"]["Err"][i]["Msg"] + '</li>'
                    };
                  } else {
                    html += '<li>' + response2["facturaelectronica"]["Errors"]["Err"]["Msg"] + '</li>'
                  };
                  html += '</ul>'
                }

              };

              mostrarmensajes(html, tipo);

              if (parar) {
                return;
              }


              //Paso al comprobante siguiente
              $("#comprobante").val($(".comprobante:eq(1)").val());
              obtenerNumeroComprobante();
              $(".comprobante-wrapper:eq(0)").remove();

              $('#detalle tbody tr').each(function (i, elemento) {
                if ($(elemento).attr("data-orden-detalle") != 0) {
                  if ($(elemento).find(".producto").is(":visible") && $(elemento).find(".producto").hasClass("select2-hidden-accessible")) {
                    $(elemento).find(".producto").select2("destroy");
                  };

                  $(elemento).remove();
                }
              });

              //importarmovimiento

              importarmovimiento(response.exito);

            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
          });

        } else {
          localStorage.setItem("preguntar", 0);
          window.parent.location = 'movimientoslist.php';
        }
      };
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });

}


function movimientosparaimportar() {

  ocultarmensaje();
  if ($("#doctercero").val() == 0) {
    mostrarmensajes("Antes debe seleccionar un tercero", "danger");
  } else {

    $("#tercero").attr("disabled", true).attr("readonly", true);
    $("#tipodoctercero").attr("disabled", true).attr("readonly", true);
    $("#doctercero").attr("disabled", true).attr("readonly", true);

    var tercero = $("#tercero").val();
    //Si contable esta chequeado enviar 1 sino 0
    
    if($("#contable").is(":checkbox")){
      if($("#contable").is(":checked")){
        var contable = 1;
      }else{
        var contable = 0;
      }
    }else{
      var contable = $("#contable").val();
    }

    var tipomovimiento = $("#tipomovimiento").val();
    var comprobante = $("#importar-comprobante").val();
    var accion = "movimientosparaimportar";

    $("#modalimportar tbody").empty();

    $.ajax({
      url: "api.php",
      type: "post",
      crossDomain: true,
      data: { accion: accion, tercero: tercero, comprobante: comprobante, tipomovimiento: tipomovimiento, contable: contable },
      dataType: "json",
      success: function (response) {
        console.log(response);
        if (Object.keys(response["exito"]).length > 0) {
          var html = "";
          for (var i = 0; i < Object.keys(response["exito"]).length; i++) {
            html += '<tr>'
            html += '<td><a href="javascript:void(0);" onclick="importarmovimiento(' + response["exito"][i]["id"] + ', this)"><i class="fa fa-2x fa-check-circle-o" title="Seleccionar movimiento" aria-hidden="true"></i></a></td>'
            html += '<td>' + response["exito"][i]["ptoVenta"] + '-' + response["exito"][i]["nroComprobante"] + '</td>'
            html += '<td>' + response["exito"][i]["fecha"] + '</td>'
            html += '<td>' + response["exito"][i]["importeTotal"] + '</td>'
            html += '<td>' + response["exito"][i]["cantdetalles"] + '</td>'
            html += '</tr>'
          };
          $("#modalimportar tbody").append(html);
          $("#modalimportar").modal('show');

        } else {
          $("#modalimportar").modal('show');
          //mostrarmensajes("No hay movimientos disponibles para este tercero","danger");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  }
}

function cambiarcantidadimportada(elemento) {

  var accion = "cambiarcantidadimportada";
  var id = $(elemento).parents("tr").attr("data-origen");
  var cantidadingresada = parseFloat($(elemento).val());

  var cantidad = parseFloat($(elemento).parents("tr").data("cantidad-reservada")) + cantidadingresada;

  $.ajax({
    url: "api.php",
    type: "post",
    //crossDomain: true,
    data: { accion: accion, id: id, cantidad: cantidad },
    dataType: "json",
    success: function (response) {

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });


  calculavaloresindividuales(elemento);
}

function importarmovimiento(id, elemento) {

  if (elemento) {
    $(elemento).hide()
  }

  if ($("#importar-comprobante").val() == "c") {

    //Es Cotización

    var accion = "importarcotizacion";

    $.ajax({
      url: "api.php",
      type: "post",
      crossDomain: true,
      data: { accion: accion, id: id },
      dataType: "json",
      success: function (response) {

        if (Object.keys(response["exito"]).length > 0) {

          //Por cada detalle importado

          for (var i = 0; i < Object.keys(response["exito"]).length; i++) {

            agregardetalle(
              response["exito"][i]["idarticulo"],
              response["exito"][i]["cantidad"],
              response["exito"][i]["preciocotizado"],
            );

          };
        }
        $("#modalimportar").modal('hide');
        $("#modal-pendientes").modal('hide');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
        $(elemento).show()
      }
    });


  } else {

    var accion = "importarmovimiento";

    $.ajax({
      url: "api.php",
      type: "post",
      crossDomain: true,
      data: { accion: accion, id: id },
      dataType: "json",
      success: function (response) {

        if (Object.keys(response["exito"]).length > 0) {

          var max = 0;

          //Guardo el máximo data-orden-detalle
          $.each($('tbody tr'), function (index, value) {

            if (parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle")) > max) {

              max = parseInt($("tbody tr:eq(" + index + ")").attr("data-orden-detalle"));

            };

          });

          //////////

          //Por cada detalle importado

          for (var i = 0; i < Object.keys(response["exito"]).length; i++) {

            //recuperareliminados.splice(response["exito"][i]["id"],1);

            //Si ese origen ya se importó
            if ($("[data-origen='" + response["exito"][i]["id"] + "']").length > 0) {
              //Le sumo la cantidad
              //$("[data-origen='" + response["exito"][i]["id"] + "']").data("cantidad-importada",response["exito"][i]["cantidadimportar"]);
              //$("[data-origen='" + response["exito"][i]["id"] + "']").data("cantidad-reservada",response["exito"][i]["cantidadreservada"]);
              $("[data-origen='" + response["exito"][i]["id"] + "'] .cantidad").val(parseFloat(response["exito"][i]["cantidadimportar"]) + parseFloat($("[data-origen='" + response["exito"][i]["id"] + "'] .cantidad").val()));
              //Si no se importó
            } else {

              //Verifico si el precio cambió

              var idarticulo = response["exito"][i]["codProducto"];

              var accion = "importearticulo";
              var idtercero = $("#tercero").val();
              var tipomovimiento = $("#tipomovimiento").val();
              var unidadmedida = response["exito"][i]["idUnidadMedida"]

              var clasecambioprecio = "";

              window.responseimp = response;

              $.ajax({
                url: "api.php",
                type: "post",
                async: false,
                //crossDomain: true,
                data: { accion: accion, idarticulo: idarticulo, idtercero: idtercero, tipomovimiento: tipomovimiento, unidadmedida: unidadmedida },
                dataType: "json",
                success: function (responseprecio) {

                  //Renderizo el detalle

                  responseimportacion = window.responseimp["exito"][i];



                  var html = "";

                  max++;

                  html += '<td><input type="hidden" class="cantidadimporto" value="' + responseimportacion["cantidadimportar"] + '"/><input type="hidden" class="origenimportacion" value="' + responseimportacion["id"] + '"/><a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a></td>'
                  html += '<td><input min="0" onchange="cambiarcantidadimportada(this)" class="form-control cantidad" value="' + responseimportacion["cantidadimportar"] + '" type="number"></td>'
                  html += '<td><input disabled read-only class="form-control producto" value="' + responseimportacion["codProducto"] + '" type="hidden"><input disabled read-only class="form-control productocustom" value="' + responseimportacion["nombreProducto"] + '" type="hidden"><input disabled read-only class="form-control" value="' + responseimportacion["codProducto"] + " " + responseimportacion["nombreProducto"] + ' ' + responseimportacion["articulo"] + '" type="text"></td>'
                  html += '<td><input disabled read-only class="form-control unidadmedida" value="' + responseimportacion["idUnidadMedida"] + '" type="hidden"><input disabled read-only class="form-control" value="' + responseimportacion["denominacionCorta"] + '" type="text"></td>'

                  if (parseFloat(responseprecio["exito"]["precio"]) != parseFloat(responseimportacion["importeUnitario"])) {

                    //El precio cambió

                    html += '<td><input onclick="infoprecio(this)" data-actualizo-precio="false" data-viejo-importe="' + responseimportacion["importeUnitario"] + '" data-nuevo-importe="' + responseprecio["exito"]["precio"] + '" onchange="calculavaloresindividuales(this)" class="form-control cambio-precio impunit" value="' + responseimportacion["importeUnitario"] + '" type="number"></td>'

                  } else {

                    //El precio no cambió

                    html += '<td><input onchange="calculavaloresindividuales(this)" class="form-control impunit" value="' + responseimportacion["importeUnitario"] + '" type="number"></td>'
                  }

                  html += '<td><input disabled read-only class="form-control imptotal" value="' + responseimportacion["importeTotal"] + '" type="number"><input disabled read-only class="form-control impiva" value="' + responseimportacion["importeIva"] + '" type="hidden"></td>'
                  html += '<td><select disabled read-only class="form-control alicuotaiva" id=""><option value="' + responseimportacion["alicuotaIva"] + '">' + responseimportacion["iva"] + '</option></select></td>'
                  //html+='<td><input type="hidden" disabled read-only class="form-control alicuotaiva" value="'+response["exito"][i]["alicuotaIva"]+'"><input type="number" disabled read-only class="form-control" value="'+response["exito"][i]["iva"]+'"></td>'
                  html += '<td><input disabled read-only class="form-control impneto" value="' + responseimportacion["importeNeto"] + '" type="number"></td>'

                  $("#detalle tbody").append('<tr data-cantidad-importada="' + responseimportacion["cantidadimportar"] + '" data-cantidad-reservada="' + responseimportacion["cantidadreservada"] + '" data-origen="' + responseimportacion["id"] + '" data-orden-detalle="' + (max) + '">' + html + '</tr>');


                },
                error: function (jqXHR, textStatus, errorThrown) {
                  console.log(textStatus, errorThrown);
                }
              });

            };

            actualizarcontadordetalles();
            calculatodosindividuales();

          };
        }
        $("#modalimportar").modal('hide');
        $("#modal-pendientes").modal('hide');

      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
        $(elemento).show()
      }
    });
  }

}

function infoprecio(elemento) {

  //Guardo el orden del detalle
  var orden = $(elemento).parents("tr").attr("data-orden-detalle");

  //Guardo precio viejo, precio nuevo y estado de actualización
  var precioviejo = $(elemento).data("viejo-importe");
  var precionuevo = $(elemento).data("nuevo-importe");
  var actualizo = $(elemento).data("actualizo-precio");

  //Renderizo el contenido del modal
  var html = '<div class="row">';
  html += '<div class="col-md-4 text-right"><h6>Precio Importado</h6>' + precioviejo + '</div>'

  html += '<div class="col-md-4 text-center">'

  html += '<div>'
  html += '<label class="switch">'

  //Muestro el checkbox dependiendo el estado de actualización
  if (actualizo) {

    html += '<input checked onchange="actualizarPrecio(' + orden + ', this, ' + precioviejo + ', ' + precionuevo + ')" type="checkbox">'


  } else {

    html += '<input onchange="actualizarPrecio(' + orden + ', this, ' + precioviejo + ', ' + precionuevo + ')" type="checkbox">'
  }

  html += '<span class="slider round"></span>'
  html += '</label>'
  html += '</div>'

  html += '</div>'
  html += '<div class="col-md-4 text-left"><h6>Precio Actual</h6>' + precionuevo + '</div>'

  html += '</div>'

  $("#modal-actualizar-precio .modal-body").empty();
  $("#modal-actualizar-precio .modal-body").append(html);

  $("#modal-actualizar-precio").modal();

}

function actualizarPrecio(orden, elemento, precioviejo, precionuevo) {

  if ($(elemento).prop("checked") == true) {
    //actualiza precio
    $('#detalle tr[data-orden-detalle = ' + orden + '] .impunit').val(precionuevo).removeClass("cambio-precio").addClass("actualizo-precio").data("actualizo-precio", true).trigger("change");
  } else {
    //precio viejo
    $('#detalle tr[data-orden-detalle = ' + orden + '] .impunit').val(precioviejo).removeClass("actualizo-precio").addClass("cambio-precio").data("actualizo-precio", false).trigger("change");
  }

}
