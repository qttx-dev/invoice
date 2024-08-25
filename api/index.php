<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization");

// Datenbankverbindung einfügen
require_once '../config/database.php';

// API-Routen
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode('/', trim($_SERVER['PATH_INFO'], '/'));

if ($request_uri[0] == 'invoices') {
    require_once 'invoices.php';
    handleInvoices($request_method, $request_uri);
} elseif ($request_uri[0] == 'customers') {
    require_once 'customers.php';
    handleCustomers($request_method, $request_uri);
} else {
    echo json_encode(["message" => "Invalid API endpoint."]);
}
?>
