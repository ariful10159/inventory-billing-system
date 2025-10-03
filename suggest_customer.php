<?php
// suggest_customer.php - Suggest customers by name or contact (AJAX)
include('config.php');
header('Content-Type: application/json');
if (isset($_GET['name'])) {
  $name = $conn->real_escape_string($_GET['name']);
  $q = $conn->query("SELECT * FROM customers WHERE name LIKE '$name%' ORDER BY name LIMIT 10");
  $out = [];
  while($row = $q->fetch_assoc()) $out[] = $row;
  echo json_encode($out);
  exit;
}
if (isset($_GET['contact'])) {
  $contact = $conn->real_escape_string($_GET['contact']);
  $q = $conn->query("SELECT * FROM customers WHERE contact LIKE '$contact%' ORDER BY name LIMIT 10");
  $out = [];
  while($row = $q->fetch_assoc()) $out[] = $row;
  echo json_encode($out);
  exit;
}
echo json_encode([]);
?>
