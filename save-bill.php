<?php
// save-bill.php - Save bill and items, handle due
include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $customer_id = intval($_POST['customer_id']);
  $total_price = 0;
  foreach ($_POST['products'] as $item) {
    $total_price += floatval($item['subtotal']);
  }
  $paid_amount = floatval($_POST['paid_amount']);
  $due_amount = $total_price - $paid_amount;

  // Insert bill
  $stmt = $conn->prepare("INSERT INTO bills (customer_id, total_price, paid_amount, due_amount) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iddd", $customer_id, $total_price, $paid_amount, $due_amount);
  $stmt->execute();
  $bill_id = $conn->insert_id;

  // Insert bill items
  foreach ($_POST['products'] as $item) {
    $stmt2 = $conn->prepare("INSERT INTO bill_items (bill_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiidd", $bill_id, $item['product_id'], $item['quantity'], $item['price'], $item['subtotal']);
    $stmt2->execute();
  }
  echo "Bill saved! <a href='invoice.php?id=$bill_id'>View Invoice</a>";
}
?>
