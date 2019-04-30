<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Obra
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($numCotizacion, $idEncaragadoObra, $nomObra,$desObra,$valEstimadoObra,$fechaInicio,$fechaTermino, $porAvanceObra, $numTrabajadores, $id_empresa_usuaria)
	{
		$sql="INSERT INTO obra (numCotizacion, idEncaragadoObra, nomObra,desObra,valEstimadoObra,fechaInicio,fechaTermino, porAvanceObra, numTrabajadores,estadoObra, id_empresa_usuaria)
		VALUES ('$numCotizacion', '$idEncaragadoObra', '$nomObra','$desObra','$valEstimadoObra','$fechaInicio','$fechaTermino', '$porAvanceObra', '$numTrabajadores', 'Ejecutando', '$id_empresa_usuaria')";
		return ejecutarConsulta($sql);


	}

	//Implementamos un método para editar registros
	public function editar($idObra, $numCotizacion, $idEncaragadoObra, $nomObra,$desObra,$valEstimadoObra,$fechaInicio,$fechaTermino, $porAvanceObra, $numTrabajadores, $estadoObra)
	{
		$sql="UPDATE obra SET numCotizacion='$numCotizacion', idEncaragadoObra='$idEncaragadoObra', nomObra='$nomObra', desObra='$desObra',valEstimadoObra='$valEstimadoObra',fechaInicio='$fechaInicio',fechaTermino='$fechaTermino', porAvanceObra='$porAvanceObra', numTrabajadores='$numTrabajadores', estadoObra='$estadoObra'  WHERE idObra='$idObra'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar un contacto
    public function eliminar($idObra)
    {
        $sql="DELETE FROM obra WHERE idObra='$idObra'";
        return ejecutarConsulta($sql);
    }

	
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idObra)
	{

		$sql="SELECT idObra,numCotizacion, c.titulo_cotizacion as titulocotizacion,c.cotizacion_lugar as lugarobra,
c.cotizacion_direccion as direccionobra,p.nombre as cliente, idEncaragadoObra, k.nomContacto as encargadoobra, nomObra,desObra,valEstimadoObra, DATE(fechaInicio)  as fechaInicio, DATE(fechaTermino) as fechaTermino, porAvanceObra,
numTrabajadores,estadoObra FROM obra o
INNER JOIN cotizacion c ON c.idcotizacion=o.numCotizacion
INNER  JOIN  persona p ON p.idpersona = c.cotizacion_idcliente
INNER  JOIN  contacto k ON k.idcontacto = o.idEncaragadoObra
 WHERE idObra='$idObra'";
	return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($id_empresa_usuaria)
	{
		$sql="SELECT idObra,numCotizacion,c.num_cotizacion as ncotizacion, c.titulo_cotizacion as titulocotizacion,c.cotizacion_lugar as lugarobra,
	c.cotizacion_direccion as direccionobra,p.nombre as cliente,idEncaragadoObra, k.nomContacto as encargadoobra, nomObra,desObra,valEstimadoObra, DATE(fechaInicio)  as fechaInicio, DATE(fechaTermino) as fechaTermino, porAvanceObra,
	numTrabajadores,estadoObra FROM obra o
	INNER JOIN cotizacion c ON c.idcotizacion=o.numCotizacion
	INNER  JOIN  persona p ON p.idpersona = c.cotizacion_idcliente
	INNER  JOIN  contacto k ON k.idcontacto = o.idEncaragadoObra
	WHERE  o.id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}

	
    public function selectcontacto()
	{
		$sql="SELECT * FROM contacto";
		return ejecutarConsulta($sql);		
	}

	public function selectcotizacion($id_empresa_usuaria)
	{
		$sql="SELECT c.idcotizacion, c.num_cotizacion, c.titulo_cotizacion  as titulo , p.num_documento as rutcliente FROM cotizacion c
		INNER JOIN  persona p ON p.idpersona=c.cotizacion_idcliente
		WHERE c.id_empresa_usuaria='$id_empresa_usuaria' and c.estado!='Anulada'
		ORDER by c.idcotizacion desc" ;
		return ejecutarConsulta($sql);		
	}


}

?>