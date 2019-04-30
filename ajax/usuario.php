<?php
session_start(); 
require_once "../modelos/Usuario.php";
 
$usuario=new Usuario();
 
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$id_empresa=isset($_POST["id_empresa"])? limpiarCadena($_POST["id_empresa"]):"";
//$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
 
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen=$_POST["imagenactual"];
        }
        else
        {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }
        //Hash SHA256 en la contraseña
        $clavehash=hash("SHA256",$clave);
       
 
        if (empty($idusuario)){
            //insertar($nombre, $num_documento,$id_empresa,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
            $rspta=$usuario->insertar($nombre,$num_documento, $id_empresa, $direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
            echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
        }
        else {
             //editar($idusuario,$nombre,$num_documento, $id_empresa,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
            $rspta=$usuario->editar($idusuario,$nombre,$num_documento,$id_empresa, $direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
    break;
 
    case 'desactivar':
        $rspta=$usuario->desactivar($idusuario);
        echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
    break;
 
    case 'activar':
        $rspta=$usuario->activar($idusuario);
        echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
    break;
 
    case 'mostrar':
        $rspta=$usuario->mostrar($idusuario);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listar':
        $rspta=$usuario->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->nombre,
               // "2"=>$reg->tipo_documento,
                "2"=>$reg->num_documento,
                "3"=>$reg->telefono,
                "4"=>$reg->email,
                "5"=>$reg->login,
               // "6"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px' >",
                "6"=>$reg->nombre_empresa,
                "7"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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
 
    case 'permisos':
        //Obtenemos todos los permisos de la tabla permisos
        require_once "../modelos/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();
 
        //Obtener los permisos asignados al usuario
        $id=$_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores=array();
 
        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object())
            {
                array_push($valores, $per->idpermiso);
            }
 
        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object())
                {
                    $sw=in_array($reg->idpermiso,$valores)?'checked':'';
                    echo '<li> <input type="checkbox" '.$sw.'  name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
                }
    break;
 
    case 'verificar':
        $logina=$_POST['logina'];
        $clavea=$_POST['clavea'];
 
        //Hash SHA256 en la contraseña
        $clavehash=hash("SHA256",$clavea);
 
        $rspta=$usuario->verificar($logina, $clavehash); 
        $fetch=$rspta->fetch_object();
 
        if (isset($fetch))
        {
            //Declaramos las variables de sesión
            $_SESSION['idusuario']=$fetch->idusuario;
            $_SESSION['nombre']=$fetch->nombre;
            $_SESSION['imagen']=$fetch->imagen;
            $_SESSION['login']=$fetch->login;
            $_SESSION['cargo']=$fetch->cargo;
/*
$sql="SELECT u.idusuario, u.nombre ,u.num_documento,u.telefono, u.email, u.cargo, u.imagen, u.login , 
        u.usuario_id_empresa as id_empresa,  e.rut_empresa, e.nombre_legal_empresa as nombre_empresa, e.nombre_fantasia_empresa as nombre_fantasia,
        e.slogan_empresa, e.direccion_empresa as direccion_e, e.fono_empresa, e.ciudad_empresa, e.email_empresa,e.giro_empresa, e.logo_empresa
*/
            $_SESSION['id_empresa']=$fetch->id_empresa;
            $_SESSION['rut_empresa']=$fetch->rut_empresa;
            $_SESSION['nombre_empresa']=$fetch->nombre_empresa;
            $_SESSION['giro_empresa']=$fetch->giro_empresa;
            $_SESSION['nombre_fantasia']=$fetch->nombre_fantasia;
            $_SESSION['direccion_empresa']=$fetch->direccion_e;
            $_SESSION['ciudad_empresa']=$fetch->ciudad_empresa;
            $_SESSION['fono_empresa']=$fetch->fono_empresa;
            $_SESSION['email_empresa']=$fetch->email_empresa;
            $_SESSION['logo_empresa']=$fetch->logo_empresa;
            $_SESSION['web_empresa']=$fetch->web_empresa;


             //Obtenemos los permisos del usuario
            $marcados = $usuario->listarmarcados($fetch->idusuario);
 
            //Declaramos el array para almacenar todos los permisos marcados
            $valores=array();
 
            //Almacenamos los permisos marcados en el array
            while ($per = $marcados->fetch_object())
                {
                    array_push($valores, $per->idpermiso);
                }
 
            //Determinamos los accesos del usuario
            in_array(1,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
            in_array(2,$valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
            in_array(4,$valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
            in_array(3,$valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
            in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
            in_array(8,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
            in_array(9,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
            in_array(6,$valores)?$_SESSION['cotizaciones']=1:$_SESSION['cotizaciones']=0;
            in_array(7,$valores)?$_SESSION['epagos']=1:$_SESSION['epagos']=0;
            in_array(10,$valores)?$_SESSION['empresas']=1:$_SESSION['empresas']=0;
            in_array(11,$valores)?$_SESSION['consultaps']=1:$_SESSION['consultaps']=0;
            in_array(12,$valores)?$_SESSION['consultam']=1:$_SESSION['consultam']=0; // Acceso al menú consultas
 
        }
        echo json_encode($fetch);
    break;

    case "selectEmpresa":
         $rspta = $usuario->selectempresa();
         while ($reg = $rspta->fetch_object())
                {
                    echo '<option value=' . $reg->id_empresa . '>'. $reg->nombre_legal_empresa . '</option>';
                      //nombre_legal_empresa


                }
    break;
 
    case 'salir':
        //Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");
 
    break;


}
?>