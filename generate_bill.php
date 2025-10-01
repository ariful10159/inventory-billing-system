<?php
include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $customer_contact = $_POST['customer_contact'];
    $customer_type = $_POST['customer_type'];
    $product_id = $_POST['product'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $subtotal = $_POST['subtotal'];
    $total_price = $_POST['total'];

    $query = "INSERT INTO bills (customer_name, customer_address, customer_contact, customer_type, product_id, quantity, price, subtotal, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssiiddd", $customer_name, $customer_address, $customer_contact, $customer_type, $product_id, $quantity, $price, $subtotal, $total_price);
    $stmt->execute();
    echo "Bill generated successfully! <a href='invoice.php?id=" . $conn->insert_id . "'>View Invoice</a>";
}
?>
