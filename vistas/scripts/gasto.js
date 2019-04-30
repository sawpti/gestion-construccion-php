var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })

    
    //Cargamos los items al select obras
   $.post("../ajax/gasto.php?op=selectObra", function(r){
                $("#idobra").html(r);
                $('#idobra').selectpicker('refresh');

    }); 
 
}

//Función limpiar
function limpiar()
{
    $("#id_gastos_obra").val("");
    $("#des_gl_gasto").val("");
    $("#num_documento").val("");
    $("#monto_gasto").val("");
    $("#idobra").val("");


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
                    url: '../ajax/gasto.php?op=listar',
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
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/gasto.php?op=guardaryeditar",
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

function mostrar(idgasto)
{

    //bootbox.alert("Hola"+idgasto);
    $.post("../ajax/gasto.php?op=mostrar",{id_gastos_obra : idgasto}, function(data, status) // El primer parametro es el campo de la tabla
    {
        data = JSON.parse(data);        
        mostrarform(true);
     
        $("#id_gastos_obra").val(data.id_gastos_obra);
        $("#des_gl_gasto").val(data.des_gl_gasto);
        $("#num_documento").val(data.num_documento);
        $("#monto_gasto").val(data.monto_gasto);
        $("#idobra").val(data.gasto_idObra);
        $("#idobra").selectpicker('refresh');
       // $("#ido").val(data.gasto_idObra);

        
       });

  //  bootbox.alert("Hola: "+(data.id_gastos_obra);
       

}

//Función eliminar
function eliminar(idgasto)
{
    bootbox.confirm("¿Está Seguro de ELIMINAR  el gasto?", function(result){
        if(result)
        {
            $.post("../ajax/gasto.php?op=eliminar", {id_gastos_obra : idgasto}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
init();