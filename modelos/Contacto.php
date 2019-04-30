<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Contacto
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($nombre,$fono, $email, $cargo, $idempresa)
    {
        $sql="INSERT INTO contacto (nomContacto,fonoContacto,emailContacto,cargoContacto, persona_id)
        VALUES ('$nombre','$fono','$email', '$cargo', '$idempresa')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($idcontacto, $nombre,$fono, $email, $cargo, $idempresa)
    {
        $sql="UPDATE contacto SET nomContacto='$nombre' ,fonoContacto='$fono' ,emailContacto='$email' ,cargoContacto='$cargo', persona_id='$idempresa' WHERE idContacto='$idcontacto'";
        return ejecutarConsulta($sql);
    }

    
    //Implementamos un método para eliminar un contacto
    public function eliminar($idcontacto)
    {
        $sql="DELETE FROM contacto WHERE idContacto='$idcontacto'";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idcontacto)
    {
        $sql="SELECT * FROM contacto WHERE idContacto='$idcontacto'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
       // $sql="SELECT * FROM contacto";
        //return ejecutarConsulta($sql);


        $sql="SELECT c.idContacto, c.nomContacto, c.fonoContacto, c.emailContacto, c.cargoContacto, c.persona_id,  p.nombre  AS empresa
              FROM contacto c  INNER JOIN  persona p ON c.persona_id=p.idpersona";
        return ejecutarConsulta($sql);        



    }
    
}

?>