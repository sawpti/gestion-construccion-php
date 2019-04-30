 var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    //$("#btnagregar").hide();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })

     //Cargamos los items al select comuna
    $.post("../ajax/empresa.php?op=selectComuna", function(r){
                $("#empresa_idcomuna").html(r);
                $('#empresa_idcomuna').selectpicker('refresh');
 
    });

    $("#imagenmuestra").hide();
}
 
//Función limpiar
function limpiar()
{
   	
    $("#id_empresa").val("");
   	$("#rut_empresa").val("");
   	$("#nombre_legal_empresa").val("");
	$("#nombre_fantasia_empresa").val("");
	$("#slogan_empresa").val("");
	$("#direccion_empresa").val("");
	$("#giro_empresa").val("");
	$("#ciudad_empresa").val("");
//	$("#empresa_idcomuna").val("");
	$("#fono_empresa").val("");
	$("#email_empresa").val("");
	$("#web_empresa").val("");
	//$("#condicion_empresa").val("");
	$("#nombre_contacto").val("");
	//$("#logo_empresa").val("");

    $("#imagenmuestra").attr("src","");
	$("#logo_actual_empresa").val("");




}
 
//Función mostrar formulario
function mostrarform(flag)
{
    limpiar();
    if (flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
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
                    url: '../ajax/empresa.php?op=listar',
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
//Función para guardar o editar
 
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
 
    $.ajax({
        url: "../ajax/empresa.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
 
        success: function(datos)
        {                    
              bootbox.alert(datos);           
              mostrarform(false);
              tabla.ajax.reload();
        }
 
    });
    limpiar();
}
 
function mostrar(id_empresa)
{
    $.post("../ajax/empresa.php?op=mostrar",{id_empresa : id_empresa}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);

        $("#id_empresa").val(data.id_empresa);
        $("#rut_empresa").val(data.rut_empresa);
	    $("#nombre_legal_empresa").val(data.nombre_legal_empresa);
		$("#nombre_fantasia_empresa").val(data.nombre_fantasia_empresa);
		$("#slogan_empresa").val(data.slogan_empresa);
		$("#direccion_empresa").val(data.direccion_empresa);
		$("#giro_empresa").val(data.giro_empresa);
		$("#ciudad_empresa").val(data.ciudad_empresa);
		$("#empresa_idcomuna").val(data.empresa_idcomuna);
		$("#empresa_idcomuna").selectpicker('refresh');
		$("#fono_empresa").val(data.fono_empresa);
		$("#email_empresa").val(data.email_empresa);
		$("#web_empresa").val(data.web_empresa);
		$("#condicion_empresa").val(data.condicion_empresa);
		$("#condicion_empresa").selectpicker('refresh');
		$("#nombre_contacto").val(data.nombre_contacto);
	//	$("#logo_empresa").val(data.logo_empresa);

		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../reportes/"+data.logo_empresa);
		$("#logo_actual_empresa").val(data.logo_empresa);
       


    })
}
 
//Función para eliminar registros
function eliminar(idpersona)
{
    bootbox.confirm("¿Está Seguro de eliminar el cliente?", function(result){
        if(result)
        {
            $.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
init();