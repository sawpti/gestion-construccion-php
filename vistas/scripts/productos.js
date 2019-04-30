var tabla;

//Función que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);  
    })

    //Cargamos los items al select categoria
    $.post("../ajax/articulo.php?op=selectCategoria", function(r){
                $("#idcategoria").html(r);
                $('#idcategoria').selectpicker('refresh');

    });
    //$("#imagenmuestra").hide();
}

//Función limpiar
function limpiar()
{
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
   // $("#imagenmuestra").attr("src","");
  //  $("#imagenactual").val("");
   // $("#tipo").val("");
    $("#valor_neto").val("");
    $("#print").hide();
    $("#idarticulo").val("");
    $("#idmedida").val("");
    
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
                    url: '../ajax/articulo.php?op=listarp',
                    type : "get",
                    dataType : "json",                      
                    error: function(e){
                        console.log(e.responseText);    
                    }
                },
        "bDestroy": true,
        "iDisplayLength": 20,//Paginación
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
        url: "../ajax/articulo.php?op=guardaryeditar",
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

function mostrar(idarticulo)
{
    $.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo}, function(data, status)
    {
        data = JSON.parse(data);        
        mostrarform(true);

        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
       // $("#imagenmuestra").show();
      //  $("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
       // $("#imagenactual").val(data.imagen);
      //  $("#tipo").val(data.tipo);
       // $('#tipo').selectpicker('refresh');
        $("#valor_neto").val(data.valor_neto);
        $("#idarticulo").val(data.idarticulo);
     //   $("#idmediada").val(data.u_medida);
        $("#idmedida").val(data.u_medida);
        
       $("#idmedida").selectpicker('refresh');

       if (data.u_medida==""){
        $("#idmedida").val("");
       

       }
        generarbarcode();

    })
}

//Función para desactivar registros
function desactivar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de desactivar el producto?", function(result){
        if(result)
        {
            $.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}

//Función para activar registros
function activar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de activar el Producto?", function(result){
        if(result)
        {
            $.post("../ajax/articulo.php?op=activar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            }); 
        }
    })
}

//función para generar el código de barras
function generarbarcode()
{
    codigo=$("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}

//Función para imprimir el Código de barras
function imprimir()
{
    $("#print").printArea();
}

init();