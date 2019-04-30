<?php 
require_once "../modelos/Empresa.php";

$empresa=new Empresa();


$id_empresa=isset($_POST["id_empresa"])? limpiarCadena($_POST["id_empresa"]):"";
$rut_empresa=isset($_POST["rut_empresa"])? limpiarCadena($_POST["rut_empresa"]):"";
$nombre_legal_empresa=isset($_POST["nombre_legal_empresa"])? limpiarCadena($_POST["nombre_legal_empresa"]):"";
$nombre_fantasia_empresa=isset($_POST["nombre_fantasia_empresa"])? limpiarCadena($_POST["nombre_fantasia_empresa"]):"";
$slogan_empresa=isset($_POST["slogan_empresa"])? limpiarCadena($_POST["slogan_empresa"]):"";
$direccion_empresa=isset($_POST["direccion_empresa"])? limpiarCadena($_POST["direccion_empresa"]):"";
$giro_empresa=isset($_POST["giro_empresa"])? limpiarCadena($_POST["giro_empresa"]):"";
$ciudad_empresa=isset($_POST["ciudad_empresa"])? limpiarCadena($_POST["ciudad_empresa"]):"";
$empresa_idcomuna=isset($_POST["empresa_idcomuna"])? limpiarCadena($_POST["empresa_idcomuna"]):"";
$fono_empresa=isset($_POST["fono_empresa"])? limpiarCadena($_POST["fono_empresa"]):"";
$email_empresa=isset($_POST["email_empresa"])? limpiarCadena($_POST["email_empresa"]):"";
$web_empresa=isset($_POST["web_empresa"])? limpiarCadena($_POST["web_empresa"]):"";
$condicion_empresa=isset($_POST["condicion_empresa"])? limpiarCadena($_POST["condicion_empresa"]):"";
$nombre_contacto=isset($_POST["nombre_contacto"])? limpiarCadena($_POST["nombre_contacto"]):"";
$logo_empresa=isset($_POST["logo_empresa"])? limpiarCadena($_POST["logo_empresa"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':
        	if (!file_exists($_FILES['logo_empresa']['tmp_name']) || !is_uploaded_file($_FILES['logo_empresa']['tmp_name']))
			{
				$logo_empresa=$_POST["logo_actual_empresa"];
			}
			else 
			{
				$ext = explode(".", $_FILES["logo_empresa"]["name"]);
				if ($_FILES['logo_empresa']['type'] == "image/jpg" || $_FILES['logo_empresa']['type'] == "image/jpeg" || $_FILES['logo_empresa']['type'] == "image/png")
				{
					$logo_empresa = round(microtime(true)) . '.' . end($ext);
					move_uploaded_file($_FILES["logo_empresa"]["tmp_name"], "../reportes/" . $logo_empresa);
				}
		}
     


		if (empty($id_empresa)){
			$rspta=$empresa->insertar($rut_empresa, $nombre_legal_empresa, $nombre_fantasia_empresa, $slogan_empresa, $direccion_empresa, $giro_empresa, $ciudad_empresa, $empresa_idcomuna, $fono_empresa, $email_empresa, $web_empresa, $condicion_empresa, $nombre_contacto, $logo_empresa);
			echo $rspta ? "Empresa registrada" : "Empresa no se pudo registrar";
		}
		else {
			$rspta=$empresa->editar($id_empresa,$rut_empresa, $nombre_legal_empresa, $nombre_fantasia_empresa, $slogan_empresa, $direccion_empresa, $giro_empresa, $ciudad_empresa, $empresa_idcomuna, $fono_empresa, $email_empresa, $web_empresa, $condicion_empresa, $nombre_contacto, $logo_empresa);
			echo $rspta ? "Empresa actualizada" : "Empresa no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$empresa->eliminar($id_empresa);
 		echo $rspta ? "Empresa eliminada" : "Empresa no se puede eliminar";
	break;

	case 'mostrar':
		$rspta=$empresa->mostrar($id_empresa);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;


	case 'listar':
	//Listar las empresas usuarias del sistema
	//"SELECT e.id_empresa, e., c.nomcomuna as comuna, e., e., e.,e., e.		
		 $rspta=$empresa->listar(); 
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
 			//$rut=esRut('$reg->num_documento');

 			  if ($reg->condicion_empresa==1) 
            {
                  $condicion_e='<span class="label bg-green">Activa</span>';
            }
            else 
            {
                  $condicion_e='<span class="label bg-red">Inactiva</span>';
            } 
            
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_empresa.')"><i class="fa fa-pencil"></i></button>'.
 					' <!-- button class="btn btn-danger" onclick="eliminar('.$reg->id_empresa.')"><i class="fa fa-trash"></i></button -->',
 				"1"=>$reg->nombre_legal_empresa,
 				"2"=>$reg->comuna,
 				"3"=>$reg->ciudad_empresa,
 				"4"=>$reg->direccion_empresa,
 				"5"=>$reg->nombre_contacto,
 				"6"=>$reg->email_empresa,
 				"7"=>"<img src='../reportes/".$reg->logo_empresa."' height='50px' width='50px' >",
 				"8"=>$condicion_e			
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
         $rspta = $empresa->selectcomuna();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idcomuna . '>' . $reg->nomcomuna . '</option>';

                }
    break;

   
}
?>