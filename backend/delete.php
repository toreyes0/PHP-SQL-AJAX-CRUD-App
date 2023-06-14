<?php
	include 'database.php';

    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

	$id = $data['id'];

	$sql = "DELETE FROM products WHERE id = $id";
    $result = $conn -> query($sql);

	if ($result) {
        echo json_encode(array('statusCode' => 200));
	}

	$conn -> close();
?>
