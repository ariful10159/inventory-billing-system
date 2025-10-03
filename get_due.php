<?php
// get_due.php - Return previous due for a customer (JSON)
include('config.php');
header('Content-Type: application/json');
if (isset($_GET['customer_id'])) {
	$customer_id = intval($_GET['customer_id']);
	$q = $conn->query("SELECT SUM(due_amount) as due FROM bills WHERE customer_id = $customer_id");
	$row = $q->fetch_assoc();
	echo json_encode(['due' => floatval($row['due'])]);
	exit;
}
if (isset($_GET['customer_name'])) {
	$name = $conn->real_escape_string($_GET['customer_name']);
	$q = $conn->query("SELECT id FROM customers WHERE name = '$name' LIMIT 1");
	if ($row = $q->fetch_assoc()) {
		$customer_id = $row['id'];
		$q2 = $conn->query("SELECT SUM(due_amount) as due FROM bills WHERE customer_id = $customer_id");
		$row2 = $q2->fetch_assoc();
		echo json_encode(['due' => floatval($row2['due'])]);
		exit;
	}
}
echo json_encode(['due' => 0]);
?>
