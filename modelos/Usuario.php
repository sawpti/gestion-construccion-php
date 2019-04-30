<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Usuario
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($nombre, $num_documento,$id_empresa,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql="INSERT INTO usuario (nombre, num_documento, usuario_id_empresa, direccion,telefono,email,cargo,login,clave,imagen,condicion)
        VALUES ('$nombre','$num_documento','$id_empresa','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
        //return ejecutarConsulta($sql);
        $idusuarionew=ejecutarConsulta_retornarID($sql);
 
        $num_elementos=0;
        $sw=true;
 
        while ($num_elementos < count($permisos))
        {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuarionew', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw;
    }
 
    //Implementamos un método para editar registros
    public function editar($idusuario,$nombre,$num_documento, $id_empresa,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql="UPDATE usuario SET nombre='$nombre',num_documento='$num_documento' ,usuario_id_empresa='$id_empresa',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen' WHERE idusuario='$idusuario'";
        ejecutarConsulta($sql);
 
        //Eliminamos todos los permisos asignados para volverlos a registrar
        $sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
        ejecutarConsulta($sqldel);
 
        $num_elementos=0;
        $sw=true;
 
        while ($num_elementos < count($permisos))
        {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos + 1;
        }
 
        return $sw;
 
    }
 
    //Implementamos un método para desactivar categorías
    public function desactivar($idusuario)
    {
        $sql="UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
 
    //Implementamos un método para activar categorías
    public function activar($idusuario)
    {
        $sql="UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
 
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idusuario)
    {
        $sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }
 
    //Implementar un método para listar los registros
    public function listar()
    {
        $sql="SELECT  u.idusuario, u.nombre ,u.num_documento, u.direccion, u.telefono, 
u.email, u.cargo, u.login, u.clave, u.imagen, u.condicion, e.nombre_legal_empresa as nombre_empresa 
FROM usuario u
INNER JOIN empresa e ON u.usuario_id_empresa=e.id_empresa ";
        return ejecutarConsulta($sql);      
    }
    //Implementar un método para listar los permisos marcados
    public function listarmarcados($idusuario)
    {
        $sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }
 
    //Función para verificar el acceso al sistema
    public function verificar($login,$clave)
    {
        $sql="SELECT u.idusuario, u.nombre ,u.num_documento,u.telefono, u.email, u.cargo, u.imagen, u.login , 
        u.usuario_id_empresa as id_empresa,  e.rut_empresa, e.nombre_legal_empresa as nombre_empresa, e.nombre_fantasia_empresa as nombre_fantasia,
        e.slogan_empresa, e.direccion_empresa as direccion_e, e.fono_empresa, e.ciudad_empresa, e.email_empresa,e.giro_empresa, e.logo_empresa, e.web_empresa
        FROM usuario u
        INNER JOIN empresa e ON u.usuario_id_empresa=e.id_empresa 
        WHERE login='$login' AND clave='$clave' AND condicion='1'"; 
        return ejecutarConsulta($sql);  
    }
    // Método que lista las empresas usuarias del sistema
     public function selectempresa()
    {
        $sql="SELECT * FROM empresa";
        return ejecutarConsulta($sql);      
    }


}
 
?>