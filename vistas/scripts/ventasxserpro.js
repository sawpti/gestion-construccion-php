var tabla;

//Función que se ejecuta al inicio
function init(){
	listar();
	//Cargamos los items al select cliente
	$.post("../ajax/consultas.php?op=selectProductoServicio", function(r){
	            $("#idarticulo").html(r);
	            $('#idarticulo').selectpicker('refresh');
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
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idarticulo = $("#idarticulo").val();

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
					url: '../ajax/consultas.php?op=ventasxproductoservicio',
					data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin, idarticulo: idarticulo},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 15,//Paginación
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
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            //formatNumber.new(123456779.18, "$") 
            
            
            
             totaluds = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotaluds = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
 
 // Update footer
            $( api.column( 0 ).footer() ).html(
                '<i class="fa fa-info-circle" aria-hidden="true"></i>&nbspVista actual<i class="fa fa-arrow-right" aria-hidden="true"></i> &nbsp &nbsp &nbsp Cantidad = '+ pageTotaluds+'&nbsp &nbsp &nbsp T. Neto ='+ formatNumber.new(pageTotal, "$")
                 +' <br/><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp Toda la consulta <i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp &nbsp &nbsp Cantidad= '+totaluds+ '&nbsp &nbsp &nbsp T. Neto ='+ formatNumber.new(total, "$") );
        

            // Update footer
         /*   $( api.column( 0 ).footer() ).html(
                'Total vista<br/>&nbsp &nbsp &nbsp Cantidad = '+ pageTotaluds+'&nbsp &nbsp &nbsp T. Neto ='+ formatNumber.new(pageTotal, "$")
                 +' <br/>Total consulta <br/>&nbsp &nbsp &nbsp Cantidad= '+totaluds+ '&nbsp &nbsp &nbsp T. Neto ='+ formatNumber.new(total, "$") );*/
                 
                 
            
 

            // Update footer
          /*  $( api.column( 0 ).footer() ).html(
                'Total vista: '+formatNumber.new(pageTotal, "$") +'   TOTAL: '+ formatNumber.new(total, "$") );*/
        }


	})//.DataTable();

}


init();