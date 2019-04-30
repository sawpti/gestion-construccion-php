<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class ProductoServicio
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $tipo, $valor_neto)
	{
		$sql="INSERT INTO producto_servicio (idcategoria, codigo, nombre, stock, descripcion, imagen, condicion, tipo, valor_neto)
		VALUES ('$idcategoria','$codigo','$nombre','$stock','$descripcion','$imagen', '1', '$tipo','$valor_neto')";
		//return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $tipo, $valor_neto)
	{
		$sql="UPDATE producto_servicio SET  idcategoria='$idcategoria',codigo='$codigo', nombre='$nombre', stock='$stock', descripcion='$descripcion', imagen='$imagen', tipo='$tipo', valor_neto='$valor_neto' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar producto o servicio
	public function desactivar($idarticulo)
	{
		$sql="UPDATE producto_servicio SET condicion='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar productos o servicios
	public function activar($idarticulo)
	{
		$sql="UPDATE producto_servicio SET condicion='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo)
	{
		$sql="SELECT * FROM producto_servicio WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar todos los registros
	public function listar()
	{
		$sql="SELECT ps.idarticulo, ps.idcategoria, c.nombre AS categoria, ps.codigo, ps.nombre,ps.stock,
		ps.descripcion,ps.imagen, ps.tipo, ps.valor_neto FROM producto_servicio ps  INNER JOIN  categoria c ON ps.idcategoria=c.idcategoria";
		return ejecutarConsulta($sql);		
	}
}

?>