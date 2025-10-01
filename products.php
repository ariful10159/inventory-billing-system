<?php
include('config.php');

// Handle quantity update
if (isset($_GET['id']) && isset($_POST['update_quantity'])) {
  $product_id = $_GET['id'];
  $new_quantity = $_POST['new_quantity'];

  // Update product quantity
  $query = "SELECT quantity FROM products WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_quantity = $row['quantity'] + $new_quantity;
    $update_query = "UPDATE products SET quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $total_quantity, $product_id);
    $update_stmt->execute();
    echo "Product quantity updated successfully!";
  }
}

// Fetch all products
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product List</title>
</head>
<body>
  <h1>Product List</h1>
  <a href="add-product.php">Add New Product</a>
  <br><br>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>Product Name</th>
      <th>Dealer Price</th>
      <th>Discount %</th>
      <th>Cost Price</th>
      <th>Normal Price</th>
      <th>Quantity</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['product_name']); ?></td>
      <td><?php echo number_format($row['dealer_price'], 2); ?></td>
      <td><?php echo number_format($row['discount_percent'], 2); ?></td>
      <td><?php echo number_format($row['cost_price'], 2); ?></td>
      <td><?php echo number_format($row['normal_price'], 2); ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td>
        <form action="products.php?id=<?php echo $row['id']; ?>" method="POST" style="display:inline;">
          <input type="number" name="new_quantity" min="1" placeholder="Add Qty" required>
          <button type="submit" name="update_quantity">Update Qty</button>
        </form>
        <!-- You can add Edit/Delete links here if needed -->
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
