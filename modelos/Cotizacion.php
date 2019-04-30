<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Cotizacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($cotizacion_idcliente,$cotizacion_idcontacto,$cotizacion_idusuario,$titulo_cotizacion,$fecha_hora,$cotizacion_lugar, $cotizacion_direccion, $cotizacion_idcomuna, $esp_tecnicas,$observaciones,$impuesto,$total_cotizacion, $id_empresa_usuaria, $num_cotizacion,
	 $idarticulo,$cantidad,$precio_venta,$descuento)
	{
		$sql="INSERT INTO cotizacion (cotizacion_idcliente, cotizacion_idcontacto, cotizacion_idusuario, titulo_cotizacion, fecha_hora, cotizacion_lugar, cotizacion_direccion, cotizacion_idcomuna, espe_tecnicas, observaciones, impuesto, total_cotizacion, estado, id_empresa_usuaria, num_cotizacion )
		VALUES ('$cotizacion_idcliente','$cotizacion_idcontacto','$cotizacion_idusuario','$titulo_cotizacion','$fecha_hora','$cotizacion_lugar', '$cotizacion_direccion', '$cotizacion_idcomuna', '$esp_tecnicas','$observaciones','$impuesto','$total_cotizacion','Enviada','$id_empresa_usuaria', '$num_cotizacion')";
		//return ejecutarConsulta($sql);
		$idcotizacionnew=ejecutarConsulta_retornarID($sql);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($idarticulo))
		{
			/*Recordar que el detalle de las ventas y las cotizaciones están en la misma tabla*/
			$sql_detalle = "INSERT INTO detalle_cotizacion(dc_idcotizacion, idarticulo,cantidad,precio_venta,descuento) VALUES ('$idcotizacionnew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		return $sw;
	}
	
	//Implementamos un método para editar la cabacera de la cotización (EL detalle no se puede editar)
	public function editar($idcotizacion, $cotizacion_idcliente,$cotizacion_idcontacto,$cotizacion_idusuario,$titulo_cotizacion,$fecha_hora,$cotizacion_lugar, $cotizacion_direccion, $cotizacion_idcomuna, $esp_tecnicas,$observaciones,$impuesto)
	{
		$sql="UPDATE  cotizacion SET cotizacion_idcliente ='$cotizacion_idcliente', cotizacion_idcontacto='$cotizacion_idcontacto', 
		cotizacion_idusuario='$cotizacion_idusuario', titulo_cotizacion='$titulo_cotizacion', fecha_hora='$fecha_hora', cotizacion_lugar='$cotizacion_lugar', cotizacion_direccion='$cotizacion_direccion', cotizacion_idcomuna='$cotizacion_idcomuna', espe_tecnicas='$esp_tecnicas', observaciones='$observaciones', impuesto='$impuesto' WHERE idcotizacion='$idcotizacion'";
		return ejecutarConsulta($sql);
		//$idcotizacionnew=ejecutarConsulta_retornarID($sql);
	}
	

	
	//Implementamos un método para rechazar una cotización, cuando el cliente no la acepta
	public function anular($idcotizacion)
	{
		$sql="UPDATE cotizacion SET estado='Anulada' WHERE idcotizacion='$idcotizacion'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcotizacion)
	{
		$sql="SELECT c.idcotizacion ,DATE(c.fecha_hora) as fecha,c.cotizacion_idcliente,p.nombre as cliente, con.idContacto, con.nomContacto as contacto, u.idusuario,u.nombre as usuario, c.cotizacion_idcomuna, com.nomcomuna as comuna, cotizacion_lugar as lugar, c.cotizacion_direccion as direccion, c.espe_tecnicas as etecnicas, c.observaciones, c.titulo_cotizacion as titulo,  c.total_cotizacion,c.impuesto,c.estado , c.num_cotizacion
		FROM cotizacion c 
        INNER JOIN persona p ON c.cotizacion_idcliente=p.idpersona 
        INNER JOIN usuario u ON c.cotizacion_idusuario=u.idusuario  
        INNER JOIN contacto con ON c.cotizacion_idcontacto=con.idContacto
        INNER JOIN comuna com ON c.cotizacion_idcomuna=com.idcomuna

        WHERE c.idcotizacion='$idcotizacion'";
		return ejecutarConsultaSimpleFila($sql);
	}

	 public function listarDetalle($idcotizacion)
    {
        $sql="SELECT dc.dc_idcotizacion, dc.idarticulo,a.nombre,dc.cantidad,dc.precio_venta,dc.descuento,(dc.cantidad*dc.precio_venta-dc.descuento) as subtotal FROM detalle_cotizacion dc 
       INNER JOIN producto_servicio a on dc.idarticulo=a.idarticulo 
       WHERE dc.dc_idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

	//Implementar un método para listar los registros


  public function listar($id_empresa_usuaria)
	{
		$sql="SELECT c.idcotizacion ,DATE(c.fecha_hora) as fecha,c.cotizacion_idcliente,p.nombre as cliente, con.idContacto, con.nomContacto as contacto, u.idusuario,u.nombre as usuario, c.total_cotizacion,c.impuesto,c.estado, c.num_cotizacion FROM cotizacion c 
        INNER JOIN persona p ON c.cotizacion_idcliente=p.idpersona 
        INNER JOIN usuario u ON c.cotizacion_idusuario=u.idusuario  
        INNER JOIN contacto con ON c.cotizacion_idcontacto=con.idContacto
        WHERE c.id_empresa_usuaria='$id_empresa_usuaria'
        ORDER by c.idcotizacion desc";
		return ejecutarConsulta($sql);
	}


	/* Funciones para el reporte Cotizacion*/
    public function cotizacioncabecera($idcotizacion){
        $sql="SELECT c.idcotizacion ,c.cotizacion_idcliente , p.nombre as cliente, p.direccion, p.ciudad, p.giro, p.num_documento,p.email, 
         p.telefono, 
         c.cotizacion_idusuario, u.nombre as usuario, 
         c.titulo_cotizacion, c.cotizacion_lugar,  c.cotizacion_direccion, c.cotizacion_idcomuna, c.num_cotizacion, k.nomcomuna as comuna,
         c.espe_tecnicas, c.observaciones,
         date(c.fecha_hora) as fecha,
         c.impuesto,c.total_cotizacion,
         con.nomContacto, con.cargoContacto, con.fonoContacto, con.emailContacto
         FROM cotizacion c 
         INNER JOIN persona p ON c.cotizacion_idcliente = p.idpersona
         INNER JOIN usuario u ON c.cotizacion_idusuario =u.idusuario
         INNER JOIN comuna k  ON c.cotizacion_idcomuna=k.idcomuna
         INNER JOIN contacto con  ON c.cotizacion_idcontacto=con.idContacto
         WHERE c.idcotizacion ='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    //Detalle del resporte cotizacion
    public function cotizaciondetalle($idcotizacion){
        $sql= "SELECT  a.nombre as articulo, a.codigo, d.cantidad, d.precio_venta,d.descuento,
        (d.cantidad*d.precio_venta-d.descuento)as subtotal
        FROM detalle_cotizacion d 
        INNER JOIN producto_servicio a ON d.idarticulo=a.idarticulo
        WHERE d.dc_idcotizacion='$idcotizacion' ";
        return ejecutarConsulta($sql);

    }

//Obtiene el ultimo numero de cotizacion de una empresa usuaria
     public function obtenerultimonum($idempresa){
        $sql= "SELECT num_cotizacion 
				FROM cotizacion 
				WHERE id_empresa_usuaria='$idempresa'
				ORDER BY idcotizacion DESC LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);

    }

}
?>