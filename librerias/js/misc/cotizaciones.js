/*Al salir*/

  $(document).keypress(function(e){

    switch(e.charCode) {
        case 43:
            e.preventDefault();
            agregardetalle();
            break;
        case 45:
            e.preventDefault();
            eliminardetalle($(':focus'));
            break;
        case 10:
            e.preventDefault();
            var elementoorigen = $(':focus');

            if (e.ctrlKey == true) {
              
              if ($(':focus').parents("tr").find("td .articulocustom").is(":visible")) {
                articulocustom($(':focus').parents("tr").find("td .articulocustom"));
              }else{
                articulotabulado($(':focus').parents("tr").find("td .articulotabulado"));
              };

            }

            break;             
        case 13:
          if ($(':focus').prop("tagName") != "TEXTAREA" ) {
            e.preventDefault();
            var elementoorigen = $(':focus');

            var contenedorpadre = elementoorigen.parents(".detalle");
/*
            if (elementoorigen.attr("id") == "tipomovimiento") {
              $("#fecha").focus();
            };
*/
            if (elementoorigen.hasClass("cantidad")) {
              $(contenedorpadre).find(".producto").select2("open");
              $(".select2-search__field").focus();
            };

            if (elementoorigen.hasClass("dto-articulo")) {
              $(contenedorpadre).find(".nuevo-margen").focus().select();
            };

            if (elementoorigen.hasClass("nuevo-margen")) {
              $(contenedorpadre).find(".nuevo-precio-unitario").focus().select();
            };                           

            if (elementoorigen.hasClass("nuevo-precio-unitario")) {
              $(contenedorpadre).find(".referencia").focus().select();
            };                           

            if (elementoorigen.hasClass("referencia")) {
              $(contenedorpadre).find(".precio-referencia").focus().select();
            };                           

            if (elementoorigen.hasClass("precio-referencia")) {
              $(contenedorpadre).find(".cantidad").focus().select();
            };     

          };

            break;                       
        default:
    }

  });


$(document).ready(function(){
  importeunitario();
  //Guardo el primer detalle
  window.primerdetalle = $(".detalle:eq(0)").html();
  //ocultar primer registro
  //$(".detalle:eq(0)").hide();

  //obtengo el id en caso de ser edición

  var url_string = window.location.href;
  var url = new URL(url_string);
  var id = url.searchParams.get("id");

  if (id != null) {//es edición

    //obtengo la cotización

    obtenerCotizacion(id);
    $("#guardar").attr("onclick", "guardar('editar')");
    $("#idcotizacion").val(id);

  }else{
    $("#tercero").select2({
      "width":"100%"
    });    
    aplicarselect2($(".producto"));
  };  

  calculavaloresindividuales();

  $("#tercero").focus();

  actualizarcontadordetalles();
  //tipoTercero();
})

/*Agregar y eliminar detalles*/

//Formato de resultados
function resultado (res) {
  if (res.loading) return res.text;
//  var markup = "<div><span class='resultado codigo'>" + res.id+"</span><span class='resultado'>"+ res.denominacion + "</span></div>";
  if (res.cantidadtotal > 0) {
    var clase = "bold";
  }else{
    var clase = "";
  };

  var markup = '<div class="row '+clase+'"><div class="col-md-3">'+ res.id +'</div><div class="col-md-6">'+ res.denominacion +'</div><div class="col-md-3">'+ res.precio +'</div></div>';
  
  return markup;
}

//Formato de selección
function seleccion (res) {
  return res.id+" "+res.denominacion;
}

//aplicar select2 de productos
function aplicarselect2(elemento){
  var tercero=$("#tercero").val();
  var movimiento=1;
  var accion="productos";
  var limit=5000; //límite de resultados devueltos 
  $(elemento).select2({
    language: "es",
    width:'100%',
    minimumInputLength: 2,//caracteres mínimos para empezar la búsqueda 
    ajax: {
      url: "api.php",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          page: params.page,
          limit:limit,
          accion:accion,
          tercero:tercero,
          movimiento:movimiento
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
$("#agregardetalle").click(function(){
  agregardetalle();
})

function actualizarcontadordetalles(){
  var cantidad = parseInt($(".detalle").length)-1;
  $("#contador-detalles").text(cantidad);
}

function agregardetalle(evitarselect2, edit, preventscroll){

  if (evitarselect2 == null) {
    evitarselect2 = false;
  };

  if (edit == null) {
    edit = false;
  };  

  ocultarmensaje();
  if ($("#tercero").val()=="0") {
    mostrarmensajes("Antes debe seleccionar un tercero","danger");
  }else{

    $("#tercero").attr("disabled",true).attr("readonly",true);

    $("#detalles").append('<div class="detalle">' + primerdetalle + '</div>');

    if(edit){
      $(".detalle:last-child .producto").removeAttr("onchange")
      $(".detalle:last-child .producto").attr("onchange", "cambiaproducto(this, true)")
    }

    if (!evitarselect2) {
      aplicarselect2($(".detalle:last-child .producto"));
    };

    $(".detalle:last-child .dto-lista").html(parseFloat($("#dto-lista").val()));
    $(".detalle:last-child .dto-cliente").html(parseFloat($("#dto-cliente").val()));    

    $(".detalle:last-child .cantidad").focus().select();

    actualizarcontadordetalles();
    importeunitario();
  }

  if(!preventscroll){
    $("html, body").animate({ scrollTop: $(document).height() }, 100);
  }

};


//Eliminar detalle
function eliminardetalle(elemento){

  if ($(".detalle").length > 1) {

    if ($(elemento).find(".producto").is(":visible") && $(elemento).find(".producto").hasClass("select2-hidden-accessible")) {
      $(elemento).find(".producto").select2("destroy");    
    };    

    $(elemento).parents(".detalle").remove();

    $(".detalle:last-child .cantidad").focus().select();  

    actualizarcontadordetalles();
    calculavaloresindividuales();

  };

}

/*Fin Agregar y eliminar detalles*/

/*Campos activos e inactivos*/
function activarcampos(elementos){
    $.each(elementos,function(index, value) {
      $(value).val(""); 
    $(value).removeAttr("disabled");
    $(value).removeAttr("readonly");
    });   
}

function desactivarcampos(elementos){
    $.each(elementos,function(index, value) {
    $(value).val("");
    $(value).attr("disabled",true);
    $(value).attr("readonly",true); 
    }); 
}


function importeunitario(){
  $(".alicuotaiva").removeAttr("disabled").removeAttr("readonly");    
}

function tercerotabulado(){

  $("#condicion-iva").empty()

  if($("#tercero").val()!=0){

      $("#ficha-tercero").attr("href","reportecomercial.php?idtercero="+$("#tercero").val());
      $("#ficha-tercero").show();

      var accion="datosterceros";
      var idtercero=$("#tercero").val();
        $.ajax({
            url: "api.php",
            type: "get",
            //crossDomain: true,
            data: {accion:accion,idtercero:idtercero},
            dataType: "json",
            success: function (response) {

                if(response.exito[0]){
                  $("#condicion-iva").html("<br>("+response.exito[0].denominacionCondicionIVA+")")
                }

                if (response["exito"][0]["dtoCliente"] != "") {
                  $("#dto-cliente").val(response["exito"][0]["dtoCliente"]);
                }else{
                  $("#dto-cliente").val(parseInt(0));
                };

                if (response["exito"][0]["descuento"] != "") {
                  $("#dto-lista").val(response["exito"][0]["descuento"]);
                }else{
                  $("#dto-lista").val(0);
                };                

                $("#comprobante option").removeAttr('disabled');
                
                $("#limitedescubierto").val(response["exito"][0]["limiteDescubierto"]);

                if (!$("#condicionventa").data("edita") ) {
                  $("#condicionventa").val(response["exito"][0]["condicionVenta"]);
                };

                $.each(response["exito"]["comprobantesbloqueados"], function (index, value) {

                  var valor = parseFloat(value["idComprobanteBloqueado"]);

                  $("#comprobante option").each(function( index ) {
                    if ($(this).val() == valor) {
                      $(this).attr('disabled','disabled');
                      $(this).removeAttr('selected');
                    };
                  });                  

                  //estadodecuenta+=valor;

                });                

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });

      var accion = "estadodecuenta";
      var tercero = $("#tercero").val();
      var tipomovimiento = 1;
        $.ajax({
            url: "api.php",
            type: "get",
            //crossDomain: true,
            data: {accion:accion,tercero:tercero,tipomovimiento:tipomovimiento},
            dataType: "json",
            success: function (response) {

              var estadodecuenta=0;

                $.each(response["exito"], function (index, value) {
                  var valor = parseFloat(value["importeNeto"]);
                  if (value["comportamiento"] == 2) {
                    valor = valor *-1;
                  };

                  estadodecuenta+=valor;

                });                 

                $("#estadocuenta").val(parseFloat(estadodecuenta).toFixed(2));

                comprobarestadocuenta();

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
     

  }else{
 
    $("#limitedescubierto").val("");
    $("#estadocuenta").val("");
    comprobarestadocuenta();

    $("#ficha-tercero").hide();

  }
}

function comprobarestadocuenta(){

  var limitedescubierto = parseFloat($("#limitedescubierto").val());

  if (limitedescubierto != "") {
    
    var estadocuenta = parseFloat($("#estadocuenta").val());
    var compraactual = parseFloat($("#importeneto").val());

    var credito = limitedescubierto - estadocuenta - compraactual;

    if (credito <= 0) {
      mostrarmensajes("El cliente alcanzó el límite de descubierto", "danger");
    }else{
      ocultarmensaje()
    };

  }else{
    ocultarmensaje();
  };
}


$("#tercero").change(function(){
  tercerotabulado();
})


function cambiaproducto(elemento, edit){

  var orden = $(".detalle").index($(elemento).parents(".detalle"));

  var tipomovimiento = 1;
  var idarticulo = $(elemento).val();
  var accion = "datosarticulo";

    $.ajax({
        url: "api.php",
        type: "get",
        //crossDomain: true,
        data: {accion:accion,idarticulo :idarticulo, tipomovimiento:tipomovimiento},
        dataType: "json",
        success: function (response) {
          if(!edit){
            $(".detalle:eq("+orden+") .alicuotaiva").val(parseInt(response["exito"][0]["iva"]));
          }
          $(".detalle:eq("+orden+") .unidadmedida").empty();
          if(response.exito.unidades.length > 0){
            var opciones = ''
            response.exito.unidades.map((item, i) => {
              opciones += '<option '+(i==0?'selected':'')+' value="'+item.id+'">'+item.denominacion+'</option>'
            })
            $(".detalle:eq("+orden+") .unidadmedida").html(opciones);
          }

          importearticulo(elemento, edit);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });   

}


/*Importe de artículo*/

function importearticulo(elemento, edit){

  var orden = $(".detalle").index($(elemento).parents(".detalle"));

  var idarticulo = $(".detalle:eq("+orden+") .producto").val();
  var unidadmedida = $(".detalle:eq("+orden+") .unidadmedida").val();
  var accion="importe-articulo-cotizacion";
  var idtercero = $("#tercero").val();

  $.ajax({
      url: "api.php",
      type: "get",
      //crossDomain: true,
      data: {
        accion:accion,
        idarticulo :idarticulo,
        idtercero:idtercero,
        unidadmedida : unidadmedida
      },
      dataType: "json",
      success: function (response) {
         $(".detalle:eq("+orden+") .precio-unitario").html(response["exito"]["precioventa"]);
         $(".detalle:eq("+orden+") .dto-categoria").html(response["exito"]["dtocategoria"]);
         $(".detalle:eq("+orden+") .dto-subcategoria").html(response["exito"]["dtosubcategoria"]);
         $(".detalle:eq("+orden+") .costo").html(response["exito"]["costo"]);
         $(".detalle:eq("+orden+") .margen-actual").html(response["exito"]["rentabilidad"]);
         if(!edit){
          $(".detalle:eq("+orden+") .dto-articulo").val(response["exito"]["dtoarticulo"]);
         }

        obtenerUltimaCotizacion(orden, idarticulo, idtercero);

        var elemento = "dtoarticulo";
        $(".detalle:eq("+orden+")").find(".alicuotaiva ").focus();        

        calculavaloresindividuales(orden, elemento);

      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  }); 

}

function obtenerUltimaCotizacion(orden, idarticulo, idtercero){

  var accion = 'obtener-ultima-cotizacion'

  $.ajax({
      url: "api.php",
      type: "get",
      //crossDomain: true,
      data: {accion:accion,idarticulo :idarticulo ,idtercero:idtercero},
      dataType: "json",
      success: function (response) {

        var contenedor = $(".detalle:eq("+orden+") .row:first-child");

        if (response.error) {
          
          //Nunca se cotizó
          $(contenedor).css("background-color", "rgb(230,230,230)");

          $(contenedor).find(".ver-historial").hide();

        }else{

          $(contenedor).find(".ver-historial").show();

          /*
            0 = No aprobada,
            1 = Aprobada parcial
            2 = Aprobada total
          */

          if (response.exito == 2) {
            $(contenedor).css("background-color", "rgba(0,100,0,0.3)");
          }else if (response.exito == 1){
            $(contenedor).css("background-color", "rgba(200,200,0,0.3)");
          }else if (response.exito == 0){
            $(contenedor).css("background-color", "rgba(255, 000, 0, 0.3)");
          }        

        }     

      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  });   

}

function verHistorial(elemento){

  var idarticulo = $(elemento).parents(".detalle").find(".producto").val();
  var idtercero = $("#tercero").val();

  var accion = 'obtener-historial-cotizaciones'

  $.ajax({
      url: "api.php",
      type: "get",
      //crossDomain: true,
      data: {accion:accion,idarticulo :idarticulo ,idtercero:idtercero},
      dataType: "json",
      success: function (response) {

         if (!response.error) {
          
          $("#tabla-historico tbody").empty();

          var html = '';

          response.exito.map(function(value, key){

            if (value.aprobacion == 2) {
              var bg = "rgba(0,100,0,0.3)";
            }else if (value.aprobacion == 1){
              var bg = "rgba(200,200,0,0.3)";
            }else if (value.aprobacion == 0){
              var bg = "rgba(255, 000, 0, 0.3)";
            }  

            html += '<tr style="background-color:'+bg+'"><td>'+value.fecha+'</td><td>'+value.cantidad+' / '+value.cantidadAprobada+'</td><td>'+value.precioReferencia+'</td><td>'+value.precioCotizado+'</td><td>'+value.margenCotizado+'</td></tr>'    
          })

          $("#tabla-historico tbody").html(html);

          $("#modal-historico").modal();

         }

      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  });   

}


/*Calcula valores*/

function calculatodosindividuales(){
  $('.cantidad').trigger('change');
}

function cambia(tipo, elemento){
	
  var orden = $(".detalle").index($(elemento).parents(".detalle"));

  if (tipo == "iva") {
    
    var elementoorigen = $(':focus');
    var contenedorpadre = elementoorigen.parents(".detalle");

    $(contenedorpadre).find(".dto-articulo").focus().select();    
  }
  calculavaloresindividuales(orden, tipo);
}

function calculavaloresindividuales(orden, elemento){

  if (elemento == "dtoarticulo") {

    var margenfinal = parseFloat($(".detalle:eq("+orden+") .margen-actual").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-lista").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-cliente").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-categoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-subcategoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-articulo").val())
    

    $(".detalle:eq("+orden+") .nuevo-margen").val(parseFloat(margenfinal).toFixed(2));

    var nuevoprecio = (parseFloat($(".detalle:eq("+orden+") .costo").html()) * parseFloat(margenfinal) / 100) + parseFloat($(".detalle:eq("+orden+") .costo").html());


    $(".detalle:eq("+orden+") .nuevo-precio-unitario").val(parseFloat(nuevoprecio).toFixed(2));

  };

  if (elemento == "margenfinal") {

    var dtoarticulo = parseFloat($(".detalle:eq("+orden+") .margen-actual").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-lista").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-cliente").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-categoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-subcategoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .nuevo-margen").val())
    
    $(".detalle:eq("+orden+") .dto-articulo").val(parseFloat(dtoarticulo).toFixed(2));

    var nuevoprecio = (parseFloat($(".detalle:eq("+orden+") .costo").html()) * parseFloat( $(".detalle:eq("+orden+") .nuevo-margen").val() ) / 100) + parseFloat($(".detalle:eq("+orden+") .costo").html());

    $(".detalle:eq("+orden+") .nuevo-precio-unitario").val(parseFloat(nuevoprecio).toFixed(2));

  };  

  if (elemento == "preciounitario") {

    var costo = parseFloat($(".detalle:eq("+orden+") .costo").html());
    var nuevopreciounitario = parseFloat( $(".detalle:eq("+orden+") .nuevo-precio-unitario").val());

    var margenfinal = (( nuevopreciounitario / costo) - 1) * 100;
    
    $(".detalle:eq("+orden+") .nuevo-margen").val(parseFloat(margenfinal).toFixed(2));

    var dtoarticulo = parseFloat($(".detalle:eq("+orden+") .margen-actual").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-lista").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-cliente").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-categoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .dto-subcategoria").html()) -
    parseFloat($(".detalle:eq("+orden+") .nuevo-margen").val())
    
    $(".detalle:eq("+orden+") .dto-articulo").val(parseFloat(dtoarticulo).toFixed(2));

  };

  calculapreciototal(orden);  

}

function calculapreciototal(orden){
  if ($(".detalle:eq("+orden+") .cantidad").val() > 0) {
    if (isNaN(parseFloat($(".detalle:eq("+orden+") .alicuotaiva option:selected").text()))) {
      var importeiva = 0;
    }else{
      var importeiva = parseFloat($(".detalle:eq("+orden+") .nuevo-precio-unitario").val()) * parseFloat($(".detalle:eq("+orden+") .alicuotaiva option:selected").text()) /100;
    };

    $(".detalle:eq("+orden+") .importe-iva").html(importeiva.toFixed(2));
    var subtotal = parseFloat($(".detalle:eq("+orden+") .nuevo-precio-unitario").val()) + parseFloat(importeiva);
    $(".detalle:eq("+orden+") .subtotal").html(subtotal.toFixed(2));
    $(".detalle:eq("+orden+") .importe-total").html((subtotal * $(".detalle:eq("+orden+") .cantidad").val()).toFixed(2));
  }else{
    $(".detalle:eq("+orden+") .importe-iva").html(0);
    $(".detalle:eq("+orden+") .subtotal").html(0);
    $(".detalle:eq("+orden+") .importe-total").html(0);
  };

  calculatotales();

}

function calculatotales(){
 
  var neto = 0;
  var iva = 0;
  var total = 0;

  $.each($('.detalle'), function( index, value ) {
    if ( !isNaN(parseFloat($(this).find(".importe-total").html())) ) {
      total += parseFloat($(this).find(".importe-total").html());
    };
    if (!isNaN(parseFloat($(this).find(".importe-iva").html()))) {
      iva += parseFloat($(this).find(".importe-iva").html()) * parseInt($(this).find(".cantidad").val());
    };    
  });

  neto = total - iva;

  $("#importeneto").val(neto.toFixed(2));
  $("#importeiva").val(iva.toFixed(2));
  $("#importetotal").val(total.toFixed(2));

}


/*Mensajes*/

function mostrarmensajes(mensaje, tipomensaje){
  ocultarmensaje();
  switch(tipomensaje) {
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

function ocultarmensaje(){
  $("#textomensaje").empty();
  $("#mensaje").addClass("oculto").removeClass("alert-success").removeClass("alert-info").removeClass("alert-warning").removeClass("alert-danger");
}

function tipoTercero(){

  var seleccionado=$("#tercero").val();

  $("#tercero").empty();
  var tipotercero=2;

  var accion="retornarterceros";

  $.ajax({
      url: "api.php",
      type: "get",
      crossDomain: true,
      data: {accion:accion,tipotercero:tipotercero},
      dataType: "json",
      success: function (response) {
  			           
  			var html='<option value="0">Seleccione</option>';
    		for (var i = 0; i< Object.keys(response["exito"]).length ; i++) {
    			if (response["exito"][i]["id"]==seleccionado) {
	 	   			html +='<option selected value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
    			}else{
    				html +='<option value="'+response["exito"][i]["id"]+'">'+response["exito"][i]["denominacion"]+'</option>';
    			};
    		};
    		$("#tercero").append(html);
    		$("#tercero").select2({
    			"width":"100%"
    		});

      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  });		
}

/*Guardar*/

function guardar(accion){
  ocultarmensaje();
  mostrarmensajes('Procesando...', 'info');
  var cabecera={};
  var detalles={};
  var elementoscabecera=[
    "tercero",
    "fecha",
    "vigencia",
    "contable",
    "importeneto",
    "importeiva",
    "importetotal",
    "discriminaiva"
  ];

  if (accion == "editar") {
    elementoscabecera.push("idcotizacion");
  };

  var elementosdetalle=[
    "producto",
    "cantidad",
    "referencia",
    "precio-referencia",
    "alicuotaiva",
    "dto-articulo",
    "cantidad-aprobada",
    "nuevo-margen",
    "nuevo-precio-unitario",
    "dto-articulo"
  ];  

    $.each(elementoscabecera,function(index, value) {
      if ($("#"+value).prop("tagName")=="INPUT") {
        if ($("#"+value).prop("type")=="checkbox") {
          if ($("#"+value).is(':checked')) {
            cabecera[value]="1";
          }else{
            cabecera[value]="0";            
          };
        }else{
          cabecera[value]=$("#"+value).val();
        };
      }else{
        cabecera[value]=$("#"+value).val();
      };
    });

  var cantdet=$('#detalles .detalle').length;
  for (var i = 1; i <cantdet; i++) {
    var detalle={};
      $.each(elementosdetalle,function(index, value) {
        detalle[value] = $(".detalle:eq("+i+") ." + value).val();
      });   
    detalles[i]=detalle;
  };      

    var accion = accion + "-cotizacion";

    $.ajax({
        url: "api.php",
        type: "post",
        crossDomain: true,
        data: {accion:accion,cabecera:cabecera,detalles:detalles},
        dataType: "json",
        success: function (response) {
                 
          if (Object.keys(response["error"]).length>0) {
            var errores="";
            for (var i = 0; i<response["error"].length ; i++) {
              errores=errores+response["error"][i]+" </br> ";
            };
            mostrarmensajes(errores,'danger');
          }else{
            mostrarmensajes("Guardado",'success');
            localStorage.setItem("preguntar",0);            
            window.parent.location='cotizacioneslist.php';
          };
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    }); 
     
}


/* OBTENER COTIZACIÓN PARA EDITAR */

function obtenerCotizacion(id){

  var accion = "obtener-cotizacion";

  $.ajax({
      url: "api.php",
      type: "get",
      crossDomain: true,
      data: {accion:accion,id:id},
      dataType: "json",
      success: function (response) {
               
        if (Object.keys(response["error"]).length>0) {
          mostrarmensajes("Error al obtener cotizacion",'danger');
        }else{

          $("#fecha").val(response["exito"][0]["fecha"]);
          $("#vigencia").val(response["exito"][0]["vigencia"]);
          $("#tercero").val(response["exito"][0]["idTercero"]);
          $("#tercero").trigger("change");
          tercerotabulado();
          
          if (response["exito"][0]["contable"] == 1) {
            $("#contable").prop("checked", true);
          }else{
            $("#contable").removeAttr("checked");
          };

          if (response["exito"][0]["discriminaIVA"] == 1) {
            $("#discriminaiva").prop("checked", true);
          }else{
            $("#discriminaiva").removeAttr("checked");
          };          

          var accion="datosterceros";
          var idtercero=response["exito"][0]["idTercero"];
            $.ajax({
                url: "api.php",
                type: "get",
                //crossDomain: true,
                data: {accion:accion,idtercero:idtercero},
                dataType: "json",
                success: function (response2) {

                    //$("#dto-cliente").val(response2["exito"][0]["dtoCliente"]);
                    //$("#dto-lista").val(response2["exito"][0]["descuento"]);

                    $.each(response["exito"],function(index, value) {
                      agregardetalle(true, true);

                      if (parseInt(value.cantidadAprobada) > 0) {
                        $("#detalles .detalle:last-child .switch input").attr("checked", true);
                        $("#detalles .detalle:last-child").find(".cantidad-aprobada").val(parseInt(value.cantidadAprobada));
                        $("#detalles .detalle:last-child").find(".contenedor-cantidad-aprobada").show();
                      }else{
                        $("#detalles .detalle:last-child .cantidad-aprobada").val(0);
                      }

                      $("#detalles .detalle:last-child .cantidad").val(value.cantidad);
                      $("#detalles .detalle:last-child .referencia").val(value.referencia);
                      $("#detalles .detalle:last-child .precio-referencia").val(value.precioReferencia);
                      $("#detalles .detalle:last-child .producto").append('<option selected value="'+value.idArticulo+'">'+value.denominacionarticulo+'</option>').attr("disabled",true).trigger("change");

                      $("#detalles .detalle:last-child .alicuotaiva").val(value.idAlicuota);

                      $("#detalles .detalle:last-child .dto-articulo").val(value.dtoCliente);
                      $("#detalles .detalle:last-child .nuevo-margen").val(value.margenCotizado);
                      $("#detalles .detalle:last-child .nuevo-precio-unitario").val(value.precioCotizado);
                    });            
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            });          


        };

      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  }); 

}

