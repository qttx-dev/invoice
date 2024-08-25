<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Invoice.php';
require_once __DIR__ . '/../src/InvoiceItem.php';
require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

function generateInvoicePDF($invoiceId, $conn) {
    $invoice = new Invoice($conn);
    $invoiceItem = new InvoiceItem($conn);

    $invoice->id = $invoiceId;
    $invoice_data = $invoice->readOne();
    $items = $invoiceItem->readByInvoice($invoice->id);

    // Erstellen des PDF-Dokuments
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Rechnung', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Rechnungsdetails
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Rechnungsnummer: ' . $invoice_data['invoice_number'], 0, 1);
    $pdf->Cell(0, 10, 'Kunde: ' . $invoice_data['customer_name'], 0, 1);
    $pdf->Cell(0, 10, 'Rechnungsdatum: ' . $invoice_data['invoice_date'], 0, 1);
    $pdf->Cell(0, 10, 'Fälligkeitsdatum: ' . $invoice_data['due_date'], 0, 1);
    $pdf->Ln(10);
    
    // Tabelle für Rechnungsposten
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 10, 'Artikel', 1);
    $pdf->Cell(30, 10, 'Menge', 1);
    $pdf->Cell(30, 10, 'Preis', 1);
    $pdf->Cell(30, 10, 'Gesamt', 1);
    $pdf->Ln();

    $total = 0;
    while ($row = $items->fetch_assoc()) {
        $item_total = $row['quantity'] * $row['price'];
        $total += $item_total;
        $pdf->Cell(80, 10, $row['article_name'], 1);
        $pdf->Cell(30, 10, $row['quantity'], 1);
        $pdf->Cell(30, 10, number_format($row['price'], 2), 1);
        $pdf->Cell(30, 10, number_format($item_total, 2), 1);
        $pdf->Ln();
    }
    $pdf->Cell(140, 10, 'Gesamtbetrag', 1);
    $pdf->Cell(30, 10, number_format($total, 2), 1);

    // PDF speichern
    $pdfPath = __DIR__ . '/../temp/Rechnung_' . $invoice_data['invoice_number'] . '.pdf';
    $pdf->Output('F', $pdfPath);

    return $pdfPath;
}

// Wenn die Datei direkt aufgerufen wird, generieren und herunterladen wir die PDF
if (isset($_GET['id'])) {
    $pdfPath = generateInvoicePDF($_GET['id'], $conn);
    
    // PDF zum Download anbieten
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdfPath) . '"');
    header('Content-Length: ' . filesize($pdfPath));
    readfile($pdfPath);
    
    // Temporäre Datei löschen
    unlink($pdfPath);
    exit;
}
?>
