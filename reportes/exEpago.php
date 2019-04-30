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
if ($_SESSION['epagos']==1)
{
//Incluímos el archivo Factura.php
require('Epago.php');

//Establecemos los datos de la empresa
/*$logo = "logopp.jpg";
$ext_logo = "jpg";
$empresa = "Ema Lagos Arriagada";
$documento = "Rut:11.019.050-6 - Transporte y Construcción";
$direccion = "COLO COLO N°880 - PUCON";
$telefono = "94587877";
$email = "ventas@piedraspucon.cl";
*/
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
require_once "../modelos/Epago.php";
$epago= new Epago();
$rsptac = $epago->epagocabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regc = $rsptac->fetch_object();

//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
/*$pdf->addSociete(utf8_decode($empresa),
                 utf8_decode($documento)."\n" .
                  utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
                  utf8_decode("Teléfono: ").$telefono."\n" .
                  "Email : ".$email,$logo,$ext_logo);*/
$pdf->addSociete(utf8_decode($empresa),
                 utf8_decode("RUT: ". $documento)."\n" .
                utf8_decode($giro)."\n".utf8_decode($direccion)." - ".utf8_decode($ciudad)."\nFono:".$telefono." - ".$email."\n".$webe, $logo, $ext_logo);

$pdf->fact_dev(utf8_decode("EP N° 0$regc->numep") , utf8_decode (" - Folio N° 0$regc->idestadopago") );
$pdf->temporaire(utf8_decode("ESTADO PAGO N°$regc->numep"));//Marca de agua
$pdf->addDate( $regc->fechaEP);

//Enviamos los datos del lugar de la cotización
$comuna= strtoupper($regc->comuna);
$pdf->addLugar(utf8_decode("OBRA: ".$regc->nomObra." /CTZN N°:".$regc->ncotizacion), utf8_decode($comuna), utf8_decode($regc->lugar), utf8_decode($regc->direccion));
//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
//function addClientAdresse($cliente,$domicilio, $ciudad, $num_documento, $giro )
$pdf->addClientAdresse(utf8_decode($regc->cliente),utf8_decode($regc->direccione), utf8_decode($regc->ciudad), 
$regc->num_documento, utf8_decode($regc->giro));
//k.nomContacto as jefeobra, k.cargoContacto, k.fonoContacto, k.emailContacto
//addContacto($nombre, $cargo, $fono, $email )
$pdf->addContacto(utf8_decode($regc->jefeobra), utf8_decode($regc->cargoContacto), utf8_decode($regc->fonoContacto), utf8_decode($regc->emailContacto));
//Establecemos las columnas que va a tener la sección donde mostramos los detalles   del EP
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

//Obtenemos todos los detalles del EP actual
$rsptad = $epago->epagodetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->articulo"),
                "CANTIDAD"=> number_format("$regd->canItem",2,',','.'),
                "V.U. NETO"=> number_format("$regd->valorItem",0,',','.'),
                "DSCTO" =>number_format("$regd->descItem",0,',','.'),
                "SUBTOTAL"=>number_format("$regd->subtotal",0,',','.'));
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}

//Obtenemos todos los detalles de la venta actual
/*require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptad = $venta->epagodetalle($_GET["id"]);

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
$montototal=$regc->total_ep*(1+$regc->impuesto/100);

//$con_letra=strtoupper($V->ValorEnLetras(round($montototal),"PESOS"));
//$pdf->addCadreTVAs("-".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( $regc->impuesto, $regc->total_ep,"$ ");
$pdf->addCadreEurosFrancs("IVA (%):". number_format(" $regc->impuesto",0,',','.'));
//Se agreaga las ett y observaciones addETyObs($ett, $obns )
$pdf->addETyObs(utf8_decode($regc->obns1EP), utf8_decode($regc->obns2EP));
//$pdf->temporaire("Hola");
$pdf->addFirma(utf8_decode($_SESSION['nombre']), utf8_decode($_SESSION['cargo']));

$pdf->Output($regc->idestadopago.'-epago-'.$regc->fecha.".pdf",'I');


}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>