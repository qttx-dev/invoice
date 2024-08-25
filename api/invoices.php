<?php
function handleInvoices($method, $uri) {
    global $conn;
    $invoice = new Invoice($conn);

    switch ($method) {
        case 'GET':
            if (isset($uri[1])) {
                // Einzelne Rechnung abrufen
                $invoice->id = intval($uri[1]);
                $result = $invoice->readOne();
                echo json_encode($result);
            } else {
                // Alle Rechnungen abrufen
                $result = $invoice->read();
                $invoices = [];
                while ($row = $result->fetch_assoc()) {
                    $invoices[] = $row;
                }
                echo json_encode($invoices);
            }
            break;

        case 'POST':
            // Neue Rechnung erstellen
            $data = json_decode(file_get_contents("php://input"));
            $invoice->customer_id = $data->customer_id;
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->invoice_date = date('Y-m-d');
            $invoice->due_date = date('Y-m-d', strtotime('+30 days'));
            $invoice->total_amount = 0; // Dies wird später berechnet
            $invoice->status = 'Ausstehend';

            if ($invoice->create()) {
                echo json_encode(["message" => "Invoice created successfully.", "invoice_id" => $invoice->id]);
            } else {
                echo json_encode(["message" => "Failed to create invoice."]);
            }
            break;

        case 'PUT':
            // Rechnung aktualisieren
            $data = json_decode(file_get_contents("php://input"));
            $invoice->id = $data->id;
            $invoice->customer_id = $data->customer_id;
            $invoice->invoice_date = $data->invoice_date;
            $invoice->due_date = $data->due_date;
            $invoice->status = $data->status;

            if ($invoice->update()) {
                echo json_encode(["message" => "Invoice updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update invoice."]);
            }
            break;

        case 'DELETE':
            // Rechnung löschen
            if (isset($uri[1])) {
                $invoice->id = intval($uri[1]);
                if ($invoice->delete()) {
                    echo json_encode(["message" => "Invoice deleted successfully."]);
                } else {
                    echo json_encode(["message" => "Failed to delete invoice."]);
                }
            }
            break;

        default:
            echo json_encode(["message" => "Invalid request method."]);
            break;
    }
}
?>
