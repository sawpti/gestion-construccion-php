var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	
	//Cargamos los items al select obras
	$.post("../ajax/epago.php?op=selectObra", function(r){
	            $("#obra_id").html(r);
	            $('#obra_id').selectpicker('refresh');

	});	

  }

//Función limpiar
function limpiar()
{
	
	$("#idestadopago").val("");
	$("#obra_id").val("");
	//$("#fechaEP").val("");
	$("#obns1EP").val("");
	$("#obns2EP").val("");
	$("#numEPObra").val("");
	$("#anexo").val("");
	$("#numFactura").val("");
	$("#estadoEP").val("");
	$("#impuesto").val("0");
  

	$("#precio_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fechaEP').val(today);

    //Marcamos el primer tipo_impuesto
    $("#tipo_impuesto").val("NETO");
	$("#tipo_impuesto").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
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
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
	//reload();
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
					url: '../ajax/epago.php?op=listar',
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
		url: "../ajax/epago.php?op=guardaryeditar",
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

function mostrar(idestadopago)
{
	$.post("../ajax/epago.php?op=mostrar",{idestadopago : idestadopago}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
	
		$("#idestadopago").val(data.idestadopago);
		$("#obra_id").val(data.obra_id);
		$("#obra_id").selectpicker('refresh');
		$("#fechaEP").val(data.fechaEP);
		$("#obns1EP").val(data.obns1EP);
		$("#obns2EP").val(data.obns2EP);
		$("#numEPObra").val(data.numEPObra);
		$("#anexo").val(data.anexo);
		$("#numFactura").val(data.numFactura);
		$("#estadoEP").val(data.estadoEP);
		$("#estadoEP").selectpicker('refresh');
		$("#impuesto").val(data.impuesto);

		// Actualisamos el select 'tipo_impuesto' de acuerdo al valor de 'impuesto'
        if (data.impuesto=='0.00')
        {

           $("#tipo_impuesto").val("NETO");
		   $("#tipo_impuesto").selectpicker('refresh');

        }else {
        	$("#tipo_impuesto").val("IVA"); 
		    $("#tipo_impuesto").selectpicker('refresh');

		}

		//Ocultar y mostrar los botones
		$("#btnGuardar").show(); // muetra btn para guardar solo los cambios de la cabecera
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/epago.php?op=listarDetalle&id="+idestadopago,function(r){
	        $("#detalles").html(r);
	});	
}

//Función para anular registros
function anular(idestadopago)
{
	bootbox.confirm("¿Está Seguro de anular el EP?", function(result){
		if(result)
        {
        	$.post("../ajax/epago.php?op=anular", {idestadopago : idestadopago}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
//<script src="global.js"></script>
var impuesto=19;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_impuesto").change(marcarImpuesto);


function marcarImpuesto()
  {
  	var tipo_impuesto=$("#tipo_impuesto option:selected").text();
  	if (tipo_impuesto=='IVA')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
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
    	alert("Error al ingresar el detalle, revisar los datos del producto o servicio");
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
    $("#total_estadopago").val(total);
   
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