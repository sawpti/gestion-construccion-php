var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	//Cargamos los items al select cliente
	cargarEmpresas();
	
	//Cargamos los items al select contacto depediento del item selecionado en el select idcliente
	
	$('#idcliente').change(function() {		
	  cargarContactos();
	});


   //Cargamos los items al select comuna
    $.post("../ajax/persona.php?op=selectComuna", function(r){
                $("#idcomuna").html(r);
                $('#idcomuna').selectpicker('refresh');
 
    });
   // obtnernumro();

   /*  $.post("../ajax/cotizacion.php?op=ultimacotizacion", function(r){
	            $("#num_cotizacion").html(r);
	            //$('#idcliente').selectpicker('refresh');
	});	*/

}

//Funcion que carga las empresas en un selcet
function cargarEmpresas(){
    $.post("../ajax/cotizacion.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});	
    
}

// Función que carga el select idcontacto, dependiendo de la empresa seleccionada.
function cargarContactos()
{
	persona_id=$("#idcliente").val();
 //{nom_culuma_tabla (id_persona,variable javascript (idcliente))}
	$("#persona_id").val(persona_id);
	persona_id=$("#persona_id").val();
//	alert(persona_id+"-/---"+100);

   $.post("../ajax/cotizacion.php?op=selectContactoByPersona",{persona_id: persona_id}, function(r){
	            $("#idcontacto").html(r);
	             
	        $('#idcontacto').selectpicker('refresh');
	        });

   /* Pendiente: Alertar al usuario cuando una empresa no tenga contactos ingresados


   var lista = document.getElementById("idcontacto");

   	 alert("NUm elementos: "+lista.length());*/
}




//Función limpiar
function limpiar()
{
	$("#idcliente").val("");
	$("#idcotizacion").val("");
	$("#cliente").val("");
	$("#lugar").val("");
	$("#direccion").val("");
	$("#titulo").val("");
	$("#observaciones").val("");
	$("#etecnicas").val("");
	$("#impuesto").val("0");

	$("#idcontacto").val("");
    $("#idcomuna").val("");
   
    

	$("#precio_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_impuesto").val("NETO");
	$("#tipo_impuesto").selectpicker('refresh');
	//obtnernumro();

       


}

function obtnernumro(){
	$.post("../ajax/cotizacion.php?op=ultimacotizacion", function(data,status)
	{
		//console.log("sdafsdf"+data.num_cotizacion);

		data = JSON.parse(data);
		if (data!==null){
			$("#num_cotizacion").val(parseInt(data.num_cotizacion) + 1);
		}
		else {
			$("#num_cotizacion").val(1);

		}
		
	});
	
          
}



//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	
    //$("#num_cotizacion").value("");

	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles=0;
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		
	   //Genero el numero de cotización
	    obtnernumro();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();

	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/cotizacion.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función ListarArticulos
function listarArticulos()
{
	tabla=$('#tblarticulos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../ajax/cotizacion.php?op=listarArticulosCotizacion',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/cotizacion.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          listar();
	    }

	});
	limpiar();
}

function mostrar(idcotizacion)
{
	$.post("../ajax/cotizacion.php?op=mostrar",{idcotizacion : idcotizacion}, function(data, status)
	{
		//$("#num_cotizacion").hide();
		//$("#num_cotizacion_label").hide();

		
		data = JSON.parse(data);		
		mostrarform(true);
		//$("#num_cotizacion").show();


		$("#idcliente").val(data.cotizacion_idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#lugar").val(data.lugar);
    	$("#direccion").val(data.direccion);
	    $("#titulo").val(data.titulo);
	    $("#observaciones").val(data.observaciones);
	    $("#etecnicas").val(data.etecnicas);
	    //$("#tipo_comprobante").val(data.tipo_comprobante);
		//$("#tipo_comprobante").selectpicker('refresh');
		//$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_cotizacion").val(data.num_cotizacion);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idcotizacion").val(data.idcotizacion);

		$("#idcontacto").val(data.idContacto);
        $("#idcontacto").selectpicker('refresh');

        $("#idcomuna").val(data.cotizacion_idcomuna);
        $("#idcomuna").selectpicker('refresh');
        
        // Actualisamos el select t'ipo_impuesto' de acuerdo al valor de 'impuesto'
        if (data.impuesto=='0.00')
        {

           $("#tipo_impuesto").val("NETO");
		   $("#tipo_impuesto").selectpicker('refresh');

        }else if (data.impuesto=='19.00'){
        	$("#tipo_impuesto").val("IVA"); 
		    $("#tipo_impuesto").selectpicker('refresh');

		}else{
			$("#tipo_impuesto").val("HONORARIO"); 
		    $("#tipo_impuesto").selectpicker('refresh');


		}
        
        

		//Ocultar y mostrar los botones
		$("#btnGuardar").show(); // muetra btn para guardar solo los cambios de la cabecera
		//$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/cotizacion.php?op=listarDetalle&id="+idcotizacion,function(r){
	        $("#detalles").html(r);
	});	
	$("#num_cotizacion").val("");
}

//Función para anular registros
function anular(idcotizacion)
{
	bootbox.confirm("¿Está Seguro de anular la cotizacion?", function(result){
		if(result)
        {
        	$.post("../ajax/cotizacion.php?op=anular", {idcotizacion : idcotizacion}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=19;
var impuestoH=10;

var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_impuesto").change(marcarImpuesto);

function marcarImpuesto()
  {
  
  	var tipo_impuesto=$("#tipo_impuesto option:selected").text();
 // 	var tipo_impuesto1=$("#tipo_impuesto option:selected").value;

 	if (tipo_impuesto=='IVA')
    {
        $("#impuesto").val(impuesto); 
    }
    else if (tipo_impuesto=='Honorario')
    {
        $("#impuesto").val(impuestoH); 
    }else{
    	$("#impuesto").val(0); 

    }

 

  }

function agregarDetalle(idarticulo,articulo,precio_venta)
  {
  	var cantidad=1;
    var descuento=0;

    if (idarticulo!="")
    {
    	var subtotal=cantidad*precio_venta;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
    	'<td><input type="number"  step="0.01" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
    	'<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>'+
    	'<td><input type="number" name="descuento[]" value="'+descuento+'"></td>'+
    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }
    else
    {
    	alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }

  function modificarSubototales()
  {
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpD=desc[i];
    	var inpS=sub[i];

    	inpS.value=(inpC.value * inpP.value)-inpD.value;
    	document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();

  }
  function calcularTotales(){
  	var sub = document.getElementsByName("subtotal");
  	var total = 0.0;

  	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}

/*	 $("#total").html("$" + formato_numeros (total,0));
    $("#total_venta").val(total);*/



	$("#total").html("$ " + total);
    $("#total_cotizacion").val(total);
   
    evaluar();
  }

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide(); 
      cont=0;
    }
  }

  function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar()
  }

init();