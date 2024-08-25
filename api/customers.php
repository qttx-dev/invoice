<?php
function handleCustomers($method, $uri) {
    global $conn;
    $customer = new Customer($conn);

    switch ($method) {
        case 'GET':
            if (isset($uri[1])) {
                // Einzelnen Kunden abrufen
                $customer->id = intval($uri[1]);
                $result = $customer->readOne();
                echo json_encode($result);
            } else {
                // Alle Kunden abrufen
                $result = $customer->read();
                $customers = [];
                while ($row = $result->fetch_assoc()) {
                    $customers[] = $row;
                }
                echo json_encode($customers);
            }
            break;

        case 'POST':
            // Neuen Kunden erstellen
            $data = json_decode(file_get_contents("php://input"));
            $customer->name = $data->name;
            $customer->email = $data->email;
            $customer->address = $data->address;

            if ($customer->create()) {
                echo json_encode(["message" => "Customer created successfully.", "customer_id" => $customer->id]);
            } else {
                echo json_encode(["message" => "Failed to create customer."]);
            }
            break;

        case 'PUT':
            // Kunden aktualisieren
            $data = json_decode(file_get_contents("php://input"));
            $customer->id = $data->id;
            $customer->name = $data->name;
            $customer->email = $data->email;
            $customer->address = $data->address;

            if ($customer->update()) {
                echo json_encode(["message" => "Customer updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update customer."]);
            }
            break;

        case 'DELETE':
            // Kunden löschen
            if (isset($uri[1])) {
                $customer->id = intval($uri[1]);
                if ($customer->delete()) {
                    echo json_encode(["message" => "Customer deleted successfully."]);
                } else {
                    echo json_encode(["message" => "Failed to delete customer."]);
                }
            }
            break;

        default:
            echo json_encode(["message" => "Invalid request method."]);
            break;
    }
}
?>
