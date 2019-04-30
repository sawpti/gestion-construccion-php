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
if ($_SESSION['cotizaciones']==1)
{
//Incluímos el archivo Cotización.php
require('Cotizacion.php');

//Establecemos los datos de la empresa
$logo =  $_SESSION['logo_empresa'];
$ext_logo = "jpg";
$empresa = $_SESSION['nombre_empresa'];
$documento =$_SESSION['rut_empresa'];
$direccion = $_SESSION['direccion_empresa'];
$ciudad= $_SESSION['ciudad_empresa'];
$telefono = $_SESSION['fono_empresa'];
$email = $_SESSION['email_empresa'];
$giro=$_SESSION['giro_empresa'];
$webe=$_SESSION['web_empresa'];


//Obtenemos los datos de la cabecera de la cotización
require_once "../modelos/Cotizacion.php";
$cotizacion= new Cotizacion();
$rsptac = $cotizacion->cotizacioncabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regc = $rsptac->fetch_object();

//Establecemos la configuración de la cotización
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
$pdf->addSociete(utf8_decode($empresa),
                 utf8_decode("RUT: ". $documento)."\n" .
                utf8_decode($giro)."\n".utf8_decode($direccion)." - ".utf8_decode($ciudad)."\nFono:".$telefono." - ".$email."\n".$webe, $logo, $ext_logo);

$pdf->fact_dev(utf8_decode("Cotización ") /*$regv->serie_comprobante-*/, utf8_decode ("N° $regc->num_cotizacion") );


$pdf->temporaire( "COTIZACION" );//Marca de agua
$pdf->addDate( $regc->fecha);

//Enviamos los datos del lugar de la cotización
 //addLugar($titulo, $comuna, $lugar, $direccion )
$comuna= strtoupper($regc->comuna);
$pdf->addLugar(utf8_decode($regc->titulo_cotizacion), utf8_decode($comuna), utf8_decode($regc->cotizacion_lugar), utf8_decode($regc->cotizacion_direccion));


//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
//function addClientAdresse($cliente,$domicilio, $comuna, $num_documento, $giro, $email,$telefono )
$pdf->addClientAdresse(utf8_decode($regc->cliente),utf8_decode($regc->direccion), utf8_decode($regc->ciudad), $regc->num_documento, utf8_decode($regc->giro),  $regc->email, $regc->telefono);
//addContacto($nombre, $cargo, $fono, $email )
$pdf->addContacto(utf8_decode($regc->nomContacto), utf8_decode($regc->cargoContacto), utf8_decode($regc->fonoContacto), utf8_decode($regc->emailContacto));


//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
             "DESCRIPCION"=>78,
             "CANTIDAD"=>22,
             "V.U. NETO"=>25,
             "DSCTO"=>20,
             "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "V.U. NETO"=>"R",
             "DSCTO" =>"R",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 90;

//Obtenemos todos los detalles de la cotización actual
$rsptad = $cotizacion->cotizaciondetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "COD"."$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> number_format("$regd->cantidad",2,',','.'),
                "V.U. NETO"=> number_format("$regd->precio_venta",0,',','.'),
                "DSCTO" =>number_format("$regd->descuento",0,',','.'),
                "SUBTOTAL"=>number_format("$regd->subtotal",0,',','.'));
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}

//Obtenemos todos los detalles de la venta actual
/*require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptad = $venta->cotizaciondetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> number_format("$regd->cantidad",2,',','.'),
                "P.U."=> number_format("$regd->precio_venta",0,',','.'),
                "DSCTO" =>number_format("$regd->descuento",0,',','.'),
                "SUBTOTAL"=>number_format("$regd->subtotal",0,',','.'));
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}*/


//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 
$montototal=$regc->total_cotizacion*(1+$regc->impuesto/100);

//$con_letra=strtoupper($V->ValorEnLetras(round($montototal),"PESOS"));
//$pdf->addCadreTVAs("-".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( $regc->impuesto, $regc->total_cotizacion,"$ ");
$pdf->addCadreEurosFrancs("Impuesto (%):". number_format(" $regc->impuesto",0,',','.'));
//Se agreaga las ett y observaciones addETyObs($ett, $obns )
$pdf->addETyObs(utf8_decode($regc->espe_tecnicas), utf8_decode($regc->observaciones));
//$pdf->temporaire("Hola");
$pdf->addFirma(utf8_decode($_SESSION['nombre']), utf8_decode($_SESSION['cargo']));

$pdf->Output(($regc->num_cotizacion).'-cotizacion-'.$regc->fecha.".pdf",'I');


}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>