<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Cotizacion.php";


$cotizacion=new Cotizacion();

$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
$cotizacion_idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$persona_id=isset($_POST["persona_id"]) ? $_POST["persona_id"] : "0"; 
$idcontacto=isset($_POST["idcontacto"])? limpiarCadena($_POST["idcontacto"]):"";
//$idusuario=$_SESSION["idusuario"];
$titulo_cotizacion=isset($_POST["titulo"])? limpiarCadena($_POST["titulo"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$cotizacion_lugar=isset($_POST["lugar"])? limpiarCadena($_POST["lugar"]):"";
$cotizacion_direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$cotizacion_idcomuna=isset($_POST["idcomuna"])? limpiarCadena($_POST["idcomuna"]):"";
$esp_tecnicas=isset($_POST["etecnicas"])? limpiarCadena($_POST["etecnicas"]):"";
$observaciones=isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_cotizacion=isset($_POST["total_cotizacion"])? limpiarCadena($_POST["total_cotizacion"]):"";
$idcomuna=isset($_POST["idcomuna"])? limpiarCadena($_POST["idcomuna"]):"";

$num_cotizacion=isset($_POST["num_cotizacion"])? limpiarCadena($_POST["num_cotizacion"]):"";

 

$idusuario=$_SESSION["idusuario"];
$idempresa_u=$_SESSION['id_empresa'];





//estado;
switch ($_GET["op"]){
	case 'guardaryeditar':
	

		if (empty($idcotizacion)){
			
			$rspta=$cotizacion->insertar($cotizacion_idcliente,$idcontacto,$idusuario,$titulo_cotizacion,$fecha_hora,$cotizacion_lugar, $cotizacion_direccion, $cotizacion_idcomuna, $esp_tecnicas,$observaciones,$impuesto,$total_cotizacion, $idempresa_u, $num_cotizacion, $_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
			echo $rspta ? "Cotización registrada" : "No se pudieron registrar todos los datos de la cotización";
		}
		else {
		//Editamos los campos de la cabecera de la cotizacion
			$rspta=$cotizacion->editar($idcotizacion,$cotizacion_idcliente,$idcontacto,$idusuario,$titulo_cotizacion,$fecha_hora,$cotizacion_lugar, $cotizacion_direccion, $cotizacion_idcomuna, $esp_tecnicas,$observaciones,$impuesto,$total_cotizacion);
			echo $rspta ? "Cabecera  cotización actualizada" : "No se pudieron actualizar todos los datos de la cotización";

			
		}
	break;

	case 'anular':
		$rspta=$cotizacion->anular($idcotizacion);
 		echo $rspta ? "Cotización anulada" : "Cotización no se puede anular";
	break;

	case 'mostrar':
		$rspta=$cotizacion->mostrar($idcotizacion);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'ultimacotizacion':
		$rspta=$cotizacion->obtenerultimonum($idempresa_u);
		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
		
	break;


	

	case 'listarDetalle':
		//Recibimos el idcotizacion
		$id=$_GET['id'];

		$rspta = $cotizacion->listarDetalle($id);
		$total=0;
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Producto o servicio</th>
                                    <th>Cantidad (Ejem: 1,25)</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.  number_format($reg->cantidad,2,',','.').'</td><td>'.number_format($reg->precio_venta,0,',','.').'</td><td>'.number_format($reg->descuento,0,',','.').'</td><td>'.number_format($reg->subtotal,0,',','.').'</td></tr>';
					$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">$'.number_format($total,0,',','.').'</h4><input type="hidden" name="total_cotizacion" id="total_cotizacion"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$cotizacion->listar($idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		$url='../reportes/exCotizacion.php?id=';

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>(($reg->estado=='Enviada')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>'.
 					' <button class="btn btn-danger" onclick="anular('.$reg->idcotizacion.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcotizacion.')"><i class="fa fa-eye"></i></button>').
 				'<a target="_blank" href="'.$url.$reg->idcotizacion.'"> <button class="btn btn-info"><i class="fa fa-file" ></i> </button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				"3"=>$reg->contacto,
 				"4"=>$reg->usuario,
 				"5"=>$reg->num_cotizacion,
 				"6"=>number_format($reg->total_cotizacion,0,',','.'),
 				"7"=>$reg->impuesto,
 				"8"=>($reg->estado=='Enviada')?'<span class="label bg-green">Enviada</span>':
 				'<span class="label bg-red">Anulado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarC();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;

	case 'listarArticulosCotizacion':
		require_once "../modelos/Articulo.php";
		$articulo=new Articulo();

		$rspta=$articulo->listarActivosVenta1($idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->valor_neto.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->categoria,
 				"3"=>$reg->codigo,
 				"4"=>$reg->stock,
 				"5"=>$reg->valor_neto,
 				//"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
	case "selectComuna":
         $rspta = $persona->selectcomuna();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idcomuna . '>' . $reg->nomcomuna . '</option>';

                }
    break;

    case "selectContacto":
         require_once "../modelos/Persona.php";
         $persona = new Persona();
         $rspta = $persona->selectcontacto();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idContacto . '>' . $reg->nomContacto . '</option>';

                }
    break;
     case "selectContactoByPersona":
         require_once "../modelos/Persona.php";
         $persona = new Persona();
             //echo("<script>console.log('PHP: ->>".$persona_id."');</script>");
         $rspta = $persona->selectcontactoxpersona($persona_id);
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idContacto . '>' . $reg->nomContacto . '</option>';

                }
    break;

    


}
?>