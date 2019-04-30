<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{
//Incluímos el archivo Factura.php
require('Factura.php');

//Establecemos los datos de la empresa
/*$logo = "logopp.jpg";
$ext_logo = "jpg";
$empresa = "Ema Lagos Arriagada";
$documento = "Rut:11.019.050-6 - Transporte y Construcción";
$direccion = "COLO COLO N°880 - PUCON";
$telefono = "94587877";
$email = "ventas@piedraspucon.cl";*/

$logo =  $_SESSION['logo_empresa'];
$ext_logo = "jpg";
$empresa = $_SESSION['nombre_empresa'];
$documento =$_SESSION['rut_empresa'];
$direccion = $_SESSION['direccion_empresa'];
$ciudad= $_SESSION['ciudad_empresa'];
$telefono = $_SESSION['fono_empresa'];
$email = $_SESSION['email_empresa'];
$giro=  $_SESSION['giro_empresa'];
$webe=$_SESSION['web_empresa'];

//$comuna= strtoupper($regc->comuna);



//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptav = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura

$pdf->addSociete(utf8_decode($empresa),
                 utf8_decode("RUT:".$documento)."\n". utf8_decode($giro). " \n".
                  utf8_decode($direccion)."-".utf8_decode($ciudad)."\n".
                  utf8_decode("Fono: ").$telefono." - " .
                  "Email : ".$email."\n".$webe,$logo,$ext_logo);
$pdf->fact_dev( "$regv->tipo_comprobante " /*$regv->serie_comprobante-*/, utf8_decode ("N° $regv->num_comprobante") );
$pdf->temporaire( "BORRADOR" );//Marca de agua
$pdf->addDate( $regv->fecha);


//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse(utf8_decode($regv->cliente), "\n".utf8_decode($regv->direccion)."-". utf8_decode($regv->ciudad), "RUT: ".$regv->num_documento."\n".$regv->giro,"\nEmail: ".$regv->email,"\nTelefono: ".$regv->telefono);

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
             "DESCRIPCION"=>78,
             "CANTIDAD"=>22,
             "P.U."=>25,
             "DSCTO"=>20,
             "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO" =>"R",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 89;

//Obtenemos todos los detalles de la venta actual
$rsptad = $venta->ventadetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> number_format("$regd->cantidad",2,',','.'),
                "P.U."=> number_format("$regd->precio_venta",0,',','.'),
                "DSCTO" =>number_format("$regd->descuento",0,',','.'),
                "SUBTOTAL"=>number_format("$regd->subtotal",0,',','.'));
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}


//Se agreaga las ett y observaciones addETyObs($ett, $obns )
$pdf->addETyObs(utf8_decode($regv->obs_venta));

//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 
$montototal=$regv->total_venta*(1+$regv->impuesto/100);

$con_letra=utf8_decode(strtoupper($V->ValorEnLetras(round($montototal),"PESOS")));

//$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta*(1+$regv->impuesto/100),"PESOS"));
$pdf->addCadreTVAs("---".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( $regv->impuesto, $regv->total_venta,"$ ");
$pdf->addCadreEurosFrancs("IVA (%):". number_format(" $regv->impuesto ",0,',','.'));
$pdf->Output('Reporte de Venta','I');


}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>