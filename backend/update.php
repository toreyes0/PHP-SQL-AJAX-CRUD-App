<?php
	include 'database.php';

    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

	$id = $data['id'];
	$product_name = $data['product_name'];
	$unit = $data['unit'];
	$price = $data['price'];
	$expiry_date = $data['expiry_date'];
	$available_inv = $data['available_inv'];
	$available_inv_cost = $price * $available_inv;
	$image_data = $data['image_data'];

	$sql = "
	UPDATE products
	SET product_name = ?,
		unit = ?,
		price = ?,
		date_of_expiry = ?,
		available_inventory = ?,
		available_inventory_cost = ?,
		image = ?
	WHERE id = ?
    ";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param('ssdsidsi', $product_name, $unit, $price, $expiry_date, $available_inv, $available_inv_cost, $image_data, $id);

	if ($stmt -> execute()) {
		echo json_encode(array('statusCode' => 200));
	}

	$conn -> close();
?>
