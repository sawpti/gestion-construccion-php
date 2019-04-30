<?php 
//Empresa usuaria del sistema
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Empresa
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	/*
id_empresa
nombre_legal_empresa
nombre_fantasia_empresa
slogan_empresa
direccion_empresa
giro_empresa
ciudad_empresa
empresa_idcomuna
fono_empresa
email_empresa
web_empresa
condicion_empresa
nombre_contacto
logo_empresa

$id_empresa
$nombre_legal_empresa
$nombre_fantasia_empresa
$slogan_empresa
$direccion_empresa
$giro_empresa
$ciudad_empresa
$empresa_idcomuna
$fono_empresa
$email_empresa
$web_empresa
$condicion_empresa
$nombre_contacto
$logo_empresa

*/

	//Implementamos un método para insertar registros
	public function insertar($rut_empresa,$nombre_legal_empresa, $nombre_fantasia_empresa, $slogan_empresa, $direccion_empresa, $giro_empresa, $ciudad_empresa, $empresa_idcomuna, $fono_empresa, $email_empresa, $web_empresa, $condicion_empresa, $nombre_contacto, $logo_empresa)
	{
		$sql="INSERT INTO empresa (rut_empresa,nombre_legal_empresa, nombre_fantasia_empresa, slogan_empresa, direccion_empresa, giro_empresa, ciudad_empresa, empresa_idcomuna, fono_empresa, email_empresa, web_empresa, condicion_empresa, nombre_contacto, logo_empresa)
		VALUES ('$rut_empresa', '$nombre_legal_empresa', '$nombre_fantasia_empresa', '$slogan_empresa', '$direccion_empresa', '$giro_empresa', 
		'$ciudad_empresa', '$empresa_idcomuna', '$fono_empresa', '$email_empresa', '$web_empresa', '$condicion_empresa', '$nombre_contacto', 
		'$logo_empresa')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id_empresa, $rut_empresa,$nombre_legal_empresa, $nombre_fantasia_empresa, $slogan_empresa, $direccion_empresa, $giro_empresa, $ciudad_empresa, $empresa_idcomuna, $fono_empresa, $email_empresa, $web_empresa, $condicion_empresa, $nombre_contacto, $logo_empresa)
	{
		$sql="UPDATE empresa SET  rut_empresa='$rut_empresa',nombre_legal_empresa ='$nombre_legal_empresa',
		 nombre_fantasia_empresa='$nombre_fantasia_empresa', slogan_empresa='$slogan_empresa', direccion_empresa='$direccion_empresa', giro_empresa='$giro_empresa', ciudad_empresa='$ciudad_empresa', empresa_idcomuna='$empresa_idcomuna',  fono_empresa='$fono_empresa', email_empresa='$email_empresa', web_empresa='$web_empresa', condicion_empresa='$condicion_empresa', nombre_contacto='$nombre_contacto', logo_empresa='$logo_empresa'
		WHERE id_empresa='$id_empresa'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($id_empresa)
	{
		$sql="DELETE FROM empresa WHERE id_empresa='$id_empresa'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id_empresa)
	{
		$sql="SELECT * FROM empresa WHERE id_empresa='$id_empresa'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar las empresas usuarias del sistema
	public function listar()
	{
			
		$sql="SELECT e.id_empresa, e.nombre_legal_empresa, c.nomcomuna as comuna, e.ciudad_empresa, e.direccion_empresa, e.nombre_contacto,e.email_empresa, e.logo_empresa, e.condicion_empresa		
			FROM empresa e   
			INNER JOIN comuna c ON e.empresa_idcomuna= c.idcomuna
			ORDER BY id_empresa DESC";	
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los registros comunas  y mostrar en el select
	public function selectcomuna()
	{
		$sql="SELECT * FROM comuna";
		return ejecutarConsulta($sql);		
	}
	
}

?>