<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_persona,$num_documento,$nombre, $direccion, $ciudad, $giro, $telefono,$email, $web, $idcomuna)
	{
		$sql="INSERT INTO persona (tipo_persona, num_documento,nombre, direccion, ciudad, giro, telefono, email,  web,  idcomuna)
		VALUES ('$tipo_persona','$num_documento','$nombre', '$direccion', '$ciudad', '$giro', '$telefono', '$email', '$web', '$idcomuna')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idpersona,$tipo_persona,$num_documento,$nombre, $direccion, $ciudad, $giro, $telefono,$email, $web, $idcomuna)
	{
		$sql="UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',
		num_documento='$num_documento',direccion='$direccion', ciudad='$ciudad', giro='$giro', telefono='$telefono',email='$email', web='$web', idcomuna='$idcomuna' WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idpersona)
	{
		$sql="DELETE FROM persona WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpersona)
	{
		$sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los proveedores
	public function listarp()
	{
		$sql="SELECT p.idpersona, p.tipo_persona, p.num_documento,p.nombre, p.direccion, p.ciudad , c.nomcomuna as comuna, p.giro, p.telefono,p.email, p.web 
		FROM persona p   
		INNER JOIN comuna c ON p.idcomuna=c.idcomuna  
		WHERE tipo_persona='Proveedor' OR  tipo_persona='Cliente y proveedor'
		ORDER BY idpersona DESC" ;	
		return ejecutarConsulta($sql);
	
	}

	//Implementar un método para listar los clietnes
	public function listarc()
	{
		
		$sql="SELECT p.idpersona, p.tipo_persona,p.num_documento,p.nombre, p.direccion, p.ciudad ,
			c.nomcomuna as comuna,p.giro, p.telefono,p.email, p.web 
			FROM persona p   
			INNER JOIN comuna c ON p.idcomuna=c.idcomuna 
			WHERE tipo_persona='Cliente' OR  tipo_persona='Cliente y proveedor'  
			ORDER BY idpersona DESC";	
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los clietnes y proveedores
	public function listar()
	{
			
		$sql="SELECT p.idpersona, p.tipo_persona, p.num_documento,p.nombre, p.direccion, p.ciudad , c.nomcomuna as comuna,
			p.giro, p.telefono,p.email, p.web 
			FROM persona p   
			INNER JOIN comuna c ON p.idcomuna=c.idcomuna 
			ORDER BY idpersona DESC";	
		return ejecutarConsulta($sql);
	}



	//Implementar un método para listar los registros comnas  y mostrar en el select
	public function selectcomuna()
	{
		$sql="SELECT * FROM comuna";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los contactos
	public function selectcontacto()
	{
		$sql="SELECT * FROM contacto";
		return ejecutarConsulta($sql);		
	}
	
	//Método que permite mostrar los contactos de una empresa (Persona)
	public function selectcontactoxpersona($idpersona)
	{
		$sql="SELECT * FROM contacto 
		WHERE persona_id='$idpersona'";
		return ejecutarConsulta($sql);		
	}
}

?>