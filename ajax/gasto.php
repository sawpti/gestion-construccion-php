<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Gasto.php";

$gasto=new Gasto();


$id_gastos_obra=isset($_POST["id_gastos_obra"])? limpiarCadena($_POST["id_gastos_obra"]):"";
$des_gl_gasto=isset($_POST["des_gl_gasto"])? limpiarCadena($_POST["des_gl_gasto"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$monto_gasto=isset($_POST["monto_gasto"])? limpiarCadena($_POST["monto_gasto"]):"";
$gasto_idObra=isset($_POST["idobra"])? limpiarCadena($_POST["idobra"]):"";

$id_empresa=$_SESSION['id_empresa'];

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($id_gastos_obra)){
			$rspta=$gasto->insertar($des_gl_gasto, $num_documento, $monto_gasto, $gasto_idObra, $id_empresa);
			echo $rspta ? "Gasto registrado" : "Gasto no se pudo registrar";
		}
		else {
			$rspta=$gasto->editar($id_gastos_obra, $des_gl_gasto, $num_documento, $monto_gasto, $gasto_idObra);
			echo $rspta ? "Gasto actualizado" : "Gasto no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$gasto->eliminar($id_gastos_obra);
 		echo $rspta ? "Gasto eliminado" : "Gasto no se puede eliminar";
	break;

	case 'mostrar':
	    //$ido=4;
		$rspta=$gasto->mostrar($id_gastos_obra);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
	    //Lista de Gastos
		$rspta=$gasto->listar($id_empresa); 
 		//Vamos a declarar un array
 		// go.id_gastos_obra, go.des_gl_gasto, go.num_documento, go.monto_gasto, o.nomObra 
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_gastos_obra.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="eliminar('.$reg->id_gastos_obra.')"><i class="fa fa-trash"></i></button>',
 				"1"=>$reg->des_gl_gasto,
 				"2"=>$reg->obra,
 				"3"=>$reg->monto_gasto,
 				"4"=>$reg->num_documento
 				);
 			
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'selectObra':
		
		$rspta = $gasto->selectobra($id_empresa);

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idObra . '>' . $reg->nomObra . '</option>';
				}
	break;
	
	  
}
?>