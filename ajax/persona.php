<?php 
require_once "../modelos/Persona.php";

$persona=new Persona();

$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
//$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$ciudad=isset($_POST["ciudad"])? limpiarCadena($_POST["ciudad"]):"";
$giro=isset($_POST["giro"])? limpiarCadena($_POST["giro"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$web=isset($_POST["web"])? limpiarCadena($_POST["web"]):"";
$idcomuna=isset($_POST["idcomuna"])? limpiarCadena($_POST["idcomuna"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idpersona)){
			$rspta=$persona->insertar($tipo_persona,$num_documento, $nombre, $direccion, $ciudad, $giro, $telefono, $email, $web, $idcomuna);
			echo $rspta ? "Persona registrada" : "Persona no se pudo registrar";
		}
		else {
			$rspta=$persona->editar($idpersona, $tipo_persona, $num_documento, $nombre,  $direccion, $ciudad, $giro, $telefono, $email, $web, $idcomuna);
			echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$persona->eliminar($idpersona);
 		echo $rspta ? "Persona eliminada" : "Persona no se puede eliminar";
	break;

	case 'mostrar':
		$rspta=$persona->mostrar($idpersona);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarp':
	    //Lista de Proveedores
		$rspta=$persona->listarp(); 
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
 					' <!-- button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button -->',
 				"1"=>$reg->num_documento,
 				"2"=>$reg->nombre,
 				"3"=>$reg->direccion,
 				"4"=>$reg->ciudad,
 				"5"=>$reg->comuna,
 				"6"=>$reg->giro,
 				"7"=>$reg->telefono,
 				"8"=>$reg->email,
 				"9"=>$reg->tipo_persona
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'listar':
		 $rspta=$persona->listar(); 
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			//$rut=esRut('$reg->num_documento');

 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
 					' <!-- button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button -->',
 				"1"=>$reg->num_documento,
 				"2"=>$reg->nombre,
 				"3"=>$reg->direccion,
 				"4"=>$reg->ciudad,
 				"5"=>$reg->comuna,
 				"6"=>$reg->giro,
 				"7"=>$reg->telefono,
 				"8"=>$reg->email,
 				"9"=>$reg->tipo_persona
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
         $rspta = $persona->selectcontacto();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idContacto . '>' . $reg->nomContacto . '</option>';
                }
    break;


   
}
?>