<?php
	include 'database.php';

    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

	$product_name = $data['product_name'];
	$unit = $data['unit'];
	$price = $data['price'];
	$expiry_date = $data['expiry_date'];
	$available_inv = $data['available_inv'];
	$available_inv_cost = $price * $available_inv;
	$image_data = $data['image_data'];

	$sql = "
    INSERT INTO products(product_name, unit, price, date_of_expiry, available_inventory, available_inventory_cost, image)
    VALUES(?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param('ssdsids', $product_name, $unit, $price, $expiry_date, $available_inv, $available_inv_cost, $image_data);

	if ($stmt -> execute()) {
		echo json_encode(array('statusCode' => 200));
	}

	$conn -> close();
?>
