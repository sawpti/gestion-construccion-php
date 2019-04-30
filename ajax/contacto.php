<?php 
require_once "../modelos/Contacto.php";

$contacto=new Contacto();

$idcontacto=isset($_POST["idcontacto"])? limpiarCadena($_POST["idcontacto"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$fono=isset($_POST["fono"])? limpiarCadena($_POST["fono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$idempresa=isset($_POST["idempresa"])? limpiarCadena($_POST["idempresa"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idcontacto)){
			$rspta=$contacto->insertar($nombre ,$fono, $email, $cargo ,$idempresa);
			

			echo $rspta ? "Contacto registrado" : "Contacto no se pudo registrar";
		}
		else {
			$rspta=$contacto->editar($idcontacto, $nombre ,$fono, $email, $cargo ,$idempresa);
			echo $rspta ? "Contacto actualizado" : "Contacto no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$contacto->eliminar($idcontacto);
 		echo $rspta ? "Contacto eliminado" : "Contacto no se puede eliminar";
	break;

	case 'mostrar':
		$rspta=$contacto->mostrar($idcontacto);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
	    //Lista de Contactos
		$rspta=$contacto->listar(); 
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idContacto.')"><i class="fa fa-pencil"></i></button>'.
 					' <!-- button class="btn btn-danger" onclick="eliminar('.$reg->idContacto.')"><i class="fa fa-trash"></i></button -->',
 				"1"=>$reg->nomContacto,
 				"2"=>$reg->fonoContacto,
 				"3"=>$reg->emailContacto,
 				"4"=>$reg->cargoContacto,
 				"5"=>$reg->empresa, // se debe definir en la consulta
 				
 				);

 			//nomContacto,fonoContacto,emailContacto,cargoContacto, persona_id
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

		$rspta = $persona->listar();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;
	
	  
}
?>