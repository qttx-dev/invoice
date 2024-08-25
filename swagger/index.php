<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Datenbankverbindung einfügen
require_once '../config/database.php';

// API-Routen
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$api_index = array_search('api', $request_uri);
$endpoint = $request_uri[$api_index + 1] ?? null;
$id = $request_uri[$api_index + 2] ?? null;

if ($endpoint == 'invoices') {
    require_once 'invoices.php';
    handleInvoices($request_method, $id);
} elseif ($endpoint == 'customers') {
    require_once 'customers.php';
    handleCustomers($request_method, $id);
} else {
    echo json_encode(["message" => "Invalid API endpoint."]);
}

function handleInvoices($method, $id = null) {
    global $conn;
    $invoice = new Invoice($conn);

    switch ($method) {
        case 'GET':
            if ($id) {
                $invoice->id = intval($id);
                $result = $invoice->readOne();
                echo json_encode($result);
            } else {
                $result = $invoice->read();
                $invoices = [];
                while ($row = $result->fetch_assoc()) {
                    $invoices[] = $row;
                }
                echo json_encode($invoices);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
            $invoice->customer_id = $data->customer_id;
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->invoice_date = date('Y-m-d');
            $invoice->due_date = date('Y-m-d', strtotime('+30 days'));
            $invoice->total_amount = 0;
            $invoice->status = 'Ausstehend';

            if ($invoice->create()) {
                echo json_encode(["message" => "Invoice created successfully.", "invoice_id" => $invoice->id]);
            } else {
                echo json_encode(["message" => "Failed to create invoice."]);
            }
            break;
        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents("php://input"));
                $invoice->id = intval($id);
                $invoice->customer_id = $data->customer_id;
                $invoice->invoice_date = $data->invoice_date;
                $invoice->due_date = $data->due_date;
                $invoice->status = $data->status;

                if ($invoice->update()) {
                    echo json_encode(["message" => "Invoice updated successfully."]);
                } else {
                    echo json_encode(["message" => "Failed to update invoice."]);
                }
            } else {
                echo json_encode(["message" => "Invoice ID is required."]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $invoice->id = intval($id);
                if ($invoice->delete()) {
                    echo json_encode(["message" => "Invoice deleted successfully."]);
                } else {
                    echo json_encode(["message" => "Failed to delete invoice."]);
                }
            } else {
                echo json_encode(["message" => "Invoice ID is required."]);
            }
            break;
        default:
            echo json_encode(["message" => "Invalid request method."]);
            break;
    }
}

function handleCustomers($method, $id = null) {
    // Implementieren Sie hier die Logik für die Kundenverwaltung
    // Ähnlich wie bei handleInvoices
}
?>
