<?php 
if (strlen(session_id()) < 1) 
  session_start();
require_once "../modelos/Obra.php";
$obra=new Obra();
$idObra=isset($_POST["idObra"])? limpiarCadena($_POST["idObra"]):"";
$numCotizacion=isset($_POST["numCotizacion"])? limpiarCadena($_POST["numCotizacion"]):"";
$idEncaragadoObra=isset($_POST["idEncaragadoObra"])? limpiarCadena($_POST["idEncaragadoObra"]):"";
$nomObra=isset($_POST["nomObra"])? limpiarCadena($_POST["nomObra"]):"";
$desObra=isset($_POST["desObra"])? limpiarCadena($_POST["desObra"]):"";
$valEstimadoObra=isset($_POST["valEstimadoObra"])? limpiarCadena($_POST["valEstimadoObra"]):"";
$fechaInicio=isset($_POST["fechaInicio"])? limpiarCadena($_POST["fechaInicio"]):"";
$fechaTermino=isset($_POST["fechaTermino"])? limpiarCadena($_POST["fechaTermino"]):"";
$porAvanceObra=isset($_POST["porAvanceObra"])? limpiarCadena($_POST["porAvanceObra"]):"";
$numTrabajadores=isset($_POST["numTrabajadores"])? limpiarCadena($_POST["numTrabajadores"]):"";
$estadoObra=isset($_POST["estadoObra"])? limpiarCadena($_POST["estadoObra"]):"";

//$idusuario=$_SESSION["idusuario"];
$idempresa_u=$_SESSION['id_empresa'];

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idObra)){
			$rspta=$obra->insertar($numCotizacion, $idEncaragadoObra, $nomObra,$desObra,$valEstimadoObra,$fechaInicio,$fechaTermino, $porAvanceObra, $numTrabajadores, $idempresa_u);
			echo $rspta ? "Obra registrada" : "obra no se pudo registrar";

			//insertar($numCotizacion, $idEncaragadoObra, $nomObra,$desObra,$valEstimadoObra,$fechaInicio,$fechaTermino, $porAvanceObra, $numTrabajadores)
		}
		else {
			$rspta=$obra->editar($idObra, $numCotizacion, $idEncaragadoObra, $nomObra,$desObra,$valEstimadoObra,$fechaInicio,$fechaTermino, $porAvanceObra, $numTrabajadores, $estadoObra);
			echo $rspta ? "Obra actualizada" : "obra no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$obra->eliminar($idObra);
 		echo $rspta ? "Obra eliminada" : "obra no se puede eliminar";
	break;

	case 'mostrar':
		$rspta=$obra->mostrar($idObra);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
	    //Lista de Proveedores
		$rspta=$obra->listar($idempresa_u); 
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){
            if ($reg->estadoObra=='Ejecutando') 
            {
                  $salida='<span class="label bg-yellow">Ejecutando</span>';
            }
            elseif ($reg->estadoObra=='Terminada')
            {
                  $salida='<span class="label bg-green">Terminada</span>';
            } 
            else 
            {
                  $salida='<span class="label bg-red">Abortada</span>';
            }
            
             $avance= number_format($reg->porAvanceObra,0,',','.');

             if ($avance <=25){

               $barra='<div class="progress">
              <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.$avance.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$avance.'%">'.$avance.
              '</div>';
            }elseif ($avance>25 and $avance<100) {

              //progress-bar progress-bar-warning, progress-bar progress-bar-info
               $barra='<div class="progress">
              <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="'.$avance.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$avance.'%">'.$avance.
              '</div>';
            }else 
            {
                $barra='<div class="progress">
              <div class="progress-bar progress-bar-success" aria-valuenow="'.$avance.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$avance.'%">'.$avance.
              '</div>';
            }
            
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idObra.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="eliminar('.$reg->idObra.')"><i class="fa fa-trash"></i></button>',
 				"1"=>$reg->ncotizacion,
        "2"=>$reg->idObra,
        "3"=>$reg->nomObra,
 				"4"=>$reg->titulocotizacion,
 				"5"=>$reg->lugarobra,
 				"6"=>$reg->direccionobra,
 				"7"=>$reg->cliente,
 				"8"=>$reg->encargadoobra,
 				"9"=>$reg->fechaInicio,
 			  "10"=>$reg->fechaTermino,
 				"11"=>$barra,//$reg->porAvanceObra,
 				//"13"=>$reg->numTrabajadores,
 				"12"=>$salida
				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
	
    case "selectContacto":
         $rspta = $obra->selectcontacto();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idContacto . '>' . $reg->nomContacto . '</option>';
                }
    break;

     case "selectCotizacion":
         $rspta = $obra->selectcotizacion($idempresa_u);
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->idcotizacion . '>' . $reg->num_cotizacion .'-'. $reg->titulo .'-'. $reg->rutcliente .'</option>';
                }
    break;
}
?>