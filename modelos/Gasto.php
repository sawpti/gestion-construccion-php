<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Gasto
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }



    //Implementamos un método para insertar registros
    public function insertar($des_gl_gasto, $num_documento, $monto_gasto, $gasto_idObra, $id_empresa)
    {
        $sql="INSERT INTO gastos_obra (des_gl_gasto, num_documento, monto_gasto, gasto_idObra, id_empresa)
        VALUES ('$des_gl_gasto', '$num_documento', '$monto_gasto', '$gasto_idObra', '$id_empresa')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para editar registros
    public function editar($id_gastos_obra, $des_gl_gasto, $num_documento, $monto_gasto, $gasto_idObra)
    {
        $sql="UPDATE gastos_obra 
        SET des_gl_gasto='$des_gl_gasto', num_documento= '$num_documento', monto_gasto= '$monto_gasto', gasto_idObra= '$gasto_idObra'
        WHERE id_gastos_obra='$id_gastos_obra'";
        return ejecutarConsulta($sql);
    }

    
    //Implementamos un método para eliminar un gasto_obra
    public function eliminar($id_gastos_obra)
    {
        $sql="DELETE FROM gastos_obra WHERE id_gastos_obra='$id_gastos_obra'";
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($id_gastos_obra)
    {
        $sql="SELECT * FROM gastos_obra WHERE id_gastos_obra='$id_gastos_obra'";
        return ejecutarConsultaSimpleFila($sql);


    }

    //Implementar un método para listar los registros
    public function listar($id_empresa)
    {
       $sql="SELECT go.id_gastos_obra, go.des_gl_gasto, go.num_documento, go.monto_gasto, o.nomObra as obra
            FROM gastos_obra go
             INNER JOIN  obra o ON o.idObra= go.gasto_idObra
            WHERE id_empresa = '$id_empresa'";
        //return ejecutarConsulta($sql);
        return ejecutarConsulta($sql);    

    }


    //Implementar un método para mostrar las obras en un select
    public function selectobra($id_empresa)
    {
        $sql="SELECT idObra, nomObra FROM  obra 
        WHERE id_empresa_usuaria='$id_empresa'
        ORDER by idObra desc";
        return ejecutarConsulta($sql); 
    }


    
}

?>