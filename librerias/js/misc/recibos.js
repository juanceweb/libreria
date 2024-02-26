


/*Al salir*/

	window.onbeforeunload = preguntarAntesDeSalir;
 
	function preguntarAntesDeSalir () {
		var respuesta;
 
		if ( localStorage.getItem("preguntar")==1 ) {
			
			respuesta = confirm ( '¿Seguro que quieres salir?' );
 			cancelarrecibo();
			if ( respuesta ) {
				window.onunload = function () {
					return true;
				}
			} else {
				return false;
			}
			
		}
	}



function cancelarrecibo(){
	if ($("#tiporecibopago").val()==2) {
			for (var i = 0; i < $(".mediopago").length; i++) {
				$(".mediopago:eq("+i+")").val(0);
				mediosdepagos($(".mediopago:eq("+i+")"));
				
			};
	};
}



  $(document).keypress(function(e){

    switch(e.charCode) {
        case 43:
            e.preventDefault();

            var elementoorigen = $(':focus');

            if (elementoorigen.hasClass("importecancelado")) {
            	elementoorigen.val($(elementoorigen).parents("tr").find(".saldo").val());
            }else{
            	listar();
            };


            break;
        case 45:
            e.preventDefault();

            break;
        case 10:
            e.preventDefault();
            var elementoorigen = $(':focus');

            if (e.ctrlKey == true) {
              


            }

            break;             
        case 13:

            e.preventDefault();
            var elementoorigen = $(':focus');
            var orden=elementoorigen.parents("tr").attr("data-orden-detalle");

            if (elementoorigen.attr("id") == "tiporecibopago") {
              $("#nrocomprobante").focus();
            };             

            if (elementoorigen.attr("id") == "nrocomprobante") {
              $("#tercero").select2("open");
            }; 


            if (elementoorigen.hasClass("importecancelado")) {
            	var orden = $(elementoorigen).parents("tr").attr("data-orden-detallemovimientos");
            	
            	if (parseInt(orden)*1+1 != $("tr[data-orden-detallemovimientos]").length) {
            		$("tr[data-orden-detallemovimientos="+parseInt(++orden)+"] .importecancelado").focus().select();
            	}else{
            		$("tr[data-detalle-orden=0] .mediopago").focus();
            	}

            }


            if (elementoorigen.hasClass("mediopago")) {
            	$(elementoorigen).parents("tr").find(".importetotal").focus().select();
            }


            if (elementoorigen.hasClass("importetotal")) {
            	if ($(elementoorigen).parents("tr").find(".mediopago").val()!=1) {
            		var orden = $(elementoorigen).parents("tr").attr("data-detalle-orden");
								$("tr[data-detalle-orden="+(++orden)+"] .mediopago").focus();

            	}else{
            		$(elementoorigen).parents("tr").find(".banco").focus().select();
            	};
            }

            if (elementoorigen.hasClass("banco")) {
            	$(elementoorigen).parents("tr").find(".nro").focus().select();
            }

            if (elementoorigen.hasClass("nro")) {
            	$(elementoorigen).parents("tr").find(".fecha").focus().select();
            }                          


            if (elementoorigen.hasClass("fecha")) {
            	$(elementoorigen).parents("tr").find(".observaciones").focus().select();
            }             

            if (elementoorigen.hasClass("observaciones")) {
            		var orden = $(elementoorigen).parents("tr").attr("data-detalle-orden");
								$("tr[data-detalle-orden="+(++orden)+"] .mediopago").focus();
            }



            break;                       
        default:
    }

  }); 




/*Agregar y eliminar detalles*/




//Agregar detalle
$("#listar").click(function(){
	listar();
})

function listar(){
	ocultarmensaje();
	if ($("#tercero").val()==0) {
		mostrarmensajes("Antes debe seleccionar un tercero","danger");
	}else{
			$("#movimientos").css("visibility","visible");
			$("#adelantos").css("visibility","visible");
			$("#tercero").attr("disabled",true).attr("readonly",true);
			$("#tiporecibopago").attr("disabled",true).attr("readonly",true);
			$("#contable").attr("disabled",true).attr("readonly",true);
				
		  var accion="filtrarmovimientos";
		  var idtercero=$("#tercero").val();
		  var tiporecibopago=$("#tiporecibopago").val();

		  if ($('#contable').is(':checked')) {
		  	var contable = 1;
		  }else{
		  	var contable = 0;
		  };

		  $.ajax({
		      url: "api.php",
		      type: "get",
		      //crossDomain: true,
		      data: {accion:accion,idtercero:idtercero,tiporecibopago:tiporecibopago, contable:contable},
		      dataType: "json",
		      success: function (response) {
						$("#movimientos tbody").empty();
		      	for (var i = 0; i <Object.keys(response["exito"]).length; i++) {

		      		var denomtercero =  response["exito"][i]["denominaciontercero"].replace(/['"]+/g, '');

		      		var html='';
									html+='<tr data-orden-detallemovimientos="'+i+'">';
		      				html+='<td style="display:inline-flex"><a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a><a style="margin-left:10px" href="javascript:void(0);" onclick="cargarTotal(this)"><i class="fa fa-2x fa-usd" title="Cargar Total" aria-hidden="true"></i></a></td>'
									html+='<td><input disabled readonly value="'+response["exito"][i]["id"]+'" class="codigocomp form-control input-sm" type="number"></td>';
									html+='<td><input disabled readonly value="'+denomtercero+'" class="denomtercero form-control input-sm" type="text"></td>';									
									html+='<td><input disabled readonly value="'+response["exito"][i]["comprobantenombre"]+'" class="tipocomp form-control input-sm" type="text"></td>';
									html+='<td style="width:80px"><input disabled readonly value="'+response["exito"][i]["fecha"]+'" class="fechacomp form-control input-sm" type="date"></td>';
									html+='<td><input disabled readonly value="'+response["exito"][i]["ptoVenta"]+'-'+response["exito"][i]["nroComprobante"]+'" class="nrocomp form-control input-sm" type="text"></td>';
									html+='<td><input disabled readonly value="'+response["exito"][i]["importeNeto"]+'" class="importetotalcomp form-control input-sm" type="number"></td>';
									html+='<td><input disabled value="'+response["exito"][i]["importeCancelado"]+'" min="'+response["exito"][i]["importeCancelado"]+'" max="'+response["exito"][i]["importeNeto"]+'" class="importeyacancelado form-control input-sm" type="number"></td>';									
									html+='<td><input disabled value="'+(parseFloat(response["exito"][i]["importeNeto"]-response["exito"][i]["importeCancelado"]).toFixed(2))+'" class="saldo form-control input-sm" type="number"></td>';																		
									html+='<td><input value="0" data-comportamiento="'+response["exito"][i]["comprobantecomportamiento"]+'" max="'+(parseFloat(response["exito"][i]["importeNeto"]-response["exito"][i]["importeCancelado"]).toFixed(2))+'" onchange="sumamovimientos(this)" class="importecancelado form-control input-sm" type="number"></td>';
									html+='</tr>';
							$("#movimientos tbody").append(html);
						};

						$(".importecancelado").eq(0).focus().select();
						                      
		      },
		      error: function(jqXHR, textStatus, errorThrown) {
		         console.log(textStatus, errorThrown);
		      }
		  });


		  var accion="filtraradelantos";

		  $.ajax({
		      url: "api.php",
		      type: "post",
		      //crossDomain: true,
		      data: {accion:accion,idtercero:idtercero,tiporecibopago:tiporecibopago},
		      dataType: "json",
		      success: function (response) {
						$("#adelantos tbody").empty();
		      	for (var i = 0; i <Object.keys(response["exito"]).length; i++) {

		      		var html='';
									html+='<tr data-orden-detalleadelanto="'+i+'">';
		      				html+='<td><a href="javascript:void(0);" onclick="eliminardetalle(this)"><i class="fa fa-2x fa-trash-o" title="Eliminar Registro" aria-hidden="true"></i></a></td>'
									html+='<td><input disabled readonly value="'+response["exito"][i]["id"]+'" class="codigoadelanto form-control input-sm" type="number"></td>';
									html+='<td><input disabled readonly value="'+response["exito"][i]["fecha"]+'" class="fechaadelanto form-control input-sm" type="date"></td>';
									html+='<td><input disabled readonly value="'+response["exito"][i]["importe"]+'" class="importetotaladelanto form-control input-sm" type="number"></td>';
									html+='</tr>';
							$("#adelantos tbody").append(html);
							calculavaloresindividuales();
						};                       
		      },
		      error: function(jqXHR, textStatus, errorThrown) {
		         console.log(textStatus, errorThrown);
		      }
		  });		  	

	}
};

function cargarTotal(elemento){
	var valor = $(elemento).parents("tr").find(".importetotalcomp").val();
	$(elemento).parents("tr").find(".importecancelado").val(valor);
	calculavaloresindividuales();
	sumamovimientos();	
}

//Eliminar detalle
function eliminardetalle(elemento){
	//console.log(elemento);
	$(elemento).parents("tr").remove();
	calculavaloresindividuales();
	sumamovimientos();

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

function mediosdepagos(elemento){

	var orden=$(elemento).parents("tr").attr("data-detalle-orden");

  var accion="tipo-medio-pago";
  var idmediopago = $(elemento).val();

  $.ajax({
      url: "api.php",
      type: "post",
      //crossDomain: true,
      data: {accion:accion,idmediopago:idmediopago},
      dataType: "json",
      success: function (response) {

				if ($("#tiporecibopago").val()==1) {//es recibo

					if (response["exito"]) {//es cheque

						activarcampos([
							$("[data-detalle-orden='"+orden+"'] .banco"),
							$("[data-detalle-orden='"+orden+"'] .nro"),
							$("[data-detalle-orden='"+orden+"'] .fecha"),
							$("[data-detalle-orden='"+orden+"'] .observaciones"),
							$("[data-detalle-orden='"+orden+"'] .importetotal")	
							]);
							$("[data-detalle-orden='"+orden+"'] .importetotal").val(0);

					}else{//es efectivo

						desactivarcampos([
							$("[data-detalle-orden='"+orden+"'] .banco"),
							$("[data-detalle-orden='"+orden+"'] .nro"),
							$("[data-detalle-orden='"+orden+"'] .fecha"),
							$("[data-detalle-orden='"+orden+"'] .observaciones")	
							]);
							$("[data-detalle-orden='"+orden+"'] .importetotal").val(0);		
					};

				}else{//es pago

					if (response["exito"]) {//es cheque

						desactivarcampos([
							$("[data-detalle-orden='"+orden+"'] .banco"),
							$("[data-detalle-orden='"+orden+"'] .nro"),
							$("[data-detalle-orden='"+orden+"'] .fecha"),
							$("[data-detalle-orden='"+orden+"'] .observaciones"),
							$("[data-detalle-orden='"+orden+"'] .importetotal"),
							$("[data-detalle-orden='"+orden+"'] .codcheque")						
							]);
						$("[data-detalle-orden='"+orden+"'] .importetotal").val(0);

							/*Modal Cheques*/

							localStorage.setItem("orden", orden);
							chequesdisponibles();
							$('#modalcheques').modal({
							  keyboard: false,
							  backdrop:"static"
							})


					}else{//es efectivo

						cambiarestadocheque($("[data-detalle-orden='"+orden+"'] .codcheque").val(),1);

						activarcampos([
							$("[data-detalle-orden='"+orden+"'] .importetotal")	
							]);
						$("[data-detalle-orden='"+orden+"'] .importetotal").val(0);

						desactivarcampos([
							$("[data-detalle-orden='"+orden+"'] .banco"),
							$("[data-detalle-orden='"+orden+"'] .nro"),
							$("[data-detalle-orden='"+orden+"'] .fecha"),
							$("[data-detalle-orden='"+orden+"'] .observaciones"),
							$("[data-detalle-orden='"+orden+"'] .codcheque")					
							]);

					};
				};
                    
      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
  });		

}

function codcheque(){
	if ($("#tiporecibopago").val()==2) {
		$(".columnacodcheque").css("display","block");
		for (var i = 0; i < $(".mediopago").length; i++) {
			$(".mediopago:eq("+i+")").val(0);
			mediosdepagos($(".mediopago:eq("+i+")"))
		};
	}else{
		$(".columnacodcheque").css("display","none");
		for (var i = 0; i < $(".mediopago").length; i++) {
			$(".mediopago:eq("+i+")").val(0);
			mediosdepagos($(".mediopago:eq("+i+")"))
		};		
	};

		$("#tiporecibopago").focus();

}

function cancelarmodal(){
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .mediopago").val(0);
	mediosdepagos($("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .mediopago"));
}

function chequesdisponibles(){
    var accion="chequesdisponibles";
    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {accion:accion},
        dataType: "json",
        success: function (response) {
						$("#modalcheques .modal-body table tbody").empty();
		      	for (var i = 0; i < Object.keys(response["exito"]).length; i++) {

		      		var html='';
									html+='<tr>';
									if (response["exito"][i]["estado"]==1) {
		      					html+='<td><a href="javascript:void(0);" onclick="seleccionarcheque('+response["exito"][i]["id"]+','+response["exito"][i]["nro"]+','+"'"+response["exito"][i]["banco"]+"'"+','+response["exito"][i]["importe"]+','+"'"+response["exito"][i]["fecha"]+"'"+')"><i class="fa fa-2x fa-check-circle-o" title="Seleccionar cheque" aria-hidden="true"></i></a></td>'
									}else{
		      					html+='<td><a href="javascript:void(0);" onclick=""><i class="fa fa-2x fa-times-circle-o" title="Cheque reservado" aria-hidden="true"></i></a></td>'										
									};
									html+='<td>'+response["exito"][i]["id"]+'</td>';
									html+='<td>'+response["exito"][i]["nro"]+'</td>';
									html+='<td>'+response["exito"][i]["banco"]+'</td>';
									html+='<td>'+response["exito"][i]["importe"]+'</td>';
									html+='<td>'+response["exito"][i]["fecha"]+'</td>';
									html+='</tr>';
							$("#modalcheques .modal-body table tbody").append(html);
						};                               	
           
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
}

function seleccionarcheque(id,nro,banco,importe,fecha){
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .importetotal").val(importe);
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .codcheque").val(id);
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .banco").val(banco);
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .nro").val(nro);
	$("#pagos [data-detalle-orden='"+localStorage.getItem("orden")+"'] .fecha").val(fecha);
	$("#modalcheques").modal('hide');
	calculavaloresindividuales();
	cambiarestadocheque(id,2);	
}

function cambiarestadocheque(id,valor){
	if (id!="") {
    var accion="cambiarestadocheque";
    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {accion:accion, id:id, valor:valor},
        dataType: "json",
        success: function (response) {                            	
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });		
	};
}

/*Filtrar Movimientos*/

function sumamovimientos(elemento){
	
	if (parseFloat($(elemento).val())>$(elemento).attr("max")) {
		$(elemento).val($(elemento).attr("max"));
	};

	var cantdetmovimientos=$('#movimientos tbody tr').length;
	var imptotalmovimientos=0;

	for (var i = 0; i <cantdetmovimientos; i++) {
		if ($("#movimientos tbody tr:eq("+i+") .importecancelado").attr("data-comportamiento")==1) {
			imptotalmovimientos = imptotalmovimientos+ parseFloat($("#movimientos tbody tr:eq("+i+") .importecancelado").val());
		}else{
			imptotalmovimientos = imptotalmovimientos - parseFloat($("#movimientos tbody tr:eq("+i+") .importecancelado").val());			
		};
	};	
	$("#sumamovimientos").val(parseFloat(imptotalmovimientos).toFixed(2));
	var diferencia=parseFloat($("#importetotal").val()) -	parseFloat($("#sumamovimientos").val()) +	parseFloat($("#sumaadelantos").val());
	$("#diferencia").val(parseFloat(diferencia).toFixed(2));
}



/*Calcula valores*/

function calculavaloresindividuales(elemento){
	var cantdet=$('#pagos tbody tr').length;
	var cantdetadelantos=$('#adelantos tbody tr').length;
	var imptotal=0;
	var imptotaladelantos=0;
	for (var i = 0; i <cantdet; i++) {
		imptotal = imptotal+ parseFloat($("[data-detalle-orden='"+(i)+"'] .importetotal").val());
	};
	for (var i = 0; i <cantdetadelantos; i++) {
		imptotaladelantos = imptotaladelantos+ parseFloat($("[data-orden-detalleadelanto='"+(i)+"'] .importetotaladelanto").val());
	};	
	$("#importetotal").val(parseFloat(imptotal).toFixed(2));
	$("#sumaadelantos").val(parseFloat(imptotaladelantos).toFixed(2));	
	var diferencia=parseFloat($("#importetotal").val()) -	parseFloat($("#sumamovimientos").val()) +	parseFloat($("#sumaadelantos").val());
	$("#diferencia").val(parseFloat(diferencia).toFixed(2));	
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


/*Guardar*/

function guardarrecibo(){
	ocultarmensaje();
	if (parseFloat($("#diferencia").val()) < 0) {
		mostrarmensajes('La suma cancelada es mayor a la suma de importes', 'danger');	
	}else if($("#importetotal").val()==0){
		mostrarmensajes('El importe es 0', 'danger');
	}else{

	mostrarmensajes('Procesando...', 'info');
	var cabecera={};
	var detalles={};
	var adelantos={};
	var movimientos={};

	var elementoscabecera=[
		"tiporecibopago",
		"tercero",
		"nrocomprobante",
		"fecha",
		"contable",
		"importetotal",
		"sumamovimientos",
		"sumaadelantos"
	];

	var elementosdetalle=[
		"mediopago",
		"importetotal",
		"codcheque",
		"banco",
		"nro",
		"fecha",
		"observaciones"
	];

	var elementosadelantos=[
		"codigoadelanto"
	];

	var elementosmovimientos=[
		"codigocomp",
		"importecancelado"
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

  var cantdet=$('#pagos tbody tr').length;
	for (var i = 0; i <cantdet; i++) {
		var detalle={};
	    $.each(elementosdetalle,function(index, value) {
			detalle[value]=$("#pagos tbody tr:eq("+i+") ." + value).val();
	    });		
		detalles[i]=detalle;
	};    	


  var cantadelantos=$('#adelantos tbody tr').length;
	for (var i = 0; i <cantadelantos; i++) {
		var adelanto={};
	    $.each(elementosadelantos,function(index, value) {
			adelanto[value]=$("#adelantos tbody tr:eq("+i+") ." + value).val();
	    });		
		adelantos[i]=adelanto;
	};    	


  var cantmovimientos=$('#movimientos tbody tr').length;
	for (var i = 0; i <cantmovimientos; i++) {
		var movimiento={};
	    $.each(elementosmovimientos,function(index, value) {
			movimiento[value]=$("#movimientos tbody tr:eq("+i+") ." + value).val();
	    });		
		movimientos[i]=movimiento;
	};    	

    var accion="guardarrecibo";

    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {accion:accion,cabecera:cabecera,detalles:detalles,movimientos:movimientos,adelantos:adelantos},
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
        		window.parent.location='recibos2Dpagoslist.php';
        	};
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });	
	};
    	

}

/*Editar*/

function editarrecibo(){
	ocultarmensaje();
	if (parseFloat($("#diferencia").val()) < 0) {
		mostrarmensajes('La suma cancelada es mayor a la suma de importes', 'danger');	
	}else if($("#importetotal").val()==0){
		mostrarmensajes('El importe es 0', 'danger');
	}else{

	mostrarmensajes('Procesando...', 'info');
	var cabecera={};
	var detalles={};
	var adelantos={};
	var movimientos={};

	var elementoscabecera=[
		"tiporecibopago",
		"tercero",
		"nrocomprobante",
		"fecha",
		"contable",
		"importetotal",
		"sumamovimientos",
		"sumaadelantos"
	];

	var elementosdetalle=[
		"mediopago",
		"importetotal",
		"codcheque",
		"banco",
		"nro",
		"fecha",
		"observaciones"
	];

	var elementosadelantos=[
		"codigoadelanto"
	];

	var elementosmovimientos=[
		"codigocomp",
		"importecancelado"
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

  var cantdet=$('#pagos tbody tr').length;
	for (var i = 0; i <cantdet; i++) {
		var detalle={};
	    $.each(elementosdetalle,function(index, value) {
			detalle[value]=$("#pagos tbody tr:eq("+i+") ." + value).val();
	    });		
		detalles[i]=detalle;
	};    	


  var cantadelantos=$('#adelantos tbody tr').length;
	for (var i = 0; i <cantadelantos; i++) {
		var adelanto={};
	    $.each(elementosadelantos,function(index, value) {
			adelanto[value]=$("#adelantos tbody tr:eq("+i+") ." + value).val();
	    });		
		adelantos[i]=adelanto;
	};    	


  var cantmovimientos=$('#movimientos tbody tr').length;
	for (var i = 0; i <cantmovimientos; i++) {
		var movimiento={};
	    $.each(elementosmovimientos,function(index, value) {
			movimiento[value]=$("#movimientos tbody tr:eq("+i+") ." + value).val();
	    });		
		movimientos[i]=movimiento;
	};    	

    var accion="editarrecibo";
    var id= $("#idcabecera").val();

    $.ajax({
        url: "api.php",
        type: "post",
        //crossDomain: true,
        data: {accion:accion,id:id, cabecera:cabecera,detalles:detalles,movimientos:movimientos,adelantos:adelantos},
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
        		window.parent.location='recibos2Dpagoslist.php';
        	};
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });	
	};
    	

}