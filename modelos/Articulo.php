<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Articulo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$tipo, $valor_neto, $idmedida, $id_empresa_u)
	{
		$sql="INSERT INTO producto_servicio (idcategoria,codigo,nombre,stock,descripcion, condicion, tipo, valor_neto,u_medida, id_empresa_usuaria)
		VALUES ('$idcategoria','$codigo','$nombre','$stock','$descripcion','1','$tipo','$valor_neto', '$idmedida','$id_empresa_u')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$tipo,$valor_neto,$idmedida, $id_empresa_u)
	{
		$sql="UPDATE producto_servicio SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion', tipo='$tipo', valor_neto='$valor_neto', u_medida='$idmedida' WHERE idarticulo='$idarticulo' AND id_empresa_usuaria='$id_empresa_u'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idarticulo)
	{
		$sql="UPDATE producto_servicio SET condicion='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idarticulo)
	{
		$sql="UPDATE producto_servicio SET condicion='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo)
	{
		$sql="SELECT * FROM producto_servicio WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($id_empresa_u)
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion,a.tipo, a.valor_neto, a.u_medida FROM producto_servicio a 
			 INNER JOIN categoria c ON a.idcategoria=c.idcategoria
			 WHERE id_empresa_usuaria='$id_empresa_u'";
		return ejecutarConsulta($sql);		
	}

/*Lista los productos*/
	public function listarp($id_empresa_u)
	{
	    $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion,a.tipo, a.valor_neto, a.u_medida
		 FROM producto_servicio a 
	    	 INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
	    	 WHERE tipo='P'AND id_empresa_usuaria='$id_empresa_u' 
	    	 ORDER BY c.nombre ASC";
		return ejecutarConsulta($sql);	
	}

	// Listar los productos
	public function listars($id_empresa_u)
	{
			
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion,a.tipo, a.valor_neto, a.u_medida FROM producto_servicio a 
			 INNER JOIN categoria c ON a.idcategoria=c.idcategoria  
			 WHERE tipo='S' AND id_empresa_usuaria='$id_empresa_u'";
		return ejecutarConsulta($sql);	
	}

	//Implementar un método para listar los registros activos
	public function listarProductosActivos($id_empresa_u)
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion 
			FROM producto_servicio a 
			INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
			WHERE a.condicion='1' and a.tipo='P' AND id_empresa_usuaria='$id_empresa_u'";
		return ejecutarConsulta($sql);		
	}

	/*Implementar un método para listar todos los productos y servios activos
	con la finalidad de poder elegir para agregar a detalle venta*/
	public function listarProductosServiciosActivos($id_empresa_u)
	{
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion 
			FROM producto_servicio a 
			INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
			WHERE a.condicion='1' AND id_empresa_usuaria='$id_empresa_u'";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso). Esto cuando los precios están determinados por los ingresos ( compras)
    public function listarActivosVenta($id_empresa_u)
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,
        	(SELECT precio_venta 
        		FROM detalle_ingreso 
        		WHERE idarticulo=a.idarticulo 
        		order by iddetalle_ingreso desc limit 0,1) as precio_venta,
        	a.descripcion,a.imagen,a.condicion 
        	FROM producto_servicio a 
        	INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
        	WHERE a.condicion='1' AND id_empresa_usuaria='$id_empresa_u'";
        return ejecutarConsulta($sql); 
    }

    // Cuando el precio está en la tabla producto_servicio

     public function listarActivosVenta1($id_empresa_u)
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock, a.valor_neto,a.descripcion,a.imagen,a.condicion 
	        FROM producto_servicio a 
	        INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
	        WHERE a.condicion='1' AND id_empresa_usuaria='$id_empresa_u'
	        ORDER BY c.nombre ASC";
        return ejecutarConsulta($sql); 
    }

}

?>