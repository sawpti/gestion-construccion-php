var tabla;
 
//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
 
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })

     //Cargamos los items al select contacto (Encargado Obra)
    $.post("../ajax/obra.php?op=selectContacto", function(r){
                $("#idEncaragadoObra").html(r);
                $('#idEncaragadoObra').selectpicker('refresh');
    });

    //Cargamos los items al select cotizaciones 
    $.post("../ajax/obra.php?op=selectCotizacion", function(r){
                $("#numCotizacion").html(r);
                $('#numCotizacion').selectpicker('refresh');
    });

}
 
//Función limpiar
function limpiar()
{
    $("#idObra").val("");
    $("#numCotizacion").val("");
    $("#idEncaragadoObra").val("");
    $("#nomObra").val("");
    $("#desObra").val("");
    $("#valEstimadoObra").val("");
    $("#porAvanceObra").val("");
    $("#numTrabajadores").val("");
    $("#estadoObra").val("");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    
    $("#fechaInicio").val(today);
    $("#fechaTermino").val(today);


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
                    url: '../ajax/obra.php?op=listar',
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
        url: "../ajax/obra.php?op=guardaryeditar",
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
 
function mostrar(idObra)
{
    $.post("../ajax/obra.php?op=mostrar",{idObra : idObra}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);

        $("#idObra").val(data.idObra);
        $("#numCotizacion").val(data.numCotizacion);
        $("#numCotizacion").selectpicker('refresh');
        $("#idEncaragadoObra").val(data.idEncaragadoObra);
        $("#idEncaragadoObra").selectpicker('refresh');
        $("#nomObra").val(data.nomObra);
        $("#desObra").val(data.desObra);
        $("#valEstimadoObra").val(data.valEstimadoObra);
        $("#fechaInicio").val(data.fechaInicio);
        $("#fechaTermino").val(data.fechaTermino);
        $("#porAvanceObra").val(data.porAvanceObra);
        $("#numTrabajadores").val(data.numTrabajadores);
        $("#estadoObra").val(data.estadoObra);
        $("#estadoObra").selectpicker('refresh');

    })
}
 
//Función para eliminar registros
function eliminar(idObra)
{
    bootbox.confirm("¿Está Seguro de eliminar la obra?", function(result){
        if(result)
        {
            $.post("../ajax/obra.php?op=eliminar", {idObra : idObra}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
 
init();