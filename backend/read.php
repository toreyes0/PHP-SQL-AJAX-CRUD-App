<?php
	include 'database.php';

    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

	$id = $data['id'];

	$sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn -> query($sql);

	if ($result) {
        $response = json_encode(array($result -> fetch_assoc()));
		echo $response;
	}

	$conn -> close();
?>
