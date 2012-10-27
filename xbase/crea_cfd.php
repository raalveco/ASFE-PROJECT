<?php

$archivo_key = "archivosPEM/aaa010101aaa_csd_01.key.pem";
$archivo_cer = "archivosPEM/aaa010101aaa_csd_01.cer.pem";

ob_end_clean();
require('fpdf.php'); //para crear el pdf
require('numero_a_letra.php'); // funcion para convertir el total a letra
	
//datos de la empresa que factura
$rfc_emisor='ISP900909Q88';
$razon_social_emisor='Industrias del Sur Poniente, S.A. de C.V.';
$calle_emisor='Alvaro Obregón';
$num_exterior_emisor='37';
$colonia_emisor='Roma Norte';
$municipio_emisor='Cuauhtémoc';
$estado_emisor='Distrito Federal';
$codigo_postal_emisor='06700';
$pais_emisor='México';
$regimen_fiscal = $_REQUEST['regimen_emisor'];

require('rsa.php'); 

//Obtener el certificado del archivo .cer, que previamente tuvo que haber sido convertido a formato PEM
$certificado = Rsa::cargarCertificado($archivo_cer);
	
//recupero las variables
$forma_pago=trim($_REQUEST['forma_pago']); $tipo_cfd=trim($_REQUEST['tipo_cfd']); trim($fecha=$_REQUEST['fecha']);
$metodo_pago = trim($_REQUEST['metodo_pago']); $lugar_expedicion = trim($_REQUEST['lugar_expedicion']);
$aprobacion=trim($_REQUEST['aprobacion']); $year_aprobacion=trim($_REQUEST['year_aprobacion']);
$serie=trim($_REQUEST['serie']); $folio=trim($_REQUEST['folio']); $dias_credito=trim($_REQUEST['dias_credito']); 
$iva=trim($_REQUEST['iva']); $num_certificado=trim($_REQUEST['numero_certificado']);
	
$rfc=trim($_REQUEST['rfc']); $razon_social=trim($_REQUEST['razon_social']);
$calle= trim($_REQUEST['calle']); $num_exterior= trim($_REQUEST['num_exterior']); $num_interior= trim($_REQUEST['num_interior']);
$colonia= trim($_REQUEST['colonia']); $localidad=trim($_REQUEST['localidad']);
$municipio=trim($_REQUEST['municipio']); $estado=trim($_REQUEST['estado']); $pais=trim($_REQUEST['pais']);
$codigo_postal=trim($_REQUEST['codigo_postal']); $referencia=trim($_REQUEST['referencia']);
	
$d1=trim($_REQUEST['d1']); $precio1=trim($_REQUEST['precio1']); $cantidad1=trim($_REQUEST['cantidad1']); $unidad1=$_REQUEST['unidad1'];
$d2=trim($_REQUEST['d2']); $precio2=trim($_REQUEST['precio2']); $cantidad2=trim($_REQUEST['cantidad2']); $unidad2=$_REQUEST['unidad2'];
$d3=trim($_REQUEST['d3']); $precio3=trim($_REQUEST['precio3']); $cantidad3=trim($_REQUEST['cantidad3']); $unidad3=$_REQUEST['unidad3'];
$d4=trim($_REQUEST['d4']); $precio4=trim($_REQUEST['precio4']); $cantidad4=trim($_REQUEST['cantidad4']); $unidad4=$_REQUEST['unidad4'];
$d5=trim($_REQUEST['d5']); $precio5=trim($_REQUEST['precio5']); $cantidad5=trim($_REQUEST['cantidad5']); $unidad5=$_REQUEST['unidad5'];
$descuento=trim($_REQUEST['descuento']);
	
	$iva= $iva/100;         
   	$numero = $serie."-".$folio; //numero de factura
	$monto1 = $cantidad1 * $precio1;
	$monto2 = $cantidad2 * $precio2;
	$monto3 = $cantidad3 * $precio3;
	$monto4 = $cantidad4 * $precio4;
	$monto5 = $cantidad5 * $precio5;
    $subtotal = $monto1+$monto2+$monto3+$monto4+$monto5;
    $subtotal2 = $subtotal - $descuento;
	$iva = $iva*$subtotal2;
    $total = $subtotal2 + $iva;
			
class PDF extends FPDF
{
    //Encabezado de página
    function Header()
    {   
		$this->SetFillColor(140,240,90);
        $this->Image('imgs/logo.jpg',6,8,56);
		$this->Image('imgs/back.jpg',57,85,100);
		//$this->Image('imgs/back2.jpg',57,86,150);
		$this->Image('imgs/cedula.jpg',10,192,39);
		$this->SetFont('Arial','B',12);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Industrias del Sur Poniente, S.A. de C.V."),0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"FACTURA",1,1,'C',true);
		$this->SetFont('Arial','B',9);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("R.F.C. ISP900909Q88"),0,0,'C');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4,"Serie: ".$_REQUEST['serie'],1,0,'C');
		$this->Cell(25,4,"Folio: ".$_REQUEST['folio'],1,1,'C');
		$this->SetFont('Arial','B',8);
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode("Alvaro Obregón Num. 37, Col. Roma Norte"),0,1,'C');
		$this->Cell(50,4,"",0,0,'C');
		$this->Cell(95,4,utf8_decode('Del. Cuauhtémoc, México Distrito Federal, CP. 06700'),0,1,'C');
		$this->Ln(4);
    } 
}
        
    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->AddPage();    
    $pdf->Ln(7);
	$pdf->SetFillColor(140,240,90);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(105,4,"CLIENTE",1,0,'C',true);
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->SetFont('Arial','',8);
	
	//datos del cliente y datos del CFD
	$pdf->Cell(80,4,utf8_decode("Lugar de Expedición: $pais_emisor $estado_emisor."),"LRT",1,'L');
	$pdf->Cell(105,4,utf8_decode($razon_social),"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Fecha y Hora: $fecha"),"LR",1,'L');
	$pdf->Cell(105,4,"RFC: ".$rfc,"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Certificado: $num_certificado"),"LR",1,'L');
	$pdf->Cell(105,4,utf8_decode("Calle $calle #$num_exterior $num_interior, Col. $colonia"),"LR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,utf8_decode("Aprobación: $aprobacion   Año: $year_aprobacion"),"LR",1,'L');
	$pdf->Cell(105,4,utf8_decode("$municipio, $estado, $pais CP.$codigo_postal"),"LBR",0,'L');
	$pdf->Cell(10,4,"",0,0,'C');
	$pdf->Cell(80,4,"","LRB",1,'L');
    
	$pdf->Ln(5);
	$pdf->Cell(195,4,utf8_decode('Régimen fiscal: Regimen General de Ley Personas Morales'),'TLR',1,'L',false);
	$pdf->Cell(195,4,utf8_decode('Forma de  pago: '.$forma_pago),'LR',1,'L',false);
	$pdf->Cell(195,4,utf8_decode('Método de pago: '.$metodo_pago),'RLB',1,'l',false);
			
	
	//detalle de conceptos
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',8);
    $pdf->Cell(105,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',true);
    $pdf->Cell(30,5,'PRECIO',1,0,'C',true);
    $pdf->Cell(30,5,"CANTIDAD",1,0,'C',true);
    $pdf->Cell(30,5,"MONTO",1,1,'C',true);
	$pdf->SetFont('Arial','',8);
                               
	if ($d1!=""){
    $posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d1),"L",'L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio1, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad1." unidad1",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto1, 2, '.', ','),'LR',1,'C');} 	
								
    if ($d2!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d2),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio2, 2, '.', ','),"L",0,'C');
    $pdf->Cell(30,$dif_y,$cantidad2." $unidad2",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto2, 2, '.', ','),'LR',1,'C');}         
            
    if ($d3!=""){                         
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d3),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio3, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad3." $unidad3",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto3, 2, '.', ','),'LR',1,'C');}
            
    if ($d4!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,5,"\n".utf8_decode($d4),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio4, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad4." $unidad4",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto4, 2, '.', ','),'LR',1,'C');}
            
    if ($d5!=""){
	$posy1=$pdf->GetY();//posición antes de escribir concepto
    $pdf->MultiCell(105,4,"\n".utf8_decode($d5),'L','L');
    $posy2=$pdf->GetY();$posX2=$pdf->GetX();//posicion despues de escribir concepto
    $dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas
    $pdf->SetY($posy1);$pdf->SetX(115);//reposiciono Y y X despues del concepto, 10 de margen en x
    $pdf->Cell(30,$dif_y,"$".number_format($precio5, 2, '.', ','),'L',0,'C');
    $pdf->Cell(30,$dif_y,$cantidad5." $unidad5",'L',0,'C');
    $pdf->Cell(30,$dif_y,"$".number_format($monto5, 2, '.', ','),'LR',1,'C');}
         
	//cerrar tabla de conceptos
    $h = 190-($pdf->GetY());
    $pdf->Cell(105,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LB',0,'C');
    $pdf->Cell(30,$h," ",'LRB',1,'C');
            
    //subtotal y pagarè
	$pdf->SetFont('Arial','',6);
    $pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("Debo y pagaré a la orden de $razon_social_emisor "),0,0,'L');
	$pdf->SetFont('Arial','',8); $pdf->Cell(30,5,"Subtotal: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($subtotal, 2, '.', ','),0,1,'C');

    //descuento 
    $pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("en cualquier plaza donde se requiera el pago de la cantidad consignada"),0,0,'L');
    $pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Descuento: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($descuento, 2, '.', ','),0,1,'C');

    //subtotal 2
    $pdf->SetFont('Arial','',6);
	$pdf->Cell(42,4," ",0,0,'L');
    $pdf->Cell(93,4,utf8_decode("en éste título de credito, en un plazo no mayor a $dias_credito dias a partir del $fecha"),0,0,'L');
    $pdf->SetFont('Arial','',8); $pdf->Cell(30,4,"Subtotal: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($subtotal2, 2, '.', ','),0,1,'C');

    //IVA y ejecutivo
    $pdf->Cell(165,4,"IVA: ",0,0,'R');
    $pdf->Cell(30,4,"$".number_format($iva, 2, '.', ','),0,1,'C');

    //cantidad con letra y total
    $letras=utf8_decode(num2letras($total,0,0)." pesos  ");
	$total_cadena=$total;
	$total = "$".number_format($total, 2, '.', ',');
	$ultimo = substr (strrchr ($total, "."), 1 ); //recupero lo que este despues del decimal
	$letras = $letras." ".$ultimo."/100 M. N.";
			
	$pdf->SetFont('Arial','',6);
    $pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"____________________",0,0,'C');
			
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30,4,"Total: ",0,0,'R');
    $pdf->Cell(30,4,$total,0,1,'C');
			
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(100,4,"Firma",0,1,'C');
			
	$pdf->Ln(3);$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,4,"",0,0,'R');
	$pdf->Cell(160,4,"Importe en letra: ".$letras,0,1,'C');
	$pdf->Ln(3);	
		
	// genera cadena original para ingresos, para traslados hay que agregar a la cadena las condicionales
	// de los campos de cada traslado
	//datos del comprobante
	$cadena_original="||2.2|$serie|$folio|$fecha|$aprobacion|$year_aprobacion|$tipo_cfd|$forma_pago|$dias_credito días";
	$cadena_original.="|".number_format($subtotal2, 2, '.','')."|".number_format($total_cadena, 2, '.','')."|$metodo_pago|$lugar_expedicion";
	//datos del emisor
	$cadena_original.="|$rfc_emisor|$razon_social_emisor|$calle_emisor|$num_exterior_emisor|$colonia_emisor|$municipio_emisor";
	$cadena_original.="|$estado_emisor|$pais_emisor|$codigo_postal_emisor";
	$cadena_original.="|$regimen_fiscal";
	
	//datos del cliente
	$cadena_original.="|$rfc|$razon_social|$calle|$num_exterior";
	if($num_interior!=""){$cadena_original.="|$num_interior";}
	$cadena_original.="|$colonia";
	if($localidad!=""){$cadena_original.="|$localidad";}
	if($referencia!=""){$cadena_original.="|$referencia";}
	$cadena_original.="|$municipio|$estado|$pais|$codigo_postal";
	//detalle de conceptos
	if ($d1!="")
	{$cadena_original.="|$cantidad1|$unidad1|$d1|".number_format($precio1, 2, '.','')."|".number_format($monto1, 2, '.','');}
	if ($d2!="")
	{$cadena_original.="|$cantidad2|$unidad2|$d2|".number_format($precio2, 2, '.','')."|".number_format($monto2, 2, '.','');}
	if ($d3!="")
	{$cadena_original.="|$cantidad3|$unidad3|$d3|".number_format($precio3, 2, '.','')."|".number_format($monto3, 2, '.','');}
	if ($d4!="")
	{$cadena_original.="|$cantidad4|$unidad4|$d4|".number_format($precio4, 2, '.','')."|".number_format($monto4, 2, '.','');}
	if ($d5!="")
	{$cadena_original.="|$cantidad5|$unidad5|$d5|".number_format($precio5, 2, '.','')."|".number_format($monto5, 2, '.','');}
	
	//detalle de impuestos		
	$cadena_original.="|IVA|".$_REQUEST['iva']."|".number_format($iva, 2, '.','')."|".number_format($iva, 2, '.','')."||";		
	$cadena_original=str_replace("  "," ",$cadena_original);
			
	//Digestion SHA1, firmamos con nuestra clave y pasamos a base 64, requiere de openssl instalado		
	$sello = Rsa::selloDigital($cadena_original, $archivo_key);
			
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Cadena Original"),0,'L');
	$pdf->SetFont('Arial','',4);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($cadena_original),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode("Sello Digital"),0,'L');
	$pdf->SetFont('Arial','',4);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode($sello),0,'L');
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',5);
	$pdf->Cell(42,3,"",0,0,'C');
	$pdf->MultiCell(0,3,utf8_decode('Este documento es una representación impresa de un CFD'),0,'L');

	//creo el xml en memoria
	$cadena_xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\r\n";
	$cadena_xml.='<Comprobante xmlns="http://www.sat.gob.mx/cfd/2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
	$cadena_xml.=' version="2.2" serie="'.$serie.'" folio="'.$folio.'" fecha="'.$fecha.'" sello="'.$sello.'"';
	$cadena_xml.=' noAprobacion="'.$aprobacion.'" anoAprobacion="'.$year_aprobacion.'" formaDePago="'.$forma_pago.'" noCertificado="'.$num_certificado.'"  certificado="'.$certificado.'"';
	$cadena_xml.=' condicionesDePago="'.$dias_credito.' días"';
	$cadena_xml.=' subTotal="'.number_format($subtotal2, 2, '.','').'" total="'.number_format($total_cadena, 2, '.','').'" tipoDeComprobante="'.$tipo_cfd.'" metodoDePago="'.$metodo_pago.'" LugarExpedicion="'.$lugar_expedicion.'"';
	$cadena_xml.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/2 http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd">'."\r\n";
	$cadena_xml.='<Emisor rfc="'.$rfc_emisor.'" nombre="'.$razon_social_emisor.'">'."\r\n";
	$cadena_xml.='<DomicilioFiscal calle="'.$calle_emisor.'" noExterior="'.$num_exterior_emisor.'" colonia="'.$colonia_emisor.'" municipio="'.$municipio_emisor.'"';
	$cadena_xml.=' estado="'.$estado_emisor.'" pais="'.$pais_emisor.'" codigoPostal="'.$codigo_postal_emisor.'"/>'."\r";
			
	$cadena_xml.='<RegimenFiscal Regimen="'.$regimen_fiscal.'" />'.'</Emisor>'."\r\n";
			
	$cadena_xml.='<Receptor rfc="'.$rfc.'" nombre="'.$razon_social.'">'."\r\n";
	$cadena_xml.='<Domicilio calle="'.$calle.'" noExterior="'.$num_exterior.'"';
	if ($num_interior!=''){$cadena_xml.=' noInterior="'.$num_interior.'"';}
	$cadena_xml.=' colonia="'.$colonia.'"';
	if ($localidad!=''){$cadena_xml.=' localidad="'.$localidad.'"';}
	if ($referencia!=''){$cadena_xml.=' referencia="'.$referencia.'"';}
	$cadena_xml.=' municipio="'.$municipio.'" estado="'.$estado.'" pais="'.$pais.'" codigoPostal="'.$codigo_postal.'"/>'."\r\n".'</Receptor>'."\r\n".'<Conceptos>'."\r\n";
			
	if ($d1!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad1.'" unidad="'.$unidad1.'" descripcion="'.$d1.'" valorUnitario="'.number_format($precio1, 2, '.','').'" importe="'.number_format($monto1, 2, '.','').'"/>'."\r\n";}
			
	if ($d2!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad2.'" unidad="'.$unidad2.'" descripcion="'.$d2.'" valorUnitario="'.number_format($precio2, 2, '.','').'" importe="'.number_format($monto2, 2, '.','').'"/>'."\r\n";}
			
	if ($d3!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad3.'" unidad="'.$unidad3.'" descripcion="'.$d3.'" valorUnitario="'.number_format($precio3, 2, '.','').'" importe="'.number_format($monto3, 2, '.','').'"/>'."\r\n";}
			
	if ($d4!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad4.'" unidad="'.$unidad4.'" descripcion="'.$d4.'" valorUnitario="'.number_format($precio4, 2, '.','').'" importe="'.number_format($monto4, 2, '.','').'"/>'."\r\n";}
			
	if ($d5!="")
	{$cadena_xml.='<Concepto cantidad="'.$cantidad5.'" unidad="'.$unidad5.'" descripcion="'.$d5.'" valorUnitario="'.number_format($precio5, 2, '.','').'" importe="'.number_format($monto5, 2, '.','').'"/>'."\r\n";}
			
	$cadena_xml.='</Conceptos>'."\r\n";
	$cadena_xml.='<Impuestos totalImpuestosTrasladados="'.number_format($iva, 2, '.','').'">'."\r".'<Traslados>'."\r".'<Traslado impuesto="IVA" tasa="'.$_REQUEST['iva'].'" importe="'.number_format($iva, 2, '.','').'"/>'."\r\n";
	$cadena_xml.='</Traslados>'."\r\n".'</Impuestos>'."\r\n".'</Comprobante>';
			
	$cadena_xml=str_replace("  "," ",$cadena_xml);
						
	//creo un archivo de texto plano y meto la cadena del xml
	$new_xml = fopen ("facturas/Factura ".$serie."-".$folio.".xml", "w");
	fwrite($new_xml,$cadena_xml);
	fclose($new_xml);
			
	$pdf->Output("facturas/Factura ".$serie."-".$folio.".pdf","F");  //guardo en disco
    //$pdf->Output();//muestro el pdf
?>