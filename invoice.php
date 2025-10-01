<?php
include('config.php');
if (isset($_GET['id'])) {
    $bill_id = $_GET['id'];
    $query = "SELECT b.*, p.product_name FROM bills b JOIN products p ON b.product_id = p.id WHERE b.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bill_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bill = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>
<body>
    <h2>Invoice</h2>
    <?php if (!empty($bill)): ?>
    <p>Customer Name: <?php echo htmlspecialchars($bill['customer_name']); ?></p>
    <p>Address: <?php echo htmlspecialchars($bill['customer_address']); ?></p>
    <p>Contact: <?php echo htmlspecialchars($bill['customer_contact']); ?></p>
    <p>Customer Type: <?php echo htmlspecialchars($bill['customer_type']); ?></p>
    <p>Product: <?php echo htmlspecialchars($bill['product_name']); ?></p>
    <p>Quantity: <?php echo $bill['quantity']; ?></p>
    <p>Price: <?php echo number_format($bill['price'], 2); ?></p>
    <p>Subtotal: <?php echo number_format($bill['subtotal'], 2); ?></p>
    <p>Total Price: <?php echo number_format($bill['total_price'], 2); ?></p>
    <p>Date: <?php echo $bill['created_at']; ?></p>
    <?php else: ?>
    <p>Invoice not found.</p>
    <?php endif; ?>
</body>
</html>
