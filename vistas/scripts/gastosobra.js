var tabla;

//Función que se ejecuta al inicio
function init(){
	listar();
	$("#total").hide();
	$("#total_label").hide();

	//Cargamos los items al select cliente
	$.post("../ajax/gasto.php?op=selectObra", function(r){
	            $("#idobra").html(r);
	            $('#idobra').selectpicker('refresh');
	});
}

function obtnertotalobra(){
	//var idobra = $("#idobra").val();
	$.post("../ajax/consultas.php?op=totalobra", function(data,status)
	{
		
		data = JSON.parse(data);
		console.log("total :"+data.total);

		if (data!=null){
			$("#total").val(data.total);
		}
		else {
			$("#total").val(0);

		}
		
	});
	
          
}

/*Para formatrar numeros con separadro de miles*/
var formatNumber = {
 separador: ".", // separador para los miles
 sepDecimal: ',', // separador para los decimales
 formatear:function (num){
 num +='';
 var splitStr = num.split('.');
 var splitLeft = splitStr[0];
 var splitRight = splitStr.length >1 ? this.sepDecimal + splitStr[1] : '';
 var regx = /(\d+)(\d{3})/;
 while (regx.test(splitLeft)) {
 splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
 }
 return this.simbol + splitLeft +splitRight;
 },
 new:function(num, simbol){
 this.simbol = simbol ||'';
 return this.formatear(num);
 }
}


//Función Listar
function listar()
{
	//obtnertotalobra();
	var idobra = $("#idobra").val();

	//$("#total").show();
	//$("#total_label").show();

	tabla=$('#tbllistado').DataTable(
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
					url: '../ajax/consultas.php?op=gastosxobra',
					data:{gasto_idObra: idobra},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 20,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	    ,

	    
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            //formatNumber.new(123456779.18, "$") 
 

            // Update footer
            $( api.column( 0 ).footer() ).html(
                'Total vista: '+formatNumber.new(pageTotal, "$") +' /  TOTAL: '+ formatNumber.new(total, "$") );
        }


	}) //.DataTable( );
}
init();