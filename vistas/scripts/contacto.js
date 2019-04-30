var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })

    
    //Cargamos los items al select proveedor
    $.post("../ajax/contacto.php?op=selectCliente", function(r){
                $("#idempresa").html(r);
                $('#idempresa').selectpicker('refresh');
    }); 
    
}

//Función limpiar
function limpiar()
{
    $("#idcontacto").val("");
    $("#nombre").val("");
    $("#fono").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#idempresa").val("");

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
                    url: '../ajax/contacto.php?op=listar',
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
        url: "../ajax/contacto.php?op=guardaryeditar",
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

function mostrar(idcontacto)
{
    $.post("../ajax/contacto.php?op=mostrar",{idcontacto : idcontacto}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);

        $("#idempresa").val(data.persona_id);
        $('#idempresa').selectpicker('refresh');
        $("#nombre").val(data.nomContacto);
        $("#fono").val(data.fonoContacto);
        $("#email").val(data.emailContacto);
        $("#cargo").val(data.cargoContacto);
        $("#idcontacto").val(data.idContacto);
        
       })
}

//Función eliminar
function eliminar(idcontacto)
{
    bootbox.confirm("¿Está Seguro de ELIMINAR  el contacto?", function(result){
        if(result)
        {
            $.post("../ajax/contacto.php?op=eliminar", {idcontacto : idcontacto}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}
init();