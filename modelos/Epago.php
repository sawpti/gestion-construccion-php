<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Epago
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}
	//Implementamos un método para insertar registros
	public function insertar($obra_id, $fechaEP, $obns1EP, $obns2EP, $numEPObra, $anexo, $numFactura, $estadoEP, $idusuario, $impuesto, $total_ep,$id_empresa_usuaria, $idarticulo,$cantidad,$precio_venta,$descuento)
	{
		$sql="INSERT INTO estadopago (obra_id,fechaEP,obns1EP,obns2EP,numEPObra,anexo,numFactura,estadoEP,idusuario,impuesto, total_ep, id_empresa_usuaria)
		VALUES ('$obra_id',' $fechaEP', '$obns1EP', '$obns2EP', '$numEPObra', '$anexo', '$numFactura', '$estadoEP', '$idusuario', '$impuesto', '$total_ep','$id_empresa_usuaria')";
		$idestadopagonew=ejecutarConsulta_retornarID($sql);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($idarticulo))
		{
			//idDetalleEP, ep_id, productoid, canItem, valorItem, descItem
			$sql_detalle = "INSERT INTO detalleep(ep_id, productoid,canItem,valorItem,descItem) VALUES ('$idestadopagonew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		return $sw;
	}

	
	//Implementamos un método para editar la cabacera de la cotización (EL detalle no se puede editar)
	public function editar($idestadopago, $obra_id, $fechaEP, $obns1EP, $obns2EP, $numEPObra, $anexo, $numFactura, $estadoEP, $idusuario, $impuesto)
	{
		$sql="UPDATE  estadopago SET obra_id ='$obra_id', fechaEP='$fechaEP', 
		obns1EP='$obns1EP', obns2EP='$obns2EP', numEPObra='$numEPObra', anexo='$anexo', numFactura='$numFactura', estadoEP='$estadoEP', idusuario='$idusuario', impuesto='$impuesto'  WHERE idestadopago='$idestadopago'";
		return ejecutarConsulta($sql);
		
	}
	
	//Implementamos un método para rechazar una cotización, cuando el cliente no la acepta
	public function anular($idestadopago)
	{
		$sql="UPDATE estadopago SET estadoEP='Anulado' WHERE idestadopago='$idestadopago'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idestadopago)
	{
		$sql="SELECT ep.idestadopago, ep.obra_id, DATE(ep.fechaEP) as fechaEP,ep.obns1EP, ep.obns2EP, ep.numEPObra,ep.anexo,  ep.numFactura, ep.estadoEP,
			ep.impuesto, ep.total_ep
			FROM estadopago ep 
			WHERE ep.idestadopago='$idestadopago'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/*public function listarDetalle($idventa)
	{
		$sql="SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}*/

	 public function listarDetalle($idestadopago)
    {
        $sql="SELECT dep.idDetalleEP, dep.ep_id,p.nombre, dep.canItem, dep.valorItem,  
              dep.descItem,(dep.canItem*dep.valorItem-dep.descItem) as subtotal 
            FROM detalleep dep 
			INNER JOIN producto_servicio p on dep.productoid=p.idarticulo 
        	WHERE dep.ep_id='$idestadopago'";
       	 return ejecutarConsulta($sql);
    }

//Listar todos los registros
  public function listar($id_empresa_usuaria)
	{
			$sql="SELECT ep.idestadopago, ep.obra_id, p.nombre as cliente, o.nomObra,ep.numEPObra as numep, k.nomContacto as jefeobra, o.numCotizacion, c.titulo_cotizacion, DATE(ep.fechaEP) as fechaEP,ep.obns1EP, ep.obns2EP, ep.anexo,  ep.numFactura, ep.idusuario,
		  u.nombre as nombreu, ep.impuesto , ep.total_ep,  ep.estadoEP
		FROM estadopago ep 
		INNER JOIN obra o ON o.idObra=ep.obra_id
		INNER JOIN cotizacion c ON c.idcotizacion=o.numCotizacion
		INNER JOIN persona p ON p.idpersona=c.cotizacion_idcliente
		INNER JOIN contacto k ON k.idContacto= o.idEncaragadoObra
		INNER JOIN usuario u ON u.idusuario=ep.idusuario
		WHERE ep.id_empresa_usuaria='$id_empresa_usuaria'
       	ORDER by ep.idestadopago desc";
      return ejecutarConsulta($sql);
	}



	/* Funciones para el reporte estadopago*/
    public function epagocabecera($idestadopago)

    {
        $sql="SELECT ep.idestadopago, ep.obra_id, p.nombre as cliente, p.num_documento, p.giro, p.direccion as direccione,p.ciudad, o.nomObra,ep.numEPObra as numep, 
k.nomContacto as jefeobra, k.cargoContacto, k.fonoContacto, k.emailContacto, o.numCotizacion,
c.titulo_cotizacion, com.nomcomuna  as comuna, c.cotizacion_lugar as lugar, c.num_cotizacion as ncotizacion, c.cotizacion_direccion as direccion, 
DATE(ep.fechaEP) as fechaEP,ep.obns1EP, ep.obns2EP, ep.anexo,  ep.numFactura, ep.idusuario,
		  u.nombre as nombreu, ep.impuesto , ep.total_ep,  ep.estadoEP
		FROM estadopago ep 
		INNER JOIN obra o ON o.idObra=ep.obra_id
		INNER JOIN cotizacion c ON c.idcotizacion=o.numCotizacion
		INNER JOIN persona p ON p.idpersona=c.cotizacion_idcliente
		INNER JOIN contacto k ON k.idContacto= o.idEncaragadoObra
		INNER JOIN usuario u ON u.idusuario=ep.idusuario
        INNER JOIN comuna com ON com.idcomuna=c.cotizacion_idcomuna
        WHERE ep.idestadopago ='$idestadopago'";

        return ejecutarConsulta($sql);
    }

    
    public function epagodetalle($idepago){
        $sql= "SELECT  p.codigo,p.nombre as articulo,  dep.canItem, dep.valorItem,dep.descItem,
        (dep.canItem*dep.valorItem-dep.descItem)as subtotal
        FROM detalleep dep 
        INNER JOIN producto_servicio p ON dep.productoid=p.idarticulo
        WHERE dep.ep_id ='$idepago'";
        return ejecutarConsulta($sql);

    }

// Metodo para cargar las obras en un select

   /*   public function selectcobra1($id_empresa_usuaria)
	{
		$sql="SELECT * FROM obra
		WHERE id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}*/
	
	 public function selectcobra($id_empresa_usuaria)
	{
		$sql="SELECT idObra, nomObra, c.num_cotizacion as ncotizacion FROM obra o
	     INNER JOIN cotizacion c ON c.idcotizacion=o.numCotizacion
	    WHERE o.id_empresa_usuaria='$id_empresa_usuaria'";
		return ejecutarConsulta($sql);		
	}
	

	
	


}
?>