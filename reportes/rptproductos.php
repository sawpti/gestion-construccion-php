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
if ($_SESSION['almacen']==1)
{
  $idempresa_u=$_SESSION['id_empresa'];

//Inlcuímos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//Instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();

//Agregamos la primera página al documento pdf
$pdf->AddPage();

//Seteamos el inicio del margen superior en 25 pixeles 
$y_axis_initial = 25;

//Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá
$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(100,6,'LISTA DE PRODUCTOS Y SERVICIOS',1,0,'C');
//$pdf->Cell(100,11, $pdf->Image('../reportes/logopp.jpg',  $pdf->GetX(), $pdf->GetY(),11),1);
$pdf->Ln(10);

//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(80,6,'Nombre',1,0,'C',1); 
$pdf->Cell(60,6,utf8_decode('Categoría'),1,0,'C',1);
$pdf->Cell(25,6,utf8_decode('V. Neto'),1,0,'C',1);
$pdf->Cell(25,6,utf8_decode('V. IVA Incl.'),1,0,'C',1);
//$pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
//$pdf->Cell(12,6,'Stock',1,0,'C',1);
//$pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
$pdf->Ln(10);

//Comenzamos a crear las filas de los registros según la consulta mysql
require_once "../modelos/Articulo.php";
$articulo = new Articulo();

$rspta = $articulo->listar($idempresa_u);

//Implementamos las celdas de la tabla con los registros a mostrar
$pdf->SetWidths(array(80,60,25,25/*,35*/));

while($reg= $rspta->fetch_object())
{  
    $nombre = $reg->nombre;
    $categoria = $reg->categoria;

    $valorneto=number_format($reg->valor_neto,0,',','.');
    $valorconiva=number_format($reg->valor_neto*0.19+$reg->valor_neto, 0,',','.');
   // $codigo = $reg->codigo;
  //  $stock = $reg->stock;
    //$descripcion =$reg->descripcion;
 	
 	$pdf->SetFont('Arial','',10);
    $pdf->Row(array($reg->codigo."-".utf8_decode($nombre),utf8_decode($categoria),$valorneto,$valorconiva/*, utf8_decode($descripcion)*/));
}

//Mostramos el documento pdf
$pdf->Output();
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>