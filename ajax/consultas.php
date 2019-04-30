<?php 
if (strlen(session_id()) < 1) 
  session_start();
require_once "../modelos/Consultas.php";
//require_once "../config/global.php";


$consulta=new Consultas();

$idempresa_u=$_SESSION['id_empresa'];



switch ($_GET["op"]){
	case 'comprasfecha':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];

		$rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin,$idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->fecha,
 				"1"=>$reg->usuario,
 				"2"=>$reg->proveedor,
 				"3"=>$reg->tipo_comprobante,
 				"4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
 				"5"=>$reg->total_compra,//number_format($reg->total_compra,0,',','.'),
 				"6"=>number_format($reg->impuesto,2,',','.'),
 				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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


	case 'ventasfechacliente':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$idcliente=$_REQUEST["idcliente"];

		$rspta=$consulta->ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente, $idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->fecha,
 				"1"=>$reg->usuario,
 				"2"=>$reg->cliente,
 				"3"=>$reg->tipo_comprobante,
 				"4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
 				"5"=>$reg->total_venta,//number_format($reg->total_venta,0,',','.'),
 				"6"=>number_format($reg->impuesto,2,',','.'),
 				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
	case 'ventasxproductoservicio':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$idarticulo=$_REQUEST["idarticulo"];

		$rspta=$consulta->ventasProductoServicios($fecha_inicio,$fecha_fin,$idarticulo, $idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->fecha,
 				"1"=>$reg->tipo_comprobante,
 				"2"=>$reg->num_comprobante,
 				"3"=>$reg->impuesto,
 				"4"=>$reg->cantidad,
 				"5"=>$reg->precio_venta,//number_format($reg->total_venta,0,',','.'),
 				"6"=>$reg->descuento,
 				"7"=>$reg->total_neto
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
	case 'productosxcategoria':
		  $idcategoria=$_REQUEST["idcategoria"];

		 $rspta=$consulta->productosxcatgoria($idcategoria);
		 //$valorconiva= rspta->valor_neto+rspta->valor_neto*IVA;
 		//Vamos a declarar un array
 		 $data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->codigo,
 				"1"=>$reg->nombre,
 				"2"=>number_format($reg->valor_neto,0,',','.'),
 			    "3"=>number_format(($reg->valor_neto + $reg->valor_neto * 0.19),0,',','.'),
 			 /*   "4"=>($reg->condicion==1)?'<span class="label bg-green">Activo</span>':
 				 '<span class="label bg-red">Descativado</span>'*/
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'gastosxobra':
		  // Se obtiene el id de la obra de la vista que utilice esta funcion, en ete caso: gastosobra.php
		  $idobra=$_REQUEST["gasto_idObra"]; //El valor entre corchetes es el nombre del campo de la tabla

		 $rspta=$consulta->gastosxobra($idobra);
		 //$valorconiva= rspta->valor_neto+rspta->valor_neto*IVA;
 		//Vamos a declarar un array
 		 $data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>$reg->obra,
 				"1"=>$reg->gasto,
 				"2"=>$reg->documento,
 			    "3"=> $reg->monto//number_format(($reg->monto),0,',','.')
 			   // "4"=>number_format(($reg->totalacum),0,',','.')
 			 /*   "4"=>($reg->condicion==1)?'<span class="label bg-green">Activo</span>':
 				 '<span class="label bg-red">Descativado</span>'*/
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'totalobra':
	//$idcotizacion=isset($_POST["idcotizacion"])? limpiarCadena($_POST["idcotizacion"]):"";
		$idobra=isset($_POST["idobra"])? limpiarCadena($_POST["idobra"]):"";
		$rspta=$consulta->totalgastoObra($idobra);
		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
			break;

	case "selectProductoServicio":
         $rspta = $consulta->selectProductosServicios($idempresa_u);
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idarticulo . '>' . $reg->nombre . '</option>';

                }
    break;


}
?>