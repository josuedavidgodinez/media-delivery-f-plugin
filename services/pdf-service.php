<?php
$path = __DIR__ . '/..';
require_once $path . "/vendor/autoload.php";

function generateInvoicePDF($BrandName, $Name, $BankAccount, $ClientsName, $EventType, $Service, $EventDate, $BookingHours, $AudioFee, $TravelFee, $Total, $rawMedia, $specificShots, $specialRequests, $videographerSong, $requestedAudio, $experience, $comments, $startEndTimes,$DroneFee)
{
    $resp = new stdClass();
    try {
        error_log("Generating invoice");
        // Crear una instancia de TCPDF
        $pdf = new TCPDF();
        error_log("Generating invoice past");

        // Agregar una página al PDF
        $pdf->AddPage('L');

        // Establecer el título "Love in Focus"
        $pdf->SetTitle($BrandName);

        // Agregar un campo "Invoice For"
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(285, 10, $BrandName, 0, 1, 'C');

        // Agregar campos "Date" y "Name"
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(70, 10, 'Invoice For: ' . $BrandName, 0, 0, 'L');
        $pdf->Cell(70, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'L');
        $pdf->Cell(70, 10, '', 0, 0, 'L');
        $pdf->Cell(70, 10, 'Name: ' . $Name, 0, 1, 'L');
        $pdf->Cell(70, 10, '', 0, 0, 'L');
        $pdf->Cell(70, 10, 'Melio or Bank Details (Account number & ACH): ' . $BankAccount, 0, 1, 'L');

        // Agregar el encabezado en fondo rojo
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(285, 10, 'DO NOT EDIT THIS PAGE. DOWNLOAD YOUR OWN COPY', 0, 1, 'C', 1);

        // Agregar la tabla con columnas
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(50, 10, 'Client Name and Event Type', 1, 0, 'C', 1);
        $pdf->Cell(20, 10, 'Service', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Event Date', 1, 0, 'C', 1);
        $pdf->Cell(45, 10, 'Booking/Event Hours', 1, 0, 'C', 1);
        $pdf->Cell(50, 10, 'Audio Recording Fee (Video)', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'Drone Fee', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, 'Travel Fee', 1, 0, 'C', 1);
        $pdf->Cell(35, 10, 'Total Amount Due', 1, 1, 'C', 1);


        // Agregar valores en la tabla (debes reemplazar estos con datos reales)
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(50, 10, ' ' . $ClientsName . ' ' . $EventType, 1, 0, 'L');
        $pdf->Cell(20, 10, ' ' . $Service, 1, 0, 'L');
        $pdf->Cell(30, 10, ' ' . $EventDate, 1, 0, 'L');
        $pdf->Cell(45, 10, ' ' . $BookingHours, 1, 0, 'L');
        $pdf->Cell(50, 10, ' $' . $AudioFee, 1, 0, 'L');
        $pdf->Cell(25, 10, ' $' . $DroneFee, 1, 0, 'L');
        $pdf->Cell(30, 10, ' $' . $TravelFee, 1, 0, 'L');
        $pdf->Cell(35, 10, ' $' . $Total, 1, 1, 'L');


        // Agregar campos faltantes al PDF
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(285, 20, '', 0, 1, 'L');
        $pdf->MultiCell(120, 20, 'Specific Shots or Footage: ' . $specificShots, 0, 'L', false, 0);
        $pdf->Cell(10, 20, '', 0, 0, 'L');
        $pdf->MultiCell(120, 20, 'Special Requests or Notes: ' . $specialRequests, 0, 'L', false, 1);
        $pdf->MultiCell(120, 20, 'Experience: ' . $experience, 0, 'L', false, 0);
        $pdf->Cell(10, 20, '', 0, 0, 'L');
        $pdf->MultiCell(120, 20, 'Comments: ' . $comments, 0, 'L', 1);
        $pdf->Cell(120, 10, 'Videographer Song: ' . $videographerSong, 0, 0, 'L');
        $pdf->Cell(10, 20, '', 0, 0, 'L');
        $pdf->Cell(120, 10, 'Requested Audio: ' . $requestedAudio, 0, 1, 'L');
        $pdf->Cell(120, 10, 'Start and End Times: ' . $startEndTimes, 0, 0, 'L');

        error_log("Generating invoice final");

        $nombreArchivo = "$Name-$Service-$EventDate-invoice.pdf";
        $archivoPDF = WP_CONTENT_DIR . "/outputs/$nombreArchivo";
        error_log($archivoPDF);


        // Generar el PDF
        $pdf->Output($archivoPDF, 'F');

        $resp -> message = null;
        $resp -> archivoPDF = $archivoPDF;
        $resp -> nombreArchivo = $nombreArchivo;

        return $resp;
    } catch (\Throwable $th) {
        error_log(json_encode($th));
        $resp -> message = "error";
        $resp -> archivoPDF = null;
        $resp -> nombreArchivo = null;
        return $resp;

    }
}


?>