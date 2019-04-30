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
    $.post("../ajax/persona.php?op=selectComuna", function(r){
                $("#idcomuna").html(r);
                $('#idcomuna').selectpicker('refresh');
 
    });
}
 
//Función limpiar
function limpiar()
{
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#ciudad").val("");
    $("#giro").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#web").val("");
    $("#idpersona").val("");
    $("#tipo_persona").val("");
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
                    url: '../ajax/persona.php?op=listar',
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
        url: "../ajax/persona.php?op=guardaryeditar",
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
 
function mostrar(idpersona)
{
    $.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);

        //$("#tipo_persona").val("Proveedor");
        //$("#tipo_persona").selectpicker('refresh');
        $("#nombre").val(data.nombre);
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#ciudad").val(data.ciudad);
        $("#idcomuna").val(data.idcomuna);
        $("#idcomuna").selectpicker('refresh');
        $("#giro").val(data.giro);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#web").val(data.web);
        $("#idpersona").val(data.idpersona);
        $("#tipo_persona").val(data.tipo_persona);
        $("#tipo_persona").selectpicker('refresh');
       


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