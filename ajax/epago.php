<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Epago.php";


$estadopago=new Epago();


$idestadopago=isset($_POST["idestadopago"])? limpiarCadena($_POST["idestadopago"]):"";
$obra_id=isset($_POST["obra_id"])? limpiarCadena($_POST["obra_id"]):"";
$fechaEP=isset($_POST["fechaEP"])? limpiarCadena($_POST["fechaEP"]):"";
$obns1EP=isset($_POST["obns1EP"])? limpiarCadena($_POST["obns1EP"]):"";
$obns2EP=isset($_POST["obns2EP"])? limpiarCadena($_POST["obns2EP"]):"";
$numEPObra=isset($_POST["numEPObra"])? limpiarCadena($_POST["numEPObra"]):"";
$anexo=isset($_POST["anexo"])? limpiarCadena($_POST["anexo"]):"";
$numFactura=isset($_POST["numFactura"])? limpiarCadena($_POST["numFactura"]):"";
$estadoEP=isset($_POST["estadoEP"])? limpiarCadena($_POST["estadoEP"]):"";
//$idusuario=$_SESSION["idusuario"];
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_ep=isset($_POST["total_estadopago"])? limpiarCadena($_POST["total_estadopago"]):"";

$idusuario=$_SESSION["idusuario"];
$idempresa_u=$_SESSION['id_empresa'];


//estado;
switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idestadopago)){
	
			$rspta=$estadopago->insertar($obra_id, $fechaEP, $obns1EP, $obns2EP, $numEPObra, $anexo, $numFactura, $estadoEP, $idusuario, $impuesto, $total_ep, $idempresa_u,
			$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
			echo $rspta ? "Estado Pago registrado" : "No se pudieron registrar todos los datos del EP";
		}
		else {
			//Editamos los campos de la cabecera de la estadopago
			$rspta=$estadopago->editar($idestadopago, $obra_id, $fechaEP, $obns1EP, $obns2EP, $numEPObra, $anexo, $numFactura, $estadoEP, $idusuario, $impuesto);
			echo $rspta ? "Cabecera  EP actualizada" : "No se pudieron actualizar todos los datos del EP";
			
		}
	break;

	case 'anular':
		$rspta=$estadopago->anular($idestadopago);
 		echo $rspta ? "Eestado  de Pago  anulado" : "EP no se puede anular";
	break;

	case 'mostrar':
		$rspta=$estadopago->mostrar($idestadopago);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idestadopago
		$id=$_GET['id'];

		$rspta = $estadopago->listarDetalle($id);
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
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.  number_format($reg->canItem,2,',','.').'</td><td>'.number_format($reg->valorItem,0,',','.').'</td><td>'.number_format($reg->descItem,0,',','.').'</td><td>'.number_format($reg->subtotal,0,',','.').'</td></tr>';
					$total=$total+($reg->valorItem*$reg->canItem - $reg->descItem);
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">$'.number_format($total,0,',','.').'</h4><input type="hidden" name="total_estadopago" id="total_estadopago"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$estadopago->listar($idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		$url='../reportes/exEpago.php?id=';

 		/*SELECT ep.idestadopago, ep.obra_id, p.nombre as cliente, o.nomObra,ep.numEPObra as numep, k.nomContacto as jefeobra, o.numCotizacion, c.titulo_cotizacion, DATE(ep.fechaEP) as fechaEP,ep.obns1EP, ep.obns2EP, ep.anexo,  ep.numFactura, ep.idusuario,
		  u.nombre as nombreu, ep.impuesto , ep.total_ep,  ep.estadoEP*/

 		while ($reg=$rspta->fetch_object()){
 			
 			 if ($reg->estadoEP=='Enviado') 
            {
                  $salida='<span class="label bg-green">Enviado</span>';
            }
            elseif ($reg->estadoEP=='Pagado')
            {
                  $salida='<span class="label bg-blue">Pagado</span>';
            } 
            else 
            {
                  $salida='<span class="label bg-red">Anulado</span>';
            }
 			$data[]=array(
 				"0"=>(($reg->estadoEP=='Enviado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idestadopago.')"><i class="fa fa-eye"></i></button>'.
 					' <button class="btn btn-danger" onclick="anular('.$reg->idestadopago.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idestadopago.')"><i class="fa fa-eye"></i></button>').
 				'<a target="_blank" href="'.$url.$reg->idestadopago.'"> <button class="btn btn-info"><i class="fa fa-file" ></i> </button></a>',
 				"1"=>$reg->fechaEP,
 				"2"=>$reg->cliente,
 				"3"=>$reg->nomObra,
 				"4"=>$reg->numep,
 				"5"=>$reg->numFactura,
 				"6"=>$reg->jefeobra,
 				//"7"=>$reg->nombreu,
 				"7"=>$reg->impuesto,
 				"8"=>number_format($reg->total_ep,0,',','.'),
 				"9"=>$salida
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	
	case 'listarArticulosestadopago':
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
	case "selectObra":
         $rspta = $estadopago->selectcobra($idempresa_u);
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idObra . '>' . $reg->nomObra.'/N° Cotización='.$reg->ncotizacion.'</option>';

                }
    break;
}
?>