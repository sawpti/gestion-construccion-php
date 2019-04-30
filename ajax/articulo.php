<?php 
//Carga variables de sesion
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Articulo.php";

$articulo=new Articulo();

$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
//$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$tipo=isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$valor_neto=isset($_POST["valor_neto"])? limpiarCadena($_POST["valor_neto"]):"";
$idmedida=isset($_POST["idmedida"])? limpiarCadena($_POST["idmedida"]):"";
$idempresa_u=$_SESSION['id_empresa'];

switch ($_GET["op"]){
	case 'guardaryeditar':
		 /*	if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
			{
				$imagen=$_POST["imagenactual"];
			}
			else 
			{
				$ext = explode(".", $_FILES["imagen"]["name"]);
				if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
				{
					$imagen = round(microtime(true)) . '.' . end($ext);
					move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
				}
		}*/
		if (empty($idarticulo)){
			$rspta=$articulo->insertar($idcategoria,$codigo,$nombre,$stock,$descripcion, $tipo,$valor_neto,$idmedida, $idempresa_u);
			echo $rspta ? "Registro realizado" : "No se pudo realizar el registro";
		}
		else {
			$rspta=$articulo->editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$tipo,$valor_neto,$idmedida, $idempresa_u);
			echo $rspta ? "Registro actualizado" : "Registro no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$articulo->desactivar($idarticulo);
 		echo $rspta ? "Producto o servicio Desactivado" : "Producto o servicio no se puede desactivar";
 		break;
	break;

	case 'activar':
		$rspta=$articulo->activar($idarticulo);
 		echo $rspta ? "Producto o servicio activado" : "Producto o servicio no se puede activar";
 		break;
	break;

	case 'mostrar':
		$rspta=$articulo->mostrar($idarticulo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case 'listar':
		$rspta=$articulo->listar($idempresa_u); //Lista los productos y servicios
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
             $valorneto = number_format($reg->valor_neto ,0, ',', '.');
             $valorconiva=number_format($reg->valor_neto*0.19+$reg->valor_neto ,0, ',', '.');
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				//"2"=>$reg->descripcion,
 				"2"=>$reg->categoria,
 				//"4"=>$reg->codigo,
 				"3"=>$valorneto,
 				"4"=>$valorconiva,
 				"5"=>$reg->stock,
 				//"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
 				"6"=>$reg->codigo,
 				"7"=>$reg->tipo,
 				"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'listarp': // Lista los productos
		$rspta=$articulo->listarp($idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
             $valorneto = number_format($reg->valor_neto ,0, ',', '.');
             $valorconiva=number_format($reg->valor_neto*0.19+$reg->valor_neto ,0, ',', '.');
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->u_medida,
 				"3"=>$reg->categoria,
 				//"4"=>$reg->codigo,
 				"4"=>$valorneto,
 				"5"=>$valorconiva,
 				"6"=>$reg->stock,
 				//"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
 			    "7"=>$reg->codigo,
 				"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;


	case 'listars':
		$rspta=$articulo->listars($idempresa_u);
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
             $valorneto = number_format($reg->valor_neto ,0, ',', '.');
             $valorconiva=number_format($reg->valor_neto*0.19+$reg->valor_neto ,0, ',', '.');
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
				 //"2"=>$reg->descripcion,
				 "2"=>$reg->u_medida,
 				"3"=>$reg->categoria,
 				//"4"=>$reg->codigo,
 				"4"=>$valorneto,
 				"5"=>$valorconiva,
 				"6"=>$reg->stock,
 				//"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >",
 				"7"=>$reg->codigo,
 				"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
	case "selectCategoria":
		require_once "../modelos/Categoria.php";
		$categoria = new Categoria();

		$rspta = $categoria->select($idempresa_u);

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
				}
	break;
}
?>