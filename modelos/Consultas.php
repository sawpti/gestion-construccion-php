<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
	//Implementamos nuestro constructor

	public function __construct()
	{

	}

	public function comprasfecha($fecha_inicio,$fecha_fin, $id_empresa_usuaria)
	{
		$sql="SELECT DATE(i.fecha_hora) as fecha,u.nombre as usuario, p.nombre as proveedor,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado 
		FROM ingreso i 
		INNER JOIN persona p ON i.idproveedor=p.idpersona 
		INNER JOIN usuario u ON i.idusuario=u.idusuario 
		WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' AND  i.id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}

	public function ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente, $id_empresa_usuaria)
	{
		$sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado 
		FROM venta v 
		INNER JOIN persona p ON v.idcliente=p.idpersona 
		INNER JOIN usuario u ON v.idusuario=u.idusuario 
		WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' AND v.id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}

	public function ventasProductoServicios($fecha_inicio,$fecha_fin,$idarticulo, $id_empresa_usuaria)
	{
		$sql="SELECT DATE(v.fecha_hora) as fecha, v.tipo_comprobante, v.num_comprobante,v.impuesto, dv.cantidad, dv.precio_venta, dv.descuento,
		(dv.cantidad* dv.precio_venta- dv.descuento) as total_neto
		FROM detalle_venta dv
		INNER JOIN venta v ON dv.idventa=v.idventa 
		WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND dv.idarticulo='$idarticulo' AND v.id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}

    public function totalcomprahoy($idempresa)
	{
		$sql="SELECT IFNULL(SUM(total_compra),0) as total_compra 
		FROM ingreso 
		WHERE DATE(fecha_hora)=curdate() AND estado='Aceptado' AND id_empresa_usuaria='$idempresa'";
		return ejecutarConsulta($sql);
	}

    public function totalventahoy($idempresa)
	{
		$fechaActual = date('Y-m-d');
		$sql="SELECT IFNULL(SUM(total_venta),0) as total_venta 
		FROM venta 
		WHERE DATE(fecha_hora)='$fechaActual' AND estado='Aceptado' AND id_empresa_usuaria='$idempresa'";
		return ejecutarConsulta($sql);
	}
	
	public function productosxcatgoria($idcategoria)
	{
		$sql= "SELECT * FROM producto_servicio   WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);

	}

	/* public function totalcomprahoy()
    {
        $sql="SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
        return ejecutarConsulta($sql);
    }
 
    public function totalventahoy()
    {
        $sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)=curdate()";
        return ejecutarConsulta($sql);
    }*/

    public function gastosxobra($idobra)
	{
		$sql="SELECT o.nomObra as obra, go.des_gl_gasto as gasto, go.num_documento as documento, go.monto_gasto as monto
		FROM gastos_obra go
		INNER JOIN obra o ON go.gasto_idObra=o.idObra
		WHERE gasto_idObra='$idobra'";
		return ejecutarConsulta($sql);
	}
	public function totalgastoObra($idobra){
		$sql="SELECT SUM(monto_gasto) as total
		FROM gastos_obra 
		WHERE gasto_idObra='$idobra'";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function comprasultimos_10dias($idempresa)
	{
		$sql="SELECT CONCAT(DAY(fecha_hora),'/',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total 
		FROM ingreso
		WHERE id_empresa_usuaria='$idempresa'
		GROUP by fecha_hora 
		ORDER BY fecha_hora DESC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_12meses($idempresa)
	{
		$sql="SELECT DATE_FORMAT(fecha_hora,'%m/%Y') as fecha,SUM(total_venta) as total
		FROM venta
		WHERE id_empresa_usuaria='$idempresa' AND estado='Aceptado'
		GROUP by fecha 
		ORDER BY fecha_hora DESC limit 0,12";
		return ejecutarConsulta($sql);
	}
	
/*		public function ventasultimos_12meses($idempresa)
	{
		$sql="SELECT DATE_FORMAT(fecha_hora,'%m/%Y') as fecha,SUM(total_venta) as total
		FROM venta
		WHERE id_empresa_usuaria='$idempresa' AND estado='Aceptado'
		GROUP by MONTH(fecha_hora) 
		ORDER BY fecha_hora DESC limit 0,12";
		return ejecutarConsulta($sql);
	}*/

	 public function selectProductosServicios($id_empresa_u)
    {
        $sql="SELECT a.idarticulo,a.nombre 
	        FROM producto_servicio a 
	        WHERE a.condicion='1' AND id_empresa_usuaria='$id_empresa_u'
	        ORDER BY a.nombre ASC";
        return ejecutarConsulta($sql); 
    }








}

?>